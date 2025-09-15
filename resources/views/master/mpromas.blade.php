@extends('layout.main')
@section('container')

<main id="main" class="main">
    <div class="pagetitle">
        <h1>PRODUK MASTER</h1>
        <nav>
            <ol class="breadcrumb">
            <li class="breadcrumb-item">MASTER</li>
            <li class="breadcrumb-item active">MPROMAS</li>
            </ol>
        </nav>
    </div>

    <section class="section">
        <div class="row">
          <div class="col-lg-12">
            <div class="card">
              <div class="card-body">
                @if(session()->has('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                @endif
                <div class="col-lg-12" style="padding:0px 10px 10px 0px;">
                    <button id="tambahMpromas" type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#mTMpromas"> Tambah</button>

                </div>

                <div style="overflow-y: scroll;">
                    <table id="example26" class="table table-striped nowrap" style="width:100%">
                        <thead>
                          <tr>
                            <th>OPRON</th>
                            <th>PRONA</th>
                            <th>Nama Supplier</th>
                            <th>Satuan</th>
                            <th>Inventory Type</th>
                            <th>Brand</th>
                            <th>Grup Produk</th>
                            <th>Sub Grup</th>
                            <th>Sub Sub Grup</th>
                            <th>Logistik Sub Sub Produk</th>
                            <th>Berat</th>
                            <th>Tinggi</th>
                            <th>Lebar</th>
                            <th>Panjang</th>
                            <th>Volum</th>
                            <th>abccl</th>
                            <th>Kapasitas</th>
                            <th>Platform</th>
                            <th>Min Stok</th>
                            <th>Nomor</th>
                            <th>Garansi</th>
                            <th>Pbilp</th>
                            <th>Ijtype</th>
                            <th>Cls ID</th>
                            <th></th>
                          </tr>
                        </thead>

                    </table>
                </div>


              </div>
            </div>
          </div>
        </div>
    </section>

</main>

<div class="modal fade" id="mTMpromas" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Tambah PRODUK MASTER</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <form method="post" action="/mpromas" class="row g-3">
                @csrf
                <div class="col-6 row g-3">
                    <div class="col-12">
                        <label for="status" class="form-label">STATUS</label>
                        <input type="text" class="form-control text-uppercase" id="status" name="status" maxlength="100">
                    </div>
                    <div class="col-12">
                        <label for="opron" class="form-label col-12">KODE</label>
                        <input list="opron" name="opron" id="oprons" required class="form-control text-uppercase" placeholder="" data-toggle="tooltip" data-placement="left" title="PASTIKAN NOMOR BARU TIDAK TAMPIL DILIST">
                        <datalist id="opron">
                            @foreach ($mpromases as $mproma)
                                <option value="{{ $mproma->opron }}">{{ $mproma->opron }}
                            @endforeach
                        </datalist>
                        {{-- <div class="col-6" style="float: left;">
                            <input type="text" class="form-control text-uppercase" id="opron" name="opron" required maxlength="20" onpaste="return false">
                        </div>
                        <div class="col-1" style="float: left;">
                            <span class="form-control" style="border: none; font-weight: bold;">/</span>
                        </div>
                        <div class="col-2" style="float: left;">
                            <input type="text" class="form-control text-uppercase" id="opron1" name="opron1" maxlength="3" onpaste="return false">
                        </div>
                        <div class="col-1" style="float: left;">
                            <span class="form-control" style="border: none; font-weight: bold;">/</span>
                        </div>
                        <div class="col-2" style="float: left;">
                            <input type="text" class="form-control text-uppercase" id="opron2" name="opron2" maxlength="4" onpaste="return false">
                        </div> --}}
                    </div>
                    <div class="col-12">
                        <label for="prona" class="form-label">NAMA BARANG</label>
                        <input type="text" class="form-control text-uppercase" id="prona" name="prona" required maxlength="255">
                    </div>
                    <div class="col-12">
                        <label for="nama_supplier" class="form-label">NAMA UNTUK SUPPLIER</label>
                        <input type="text" class="form-control text-uppercase" id="nama_supplier" name="nama_supplier" required maxlength="255">
                    </div>
                    <div class="col-12">
                        <label for="stdqu" class="form-label">SATUAN</label>
                        <input type="text" class="form-control text-uppercase" id="stdqu" name="stdqu" required maxlength="25">
                    </div>
                    <div class="col-12">
                        <label for="itype_id" class="form-label">INVENTORY TYPE</label>
                        <input type="text" class="form-control text-uppercase" id="itype_id" name="itype_id" required maxlength="1">
                    </div>
                    <div class="col-12">
                        <label for="brand_name" class="form-label">BRAND/MERK</label>
                        <input type="text" class="form-control text-uppercase" id="brand_name" name="brand_name" required maxlength="100">
                    </div>
                    <div class="col-12">
                        <label for="pgrup" class="form-label">GRUP PRODUK</label>
                        <input type="text" class="form-control text-uppercase" id="pgrup" name="pgrup" required maxlength="255">
                    </div>
                    <div class="col-12">
                        <label for="sgrup_id" class="form-label">SUB GROUP</label>
                        <input type="text" class="form-control text-uppercase" id="sgrup_id" name="sgrup_id" required maxlength="3">
                    </div>
                    <div class="col-12">
                        <label for="ssgrup_id" class="form-label">SUB SUB GROUP</label>
                        <input type="text" class="form-control text-uppercase" id="ssgrup_id" name="ssgrup_id" required maxlength="3">
                    </div>
                    <div class="col-12">
                        <label for="lssgrup" class="form-label">LOGISTIK SUB SUB GROUP</label>
                        <input type="text" class="form-control text-uppercase" id="lssgrup" name="lssgrup" required maxlength="3">
                    </div>
                    <div class="col-12">
                        <label for="weigh" class="form-label">BERAT</label>
                        <input type="number" step="0.001" class="form-control text-uppercase" id="weigh" name="weigh" required maxlength="" min="0">
                    </div>
                    <div class="col-12">
                        <label for="meast" class="form-label">TINGGI</label>
                        <input type="number" step="0.001" class="form-control text-uppercase" id="meast" name="meast" required maxlength="" min="0">
                    </div>
                </div>


                <div class="col-6 row g-3">
                    <div class="col-12">
                        <label for="measl" class="form-label">LEBAR</label>
                        <input type="number" step="0.001" class="form-control text-uppercase" id="measl" name="measl" required maxlength="" min="0">
                    </div>
                    <div class="col-12">
                        <label for="measp" class="form-label">PANJANG</label>
                        <input type="number" step="0.001" class="form-control text-uppercase" id="measp" name="measp" required maxlength="" min="0">
                    </div>
                    <div class="col-12">
                        <label for="volum" class="form-label">VOLUME</label>
                        <input type="number" step="0.001" class="form-control text-uppercase" id="volum" name="volum" required maxlength="" min="0">
                    </div>
                    <div class="col-12">
                        <label for="abccl" class="form-label">ABCCL</label>
                        <input type="text" class="form-control text-uppercase" id="abccl" name="abccl" required maxlength="5">
                    </div>
                    <div class="col-12">
                        <label for="capac" class="form-label">KAPASITAS</label>
                        <input type="text" class="form-control text-uppercase" id="capac" name="capac" required maxlength="50">
                    </div>
                    <div class="col-12">
                        <label for="platf" class="form-label">UKURAN PLATFORM</label>
                        <input type="text" class="form-control text-uppercase" id="platf" name="platf" required maxlength="50">
                    </div>
                    <div class="col-12">
                        <label for="mstok" class="form-label">MIN STOK</label>
                        <input type="number" class="form-control text-uppercase" id="mstok" name="mstok" required maxlength="5" min="0">
                    </div>
                    <div class="col-12">
                        <label for="spnum" class="form-label">PART NUMBER</label>
                        <input type="text" class="form-control text-uppercase" id="spnum" name="spnum" required maxlength="50">
                    </div>
                    <div class="col-12">
                        <label for="garansi" class="form-label">GARANSI</label>
                        <input type="number" class="form-control text-uppercase" id="garansi" name="garansi" required maxlength="5" min="0">
                    </div>
                    <div class="col-12">
                        <label for="pbilp" class="form-label">PBILP</label>
                        <input type="text" class="form-control text-uppercase" id="pbilp" name="pbilp" required maxlength="5">
                    </div>
                    <div class="col-12">
                        <label for="ijtype" class="form-label">IJTYPE</label>
                        <input type="text" class="form-control text-uppercase" id="ijtype" name="ijtype" required maxlength="50">
                    </div>
                    <div class="col-12">
                        <label for="id_cls" class="form-label">CLASS</label>
                        <input type="text" class="form-control text-uppercase" id="id_cls" name="id_cls" required maxlength="1">
                    </div>
                </div>
        </div>
        <div class="modal-footer">
            <button type="submit" class="btn btn-primary">Simpan</button>
            </form>
        </div>
      </div>
    </div>
</div>


<div class="modal fade" id="mLMpromas" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Ubah PRODUK MASTER</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <form method="post" id="formUpdatePromas" action="" class="row g-3">
                @csrf
                @method('put')
                <input type="hidden" id="mproma" name="mproma" value="">
                <div class="col-6 row g-3">
                    <div class="col-12">
                        <label for="status" class="form-label">STATUS</label>
                        <input type="text" class="form-control text-uppercase" id="statusEdit" name="status" maxlength="100">
                    </div>
                    <div class="col-12">
                        <label for="opron" class="form-label col-12">KODE</label>
                        <input type="text" class="form-control text-uppercase" id="opronEdit" name="opron" readonly maxlength="25" onpaste="return false" value="">
                    </div>
                    <div class="col-12">
                        <label for="prona" class="form-label">NAMA BARANG</label>
                        <input type="text" class="form-control text-uppercase" id="pronaEdit" name="prona" required maxlength="255">
                    </div>
                    <div class="col-12">
                        <label for="nama_supplier" class="form-label">NAMA UNTUK SUPPLIER</label>
                        <input type="text" class="form-control text-uppercase" id="nama_supplierEdit" name="nama_supplier" required maxlength="255">
                    </div>
                    <div class="col-12">
                        <label for="stdqu" class="form-label">SATUAN</label>
                        <input type="text" class="form-control text-uppercase" id="stdquEdit" name="stdqu" required maxlength="25">
                    </div>
                    <div class="col-12">
                        <label for="itype_id" class="form-label">INVENTORY TYPE</label>
                        <input type="text" class="form-control text-uppercase" id="itype_idEdit" name="itype_id" required maxlength="1">
                    </div>
                    <div class="col-12">
                        <label for="brand_name" class="form-label">BRAND/MERK</label>
                        <input type="text" class="form-control text-uppercase" id="brand_nameEdit" name="brand_name" required maxlength="100">
                    </div>
                    <div class="col-12">
                        <label for="pgrup" class="form-label">GRUP PRODUK</label>
                        <input type="text" class="form-control text-uppercase" id="pgrupEdit" name="pgrup" required maxlength="255">
                    </div>
                    <div class="col-12">
                        <label for="sgrup_id" class="form-label">SUB GROUP</label>
                        <input type="text" class="form-control text-uppercase" id="sgrup_idEdit" name="sgrup_id" required maxlength="3">
                    </div>
                    <div class="col-12">
                        <label for="ssgrup_id" class="form-label">SUB SUB GROUP</label>
                        <input type="text" class="form-control text-uppercase" id="ssgrup_idEdit" name="ssgrup_id" required maxlength="3">
                    </div>
                    <div class="col-12">
                        <label for="lssgrup" class="form-label">LOGISTIK SUB SUB GROUP</label>
                        <input type="text" class="form-control text-uppercase" id="lssgrupEdit" name="lssgrup" required maxlength="3">
                    </div>
                    <div class="col-12">
                        <label for="weigh" class="form-label">BERAT</label>
                        <input type="number" step="0.001" class="form-control text-uppercase" id="weighEdit" name="weigh" required maxlength="" min="0">
                    </div>
                    <div class="col-12">
                        <label for="meast" class="form-label">TINGGI</label>
                        <input type="number" step="0.001" class="form-control text-uppercase" id="meastEdit" name="meast" required maxlength="" min="0">
                    </div>
                </div>


                <div class="col-6 row g-3">
                    <div class="col-12">
                        <label for="measl" class="form-label">LEBAR</label>
                        <input type="number" step="0.001" class="form-control text-uppercase" id="measlEdit" name="measl" required maxlength="" min="0">
                    </div>
                    <div class="col-12">
                        <label for="measp" class="form-label">PANJANG</label>
                        <input type="number" step="0.001" class="form-control text-uppercase" id="measpEdit" name="measp" required maxlength="" min="0">
                    </div>
                    <div class="col-12">
                        <label for="volum" class="form-label">VOLUME</label>
                        <input type="number" step="0.001" class="form-control text-uppercase" id="volumEdit" name="volum" required maxlength="" min="0">
                    </div>
                    <div class="col-12">
                        <label for="abccl" class="form-label">ABCCL</label>
                        <input type="text" class="form-control text-uppercase" id="abcclEdit" name="abccl" required maxlength="5">
                    </div>
                    <div class="col-12">
                        <label for="capac" class="form-label">KAPASITAS</label>
                        <input type="text" class="form-control text-uppercase" id="capacEdit" name="capac" required maxlength="50">
                    </div>
                    <div class="col-12">
                        <label for="platf" class="form-label">UKURAN PLATFORM</label>
                        <input type="text" class="form-control text-uppercase" id="platfEdit" name="platf" required maxlength="50">
                    </div>
                    <div class="col-12">
                        <label for="mstok" class="form-label">MIN STOK</label>
                        <input type="number" class="form-control text-uppercase" id="mstokEdit" name="mstok" required maxlength="5" min="0">
                    </div>
                    <div class="col-12">
                        <label for="spnum" class="form-label">PART NUMBER</label>
                        <input type="text" class="form-control text-uppercase" id="spnumEdit" name="spnum" required maxlength="50">
                    </div>
                    <div class="col-12">
                        <label for="garansi" class="form-label">GARANSI</label>
                        <input type="number" class="form-control text-uppercase" id="garansiEdit" name="garansi" required maxlength="5" min="0">
                    </div>
                    <div class="col-12">
                        <label for="pbilp" class="form-label">PBILP</label>
                        <input type="text" class="form-control text-uppercase" id="pbilpEdit" name="pbilp" required maxlength="5">
                    </div>
                    <div class="col-12">
                        <label for="ijtype" class="form-label">IJTYPE</label>
                        <input type="text" class="form-control text-uppercase" id="ijtypeEdit" name="ijtype" required maxlength="50">
                    </div>
                    <div class="col-12">
                        <label for="id_cls" class="form-label">CLASS</label>
                        <input type="text" class="form-control text-uppercase" id="id_clsEdit" name="id_cls" required maxlength="1">
                    </div>
                </div>
        </div>
        <div class="modal-footer">
            <button type="submit" class="btn btn-primary" id="btn_update_mproma">Simpan</button>
            </form>
        </div>
      </div>
    </div>
</div>

@endsection
