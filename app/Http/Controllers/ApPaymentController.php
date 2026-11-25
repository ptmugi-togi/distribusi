<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use illuminate\Support\Facades\Log;
use Carbon\Carbon;

use App\Models\ApPaymentHdr;
use App\Models\ApPaymentDtl;
use App\Models\Mcurco;

class ApPaymentController extends Controller
{
    public function index() {
        $aphdr = ApPaymentHdr::with('supplier')->get();

        $latestPeriod = DB::table('tperiode')
            ->where('braco', Auth::user()->cabang)
            ->orderByDesc('periode')
            ->first();

        $periodClosed = $latestPeriod && $latestPeriod->status === 'C';

        return view('fna.ap_payment.ap_payment_index', compact('aphdr', 'periodClosed'));
    }

    public function create() {
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

        $currency = DB::table('mcurco_tbl')->get();

        $supplier = DB::table('mvendor_tbl')->get();

        $mbank = DB::table('mbank')->get();

        return view('fna.ap_payment.ap_payment_create', compact('minDate', 'currency', 'supplier', 'mbank'));
    }

    public function store(Request $request)
    {
        // dd($request->all());
        DB::beginTransaction();

        try {
            $braco = $request->braco;
            $formc = $request->formc;
            $year  = Carbon::parse($request->pdate)->format('y');

            $last = ApPaymentHdr::where('braco', $braco)
                ->where('formc', $formc)
                ->whereRaw('LEFT(vcrno, 2) = ?', [$year])
                ->lockForUpdate()
                ->orderByDesc('vcrno')
                ->value('vcrno');

            $number = $last ? ((int) substr($last, 2) + 1) : 1;
            $vcrno  = $year . str_pad($number, 4, '0', STR_PAD_LEFT);
            $apid   = $braco . $formc . $vcrno;

            $header = ApPaymentHdr::create([
                'apid'            => $apid,
                'braco'           => $braco,
                'formc'           => $formc,
                'bracoformc'      => $braco . $formc,
                'vcrno'           => $vcrno,
                'pdate'           => $request->pdate,
                'priod'           => $request->priod,
                'supno'           => $request->supno,
                'curco'           => $request->curco,
                'payrate'         => $request->crate,
                'tpayment'        => $request->tpayment,
                'tpayment_detail' => $request->tpayment_detail,
                'glacc'           => $request->glacc,
                'bankname'        => $request->bankname,
                'noteh'           => $request->noteh,
                'created_by'      => Auth::user()->name ?? Auth::id(),
                'created_at'      => now(),
                'updated_by'      => Auth::user()->name ?? Auth::id(),
                'updated_at'      => now(),
            ]);

            foreach ($request->invno as $i => $invno) {
                $invno     = trim($invno);
                $payAmount = (float) $request->pay_amount[$i];

                $invoice = DB::table('tsupih_tbl')
                    ->whereRaw('TRIM(invno) = ?', [$invno])
                    ->whereRaw('TRIM(supno) = ?', [trim($request->supno)])
                    ->lockForUpdate()
                    ->first();

                if (!$invoice) {
                    throw new \Exception("Invoice {$invno} tidak ditemukan di tsupih_tbl.");
                }

                $currentPaid = (float) ($invoice->payment ?? 0);
                $sisaTagihan = round((float) $invoice->amount - $currentPaid, 2);

                if ($payAmount > $sisaTagihan) {
                    throw new \Exception("Jumlah bayar invoice {$invno} ({$payAmount}) melebihi sisa tagihan ({$sisaTagihan}).");
                }

                ApPaymentDtl::create([
                    'apid'       => $apid,
                    'braco'      => $braco,
                    'formc'      => $formc,
                    'vcrno'      => $vcrno,
                    'invno'      => $invno,
                    'curco'      => $request->curco_inv[$i],
                    'invrate'    => (float) $request->invrate[$i],
                    'payrate'    => (float) $request->payrate[$i],
                    'payval'     => $payAmount,
                    'totalidr'   => (float) $request->pay_idr[$i],
                    'noted'      => $request->noted[$i] ?? null,
                ]);

                DB::table('tsupih_tbl')
                    ->whereRaw('TRIM(invno) = ?', [$invno])
                    ->whereRaw('TRIM(supno) = ?', [trim($request->supno)])
                    ->update([
                        'payment' => $currentPaid + $payAmount,
                    ]);
            }

            DB::commit();

            return redirect()
                ->route('ap_payment.index')
                ->with('success', "Data AP Payment ({$apid}) berhasil disimpan!");

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error Store AP Payment: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
            ]);

            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Gagal menyimpan AP Payment');
        }
    }

    public function show(string $id)
    {
        $header = ApPaymentHdr::with('details','supplier')
            ->where('apid', $id)
            ->firstOrFail();

        return view('fna.ap_payment.ap_payment_detail', compact('header'));
    }

    public function edit(string $id)
    {
        $header = ApPaymentHdr::with(['details', 'supplier'])
            ->where('apid', $id)
            ->firstOrFail();

        $currency = DB::table('mcurco_tbl')->get();
        $supplier = DB::table('mvendor_tbl')->get();$mbank    = DB::table('mbank')->get();

        $existingInvNos = $header->details->pluck('invno')->map(fn($v) => trim($v))->toArray();$invoicesMap = DB::table('tsupih_tbl')
            ->whereIn(DB::raw('TRIM(invno)'), $existingInvNos)
            ->whereRaw('TRIM(supno) = ?', [trim($header->supno)])
            ->get()
            ->keyBy(fn($item) => trim($item->invno));

        return view('fna.ap_payment.ap_payment_edit', compact('header', 'currency', 'supplier', 'mbank', 'invoicesMap'));
    }

    public function update(Request $request, string $id)
    {
        DB::beginTransaction();

        try {
            $header = ApPaymentHdr::with('details')
                ->where('apid', $id)
                ->lockForUpdate()
                ->firstOrFail();

            $supno = trim($header->supno);

            foreach ($header->details as $oldDetail) {
                $oldInvno = trim($oldDetail->invno);
                $oldPay   = (float) $oldDetail->payval;

                $oldInvoice = DB::table('tsupih_tbl')
                    ->whereRaw('TRIM(invno) = ?', [$oldInvno])
                    ->whereRaw('TRIM(supno) = ?', [$supno])
                    ->lockForUpdate()
                    ->first();

                if ($oldInvoice) {
                    $revertedPaid = max(0, round((float) ($oldInvoice->payment ?? 0) - $oldPay, 2));
                    DB::table('tsupih_tbl')
                        ->whereRaw('TRIM(invno) = ?', [$oldInvno])
                        ->whereRaw('TRIM(supno) = ?', [$supno])
                        ->update(['payment' => $revertedPaid]);
                }
            }

            ApPaymentDtl::where('apid', $id)->delete();

            $header->update([
                'tpayment'        => (float) $request->tpayment,
                'tpayment_detail' => (float) $request->tpayment_detail,
                'noteh'           => $request->noteh,
                'updated_by'      => Auth::user()->name ?? Auth::id(),
                'updated_at'      => now(),
            ]);

            foreach ($request->invno as $i => $invno) {
                $invno     = trim($invno);
                $payAmount = (float) $request->pay_amount[$i];

                $invoice = DB::table('tsupih_tbl')
                    ->whereRaw('TRIM(invno) = ?', [$invno])
                    ->whereRaw('TRIM(supno) = ?', [$supno])
                    ->lockForUpdate()
                    ->first();

                if (!$invoice) {
                    throw new \Exception("Invoice {$invno} tidak ditemukan di tsupih_tbl.");
                }

                $currentPaid = (float) ($invoice->payment ?? 0);
                $sisaTagihan = round((float) $invoice->amount - $currentPaid, 2);

                if ($payAmount > $sisaTagihan) {
                    throw new \Exception("Jumlah bayar invoice {$invno} ({$payAmount}) melebihi sisa tagihan ({$sisaTagihan}).");
                }

                ApPaymentDtl::create([
                    'apid'     => $header->apid,
                    'braco'    => $header->braco,
                    'formc'    => $header->formc,
                    'vcrno'    => $header->vcrno,
                    'invno'    => $invno,
                    'curco'    => $request->curco_inv[$i],
                    'invrate'  => (float) $request->invrate[$i],
                    'payrate'  => (float) $request->payrate[$i],
                    'payval'   => $payAmount,
                    'totalidr' => (float) $request->pay_idr[$i],
                    'noted'    => $request->noted[$i] ?? null,
                ]);

                DB::table('tsupih_tbl')
                    ->whereRaw('TRIM(invno) = ?', [$invno])
                    ->whereRaw('TRIM(supno) = ?', [$supno])
                    ->update([
                        'payment' => $currentPaid + $payAmount,
                    ]);
            }

            DB::commit();

            return redirect()
                ->route('ap_payment.index')
                ->with('success', "Data AP Payment ({$header->apid}) berhasil diperbarui!");

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error Update AP Payment: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
            ]);

            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Gagal memperbarui AP Payment: ' . $e->getMessage());
        }
    }

    public function getCurrencyRate($curco)
    {
        $currency = Mcurco::find($curco);

        if ($currency) {
            return response()->json([
                'success' => true,
                'crate' => $currency->crate,
            ]);
        }

        return response()->json(['success' => false], 404);
    }

    public function getInvoicesBySupplier($supno)
    {
        $invoices = DB::table('tsupih_tbl')
            ->leftJoin('mcurco_tbl', 'tsupih_tbl.curco', '=', 'mcurco_tbl.curco')
            ->where('tsupih_tbl.supno', $supno)
            ->whereRaw('COALESCE(tsupih_tbl.payment, 0) < COALESCE(tsupih_tbl.amount, 0)')
            ->select(
                'tsupih_tbl.invno',
                'tsupih_tbl.curco as curco_inv',
                'tsupih_tbl.crate as invrate',
                'tsupih_tbl.amount as total_amount',
                DB::raw('COALESCE(mcurco_tbl.crate, 1) as payrate')
            )
            ->get();

        return response()->json([
            'success' => true,
            'data' => $invoices
        ]);
    }
}