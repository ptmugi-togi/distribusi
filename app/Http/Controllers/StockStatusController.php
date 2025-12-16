<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

use App\Models\Stobl;
class StockStatusController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $userBraco = Auth::user()->cabang;

        $ss = DB::table('stobw_tbl')
            ->leftJoin('mpromas', 'stobw_tbl.opron', '=', 'mpromas.opron')
            ->leftJoin('stobl_tbl', 'stobw_tbl.opron', '=', 'stobl_tbl.opron')
            ->select(
                'stobw_tbl.opron',
                'stobw_tbl.braco',
                'stobw_tbl.warco',
                'mpromas.prona',
                'stobl_tbl.locco',
                DB::raw('(SELECT SUM(qtyit) FROM stobl_tbl WHERE stobl_tbl.opron = stobw_tbl.opron AND stobl_tbl.braco = stobw_tbl.braco AND stobl_tbl.warco = stobw_tbl.warco) AS total_transit'),
                DB::raw('(SELECT SUM(toqoh) FROM stobl_tbl WHERE stobl_tbl.opron = stobw_tbl.opron AND stobl_tbl.braco = stobw_tbl.braco AND stobl_tbl.warco = stobw_tbl.warco) AS total_stock')
            )
            ->havingRaw('total_stock > 0')
            ->where('stobw_tbl.braco', $userBraco)
            ->groupBy('stobw_tbl.opron', 'stobw_tbl.braco', 'stobw_tbl.warco', 'mpromas.prona', 'stobl_tbl.locco')
            ->get();

        return view('logistic.reports.stock_status.ss_index', compact('ss'));
    }

    public function getLot(Request $request, $opron)
    {
        $userBraco = Auth::user()->cabang;
        $warco = $request->warco;

        $lot = Stobl::where('opron', $opron)
                    ->where('braco', $userBraco)
                    ->where('warco', $warco)
                    ->select('lotno', 'toqoh')
                    ->orderBy('lotno', 'ASC')
                    ->get();

        return response()->json($lot);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
