<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Models\Mpromas;

class ProductController extends Controller
{
    public function getProducts(Request $request)
    {
        $search = $request->input('q');
        $page = $request->input('page', 1);
        $perPage = 10;

        $query = \App\Models\Mpromas::query();

        if ($search) {
            $query->where('opron', 'like', "%{$search}%")
                ->orWhere('prona', 'like', "%{$search}%");
        }

        $query->orderBy('prona');

        $total = $query->count();
        $products = $query->skip(($page - 1) * $perPage)
                        ->take($perPage)
                        ->get();

        $results = $products->map(function ($p) {
            return [
                'id' => $p->opron,
                'text' => "{$p->opron} - {$p->prona}",
                'data_prona' => $p->prona,
                'data_stdqu' => $p->stdqu,
            ];
        });

        return response()->json([
            'results' => $results,
            'pagination' => ['more' => ($page * $perPage) < $total]
        ]);
    }

    public function getSpareparts(Request $request)
    {
        $search = $request->input('q');
        $page = $request->input('page', 1);
        $perPage = 10;

        $query = \App\Models\Mpromas::query()
            ->join('stobl_tbl as stobl', 'stobl.opron', '=', 'mpromas.opron')
            ->whereNotIn('mpromas.ssgrup_id', ['036', '028'])
            ->whereIn('mpromas.itype_id', ['N', 'S'])
            ->where('stobl.toqoh', '>', 0)
            ->where('stobl.braco', Auth::user()->cabang)
            ->distinct('mpromas.opron');

        // search
        if ($search) {
            $query->where(function($q) use ($search){
                $q->where('mpromas.opron', 'like', "%{$search}%")
                ->orWhere('mpromas.prona', 'like', "%{$search}%");
            });
        }

        $query->select(
                'mpromas.*',
                'stobl.locco',
                'stobl.warco',
                'stobl.toqoh'
            )
            ->orderBy('mpromas.prona');

        $total = $query->count();

        $products = $query->skip(($page - 1) * $perPage)
                        ->take($perPage)
                        ->get();

        $results = $products->map(function ($p) {

            return [
                'id' => $p->opron,
                'text' => "{$p->opron} - {$p->prona}",

                'data_prona' => $p->prona,
                'data_stdqu' => $p->stdqu,

                'data_locco' => $p->locco,
                'data_warco' => $p->warco,
                'data_toqoh' => $p->toqoh,
            ];
        });

        return response()->json([
            'results' => $results,
            'pagination' => [
                'more' => ($page * $perPage) < $total
            ]
        ]);
    }
}