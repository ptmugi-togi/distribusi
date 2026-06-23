<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

use App\Models\Mbranch;

class AgingArByInvoiceController extends Controller
{
    public function create()
    {
        $braco = Auth::user()->cabang;

        $customers = DB::table('mcusmas')->where('braco', $braco)->orderBy('cusno')->get();

        return view('fna.reports.aging_ar_by_invoice.aging_ar_by_invoice_create' , compact('braco', 'customers'));
    }


    public function getData(Request $req)
    {
        $braco = Auth::user()->cabang;
        $asper = $req->asper;

        $data = DB::table('tinmas as h')
            ->leftJoin('mcusmas as c', 'h.cusno', '=', 'c.cusno')
            ->where('h.braco', $braco)
            ->when($req->asper, function ($q) use ($req) {
                $q->whereDate('h.invdt', '<=', $req->asper);
            })
            ->when($req->formc, function ($q) use ($req) {
                $q->where('h.formc', $req->formc);
            })
            ->when($req->cusno, function ($q) use ($req) {
                $q->where('h.cusno', $req->cusno);
            })
            ->whereRaw('(COALESCE(h.blamt,0)- COALESCE(h.caval,0)- COALESCE(h.recwo,0)- COALESCE(h.cramt,0)) > 0')

            ->select([
                'h.cusno',
                'c.cusna',
                'h.formc',
                'h.invno',
                'h.invdt',
                'h.duedt',
                'h.sreno',
                'h.curco',
                'h.crate',
                DB::raw('COALESCE((COALESCE(h.blamt,0) - COALESCE(h.caval,0) - COALESCE(h.recwo,0) - COALESCE(h.cramt,0)) * COALESCE(h.crate,1),0) as osamt'),
                DB::raw("DATEDIFF('$asper', h.duedt) as overdays")
            ])

            ->orderBy('h.cusno')
            ->orderBy('h.duedt')
            ->get();

        return $data;
    }

    public function previewAgingArByInvoiceList(Request $req)
    {
        $data = $this->getData($req);
        $branch = Mbranch::where('braco', Auth::user()->cabang)->first();

        $html = view('fna.reports.aging_ar_by_invoice.aging_ar_by_invoice_preview',[
            'items'=>$data,
            'asper'=>$req->asper,
            'braco'=>Auth::user()->cabang,
            'brana' => $branch->brana ?? ''
        ])->render();

        $mpdf = new \Mpdf\Mpdf([
            'format'=>'A4-L',
            'margin_top'=>25,
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