<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

use App\Models\RetailInvRelHdr;
use App\Models\RetailInvRelDtl;

class RetailInvRelController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $userBraco = Auth::user()->cabang;

        $retail_inv_rel = RetailInvRelHdr::with('mbranch', 'mcusmas', 'mformcode', 'msreno')
            ->leftJoin('tcoreh as h', function($join){
                $join->on('h.sorno', '=', 'tinmas.sorno')
                    ->on('h.braco', '=', 'tinmas.braco');
            })
            ->where('tinmas.braco', $userBraco)
            ->where('tinmas.dorfc', 'DO')
            ->select('tinmas.*', 'h.dpist')
            ->get();

        $latestPeriod = DB::table('tperiode')
            ->where('braco', Auth::user()->cabang)
            ->orderByDesc('periode')
            ->first();

        $periodClosed = $latestPeriod && $latestPeriod->status === 'C';

        return view('fna.retail_inv_rel.retail_inv_rel_index', compact('retail_inv_rel', 'periodClosed'));
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

        return view('fna.retail_inv_rel.retail_inv_rel_create', compact('periodeAktif', 'minDate', 'mbranch'));
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

            RetailInvRelHdr::create([
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
                'dorfc'      => $request->sorfc,
                'donom'      => $request->sorno,
                'sorfc'      => $request->rfc01,
                'sorno'      => $request->ref01,
                'cusno'      => $request->cusno,
                'sreno'      => $request->sreno,
                'cuspo'      => $request->cuspo,
                'topay'      => $topay,
                'vatax'      => $request->vatax,
                'curco'      => $request->curco,
                'crate'      => $request->crate,
                'gramt'      => $request->gross,
                'odisa'      => $request->odisa,
                'dpper'      => $request->dpper,
                'dpamt'      => $request->dpamt,
                'ntamt'      => $request->ntamt,
                'txamt'      => $request->txamt,
                'blamt'      => $request->blamt,
                'itext'      => $request->itext,
                'invtp'      => '2',
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

                RetailInvRelDtl::create([
                    'invid' => $invid,
                    'braco' => $request->braco,
                    'formc' => $request->formc,
                    'invno' => $request->invno,
                    'sorfc' => $request->sorfc,
                    'sorno' => $request->sorno,
                    'opron' => $opron,
                    'prona' => $prona,
                    'lotno' => $request->lotno[$i],
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
    
                DB::table('tcored')
                    ->where('braco', $request->braco)
                    ->where('formc', $request->rfc01)
                    ->where('sorno', $request->ref01)
                    ->where('opron', $opron)
                    ->update([
                        'qtyin' => $qty,
                    ]);
            }

            DB::table('tsisnh')
                ->where('braco', $request->braco)
                ->where('formc', $request->sorfc)
                ->where('trano', $request->sorno)
                ->update([
                    'rfc02' => $request->formc,
                    'ref02' => $request->invno,
                ]);

            DB::commit();
            return redirect()->route('retail_inv_rel.index')->with('success', "data DP Invoice Relelase \"$invid\" berhasil disimpan.");

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
    
    public function getDo()
    {
        $braco = Auth::user()->cabang;

        $do = DB::table('tsisnh as h')
            ->leftJoin(DB::raw("
                (SELECT sorno, topay as topay, curco as curco, crate as crate, dpper as dpper, sreno as sreno
                FROM tcoreh 
                WHERE braco = '$braco') as th
            "), 'th.sorno', '=', 'h.ref01')
            ->leftJoin('mcusmas as c', 'c.cusno', '=', 'h.cusno')
            ->leftJoin('mstmas as m', function($join) use ($braco){
                $join->on('m.cusno', '=', 'h.cusno')
                    ->on('m.shpto', '=', 'h.shpto')
                    ->on('m.braco', '=', DB::raw("'$braco'"));
            })
            ->leftJoin('mtaxes as t', 't.braco', '=', 'h.braco')
            ->select(
                'h.trano as value',
                'h.formc as formc',
                'h.cusno as cusno',
                'h.cuspo as cuspo',
                'h.trano',
                'h.rfc01 as rfc01',
                'h.ref01 as ref01',
                'th.sreno as sreno',
                'th.topay as topay',
                'th.curco as curco',
                'th.crate as crate',
                'th.dpper as dpper',
                DB::raw("DATE_FORMAT(h.tradt, '%d-%m-%Y') as ocdt"),
                DB::raw("COALESCE(c.cusna, '-') as cust"),
                DB::raw("CONCAT('SA',' - ',h.ref01) as text"),
                'm.shpto as shpto',
                'm.shpnm as shpnm',
                'm.phone as phone',
                'm.contp as contp',
                'm.province as province',
                'm.kabupaten as kabupaten',
                'm.deliveryaddress as address',
                't.taxes as taxes',
            )
            ->where('h.braco', $braco)
            ->where('h.formc', 'DO')
            ->whereNull('h.rfc02')
            ->whereNotNull('h.rfc01')
            ->whereNotNull('h.ref01')
            ->orderBy('h.trano', 'desc');

            return $do
            ->distinct()
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

    public function getOpronByDoSa(Request $request)
    {
        $braco = Auth::user()->cabang;
        $refno = $request->refno;
        $trano = $request->trano;

        $data = DB::table('tcored as d')
            ->join('toutg as t', function($join){
                $join->on('t.opron', '=', 'd.opron')
                    ->on('t.reffc', '=', 'd.formc')
                    ->on('t.refno', '=', 'd.sorno');
            })
            ->join('tcoreh as h', 'h.ocid', '=', 'd.ocid')
            ->where('t.trano', $trano)
            ->where('d.braco', $braco)
            ->where('d.sorno', $refno)
            ->select(
                'd.opron',
                'd.prona',
                't.lotno',
                'd.stdqu',
                't.trqty', 
                'd.odisp',
                DB::raw('d.plist * t.trqty as plist'),
                DB::raw('d.price as price'),
                DB::raw('d.price * t.trqty as gross_dtl'),
                DB::raw('d.odisa * t.trqty as odisa_dtl'),
                DB::raw('((d.gross * t.trqty) - (d.odisa * t.trqty)) * (h.dpper / 100) as dpamt'),
                'd.teknik',
                't.noted',
                'h.dpper',
            )
            ->get();

        return $data;
    }

    public function preview($id)
    {
        $retailinvrelhdr = \App\Models\RetailInvRelHdr::with([
            'retailinvreldtls.mpromas',
            'mbranch',
            'mformcode',
            'msreno',
            'mcusmas',
            'mtaxes',
            'mdepo',
            'mstmas',
            'mstmas_print'
        ])->findOrFail($id);

        $html = view('fna.retail_inv_rel.retail_inv_rel_print', compact('retailinvrelhdr'))->render();

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
        $retailinvrelhdr = \App\Models\RetailInvRelHdr::with([
            'retailinvreldtls.mpromas',
            'mbranch',
            'mformcode',
            'msreno',
            'mcusmas',
            'mtaxes',
            'mdepo',
            'mstmas',
            'mstmas_print'
        ])->findOrFail($id);

        $html = view('fna.retail_inv_rel.retail_inv_rel_print', compact('retailinvrelhdr'))->render();

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

        $pdfContent = $mpdf->Output("{$retailinvrelhdr->invid}.pdf", "S");

        return response($pdfContent)
        ->header('Content-Type', 'application/pdf')
        ->header('Content-Disposition', 'attachment; filename="'.$retailinvrelhdr->invid.'.pdf"');
    }
}
