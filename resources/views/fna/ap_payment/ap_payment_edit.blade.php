@extends('layout.main')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/global.css') }}">
@endpush

@section('container')
    <main id="main" class="main">
        <div class="d-flex justify-content-between align-items-center">
            <div class="pagetitle">
                <h1>Edit Data AP Payment ({{ $header->formc }} - {{ $header->vcrno }})</h1>
                <nav>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('ap_payment.index') }}">List AP Payment</a></li>
                        <li class="breadcrumb-item active">AP Payment Edit</li>
                    </ol>
                </nav>
            </div>
        </div>

        @if (session()->has('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <section class="section">
            <form id="form-ap-payment" action="{{ route('ap_payment.update', $header->apid) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="row">
                    <div class="col-md-6 mt-3">
                        <label for="pdate" class="form-label">Payment Date</label>
                        <input type="date" class="form-control" id="pdate" value="{{ \Carbon\Carbon::parse($header->pdate)->format('Y-m-d') }}" disabled>
                    </div>

                    <div class="col-md-6 mt-3">
                        <label for="supno" class="form-label">Supplier</label>
                        <input type="text" class="form-control" id="supplier" value="{{ $header->supno }} - {{ $header->supplier->supna }}" disabled>
                        <input type="hidden" class="form-control" id="supno" value="{{ $header->supno }}" disabled>
                    </div>

                    <div class="col-md-6 mt-3">
                        <label for="curco" class="form-label">Currency Code (Payment)</label>
                        <input type="text" class="form-control" id="currency_code_display" value="{{ $header->curco }}" disabled>
                    </div>

                    <div class="col-md-6 mt-3">
                        <label for="crate" class="form-label">Currency Rate</label>
                        <input type="text" class="form-control" id="currency_rate_display" value="{{ 'Rp ' . number_format($header->payrate, 2, ',', '.') }}" disabled>
                    </div>

                    <div class="col-md-6 mt-3">
                        <label for="tpayment" class="form-label">Total Payment (IDR)</label>
                        <input type="text" class="form-control" id="tpayment_display" value="{{ 'Rp ' . number_format(old('tpayment', $header->tpayment), 2, ',', '.') }}" disabled>
                        <input type="hidden" name="tpayment" id="tpayment_raw" value="{{ old('tpayment', $header->tpayment) }}">
                    </div>

                    <div class="col-md-6 mt-3">
                        <label for="tpayment_detail" class="form-label">Total Payment Detail (IDR)</label>
                        <input type="text" class="form-control" id="tpayment_detail_display" value="{{ 'Rp ' . number_format(old('tpayment_detail', $header->tpayment_detail), 2, ',', '.') }}" disabled>
                        <input type="hidden" name="tpayment_detail" id="tpayment_detail_raw" value="{{ old('tpayment_detail', $header->tpayment_detail) }}">
                    </div>

                    <div class="col-md-6 mt-3">
                        <label for="glacc" class="form-label">Bank</label>
                        <input type="text" class="form-control" id="bank_display" value="{{ $header->glacc }} - {{ $header->bankname }}" disabled>
                    </div>

                    <div class="col-md-12 mt-3">
                        <label class="form-label">Notes</label>
                        <textarea class="form-control" name="noteh" id="noteh" maxlength="200">{{ old('noteh', $header->noteh) }}</textarea>
                        <div class="form-text text-danger text-end" style="font-size:0.7rem;">Maksimal 200 karakter</div>
                    </div>
                </div>

                <div class="detail my-3">
                    @include('fna.ap_payment.partial_edit.ap_payment_edit_detail')
                </div>

                <div class="mt-3 d-flex justify-content-between">
                    <a href="{{ route('ap_payment.index') }}" class="btn btn-secondary">Kembali</a>
                    <button type="submit" class="btn btn-primary">Update Data</button>
                </div>
            </form>
        </section>
    </main>

    @push('scripts')
        <script>
            window.supplierInvoices = [];

            $(document).ready(function() {
                $('.select2').select2({ width: '100%', theme: 'bootstrap-5' });

                const supno = $('#supno').val();
                if (supno) {
                    fetchSupplierInvoices(supno, true);
                }
            });

            window.formatIDR = function(val) {
                if (val === '' || val === null || isNaN(val)) return '';
                return new Intl.NumberFormat('id-ID', {
                    style: 'currency',
                    currency: 'IDR',
                    minimumFractionDigits: 2,
                    maximumFractionDigits: 2
                }).format(val);
            };

            window.formatMoney = function(val, currency) {
                if (val === '' || val === null || isNaN(val)) return '';
                const locale = (currency === 'IDR') ? 'id-ID' : 'en-US';
                return new Intl.NumberFormat(locale, {
                    style: 'currency',
                    currency: currency || 'IDR',
                    minimumFractionDigits: 2,
                    maximumFractionDigits: 2
                }).format(val);
            };

            document.addEventListener("DOMContentLoaded", function() {
                const form = document.getElementById('form-ap-payment');
                form.addEventListener('submit', function (e) {
                    e.preventDefault();
                    Swal.fire({
                        title: 'Konfirmasi Update',
                        text: 'Apakah Anda yakin ingin memperbarui data AP Payment ini?',
                        icon: 'question',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Ya, Update!',
                        cancelButtonText: 'Batal'
                    }).then((res) => {
                        if (res.isConfirmed) {
                            Swal.fire({
                                title: 'Memperbarui...',
                                text: 'Mohon tunggu sebentar',
                                icon: 'info',
                                showConfirmButton: false,
                                allowOutsideClick: false,
                                allowEscapeKey: false,
                                didOpen: () => Swal.showLoading()
                            });
                            form.submit();
                        }
                    });
                });
            });
        </script>
    @endpush
@endsection