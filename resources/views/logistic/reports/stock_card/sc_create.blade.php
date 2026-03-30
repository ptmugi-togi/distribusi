@extends('layout.main')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/global.css') }}">
@endpush

@section('container')
<main id="main" class="main">
    <div class="d-flex justify-content-between align-items-center">
        <div class="pagetitle">
        <h1>Stock Card</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                <li class="breadcrumb-item active">Stock Card</li>
            </ol>
        </nav>
        </div>
    </div>

    <section class="section">
        <form id="form-sc" action="" method="POST">
            @csrf

            <div class="row">
                <div class="col-md-6 mt-3">
                    <label for="braco" class="form-label">Branch</label><span class="text-danger"> *</span>
                    <input type="text" class="form-control" name="braco" id="braco" value="{{ (old('braco') ? old('braco') : auth()->user()->cabang) }}" disabled required>
                </div>

                <div class="col-md-6 mt-3">
                    <label for="opron" class="form-label">Product</label><span class="text-danger"> *</span>
                    <select class="select2 form-control" name="opron" id="opron" required>
                        <option value="" disabled {{ old('opron') ? '' : 'selected' }}>Silahkan Pilih Product</option>
                    </select>
                </div>

                <div class="col-md-6 mt-3">
                    <label for="sodat_s" class="form-label">Date Start</label><span class="text-danger"> *</span>
                    <input type="date" class="form-control" name="sodat_s" id="sodat_s" value="{{ old('sodat_s') }}" required>
                </div>

                <div class="col-md-6 mt-3">
                    <label for="sodat_e" class="form-label">Date End</label><span class="text-danger"> *</span>
                    <input type="date" class="form-control" name="sodat_e" id="sodat_e" value="{{ old('sodat_e') }}" required>
                </div>
            </div>

            <div class="mt-3 d-flex justify-content-between">
                <button type="button" id="excelSc" class="btn btn-success" disabled>Download Excel</button>
                <button type="button" id="printSc" class="btn btn-primary">Print Data</button>
            </div>
        </form>
    </section>
</main>

    @push('scripts')
        <script>
            $(document).ready(function () {
                setTimeout(function(){
                    initSelect2Product();
                }, 300);
            });

            function initSelect2Product() {
                let el = $('#opron');

                el.select2({
                    placeholder: 'Pilih Barang',
                    theme: 'bootstrap-5',
                    width: '100%',
                    allowClear: true,
                    minimumInputLength: 0,
                    ajax: {
                        url: '{{ route("api.products") }}',
                        dataType: 'json',
                        delay: 250,
                        data: function(params){
                            return {
                                q: params.term || '',
                                page: params.page || 1
                            };
                        },
                        processResults: function(data){
                            return {
                                results: data.results,
                                pagination: {
                                    more: data.pagination.more
                                }
                            };
                        }
                    }
                });
            }

            document.getElementById('printSc').addEventListener('click', function () {
                // ambil elemen input
                const braco = document.getElementById('braco').value.trim();
                const opron = document.getElementById('opron').value.trim();
                const sodat_s = document.getElementById('sodat_s').value.trim();
                const sodat_e = document.getElementById('sodat_e').value.trim();

                // cek required field
                if (!opron) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Oops...',
                        text: 'Pilih Product Terlebih Dahulu!',
                    }).then(() => {
                        document.getElementById('opron').focus();
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
                    opron: opron,
                    sodat_s: sodat_s,
                    sodat_e: sodat_e,
                });

                window.open("{{ route('sc.preview') }}?" + params.toString(), "_blank");
            });
        </script>
@endpush
@endsection