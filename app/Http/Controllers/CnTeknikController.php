<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use illuminate\Support\Facades\Log;
use Carbon\Carbon;

use App\Models\CnHdr;
use App\Models\CnDtl;

class CnTeknikController extends Controller
{
    public function index()
    {
        $userBraco = Auth::user()->cabang;

        $cnhdr = CnHdr::with('cndtls', 'customer')
            ->join('tinmas', function ($join) {
                $join->on('tcnh.invfc', '=', 'tinmas.formc')
                    ->on('tcnh.invno', '=', 'tinmas.invno')
                    ->on('tcnh.braco', '=', 'tinmas.braco');
            })
            ->where('tcnh.braco', $userBraco)
            ->where('tcnh.invfc', 'SD')
            ->select('tcnh.*')
            ->get();

        return view('fna.cn_teknik.cn_teknik_index', compact('cnhdr', 'userBraco'));
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

        return view('fna.cn_teknik.cn_teknik_create', compact('minDate'));
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
                'invfc'      => $request->invfc,
                'invno'      => $request->invno,
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
                'notar'      => $request->notar,
                'reaso'      => $request->reaso,
                'srnfc'      => null,
                'srnno'      => null,
                'created_at' => now(),
                'created_by' => Auth::user()->name,
                'updated_at' => now(),
                'updated_by' => Auth::user()->name,
            ]);

            if ($request->filled('tdna_dnlin')) {
                foreach ($request->tdna_dnlin as $i => $dnlin) {

                    DB::table('tcnotea')->insert([
                        'crnln'         => $i + 1,
                        'braco'         => $braco,
                        'formc'         => $formc,
                        'crnno'         => $crnno,
                        'crnln'         => $dnlin,
                        'tofee'         => $request->tdna_tofee[$i],
                        'descr'         => $request->tdna_descr[$i],
                        'opron'         => $request->tdna_opron[$i],
                        'trqty'         => $request->tdna_trqty[$i],
                        'lotno'         => $request->tdna_lotno[$i],
                        'gramt'         => $request->tdna_gramt[$i] ?? 0,
                        'odisa'         => $request->tdna_odisa[$i] ?? 0,
                        'odisp'         => $request->tdna_odisp[$i] ?? 0,
                        'ntamt'         => $request->tdna_ntamt[$i] ?? 0,
                    ]);
                }
            }

            if ($request->filled('tdnb_dnlin')) {
                foreach ($request->tdnb_dnlin as $i => $dnlin) {

                    DB::table('tcnoteb')->insert([
                        'crnln'         => $i + 1,
                        'braco'         => $braco,
                        'formc'         => $formc,
                        'crnno'         => $crnno,
                        'crnln'         => $dnlin,
                        'serty'         => $request->tdnb_serty[$i],
                        'tofee'         => $request->tdnb_tofee[$i],
                        'gramt'         => $request->tdnb_gramt[$i] ?? 0,
                        'odisa'         => $request->tdnb_odisa[$i] ?? 0,
                        'odisp'         => $request->tdnb_odisp[$i] ?? 0,
                        'ntamt'         => $request->tdnb_ntamt[$i] ?? 0,
                    ]);
                }
            }

            if ($request->filled('tdnc_opron')) {
                foreach ($request->tdnc_opron as $i => $opron) {

                    DB::table('tcnotec')->insert([
                        'braco'         => $braco,
                        'formc'         => $formc,
                        'crnno'         => $crnno,
                        'opron'         => $opron,
                        'price'         => $request->tdnc_price[$i],
                        'trqty'         => $request->tdnc_trqty[$i],
                        'lotno'         => $request->tdnc_lotno[$i],
                        'gramt'         => $request->tdnc_gramt[$i] ?? 0,
                        'odisa'         => $request->tdnc_odisa[$i] ?? 0,
                        'odisp'         => $request->tdnc_odisp[$i] ?? 0,
                        'ntamt'         => $request->tdnc_ntamt[$i] ?? 0,
                    ]);
                }
            }

            DB::table('tinmas')
                ->where('braco', $request->braco)
                ->where('formc', $request->invfc)
                ->where('invno', $request->invno)
                ->update([
                    'cramt' => $request->cramt_hdr
                ]);

            DB::commit();
            return redirect()->route('cn_teknik.index')->with('success', "data CN Teknik \"$cnid\" berhasil disimpan.");

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Gagal simpan CN Teknik:', ['error' => $e->getMessage()]);
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function getSD()
    {
        $userBraco = Auth::user()->cabang;

        $listsc = DB::table('tinmas')
            ->join('mcusmas', function($join){
                $join->on('mcusmas.cusno','=','tinmas.cusno');
            })
            ->where('tinmas.braco', $userBraco)
            ->where('tinmas.formc', 'SD')
            ->select(
                'tinmas.*',
                'mcusmas.cusna as customer'
            )
            ->orderByDesc('tinmas.invno')
            ->get();

        return response()->json($listsc);
    }
    
   public function getDetailSd(Request $request)
    {
        $braco = Auth::user()->cabang;

        $barang = DB::table('tinta')
            ->join('mpromas', 'mpromas.opron', '=', 'tinta.opron')
            ->where('tinta.braco', $braco)
            ->where('tinta.formc', 'SD')
            ->where('tinta.invno', $request->sorno)
            ->select(
                'tinta.*',
                'mpromas.prona',
                'mpromas.stdqu'
            )
            ->get();

        $service = DB::table('tintb')
            ->where('braco', $braco)
            ->where('formc', 'SD')
            ->where('invno', $request->sorno)
            ->get();

        $sparepart = DB::table('tintc')
            ->join('mpromas', 'mpromas.opron', '=', 'tintc.opron')
            ->where('tintc.braco', $braco)
            ->where('tintc.formc', 'SD')
            ->where('tintc.invno', $request->sorno)
            ->select(
                'tintc.*',
                'mpromas.prona',
                'mpromas.stdqu'
            )
            ->get();

        return response()->json([
            'barang'    => $barang,
            'service'   => $service,
            'sparepart' => $sparepart,
        ]);
    }
}