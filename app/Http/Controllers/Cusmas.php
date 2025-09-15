<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Mcusmas;
use Illuminate\Support\Facades\DB;
use App\Models\Mcindu;
use App\Models\Mczone;
use App\Models\Mbranch;
use App\Models\Mcarea;
use Yajra\DataTables\DataTables;

class Cusmas extends Controller
{
    public function index()
    {
        if(auth()->user()->cabang=="PST"){
            $mas=DB::table('mcusmas')
            ->select('mcusmas.*')
            ->orderBy('mcusmas.id','ASC')->get();
        }else{
            $mas=DB::table('mcusmas')
            ->select('mcusmas.*')
            ->where('mcusmas.braco',auth()->user()->cabang)
            ->orderBy('mcusmas.id','ASC')->get();
        }

        if (request()->ajax()) {
            $cusmas = $mas;
            return DataTables::of($cusmas)
            ->addIndexColumn()
            ->addColumn('action', function ($item) {
                $btn='';
                $button =   '<a id="btn_edit_cusmas" data-bs-toggle="modal" data-bs-target="#mLCusmas" class="btn btn-block badge bg-info" i_d="'. $item->id .'" cusno="'. $item->cusno .'" braco="'. $item->braco .'" cusna="'. $item->cusna .'" billn="'. $item->billn .'" title="'. $item->title .'" prpos="'. $item->prpos .'"  pkp="'. $item->pkp .'" npwp="'. $item->npwp .'" address="'.$item->address.'" city="'.$item->city.'" kodepost="'.$item->kodepost.'" phone="'.$item->phone.'" fax="'.$item->fax.'" contact="'.$item->contact.'" email="'.$item->email.'" topay="'.$item->topay.'" cindu="'.$item->cindu.'" czone="'.$item->czone.'" carea="'.$item->carea.'" dopen="'.$item->dopen.'" crlim="'.$item->crlim.'" lauid="'.$item->lauid.'" ladup="'.$item->ladup.'" status="'.$item->status.'" barval="'.$item->barval.'" openo="'.$item->openo.'" oarval="'.$item->oarval.'" csect="'.$item->csect.'" >Ubah</a>';
                $btn .= $button;

                if(auth()->user()->level=="IT"){
                $deleteButton = '<form class="d-inline" action=/cusmas/' . $item->id . '
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
        return view('master.cusmas', [
            'bracos'=> Mbranch::all(),
            'cindus'=> Mcindu::all(),
            'czones'=> Mczone::all(),
            'careas'=> Mcarea::all(),
        ]);
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        $validasi= $request->validate([
            'cusno'=>'required',
            'braco'=>'required',
            'cusna'=>'required',
            'billn'=>'required',
            'title'=>'required',
            'prpos'=>'required',
            'pkp'=>'required',
            'npwp'=>'required',
            'address'=>'required',
            'city'=>'required',
            'kodepost'=>'required',
            'phone'=>'required',
            'fax'=>'required',
            'contact'=>'required',
            'email'=>'required',
            'topay'=>'required',
            'cindu'=>'required',
            'czone'=>'required',
            'carea'=>'required',
            'dopen'=>'required',
            'crlim'=>'required',
            'lauid'=>'required',
            'ladup'=>'required',
            'status'=>'required',
            'barval'=>'required',
            'openo'=>'required',
            'oarval'=>'required',
            'csect'=>'required',
        ]);
        Mcusmas::create($validasi);
        return redirect('/cusmas')->with('success','Pelanggan berhasil disimpan');
    }

    public function show(string $id)
    {
        //
    }

    public function edit(string $id)
    {
        //
    }

    public function update(Request $request, Mcusmas $cusma)
    {
        $validasi= $request->validate([
            'cusno'=>'required',
            'braco'=>'required',
            'cusna'=>'required',
            'billn'=>'required',
            'title'=>'required',
            'prpos'=>'required',
            'pkp'=>'required',
            'npwp'=>'required',
            'address'=>'required',
            'city'=>'required',
            'kodepost'=>'required',
            'phone'=>'required',
            'fax'=>'required',
            'contact'=>'required',
            'email'=>'required',
            'topay'=>'required',
            'cindu'=>'required',
            'czone'=>'required',
            'carea'=>'required',
            'dopen'=>'required',
            'crlim'=>'required',
            'lauid'=>'required',
            'ladup'=>'required',
            'status'=>'required',
            'barval'=>'required',
            'openo'=>'required',
            'oarval'=>'required',
            'csect'=>'required',
        ]);
        Mcusmas::where('id', $cusma->id)->update($validasi);
        return redirect('/cusmas')->with('success','Pelanggan berhasil diubah');
    }

    public function destroy(Mcusmas $cusma)
    {
        Mcusmas::destroy($cusma->id);
        return redirect('/cusmas')->with('success','Pelanggan berhasil dihapus');
    }

    public function titleCusmas(){ ?>
        <option value="PT.">PT.</option>
        <option value="CV.">CV.</option>
        <option value="BPK">BAPAK</option>
        <option value="IBU">IBU</option>
        <option value="TOKO">TOKO</option>
        <option value="UD.">UD.</option>
        <option value="TM.">TM.</option>
        <option value="HOTEL">HOTEL</option>
        <option value="KOP">UNIT KOPERASI</option> <?php
    }
    public function cinduCusmas(){
        $cindus=Mcindu::all();
        foreach($cindus as $cindu){ ?>
            <option value="<?php echo $cindu->cindu ?>"><?php echo $cindu->descr_cindu ?></option>
        <?php }
    }
    public function czoneCusmas(){
        $czones=Mczone::all();
        foreach($czones as $czone){ ?>
            <option value="<?php echo $czone->czone ?>"><?php echo $czone->descr_zone ?></option>
        <?php }
    }
    public function careaCusmas(){
        $careas=Mcarea::all();
        foreach($careas as $carea){ ?>
            <option value="<?php echo $carea->id_area ?>"><?php echo $carea->carea ?></option>
        <?php }
    }

}
