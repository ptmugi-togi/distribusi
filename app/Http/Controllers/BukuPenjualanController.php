<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

use App\Models\Mbranch;

class BukuPenjualanController extends Controller
{
    public function create()
    {
        $braco = Auth::user()->cabang;
        $periode = DB::table('tperiode')
        ->where('status', 'O')
        ->orderBy('periode', 'DESC')
        ->first();

        $bdate_s = null;
        $bdate_e = null;

        if ($periode) {
            $date = Carbon::createFromFormat('Ym', $periode->periode);

            $bdate_s = $date->copy()->startOfMonth()->format('Y-m-d');
            $bdate_e = $date->copy()->endOfMonth()->format('Y-m-d');
        }

        return view('fna.reports.buku_penjualan.buku_penjualan_create' , compact('braco', 'bdate_s', 'bdate_e'));
    }

    private function getMstAcc()
    {
        $listAccNo = [
            '021.001', '431.001', '441.001', '442.001', '443.001', '444.001',
            '701.001', '701.002', '701.003', '701.004', '701.005', '701.006', '701.007',
            '703.001', '704.001', '704.003',
            '721.001', '721.002', '721.003', '721.004', '721.005', '721.006', '721.007',
            '723.001', '724.001', '724.003'
        ];

        return DB::table('mstacc')
            ->select('accno', 'accdesc')
            ->whereIn('accno', $listAccNo)
            ->get()
            ->keyBy('accno');
    }

    public function cetakBukuPenjualan(Request $req)
    {
        $start  = $req->bdate_s;
        $end    = $req->bdate_e;
        $branch = Mbranch::where('braco', Auth::user()->cabang)->first();
        $brana  = $branch->brana ?? Auth::user()->cabang;

        $items  = $this->queryBukuPenjualan($req);
        $mstacc = $this->getMstAcc();

        return view('fna.reports.buku_penjualan.buku_penjualan_preview', compact('items', 'start', 'end', 'brana', 'mstacc'));
    }

    private function queryBukuPenjualan(Request $req)
    {
        $braco = Auth::user()->cabang;
        $start = $req->bdate_s;
        $end   = $req->bdate_e;

        $sc = DB::table('tinmas as h')
            ->join('tindet as d', 'h.invid', '=', 'd.invid')
            ->leftJoin('mpromas as p', 'd.opron', '=', 'p.opron')
            ->leftJoin('mcusmas as c', 'h.cusno', '=', 'c.cusno')
            ->where('h.braco', $braco)
            ->where('h.formc', 'SC')
            ->whereBetween('h.invdt', [$start, $end])
            ->select([
                'h.formc',
                'h.invid',
                'h.invno',
                'h.invdt',
                'h.gramt as header_gramt',
                'h.dpamt',
                'h.instf',
                'h.txamt',
                'h.braco',
                'h.cusno',
                'h.curco',
                'h.sorfc',
                'h.sorno',
                'h.fpnum',
                'h.invtp',

                'c.cusna',

                'd.*',

                // GROUP SC
                'p.acgrup as group',
            ])
            ->orderBy('h.invdt')
            ->orderBy('h.invno')
            ->get();

        $sdTinta = DB::table('tinmas as h')
            ->join('tinta as d', function ($join) {
                $join->on('d.formc', '=', 'h.formc')
                    ->on('d.invno', '=', 'h.invno')
                    ->on('d.braco', '=', 'h.braco');
            })
            ->leftJoin('mcusmas as c', 'h.cusno', '=', 'c.cusno')
            ->where('h.braco', $braco)
            ->where('h.formc', 'SD')
            ->whereBetween('h.invdt', [$start, $end])
            ->select([
                'h.formc',
                'h.invid',
                'h.invno',
                'h.invdt',
                'h.gramt as header_gramt',
                'h.dpamt',
                'h.instf',
                'h.txamt',
                'h.braco',
                'h.cusno',
                'h.curco',
                'h.dorfc',
                'h.donom',
                'h.sorfc',
                'h.sorno',
                'h.fpnum',
                'h.invtp',

                'c.cusna',

                'd.*',

                // GROUP SD TINTA
                'd.tofee as group',
            ])
            ->orderBy('h.invdt')
            ->orderBy('h.invno')
            ->get();

        $sdTintc = DB::table('tinmas as h')
            ->join('tintc as d', function ($join) {
                $join->on('d.formc', '=', 'h.formc')
                    ->on('d.invno', '=', 'h.invno')
                    ->on('d.braco', '=', 'h.braco');
            })
            ->leftJoin('mpromas as p', 'd.opron', '=', 'p.opron')
            ->leftJoin('mcusmas as c', 'h.cusno', '=', 'c.cusno')
            ->where('h.braco', $braco)
            ->where('h.formc', 'SD')
            ->whereBetween('h.invdt', [$start, $end])
            ->select([
                'h.formc',
                'h.invid',
                'h.invno',
                'h.invdt',
                'h.gramt as header_gramt',
                'h.dpamt',
                'h.instf',
                'h.txamt',
                'h.braco',
                'h.cusno',
                'h.curco',
                'h.dorfc',
                'h.donom',
                'h.sorfc',
                'h.sorno',
                'h.fpnum',
                'h.invtp',

                'c.cusna',

                'd.*',

                'p.acgrup as group',
            ])
            ->orderBy('h.invdt')
            ->orderBy('h.invno')
            ->get();

        return $sc->concat($sdTinta)
            ->concat($sdTintc)
            ->sortBy(function ($row) {
                return $row->invdt . '-' . sprintf('%010d', (int)$row->invno);
            })
            ->values();
    }

    public function getData(Request $req)
    {
        $data = $this->queryBukuPenjualan($req);

        return response()->json([
            'data'  => $data,
            'total' => $data->count(),
        ]);
    }

    public function previewBukuPenjualan(Request $req)
    {
        $data = $this->queryBukuPenjualan($req);
        $mstacc = $this->getMstAcc();

        $branch = Mbranch::where(
            'braco',
            Auth::user()->cabang
        )->first();

        $html = view(
            'fna.reports.buku_penjualan.buku_penjualan_preview',
            [
                'items' => $data,
                'start' => $req->bdate_s,
                'end'   => $req->bdate_e,
                'braco' => Auth::user()->cabang,
                'brana' => $branch->brana ?? '',
                'mstacc' => $mstacc
            ]
        )->render();

        $mpdf = new \Mpdf\Mpdf([
            'format'        => 'Folio-L',
            'margin_top'    => 30,
            'margin_bottom' => 20,
        ]);

        $mpdf->SetHTMLFooter('
            <div style="text-align:right; font-size:9pt;">
                {PAGENO}/{nbpg}
            </div>
        ');

        $mpdf->WriteHTML($html);
        $mpdf->Output();
    }
}