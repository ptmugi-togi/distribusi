@extends('layout.main')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/global.css') }}">
@endpush

@section('container')
<main id="main" class="main">
    <div class="d-flex justify-content-between align-items-center">
        <div class="pagetitle">
        <h1>Print Outstanding PO</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                <li class="breadcrumb-item active">Print Outstanding PO</li>
            </ol>
        </nav>
        </div>
    </div>

    <section class="section">
        <form id="form-buku-penjualan" action="" method="POST">
            @csrf

            <div class="row">
                <div class="col-md-6 mt-3">
                    <label for="po_status" class="form-label">PO Status</label>
                    <select name="po_status" id="po_status" class="form-control select2">
                        <option value="All" selected {{ old('po_status') == 'All' ? 'selected' : '' }}>All</option>
                        <option value="Outstanding" {{ old('po_status') == 'Outstanding' ? 'selected' : '' }}>Outstanding</option>
                    </select>
                </div>
                <div class="col-md-6 mt-3">
                    <label for="supno" class="form-label">Supplier</label>
                    <select class="select2 form-control" name="supno" id="supno">
                        <option value="" disabled {{ old('supno') ? '' : 'selected' }}>Silahkan pilih Supplier</option>
                        @foreach($supplier as $s)
                            <option
                                value="{{ $s->supno }}"
                                {{ old('supno') == $s->supno ? 'selected' : '' }}>
                                {{ $s->supno }} - {{ $s->supna }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6 mt-3">
                    <label for="bdate_s" class="form-label">Date Start</label><span class="text-danger"> *</span>
                    <input type="date" class="form-control" name="bdate_s" id="bdate_s" value="{{ old('bdate_s', $bdate_s) }}" required>
                </div>

                <div class="col-md-6 mt-3">
                    <label for="bdate_e" class="form-label">Date End</label><span class="text-danger"> *</span>
                    <input type="date" class="form-control" name="bdate_e" id="bdate_e" value="{{ old('bdate_e', $bdate_e) }}" required>
                </div>

                <div class="col-md-6 mt-3">
                    <label for="po_formc" class="form-label">PO Form Code</label>
                    <select name="po_formc" id="po_formc" class="form-control select2">
                        <option value="" disabled {{ old('po_formc') ? '' : 'selected' }}>Silahkan Pilih PO Form Code</option>
                        <option value="PO" {{ old('po_formc') == 'PO' ? 'selected' : '' }}>PO</option>
                        <option value="PI" {{ old('po_formc') == 'PI' ? 'selected' : '' }}>PI</option>
                        <option value="PN" {{ old('po_formc') == 'PN' ? 'selected' : '' }}>PN</option>
                    </select>
                </div>

                <div class="col-md-6 mt-3">
                    <label for="sort_by" class="form-label">Sort By</label>
                    <select name="sort_by" id="sort_by" class="form-control select2">
                        <option value="pono" selected {{ old('sort_by') == 'pono' ? 'selected' : '' }}>PO No</option>
                        <option value="prona" {{ old('sort_by') == 'prona' ? 'selected' : '' }}>Product Name</option>
                        <option value="supplier" {{ old('sort_by') == 'supplier' ? 'selected' : '' }}>Supplier</option>
                    </select>
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
                const bdate_s = document.getElementById('bdate_s').value.trim();
                const bdate_e = document.getElementById('bdate_e').value.trim();
                const po_status = document.getElementById('po_status').value;
                const supno = document.getElementById('supno').value;
                const po_formc = document.getElementById('po_formc').value;
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
                    po_status: po_status,
                    supno: supno,
                    po_formc: po_formc,
                    sort_by: sort_by
                });

                window.open("{{ route('outstandingpo.preview') }}?" + params.toString(), "_blank");
            });
        </script>
@endpush
@endsection