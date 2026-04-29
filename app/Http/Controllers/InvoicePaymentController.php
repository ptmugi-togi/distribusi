<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

use App\Models\InvoicePaymentHdr;
use App\Models\InvoicePaymentDtl;

class InvoicePaymentController extends Controller
{

    public function index()
    {
        $userBraco = Auth::user()->cabang;
        
        $invoice_payment = InvoicePaymentHdr::with('mbranch', 'mcusmas', 'mformcode', 'msreno')
            ->where('braco', $userBraco)
            ->get();

        $latestPeriod = DB::table('tperiode')
            ->where('braco', Auth::user()->cabang)
            ->orderByDesc('periode')
            ->first();

        $periodClosed = $latestPeriod && $latestPeriod->status === 'C';

        return view('fna.invoice_payment.invoice_payment_index', compact('invoice_payment', 'periodClosed'));
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

        return view('fna.invoice_payment.invoice_payment_create', compact('periodeAktif', 'minDate', 'mbranch'));
    }

    public function store(Request $request)
    {
        DB::beginTransaction();

        // dd($request->all());

        try {
            $invpid = $request->braco .  $request->formc . $request->vcrno;

            $bracoformc = $request->braco . $request->formc;

            $pdate = Carbon::parse($request->pdate);

            InvoicePaymentHdr::create([
                'invpid'     => $invpid,
                'bracoformc' => $bracoformc,
                'vcrno'      => $request->vcrno,
                'braco'      => $request->braco,
                'formc'      => $request->formc,
                'priod'      => $request->priod,
                'pdate'      => $pdate,
                'iorno'      => $request->iorno,
                'curco'      => $request->curco,
                'prate'      => $request->prate,
                'tpaye'      => $request->total,
                'noteh'      => $request->noteh,
                'created_at' => now(),
                'created_by' => Auth::user()->name,
                'updated_at' => now(),
                'updated_by' => Auth::user()->name,
            ]);

            foreach ($request->invno as $i => $invno) {

                $payva = $request->pcval[$i] + $request->pcwo[$i];

                InvoicePaymentDtl::create([
                    'invpid' => $invpid,
                    'braco' => $request->braco,
                    'formc' => $request->formc,
                    'vcrno' => $request->vcrno,
                    'iorno' => $request->iorno,
                    'invno' => $request->invno[$i],
                    'cusno' => $request->cusno[$i],
                    'invfc' => $request->formc_inv[$i],
                    'invrn' => $request->invno_raw[$i],
                    'pcval' => $request->pcval[$i],
                    'pcwo' => $request->pcwo[$i],
                    'payva' => $payva,
                    'noted' => $request->noted[$i],
                ]);
    
                DB::table('tinmas')
                    ->where('braco', $request->braco)
                    ->where('formc', $request->formc_inv[$i])
                    ->where('invno', $request->invno_raw[$i])
                    ->update([
                        'caval' => $request->pcval[$i],
                        'recwo' => $request->pcwo[$i],
                    ]);
            }

            DB::commit();
            return redirect()->route('invoice_payment.index')->with('success', "data Invoice Payment \"$invpid\" berhasil disimpan.");

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Gagal simpan data Invoice Payment:', ['error' => $e->getMessage()]);
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function show(string $id)
    {
        $invp = InvoicePaymentHdr::with([
            'mbranch',
            'mformcode',
            'mcusmas',
            'msreno'
        ])->findOrFail($id);

        $details = DB::table('tpayind as d')
            ->leftJoin('tinmas as t', function ($join) {
                $join->on('t.formc', '=', 'd.invfc')
                    ->on('t.invno', '=', 'd.invrn');
            })
            ->where('d.invpid', $id)
            ->select(
                'd.*',
                't.duedt',
                't.blamt',
                't.caval',
                't.cramt',
                't.recwo',
                DB::raw('(t.blamt - (t.caval + t.recwo)) + d.payva as arval')
            )
            ->get();

        $invp->setRelation('invoicepaymentdtls', $details);

        return view('fna.invoice_payment.invoice_payment_detail',compact('invp'));
    }

    public function edit(string $id){
        $invp = InvoicePaymentHdr::with([
            'mbranch',
            'mformcode',
            'mcusmas',
            'msreno'
        ])->findOrFail($id);

        $details = DB::table('tpayind as d')
            ->leftJoin('mcusmas as c', 'c.cusno', '=', 'd.cusno')
            ->leftJoin('tinmas as t', function ($join) {
                $join->on('t.formc', '=', 'd.invfc')
                    ->on('t.invno', '=', 'd.invrn');
            })
            ->where('d.invpid', $id)
            ->select(
                'd.*',
                'c.cusna',
                't.duedt',
                't.blamt',
                't.caval',
                't.cramt',
                't.recwo',
                DB::raw('(t.blamt - (t.caval + t.recwo)) + d.payva as arval')
            )
            ->get();

        $invp->setRelation('invoicepaymentdtls', $details);

        return view('fna.invoice_payment.invoice_payment_edit', compact('invp'));
    }

    public function update(Request $request, $id)
    {
        DB::beginTransaction();

        // dd($request->all());

        try {

            $hdr = InvoicePaymentHdr::where('invpid', $id)->firstOrFail();

            // UPDATE HEADER
            $hdr->update([
                'noteh'      => $request->noteh,
                'updated_at' => now(),
                'updated_by' => Auth::user()->name,
            ]);

            InvoicePaymentDtl::where('invpid', $id)->delete();

            // INSERT DETAIL BARU
            foreach ($request->invrn as $i => $invrn) {

                $pcval = $request->pcval[$i] ?? 0;
                $pcwo  = $request->pcwo[$i] ?? 0;
                $payva = $pcval + $pcwo;

                InvoicePaymentDtl::create([
                    'invpid' => $id,
                    'braco'  => $request->braco,
                    'formc'  => $request->formc,
                    'vcrno'  => $hdr->vcrno,
                    'iorno'  => $hdr->iorno,

                    'cusno'  => $request->cusno[$i],
                    'invfc'  => $request->formc_inv[$i],
                    'invrn'  => $request->invrn[$i],

                    'pcval'  => $pcval,
                    'pcwo'   => $pcwo,
                    'payva'  => $payva,
                    'noted'  => $request->noted[$i] ?? null,
                ]);

                DB::table('tinmas')
                    ->where('braco', $request->braco)
                    ->where('formc', $request->formc_inv[$i])
                    ->where('invno', $request->invrn[$i])
                    ->update([
                        'caval' => $pcval,
                        'recwo' => $pcwo,
                    ]);
            }

            DB::commit();

            return redirect()
                ->route('invoice_payment.index')
                ->with('success', "Data Invoice Payment \"$id\" berhasil diupdate.");

        } catch (\Exception $e) {

            DB::rollBack();

            return back()->with('error', $e->getMessage());
        }
    }

    public function generateInvoicePaymentNo(Request $request)
    {
        $braco = auth()->user()->cabang;
        $formc = $request->formc;
        $pdate = $request->pdate;

        $year = Carbon::parse($pdate)->format('y');

        $last = DB::table('tpayinh')
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

        return $year . $running . '/' . $braco;
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
                'h.cuspo as cuspo',
                'h.sreno as sreno',
                'h.curco as curco',
                'h.crate as crate',
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