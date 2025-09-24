<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TpoHdr;
use Mpdf\Mpdf;

class PdfController extends Controller
{
    public function preview($id)
    {
        $tpohdr = \App\Models\TpoHdr::with([
            'vendor',
            'tpodtl.mpromas',
            'formcode',
            'branches'
        ])->findOrFail($id);

        $html = view('purchasing.tpo.tpo_pdf', compact('tpohdr'))->render();

        $mpdf = new \Mpdf\Mpdf([
            'format' => 'A4',
            'margin_top' => 10,
            'margin_bottom' => 15,
        ]);
        
        $mpdf->SetHTMLFooter('
            <div style="text-align: right; font-size: 9pt;">
                {PAGENO}/{nbpg}
            </div>
        ');

        $mpdf->WriteHTML($html);
        $mpdf->Output(); 
    }

    public function download($id)
    {
        $tpohdr = \App\Models\TpoHdr::with([
            'vendor',
            'tpodtl.mpromas',
            'formcode',
            'branches'
        ])->findOrFail($id);

        $html = view('purchasing.tpo.tpo_pdf', compact('tpohdr'))->render();

        $mpdf = new \Mpdf\Mpdf([
            'format' => 'A4',
            'margin_top' => 10,
            'margin_bottom' => 15,
        ]);

        $mpdf->SetHTMLFooter('
            <div style="text-align: right; font-size: 9pt;">
                {PAGENO}/{nbpg}
            </div>
        ');

        $mpdf->WriteHTML($html);
        $mpdf->Output("PO-{$tpohdr->pono}.pdf", "D");
    }
}
