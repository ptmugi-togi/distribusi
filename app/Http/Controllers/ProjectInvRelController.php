<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

use App\Models\ProjectInvRelHdr;
use App\Models\ProjectInvRelDtl;

class ProjectInvRelController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $userBraco = Auth::user()->cabang;

        $project_inv_rel = ProjectInvRelHdr::with('mbranch', 'mcusmas', 'mformcode', 'msreno')
            ->leftJoin('tcoreh as h', function($join){
                $join->on('h.sorno', '=', 'tinmas.sorno')
                    ->on('h.braco', '=', 'tinmas.braco');
            })
            ->where('tinmas.braco', $userBraco)
            ->where('tinmas.sorfc', 'SB')
            ->select('tinmas.*', 'h.dpist')
            ->get();

        $latestPeriod = DB::table('tperiode')
            ->where('braco', Auth::user()->cabang)
            ->orderByDesc('periode')
            ->first();

        $periodClosed = $latestPeriod && $latestPeriod->status === 'C';

        return view('fna.project_inv_rel.project_inv_rel_index', compact('project_inv_rel', 'periodClosed'));
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

        return view('fna.project_inv_rel.project_inv_rel_create', compact('periodeAktif', 'minDate', 'mbranch'));
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

            ProjectInvRelHdr::create([
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
                'gramt'      => $request->gross,
                'odisa'      => $request->odisa,
                'dpper'      => '0',
                'toppc'      => $request->toppc,
                'ntamt'      => $request->ntamt,
                'txamt'      => $request->txamt,
                'blamt'      => $request->blamt,
                'itext'      => $request->noted,
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
                $price = (float)$request->price[$i] ?? 0;
                $odisa = (float)$request->odisa_dtl[$i] ?? 0;

                $net = $price - $odisa;

                $netbe = $price - $odisa;

                ProjectInvRelDtl::create([
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
                    'gramt' => $price,
                    'price' => $price,
                    'odisa' => $odisa,
                    'netamt' => $net,
                    'dpper' => $request->dpper ?? '0',
                    'dpamt' => $request->dpamt ?? '0',
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

            DB::table('tprojd')
                ->where('braco', $request->braco)
                ->where('formc', $request->sorfc)
                ->where('sorno', $request->sorno)
                ->where('phase', $request->phase)
                ->update([
                    'invfc' => $request->formc,
                    'invno' => $request->invno
                ]);

            DB::commit();
            return redirect()->route('project_inv_rel.index')->with('success', "data Project Invoice Relelase \"$invid\" berhasil disimpan.");

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Gagal simpan Project Inv Rel:', ['error' => $e->getMessage()]);
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
    
    public function getOcSb()
    {
        $braco = Auth::user()->cabang;

        return DB::table('tproja as h')
            ->leftJoin('mcusmas as c', 'c.cusno', '=', 'h.cusno')
            ->join('tprojd as d', function ($join) {
                $join->on('d.ocsbid', '=', 'h.ocsbid')
                    ->whereNull('d.invfc')
                    ->whereNull('d.invno');
            })
            ->select(
                DB::raw("DATE_FORMAT(h.sordt, '%d-%m-%Y') as ocdt"),
                'h.sorno as value',
                DB::raw("CONCAT('SB',' - ',h.sorno) as text"),
                'h.cusno',
                'h.cuspo',
                'h.sreno',
                DB::raw("COALESCE(c.cusna, '-') as cust"),
                'h.curco',
                'h.crate'
            )
            ->where('h.braco', $braco)
            ->where(function($q) {
                $q->whereNull('h.resta')
                    ->orWhere('h.resta', '!=', 'C');
            })
            ->groupBy(
                'h.ocsbid',
                'h.sordt',
                'h.sorno',
                'h.cusno',
                'h.cuspo',
                'h.sreno',
                'c.cusna',
                'h.curco',
                'h.crate'
            )
            ->orderBy('h.sorno', 'desc')
            ->get();
    }

    public function getPhaseByOc(Request $request)
    {
        $braco = Auth::user()->cabang;
        $sorno = $request->sorno;

        return DB::table('tprojd as p')
            ->leftJoin('mtaxes as t', 't.braco', '=', 'p.braco')
            ->where('p.braco', $braco)
            ->where('p.sorno', $sorno)
            ->where(function($q){
                $q->where('sts01', '!=', 'I')
                ->orWhereNull('sts01');
            })
            ->whereNull('invno')
            ->select(
                'p.phase',
                'p.toppc',
                'p.descr',
                'p.gross',
                'p.odisa',
                'p.ntamt',
                't.taxes as taxes',
                DB::raw("(p.ntamt * (t.taxes/100)) AS txamt"),
                DB::raw("(p.ntamt + (p.ntamt * (t.taxes/100))) AS blamt"),
                'p.noted'
            )
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

    public function getOpronByOcSb(Request $request)
    {
        $braco = Auth::user()->cabang;
        $sorno = $request->sorno;
        $toppc = $request->toppc;

        $data = DB::table('tprojb as b')
            ->leftJoin('tprojd as p', function ($join) use ($toppc) {
                $join->on('b.ocsbid', '=', 'p.ocsbid')
                    ->where('p.toppc', $toppc);
            })
            ->where('b.braco', $braco)
            ->where('b.sorno', $sorno)
            ->select(
                'b.opron',
                'b.prona',
                'b.stdqu',
                'b.qtyor',
                'b.plist',
                DB::raw("(b.price * (p.toppc/100) * b.qtyor) as price"),
                DB::raw("(b.odisa * (p.toppc/100) * b.qtyor) as odisa"),
                'b.teknik',
            )
            ->get();

        return $data;
    }

    public function preview($id)
    {
        $projectinvrelhdr = \App\Models\ProjectInvRelHdr::with([
            'projectinvreldtls.mpromas',
            'mbranch',
            'mformcode',
            'msreno',
            'mcusmas',
            'mtaxes',
            'mdepo',
            'mstmas',
            'mstmas_print'
        ])->findOrFail($id);

        $html = view('fna.project_inv_rel.project_inv_rel_print', compact('projectinvrelhdr'))->render();

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
        $projectinvrelhdr = \App\Models\ProjectInvRelHdr::with([
            'projectinvreldtls.mpromas',
            'mbranch',
            'mformcode',
            'msreno',
            'mcusmas',
            'mtaxes',
            'mdepo',
            'mstmas',
            'mstmas_print'
        ])->findOrFail($id);

        $html = view('fna.project_inv_rel.project_inv_rel_print', compact('projectinvrelhdr'))->render();

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

        $pdfContent = $mpdf->Output("{$projectinvrelhdr->invid}.pdf", "S");

        return response($pdfContent)
        ->header('Content-Type', 'application/pdf')
        ->header('Content-Disposition', 'attachment; filename="'.$projectinvrelhdr->invid.'.pdf"');
    }
}
