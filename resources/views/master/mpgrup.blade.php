@extends('layout.main')
@section('container')

<main id="main" class="main">
    <div class="pagetitle">
        <h1>MPGRUP</h1>
        <nav>
            <ol class="breadcrumb">
            <li class="breadcrumb-item">MASTER</li>
            <li class="breadcrumb-item active">MPGRUP</li>
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
                    <button id="tambahMpgrup" type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#mTMpgrup"> Tambah</button>
                </div>

                <table id="myTable3" class="table table-striped nowrap" style="width:100%">
                  <thead>
                    <tr>
                      <th>ID</th>
                      <th>DESKRIPSI</th>
                      <th></th>
                    </tr>
                  </thead>
                  <tbody>
                    @foreach ($mpgrups as $mpgrup)
                    <tr>
                      <td>{{ strtoupper($mpgrup->pgrup) }}</td>
                      <td>{{ strtoupper($mpgrup->descr) }}</td>
                      <td>
                        <a data-bs-toggle="modal" data-bs-target="#mLMpgrup-{{ $mpgrup->pgrup }}" class="badge bg-info">Ubah</a>
                        <?php if(auth()->user()->level=="IT"){ ?>
                        <form method="post" action="/mpgrup/{{ $mpgrup->pgrup }}" class="d-inline">
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

<div class="modal fade" id="mTMpgrup" tabindex="-1">
    <div class="modal-dialog modal-dialog-scrollable">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Tambah MPGRUP</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <form method="post" action="/mpgrup" class="row g-3">
                @csrf
                <div class="col-12">
                    <label for="pgrup" class="form-label">ID</label>
                    <input type="text" class="form-control text-uppercase" id="pgrup" name="pgrup" maxlength="1" required>
                </div>
                <div class="col-12">
                    <label for="descr" class="form-label">Deskripsi</label>
                    <input type="text" class="form-control text-uppercase" id="descr" name="descr" required>
                </div>
        </div>
        <div class="modal-footer">
            <button type="submit" class="btn btn-primary">Simpan</button>
            </form>
        </div>
      </div>
    </div>
</div>

@foreach($mpgrups as $mpgrup)
<div class="modal fade" id="mLMpgrup-{{ $mpgrup->pgrup }}" tabindex="-1">
    <div class="modal-dialog modal-dialog-scrollable">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Ubah MPGRUP</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <form method="post" action="/mpgrup/{{ $mpgrup->pgrup }}" class="row g-3">
                @csrf
                @method('put')
                <div class="col-12">
                    <label for="pgrup" class="form-label">ID</label>
                    <input type="text" class="form-control" id="pgrup" name="pgrup" readonly value="{{ $mpgrup->pgrup }}">
                </div>
                <div class="col-12">
                    <label for="descr" class="form-label">Deskripsi</label>
                    <input type="text" class="form-control" id="descr" name="descr" required value="{{ $mpgrup->descr }}">
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
