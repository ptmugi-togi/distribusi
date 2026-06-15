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
            ->where('braco', $userBraco)
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
                'created_at'=>now(),
                'created_by'=>Auth::user()->name,
                'updated_at'=>now(),
                'updated_by'=>Auth::user()->name
            ]);

            if($request->tdna_dnlin){
                foreach($request->tdna_dnlin as $i=>$dnlin){
                    DB::table('tinta')->insert([
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
                'mcusmas.offad',
                'mcusmas.offad2',
                'mcusmas.offad3',
                'mcusmas.offad4'
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
}