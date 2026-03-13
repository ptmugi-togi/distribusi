<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

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

        $openPeriod = DB::table('tperiode')
            ->where('status', 'O')
            ->orderBy('periode', 'asc')
            ->first();

        $minDate = null;

        if ($openPeriod) {
            $year = substr($openPeriod->periode, 0, 4);
            $month = substr($openPeriod->periode, 4, 2);

            $minDate = "$year-$month-01";
        }

        return view('marketing.reports.mkt.mkt_create' , compact('sales', 'minDate'));
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
            ->where('h.braco',$braco)
            ->whereBetween('h.sordt',[$start,$end]);

        if($sreno){
            $sa->where('h.sreno',$sreno);
        }

        $sa = $sa->select(
            'h.sreno',
            DB::raw("'SA' as formc"),
            'h.sorno as number',
            'h.sordt as date',
            'c.cusna as customer',
            DB::raw("CONCAT(p.opron,' / ',p.prona) as product"),
            'd.qtyor as qty',
            DB::raw("(d.qtyor * (d.price - COALESCE(d.teknik,0))) as gross"),
            DB::raw("COALESCE(d.odisa,0) as disc"),
            DB::raw("COALESCE(h.edisa,0) as edisa"),
            DB::raw("(COALESCE(d.odisa,0) + COALESCE(h.edisa,0)) as totalDisc"),
            DB::raw("((d.qtyor * (d.price - COALESCE(d.teknik,0))) - (COALESCE(d.odisa,0) + COALESCE(h.edisa,0))) as net")
        );

        // SB
        $sb = DB::table('tproja as h')
            ->join('tprojb as d','h.ocsbid','=','d.ocsbid')
            ->leftJoin('mcusmas as c','h.cusno','=','c.cusno')
            ->leftJoin('mpromas as p','d.opron','=','p.opron')
            ->where('h.braco',$braco)
            ->whereBetween('h.sordt',[$start,$end]);

        if($sreno){
            $sb->where('h.sreno',$sreno);
        }

        $sb = $sb->select(
            'h.sreno',
            DB::raw("'SB' as formc"),
            'h.sorno as number',
            'h.sordt as date',
            'c.cusna as customer',
            DB::raw("CONCAT(p.opron,' / ',p.prona) as product"),
            'd.qtyor as qty',
            DB::raw("(d.qtyor * (d.price - COALESCE(d.teknik,0))) as gross"),
            DB::raw("COALESCE(d.odisa,0) as disc"),
            DB::raw("COALESCE(h.edisa,0) as edisa"),
            DB::raw("(COALESCE(d.odisa,0) + COALESCE(h.edisa,0)) as totalDisc"),
            DB::raw("((d.qtyor * (d.price - COALESCE(d.teknik,0))) - (COALESCE(d.odisa,0) + COALESCE(h.edisa,0))) as net")
        );

        return DB::query()
            ->fromSub($sa->unionAll($sb), 'x')
            ->orderBy('sreno')
            ->orderBy('date')
            ->get();
    }

    public function getTotalSales($data)
    {
        return collect($data)
            ->groupBy('sreno')
            ->map(function($rows){
                return [
                    'total_gross' => $rows->sum('gross'),
                    'total_disc'  => $rows->sum('disc'),
                    'total_edisa' => $rows->sum('edisa'),
                    'total_net'   => $rows->sum('net')
                ];
            });
    }

    public function previewMkt(Request $req)
    {
        $data = $this->getData($req);

        $html = view('marketing.reports.mkt.mkt_preview',[
            'items'=>$data,
            'start'=>$req->sodat_s,
            'end'=>$req->sodat_e,
            'braco'=>Auth::user()->cabang
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

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}