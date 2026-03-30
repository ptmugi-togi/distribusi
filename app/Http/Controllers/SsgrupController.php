<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\mssgrup;

class SsgrupController extends Controller
{
    public function index()
    {
        return view('master.mssgrup',[
            'mssgrups'=>mssgrup::all(),
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
            'descr_ssgrup'=>'required|unique:mssgrup|max:255',
        ]);
        mssgrup::create($validasi);
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
    public function update(Request $request, mssgrup $ssgrup)
    {
        $validasi= $request->validate([
            'descr_ssgrup'=>'required|unique:mssgrup|max:255',
        ]);
        mssgrup::where('ssgrup_id',$ssgrup->ssgrup_id)
                    ->update($validasi);
        return redirect('/ssgrup')->with('success','MSSGRUP berhasil diubah');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(mssgrup $ssgrup)
    {
        mssgrup::destroy($ssgrup->ssgrup_id);
        return redirect('/ssgrup')->with('success','MSSGRUP berhasil dihapus');
    }
}
