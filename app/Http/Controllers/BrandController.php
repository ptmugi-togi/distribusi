<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Mbrand;

class BrandController extends Controller
{
    public function index()
    {
        return view('master.mbrand', [
            'mbrands'=> Mbrand::all(),
        ]);
    }

    public function create()
    {

    }

    public function store(Request $request)
    {
        $validasi= $request->validate([
            'brand_name'=>'required|max:100|unique:mbrand_tbl',
            'descr_brand'=>'required|max:200',
            'topay'=>'required|max:100',
        ]);
        Mbrand::create($validasi);
        return redirect('/mbrand')->with('success','MBRAND berhasil disimpan');
    }

    public function show(string $id)
    {

    }

    public function edit(string $id)
    {

    }

    public function update(Request $request, Mbrand $mbrand)
    {
        $validasi= $request->validate([
            'descr_brand'=>'required|max:200',
            'topay'=>'required|max:100',
        ]);
        Mbrand::where('id',$mbrand->id)
                ->update($validasi);
        return redirect('/mbrand')->with('success','MBRAND berhasil diubah');
    }

    public function destroy(Mbrand $mbrand)
    {
        Mbrand::destroy($mbrand->id);
        return redirect('/mbrand')->with('success','MBRAND berhasil dihapus');
    }
}
