<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use illuminate\Support\Facades\Log;

use App\Models\BbmHdr;
use App\Models\BbmDtl;
use App\Models\Mformcode;
use App\Models\InvoiceHdr;
use App\Models\Vendor;


class BbmController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $bbmhdr = BbmHdr::all();

        return view('logistic.bbm.bbm_index', compact('bbmhdr'));
    }

    public function getInvoice($rinum)
    {
        $invoices = DB::table('tsupih_tbl')
            ->where('rinum', $rinum)
            ->select('invno')
            ->get();

        return response()->json($invoices);
    }

    public function getBarang($invno)
    {
        $barang = DB::table('tsupid_tbl as t')
            ->leftJoin('mpromas as m', 't.opron', '=', 'm.opron')
            ->where('t.invno', $invno)
            ->select('t.opron as opron', 'm.prona as prona', 't.inqty as inqty', 't.stdqt as stdqt', 't.pono as pono')
            ->get();

        return response()->json($barang);
    }

    public function getLocco($warco)
    {
        $locco = DB::table('mlocco_tbl')
            ->where('warco', $warco)
            ->select('locco')
            ->get();

        return response()->json($locco);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $bbmhdr = BbmHdr::all();

        $mwarco = DB::table('mwarco_tbl')->get();

        $last = DB::table('tstorh')
        ->orderBy('trano', 'desc')
        ->value('trano');

        $year = date('y'); // ambil tahun sekarang

        if ($last && substr($last, 0, 2) == $year) {
            // jika masih tahun yang sama lanjutkan urutannya
            $number = (int)substr($last, 2) + 1;
        } else {
            // kalau tahun baru atau belum ada data mulai dari 1
            $number = 1;
        }

        $trano = $year . str_pad($number, 4, '0', STR_PAD_LEFT);

        $tsupih = DB::table('tsupih_tbl')
        ->leftJoin('mvendor_tbl', 'tsupih_tbl.supno', '=', 'mvendor_tbl.supno')
        ->leftJoin('tbolh', 'tsupih_tbl.rinum', '=', 'tbolh.rinum')
        ->select('tsupih_tbl.*', 'mvendor_tbl.supna', 'tbolh.blnum', 'tbolh.vesel')
        ->get();

        return view('logistic.bbm.bbm_create', compact('bbmhdr', 'mwarco', 'tsupih', 'trano'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        DB::beginTransaction();

        $request->validate([
            'noteh' => 'required',
        ],
        [
            'noteh.required' => 'Note harus diisi',
        ]);

        try {

            $bbmid = $request->braco . $request->warco . $request->formc . $request->trano;

            $hdr = BbmHdr::create([
                'bbmid'   => $bbmid,
                'braco'   => $request->braco,
                'warco'   => $request->warco,
                'formc'   => $request->formc,
                'trano'   => $request->trano,
                'tradt'   => $request->tradt,
                'reffc'   => $request->reffc,
                'refno'   => $request->refno,
                'supno'   => $request->supno,
                'blnum'   => $request->blnum,
                'vesel'   => $request->vesel,
                'noteh'   => $request->noteh,
                'created_at' => now(),
                'created_by' => Auth::user()->name,
                'updated_at' => now(),
                'updated_by' => Auth::user()->name,
                'user_id' => Auth::user()->id,
            ]);

            $details = [];
            foreach ($request->invno as $i => $invno) {
                $details[] = [
                    'bbmid'   => $bbmid,
                    'trano'   => $request->trano,
                    'invno'   => $invno,
                    'opron'   => $request->opron[$i],
                    'lotno'   => $request->lotno[$i],
                    'trqty'   => $request->trqty[$i],
                    'qunit'   => $request->stdqt[$i],
                    'locco'   => $request->locco[$i],
                    'noted'   => $request->noted[$i],
                ];

                DB::table('stobw_tbl')->insert([
                    'braco'  => $request->braco,
                    'warco'  => $request->warco,
                    'opron'  => $request->opron[$i],
                    'toqoh'  => $request->trqty[$i],
                ]);

                DB::table('stobl_tbl')->insert([
                    'braco'  => $request->braco,
                    'warco'  => $request->warco,
                    'opron'  => $request->opron[$i],
                    'qunit'  => $request->stdqt[$i],
                    'locco'  => $request->locco[$i],
                    'lotno'  => $request->lotno[$i],
                    'toqoh'  => $request->trqty[$i],
                ]);
            }

            BbmDtl::insert($details);

            DB::commit();

            return redirect()
                ->route('bbm.index')
                ->with('success', 'Data BBM ' . $bbmid . ' berhasil disimpan.');

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Gagal simpan BBM:', ['error' => $e->getMessage()]);
            return back()->with('error', 'Terjadi kesalahan saat menyimpan data: ' . $e->getMessage());
        }
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
        
    }
}
