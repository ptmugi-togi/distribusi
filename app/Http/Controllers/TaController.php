<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

use App\Models\TaHdr;
use App\Models\TaDtl;

class TaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $userBraco = Auth::user()->cabang;

        $tahdr = TaHdr::with('mformcode')
                        ->where('braco', $userBraco)
                        ->get();

        $latestPeriod = DB::table('tperiode')
            ->where('braco', Auth::user()->cabang)
            ->orderByDesc('periode')
            ->first();

        $periodClosed = $latestPeriod && $latestPeriod->status === 'C';

        return view('logistic.ta.ta_index', compact('tahdr', 'userBraco', 'periodClosed'));
    }

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

    public function getSa(Request $request)
    {
        $rqbrc = $request->rqbrc;

        $sa = DB::table('tsreqh')
            ->where('braco', $rqbrc)
            ->get();

        $braco = DB::table('mbranches')
            ->where('braco', $rqbrc)
            ->first();

        return response()->json([
          'sa' => $sa,
          'braco' => $braco
        ]);
    }

    public function getBarangByRA($ra_id)
    {
        $data = DB::table('tsreqd')
            ->join('mpromas', 'mpromas.opron', '=', 'tsreqd.opron')
            ->where('tsreqd.bpbid', $ra_id)
            ->select(
                'tsreqd.opron',
                'tsreqd.rqqty',
                'mpromas.prona',
                'mpromas.stdqu'
            )
            ->get();

        return response()->json($data);
    }

    public function getLotByRA($ra_id, $opron)
    {
        return DB::table('stobl_tbl')
            ->join('tsreqd', 'tsreqd.opron', '=', 'stobl_tbl.opron')
            ->where('tsreqd.bpbid', $ra_id)
            ->where('stobl_tbl.opron', $opron)
            ->where('stobl_tbl.toqoh', '>', 0)
            ->select(
                'stobl_tbl.lotno',
                'stobl_tbl.toqoh',
                'stobl_tbl.locco',
                'tsreqd.rqqty'
            )
            ->get();
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

        $mwarco = DB::table('mwarco_tbl')
            ->where('braco', auth()->user()->cabang)
            ->get();

        $mexped = DB::table('mexped')->get();

        return view('logistic.ta.ta_create', compact('periodeAktif', 'minDate', 'mbranch', 'mwarco', 'mexped'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        DB::beginTransaction();

        try {
            $bbkid = $request->braco . $request->warco . $request->formc . $request->trano;

            TaHdr::create([
                'bbkid'      => $bbkid,
                'braco'      => $request->braco,
                'warco'      => $request->warco,
                'formc'      => $request->formc,
                'trano'      => $request->trano,
                'tradt'      => $request->tradt,
                'priod'      => $request->priod,
                'rqbrc'      => $request->rqbrc,
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

                TaDtl::create([
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
                    ->where('warco', $request->warco)
                    ->where('braco', $request->braco)
                    ->where('opron', $opron)
                    ->first();

                if ($existW) {
                    DB::table('stobw_tbl')
                        ->where('warco', $request->warco)
                        ->where('braco', $request->braco)
                        ->where('opron', $opron)
                        ->update([
                            'toqoh' => DB::raw("toqoh - $trqty"),
                            'qtyit' => DB::raw("qtyit + $trqty"),
                        ]);
                }

                $existL = DB::table('stobl_tbl')
                    ->where('warco', $request->warco)
                    ->where('braco', $request->braco)
                    ->where('opron', $opron)
                    ->where('lotno', $lotno)
                    ->first();

                if ($existL) {
                    DB::table('stobl_tbl')
                        ->where('warco', $request->warco)
                        ->where('braco', $request->braco)
                        ->where('opron', $opron)
                        ->where('lotno', $lotno)
                        ->update([
                            'toqoh' => DB::raw("toqoh - $trqty"),
                            'qtyit' => DB::raw("qtyit + $trqty"),
                        ]);
                }
            }

            DB::commit();
            return redirect()->route('ta.index')->with('success', "TA \"$bbkid\" berhasil disimpan.");

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Gagal simpan TA:', ['error' => $e->getMessage()]);
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $ta = TaHdr::with('tadtls', 'mbranch', 'mwarco')->findOrFail($id);
        return view('logistic.ta.ta_detail', compact('ta'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $ta = TaHdr::with('mbranch', 'mwarco', 'mpromas')->findOrFail($id);
        $tadtls = Tadtl::with('mpromas')->where('bbkid', $id)->get();

        return view('logistic.ta.ta_edit', compact('ta', 'tadtls'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $bbkid)
    {
        DB::beginTransaction();

        try {
            $ta = DB::table('tsisnh')->where('bbkid', $bbkid)->first();
            if (!$ta) {
                return redirect()->route('bbk.index')->with('error', 'Data TA tidak ditemukan.');
            }

            // Update header
            DB::table('tsisnh')->where('bbkid', $bbkid)->update([
                'noteh'      => $request->noteh,
                'updated_at' => now(),
                'updated_by' => Auth::user()->name,
            ]);

            // Ambil detail lama (per lot)
            $oldDetails = DB::table('toutg')
                ->where('trano', $ta->trano)
                ->get();

            // Rollback stok lama per lot
            foreach ($oldDetails as $old) {
                // STOBW rollback
                $stobw = DB::table('stobw_tbl')
                    ->where('braco', $ta->braco)
                    ->where('warco', $ta->warco)
                    ->where('opron', $old->opron)
                    ->first();

                if ($stobw) {
                    DB::table('stobw_tbl')
                        ->where('braco', $ta->braco)
                        ->where('warco', $ta->warco)
                        ->where('opron', $old->opron)
                        ->update([
                            'toqoh' => DB::raw("toqoh + {$old->trqty}"),
                            'qtyit' => max(0, $stobw->qtyit - $old->trqty),
                        ]);
                }

                // STOBL rollback
                $stobl = DB::table('stobl_tbl')
                    ->where('braco', $ta->braco)
                    ->where('warco', $ta->warco)
                    ->where('opron', $old->opron)
                    ->where('lotno', $old->lotno)
                    ->where('qunit', $old->qunit)
                    ->where('locco', $old->locco)
                    ->first();

                if ($stobl) {
                    DB::table('stobl_tbl')
                        ->where('braco', $ta->braco)
                        ->where('warco', $ta->warco)
                        ->where('opron', $old->opron)
                        ->where('lotno', $old->lotno)
                        ->where('qunit', $old->qunit)
                        ->where('locco', $old->locco)
                        ->update([
                            'toqoh' => DB::raw("toqoh + {$old->trqty}"),
                            'qtyit' => max(0, $stobl->qtyit - $old->trqty),
                        ]);
                }
            }

            // Hapus detail lama
            DB::table('toutg')->where('trano', $ta->trano)->delete();

            // Insert detail baru per lot
            foreach ($request->opron as $i => $opron) {
                $lotno = $request->lotno[$i];
                $trqty = (int) $request->trqty[$i];
                $qunit = $request->qunit[$i];
                $locco = $request->locco[$i];
                $noted = $request->noted[$i] ?? null;

                // Insert toutg
                DB::table('toutg')->insert([
                    'bbkid' => $bbkid,
                    'formc' => $ta->formc,
                    'trano' => $ta->trano,
                    'opron' => $opron,
                    'lotno' => $lotno,
                    'trqty' => $trqty,
                    'qunit' => $qunit,
                    'locco' => $locco,
                    'noted' => $noted,
                ]);

                // Update STOBW
                $stobw = DB::table('stobw_tbl')
                    ->where('braco', $ta->braco)
                    ->where('warco', $ta->warco)
                    ->where('opron', $opron)
                    ->first();

                if (!$stobw) {
                    DB::table('stobw_tbl')->insert([
                        'braco' => $ta->braco,
                        'warco' => $ta->warco,
                        'opron' => $opron,
                        'toqoh' => 0,
                        'qtyit' => 0,
                    ]);
                    $stobw = (object)['qtyit'=>0,'toqoh'=>0];
                }

                DB::table('stobw_tbl')
                    ->where('braco', $ta->braco)
                    ->where('warco', $ta->warco)
                    ->where('opron', $opron)
                    ->update([
                        'toqoh' => DB::raw("toqoh - {$trqty}"),
                        'qtyit' => $stobw->qtyit + $trqty,
                    ]);

                // Update STOBL
                $stobl = DB::table('stobl_tbl')
                    ->where('braco', $ta->braco)
                    ->where('warco', $ta->warco)
                    ->where('opron', $opron)
                    ->where('lotno', $lotno)
                    ->where('qunit', $qunit)
                    ->where('locco', $locco)
                    ->first();

                if (!$stobl) {
                    DB::table('stobl_tbl')->insert([
                        'braco' => $ta->braco,
                        'warco' => $ta->warco,
                        'opron' => $opron,
                        'lotno' => $lotno,
                        'qunit' => $qunit,
                        'locco' => $locco,
                        'toqoh' => 0,
                        'qtyit' => 0,
                    ]);
                    $stobl = (object)['qtyit'=>0,'toqoh'=>0];
                }

                DB::table('stobl_tbl')
                    ->where('braco', $ta->braco)
                    ->where('warco', $ta->warco)
                    ->where('opron', $opron)
                    ->where('lotno', $lotno)
                    ->where('qunit', $qunit)
                    ->where('locco', $locco)
                    ->update([
                        'toqoh' => DB::raw("toqoh - {$trqty}"),
                        'qtyit' => $stobl->qtyit + $trqty,
                    ]);
            }

            DB::commit();
            return redirect()->route('ta.index')->with('success', "Data TA $bbkid berhasil diperbarui.");

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Gagal update TA:', ['error' => $e->getMessage()]);
            return back()->with('error', 'Terjadi kesalahan saat update: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $ta = TaHdr::where('bbkid', $id)->firstOrFail();
            $ta->tadtls()->delete();
            $ta->delete();

            return redirect()->route('ta.index')
                ->with('success', 'Data TA berhasil dihapus.');
        } catch (\Exception $e) {
            return redirect()->route('ta.index')
                ->with('error', 'Gagal menghapus data: ' . $e->getMessage());
        }
    }
}
