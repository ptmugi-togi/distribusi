<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Mssgrup_tbl;
use Illuminate\Support\Facades\DB;

class MssgrupController extends Controller
{
    public function index(){
        return view('master.mssgrup',[
            'mssgrups'=>Mssgrup_tbl::all(),
        ]);
    }

    public function store(Request $request){
        $validasi= $request->validate([
            'descr_ssgrup'=>'required|max:255',
        ]);
        Mssgrup_tbl::create($validasi);
        return redirect('/mssgrup')->with('success','MSSGRUP berhasil ditambahkan');
    }

    public function destroy(Request $request, Mssgrup_tbl $ssgrup_id){
        $deleted = DB::table('mssgrup_tbl')->where('ssgrup_id', '=', $ssgrup_id)->delete();
        //Mssgrup_tbl::destroy($ssgrup_id);
        return redirect('/mssgrup')->with('success','MSSGRUP berhasil dihapus');
    }

}
