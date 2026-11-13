@extends('layout.main')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/global.css') }}">
@endpush

@section('container')
    <main id="main" class="main">
        <div class="d-flex justify-content-between align-items-center">
            <div class="pagetitle">
                <h1>Tambah Data Garantie Card Release</h1>
                <nav>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('gr.index') }}">List Garantie Card Release</a></li>
                        <li class="breadcrumb-item active">Garantie Card Release Create</li>
                    </ol>
                </nav>
            </div>
            <div class="card">
                <h5 class="p-2 mb-0"><b>Branch : {{ auth()->user()->cabang }}</b></h5>
            </div>
        </div>
        @if (session()->has('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <section class="section">
            <form id="form-gr" action="{{ route('gr.store') }}" method="POST">
                @csrf

                <div class="row">
                    <input type="hidden" name="braco" id="braco" value="{{ auth()->user()->cabang }}">
                    <input type="hidden" name="formc" id="formc-store" value="GR">
                    
                    <div class="col-md-6 mt-3">
                        <label for="do" class="form-label">DO</label><span class="text-danger"> *</span>
                        <select name="do" id="do" class="form-control select2" required disabled>
                            <option value="" disabled selected>Memuat data DO...</option>
                        </select>
                        <input type="hidden" name="dofcd" id="dofcd" value="{{ old('dofcd') }}">
                        <input type="hidden" name="donom" id="donom" value="{{ old('donom') }}">
                    </div>

                    <div class="col-md-6 mt-3">
                        <label for="dodat" class="form-label">DO Date (DD/MM/YYYY)</label>
                        <input class="form-control" name="dodat" id="dodat" value="{{ old('dodat') }}" readonly style="background-color:#e9ecef">
                    </div>

                    <div class="col-md-6 mt-3">
                        <label for="cusno" class="form-label">Customer</label>
                        <input type="text" class="form-control" name="customer" id="customer" value="{{ old('customer') }}" disabled>
                        <input type="hidden" class="form-control" name="cusno" id="cusno" value="{{ old('cusno') }}" readonly style="background-color:#e9ecef">
                        <input type="hidden" class="form-control" name="cusna" id="cusna" value="{{ old('cusna') }}" readonly style="background-color:#e9ecef">
                    </div>

                    <div class="col-md-6 mt-3">
                        <label for="phone" class="form-label">Phone / Contact Person</label>
                        <input type="text" class="form-control" name="phone" id="phone" value="{{ old('phone') }}" readonly style="background-color:#e9ecef">
                    </div>
                    
                    <div class="col-md-12 mt-3">
                        <label for="address" class="form-label">Address</label>
                        <input type="hidden" class="form-control" name="shpto" id="shpto" value="{{ old('shpto') }}" readonly style="background-color:#e9ecef">
                        <textarea class="form-control" name="address" id="address" rows="2" disabled>{{ old('address') }}</textarea>
                    </div>

                    <div class="col-md-6 mt-3">
                        <label for="gmcfr" class="form-label">Garantie Period (MM/DD/YYYY)</label>
                        <div class="d-flex align-items-center gap-2">
                            <input type="date" class="form-control" name="gmcfr" id="gmcfr" value="{{ old('gmcfr') }}">
                            <span class="fw-bold fs-5">+</span>
                            <input type="number" class="form-control text-center" name="grdays" id="grdays" value="365" style="max-width: 100px;">
                            <span class="text-nowrap">days =</span>
                            <input type="date" class="form-control" name="gmcto" id="gmcto" value="{{ old('gmcto') }}" readonly style="background-color: #e9ecef;">
                        </div>
                    </div>

                    <div class="col-md-6 mt-3">
                        <label for="teraf" class="form-label">Tera Period (MM/DD/YYYY)</label>
                        <div class="d-flex align-items-center gap-2">
                            <input type="date" class="form-control" name="teraf" id="teraf" value="{{ old('teraf') }}">
                            <span class="fw-bold fs-5">+</span>
                            <input type="number" class="form-control text-center" name="trdays" id="trdays" value="365" style="max-width: 100px;">
                            <span class="text-nowrap">days =</span>
                            <input type="date" class="form-control" name="terat" id="terat" value="{{ old('terat') }}" readonly style="background-color: #e9ecef;">
                        </div>
                    </div>

                    <div class="col-md-6 mt-3">
                        <label for="terap" class="form-label">Print Tera Period as</label>
                        <input type="text" class="form-control" name="terap" id="terap" value="{{ old('terap') }}">
                    </div>

                    <div class="col-md-6 mt-3">
                        <label for="terac" class="form-label">Tera Certificate</label>
                        <input type="text" class="form-control" name="terac" id="terac" value="{{ old('terac') }}">
                    </div>
                    
                    <div class="col-md-12 mt-3">
                        <label for="noteh" class="form-label">Notes</label>
                        <textarea class="form-control" name="noteh" id="noteh" rows="2">{{ old('noteh') }}</textarea>
                    </div>
                </div>

                <div class="col-md-12 mt-4" id="detail-container" style="display:none">
                    <label class="form-label fw-bold">Pilih Produk untuk Garansi</label>
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped" id="detail-table">
                            <thead class="table-light">
                                <tr>
                                    <th class="text-center" style="width: 50px;">
                                        <input type="checkbox" id="check-all" class="form-check-input">
                                    </th>
                                    <th>Product No</th>
                                    <th>Product Name</th>
                                    <th>Serial No</th>
                                </tr>
                            </thead>
                            <tbody id="detail-tbody">
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="mt-4 d-flex justify-content-between">
                    <a href="{{ route('maintenance_contract.index') }}" class="btn btn-secondary">Kembali</a>
                    <button type="submit" class="btn btn-primary">Simpan Data</button>
                </div>
            </form>
        </section>
    </main>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            setTimeout(function() {
                loadDo();
            }, 300);

            function addDaysToDateString(dateStr, daysToAdd) {
                if (!dateStr) return '';
                let parts = dateStr.split('-');
                if (parts.length !== 3) return '';

                let d = new Date(Date.UTC(parts[0], parts[1] - 1, parts[2]));
                d.setUTCDate(d.getUTCDate() + parseInt(daysToAdd || 0));

                let yyyy = d.getUTCFullYear();
                let mm = String(d.getUTCMonth() + 1).padStart(2, '0');
                let dd = String(d.getUTCDate()).padStart(2, '0');

                return `${yyyy}-${mm}-${dd}`;
            }

            function calculateGmcTo() {
                let startDateVal = $('#gmcfr').val();
                let daysToAdd = $('#grdays').val();
                $('#gmcto').val(addDaysToDateString(startDateVal, daysToAdd));
            }

            function calculateTeraTo() {
                let startDateVal = $('#teraf').val();
                let daysToAdd = $('#trdays').val();
                $('#terat').val(addDaysToDateString(startDateVal, daysToAdd));
            }

            function loadDo() {
                let doSelect = $('#do');

                $.get("{{ route('gr.get-do') }}", function(data) {
                    doSelect.empty();
                    doSelect.append('<option value="" disabled selected>Pilih Delivery Order</option>');

                    if (data.results && data.results.length > 0) {
                        data.results.forEach(function(item) {
                            doSelect.append(`
                                <option value="${item.id}"
                                    data-formc="${item.formc}"
                                    data-trano="${item.trano}"
                                    data-dodate="${item.dodate}"
                                    data-dodate-fmt="${item.dodate_formatted}"
                                    data-cusno="${item.cusno ?? ''}"
                                    data-cusna="${item.cusna ?? ''}"
                                    data-customer="${item.customer ?? ''}"
                                    data-shpto="${item.shpto ?? ''}"
                                    data-address="${item.address ?? ''}"
                                    data-phone="${item.phone ?? ''}">
                                    ${item.text}
                                </option>`);
                        });
                    }

                    doSelect.prop('disabled', false);

                    doSelect.select2({ 
                        width: '100%', 
                        theme: 'bootstrap-5' 
                    });
                }).fail(function() {
                    alert('Gagal mengambil data DO.');
                });
            }

            $(document).on('change', '#do', function() {
                let selectedOption = $(this).find(':selected');
                let bbkVal    = $(this).val() || '';
                let doDat     = selectedOption.data('dodate') || '';
                let doDatFmt  = selectedOption.data('dodate-fmt') || doDat;
                let formcVal  = selectedOption.data('formc') || '';
                let doVal     = selectedOption.data('trano') || '';
                let customer  = selectedOption.data('customer') || '';
                let cusnoVal  = selectedOption.data('cusno') || '';
                let cusnaVal  = selectedOption.data('cusna') || '';
                let shptoVal  = selectedOption.data('shpto') || '';
                let address   = selectedOption.data('address') || '';
                let phone     = selectedOption.data('phone') || '';

                $('#dofcd').val(formcVal);
                $('#dodat').val(doDatFmt);
                $('#donom').val(doVal);
                $('#cusno').val(cusnoVal);
                $('#cusna').val(cusnaVal);
                $('#customer').val(customer);
                $('#shpto').val(shptoVal);
                $('#address').val(address);
                $('#phone').val(phone);
                
                $('#gmcfr').val(doDat);
                $('#teraf').val(doDat);

                calculateGmcTo();
                calculateTeraTo();

                if (bbkVal) {
                    fetchDetailDo(bbkVal);
                }
            });

            $(document).on('change input', '#gmcfr, #grdays', function() {
                calculateGmcTo();
            });

            $(document).on('change input', '#teraf, #trdays', function() {
                calculateTeraTo();
            });

            function fetchDetailDo(doId) {
                let url = "{{ route('gr.get-detail-do', ':do') }}".replace(':do', encodeURIComponent(doId));
                let tbody = $('#detail-tbody');
                tbody.empty();
                $('#check-all').prop('checked', false);

                $.get(url, function(items) {
                    if (items.length > 0) {
                        items.forEach(function(item, index) {
                            let lotNo = item.lotno || '-';

                            let row = `
                                <tr>
                                    <td class="text-center">
                                        <input type="checkbox" name="items[${index}][selected]" value="1" class="form-check-input item-checkbox">
                                        <input type="hidden" name="items[${index}][opron]" value="${item.opron}">
                                        <input type="hidden" name="items[${index}][prona]" value="${item.prona}">
                                        <input type="hidden" name="items[${index}][lotno]" value="${lotNo}">
                                    </td>
                                    <td>${item.opron}</td>
                                    <td>${item.prona}</td>
                                    <td>${lotNo}</td>
                                </tr>
                            `;
                            tbody.append(row);
                        });

                        $('#detail-container').fadeIn();
                    } else {
                        $('#detail-container').hide();
                        alert('Detail item tidak ditemukan untuk DO ini.');
                    }
                }).fail(function() {
                    $('#detail-container').hide();
                    alert('Gagal mengambil detail DO.');
                });
            }

            $(document).on('change', '#check-all', function() {
                $('.item-checkbox').prop('checked', $(this).is(':checked'));
            });

            $(document).on('change', '.item-checkbox', function() {
                if (!$(this).is(':checked')) {
                    $('#check-all').prop('checked', false);
                } else if ($('.item-checkbox:checked').length === $('.item-checkbox').length) {
                    $('#check-all').prop('checked', true);
                }
            });

            $('#form-gr').on('submit', function(e) {
                e.preventDefault();
                let form = this;

                let checkedCount = $('.item-checkbox:checked').length;
                if (checkedCount === 0) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Produk Belum Dipilih',
                        text: 'Silakan centang minimal satu produk terlebih dahulu.',
                        confirmButtonColor: '#3085d6'
                    });
                    return;
                }

                Swal.fire({
                    title: 'Simpan Data?',
                    text: `Anda akan memproses ${checkedCount} item untuk Garantie Card Release.`,
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Ya, Simpan!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        Swal.fire({
                            title: 'Menyimpan...',
                            text: 'Mohon tunggu sebentar',
                            allowOutsideClick: false,
                            didOpen: () => {
                                Swal.showLoading();
                            }
                        });

                        form.submit();
                    }
                });
            });
        });
    </script>
@endpush