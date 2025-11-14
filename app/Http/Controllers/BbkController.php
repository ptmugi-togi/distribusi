<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use illuminate\Support\Facades\Log;

use App\Models\BbkHdr;
use App\Models\BbkDtl;
use App\Models\Mformcode;
use App\Models\InvoiceHdr;
use App\Models\Mvendor;
use App\Models\Mpromas;

class BbkController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $userBraco = Auth::user()->cabang;

        $bbkhdr = BbkHdr::with('mformcode')
                        ->where('braco', $userBraco)
                        ->get();

        $latestPeriod = DB::table('tperiode')
            ->where('braco', Auth::user()->cabang)
            ->orderByDesc('periode')
            ->first();

        $periodClosed = $latestPeriod && $latestPeriod->status === 'C';
        
        return view('logistic.bbk.bbk_index', compact('bbkhdr', 'userBraco', 'periodClosed'));
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

        $last = DB::table('tsisnh')
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

    public function create()
    {
        $bbkhdr = BbkHdr::all();
        $mwarco = DB::table('mwarco_tbl')->get();
        $vendors = DB::table('mvendor_tbl')->get();

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

        return view('logistic.bbk.bbk_create', compact('bbkhdr','mwarco','vendors','priod','minDate','periodeAktif','tsupih'));
    }

    // get barang untuk OF
    public function getBarangOF($braco, $warco, $locco)
    {
        try {
            $barang = DB::table('stobl_tbl as s')
                ->join('mpromas as m', function($join) {
                    $join->on('s.opron', '=', 'm.opron');
                })
                ->where('s.braco', $braco)
                ->where('s.warco', $warco)
                ->where('s.locco', $locco)
                ->where('s.toqoh', '>', 0)
                ->select(
                    's.opron',
                    'm.prona',
                    'm.stdqu',
                    DB::raw('SUM(s.toqoh) as qty')
                )
                ->groupBy('s.opron', 'm.prona', 'm.stdqu')
                ->get();

            return response()->json($barang);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    // untuk OF cek barang dari stobl
    public function getStobl($braco, $warco, $opron)
    {
        $stok = DB::table('stobl_tbl')
            ->where('braco', $braco)
            ->where('warco', $warco)
            ->where('opron', $opron)
            ->where('toqoh', '>', 0)
            ->select('lotno', 'qunit', 'toqoh')
            ->get();
        return response()->json($stok);
    }

    // untuk OF mengurangi barang di stobw dan stobl
    public function reduceStock(Request $request)
    {
        $opron = $request->opron;
        $lotno = $request->lotno;
        $qty   = (int)$request->qty;
        $braco = $request->braco;
        $warco = $request->warco;

        DB::table('stobl_tbl')
            ->where('braco', $braco)
            ->where('warco', $warco)
            ->where('opron', $opron)
            ->where('lotno', $lotno)
            ->update(['toqoh' => DB::raw("toqoh - $qty")]);

        DB::table('stobw_tbl')
            ->where('braco', $braco)
            ->where('warco', $warco)
            ->where('opron', $opron)
            ->update(['toqoh' => DB::raw("toqoh - $qty")]);

        return response()->json(['success' => true]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        DB::beginTransaction();

        try {
            $bbkid = $request->braco . $request->warco . $request->formc . $request->trano;

            // Simpan header
            BbkHdr::create([
                'bbkid' => $bbkid,
                'braco' => $request->braco,
                'warco' => $request->warco,
                'formc' => $request->formc,
                'trano' => $request->trano,
                'priod' => $request->priod,
                'tradt' => $request->tradt,
                'reffc' => $request->reffc,
                'refno' => $request->refno,
                'supno' => $request->supno ?? '',
                'blnum' => $request->blnum,
                'vesel' => $request->vesel,
                'noteh' => $request->noteh,
                'created_at' => now(),
                'created_by' => Auth::user()->name,
                'updated_at' => now(),
                'updated_by' => Auth::user()->name,
                'user_id' => Auth::user()->id,
            ]);

            // Loop tiap detail barang
            foreach ($request->opron as $i => $useOpron) {
                $isNoLot = isset($request->nolot[$i]) && $request->nolot[$i] == 1;
                $lotStart = $request->lotno[$i] ?: '-';
                $trqty = (int) $request->trqty[$i];

                if ($isNoLot) {
                    // Kalau tidak ada LOT, ambil dari stok LOT existing
                    $existingLot = DB::table('stobl_tbl')
                        ->where('warco', $request->warco)
                        ->where('braco', $request->braco)
                        ->where('opron', $useOpron)
                        ->value('lotno');

                    $lotList = [$existingLot ?: $lotStart];
                } else {
                    $lotList = $this->generateLotList($lotStart, $trqty);
                }

                // INSERT DETAIL KE TABEL toutg
                foreach ($lotList as $lotno) {
                    DB::table('toutg')->insert([
                        'bbkid' => $bbkid,
                        'formc' => $request->formc,
                        'trano' => $request->trano,
                        'opron' => $useOpron,
                        'lotno' => $lotno,
                        'trqty' => $isNoLot ? $trqty : 1,
                        'qunit' => $request->stdqt[$i],
                        'locco' => $request->locco[$i],
                        'noted' => $request->noted[$i],
                    ]);
                }

                // UPDATE stok per barang di tabel stobw_tbl
                $existW = DB::table('stobw_tbl')
                    ->where('warco', $request->warco)
                    ->where('braco', $request->braco)
                    ->where('opron', $useOpron)
                    ->first();

                if ($existW) {
                    DB::table('stobw_tbl')
                        ->where('warco', $request->warco)
                        ->where('braco', $request->braco)
                        ->where('opron', $useOpron)
                        ->update(['toqoh' => DB::raw("toqoh - $trqty")]);
                } else {
                    DB::table('stobw_tbl')->insert([
                        'braco' => $request->braco,
                        'warco' => $request->warco,
                        'opron' => $useOpron,
                        'toqoh' => 0 - $trqty, // langsung keluar
                    ]);
                }

                // UPDATE stok per LOT di tabel stobl_tbl
                foreach ($lotList as $lotno) {
                    $existL = DB::table('stobl_tbl')
                        ->where('warco', $request->warco)
                        ->where('braco', $request->braco)
                        ->where('opron', $useOpron)
                        ->where('lotno', $lotno)
                        ->first();

                    if ($existL) {
                        DB::table('stobl_tbl')
                            ->where('warco', $request->warco)
                            ->where('braco', $request->braco)
                            ->where('opron', $useOpron)
                            ->where('lotno', $lotno)
                            ->update(['toqoh' => DB::raw("toqoh - " . ($isNoLot ? $trqty : 1))]);
                    } else {
                        DB::table('stobl_tbl')->insert([
                            'braco' => $request->braco,
                            'warco' => $request->warco,
                            'opron' => $useOpron,
                            'qunit' => $request->stdqt[$i],
                            'locco' => $request->locco[$i],
                            'lotno' => $lotno,
                            'toqoh' => 0 - ($isNoLot ? $trqty : 1),
                        ]);
                    }
                }
            }

            DB::commit();
            return redirect()->route('bbk.index')->with('success', "Data BBK \"$bbkid\" berhasil disimpan.");
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Gagal simpan BBK:', ['error' => $e->getMessage()]);
            return back()->with('error', 'Terjadi kesalahan saat menyimpan: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $bbk = BbkHdr::with('mformcode','bbkdtls.mpromas', 'bbkdtls.tsupid', 'tsupih', 'vendor')->findOrFail($id);
        return view('logistic.bbk.bbk_detail', compact('bbk'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        // Ambil header
        $bbk = DB::table('tsisnh as h')
            ->leftJoin('mvendor_tbl as v', 'h.supno', '=', 'v.supno')
            ->leftJoin('mformcode_tbl as f', 'h.formc', '=', 'f.formc')
            ->select('h.*', 'v.supna', 'f.desc_c')
            ->where('h.bbkid', $id)
            ->first();

        if (!$bbk) {
            return redirect()->route('bbk.index')->with('error', 'Data BBK tidak ditemukan.');
        }

        // Ambil detail dengan join ke mpromas untuk ambil prona
        $details = DB::table('toutg as d')
            ->leftJoin('mpromas as p', 'd.opron', '=', 'p.opron')
            ->select(
                'd.*',
                'p.prona',
                'd.trqty',
                'd.qunit',
            )
        ->where('d.bbkid', $bbk->bbkid)
        ->where('d.trano', $bbk->trano)
        ->distinct()
        ->get();

        // Dropdown
        $mwarco = DB::table('mwarco_tbl')->get();
        $tsupih = DB::table('tsupih_tbl')->get();
        $loccos = DB::table('mlocco_tbl')->get();

        return view('logistic.bbk.bbk_edit', compact('bbk', 'details', 'mwarco', 'tsupih', 'loccos'));
    }

    public function update(Request $request, $bbkid)
    {
        DB::beginTransaction();

        try {
            $bbk = DB::table('tsisnh')->where('bbkid', $bbkid)->first();
            if (!$bbk) {
                return redirect()->route('bbk.index')->with('error', 'Data BBK tidak ditemukan.');
            }

            // Update header
            DB::table('tsisnh')->where('bbkid', $bbkid)->update([
                'noteh'      => $request->noteh,
                'updated_at' => now(),
                'updated_by' => Auth::user()->name,
            ]);

            // Ambil detail lama (untuk rollback stok)
            $oldDetails = DB::table('toutg')
                ->select('opron', 'locco', 'lotno', 'trqty', 'qunit')
                ->where('trano', $bbk->trano)
                ->get();

            // Rollback stok lama
            foreach ($oldDetails as $old) {

                // Rollback stobw
                DB::table('stobw_tbl')
                    ->where('braco', $bbk->braco)
                    ->where('warco', $bbk->warco)
                    ->where('opron', $old->opron)
                    ->increment('toqoh', $old->trqty);

                // Rollback stobl
                DB::table('stobl_tbl')
                    ->where('braco', $bbk->braco)
                    ->where('warco', $bbk->warco)
                    ->where('opron', $old->opron)
                    ->where('qunit', $old->qunit)
                    ->where('locco', $old->locco)
                    ->where('lotno', $old->lotno)
                    ->increment('toqoh', $old->trqty);
            }

            // Bersihkan stok nol
            DB::table('stobw_tbl')->where('toqoh', '<=', 0)->delete();
            DB::table('stobl_tbl')->where('toqoh', '<=', 0)->delete();

            // Hapus detail lama
            DB::table('toutg')->where('trano', $bbk->trano)->delete();

            // Insert detail baru + kurangi stok baru
            foreach ($request->opron as $i => $opron) {

                $lotStart = $request->lotno[$i] ?? '-';
                $trqty    = (int)$request->trqty[$i];
                $qunit    = $request->stdqt[$i];
                $locco    = $request->locco[$i];
                $noted    = $request->noted[$i] ?? null;

                // Tentukan LOT LIST hanya sekali
                if ($lotStart === '-' || $lotStart === '' || $lotStart === null) {
                    $lotList = ['-'];
                } else {
                    $lotList = $this->generateLotList($lotStart, $trqty);
                }

                // Insert detail baru ke toutg
                foreach ($lotList as $lotno) {
                    DB::table('toutg')->insert([
                        'bbkid' => $bbkid,
                        'formc' => $bbk->formc,
                        'trano' => $bbk->trano,
                        'opron' => $opron,
                        'lotno' => $lotno,
                        'trqty' => ($lotno === '-' ? $trqty : 1),
                        'qunit' => $qunit,
                        'locco' => $locco,
                        'noted' => $noted,
                    ]);
                }

                /*
                * ========================
                *  KURANGI STOK BARU
                * ========================
                */

                // --- STOBW (global qty) ---
                $stobw = DB::table('stobw_tbl')
                    ->where('braco', $bbk->braco)
                    ->where('warco', $bbk->warco)
                    ->where('opron', $opron)
                    ->first();

                if (!$stobw) {
                    DB::table('stobw_tbl')->insert([
                        'braco' => $bbk->braco,
                        'warco' => $bbk->warco,
                        'opron' => $opron,
                        'toqoh' => 0,
                    ]);
                }

                DB::table('stobw_tbl')
                    ->where('braco', $bbk->braco)
                    ->where('warco', $bbk->warco)
                    ->where('opron', $opron)
                    ->decrement('toqoh', $trqty);


                // --- STOBL (lot qty) ---
                foreach ($lotList as $lotno) {

                    $qtyToDecrease = ($lotno === '-' ? $trqty : 1);

                    $stobl = DB::table('stobl_tbl')
                        ->where('braco', $bbk->braco)
                        ->where('warco', $bbk->warco)
                        ->where('opron', $opron)
                        ->where('qunit', $qunit)
                        ->where('locco', $locco)
                        ->where('lotno', $lotno)
                        ->first();

                    if (!$stobl) {
                        DB::table('stobl_tbl')->insert([
                            'braco' => $bbk->braco,
                            'warco' => $bbk->warco,
                            'opron' => $opron,
                            'qunit' => $qunit,
                            'locco' => $locco,
                            'lotno' => $lotno,
                            'toqoh' => 0,
                        ]);
                    }

                    DB::table('stobl_tbl')
                        ->where('braco', $bbk->braco)
                        ->where('warco', $bbk->warco)
                        ->where('opron', $opron)
                        ->where('qunit', $qunit)
                        ->where('locco', $locco)
                        ->where('lotno', $lotno)
                        ->decrement('toqoh', $qtyToDecrease);
                }
            }

            DB::commit();
            return redirect()->route('bbk.index')->with('success', "Data BBK $bbkid berhasil diperbarui.");

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Gagal update BBK:', ['error' => $e->getMessage()]);
            return back()->with('error', 'Terjadi kesalahan saat update: ' . $e->getMessage());
        }
    }
    
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $bbk = BbkHdr::where('bbkid', $id)->firstOrFail();
            $bbk->bbkdtls()->delete();
            $bbk->delete();

            return redirect()->route('bbk.index')
                ->with('success', 'Data BBK berhasil dihapus.');
        } catch (\Exception $e) {
            return redirect()->route('bbk.index')
                ->with('error', 'Gagal menghapus data: ' . $e->getMessage());
        }
    }
}