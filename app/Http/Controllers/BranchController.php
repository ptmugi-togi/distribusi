<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Mbranch;

class BranchController extends Controller
{
    public function index()
    {
        if(auth()->user()->cabang=="PST"){
            $mas=Mbranch::all();
        }else{
            $mas=Mbranch::where('braco',auth()->user()->cabang)->get();
        }
        return view ('master.mbranch', [
            'mbranchs'=> $mas,
         ]);
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        $validasi=$request->validate([
            'braco'=>'required|max:3|unique:mbranches',
            'brana'=>'required|max:200',
            'conam'=>'required|max:200',
            'address'=>'required|max:200',
            'contactp'=>'required|max:200',
            'phone'=>'required|max:200',
            'faxno'=>'required|max:200',
            'npwp'=>'required|max:200',
            'tglsk'=>'required',
            'email'=>'required|max:200',
        ]);
        Mbranch::create($validasi);
        return redirect('/mbranch')->with('success','MBRANCH berhasil disimpan');
    }

    public function show(string $id)
    {
        //
    }

    public function edit(string $id)
    {
        //
    }

    public function update(Request $request, Mbranch $mbranch)
    {
        $validasi=$request->validate([
            'brana'=>'required|max:200',
            'conam'=>'required|max:200',
            'address'=>'required|max:200',
            'contactp'=>'required|max:200',
            'phone'=>'required|max:200',
            'faxno'=>'required|max:200',
            'npwp'=>'required|max:200',
            'tglsk'=>'required',
            'email'=>'required|max:200',
        ]);
        Mbranch::where('braco',$mbranch->braco)
                ->update($validasi);
        return redirect('/mbranch')->with('success','MBRANCH berhasil diubah');
    }

    public function destroy(Mbranch $mbranch)
    {
        Mbranch::destroy($mbranch->braco);
        return redirect('/mbranch')->with('success','MBRANCH berhasil dihapus');
    }
}
