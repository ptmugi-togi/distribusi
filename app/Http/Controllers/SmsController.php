<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class SmsController extends Controller
{
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $mwarco = DB::table('mwarco_tbl')
            ->where('braco', Auth::user()->cabang)
            ->get();

        $mitype = DB::table('mitype_tbl')->get();
        $msgrup = DB::table('msgrup')->get();
        $mssgrup = DB::table('mssgrup')->get();

        $openPeriod = DB::table('tperiode')
            ->where('braco', Auth::user()->cabang)
            ->where('status', 'O')
            ->orderBy('periode', 'asc')
            ->first();

        $minDate = null;

        if ($openPeriod) {
            $year = substr($openPeriod->periode, 0, 4);
            $month = substr($openPeriod->periode, 4, 2);

            $minDate = "$year-$month-01";
        }

        return view('logistic.reports.sms.sms_create', compact('mwarco', 'mitype', 'msgrup', 'mssgrup', 'minDate'));
    }

    public function getData(Request $req)
    {
        $braco  = Auth::user()->cabang;
        $warco  = $req->warco;
        $asof   = $req->asof;
        $itype  = $req->itype;
        $sgrup  = $req->sgrup;
        $ssgrup = $req->ssgrup;
        $sort   = $req->sort;

        $lastOpen = DB::table('tperiode')
            ->where('braco', $braco)
            ->where('status', 'O')
            ->orderBy('periode', 'asc')
            ->value('periode');

        if (!$lastOpen) {
            return ['error' => 'Tidak ada periode OPEN di tperiode'];
        }

        $minAllowedDate = Carbon::createFromFormat('Ym', $lastOpen)->startOfMonth()->format('Y-m-d');
        if ($asof < $minAllowedDate) {
            return ['error' => "Tanggal As Of tidak boleh kurang dari awal periode aktif ({$minAllowedDate})"];
        }

        $maxStorh = DB::table('tstorh')
            ->where('braco', $braco)
            ->where('warco', $warco)
            ->whereRaw("CAST(tradt AS DATE) <= ?", [$asof])
            ->max('priod');

        $maxSisnh = DB::table('tsisnh')
            ->where('braco', $braco)
            ->where('warco', $warco)
            ->whereRaw("CAST(tradt AS DATE) <= ?", [$asof])
            ->max('priod');

        $maxTransPeriod = max($maxStorh ?? 0, $maxSisnh ?? 0, $lastOpen);

        $periods = [];
        $cursor = Carbon::createFromFormat('Ym', $lastOpen);
        $end    = Carbon::createFromFormat('Ym', $maxTransPeriod);

        while ($cursor->lte($end)) {
            $periods[] = $cursor->format('Ym');
            $cursor->addMonth();
        }

        $opronMasuk = DB::table('tstord as d')
            ->join('tstorh as h', 'h.bbmid', '=', 'd.bbmid')
            ->where('h.braco', $braco)
            ->where('h.warco', $warco)
            ->whereIn('h.priod', $periods)
            ->whereRaw("CAST(h.tradt AS DATE) <= ?", [$asof])
            ->pluck('d.opron');

        $opronKeluar = DB::table('toutg as d')
            ->leftJoin('tsisnh as h', 'h.bbkid', '=', 'd.bbkid')
            ->where('h.braco', $braco)
            ->where('d.warco', $warco)
            ->whereIn('h.priod', $periods)
            ->whereRaw("CAST(h.tradt AS DATE) <= ?", [$asof])
            ->pluck('d.opron');

        $opronSaldoAwal = DB::table('stobw_tbl')
            ->where('braco', $braco)
            ->where('warco', $warco)
            ->where('bbqoh', '>', 0)
            ->pluck('opron');

        $oprons = $opronMasuk
            ->merge($opronKeluar)
            ->merge($opronSaldoAwal)
            ->unique()
            ->values();

        if ($oprons->isEmpty()) {
            return collect();
        }

        $data = DB::table('mpromas as p')
            ->leftJoin('mitype_tbl as it', 'it.itype_id', '=', 'p.itype_id')
            ->leftJoin('msgrup as sg', 'sg.sgrup_id', '=', 'p.sgrup_id')
            ->leftJoin('mssgrup as ssg', 'ssg.ssgrup_id', '=', 'p.ssgrup_id')
            ->whereIn('p.opron', $oprons)

            ->leftJoin(DB::raw("
                (
                    SELECT opron, SUM(bbqoh) AS awal
                    FROM stobw_tbl
                    WHERE braco = '{$braco}' AND warco = '{$warco}'
                    GROUP BY opron
                ) AS sa
            "), "sa.opron", "=", "p.opron")

            ->leftJoin(DB::raw("
                (
                    SELECT d.opron, SUM(d.trqty) AS masuk
                    FROM tstord d
                    JOIN tstorh h ON h.bbmid = d.bbmid
                    WHERE h.warco = '{$warco}'
                    AND h.braco = '{$braco}'
                    AND h.priod IN (" . implode(',', array_map(fn($p) => "'$p'", $periods)) . ")
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
                    AND h.braco = '{$braco}'
                    AND h.priod IN (" . implode(',', array_map(fn($p) => "'$p'", $periods)) . ")
                    AND CAST(h.tradt AS DATE) <= '{$asof}'
                    GROUP BY d.opron
                ) AS tk
            "), "tk.opron", "=", "p.opron")

            ->when($itype, fn($q) => $q->where('p.itype_id', $itype))
            ->when($sgrup, fn($q) => $q->where('p.sgrup_id', $sgrup))
            ->when($ssgrup, fn($q) => $q->where('p.ssgrup_id', $ssgrup))

            ->whereRaw("(COALESCE(sa.awal, 0) > 0 OR COALESCE(tm.masuk, 0) > 0 OR COALESCE(tk.keluar, 0) > 0)")

            ->groupBy('p.opron')

            ->select(
                'p.opron',
                DB::raw("MAX(p.prona) as prona"),
                DB::raw("MAX(p.stdqu) as stdqu"),
                DB::raw("MAX(it.descr_itype) as descr_itype"),
                DB::raw("MAX(sg.sgrup_id) as sgrup_id"),
                DB::raw("MAX(sg.descr_sgrup) as descr_sgrup"),
                DB::raw("MAX(ssg.ssgrup_id) as ssgrup_id"),
                DB::raw("MAX(ssg.descr_ssgrup) as descr_ssgrup"),
                DB::raw("COALESCE(MAX(sa.awal), 0) as awal"),
                DB::raw("COALESCE(MAX(tm.masuk), 0) as masuk"),
                DB::raw("COALESCE(MAX(tk.keluar), 0) as keluar"),
                DB::raw("(COALESCE(MAX(sa.awal),0) + COALESCE(MAX(tm.masuk),0) - COALESCE(MAX(tk.keluar),0)) as akhir")
            )

            ->when($sort == 'opron', fn($q) => $q->orderBy('p.opron'))
            ->when($sort == 'prona', fn($q) => $q->orderBy(DB::raw('MAX(p.prona)')))
            ->when($sort == 'brand', fn($q) => $q->orderBy(DB::raw('MAX(p.brand)')))
            ->when(!$sort, function ($q) {
                $q->orderBy(DB::raw('MAX(it.descr_itype)'))
                  ->orderBy(DB::raw('MAX(ssg.descr_ssgrup)'))
                  ->orderBy('p.opron');
            })
            ->get();

        return $data;
    }

    public function preview(Request $req)
    {
        $data = $this->getData($req);

        if (isset($data['error'])) {
            return "<h3>{$data['error']}</h3>";
        }

        if ($data instanceof \Illuminate\Support\Collection) {
            $data = $data->unique('opron')->values();
        }

        $itype = DB::table('mitype_tbl')
            ->where('itype_id', $req->itype)
            ->value('descr_itype');

        $sgrup = DB::table('msgrup')
            ->where('sgrup_id', $req->sgrup)
            ->value('descr_sgrup');

        $ssgrup = DB::table('mssgrup')
            ->where('ssgrup_id', $req->ssgrup)
            ->value('descr_ssgrup');

        $html = view('logistic.reports.sms.sms_preview', [
            'items'  => $data,
            'asof'   => $req->asof,
            'warco'  => $req->warco,
            'itype'  => $itype,
            'sgrup'  => $sgrup,
            'ssgrup' => $ssgrup
        ])->render();

        $mpdf = new \Mpdf\Mpdf([
            'format' => 'A4',
            'margin_top' => 30,
            'margin_bottom' => 35,
        ]);

        $mpdf->WriteHTML($html);

        $mpdf->Output();
    }
}