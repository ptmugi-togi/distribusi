<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

use App\Models\McHdr;
use App\Models\McDtl;

class McInvoiceReleaseController extends Controller
{
    public function index()
    {
        $userBraco = Auth::user()->cabang;
        
        $sir = DB::table('tinmas')
            ->where('braco', $userBraco)
            ->where('dorfc', 'MC')
            ->get();

        $latestPeriod = DB::table('tperiode')
            ->where('braco', Auth::user()->cabang)
            ->orderByDesc('periode')
            ->first();

        $periodClosed = $latestPeriod && $latestPeriod->status === 'C';

        return view('teknik.mc_invoice_release.mc_invoice_release_index', compact('sir', 'userBraco', 'periodClosed'));
    }

    public function create()
    {
        $userBraco = Auth::user()->cabang;

        $mc = McHdr::with('mbranch', 'mcusmas', 'mformcode')
            ->where('braco', $userBraco)
            ->get();

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
    
        return view('teknik.mc_invoice_release.mc_invoice_release_create', compact('minDate'));
    }

    public function store(Request $request)
    {
        // dd($request->all());
        DB::beginTransaction();

        try {

            $invid = $request->braco .  $request->formc . $request->invno;
            $bracoformc = $request->braco . $request->formc;
            $odisp = 0;

            if($request->gramt > 0){
                $odisp = ($request->odisa / $request->gramt) * 100;
            }

            $odisp = round($odisp, 2);

            $tinmasId = DB::table('tinmas')->insertGetId([
                'invid' => $invid,
                'bracoformc' => $bracoformc,
                'braco' => $request->braco,
                'formc' => $request->formc,
                'invno' => $request->invno,
                'invdt' => $request->invdt,
                'priod' => $request->priod,
                'duedt' => $request->duedt,
                'delto' => $request->address_source,
                'dorfc' => $request->dorfc,
                'donom' => $request->donom,
                'cusno' => $request->cusno,
                'cuspo' => $request->cuspo,
                'curco' => $request->curco,
                'crate' => $request->crate,
                'gramt' => $request->gramt ?? 0,
                'ntamt' => $request->ntamt ?? 0,
                'dpamt' => $request->dpamt ?? 0,
                'vatax' => $request->vatax ?? 0,
                'txamt' => $request->txamt ?? 0,
                'odisa' => $request->odisa ?? 0,
                'blamt' => $request->blamt ?? 0,
                'vatax' => $request->vatax ?? 0,
                'itext' => $request->noteh,
                'divco' => $request->divco,
                'created_at'=>now(),
                'created_by'=>Auth::user()->name,
                'updated_at'=>now(),
                'updated_by'=>Auth::user()->name
            ]);

            if($request->product_opron){
                foreach($request->product_opron as $i=>$opron){
                    DB::table('tinta')->insert([
                        'braco'=>$request->braco,
                        'formc'=>$request->formc,
                        'invno'=>$request->invno,
                        'invln'=>$i+1,
                        'tofee'=>'MC',
                        'descr'=>null,
                        'opron'=>$opron,
                        'trqty'=>1,
                        'lotno'=>$request->product_lotno[$i] ?? null,

                        // dari phase
                        'gramt'=>$request->gramt ?? 0,
                        'odisa'=>$request->odisa ?? 0,
                        'odisp'=>$odisp,
                        'netbe'=>$request->ntamt ?? 0,
                    ]);
                }
            }

            DB::table('tmcd2')
                ->where('braco', $request->braco)
                ->where('formc', $request->dorfc)
                ->where('refno', $request->donom)
                ->update([
                    'invfc' => $request->formc,
                    'invno' => $request->invno,
                    'wdelto' => $request->address_source,
                    'winvdt' => $request->invdt,
                    'wduedt' => $request->duedt,
                    'wpriod' => $request->priod,
                    'witext' => $request->noteh
                ]);

            DB::commit();

            return redirect()
                ->route('mc_invoice_release.index')
                ->with('success',"Invoice \"$invid\" berhasil dibuat");

        } catch(\Exception $e){
            DB::rollBack();
            \Log::error('Gagal simpan SD:', ['error' => $e->getMessage()]);
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

    public function searchMc(Request $request)
    {
        $mc = DB::table('tmch')
            ->where('braco', Auth::user()->cabang)
            ->where('refno','like','%'.$request->search.'%')
            ->orderBy('refno','desc')
            ->limit(20)
            ->get();

        return response()->json($mc);
    }

    public function getMc($mcid)
    {
        $mc = DB::table('tmch')
            ->leftJoin('mcusmas','tmch.cusno','=','mcusmas.cusno')
            ->where('tmch.mcid',$mcid)
            ->select(
                'tmch.*',
                'mcusmas.cusna',
                'mcusmas.offad',
                'mcusmas.offad2',
                'mcusmas.offad3',
                'mcusmas.offad4'
            )
            ->first();


        $delivery = DB::table('mstmas')
            ->where('cusno',$mc->cusno)
            ->select(
                'shpto',
                'deliveryaddress'
            )
            ->get();


        return response()->json([
            'mc' => $mc,
            'delivery' => $delivery
        ]);
    }

    public function getMcProduct($mcid)
    {
        $product = DB::table('tmcd as a')
            ->leftJoin('mpromas as b','a.opron','=','b.opron')
            ->where('a.mcid',$mcid)
            ->select(
                'a.opron',
                'a.lotno',
                'b.prona'
            )
            ->get();


        return response()->json([
            'product'=>$product
        ]);
    }

    public function getMcDetail($mcid)
    {
        $detail = DB::table('tmcd2')
            ->where('mcid', $mcid)
            ->orderBy('phase')
            ->get();

        return response()->json([
            'detail' => $detail
        ]);
    }
}