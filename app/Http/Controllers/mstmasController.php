<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;

use App\Models\MstMas;

class MstmasController extends Controller
{
    public function getMstmas(Request $request)
    {
        $search = $request->input('q');
        $page = $request->input('page', 1);
        $perPage = 10;

        $query = \App\Models\Mstmas::query();

        if ($search) {
            $query->where('cusno', 'like', "%{$search}%")
                ->orWhere('shpnm', 'like', "%{$search}%");
        }

        $total = $query->count();
        $datas = $query->skip(($page - 1) * $perPage)
                        ->take($perPage)
                        ->get();

        $results = $datas->map(function ($p) {
            return [
                'id' => $p->cusno,
                'shpto' => $p->shpto,
                'text' => "{$p->cusno} - {$p->shpnm}",
                'address' => $p->deliveryaddress,
            ];
        });

        return response()->json([
            'results' => $results,
            'pagination' => ['more' => ($page * $perPage) < $total]
        ]);
    }
}