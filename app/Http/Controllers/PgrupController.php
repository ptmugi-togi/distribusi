<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Mpgrup;

class PgrupController extends Controller
{

    public function index()
    {
        return view('master.mpgrup',[
            'mpgrups'=>Mpgrup::all(),
        ]);
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        $validasi= $request->validate([
            'pgrup'=>'required|max:1|unique:mpgrups',
            'descr'=>'required|max:255|unique:mpgrups',
        ]);
        Mpgrup::create($validasi);
        return redirect('/mpgrup')->with('success','MPGRUP berhasil disimpan');
    }

    public function show(string $id)
    {
        //
    }

    public function edit(string $id)
    {
        //
    }

    public function update(Request $request, Mpgrup $mpgrup)
    {
        $validasi= $request->validate([
            'descr'=>'required|max:255|unique:mpgrups',
        ]);
        Mpgrup::where('pgrup',$mpgrup->pgrup)
                ->update($validasi);
        return redirect('/mpgrup')->with('success','MPGRUP berhasil diubah');
    }

    public function destroy(Mpgrup $mpgrup)
    {
        Mpgrup::destroy($mpgrup->pgrup);
        return redirect('mpgrup')->with('success','MPGRUP berhasil dihapus');
    }
}
