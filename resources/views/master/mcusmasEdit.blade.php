@extends('layout.main')
@section('container')

<main id="main" class="main">
    <div class="pagetitle">
        <h1>Ubah Pelanggan</h1>
        <nav>
            <ol class="breadcrumb">
            <li class="breadcrumb-item">MASTER</li>
            <li class="breadcrumb-item">MCUSMAS</li>
            <li class="breadcrumb-item active">UBAH MCUSMAS</li>
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

                <form action="/mcusmas/{{ $mcusma->customer_id }}" method="post" class="row">
                    @csrf
                    @method('put')
                    <div class="row col-6">
                        <input type="hidden" value="{{ $mcusma->customer_id }}" id="customer_id_edit_cusmas" readonly>
                        <input type="hidden" value="{{ 'admin' }}" name="user_" readonly>
                        <div class="col-8 mb-3">
                            <label for="" class="col-sm-12 col-form-label">Grup</label>
                            <input type="text" class="form-control text-uppercase" id="grouppEdit" name="groupp" value="{{ $mcusma->groupp }}" readonly>
                        </div>

                        <div class="col-4 mb-3">
                            <label for="" class="col-sm-12 col-form-label">Titel</label>
                            <select id="titleEdit" name="title" required class="form-control text-uppercase">
                                <option value="{{ $mcusma->title }}">{{ $mcusma->title }}</option>
                                <option value=""></option>
                                <option value="PT.">PT.</option>
                                <option value="CV.">CV.</option>
                                <option value="BAPAK">BAPAK</option>
                                <option value="IBU">IBU</option>
                                <option value="TOKO">TOKO</option>
                                <option value="UD.">UD.</option>
                                <option value="TM.">TM.</option>
                                <option value="HOTEL">HOTEL</option>
                                <option value="KOP">UNIT KOPERASI</option>
                            </select>
                        </div>

                        <div class="col-12 mb-3">
                            <label for="" class="col-sm-12 col-form-label">Nama Perusahaan</label>
                            <input type="text" class="form-control text-uppercase" id="nama_perusahaanEdit" name="nama_perusahaan" value="{{ $mcusma->nama_perusahaan }}" required>
                        </div>

                        <div class="col-12 mb-3">
                            <label for="" class="col-sm-12 col-form-label">Lokasi</label>
                            <input type="text" class="form-control text-uppercase" id="lokasiEdit" name="lokasi" value="{{ $mcusma->lokasi }}" readonly>
                        </div>

                        <div class="col-12 mb-3">
                            <label for="" class="col-sm-12 col-form-label">Alamat</label>
                            <textarea class="form-control text-uppercase" name="alamat" id="addressEdit" style="height: 50px;">{{ $mcusma->alamat }}</textarea>
                        </div>

                        <div class="col-12 mb-3">
                            <label for="" class="col-sm-12 col-form-label">Provinsi</label>
                            <select id="provinsiEdit" name="provinsi" required class="form-control text-uppercase {{ $mcusma->provinsi }}">
                                @foreach($provinsi as $prov)
                                    @if(old('provinsi', $mcusma->provinsi) == $prov->id_prov)
                                        <option value="{{ $prov->id_prov }}" selected>{{ $prov->provinsi }}</option>
                                    @else
                                        <option value="{{ $prov->id_prov }}">{{ $prov->provinsi }}</option>
                                    @endif
                                @endforeach
                            </select>
                        </div>

                        <div class="col-12 mb-3">
                            <label for="" class="col-sm-12 col-form-label">Kabupaten/Kota</label>
                            <select id="kabupatenEdit" name="kabupaten" required class="form-control text-uppercase {{ $mcusma->kabupaten }}">
                                @foreach($kabkot as $kk)
                                    @if(old('kabupaten', $mcusma->kabupaten) == $kk->id)
                                        <option value="{{ $kk->id }}" selected>{{ $kk->kabupaten }}</option>
                                    @else
                                        <option value="{{ $kk->id }}">{{ $kk->kabupaten }}</option>
                                    @endif
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="row col-6">

                        <div class="col-6 mb-3">
                            <label for="" class="col-sm-12 col-form-label">Telepon</label>
                            <input type="text" class="form-control" value="{{ $mcusma->telp }}" id="telpEdit" name="telp" required>
                        </div>

                        <div class="col-6 mb-3">
                            <label for="" class="col-sm-12 col-form-label">Fax</label>
                            <input type="text" class="form-control" id="faxEdit" name="fax" value="{{ $mcusma->fax }}" required>
                        </div>

                        <div class="col-6 mb-3">
                            <label for="" class="col-sm-12 col-form-label">NPWP</label>
                            <input type="text" class="form-control" id="npwpEdit" name="npwp" value="{{ $mcusma->npwp }}" required>
                        </div>

                        <div class="col-6 mb-3">
                            <label for="" class="col-sm-12 col-form-label">NIK</label>
                            <input type="text" class="form-control" id="nikEdit" name="nik" value="{{ $mcusma->nik }}" required>
                        </div>

                        <div class="col-6 mb-3">
                            <label for="" class="col-sm-12 col-form-label">Email</label>
                            <input type="text" class="form-control" id="emailEdit" name="email" value="{{ $mcusma->email }}" required>
                        </div>

                        <div class="col-6 mb-3">
                            <label for="" class="col-sm-12 col-form-label">Kontak Person</label>
                            <input type="text" class="form-control" id="kontakEdit" name="kontak" value="{{ $mcusma->kontak }}" required>
                        </div>

                        <div class="col-6 mb-3">
                            <label for="" class="col-sm-12 col-form-label">Telp. Kontak Person</label>
                            <input type="text" class="form-control" id="telp_kontakEdit" name="telp_kontak" value="{{ $mcusma->telp_kontak }}" required>
                        </div>

                        <div class="col-6 mb-3">
                            <label for="" class="col-sm-12 col-form-label">Branch</label>
                            <select id="bracoMcusmasEdit" name="braco" required class="form-control text-uppercase {{ $mcusma->braco }}">
                                @foreach($branch as $brc)
                                    @if(old('braco', $mcusma->braco) == $brc->braco)
                                        <option value="{{ $brc->braco }}" selected>{{ $brc->brana }}</option>
                                    @else
                                        <option value="{{ $brc->braco }}">{{ $brc->brana }}</option>
                                    @endif
                                @endforeach
                            </select>
                        </div>

                        <div class="col-6 mb-3">
                            <label for="" class="col-sm-12 col-form-label">Depo</label>
                            <select id="depoMcusmasEdit" name="depo" class="form-control text-uppercase">
                                @foreach($depo as $dpo)
                                    @if(old('depo', $mcusma->depo) == $dpo->depo)
                                        <option value="{{ $dpo->depo }}" selected>{{ $dpo->name }}</option>
                                    @else
                                        <option value="{{ $dpo->depo }}">{{ $dpo->name }}</option>
                                    @endif
                                @endforeach
                            </select>
                        </div>


                    </div>
                    <div class="text-center">
                        <button type="submit" class="btn btn-primary" style="margin-top: 20px;"><i class="ri-edit-line"></i> Ubah</button>
                        <a type="button" class="btn btn-danger" href="/mcusmas" style="margin-top: 20px;"><i class="ri-arrow-go-back-fill"></i> Kembali</a>
                    </div>
                </form>

                <h4 style="margin-top: 30px;"><strong>Daftar Site</strong></h4>
                <div id="table_site_cusmas" style="overflow-y: scroll;"></div>


                <h4 style="margin-top: 40px;"><strong>Daftar Timbangan</strong></h4>
                <div id="table_timbangan_cusmas" style="overflow-y: scroll;"></div>


              </div>
            </div>
          </div>
        </div>
    </section>

