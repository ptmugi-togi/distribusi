<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Mcls;

class ClsController extends Controller
{
    public function index()
    {
        return view('master.mcls',[
           'mclses'=> Mcls::all()
        ]);
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        $validasi= $request->validate([
            'id_cls'=>'required|max:1|unique:mcls_tbl',
            'class'=>'required|max:3',
            'descr_cls'=>'required|max:100',
        ]);
        Mcls::create($validasi);
        return redirect('/mcls')->with('success','MCLS berhasil disimpan');
    }

    public function show(string $id)
    {
        //
    }

    public function edit(string $id)
    {
        //
    }

    public function update(Request $request, Mcls $mcl)
    {
        $validasi= $request->validate([
            'class'=>'required|max:3',
            'descr_cls'=>'required|max:100',
        ]);
        Mcls::where('id_cls',$mcl->id_cls)
                ->update($validasi);
        return redirect('/mcls')->with('success','MCLS berhasil diubah');
    }

    public function destroy(Mcls $mcl)
    {
        Mcls::destroy($mcl->id_cls);
        return redirect('/mcls')->with('success','MCLS berhasil dihapus');
    }
}
