<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class OsrController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $userBraco = Auth::user()->cabang;

        $osr = DB::table('tsreqd')
            ->leftJoin('mpromas', 'tsreqd.opron', '=', 'mpromas.opron')
            ->leftJoin('tsisnh', 'tsreqd.bpbid', '=', 'tsisnh.bpbid')
            ->select(
                'tsreqd.bpbid',
                'tsreqd.formc',
                'tsreqd.reqno',
                'tsreqd.braco',
                'tsreqd.opron',
                'mpromas.prona',
                'mpromas.qunit',
                'mpromas.itype_id',
                'tsisnh.reqdt',
                'tsreqd.aloka',
                'tsreqd.eariv',
            );
        $mbranch = DB::table('mbranches')->get();
        $mitype = DB::table('mitype_tbl')->get();

        return view('logistic.reports.osr.osr_create', compact('userBraco', 'osr', 'mbranch', 'mitype'));
    }

    private function getData(Request $req)
    {

        $data = DB::table('tsreqd as d')
            ->leftJoin('mpromas as m', 'd.opron', '=', 'm.opron')
            ->leftJoin('tsreqh as h', 'd.bpbid', '=', 'h.bpbid')
            ->when($req->filled('invtype'), function ($q) use ($req) {
                $q->where('m.itype_id', $req->invtype);
            })
            ->select(
                'd.reqno',
                'd.braco',
                'd.opron',
                DB::raw('d.rqqty - d.rcqty as outstanding'),
                'm.prona',
                'm.stdqu',
                'm.itype_id',
                'd.aloka',
                'd.eariv',
                'h.reqdt'
            )
            ->whereRaw('(d.rqqty - d.rcqty) > 0')
            ->orderBy('d.reqno')
            ->get();

        if ($data->isEmpty()) {
            return ['error' => 'Data tidak ditemukan'];
        }

        return $data;
    }

    public function print(Request $req)
    {
        $data = $this->getData($req);
        $userBraco = Auth::user()->cabang;

        if (isset($data['error'])) {
            return "<h3>{$data['error']}</h3>";
        }

        $mpdf = new \Mpdf\Mpdf([
            'format' => 'A4',
            'margin_top' => 30,
            'margin_bottom' => 35,
        ]);

        $html = view('logistic.reports.osr.osr_print', [
            'items' => $data,
            'userBraco' => $userBraco,
            'reqto' => $req->reqto,
            'invtype' => $req->invtype,
        ])->render();

        $mpdf->WriteHTML($html);
        $mpdf->Output();
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
