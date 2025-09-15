<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Mcusmas;
use Illuminate\Support\Facades\DB;
use App\Models\Provinsi;
use App\Models\Mdepo;
use App\Models\Mbranch;
use App\Models\KabKot;
use App\Models\McusmasCbg;
use App\Models\McusmasDet;
use Yajra\DataTables\DataTables;

class CusmasController extends Controller
{
    public function mas(){

    }

    public function index(Request $request)
    {
        if(auth()->user()->cabang=="PST"){
            $mas=DB::table('tcustomer_tbl')
            ->join('provinsi','provinsi.id_prov','=','tcustomer_tbl.provinsi')
            ->join('kabupaten_kota','kabupaten_kota.id','=','tcustomer_tbl.kabupaten')
            ->leftJoin('tcustomer_det','tcustomer_tbl.customer_id','=','tcustomer_det.customer_id')
            ->select('tcustomer_tbl.*', 'provinsi.id_prov', 'provinsi.provinsi', 'kabupaten_kota.*', 'tcustomer_det.id', 'tcustomer_det.customer_cbg_id', 'tcustomer_det.npwp', 'tcustomer_det.nik')
            ->orderBy('tcustomer_tbl.nama_perusahaan','ASC')->get();
        }else{
            $mas=DB::table('tcustomer_tbl')
            ->join('provinsi','provinsi.id_prov','=','tcustomer_tbl.provinsi')
            ->join('kabupaten_kota','kabupaten_kota.id','=','tcustomer_tbl.kabupaten')
            ->leftJoin('tcustomer_det','tcustomer_tbl.customer_id','=','tcustomer_det.customer_id')
            ->select('tcustomer_tbl.*', 'provinsi.id_prov', 'provinsi.provinsi', 'kabupaten_kota.*', 'tcustomer_det.id', 'tcustomer_det.customer_cbg_id', 'tcustomer_det.npwp', 'tcustomer_det.nik')
            ->where('tcustomer_tbl.braco',auth()->user()->cabang)
            ->orderBy('tcustomer_tbl.nama_perusahaan','ASC')->get();
        }

        if (request()->ajax()) {
            $cusmas = $mas;
            return DataTables::of($cusmas)
            ->addIndexColumn()
            ->addColumn('action', function ($item) {
                $btn='';
                $button =   '<a id="btn_edit_cusmas" href="/mcusmas/'. $item->customer_id .'/edit" class="btn btn-block badge bg-info" >Ubah</a>';
                $btn .= $button;

                if(auth()->user()->level=="IT"){
                $deleteButton = '<form class="d-inline" action=/mcusmas/' . $item->customer_id . '
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
        return view('master.mcusmas', [
            // 'mcusmas'=>$mas
        ]);
    }

    public function create()
    {
        return view('master.mcusmasadd',[

        ]);
    }

    public function grup(){ $mcusmas= Mcusmas::select('groupp')->groupBy('groupp')->get(); ?>
        <datalist id="groupp_">
            <option value=""></option>
            <?php foreach($mcusmas as $mcusma){ ?>
                <option value="<?php echo $mcusma->groupp ?>"></option>
            <?php } ?>
        </datalist> <?php
    }
    public function customer(){ $mcusmas= Mcusmas::select('nama_perusahaan')->get(); ?>
        <datalist id="customer_">
            <option value=""></option>
            <?php foreach($mcusmas as $mcusma){ ?>
                <option value="<?php echo $mcusma->nama_perusahaan ?>"></option>
            <?php } ?>
        </datalist> <?php
    }
    public function getDepo($id){
        $mdepos=Mdepo::select('depo','name')->where('braco', $id)->get();
        foreach($mdepos as $kk){ ?>
            <option value="<?php echo $kk->depo ?>"><?php echo $kk->name ?></option>
        <?php }
    }
    public function getSite($id){
        $site=McusmasCbg::join('provinsi','provinsi.id_prov','=','tcustomer_cbg_tbl.provinsi')
            ->select('provinsi.*', 'tcustomer_cbg_tbl.*', 'kabupaten_kota.kabupaten')
            ->join('kabupaten_kota','kabupaten_kota.id','=','tcustomer_cbg_tbl.kabupaten')

            ->where('customer_id',$id)->get(); ?>
        <table id="myTable12" class="table table-striped nowrap" style="width:100%">
            <div class="col-lg-12">
                <a id="tambahKabKotCusMas" class="btn btn-success"><i class="ri-add-line"></i> Site</a>
            </div>
            <thead>
                <tr>
                    <th>SITE</th>
                    <th>ALAMAT</th>
                    <th>TELP.</th>
                    <th>FAX</th>
                    <th>EMAIL</th>
                    <th>KONTAK PERSON</th>
                    <th>TELP. KONTAK</th>
                    <th>PROVINSI</th>
                    <th>KAB/KOTA</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
            <?php foreach($site as $i){ ?>
                <tr id="siteDtl" lokasi="<?php echo $i["lokasi"]; ?>" alamat="<?php echo $i["alamat"]; ?>" telp="<?php echo $i["telp"]; ?>" fax="<?php echo $i["fax"]; ?>" email="<?php echo $i["email"]; ?>" kontak="<?php echo $i["kontak"]; ?>" telpKontak="telp_kontak" provinsi="<?php echo $i["provinsi"]; ?>" >
                    <td><?php echo strtoupper($i["lokasi"]); ?></td>
                    <td><?php echo strtoupper($i["alamat"]); ?></td>
                    <td><?php echo strtoupper($i["telp"]); ?></td>
                    <td><?php echo strtoupper($i["fax"]); ?></td>
                    <td><?php echo strtoupper($i["email"]); ?></td>
                    <td><?php echo strtoupper($i["kontak"]); ?></td>
                    <td><?php echo strtoupper($i["telp_kontak"]); ?></td>
                    <td><?php echo strtoupper($i["provinsi"]); ?></td>
                    <td><?php echo strtoupper($i["kabupaten"]); ?></td>
                    <td style="text-align: center;">
                        <form method="post" action="/mcusmascab/<?php echo $i["id"]; ?>" class="d-inline">
                            <input type="hidden" name="_token" value='<?php echo csrf_token() ?>'>
                            <input type="hidden" name="_method" value="delete">
                            <button type="" id="del_cabang_eacd" value="<?php echo $i["id"]; ?>" class="border-0 badge bg-danger" onclick="return confirm('Apakah anda yakin?')">Hapus</button>
                        </form>
                    </td>
                </tr>
            <?php } ?>
            </tbody>
        </table>
        <?php
    }

    public function getMesin($id){
        $mesin=Mcusmas::select('lokasi','mssgrup_tbl.descr_ssgrup','type','kapasitas','serial_num','sn_indikator','status','intalled','akhir_tera','nomor_mc','awal_mc','akhir_mc','no_garansi','awal_mc_garansi','akhir_mc_garansi','tmesin_tbl.idmesin','tcustomer_tbl.customer_id')
            ->join('tmesin_tbl','tcustomer_tbl.customer_id','=','tmesin_tbl.customer_id')
            ->join('provinsi','provinsi.id_prov','=','tcustomer_tbl.provinsi')
            ->join('tmc_tbl','tmc_tbl.idmesin','=','tmesin_tbl.idmesin')
            ->join('mssgrup_tbl','mssgrup_tbl.ssgrup_id','=','tmesin_tbl.product')
            ->where('tcustomer_tbl.customer_id',$id)->get(); ?>
        <table id="myTable13" class="table table-striped nowrap" style="width:100%">
            <div class="col-lg-12">
                <!-- <a id="tambahMesinCusMas" class="btn btn-success"><i class="ri-add-line"></i> Mesin</a> -->
            </div>
            <thead>
                <tr>
                    <th>Site</th>
                    <th>Produk</th>
                    <th>Tipe</th>
                    <th>Kapasitas</th>
                    <th>S/N</th>
                    <th>S/N Indikator</th>
                    <th>Status</th>
                    <th>Instalasi</th>
                    <th>Tera</th>
                    <th>MC / Garansi</th>
                    <th>Awal</th>
                    <th>Akhir</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
            <?php if(!empty($mesin)) foreach($mesin as $i){ ?>
                <tr id="mesinDtl" >
                    <td><?php echo strtoupper($i["lokasi"]); ?></td>
                    <td><?php echo strtoupper($i["descr_ssgrup"]); ?></td>
                    <td><?php echo strtoupper($i["type"]); ?></td>
                    <td><?php echo strtoupper($i["kapasitas"]); ?></td>
                    <td><?php echo strtoupper($i["serial_num"]); ?></td>
                    <td><?php echo strtoupper($i["sn_indikator"]); ?></td>
                    <td><?php echo strtoupper($i["status"]); ?></td>
                    <td><?php echo date("d-m-Y", strtotime($i["intalled"])); ?></td>
                    <td><?php echo date("d-m-Y", strtotime($i["akhir_tera"])); ?></td>
                    <?php if($i['status']!="Garansi") {?>
                        <td><?php echo strtoupper($i["nomor_mc"]); ?></td>
                        <td><?php echo date("d-m-Y", strtotime($i["awal_mc"])); ?></td>
                        <td><?php echo date("d-m-Y", strtotime($i["akhir_mc"])); ?></td>
                    <?php }else {?>
                        <td><?php echo strtoupper($i["no_garansi"]); ?></td>
                        <td><?php echo date("d-m-Y", strtotime($i["awal_mc_garansi"])); ?></td>
                        <td><?php echo date("d-m-Y", strtotime($i["akhir_mc_garansi"])); ?></td>
                    <?php } ?>

                    <td style="text-align: center;">
                        <!-- <a id="del_cabang_eacd" id_del="<?php echo $i["idmesin"]; ?>" class="btn btn-block badge bg-danger" title="Delete" alt="Delete">Hapus</a> -->
                    </td>
                </tr>
            <?php } ?>
            </tbody>
        </table>
        <?php
    }

    public function provinsi(){
        $prov=Provinsi::all(); ?>
        <option value=""></option> <?php
        foreach($prov as $provinc){ ?>
            <option value="<?php echo $provinc->id_prov ?>"><?php echo $provinc->provinsi ?></option>
        <?php }
    }

    public function store(Request $request)
    {
        $validasi= $request->validate([
            'customer_key'=>'1',
            'groupp'=>'required|max:200',
            'title'=>'required|max:200',
            'nama_perusahaan'=>'required|unique:tcustomer_tbl|max:200',
            'lokasi'=>'required|max:200',
            'alamat'=>'required|max:200',
            'telp'=>'required|max:50',
            'fax'=>'required|max:50',
            'email'=>'required|max:100',
            'kontak'=>'required|max:100',
            'provinsi'=>'required|max:200',
            'braco'=>'required|max:3',
            'depo'=>'max:20',
            'user_'=>'required|max:50',
            'telp_kontak'=>'required|max:50',
            'kabupaten'=>'required|max:200',
        ]);
        $id= DB::table('tcustomer_tbl')->insertGetId($validasi);
        $cus= Mcusmas::where('customer_id',$id)->first();

        $idCab= DB::table('tcustomer_cbg_tbl')->insertGetId([
            'customer_id'=>$id,
            'lokasi'=> $cus->lokasi,
            'alamat'=> $cus->alamat,
            'telp'=> $cus->telp,
            'fax'=> $cus->fax,
            'email'=> $cus->email,
            'kontak'=> $cus->kontak,
            'provinsi'=> $cus->provinsi,
            'telp_kontak'=> $cus->telp_kontak,
            'kabupaten'=> $cus->kabupaten,
        ]);
        // var_dump($idCab); exit;

        DB::table('tcustomer_det')->insert([
            'customer_id'=> $id,
            'customer_cbg_id'=> $idCab,
            'npwp'=> $request->npwp,
            'nik'=> $request->nik,
        ]);

        return redirect('/mcusmas')->with('success','Pelanggan berhasil disimpan');
    }

    public function show(string $id)
    {
        //
    }

    public function edit($mcusma)
    {
        $mas=DB::table('tcustomer_tbl')
            ->select('tcustomer_tbl.*', 'provinsi.id_prov', 'provinsi.provinsi', 'kabupaten_kota.*', 'tcustomer_det.id', 'tcustomer_det.customer_cbg_id', 'tcustomer_det.npwp', 'tcustomer_det.nik')
            ->join('provinsi','provinsi.id_prov','=','tcustomer_tbl.provinsi')
            ->join('kabupaten_kota','kabupaten_kota.id','=','tcustomer_tbl.kabupaten')
            ->leftJoin('tcustomer_det','tcustomer_tbl.customer_id','=','tcustomer_det.customer_id')
            ->where('tcustomer_tbl.customer_id','=',$mcusma)->first();
        return view('master.mcusmasEdit',[
            'mcusma'=> $mas,
            'provinsi'=> Provinsi::all(),
            'kabkot'=> KabKot::all(),
            'branch'=> Mbranch::all(),
            'depo'=> Mdepo::all()
        ]);
    }

    public function update(Request $request, Mcusmas $mcusma)
    {
        $lokasi=$request->lokasi; $npwp=$request->npwp; $nik=$request->nik;
        $validasi= $request->validate([
            'groupp'=>'required|max:200',
            'title'=>'required|max:200',
            'nama_perusahaan'=>'required|max:200',
            'lokasi'=>'required|max:200',
            'alamat'=>'required|max:200',
            'telp'=>'required|max:50',
            'fax'=>'required|max:50',
            'email'=>'required|max:100',
            'kontak'=>'required|max:100',
            'provinsi'=>'required|max:200',
            'braco'=>'required|max:3',
            'depo'=>'max:20',
            'user_'=>'required|max:50',
            'telp_kontak'=>'required|max:50',
            'kabupaten'=>'required|max:200',
        ]);
        Mcusmas::where('customer_id', $mcusma->customer_id)->update($validasi);
        $cus= Mcusmas::where('customer_id',$mcusma->customer_id)->first();
        McusmasCbg::where(['customer_id'=> $mcusma->customer_id, 'lokasi'=> $lokasi ])->update([
            'lokasi'=> $cus->lokasi,
            'alamat'=> $cus->alamat,
            'telp'=> $cus->telp,
            'fax'=> $cus->fax,
            'email'=> $cus->email,
            'kontak'=> $cus->kontak,
            'provinsi'=> $cus->provinsi,
            'telp_kontak'=> $cus->telp_kontak,
            'kabupaten'=> $cus->kabupaten
        ]);

        McusmasDet::where('customer_id',$mcusma->customer_id)->update([
            'npwp'=> $npwp,
            'nik'=> $nik
        ]);

        $linkcus='/mcusmas/'.$mcusma->customer_id.'/edit';
        return redirect($linkcus)->with('success','MCUSMAS berhasil diubah');
    }

    public function destroy(Mcusmas $mcusma)
    {
        Mcusmas::destroy($mcusma);
        return redirect('/mcusmas')->with('success','MCUSMAS berhasil dihapus');
    }
}
