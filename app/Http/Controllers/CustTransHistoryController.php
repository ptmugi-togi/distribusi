<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\Mbranch;

class CustTransHistoryController extends Controller
{
    public function create()
    {
        $braco = Auth::user()->cabang;

        $customers = DB::table('mcusmas')
            ->where('braco', $braco)
            ->orderBy('cusno')
            ->get();

        return view('fna.reports.cust_trans_history.cust_trans_history_create', compact('braco', 'customers'));
    }

    public function getData(Request $req)
    {
        $braco = Auth::user()->cabang;

        $data = DB::table('tinmas as h')
            ->leftJoin('mcusmas as c', function ($join) {
                $join->on('h.braco', '=', 'c.braco')
                    ->on('h.cusno', '=', 'c.cusno');
            })
            ->leftJoin('tpayind as pd', function ($join) {
                $join->on('h.braco', '=', 'pd.braco')
                    ->on('h.formc', '=', 'pd.invfc')
                    ->on('h.invno', '=', 'pd.invrn');
            })
            ->leftJoin('tpayinh as ph', function ($join) {
                $join->on('pd.braco', '=', 'ph.braco')
                    ->on('pd.vcrno', '=', 'ph.vcrno');
            })
            ->leftJoin(DB::raw("
                (
                    SELECT
                        wd.braco,
                        wd.invfc,
                        wd.invrn,

                        MAX(wh.formc) as wo,
                        MAX(wh.vcrno) as wono,
                        MAX(wh.tradt) as wodat,

                        SUM(COALESCE(wd.trval,0)) as writeoff_amount,
                        MAX(COALESCE(wd.irate,1)) as irate

                    FROM twoffd wd

                    LEFT JOIN twoffh wh
                        ON wd.braco = wh.braco
                        AND wd.vcrno = wh.vcrno

                    GROUP BY
                        wd.braco,
                        wd.invfc,
                        wd.invrn

                ) as wo
            "), function ($join) {
                $join->on('h.braco', '=', 'wo.braco')
                    ->on('h.formc', '=', 'wo.invfc')
                    ->on('h.invno', '=', 'wo.invrn');
            })
            ->where('h.braco', $braco)
            ->when($req->cusno, function ($q) use ($req) {
                $q->where('h.cusno', $req->cusno);
            })
            ->when($req->outs, function ($q) use ($req) {
                if ($req->outs == 'Yes') {
                    $q->whereRaw('COALESCE(h.blamt,0) - COALESCE(h.caval,0) - COALESCE(h.recwo,0) - COALESCE(h.cramt,0) > 0');
                }
                if ($req->outs == 'No') {
                    $q->whereRaw('COALESCE(h.blamt,0) - COALESCE(h.caval,0) - COALESCE(h.recwo,0) - COALESCE(h.cramt,0) = 0');
                }
            })

            ->select([

                'h.cusno',
                'c.cusna',

                'h.formc',
                'h.invno',
                'h.invdt',
                'h.curco',
                'h.crate',

                DB::raw('h.blamt as invoice_amount'),

                DB::raw('(h.blamt * h.crate) as invoice_rp'),

                'pd.vcrno',
                'ph.pdate as payment_date',

                DB::raw('DATEDIFF(ph.pdate, h.invdt)as hari'),

                DB::raw('COALESCE(pd.payva, 0)as payment_amount'),

                DB::raw('(COALESCE(pd.payva, 0)* h.crate) as payment_rp'),

                DB::raw('0 as creditnote_amount'),
                DB::raw('0 as creditnote_rp'),

                'wo.wo',
                'wo.wono',
                'wo.wodat',

                DB::raw('COALESCE(wo.writeoff_amount, 0)as writeoff_amount'),

                DB::raw('(COALESCE(wo.writeoff_amount, 0)* COALESCE(wo.irate,1)) as writeoff_rp'),

                DB::raw('(COALESCE(h.blamt,0) - COALESCE(h.caval,0) - COALESCE(h.cramt,0) - COALESCE(h.recwo,0)) as ending_balance'),

                DB::raw('((COALESCE(h.blamt,0) - COALESCE(h.caval,0) - COALESCE(h.cramt,0) - COALESCE(h.recwo,0)) * h.crate) as balance_rp'),
            ])

            ->orderBy('h.cusno')
            ->orderBy('h.invdt')
            ->orderBy('h.invno')
            ->orderBy('ph.pdate')

            ->get();

        return $data;
    }

    public function previewCustTransHistory(Request $req)
    {
        $data = $this->getData($req);
        $branch = Mbranch::where('braco', Auth::user()->cabang)->first();
        $customer = $data->first();

        $html = view('fna.reports.cust_trans_history.cust_trans_history_preview', [
            'items' => $data,
            'customer' => $customer,
            'asper' => $req->asper,
            'braco' => Auth::user()->cabang,
            'brana' => $branch->brana ?? '',
        ])->render();

        $mpdf = new \Mpdf\Mpdf([
            'format' => 'A4-L',
            'margin_top' => 25,
            'margin_bottom' => 20,
            'margin_left' => 5,
            'margin_right' => 5,
        ]);

        $mpdf->SetHTMLFooter('
            <div style="text-align:right; font-size:9pt;">
                {PAGENO}/{nbpg}
            </div>
        ');

        $mpdf->WriteHTML($html);
        $mpdf->Output();
    }
}