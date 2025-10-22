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
                        <select class="form-control select2" id="potype" name="potype">
                            <option value="" disabled {{ old('potype') ? '' : 'selected' }}>Silahkan pilih PO Type</option>
                            <option value="PO" {{ old('potype') == 'PO' ? 'selected' : '' }}>Lokal</option>
                            <option value="PN" {{ old('potype') == 'PN' ? 'selected' : '' }}>Inventaris</option>
                            <option value="PI" {{ old('potype') == 'PI' ? 'selected' : '' }}>Import</option>
                        </select>
                    </div>
                </div>

                <div id="content-import" style="display:none;">
                    @include('purchasing.invoice.partial_create.invoice_create_import')
                </div>

                <div id="content-loc-inv" style="display:none;">
                    @include('purchasing.invoice.partial_create.invoice_create_loc_inv')
                </div>

                <div class="mt-3 d-flex justify-content-between">
                    <a href="{{ route('invoice.index') }}" class="btn btn-secondary">Kembali</a>
                    <button type="submit" class="btn btn-primary">Simpan Data</button>
                </div>
            </form>
        </section>
    </main>

    @push('scripts')
        <script>
            let selectedSupplier = null;

            // simpan pilihan supplier 
            $(document).on('change', 'select[name="supno"]', function () {
                selectedSupplier = $(this).val();
            });
        </script>

        {{-- old value --}}
        <script>
            $(document).ready(function () {
                $('.select2').select2({ width: '100%' });

                const oldSupno = @json(old('supno'));
                const oldRinum = @json(old('rinum'));
                const oldPono = @json(old('pono', []));
                const oldOpron = @json(old('opron', []));
                const oldHsn = @json(old('hsn', []));
                const oldPotype = @json(old('potype'));

                let $formSection;

                if (oldPotype === 'PI') {
                    $formSection = $('#content-import');
                } else if (oldPotype === 'PO' || oldPotype === 'PN') {
                    $formSection = $('#content-loc-inv');
                } else {
                    return;
                }

                if (!oldSupno) {
                    return;
                }

                const $supSelect = $('select[name="supno"]');
                $supSelect.val(oldSupno).trigger('change.select2');

                 $.getJSON(`/get-rinum-by-supplier/${oldSupno}`).then(response => {
                    if (response.success && response.data.length > 0) {
                        let rinumOptions = '<option value="">Pilih Receipt Number</option>';
                        response.data.forEach(row => {
                            rinumOptions += `
                                <option value="${row.rinum}" 
                                        data-blnum="${row.blnum}">
                                    RI${row.rinum}
                                </option>`;
                        });
                        const $rinumSelect = $('#rinum-import');
                        $rinumSelect.html(rinumOptions).trigger('change.select2');

                        // kalau ada old rinum → set value dan isi blnum otomatis
                        if (oldRinum) {
                            $rinumSelect.val(oldRinum).trigger('change.select2');
                            const selected = $rinumSelect.find(':selected');
                            $('#blnum-import').val(selected.data('blnum') || '');
                        }
                    }
                });

                $.getJSON(`/get-po-by-supplier/${oldSupno}`).then(async (response) => {

                    if (!response.success || response.data.length === 0) {
                        return;
                    }

                    let poOptions = '<option value="" disabled>Pilih No. PO</option>';
                    response.data.forEach(po => {
                        poOptions += `<option value="${po.pono}">${po.pono}</option>`;
                    });

                    $formSection.find('select[name="pono[]"]').each(function (i) {
                        $(this).html(poOptions).trigger('change.select2');
                    });

                    // Loop setiap baris yang ada old value
                    for (let i = 0; i < oldPono.length; i++) {
                        const pono = oldPono[i];
                        const opron = oldOpron[i];
                        const hsn = oldHsn[i];

                        if (!pono) continue;

                        const $accordion = $formSection.find('.accordion-item').eq(i);
                        const $ponoSelect = $accordion.find('select[name="pono[]"]');
                        const $opronSelect = $accordion.find('select[name="opron[]"]');
                        const $hsnSelect = $accordion.find('select[name="hsn[]"]');

                        $ponoSelect.val(pono).trigger('change.select2');

                        const res = await $.getJSON(`/get-items-by-po/${pono}`);

                        if (res.success && res.data.length > 0) {
                            let opronOptions = '<option value="" disabled>Pilih Barang</option>';
                            res.data.forEach(item => {
                                const sisaQty = item.poqty - item.inqty;
                                if (sisaQty <= 0) {
                                    $opron.append(
                                        `<option value="${item.opron}"
                                            data-qty="${item.poqty}"
                                            data-price="${item.price}"
                                            data-stdqu="${item.stdqu}">
                                            ${item.opron} - ${item.prona}
                                        </option>`
                                    );
                                }
                            };
                            $opronSelect.html(opronOptions).trigger('change.select2');

                            if (opron) {
                                $opronSelect.val(opron).trigger('change'); // pilih value
                                $opronSelect.trigger({
                                    type: 'select2:select',
                                    params: { data: { id: opron, text: $opronSelect.find(':selected').text() } }
                                });
                            }


                            const selected = $opronSelect.find(':selected');
                            const qty = selected.data('qty') || '';
                            const inqty = selected.data('inqty') || '';
                            const stdqu = selected.data('stdqu') || '';
                            const price = selected.data('price') || '';

                            const remainingQty = Math.max(qty - inqty, 0);

                            const $body = $accordion.find('.accordion-body');
                            $body.find('.poqty').val(remainingQty);
                            $body.find('.unit-label').text(stdqu);
                            $body.find('input[name="price[]"]').val(price);
                            $body.find('.stdqu-input').val(stdqu);

                        }
                        if (hsn && $hsnSelect.length) {
                            $hsnSelect.val(hsn).trigger('change.select2');
                        }
                    }
                });
            });
        </script>

        {{-- add invoice lokal inventaris --}}
        @include('purchasing.invoice.partial_create.add_invoice_loc_inv')

        {{-- add invoice import --}}
        @include('purchasing.invoice.partial_create.add_invoice_import')
        
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
            document.addEventListener("DOMContentLoaded", function () {
                const localeMap = {
                    IDR: 'id-ID',
                    USD: 'en-US',
                    EUR: 'de-DE',
                    GBP: 'en-GB',
                    MYR: 'ms-MY',
                    SGD: 'en-SG',
                    CHF: 'de-CH'
                };

                // Fungsi bantu untuk parse angka dari input apapun
                function parseCurrencyString(str) {
                    if (!str) return 0;
                    let clean = String(str).replace(/[^\d.,-]/g, '');

                    // Deteksi format (Eropa vs US)
                    if (clean.includes(',') && clean.includes('.')) {
                        // format Eropa
                        if (clean.lastIndexOf(',') > clean.lastIndexOf('.')) {
                            clean = clean.replace(/\./g, '').replace(',', '.');
                        } else {
                            // format US
                            clean = clean.replace(/,/g, '');
                        }
                    } else if (clean.includes(',')) {
                        // Bisa jadi format lokal
                        const commaCount = (clean.match(/,/g) || []).length;
                        if (commaCount === 1 && clean.indexOf(',') > clean.length - 4) {
                            clean = clean.replace(',', '.');
                        } else {
                            clean = clean.replace(/,/g, '');
                        }
                    } else {
                        clean = clean.replace(/[^\d.-]/g, '');
                    }

                    const number = parseFloat(clean);
                    return isNaN(number) ? 0 : number;
                }

                // format ke tampilan currency sesuai locale
                function formatCurrency(value, currencyCode) {
                    const locale = localeMap[currencyCode] || 'id-ID';
                    const number = parseCurrencyString(value);
                        if (currencyCode === 'SGD') {
                            return new Intl.NumberFormat(locale, {
                                style: 'currency',
                                currency: currencyCode,
                                currencyDisplay: 'code',  // tampil kode "SGD" bukan simbol "$"
                                minimumFractionDigits: 2
                            }).format(number);
                        }
                    return new Intl.NumberFormat(locale, {
                        style: 'currency',
                        currency: currencyCode,
                        minimumFractionDigits: 2
                    }).format(number);
                }

                // ambil kode currency aktif dari section
                function getSelectedCurrency($context) {
                    let currency = $context.find('.currency-selector').val();

                    if (!currency) {
                        currency = $('select[name="curco"]').val() || 'IDR';
                    }

                    return currency;
                }


                // format ulang semua .currency di dalam satu container
                function reformatAll($context) {
                    const selectedCurrency = getSelectedCurrency($context);
                    $context.find('.currency').each(function () {
                        const val = $(this).val();
                        if (val) {
                            $(this).val(formatCurrency(val, selectedCurrency));
                        }
                    });
                }

                // saat user mengetik
                $(document).on('input', '.currency', function (e) {
                    const val = e.target.value;
                    // Hapus semua simbol currency & spasi biar bebas input
                    e.target.value = val.replace(/[^\d.,-]/g, '');
                });

                // saat user keluar dari input → format ke tampilan currency
                $(document).on('blur', '.currency', function () {
                    const $context = $(this).closest('#content-import, #content-loc-inv');
                    const selectedCurrency = getSelectedCurrency($context);
                    const val = $(this).val();
                    if (val) {
                        $(this).val(formatCurrency(val, selectedCurrency));
                    }
                });

                // saat input focus → hapus format dulu (biar user bisa edit angka mentah)
                $(document).on('focus', '.currency', function () {
                    const val = $(this).val();
                    const number = parseCurrencyString(val);
                    $(this).val(number ? number.toString().replace('.', ',') : '');
                });

                // Ganti currency selector → reformat semua
                $(document).on('select2:select change', '.currency-selector', function () {
                    const $context = $(this).closest('#content-import, #content-loc-inv');
                    reformatAll($context);
                });

                // Format semua input saat halaman load
                reformatAll($('#content-import'));
                reformatAll($('#content-loc-inv'));

                // Jika ada elemen baru ditambahkan (misal tambah invoice)
                const observer = new MutationObserver(() => {
                    reformatAll($('#content-import'));
                    reformatAll($('#content-loc-inv'));
                });
                observer.observe(document.body, { childList: true, subtree: true });

                // Expose fungsi kalau dibutuhkan manual
                window.reformatAll = reformatAll;
            });
        </script>

        {{-- Ambil berdasarkan Supplier --}}
        <script>
            $(document).ready(function () {

                // Saat supplier berubah
                $(document).on('change', 'select[name="supno"]', function () {
                    const supno = $(this).val();

                    // tentukan section aktif
                    const $formSection = $(this).closest('#content-import').length ? $('#content-import') : $('#content-loc-inv');
                    const $ponoSelect = $formSection.find('select[name="pono[]"]');
                    const $rinumSelect = $('#rinum-import');
                    const $blnumInput = $('#blnum-import');

                    // reset isi dropdown
                    $rinumSelect.html('<option value="">Loading...</option>');
                    $blnumInput.val('');
                    $ponoSelect.html('<option value="">Loading...</option>');

                    if (!supno) return;

                    if (!supno) {
                        $rinumSelect.html('<option value="">Pilih Supplier Terlebih Dahulu</option>');
                        return;
                    }

                    // ambil RINUM berdasarkan SUPPLIER
                    $.ajax({
                        url: `/get-rinum-by-supplier/${supno}`,
                        type: 'GET',
                        dataType: 'json',
                        success: function(response) {
                            if (response.success && response.data.length > 0) {
                                let options = '<option value="">Pilih Receipt Number</option>';
                                response.data.forEach(row => {
                                    options += `
                                        <option value="${row.rinum}" data-blnum="${row.blnum}">
                                            RI${row.rinum}
                                        </option>`;
                                });
                                $rinumSelect.html(options).trigger('change.select2');
                            } else {
                                $rinumSelect.html('<option value="">Tidak ada Receipt Number untuk supplier ini</option>');
                            }
                        },
                        error: function() {
                            $rinumSelect.html('<option value="">Gagal memuat data Receipt Number</option>');
                        }
                    });

                    $(document).on('change', '#rinum-import', function() {
                        const selected = $(this).find(':selected');
                        const blnum = selected.data('blnum') || '';

                        $('#blnum-import').val(blnum); // isi otomatis BL
                    });

                    // ambil PO berdasarkan SUPPLIER
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
                                $ponoSelect.html(options).trigger('change.select2');
                            } else {
                                $ponoSelect.html('<option value="">Tidak ada PO untuk supplier ini</option>');
                            }
                        },
                        error: function () {
                            $ponoSelect.html('<option value="">Gagal memuat data PO</option>');
                        }
                    });
                });

                // ambil daftar BLNUM dari RINUM yang kepilih
                $(document).on('change', '#rinum-import', function() {
                    const rinum = $(this).val();
                    const $blnumSelect = $('#blnum-import');

                    $blnumSelect.html('<option value="">Loading...</option>');

                    if (!rinum) {
                        $blnumSelect.html('<option value="">Pilih Receipt Number terlebih dahulu</option>');
                        return;
                    }

                    $.ajax({
                        url: `/get-blnum-by-rinum/${rinum}`,
                        type: 'GET',
                        dataType: 'json',
                        success: function(response) {
                            if (response.success && response.data.length > 0) {
                                let options = '<option value="">Pilih BL / AWB No.</option>';
                                response.data.forEach(row => {
                                    options += `<option value="${row.blnum}" data-bldat="${row.bldat}">
                                        ${row.blnum} (${row.bldat})
                                    </option>`;
                                });
                                $blnumSelect.html(options).trigger('change.select2');
                            } else {
                                $blnumSelect.html('<option value="">Tidak ada BL untuk RINUM ini</option>');
                            }
                        },
                        error: function() {
                            $blnumSelect.html('<option value="">Gagal memuat data BL</option>');
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
                                let barang = false;

                                $opron.append('<option value="" disabled selected>Pilih Barang</option>');
                                res.data.forEach(item => {
                                    const sisaQty = item.poqty - item.inqty;
                                    if (sisaQty > 0) {
                                        barang = true;
                                        $opron.append(
                                            `<option value="${item.opron}" 
                                                data-qty="${item.poqty}" 
                                                data-inqty="${item.inqty}"
                                                data-price="${item.price}"
                                                data-stdqu="${item.stdqu}">
                                                ${item.opron} - ${item.prona}
                                            </option>`
                                        );
                                    };
                                });

                                if (!barang) {
                                    $opron.html('<option value="" disabled selected>Tidak ada barang untuk PO ini</option>');
                                }

                            } else {
                                $opron.html('<option value="">Tidak ada barang untuk PO ini</option>');
                            }
                        },
                        error: function () {
                            $opron.html('<option value="">Gagal memuat data barang</option>');
                        }
                    });
                });

                // ambil qty & harga otomatis
                $(document).on('change', 'select[name="opron[]"]', function () {
                    const selectedOption = $(this).find(':selected');
                    const qty = selectedOption.data('qty') || '';
                    const inqty = selectedOption.data('inqty') || '';
                    const stdqu = selectedOption.data('stdqu') || '';
                    const price = selectedOption.data('price') || '';

                    const remainingQty = Math.max(qty - inqty, 0);

                    const parent = $(this).closest('.accordion-body');
                    parent.find('.poqty').val(remainingQty);
                    parent.find('.unit-label').text(stdqu);
                    parent.find('input[name="price[]"]').val(price);
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

                    headerButton.contents().filter(function () {
                        return this.nodeType === 3;
                    }).first().replaceWith(` ${newLabel}`);
                });
            });
        </script>

        {{-- inamt auto generate --}}
        <script>
            $(document).on('input', 'input[name="inqty[]"], input[name="inprc[]"]', function () {
                const $row = $(this).closest('.row');
                const qty = parseFloat($row.find('input[name="inqty[]"]').val()) || 0;
                const priceStr = $row.find('input[name="inprc[]"]').val();

                // Ambil currency code dari selector
                const curco = $('.currency-selector').val() || $('input[name="curco"]').val() || 'IDR';

                // Ambil locale dari kode currency
                const localeMap = {
                    IDR: 'id-ID',
                    USD: 'en-US',
                    EUR: 'de-DE',
                    GBP: 'en-GB',
                    MYR: 'ms-MY',
                    SGD: 'en-SG',
                    CHF: 'de-CH'
                };
                const locale = localeMap[curco] || 'id-ID';

                // Bersihkan angka
                const price = parseFloat(String(priceStr).replace(/[^\d.,-]/g, '').replace(',', '.')) || 0;

                // Hitung total
                const total = qty * price;

                // Format hasil sesuai currency yang dipilih
                const formatted = new Intl.NumberFormat(locale, {
                    style: 'currency',
                    currency: curco,
                    minimumFractionDigits: 2
                }).format(total);

                $row.find('input[name="inamt[]"]').val(formatted);
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
            document.addEventListener("DOMContentLoaded", function() {
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

                                    function parseCurrencyString(str) {
                                        if (!str) return 0;
                                        let clean = String(str).replace(/[^\d.,-]/g, '');
                                        if (clean.includes(',') && clean.includes('.')) {
                                            if (clean.lastIndexOf(',') > clean.lastIndexOf('.')) {
                                                clean = clean.replace(/\./g, '').replace(',', '.');
                                            } else {
                                                clean = clean.replace(/,/g, '');
                                            }
                                        } else if (clean.includes(',')) {
                                            const commaCount = (clean.match(/,/g) || []).length;
                                            if (commaCount === 1 && clean.indexOf(',') > clean.length - 4) {
                                                clean = clean.replace(',', '.');
                                            } else {
                                                clean = clean.replace(/,/g, '');
                                            }
                                        } else {
                                            clean = clean.replace(/[^\d.-]/g, '');
                                        }
                                        const number = parseFloat(clean);
                                        return isNaN(number) ? 0 : number;
                                    }

                                    document.querySelectorAll('.currency').forEach(function(el) {
                                        const before = el.value;
                                        const parsed = parseCurrencyString(before);
                                        el.value = parsed.toString();
                                        console.log('Currency cleaned:', el.name, '| Before:', before, '| After:', el.value);
                                    });

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