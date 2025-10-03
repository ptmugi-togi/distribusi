@extends('layout.main')

<style>
    input::-webkit-outer-spin-button,
    input::-webkit-inner-spin-button {
    -webkit-appearance: none;
    margin: 0;
    }

    /* Select2 default list */
    .select2-results__options li {
    display: none;
    }

    /* hanya tampilkan 10 pertama */
    .select2-results__options li:nth-child(-n+10) {
    display: list-item;
    }
</style>

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
            <form id="form-edit-blawb" action="{{ route('blawb.update', $tbolh->rinum) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="row">
                    <div class="col-md-6 mt-3">
                        <input type="text" name="braco" id="braco" value="PST" hidden>
                        <input type="text" name="formc" id="formc" value="RI" hidden>

                        <label for="rinum" class="form-label">Receipt Number</label><span class="text-danger"> *</span>
                        <div class="input-group">
                            <span class="input-group-text">RI</span>
                            <input type="number" class="form-control" placeholder="Cth : 250XXX" name="rinum" id="rinum" value="{{ old('rinum', $tbolh->rinum) }}" required oninput="this.value = this.value.replace(/[^0-9]/g, '')">
                        </div>
                        @error('rinum')
                            <span class="text-danger">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>

                    <div class="col-md-6 mt-3">
                        <label for="nocal" class="form-label">Calculation Number</label>
                        <input type="text" class="form-control" name="nocal" id="nocal" value="{{ old('nocal', $tbolh->nocal) }}">
                    </div>

                    <div class="col-md-6 mt-3">
                        <label for="blnum" class="form-label">BL/AWB No.</label><span class="text-danger"> *</span>
                        <input type="text" class="form-control" name="blnum" id="blnum" value="{{ old('blnum', $tbolh->blnum) }}" required>
                    </div>

                    <div class="col-md-6 mt-3">
                        <label for="bldat" class="form-label">BL/AWB Date</label><span class="text-danger"> *</span>
                        <input type="date" class="form-control" name="bldat" id="bldat" value="{{ old('bldat', $tbolh->bldat) }}" required>
                    </div>

                    <div class="col-md-6 mt-3">
                        <label for="shpln" class="form-label">Shipping Line</label><span class="text-danger"> *</span>
                        <input type="text" class="form-control" name="shpln" id="shpln" value="{{ old('shpln', $tbolh->shpln) }}" required>
                    </div>

                    <div class="col-md-6 mt-3">
                        <label for="vesel" class="form-label">Vessel/Flight</label>
                        <input type="text" class="form-control" name="vesel" id="vesel" value="{{ old('vesel', $tbolh->vesel) }}">
                    </div>

                    <div class="col-md-6 mt-3">
                        <label for="pload" class="form-label">Port of Loading</label>
                        <input type="text" class="form-control" name="pload" id="pload" value="{{ old('pload', $tbolh->pload) }}">
                    </div>

                    <div class="col-md-6 mt-3">
                        <label for="pdest" class="form-label">Port of Destination</label>
                        <input type="text" class="form-control" name="pdest" id="pdest" value="{{ old('pdest', $tbolh->pdest) }}">
                    </div>

                    <div class="col-md-4 mt-3">
                        <label for="etds" class="form-label">ETD</label><span class="text-danger"> *</span>
                        <input type="date" class="form-control" name="etds" id="etds" value="{{ old('etds', $tbolh->etds) }}" required>
                    </div>

                    <div class="col-md-4 mt-3">
                        <label for="etah" class="form-label">ETA Harbour</label><span class="text-danger"> *</span>
                        <input type="date" class="form-control" name="etah" id="etah" value="{{ old('etah', $tbolh->etah) }}" required>
                    </div>

                    <div class="col-md-4 mt-3">
                        <label for="etaw" class="form-label">ETA Warehouse</label><span class="text-danger"> *</span>
                        <input type="date" class="form-control" name="etaw" id="etaw" value="{{ old('etaw', $tbolh->etaw) }}" required>
                    </div>

                    <div class="col-md-6 mt-3">
                        <label for="supno" class="form-label">Supplier <span class="text-danger">*</span></label>
                        <select class="select2 form-control" name="supno" id="supno" required>
                            <option value="" disabled {{ old('supno', $tbolh->supno) ? '' : 'selected' }}>Silahkan pilih Supplier</option>
                            @foreach($vendors as $v)
                                <option
                                    value="{{ $v->supno }}"
                                    {{ old('supno', $tbolh->supno) == $v->supno ? 'selected' : '' }}>
                                    {{ $v->supno }} - {{ $v->supna }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-3 mt-3">
                        <label for="gweight" class="form-label">Gross Wight (Kg)</label>
                        <input type="number" class="form-control" name="gweight" id="gweight" value="{{ old('gweight', $tbolh->gweight) }}">
                    </div>

                    <div class="col-md-3 mt-3">
                        <label for="nweight" class="form-label">Net Weight (Kg)</label>
                        <input type="number" class="form-control" name="nweight" id="nweight" value="{{ old('nweight', $tbolh->nweight) }}">
                    </div>

                    <div class="col-md-6 mt-3">
                        <label for="npolis" class="form-label">Insurance Polis No.</label>
                        <input type="text" class="form-control" name="npolis" id="npolis" value="{{ old('npolis', $tbolh->npolis) }}">
                    </div>

                    <div class="col-md-6 mt-3">
                        <label for="tpiud" class="form-label">Insurance Polis Date</label>
                        <input type="date" class="form-control" name="tpiud" id="tpiud" value="{{ old('tpiud', $tbolh->tpiud) }}">
                    </div>

                    <div class="col-md-6 mt-3">
                        <label for="npiud" class="form-label">PIB No.</label>
                        <input type="text" class="form-control" name="npiud" id="npiud" value="{{ old('npiud', $tbolh->npiud) }}">
                    </div>

                    <div class="col-md-6 mt-3">
                        <label for="tpiud" class="form-label">PIB Date</label>
                        <input type="date" class="form-control" name="tpiud" id="tpiud" value="{{ old('tpiud', $tbolh->tpiud) }}">
                    </div>

                    <div class="col-md-6 mt-3">
                        <label for="clrag" class="form-label">Clearing Agent</label>
                        <input type="text" class="form-control" name="clrag" id="clrag" value="{{ old('clrag', $tbolh->clrag) }}">
                    </div>
                </div>


                <hr class="my-4">

                <div class="row">
                    <h3 class="my-2">Detail</h3>

                    <div class="col-md-6 mt-3">
                        <label for="I01" class="form-label">Biaya Transport</label>
                        <input type="text" class="form-control currency" name="I01" id="I01" value="{{ old('I01', $biaya['I01'] ?? '') }}">
                    </div>
                    
                    <div class="col-md-6 mt-3">
                        <label for="I02" class="form-label">Biaya Asuransi</label>
                        <input type="text" class="form-control currency" name="I02" id="I02" value="{{ old('I02', $biaya['I02'] ?? '') }}">
                    </div>

                    <div class="col-md-6 mt-3">
                        <label for="I03" class="form-label">Biaya Sewa Gudang</label>
                        <input type="text" class="form-control currency" name="I03" id="I03" value="{{ old('I03', $biaya['I03'] ?? '') }}">
                    </div>

                    <div class="col-md-6 mt-3">
                        <label for="I04" class="form-label">Biaya Handling Charges</label>
                        <input type="text" class="form-control currency" name="I04" id="I04" value="{{ old('I04', $biaya['I04'] ?? '') }}">
                    </div>

                    <div class="col-md-6 mt-3">
                        <label for="I05" class="form-label">Freight Collect</label>
                        <input type="text" class="form-control currency" name="I05" id="I05" value="{{ old('I05', $biaya['I05'] ?? '') }}">
                    </div>

                    <div class="col-md-6 mt-3">
                        <label for="I06" class="form-label">BEA Masuk</label>
                        <input type="text" class="form-control currency" name="I06" id="I06" value="{{ old('I06', $biaya['I06'] ?? '') }}">
                    </div>

                    <div class="col-md-6 mt-3">
                        <label for="I07" class="form-label">Biaya Lain - lain</label>
                        <input type="text" class="form-control currency" name="I07" id="I07" value="{{ old('I07', $biaya['I07'] ?? '') }}">
                    </div>
                </div>

                <div class="mt-3 d-flex justify-content-between">
                    <a href="{{ route('blawb.index') }}" class="btn btn-secondary">Kembali</a>
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
                        <p>Apakah anda yakin ingin menyimpan perubahan data?</p>
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

        <script>
            document.querySelectorAll('.currency').forEach(function(el) {
                // kalo sudah ada value langsung format
                let initValue = el.value.replace(/\D/g,''); 
                if(initValue){
                    el.value = 'Rp. ' + new Intl.NumberFormat('id-ID').format(initValue);
                }
                
                // format ketika input
                el.addEventListener('input', function(e) {
                    let value = e.target.value.replace(/\D/g,''); // hapus semua non-digit
                    if(value){
                        e.target.value = 'Rp. ' + new Intl.NumberFormat('id-ID').format(value);
                    } else {
                        e.target.value = '';
                    }
                });
            });

            // hapus  titik sebelum kirim
            document.querySelector('form').addEventListener('submit', function() {
                document.querySelectorAll('.currency').forEach(function(el) {
                    el.value = el.value.replace(/\D/g, ''); // hanya angka yang dikirim
                });
            });
        </script>

        {{-- Modal Konfirmasi simpan data --}}
        <script>
            const form = document.getElementById('form-edit-blawb');
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