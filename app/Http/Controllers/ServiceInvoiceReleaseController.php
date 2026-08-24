<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

use App\Models\DnHdr;
use App\Models\DnDtl;

class ServiceInvoiceReleaseController extends Controller
{
    public function index()
    {
        $userBraco = Auth::user()->cabang;
        
        $sir = DB::table('tinmas')
            ->leftJoin('mcusmas', 'tinmas.cusno', '=', 'mcusmas.cusno')
            ->where('tinmas.braco', $userBraco)
            ->where('tinmas.dorfc', 'DN')
            ->get();

        $latestPeriod = DB::table('tperiode')
            ->where('braco', Auth::user()->cabang)
            ->orderByDesc('periode')
            ->first();

        $periodClosed = $latestPeriod && $latestPeriod->status === 'C';

        return view('teknik.service_invoice_release.service_invoice_release_index', compact('sir', 'userBraco', 'periodClosed'));
    }

    public function create()
    {
        $userBraco = Auth::user()->cabang;

        $dn = DnHdr::with('mbranch', 'mcusmas', 'mformcode')
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
    
        return view('teknik.service_invoice_release.service_invoice_release_create', compact('minDate'));
    }

    public function store(Request $request)
    {
        // dd($request->all());
        DB::beginTransaction();

        try {

            $invid = $request->braco .  $request->formc . $request->invno;
            $bracoformc = $request->braco . $request->formc;

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

            if($request->tdna_dnlin){
                foreach($request->tdna_dnlin as $i=>$dnlin){
                    DB::table('tinta')->insert([
                        'invid'=>$invid,
                        'braco'=>$request->braco,
                        'formc'=>$request->formc,
                        'invno'=>$request->invno,
                        'invln'=>$dnlin,
                        'tofee'=>$request->tdna_tofee[$i],
                        'descr'=>$request->tdna_descr[$i],
                        'opron'=>$request->tdna_opron[$i],
                        'trqty'=>$request->tdna_trqty[$i],
                        'lotno'=>$request->tdna_lotno[$i],
                        'gramt'=>$request->tdna_gramt[$i] ?? 0,
                        'odisa'=>$request->tdna_odisa[$i] ?? 0,
                        'odisp'=>$request->tdna_odisp[$i] ?? 0,
                        'netbe'=>$request->tdna_netbe[$i] ?? 0,
                    ]);
                }
            }

            if($request->tdnb_dnlin){
                foreach($request->tdnb_dnlin as $i=>$dnlin){
                    DB::table('tintb')->insert([
                        'invid'=>$invid,
                        'braco'=>$request->braco,
                        'formc'=>$request->formc,
                        'invno'=>$request->invno,
                        'invln'=>$dnlin,
                        'serty'=>$request->tdnb_serty[$i],
                        'tofee'=>$request->tdnb_tofee[$i],
                        'gramt'=>$request->tdnb_gramt[$i] ?? 0,
                        'odisp'=>$request->tdnb_odisp[$i] ?? 0,
                        'odisa'=>$request->tdnb_odisa[$i] ?? 0,
                        'netbe'=>$request->tdnb_netbe[$i] ?? 0,
                    ]);
                }
            }

            if($request->tdnc_opron){
                foreach($request->tdnc_opron as $i=>$opron){
                    DB::table('tintc')->insert([
                        'invid'=>$invid,
                        'braco'=>$request->braco,
                        'formc'=>$request->formc,
                        'invno'=>$request->invno,
                        'opron'=>$opron,
                        'price'=>$request->tdnc_price[$i],
                        'trqty'=>$request->tdnc_trqty[$i],
                        'lotno'=>$request->tdnc_lotno[$i],
                        'gramt'=>$request->tdnc_gramt[$i] ?? 0,
                        'odisa'=>$request->tdnc_odisa[$i] ?? 0,
                        'odisp'=>$request->tdnc_odisp[$i] ?? 0,
                        'netbe'=>$request->tdnc_netbe[$i] ?? 0,
                    ]);
                }
            }

            DB::table('tdnh')
                ->where('formc', $request->dorfc)
                ->where('dnnum', $request->donom)
                ->where('depo', $request->divco)
                ->update([
                    'invfc' => $request->formc,
                    'invno' => $request->invno,
                ]);

            DB::commit();

            return redirect()
                ->route('service_invoice_release.index')
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

    public function searchDn(Request $request)
    {
        $dn = DB::table('tdnh')
            ->where('braco', Auth::user()->cabang)
            ->where('dnnum','like','%'.$request->search.'%')
            ->where('invno' ,'=',null)
            ->limit(20)
            ->get();

        return response()->json($dn);
    }

    public function getDn($dnid)
    {
        $dn = DB::table('tdnh')
            ->leftJoin('mcusmas','tdnh.cusno','=','mcusmas.cusno')
            ->where('tdnh.dnid',$dnid)
            ->select(
                'tdnh.*',
                'mcusmas.cusna',
                'mcusmas.address',
            )
            ->first();


        $delivery = DB::table('mstmas')
            ->where('cusno',$dn->cusno)
            ->select(
                'shpto',
                'deliveryaddress'
            )
            ->get();


        return response()->json([
            'dn' => $dn,
            'delivery' => $delivery
        ]);
    }

    public function getDnDetail($dnid){
        $tdna = DB::table('tdna')
            ->where('dnid', $dnid)
            ->get();

        $tdnb = DB::table('tdnb')
            ->where('dnid', $dnid)
            ->get();

        $tdnc = DB::table('tdnc')
            ->where('dnid', $dnid)
            ->get();

        return response()->json([
            'tdna' => $tdna,
            'tdnb' => $tdnb,
            'tdnc' => $tdnc
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
            ->where('tinta.invid', $id)
            ->select('tinta.*', 'mpromas.prona')
            ->orderBy('tinta.invln')
            ->get();

        $serviceFees = DB::table('tintb')
            ->where('invid', $id)
            ->orderBy('invln')
            ->get()
            ->groupBy('invln');

        $spareparts = DB::table('tintc')
            ->leftJoin('mpromas', 'mpromas.opron', '=', 'tintc.opron')
            ->where('tintc.invid', $id)
            ->select('tintc.*', 'mpromas.prona')
            ->get();

        if($tinmas->delto == 0){
            $shipto = $customer;
        }else{
            $shipto = DB::table('mstmas')
                ->where('cusno',$tinmas->cusno)
                ->where('shpto',$tinmas->delto)
                ->first();
        }

        $html = view('teknik.service_invoice_release.service_invoice_release_print', compact(
            'tinmas',
            'products',
            'customer',
            'services',
            'serviceFees',
            'spareparts',
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
            ->where('tinta.invid',$id)
            ->select(
                'tinta.*',
                'mpromas.prona'
            )
            ->orderBy('tinta.invln')
            ->get();

        $serviceFees = DB::table('tintb')
            ->where('invid',$id)
            ->orderBy('invln')
            ->get()
            ->groupBy('invln');

        $spareparts = DB::table('tintc')
            ->leftJoin('mpromas','mpromas.opron','=','tintc.opron')
            ->where('tintc.invid',$id)
            ->select(
                'tintc.*',
                'mpromas.prona'
            )
            ->get();

        if($tinmas->delto == 0){
            $shipto = $customer;
        }else{
            $shipto = DB::table('mstmas')
                ->where('cusno',$tinmas->cusno)
                ->where('shpto',$tinmas->delto)
                ->first();
        }

        $html = view(
            'teknik.service_invoice_release.service_invoice_release_print',
            compact(
                'tinmas',
                'products',
                'customer',
                'services',
                'serviceFees',
                'spareparts',
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