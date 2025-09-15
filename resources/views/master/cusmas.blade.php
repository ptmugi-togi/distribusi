@extends('layout.main')
@section('container')

<main id="main" class="main">
    <div class="pagetitle">
        <h1>PELANGGAN</h1>
        <nav>
            <ol class="breadcrumb">
            <li class="breadcrumb-item">MASTER</li>
            <li class="breadcrumb-item active">CUSMAS</li>
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
                    {{-- <a id="tambahMcusmas" href="mcusmas/create" type="button" class="btn btn-success" > Tambah</a> --}}
                    <button id="tambahMcusmas" type="button" class="btn btn-success"data-bs-toggle="modal" data-bs-target="#mTCusmas"> Tambah</button>
                </div>

                <div style="overflow-y: scroll;">
                    <table id="example29" class="table table-striped nowrap" style="width:100%;">
                        <thead>
                          <tr>
                            <th>CUSNO</th>
                            <th>BRACO</th>
                            <th>CUSNA</th>
                            <th>BILLN</th>
                            <th>TITLE</th>
                            <th>PRPOS</th>
                            <th>PKP</th>
                            <th>NPWP</th>
                            <th>ADDRESS</th>
                            <th>CITY</th>
                            <th>KODEPOS</th>
                            <th>PHONE</th>
                            <th>FAX</th>
                            <th>CONTACT</th>
                            <th>EMAIL</th>
                            <th>TOPAY</th>
                            <th>CINDU</th>
                            <th>CZONE</th>
                            <th>CAREA</th>
                            <th>DOPEN</th>
                            <th>CRLIM</th>
                            <th>LAUID</th>
                            <th>LADUP</th>
                            <th>STATUS</th>
                            <th>BARVAL</th>
                            <th>OPENO</th>
                            <th>OARVAL</th>
                            <th>CSECT</th>

                            <th></th>
                          </tr>
                        </thead>

                    </table>
                </div>


              </div>
            </div>
          </div>
        </div>
    </section>

</main>


