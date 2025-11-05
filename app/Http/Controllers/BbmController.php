<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use illuminate\Support\Facades\Log;

use App\Models\BbmHdr;
use App\Models\BbmDtl;
use App\Models\Mformcode;
use App\Models\InvoiceHdr;
use App\Models\Mvendor;
use App\Models\Mpromas;


class BbmController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $bbmhdr = BbmHdr::with('mformcode')->get();

        $userBraco = Auth::user()->cabang;

        $latestPeriod = DB::table('tperiode')
            ->where('braco', Auth::user()->cabang)
            ->orderByDesc('periode')
            ->first();

        $periodClosed = $latestPeriod && $latestPeriod->status === 'C';
        
        return view('logistic.bbm.bbm_index', compact('bbmhdr', 'userBraco', 'periodClosed'));
    }

    public function getInvoice($rinum)
    {
        $invoices = DB::table('tsupih_tbl')
            ->where('rinum', $rinum)
            ->select('invno')
            ->get();

        return response()->json($invoices);
    }

    public function getPoList()
    {
        $braco = Auth::user()->cabang;

        $pos = DB::table('pohdr_tbl as h')
            ->where('h.braco', $braco)
            ->whereExists(function($q){
                $q->select(DB::raw(1))
                ->from('podtl_tbl as d')
                ->whereColumn('d.pono', 'h.pono')
                ->whereColumn('d.rcqty', '<', 'd.poqty');
            })
            ->select('h.pono')
            ->orderBy('h.pono', 'desc')
            ->get();

        return response()->json($pos);
    }

    public function getPoSupplier($pono)
    {
        $data = DB::table('pohdr_tbl as h')
            ->leftJoin('mvendor_tbl as v', 'h.supno', '=', 'v.supno')
            ->where('h.pono', $pono)
            ->select('h.supno', 'v.supna')
            ->first();

        return response()->json($data);
    }

    public function getBarang($invno, Request $request)
    {
        $formc = $request->query('formc');

        if ($formc === 'IA') {
            // invno di IA = PONO
            return DB::table('podtl_tbl as p')
                ->leftJoin('mpromas as m','p.opron','=','m.opron')
                ->where('p.pono', $invno)
                ->whereColumn('p.rcqty','<','p.poqty') // hanya yg belum full receive
                ->select('p.opron','m.prona','p.poqty as inqty','p.stdqu as stdqt','p.pono')
                ->get();
        }

        // IB (default): t.inqty dari invoice, hide yang sudah full: rcqty >= inqty
        return DB::table('tsupid_tbl as t')
            ->leftJoin('mpromas as m','t.opron','=','m.opron')
            ->leftJoin('podtl_tbl as p', function($j){
                $j->on('t.opron','=','p.opron')
                ->on('t.pono','=','p.pono');
            })
            ->where('t.invno', $invno)
            ->whereColumn('p.rcqty','<','t.inqty')
            ->select('t.opron','m.prona','t.inqty','t.stdqt','t.pono')
            ->get();
    }

    public function getLocco($warco)
    {
        $locco = DB::table('mlocco_tbl')
            ->where('warco', $warco)
            ->select('locco')
            ->get();

        return response()->json($locco);
    }

    // untuk generate lotno
    private function generateLotList($lotStart, $trqty)
    {
        preg_match_all('/\d+/', $lotStart, $matches, PREG_OFFSET_CAPTURE);

        if (count($matches[0]) === 0) {
            return [$lotStart];
        }

        // Pilih angka terakhir yang paling pendek (biasanya serial)
        $chosen = collect($matches[0])
            ->sortBy(fn($m) => strlen($m[0]))
            ->first();

        $number = (int)$chosen[0];
        $padLength = strlen($chosen[0]);
        $startPos = $chosen[1];
        $endPos = $startPos + $padLength;

        $lotList = [];
        for ($i = 0; $i < $trqty; $i++) {
            $newNum = str_pad($number + $i, $padLength, '0', STR_PAD_LEFT);
            $newLot = substr($lotStart, 0, $startPos) . $newNum . substr($lotStart, $endPos);
            $lotList[] = $newLot;
        }

        return $lotList;
    }

    // generate trano sesuai warco, braco, formc
    public function generateTrano(Request $request)
    {
        $braco = auth()->user()->cabang;
        $warco = $request->warco;
        $formc = $request->formc;
        $year = date('y');

        $last = DB::table('tstorh')
            ->where('braco', $braco)
            ->where('warco', $warco)
            ->where('formc', $formc)
            ->whereRaw("LEFT(trano,2) = ?", [$year])
            ->orderBy('trano','desc')
            ->value('trano');

        if ($last) {
            $number = (int)substr($last, 2) + 1;
        } else {
            $number = 1;
        }

        return $year . str_pad($number, 4, '0', STR_PAD_LEFT);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $bbmhdr = BbmHdr::all();
        $mwarco = DB::table('mwarco_tbl')->get();

        $periodeAktif = DB::table('tperiode')
            ->where('braco', auth()->user()->cabang)
            ->where('status', 'O')
            ->orderBy('periode', 'desc')
            ->first();

        if ($periodeAktif) {
            $priod = $periodeAktif->periode;
            $year = substr($periodeAktif->periode, 0, 4);
            $month = substr($periodeAktif->periode, 4, 2);
            $minDate = "$year-$month-01";
        }

        $tsupih = DB::table('tsupih_tbl')
            ->leftJoin('mvendor_tbl', 'tsupih_tbl.supno', '=', 'mvendor_tbl.supno')
            ->leftJoin('tbolh', 'tsupih_tbl.rinum', '=', 'tbolh.rinum')
            ->select('tsupih_tbl.*', 'mvendor_tbl.supna', 'tbolh.blnum', 'tbolh.vesel')
            ->get();

        return view('logistic.bbm.bbm_create', compact('bbmhdr','mwarco','priod','minDate','periodeAktif','tsupih'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        DB::beginTransaction();

        try {
            $bbmid = $request->braco . $request->warco . $request->formc . $request->trano;

            BbmHdr::create([
                'bbmid'   => $bbmid,
                'braco'   => $request->braco,
                'warco'   => $request->warco,
                'formc'   => $request->formc,
                'trano'   => $request->trano,
                'priod'   => $request->priod,
                'tradt'   => $request->tradt,
                'reffc'   => $request->reffc,
                'refno'   => $request->refno,
                'supno'   => $request->supno,
                'blnum'   => $request->blnum,
                'vesel'   => $request->vesel,
                'noteh'   => $request->noteh,
                'created_at' => now(),
                'created_by' => Auth::user()->name,
                'updated_at' => now(),
                'updated_by' => Auth::user()->name,
                'user_id' => Auth::user()->id,
            ]);

            foreach ($request->invno as $i => $invno) {

                $isNoLot = isset($request->nolot[$i]) && $request->nolot[$i] == 1;
                $lotStart = $request->lotno[$i] ?: '-';
                $trqty = (int) $request->trqty[$i];

                if($isNoLot){
                    $lotList = [$lotStart];
                }else{
                    $lotList = $this->generateLotList($lotStart, $trqty);
                }

                // TSTORD
                if($isNoLot){
                    DB::table('tstord')->insert([
                        'bbmid' => $bbmid,
                        'trano' => $request->trano,
                        'invno' => $invno,
                        'opron' => $request->opron[$i],
                        'lotno' => $lotList[0],
                        'trqty' => $trqty,
                        'qunit' => $request->stdqt[$i],
                        'locco' => $request->locco[$i],
                        'pono' => $request->pono[$i],
                        'noted' => $request->noted[$i],
                    ]);
                } else {
                    foreach ($lotList as $lotno) {
                        DB::table('tstord')->insert([
                            'bbmid' => $bbmid,
                            'trano' => $request->trano,
                            'invno' => $invno,
                            'opron' => $request->opron[$i],
                            'lotno' => $lotno,
                            'trqty' => 1,
                            'qunit' => $request->stdqt[$i],
                            'locco' => $request->locco[$i],
                            'pono' => $request->pono[$i],
                            'noted' => $request->noted[$i],
                        ]);
                    }
                }

                // stobw_tbl summary
                $existW = DB::table('stobw_tbl')
                    ->where('warco', $request->warco)
                    ->where('braco', $request->braco)
                    ->where('opron', $request->opron[$i])
                    ->first();

                if (!$existW) {
                    DB::table('stobw_tbl')->insert([
                        'braco' => $request->braco,
                        'warco' => $request->warco,
                        'opron' => $request->opron[$i],
                        'toqoh' => $trqty,
                    ]);
                } else {
                    DB::table('stobw_tbl')
                        ->where('warco', $request->warco)
                        ->where('braco', $request->braco)
                        ->where('opron', $request->opron[$i])
                        ->update(['toqoh' => DB::raw('toqoh + ' . $trqty)]);
                }

                // podtl update
                DB::table('podtl_tbl')
                    ->where('pono', $request->pono[$i])
                    ->where('opron', $request->opron[$i])
                    ->update([
                        'rcqty' => DB::raw('rcqty + '.$trqty)
                    ]);

                // stobl_tbl
                if($isNoLot){

                    $existL = DB::table('stobl_tbl')
                        ->where('warco', $request->warco)
                        ->where('braco', $request->braco)
                        ->where('opron', $request->opron[$i])
                        ->where('qunit', $request->stdqt[$i])
                        ->where('locco', $request->locco[$i])
                        ->where('lotno', $lotList[0])
                        ->first();

                    if (!$existL) {
                        DB::table('stobl_tbl')->insert([
                            'braco' => $request->braco,
                            'warco' => $request->warco,
                            'opron' => $request->opron[$i],
                            'qunit' => $request->stdqt[$i],
                            'locco' => $request->locco[$i],
                            'lotno' => $lotList[0],
                            'toqoh' => $trqty,
                        ]);
                    } else {
                        DB::table('stobl_tbl')
                            ->where('warco', $request->warco)
                            ->where('braco', $request->braco)
                            ->where('opron', $request->opron[$i])
                            ->where('qunit', $request->stdqt[$i])
                            ->where('locco', $request->locco[$i])
                            ->where('lotno', $lotList[0])
                            ->update(['toqoh' => DB::raw("toqoh + $trqty")]);
                    }

                } else {

                    foreach ($lotList as $lotno) {
                        $existL = DB::table('stobl_tbl')
                            ->where('warco', $request->warco)
                            ->where('braco', $request->braco)
                            ->where('opron', $request->opron[$i])
                            ->where('qunit', $request->stdqt[$i])
                            ->where('locco', $request->locco[$i])
                            ->where('lotno', $lotno)
                            ->first();

                        if (!$existL) {
                            DB::table('stobl_tbl')->insert([
                                'braco' => $request->braco,
                                'warco' => $request->warco,
                                'opron' => $request->opron[$i],
                                'qunit' => $request->stdqt[$i],
                                'locco' => $request->locco[$i],
                                'lotno' => $lotno,
                                'toqoh' => 1,
                            ]);
                        } else {
                            DB::table('stobl_tbl')
                                ->where('warco', $request->warco)
                                ->where('braco', $request->braco)
                                ->where('opron', $request->opron[$i])
                                ->where('qunit', $request->stdqt[$i])
                                ->where('locco', $request->locco[$i])
                                ->where('lotno', $lotno)
                                ->update(['toqoh' => DB::raw('toqoh + 1')]);
                        }
                    }
                }
            }

            DB::commit();
            return redirect()->route('bbm.index')->with('success', "Data BBM \"$bbmid\" berhasil disimpan.");
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Gagal simpan BBM:', ['error' => $e->getMessage()]);
            return back()->with('error', 'Terjadi kesalahan saat menyimpan: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $bbm = BbmHdr::with('mformcode','bbmdtl.mpromas', 'bbmdtl.tsupid', 'bbmdtl.podtl', 'tsupih', 'vendor')->findOrFail($id);
        return view('logistic.bbm.bbm_detail', compact('bbm'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        // Ambil header
        $bbm = DB::table('tstorh as h')
            ->leftJoin('mvendor_tbl as v', 'h.supno', '=', 'v.supno')
            ->leftJoin('mformcode_tbl as f', 'h.formc', '=', 'f.formc')
            ->select('h.*', 'v.supna', 'f.desc_c')
            ->where('h.bbmid', $id)
            ->first();

        if (!$bbm) {
            return redirect()->route('bbm.index')->with('error', 'Data BBM tidak ditemukan.');
        }

        // Ambil detail dengan join ke mpromas untuk ambil prona
        $details = DB::table('tstord as d')
            ->leftJoin('mpromas as p', 'd.opron', '=', 'p.opron')
            ->leftJoin('tsupid_tbl as s', function ($join){
                $join->on('d.invno', '=', 's.invno')
                    ->on('d.opron', '=', 's.opron');
            })
            ->leftJoin('podtl_tbl as t', function($join){
                $join->on('d.pono','=','t.pono')
                    ->on('d.opron','=','t.opron');
            })
            ->select(
                'd.*',
                'p.prona',
                'd.trqty',
                'd.qunit',
                DB::raw('COALESCE(s.inqty, t.poqty) as inqty'),
                DB::raw('COALESCE(s.stdqt, t.stdqu) as stdqt'),
                't.poqty',
            )
        ->where('d.trano', $bbm->trano)
        ->get();

        // Dropdown
        $mwarco = DB::table('mwarco_tbl')->get();
        $tsupih = DB::table('tsupih_tbl')->get();
        $loccos = DB::table('mlocco_tbl')->get();

        return view('logistic.bbm.bbm_edit', compact('bbm', 'details', 'mwarco', 'tsupih', 'loccos'));
    }

    public function update(Request $request, $bbmid)
    {
        DB::beginTransaction();

        try {
            $bbm = DB::table('tstorh')->where('bbmid', $bbmid)->first();
            if (!$bbm) {
                return redirect()->route('bbm.index')->with('error', 'Data BBM tidak ditemukan.');
            }

            DB::table('tstorh')->where('bbmid', $bbmid)->update([
                'noteh' => $request->noteh,
                'updated_at' => now(),
                'updated_by' => Auth::user()->name,
            ]);

            // Hapus stok lama
            $oldDetails = DB::table('tstord')
                ->select('opron', 'locco', 'lotno', 'trqty', 'qunit', 'pono')
                ->where('trano', $bbm->trano)
                ->get();

            foreach ($oldDetails as $old) {
                DB::table('stobw_tbl')
                    ->where('warco', $bbm->warco)
                    ->where('braco', $bbm->braco)
                    ->where('opron', $old->opron)
                    ->decrement('toqoh', $old->trqty);

                DB::table('stobl_tbl')
                    ->where('warco', $bbm->warco)
                    ->where('braco', $bbm->braco)
                    ->where('opron', $old->opron)
                    ->where('qunit', $old->qunit)
                    ->where('locco', $old->locco)
                    ->where('lotno', $old->lotno)
                    ->decrement('toqoh', 1);

                DB::table('podtl_tbl')
                    ->where('pono', $old->pono)
                    ->where('opron', $old->opron)
                    ->update([
                        'rcqty' => DB::raw('rcqty - '.$old->trqty)
                    ]);
            }

            DB::table('stobw_tbl')->where('toqoh', '<=', 0)->delete();
            DB::table('stobl_tbl')->where('toqoh', '<=', 0)->delete();
            DB::table('tstord')->where('trano', $bbm->trano)->delete();

            // Insert ulang
            foreach ($request->invno as $i => $invno) {
                $lotStart = $request->lotno[$i];
                $trqty = (int) $request->trqty[$i];
                $lotList = $this->generateLotList($lotStart, $trqty);

                foreach ($lotList as $lotno) {
                    DB::table('tstord')->insert([
                        'bbmid' => $bbmid,
                        'trano' => $bbm->trano,
                        'invno' => $invno,
                        'opron' => $request->opron[$i],
                        'lotno' => $lotno,
                        'trqty' => 1,
                        'qunit' => $request->stdqt[$i],
                        'locco' => $request->locco[$i],
                        'pono' => $request->pono[$i],
                        'noted' => $request->noted[$i],
                    ]);
                }

                $existW = DB::table('stobw_tbl')
                    ->where('warco', $bbm->warco)
                    ->where('braco', $bbm->braco)
                    ->where('opron', $request->opron[$i])
                    ->first();

                if (!$existW) {
                    DB::table('stobw_tbl')->insert([
                        'braco' => $bbm->braco,
                        'warco' => $bbm->warco,
                        'opron' => $request->opron[$i],
                        'toqoh' => $trqty,
                    ]);
                } else {
                    DB::table('stobw_tbl')
                        ->where('warco', $bbm->warco)
                        ->where('braco', $bbm->braco)
                        ->where('opron', $request->opron[$i])
                        ->update(['toqoh' => DB::raw('toqoh + ' . $trqty)]);
                }

                foreach ($lotList as $lotno) {
                    $existL = DB::table('stobl_tbl')
                        ->where('warco', $bbm->warco)
                        ->where('braco', $bbm->braco)
                        ->where('opron', $request->opron[$i])
                        ->where('qunit', $request->stdqt[$i])
                        ->where('locco', $request->locco[$i])
                        ->where('lotno', $lotno)
                        ->first();

                    if (!$existL) {
                        DB::table('stobl_tbl')->insert([
                            'braco' => $bbm->braco,
                            'warco' => $bbm->warco,
                            'opron' => $request->opron[$i],
                            'qunit' => $request->stdqt[$i],
                            'locco' => $request->locco[$i],
                            'lotno' => $lotno,
                            'toqoh' => 1,
                        ]);
                    } else {
                        DB::table('stobl_tbl')
                            ->where('warco', $bbm->warco)
                            ->where('braco', $bbm->braco)
                            ->where('opron', $request->opron[$i])
                            ->where('qunit', $request->stdqt[$i])
                            ->where('locco', $request->locco[$i])
                            ->where('lotno', $lotno)
                            ->update(['toqoh' => DB::raw('toqoh + 1')]);
                    }
                }
                DB::table('podtl_tbl')
                    ->where('pono', $request->pono[$i])
                    ->where('opron', $request->opron[$i])
                    ->update([
                        'rcqty' => DB::raw('rcqty + '.$trqty)
                    ]);
            }

            DB::commit();
            return redirect()->route('bbm.index')->with('success', 'Data BBM ' . $bbm->bbmid . ' berhasil diperbarui.');
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Gagal update BBM:', ['error' => $e->getMessage()]);
            return back()->with('error', 'Terjadi kesalahan saat update: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $bbm = BbmHdr::findOrFail($id);
            $bbm->bbmdtl()->delete();
            $bbm->delete();

            return redirect()->route('bbm.index')
                ->with('success', 'Data BBM berhasil dihapus.');
        } catch (\Exception $e) {
            return redirect()->route('bbm.index')
                ->with('error', 'Gagal menghapus data: ' . $e->getMessage());
        }
    }
}
