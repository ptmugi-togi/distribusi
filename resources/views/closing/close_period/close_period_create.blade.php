@extends('layout.main')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/global.css') }}">
@endpush

@section('container')
<main id="main" class="main">
    <div class="d-flex justify-content-between align-items-center">
        <div class="pagetitle">
            <h1>Closing Period</h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item active">CLOSING PERIOD</li>
                </ol>
            </nav>
        </div>
    </div>

    <section class="section">
        <form id="form-close-period" action="{{ route('close_period.store') }}" method="POST">
            @csrf

            <div class="row">
                <div class="col-md-6 mt-3">
                    <label for="periode" class="form-label">Current Open Period</label><span class="text-danger"> *</span>
                    <input type="text" class="form-control" value="{{ $formattedPeriode }}" disabled>
                    <input type="hidden" name="periode" id="periode" value="{{ $periode }}">
                </div>
                <div class="col-md-6 mt-3">
                    <label for="close_period" class="form-label">Close Period</label>
                    <select name="close_period" id="close_period" class="form-control select2">
                        <option value="yes" {{ old('close_period', 'yes') == 'yes' ? 'selected' : '' }}>Yes</option>
                        <option value="no" {{ old('close_period') == 'no' ? 'selected' : '' }}>No</option>
                    </select>
                </div>
                <div class="text-center mt-3">
                    <h5><b>Perhatian!</b> Tolong backup dahulu sebelum menutup periode</h5>
                </div>
            </div>

            <div class="mt-3 d-flex justify-content-end">
                <button type="button" id="btnSubmit" class="btn btn-primary">Simpan</button>
            </div>
        </form>
    </section>
</main>

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            
            @if(session('success'))
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: "{{ session('success') }}",
                    confirmButtonColor: '#3085d6'
                });
            @endif

            @if(session('error'))
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal!',
                    text: "{{ session('error') }}",
                    confirmButtonColor: '#d33'
                });
            @endif

            const btnSubmit = document.getElementById('btnSubmit');
            const formClosePeriod = document.getElementById('form-close-period');

            btnSubmit.addEventListener('click', function (e) {
                e.preventDefault();

                const periodeVal = document.getElementById('periode').value;
                if (!periodeVal) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Peringatan',
                        text: 'Tidak ada periode open yang dapat diproses.',
                    });
                    return;
                }

                Swal.fire({
                    title: 'Konfirmasi Closing Period',
                    text: "Sistem akan mengupdate stok, menutup periode ini, dan otomatis membuka periode bulan berikutnya. Lanjutkan?",
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Ya, Proses Sekarang',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        Swal.fire({
                            title: 'Memproses...',
                            text: 'Sistem sedang memproses transaksi dan memperbarui periode',
                            allowOutsideClick: false,
                            didOpen: () => {
                                Swal.showLoading();
                            }
                        });
                        
                        formClosePeriod.submit();
                    }
                });
            });
        });
    </script>
@endpush
@endsection