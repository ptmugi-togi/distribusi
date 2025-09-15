@extends('layout.main')
@section('container')

<main id="main" class="main">
    <div class="pagetitle">
        <h1>PELANGGAN</h1>
        <nav>
            <ol class="breadcrumb">
            <li class="breadcrumb-item">MASTER</li>
            <li class="breadcrumb-item active">MCUSMAS</li>
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
                    {{-- <a id="tambahMcusmas" href="mcusmas/create" type="button" class="btn btn-success" > Tambah</a> --}}
                    <button id="tambahMcusmas" type="button" class="btn btn-success"data-bs-toggle="modal" data-bs-target="#mTMcusmas"> Tambah</button>
                </div>

                <div style="overflow-y: scroll;">
                    <table id="myTable26" class="table table-striped nowrap" style="width:100%;">
                        <thead>
                          <tr>
                            <th>GRUP</th>
                            <th>TITEL</th>
                            <th>NAMA</th>
                            <th>LOKASI</th>
                            <th>ALAMAT</th>
                            <th>KODEPOS</th>
                            <th>TELP.</th>
                            <th>FAX</th>
                            <th>EMAIL</th>
                            <th>KONTAK</th>
                            <th>NPWP</th>
                            <th>NIK</th>
                            <th>PROVINSI</th>
                            <th>CINDU</th>
                            <th>BRACO</th>
                            <th>DEPO</th>
                            <th>TELP. KONTAK</th>
                            <th>KABUPATEN</th>

                            <th></th>
                          </tr>
                        </thead>
                        {{-- <tbody>
                          @foreach ($mcusmas as $mcusma)
                          <tr>
                            <td>{{ strtoupper($mcusma->groupp) }}</td>
                            <td>{{ strtoupper($mcusma->title) }}</td>
                            <td>{{ strtoupper($mcusma->nama_perusahaan) }}</td>
                            <td>{{ strtoupper($mcusma->lokasi) }}</td>
                            <td>{{ strtoupper($mcusma->alamat) }}</td>
                            <td>{{ strtoupper($mcusma->pos) }}</td>
                            <td>{{ strtoupper($mcusma->telp) }}</td>
                            <td>{{ strtoupper($mcusma->fax) }}</td>
                            <td>{{ strtoupper($mcusma->email) }}</td>
                            <td>{{ strtoupper($mcusma->kontak) }}</td>
                            <td>{{ strtoupper($mcusma->npwp) }}</td>
                            <td>{{ strtoupper($mcusma->nik) }}</td>
                            <td>{{ strtoupper($mcusma->provinsi) }}</td>
                            <td>{{ strtoupper($mcusma->cindu) }}</td>
                            <td>{{ strtoupper($mcusma->braco) }}</td>
                            <td>{{ strtoupper($mcusma->depo) }}</td>
                            <td>{{ strtoupper($mcusma->telp_kontak) }}</td>
                            <td>{{ strtoupper($mcusma->kabupaten) }}</td>

                            <td>
                              <a id="call_mLmcusma" href="/mcusmas/{{ $mcusma->customer_id }}/edit" class="badge bg-info" >Ubah</a>
                              <form method="post" action="/mcusmas/{{ $mcusma->customer_id }}" class="d-inline">
                                  @csrf
                                  @method("delete")
                                  <button type="" class="border-0 badge bg-danger" onclick="return confirm('Apakah anda yakin?')">Hapus</button>
                              </form>
                            </td>
                          </tr>
                          @endforeach
                        </tbody> --}}
                    </table>
                </div>


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
            <form method="post" action="/mcusmas" class="row g-3">
                @csrf
                <div class="col-6 row g-3">
                    <div class="col-9">
                        <label for="" class="col-sm-12 col-form-label">Grup</label>
                            <input list="groupp_" id="groupp" name="groupp" required class="form-control text-uppercase" tabindex="1">
                    </div>
                    <div class="col-3">
                        <label for="" class="col-sm-12 col-form-label">Titel</label>
                        <select id="title" name="title" required class="form-control text-uppercase" tabindex="2">
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
                    <div class="col-9">
                        <label for="" class="col-sm-12 col-form-label">Nama Perusahaan</label>
                        <input list="customer_" id="nama_perusahaan" name="nama_perusahaan" required class="form-control text-uppercase" tabindex="3">
                    </div>
                    <div class="col-9">
                        <label for="" class="col-sm-12 col-form-label">Lokasi</label>
                        <input type="text" class="form-control" id="lokasi" name="lokasi" required tabindex="4">
                    </div>
                    <div class="col-12">
                        <label for="" class="col-sm-12 col-form-label">Alamat</label>
                        <textarea class="form-control" name="alamat" id="alamat" style="height: 50px;" required tabindex="5"></textarea>
                    </div>
                    <div class="col-9">
                        <label for="" class="col-sm-12 col-form-label">Provinsi</label>
                        <select id="provinsi" name="provinsi" required class="form-control text-uppercase" tabindex="6">

                        </select>
                    </div>
                    <div class="col-9">
                        <label for="" class="col-sm-12 col-form-label">Kabupaten/Kota</label>
                        <select id="kabupaten" name="kabupaten" required class="form-control text-uppercase" tabindex="7">

                        </select>
                    </div>
                </div>


                <div class="col-6 row g-3">
                    <div class="col-6">
                        <label for="" class="col-sm-12 col-form-label">Telepon</label>
                        <input type="text" class="form-control" id="telp" name="telp" required tabindex="8">
                    </div>
                    <div class="col-6">
                        <label for="" class="col-sm-12 col-form-label">Fax</label>
                        <input type="text" class="form-control" id="fax" name="fax" tabindex="9">
                    </div>
                    <div class="col-6">
                        <label for="" class="col-sm-12 col-form-label">NPWP</label>
                        <input type="text" class="form-control" id="npwp" name="npwp" required tabindex="10">
                    </div>
                    <div class="col-6">
                        <label for="" class="col-sm-12 col-form-label">NIK</label>
                        <input type="text" class="form-control" id="nik" name="nik" required tabindex="11">
                    </div>
                    <div class="col-6">
                        <label for="" class="col-sm-12 col-form-label">Email</label>
                        <input type="text" class="form-control" id="email" name="email" tabindex="12">
                    </div>
                    <div class="col-6">
                        <label for="" class="col-sm-12 col-form-label">Kontak Person</label>
                        <input type="text" class="form-control" id="kontak" name="kontak" required tabindex="13">
                    </div>
                    <div class="col-6">
                        <label for="" class="col-sm-12 col-form-label">Telp. Kontak Person</label>
                        <input type="text" class="form-control" id="telp_kontak" name="telp_kontak" required tabindex="14">
                    </div>
                    <div class="col-6">
                        <label for="" class="col-sm-12 col-form-label">Branch</label>
                        <select id="bracoMcusmas" name="braco" required class="form-control text-uppercase" tabindex="15">

                        </select>
                    </div>
                    <div class="col-6">
                        <label for="" class="col-sm-12 col-form-label">Depo</label>
                        <select id="depoMcusmas" name="depo" class="form-control text-uppercase" tabindex="16">

                        </select>
                    </div>
                    <input type="hidden" value="admin" name="user_">
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
