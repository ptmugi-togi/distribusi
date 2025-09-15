<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Mssgrup_tbl;

class SsgrupController extends Controller
{
    public function index()
    {
        return view('master.mssgrup',[
            'mssgrups'=>Mssgrup_tbl::all(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validasi= $request->validate([
            'descr_ssgrup'=>'required|unique:mssgrup_tbl|max:255',
        ]);
        Mssgrup_tbl::create($validasi);
        return redirect('/ssgrup')->with('success','MSSGRUP berhasil ditambahkan');
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
    public function update(Request $request, Mssgrup_tbl $ssgrup)
    {
        $validasi= $request->validate([
            'descr_ssgrup'=>'required|unique:mssgrup_tbl|max:255',
        ]);
        Mssgrup_tbl::where('ssgrup_id',$ssgrup->ssgrup_id)
                    ->update($validasi);
        return redirect('/ssgrup')->with('success','MSSGRUP berhasil diubah');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Mssgrup_tbl $ssgrup)
    {
        Mssgrup_tbl::destroy($ssgrup->ssgrup_id);
        return redirect('/ssgrup')->with('success','MSSGRUP berhasil dihapus');
    }
}
