@extends('layout.main')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/global.css') }}">
@endpush

@section('container')
    <main id="main" class="main">
        <div class="d-flex justify-content-between align-items-center">
            <div class="pagetitle">
                <h1>Print Data Outstanding Payment</h1>
                <nav>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                        <li class="breadcrumb-item active">Print Data Outstanding Payment</li>
                    </ol>
                </nav>
            </div>
        </div>

        <section class="section">
            <form id="form-outstanding-payment" action="" method="POST">
                @csrf

                <div class="row">
                    <div class="col-md-6 mt-3">
                        <label for="braco" class="form-label">Branch</label><span class="text-danger"> *</span>
                        <input type="text" class="form-control" name="braco" id="braco"
                            value="{{ old('braco') ? old('braco') : auth()->user()->cabang }}" disabled required>
                    </div>

                    <div class="col-md-6 mt-3">
                        <label for="supno" class="form-label">Supplier</label>
                        <select class="form-control select2" name="supno" id="supno">
                            <option value="">Select Supplier</option>
                            @foreach ($suppliers as $sup)
                                <option value="{{ trim($sup->supno) }}" {{ old('supno') == trim($sup->supno) ? 'selected' : '' }}>
                                    {{ trim($sup->supno) }} - {{ trim($sup->supna) }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="mt-3 d-flex justify-content-end">
                    <button type="button" id="previewOutstandingPayment" class="btn btn-primary">Preview Data</button>
                </div>
            </form>
        </section>
    </main>

    @push('scripts')
        <script>
            $(document).ready(function () {
                $('.select2').select2({
                    width: '100%',
                    theme: 'bootstrap-5',
                    placeholder: 'Select Supplier',
                    allowClear: true
                });
            });

            document.getElementById('previewOutstandingPayment').addEventListener('click', function () {
                const supno = document.getElementById('supno').value.trim();
                const braco = document.getElementById('braco').value;

                let params = new URLSearchParams({
                    braco: braco,
                    supno: supno,
                });

                window.open("{{ route('outstanding_ap_payment.preview') }}?" + params.toString(), "_blank");
            });
        </script>
    @endpush
@endsection