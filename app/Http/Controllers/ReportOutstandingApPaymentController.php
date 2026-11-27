<?php

namespace App\Http\Controllers;

use App\Models\Mbranch;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ReportOutstandingApPaymentController extends Controller
{
    public function index()
    {
        $suppliers = DB::table('mvendor_tbl')
            ->orderBy('supno', 'asc')
            ->get();

        return view('fna.reports.outstanding_ap_payment.outstanding_ap_payment_create', compact('suppliers'));
    }

    private function getActivePeriod(string $braco)
    {
        return DB::table('tperiode')
            ->whereRaw('TRIM(braco) = ?', [$braco])
            ->whereRaw("TRIM(status) = 'O'")
            ->first();
    }

    private function getData(Request $request)
    {
        $braco  = trim($request->braco ?? Auth::user()->cabang);
        $supno  = trim($request->supno ?? '');
        $period = $this->getActivePeriod($braco);

        $activePeriode = $period ? trim($period->periode) : date('Ym');

        $paymentSub = DB::table('tpayaph as hdr')
            ->join('tpayapd as dtl', function ($join) {$join->on(DB::raw('TRIM(hdr.apid)'), '=', DB::raw('TRIM(dtl.apid)'));
            })
            ->whereRaw('TRIM(hdr.braco) = ?', [$braco])
            ->whereRaw('TRIM(hdr.priod) = ?', [$activePeriode])
            ->select(
                DB::raw('TRIM(hdr.supno) as supno'),
                DB::raw('TRIM(dtl.invno) as invno'),
                'hdr.formc as pay_formc',
                'hdr.vcrno as pay_vcrno',
                'hdr.pdate as pay_date',
                'hdr.curco as curco_pay',
                'dtl.payrate',
                'dtl.payval',
                'dtl.totalidr'
            );

        return DB::table('tsupih_tbl as inv')
            ->leftJoin('mvendor_tbl as sup', function ($join) {$join->on(DB::raw('TRIM(inv.supno)'), '=', DB::raw('TRIM(sup.supno)'));
            })
            ->leftJoinSub($paymentSub, 'pay', function ($join) {$join->on(DB::raw('TRIM(inv.supno)'), '=', 'pay.supno')
                ->on(DB::raw('TRIM(inv.invno)'), '=', 'pay.invno');
            })
            ->whereRaw('TRIM(inv.braco) = ?', [$braco])
            ->where(function ($query) {
                $query->whereRaw('(COALESCE(inv.amount, 0) - COALESCE(inv.payment, 0)) > 0')
                ->orWhereNotNull('pay.pay_vcrno');
            })
            ->when($supno !== '', function ($q) use ($supno) {
                $q->whereRaw('TRIM(inv.supno) = ?', [$supno]);
            })
            ->select(
                'inv.supno',
                DB::raw('COALESCE(sup.supna, inv.supno) as supna'),
                'inv.invno',
                'inv.invdt',
                DB::raw('COALESCE(inv.curco, "IDR") as curco_inv'),
                DB::raw('COALESCE(inv.crate, 1) as invrate'),
                DB::raw('COALESCE(inv.amount, 0) as inv_amount'),
                DB::raw('COALESCE(inv.payment, 0) as inv_paid'),
                'pay.pay_formc',
                'pay.pay_vcrno',
                'pay.pay_date',
                'pay.curco_pay',
                DB::raw('COALESCE(pay.payrate, 0) as payrate'),
                DB::raw('COALESCE(pay.payval, 0) as payval'),
                DB::raw('COALESCE(pay.totalidr, 0) as totalidr')
            )
            ->orderBy('inv.supno', 'asc')
            ->orderBy('inv.invdt', 'asc')
            ->orderBy('inv.invno', 'asc')
            ->orderBy('pay.pay_date', 'asc')
            ->orderBy('pay.pay_vcrno', 'asc')
            ->get();
    }

    public function preview(Request $request)
    {
        $braco  = trim($request->braco ?? Auth::user()->cabang);
        $data   =$this->getData($request);$branch = Mbranch::where('braco', $braco)->first();$period = $this->getActivePeriod($braco);

        $rawPeriode = $period ? trim($period->periode) : date('Ym');
        $formattedPeriode = strlen($rawPeriode) === 6
            ? substr($rawPeriode, 4, 2) . '-' . substr($rawPeriode, 0, 4)
            : $rawPeriode;

        $html = view('fna.reports.outstanding_ap_payment.outstanding_ap_payment_preview', [
            'items'   => $data,
            'braco'   => $braco,
            'brana'   => $branch->brana ?? '',
            'periode' => $formattedPeriode,
        ])->render();

        $mpdf = new \Mpdf\Mpdf([
            'format'        => 'A4-L',
            'margin_top'    => 25,
            'margin_bottom' => 20,
            'margin_left'   => 6,
            'margin_right'  => 6,
        ]);

        $mpdf->SetHTMLFooter('
            <div style="text-align:right; font-size:9pt;">
                {PAGENO}/{nbpg}
            </div>
        ');

        $mpdf->WriteHTML($html);$mpdf->Output('Outstanding_Payment.pdf', 'I');
    }
}