<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use illuminate\Support\Facades\Log;

class SmsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // 
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $mwarco = DB::table('mwarco_tbl')->get();

        $mitype = DB::table('mitype_tbl')->get();

        $msgrup = DB::table('msgrup_tbl')->get();

        $mssgrup = DB::table('mssgrup_tbl')->get();

        return view('reports.sms.sms_create' , compact('mwarco', 'mitype', 'msgrup', 'mssgrup'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    public function getData(Request $req)
    {
        $period = DB::table('tstorh')
            ->where('warco', $req->warco)
            ->whereRaw("CAST(tradt AS DATE) <= ?", [$req->asof])
            ->orderByDesc('tradt')
            ->value('priod');

        if (!$period) {
            return ['error' => 'Period tidak ditemukan'];
        }

        // cek status periode
        $isOpen = DB::table('tperiode')
            ->where('periode', $period)
            ->value('status');

        if ($isOpen !== 'O') {
            return ['error' => "Periode $period sudah close"];
        }

        // ambil OPRON yg ada transaksi (masuk atau keluar)
        $opronMasuk = DB::table('tstord as d')
            ->join('tstorh as h', 'h.bbmid', '=', 'd.bbmid')
            ->where('h.warco', $req->warco)
            ->where('h.priod', $period)
            ->whereRaw("CAST(h.tradt AS DATE) <= ?", [$req->asof])
            ->pluck('d.opron');

        $opronKeluar = DB::table('toutg as d')
            ->join('tsisnh as h', 'h.bbkid', '=', 'd.bbkid')
            ->where('h.warco', $req->warco)
            ->where('h.priod', $period)
            ->whereRaw("CAST(h.tradt AS DATE) <= ?", [$req->asof])
            ->pluck('d.opron');

        // merge & unique
        $oprons = $opronMasuk->merge($opronKeluar)->unique()->values();

        if ($oprons->isEmpty()) {
            return collect(); // tidak ada transaksi
        }

        $data = DB::table('mpromas as p')
            ->whereIn('p.opron', $oprons)

            // stok awal
            ->leftJoin(DB::raw("
                (
                    SELECT opron, SUM(bbqoh) AS awal
                    FROM stobw_tbl
                    GROUP BY opron
                ) AS sa
            "), "sa.opron", "=", "p.opron")

            // barang masuk
            ->leftJoin(DB::raw("
                (
                    SELECT d.opron, SUM(d.trqty) AS masuk
                    FROM tstord d
                    JOIN tstorh h ON h.bbmid = d.bbmid
                    WHERE h.warco = '{$req->warco}'
                    AND h.priod = '{$period}'
                    AND CAST(h.tradt AS DATE) <= '{$req->asof}'
                    GROUP BY d.opron
                ) AS tm
            "), "tm.opron", "=", "p.opron")

            // barang keluar
            ->leftJoin(DB::raw("
                (
                    SELECT d.opron, SUM(d.trqty) AS keluar
                    FROM toutg d
                    JOIN tsisnh h ON h.bbkid = d.bbkid
                    WHERE h.warco = '{$req->warco}'
                    AND h.priod = '{$period}'
                    AND CAST(h.tradt AS DATE) <= '{$req->asof}'
                    GROUP BY d.opron
                ) AS tk
            "), "tk.opron", "=", "p.opron")

            ->select(
                'p.opron',
                'p.prona',
                'p.stdqu',
                DB::raw("COALESCE(sa.awal,0) AS awal"),
                DB::raw("COALESCE(tm.masuk,0) AS masuk"),
                DB::raw("COALESCE(tk.keluar,0) AS keluar"),
                DB::raw("(COALESCE(sa.awal,0) + COALESCE(tm.masuk,0) - COALESCE(tk.keluar,0)) AS akhir")
            )
            ->orderBy('p.opron')
            ->get();

        return $data;
    }

    public function preview(Request $req)
    {
        $data = $this->getData($req);

        if (isset($data['error'])) {
            return "<h3>{$data['error']}</h3>";
        }

        $html = view('reports.sms.sms_preview', [
            'items' => $data,
            'asof' => $req->asof,
            'warco' => $req->warco,
            'invtype' => $req->invtype,
            'subgroup' => $req->subgroup,
            'subsubgroup' => $req->subsubgroup
        ])->render();

        $mpdf = new \Mpdf\Mpdf([
            'format' => 'A4',
            'margin_top' => 30,
            'margin_bottom' => 35,
        ]);

        
        $html = view('reports.sms.sms_preview', [
            'items' => $data,
            'asof' => $req->asof,
            'warco' => $req->warco
            ])->render();

        $mpdf->WriteHTML($html);
        
        $mpdf->Output();
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
