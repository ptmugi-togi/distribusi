<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use illuminate\Support\Facades\Log;

use App\Models\Mvendor;
use App\Models\InvoiceHdr;
use App\Models\InvoiceDtl;

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
            ->select('d.opron', 'm.prona', 'd.poqty', 'd.stdqu', 'd.netpr')
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
        $vendors = Mvendor::select('supno','supna')->orderBy('supno')->get();
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
    // public function store(Request $request)
    // {
    //     DB::beginTransaction();

    //     try {
    //         $validated = $request->validate([
    //             'supno.*' => 'required|string',
    //             'invno' => 'required|numeric',
    //             'invdt' => 'required|date',
    //             'duedt' => 'required|date',
    //             'curco' => 'required|string|max:10',
    //             'tfreight' => 'nullable|numeric',
    //             'pono.*' => 'required|string',
    //             'opron.*' => 'required|string',
    //             'inqty.*' => 'nullable|numeric',
    //         ]);

    //         // simpan ke tsupih
    //         $headerId = DB::table('tsupih')->insertGetId([
    //             'supno'   => $request->supno[0], 
    //             'invno'   => $request->invno,
    //             'invdt'   => $request->invdt,
    //             'duedt'   => $request->duedt,
    //             'curco'   => $request->curco,
    //             'tfreight'=> $request->tfreight,
    //             'blnum'   => $request->blnum,
    //             'rinum'   => $request->rinum,
    //             'braco'   => $request->braco,
    //             'formc'   => $request->formc,
    //             'created_at' => now(),
    //             'updated_at' => now(),
    //         ]);

    //         // kirim ke tsupid
    //         $oprons = $request->opron ?? [];
    //         foreach ($oprons as $i => $opron) {
    //             $pono   = $request->pono[$i] ?? null;
    //             $inqty  = $request->inqty[$i] ?? null;
    //             $poqty  = $request->poqty[$i] ?? null;
    //             $netpr  = $request->netpr[$i] ?? 0;
    //             $inprc  = $request->inprc[$i] ?? 0;
    //             $ewprc  = $request->ewprc[$i] ?? 0;
    //             $fobch  = $request->fobch[$i] ?? 0;
    //             $incst  = $request->incst[$i] ?? 0;
    //             $hsn    = $request->hsn[$i] ?? null;
    //             $bm     = $request->bm[$i] ?? 0;
    //             $ppn    = $request->ppn[$i] ?? 0;
    //             $ppnbm  = $request->ppnbm[$i] ?? 0;
    //             $pph    = $request->pph[$i] ?? 0;

    //             DB::table('tsupid')->insert([
    //                 'header_id' => $headerId,
    //                 'pono'      => $pono,
    //                 'opron'     => $opron,
    //                 'poqty'     => $poqty,
    //                 'inqty'     => $inqty,
    //                 'netpr'     => $netpr,
    //                 'inprc'     => $inprc,
    //                 'ewprc'     => $ewprc,
    //                 'fobch'     => $fobch,
    //                 'incst'     => $incst,
    //                 'hsn'       => $hsn,
    //                 'bm'        => $bm,
    //                 'ppn'       => $ppn,
    //                 'ppnbm'     => $ppnbm,
    //                 'pph'       => $pph,
    //                 'created_at' => now(),
    //                 'updated_at' => now(),
    //             ]);

    //             // update inqty ke podtl
    //             if ($inqty !== null) {
    //                 DB::table('podtl_tbl')
    //                     ->where('pono', $pono)
    //                     ->where('opron', $opron)
    //                     ->update(['inqty' => $inqty]);
    //             }
    //         }

    //         DB::commit();

    //         return redirect()->route('invoice.index')->with('success', 'Data Invoice berhasil disimpan!');
    //     } catch (\Throwable $th) {
    //         DB::rollBack();
    //         Log::error('Gagal simpan invoice: ' . $th->getMessage());
    //         return back()->with('error', 'Gagal menyimpan data invoice: ' . $th->getMessage());
    //     }
    // }

    public function store(Request $request)
    {
        DB::beginTransaction();

        try {
            $validated = $request->validate([
                'invno' => 'required|unique:tsupih_tbl,invno',
            ], [
                'invno.unique' => 'Nomor Invoice sudah ada',
            ]);

            // simpan ke header
            $headerId = DB::table('tsupih_tbl')->insertGetId([
                'supno'     => $request->supno[0] ?? null,
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
                'updated_at'=> now(),
            ]);

            // simpan ke detail
            $oprons = $request->opron ?? [];
            foreach ($oprons as $i => $opron) {
                $pono   = $request->pono[$i] ?? null;
                $inqty  = $request->inqty[$i] ?? null;
                $poqty  = $request->poqty[$i] ?? null;

                DB::table('tsupid_tbl')->insert([
                    'header_id' => $headerId,
                    'pono'      => $pono,
                    'opron'     => $opron,
                    'poqty'     => str_replace(',', '', $poqty) ?? 0,
                    'inqty'     => str_replace(',', '', $inqty) ?? 0,
                    'netpr'     => str_replace(',', '', $request->netpr[$i] ?? 0),
                    'inprc'     => str_replace(',', '', $request->inprc[$i] ?? 0),
                    'ewprc'     => str_replace(',', '', $request->ewprc[$i] ?? 0),
                    'fobch'     => str_replace(',', '', $request->fobch[$i] ?? 0),
                    'incst'     => str_replace(',', '', $request->incst[$i] ?? 0),
                    'hsn'       => $request->hsn[$i] ?? null,
                    'bm'        => $request->bm[$i] ?? 0,
                    'ppn'       => $request->ppn[$i] ?? 0,
                    'ppnbm'     => $request->ppnbm[$i] ?? 0,
                    'pph'       => $request->pph[$i] ?? 0,
                    'created_at'=> now(),
                    'updated_at'=> now(),
                ]);

                // update inqty ke tabel podtl_tbl
                if ($inqty !== null && $pono && $opron) {
                    DB::table('podtl_tbl')
                        ->where('pono', $pono)
                        ->where('opron', $opron)
                        ->update(['inqty' => $inqty]);
                }
            }

            DB::commit();

            return redirect()
                ->route('invoice.index')
                ->with('success', 'Data Invoice berhasil disimpan!');
        } catch (\Throwable $th) {
            DB::rollBack();

            // Log error detail untuk debugging
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
