<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Mpromas;
use Yajra\DataTables\DataTables;

class PromasController extends Controller
{
    public function index()
    {
        if (request()->ajax()) {
            $promas = Mpromas::all();
            return DataTables::of($promas)
            ->addIndexColumn()
            ->addColumn('action', function ($item) {
                $btn='';
                $button =   '<a data-bs-toggle="modal" data-bs-target="#mLMpromas" id="call_mLMpromas" mproma='.$item->mproma.' status="'. $item->status .'" oprn="'. $item->opron .'" prona="'. $item->prona .'" nama_supplier="'. $item->nama_supplier .'" stdqu="'. $item->stdqu .'" itype_id="'. $item->itype_id .'" brand_name="'. $item->brand_name .'" pgrup="'. $item->pgrup .'" sgrup_id="'. $item->sgrup_id .'" ssgrup_id="'. $item->ssgrup_id .'" lssgrup="'. $item->lssgrup .'" weigh="'. $item->weigh .'" meast="'. $item->meast .'" measl="'. $item->measl .'" measp="'. $item->measp .'" volum="'. $item->volum .'" abccl="'. $item->abccl .'" capac="'. $item->capac .'" platf="'. $item->platf .'" mstok="'. $item->mstok .'" spnum="'. $item->spnum .'" garansi="'. $item->garansi .'" pbilp="'. $item->pbilp .'" ijtype="'. $item->ijtype .'" id_cls="'. $item->id_cls .'"  class="btn btn-block badge bg-info" >Ubah</a>';
                $btn .= $button;

                if(auth()->user()->level=="IT"){
                $deleteButton = '<form class="d-inline" action=/mpromas/' . $item->mproma . '
                       method="POST">
                       <input type="hidden" name="_token" value=' . csrf_token() . '>
                       <input type="hidden" name="_method" value="delete">
                       <button type="submit" id="btn-delete-promas" class="btn btn-block badge bg-danger" proma='. $item->mproma .'><i class="fa-solid fa-trash-can"></i> Hapus</button>';
                $btn .= $deleteButton;
                }
                return $btn;
            })
            ->make();

        }
        return view('master.mpromas',[
            'mpromases'=>Mpromas::select('opron')->orderBy('opron','asc')->get() // 'mpromases'=>Mpromas::orderBy('opron','asc')->take(150)->get();
        ]);
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        $validasi= $request->validate([
            'status'=>'required|max:100',
            'opron'=>'required|min:3',
            'prona'=>'required|max:255',
            'nama_supplier'=>'required|max:255',
            'stdqu'=>'required|max:25',
            'itype_id'=>'required|max:1',
            'brand_name'=>'required|max:100',
            'pgrup'=>'required|max:255',
            'sgrup_id'=>'required|max:3',
            'ssgrup_id'=>'required|max:20',
            'lssgrup'=>'required|max:20',
            'weigh'=>'required',
            'meast'=>'required',
            'measl'=>'required',
            'measp'=>'required',
            'volum'=>'required',
            'abccl'=>'required|max:5',
            'capac'=>'required|max:50',
            'platf'=>'required|max:50',
            'mstok'=>'required|max:5',
            'spnum'=>'required|max:50',
            'garansi'=>'required|max:5',
            'pbilp'=>'required|max:5',
            'ijtype'=>'required|max:50',
            'id_cls'=>'required|max:1',
        ]);
        Mpromas::create([
            'status'=>strtoupper($request->status),
            'opron'=>strtoupper($request->opron.$request->opron1.$request->opron2),
            'prona'=>strtoupper($request->prona),
            'nama_supplier'=>strtoupper($request->nama_supplier),
            'stdqu'=>strtoupper($request->stdqu),
            'itype_id'=>strtoupper($request->itype_id),
            'brand_name'=>strtoupper($request->brand_name),
            'pgrup'=>strtoupper($request->pgrup),
            'sgrup_id'=>strtoupper($request->sgrup_id),
            'ssgrup_id'=>strtoupper($request->ssgrup_id),
            'lssgrup'=>strtoupper($request->lssgrup),
            'weigh'=>strtoupper($request->weigh),
            'meast'=>strtoupper($request->meast),
            'measl'=>strtoupper($request->measl),
            'measp'=>strtoupper($request->measp),
            'volum'=>strtoupper($request->volum),
            'abccl'=>strtoupper($request->abccl),
            'capac'=>strtoupper($request->capac),
            'platf'=>strtoupper($request->platf),
            'mstok'=>strtoupper($request->mstok),
            'spnum'=>strtoupper($request->spnum),
            'garansi'=>strtoupper($request->garansi),
            'pbilp'=>strtoupper($request->pbilp),
            'ijtype'=>strtoupper($request->ijtype),
            'id_cls'=>strtoupper($request->id_cls),
        ]);
        return redirect('/mpromas')->with('success','MPROMAS berhasil disimpan');
    }

