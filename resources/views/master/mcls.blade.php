@extends('layout.main')
@section('container')

<main id="main" class="main">
    <div class="pagetitle">
        <h1>MCLS</h1>
        <nav>
            <ol class="breadcrumb">
            <li class="breadcrumb-item">MASTER</li>
            <li class="breadcrumb-item active">MCLS</li>
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
                    <button id="tambahMcls" type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#mTMcls"> Tambah</button>
                </div>

                <table id="myTable4" class="table table-striped nowrap" style="width:100%">
                  <thead>
                    <tr>
                      <th>CLS ID</th>
                      <th>CLASS</th>
                      <th>DESKRIPSI</th>
                      <th></th>
                    </tr>
                  </thead>
                  <tbody>
                    @foreach ($mclses as $cls)
                    <tr>
                      <td>{{ strtoupper($cls->id_cls) }}</td>
                      <td>{{ strtoupper($cls->class) }}</td>
                      <td>{{ strtoupper($cls->descr_cls) }}</td>
                      <td>
                        <a data-bs-toggle="modal" data-bs-target="#mLMcls-{{ $cls->id_cls }}" class="badge bg-info">Ubah</a>
                        <?php if(auth()->user()->level=="IT"){ ?>
                        <form method="post" action="/mcls/{{ $cls->id_cls }}" class="d-inline">
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

<div class="modal fade" id="mTMcls" tabindex="-1">
    <div class="modal-dialog modal-dialog-scrollable">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Tambah MCLS</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <form method="post" action="/mcls" class="row g-3">
                @csrf
                <div class="col-12">
                    <label for="id_cls" class="form-label">CLS ID</label>
                    <input type="text" class="form-control text-uppercase" id="id_cls" name="id_cls" maxlength="1" required>
                </div>
                <div class="col-12">
                    <label for="class" class="form-label">CLASS</label>
                    <input type="text" class="form-control text-uppercase" id="class" name="class" required maxlength="3">
                </div>
                <div class="col-12">
                    <label for="descr_cls" class="form-label">Deskripsi CLASS</label>
                    <input type="text" class="form-control text-uppercase" id="descr_cls" name="descr_cls" required maxlength="100">
                </div>
        </div>
        <div class="modal-footer">
            <button type="submit" class="btn btn-primary">Simpan</button>
            </form>
        </div>
      </div>
    </div>
</div>

@foreach($mclses as $cls)
<div class="modal fade" id="mLMcls-{{ $cls->id_cls }}" tabindex="-1">
    <div class="modal-dialog modal-dialog-scrollable">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Ubah MCLS</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <form method="post" action="/mcls/{{ $cls->id_cls }}" class="row g-3">
                @csrf
                @method('put')
                <div class="col-12">
                    <label for="id_cls" class="form-label">CLS ID</label>
                    <input type="text" class="form-control text-uppercase" id="id_cls" name="id_cls" maxlength="1" readonly value="{{ $cls->id_cls }}">
                </div>
                <div class="col-12">
                    <label for="class" class="form-label">CLASS</label>
                    <input type="text" class="form-control text-uppercase" id="class" name="class" readonly maxlength="3" value="{{ $cls->class }}">
                </div>
                <div class="col-12">
                    <label for="descr_cls" class="form-label">Deskripsi CLASS</label>
                    <input type="text" class="form-control text-uppercase" id="descr_cls" name="descr_cls" autofocus required maxlength="100" value="{{ $cls->descr_cls }}">
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
