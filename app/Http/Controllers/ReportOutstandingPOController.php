<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Models\Mbranch;
use App\Models\Mvendor;

class ReportOutstandingPOController extends Controller
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

        $supplier = Mvendor::orderBy('supna', 'ASC')->get();

        if ($periode) {
            $date = Carbon::createFromFormat('Ym', $periode->periode);
            $bdate_s = $date->copy()->startOfMonth()->format('Y-m-d');
            $bdate_e = $date->copy()->endOfMonth()->format('Y-m-d');
        }

        return view('purchasing.reports.outstanding_po.outstanding_po_create', compact('braco', 'supplier', 'bdate_s', 'bdate_e'));
    }

    private function queryOutstandingPO(Request $req)
    {
        $query = DB::table('pohdr_tbl as h')
            ->join('podtl_tbl as d', 'h.pono', '=', 'd.pono')
            ->leftJoin('mpromas as p', 'd.opron', '=', 'p.opron')
            ->leftJoin('mvendor_tbl as v', 'h.supno', '=', 'v.supno')
            ->select(
                'h.pono',
                'h.podat',
                'h.supno',
                'v.supna',
                'h.curco',
                'h.potype',
                'd.opron',
                'p.prona',
                'd.poqty',
                'd.rcqty',
                'd.stdqu',
                'd.price',
                'd.netpr',
                'd.edeld'
            )
            ->whereBetween('h.podat', [$req->bdate_s, $req->bdate_e]);

        if (Auth::user()->cabang) {
            $query->where('h.braco', Auth::user()->cabang);
        }

        if ($req->po_status == 'Outstanding') {
            $query->whereRaw('d.poqty > d.rcqty');
        }

        if ($req->filled('supno')) {
            $query->where('h.supno', $req->supno);
        }

        if ($req->filled('po_formc')) {
            $typeMapping = [
                'PO' => 'Lokal',
                'PI' => 'Import',
                'PN' => 'Inventaris'
            ];

            $mappedType = $typeMapping[$req->po_formc] ?? $req->po_formc;
            $query->where('h.potype', $mappedType);
        }

        if ($req->sort_by == 'prona') {
            $query->orderBy('p.prona', 'ASC');
        } elseif ($req->sort_by == 'supplier') {
            $query->orderBy('v.supna', 'ASC')->orderBy('h.pono', 'ASC');
        } else {
            $query->orderBy('h.pono', 'ASC')->orderBy('p.prona', 'ASC');
        }

        return $query->get()->groupBy('pono');
    }

    public function previewOutstandingPO(Request $req)
    {
        $items = $this->queryOutstandingPO($req);

        $branch = Mbranch::where('braco', Auth::user()->cabang)->first();
        $brana = $branch->brana ?? Auth::user()->cabang;

        $selectedSupplier = null;
        if ($req->filled('supno')) {
            $vendor = Mvendor::where('supno', $req->supno)->first();
            if ($vendor) {
                $selectedSupplier = $vendor->supno . ' - ' . $vendor->supna;
            }
        }

        $html = view('purchasing.reports.outstanding_po.outstanding_po_preview', [
            'items'            => $items,
            'start'            => $req->bdate_s,
            'end'              => $req->bdate_e,
            'po_status'        => $req->po_status ?? 'Outstanding',
            'sort_by'          => $req->sort_by ?? 'pono',
            'selectedSupplier' => $selectedSupplier,
            'brana'            => $brana
        ])->render();

        $mpdf = new \Mpdf\Mpdf([
            'format'        => 'A4-L',
            'margin_top'     => 10,
            'margin_bottom'  => 10,
            'margin_left'    => 10,
            'margin_right'   => 10,
        ]);

        $mpdf->WriteHTML($html);
        $mpdf->Output('Outstanding_PO_Report.pdf', 'I');
    }
}