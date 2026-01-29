<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

use App\Models\WoHdr;
use App\Models\WoDtl;

class WoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $userBraco = Auth::user()->cabang;

        $wohdr = WoHdr::with(['mbranch', 'mformcode', 'wodtls'])
                    ->where('braco', $userBraco)
                    ->get();

        $latestPeriod = DB::table('tperiode')
            ->where('braco', Auth::user()->cabang)
            ->orderByDesc('periode')
            ->first();

        $periodClosed = $latestPeriod && $latestPeriod->status === 'C';

        return view('production.work_order.wo_index', compact('wohdr', 'periodClosed'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(request $request)
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

        $branches = DB::table('mbranches')->get();

        $costc = DB::table('mcost_tbl')->get();

        return view('production.work_order.wo_create', compact('periodeAktif', 'minDate', 'branches', 'costc'));
    }

    public function generateWonum(Request $request)
    {
        $braco = auth()->user()->cabang;
        $formc = 'WO';
        $wodat = $request->wodat;

        $year = Carbon::parse($wodat)->format('y');

        $last = DB::table('tworkh')
            ->where('braco', $braco)
            ->where('formc', $formc)
            ->whereRaw("LEFT(wonum,2) = ?", [$year])
            ->orderBy('wonum','desc')
            ->value('wonum');

        if ($last) {
            $number = (int)substr($last, 2) + 1;
        } else {
            $number = 1;
        }

        return $year . str_pad($number, 4, '0', STR_PAD_LEFT);
    }

    public function getRa($braco)
    {
        $ra = DB::table('tsreqh')
            ->where('braco', $braco)
            ->orderBy('reqno', 'desc')
            ->get();

        return response()->json($ra);
    }

    public function getBarangByRA($bpbid)
    {
        $data = DB::table('tsreqd')
            ->join('mpromas', 'mpromas.opron', '=', 'tsreqd.opron')
            ->where('tsreqd.bpbid', $bpbid)
            ->whereColumn('tsreqd.qtyta', '<', 'tsreqd.rqqty')
            ->select(
                'tsreqd.opron',
                'tsreqd.rqqty',
                'mpromas.prona',
                'mpromas.stdqu'
            )
            ->get();

        return response()->json($data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        DB::beginTransaction();

        try {
            $woid = $request->braco . $request->formc . $request->wonum;

            $bracoformc = $request->braco . $request->formc;

            // Simpan header
            WoHdr::create([
                'woid' => $woid,
                'bracoformc' => $bracoformc,
                'braco' => $request->braco,
                'formc' => $request->formc,
                'wonum' => $request->wonum,
                'wodat' => $request->wodat,
                'priod' => $request->priod,
                'reqbr' => $request->reqbr,
                'ppose' => $request->ppose,
                'reffc' => $request->reffc,
                'refno' => $request->refno,
                'reqdt' => $request->reqdt,
                'cusna' => $request->cusna,
                'sorfc' => $request->sorfc,
                'sorno' => $request->sorno,
                'costc' => $request->costc,
                'fdate' => $request->fdate,
                'noteh' => $request->noteh,
                'created_at' => now(),
                'created_by' => Auth::user()->name,
                'updated_at' => now(),
                'updated_by' => Auth::user()->name,
            ]);

            // Loop tiap detail barang
            foreach ($request->opron as $i => $useOpron) {
                WoDtl::create([
                    'woid' => $woid,
                    'braco' => $request->braco,
                    'formc' => $request->formc,
                    'wonum' => $request->wonum,
                    'outpr' => $useOpron,
                    'outqt' => $request->outqt[$i],
                    'stdqu' => $request->stdqu[$i],
                    'noted' => $request->noted[$i],
                ]);
            }

            DB::commit();
            return redirect()->route('wo.index')->with('success', "Data WO \"$woid\" berhasil disimpan.");
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Gagal simpan WO:', ['error' => $e->getMessage()]);
            return back()
            ->withInput()
            ->with('error', 'Terjadi kesalahan saat menyimpan: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $wo = WoHdr::with('wodtls.mpromas')->findOrFail($id);

        return view('production.work_order.wo_detail', compact('wo'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $wo = WoHdr::with('wodtls.mpromas')->findOrFail($id);

        return view('production.work_order.wo_edit', compact('wo'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        DB::beginTransaction();

        try {
            $woid = $request->braco . $request->formc . $request->wonum;

            // update header
            WoHdr::where('woid', $woid)->update([
                'noteh' => $request->noteh,
                'updated_at' => now(),
                'updated_by' => Auth::user()->name,
            ]);

            // hapus detail
            WoDtl::where('woid', $woid)->delete();

            // Loop tiap detail barang
            foreach ($request->opron as $i => $useOpron) {
                WoDtl::create([
                    'woid' => $woid,
                    'braco' => $request->braco,
                    'formc' => $request->formc,
                    'wonum' => $request->wonum,
                    'outpr' => $useOpron,
                    'outqt' => $request->outqt[$i],
                    'stdqu' => $request->stdqu[$i],
                    'noted' => $request->noted[$i],
                ]);
            }

            DB::commit();
            return redirect()->route('wo.index')->with('success', "Data Work Order \"$woid\" berhasil dirubah.");
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Gagal ubah Work Order:', ['error' => $e->getMessage()]);
            return back()
            ->withInput()
            ->with('error', 'Terjadi kesalahan saat mengubah: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $wo = WoHdr::where('woid', $id)->firstOrFail();
            $wo->wodtls()->delete();
            $wo->delete();

            return redirect()->route('wo.index')
                ->with('success', 'Data Work Order "'.$id.'" berhasil dihapus.');
        } catch (\Exception $e) {
            return redirect()->route('wo.index')
                ->with('error', 'Gagal menghapus data: ' . $e->getMessage());
        }
    }
}
