<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use illuminate\Support\Facades\Log;
use Carbon\Carbon;

use App\Models\CnHdr;
use App\Models\CnDtl;

class CnRetailController extends Controller
{
    public function index()
    {
        $userBraco = Auth::user()->cabang;

        $cnhdr = CnHdr::with('cndtls', 'customer')
                        ->where('braco', $userBraco)
                        ->where('invfc', 'SC')
                        ->where('srnfc', 'IC')
                        ->get();

        return view('fna.cn_retail.cn_retail_index', compact('cnhdr', 'userBraco'));
    }

    public function create()
    {
        $userBraco = Auth::user()->cabang;

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

        return view('fna.cn_retail.cn_retail_create', compact('minDate'));
    }

    public function store(Request $request)
    {
        DB::beginTransaction();

        // dd($request->all());

        try {
            $braco = Auth::user()->cabang;
            $formc = $request->formc;
            $year = Carbon::parse($request->crndt)->format('y');

            $last = DB::table('tcnh')
                ->where('braco', $braco)
                ->where('formc', $formc)
                ->whereRaw("LEFT(crnno,2) = ?", [$year])
                ->orderByDesc('crnno')
                ->lockForUpdate()
                ->value('crnno');

            if ($last) {
                $number = (int) substr($last, 2) + 1;
            } else {
                $number = 1;
            }

            $crnno = $year . str_pad($number, 4, '0', STR_PAD_LEFT);

            $cnid = $braco . $formc . $crnno;
            $bracoformc = $braco . $formc;

            CnHdr::create([
                'cnid'      => $cnid,
                'bracoformc' => $bracoformc,
                'braco'      => $request->braco,
                'warco'      => '-',
                'formc'      => $request->formc,
                'crnno'      => $crnno,
                'crndt'      => $request->crndt,
                'priod'      => $request->priod,
                'notar'      => $request->notar ?? '-',
                'cusno'      => $request->cusno,
                'invfc'      => $request->sorfc,
                'invno'      => $request->sorno,
                'ortyp'      => $request->ortyp,
                'vatax'      => $request->vatax,
                'curco'      => $request->curco,
                'crate'      => $request->crate,
                'gramt'      => $request->gross_hdr,
                'dpamt'      => $request->dpamt_hdr,
                'odisa'      => $request->odisa_hdr,
                'ntamt'      => $request->ntamt_hdr,
                'txamt'      => $request->txamt_hdr,
                'cramt'      => $request->cramt_hdr,
                'lauid'      => Auth::user()->name,
                'reaso'      => $request->reaso,
                'srnfc'      => $request->icfc,
                'srnno'      => $request->icno,
                'created_at' => now(),
                'created_by' => Auth::user()->name,
                'updated_at' => now(),
                'updated_by' => Auth::user()->name,
            ]);

            foreach ($request->opron as $i => $opron) {

                $prona = $request->prona[$i];
                $qty   = (int) $request->qtycn[$i];
                $stdqu = $request->stdqu[$i];

                $gramt = (float) $request->gramt_dtl[$i];
                $odisa = (float) $request->odisa_dtl[$i];

                $odisp = $gramt > 0 ? round(($odisa / $gramt) * 100, 2) : 0;

                CnDtl::create([
                    'cnid'          => $cnid,
                    'crnln'         => $i + 1,
                    'bracoformc'    => $bracoformc,
                    'braco'         => $request->braco,
                    'formc'         => $request->formc,
                    'crnno'         => $crnno,
                    'opron'         => $opron,
                    'prona'         => $prona,
                    'stdqu'         => $stdqu,
                    'qtycn'         => $qty,
                    'price'         => $request->price_dtl[$i],
                    'gramt'         => $gramt,
                    'odisp'         => $odisp,
                    'odisa'         => $odisa,
                    'ntamt'         => $request->ntamt_dtl[$i],
                    'dpamt'         => $request->dpamt_dtl[$i],
                    'noted'         => $request->noted[$i],
                ]);
            }

            DB::table('tinmas')
                ->where('braco', $request->braco)
                ->where('formc', $request->sorfc)
                ->where('invno', $request->sorno)
                ->update([
                    'cramt' => $request->cramt_hdr
                ]);

            DB::commit();
            return redirect()->route('cn_retail.index')->with('success', "data CN Retail \"$cnid\" berhasil disimpan.");

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Gagal simpan CN Retail:', ['error' => $e->getMessage()]);
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function getIC()
    {
        $userBraco = Auth::user()->cabang;

        $listic = DB::table('tstorh')
            ->join('mcusmas', 'mcusmas.cusno', '=', 'tstorh.cusno')
            ->where('tstorh.braco', $userBraco)
            ->where('tstorh.formc', 'IC')
            ->orderBy('tstorh.trano', 'asc')
            ->select(
                'tstorh.*',
                DB::raw("CONCAT(mcusmas.cusno, ' - ', mcusmas.cusna) as customer")
            )
            ->get();

        return response()->json($listic);
    }

    public function getSC(Request $request)
    {
        $userBraco = Auth::user()->cabang;

        $listsc = DB::table('tinmas')
                ->where('braco', $userBraco)
                ->where('formc', 'SC')
                ->where('dorfc', $request->dorfc)
                ->where('donom', $request->donom)
                ->orderBy('invno', 'asc')
                ->get();

        return response()->json($listsc);
    }

    public function getDetailSc(Request $request)
    {
        $userBraco = Auth::user()->cabang;

        $detailsc = DB::table('tindet')
                ->where('braco', $userBraco)
                ->where('formc', 'SC')
                ->where('invno', $request->sorno)
                ->get();

        return response()->json($detailsc);
    }

    public function checkInvoice(Request $request)
    {
        $userBraco = Auth::user()->cabang;

        $invoice = DB::table('tinmas')
            ->where('braco', $userBraco)
            ->where('formc', 'SC')
            ->where('invno', $request->sorno)
            ->first();

        $isPaid = false;

        if ($invoice) {
            $isPaid = !is_null($invoice->cramt) || !is_null($invoice->caval);
        }

        return response()->json([
            'invoice' => $invoice,
            'is_paid' => $isPaid,
        ]);
    }
}