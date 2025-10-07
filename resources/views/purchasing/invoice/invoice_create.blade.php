@extends('layout.main')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/purchasing/invoice.css') }}">
@endpush

@section('container')
    <main id="main" class="main">
        <div class="pagetitle">
            <h1>Tambah Data Invoice</h1>
            <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('invoice.index') }}">List Invoice</a></li>
                <li class="breadcrumb-item active">Invoice Create</li>
            </ol>
            </nav>
        </div>

        <section class="section">
            <form id="form-invoice" action="{{ route('invoice.store') }}" method="POST">
                @csrf
                <div class="d-flex justify-content-center align-items-center">
                    <div class="col-md-6 mt-3">
                        <label for="potype">PO Type</label>
                        <select class="form-control select2" id="potype">
                            <option value="" disabled {{ old('potype') ? '' : 'selected' }}>Silahkan pilih PO Type</option>
                            <option value="PO">Lokal</option>
                            <option value="PN">Inventaris</option>
                            <option value="PI">Import</option>
                        </select>
                    </div>
                </div>

                <div id="content-import" style="display:none;">
                    @include('purchasing.invoice.partial.invoice_create_import')
                </div>

                <div id="content-loc-inv" style="display:none;">
                    @include('purchasing.invoice.partial.invoice_create_loc_inv')
                </div>

                <div class="mt-3 d-flex justify-content-between">
                    <a href="{{ route('blawb.index') }}" class="btn btn-secondary">Kembali</a>
                    <button type="submit" class="btn btn-primary">Simpan Data</button>
                </div>
            </form>
        </section>
    </main>

    @push('scripts')
        {{-- menampilkan form Invoice berdasarkan pilihan potype --}}
        <script>
            $(document).ready(function() {
                // Fungsi untuk menampilkan/menyembunyikan konten berdasarkan pilihan PO Type
                function toggleInvoiceContent() {
                    const poType = $('#potype').val();
                    const importContent = $('#content-import');
                    const locInvContent = $('#content-loc-inv');

                    if (poType === 'PI') {
                        importContent.fadeIn();
                        locInvContent.fadeOut();
                    } else if (poType === 'PO' || poType === 'PN') {
                        locInvContent.fadeIn();
                        importContent.fadeOut();
                    } else {
                        // Sembunyikan keduanya jika belum ada yang dipilih
                        importContent.slideUp();
                        locInvContent.slideUp();
                    }
                }

                $('#potype').on('change', function() {
                    toggleInvoiceContent();
                });

                toggleInvoiceContent();
            });
        </script>

        {{-- price input --}}
        <script>
            document.addEventListener("DOMContentLoaded", function() {
                const currencySelect = $('.currency-selector');

                const localeMap = {
                    IDR: 'id-ID',
                    USD: 'en-US',
                    EUR: 'de-DE',
                    GBP: 'en-GB',
                    MYR: 'ms-MY',
                    SGD: 'en-SG',
                    CHF: 'de-CH'
                };

                function formatCurrency(value, currencyCode) {
                    if (!value) return '';
                    const number = Number(String(value).replace(/[^\d]/g, ''));
                    if (isNaN(number)) return value;
                    const locale = localeMap[currencyCode] || 'id-ID';
                    return new Intl.NumberFormat(locale, {
                        style: 'currency',
                        currency: currencyCode,
                        minimumFractionDigits: 0
                    }).format(number);
                }

                // fungsi untuk reformat semua input .currency
                function reformatAll() {
                    const selectedCurrency = currencySelect.val() || 'IDR';
                    document.querySelectorAll('.currency').forEach(function(input) {
                        const rawValue = input.value.replace(/[^\d]/g, '');
                        if (rawValue) {
                            input.value = formatCurrency(rawValue, selectedCurrency);
                        }
                    });
                }

                // langsung format ketika ketik
                $(document).on('input', '.currency', function(e) {
                    const selectedCurrency = currencySelect.val() || 'IDR';
                    const rawValue = e.target.value.replace(/[^\d]/g, '');
                    e.target.value = rawValue ? formatCurrency(rawValue, selectedCurrency) : '';
                });

                // format ulang ketika user ganti mata uang
                currencySelect.on('select2:select change', reformatAll);

                // langsung format semua input (termasuk dari DB/old())
                reformatAll();

                // kalau ada elemen baru dimasukkan ke DOM (misal tambah invoice detail)
                const observer = new MutationObserver(() => reformatAll());
                observer.observe(document.body, { childList: true, subtree: true });

                // hapus format sebelum submit
                document.querySelector('form').addEventListener('submit', function() {
                    document.querySelectorAll('.currency').forEach(function(input) {
                        input.value = input.value.replace(/[^\d]/g, '');
                    });
                });
            });
        </script>

        {{-- Ambil PO berdasarkan Supplier --}}
        <script>
            $(document).ready(function () {
                $(document).on('change', 'select[name="supno[]"]', function () {
                    const supno = $(this).val();

                    // cari container form aktif (import atau lokal)
                    const formSection = $(this).closest('#content-import').length ? '#content-import' : '#content-loc-inv';
                    const $pono = $(formSection).find('select[name="pono[]"]');

                    if (!supno) return;

                    $pono.html('<option value="">Loading...</option>');

                    $.ajax({
                        url: `/get-po-by-supplier/${supno}`,
                        type: 'GET',
                        dataType: 'json',
                        success: function (response) {

                            if (response.success && response.data.length > 0) {
                                let options = '<option value="" disabled selected>Pilih No. PO</option>';
                                response.data.forEach(po => {
                                    options += `<option value="${po.pono}">${po.pono}</option>`;
                                });
                                $pono.html(options).trigger('change.select2');
                            } else {
                                $pono.html('<option value="">Tidak ada PO untuk supplier ini</option>');
                            }
                        },
                        error: function (xhr) {
                            $pono.html('<option value="">Gagal memuat data PO</option>');
                        }
                    });
                });

                // Ambil Barang berdasarkan PONO
                $(document).on('change', 'select[name="pono[]"]', function () {
                    const selectedPo = $(this).val();
                    const formContainer = $(this).closest('.accordion-body');
                    const $opron = formContainer.find('select[name="opron[]"]');

                    if (!selectedPo) return;

                    $opron.html('<option value="">Loading...</option>');

                    $.ajax({
                        url: `/get-items-by-po/${selectedPo}`,
                        type: 'GET',
                        dataType: 'json',
                        success: function (res) {
                            $opron.empty();

                            if (res.success && res.data.length > 0) {
                                $opron.append('<option value="" disabled selected>Pilih Barang</option>');
                                res.data.forEach(item => {
                                    $opron.append(
                                        `<option value="${item.opron}" 
                                            data-qty="${item.poqty}" 
                                            data-price="${item.netpr}"
                                            data-stdqu="${item.stdqu}">
                                            ${item.opron} - ${item.prona}
                                        </option>`
                                    );
                                });
                            } else {
                                $opron.html('<option value="">Tidak ada barang untuk PO ini</option>');
                            }
                        },
                        error: function (xhr) {
                            $opron.html('<option value="">Gagal memuat data barang</option>');
                        }
                    });
                });

                // ambil qty & harga otomatis
                $(document).on('change', 'select[name="opron[]"]', function () {
                    const selectedOption = $(this).find(':selected');
                    const qty = selectedOption.data('qty') || '';
                    const stdqu = selectedOption.data('stdqu') || '';
                    const price = selectedOption.data('price') || '';

                    const parent = $(this).closest('.accordion-body');
                    parent.find('input[name="poqty[]"]').val(qty);
                    parent.find('.unit-label').text(stdqu);
                    parent.find('input[name="netpr[]"]').val(price);
                    parent.find('.stdqu-input').val(stdqu);
                });

                // ubah judul accordion jadi opron - prona
                $(document).on('change', 'select[name="opron[]"]', function () {
                    const selectedOption = $(this).find(':selected');
                    const opron = selectedOption.val() || '';
                    const prona = selectedOption.text().split(' - ')[1] || '';
                    const newLabel = opron && prona ? `${opron} - ${prona}` : 'Line';

                    const parentItem = $(this).closest('.accordion-item');
                    const headerButton = parentItem.find('.accordion-button');

                    // update teks header
                    headerButton.contents().filter(function () {
                        return this.nodeType === 3;
                    }).first().replaceWith(` ${newLabel}`);
                });
            });
        </script>

        {{-- ambil data bm dari hsn --}}
        <script>
            $(document).on('change', '.hsn-select', function () {
                const selectedBM = $(this).find(':selected').data('bm') || '';
                $(this).closest('.accordion-body').find('input[name="bm[]"]').val(selectedBM);
            });
        </script>

        {{-- saat submit matikan validasi form lain --}}
        <script>
            function toggleInvoiceContent() {
                const poType = $('#potype').val();
                const importContent = $('#content-import');
                const locInvContent = $('#content-loc-inv');

                if (poType === 'PI') {
                    // tampilkan import, sembunyikan lokal/inventaris
                    importContent.fadeIn();
                    locInvContent.fadeOut();

                    // aktifkan input di import
                    importContent.find(':input').prop('disabled', false);
                    // nonaktifkan input di local/inv
                    locInvContent.find(':input').prop('disabled', true);
                } 
                else if (poType === 'PO' || poType === 'PN') {
                    // tampilkan lokal/inventaris, sembunyikan import
                    locInvContent.fadeIn();
                    importContent.fadeOut();

                    // aktifkan input di local/inv
                    locInvContent.find(':input').prop('disabled', false);
                    // nonaktifkan input di import
                    importContent.find(':input').prop('disabled', true);
                } 
                else {
                    // jika belum pilih po type
                    importContent.hide();
                    locInvContent.hide();

                    // disable semua biar gak tervalidasi
                    importContent.find(':input').prop('disabled', true);
                    locInvContent.find(':input').prop('disabled', true);
                }
            }

            // jalankan saat document siap & saat pilihan berubah
            $(document).ready(function() {
                toggleInvoiceContent();
                $('#potype').on('change', toggleInvoiceContent);
            });
        </script>

        {{-- Modal Konfirmasi simpan data --}}
        <script>
            $(document).ready(function() {
                const form = document.getElementById('form-invoice');

                form.addEventListener('submit', function (e) {
                    e.preventDefault();

                    if (!form.checkValidity()) {
                        form.classList.add('was-validated');
                        return;
                    }

                    Swal.fire({
                        title: 'Konfirmasi Simpan',
                        text: 'Apakah Anda yakin ingin menyimpan data ini?',
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
                                icon: 'info',
                                showConfirmButton: false,
                                allowOutsideClick: false,
                                allowEscapeKey: false,
                                didOpen: () => {
                                    Swal.showLoading();
                                    form.submit();
                                }
                            });
                        }
                    });
                });
            });
        </script>
    @endpush

@endsection