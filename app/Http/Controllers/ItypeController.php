<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Mitype;

class ItypeController extends Controller
{

    public function index()
    {
        return view('master.mitype',[
            'mitypes'=>Mitype::all()
        ]);
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        $validasi= $request->validate([
            'itype_id'=>'required|max:1|unique:mitype_tbl',
            'descr_itype'=>'required|max:100',
        ]);
        Mitype::create($validasi);
        return redirect('/mitype')->with('success','MITYPE berhasil disimpan');
    }

    public function show(string $id)
    {
        //
    }

    public function edit(string $id)
    {
        //
    }

    public function update(Request $request, Mitype $mitype)
    {
        $validasi= $request->validate([
            'descr_itype'=>'required|max:100',
        ]);
        Mitype::where('itype_id',$mitype->itype_id)
                ->update($validasi);
        return redirect('/mitype')->with('success','MITYPE berhasil diubah');
    }

    public function destroy(Mitype $mitype)
    {
        Mitype::destroy($mitype->itype_id);
        return redirect('/mitype')->with('success','MITYPE berhasil dihapus');
    }
}
