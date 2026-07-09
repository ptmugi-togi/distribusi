<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

use App\Models\Mstmas;
use App\Models\Mcusmas;
use App\Models\Mcindu;
use App\Models\Mczone;
use App\Models\Mbranch;
use App\Models\Mcarea;
use Yajra\DataTables\DataTables;

class Cusmas extends Controller
{
    public function index()
    {
        $cusmas=DB::table('mcusmas')
            ->select('mcusmas.*')
            ->where('mcusmas.braco', auth()->user()->cabang)
            ->orderBy('mcusmas.cusno','ASC')->get();

        return view('master.mcusmas.mcusmas_index', compact('cusmas'));
    }

    public function create(Request $request)
    {
        $bracos = DB::table('mbranches')->get();
        $cindus = DB::table('mcindu_tbl')->get();
        $czones = DB::table('mczone_tbl')->get();
        $careas = DB::table('mcarea_tbl')->get();
        $provinsi = DB::table('provinsi')->get();

        return view('master.mcusmas.mcusmas_create', compact('bracos', 'cindus', 'czones', 'careas', 'provinsi'));
    }

    public function store(Request $request)
    {
        DB::beginTransaction();
        try {
        $validasi = $request->validate([
            'braco'     => 'required',
            'dopen'     => 'required|date',
            'cusno'     => 'required|unique:mcusmas,cusno',
            'cusna'     => 'required|max:200',
            'billn'     => 'required|max:200',
            'email'     => 'required|email|max:100',
            'taxrn'     => 'required|max:100',
            'nitku'     => 'required|max:100',
            'title'     => 'required',
            'pkp'       => 'required',
            'province'  => 'required|max:100',
            'kabupaten' => 'required|max:100',
            'offcy'     => 'required|max:100',
            'address'   => 'required',
            'opost'     => 'required|max:15',
            'offph'     => 'required|max:100',
            'offax'     => 'required|max:100',
            'ofcon'     => 'required|max:100',
            'topay'     => 'required|numeric',
            'cindu'     => 'required',
            'lauid'     => 'required',
            'ladup'     => 'required',
        ],
        [
            'cusno.unique' => 'Kode Customer sudah digunakan.',
        ]);

        foreach ($validasi as $key => $value) {
            if (is_string($value) && $key !== 'email') {
                $validasi[$key] = mb_strtoupper($value, 'UTF-8');
            }
        }

        Mcusmas::create($validasi);

        Mstmas::create([
            'braco' => $request->braco,
            'cusno' => $request->cusno,
            'shpto' => '1',
            'shpnm' => $request->cusna,
            'deliveryaddress' => $request->address,
            'phone' => $request->offph,
            'fax' => $request->offax,
            'contp' => $request->ofcon,
            'nitku' => $request->nitku,
            'province' => $request->province,
            'kabupaten' => $request->kabupaten,
        ]);

        DB::commit();
            return redirect()->route('cusmas.index')->with('success', "Data Customer \"$request->cusno\" berhasil disimpan.");
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Gagal simpan data customer:', ['error' => $e->getMessage()]);
            return back()->with('error', 'Terjadi kesalahan saat menyimpan: ' . $e->getMessage());
        }
    }

    public function show(string $id)
    {
        $cust = Mcusmas::with('industry', 'prov', 'kabkota')->findOrFail($id);
        return view('master.mcusmas.mcusmas_detail', compact('cust'));
    }

    public function edit(string $id)
    {
        $cust = Mcusmas::with('industry', 'prov', 'kabkota')->findOrFail($id);
        $prov = DB::table('provinsi')->get();
        $cindu = DB::table('mcindu_tbl')->get();

        return view('master.mcusmas.mcusmas_edit', compact('cust', 'prov', 'cindu'));
    }

    public function update(Request $request, $id)
    {
        DB::beginTransaction();

        try {

            $validasi = $request->validate([
                'braco'     => 'required',
                'dopen'     => 'required|date',
                'cusno'     => 'required',
                'cusna'     => 'required|max:200',
                'billn'     => 'required|max:200',
                'email'     => 'required|email|max:100',
                'taxrn'     => 'required|max:100',
                'nitku'     => 'required|max:100',
                'title'     => 'required',
                'pkp'       => 'required',
                'province'  => 'required|max:100',
                'kabupaten' => 'required|max:100',
                'offcy'     => 'required|max:100',
                'address'   => 'required',
                'opost'     => 'required|max:15',
                'offph'     => 'required|max:100',
                'offax'     => 'required|max:100',
                'ofcon'     => 'required|max:100',
                'topay'     => 'required|numeric',
                'cindu'     => 'required',
                'lauid'     => 'required',
            ]);

            foreach ($validasi as $key => $value) {
                if (is_string($value) && $key !== 'email') {
                    $validasi[$key] = mb_strtoupper($value, 'UTF-8');
                }
            }

            Mcusmas::where('cusno', $id)->update($validasi);

            Mstmas::where('cusno', $id)
                ->where('shpto', '1')
                ->update([
                    'braco'             => $request->braco,
                    'cusno'             => $request->cusno,
                    'shpnm'             => $request->cusna,
                    'deliveryaddress'   => $request->address,
                    'phone'             => $request->offph,
                    'fax'               => $request->offax,
                    'contp'             => $request->ofcon,
                    'nitku'             => $request->nitku,
                    'province'          => $request->province,
                    'kabupaten'         => $request->kabupaten,
                ]);

            DB::commit();

            return redirect()->route('cusmas.index')
                ->with('success', "Data Customer \"$request->cusno\" berhasil diubah.");

        } catch (\Exception $e) {
            DB::rollBack();

            \Log::error('Gagal update data customer:', [
                'error' => $e->getMessage()
            ]);

            return back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan saat mengubah data: '.$e->getMessage());
        }
    }

    public function destroy($id)
    {
        DB::beginTransaction();

        try {

            if (DB::table('tinmas')->where('cusno', $id)->exists()) {
                return back()->with('error', 'Customer tidak dapat dihapus karena masih digunakan pada data Invoice.');
            }

            if (DB::table('tcoreh')->where('cusno', $id)->exists()) {
                return back()->with('error', 'Customer tidak dapat dihapus karena masih digunakan pada data Core History.');
            }

            if (DB::table('tproja')->where('cusno', $id)->exists()) {
                return back()->with('error', 'Customer tidak dapat dihapus karena masih digunakan pada data Project.');
            }

            Mcusmas::where('cusno', $id)->delete();

            Mstmas::where('cusno', $id)->delete();

            DB::commit();

            return redirect()
                ->route('cusmas.index')
                ->with('success', 'Customer berhasil dihapus.');

        } catch (\Exception $e) {

            DB::rollBack();

            \Log::error('Gagal menghapus customer', [
                'error' => $e->getMessage()
            ]);

            return redirect()
                ->route('cusmas.index')
                ->with('error', 'Terjadi kesalahan saat menghapus customer.');
        }
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

    public function getKabKota(Request $request)
    {
        $kabkota = DB::table('kabupaten_kota')
            ->where('id_prov', $request->prov)
            ->get();

        return response()->json($kabkota);
    }

}
