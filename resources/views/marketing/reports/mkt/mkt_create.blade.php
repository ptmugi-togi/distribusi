@extends('layout.main')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/global.css') }}">
@endpush

@section('container')
<main id="main" class="main">
    <div class="d-flex justify-content-between align-items-center">
        <div class="pagetitle">
        <h1>Print Data MKT OC</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                <li class="breadcrumb-item active">Print Data MKT OC</li>
            </ol>
        </nav>
        </div>
    </div>

    <section class="section">
        <form id="form-mkt" action="" method="POST">
            @csrf

            <div class="row">
                <div class="col-md-6 mt-3">
                    <label for="braco" class="form-label">Branch</label><span class="text-danger"> *</span>
                    <input type="text" class="form-control" name="braco" id="braco" value="{{ (old('braco') ? old('braco') : auth()->user()->cabang) }}" disabled required>
                </div>
                
                <div class="col-md-6 mt-3">
                    <label for="sreno" class="form-label">Sales Rep</label>
                    <select name="sreno" id="sreno" class="form-control select2">
                        <option value="" disabled {{ old('sreno') ? '' : 'selected' }}>Silahkan Pilih Sales Rep</option>
                        @foreach ($sales as $s)
                            <option value="{{ $s->sreno }}" {{ old('sreno') == $s->sreno ? 'selected' : '' }}>
                                {{ $s->sreno }} - {{ $s->srena }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-6 mt-3">
                    <label for="sodat_s" class="form-label">SO Date Start</label><span class="text-danger"> *</span>
                    <input type="date" class="form-control" name="sodat_s" id="sodat_s" value="{{ old('sodat_s') }}" required>
                </div>

                <div class="col-md-6 mt-3">
                    <label for="sodat_e" class="form-label">SO Date End</label><span class="text-danger"> *</span>
                    <input type="date" class="form-control" name="sodat_e" id="sodat_e" value="{{ old('sodat_e') }}" required>
                </div>
            </div>

            <div class="mt-3 d-flex justify-content-end">
                <button type="button" id="printMkt" class="btn btn-primary">Print Data</button>
            </div>
        </form>
    </section>
</main>

    @push('scripts')
        <script>
            document.getElementById('printMkt').addEventListener('click', function () {
                // ambil elemen input
                const braco = document.getElementById('braco').value.trim();
                const sreno = document.getElementById('sreno').value.trim();
                const sodat_s = document.getElementById('sodat_s').value.trim();
                const sodat_e = document.getElementById('sodat_e').value.trim();

                // cek required field
                if (!braco) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Oops...',
                        text: 'Pilih Branch Terlebih Dahulu!',
                    }).then(() => {
                        document.getElementById('braco').focus();
                    });
                    return;
                }
                if (!sodat_s) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Oops...',
                        text: 'Pilih Tanggal Awal Terlebih Dahulu!',
                    }).then(() => {
                        document.getElementById('sodat_s').focus();
                    });
                    return;
                }
                if (!sodat_e) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Oops...',
                        text: 'Pilih Tanggal Akhir Terlebih Dahulu!',
                    }).then(() => {
                        document.getElementById('sodat_e').focus();
                    });
                    return;
                }

                // jika lolos validasi, buat URL dan buka window
                let params = new URLSearchParams({
                    braco: braco,
                    sreno: sreno,
                    sodat_s: sodat_s,
                    sodat_e: sodat_e,
                });

                window.open("{{ route('mkt.previewMkt') }}?" + params.toString(), "_blank");
            });
        </script>
@endpush
@endsection