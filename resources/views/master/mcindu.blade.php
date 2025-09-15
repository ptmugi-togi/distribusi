@extends('layout.main')
@section('container')

<main id="main" class="main">
    <div class="pagetitle">
        <h1>MCINDU</h1>
        <nav>
            <ol class="breadcrumb">
            <li class="breadcrumb-item">MASTER</li>
            <li class="breadcrumb-item active">MCINDU</li>
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
                    <button id="tambahMcindu" type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#mTMcindu"> Tambah</button>
                </div>

                <table id="myTable7" class="table table-striped nowrap" style="width:100%">
                  <thead>
                    <tr>
                      <th>CINDU</th>
                      <th>DESKRIPSI</th>
                      <th></th>
                    </tr>
                  </thead>
                  <tbody>
                    @foreach ($mcindus as $mcindu)
                    <tr>
                      <td>{{ strtoupper($mcindu->cindu) }}</td>
                      <td>{{ strtoupper($mcindu->descr_cindu) }}</td>
                      <td>
                        <a data-bs-toggle="modal" data-bs-target="#mLMcindu" id="call_mLMcindu" class="badge bg-info" cindu="{{ $mcindu->cindu }}" descr_cindu="{{ $mcindu->descr_cindu }}">Ubah</a>
                        <?php if(auth()->user()->level=="IT"){ ?>
                        <form method="post" action="/mcindu/{{ $mcindu->cindu }}" class="d-inline">
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

<div class="modal fade" id="mTMcindu" tabindex="-1">
    <div class="modal-dialog modal-dialog-scrollable">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Tambah Mcindu</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <form method="post" action="/mcindu" class="row g-3">
                @csrf
                <div class="col-12">
                    <label for="descr_cindu" class="form-label">DESKRIPSI</label>
                    <input type="text" class="form-control text-uppercase" id="descr_cindu" name="descr_cindu" maxlength="100" required>
                </div>
        </div>
        <div class="modal-footer">
            <button type="submit" class="btn btn-primary">Simpan</button>
            </form>
        </div>
      </div>
    </div>
</div>


<div class="modal fade" id="mLMcindu" tabindex="-1">
    <div class="modal-dialog modal-dialog-scrollable">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Ubah MCINDU</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <form method="post" id="formUpdateMcindu" action="" class="row g-3">
                @csrf
                @method('put')
                <div class="col-12">
                    <label for="descr_cinduEdit" class="form-label">DESKRIPSI</label>
                    <input type="text" class="form-control text-uppercase" id="descr_cinduEdit" name="descr_cindu" maxlength="100" required>
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
