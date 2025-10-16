<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;

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
}