@extends('layout.main')

@section('container')
  <main id="main" class="main">
    <div class="pagetitle">
        <h1>Tambah List TPO</h1>
        <nav>
          <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
            <li class="breadcrumb-item"><a href="{{ route('tpohdr.index') }}">List TPO</a></li>
            <li class="breadcrumb-item active">TPO Create</li>
          </ol>
        </nav>
    </div>

    <section class="section">
        <form action="{{ route('tpohdr.store') }}" method="POST">
            @csrf
            <div class="row">
                <div class="col-md-6 mt-3">
                    <label for="pono" class="form-label">Nomor PO</label><span class="text-danger"> *</span>
                    <input type="text" class="form-control" placeholder="Cth : BAMAC-20199" name="pono" id="pono" required>
                    @error('pono')
                        <span class="text-danger">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <div class="col-md-6 mt-3">
                    <label for="formc" class="form-label">Form Code</label><span class="text-danger"> *</span>
                    <select class="select2 form-control" name="formc" id="formc" style="width: 100%;" required>
                    <option value="" {{ old('formc') == '' ? 'selected' : '' }} disabled selected>Silahkan pilih Form Code</option>
                    <option value="PO" {{ old('formc') == 'PO' ? 'selected' : '' }}>PO (PO Lokal)</option>
                    <option value="PI" {{ old('formc') == 'PI' ? 'selected' : '' }}>PI (PO Import)</option>
                    <option value="PN" {{ old('formc') == 'PN' ? 'selected' : '' }}>PN (PO Inventaris)</option>
                    </select>
                </div>

                <div class="col-md-6 mt-3">
                    <label for="podat" class="form-label">Tanggal PO</label><span class="text-danger"> *</span>
                    <input type="date" class="form-control" placeholder="" name="podat" id="podat"  value="{{ old('podat') }}" required>
                </div>

                <div class="col-md-6 mt-3">
                    <label for="potype" class="form-label">Tipe PO</label><span class="text-danger"> *</span>
                    <select class="select2 form-control" name="potype" id="potype" style="width: 100%;" required>
                    <option value="" {{ old('potype') == '' ? 'selected' : '' }} disabled selected>Silahkan pilih Tipe PO</option>
                    <option value="Lokal" {{ old('potype') == 'Lokal' ? 'selected' : '' }}>Lokal</option>
                    <option value="Import" {{ old('potype') == 'Import' ? 'selected' : '' }}>Import</option>
                    <option value="Inventaris" {{ old('potype') == 'Inventaris' ? 'selected' : '' }}>Inventaris</option>
                    </select>
                </div>

                <div class="col-md-6 mt-3">
                    <label for="topay" class="form-label">Term Of Payment</label><span class="text-danger"> *</span>
                    <input type="number" class="form-control" placeholder="Cth : 30" name="topay" id="topay" value="{{ old('topay') }}" required>
                </div>

                <div class="col-md-6 mt-3">
                    <label for="tdesc" class="form-label">Deskripsi Term Of Payment</label><span class="text-danger"> *</span>
                    <input type="text" class="form-control" placeholder="Cth : Bulan Kredit" name="tdesc" id="tdesc" value="{{ old('tdesc') }}" required>
                </div>

                <div class="col-md-6 mt-3">
                    <label for="curco" class="form-label">Currency Code</label><span class="text-danger"> *</span>
                    <select class="select2 form-control" name="curco" id="curco" style="width: 100%;" required>
                    <option value="" {{ old('curco') == '' ? 'selected' : '' }} disabled selected>Silahkan pilih Currency Code</option>
                    <option value="CHF" {{ old('curco') == 'CHF' ? 'selected' : '' }}>CHF (Franc Swiss)</option>
                    <option value="EUR" {{ old('curco') == 'EUR' ? 'selected' : '' }}>EUR (Euro)</option>
                    <option value="GBP" {{ old('curco') == 'GBP' ? 'selected' : '' }}>GBP (Pound Sterling)</option>
                    <option value="IDR" {{ old('curco') == 'IDR' ? 'selected' : '' }}>IDR (Rupiah Indonesia)</option>
                    <option value="MYR" {{ old('curco') == 'MYR' ? 'selected' : '' }}>MYR (Ringgit Malaysia)</option>
                    <option value="SGD" {{ old('curco') == 'SGD' ? 'selected' : '' }}>SGD (Dollar Singapura)</option>
                    <option value="USD" {{ old('curco') == 'USD' ? 'selected' : '' }}>USD (Dollar Amerika Serikat)</option>
                    </select>
                </div>

                <div class="col-md-6 mt-3">
                    <label for="shvia" class="form-label">Pengiriman</label><span class="text-danger"> *</span>
                    <select class="select2 form-control" name="shvia" id="shvia" style="width: 100%;" required>
                    <option value="" {{ old('shvia') ? '' : 'selected' }} disabled selected>Silahkan pilih Pengiriman</option>
                    <option value="DARAT" {{ old('shvia') == 'DARAT' ? 'selected' : '' }}>DARAT</option>
                    <option value="LAUT" {{ old('shvia') == 'LAUT' ? 'selected' : '' }}>LAUT</option>
                    <option value="UDARA" {{ old('shvia') == 'UDARA' ? 'selected' : '' }}>UDARA</option>
                    </select>
                </div>

                <div class="col-md-6 mt-3">
                    <label for="delco" class="form-label">Kode Pengirim</label><span class="text-danger"> *</span>
                    <select class="select2 form-control" name="delco" id="delco" style="width: 100%;" required>
                    <option value="" {{ old('delco') ? '' : 'selected' }} disabled selected>Silahkan pilih Kode Pengirim</option>
                    <option value="PST" {{ old('delco') == 'PST' ? 'selected' : '' }}>PST (Pusat)</option>
                    <option value="CKG" {{ old('delco') == 'CKG' ? 'selected' : '' }}>CKG (Cakung)</option>
                    <option value="D3" {{ old('delco') == 'D3' ? 'selected' : '' }}>D3 (Duren 3)</option>
                    </select>
                </div>

                <div class="col-md-6 mt-3">
                    <label for="delnm" class="form-label">Nama Pengirim</label><span class="text-danger"> *</span>
                    <input type="text" class="form-control" placeholder="Cth : PT MUGI" name="delnm" id="delnm" value="{{ old('delnm') }}" required>
                </div>

                <div class="col-md-6 mt-3">
                    <label for="dconp" class="form-label">Kontak Pengirim</label><span class="text-danger"> *</span>
                    <input type="text" class="form-control" placeholder="Cth : PT MUGI" name="dconp" id="dconp" value="{{ old('dconp') }}" required>
                </div>

                <div class="col-md-6 mt-3">
                    <label for="diper" class="form-label">Diskon (%)</label><span class="text-danger"> *</span>
                    <input type="double" class="form-control" placeholder="Cth : 10" name="diper" id="diper" value="{{ old('diper') }}" required>
                </div>

                <div class="col-md-6 mt-3">
                    <label for="vatax" class="form-label">Tax Rate (%)</label><span class="text-danger"> *</span>
                    <input type="double" class="form-control" placeholder="Cth : 5" name="vatax" id="vatax" value="{{ old('vatax') }}" required>
                </div>

                <div class="col-md-6 mt-3">
                    <label for="pph" class="form-label">PPH (%)</label><span class="text-danger"> *</span>
                    <input type="number" class="form-control" placeholder="Cth : 5" name="pph" id="pph" value="{{ old('pph') }}" required>
                </div>

                <div class="col-md-6 mt-3">
                    <label for="stamp" class="form-label">Meterai</label>
                    <input type="number" class="form-control" placeholder="Cth : 10000" name="stamp" id="stamp" value="{{ old('stamp') }}">
                </div>
                <div class="col-md-6 mt-3">
                    <label for="noteh" class="form-label">Note</label><span class="text-danger"> *</span>
                    <input type="text" class="form-control" placeholder="Cth : note" name="noteh" id="noteh" value="{{ old('noteh') }}" required>
                </div>

                <div class="col-md-6 mt-3">
                <label for="supno" class="form-label">No Supplier <span class="text-danger">*</span></label>
                <select class="select2 form-control" name="supno" id="supno" onchange="$('#supna').val($(this).find(':selected').data('supna')||'')" required>
                    <option value="" disabled {{ old('supno') ? '' : 'selected' }}>Silahkan pilih No Supplier</option>
                    @foreach($vendors as $v)
                        <option
                            value="{{ $v->supno }}"
                            data-supna="{{ $v->supna }}"
                            {{ old('supno') == $v->supno ? 'selected' : '' }}>
                            {{ $v->supno }} — {{ $v->supna }}
                        </option>
                    @endforeach
                </select>
                @error('formc')
                        <span class="text-danger">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <div class="col-md-6 mt-3">
                    <label for="supna" class="form-label">Nama Supplier</label>
                    <input type="text" class="form-control" name="supna" id="supna"
                            value="{{ old('supna') }}" readonly required>
                            @error('formc')
                        <span class="text-danger">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <div class="mt-3 d-flex justify-content-between">
                    <a href="{{ route('tpohdr.index') }}" class="btn btn-secondary">Kembali</a>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </div>
        </form>
    </section>

@endsection