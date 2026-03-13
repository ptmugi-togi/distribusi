<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\TpoHdr;
use App\Models\BbmHdr;
use App\Models\BbkHdr;
use App\Models\BpbHdr;
use App\Models\TaHdr;
use App\Models\WoHdr;
use App\Models\MstMas;
use App\Models\Mcindu;
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

        $mpdf->SetHTMLFooter('
            <div style="text-align:right; font-size:9pt;">
                {PAGENO}/{nbpg}
            </div>
        ');

        
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

        $mpdf->SetHTMLFooter('
            <div style="text-align:right; font-size:9pt;">
                {PAGENO}/{nbpg}
            </div>
        ');

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
        $mpdf->SetHTMLFooter('
            <div style="text-align:right; font-size:9pt;">
                {PAGENO}/{nbpg}
            </div>
        ');
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
        $mpdf->SetHTMLFooter('
            <div style="text-align:right; font-size:9pt;">
                {PAGENO}/{nbpg}
            </div>
        ');
        $mpdf->WriteHTML($html);
        $mpdf->SetHTMLFooterByName('myFooter', 'E_ALL');

        $pdfContent = $mpdf->output("PI-{$tpohdr->pono}.pdf", "S");

        return response($pdfContent)
            ->header('Content-Type', 'application/pdf')
            ->header('Content-Disposition', 'attachment; filename="PI-'.$tpohdr->pono.'.pdf"');
    }

    public function previewBbm($id)
    {
        $bbmhdr = \App\Models\BbmHdr::with([
            'bbmdtls.mpromas',
            'tsupih',
            'mformcode',
            'vendor',
            'tbolh',
            'mbranches',
            'bbmdtls.bbmhdr',
            'referenceHeader',
            'wo',
            'wodtl'

        ])->findOrFail($id);

        $tahdr = \DB::table('tsisnh AS t')
            ->leftJoin('mbranches AS m', 'm.braco', '=', 't.braco')
            ->select(
                't.*',
                'm.address'
            )
            ->where('t.rfc01', $bbmhdr->reffc)
            ->where('t.ref01', $bbmhdr->refno)
            ->first();

        $bbmdtls = collect($bbmhdr->bbmdtls)->groupBy(function($i){
            return implode('|', [
                $i->opron,
                $i->mpromas->brand_name,
                $i->mpromas->prona,
                $i->pono,
                $i->invno,
                $i->locco,
                trim($i->noted)
            ]);
        })->map(function($group){
            $first = $group->first();
            $first->lotno_merged = implode(', ', $group->pluck('lotno')->toArray());
            $first->trqty = $group->sum('trqty'); //sum jika sama hanya beda sn
            return $first;
        });

        $html = view('logistic.bbm.bbm_print', [
            'bbmhdr' => $bbmhdr,
            'bbmdtls' => $bbmdtls,
            'tahdr' => $tahdr
        ])->render();

        $mpdf = new \Mpdf\Mpdf([
            'format' => 'A4',
            'margin_top' => 10,
            'margin_bottom' => 10,
        ]);

        $mpdf->SetHTMLFooter('
            <div style="text-align:right; font-size:9pt;">
                {PAGENO}/{nbpg}
            </div>
        ');

        $mpdf->WriteHTML($html);

        $mpdf->SetHTMLFooterByName('myFooter', 'E_ALL');

        $mpdf->Output(); 
    }

    public function printBbm($id)
    {
        $bbmhdr = \App\Models\BbmHdr::with([
            'bbmdtls.mpromas',
            'tsupih',
            'mformcode',
            'vendor',
            'tbolh',
            'mbranches',
            'bbmdtls.bbmhdr',
            'referenceHeader',
            'wo',
            'wodtl'
        ])->findOrFail($id);

        $tahdr = \DB::table('tsisnh AS t')
            ->leftJoin('mbranches AS m', 'm.braco', '=', 't.braco')
            ->select(
                't.*',
                'm.address'
            )
            ->where('t.rfc01', $bbmhdr->reffc)
            ->where('t.ref01', $bbmhdr->refno)
            ->first();

        // increment counter print
        DB::table('tstorh')
            ->where('bbmid', $id)
            ->update([
                'prctr' => DB::raw('prctr + 1')
            ]);

        $bbmdtls = collect($bbmhdr->bbmdtls)->groupBy(function($i){
            return implode('|', [
                $i->opron,
                $i->mpromas->brand_name,
                $i->mpromas->prona,
                $i->pono,
                $i->invno,
                $i->locco,
                trim($i->noted)
            ]);
        })->map(function($group){
            $first = $group->first();
            $first->lotno_merged = implode(', ', $group->pluck('lotno')->toArray());
            $first->trqty = $group->sum('trqty'); //sum jika sama hanya beda sn
            return $first;
        });

        $html = view('logistic.bbm.bbm_print', [
            'bbmhdr' => $bbmhdr,
            'bbmdtls' => $bbmdtls,
            'tahdr' => $tahdr
        ])->render();

        $mpdf = new \Mpdf\Mpdf([
            'format' => 'A4',
            'margin_top' => 10,
            'margin_bottom' => 10,
        ]);

        $mpdf->SetHTMLFooter('
            <div style="text-align:right; font-size:9pt;">
                {PAGENO}/{nbpg}
            </div>
        ');
        $mpdf->WriteHTML($html);
        $mpdf->SetHTMLFooterByName('myFooter', 'E_ALL');

        // save PDF jadi string
        $filename = $bbmhdr->braco.'-'.$bbmhdr->formc.$bbmhdr->trano.'.pdf';
        $pdfContent = $mpdf->Output($filename, 'S');

        return response($pdfContent)
                ->header('Content-Type', 'application/pdf')
                ->header('Content-Disposition', 'attachment; filename="'.$filename.'"');
    }

    public function previewBbk($id)
    {
        $bbkhdr = BbkHdr::with([
            'mbranch',
            'mwarco',
            'mformcode',
            'bbkdtls.mpromas',
        ])->findOrFail($id);

        $bbkdtls = collect($bbkhdr->bbkdtls)->groupBy(function($i){
            return implode('|', [
                $i->opron,
                $i->mpromas->brand_name,
                $i->mpromas->prona,
                $i->pono,
                $i->invno,
                $i->locco,
                trim($i->noted)
            ]);
        })->map(function($group){
            $first = $group->first();
            $first->lotno_merged = implode(', ', $group->pluck('lotno')->toArray());
            $first->trqty = $group->sum('trqty'); //sum jika sama hanya beda sn
            return $first;
        });

        $html = view('logistic.bbk.bbk_print', [
            'bbkhdr' => $bbkhdr,
            'bbkdtls' => $bbkdtls,
        ])->render();

        $mpdf = new \Mpdf\Mpdf([
            'format' => 'A4',
            'margin_top' => 10,
            'margin_bottom' => 10,
        ]);

        $mpdf->SetHTMLFooter('
            <div style="text-align:right; font-size:9pt;">
                {PAGENO}/{nbpg}
            </div>
        ');
        $mpdf->WriteHTML($html);
        $mpdf->SetHTMLFooterByName('myFooter', 'E_ALL');

        $mpdf->Output();
    }

    public function printBbk($id)
    {
        $bbkhdr = BbkHdr::with([
            'mbranch',
            'mwarco',
            'mformcode',
            'bbkdtls.mpromas',
        ])->findOrFail($id);

        $bbkdtls = collect($bbkhdr->bbkdtls)->groupBy(function($i){
            return implode('|', [
                $i->opron,
                $i->mpromas->brand_name,
                $i->mpromas->prona,
                $i->pono,
                $i->invno,
                $i->locco,
                trim($i->noted)
            ]);
        })->map(function($group){
            $first = $group->first();
            $first->lotno_merged = implode(', ', $group->pluck('lotno')->toArray());
            $first->trqty = $group->sum('trqty'); //sum jika sama hanya beda sn
            return $first;
        });

        $html = view('logistic.bbk.bbk_print', [
            'bbkhdr' => $bbkhdr,
            'bbkdtls' => $bbkdtls,
        ])->render();

        // increment counter total print
        DB::table('tsisnh')
        ->where('bbkid', $id)
        ->update([
            'prctr' => DB::raw('prctr + 1')
        ]);

        $mpdf = new \Mpdf\Mpdf([
            'format' => 'A4',
            'margin_top' => 10,
            'margin_bottom' => 10,
        ]);

        $mpdf->SetHTMLFooter('
            <div style="text-align:right; font-size:9pt;">
                {PAGENO}/{nbpg}
            </div>
        ');
        $mpdf->WriteHTML($html);
        $mpdf->SetHTMLFooterByName('myFooter', 'E_ALL');

        // save PDF jadi string
        $filename = $bbkhdr->braco.'-'.$bbkhdr->formc.$bbkhdr->trano.'.pdf';
        $pdfContent = $mpdf->Output($filename, 'S');

        return response($pdfContent)
                ->header('Content-Type', 'application/pdf')
                ->header('Content-Disposition', 'attachment; filename="'.$filename.'"');
    }

    public function previewBpb($id)
    {
        $bpbhdr = BpbHdr::with([
            'mbranch',
            'mwarco',
            'mformcode',
            'bpbdtls.mpromas',
        ])->findOrFail($id);

        $bpbdtls = $bpbhdr->bpbdtls;

        $html = view('logistic.bpb.bpb_print', [
            'bpbhdr' => $bpbhdr,
            'bpbdtls' => $bpbdtls,
        ])->render();

        $mpdf = new \Mpdf\Mpdf([
            'format' => 'A4',
            'margin_top' => 10,
            'margin_bottom' => 10,
        ]);

        $mpdf->SetHTMLFooter('
            <div style="text-align:right; font-size:9pt;">
                {PAGENO}/{nbpg}
            </div>
        ');
        $mpdf->WriteHTML($html);
        $mpdf->SetHTMLFooterByName('myFooter', 'E_ALL');

        $mpdf->Output();
    }

    public function printBpb($id)
    {
        $bpbhdr = BpbHdr::with([
            'mbranch',
            'mwarco',
            'mformcode',
            'bpbdtls.mpromas',
        ])->findOrFail($id);

        $bpbdtls = $bpbhdr->bpbdtls;

        $html = view('logistic.bpb.bpb_print', [
            'bpbhdr' => $bpbhdr,
            'bpbdtls' => $bpbdtls,
        ])->render();

        // increment counter total print
        DB::table('tsreqh')
        ->where('bpbid', $id)
        ->update([
            'prctr' => DB::raw('prctr + 1')
        ]);

        $mpdf = new \Mpdf\Mpdf([
            'format' => 'A4',
            'margin_top' => 10,
            'margin_bottom' => 10,
        ]);

        $mpdf->SetHTMLFooter('
            <div style="text-align:right; font-size:9pt;">
                {PAGENO}/{nbpg}
            </div>
        ');
        $mpdf->WriteHTML($html);
        $mpdf->SetHTMLFooterByName('myFooter', 'E_ALL');

        // save PDF jadi string
        $filename = $bpbhdr->braco.'-'.$bpbhdr->formc.$bpbhdr->reqno.'.pdf';
        $pdfContent = $mpdf->Output($filename, 'S');

        return response($pdfContent)
                ->header('Content-Type', 'application/pdf')
                ->header('Content-Disposition', 'attachment; filename="'.$filename.'"');
    }
    
    public function previewTa($id)
    {
        $tahdr = TaHdr::with([
            'mbranch',
            'mwarco',
            'mformcode',
            'tadtls.mpromas',
        ])->findOrFail($id);

        $tadtls = $tahdr->tadtls;

        $html = view('logistic.ta.ta_print', [
            'tahdr' => $tahdr,
            'tadtls' => $tadtls,
        ])->render();

        $mpdf = new \Mpdf\Mpdf([
            'format' => 'A4',
            'margin_top' => 10,
            'margin_bottom' => 10,
        ]);

        $mpdf->SetHTMLFooter('
            <div style="text-align:right; font-size:9pt;">
                {PAGENO}/{nbpg}
            </div>
        ');
        $mpdf->WriteHTML($html);
        $mpdf->SetHTMLFooterByName('myFooter', 'E_ALL');

        $mpdf->Output();
    }

    public function printTa($id)
    {
        $tahdr = TaHdr::with([
            'mbranch',
            'mwarco',
            'mformcode',
            'tadtls.mpromas',
        ])->findOrFail($id);

        $tadtls = $tahdr->tadtls;

        $html = view('logistic.ta.ta_print', [
            'tahdr' => $tahdr,
            'tadtls' => $tadtls,
        ])->render();

        // increment counter total print
        DB::table('tsisnh')
        ->where('bbkid', $id)
        ->update([
            'prctr' => DB::raw('prctr + 1')
        ]);

        $mpdf = new \Mpdf\Mpdf([
            'format' => 'A4',
            'margin_top' => 10,
            'margin_bottom' => 10,
        ]);

        $mpdf->SetHTMLFooter('
            <div style="text-align:right; font-size:9pt;">
                {PAGENO}/{nbpg}
            </div>
        ');
        $mpdf->WriteHTML($html);
        $mpdf->SetHTMLFooterByName('myFooter', 'E_ALL');

        // save PDF jadi string
        $filename = $tahdr->braco.'-'.$tahdr->formc.$tahdr->trano.'.pdf';
        $pdfContent = $mpdf->Output($filename, 'S');

        return response($pdfContent)
                ->header('Content-Type', 'application/pdf')
                ->header('Content-Disposition', 'attachment; filename="'.$filename.'"');
    }

    public function previewWo($id)
    {
        $wohdr = WoHdr::with([
            'wodtls.mpromas',
        ])->findOrFail($id);

        $wodtls = $wohdr->wodtls;

        $html = view('production.work_order.wo_print', [
            'wohdr' => $wohdr,
            'wodtls' => $wodtls,
        ])->render();

        $mpdf = new \Mpdf\Mpdf([
            'format' => 'A4',
            'margin_top' => 10,
            'margin_bottom' => 10,
        ]);

        $mpdf->SetHTMLFooter('
            <div style="text-align:right; font-size:9pt;">
                {PAGENO}/{nbpg}
            </div>
        ');
        $mpdf->WriteHTML($html);
        $mpdf->SetHTMLFooterByName('myFooter', 'E_ALL');

        $mpdf->Output();
    }

    public function printWo($id)
    {
        $wohdr = WoHdr::with([
            'wodtls.mpromas',
        ])->findOrFail($id);

        $wodtls = $wohdr->wodtls;

        $html = view('production.work_order.wo_print', [
            'wohdr' => $wohdr,
            'wodtls' => $wodtls,
        ])->render();

        // increment counter total print
        DB::table('tworkh')
        ->where('woid', $id)
        ->update([
            'prctr' => DB::raw('prctr + 1')
        ]);

        $mpdf = new \Mpdf\Mpdf([
            'format' => 'A4',
            'margin_top' => 10,
            'margin_bottom' => 10,
        ]);

        $mpdf->SetHTMLFooter('
            <div style="text-align:right; font-size:9pt;">
                {PAGENO}/{nbpg}
            </div>
        ');
        $mpdf->WriteHTML($html);
        $mpdf->SetHTMLFooterByName('myFooter', 'E_ALL');

        // save PDF jadi string
        $filename = $wohdr->braco.'-'.$wohdr->formc.$wohdr->wonum.'.pdf';
        $pdfContent = $mpdf->Output($filename, 'S');

        return response($pdfContent)
                ->header('Content-Type', 'application/pdf')
                ->header('Content-Disposition', 'attachment; filename="'.$filename.'"');
    }

    public function previewOc($id)
    {
        $ochdr = \App\Models\OcHdr::with([
            'ocdtls.mpromas',
            'msreno',
            'mcusmas',
            'mtaxes',
            'mdepo'
        ])->findOrFail($id);

        $delto = MstMas::where('braco', $ochdr->braco)
        ->where('cusno', $ochdr->cusno)
        ->where('shpto', $ochdr->delto)
        ->first();

        $mcindu = Mcindu::where('cindu', $ochdr->mcusmas->cindu)->first();

        $html = view('marketing.oc_sa.oc_print', compact('ochdr', 'delto', 'mcindu'))->render();

        $mpdf = new \Mpdf\Mpdf([
            'format' => 'A4',
            'margin_top' => 10,
            'margin_bottom' => 10,
        ]);

        $mpdf->SetHTMLFooter('
            <div style="text-align:right; font-size:9pt;">
                {PAGENO}/{nbpg}
            </div>
        ');

        $mpdf->WriteHTML($html);

        $mpdf->SetHTMLFooterByName('myFooter', 'E_ALL');

        $mpdf->Output(); 
    }

    public function printOc($id) {
        $ochdr = \App\Models\OcHdr::with([
            'ocdtls.mpromas',
            'msreno',
            'mcusmas',
            'mtaxes',
            'mdepo'
        ])->findOrFail($id);

        $delto = MstMas::where('braco', $ochdr->braco)
        ->where('cusno', $ochdr->cusno)
        ->where('shpto', $ochdr->delto)
        ->first();

        $mcindu = Mcindu::where('cindu', $ochdr->cindu)->first();

        $ocdtls = $ochdr->ocdtls;

        $html = view('marketing.oc_sa.oc_print', [
            'ochdr' => $ochdr,
            'delto' => $delto,
            'mcindu' => $mcindu,
            'ocdtls' => $ocdtls,
        ])->render();

        // increment counter total print
        DB::table('tcoreh')
        ->where('ocid', $id)
        ->update([
            'prctr' => DB::raw('prctr + 1')
        ]);

        $mpdf = new Mpdf();

        $mpdf->SetHTMLFooter('
            <div style="text-align:right; font-size:9pt;">
                {PAGENO}/{nbpg}
            </div>
        ');

        $mpdf->WriteHTML($html);

        $mpdf->SetHTMLFooterByName('myFooter', 'E_ALL');

        $pdfContent = $mpdf->Output("{$ochdr->ocid}.pdf", "S");

        return response($pdfContent)
        ->header('Content-Type', 'application/pdf')
        ->header('Content-Disposition', 'attachment; filename="'.$ochdr->ocid.'.pdf"');
    }

    public function previewOcSb($id)
    {
        $ocsbhdr = \App\Models\OcSbHdr::with([
            'ocsbdtls.mpromas',
            'mformcode',
            'msreno',
            'mcusmas',
            'mtaxes',
            'mdepo',
            'invoices'
        ])->findOrFail($id);

        $mcindu = Mcindu::where('cindu', $ocsbhdr->mcusmas->cindu)->first();

        $bomList = DB::table('tprojc')
            ->join('mpromas', 'tprojc.opron', '=', 'mpromas.opron')
            ->where('tprojc.ocsbid', $id)
            ->select(
                'tprojc.uopron',
                'tprojc.opron',
                'tprojc.trqty',
                'tprojc.stdqu',
                'mpromas.prona'
            )
            ->orderBy('tprojc.opron')
            ->get()
            ->groupBy('uopron');

        $html = view('marketing.oc_sb.oc_sb_print', compact('ocsbhdr', 'mcindu', 'bomList'))->render();

        $mpdf = new \Mpdf\Mpdf([
            'format' => 'A4',
            'margin_top' => 10,
            'margin_bottom' => 10,
        ]);

        $mpdf->SetHTMLFooter('
            <div style="text-align:right; font-size:9pt;">
                {PAGENO}/{nbpg}
            </div>
        ');

        $mpdf->WriteHTML($html);

        $mpdf->SetHTMLFooterByName('myFooter', 'E_ALL');

        $mpdf->Output(); 
    }

    public function printOcSb($id) 
    {
        $ocsbhdr = \App\Models\OcSbHdr::with([
            'ocsbdtls.mpromas',
            'mformcode',
            'msreno',
            'mcusmas',
            'mtaxes',
            'mdepo',
            'invoices'
        ])->findOrFail($id);

        $mcindu = Mcindu::where('cindu', $ocsbhdr->mcusmas->cindu)->first();

        $bomList = DB::table('tprojc')
            ->join('mpromas', 'tprojc.opron', '=', 'mpromas.opron')
            ->where('tprojc.ocsbid', $id)
            ->select(
                'tprojc.uopron',
                'tprojc.opron',
                'tprojc.trqty',
                'tprojc.stdqu',
                'mpromas.prona'
            )
            ->orderBy('tprojc.opron')
            ->get()
            ->groupBy('uopron');

        $html = view('marketing.oc_sb.oc_sb_print', compact('ocsbhdr', 'mcindu', 'bomList'))->render();

        // increment counter total print
        DB::table('tproja')
        ->where('ocsbid', $id)
        ->update([
            'prctr' => DB::raw('prctr + 1')
        ]);

        $mpdf = new \Mpdf\Mpdf([
            'format' => 'A4',
            'margin_top' => 10,
            'margin_bottom' => 10,
        ]);

        $mpdf->SetHTMLFooter('
            <div style="text-align:right; font-size:9pt;">
                {PAGENO}/{nbpg}
            </div>
        ');

        $mpdf->WriteHTML($html);

        $mpdf->SetHTMLFooterByName('myFooter', 'E_ALL');

        $pdfContent = $mpdf->Output("{$ocsbhdr->ocsbid}.pdf", "S");

        return response($pdfContent)
        ->header('Content-Type', 'application/pdf')
        ->header('Content-Disposition', 'attachment; filename="'.$ocsbhdr->ocsbid.'.pdf"');
    }
}   
