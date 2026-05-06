@extends('layout.main')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/global.css') }}">
@endpush

@section('container')
<main id="main" class="main">
    <div class="d-flex justify-content-between align-items-center">
        <div class="pagetitle">
        <h1>Print Data AR Write Off List</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                <li class="breadcrumb-item active">Print Data AR Write Off List</li>
            </ol>
        </nav>
        </div>
    </div>

    <section class="section">
        <form id="form-payment-list" action="" method="POST">
            @csrf

            <div class="row">
                <div class="col-md-6 mt-3 mx-auto">
                    <label for="braco" class="form-label">Branch</label><span class="text-danger"> *</span>
                    <input type="text" class="form-control" name="braco" id="braco" value="{{ (old('braco') ? old('braco') : auth()->user()->cabang) }}" disabled required>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 mt-3">
                    <label for="tradt_s" class="form-label">Payment Date Start</label><span class="text-danger"> *</span>
                    <input type="date" class="form-control" name="tradt_s" id="tradt_s" value="{{ old('tradt_s') }}" required>
                </div>

                <div class="col-md-6 mt-3">
                    <label for="tradt_e" class="form-label">Payment Date End</label><span class="text-danger"> *</span>
                    <input type="date" class="form-control" name="tradt_e" id="tradt_e" value="{{ old('tradt_e') }}" required>
                </div>
            </div>

            <div class="mt-3 d-flex justify-content-end">
                <button type="button" id="printPaymentList" class="btn btn-primary">Print Data</button>
            </div>
        </form>
    </section>
</main>

    @push('scripts')
        <script>
            document.getElementById('printPaymentList').addEventListener('click', function () {
                // ambil elemen input
                const braco = document.getElementById('braco').value.trim();
                const tradt_s = document.getElementById('tradt_s').value.trim();
                const tradt_e = document.getElementById('tradt_e').value.trim();

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
                if (!tradt_s) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Oops...',
                        text: 'Pilih Tanggal Awal Terlebih Dahulu!',
                    }).then(() => {
                        document.getElementById('tradt_s').focus();
                    });
                    return;
                }
                if (!tradt_e) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Oops...',
                        text: 'Pilih Tanggal Akhir Terlebih Dahulu!',
                    }).then(() => {
                        document.getElementById('tradt_e').focus();
                    });
                    return;
                }

                // jika lolos validasi, buat URL dan buka window
                let params = new URLSearchParams({
                    braco: braco,
                    tradt_s: tradt_s,
                    tradt_e: tradt_e,
                });

                window.open("{{ route('ar_woff_list.preview') }}?" + params.toString(), "_blank");
            });
        </script>
@endpush
@endsection