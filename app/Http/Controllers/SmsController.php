<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use illuminate\Support\Facades\Log;

class SmsController extends Controller
{
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $mwarco = DB::table('mwarco_tbl')->get();

        $mitype = DB::table('mitype_tbl')->get();

        $msgrup = DB::table('msgrup')->get();

        $mssgrup = DB::table('mssgrup')->get();

        $openPeriod = DB::table('tperiode')
            ->where('status', 'O')
            ->orderBy('periode', 'asc')
            ->first();

        $minDate = null;

        if ($openPeriod) {
            $year = substr($openPeriod->periode, 0, 4);
            $month = substr($openPeriod->periode, 4, 2);

            $minDate = "$year-$month-01";
        }

        return view('logistic.reports.sms.sms_create' , compact('mwarco', 'mitype', 'msgrup', 'mssgrup', 'minDate'));
    }

    public function getData(Request $req)
    {
        $warco = $req->warco;
        $asof  = $req->asof;

        // Cari periode open terakhir di tperiode
        $lastOpen = DB::table('tperiode')
            ->where('status', 'O')
            ->orderByDesc('periode')
            ->value('periode');

        if (!$lastOpen) {
            return ['error' => 'Tidak ada periode OPEN di tperiode'];
        }

        // Cari periode transaksi terbesar di tstorh & tsisnh
        $maxStorh = DB::table('tstorh')
            ->where('warco', $warco)
            ->whereRaw("CAST(tradt AS DATE) <= ?", [$asof])
            ->max('priod');

        $maxSisnh = DB::table('tsisnh')
            ->where('warco', $warco)
            ->whereRaw("CAST(tradt AS DATE) <= ?", [$asof])
            ->max('priod');

        $maxTransPeriod = max($maxStorh ?? 0, $maxSisnh ?? 0);

        if (!$maxTransPeriod) {
            return ['error' => 'Tidak ada transaksi sebelum Asof'];
        }

        // Bangun array periode mulai lastOpen sampai maxTransPeriod
        $periods = [];
        $cursor = \Carbon\Carbon::createFromFormat('Ym', $lastOpen);
        $end    = \Carbon\Carbon::createFromFormat('Ym', $maxTransPeriod);

        while ($cursor->lte($end)) {
            $periods[] = $cursor->format('Ym');
            $cursor->addMonth();
        }

        // Ambil OPRON dari semua periode
        $opronMasuk = DB::table('tstord as d')
            ->join('tstorh as h', 'h.bbmid', '=', 'd.bbmid')
            ->where('h.warco', $warco)
            ->whereIn('h.priod', $periods)
            ->whereRaw("CAST(h.tradt AS DATE) <= ?", [$asof])
            ->pluck('d.opron');

        $opronKeluar = DB::table('toutg as d')
            ->leftJoin('tsisnh as h', 'h.bbkid', '=', 'd.bbkid')
            ->where('d.warco', $warco)
            ->whereIn('h.priod', $periods)
            ->whereRaw("CAST(h.tradt AS DATE) <= ?", [$asof])
            ->pluck('d.opron');

        $oprons = $opronMasuk->merge($opronKeluar)->unique()->values();

        if ($oprons->isEmpty()) {
            return collect();
        }

        // Siapkan list periode untuk di-embed di DB::raw
        $periodList = "'" . implode("','", $periods) . "'";

        $data = DB::table('mpromas as p')
            ->whereIn('p.opron', $oprons)

            ->leftJoin(DB::raw("
                (
                    SELECT opron, SUM(bbqoh) AS awal
                    FROM stobw_tbl
                    GROUP BY opron
                ) AS sa
            "), "sa.opron", "=", "p.opron")

            ->leftJoin(DB::raw("
                (
                    SELECT d.opron, SUM(d.trqty) AS masuk
                    FROM tstord d
                    JOIN tstorh h ON h.bbmid = d.bbmid
                    WHERE h.warco = '{$warco}'
                    AND h.priod IN ({$periodList})
                    AND CAST(h.tradt AS DATE) <= '{$asof}'
                    GROUP BY d.opron
                ) AS tm
            "), "tm.opron", "=", "p.opron")

            ->leftJoin(DB::raw("
                (
                    SELECT d.opron, SUM(d.trqty) AS keluar
                    FROM toutg d
                    JOIN tsisnh h ON h.bbkid = d.bbkid
                    WHERE d.warco = '{$warco}'
                    AND h.priod IN ({$periodList})
                    AND CAST(h.tradt AS DATE) <= '{$asof}'
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

        $html = view('logistic.reports.sms.sms_preview', [
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

        
        $html = view('logistic.reports.sms.sms_preview', [
            'items' => $data,
            'asof' => $req->asof,
            'warco' => $req->warco
            ])->render();

        $mpdf->WriteHTML($html);
        
        $mpdf->Output();
    }
}
