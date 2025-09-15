@extends('layout.main')
@section('container')

<main id="main" class="main">
    <div class="pagetitle">
        <h1>MSTMAS</h1>
        <nav>
            <ol class="breadcrumb">
            <li class="breadcrumb-item">MASTER</li>
            <li class="breadcrumb-item active">MSTMAS</li>
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
                    <button id="tambahMstmas" type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#mTMstmas"> Tambah</button>
                </div>

                <table id="example28" class="table table-striped nowrap" style="width:100%">
                  <thead>
                    <tr>
                      <th>BRACO</th>
                      <th>CUSNO</th>
                      <th>SHPTO</th>
                      <th>SHPNM</th>
                      <th>ALAMAT</th>
                      <th>PHONE</th>
                      <th>FAX</th>
                      <th>CONTP</th>
                      <th>PROVINSI</th>
                      <th>KABUPATEN</th>
                      <th></th>
                    </tr>
                  </thead>
                  {{-- <tbody>
                    @foreach ($mstmases as $mstmas)
                    <tr>
                      <td>{{ strtoupper($mstmas->braco) }}</td>
                      <td>{{ strtoupper($mstmas->cusno) }}</td>
                      <td>{{ strtoupper($mstmas->shpto) }}</td>
                      <td>{{ strtoupper($mstmas->shpnm) }}</td>
                      <td>{{ strtoupper($mstmas->deliveryaddress) }}</td>
                      <td>{{ strtoupper($mstmas->phone) }}</td>
                      <td>{{ strtoupper($mstmas->fax) }}</td>
                      <td>{{ strtoupper($mstmas->contp) }}</td>
                      <td>{{ strtoupper($mstmas->province) }}</td>
                      <td>{{ strtoupper($mstmas->kabupaten) }}</td>
                      <td>
                        <a data-bs-toggle="modal" data-bs-target="#mLMstmas" id="call_mLMstmas" class="badge bg-info"  i_d="{{ $mstmas->id }}" braco="{{ $mstmas->braco }}" cusno="{{ $mstmas->cusno }}" shpto="{{ $mstmas->shpto }}" shpnm="{{ $mstmas->shpnm }}" deliveryaddress="{{ $mstmas->deliveryaddress }}" phone="{{ $mstmas->phone }}" fax="{{ $mstmas->fax }}" contp="{{ $mstmas->contp }}" province="{{ $mstmas->province }}" kabupaten="{{ $mstmas->kabupaten }}">Ubah</a>
                        <form method="post" action="/mstmas/{{ $mstmas->id }}" class="d-inline">
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

<div class="modal fade" id="mTMstmas" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Tambah Mstmas</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <form method="post" action="/mstmas" class="row g-3">
                @csrf
                <div class="col-6 row g-3">
                    <div class="col-12">
                        <label for="braco" class="form-label">BRACO</label>
                        <input type="text" class="form-control text-uppercase" id="braco" name="braco" maxlength="3" required>
                    </div>
                    <div class="col-12">
                        <label for="cusno" class="form-label">CUSNO</label>
                        <input type="text" class="form-control text-uppercase" id="cusno" name="cusno" maxlength="200" required>
                    </div>
                    <div class="col-12">
                        <label for="shpto" class="form-label">SHPTO</label>
                        <input type="text" class="form-control text-uppercase" id="shpto" name="shpto" maxlength="200" required>
                    </div>
                    <div class="col-12">
                        <label for="shpnm" class="form-label">SHPNM</label>
                        <input type="text" class="form-control text-uppercase" id="shpnm" name="shpnm" maxlength="200" required>
                    </div>
                    <div class="col-12">
                        <label for="deliveryaddress" class="form-label">ALAMAT</label>
                        <textarea class="form-control" name="deliveryaddress" id="deliveryaddress" style="height: 100px;"></textarea>
                    </div>
                </div>


                <div class="col-6 row g-3">
                    <div class="col-12">
                        <label for="phone" class="form-label">TELP.</label>
                        <input type="text" class="form-control text-uppercase" id="phone" name="phone" maxlength="200" required>
                    </div>
                    <div class="col-12">
                        <label for="fax" class="form-label">FAX</label>
                        <input type="text" class="form-control text-uppercase" id="fax" name="fax" maxlength="200" required>
                    </div>
                    <div class="col-12">
                        <label for="contp" class="form-label">KONTAK PERSON</label>
                        <input type="text" class="form-control text-uppercase" id="contp" name="contp" maxlength="200" required>
                    </div>
                    <div class="col-12">
                        <label for="province" class="form-label">PROVINSI</label>
                        <select id="province" name="province" required class="form-control text-uppercase">
                            <option value=""></option>
                            @foreach($province as $province)
                            <option value="{{ $province->id_prov }}">{{ $province->provinsi }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-12">
                        <label for="kabupaten" class="form-label">KABUPATEN</label>
                        <select id="kabupaten" name="kabupaten" required class="form-control text-uppercase">

                        </select>
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


<div class="modal fade" id="mLMstmas" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Ubah MSTMAS</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <form method="post" id="formUpdateMstmas" action="" class="row g-3">
                @csrf
                @method('put')
                <div class="col-6 row g-3">
                    <div class="col-12">
                        <label for="braco" class="form-label">BRACO</label>
                        <input type="text" class="form-control text-uppercase" id="bracoEdit" name="braco" maxlength="3" required>
                    </div>
                    <div class="col-12">
                        <label for="cusno" class="form-label">CUSNO</label>
                        <input type="text" class="form-control text-uppercase" id="cusnoEdit" name="cusno" maxlength="200" required>
                    </div>
                    <div class="col-12">
                        <label for="shpto" class="form-label">SHPTO</label>
                        <input type="text" class="form-control text-uppercase" id="shptoEdit" name="shpto" maxlength="200" required>
                    </div>
                    <div class="col-12">
                        <label for="shpnm" class="form-label">SHPNM</label>
                        <input type="text" class="form-control text-uppercase" id="shpnmEdit" name="shpnm" maxlength="200" required>
                    </div>
                    <div class="col-12">
                        <label for="deliveryaddress" class="form-label">ALAMAT</label>
                        <textarea class="form-control" name="deliveryaddress" id="deliveryaddressEdit" style="height: 100px;"></textarea>
                    </div>
                </div>


                <div class="col-6 row g-3">
                    <div class="col-12">
                        <label for="phone" class="form-label">TELP.</label>
                        <input type="text" class="form-control text-uppercase" id="phoneEdit" name="phone" maxlength="200" required>
                    </div>
                    <div class="col-12">
                        <label for="fax" class="form-label">FAX</label>
                        <input type="text" class="form-control text-uppercase" id="faxEdit" name="fax" maxlength="200" required>
                    </div>
                    <div class="col-12">
                        <label for="contp" class="form-label">KONTAK PERSON</label>
                        <input type="text" class="form-control text-uppercase" id="contpEdit" name="contp" maxlength="200" required>
                    </div>
                    <div class="col-12">
                        <label for="province" class="form-label">PROVINSI</label>
                        <select id="provinceEdit" name="province" required class="form-control text-uppercase">

                        </select>
                    </div>
                    <div class="col-12">
                        <label for="kabupaten" class="form-label">KABUPATEN</label>
                        <select id="kabupatenEdit" name="kabupaten" required class="form-control text-uppercase">

                        </select>
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
