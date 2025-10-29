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
use App\Models\Mpromas;


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

        try {
            $bbmid = $request->braco . $request->warco . $request->formc . $request->trano;

            BbmHdr::create([
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

            foreach ($request->invno as $i => $invno) {
                $lotStart = (int) $request->lotno[$i];
                $trqty = (int) $request->trqty[$i];
                $lotEnd = $lotStart + $trqty - 1;

                for ($sn = $lotStart; $sn <= $lotEnd; $sn++) {
                    DB::table('tstord')->insert([
                        'bbmid' => $bbmid,
                        'trano' => $request->trano,
                        'invno' => $invno,
                        'opron' => $request->opron[$i],
                        'lotno' => $sn,
                        'trqty' => 1,
                        'qunit' => $request->stdqt[$i],
                        'locco' => $request->locco[$i],
                        'noted' => $request->noted[$i],
                    ]);
                }

                $existW = DB::table('stobw_tbl')
                    ->where('warco', $request->warco)
                    ->where('braco', $request->braco)
                    ->where('opron', $request->opron[$i])
                    ->first();

                if (!$existW) {
                    DB::table('stobw_tbl')->insert([
                        'braco' => $request->braco,
                        'warco' => $request->warco,
                        'opron' => $request->opron[$i],
                        'toqoh' => $trqty, // total qty masuk pertama kali
                    ]);
                } else {
                    DB::table('stobw_tbl')
                        ->where('warco', $request->warco)
                        ->where('braco', $request->braco)
                        ->where('opron', $request->opron[$i])
                        ->update([
                            'toqoh' => DB::raw('toqoh + ' . $trqty), // tambah total qty
                        ]);
                }

                for ($sn = $lotStart; $sn <= $lotEnd; $sn++) {
                    $existL = DB::table('stobl_tbl')
                        ->where('warco', $request->warco)
                        ->where('braco', $request->braco)
                        ->where('opron', $request->opron[$i])
                        ->where('qunit', $request->stdqt[$i])
                        ->where('locco', $request->locco[$i])
                        ->where('lotno', $sn)
                        ->first();

                    if (!$existL) {
                        DB::table('stobl_tbl')->insert([
                            'braco' => $request->braco,
                            'warco' => $request->warco,
                            'opron' => $request->opron[$i],
                            'qunit' => $request->stdqt[$i],
                            'locco' => $request->locco[$i],
                            'lotno' => $sn,
                            'toqoh' => 1,
                        ]);
                    } else {
                        DB::table('stobl_tbl')
                            ->where('warco', $request->warco)
                            ->where('braco', $request->braco)
                            ->where('opron', $request->opron[$i])
                            ->where('qunit', $request->stdqt[$i])
                            ->where('locco', $request->locco[$i])
                            ->where('lotno', $sn)
                            ->update([
                                'toqoh' => DB::raw('toqoh + 1'),
                            ]);
                    }
                }
            }

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
        $bbm = BbmHdr::with('mformcode','bbmdtl.mpromas', 'bbmdtl.tsupid', 'tsupih', 'vendor')->findOrFail($id);
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
            ->leftJoin('mformcode_tbl as f', 'h.formc', '=', 'f.formc')
            ->select('h.*', 'v.supna', 'f.desc_c')
            ->where('h.bbmid', $id)
            ->first();

        if (!$bbm) {
            return redirect()->route('bbm.index')->with('error', 'Data BBM tidak ditemukan.');
        }

        // Ambil detail dengan join ke mpromas untuk ambil prona
        $details = DB::table('tstord as d')
            ->leftJoin('mpromas as p', 'd.opron', '=', 'p.opron')
            ->leftJoin('tsupid_tbl as s', function ($join){
                $join->on('d.invno', '=', 's.invno')
                     ->on('d.opron', '=', 's.opron');
            })
            ->select(
                'd.*',
                'p.prona',
                'd.trqty',
                'd.qunit',
                's.inqty',
                's.stdqt',
                's.pono as spono',
                'd.pono as dpono'
            )
            ->where('d.trano', $bbm->trano)
            ->get();

        // Dropdown
        $mwarco = DB::table('mwarco_tbl')->get();
        $tsupih = DB::table('tsupih_tbl')->get();
        $loccos = DB::table('mlocco_tbl')->get();

        return view('logistic.bbm.bbm_edit', compact('bbm', 'details', 'mwarco', 'tsupih', 'loccos'));
    }

    public function update(Request $request, $bbmid)
    {
        DB::beginTransaction();

        try {
            $bbm = DB::table('tstorh')->where('bbmid', $bbmid)->first();
            if (!$bbm) {
                return redirect()->route('bbm.index')->with('error', 'Data BBM tidak ditemukan.');
            }

            // Update header
            DB::table('tstorh')
                ->where('bbmid', $bbmid)
                ->update([
                    'noteh' => $request->noteh,
                    'updated_at' => now(),
                    'updated_by' => Auth::user()->name,
                ]);

            $oldDetails = DB::table('tstord')
                ->select('opron', 'locco', 'lotno', 'trqty', 'qunit')
                ->where('trano', $bbm->trano)
                ->get();

            foreach ($oldDetails as $old) {
                // Kurangi stok summary di stobw_tbl
                DB::table('stobw_tbl')
                    ->where('warco', $bbm->warco)
                    ->where('braco', $bbm->braco)
                    ->where('opron', $old->opron)
                    ->decrement('toqoh', $old->trqty);

                // Kurangi stok per lotno di stobl_tbl
                DB::table('stobl_tbl')
                    ->where('warco', $bbm->warco)
                    ->where('braco', $bbm->braco)
                    ->where('opron', $old->opron)
                    ->where('qunit', $old->qunit)
                    ->where('locco', $old->locco)
                    ->where('lotno', $old->lotno)
                    ->decrement('toqoh', 1);
            }

            // Hapus stok yang sudah habis (toqoh <= 0)
            DB::table('stobw_tbl')->where('toqoh', '<=', 0)->delete();
            DB::table('stobl_tbl')->where('toqoh', '<=', 0)->delete();

            DB::table('tstord')->where('trano', $bbm->trano)->delete();

            foreach ($request->invno as $i => $invno) {
                $lotStart = (int) $request->lotno[$i];
                $trqty = (int) $request->trqty[$i];
                $lotEnd = $lotStart + $trqty - 1;

                // Simpan ke detail
                for ($sn = $lotStart; $sn <= $lotEnd; $sn++) {
                    DB::table('tstord')->insert([
                        'bbmid' => $bbmid,
                        'trano' => $bbm->trano,
                        'invno' => $invno,
                        'opron' => $request->opron[$i],
                        'lotno' => $sn,
                        'trqty' => 1,
                        'qunit' => $request->stdqt[$i],
                        'locco' => $request->locco[$i],
                        'noted' => $request->noted[$i],
                    ]);
                }

                // Tambah stok summary di stobw_tbl
                $existW = DB::table('stobw_tbl')
                    ->where('warco', $bbm->warco)
                    ->where('braco', $bbm->braco)
                    ->where('opron', $request->opron[$i])
                    ->first();

                if (!$existW) {
                    DB::table('stobw_tbl')->insert([
                        'braco' => $bbm->braco,
                        'warco' => $bbm->warco,
                        'opron' => $request->opron[$i],
                        'toqoh' => $trqty,
                    ]);
                } else {
                    DB::table('stobw_tbl')
                        ->where('warco', $bbm->warco)
                        ->where('braco', $bbm->braco)
                        ->where('opron', $request->opron[$i])
                        ->update(['toqoh' => DB::raw('toqoh + ' . $trqty)]);
                }

                // Tambah stok per lotno di stobl_tbl
                for ($sn = $lotStart; $sn <= $lotEnd; $sn++) {
                    $existL = DB::table('stobl_tbl')
                        ->where('warco', $bbm->warco)
                        ->where('braco', $bbm->braco)
                        ->where('opron', $request->opron[$i])
                        ->where('qunit', $request->stdqt[$i])
                        ->where('locco', $request->locco[$i])
                        ->where('lotno', $sn)
                        ->first();

                    if (!$existL) {
                        DB::table('stobl_tbl')->insert([
                            'braco' => $bbm->braco,
                            'warco' => $bbm->warco,
                            'opron' => $request->opron[$i],
                            'qunit' => $request->stdqt[$i],
                            'locco' => $request->locco[$i],
                            'lotno' => $sn,
                            'toqoh' => 1,
                        ]);
                    } else {
                        DB::table('stobl_tbl')
                            ->where('warco', $bbm->warco)
                            ->where('braco', $bbm->braco)
                            ->where('opron', $request->opron[$i])
                            ->where('qunit', $request->stdqt[$i])
                            ->where('locco', $request->locco[$i])
                            ->where('lotno', $sn)
                            ->update(['toqoh' => DB::raw('toqoh + 1')]);
                    }
                }
            }

            DB::commit();
            return redirect()->route('bbm.index')->with('success', 'Data BBM berhasil diperbarui.');
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Gagal update BBM:', ['error' => $e->getMessage()]);
            return redirect()->back()->with('error', 'Terjadi kesalahan saat update: ' . $e->getMessage());
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