</main>

<div class="modal fade" id="mTSiteCusMas" >
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Tambah SITE</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <form method="post" action="/mcusmascab" class="row g-3">
                @csrf
                <input type="hidden" name="customer_id" id="customer_idCusMas" readonly>
                <div class="col-6 row g-3">
                    <div class="col-12">
                        <label for="depo" class="form-label">SITE</label>
                        <input type="text" class="form-control text-uppercase" id="lokasiCusMas" name="lokasi" maxlength="250" required tabIndex="1">
                    </div>
                    <div class="col-12">
                        <label for="name" class="form-label">ALAMAT</label>
                        <textarea tabIndex="2" class="form-control text-uppercase" name="alamat" id="alamatCusMas" style="height: 100px;"></textarea>
                    </div>
                    <div class="col-12">
                        <label for="braco" class="form-label">TELEPON</label>
                        <input type="text" class="form-control text-uppercase" id="telpCusMas" name="telp" maxlength="50" required tabIndex="3">
                    </div>
                    <div class="col-12">
                        <label for="faxCusMas" class="form-label">FAX</label>
                        <input type="text" class="form-control text-uppercase" name="fax" id="faxCusMas" maxlength="50" required tabIndex="4">
                    </div>

                </div>


                <div class="col-6 row g-3">
                    <div class="col-12">
                        <label for="cont" class="form-label">EMAIL</label>
                        <input type="text" class="form-control text-uppercase" id="emailCusMas" name="email" maxlength="100" required tabIndex="5">
                    </div>
                    <div class="col-12">
                        <label for="email" class="form-label">KONTAK PERSON</label>
                        <input type="text" class="form-control text-uppercase" id="kontakCusMas" name="kontak" maxlength="100" required tabIndex="6">
                    </div>
                    <div class="col-12">
                        <label for="phone" class="form-label">TELP. KONTAK PERSON</label>
                        <input type="text" class="form-control text-uppercase" id="telp_kontakCusMas" name="telp_kontak" maxlength="50" required tabIndex="7">
                    </div>
                    <div class="col-12">
                        <label for="faxno" class="form-label">PROVINSI</label>
                        <select id="provinsiCusMas" name="provinsi" required class="form-control text-uppercase" tabindex="8">

                        </select>
                    </div>
                    <div class="col-12">
                        <label for="npwp" class="form-label">KABUPATEN</label>
                        <select id="kabupatenCusMas" name="kabupaten" required class="form-control text-uppercase" tabindex="9">

                        </select>
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

