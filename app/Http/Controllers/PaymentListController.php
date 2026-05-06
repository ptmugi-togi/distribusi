<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

use App\Models\Mbranch;

class PaymentListController extends Controller
{
    public function create()
    {
        $braco = Auth::user()->cabang;

        return view('fna.reports.payment_list.payment_list_create' , compact('braco'));
    }

    public function getData(Request $req)
    {
        $braco = Auth::user()->cabang;
        $start = $req->pdate_s;
        $end   = $req->pdate_e;

        $data = DB::table('tpayind as d')
            ->join('tpayinh as h', 'd.invpid', '=', 'h.invpid')
            ->leftJoin('mcusmas as c', 'd.cusno', '=', 'c.cusno')
            ->leftJoin('tinmas as t', function ($join) {
                $join->on('t.formc', '=', 'd.invfc')
                    ->on('t.invno', '=', 'd.invrn');
            })
            ->where('h.braco', $braco)
            ->whereBetween('h.pdate',[$start,$end])
            ->select([
                'h.invpid as invpid',
                'h.formc as formc',
                'h.vcrno as vcrno',
                'h.iorno as iorno',
                'h.pdate as pdate',
                'h.tpaye as tpaye',
                'h.curco as curco',
                'h.prate as prate',
                'h.noteh as noteh',
                'd.invfc as invfc',
                'd.invrn as invrn',
                'd.pcval as pcval',
                'd.pcwo as pcwo',
                'd.payva as payva',
                'd.noted as noted',
                'd.cusno as cusno',
                'c.cusna as cusna',
                't.blamt as blamt',
            ])
            ->orderBy('h.vcrno')
            ->orderBy('d.invrn')
            ->get()
            ->groupBy('vcrno');

        return $data;
    }

    public function previewPaymentList(Request $req)
    {
        $data = $this->getData($req);
        $branch = Mbranch::where('braco', Auth::user()->cabang)->first();

        $html = view('fna.reports.payment_list.payment_list_preview',[
            'items'=>$data,
            'start'=>$req->pdate_s,
            'end'=>$req->pdate_e,
            'braco'=>Auth::user()->cabang,
            'brana' => $branch->brana ?? ''
        ])->render();

        $mpdf = new \Mpdf\Mpdf([
            'format'=>'A4-L',
            'margin_top'=>30,
            'margin_bottom'=>20
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