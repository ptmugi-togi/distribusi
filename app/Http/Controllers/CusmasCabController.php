<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\models\McusmasCbg;

class CusmasCabController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
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
    { $id=$request->customer_id;
        $validasi= $request->validate([
            'customer_id'=>'required|max:255',
            'lokasi'=>'required|max:250',
            'alamat'=>'required|max:250',
            'telp'=>'required|max:50',
            'fax'=>'required|max:50',
            'email'=>'required|max:100',
            'kontak'=>'required|max:100',
            'provinsi'=>'required|max:100',
            'telp_kontak'=>'required|max:50',
            'kabupaten'=>'required|max:100',

        ]);
        McusmasCbg::create($validasi); $linkcus='/mcusmas/'.$id.'/edit';
        return redirect($linkcus)->with('success','Site berhasil disimpan');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(McusmasCbg $mcusmascab)
    {
        $linkcus='/mcusmas/'.$mcusmascab->customer_id.'/edit';
        McusmasCbg::destroy($mcusmascab->id);
        return redirect($linkcus)->with('success','Site berhasil dihapus');
    }
}
