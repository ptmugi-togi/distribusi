@extends('layout.main')
@section('container')

<main id="main" class="main">
    <div class="pagetitle">
        <h1>MSGRUP</h1>
        <nav>
            <ol class="breadcrumb">
            <li class="breadcrumb-item">MASTER</li>
            <li class="breadcrumb-item active">MSGRUP</li>
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
                    <button id="tambahMssgrup" type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#mTMsgrup"> Tambah</button>
                </div>

                <table id="myTable2" class="table table-striped nowrap" style="width:100%">
                  <thead>
                    <tr>
                      <th>ID</th>
                      <th>DESKRIPSI</th>
                      <th></th>
                    </tr>
                  </thead>
                  <tbody>
                    @foreach ($msgrups as $msgrup)
                    <tr>
                      <td>{{ strtoupper($msgrup->sgrup_id) }}</td>
                      <td>{{ strtoupper($msgrup->descr_sgrup) }}</td>
                      <td>
                        <a data-bs-toggle="modal" data-bs-target="#mLMsgrup-{{ $msgrup->sgrup_id }}" class="badge bg-info">Ubah</a>
                        <?php if(auth()->user()->level=="IT"){ ?>
                        <form method="post" action="/msgrup/{{ $msgrup->sgrup_id }}" class="d-inline">
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

<div class="modal fade" id="mTMsgrup" tabindex="-1">
    <div class="modal-dialog modal-dialog-scrollable">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Tambah MSGRUP</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <form method="post" action="/msgrup" class="row g-3">
                @csrf
                <div class="col-12">
                    <label for="sgrup_id" class="form-label">SGRUP ID</label>
                    <input type="text" class="form-control text-uppercase" id="sgrup_id" name="sgrup_id" maxlength="3" required>
                </div>
                <div class="col-12">
                    <label for="descr_sgrup" class="form-label">Deskripsi SGRUP</label>
                    <input type="text" class="form-control text-uppercase" id="descr_sgrup" name="descr_sgrup" required>
                </div>
        </div>
        <div class="modal-footer">
            <button type="submit" class="btn btn-primary">Simpan</button>
            </form>
        </div>
      </div>
    </div>
</div>

@foreach($msgrups as $msgrup)
<div class="modal fade" id="mLMsgrup-{{ $msgrup->sgrup_id }}" tabindex="-1">
    <div class="modal-dialog modal-dialog-scrollable">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Ubah MSGRUP</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <form method="post" action="/msgrup/{{ $msgrup->sgrup_id }}" class="row g-3">
                @csrf
                @method('put')
                <div class="col-12">
                    <label for="sgrup_id" class="form-label">SGRUP ID</label>
                    <input type="text" class="form-control" id="sgrup_id" name="sgrup_id" readonly value="{{ $msgrup->sgrup_id }}">
                </div>
                <div class="col-12">
                    <label for="descr_sgrup" class="form-label">Deskripsi SGRUP</label>
                    <input type="text" class="form-control" id="descr_sgrup" name="descr_sgrup" required value="{{ $msgrup->descr_sgrup }}">
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
