<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Msreno;
use Yajra\DataTables\DataTables;

class SrenoController extends Controller
{
    public function index()
    {
        if (request()->ajax()) {
            if(auth()->user()->cabang=="PST"){
                $mas=Msreno::all();
            }else{
                $mas=Msreno::where('braco',auth()->user()->cabang);
            }
            return DataTables::of($mas)
            ->addIndexColumn()
            ->addColumn('action', function ($item) {
                $btn = '';
                $button =   '<a data-bs-toggle="modal" data-bs-target="#mLMsreno" id="call_mLMsreno" idSR="'. $item->id .'" class="badge bg-info" braco="'. $item->braco .'" sreno="'. $item->sreno .'" srena="'. $item->srena .'" steam="'. $item->steam .'" address="'. $item->address .'" phone="'. $item->phone .'" grade="'. $item->grade .'" aktif="'. $item->aktif .'">Ubah</a>';
                $btn .= $button;

                if(auth()->user()->level=="MANAGER" or auth()->user()->level=="IT"){
                $deleteButton = '<form class="d-inline" action=/msreno/' . $item->id . '
                       method="POST">
                       <input type="hidden" name="_token" value=' . csrf_token() . '>
                       <input type="hidden" name="_method" value="delete">
                       <button type="submit" id="btn-delete" class="btn btn-block badge bg-danger"><i class="fa-solid fa-trash-can"></i> Hapus</button>';
                $btn .= $deleteButton;
                }
                return $btn;
            })
            ->make();
        }

        return view('master.msreno', [
           //'msrenos'=> Msreno::all()
        ]);
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        $validasi= $request->validate([
            'braco'=>'required|min:3|max:3',
            'sreno'=>'required|max:6',
            'srena'=>'required|max:255',
            'steam'=>'required|max:255',
            'address'=>'required|max:255',
            'phone'=>'required|max:200',
            'grade'=>'required|max:2',
            'aktif'=>'required|max:1',
        ]);
        Msreno::create($validasi);
        return redirect('/msreno')->with('success','MSRENO berhasil disimpan');
    }

    public function show(string $id)
    {
        //
    }

    public function edit(string $id)
    {
        //
    }

    public function update(Request $request, Msreno $item)
    {
        $validasi= $request->validate([
            'braco'=>'required|min:3|max:3',
            'sreno'=>'required|max:6',
            'srena'=>'required|max:255',
            'steam'=>'required|max:255',
            'address'=>'required|max:255',
            'phone'=>'required|max:200',
            'grade'=>'required|max:2',
            'aktif'=>'required|max:1',
        ]);
        Msreno::where('id',$item->id)
                ->update($validasi);
        return redirect('/msreno')->with('success','MSRENO berhasil diubah');
    }

    public function destroy(Msreno $item)
    {
        Msreno::destroy($item->id);
        return redirect('/msreno')->with('success','MSRENO berhasil dihapus');
    }
}
