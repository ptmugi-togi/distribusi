<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Mbranch;

class NationalStockPositionController extends Controller
{
    public function create()
    {
        return view('logistic.reports.nsp.nsp_create');
    }

    public function preview(Request $req)
    {
        $itype = $req->get('itype', 'N');
        $sortBy = $req->get('sort_by', 'opron');

        // 1. Ambil daftar cabang (Kecualikan 'PST' dan 'CKG')
        $branches = DB::table('mbranches')
            ->select('braco')
            ->whereNotIn('braco', ['PST', 'CKG'])
            ->orderBy('braco', 'ASC')
            ->get();

        $orderColumn = $sortBy === 'prona' ? 'p.prona' : 'p.opron';

        // 2. Query Utama Produk
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

        // 3. Batch Query Data Pendukung
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

        // 4. Filtering Produk Aktif
        $filteredProducts = $products->filter(function ($item) use ($allStocks, $allInden, $allBpb) {
            $opron = $item->opron;

            $productStocks = $allStocks->get($opron, collect());
            
            $branchStocksFormatted = [];
            $totalStockCabangLain = 0;
            $availD3 = 0;

            foreach ($productStocks as $st) {
                $qty = (float) $st->toqoh;

                // AVAIL_D3 murni stok milik PST
                if ($st->braco === 'PST') {
                    $availD3 = $qty;
                } 
                // Stok cabang-cabang lain (di luar PST & CKG)
                elseif ($st->braco !== 'CKG' && $qty > 0) {
                    $branchStocksFormatted[$st->braco] = $qty;
                    $totalStockCabangLain += $qty;
                }
            }

            $item->branch_stocks = $branchStocksFormatted;
            $item->avail_d3 = $availD3;
            $item->bop = 0;
            $item->rusak = 0;

            // Inden & BPB Breakdown
            $indenCollection = $allInden->get($opron, collect());
            $item->inden = (float) $indenCollection->sum('qty');
            $item->inden_breakdown = $indenCollection;

            $bpbCollection = $allBpb->get($opron, collect());
            $item->bpb = (float) $bpbCollection->sum('qty');
            $item->bpb_breakdown = $bpbCollection;

            // Syarat Lolos Tampil
            $hasAvailD3         = $item->avail_d3 > 0;
            $hasBranchStockLain = $totalStockCabangLain > 0;
            $hasInden           = $item->inden > 0;
            $hasBpb             = $item->bpb > 0;

            return $hasAvailD3 || $hasBranchStockLain || $hasInden || $hasBpb;
        });

        $groupedItems = $filteredProducts->groupBy('subgroup_name');

        // Ambil nama cabang PST untuk header PT (atau bisa set statis)
        $branch = Mbranch::where('braco', 'PST')->first();
        $brana = $branch->brana ?? 'PUSAT';

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