<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use illuminate\Support\Facades\Log;

use App\Models\Mbranch;

class ScController extends Controller
{
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $openPeriod = DB::table('tperiode')
            ->where('status', 'O')
            ->orderBy('periode', 'asc')
            ->first();

        $minDate = null;

        if ($openPeriod) {
            $year = substr($openPeriod->periode, 0, 4);
            $month = substr($openPeriod->periode, 4, 2);

            $minDate = "$year-$month-01";
        }

        return view('logistic.reports.stock_card.sc_create' , compact('minDate'));
    }

    private function getStockAwal($braco, $opron, $dateStart)
    {
        $in = DB::table('tstord as d')
            ->join('tstorh as h', 'h.bbmid', '=', 'd.bbmid')
            ->where('h.braco', $braco)
            ->where('d.opron', $opron)
            ->whereDate('h.tradt', '<', $dateStart)
            ->sum('d.trqty');

        $out = DB::table('toutg as d')
            ->join('tsisnh as h', 'h.bbkid', '=', 'd.bbkid')
            ->where('h.braco', $braco)
            ->where('d.opron', $opron)
            ->whereDate('h.tradt', '<', $dateStart)
            ->sum('d.trqty');

        return $in - $out;
    }

    private function getTransaksi($braco, $opron, $start, $end)
    {
        // IN
        $in = DB::table('tstord as d')
            ->join('tstorh as h', 'h.bbmid', '=', 'd.bbmid')
            ->leftJoin('mformcode_tbl as f', 'f.bracoformc', '=', 'h.bracoformc')
            ->leftJoin('mpromas as p', 'p.opron', '=', 'd.opron')
            ->select(
                DB::raw("1 as sorting"),
                DB::raw("CAST(h.tradt AS DATE) as date"),
                'h.formc',
                'h.trano',
                'h.noteh',
                DB::raw("NULL as rqbrc"),
                'f.descr as form_descr',
                DB::raw("NULL as cusna"),
                'd.lotno',
                'd.trqty as qty_in',
                DB::raw("0 as qty_out"),
                'p.prona'
            )
            ->where('h.braco', $braco)
            ->where('d.opron', $opron)
            ->whereBetween(DB::raw("CAST(h.tradt AS DATE)"), [$start, $end]);

        // OUT
        $out = DB::table('toutg as d')
            ->join('tsisnh as h', 'h.bbkid', '=', 'd.bbkid')
            ->leftJoin('mformcode_tbl as f', 'f.bracoformc', '=', 'h.bracoformc')
            ->leftJoin('mcusmas as c', 'c.cusno', '=', 'h.cusno')
            ->leftJoin('mpromas as p', 'p.opron', '=', 'd.opron')
            ->select(
                DB::raw("2 as sorting"),
                DB::raw("CAST(h.tradt AS DATE) as date"),
                'h.formc',
                'h.trano',
                'h.noteh',
                'h.rqbrc',
                'f.descr as form_descr',
                'c.cusna',
                'd.lotno',
                DB::raw("0 as qty_in"),
                'd.trqty as qty_out',
                'p.prona'
            )
            ->where('h.braco', $braco)
            ->where('d.opron', $opron)
            ->whereBetween(DB::raw("CAST(h.tradt AS DATE)"), [$start, $end]);

        return DB::query()
            ->fromSub($in->unionAll($out), 'x')
            ->orderBy('sorting')
            ->orderBy('date')
            ->orderBy('formc')
            ->orderBy('trano')
            ->get();
    }

    public function preview(Request $req)
    {
        $braco = $req->braco;
        $opron = $req->opron;
        $start = $req->sodat_s;
        $end   = $req->sodat_e;

        $branch = Mbranch::where('braco', $braco)->first();

        $stockAwal = $this->getStockAwal($braco, $opron, $start);

        $rows = $this->getTransaksi($braco, $opron, $start, $end);

        $running = $stockAwal;
        foreach ($rows as $r) {
            $r->document = $r->formc . '-' . $r->trano;

            if ($r->formc === 'DO') {
                $r->description = $r->cusna ?? '-';
            } elseif ($r->formc === 'TA') {
                $r->description = 'TRANSFER TO: ' . ($r->rqbrc ?? '-');
            } elseif (in_array($r->formc, ['IB', 'IA'])) {
                $r->description = $r->form_descr ?? '-';
            } else {
                $r->description = $r->form_descr ?? $r->noteh ?? '-';
            }
        }

        $totalIn = $rows->sum('qty_in');
        $totalOut = $rows->sum('qty_out');
        $stockAkhir = $stockAwal + $totalIn - $totalOut;

        if ($rows->count() === 0) {
            $html = '<p>Tidak ada data Stock Card</p>';
        } else {
            $html = view('logistic.reports.stock_card.sc_preview', [
                'rows' => $rows,
                'stockAwal' => $stockAwal,
                'start' => $start,
                'end' => $end,
                'totalIn' => $totalIn,
                'totalOut' => $totalOut,
                'stockAkhir' => $stockAkhir,
                'opron' => $opron,
                'prona' => $rows->first()->prona ?? '',
                'braco' => $braco,
                'brana' => $branch->brana ?? ''
            ])->render();
        }

        $mpdf = new \Mpdf\Mpdf([
            'format' => 'A4-L',
            'margin_top' => 25,
            'margin_bottom' => 10
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

