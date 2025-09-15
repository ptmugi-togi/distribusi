@extends('layout.main')
@section('container')

<main id="main" class="main">
    <div class="pagetitle">
        <h1>MBRANCH</h1>
        <nav>
            <ol class="breadcrumb">
            <li class="breadcrumb-item">MASTER</li>
            <li class="breadcrumb-item active">MBRANCH</li>
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
                    <button id="tambahMbranch" type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#mTMbranch"> Tambah</button>
                </div>

                <table id="myTable9" class="table table-striped nowrap" style="width:100%">
                  <thead>
                    <tr>
                      <th>BRACO</th>
                      <th>BRANA</th>
                      <th>CONAM</th>
                      <th>ALAMAT</th>
                      <th>KONTAK</th>
                      <th>TELP.</th>
                      <th>FAX</th>
                      <th>NPWP</th>
                      <th>TGL</th>
                      <th>EMAIL</th>
                      <th></th>
                    </tr>
                  </thead>
                  <tbody>
                    @foreach ($mbranchs as $mbranch)
                    <tr>
                      <td>{{ strtoupper($mbranch->braco) }}</td>
                      <td>{{ strtoupper($mbranch->brana) }}</td>
                      <td>{{ strtoupper($mbranch->conam) }}</td>
                      <td>{{ strtoupper($mbranch->address) }}</td>
                      <td>{{ strtoupper($mbranch->contactp) }}</td>
                      <td>{{ strtoupper($mbranch->phone) }}</td>
                      <td>{{ strtoupper($mbranch->faxno) }}</td>
                      <td>{{ strtoupper($mbranch->npwp) }}</td>
                      <td> @if($mbranch->tglsk!="0000-00-00")
                        {{ date('d-m-Y', strtotime($mbranch->tglsk)) }}
                        @endif </td>
                      <td>{{ strtoupper($mbranch->email) }}</td>
                      <td>
                        <a data-bs-toggle="modal" data-bs-target="#mLMbranch" id="call_mLMbranch" class="badge bg-info" braco="{{ $mbranch->braco }}" brana="{{ $mbranch->brana }}" conam="{{ $mbranch->conam }}" address="{{ $mbranch->address }}" contactp="{{ $mbranch->contactp }}" phone="{{ $mbranch->phone }}" faxno="{{ $mbranch->faxno }}" npwp="{{ $mbranch->npwp }}" tglsk="{{ $mbranch->tglsk }}" email="{{ $mbranch->email }}" >Ubah</a>
                        <?php if(auth()->user()->level=="IT"){ ?>
                        <form method="post" action="/mbranch/{{ $mbranch->braco }}" class="d-inline">
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

<div class="modal fade" id="mTMbranch" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Tambah Mbranch</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <form method="post" action="/mbranch" class="row g-3">
                @csrf
                <div class="col-6 row g-3">
                    <div class="col-12">
                        <label for="braco" class="form-label">BRACO</label>
                        <input type="text" class="form-control text-uppercase" id="braco" name="braco" maxlength="3" required>
                    </div>
                    <div class="col-12">
                        <label for="brana" class="form-label">BRANA</label>
                        <input type="text" class="form-control text-uppercase" id="brana" name="brana" maxlength="200" required>
                    </div>
                    <div class="col-12">
                        <label for="conam" class="form-label">CONAM</label>
                        <input type="text" class="form-control text-uppercase" id="conam" name="conam" maxlength="200" required>
                    </div>
                    <div class="col-12">
                        <label for="address" class="form-label">ALAMAT</label>
                        <textarea class="form-control" name="address" id="address" style="height: 100px;"></textarea>
                    </div>
                    <div class="col-12">
                        <label for="contactp" class="form-label">KONTAK</label>
                        <input type="text" class="form-control text-uppercase" id="contactp" name="contactp" maxlength="200" required>
                    </div>
                </div>


                <div class="col-6 row g-3">
                    <div class="col-12">
                        <label for="phone" class="form-label">TELP.</label>
                        <input type="text" class="form-control text-uppercase" id="phone" name="phone" maxlength="200" required>
                    </div>
                    <div class="col-12">
                        <label for="faxno" class="form-label">FAX</label>
                        <input type="text" class="form-control text-uppercase" id="faxno" name="faxno" maxlength="200" required>
                    </div>
                    <div class="col-12">
                        <label for="npwp" class="form-label">NPWP</label>
                        <input type="text" class="form-control text-uppercase" id="npwp" name="npwp" maxlength="200" required>
                    </div>
                    <div class="col-12">
                        <label for="tglsk" class="form-label">TGL</label>
                        <input type="date" class="form-control text-uppercase" id="tglsk" name="tglsk" required>
                    </div>
                    <div class="col-12">
                        <label for="email" class="form-label">EMAIL</label>
                        <input type="text" class="form-control text-uppercase" id="email" name="email" maxlength="200" required>
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


<div class="modal fade" id="mLMbranch" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Ubah MBRANCH</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <form method="post" id="formUpdateMbranch" action="" class="row g-3">
                @csrf
                @method('put')
                <div class="col-6 row g-3">
                    <div class="col-12">
                        <label for="braco" class="form-label">BRACO</label>
                        <input type="text" class="form-control text-uppercase" id="bracoEdit" name="braco" maxlength="3" readonly>
                    </div>
                    <div class="col-12">
                        <label for="brana" class="form-label">BRANA</label>
                        <input type="text" class="form-control text-uppercase" id="branaEdit" name="brana" maxlength="200" required>
                    </div>
                    <div class="col-12">
                        <label for="conam" class="form-label">CONAM</label>
                        <input type="text" class="form-control text-uppercase" id="conamEdit" name="conam" maxlength="200" required>
                    </div>
                    <div class="col-12">
                        <label for="address" class="form-label">ALAMAT</label>
                        <textarea class="form-control" name="address" id="addressEdit" style="height: 100px;"></textarea>
                    </div>
                    <div class="col-12">
                        <label for="contactp" class="form-label">KONTAK</label>
                        <input type="text" class="form-control text-uppercase" id="contactpEdit" name="contactp" maxlength="200" required>
                    </div>
                </div>


                <div class="col-6 row g-3">
                    <div class="col-12">
                        <label for="phone" class="form-label">TELP.</label>
                        <input type="text" class="form-control text-uppercase" id="phoneEdit" name="phone" maxlength="200" required>
                    </div>
                    <div class="col-12">
                        <label for="faxno" class="form-label">FAX</label>
                        <input type="text" class="form-control text-uppercase" id="faxnoEdit" name="faxno" maxlength="200" required>
                    </div>
                    <div class="col-12">
                        <label for="npwp" class="form-label">NPWP</label>
                        <input type="text" class="form-control text-uppercase" id="npwpEdit" name="npwp" maxlength="200" required>
                    </div>
                    <div class="col-12">
                        <label for="tglsk" class="form-label">TGL</label>
                        <input type="date" class="form-control text-uppercase" id="tglskEdit" name="tglsk" required>
                    </div>
                    <div class="col-12">
                        <label for="email" class="form-label">EMAIL</label>
                        <input type="text" class="form-control text-uppercase" id="emailEdit" name="email" maxlength="200" required>
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
