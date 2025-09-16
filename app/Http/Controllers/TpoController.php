<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Tpohdr;
use App\Models\Mvendor;

class TpoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('purchasing.tpo.tpohdr', [
            'tpohdr'=>Tpohdr::with('vendor')->get(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $vendors = Mvendor::select('supno','supna')->orderBy('supno')->get();
        return view('purchasing.tpo.tpo_create', compact('vendors'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'pono' => 'required|unique:pohdr_tbl,pono',
            'stamp' => 'nullable|numeric',
        ], [
            'pono.required' => 'Nomor PO harus diisi',
            'pono.unique'   => 'Nomor PO sudah ada',
        ]);

        Tpohdr::create($request->all());
        
        

        return redirect('/tpohdr')->with('success', 'Data berhasil disimpan');
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
        Tpohdr::destroy($id);
        return redirect('/tpohdr')->with('success', 'Data berhasil dihapus');
    }
}
