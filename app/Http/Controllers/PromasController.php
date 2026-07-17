<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Mpromas;
use App\Models\Mssgrup_tbl;
use App\Models\Msgrup_tbl;
use App\Models\Mitype;
use App\Models\Mpgrup;

use Yajra\DataTables\DataTables;

class PromasController extends Controller
{
    public function data()
    {
        $query = DB::table('mpromas')
            ->leftJoin('msgrup', 'mpromas.sgrup_id', '=', 'msgrup.sgrup_id')
            ->leftJoin('mssgrup', 'mpromas.ssgrup_id', '=', 'mssgrup.ssgrup_id')
            ->leftJoin('mitype_tbl', 'mpromas.itype_id', '=', 'mitype_tbl.itype_id')
            ->select([
                'mpromas.opron as opron',
                'mpromas.prona as prona',
                'mpromas.itype_id',
                'mitype_tbl.descr_itype as itype',
                'mpromas.sgrup_id',
                'mpromas.ssgrup_id',
                'msgrup.descr_sgrup as sgrup',
                'mssgrup.descr_ssgrup as ssgrup',
            ]);

        return datatables()
            ->of($query)
            ->addColumn('action', function ($row) {
                return view('master.mpromas.mpromas_action', compact('row'));
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    public function index()
    {
        return view('master.mpromas.mpromas_index');
    }
    public function create()
    {
        $brands = DB::table('mbrand_tbl')->get();
        $itypes = DB::table('mitype_tbl')->get();
        $pgrups = DB::table('mpgrups')->get();
        $sgrups = DB::table('msgrup')->get();
        $ssgrups = DB::table('mssgrup')->get();

        return view('master.mpromas.mpromas_create', compact('brands', 'pgrups', 'itypes', 'sgrups', 'ssgrups'));
    }

    public function store(Request $request)
    {
        DB::beginTransaction();

        try {

            $validasi = $request->validate([
                'opron'      => 'required|unique:mpromas,opron|max:50',
                'prona'      => 'required|max:255',
                'iname'      => 'nullable|max:255',
                'stdqu'      => 'nullable|max:20',
                'itype_id'   => 'required',
                'brand'      => 'required',
                'pgrup'      => 'required',
                'sgrup_id'   => 'required',
                'ssgrup_id'  => 'required',
                'lssgrup'    => 'required',
                'status'     => 'nullable',
                'garan'      => 'required|numeric',
                'capac'      => 'nullable|max:100',
                'platf'      => 'nullable|max:100',
                'weigh'      => 'nullable|numeric',
                'meast'      => 'nullable|numeric',
                'measl'      => 'nullable|numeric',
                'measp'      => 'nullable|numeric',
                'ijtype'     => 'nullable|max:100',
                'mstok'      => 'nullable|numeric',
                'spnum'      => 'nullable|max:100',
                'acinv'      => 'nullable|max:100',
                'achpp'      => 'nullable|max:100',
                'acals'      => 'nullable|max:100',
                'acdis'      => 'nullable|max:100',
                'pbilp'      => 'nullable',
            ], [
                'opron.unique' => 'Kode Produk sudah digunakan.',
            ]);

            foreach ($validasi as $key => $value) {
                if (is_string($value)) {
                    $validasi[$key] = mb_strtoupper($value, 'UTF-8');
                }
            }

            Mpromas::create($validasi);

            DB::commit();

            return redirect()
                ->route('mpromas.index')
                ->with('success', 'Data Produk "' . $validasi['opron'] . ' - ' . $validasi['prona'] . '" berhasil disimpan.');

        } catch (\Exception $e) {

            DB::rollBack();

            \Log::error('Gagal simpan produk', [
                'error' => $e->getMessage()
            ]);

            return back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan saat menyimpan: '.$e->getMessage());
        }
    }

    public function show($opron)
    {
        $mpromas = Mpromas::where('opron', $opron)
            ->with('mitype', 'mpgrup', 'msgrup', 'mssgrup')
            ->firstOrFail();

        return view('master.mpromas.mpromas_detail', compact('mpromas'));
    }

    public function edit($opron)
    {
        $mpromas = Mpromas::where('opron', $opron)
            ->with('mitype', 'mpgrup', 'msgrup', 'mssgrup')
            ->firstOrFail();

        $brands = DB::table('mbrand_tbl')->get();
        $itypes = DB::table('mitype_tbl')->get();
        $pgrups = DB::table('mpgrups')->get();
        $sgrups = DB::table('msgrup')->get();
        $ssgrups = DB::table('mssgrup')->get();

        return view('master.mpromas.mpromas_edit', compact('mpromas', 'brands', 'itypes', 'pgrups', 'sgrups', 'ssgrups'));
    }

    public function update(Request $request, $opron)
    {
        DB::beginTransaction();

        try {

            $mpromas = Mpromas::where('opron', $opron)->firstOrFail();

            $validasi = $request->validate([
                'opron'      => 'required|max:50|unique:mpromas,opron,' . $mpromas->mproma . ',mproma',
                'prona'      => 'required|max:255',
                'iname'      => 'nullable|max:255',
                'stdqu'      => 'nullable|max:20',
                'itype_id'   => 'required',
                'brand'      => 'required',
                'pgrup'      => 'required',
                'sgrup_id'   => 'required',
                'ssgrup_id'  => 'required',
                'lssgrup'    => 'required',
                'status'     => 'nullable',
                'garan'      => 'required|numeric',
                'capac'      => 'nullable|max:100',
                'platf'      => 'nullable|max:100',
                'weigh'      => 'nullable|numeric',
                'meast'      => 'nullable|numeric',
                'measl'      => 'nullable|numeric',
                'measp'      => 'nullable|numeric',
                'ijtype'     => 'nullable|max:100',
                'mstok'      => 'nullable|numeric',
                'spnum'      => 'nullable|max:100',
                'acinv'      => 'nullable|max:100',
                'achpp'      => 'nullable|max:100',
                'acals'      => 'nullable|max:100',
                'acdis'      => 'nullable|max:100',
                'pbilp'      => 'nullable',
            ], [
                'opron.unique' => 'Kode Produk sudah digunakan.',
            ]);

            foreach ($validasi as $key => $value) {
                if (is_string($value)) {
                    $validasi[$key] = mb_strtoupper($value, 'UTF-8');
                }
            }

            $mpromas->update($validasi);

            DB::commit();

            return redirect()
                ->route('mpromas.index')
                ->with('success', 'Data Produk "' . $validasi['opron'] . ' - ' . $validasi['prona'] . '" berhasil diubah.');

        } catch (\Exception $e) {

            DB::rollBack();

            \Log::error('Gagal update produk', [
                'error' => $e->getMessage()
            ]);

            return back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan saat mengubah data: ' . $e->getMessage());
        }
    }

    public function destroy($opron)
    {
        try {
            $mpromas = Mpromas::where('opron', $opron)->firstOrFail();

            $namaProduk = $mpromas->opron . ' - ' . $mpromas->prona;

            $mpromas->delete();

            return redirect()
                ->route('mpromas.index')
                ->with('success', 'Data Produk "' . $namaProduk . '" berhasil dihapus.');
        } catch (\Exception $e) {
            return redirect()
                ->route('mpromas.index')
                ->with('error', 'Gagal menghapus data: ' . $e->getMessage());
        }
    }

    // public function listJson(Request $request){
    //     $pageNumber = ( $request->start / $request->length )+1;
    //     $pageLength = $request->length;
    //     $skip       = ($pageNumber-1) * $pageLength;

    //     // Page Order
    //     $orderColumnIndex = $request->order[0]['column'] ?? '0';
    //     $orderBy = $request->order[0]['dir'] ?? 'desc';


    //     // Build Query
    //     // Main
    //     $query = DB::table('mpromas')->select('*');

    //     // Search
    //     $search = $request->cSearch;
    //     $query = $query->where(function($query) use ($search){
    //         $query->orWhere('opron', 'like', "%".$search."%");
    //         $query->orWhere('prona', 'like', "%".$search."%");
    //         $query->orWhere('brand_name', 'like', "%".$search."%");
    //     });

    //     $orderByName = 'opron';
    //     switch($orderColumnIndex){
    //         case '0':
    //             $orderByName = 'opron';
    //             break;
    //         case '1':
    //             $orderByName = 'prona';
    //             break;
    //         case '2':
    //             $orderByName = 'brand_name';
    //             break;
    //         case '3':
    //             $orderByName = 'sgrup_id';
    //             break;
    //         default:
    //             $orderByName = 'opron';
    //             break;
    //     }

    //     $query = $query->orderBy($orderByName, $orderBy);
    //     $recordsFiltered = $recordsTotal = $query->count();
    //     $users = $query->skip($skip)->take($pageLength)->get();

    //     return response()->json(["draw"=> $request->draw, "recordsTotal"=> $recordsTotal, "recordsFiltered" => $recordsFiltered, 'data' => $users], 200);
    // }

    // function cekOpron(Mpromas $mpromas){
    //     $opron= $_POST['opron'];
    //     $produk=Mpromas::Where('opron',$opron)->count();
    //     echo $produk;

    // }

}