    public function show(string $id)
    {
        //
    }

    public function edit(string $id)
    {
        //
    }

    public function update(Request $request, Mpromas $mproma)
    {
        $validasi= $request->validate([
            'status'=>'required|max:100',
            'prona'=>'required|max:255',
            'nama_supplier'=>'required|max:255',
            'stdqu'=>'required|max:25',
            'itype_id'=>'required|max:1',
            'brand_name'=>'required|max:100',
            'pgrup'=>'required|max:255',
            'sgrup_id'=>'required|max:3',
            'ssgrup_id'=>'required|max:20',
            'lssgrup'=>'required|max:20',
            'weigh'=>'required',
            'meast'=>'required',
            'measl'=>'required',
            'measp'=>'required',
            'volum'=>'required',
            'abccl'=>'required|max:5',
            'capac'=>'required|max:50',
            'platf'=>'required|max:50',
            'mstok'=>'required|max:5',
            'spnum'=>'required|max:50',
            'garansi'=>'required|max:5',
            'pbilp'=>'required|max:5',
            'ijtype'=>'required|max:50',
            'id_cls'=>'required|max:1',
        ]);
        Mpromas::where("mproma", $mproma->mproma)
                ->update([
                    'status'=>$request->status,
                    'prona'=>$request->prona,
                    'nama_supplier'=>$request->nama_supplier,
                    'stdqu'=>$request->stdqu,
                    'itype_id'=>$request->itype_id,
                    'brand_name'=>$request->brand_name,
                    'pgrup'=>$request->pgrup,
                    'sgrup_id'=>$request->sgrup_id,
                    'ssgrup_id'=>$request->ssgrup_id,
                    'lssgrup'=>$request->lssgrup,
                    'weigh'=>$request->weigh,
                    'meast'=>$request->meast,
                    'measl'=>$request->measl,
                    'measp'=>$request->measp,
                    'volum'=>$request->volum,
                    'abccl'=>$request->abccl,
                    'capac'=>$request->capac,
                    'platf'=>$request->platf,
                    'mstok'=>$request->mstok,
                    'spnum'=>$request->spnum,
                    'garansi'=>$request->garansi,
                    'pbilp'=>$request->pbilp,
                    'ijtype'=>$request->ijtype,
                    'id_cls'=>$request->id_cls,
                ]);
        return redirect('/mpromas')->with('success','MPROMAS berhasil diubah');
    }

    public function destroy(Mpromas $mproma)
    {
        Mpromas::destroy($mproma->mproma);
        return redirect('/mpromas')->with('success','MPROMAS berhasil dihapus');
    }

    public function listJson(Request $request){
        $pageNumber = ( $request->start / $request->length )+1;
        $pageLength = $request->length;
        $skip       = ($pageNumber-1) * $pageLength;

        // Page Order
        $orderColumnIndex = $request->order[0]['column'] ?? '0';
        $orderBy = $request->order[0]['dir'] ?? 'desc';


        // Build Query
        // Main
        $query = DB::table('mpromas')->select('*');

        // Search
        $search = $request->cSearch;
        $query = $query->where(function($query) use ($search){
            $query->orWhere('opron', 'like', "%".$search."%");
            $query->orWhere('prona', 'like', "%".$search."%");
            $query->orWhere('brand_name', 'like', "%".$search."%");
        });

        $orderByName = 'opron';
        switch($orderColumnIndex){
            case '0':
                $orderByName = 'opron';
                break;
            case '1':
                $orderByName = 'prona';
                break;
            case '2':
                $orderByName = 'brand_name';
                break;
            case '3':
                $orderByName = 'sgrup_id';
                break;
            default:
                $orderByName = 'opron';
                break;
        }

        $query = $query->orderBy($orderByName, $orderBy);
        $recordsFiltered = $recordsTotal = $query->count();
        $users = $query->skip($skip)->take($pageLength)->get();

        return response()->json(["draw"=> $request->draw, "recordsTotal"=> $recordsTotal, "recordsFiltered" => $recordsFiltered, 'data' => $users], 200);
    }

    function cekOpron(Mpromas $mpromas){
        $opron= $_POST['opron'];
        $produk=Mpromas::Where('opron',$opron)->count();
        echo $produk;

    }

}
