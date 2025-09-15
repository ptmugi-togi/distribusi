<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Msgrup_tbl;

class SgrupController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('master.msgrup',[
            'msgrups'=>Msgrup_tbl::all(),
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
        $validasi=$request->validate([
            'sgrup_id'=>'required|max:3|unique:msgrup_tbl',
            'descr_sgrup'=> 'required|max:100|unique:msgrup_tbl',
        ]);
        Msgrup_tbl::create($validasi);
        return redirect('/msgrup')->with('success','MSGRUP berhasil ditambahkan');
    }

    public function show(string $id)
    {
        //
    }

    public function edit(string $id)
    {
        //
    }

    public function update(Request $request, Msgrup_tbl $msgrup)
    {
        $validasi= $request->validate([
            'descr_sgrup'=> 'required|max:100|unique:msgrup_tbl',
        ]);
        Msgrup_tbl::where('sgrup_id',$msgrup->sgrup_id)
                    ->update($validasi);
        return redirect('/msgrup')->with('success','MSGRUP berhasil diubah');
    }

    public function destroy(Msgrup_tbl $msgrup)
    {
        Msgrup_tbl::destroy($msgrup->sgrup_id);
        return redirect('/msgrup')->with('success','MSGRUP berhasil dihapus');
    }
}
