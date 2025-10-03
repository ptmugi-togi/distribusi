<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\Tbolh;
use App\Models\Tbold;
use App\Models\Mvendor;
use App\Models\Mcurco;

class BlawbController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $tbolh = tbolh:: with(['vendor', 'currency', 'branches', 'formcode'])
            ->orderBy('created_at', 'desc')
            ->get();
        return view('purchasing.blawb.blawb_index', compact('tbolh'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $vendors = Mvendor::select('supno','supna')->orderBy('supno')->get();

        return view('purchasing.blawb.blawb_create', compact('vendors'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'rinum' => 'required | unique:tbolh,rinum',
        ], [
            'rinum.required' => 'Nomor BL harus diisi',
            'rinum.unique'   => 'Nomor BL / AWB sudah ada',
        ]);

        // header
        $tbolh = new Tbolh;
        $tbolh->fill($request->except(['I01','I02','I03','I04','I05','I06','I07']));
        $tbolh['user_id'] = Auth::user()->id;
        $tbolh['created_by'] = Auth::user()->name;
        $tbolh['updated_by'] = Auth::user()->name;
        
        $tbolh->save();

        $kodeBiaya = ['I01','I02','I03','I04','I05','I06','I07'];

        foreach ($kodeBiaya as $kode) {
            \DB::table('tbold')->insert([
                'rinum' => $tbolh->rinum,
                'costt' => $kode,
                'costf' => $request->$kode ? preg_replace('/\D/', '', $request->$kode) : null, // NULL kalau kosong
            ]);
        }

        $tbold = new Tbold;

        return redirect()->route('blawb.index')->with('success', 'Data BL AWB "RI ' . $tbolh->rinum . '" berhasil disimpan');
    }

    /**
     * Display the specified resource.
     */
    public function show($rinum)
    {
        $tbolh = Tbolh::with('tbold')->findOrFail($rinum);
        $biaya = $tbolh->tbold->pluck('costf', 'costt')->toArray();

        return view('purchasing.blawb.blawb_detail', compact('tbolh', 'biaya'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $tbolh = Tbolh::with('tbold')->findOrFail($id);
        $vendors = Mvendor::select('supno','supna')->orderBy('supno')->get();
        $biaya = $tbolh->tbold->pluck('costf', 'costt')->toArray();

        return view('purchasing.blawb.blawb_edit', compact('tbolh', 'vendors', 'biaya'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'rinum' => 'required | unique:tbolh,rinum,' . $id . ',rinum',
        ], [
            'rinum.required' => 'Nomor BL harus diisi',
            'rinum.unique'   => 'Nomor BL / AWB sudah ada',
        ]);

        // header
        $tbolh = Tbolh::findOrFail($id);
        $tbolh->fill($request->except(['I01','I02','I03','I04','I05','I06','I07']));
        $tbolh['user_id'] = Auth::user()->id;
        $tbolh['updated_by'] = Auth::user()->name;
        
        $tbolh->save();

        $kodeBiaya = ['I01','I02','I03','I04','I05','I06','I07'];

        foreach ($kodeBiaya as $kode) {
            \DB::table('tbold')->updateOrInsert([
                'rinum' => $tbolh->rinum,
                'costt' => $kode,
            ], [
                'costf' => $request->$kode ? preg_replace('/\D/', '', $request->$kode) : null, // NULL kalau kosong
            ]);
        }

        return redirect()->route('blawb.index')->with('success', 'Data BL AWB "RI ' . $tbolh->rinum . '" berhasil diubah');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        Tbold::where('rinum', $id)->delete();

        Tbolh::destroy($id);

        return redirect()->route('blawb.index')->with('success', 'Data BL AWB "RI ' . $id . '" berhasil dihapus');
    }
}
