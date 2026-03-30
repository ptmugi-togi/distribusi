<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\msgrup;

class SgrupController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('master.msgrup',[
            'msgrups'=>msgrup::all(),
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
            'sgrup_id'=>'required|max:3|unique:msgrup',
            'descr_sgrup'=> 'required|max:100|unique:msgrup',
        ]);
        msgrup::create($validasi);
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

    public function update(Request $request, msgrup $msgrup)
    {
        $validasi= $request->validate([
            'descr_sgrup'=> 'required|max:100|unique:msgrup',
        ]);
        msgrup::where('sgrup_id',$msgrup->sgrup_id)
                    ->update($validasi);
        return redirect('/msgrup')->with('success','MSGRUP berhasil diubah');
    }

    public function destroy(msgrup $msgrup)
    {
        msgrup::destroy($msgrup->sgrup_id);
        return redirect('/msgrup')->with('success','MSGRUP berhasil dihapus');
    }
}
