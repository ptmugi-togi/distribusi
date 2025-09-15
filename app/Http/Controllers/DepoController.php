<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Mdepo;
use App\Models\Mbranch;

class DepoController extends Controller
{

    public function index()
    {
        if(auth()->user()->cabang=="PST"){
            $mas=Mdepo::all();
        }else{
            $mas=Mdepo::where('braco',auth()->user()->cabang)->get();
        }
        return view ('master.mdepo', [
            'mdepos'=> $mas,
            'mbranch'=>Mbranch::all(),
        ]);
    }

    public function getBranch($id){
        $branch=Mbranch::select('braco','brana')->where('braco', $id)->get();
        foreach($branch as $kk){ ?>
            <option value="<?php echo $kk->braco ?>"><?php echo $kk->brana ?></option>
        <?php }
    }

    public function branche(){
        $branch=Mbranch::all();
        foreach($branch as $kk){ ?>
            <option value="<?php echo $kk->braco ?>"><?php echo $kk->brana ?></option>
        <?php }
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        $validasi= $request->validate([
            'depo'=>'required|max:3',
            'name'=>'required|max:200',
            'braco'=>'required|max:3',
            'address'=>'required|max:200',
            'cont'=>'required|max:200',
            'email'=>'required|max:200',
            'phone'=>'required|max:200',
            'faxno'=>'required|max:200',
            'npwp'=>'required|max:200',
            'tglsk'=>'required|max:200',
        ]);
        Mdepo::create($validasi);
        return redirect('/mdepo')->with('success','MDEPO berhasil disimpan');
    }

    public function show(string $id)
    {
        //
    }

    public function edit(string $id)
    {
        //
    }

    public function update(Request $request, Mdepo $mdepo)
    {
        $validasi= $request->validate([
            'depo'=>'required|max:3',
            'name'=>'required|max:200',
            'braco'=>'required|max:3',
            'address'=>'required|max:200',
            'cont'=>'required|max:200',
            'email'=>'required|max:200',
            'phone'=>'required|max:200',
            'faxno'=>'required|max:200',
            'npwp'=>'required|max:200',
            'tglsk'=>'required|max:200',
        ]);
        Mdepo::where('depo',$mdepo->depo)
                ->update($validasi);
        return redirect('/mdepo')->with('success','MDEPO berhasil diubah');
    }

    public function destroy(Mdepo $mdepo)
    {
        Mdepo::destroy($mdepo->depo);
        return redirect('/mdepo')->with('success','MDEPO berhasil dihapus');
    }
}
