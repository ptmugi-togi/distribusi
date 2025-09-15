@extends('layout.main')
@section('container')

<main id="main" class="main">
    <div class="pagetitle">
        <h1>MSRENO</h1>
        <nav>
            <ol class="breadcrumb">
            <li class="breadcrumb-item">MASTER</li>
            <li class="breadcrumb-item active">MSRENO</li>
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
                    <button id="tambahMsreno" type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#mTMsreno"> Tambah</button>
                </div>

                <table id="example27" class="table table-striped nowrap" style="width:100%">
                  <thead>
                    <tr>
                      <th>BRACO</th>
                      <th>SRENO</th>
                      <th>SRENA</th>
                      <th>STEAM</th>
                      <th>ALAMAT</th>
                      <th>PHONE</th>
                      <th>GRADE</th>
                      <th>AKTIF</th>
                      <th></th>
                    </tr>
                  </thead>
                  {{-- <tbody>
                    @foreach ($msrenos as $msreno)
                    <tr>
                      <td>{{ strtoupper($msreno->braco) }}</td>
                      <td>{{ strtoupper($msreno->sreno) }}</td>
                      <td>{{ strtoupper($msreno->srena) }}</td>
                      <td>{{ strtoupper($msreno->steam) }}</td>
                      <td>{{ strtoupper($msreno->address) }}</td>
                      <td>{{ strtoupper($msreno->phone) }}</td>
                      <td>{{ strtoupper($msreno->grade) }}</td>
                      <td>{{ strtoupper($msreno->aktif) }}</td>
                      <td>
                        <a data-bs-toggle="modal" data-bs-target="#mLMsreno" id="call_mLMsreno" idSR="{{ $msreno->id }}" class="badge bg-info" braco="{{ $msreno->braco }}" sreno="{{ $msreno->sreno }}" srena="{{ $msreno->srena }}" steam="{{ $msreno->steam }}" address="{{ $msreno->address }}" phone="{{ $msreno->phone }}" grade="{{ $msreno->grade }}" aktif="{{ $msreno->aktif }}">Ubah</a>
                        <form method="post" action="/msreno/{{ $msreno->id }}" class="d-inline">
                            @csrf
                            @method('delete')

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
    </section>

</main>

<div class="modal fade" id="mTMsreno" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Tambah Msreno</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <form method="post" action="/msreno" class="row g-3">
                @csrf
                <div class="col-6 row g-3">
                    <div class="col-12">
                        <label for="braco" class="form-label">BRACO</label>
                        <input type="text" class="form-control text-uppercase" id="braco" name="braco" maxlength="3" required>
                    </div>
                    <div class="col-12">
                        <label for="sreno" class="form-label">SRENO</label>
                        <input type="text" class="form-control text-uppercase" id="sreno" name="sreno" maxlength="6" required>
                    </div>
                    <div class="col-12">
                        <label for="srena" class="form-label">SRENA</label>
                        <input type="text" class="form-control text-uppercase" id="srena" name="srena" maxlength="255" required>
                    </div>
                    <div class="col-12">
                        <label for="steam" class="form-label">STEAM</label>
                        <input type="text" class="form-control text-uppercase" id="steam" name="steam" maxlength="255" required>
                    </div>
                </div>


                <div class="col-6 row g-3">
                    <div class="col-12">
                        <label for="address" class="form-label">ALAMAT</label>
                        <textarea class="form-control" id="address" name="address" style="height: 100px;"></textarea>
                    </div>
                    <div class="col-12">
                        <label for="phone" class="form-label">TELP.</label>
                        <input type="text" class="form-control text-uppercase" id="phone" name="phone" maxlength="200" required>
                    </div>
                    <div class="col-12">
                        <label for="grade" class="form-label">GRADE</label>
                        <input type="text" class="form-control text-uppercase" id="grade" name="grade" maxlength="2" required>
                    </div>
                    <div class="col-12">
                        <label for="aktif" class="form-label">AKTIF</label>
                        <input type="text" class="form-control text-uppercase" id="aktif" name="aktif" maxlength="1" required>
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


<div class="modal fade" id="mLMsreno" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Ubah MPROMAS</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <form method="post" id="formUpdateMsreno" action="" class="row g-3">
                @csrf
                @method('put')
                <div class="col-6 row g-3">
                    <div class="col-12">
                        <label for="braco" class="form-label">BRACO</label>
                        <input type="text" class="form-control text-uppercase" id="bracoEdit" name="braco" maxlength="3" required>
                    </div>
                    <div class="col-12">
                        <label for="sreno" class="form-label">SRENO</label>
                        <input type="text" class="form-control text-uppercase" id="srenoEdit" name="sreno" maxlength="6" required>
                    </div>
                    <div class="col-12">
                        <label for="srena" class="form-label">SRENA</label>
                        <input type="text" class="form-control text-uppercase" id="srenaEdit" name="srena" maxlength="255" required>
                    </div>
                    <div class="col-12">
                        <label for="steam" class="form-label">STEAM</label>
                        <input type="text" class="form-control text-uppercase" id="steamEdit" name="steam" maxlength="255" required>
                    </div>
                </div>


                <div class="col-6 row g-3">
                    <div class="col-12">
                        <label for="address" class="form-label">ALAMAT</label>
                        <textarea class="form-control" id="addressEdit" name="address" style="height: 100px;"></textarea>
                    </div>
                    <div class="col-12">
                        <label for="phone" class="form-label">TELP.</label>
                        <input type="text" class="form-control text-uppercase" id="phoneEdit" name="phone" maxlength="200" required>
                    </div>
                    <div class="col-12">
                        <label for="grade" class="form-label">GRADE</label>
                        <input type="text" class="form-control text-uppercase" id="gradeEdit" name="grade" maxlength="2" required>
                    </div>
                    <div class="col-12">
                        <label for="aktif" class="form-label">AKTIF</label>
                        <input type="text" class="form-control text-uppercase" id="aktifEdit" name="aktif" maxlength="1" required>
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
