<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Models\Mstmas;
use App\Models\Provinsi;
use App\Models\KabKot;
use Yajra\DataTables\DataTables;

class StmasController extends Controller
{
    public function data()
    {
        $query = DB::table('mcusmas')
            ->select(
                'braco',
                'cusno',
                DB::raw("CONCAT(cusno, ' - ', cusna) as customer"),
                'taxrn as npwp'
            );

        return datatables()
            ->of($query)
            ->filterColumn('customer', function ($query, $keyword) {
                $query->where(function ($q) use ($keyword) {
                    $q->where('mcusmas.cusno', 'like', "%{$keyword}%")
                    ->orWhere('mcusmas.cusna', 'like', "%{$keyword}%");
                });
            })
            ->addColumn('action', function ($row) {
                return view('master.mstmas.mstmas_action', compact('row'));
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    public function index()
    {
        return view('master.mstmas.mstmas_index');
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        //
    }

    public function show($braco, $cusno)
    {
        $shiptos = Mstmas::with('cusmas','prov', 'kabkota')
            ->where('braco', $braco)
            ->where('cusno', $cusno)
            ->orderBy('shpto')
            ->get();

        if ($shiptos->isEmpty()) {
            abort(404);
        }

        return view('master.mstmas.mstmas_detail', compact('shiptos'));
    }

    public function edit($braco, $cusno)
    {
        $shiptos = Mstmas::with('prov', 'kabkota', 'cusmas')
            ->where('braco', $braco)
            ->where('cusno', $cusno)
            ->orderBy('shpto')
            ->get();
        $prov = DB::table('provinsi')->get();

        abort_if($shiptos->isEmpty(), 404);

        return view('master.mstmas.mstmas_edit', compact('shiptos', 'prov'));
    }

    public function update(Request $request, $braco, $cusno)
    {
        DB::beginTransaction();

        try {

            $shptoForm = $request->shpto ?? [];

            DB::table('mstmas')
                ->where('braco', $braco)
                ->where('cusno', $cusno)
                ->whereNotIn('shpto', $shptoForm)
                ->delete();

            foreach ($shptoForm as $i => $shpto) {
                DB::table('mstmas')->updateOrInsert(
                    [
                        'braco' => $braco,
                        'cusno' => $cusno,
                        'shpto' => $shpto,
                    ],
                    [
                        'shpnm'             => strtoupper($request->shpnm[$i]) ?? null,
                        'deliveryaddress'   => strtoupper($request->deliveryaddress[$i]) ?? null,
                        'phone'             => $request->phone[$i] ?? null,
                        'fax'               => $request->fax[$i] ?? null,
                        'contp'             => strtoupper($request->contp[$i]) ?? null,
                        'nitku'             => $request->nitku[$i] ?? null,
                        'province'          => $request->province[$i] ?? null,
                        'kabupaten'         => $request->kabupaten[$i] ?? null,
                    ]
                );
            }

            DB::commit();

            return redirect()
                ->route('mstmas.index')
                ->with('success', "Shipto \"$cusno\" berhasil diupdate.");

        } catch (\Exception $e) {

            DB::rollBack();

            \Log::error('Gagal update data mtmas:', [
                'error' => $e->getMessage()
            ]);

            return back()
                ->withInput()
                ->with('error', $e->getMessage());
        }
    }
    public function destroy(Mstmas $mstma)
    {
        Mstmas::destroy($mstma->id);
        return redirect('/mstmas')->with('success','Shipto '.$mstma->cusno.' berhasil dihapus');
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
}
