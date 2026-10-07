<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\Mbranch;

class NationalStockPositionController extends Controller
{
    public function create()
    {
        $braco = Auth::user()->cabang;
        return view('logistic.reports.nsp.nsp_create', compact('braco'));
    }

    public function preview(Request $req)
    {
        $itype = $req->get('itype', 'N');
        $sortBy = $req->get('sort_by', 'opron');
        $userCabang = Auth::user()->cabang;

        $branches = DB::table('mbranches')
            ->select('braco')
            ->where('braco', '!=', 'PST')
            ->orderBy('braco', 'ASC')
            ->get();

        $orderColumn = $sortBy === 'prona' ? 'p.prona' : 'p.opron';

        $products = DB::table('mpromas as p')
            ->leftJoin('msgrup as sg', 'p.sgrup_id', '=', 'sg.sgrup_id')
            ->select(
                'p.opron',
                'p.prona',
                'p.sgrup_id',
                DB::raw("COALESCE(sg.descr_sgrup, 'OTHERS') as subgroup_name")
            )
            ->where('p.itype_id', $itype)
            ->orderBy('subgroup_name', 'ASC')
            ->orderBy($orderColumn, 'ASC')
            ->get();

        if ($products->isEmpty()) {
            return redirect()->back()->with('error', 'Data tidak ditemukan.');
        }

        $oprons = $products->pluck('opron')->toArray();

        $allStocks = DB::table('stobw_tbl')
            ->whereIn('opron', $oprons)
            ->where('toqoh', '>', 0)
            ->select('opron', 'braco', 'toqoh')
            ->get()->groupBy('opron');

        $allInden = DB::table('podtl_tbl as d')
            ->join('pohdr_tbl as h', 'd.pono', '=', 'h.pono')
            ->select('d.opron', 'h.pono', 'h.potype', DB::raw('(d.poqty - d.rcqty) as qty'))
            ->whereIn('d.opron', $oprons)
            ->whereRaw('d.poqty > d.rcqty')
            ->get()->groupBy('opron');

        $allBpb = DB::table('tsreqd as d')
            ->join('tsreqh as h', 'd.reqno', '=', 'h.reqno')
            ->select('d.opron', 'h.reqno', 'h.braco', 'd.aloka', DB::raw('(d.rqqty - d.rcqty) as qty'))
            ->whereIn('d.opron', $oprons)
            ->whereRaw('d.rqqty > d.rcqty')
            ->get()->groupBy('opron');

        $filteredProducts = $products->filter(function ($item) use ($allStocks, $allInden, $allBpb, $userCabang) {
            $opron = $item->opron;

            $productStocks = $allStocks->get($opron, collect());
            $item->branch_stocks = $productStocks->pluck('toqoh', 'braco')->toArray();
            $totalStockCabang = array_sum($item->branch_stocks);

            $userStockObj = $productStocks->firstWhere('braco', $userCabang);
            $item->avail_d3 = $userStockObj ? $userStockObj->toqoh : 0;

            $item->bop = 0;
            $item->rusak = 0;

            $indenCollection = $allInden->get($opron, collect());
            $item->inden = $indenCollection->sum('qty');
            $item->inden_breakdown = $indenCollection;

            $bpbCollection = $allBpb->get($opron, collect());
            $item->bpb = $bpbCollection->sum('qty');
            $item->bpb_breakdown = $bpbCollection;

            return ($totalStockCabang > 0) || ($item->inden > 0) || ($item->bpb > 0);
        });

        $groupedItems = $filteredProducts->groupBy('subgroup_name');
        $branch = Mbranch::where('braco', $userCabang)->first();
        $brana = $branch->brana ?? $userCabang;

        ini_set('pcre.backtrack_limit', '20000000'); 
        ini_set('memory_limit', '1024M');

        $html = view('logistic.reports.nsp.nsp_preview', [
            'groupedItems' => $groupedItems,
            'branches'     => $branches,
            'sort_by'      => $sortBy,
            'brana'        => $brana
        ])->render();

        $mpdf = new \Mpdf\Mpdf([
            'format'        => 'A4-L',
            'margin_top'    => 10,
            'margin_bottom' => 10,
            'margin_left'   => 10,
            'margin_right'  => 10,
        ]);

        $mpdf->WriteHTML($html);

        if (ob_get_contents()) {
            ob_end_clean();
        }

        $mpdf->Output('National Stock Position.pdf', 'I');
        exit;
    }
}