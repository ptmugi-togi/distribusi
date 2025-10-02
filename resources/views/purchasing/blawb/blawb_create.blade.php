@extends('layout.main')

@section('container')
    <main id="main" class="main">
        <div class="pagetitle">
            <h1>Tambah Data BL AWB</h1>
            <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('blawb.index') }}">List BL AWB</a></li>
                <li class="breadcrumb-item active">BL AWB Create</li>
            </ol>
            </nav>
        </div>

        <section class="section">
            <form id="form-po" action="{{ route('tpo.store') }}" method="POST">
                @csrf
                <div class="row">
                    <div class="col-md-6 mt-3">
                        <input type="text" name="formc" id="formc" value="RI" hidden>

                        <label for="rinum" class="form-label">Receipt Number</label><span class="text-danger"> *</span>
                        <div class="input-group">
                            <span class="input-group-text">RI</span>
                            <input type="text" class="form-control" placeholder="Cth : 250XXX" name="rinum" id="rinum" value="{{ old('rinum') }}" required>
                        </div>
                        @error('rinum')
                            <span class="text-danger">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>

                    <div class="col-md-6 mt-3">
                        <label for="rinum" class="form-label">Receipt Number</label><span class="text-danger"> *</span>
                        <input type="text" class="form-control" placeholder="Cth : 06X/2X" name="rinum" id="rinum" value="{{ old('rinum') }}" required>
                    </div>

                    <div class="col-md-6 mt-3">
                        <label for="nocal" class="form-label">Calculation Number</label><span class="text-danger"> *</span>
                        <input type="text" class="form-control" placeholder="Cth : 06X/2X" name="nocal" id="nocal" value="{{ old('nocal') }}" required>
                    </div>

                    <div class="col-md-6 mt-3">
                        <label for="blnum" class="form-label">BL/AWB No.</label><span class="text-danger"> *</span>
                        <input type="text" class="form-control" placeholder="Cth : 06X/2X" name="blnum" id="blnum" value="{{ old('blnum') }}" required>
                    </div>

                    <div class="col-md-6 mt-3">
                        <label for="bldat" class="form-label">BL/AWB Date</label><span class="text-danger"> *</span>
                        <input type="date" class="form-control" name="bldat" id="bldat" value="{{ old('bldat') }}" required>
                    </div>

                    <div class="col-md-6 mt-3">
                        <label for="shpln" class="form-label">Shipping Line</label><span class="text-danger"> *</span>
                        <input type="text" class="form-control" placeholder="Cth : 06X/2X" name="shpln" id="shpln" value="{{ old('shpln') }}" required>
                    </div>

                    <div class="col-md-6 mt-3">
                        <label for="vesel" class="form-label">Vessel/Flight</label><span class="text-danger"> *</span>
                        <input type="text" class="form-control" placeholder="Cth : 06X/2X" name="vesel" id="vesel" value="{{ old('vesel') }}" required>
                    </div>

                    <div class="col-md-6 mt-3">
                        <label for="pload" class="form-label">Port of Loading</label><span class="text-danger"> *</span>
                        <input type="text" class="form-control" placeholder="Cth : 06X/2X" name="pload" id="pload" value="{{ old('pload') }}" required>
                    </div>

                    <div class="col-md-6 mt-3">
                        <label for="pdest" class="form-label">Port of Destination</label><span class="text-danger"> *</span>
                        <input type="text" class="form-control" placeholder="Cth : 06X/2X" name="pdest" id="pdest" value="{{ old('pdest') }}" required>
                    </div>

                    <div class="col-md-6 mt-3">
                        <label for="supno" class="form-label">Supplier <span class="text-danger">*</span></label>
                        <select class="select2 form-control" name="supno" id="supno" required>
                            <option value="" disabled {{ old('supno') ? '' : 'selected' }}>Silahkan pilih Supplier</option>
                            @foreach($vendors as $v)
                                <option
                                    value="{{ $v->supno }}"
                                    {{ old('supno') == $v->supno ? 'selected' : '' }}>
                                    {{ $v->supna }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                </div>


                <hr class="my-4">

                <div class="row">
                    <h3 class="my-2">Detail Barang PO</h3>
                </div>

                <div class="mt-3 d-flex justify-content-between">
                    <a href="{{ route('tpo.index') }}" class="btn btn-secondary">Kembali</a>
                    <button type="submit" class="btn btn-primary">Simpan Data</button>
                </div>
            </form>

            {{-- modal konfirmasi simpan data --}}
            <div class="modal fade" id="modalKonfirmasi" tabindex="-1">
                <div class="modal-dialog">
                    <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Konfirmasi Simpan</h5>
                    </div>
                    <div class="modal-body">
                        <p>Apakah anda yakin ingin menyimpan data?</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="button" id="btnKonfirmasiSimpan" class="btn btn-primary">Ya, Simpan</button>
                    </div>
                    </div>
                </div>
            </div>
        </section>
    </main>

    @push('scripts')

        {{-- custom select2 agar tidak load semua data, hanya 10 --}}
        <script>
            $('.select2').select2({
                placeholder: "Silahkan pilih Supplier",
                minimumResultsForSearch: 0,
                templateResult: function (data, container) {
                    // kalau tidak ada pencarian (params.term kosong) → batasi 10
                    if ($('.select2-search__field').val() === '' && data._resultId) {
                        // ambil index option dari ID yang dibikin Select2
                        let index = parseInt(data._resultId.split('-').pop());
                        if (index > 10) {
                            return null; // hide item > 10
                        }
                    }
                    return data.text;
                }
            });
        </script>

        {{-- Modal Konfirmasi simpan data --}}
        <script>
            const form = document.getElementById('form-po');
            const btnKonfirmasi = document.getElementById('btnKonfirmasiSimpan');

            // cegah submit default
            form.addEventListener('submit', function (e) {
            e.preventDefault();

            // jalankan validasi browser dulu
            if (form.checkValidity()) {
                // tampilkan modal konfirmasi
                const modal = new bootstrap.Modal(document.getElementById('modalKonfirmasi'));
                modal.show();
            } else {
                // trigger bootstrap validation styling
                form.classList.add('was-validated');
            }
            });

            // kalau user setuju simpan
            btnKonfirmasi.addEventListener('click', () => {
            form.submit(); // submit form beneran
            });
        </script>
    @endpush

@endsection