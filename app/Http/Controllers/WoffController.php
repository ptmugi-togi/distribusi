<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

use App\Models\WoffHdr;
use App\Models\WoffDtl;

class WoffController extends Controller
{

    public function index()
    {
        $userBraco = Auth::user()->cabang;
        
        $writeoff_ar = WoffHdr::with('mbranch', 'mcusmas', 'mformcode')
            ->where('braco', $userBraco)
            ->get();

        $latestPeriod = DB::table('tperiode')
            ->where('braco', Auth::user()->cabang)
            ->orderByDesc('periode')
            ->first();

        $periodClosed = $latestPeriod && $latestPeriod->status === 'C';

        return view('fna.writeoff_ar.writeoff_ar_index', compact('writeoff_ar', 'periodClosed'));
    }

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

        return view('fna.writeoff_ar.writeoff_ar_create', compact('periodeAktif', 'minDate', 'mbranch'));
    }

    public function store(Request $request)
    {
        DB::beginTransaction();

        // dd($request->all());

        try {
            $woffid = $request->braco .  $request->formc . $request->vcrno;

            $bracoformc = $request->braco . $request->formc;

            $tradt = Carbon::parse($request->tradt);

            WoffHdr::create([
                'woffid'     => $woffid,
                'vcrno'      => $request->vcrno,
                'bracoformc' => $bracoformc,
                'braco'      => $request->braco,
                'formc'      => $request->formc,
                'priod'      => $request->priod,
                'tradt'      => $tradt,
                'refno'      => $request->refno,
                'noteh'      => $request->noteh,
                'created_at' => now(),
                'created_by' => Auth::user()->name,
                'updated_at' => now(),
                'updated_by' => Auth::user()->name,
            ]);

            foreach ($request->invno as $i => $invno) {

                WoffDtl::create([
                    'woffid' => $woffid,
                    'braco' => $request->braco,
                    'formc' => $request->formc,
                    'vcrno' => $request->vcrno,
                    'invfc' => $request->formc_inv[$i],
                    'invrn' => $request->invno_raw[$i],
                    'cusno' => $request->cusno[$i],
                    'trval' => $request->pcwo[$i],
                    'curco' => $request->curco[$i],
                    'irate' => $request->irate[$i],
                    'noted' => $request->noted[$i],
                ]);
    
                DB::table('tinmas')
                    ->where('braco', $request->braco)
                    ->where('formc', $request->formc_inv[$i])
                    ->where('invno', $request->invno_raw[$i])
                    ->update([
                        'recwo' => DB::raw('COALESCE(recwo,0) + ' . (float)($request->pcwo[$i] ?? 0)),
                    ]);
            }

            DB::commit();
            return redirect()->route('writeoff_ar.index')->with('success', "data Write Off A/R \"$woffid\" berhasil disimpan.");

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Gagal simpan data Write Off A/R:', ['error' => $e->getMessage()]);
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function show(string $id)
    {
        $woffar = WoffHdr::with([
            'mbranch',
            'mformcode',
            'mcusmas',
        ])->findOrFail($id);

        $details = DB::table('twoffd as d')
            ->leftJoin('tinmas as t', function ($join) {
                $join->on('t.formc', '=', 'd.invfc')
                    ->on('t.braco', '=', 'd.braco')
                    ->on('t.invno', '=', 'd.invrn');
            })
            ->leftJoin('mcusmas as c', 'c.cusno', '=', 'd.cusno')
            ->where('d.woffid', $id)
            ->select(
                'd.*',
                't.txamt',
                't.duedt',
                't.ntamt',
                't.blamt',
                't.caval',
                't.recwo',
                'c.cusna',
                DB::raw('(t.blamt - (t.caval + t.recwo)) + d.trval as arval')
            )
            ->get();

        return view('fna.writeoff_ar.writeoff_ar_detail',compact('woffar', 'details'));
    }

    public function edit(string $id){
        $woffar = WoffHdr::with([
            'mbranch',
            'mformcode',
            'mcusmas',
        ])->findOrFail($id);

         $details = DB::table('twoffd as d')
            ->leftJoin('tinmas as t', function ($join) {
                $join->on('t.formc', '=', 'd.invfc')
                    ->on('t.braco', '=', 'd.braco')
                    ->on('t.invno', '=', 'd.invrn');
            })
            ->leftJoin('mcusmas as c', 'c.cusno', '=', 'd.cusno')
            ->where('d.woffid', $id)
            ->select(
                'd.*',
                't.txamt',
                't.duedt',
                't.ntamt',
                't.blamt',
                't.caval',
                't.recwo',
                'c.cusna',
                DB::raw('(t.blamt - (t.caval + t.recwo)) + d.trval as arval')
            )
            ->get();

        return view('fna.writeoff_ar.writeoff_ar_edit', compact('woffar', 'details'));
    }

    public function update(Request $request, $id)
    {
        DB::beginTransaction();

        // dd($request->all());

        try {

            $hdr = WoffHdr::where('woffid', $id)->firstOrFail();

            // UPDATE HEADER
            $hdr->update([
                'noteh'      => $request->noteh,
                'updated_at' => now(),
                'updated_by' => Auth::user()->name,
            ]);

            WoffDtl::where('woffid', $id)->delete();

            // INSERT DETAIL BARU
            foreach ($request->invno as $i => $invno) {

                WoffDtl::create([
                    'woffid' => $id,
                    'braco' => $request->braco,
                    'formc' => $request->formc,
                    'vcrno' => $request->vcrno,
                    'invfc' => $request->formc_inv[$i],
                    'invrn' => $request->invrn[$i],
                    'cusno' => $request->cusno[$i],
                    'trval' => $request->trval[$i],
                    'curco' => $request->curco[$i],
                    'irate' => $request->irate[$i],
                    'noted' => $request->noted[$i],
                ]);
    
                DB::table('tinmas')
                    ->where('braco', $request->braco)
                    ->where('formc', $request->formc_inv[$i])
                    ->where('invno', $request->invrn[$i])
                    ->update([
                        'recwo' => $request->trval[$i],
                    ]);
            }

            DB::commit();

            return redirect()
                ->route('writeoff_ar.index')
                ->with('success', "Data Write Off A/R \"$id\" berhasil diupdate.");

        } catch (\Exception $e) {

            DB::rollBack();

            return back()->with('error', $e->getMessage());
        }
    }

    public function generateWoffNo(Request $request)
    {
        $braco = auth()->user()->cabang;
        $formc = $request->formc;
        $tradt = $request->tradt;

        $year = Carbon::parse($tradt)->format('y');

        $last = DB::table('twoffh')
            ->where('braco', $braco)
            ->where('formc', $formc)
            ->whereRaw("LEFT(vcrno,2) = ?", [$year])
            ->orderByDesc('vcrno')
            ->value('vcrno');

        if ($last) {
            $number = (int) substr($last, 2, 4) + 1;
        } else {
            $number = 1;
        }

        $running = str_pad($number, 4, '0', STR_PAD_LEFT);

        return $year . $running;
    }

    public function getInvoice(){
        $braco = Auth::user()->cabang;

        $invoice = DB::table('tinmas as h')
            ->leftJoin('mcusmas as c', 'h.cusno', '=', 'c.cusno')
            ->select(
                'h.formc as formc',
                'h.invno as invno',
                'h.cusno as cusno',
                'c.cusna as cusna',
                DB::raw("CONCAT(h.cusno, ' ', c.cusna) as cust"),
                'h.cuspo as cuspo',
                'h.sreno as sreno',
                'h.curco as curco',
                'h.crate as crate',
                'h.ntamt as ntamt',
                'h.txamt as txamt',
                'h.blamt as blamt',
                'h.odisa as odisa',
                DB::raw("DATE_FORMAT(h.duedt, '%m-%d-%Y') as duedt"),
                DB::raw("CONCAT(COALESCE(h.formc,''), ' - ', COALESCE(h.invno,'')) as text"),
                DB::raw("(COALESCE(h.blamt,0) - (COALESCE(h.caval,0) + COALESCE(h.recwo,0))) as arval")
            )
            ->where('h.braco', $braco)
            ->whereRaw('(COALESCE(h.blamt,0) - (COALESCE(h.caval,0) + COALESCE(h.recwo,0))) > 0')
            ->orderBy('h.invno', 'desc')
            ->distinct()
            ->get();

        return $invoice;
    }

}