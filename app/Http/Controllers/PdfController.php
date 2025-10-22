<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\TpoHdr;
use Mpdf\Mpdf;

class PdfController extends Controller
{
    // sementara dinonaktifkan prieview
    public function preview($id)
    {
        $tpohdr = \App\Models\TpoHdr::with([
            'vendor',
            'tpodtl.mpromas',
            'formcode',
            'branches'
        ])->findOrFail($id);

        $html = view('purchasing.tpo.pdf.tpo_pdf', compact('tpohdr'))->render();

        $mpdf = new \Mpdf\Mpdf([
            'format' => 'A4',
            'margin_top' => 10,
            'margin_bottom' => 10,
        ]);
        
        $mpdf->WriteHTML($html);

        $mpdf->SetHTMLFooterByName('myFooter', 'E_ALL');

        $mpdf->Output(); 
    }

    // sementara dinonaktifkan prieview
    public function previewPi($id)
    {
        $tpohdr = \App\Models\TpoHdr::with([
            'vendor',
            'tpodtl.mpromas',
            'formcode',
            'branches'
        ])->findOrFail($id);

        $html = view('purchasing.tpo.pdf.tpo_pdf_pi', compact('tpohdr'))->render();

        $mpdf = new \Mpdf\Mpdf([
            'format' => 'A4',
            'margin_top' => 10,
            'margin_bottom' => 10,
        ]);

        $mpdf->WriteHTML($html);

        $mpdf->SetHTMLFooterByName('myFooter', 'E_ALL');

        $mpdf->Output(); 
    }

    // counter print
    public function print($pono) {
        $tpohdr = Tpohdr::where('pono', $pono)->firstOrFail();

        // increment counter total print
        DB::table('pohdr_tbl')
        ->where('pono', $pono)
        ->update([
            'prctr' => DB::raw('prctr + 1')
        ]);

        $html = view('purchasing.tpo.pdf.tpo_pdf', compact('tpohdr'))->render();

        
        $mpdf = new Mpdf();
        $mpdf->WriteHTML($html);
        $mpdf->SetHTMLFooterByName('myFooter', 'E_ALL');

        $pdfContent = $mpdf->Output("{$tpohdr->potype}-{$tpohdr->pono}.pdf", "S");

        return response($pdfContent)
        ->header('Content-Type', 'application/pdf')
        ->header('Content-Disposition', 'attachment; filename="'.$tpohdr->formc.'-'.$tpohdr->pono.'.pdf"');
    }

    public function printPi($pono) {
        $tpohdr = Tpohdr::where('pono', $pono)->firstOrFail();

        // increment counter total print
        DB::table('pohdr_tbl')
        ->where('pono', $pono)
        ->update([
            'prctr' => DB::raw('prctr + 1')
        ]);

        $html = view('purchasing.tpo.pdf.tpo_pdf_pi', compact('tpohdr'))->render();

        $mpdf = new Mpdf();
        $mpdf->WriteHTML($html);
        $mpdf->SetHTMLFooterByName('myFooter', 'E_ALL');

        $pdfContent = $mpdf->output("PI-{$tpohdr->pono}.pdf", "S");

        return response($pdfContent)
            ->header('Content-Type', 'application/pdf')
            ->header('Content-Disposition', 'attachment; filename="PI-'.$tpohdr->pono.'.pdf"');
    }
}
