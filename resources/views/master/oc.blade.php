@extends('layout.main')
@section('container')

<main id="main" class="main">
    <div class="pagetitle">
        <h1>OC</h1>
        <nav>
            <ol class="breadcrumb">
            <li class="breadcrumb-item">OC</li>
            <li class="breadcrumb-item active">RETAIL ORDER CONFIRMATION ENTRY</li>
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
                    <button id="tambahMcusmas" type="button" class="btn btn-primary"data-bs-toggle="modal" data-bs-target="#mTMcusmas"> Daftar OC</button>
                </div>

                <form method="post" action="/roce" class="row g-2 form_oc">
                    @csrf
                    <div class="col-6 row g-2">
                        <div class="col-8">
                            <label for="" class="col-sm-12 col-form-label">OC No.</label>
                            <input type="text" id="ocno" name="ocno" required class="form-control text-uppercase">
                        </div>
                        <div class="col-4">
                            <input type="date" id="tanggal" name="tanggal" value="{{ date('Y-m-d') }}" required class="form-control" tabindex="1" style="margin-top: 38px;" autofocus>
                        </div>
                        <div class="col-12">
                            <label for="" class="col-sm-12 col-form-label">Customer</label>
                            <input list="customer_" id="nama_perusahaan_oc" name="nama_perusahaan_oc" required class="form-control text-uppercase" tabindex="2" placeholder="Pilih Customer">
                            <input type="hidden" required name="nama_perusahaan" id="nama_perusahaan_fix">
                        </div>
                    </div>
                    <div class="col-6 row g-2">
                    </div>


                    <div class="col-6 row g-1">
                        <div class="col-8">
                            <label for="" class="col-sm-12 col-form-label">Sales rep.</label>
                            <input list="msrenosoc" id="msrenos_oc" name="msrenos_oc" required class="form-control text-uppercase" tabindex="3" placeholder="Pilih Sales rep.">
                            <input type="hidden" required name="msrenos" id="msrenos_fix">
                        </div>
                        <div class="col-4">
                            <label for="" class="col-sm-12 col-form-label">Product</label>
                            <select id="product_oc" name="product" required class="form-control text-uppercase" tabindex="4">
                                <option value="U">Utama</option>
                                <option value="N">Non Utama</option>
                            </select>
                        </div>
                        <div class="col-6">
                            <label for="" class="col-sm-12 col-form-label">Product Group</label>
                            <select id="grup_produk_oc" name="grup_produk" required class="form-control text-uppercase" tabindex="5">

                            </select>
                        </div>
                        <div class="col-6">
                            <label for="" class="col-sm-12 col-form-label">Class</label>
                            <select id="cls_oc" name="class" required class="form-control text-uppercase" tabindex="6">

                            </select>
                        </div>
                        <div class="col-12 input-group mb-3">
                            <label for="" class="col-sm-12 col-form-label">Payment term</label>
                            <input type="number" class="form-control" id="payment_term_oc" name="payment_term" required tabindex="7" aria-describedby="basic-addon2">
                            <span class="input-group-text" id="basic-addon2">days</span>
                        </div>
                        <div class="col-4">
                            <label for="" class="col-sm-12 col-form-label">Order Type</label>
                            <input type="text" class="form-control" id="telp" name="telp" required tabindex="8">
                        </div>
                        <div class="col-8">
                            <label for="" class="col-sm-12 col-form-label">Customer PO</label>
                            <input type="text" class="form-control" id="telp" name="telp" required tabindex="9">
                        </div>
                        <div class="col-6">
                            <label for="" class="col-sm-12 col-form-label">Currency code</label>
                            <input type="text" class="form-control" id="telp" name="telp" required tabindex="10">
                        </div>
                        <div class="clearfix"></div>
                        <div class="col-6">
                            <label for="" class="col-sm-12 col-form-label">Currency rate</label>
                            <input type="text" class="form-control" id="telp" name="telp" required tabindex="11">
                        </div>
                        <div class="clearfix"></div>
                        <div class="col-6 input-group mb-3">
                            <label for="" class="col-sm-12 col-form-label">EB type</label>
                            <input type="text" class="form-control" id="npwp" name="npwp" required tabindex="12" aria-describedby="basic-addon2">
                            <span class="input-group-text" id="basic-addon2">P-%, V-value</span>
                        </div>
                        <div class="col-6">
                            <label for="" class="col-sm-12 col-form-label">EB %</label>
                            <input type="text" class="form-control" id="email" name="email" tabindex="13">
                        </div>
                        <div class="col-8">
                            <label for="" class="col-sm-12 col-form-label">EB amount</label>
                            <input type="text" class="form-control" id="telp_kontak" name="telp_kontak" required tabindex="14">
                        </div>
                        <div class="col-12">
                            <label for="" class="col-sm-12 col-form-label">Disposisi EB#</label>
                            <input type="text" class="form-control" id="telp_kontak" name="telp_kontak" required tabindex="15">
                        </div>
                        <div class="col-6">
                            <label for="" class="col-sm-12 col-form-label">Down payment %</label>
                            <input type="text" class="form-control" id="telp_kontak" name="telp_kontak" required tabindex="16">
                        </div>

                    </div>


                    <div class="col-6 row g-1">
                        <div class="col-6">
                            <label for="" class="col-sm-12 col-form-label">Delivery to</label>
                            <input type="text" id="provinsi" name="provinsi" required class="form-control text-uppercase" tabindex="17">
                        </div>
                        <div class="col-12">
                            <label for="" class="col-sm-12 col-form-label">Name</label>
                            <input type="text" id="kabupaten" name="kabupaten" required class="form-control text-uppercase" tabindex="18">
                        </div>
                        <div class="col-12">
                            <label for="" class="col-sm-12 col-form-label">Address</label>
                            <textarea type="text" class="form-control" id="fax" name="fax" tabindex="19" rows="5"></textarea>
                        </div>
                        <div class="col-12">
                            <label for="" class="col-sm-12 col-form-label">Attn.</label>
                            <input type="text" class="form-control" id="telp" name="telp" required tabindex="20">
                        </div>
                        <div class="clearfix"></div>
                        <div class="col-12">
                            <label for="" class="col-sm-12 col-form-label">Phone</label>
                            <input type="text" class="form-control" id="telp" name="telp" required tabindex="21">
                        </div>
                        <div class="clearfix"></div>
                        <div class="col-6">
                            <label for="" class="col-sm-12 col-form-label">Zone</label>
                            <input type="text" class="form-control" id="nik" name="nik" required tabindex="22">
                        </div>
                        <div class="clearfix"></div>
                        <div class="col-6">
                            <label for="" class="col-sm-12 col-form-label">Route</label>
                            <input type="text" class="form-control" id="kontak" name="kontak" required tabindex="23">
                        </div>
                        <div class="col-6 input-group mb-3">
                            <label for="" class="col-sm-12 col-form-label">Days of delivery</label>
                            <input type="text" id="bracoMcusmas" name="braco" required class="form-control text-uppercase" tabindex="24" aria-describedby="basic-addon2">
                            <span class="input-group-text" id="basic-addon2">days</span>
                        </div>
                        <div class="col-6">
                            <label for="" class="col-sm-12 col-form-label">Entry note (Y/N)</label>
                            <input type="text" id="bracoMcusmas" name="braco" required class="form-control text-uppercase" tabindex="25">
                        </div>

                    </div>


                    <div class="col-4 row g-2">
                        <div class="col-12">
                            <label for="" class="col-sm-12 col-form-label">Split quota %</label>
                            <input type="text" class="form-control" id="telp_kontak" name="telp_kontak" required tabindex="26">
                        </div>
                    </div>
                    <div class="col-4 row g-2">
                        <div class="col-12">
                            <label for="" class="col-sm-12 col-form-label">to Branch</label>
                            <input type="text" class="form-control" id="telp_kontak" name="telp_kontak" required tabindex="27">
                        </div>
                    </div>
                    <div class="col-4 row g-2">
                        <div class="col-12">
                            <label for="" class="col-sm-12 col-form-label">Sales Rep.</label>
                            <input type="text" class="form-control" id="telp_kontak" name="telp_kontak" required tabindex="28">
                        </div>
                    </div>


                </form>


              </div>
            </div>
          </div>
        </div>
    </section>

</main>


<div class="modal fade" id="mTMcusmas" tabindex="-1">
    <div class="modal-dialog modal-xl modal-dialog-scrollable">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Tambah Pelanggan</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">

        </div>
        <div class="modal-footer">
            <button type="submit" class="btn btn-primary">Simpan</button>
        </div>
      </div>
    </div>
</div>

@endsection
