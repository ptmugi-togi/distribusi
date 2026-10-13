<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Models\Mbranch;

class ReportOutstandingWOController extends Controller
{
    public function create()
    {
        $braco = Auth::user()->cabang;
        $periode = DB::table('tperiode')
            ->where('status', 'O')
            ->orderBy('periode', 'DESC')
            ->first();

        $bdate_s = null;
        $bdate_e = null;

        if ($periode) {
            $date = Carbon::createFromFormat('Ym', $periode->periode);
            $bdate_s = $date->copy()->startOfMonth()->format('Y-m-d');
            $bdate_e = $date->copy()->endOfMonth()->format('Y-m-d');
        }

        return view('production.reports.outstanding_wo.outstanding_wo_create', compact('braco', 'bdate_s', 'bdate_e'));
    }

    private function queryOutstandingWO(Request $req)
    {
        $query = DB::table('tworkh as h')
            ->join('tworkd as d', 'h.wonum', '=', 'd.wonum')
            ->leftJoin('mpromas as p', 'd.outpr', '=', 'p.opron')
            ->select(
                'h.wonum',
                'h.wodat',
                DB::raw('CONCAT(h.reffc, " - ", h.refno) as refer'),
                'd.outpr',
                'p.prona',
                'd.outqt',
                'd.acqty'
            )
            ->whereBetween('h.wodat', [$req->bdate_s, $req->bdate_e]);

        if (Auth::user()->cabang) {
            $query->where('h.braco', Auth::user()->cabang);
        }

        // Filter status WO Outstanding (Qty WO > Qty BBM)
        if ($req->wo_status == 'Outstanding') {
            $query->whereRaw('d.outqt > d.acqty');
        }

        if ($req->sort_by == 'outpr') {
            $query->orderBy('d.outpr', 'ASC')->orderBy('h.wonum', 'ASC');
        } else {
            $query->orderBy('h.wonum', 'ASC')->orderBy('d.outpr', 'ASC');
        }

        return $query->get()->groupBy('wonum');
    }

    public function preview(Request $req)
    {
        $items = $this->queryOutstandingWO($req);

        $branch = Mbranch::where('braco', Auth::user()->cabang)->first();
        $brana = $branch->brana ?? Auth::user()->cabang;

        $html = view('production.reports.outstanding_wo.outstanding_wo_preview', [
            'items'     => $items,
            'start'     => $req->bdate_s,
            'end'       => $req->bdate_e,
            'wo_status' => $req->wo_status ?? 'Outstanding',
            'sort_by'   => $req->sort_by ?? 'wonum',
            'brana'     => $brana
        ])->render();

        $mpdf = new \Mpdf\Mpdf([
            'format'        => 'A4-L',
            'margin_top'    => 10,
            'margin_bottom' => 10,
            'margin_left'   => 10,
            'margin_right'  => 10,
        ]);

        $mpdf->WriteHTML($html);
        $mpdf->Output('Outstanding_WO_Report.pdf', 'I');
    }
}