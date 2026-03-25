<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

use App\Models\DoHdr;
use App\Models\DoDtl;

class DoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $userBraco = Auth::user()->cabang;

        $dohdr = DoHdr::with('mformcode')
                        ->where('braco', $userBraco)
                        ->get();

        $latestPeriod = DB::table('tperiode')
            ->where('braco', Auth::user()->cabang)
            ->orderByDesc('periode')
            ->first();

        $periodClosed = $latestPeriod && $latestPeriod->status === 'C';

        return view('logistic.do.do_index', compact('dohdr', 'userBraco', 'periodClosed'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $minDate = null;

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

        $mbranch = DB::table('mbranches')->get();

        $mexped = DB::table('mexped')->get();

        return view('logistic.do.do_create', compact('periodeAktif', 'minDate', 'mbranch', 'mexped'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // dd(request()->all());
        DB::beginTransaction();

        try {
            $bbkid = $request->braco .  $request->formc . $request->trano;

            $bracoformc = $request->braco . $request->formc;

            DoHdr::create([
                'bbkid'      => $bbkid,
                'bracoformc' => $bracoformc,
                'braco'      => $request->braco,
                'warco'      => '-',
                'formc'      => $request->formc,
                'trano'      => $request->trano,
                'tradt'      => $request->tradt,
                'priod'      => $request->priod,
                'rfc01'      => $request->rfc01,
                'ref01'      => $request->ref01,
                'exped'      => $request->exped,
                'noteh'      => $request->noteh,
                'created_at' => now(),
                'created_by' => Auth::user()->name,
                'updated_at' => now(),
                'updated_by' => Auth::user()->name,
                'user_id'    => Auth::user()->id,
            ]);

            foreach ($request->opron as $i => $opron) {

                $lotno = $request->lotno[$i];
                $trqty = (int) $request->trqty[$i];
                $locco = $request->locco[$i];
                $qunit = $request->qunit[$i];
                $noted = $request->noted[$i] ?? '';

                DoDtl::create([
                    'bbkid' => $bbkid,
                    'formc' => $request->formc,
                    'trano' => $request->trano,
                    'opron' => $opron,
                    'qunit' => $qunit,
                    'trqty' => $trqty,
                    'lotno' => $lotno,
                    'locco' => $locco,
                    'qtyit' => $trqty,
                    'reffc' => $request->rfc01,
                    'refno' => $request->ref01,
                    'noted' => $noted,
                ]);

                $existW = DB::table('stobw_tbl')
                    ->where('braco', $request->braco)
                    ->where('opron', $opron)
                    ->first();

                if ($existW) {
                    DB::table('stobw_tbl')
                        ->where('braco', $request->braco)
                        ->where('opron', $opron)
                        ->update([
                            'toqoh' => DB::raw("toqoh - $trqty"),
                        ]);
                }

                $existL = DB::table('stobl_tbl')
                    ->where('braco', $request->braco)
                    ->where('opron', $opron)
                    ->where('lotno', $lotno)
                    ->first();

                if ($existL) {
                    DB::table('stobl_tbl')
                        ->where('braco', $request->braco)
                        ->where('opron', $opron)
                        ->where('lotno', $lotno)
                        ->update([
                            'toqoh' => DB::raw("toqoh - $trqty"),
                        ]);
                }

                if($request->rfc01 == 'SA') {
                    DB::table('tcored')
                        ->where('braco', $request->braco)
                        ->where('formc', $request->rfc01)
                        ->where('sorno', $request->ref01)
                        ->where('opron', $opron)
                        ->update([
                            'qtydo' => DB::raw("qtydo + $trqty")
                        ]);
                }

                if($request->rfc01 == 'SB') {
                    DB::table('tprojc')
                        ->where('braco', $request->braco)
                        ->where('formc', $request->rfc01)
                        ->where('sorno', $request->ref01)
                        ->where('opron', $opron)
                        ->update([
                            'delqt' => DB::raw("delqt + $trqty")
                        ]);
                }
            }

            DB::commit();
            return redirect()->route('do.index')->with('success', "DO \"$bbkid\" berhasil disimpan.");

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Gagal simpan DO:', ['error' => $e->getMessage()]);
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $do = DoHdr::with('dodtls', 'mbranch', 'mformcode')->findOrFail($id);
        return view('logistic.do.do_detail', compact('do'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $do = DoHdr::with('dodtls', 'mbranch', 'mformcode', 'mpromas')->findOrFail($id);
        $dodtls = Dodtl::with('mpromas')->where('bbkid', $id)->get();

        return view('logistic.do.do_edit', compact('do', 'dodtls'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        DB::beginTransaction();

        try {
            $do = DB::table('tsisnh')->where('bbkid', $id)->first();
            if (!$do) {
                return redirect()->route('bbk.index')->with('error', 'Data DO tidak ditemukan.');
            }

            // Update header
            DB::table('tsisnh')->where('bbkid', $id)->update([
                'noteh'      => $request->noteh,
                'updated_at' => now(),
                'updated_by' => Auth::user()->name,
            ]);

            // Ambil detail lama (per lot)
            $oldDetails = DB::table('toutg')
                ->where('trano', $do->trano)
                ->get();

            // Rollback stok lama per lot
            foreach ($oldDetails as $old) {
                // STOBW rollback
                $stobw = DB::table('stobw_tbl')
                    ->where('braco', $do->braco)
                    ->where('opron', $old->opron)
                    ->first();

                if ($stobw) {
                    DB::table('stobw_tbl')
                        ->where('braco', $do->braco)
                        ->where('opron', $old->opron)
                        ->update([
                            'toqoh' => DB::raw("toqoh + {$old->trqty}"),
                            'qtyit' => max(0, $stobw->qtyit - $old->trqty),
                        ]);
                }

                // STOBL rollback
                $stobl = DB::table('stobl_tbl')
                    ->where('braco', $do->braco)
                    ->where('opron', $old->opron)
                    ->where('lotno', $old->lotno)
                    ->where('qunit', $old->qunit)
                    ->where('locco', $old->locco)
                    ->first();

                if ($stobl) {
                    DB::table('stobl_tbl')
                        ->where('braco', $do->braco)
                        ->where('opron', $old->opron)
                        ->where('lotno', $old->lotno)
                        ->where('qunit', $old->qunit)
                        ->where('locco', $old->locco)
                        ->update([
                            'toqoh' => DB::raw("toqoh + {$old->trqty}"),
                            'qtyit' => max(0, $stobl->qtyit - $old->trqty),
                        ]);
                }

                // ROLLBACK SA
                if ($do->rfc01 == 'SA') {
                    $ocid = DB::table('tcoreh')
                        ->where('braco', $do->braco)
                        ->where('sorno', $do->ref01)
                        ->value('ocid');

                    DB::table('tcored')
                        ->where('ocid', $ocid)
                        ->where('opron', $old->opron)
                        ->update([
                            'qtydo' => DB::raw("GREATEST(qtydo - {$old->trqty}, 0)")
                        ]);
                }

                // ROLLBACK SB
                if ($do->rfc01 == 'SB') {
                    $ocsbid = DB::table('tproja')
                        ->where('braco', $do->braco)
                        ->where('sorno', $do->ref01)
                        ->value('ocsbid');

                    DB::table('tprojc')
                        ->where('ocsbid', $ocsbid)
                        ->where('opron', $old->opron)
                        ->update([
                            'delqt' => DB::raw("GREATEST(delqt - {$old->trqty}, 0)")
                        ]);
                }
            }

            // Hapus detail lama
            DB::table('toutg')->where('trano', $do->trano)->delete();

            // Insert detail baru per lot
            foreach ($request->opron as $i => $opron) {
                $lotno = $request->lotno[$i];
                $trqty = (int) $request->trqty[$i];
                $qunit = $request->qunit[$i];
                $locco = $request->locco[$i];
                $noted = $request->noted[$i] ?? null;

                // Insert toutg
                DB::table('toutg')->insert([
                    'bbkid' => $id,
                    'formc' => $do->formc,
                    'trano' => $do->trano,
                    'opron' => $opron,
                    'lotno' => $lotno,
                    'trqty' => $trqty,
                    'qunit' => $qunit,
                    'locco' => $locco,
                    'noted' => $noted,
                ]);

                // Update STOBW
                $stobw = DB::table('stobw_tbl')
                    ->where('braco', $do->braco)
                    ->where('opron', $opron)
                    ->first();

                if (!$stobw) {
                    DB::table('stobw_tbl')->insert([
                        'braco' => $do->braco,
                        'warco' => '-',
                        'opron' => $opron,
                        'toqoh' => 0,
                    ]);
                    $stobw = (object)['toqoh'=>0];
                }

                DB::table('stobw_tbl')
                    ->where('braco', $do->braco)
                    ->where('opron', $opron)
                    ->update([
                        'toqoh' => DB::raw("toqoh - {$trqty}"),
                    ]);

                // Update STOBL
                $stobl = DB::table('stobl_tbl')
                    ->where('braco', $do->braco)
                    ->where('opron', $opron)
                    ->where('lotno', $lotno)
                    ->where('qunit', $qunit)
                    ->where('locco', $locco)
                    ->first();

                if (!$stobl) {
                    DB::table('stobl_tbl')->insert([
                        'braco' => $do->braco,
                        'opron' => $opron,
                        'lotno' => $lotno,
                        'qunit' => $qunit,
                        'locco' => $locco,
                        'toqoh' => 0,
                    ]);
                    $stobl = (object)['toqoh'=>0];
                }

                DB::table('stobl_tbl')
                    ->where('braco', $do->braco)
                    ->where('opron', $opron)
                    ->where('lotno', $lotno)
                    ->where('qunit', $qunit)
                    ->where('locco', $locco)
                    ->update([
                        'toqoh' => DB::raw("toqoh - {$trqty}"),
                    ]);

                $ocid = null;
                if ($request->rfc01 == 'SA') {
                    $ocid = DB::table('tcoreh')
                        ->where('braco', $request->braco)
                        ->where('sorno', $request->ref01)
                        ->value('ocid');

                    DB::table('tcored')
                        ->where('ocid', $ocid)
                        ->where('opron', $opron)
                        ->update([
                            'qtydo' => DB::raw("COALESCE(qtydo,0) + $trqty")
                        ]);
                }

                $ocsbid = null;
                if ($request->rfc01 == 'SB') {
                    $ocsbid = DB::table('tproja')
                        ->where('braco', $request->braco)
                        ->where('sorno', $request->ref01)
                        ->value('ocsbid');

                    DB::table('tprojc')
                        ->where('ocsbid', $ocsbid)
                        ->where('opron', $opron)
                        ->update([
                            'delqt' => DB::raw("COALESCE(delqt,0) + $trqty")
                        ]);
                }
            }

            DB::commit();
            return redirect()->route('do.index')->with('success', "Data DO $id berhasil diperbarui.");

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Gagal update DO:', ['error' => $e->getMessage()]);
            return back()->with('error', 'Terjadi kesalahan saat update: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $do = DoHdr::where('bbkid', $id)->firstOrFail();
            $do->dodtls()->delete();
            $do->delete();

            return redirect()->route('do.index')
                ->with('success', 'Data DO berhasil dihapus.');
        } catch (\Exception $e) {
            return redirect()->route('do.index')
                ->with('error', 'Gagal menghapus data: ' . $e->getMessage());
        }
    }

    public function generateTrano(Request $request)
    {
        $braco = auth()->user()->cabang;
        $formc = $request->formc;
        $tradt = $request->tradt;
        
        $year = Carbon::parse($tradt)->format('y');

        $last = DB::table('tsisnh')
            ->where('braco', $braco)
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

    public function getOc()
    {
        $braco = Auth::user()->cabang;

        $sa = DB::table('tcoreh as h')
            ->join('mcusmas as c', 'c.cusno', '=', 'h.cusno')
            ->select(
                'h.sorno as value',
                DB::raw("'SA' as type"),
                'h.sorno',
                'c.cusna as cust',
                DB::raw("CONCAT('SA',' - ',h.sorno) as text")
            )
            ->where('h.braco', $braco);

        $sb = DB::table('tproja as h')
            ->join('mcusmas as c', 'c.cusno', '=', 'h.cusno')
            ->select(
                'h.sorno as value',
                DB::raw("'SB' as type"),
                'h.sorno',
                'c.cusna as cust',
                DB::raw("CONCAT('SB',' - ',h.sorno) as text")
            )
            ->where('h.braco', $braco);

        return $sa->unionAll($sb)->orderBy('text')->get();
    }

    public function getBarangByOC(Request $req)
    {
        $type = $req->type;
        $sorno = $req->sorno;

        if ($type == 'SA') {

            $data = DB::table('tcoreh as h')
                ->join('tcored as d', 'h.ocid', '=', 'd.ocid')
                ->join('mpromas as p', 'p.opron', '=', 'd.opron')
                ->where('h.sorno', $sorno)
                ->select(
                    'd.opron',
                    DB::raw('(d.qtyor - d.qtydo) as qty'),
                    'p.prona',
                    'p.stdqu'
                )
                ->get();

        } else {

            $data = DB::table('tproja as h')
                ->join('tprojc as d', 'h.ocsbid', '=', 'd.ocsbid')
                ->join('mpromas as p', 'p.opron', '=', 'd.opron')
                ->where('h.sorno', $sorno)
                ->where('d.trqty', '>', 'd.delqt')
                ->select(
                    'd.opron',
                    DB::raw('(d.trqty - d.delqt) as qty'),
                    'p.prona',
                    'p.stdqu'
                )
                ->get();
        }

        return response()->json($data);
    }

   public function getLotByOC(Request $req)
    {
        $braco = Auth::user()->cabang;
        $opron = $req->opron;

        $data = DB::table('stobl_tbl')
            ->where('braco', $braco)
            ->where('opron', $opron)
            ->select(
                'lotno',
                'locco'
            )
            ->get();

        return response()->json($data);
    }
}
