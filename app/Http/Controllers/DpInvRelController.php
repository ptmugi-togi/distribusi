<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

use App\Models\DpInvRelHdr;
use App\Models\DpInvRelDtl;

class DpInvRelController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $userBraco = Auth::user()->cabang;

        $dp_inv_rel = DpInvRelHdr::with('mbranch', 'mcusmas', 'mformcode', 'msreno')
            ->leftJoin('tcoreh as h', function($join){
                $join->on('h.sorno', '=', 'tinmas.sorno')
                    ->on('h.braco', '=', 'tinmas.braco');
            })
            ->where('tinmas.braco', $userBraco)
            ->where('tinmas.sorfc', 'SA')
            ->select('tinmas.*', 'h.dpist')
            ->get();

        $latestPeriod = DB::table('tperiode')
            ->where('braco', Auth::user()->cabang)
            ->orderByDesc('periode')
            ->first();

        $periodClosed = $latestPeriod && $latestPeriod->status === 'C';

        return view('fna.dp_inv_rel.dp_inv_rel_index', compact('dp_inv_rel', 'periodClosed'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $minDate = null;

        $periodeAktif = DB::table('tperiode')
            ->where('braco', auth()->user()->cabang)
            ->where('status', 'O')
            ->orderBy('periode', 'desc')
            ->first();

        if ($periodeAktif) {
            $priod = $periodeAktif->periode;
            $year = substr($periodeAktif->periode, 0, 4);
            $month = substr($periodeAktif->periode, 4, 2);
            $minDate = "$year-$month-01";
        }

        $mbranch = DB::table('mbranches')->get();

        return view('fna.dp_inv_rel.dp_inv_rel_create', compact('periodeAktif', 'minDate', 'mbranch'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        DB::beginTransaction();

        // dd($request->all());

        try {
            $invid = $request->braco .  $request->formc . $request->invno;

            $bracoformc = $request->braco . $request->formc;

            $invdt = Carbon::parse($request->invdt);
            $topay = (int) $request->topay;

            $duedt = $invdt->copy()->addDays($topay);

            DpInvRelHdr::create([
                'invid'      => $invid,
                'bracoformc' => $bracoformc,
                'braco'      => $request->braco,
                'warco'      => '-',
                'formc'      => $request->formc,
                'invno'      => $request->invno,
                'invdt'      => $request->invdt,
                'priod'      => $request->priod,
                'duedt'      => $duedt,
                'delto'      => $request->shpto ?? '0',
                'sorfc'      => $request->sorfc,
                'sorno'      => $request->sorno,
                'cusno'      => $request->cusno,
                'sreno'      => $request->sreno,
                'cuspo'      => $request->cuspo,
                'topay'      => $topay,
                'vatax'      => $request->vatax,
                'curco'      => $request->curco,
                'crate'      => $request->crate,
                'gramt'      => $request->dpamt,
                'odisa'      => $request->odisa,
                'dpper'      => $request->dpper,
                'ntamt'      => $request->ntamt,
                'txamt'      => $request->txamt,
                'blamt'      => $request->blamt,
                'itext'      => $request->itext,
                'invtp'      => '1',
                'created_at' => now(),
                'created_by' => Auth::user()->name,
                'updated_at' => now(),
                'updated_by' => Auth::user()->name,
            ]);

            foreach ($request->opron as $i => $opron) {

                $prona = $request->prona[$i];
                $qty   = (int) $request->rqqty[$i];
                $stdqu  = $request->stdqu[$i];
                $price = $request->price[$i] ?? 0;
                $odisa = $request->odisa[$i] ?? 0;

                $net = $price - $odisa;

                $netbe = $request->gross_dtl[$i] - $request->odisa_dtl[$i];

                DpInvRelDtl::create([
                    'invid' => $invid,
                    'braco' => $request->braco,
                    'formc' => $request->formc,
                    'invno' => $request->invno,
                    'sorfc' => $request->sorfc,
                    'sorno' => $request->sorno,
                    'opron' => $opron,
                    'prona' => $prona,
                    'stdqu' => $stdqu,
                    'qtyin' => $qty,
                    'price' => $price,
                    'gramt' => $request->gross_dtl[$i],
                    'odisa' => $request->odisa_dtl[$i],
                    'netamt' => $net,
                    'dpper' => $request->dpper,
                    'dpamt' => $request->dpamt,
                    'netbe' => $netbe,
                ]);
            }

            DB::table('tcoreh')
                ->where('braco', $request->braco)
                ->where('formc', $request->sorfc)
                ->where('sorno', $request->sorno)
                ->update([
                    'dpist' => 'Y'
                ]);

            DB::commit();
            return redirect()->route('dp_inv_rel.index')->with('success', "data DP Invoice Relelase \"$invid\" berhasil disimpan.");

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Gagal simpan DP Inv Rel:', ['error' => $e->getMessage()]);
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function generateInvno(Request $request)
    {
        $braco = auth()->user()->cabang;
        $formc = $request->formc;
        $invdt = $request->invdt;
        
        $year = Carbon::parse($invdt)->format('y');

        $last = DB::table('tinmas')
            ->where('braco', $braco)
            ->where('formc', $formc)
            ->whereRaw("LEFT(invno,2) = ?", [$year])
            ->orderBy('invno','desc')
            ->value('invno');

        if ($last) {
            $number = (int)substr($last, 2) + 1;
        } else {
            $number = 1;
        }

        return $year . str_pad($number, 4, '0', STR_PAD_LEFT);
    }
    
    public function getOcSa()
    {
        $braco = Auth::user()->cabang;

        $sa = DB::table('tcoreh as h')
            ->leftJoin(DB::raw("
                (SELECT ocid, SUM(odisa) as total_odisa 
                FROM tcored 
                GROUP BY ocid) dsum
            "), 'dsum.ocid', '=', 'h.ocid')
            ->leftJoin('mcusmas as c', 'c.cusno', '=', 'h.cusno')
            ->leftJoin('mstmas as m', function($join) use ($braco){
                $join->on('m.cusno', '=', 'h.cusno')
                    ->on('m.shpto', '=', 'h.delto')
                    ->on('m.braco', '=', DB::raw("'$braco'"));
            })
            ->leftJoin('mtaxes as t', 't.braco', '=', 'h.braco')
            ->select(
                'h.sorno as value',
                DB::raw("'SA' as type"),
                'h.cusno as cusno',
                'h.sreno as sreno',
                'h.cuspo as cuspo',
                'h.sorno',
                DB::raw("DATE_FORMAT(h.sordt, '%d-%m-%Y') as ocdt"),
                DB::raw("COALESCE(c.cusna, '-') as cust"),
                DB::raw("CONCAT('SA',' - ',h.sorno) as text"),
                'm.shpto as shpto',
                'm.shpnm as shpnm',
                'm.phone as phone',
                'm.contp as contp',
                'm.province as province',
                'm.kabupaten as kabupaten',
                'm.deliveryaddress as address',
                't.taxes as taxes',
                'h.dpper as dpper',
                'h.gross as gross',
                DB::raw("(h.gross * (h.dpper/100)) AS dpamt"),
                DB::raw("dsum.total_odisa * (h.dpper/100) AS odisa"),
                DB::raw("(h.gross * (h.dpper/100)) - (dsum.total_odisa * (h.dpper/100)) AS ntamt"),
                DB::raw("((h.gross * (h.dpper/100)) - (dsum.total_odisa * (h.dpper/100))) * (COALESCE(t.taxes,0)/100) AS txamt"),
                DB::raw("((h.gross * (h.dpper/100)) - (dsum.total_odisa * (h.dpper/100))) + (((h.gross * (h.dpper/100)) - (dsum.total_odisa * (h.dpper/100))) * (COALESCE(t.taxes,0)/100)) AS blamt"),
                'h.curco as curco',
                'h.crate as crate',
                'h.topay as topay',
            )
            ->where('h.braco', $braco)
            ->where(function($q) {
                $q->where('h.dpist', '!=', 'Y')
                ->orWhereNull('h.dpist');
            })
            ->where('h.dpper', '>' , 0);

            return $sa
            ->get();
    }

    public function getMainAddress(Request $request)
    {
        $cusno = $request->cusno;

        $data = DB::table('mcusmas')
            ->where('cusno', $cusno)
            ->first();

        if (!$data) {
            return response()->json([
                'message' => 'Address tidak ditemukan'
            ], 404);
        }

        return response()->json([
            'name' => $data->cusna ?? '',
            'attn' => $data->ofcon ?? '',
            'phone' => $data->offph ?? '',
            'address' => collect([
                $data->offad,
                $data->offad2,
                $data->offad3,
                $data->offad4
            ])
        ]);
    }

    public function getOpronByOcSa(Request $request)
    {
        $braco = Auth::user()->cabang;
        $sorno = $request->sorno;

        $data = DB::table('tcored as d')
            ->join('tcoreh as h', function($join){
                $join->on('h.sorno', '=', 'd.sorno')
                    ->on('h.braco', '=', 'd.braco');
            })
            ->where('d.braco', $braco)
            ->where('d.sorno', $sorno)
            ->select(
                'd.opron',
                'd.prona',
                'd.stdqu',
                'd.qtyor',
                DB::raw('d.plist * (h.dpper / 100) as plist'),
                DB::raw('d.price * (h.dpper / 100) as price'),
                DB::raw('d.gross * (h.dpper / 100) as gross_dtl'),
                DB::raw('d.odisa * (h.dpper / 100) as odisa_dtl'),
                'd.teknik',
                'd.noted',
            )
            ->get();

        return $data;
    }

    public function preview($id)
    {
        $dpinvrelhdr = \App\Models\DpInvrelHdr::with([
            'dpinvreldtls.mpromas',
            'mbranch',
            'mformcode',
            'msreno',
            'mcusmas',
            'mtaxes',
            'mdepo',
            'mstmas',
        ])->findOrFail($id);

        $bomList = DB::table('tprojc')
            ->join('mpromas', 'tprojc.opron', '=', 'mpromas.opron')
            ->where('tprojc.ocsbid', $id)
            ->select(
                'tprojc.uopron',
                'tprojc.opron',
                'tprojc.trqty',
                'tprojc.stdqu',
                'mpromas.prona'
            )
            ->orderBy('tprojc.opron')
            ->get()
            ->groupBy('uopron');

        $html = view('fna.dp_inv_rel.dp_inv_rel_print', compact('dpinvrelhdr'))->render();

        $mpdf = new \Mpdf\Mpdf([
            'format' => 'A4',
            'margin_top' => 10,
            'margin_bottom' => 60,
        ]);

        $mpdf->SetHTMLFooter('
            <div style="text-align:right; font-size:9pt;">
                {PAGENO}/{nbpg}
            </div>
        ');

        $mpdf->WriteHTML($html);

        $mpdf->SetHTMLFooterByName('myFooter', 'E_ALL');

        $mpdf->Output(); 
    }

    public function print($id) 
    {
        $dpinvrelhdr = \App\Models\DpInvrelHdr::with([
            'dpinvreldtls.mpromas',
            'mbranch',
            'mformcode',
            'msreno',
            'mcusmas',
            'mtaxes',
            'mdepo',
            'mstmas',
        ])->findOrFail($id);

        $html = view('fna.dp_inv_rel.dp_inv_rel_print', compact('dpinvrelhdr'))->render();

        // increment counter total print
        DB::table('tinmas')
        ->where('invid', $id)
        ->update([
            'prctr' => DB::raw('prctr + 1')
        ]);

        $mpdf = new \Mpdf\Mpdf([
            'format' => 'A4',
            'margin_top' => 10,
            'margin_bottom' => 60,
        ]);


        $mpdf->SetHTMLFooter('
            <div style="text-align:right; font-size:9pt;">
                {PAGENO}/{nbpg}
            </div>
        ');

        $mpdf->WriteHTML($html);

        $mpdf->SetHTMLFooterByName('myFooter', 'E_ALL');

        $pdfContent = $mpdf->Output("{$dpinvrelhdr->invid}.pdf", "S");

        return response($pdfContent)
        ->header('Content-Type', 'application/pdf')
        ->header('Content-Disposition', 'attachment; filename="'.$dpinvrelhdr->invid.'.pdf"');
    }
}
