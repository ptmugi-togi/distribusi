@extends('layout.main')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/global.css') }}">
@endpush

@section('container')
<main id="main" class="main">
    <div class="d-flex justify-content-between align-items-center">
        <div class="pagetitle">
            <h1>Tambah Data AP Payment</h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('ap_payment.index') }}">List AP Payment</a></li>
                    <li class="breadcrumb-item active">AP Payment Create</li>
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
        <form id="form-ap-payment" action="{{ route('ap_payment.store') }}" method="POST">
            @csrf

            <div class="row">
                <input type="text" class="form-control" id="braco" name="braco" value="{{ auth()->user()->cabang }}" hidden>
                <input type="text" class="form-control" id="formc" name="formc" value="PT" hidden>

                <div class="col-md-6 mt-3">
                    <label for="pdate" class="form-label">Payment Date</label><span class="text-danger"> *</span>
                    <input type="date" class="form-control" name="pdate" id="pdate" value="{{ old('pdate') }}" min="{{ $minDate }}" required>
                    <input type="hidden" name="priod" id="priod" value="{{ old('priod', '') }}">
                </div>
                
                <div class="col-md-6 mt-3">
                    <label for="supno" class="form-label">Supplier</label><span class="text-danger"> *</span>
                    <select name="supno" id="supno" class="form-control select2" required>
                        <option value="" disabled {{ old('supno') ? '' : 'selected' }}>Select Supplier</option>
                        @foreach ($supplier as $sup)
                            <option value="{{ $sup->supno }}" {{ old('supno') == $sup->supno ? 'selected' : '' }}>
                                {{ $sup->supno }} - {{ $sup->supna }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-6 mt-3">
                    <label for="curco" class="form-label">Currency Code (Payment)</label><span class="text-danger"> *</span>
                    <select name="curco" id="curco" class="form-control select2">
                        <option value="" disabled {{ old('curco') ? '' : 'selected' }}>Select Currency</option>
                        @foreach ($currency as $curr)
                            <option value="{{ $curr->curco }}" {{ old('curco', 'IDR') == $curr->curco ? 'selected' : '' }}>
                                {{ $curr->curco }} - {{ $curr->desc_curco }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-6 mt-3">
                    <label for="crate" class="form-label">Currency Rate</label><span class="text-danger"> *</span>
                    <input type="text" class="form-control" name="crate_d" id="currency_rate_display" value="{{ old('crate_d') }}" required readonly style="background-color:#e9ecef">
                    <input type="text" class="form-control" name="crate" id="currency_rate" value="{{ old('crate') }}" required hidden>
                </div>

                <div class="col-md-6 mt-3">
                    <label for="tpayment" class="form-label">Total Payment (IDR)</label>
                    <input type="text" class="form-control" id="tpayment_display" value="{{ old('tpayment') ? number_format(old('tpayment'), 2, ',', '.') : '' }}" readonly style="background-color:#e9ecef;">
                    <input type="text" name="tpayment" id="tpayment_raw" value="{{ old('tpayment') }}" hidden>
                </div>

                <div class="col-md-6 mt-3">
                    <label for="tpayment_detail" class="form-label">Total Payment Detail (IDR)</label>
                    <input type="text" class="form-control" id="tpayment_detail_display" value="{{ old('tpayment_detail') ? number_format(old('tpayment_detail'), 2, ',', '.') : '' }}" readonly style="background-color:#e9ecef;">
                    <input type="text" name="tpayment_detail" id="tpayment_detail_raw" value="{{ old('tpayment_detail') }}" hidden>
                </div>

                <div class="col-md-6 mt-3">
                    <label for="glacc" class="form-label">Bank</label><span class="text-danger"> *</span>
                    <select name="glacc" id="glacc" class="form-control select2" required>
                        <option value="" disabled {{ old('glacc') ? '' : 'selected' }}>Select Bank</option>
                        @foreach ($mbank as $bank)
                            <option value="{{ trim($bank->glacc) }}"
                                data-bankname="{{ trim($bank->bankname) }}"
                                {{ old('glacc') == trim($bank->glacc) ? 'selected' : '' }}>
                                {{ trim($bank->glacc) }} - {{ trim($bank->bankname) }}
                            </option>
                        @endforeach
                    </select>
                    <input type="hidden" name="bankname" id="bankname" value="{{ old('bankname') }}">
                </div>

                <div class="col-md-12 mt-3"> 
                    <label class="form-label">Notes</label>
                    <textarea class="form-control" name="noteh" id="noteh" maxlength="200">{{ old('noteh') }}</textarea>
                    <div class="form-text text-danger text-end" style="font-size:0.7rem;">Maksimal 200 karakter</div>
                </div>
            </div>

            <div class="detail my-3">
                @include('fna.ap_payment.partial_create.ap_payment_create_detail')
            </div>

            <div class="mt-3 d-flex justify-content-between">
                <a href="{{ route('ap_payment.index') }}" class="btn btn-secondary">Kembali</a>
                <button type="submit" class="btn btn-primary">Simpan Data</button>
            </div>
        </form>
    </section>
</main>

@push('scripts')
    <script>
        window.supplierInvoices = [];

        $(document).ready(function() {
            $('.select2').select2({ width: '100%', theme: 'bootstrap-5' });

            if (!$('#curco').val()) {
                $('#curco').val('IDR').trigger('change.select2');
            } else {
                $('#curco').trigger('change');
            }

            if ($('#glacc').val()) {
                $('#glacc').trigger('change');
            }
        });

        $('#glacc').on('change', function () {
            const bankname = $(this).find('option:selected').data('bankname') || '';
            $('#bankname').val(bankname);
        });

        $('#pdate').on('change', function(){
            let pdate = $(this).val();
            if(pdate) {
                $('#priod').val(pdate.substring(0, 4) + pdate.substring(5, 7));
            }
        });

        // Fetch kurs header
        $('#curco').on('change', function(){
            const curco = $(this).val();
            if (!curco) return;

            $.ajax({
                url: `/get-currency-rate/${curco}`,
                method: 'GET',
                success: function (response) {
                    if (response.success && !isNaN(parseFloat(response.crate))) {
                        const rate = parseFloat(response.crate);
                        $('#currency_rate').val(rate);
                        $('#currency_rate_display').val(formatIDR(rate));
                    } else {
                        $('#currency_rate').val('');
                        $('#currency_rate_display').val('');
                    }
                },
                error: function () {
                    alert('Gagal mengambil data kurs dari server.');
                }
            });
        });

        // Helper format angka
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

        // SweetAlert confirm submit
        document.addEventListener("DOMContentLoaded", function() {
            const form = document.getElementById('form-ap-payment');
            form.addEventListener('submit', function (e) {
                e.preventDefault();
                Swal.fire({
                    title: 'Konfirmasi Simpan',
                    text: 'Apakah Anda yakin ingin menyimpan data AP Payment ini?',
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Ya, Simpan!',
                    cancelButtonText: 'Batal'
                }).then((res) => {
                    if (res.isConfirmed) {
                        Swal.fire({
                            title: 'Menyimpan...',
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