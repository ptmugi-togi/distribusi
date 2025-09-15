@extends('layout.main')
@section('container')

<main id="main" class="main">
    <div class="pagetitle">
        <h1>MDEPO</h1>
        <nav>
            <ol class="breadcrumb">
            <li class="breadcrumb-item">MASTER</li>
            <li class="breadcrumb-item active">MDEPO</li>
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
                    <button id="tambahMdepo" type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#mTMdepo"> Tambah</button>
                </div>

                <table id="myTable10" class="table table-striped nowrap" style="width:100%">
                  <thead>
                    <tr>
                      <th>DEPO</th>
                      <th>NAMA</th>
                      <th>BRACO</th>
                      <th>ALAMAT</th>
                      <th>KONTAK</th>
                      <th>EMAIL</th>
                      <th>TELP.</th>
                      <th>FAXNO</th>
                      <th>NPWP</th>
                      {{-- <th>PKP</th> --}}
                      <th>TGL SK</th>
                      <th></th>
                    </tr>
                  </thead>
                  <tbody>
                    @foreach ($mdepos as $mdepo)
                    <tr>
                      <td>{{ strtoupper($mdepo->depo) }}</td>
                      <td>{{ strtoupper($mdepo->name) }}</td>
                      <td>{{ strtoupper($mdepo->braco) }}</td>
                      <td>{{ strtoupper($mdepo->address) }}</td>
                      <td>{{ strtoupper($mdepo->cont) }}</td>
                      <td>{{ strtoupper($mdepo->email) }}</td>
                      <td>{{ strtoupper($mdepo->phone) }}</td>
                      <td>{{ strtoupper($mdepo->faxno) }}</td>
                      <td>{{ strtoupper($mdepo->npwp) }}</td>
                      {{-- <td>{{ strtoupper($mdepo->pkp) }}</td> --}}
                      <td> @if($mdepo->tglsk!="0000-00-00")
                        {{ date('d-m-Y', strtotime($mdepo->tglsk)) }}
                        @endif </td>
                      <td>
                        <a data-bs-toggle="modal" data-bs-target="#mLmdepo" id="call_mLmdepo" class="badge bg-info" depo="{{ $mdepo->depo }}" name="{{ $mdepo->name }}" braco="{{ $mdepo->braco }}" address="{{ $mdepo->address }}" cont="{{ $mdepo->cont }}" phone="{{ $mdepo->phone }}" faxno="{{ $mdepo->faxno }}" npwp="{{ $mdepo->npwp }}" tglsk="{{ $mdepo->tglsk }}" email="{{ $mdepo->email }}" >Ubah</a>
                        <?php if(auth()->user()->level=="IT"){ ?>
                        <form method="post" action="/mdepo/{{ $mdepo->depo }}" class="d-inline">
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

<div class="modal fade" id="mTMdepo" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Tambah DEPO</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <form method="post" action="/mdepo" class="row g-3">
                @csrf
                <div class="col-6 row g-3">
                    <div class="col-12">
                        <label for="depo" class="form-label">DEPO</label>
                        <input type="text" class="form-control text-uppercase" id="depo" name="depo" maxlength="3" required>
                    </div>
                    <div class="col-12">
                        <label for="name" class="form-label">NAMA</label>
                        <input type="text" class="form-control text-uppercase" id="name" name="name" maxlength="200" required>
                    </div>
                    <div class="col-12">
                        <label for="braco" class="form-label">BRACO</label>
                        <select id="braco" name="braco" required class="form-control text-uppercase">
                            <option value=""></option>
                            @foreach($mbranch as $mbranch)
                            <option value="{{ $mbranch->braco }}">{{ $mbranch->brana }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-12">
                        <label for="address" class="form-label">ALAMAT</label>
                        <textarea class="form-control" name="address" id="address" style="height: 100px;"></textarea>
                    </div>
                    <div class="col-12">
                        <label for="cont" class="form-label">KONTAK</label>
                        <input type="text" class="form-control text-uppercase" id="cont" name="cont" maxlength="200" required>
                    </div>
                </div>


                <div class="col-6 row g-3">
                    <div class="col-12">
                        <label for="email" class="form-label">EMAIL</label>
                        <input type="text" class="form-control text-uppercase" id="email" name="email" maxlength="200" required>
                    </div>
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
                </div>
        </div>
        <div class="modal-footer">
            <button type="submit" class="btn btn-primary">Simpan</button>
            </form>
        </div>
      </div>
    </div>
</div>


<div class="modal fade" id="mLmdepo" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Ubah DEPO</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <form method="post" id="formUpdatemdepo" action="" class="row g-3">
                @csrf
                @method('put')
                <div class="col-6 row g-3">
                    <div class="col-12">
                        <label for="depo" class="form-label">DEPO</label>
                        <input type="text" class="form-control text-uppercase" id="depoEdit" name="depo" maxlength="3" readonly>
                    </div>
                    <div class="col-12">
                        <label for="name" class="form-label">NAMA</label>
                        <input type="text" class="form-control text-uppercase" id="nameEdit" name="name" maxlength="200" required>
                    </div>
                    <div class="col-12">
                        <label for="braco" class="form-label">BRACO</label>
                        <select id="bracoEdit" name="braco" required class="form-control text-uppercase">

                        </select>
                    </div>
                    <div class="col-12">
                        <label for="address" class="form-label">ALAMAT</label>
                        <textarea class="form-control" name="address" id="addressEdit" style="height: 100px;"></textarea>
                    </div>
                    <div class="col-12">
                        <label for="cont" class="form-label">KONTAK</label>
                        <input type="text" class="form-control text-uppercase" id="contEdit" name="cont" maxlength="200" required>
                    </div>
                </div>


                <div class="col-6 row g-3">
                    <div class="col-12">
                        <label for="email" class="form-label">EMAIL</label>
                        <input type="text" class="form-control text-uppercase" id="emailEdit" name="email" maxlength="200" required>
                    </div>
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
