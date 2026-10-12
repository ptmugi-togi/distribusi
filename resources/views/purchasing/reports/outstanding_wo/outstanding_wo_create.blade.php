@extends('layout.main')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/global.css') }}">
@endpush

@section('container')
<main id="main" class="main">
    <div class="d-flex justify-content-between align-items-center">
        <div class="pagetitle">
        <h1>Print Outstanding WO</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                <li class="breadcrumb-item active">Print Outstanding WO</li>
            </ol>
        </nav>
        </div>
    </div>

    <section class="section">
        <form id="form-buku-penjualan" action="" method="POST">
            @csrf

            <div class="row">
                <div class="col-md-6 mt-3">
                    <label for="bdate_s" class="form-label">Date Start</label><span class="text-danger"> *</span>
                    <input type="date" class="form-control" name="bdate_s" id="bdate_s" value="{{ old('bdate_s', $bdate_s) }}" required>
                </div>

                <div class="col-md-6 mt-3">
                    <label for="bdate_e" class="form-label">Date End</label><span class="text-danger"> *</span>
                    <input type="date" class="form-control" name="bdate_e" id="bdate_e" value="{{ old('bdate_e', $bdate_e) }}" required>
                </div>
                
                <div class="col-md-6 mt-3">
                    <label for="wo_status" class="form-label">WO Status</label>
                    <select name="wo_status" id="wo_status" class="form-control select2">
                        <option value="All" selected {{ old('wo_status') == 'All' ? 'selected' : '' }}>All</option>
                        <option value="Outstanding" {{ old('wo_status') == 'Outstanding' ? 'selected' : '' }}>Outstanding</option>
                    </select>
                </div>

                <div class="col-md-6 mt-3">
                    <label for="sort_by" class="form-label">Sort By</label>
                    <select name="sort_by" id="sort_by" class="form-control select2">
                        <option value="wonum" selected {{ old('sort_by') == 'wonum' ? 'selected' : '' }}>WO No</option>
                        <option value="opron" {{ old('sort_by') == 'opron' ? 'selected' : '' }}>Product No</option>
                    </select>
                </div>
            </div>

            <div class="mt-3 d-flex justify-content-end">
                <button type="button" id="printOutstandingWo" class="btn btn-primary">Print Data</button>
            </div>
        </form>
    </section>
</main>

    @push('scripts')
        <script>
            document.getElementById('printOutstandingWo').addEventListener('click', function () {
                // ambil elemen input
                const bdate_s = document.getElementById('bdate_s').value.trim();
                const bdate_e = document.getElementById('bdate_e').value.trim();
                const wo_status = document.getElementById('wo_status').value;
                const sort_by = document.getElementById('sort_by').value;

                // cek field
                if (!bdate_s) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Oops...',
                        text: 'Pilih Tanggal Awal Terlebih Dahulu!',
                    }).then(() => {
                        document.getElementById('bdate_s').focus();
                    });
                    return;
                }
                if (!bdate_e) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Oops...',
                        text: 'Pilih Tanggal Akhir Terlebih Dahulu!',
                    }).then(() => {
                        document.getElementById('bdate_e').focus();
                    });
                    return;
                }

                // jika lolos validasi, buat URL dan buka window
                let params = new URLSearchParams({
                    bdate_s: bdate_s,
                    bdate_e: bdate_e,
                    wo_status: wo_status,
                    sort_by: sort_by
                });

                window.open("{{ route('outstandingwo.preview') }}?" + params.toString(), "_blank");
            });
        </script>
@endpush
@endsection