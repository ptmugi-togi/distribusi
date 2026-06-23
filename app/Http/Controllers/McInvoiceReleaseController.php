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
                'toppc' => $request->toppc,
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
                'phase' => $request->phase,
                'created_at'=>now(),
                'created_by'=>Auth::user()->name,
                'updated_at'=>now(),
                'updated_by'=>Auth::user()->name
            ]);

            if($request->product_opron){
                foreach($request->product_opron as $i=>$opron){
                    DB::table('tinta')->insert([
                        'invid'=>$invid,
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
                ->where('phase', $request->phase)
                ->update([
                    'invfc' => $request->formc,
                    'invno' => $request->invno,
                    'wdelto' => $request->address_source,
                    'winvdt' => $request->invdt,
                    'wduedt' => $request->duedt,
                    'wpriod' => $request->priod,
                    'sts01'  => 'I',
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

    public function preview($id)
    {
        $tinmas = DB::table('tinmas')
            ->leftJoin('mbranches','mbranches.braco','=','tinmas.braco')
            ->leftJoin('mformcode_tbl','mformcode_tbl.formc','=','tinmas.formc')
            ->where('tinmas.invid',$id)
            ->select(
                'tinmas.*',
                'mbranches.bank_acc',
                'mbranches.bank_address',
                'mbranches.email',
                'mformcode_tbl.pos4',
                'mformcode_tbl.name4',
                'mformcode_tbl.docd1',
                'mformcode_tbl.docd2'
            )
            ->first();

        $products = DB::table('tinta')
            ->leftJoin('mpromas','mpromas.opron','=','tinta.opron')
            ->where('tinta.invid',$tinmas->invid)
            ->select(
                'tinta.*',
                'mpromas.prona'
            )
            ->orderBy('tinta.invln')
            ->get();

        $customer = DB::table('mcusmas')
            ->where('cusno',$tinmas->cusno)
            ->first();

        $services = DB::table('tinta')
            ->leftJoin('mpromas', 'mpromas.opron', '=', 'tinta.opron')
            ->leftJoin('tmcd', 'tmcd.opron', '=', 'tinta.opron')
            ->where('tinta.invid', $id)
            ->select(
                'tinta.*',
                'mpromas.prona',
                'mpromas.stdqu',
                'tmcd.price'
            )
            ->orderBy('tinta.invln')
            ->get();

        $services = $services->map(function($item) use ($tinmas){
            $price = $item->price ?? 0;
            $item->calc_price = $price * ($tinmas->toppc / 100);

            return $item;
        });

        if($tinmas->delto == 0){
            $shipto = $customer;
        }else{
            $shipto = DB::table('mstmas')
                ->where('cusno',$tinmas->cusno)
                ->where('shpto',$tinmas->delto)
                ->first();
        }

        $html = view('teknik.mc_invoice_release.mc_invoice_release_print', compact(
            'tinmas',
            'products',
            'customer',
            'services',
            'shipto'
        ))->render();

        $mpdf = new \Mpdf\Mpdf([
            'format'=>'A4',
            'margin_top'=>8,
            'margin_bottom'=>45,
            'margin_left'=>8,
            'margin_right'=>8,
        ]);

        $mpdf->WriteHTML($html);
        $mpdf->Output();
    }

    public function print($id)
    {
        $tinmas = DB::table('tinmas')
            ->leftJoin('mbranches','mbranches.braco','=','tinmas.braco')
            ->leftJoin('mformcode_tbl','mformcode_tbl.formc','=','tinmas.formc')
            ->where('tinmas.invid',$id)
            ->select(
                'tinmas.*',
                'mbranches.bank_acc',
                'mbranches.bank_address',
                'mbranches.email',
                'mformcode_tbl.pos4',
                'mformcode_tbl.name4',
                'mformcode_tbl.docd1',
                'mformcode_tbl.docd2'
            )
            ->first();

        DB::table('tinmas')
            ->where('invid',$id)
            ->update([
                'prctr'=>DB::raw('COALESCE(prctr,0)+1')
            ]);

        $products = DB::table('tinta')
            ->leftJoin('mpromas','mpromas.opron','=','tinta.opron')
            ->where('tinta.invid',$tinmas->invid)
            ->select(
                'tinta.*',
                'mpromas.prona'
            )
            ->orderBy('tinta.invln')
            ->get();

        $customer = DB::table('mcusmas')
            ->where('cusno',$tinmas->cusno)
            ->first();

        $services = DB::table('tinta')
            ->leftJoin('mpromas','mpromas.opron','=','tinta.opron')
            ->leftJoin('tmcd', 'tmcd.opron', '=', 'tinta.opron')
            ->where('tinta.invid',$id)
            ->select(
                'tinta.*',
                'mpromas.prona',
                'mpromas.stdqu',
                'tmcd.price'
            )
            ->orderBy('tinta.invln')
            ->get();


        $services = $services->map(function($item) use ($tinmas){

            $price = $item->price ?? 0;

            $item->calc_price = $price * ($tinmas->toppc / 100);

            return $item;
        });

        if($tinmas->delto == 0){
            $shipto = $customer;
        }else{
            $shipto = DB::table('mstmas')
                ->where('cusno',$tinmas->cusno)
                ->where('shpto',$tinmas->delto)
                ->first();
        }

        $html = view(
            'teknik.mc_invoice_release.mc_invoice_release_print',
            compact(
                'tinmas',
                'products',
                'customer',
                'services',
                'shipto'
            )
        )->render();

        $mpdf = new \Mpdf\Mpdf([
            'format'=>'A4',
            'margin_top'=>8,
            'margin_bottom'=>45,
            'margin_left'=>8,
            'margin_right'=>8,
        ]);

        $mpdf->WriteHTML($html);

        $pdfContent = $mpdf->Output(
            "{$tinmas->invid}.pdf",
            "S"
        );

        return response($pdfContent)
            ->header(
                'Content-Type',
                'application/pdf'
            )
            ->header(
                'Content-Disposition',
                'attachment; filename="'.$tinmas->invid.'.pdf"'
            );
    }
}