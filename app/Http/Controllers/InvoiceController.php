<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use illuminate\Support\Facades\Log;

use App\Models\Mvendor;
use App\Models\InvoiceHdr;
use App\Models\InvoiceDtl;
use App\Models\Mpromas;

class InvoiceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $invoice = InvoiceHdr::with(['vendor', 'currency', 'branches', 'formcode'])->get();
        return view('purchasing.invoice.invoice_index', compact('invoice'));
    }

    public function getRinumBySupplier($supno)
    {
        $data = DB::table('tbolh')
            ->select('rinum', 'blnum', 'bldat')
            ->where('supno', $supno)
            ->orderByDesc('bldat')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $data
        ]);
    }

    public function getPoBySupplier($supno)
    {
        $poList = DB::table('pohdr_tbl')
            ->select('pono')
            ->where('supno', $supno)
            ->orderBy('pono')
            ->get();

        if ($poList->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'Tidak ada PO untuk supplier ini.'
            ]);
        }

        return response()->json([
            'success' => true,
            'data' => $poList
        ]);
    }

    // ambil data product po berdasarkan pono
    public function getItemsByPo($pono)
    {
        $items = DB::table('podtl_tbl as d')
            ->join('mpromas as m', 'd.opron', '=', 'm.opron')
            ->select('d.opron', 'm.prona', 'd.poqty', 'd.stdqu', 'd.price')
            ->where('d.pono', $pono)
            ->get();

        if ($items->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'Tidak ada data barang untuk PO ini.'
            ]);
        }

        return response()->json([
            'success' => true,
            'data' => $items
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $invoice = InvoiceHdr::with(['currency', 'branches', 'formcode'])->get();
        $vendors = Mvendor::select('supno','supna', 'vgrp')->orderBy('supno')->get();
        $po = DB::table('pohdr_tbl')->select('pono','supno')->where('supno', '!=', '')->orderBy('pono')->get();
        $hsnList = DB::table('mhsno_tbl')
            ->select('hsn', 'bm')
            ->orderBy('hsn')
            ->get();
        return view('purchasing.invoice.invoice_create', compact('invoice', 'vendors', 'po', 'hsnList'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'invno' => 'required|unique:tsupih_tbl,invno',
        ], [
            'invno.unique' => 'Nomor Invoice sudah ada',
        ]);

        DB::beginTransaction();

        try {
            // simpan ke header
            $headerId = DB::table('tsupih_tbl')->insertGetId([
                'supno'     => $request->supno,
                'invno'     => $request->invno,
                'invdt'     => $request->invdt,
                'duedt'     => $request->duedt,
                'curco'     => $request->curco,
                'tfreight'  => $request->tfreight ?? 0,
                'blnum'     => $request->blnum,
                'rinum'     => $request->rinum,
                'braco'     => $request->braco,
                'formc'     => $request->formc,
                'created_at'=> now(),
                'created_by'=> Auth::user()->name,
                'updated_at'=> now(),
                'updated_by'=> Auth::user()->name,
                'user_id'   => Auth::id(),
            ]);

            // simpan ke detail
            $oprons = $request->opron ?? [];
            foreach ($oprons as $i => $opron) {
                $pono   = $request->pono[$i] ?? null;
                $inqty  = $request->inqty[$i] ?? null;
                $poqty  = $request->poqty[$i] ?? null;

                DB::table('tsupid_tbl')->insert([
                    'invno'     => $request->invno,
                    'pono'      => $pono,
                    'potyp'     => $request->potype,
                    'opron'     => $opron,
                    'inqty'     => $inqty,
                    'stdqt'     => $request->stdqt[$i] ?? 0,
                    'inprc'     => $request->inprc[$i] ?? 0,
                    'inamt'     => ((float) str_replace(',', '', $inqty)) * ((float) str_replace(',', '', $request->inprc[$i] ?? 0)),
                    'curco'     => $request->curco,
                    'ewprc'     => $request->ewprc[$i] ?? 0,
                    'fobch'     => $request->fobch[$i] ?? 0,
                    'frcst'     => $request->tfreight ?? 0,
                    'incst'     => $request->incst[$i] ?? 0,
                    'hsn'       => $request->hsn[$i] ?? null,
                    'bm'        => $request->bm[$i] ?? 0,
                    'ppn'       => $request->ppn[$i] ?? 0,
                    'ppnbm'     => $request->ppnbm[$i] ?? 0,
                    'pph'       => $request->pph[$i] ?? 0,
                ]);

                // update inqty ke tabel podtl_tbl
                if ($inqty !== null && $pono && $opron) {
                    DB::table('podtl_tbl')
                        ->where('pono', $pono)
                        ->where('opron', $opron)
                        ->update([
                            'inqty' => $inqty,
                        ]);
                }
            }
            
            DB::commit();

            return redirect()
                ->route('invoice.index')
                ->with('success', 'Data Invoice berhasil disimpan!');
        } catch (\Throwable $th) {
            DB::rollBack();

            // Log error 
            \Illuminate\Support\Facades\Log::error('Gagal simpan invoice: ' . $th->getMessage());

            return back()
                ->with('error', 'Gagal menyimpan data invoice: ' . $th->getMessage());
        }
    }


    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $invoice = InvoiceHdr::with(['vendor'])->findOrFail($id);
        $invoice->details = \DB::table('tsupid_tbl as d')
            ->leftJoin('podtl_tbl as p', function ($join) {
                $join->on('d.pono', '=', 'p.pono')
                    ->on('d.opron', '=', 'p.opron');
            })
            ->leftJoin('mpromas as m', 'd.opron', '=', 'm.opron')
            ->where('d.invno', $invoice->invno)
            ->select(
                'd.*',
                'p.price',
                'p.netpr',
                'p.stdqu',
                'p.poqty',
                'm.prona'
            )
            ->get();
        
        return view('purchasing.invoice.invoice_detail', compact('invoice'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $invoice = InvoiceHdr::with('vendor')->where('invno', $id)->firstOrFail();

        $details = DB::table('tsupid_tbl as d')
            ->leftJoin('podtl_tbl as p', function ($join) {
                $join->on('d.pono', '=', 'p.pono')
                    ->on('d.opron', '=', 'p.opron');
            })
            ->select(
                'd.*',
                'p.poqty',
                'p.stdqu',
                'p.netpr',
                'p.hsn as pohsn'
            )
            ->where('d.invno', $id)
            ->get();

        // data untuk select2
        $vendors = Mvendor::select('supno', 'supna')->orderBy('supno')->get();
        $po = DB::table('pohdr_tbl')
            ->select('pono', 'supno')
            ->whereNotNull('supno')
            ->orderBy('pono')
            ->get();
        $hsnList = DB::table('mhsno_tbl')
            ->select('hsn', 'bm')
            ->orderBy('hsn')
            ->get();

        return view('purchasing.invoice.invoice_edit', compact('invoice', 'details', 'vendors', 'po', 'hsnList'));
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        DB::beginTransaction();

        try {
            // Update / insert detail
            foreach ($request->pono ?? [] as $i => $pono) {
                $opron = $request->opron[$i] ?? null;
                $inqty = (float) str_replace(',', '', $request->inqty[$i] ?? 0);
                $inprc = (float) str_replace(',', '', $request->inprc[$i] ?? 0);
                $inamt = (float) str_replace(',', '', $request->inamt[$i] ?? ($inqty * $inprc));

                $payload = [
                    'pono'   => $pono,
                    'opron'  => $opron,
                    'inqty'  => $inqty,
                    'stdqt'  => $request->stdqt[$i],
                    'inprc'  => $inprc,
                    'inamt'  => $inamt,
                    'ewprc'  => $request->ewprc[$i],
                    'fobch' => $request->fobch[$i],
                    'incst'  => $request->incst[$i],
                    'hsn'    => $request->hsn[$i],
                    'bm'     => $request->bm[$i],
                    'ppn'    => $request->ppn[$i],
                    'ppnbm'  => $request->ppnbm[$i],
                    'pph'    => $request->pph[$i],
                ];

                $id_su = $request->id_su[$i] ?? null;

                if ($id_su) {
                    $updated = DB::table('tsupid_tbl')
                        ->where('id_su', $id_su)
                        ->where('invno', $id)
                        ->update($payload);

                    if ($updated === 0) {
                        DB::table('tsupid_tbl')
                            ->where('invno', $id)
                            ->where('pono', $pono)
                            ->where('opron', $opron)
                            ->update($payload);
                    }
                } else {
                    // insert baris baru
                    $payload = array_merge($payload, [
                        'invno'      => $id,
                        'potyp'      => $request->potyp ?? $request->potype ?? null,
                        'curco'      => $request->curco,
                        'stdqt'      => $request->stdqt[$i] ?? '',
                    ]);
                    DB::table('tsupid_tbl')->insert($payload);
                }

                // update inqty ke tabel podtl_tbl
                if ($pono && $opron) {
                    DB::table('podtl_tbl')
                        ->where('pono', $pono)
                        ->where('opron', $opron)
                        ->update(['inqty' => $inqty]);
                }
            }

            DB::commit();
            return redirect()->route('invoice.index')
                ->with('success', "Invoice $id berhasil diperbarui!");
        } catch (\Throwable $th) {
            DB::rollBack();
            Log::error('Gagal update invoice: ' . $th->getMessage());
            return back()->with('error', 'Gagal update invoice: ' . $th->getMessage());
        }
        Log::info('Update invoice', [
            'invno' => $id,
            'id_su' => $id_su,
            'mode' => $id_su ? 'update' : 'insert',
        ]);
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        DB::beginTransaction();

        try {
            $invoice = DB::table('tsupih_tbl')->where('invno', $id)->first();
            if (!$invoice) {
                return redirect()
                    ->route('invoice.index')
                    ->with('error', "Invoice dengan nomor $id tidak ditemukan.");
            }

            DB::table('tsupid_tbl')->where('invno', $id)->delete();

            DB::table('tsupih_tbl')->where('invno', $id)->delete();

            DB::commit();

            return redirect()
                ->route('invoice.index')
                ->with('success', "Invoice $id berhasil dihapus!");
        } catch (\Throwable $th) {
            DB::rollBack();

            \Log::error("Gagal menghapus invoice $id: " . $th->getMessage());

            return redirect()
                ->route('invoice.index')
                ->with('error', "Gagal menghapus invoice: " . $th->getMessage());
        }
    }
}
