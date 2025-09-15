@extends('layout.main')
@section('container')

<main id="main" class="main">
    <div class="pagetitle">
        <h1>MBRAND</h1>
        <nav>
            <ol class="breadcrumb">
            <li class="breadcrumb-item">MASTER</li>
            <li class="breadcrumb-item active">MBRAND</li>
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
                    <button id="tambahMbrand" type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#mTMbrand"> Tambah</button>
                </div>

                <table id="myTable4" class="table table-striped nowrap" style="width:100%">
                  <thead>
                    <tr>
                      <th>ID</th>
                      <th>BRAND</th>
                      <th>DESKRIPSI</th>
                      <th>TOPAY</th>
                      <th></th>
                    </tr>
                  </thead>
                  <tbody>
                    @foreach ($mbrands as $mbrand)
                    <tr>
                      <td>{{ $mbrand->id }}</td>
                      <td>{{ strtoupper($mbrand->brand_name) }}</td>
                      <td>{{ strtoupper($mbrand->descr_brand) }}</td>
                      <td>{{ strtoupper($mbrand->topay) }}</td>
                      <td>
                        <a data-bs-toggle="modal" data-bs-target="#mLMbrand-{{ $mbrand->id }}" class="badge bg-info">Ubah</a>
                        <?php if(auth()->user()->level=="IT"){ ?>
                        <form method="post" action="/mbrand/{{ $mbrand->id }}" class="d-inline">
                            @csrf
                            @method('delete')

                            <button type="" class="border-0 badge bg-danger" onclick="return confirm('Apakah anda yakin?')">Hapus</button>
                        </form>
                        <?php } ?>
                      </td>
                    </tr>
                    @endforeach
                  </tbody>
                </table>

              </div>
            </div>
          </div>
        </div>
    </section>

</main>

<div class="modal fade" id="mTMbrand" tabindex="-1">
    <div class="modal-dialog modal-dialog-scrollable">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Tambah MBRAND</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <form method="post" action="/mbrand" class="row g-3">
                @csrf
                <div class="col-12">
                    <label for="brand_name" class="form-label">BRAND</label>
                    <input type="text" class="form-control text-uppercase" id="brand_name" name="brand_name" maxlength="100" required>
                </div>
                <div class="col-12">
                    <label for="descr_brand" class="form-label">Deskripsi</label>
                    <input type="text" class="form-control text-uppercase" id="descr_brand" name="descr_brand" required maxlength="200">
                </div>
                <div class="col-12">
                    <label for="topay" class="form-label">Topay</label>
                    <input type="number" class="form-control text-uppercase" id="topay" name="topay" required maxlength="100">
                </div>
        </div>
        <div class="modal-footer">
            <button type="submit" class="btn btn-primary">Simpan</button>
            </form>
        </div>
      </div>
    </div>
</div>

@foreach($mbrands as $mbrand)
<div class="modal fade" id="mLMbrand-{{ $mbrand->id }}" tabindex="-1">
    <div class="modal-dialog modal-dialog-scrollable">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Ubah MPGRUP</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <form method="post" action="/mbrand/{{ $mbrand->id }}" class="row g-3">
                @csrf
                @method('put')
                <div class="col-12">
                    <label for="brand_name" class="form-label">BRAND</label>
                    <input type="text" class="form-control text-uppercase" id="brand_name" name="brand_name" maxlength="100" readonly value="{{ $mbrand->brand_name }}">
                </div>
                <div class="col-12">
                    <label for="descr_brand" class="form-label">Deskripsi</label>
                    <input type="text" class="form-control text-uppercase" id="descr_brand" name="descr_brand" required maxlength="200" value="{{ $mbrand->descr_brand }}">
                </div>
                <div class="col-12">
                    <label for="topay" class="form-label">Topay</label>
                    <input type="string" class="form-control text-uppercase" id="topay" name="topay" required maxlength="100" value="{{ $mbrand->topay }}">
                </div>
        </div>
        <div class="modal-footer">
            <button type="submit" class="btn btn-primary">Simpan</button>
            </form>
        </div>
      </div>
    </div>
</div>
@endforeach

@endsection
