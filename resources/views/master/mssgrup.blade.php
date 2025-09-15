@extends('layout.main')
@section('container')

<main id="main" class="main">
    <div class="pagetitle">
        <h1>MSSGRUP</h1>
        <nav>
            <ol class="breadcrumb">
            <li class="breadcrumb-item">MASTER</li>
            <li class="breadcrumb-item active">MSSGRUP</li>
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
                    <button id="tambahMssgrup" type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#mTMssgrup"> Tambah</button>
                </div>

                <table id="myTable" class="table table-striped nowrap" style="width:100%">
                  <thead>
                    <tr>
                      <th>ID</th>
                      <th>DESKRIPSI</th>
                      <th></th>
                    </tr>
                  </thead>
                  <tbody>
                    @foreach ($mssgrups as $mssgrup)
                    <tr>
                      <td>{{ strtoupper($mssgrup->ssgrup_id) }}</td>
                      <td>{{ strtoupper($mssgrup->descr_ssgrup) }}</td>
                      <td>
                        <a data-bs-toggle="modal" data-bs-target="#mLMssgrup-{{ $mssgrup->ssgrup_id }}" class="badge bg-info">Ubah</a>
                        <?php if(auth()->user()->level=="IT"){ ?>
                        <form method="post" action="/ssgrup/{{ $mssgrup->ssgrup_id }}" class="d-inline">
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

<div class="modal fade" id="mTMssgrup" tabindex="-1">
    <div class="modal-dialog modal-dialog-scrollable">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Tambah MSSGRUP</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <form method="post" action="/ssgrup">
                @csrf
                <div class="col-12">
                    <label for="descr_ssgrup" class="form-label">Deskripsi</label>
                    <input type="text" class="form-control" id="descr_ssgrup" name="descr_ssgrup" required>
                </div>
        </div>
        <div class="modal-footer">
            <button type="submit" class="btn btn-primary">Simpan</button>
            </form>
        </div>
      </div>
    </div>
</div>

@foreach($mssgrups as $mssgrup)
<div class="modal fade" id="mLMssgrup-{{ $mssgrup->ssgrup_id }}" tabindex="-1">
    <div class="modal-dialog modal-dialog-scrollable">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Ubah MSSGRUP</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <form method="post" action="/ssgrup/{{ $mssgrup->ssgrup_id }}">
                @csrf
                @method('put')
                <div class="col-12">
                    <label for="descr_ssgrup" class="form-label">Deskripsi</label>
                    <input type="text" class="form-control" id="descr_ssgrup" name="descr_ssgrup" required value="{{ $mssgrup->descr_ssgrup }}">
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
