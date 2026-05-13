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
                </div>

                <div class="row">
                    <div class="col-md-6 mt-3">
                        <label for="outs" class="form-label">Outstanding Only</label>
                        <select name="outs" id="outs" class="form-control select2">
                            <option value="" disabled selected>Silahkan Pilih Outstanding Only</option>
                            <option value="Yes">Yes</option>
                            <option value="No">No</option>
                        </select>
                    </div>
                    <div class="col-md-6 mt-3">
                        <label for="cusno" class="form-label">Customer</label><span class="text-danger"> *</span>
                        <select name="cusno" id="cusno" class="form-control select2" required>
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
                const cusno = $('#cusno').val();
                
                if (!cusno) {
                    Swal.fire({ icon: 'warning', title: 'Oops...', text: 'Pilih Customer Terlebih Dahulu!' });
                    return;
                }

                let params = new URLSearchParams({
                    braco: document.getElementById('braco').value,
                    outs: $('#outs').val() || '',
                    cusno: cusno || '',
                });

                window.open("{{ route('cust_trans_history.preview') }}?" + params.toString(), "_blank");
            });
        </script>
    @endpush
@endsection