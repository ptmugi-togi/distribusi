<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Mcindu;

class CinduController extends Controller
{
    public function index()
    {
        return view('master.mcindu',[
            'mcindus'=>Mcindu::all()
        ]);
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        $validasi= $request->validate([
            'descr_cindu'=>'required|max:100',
        ]);
        Mcindu::create($validasi);
        return redirect('/mcindu')->with('success','MCINDU berhasil disimpan');
    }

    public function show(string $id)
    {
        //
    }

    public function edit(string $id)
    {
        //
    }

    public function update(Request $request, Mcindu $mcindu)
    {
        $validasi= $request->validate([
            'descr_cindu'=>'required|max:100',
        ]);
        Mcindu::where('cindu',$mcindu->cindu)
                ->update($validasi);
        return redirect('/mcindu')->with('success','MCINDU berhasil diubah');
    }

    public function destroy(Mcindu $mcindu)
    {
        Mcindu::destroy($mcindu->cindu);
        return redirect('/mcindu')->with('success','MCINDU berhasil dihapus');
    }
}