<div class="modal fade" id="mTMesinCusMas" >
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Tambah MESIN</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <form method="post" action="/mmesin" class="row g-3">
                @csrf
                <input type="hidden" name="customer_id" id="customer_idCusMasmesin" readonly>
                <div class="col-6 row g-3">
                    <div class="col-12">
                        <label for="depo" class="form-label">SITE</label>
                        <input type="text" class="form-control text-uppercase" id="lokasiCusMasmesin" name="lokasi" maxlength="250" required tabIndex="1">
                    </div>
                    <div class="col-6">
                        <label class="form-label">Brand</label>
                        <select id="provinsiCusMas" name="provinsi" required class="form-control text-uppercase" tabindex="8">

                        </select>
                    </div>
                    <div class="col-6">
                        <label class="form-label">Grup Produk</label>
                        <select id="provinsiCusMas" name="provinsi" required class="form-control text-uppercase" tabindex="8">

                        </select>
                    </div>
                    <div class="col-6">
                        <label class="form-label">Produk</label>
                        <select id="provinsiCusMas" name="provinsi" required class="form-control text-uppercase" tabindex="8">

                        </select>
                    </div>
                    <div class="col-6">
                        <label class="form-label">Ukuran Platform</label>
                        <select id="provinsiCusMas" name="provinsi" required class="form-control text-uppercase" tabindex="8">

                        </select>
                    </div>
                    <div class="col-6">
                        <label class="form-label">Mainbeam</label>
                        <select id="provinsiCusMas" name="provinsi" required class="form-control text-uppercase" tabindex="8">

                        </select>
                    </div>
                    <div class="col-6">
                        <label class="form-label">Crossbeam</label>
                        <select id="provinsiCusMas" name="provinsi" required class="form-control text-uppercase" tabindex="8">

                        </select>
                    </div>
                    <div class="col-6">
                        <label class="form-label">Support</label>
                        <select id="provinsiCusMas" name="provinsi" required class="form-control text-uppercase" tabindex="8">

                        </select>
                    </div>
                    <div class="col-6">
                        <label class="form-label">Plate</label>
                        <select id="provinsiCusMas" name="provinsi" required class="form-control text-uppercase" tabindex="8">

                        </select>
                    </div>
                    <div class="col-6">
                        <label class="form-label">Kapasitas x Ketelitian</label>
                        <select id="provinsiCusMas" name="provinsi" required class="form-control text-uppercase" tabindex="8">

                        </select>
                    </div>
                    <div class="col-12">
                        <label for="braco" class="form-label">TELEPON</label>
                        <input type="text" class="form-control text-uppercase" id="telpCusMas" name="telp" maxlength="50" required tabIndex="3">
                    </div>
                    <div class="col-12">
                        <label for="faxCusMas" class="form-label">FAX</label>
                        <input type="text" class="form-control text-uppercase" name="fax" id="faxCusMas" maxlength="50" required tabIndex="4">
                    </div>

                </div>


                <div class="col-6 row g-3">
                    <div class="col-12">
                        <label for="cont" class="form-label">EMAIL</label>
                        <input type="text" class="form-control text-uppercase" id="emailCusMas" name="email" maxlength="100" required tabIndex="5">
                    </div>
                    <div class="col-12">
                        <label for="email" class="form-label">KONTAK PERSON</label>
                        <input type="text" class="form-control text-uppercase" id="kontakCusMas" name="kontak" maxlength="100" required tabIndex="6">
                    </div>
                    <div class="col-12">
                        <label for="phone" class="form-label">TELP. KONTAK PERSON</label>
                        <input type="text" class="form-control text-uppercase" id="telp_kontakCusMas" name="telp_kontak" maxlength="50" required tabIndex="7">
                    </div>
                    <div class="col-12">
                        <label for="faxno" class="form-label">PROVINSI</label>
                        <select id="provinsiCusMas" name="provinsi" required class="form-control text-uppercase" tabindex="8">

                        </select>
                    </div>
                    <div class="col-12">
                        <label for="npwp" class="form-label">KABUPATEN</label>
                        <select id="kabupatenCusMas" name="kabupaten" required class="form-control text-uppercase" tabindex="9">

                        </select>
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

@endsection
