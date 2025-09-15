@extends('layout.main')
@section('container')

<main id="main" class="main">
    <div class="pagetitle">
        <h1>MITYPE</h1>
        <nav>
            <ol class="breadcrumb">
            <li class="breadcrumb-item">MASTER</li>
            <li class="breadcrumb-item active">MITYPE</li>
            </ol>
        </nav>
    </div>

    <section class="section">
        <div class="row">
          <div class="col-lg-12">
            <div class="card">
              <div class="card-body">
                @if(session()->has('success'))`
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                @endif
                <div class="col-lg-12" style="padding:0px 10px 10px 0px;">
                    <button id="tambahMitype" type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#mTMitype"> Tambah</button>
                </div>

                <table id="myTable4" class="table table-striped nowrap" style="width:100%">
                  <thead>
                    <tr>
                      <th>ID</th>
                      <th>DESKRIPSI</th>
                      <th></th>
                    </tr>
                  </thead>
                  <tbody>
                    @foreach ($mitypes as $mitype)
                    <tr>
                      <td>{{ strtoupper($mitype->itype_id) }}</td>
                      <td>{{ strtoupper($mitype->descr_itype) }}</td>
                      <td>
                        <a data-bs-toggle="modal" data-bs-target="#mLMitype-{{ $mitype->itype_id }}" class="badge bg-info">Ubah</a>
                        <?php if(auth()->user()->level=="IT"){ ?>
                        <form method="post" action="/mitype/{{ $mitype->itype_id }}" class="d-inline">
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

<div class="modal fade" id="mTMitype" tabindex="-1">
    <div class="modal-dialog modal-dialog-scrollable">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Tambah MITYPE</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <form method="post" action="/mitype" class="row g-3">
                @csrf
                <div class="col-12">
                    <label for="itype_id" class="form-label">ITYPE ID</label>
                    <input type="text" class="form-control text-uppercase" id="itype_id" name="itype_id" maxlength="1" required>
                </div>
                <div class="col-12">
                    <label for="descr_itype" class="form-label">Deskripsi</label>
                    <input type="text" class="form-control text-uppercase" id="descr_itype" name="descr_itype" required maxlength="100">
                </div>
        </div>
        <div class="modal-footer">
            <button type="submit" class="btn btn-primary">Simpan</button>
            </form>
        </div>
      </div>
    </div>
</div>

@foreach($mitypes as $mitype)
<div class="modal fade" id="mLMitype-{{ $mitype->itype_id }}" tabindex="-1">
    <div class="modal-dialog modal-dialog-scrollable">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Ubah MITYPE</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <form method="post" action="/mitype/{{ $mitype->itype_id }}" class="row g-3">
                @csrf
                @method('put')
                <div class="col-12">
                    <label for="itype_id" class="form-label">ITYPE ID</label>
                    <input type="text" class="form-control text-uppercase" id="itype_id" name="itype_id" maxlength="1" readonly value="{{ $mitype->itype_id }}">
                </div>
                <div class="col-12">
                    <label for="descr_itype" class="form-label">Deskripsi</label>
                    <input type="text" class="form-control text-uppercase" id="descr_itype" name="descr_itype" required maxlength="100" value="{{ $mitype->descr_itype }}">
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
