<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\mssgrup;
use Illuminate\Support\Facades\DB;

class MssgrupController extends Controller
{
    public function index(){
        return view('master.mssgrup',[
            'mssgrups'=>mssgrup::all(),
        ]);
    }

    public function store(Request $request){
        $validasi= $request->validate([
            'descr_ssgrup'=>'required|max:255',
        ]);
        mssgrup::create($validasi);
        return redirect('/mssgrup')->with('success','MSSGRUP berhasil ditambahkan');
    }

    public function destroy(Request $request, mssgrup $ssgrup_id){
        $deleted = DB::table('mssgrup')->where('ssgrup_id', '=', $ssgrup_id)->delete();
        //mssgrup::destroy($ssgrup_id);
        return redirect('/mssgrup')->with('success','MSSGRUP berhasil dihapus');
    }

}
