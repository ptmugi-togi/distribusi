<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Mstmas;
use App\Models\Provinsi;
use App\Models\KabKot;
use Yajra\DataTables\DataTables;

class StmasController extends Controller
{

    public function index()
    {
        if (request()->ajax()) {
            if(auth()->user()->cabang=="PST"){
                $mas=Mstmas::all();
            }else{
                $mas=Mstmas::where('braco',auth()->user()->cabang);
            }
            return DataTables::of($mas)
            ->addIndexColumn()
            ->addColumn('action', function ($item) {
                $btn = '';
                $button =   '<a data-bs-toggle="modal" data-bs-target="#mLMstmas" id="call_mLMstmas" class="badge bg-info"  i_d="'. $item->id .'" braco="'. $item->braco .'" cusno="'. $item->cusno .'" shpto="'. $item->shpto .'" shpnm="'. $item->shpnm .'" deliveryaddress="'. $item->deliveryaddress .'" phone="'. $item->phone .'" fax="'. $item->fax .'" contp="'. $item->contp .'" province="'. $item->province .'" kabupaten="'. $item->kabupaten .'">Ubah</a>';
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
        return view ('master.mstmas', [
           //'mstmases'=> Mstmas::orderBy('id','desc')->limit(100)->get(),
           'province'=> Provinsi::all()
        ]);
    }

    public function provinsii(){
        $prov=Provinsi::all();
        foreach($prov as $provinc){ ?>
            <option value="<?php echo $provinc->id_prov ?>"><?php echo $provinc->provinsi ?></option>
        <?php }
    }
    public function kabkot($id){
        $kab_kot=KabKot::select('id','kabupaten')->where('id_prov', $id)->get();
        foreach($kab_kot as $kk){ ?>
            <option value="<?php echo $kk->id ?>"><?php echo $kk->kabupaten ?></option>
        <?php }
    }
    public function getKabKot($id){
        $getkk=KabKot::select('id','kabupaten')->where('id', $id)->first(); ?>
            <option value="<?php echo $id ?>"><?php echo $getkk->kabupaten ?></option>
        <?php
    }
    public function getProvinsi($id){
        $getProv=Provinsi::select('provinsi')->where('id_prov', $id)->first(); ?>
            <option value="<?php echo $id ?>"><?php echo $getProv->provinsi ?></option>
        <?php
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        $validasi= $request->validate([
            'braco'=>'required|max:3',
            'cusno'=>'required|max:200',
            'shpto'=>'required|max:200',
            'shpnm'=>'required|max:200',
            'deliveryaddress'=>'required',
            'phone'=>'required|max:200',
            'fax'=>'required|max:200',
            'contp'=>'required|max:200',
            'province'=>'required|max:200',
            'kabupaten'=>'required|max:200',
        ]);
        Mstmas::create($validasi);
        return redirect('/mstmas')->with('success','MSTMAS berhasil disimpan');
    }

    public function show(string $id)
    {
        //
    }

    public function edit(string $id)
    {
        //
    }

    public function update(Request $request, Mstmas $mstma)
    {
        $validasi= $request->validate([
            'braco'=>'required|max:3',
            'cusno'=>'required|max:200',
            'shpto'=>'required|max:200',
            'shpnm'=>'required|max:200',
            'deliveryaddress'=>'required',
            'phone'=>'required|max:200',
            'fax'=>'required|max:200',
            'contp'=>'required|max:200',
            'province'=>'required|max:200',
            'kabupaten'=>'required|max:200',
        ]);
        Mstmas::where('id',$mstma->id)
                ->update($validasi);
        return redirect('/mstmas')->with('success','MSTMAS berhasil diubah');
    }

    public function destroy(Mstmas $mstma)
    {
        Mstmas::destroy($mstma->id);
        return redirect('/mstmas')->with('success','MSTMAS berhasil dihapus');
    }
}
