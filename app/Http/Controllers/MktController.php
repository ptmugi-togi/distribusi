<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

use App\Models\Mbranch;
use App\Models\OcHdr;
use App\Models\OcDtl;
use App\Models\OcSbHdr;
use App\Models\OcSbDtl;
use App\Models\Mcurco;

class MktController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $braco = Auth::user()->cabang;

        $sales = DB::table('msreno')
            ->where('braco', $braco)
            ->get();


        return view('marketing.reports.mkt.mkt_create' , compact('sales'));
    }

    public function createSs()
    {
        $braco = Auth::user()->cabang;

        $depo = DB::table('mdepos')
            ->where('braco', $braco)
            ->get();

        $sales = DB::table('msreno')
            ->where('braco', $braco)
            ->get();

        $msgrup = DB::table('msgrup')
            ->get();

        $mssgrup = DB::table('mssgrup')
            ->get();

        return view('marketing.reports.mkt.mkt_ss_create' , compact('depo', 'sales', 'msgrup', 'mssgrup'));
    }

    public function getData(Request $req)
    {
        $braco = Auth::user()->cabang;
        $sreno = $req->sreno;
        $start = $req->sodat_s;
        $end   = $req->sodat_e;

        // SA
        $sa = DB::table('tcoreh as h')
            ->join('tcored as d','h.ocid','=','d.ocid')
            ->leftJoin('mcusmas as c','h.cusno','=','c.cusno')
            ->leftJoin('mpromas as p','d.opron','=','p.opron')
            ->where(function($q) use ($braco){
                $q->where('h.braco',$braco)
                ->orWhere('h.sqtbr',$braco);
            })
            ->whereBetween('h.sordt',[$start,$end]);

        if($sreno){
            $sa->where('h.sreno',$sreno);
        }

        $qtySa = "
            CASE 
                WHEN h.sqper != 0 AND h.sqtbr != '$braco'
                    THEN 0
                ELSE d.qtyor
            END
        ";

        $qtyCalcSa = "d.qtyor";
        
        $totalGrossSa = "
        (
            SELECT SUM(d2.qtyor * (d2.price - COALESCE(d2.teknik,0)))
            FROM tcored d2
            WHERE d2.ocid = h.ocid
        )
        ";

        // split
        $factorSa = "
            CASE
                WHEN h.sqper != 0 AND h.sqtbr = '$braco'
                    THEN (h.sqper / 100)
                WHEN h.sqper != 0 AND h.braco != '$braco'
                    THEN 0
                WHEN h.sqper != 0 AND h.sqtbr != '$braco'
                    THEN (100 - h.sqper) / 100
                ELSE 1
            END
        ";

        $sa = $sa->select(
            DB::raw("
            CASE 
                WHEN h.sqper != 0 AND h.sqtbr = '$braco'
                    THEN h.sqtsr
                ELSE h.sreno
            END as sreno
            "),
            'h.braco as braco',
            DB::raw("
                CASE 
                    WHEN h.braco != '$braco'
                        THEN CONCAT(h.braco, '-', 'SA ', h.sorno)
                    ELSE CONCAT('SA ', h.sorno)
                END as nomor_oc
            "),
            'h.sordt as date',
            'c.cusna as customer',
            DB::raw("CONCAT(p.opron,' / ',p.prona) as product"),
            DB::raw("$qtySa as qty"),
            DB::raw("($qtyCalcSa * (d.price - COALESCE(d.teknik,0)) * $factorSa) as gross"),
            DB::raw("(COALESCE(d.odisa,0) * $qtyCalcSa * $factorSa) as disc"),
            DB::raw("(($qtyCalcSa * (d.price - COALESCE(d.teknik,0))) / NULLIF($totalGrossSa,0)) * COALESCE(h.edisa,0) * $factorSa as edisa"),
            DB::raw("((COALESCE(d.odisa,0) * $qtyCalcSa * $factorSa) + (($qtyCalcSa * (d.price - COALESCE(d.teknik,0))) / NULLIF($totalGrossSa,0)) * COALESCE(h.edisa,0) * $factorSa) as totalDisc"),
            DB::raw("(($qtyCalcSa * (d.price - COALESCE(d.teknik,0)) * $factorSa)-((COALESCE(d.odisa,0) * $qtyCalcSa * $factorSa)+(($qtyCalcSa * (d.price - COALESCE(d.teknik,0)))/ NULLIF($totalGrossSa,0)) * COALESCE(h.edisa,0) * $factorSa)) as net"),
        );

        // SB
        $sb = DB::table('tproja as h')
            ->join('tprojb as d','h.ocsbid','=','d.ocsbid')
            ->leftJoin('mcusmas as c','h.cusno','=','c.cusno')
            ->leftJoin('mpromas as p','d.opron','=','p.opron')
            ->where(function($q) use ($braco) {
                $q->where('h.braco',$braco)
                ->orWhereExists(function($sub) use ($braco){
                    $sub->select(DB::raw(1))
                        ->from('tprojd')
                        ->whereColumn('tprojd.ocsbid','h.ocsbid')
                        ->where(function($x) use ($braco){
                            $x->where('smqtb1',$braco)
                                ->orWhere('smqtb2',$braco)
                                ->orWhere('smqtb3',$braco)
                                ->orWhere('smqtb4',$braco)
                                ->orWhere('smqtb5',$braco);
                        });
                });
            })
            ->whereBetween('h.sordt',[$start,$end]);

        if($sreno){
            $sb->where('h.sreno',$sreno);
        }

        $qtySb = "
            CASE 
                WHEN d.insby != '$braco'
                    THEN 0
                ELSE d.qtyor
            END
        ";

        $qtyCalcSb = "d.qtyor";

        $totalGrossSb = "
        (
            SELECT SUM(d2.qtyor * (d2.price - COALESCE(d2.teknik,0)))
            FROM tprojb d2
            WHERE d2.ocsbid = h.ocsbid
        )
        ";

        // split
        $factorSb = "
            COALESCE(
                (
                    SELECT
                        (
                            CASE WHEN smqtb1 = '$braco' THEN COALESCE(smqp1,0) ELSE 0 END +
                            CASE WHEN smqtb2 = '$braco' THEN COALESCE(smqp2,0) ELSE 0 END +
                            CASE WHEN smqtb3 = '$braco' THEN COALESCE(smqp3,0) ELSE 0 END +
                            CASE WHEN smqtb4 = '$braco' THEN COALESCE(smqp4,0) ELSE 0 END +
                            CASE WHEN smqtb5 = '$braco' THEN COALESCE(smqp5,0) ELSE 0 END
                        ) / 100
                    FROM tprojd
                    WHERE tprojd.ocsbid = h.ocsbid
                    LIMIT 1
                )
            , 0)
        ";

        $sb = $sb->select(
            DB::raw("
            (
                SELECT 
                    CASE 
                        WHEN smqtb1 = '$braco' THEN smqts1
                        WHEN smqtb2 = '$braco' THEN smqts2
                        WHEN smqtb3 = '$braco' THEN smqts3
                        WHEN smqtb4 = '$braco' THEN smqts4
                        WHEN smqtb5 = '$braco' THEN smqts5
                        ELSE h.sreno
                    END
                FROM tprojd
                WHERE tprojd.ocsbid = h.ocsbid
                LIMIT 1
            ) as sreno
            "),
            'h.braco as braco',
            DB::raw("
                CASE 
                    WHEN h.braco != '$braco'
                        THEN CONCAT(h.braco, '-', 'SB ', h.sorno)
                    ELSE CONCAT('SB ', h.sorno)
                END as nomor_oc
            "),
            'h.sordt as date',
            'c.cusna as customer',
            DB::raw("CONCAT(p.opron,' / ',p.prona) as product"),
            DB::raw("$qtySb as qty"),
            DB::raw("($qtyCalcSb * (d.price - COALESCE(d.teknik,0)) * $factorSb) as gross"),
            DB::raw("(COALESCE(d.odisa,0) * $qtyCalcSb * $factorSb) as disc"),
            DB::raw("(($qtyCalcSb * (d.price - COALESCE(d.teknik,0)))/ NULLIF($totalGrossSb,0)) * COALESCE(h.edisa,0) * $factorSb as edisa"),
            DB::raw("((COALESCE(d.odisa,0) * $qtyCalcSb * $factorSb) + (($qtyCalcSb * (d.price - COALESCE(d.teknik,0))) / NULLIF($totalGrossSb,0)) * COALESCE(h.edisa,0) * $factorSb) as totalDisc"),
            DB::raw("(($qtyCalcSb * (d.price - COALESCE(d.teknik,0)) * $factorSb)-((COALESCE(d.odisa,0) * $qtyCalcSb * $factorSb)+(($qtyCalcSb * (d.price - COALESCE(d.teknik,0)))/ NULLIF($totalGrossSb,0)) * COALESCE(h.edisa,0) * $factorSb)) as net"),
        );

        return DB::query()
            ->fromSub($sa->unionAll($sb), 'x')
            ->where('gross','>',0)
            ->orderBy('sreno')
            ->orderBy('date')
            ->get();
    }

    public function previewMkt(Request $req)
    {
        $data = $this->getData($req);
        $branch = Mbranch::where('braco', Auth::user()->cabang)->first();

        $html = view('marketing.reports.mkt.mkt_preview',[
            'items'=>$data,
            'start'=>$req->sodat_s,
            'end'=>$req->sodat_e,
            'braco'=>Auth::user()->cabang,
            'brana' => $branch->brana ?? ''
        ])->render();

        $mpdf = new \Mpdf\Mpdf([
            'format'=>'A4-L',
            'margin_top'=>25,
            'margin_bottom'=>20
        ]);

        $mpdf->SetHTMLFooter('
            <div style="text-align:right; font-size:9pt;">
                {PAGENO}/{nbpg}
            </div>
        ');

        $mpdf->WriteHTML($html);
        $mpdf->Output();
    }

    public function getDataSs(Request $req)
    {
        $braco = Auth::user()->cabang;
        $depo = $req->depo;
        $sreno = $req->sreno;
        $msgrup = $req->msgrup;
        $mssgrup = $req->mssgrup;
        $start = $req->ocdat_s;
        $end   = $req->ocdat_e;

        // SA
        $sa = DB::table('tcoreh as h')
            ->join('tcored as d','h.ocid','=','d.ocid')
            ->leftJoin('mcusmas as c','h.cusno','=','c.cusno')
            ->leftJoin('mpromas as p','d.opron','=','p.opron')
            ->leftJoin('msgrup as g','p.sgrup_id','=','g.sgrup_id')
            ->leftJoin('mssgrup as sg','p.ssgrup_id','=','sg.ssgrup_id')
            ->where(function($q) use ($braco){
                $q->where('h.braco',$braco)
                ->orWhere('h.sqtbr',$braco);
            })
            ->whereBetween('h.sordt',[$start,$end]);

        if($sreno){
            $sa->where('h.sreno',$sreno);
        }

        $qtySa = "
            CASE 
                WHEN h.sqper != 0 AND h.sqtbr != '$braco'
                    THEN 0
                ELSE d.qtyor
            END
        ";

        $qtyCalcSa = "d.qtyor";
        
        $totalGrossSa = "
        (
            SELECT SUM(d2.qtyor * (d2.price - COALESCE(d2.teknik,0)))
            FROM tcored d2
            WHERE d2.ocid = h.ocid
        )
        ";

        // split
        $factorSa = "
            CASE
                WHEN h.sqper != 0 AND h.sqtbr = '$braco'
                    THEN (h.sqper / 100)
                WHEN h.sqper != 0 AND h.braco != '$braco'
                    THEN 0
                WHEN h.sqper != 0 AND h.sqtbr != '$braco'
                    THEN (100 - h.sqper) / 100
                ELSE 1
            END
        ";

        $sa = $sa->select(
            DB::raw("COALESCE(g.descr_sgrup,'INDUSTRY LAIN-LAIN') as msgrup_name"),
            DB::raw("COALESCE(sg.descr_ssgrup,'-') as mssgrup_name"),
            DB::raw("
            CASE 
                WHEN h.sqper != 0 AND h.sqtbr = '$braco'
                    THEN h.sqtsr
                ELSE h.sreno
            END as sreno
            "),
            'h.braco as braco',
            DB::raw("
                CASE 
                    WHEN h.braco != '$braco'
                        THEN CONCAT(h.braco, '-', 'SA ', h.sorno)
                    ELSE CONCAT('SA ', h.sorno)
                END as nomor_oc
            "),
            'h.sordt as date',
            'c.cusna as customer',
            DB::raw("CONCAT(p.opron,' / ',p.prona) as product"),
            DB::raw("$qtySa as qty"),
            DB::raw("($qtyCalcSa * (d.price - COALESCE(d.teknik,0)) * $factorSa) as gross"),
            DB::raw("(COALESCE(d.odisa,0) * $qtyCalcSa * $factorSa) as disc"),
            DB::raw("(($qtyCalcSa * (d.price - COALESCE(d.teknik,0))) / NULLIF($totalGrossSa,0)) * COALESCE(h.edisa,0) * $factorSa as edisa"),
            DB::raw("((COALESCE(d.odisa,0) * $qtyCalcSa * $factorSa) + (($qtyCalcSa * (d.price - COALESCE(d.teknik,0))) / NULLIF($totalGrossSa,0)) * COALESCE(h.edisa,0) * $factorSa) as totalDisc"),
            DB::raw("(($qtyCalcSa * (d.price - COALESCE(d.teknik,0)) * $factorSa)-((COALESCE(d.odisa,0) * $qtyCalcSa * $factorSa)+(($qtyCalcSa * (d.price - COALESCE(d.teknik,0)))/ NULLIF($totalGrossSa,0)) * COALESCE(h.edisa,0) * $factorSa)) as net"),
        );

        // SB
        $sb = DB::table('tproja as h')
            ->join('tprojb as d','h.ocsbid','=','d.ocsbid')
            ->leftJoin('mcusmas as c','h.cusno','=','c.cusno')
            ->leftJoin('mpromas as p','d.opron','=','p.opron')
            ->leftJoin('msgrup as g','p.sgrup_id','=','g.sgrup_id')
            ->leftJoin('mssgrup as sg','p.ssgrup_id','=','sg.ssgrup_id')
            ->where(function($q) use ($braco) {
                $q->where('h.braco',$braco)
                ->orWhereExists(function($sub) use ($braco){
                    $sub->select(DB::raw(1))
                        ->from('tprojd')
                        ->whereColumn('tprojd.ocsbid','h.ocsbid')
                        ->where(function($x) use ($braco){
                            $x->where('smqtb1',$braco)
                                ->orWhere('smqtb2',$braco)
                                ->orWhere('smqtb3',$braco)
                                ->orWhere('smqtb4',$braco)
                                ->orWhere('smqtb5',$braco);
                        });
                });
            })
            ->whereBetween('h.sordt',[$start,$end]);

        if($sreno){
            $sb->where('h.sreno',$sreno);
        }

        $qtySb = "
            CASE 
                WHEN d.insby != '$braco'
                    THEN 0
                ELSE d.qtyor
            END
        ";

        $qtyCalcSb = "d.qtyor";

        $totalGrossSb = "
        (
            SELECT SUM(d2.qtyor * (d2.price - COALESCE(d2.teknik,0)))
            FROM tprojb d2
            WHERE d2.ocsbid = h.ocsbid
        )
        ";

        // split
        $factorSb = "
            COALESCE(
                (
                    SELECT
                        (
                            CASE WHEN smqtb1 = '$braco' THEN COALESCE(smqp1,0) ELSE 0 END +
                            CASE WHEN smqtb2 = '$braco' THEN COALESCE(smqp2,0) ELSE 0 END +
                            CASE WHEN smqtb3 = '$braco' THEN COALESCE(smqp3,0) ELSE 0 END +
                            CASE WHEN smqtb4 = '$braco' THEN COALESCE(smqp4,0) ELSE 0 END +
                            CASE WHEN smqtb5 = '$braco' THEN COALESCE(smqp5,0) ELSE 0 END
                        ) / 100
                    FROM tprojd
                    WHERE tprojd.ocsbid = h.ocsbid
                    LIMIT 1
                )
            , 0)
        ";

        $sb = $sb->select(
            DB::raw("COALESCE(g.descr_sgrup,'INDUSTRY LAIN-LAIN') as msgrup_name"),
            DB::raw("COALESCE(sg.descr_ssgrup,'-') as mssgrup_name"),
            DB::raw("
            (
                SELECT 
                    CASE 
                        WHEN smqtb1 = '$braco' THEN smqts1
                        WHEN smqtb2 = '$braco' THEN smqts2
                        WHEN smqtb3 = '$braco' THEN smqts3
                        WHEN smqtb4 = '$braco' THEN smqts4
                        WHEN smqtb5 = '$braco' THEN smqts5
                        ELSE h.sreno
                    END
                FROM tprojd
                WHERE tprojd.ocsbid = h.ocsbid
                LIMIT 1
            ) as sreno
            "),
            'h.braco as braco',
            DB::raw("
                CASE 
                    WHEN h.braco != '$braco'
                        THEN CONCAT(h.braco, '-', 'SB ', h.sorno)
                    ELSE CONCAT('SB ', h.sorno)
                END as nomor_oc
            "),
            'h.sordt as date',
            'c.cusna as customer',
            DB::raw("CONCAT(p.opron,' / ',p.prona) as product"),
            DB::raw("$qtySb as qty"),
            DB::raw("($qtyCalcSb * (d.price - COALESCE(d.teknik,0)) * $factorSb) as gross"),
            DB::raw("(COALESCE(d.odisa,0) * $qtyCalcSb * $factorSb) as disc"),
            DB::raw("(($qtyCalcSb * (d.price - COALESCE(d.teknik,0)))/ NULLIF($totalGrossSb,0)) * COALESCE(h.edisa,0) * $factorSb as edisa"),
            DB::raw("((COALESCE(d.odisa,0) * $qtyCalcSb * $factorSb) + (($qtyCalcSb * (d.price - COALESCE(d.teknik,0))) / NULLIF($totalGrossSb,0)) * COALESCE(h.edisa,0) * $factorSb) as totalDisc"),
            DB::raw("(($qtyCalcSb * (d.price - COALESCE(d.teknik,0)) * $factorSb)-((COALESCE(d.odisa,0) * $qtyCalcSb * $factorSb)+(($qtyCalcSb * (d.price - COALESCE(d.teknik,0)))/ NULLIF($totalGrossSb,0)) * COALESCE(h.edisa,0) * $factorSb)) as net"),
        );
                
        if($msgrup){
            $sa->where('p.sgrup_id',$msgrup);
            $sb->where('p.sgrup_id',$msgrup);
        }

        if($mssgrup){
            $sa->where('p.ssgrup_id',$mssgrup);
            $sb->where('p.ssgrup_id',$mssgrup);
        }

        return DB::query()
            ->fromSub($sa->unionAll($sb), 'x')
            ->where('gross','>',0)
            ->orderBy('msgrup_name')
            ->orderBy('mssgrup_name')
            ->orderBy('sreno')
            ->orderBy('date')
            ->get();
    }

    public function previewMktSs(Request $req)
    {
        $data = $this->getDataSs($req);
        $branch = Mbranch::where('braco', Auth::user()->cabang)->first();

        $html = view('marketing.reports.mkt.mkt_ss_preview',[
            'items'=>$data,
            'start'=>$req->ocdat_s,
            'end'=>$req->ocdat_e,
            'braco'=>Auth::user()->cabang,
            'brana' => $branch->brana ?? ''
        ])->render();

        $mpdf = new \Mpdf\Mpdf([
            'format'=>'A4-L',
            'margin_top'=>25,
            'margin_bottom'=>20
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