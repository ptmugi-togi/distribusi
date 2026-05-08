@extends('layout.main')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/global.css') }}">
@endpush

@section('container')
    <main id="main" class="main">
        <div class="d-flex justify-content-between align-items-center">
            <div class="pagetitle">
            <h1>Print Aging AR By Invoice List</h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item active">Print Aging AR By Invoice List</li>
                </ol>
            </nav>
            </div>
        </div>

        <section class="section">
            <form id="form-aging-ar-by-invoice-list" action="" method="POST">
                @csrf

                <div class="row">
                    <div class="col-md-6 mt-3 mx-auto">
                        <label for="braco" class="form-label">Branch</label><span class="text-danger"> *</span>
                        <input type="text" class="form-control" name="braco" id="braco" value="{{ (old('braco') ? old('braco') : auth()->user()->cabang) }}" disabled required>
                    </div>
                    <div class="col-md-6 mt-3">
                        <label for="asper" class="form-label">As Per</label><span class="text-danger"> *</span>
                        <input type="date" class="form-control" name="asper" id="asper" value="{{ old('asper') }}" required>
                    </div>
                    <div class="col-md-6 mt-3">
                        <label for="formc" class="form-label">Invoice Formcode</label>
                        <select name="formc" id="formc" class="form-control select2">
                            <option value="" disabled selected>Silahkan Pilih Invoice Formcode</option>
                            <option value="SC">SC</option>
                            <option value="SD">SD</option>
                        </select>
                    </div>
                    <div class="col-md-6 mt-3">
                        <label for="cusno" class="form-label">Customer</label>
                        <select name="cusno" id="cusno" class="form-control select2">
                            <option value="" disabled selected>Silahkan Pilih Customer</option>
                            @foreach ($customers as $c)
                                <option value="{{ $c->cusno }}">{{ $c->cusno }} - {{ $c->cusna }}</option>    
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="mt-3 d-flex justify-content-end">
                    <button type="button" id="printAgingArByInvoiceList" class="btn btn-primary">Print Data</button>
                </div>
            </form>
        </section>
    </main>

    @push('scripts')
        <script>
            // Handler untuk tombol Print
            document.getElementById('printAgingArByInvoiceList').addEventListener('click', function () {
                const asper = document.getElementById('asper').value;
                const cusno = $('#cusno').val();
                
                if (!asper) {
                    Swal.fire({ icon: 'warning', title: 'Oops...', text: 'Pilih Tanggal Terlebih Dahulu!' });
                    return;
                }

                let params = new URLSearchParams({
                    braco: document.getElementById('braco').value,
                    asper: asper,
                    formc: $('#formc').val() || '',
                    cusno: cusno || '',
                });

                window.open("{{ route('aging_ar_by_invoice.preview') }}?" + params.toString(), "_blank");
            });
        </script>
    @endpush
@endsection