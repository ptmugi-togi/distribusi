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
use App\Models\Mvendor;


class BbmController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $bbmhdr = BbmHdr::with('mformcode')->get();
        
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

                $exist = DB::table('stobw_tbl')
                    ->where('warco', $request->warco)
                    ->where('braco', $request->branco)
                    ->count();

                if ($exist == 0) {
                    DB::table('stobw_tbl')->insert([
                        'braco'  => $request->branco,
                        'warco'  => $request->warco,
                        'opron'  => $request->opron[$i],
                        'toqoh'  => $request->trqty[$i],
                    ]);
                } else {
                    DB::table('stobw_tbl')
                        ->where('warco', $request->warco)
                        ->where('braco', $request->branco)
                        ->where('opron', $request->opron[$i])
                        ->update([
                            'toqoh'  => DB::raw('toqoh + ' . $request->trqty[$i]),
                        ]);
                }

                $exist = DB::table('stobl_tbl')
                    ->where('warco', $request->warco)
                    ->where('braco', $request->branco)
                    ->count();

                if ($exist == 0) {
                    DB::table('stobl_tbl')->insert([
                        'braco'  => $request->branco,
                        'warco'  => $request->warco,
                        'opron'  => $request->opron[$i],
                        'qunit'  => $request->stdqt[$i],
                        'locco'  => $request->locco[$i],
                        'lotno'  => $request->lotno[$i],
                        'toqoh'  => $request->trqty[$i],
                    ]);
                } else {
                    DB::table('stobl_tbl')
                        ->where('warco', $request->warco)
                        ->where('braco', $request->branco)
                        ->where('opron', $request->opron[$i])
                        ->where('qunit', $request->stdqt[$i])
                        ->where('locco', $request->locco[$i])
                        ->where('lotno', $request->lotno[$i])
                        ->update([
                            'toqoh'  => DB::raw('toqoh + ' . $request->trqty[$i]),
                        ]);
                }
            }

            BbmDtl::insert($details);

            DB::commit();

            return redirect()
                ->route('bbm.index')
                ->with('success', 'Data BBM "' . $bbmid . '" berhasil disimpan.');

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
        $bbm = BbmHdr::with('mformcode','bbmdtl')->findOrFail($id);
        return view('logistic.bbm.bbm_detail', compact('bbm'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        // Ambil header
        $bbm = DB::table('tstorh as h')
            ->leftJoin('mvendor_tbl as v', 'h.supno', '=', 'v.supno')
            ->select('h.*', 'v.supna')
            ->where('h.bbmid', $id)
            ->first();

        if (!$bbm) {
            return redirect()->route('bbm.index')->with('error', 'Data BBM tidak ditemukan.');
        }

        // Ambil detail dengan join ke mpromas untuk ambil prona
        $details = DB::table('tstord as d')
            ->leftJoin('mpromas as p', 'd.opron', '=', 'p.opron')
            ->select(
                'd.*',
                'p.prona',
                'd.trqty',
                'd.qunit',
                'd.pono'
            )
            ->where('d.trano', $bbm->trano)
            ->get();

        // Dropdown
        $mwarco = DB::table('mwarco_tbl')->get();
        $tsupih = DB::table('tsupih_tbl')->get();
        $loccos = DB::table('mlocco_tbl')->get();

        return view('logistic.bbm.bbm_edit', compact('bbm', 'details', 'mwarco', 'tsupih', 'loccos'));
    }

    public function update(Request $request, $id)
    {
        DB::beginTransaction();
        try {
            // Update header
            DB::table('tstorh')
                ->where('id', $id)
                ->update([
                    'formc' => $request->formc,
                    'warco' => $request->warco,
                    'tradt' => $request->tradt,
                    'refcno' => $request->refcno,
                    'reffc' => $request->reffc,
                    'refno' => $request->refno,
                    'supno' => $request->supno,
                    'blnum' => $request->blnum,
                    'vesel' => $request->vesel,
                    'noteh' => $request->noteh,
                    'updated_at' => now(),
                ]);

            // Update detail berdasarkan trano
            $trano = DB::table('tstorh')->where('id', $id)->value('trano');

            $details = DB::table('tstord')->where('trano', $trano)->get();

            foreach ($details as $i => $d) {
                DB::table('tstord')
                    ->where('id', $d->id)
                    ->update([
                        'trqty' => $request->trqty[$i] ?? $d->trqty,
                        'lotno' => $request->lotno[$i] ?? $d->lotno,
                        'locco' => $request->locco[$i] ?? $d->locco,
                        'noted' => $request->noted[$i] ?? $d->noted,
                        'updated_at' => now(),
                    ]);
            }

            DB::commit();
            return redirect()->route('bbm.index')->with('success', 'Data BBM berhasil diperbarui.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $bbm = BbmHdr::findOrFail($id);
            $bbm->bbmdtl()->delete();
            $bbm->delete();

            return redirect()->route('bbm.index')
                ->with('success', 'Data BBM berhasil dihapus.');
        } catch (\Exception $e) {
            return redirect()->route('bbm.index')
                ->with('error', 'Gagal menghapus data: ' . $e->getMessage());
        }
    }
}