<div class="modal fade" id="mTCusmas" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Tambah Pelanggan</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <form method="post" action="/cusmas" class="row g-3">
                @csrf
                <div class="col-6 row g-3">
                    <div class="col-12">
                        <label class="form-label">CUSNO</label>
                        <input type="text" class="form-control text-uppercase" id="cusnoCusmas" name="cusno" required>
                    </div>
                    <div class="col-12">
                        <label class="form-label">BRACO</label>
                        <select class="form-control text-uppercase" id="bracoCusmas" name="braco" maxlength="3" required>
                            @foreach ($bracos as $braco)
                                <option value="{{ $braco->braco }}">{{ $braco->brana }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-12">
                        <label class="form-label">CUSNA</label>
                        <input type="text" class="form-control text-uppercase" id="cusnaCusmas" name="cusna" maxlength="200" required>
                    </div>
                    <div class="col-12">
                        <label class="form-label">BILLN</label>
                        <input type="text" class="form-control text-uppercase" id="billnCusmas" name="billn" maxlength="100" required>
                    </div>
                    <div class="col-12">
                        <label class="form-label">TITLE</label>
                        <select class="form-control text-uppercase" name="title" id="titleCusmas" maxlength="5" required>
                            <option value="PT.">PT.</option>
                            <option value="CV.">CV.</option>
                            <option value="BPK">BAPAK</option>
                            <option value="IBU">IBU</option>
                            <option value="TOKO">TOKO</option>
                            <option value="UD.">UD.</option>
                            <option value="TM.">TM.</option>
                            <option value="HOTEL">HOTEL</option>
                            <option value="KOP">UNIT KOPERASI</option>
                        </select>
                    </div>
                    <div class="col-12">
                        <label class="form-label">PRPOS</label>
                        <input type="text" class="form-control text-uppercase" name="prpos" id="prposCusmas" maxlength="1" required>
                    </div>
                    <div class="col-12">
                        <label class="form-label">PKP</label>
                        <input type="text" class="form-control text-uppercase" name="pkp" id="pkpCusmas" maxlength="1" required>
                    </div>
                    <div class="col-12">
                        <label class="form-label">NPWP</label>
                        <input type="text" class="form-control text-uppercase" name="npwp" id="npwpCusmas" maxlength="50" required>
                    </div>
                    <div class="col-12">
                        <label class="form-label">ADDRESS</label>
                        <textarea class="form-control" name="address" id="addressCusmas" style="height: 100px;" required></textarea>
                    </div>
                    <div class="col-12">
                        <label class="form-label">CITY</label>
                        <input type="text" class="form-control text-uppercase" name="city" id="cityCusmas" maxlength="50" required>
                    </div>
                    <div class="col-12">
                        <label class="form-label">KODEPOS</label>
                        <input type="text" class="form-control text-uppercase" name="kodepost" id="kodepostCusmas" maxlength="5" required>
                    </div>
                    <div class="col-12">
                        <label class="form-label">PHONE</label>
                        <input type="text" class="form-control text-uppercase" name="phone" id="phoneCusmas" maxlength="40" required>
                    </div>
                    <div class="col-12">
                        <label class="form-label">FAX</label>
                        <input type="text" class="form-control text-uppercase" name="fax" id="faxCusmas" maxlength="40" required>
                    </div>
                    <div class="col-12">
                        <label class="form-label">CONTACT</label>
                        <input type="text" class="form-control text-uppercase" name="contact" id="contactCusmas" maxlength="40" required>
                    </div>

                </div>


                <div class="col-6 row g-3">
                    <div class="col-12">
                        <label class="form-label">EMAIL</label>
                        <input type="text" class="form-control text-uppercase" name="email" id="emailCusmas" maxlength="30" required>
                    </div>
                    <div class="col-12">
                        <label class="form-label">TOPAY</label>
                        <input type="number" class="form-control text-uppercase" name="topay" id="topayCusmas" maxlength="3" required>
                    </div>
                    <div class="col-12">
                        <label class="form-label">CINDU</label>
                        <select class="form-control text-uppercase" name="cindu" id="cinduCusmas" maxlength="3" required>
                            @foreach ($cindus as $cindu)
                                <option value="{{ $cindu->cindu }}">{{ $cindu->descr_cindu }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-12">
                        <label class="form-label">CZONE</label>
                        <select class="form-control text-uppercase" name="czone" id="czoneCusmas" maxlength="5" required>
                            <option value="-"></option>
                            @foreach ($czones as $czone)
                                <option value="{{ $czone->czone }}">{{ $czone->descr_zone }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-12">
                        <label class="form-label">CAREA</label>
                        <select class="form-control text-uppercase" name="carea" id="careaCusmas" maxlength="4" required>
                            <option value="0000"></option>
                            @foreach ($careas as $carea)
                                <option value="{{ $carea->id_area }}">{{ $carea->carea }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-12">
                        <label class="form-label">DOPEN</label>
                        <input type="date" class="form-control text-uppercase" name="dopen" id="dopenCusmas" required>
                    </div>
                    <div class="col-12">
                        <label class="form-label">CRLIM</label>
                        <input type="number" class="form-control text-uppercase" name="crlim" id="crlimCusmas" maxlength="12" required>
                    </div>
                    <div class="col-12">
                        <label class="form-label">LAUID</label>
                        <input type="text" class="form-control text-uppercase" value="{{ auth()->user()->username }}" name="lauid" id="lauidCusmas" maxlength="50" readonly>
                    </div>

                    <input type="hidden" value="{{ date("Y-m-d H:i:s") }}" class="form-control text-uppercase" name="ladup" id="ladupCusmas" required>

                    <div class="col-12">
                        <label class="form-label">STATUS</label>
                        <input type="text" class="form-control text-uppercase" name="status" id="statusCusmas" maxlength="1" required>
                    </div>
                    <div class="col-12">
                        <label class="form-label">BARVAL</label>
                        <input type="number" class="form-control text-uppercase" name="barval" id="barvalCusmas" maxlength="50" required>
                    </div>
                    <div class="col-12">
                        <label class="form-label">OPENO</label>
                        <input type="number" class="form-control text-uppercase" name="openo" id="openoCusmas" maxlength="50" required>
                    </div>
                    <div class="col-12">
                        <label class="form-label">OARVAL</label>
                        <input type="number" class="form-control text-uppercase" name="oarval" id="oarvalCusmas" maxlength="50" required>
                    </div>
                    <div class="col-12">
                        <label class="form-label">CSECT</label>
                        <input type="text" class="form-control text-uppercase" name="csect" id="csectCusmas" maxlength="2" required>
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

<div class="modal fade" id="mLCusmas" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">Ubah Pelanggan <span id="span_id_pelanggan" style="font-size: 11px;"></span></h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
              <form method="post" id="formUpdateCusmas" action="" class="row g-3">
                  @csrf
                  @method('put')
                  <div class="col-6 row g-3">
                    <div class="col-12">
                        <label class="form-label">CUSNO</label>
                        <input type="text" class="form-control text-uppercase" id="cusnoEditCusmas" name="cusno" readonly>
                    </div>
                    <div class="col-12">
                        <label class="form-label">BRACO</label>
                        <select class="form-control text-uppercase" id="bracoEditCusmas" name="braco" maxlength="3" required>
                        </select>
                    </div>
                    <div class="col-12">
                        <label class="form-label">CUSNA</label>
                        <input type="text" class="form-control text-uppercase" id="cusnaEditCusmas" name="cusna" maxlength="200" required>
                    </div>
                    <div class="col-12">
                        <label class="form-label">BILLN</label>
                        <input type="text" class="form-control text-uppercase" id="billnEditCusmas" name="billn" maxlength="100" required>
                    </div>
                    <div class="col-12">
                        <label class="form-label">TITLE</label>
                        <select class="form-control text-uppercase" name="title" id="titleEditCusmas" maxlength="5" required>
                        </select>
                    </div>
                    <div class="col-12">
                        <label class="form-label">PRPOS</label>
                        <input type="text" class="form-control text-uppercase" name="prpos" id="prposEditCusmas" maxlength="1" required>
                    </div>
                    <div class="col-12">
                        <label class="form-label">PKP</label>
                        <input type="text" class="form-control text-uppercase" name="pkp" id="pkpEditCusmas" maxlength="1" required>
                    </div>
                    <div class="col-12">
                        <label class="form-label">NPWP</label>
                        <input type="text" class="form-control text-uppercase" name="npwp" id="npwpEditCusmas" maxlength="50" required>
                    </div>
                    <div class="col-12">
                        <label class="form-label">ADDRESS</label>
                        <textarea class="form-control" name="address" id="addressEditCusmas" style="height: 100px;" required></textarea>
                    </div>
                    <div class="col-12">
                        <label class="form-label">CITY</label>
                        <input type="text" class="form-control text-uppercase" name="city" id="cityEditCusmas" maxlength="50" required>
                    </div>
                    <div class="col-12">
                        <label class="form-label">KODEPOS</label>
                        <input type="text" class="form-control text-uppercase" name="kodepost" id="kodepostEditCusmas" maxlength="5" required>
                    </div>
                    <div class="col-12">
                        <label class="form-label">PHONE</label>
                        <input type="text" class="form-control text-uppercase" name="phone" id="phoneEditCusmas" maxlength="40" required>
                    </div>
                    <div class="col-12">
                        <label class="form-label">FAX</label>
                        <input type="text" class="form-control text-uppercase" name="fax" id="faxEditCusmas" maxlength="40" required>
                    </div>
                    <div class="col-12">
                        <label class="form-label">CONTACT</label>
                        <input type="text" class="form-control text-uppercase" name="contact" id="contactEditCusmas" maxlength="40" required>
                    </div>

                  </div>


                  <div class="col-6 row g-3">
                    <div class="col-12">
                        <label class="form-label">EMAIL</label>
                        <input type="text" class="form-control text-uppercase" name="email" id="emailEditCusmas" maxlength="30" required>
                    </div>
                    <div class="col-12">
                        <label class="form-label">TOPAY</label>
                        <input type="number" class="form-control text-uppercase" name="topay" id="topayEditCusmas" maxlength="3" required>
                    </div>
                    <div class="col-12">
                        <label class="form-label">CINDU</label>
                        <select class="form-control text-uppercase" name="cindu" id="cinduEditCusmas" maxlength="3" required>
                        </select>
                    </div>
                    <div class="col-12">
                        <label class="form-label">CZONE</label>
                        <select class="form-control text-uppercase" name="czone" id="czoneEditCusmas" maxlength="5" required>
                        </select>
                    </div>
                    <div class="col-12">
                        <label class="form-label">CAREA</label>
                        <select class="form-control text-uppercase" name="carea" id="careaEditCusmas" maxlength="4" required>
                        </select>
                    </div>
                    <div class="col-12">
                        <label class="form-label">DOPEN</label>
                        <input type="date" class="form-control text-uppercase" name="dopen" id="dopenEditCusmas" required>
                    </div>
                    <div class="col-12">
                        <label class="form-label">CRLIM</label>
                        <input type="number" class="form-control text-uppercase" name="crlim" id="crlimEditCusmas" maxlength="12" required>
                    </div>
                    <div class="col-12">
                        <label class="form-label">LAUID</label>
                        <input type="text" class="form-control text-uppercase" value="{{ auth()->user()->username }}" name="lauid" id="lauidEditCusmas" maxlength="50" readonly>
                    </div>

                    <input type="hidden" value="{{ date("Y-m-d H:i:s") }}" class="form-control text-uppercase" name="ladup" id="ladupEditCusmas" required>

                    <div class="col-12">
                        <label class="form-label">STATUS</label>
                        <input type="text" class="form-control text-uppercase" name="status" id="statusEditCusmas" maxlength="1" required>
                    </div>
                    <div class="col-12">
                        <label class="form-label">BARVAL</label>
                        <input type="number" class="form-control text-uppercase" name="barval" id="barvalEditCusmas" maxlength="50" required>
                    </div>
                    <div class="col-12">
                        <label class="form-label">OPENO</label>
                        <input type="number" class="form-control text-uppercase" name="openo" id="openoEditCusmas" maxlength="50" required>
                    </div>
                    <div class="col-12">
                        <label class="form-label">OARVAL</label>
                        <input type="number" class="form-control text-uppercase" name="oarval" id="oarvalEditCusmas" maxlength="50" required>
                    </div>
                    <div class="col-12">
                        <label class="form-label">CSECT</label>
                        <input type="text" class="form-control text-uppercase" name="csect" id="csectEditCusmas" maxlength="2" required>
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
