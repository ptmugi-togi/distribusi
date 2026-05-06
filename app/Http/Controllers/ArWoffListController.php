<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

use App\Models\Mbranch;

class ArWoffListController extends Controller
{
    public function create()
    {
        $braco = Auth::user()->cabang;

        return view('fna.reports.ar_woff_list.ar_woff_list_create' , compact('braco'));
    }

    public function getData(Request $req)
    {
        $braco = Auth::user()->cabang;
        $start = $req->tradt_s;
        $end   = $req->tradt_e;

        $data = DB::table('twoffd as d')
            ->join('twoffh as h', 'd.woffid', '=', 'h.woffid')
            ->leftJoin('mcusmas as c', 'd.cusno', '=', 'c.cusno')
            ->where('h.braco', $braco)
            ->whereBetween('h.tradt',[$start,$end])
            ->select([
                'h.woffid as woffid',
                'h.formc as formc',
                'h.vcrno as vcrno',
                'h.tradt as tradt',
                'h.refno as refno',
                'h.noteh as noteh',
                'd.invfc as invfc',
                'd.invrn as invrn',
                'd.trval as trval',
                'd.curco as curco',
                'd.irate as irate',
                'd.cusno as cusno',
                'd.noted as noted',
                'c.cusna as cusna',
            ])
            ->orderBy('h.vcrno')
            ->orderBy('d.invrn')
            ->get()
            ->groupBy('vcrno');

        return $data;
    }

    public function previewArWoffList(Request $req)
    {
        $data = $this->getData($req);
        $branch = Mbranch::where('braco', Auth::user()->cabang)->first();

        $html = view('fna.reports.ar_woff_list.ar_woff_list_preview',[
            'items'=>$data,
            'start'=>$req->tradt_s,
            'end'=>$req->tradt_e,
            'braco'=>Auth::user()->cabang,
            'brana' => $branch->brana ?? ''
        ])->render();

        $mpdf = new \Mpdf\Mpdf([
            'format'=>'A4',
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