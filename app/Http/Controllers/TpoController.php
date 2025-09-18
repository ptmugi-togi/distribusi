<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\TpoHdr;
use App\Models\TpoDtl;
use App\Models\Mvendor;
use App\Models\Mpromas;

class TpoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $tpohdr = Tpohdr::with('vendor')
                    ->orderBy('podat','desc')
                    ->get();
        return view('purchasing.tpo.tpohdr', compact('tpohdr'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $vendors = Mvendor::select('supno','supna')->orderBy('supno')->get();
        $products = Mpromas::select('opron','prona')->orderBy('opron')->get();

        return view('purchasing.tpo.tpo_create', compact('vendors', 'products'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'pono' => 'required|unique:pohdr_tbl,pono',
            'formc' => 'required|in:PO,PI,PN',
            'stamp' => 'nullable|numeric',
            'podat' => 'required|date|after_or_equal:today',
        ], [
            'pono.required' => 'Nomor PO harus diisi',
            'pono.unique'   => 'Nomor PO sudah ada',
        ]);

        // simpan ke tabel header
        $HeaderData = $request->only([
            'pono','formc','podat','potype','topay','tdesc','curco','shvia','sconp',
            'delco','delnm','dconp','diper','vatax','pph','stamp','noteh','supno',
        ]);

        $HeaderData['user_id'] = Auth::id();
        $HeaderData['created_by'] = Auth::user()->name;
        $HeaderData['updated_by'] = Auth::user()->name;

        $header = Tpohdr::create($HeaderData);

        // simpan yang di tabel detail
        if ($request->has('opron') && $header) {
            foreach ($request->opron as $i => $opron) {
                $berat = $request->weigh[$i] ?? 0;

                $detailData = [
                    'pono'   => $header->pono,
                    'formc'  => $header->formc,
                    'supno'  => $header->supno,
                    'opron'  => $opron,
                    'poqty'  => $request->poqty[$i] ?? 0,
                    'price'  => $request->price[$i] ?? 0,
                    'berat'  => $berat,
                    'odisp'  => $request->odisp[$i] ?? 0,
                    'edeld'  => $request->edeld[$i] ?? null,
                    'earrd'  => $request->earrd[$i] ?? null,
                    'hsn'    => $request->hsn[$i] ?? null,
                    'bm'     => $request->bm[$i] ?? null,
                    'bmt'    => $request->bmt[$i] ?? null,
                    'pphd'   => $request->pphd[$i] ?? null,
                    'noted'  => $request->noted[$i] ?? null,
                ];

                Tpodtl::create($detailData);
            }
        }

        return redirect()->route('tpohdr.index')->with('success', 'Data PO "' . $header->pono . '" berhasil ditambahkan');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $tpohdr = Tpohdr::with(['tpodtl', 'vendor'])->find($id);

        return view('purchasing.tpo.tpo_detail', [
            'tpohdr' => $tpohdr,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        return view('purchasing.tpo.tpo_edit', [
            'tpohdr' => Tpohdr::find($id),
            'vendors' => Mvendor::select('supno','supna')->orderBy('supno')->get(),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $data = $request->only([
        'formc','podat','potype','topay','tdesc','curco','shvia','sconp',
        'delco','delnm','dconp','diper','vatax','pph','stamp','noteh','supno',
    ]);

        $tpohdr = Tpohdr::find($id);
        $tpohdr->fill($data);
        $tpohdr->updated_by = Auth::user()->name ?? null;
        $tpohdr->save();

        return back()->with('success','Data berhasil diubah');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        // hapus podtl
        Tpodtl::where('pono', $id)->delete();

        // hapus header
        Tpohdr::destroy($id);

        return redirect('/tpohdr')->with('success', 'Data PO berhasil dihapus');
    }
}
