<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use illuminate\Support\Facades\Log;
use Carbon\Carbon;

use App\Models\BbkHdr;
use App\Models\BbkDtl;
use App\Models\Mformcode;
use App\Models\InvoiceHdr;
use App\Models\Mvendor;
use App\Models\Mpromas;
use App\Models\DoHdr;
use App\Models\DoDtl;

class BbkController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $userBraco = Auth::user()->cabang;

        $bbkhdr = BbkHdr::with('mformcode')
                        ->whereIn('formc', ['OF', 'OC', 'OB', 'OD', 'OG', 'OA', 'OE', 'TA', 'DO'])
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

    public function getWarco(Request $request)
    {
        $query = DB::table('mwarco_tbl');

        if ($request->formc_store === 'OB') {
            $query->where('warco', 'like', 'CKG%');
        }

        if ($request->formc_store !== 'OB') {
            $query->where('braco', auth()->user()->cabang);
        }

        return response()->json(
            $query->orderBy('warco')->get()
        );
    }

    public function create()
    {
        $bbkhdr = BbkHdr::all();

        $vendors = DB::table('mvendor_tbl')->get();

        $priod = null;
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

        $tsupih = DB::table('tsupih_tbl')
            ->leftJoin('mvendor_tbl', 'tsupih_tbl.supno', '=', 'mvendor_tbl.supno')
            ->leftJoin('tbolh', 'tsupih_tbl.rinum', '=', 'tbolh.rinum')
            ->select('tsupih_tbl.*', 'mvendor_tbl.supna', 'tbolh.blnum', 'tbolh.vesel')
            ->get();

        $mbranch = DB::table('mbranches')->get();
        $mexped = DB::table('mexped')->get();

        return view('logistic.bbk.bbk_create', compact('bbkhdr','vendors','priod','minDate','periodeAktif','tsupih', 'mbranch', 'mexped'));
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

    public function getBarangOE($braco, $warco, $locco)
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

    public function getStobl($braco, $warco, $opron)
    {
        $stok = DB::table('stobl_tbl')
            ->where('braco', $braco)
            ->where('warco', $warco)
            ->where('opron', $opron)
            ->where('toqoh', '>', 0)
            ->select('lotno', 'qunit', 'toqoh', 'locco')
            ->get();
        return response()->json($stok);
    }

    public function getStoblOb($braco, $warco, $opron)
    {
        $stok = DB::table('stobl_tbl')
            ->where('braco', $braco)
            ->where('warco', $warco)
            ->where('opron', $opron)
            ->where('toqoh', '>', 0)
            ->selectRaw('SUM(toqoh) as toqoh, MAX(qunit) as qunit')
            ->first();

        return response()->json([
            'toqoh' => $stok->toqoh ?? 0,
            'qunit' => $stok->qunit ?? '-',
        ]);
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
        // Deteksi Form Code (apakah dari DO)
        $formc = $request->formc_store;

        if ($formc === 'DO') {
            return $this->storeDeliveryOrder($request);
        }

        return $this->storeBbkBiasa($request);
    }

    private function storeDeliveryOrder(Request $request)
    {
        // dd($request->all());
        DB::beginTransaction();
        try {
            $braco = auth()->user()->cabang ?? $request->braco;
            $formc = 'DO';
            $tradt = $request->tradt;
            $year  = Carbon::parse($tradt)->format('y');
            $mainWarco = is_array($request->warco) ? ($request->warco[0] ?? '-') : ($request->warco ?? '-');

            $last = DB::table('tsisnh')
                ->where('braco', $braco)
                ->where('formc', $formc)
                ->whereRaw("LEFT(trano,2) = ?", [$year])
                ->lockForUpdate()
                ->orderByDesc('trano')
                ->value('trano');

            $number = $last ? ((int) substr($last, 2) + 1) : 1;
            $trano  = $year . str_pad($number, 4, '0', STR_PAD_LEFT);

            $bbkid      = $braco . $formc . $trano;
            $bracoformc = $braco . $formc;

            DoHdr::create([
                'bbkid'      => $bbkid,
                'bracoformc' => $bracoformc,
                'braco'      => $braco,
                'warco'      => $mainWarco,
                'formc'      => $formc,
                'trano'      => $trano,
                'tradt'      => $tradt,
                'priod'      => $request->priod,
                'cusno'      => $request->cusno,
                'cuspo'      => $request->cuspo,
                'rfc01'      => $request->rfc01,
                'ref01'      => $request->ref01,
                'exped'      => $request->exped,
                'noteh'      => $request->noteh,
                'shpto'      => $request->shpto,
                'created_at' => now(),
                'created_by' => Auth::user()->name,
                'updated_at' => now(),
                'updated_by' => Auth::user()->name,
                'user_id'    => Auth::user()->id,
            ]);

            if ($request->has('opron') && is_array($request->opron)) {
                foreach ($request->opron as $i => $opron) {
                    if (!$opron) continue;

                    $lotno = $request->lotno[$i] ?? '-';
                    $trqty = (int) ($request->trqty[$i] ?? 0);
                    $locco = $request->locco[$i] ?? '';
                    $qunit = $request->qunit[$i] ?? '';
                    $warco = is_array($request->warco) ? ($request->warco[$i] ?? $mainWarco) : $mainWarco;
                    $noted = $request->noted[$i] ?? '';

                    DoDtl::create([
                        'bbkid' => $bbkid,
                        'formc' => $formc,
                        'trano' => $trano,
                        'opron' => $opron,
                        'qunit' => $qunit,
                        'trqty' => $trqty,
                        'lotno' => $lotno,
                        'locco' => $locco,
                        'warco' => $warco,
                        'qtyit' => $trqty,
                        'reffc' => $request->rfc01,
                        'refno' => $request->ref01,
                        'noted' => $noted,
                    ]);

                    // Update Stok Header (stobw_tbl)
                    DB::table('stobw_tbl')
                        ->where('braco', $braco)
                        ->where('warco', $warco)
                        ->where('opron', $opron)
                        ->update(['toqoh' => DB::raw("toqoh - $trqty")]);

                    // Update Stok Lot (stobl_tbl)
                    DB::table('stobl_tbl')
                        ->where('braco', $braco)
                        ->where('warco', $warco)
                        ->where('opron', $opron)
                        ->where('lotno', $lotno)
                        ->update(['toqoh' => DB::raw("toqoh - $trqty")]);

                    // Update Outstandings
                    if ($request->rfc01 === 'SA') {
                        DB::table('tcored')
                            ->where('braco', $braco)
                            ->where('formc', $request->rfc01)
                            ->where('sorno', $request->ref01)
                            ->where('opron', $opron)
                            ->update(['qtydo' => DB::raw("qtydo + $trqty")]);
                    } elseif ($request->rfc01 === 'SB') {
                        DB::table('tprojc')
                            ->where('braco', $braco)
                            ->where('formc', $request->rfc01)
                            ->where('sorno', $request->ref01)
                            ->where('opron', $opron)
                            ->update(['delqt' => DB::raw("delqt + $trqty")]);
                    }
                }
            }

            DB::commit();
            return redirect()->route('bbk.index')->with('success', "DO \"$bbkid\" berhasil disimpan.");

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Gagal Simpan DO:', ['error' => $e->getMessage()]);
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage())->withInput();
        }
    }

    private function storeBbkBiasa(Request $request)
    {
        DB::beginTransaction();
        try {
            $braco = auth()->user()->cabang;
            $warco = $request->warco;
            $formc = $request->formc_store;
            $year = Carbon::parse($request->tradt)->format('y');

            $last = DB::table('tsisnh')
                ->where('braco', $braco)
                ->where('warco', $warco)
                ->where('formc', $formc)
                ->whereRaw("LEFT(trano,2) = ?", [$year])
                ->lockForUpdate()
                ->orderByDesc('trano')
                ->value('trano');

            $number = $last ? ((int) substr($last, 2) + 1) : 1;
            $trano = $year . str_pad($number, 4, '0', STR_PAD_LEFT);

            $bbkid = $request->braco . $request->warco . $formc . $trano;
            $bracoformc = $request->braco . $formc;

            BbkHdr::create([
                'bbkid' => $bbkid,
                'bracoformc' => $bracoformc,
                'braco' => $request->braco,
                'warco' => $request->warco,
                'formc' => $formc,
                'trano' => $trano,
                'priod' => $request->priod,
                'rqbrc' => $request->rqbrc,
                'tradt' => $request->tradt,
                'reffc' => $request->reffc,
                'refno' => $request->refno,
                'rfc01' => $request->rfc01,
                'ref01' => $request->ref01,
                'kdprod' => $request->kdprod,
                'supno' => $request->supno ?? '',
                'isutn' => $request->isutn,
                'exped' => $request->exped ?? '',
                'isua1' => $request->isua1,
                'noteh' => $request->noteh,
                'created_at' => now(),
                'created_by' => Auth::user()->name,
                'updated_at' => now(),
                'updated_by' => Auth::user()->name,
                'user_id' => Auth::user()->id,
            ]);

            foreach ($request->opron as $i => $useOpron) {
                $lotStart = $request->lotno[$i] ?? '-';
                $trqty    = (int) ($request->trqty[$i] ?? 1);

                $isNoLot = (isset($request->nolot[$i]) && $request->nolot[$i] == 1) 
                        || in_array($lotStart, ['-', '.', '']);

                if ($isNoLot) {
                    $existingLot = DB::table('stobl_tbl')
                        ->where('warco', $request->warco)
                        ->where('braco', $request->braco)
                        ->where('opron', $useOpron)
                        ->value('lotno');

                    $lotList = [$existingLot ?: '-'];
                } else {
                    $lotList = $this->generateLotList($lotStart, $trqty);
                }

                foreach ($lotList as $lotno) {
                    $qtyPerRecord = $isNoLot ? $trqty : 1;

                    DB::table('toutg')->insert([
                        'bbkid' => $bbkid,
                        'formc' => $formc,
                        'trano' => $trano,
                        'warco' => $request->warco,
                        'opron' => $useOpron,
                        'lotno' => $lotno ?? '-',
                        'reffc' => $request->rfc01,
                        'refno' => $request->ref01,
                        'trqty' => $qtyPerRecord,
                        'qunit' => $request->stdqt[$i] ?? '',
                        'locco' => $request->locco[$i] ?? '',
                        'noted' => $noted[$i] ?? '',
                    ]);
                }

                $existW = DB::table('stobw_tbl')
                    ->where('warco', $request->warco)
                    ->where('braco', $request->braco)
                    ->where('opron', $useOpron)
                    ->first();

                $stobwData = ['toqoh' => DB::raw("toqoh - $trqty")];
                if ($formc === 'TA') {
                    $stobwData['qtyit'] = DB::raw("qtyit + $trqty");
                }

                if ($existW) {
                    DB::table('stobw_tbl')
                        ->where('warco', $request->warco)
                        ->where('braco', $request->braco)
                        ->where('opron', $useOpron)
                        ->update($stobwData);
                } else {
                    DB::table('stobw_tbl')->insert([
                        'braco' => $request->braco,
                        'warco' => $request->warco,
                        'opron' => $useOpron,
                        'toqoh' => 0 - $trqty,
                        'qtyit' => $formc === 'TA' ? $trqty : 0,
                    ]);
                }

                foreach ($lotList as $lotno) {
                    $qtyToDeduct = $isNoLot ? $trqty : 1;

                    $existL = DB::table('stobl_tbl')
                        ->where('warco', $request->warco)
                        ->where('braco', $request->braco)
                        ->where('opron', $useOpron)
                        ->where('lotno', $lotno)
                        ->first();

                    $stoblData = ['toqoh' => DB::raw("toqoh - $qtyToDeduct")];
                    if ($formc === 'TA') {
                        $stoblData['qtyit'] = DB::raw("qtyit + $qtyToDeduct");
                    }

                    if ($existL) {
                        DB::table('stobl_tbl')
                            ->where('warco', $request->warco)
                            ->where('braco', $request->braco)
                            ->where('opron', $useOpron)
                            ->where('lotno', $lotno)
                            ->update($stoblData);
                    } else {
                        DB::table('stobl_tbl')->insert([
                            'braco' => $request->braco,
                            'warco' => $request->warco,
                            'opron' => $useOpron,
                            'qunit' => $request->stdqt[$i] ?? '',
                            'locco' => $request->locco[$i] ?? '',
                            'lotno' => $lotno ?? '-',
                            'toqoh' => 0 - $qtyToDeduct,
                            'qtyit' => $formc === 'TA' ? $qtyToDeduct : 0,
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
            ->select('h.*', 'v.supna', 'f.descr')
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

                $stobwRollback = ['toqoh' => DB::raw("toqoh + {$old->trqty}")];
                if ($bbk->formc === 'TA') {
                    $stobwRollback['qtyit'] = DB::raw("qtyit - {$old->trqty}");
                }

                DB::table('stobw_tbl')
                    ->where('braco', $bbk->braco)
                    ->where('warco', $bbk->warco)
                    ->where('opron', $old->opron)
                    ->update($stobwRollback);

                $stoblRollback = ['toqoh' => DB::raw("toqoh + {$old->trqty}")];
                if ($bbk->formc === 'TA') {
                    $stoblRollback['qtyit'] = DB::raw("qtyit - {$old->trqty}");
                }

                DB::table('stobl_tbl')
                    ->where('braco', $bbk->braco)
                    ->where('warco', $bbk->warco)
                    ->where('opron', $old->opron)
                    ->where('qunit', $old->qunit)
                    ->where('locco', $old->locco)
                    ->where('lotno', $old->lotno)
                    ->update($stoblRollback);
            }

            DB::table('stobw_tbl')->where('toqoh', '<=', 0)->where('qtyit', '<=', 0)->delete();
            DB::table('stobl_tbl')->where('toqoh', '<=', 0)->where('qtyit', '<=', 0)->delete();

            DB::table('toutg')->where('trano', $bbk->trano)->delete();

            foreach ($request->opron as $i => $opron) {

                $lotStart = $request->lotno[$i] ?? '-';
                $trqty    = (int)$request->trqty[$i];
                $qunit    = $request->stdqt[$i];
                $locco    = $request->locco[$i] ?? '000001';
                $noted    = $request->noted[$i] ?? null;

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
                        'warco' => $bbk->warco,
                        'lotno' => $lotno ?? '-',
                        'trqty' => ($lotno === '-' ? $trqty : 1),
                        'qunit' => $qunit,
                        'locco' => $locco,
                        'noted' => $noted,
                    ]);
                }

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
                        'qtyit' => 0,
                    ]);
                }

                $stobwUpdate = ['toqoh' => DB::raw("toqoh - $trqty")];
                if ($bbk->formc === 'TA') {
                    $stobwUpdate['qtyit'] = DB::raw("qtyit + $trqty");
                }

                DB::table('stobw_tbl')
                    ->where('braco', $bbk->braco)
                    ->where('warco', $bbk->warco)
                    ->where('opron', $opron)
                    ->update($stobwUpdate);

                foreach ($lotList as $lotno) {

                    $qtyToDeduct = ($lotno === '-' ? $trqty : 1);

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
                            'qtyit' => 0,
                        ]);
                    }

                    $stoblUpdate = ['toqoh' => DB::raw("toqoh - $qtyToDeduct")];
                    if ($bbk->formc === 'TA') {
                        $stoblUpdate['qtyit'] = DB::raw("qtyit + $qtyToDeduct");
                    }

                    DB::table('stobl_tbl')
                        ->where('braco', $bbk->braco)
                        ->where('warco', $bbk->warco)
                        ->where('opron', $opron)
                        ->where('qunit', $qunit)
                        ->where('locco', $locco)
                        ->where('lotno', $lotno)
                        ->update($stoblUpdate);
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
    // update TA, DO ke masing" Controller
    
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