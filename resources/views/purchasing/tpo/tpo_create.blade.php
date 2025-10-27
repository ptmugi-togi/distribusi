@extends('layout.main')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/global.css') }}">
@endpush

@section('container')
    <main id="main" class="main">
        <div class="pagetitle">
            <h1>Tambah Data PO</h1>
            <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('tpo.index') }}">List PO</a></li>
                <li class="breadcrumb-item active">PO Create</li>
            </ol>
            </nav>
        </div>

        <section class="section">
            <form id="form-po" action="{{ route('tpo.store') }}" method="POST">
                @csrf
                <div class="row">
                    <div class="col-md-6 mt-3">
                        <label for="potype" class="form-label">Tipe PO</label><span class="text-danger"> *</span>
                        <select class="select2 form-control" name="potype" id="potype" style="width: 100%;" required>
                            <option value="" {{ old('potype') == '' ? 'selected' : '' }} disabled selected>Silahkan pilih Tipe PO</option>
                            <option value="Lokal" {{ old('potype') == 'Lokal' ? 'selected' : '' }}>Lokal</option>
                            <option value="Import" {{ old('potype') == 'Import' ? 'selected' : '' }}>Import</option>
                            <option value="Inventaris" {{ old('potype') == 'Inventaris' ? 'selected' : '' }}>Inventaris</option>
                        </select>
                    </div>
                    
                    <input type="text" name="formc" id="formc" value="{{ old('formc') }}" hidden>

                    <div class="col-md-6 mt-3">
                        <label for="pono" class="form-label">Nomor PO</label><span class="text-danger"> *</span>
                        <input type="text" class="form-control text-uppercase" placeholder="Cth : BAxxx-2xxx" name="pono" id="pono" value="{{ strtoupper(old('pono')) }}" required>
                        @error('pono')
                            <span class="text-danger">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>

                    <div class="col-md-6 mt-3">
                        <label for="podat" class="form-label">Tanggal PO</label><span class="text-danger"> *</span>
                        <input type="date" class="form-control"
                            name="podat" id="podat"
                            value="{{ old('podat') }}"
                            required min="{{ date('Y-m-d') }}">
                        @error('podat')
                            <small class="text-danger"><strong>{{ $message }}</strong></small>
                        @enderror
                    </div>

                    <div class="col-md-6 mt-3">
                        <label for="supno" class="form-label">Supplier <span class="text-danger">*</span></label>
                        <select class="select2 form-control" name="supno" id="supno" required>
                            <option value="" disabled {{ old('supno') ? '' : 'selected' }}>Silahkan pilih Supplier</option>
                            @foreach($vendors as $v)
                                <option
                                    value="{{ $v->supno }}"
                                    {{ old('supno') == $v->supno ? 'selected' : '' }}>
                                    {{ $v->supna }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-3 mt-3">
                        <label for="topay" class="form-label">TOP (Hari)</label><span class="text-danger"> *</span>
                        <input type="number" class="form-control" placeholder="Cth : 30" name="topay" id="topay" value="{{ old('topay') }}" required min="0">
                    </div>

                    <div class="col-md-6 mt-3">
                        <label for="tdesc" class="form-label">Deskripsi TOP</label>
                        <input type="text" class="form-control" placeholder="Cth : Bulan Kredit" name="tdesc" id="tdesc" value="{{ old('tdesc') }}">
                    </div>
                    
                    <div class="col-md-3 mt-3">
                        <label for="curco" class="form-label">Currency Code</label><span class="text-danger"> *</span>
                        <select class="select2 form-control" name="curco" id="currency" style="width: 100%;" required>
                            <option value="IDR" {{ old('curco') == '' ? 'selected' : '' }} disabled selected>IDR (Rupiah Indonesia)</option>
                            <option value="CHF" {{ old('curco') == 'CHF' ? 'selected' : '' }}>CHF (Franc Swiss)</option>
                            <option value="EUR" {{ old('curco') == 'EUR' ? 'selected' : '' }}>EUR (Euro)</option>
                            <option value="GBP" {{ old('curco') == 'GBP' ? 'selected' : '' }}>GBP (Pound Sterling)</option>
                            <option value="IDR" {{ old('curco') == 'IDR' ? 'selected' : '' }}>IDR (Rupiah Indonesia)</option>
                            <option value="MYR" {{ old('curco') == 'MYR' ? 'selected' : '' }}>MYR (Ringgit Malaysia)</option>
                            <option value="SGD" {{ old('curco') == 'SGD' ? 'selected' : '' }}>SGD (Dollar Singapura)</option>
                            <option value="USD" {{ old('curco') == 'USD' ? 'selected' : '' }}>USD (Dollar Amerika Serikat)</option>
                        </select>
                    </div>

                    <div class="col-md-6 mt-3">
                        <label for="currency_rate" class="form-label" id="currency_rate_label">Kurs (IDR)</label>
                        <input type="text" class="form-control" id="currency_rate_display" value="{{ old('currency_rate') ? 'Rp ' . number_format(old('currency_rate'), 2, ',', '.') : '1' }}" required>
                        <input type="text" class="form-control" name="currency_rate" id="currency_rate" value="{{ old('currency_rate') ? old('currency_rate') : '1' }}" hidden required>
                    </div>

                    <div class="col-md-6 mt-3">
                        <label for="freight_cost" class="form-label">Freight Cost</label>
                        <input type="text" class="form-control" id="freight_cost_display"
                            value="{{ old('freight_cost') ? number_format(old('freight_cost'), 2, ',', '.') : '' }}"
                            placeholder="Cth : 1000000">

                        <input type="text" name="freight_cost" id="freight_cost" value="{{ old('freight_cost') }}" hidden>
                    </div>

                    <div class="col-md-6 mt-3">
                        <label for="shvia" class="form-label">Pengiriman</label><span class="text-danger"> *</span>
                        <input type="text" class="form-control" name="shvia" id="shvia" value="{{ old('shvia') }}" required>
                    </div>

                    <div class="col-md-6 mt-3">
                        <label for="delco" class="form-label">Dikirim Ke</label><span class="text-danger"> *</span>
                        <select class="select2 form-control" name="delco" id="delco" style="width: 100%;" required>
                            <option value="" {{ old('delco') ? '' : 'selected' }} disabled selected>Silahkan pilih tujuan pengiriman</option>
                            <option value="PST" {{ old('delco') == 'PST' ? 'selected' : '' }}>PST (Pusat)</option>
                            <option value="CKG" {{ old('delco') == 'CKG' ? 'selected' : '' }}>CKG (Cakung)</option>
                            <option value="D3" {{ old('delco') == 'D3' ? 'selected' : '' }}>D3 (Duren 3)</option>
                        </select>
                    </div>
                    <input type="text" class="form-control" name="braco" id="braco" value="{{ old('braco') }}" hidden>
                </div>

                <div class="row">
                    <div class="col-md-4 mt-3">
                        <label for="diper" class="form-label">Diskon (%)</label>
                        <input type="text" class="form-control" placeholder="Cth : 1.25" name="diper" id="diper" value="{{ old('diper', 0) }}" oninput="this.value = this.value.replace(/[^0-9.]/g, '')">
                    </div>
                    <div class="col-md-4 mt-3">
                        <label for="vatax" class="form-label">Tax Rate (%)</label>
                        <input type="text" class="form-control" placeholder="Cth : 5.5" name="vatax" id="vatax" value="{{ old('vatax', 0) }}" oninput="this.value = this.value.replace(/[^0-9.]/g, '')">
                    </div>

                    <div class="col-md-4 mt-3">
                        <label for="stamp" class="form-label">Meterai</label>
                        <input type="number" class="form-control" placeholder="Cth : 10000" name="stamp" id="stamp" value="{{ old('stamp', 0) }}"  min="0">
                    </div>
                    <div class="col-md-12 mt-3">
                        <label for="noteh" class="form-label">Catatan</label>
                        <textarea 
                            type="text" class="form-control" placeholder="Cth : note" name="noteh" id="noteh" maxlength="200"
                        >{{ old('noteh') }}</textarea>
                        <div class="form-text text-danger text-end" style="font-size: 0.7rem;">
                            Maksimal 200 karakter
                        </div>
                    </div>
                </div>


                <hr class="my-4">

                <div class="row">
                    <h3 class="my-2">Detail Barang PO</h3>
                    
                    <div id="barang_po">
                        <div class="accordion" id="accordionPoBarang">
                            @foreach(old('opron', [null]) as $i => $oldOpron)
                                @include('purchasing.tpo.partial.tpo_create_detail', ['i' => $i, 'oldOpron' => $oldOpron, 'products' => $products])
                            @endforeach
                        </div>
                    </div>
                </div>
 
                <div class="text-end">
                    <button type="button" class="btn mt-3" style="background-color: #4456f1; color: #fff" onclick="addBarang()">Tambah Barang</button>
                </div>

                <div class="mt-3 d-flex justify-content-between">
                    <a href="{{ route('tpo.index') }}" class="btn btn-secondary">Kembali</a>
                    <button type="submit" class="btn btn-primary">Simpan Data</button>
                </div>
            </form>
        </section>
    </main>

    @push('scripts') 
        {{-- get formc --}}
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const formc = document.getElementById('formc');
                const map = { Lokal: 'PO', Import: 'PI', Inventaris: 'PN' };

                $('#potype').on('change', function () {
                    formc.value = map[this.value] || '';
                });
            });

            document.addEventListener('DOMContentLoaded', function () {
                const braco = document.getElementById('braco');
                
                $('#delco').on('change', function () {
                    braco.value = this.value;
                });
            });
        </script>

        {{-- toggle HSN dari potype --}}
        <script>
            $(document).ready(function () {
                function toggleHSBM() {
                    const potype = $('#potype').val();
                    if (potype === "Import") {
                        $('.hsn-input, .bm-input').prop('disabled', false);
                    } else {
                        $('.hsn-input, .bm-input').prop('disabled', true).val('');
                    }
                }

                toggleHSBM();

                // kalau ganti PO Type
                $('#potype').on('change', toggleHSBM);

                window.toggleHSBM = toggleHSBM;
            });
        </script>


        {{-- get currency from curco --}}
        <script>
            $(document).ready(function () {
                $('#currency').on('change', function () {
                    const curco = $(this).val();
                    if (!curco) return;

                    $.ajax({
                        url: `/get-currency-rate/${curco}`,
                        method: 'GET',
                        success: function (response) {

                            if (response.success) {
                                const rate = parseFloat(response.crate);

                                if (!isNaN(rate)) {
                                    // isi ke hidden
                                    $('#currency_rate').val(rate);

                                    // tampilkan ke display
                                    $('#currency_rate_display').val(
                                        new Intl.NumberFormat('id-ID', {
                                            style: 'currency',
                                            currency: 'IDR',
                                            minimumFractionDigits: 2,
                                            maximumFractionDigits: 2
                                        }).format(rate)
                                    );
                                } else {
                                    $('#currency_rate').val('');
                                    $('#currency_rate_display').val('');
                                }
                            } else {
                                alert('Currency tidak ditemukan.');
                                $('#currency_rate').val('');
                                $('#currency_rate_display').val('');
                            }
                        },
                        error: function (xhr) {
                            console.error(xhr.responseText);
                            alert('Gagal mengambil data kurs dari server.');
                            $('#currency_rate').val('');
                            $('#currency_rate_display').val('');
                        }
                    });
                });
            });
        </script>

        {{-- get product --}}
        <script>
            document.addEventListener("DOMContentLoaded", function () {

                function initSelect2Barang(index) {
                    const selector = `#opron-${index}`;
                    const $select = $(selector);

                    if (!$select.length) return;

                    console.log("Init Select2 untuk:", selector);

                    // ambil old value dari Blade (kalau ada)
                    const oldVal = $select.attr('data-old'); // kita isi nanti dari Blade

                    $select.select2({
                        placeholder: 'Silahkan pilih Barang',
                        theme: 'bootstrap-5',
                        width: '100%',
                        ajax: {
                            url: '/api/products',
                            dataType: 'json',
                            delay: 250,
                            data: function (params) {
                                return {
                                    q: params.term || '',
                                    page: params.page || 1
                                };
                            },
                            processResults: function (data) {
                                return {
                                    results: data.results.map(item => ({
                                        id: item.id || item.opron,
                                        text: `${item.id} - ${item.data_prona}`,
                                        data_prona: item.data_prona,
                                        data_stdqu: item.data_stdqu
                                    })),
                                    pagination: { more: data.pagination.more }
                                };
                            },
                            error: function (xhr, status, error) {
                                if (status !== 'abort') console.error('Select2 AJAX error:', status, error);
                            }
                        },
                        minimumInputLength: 0,
                        allowClear: true
                    });

                    $select.on('select2:select', function (e) {
                        const data = e.params.data;
                        const index = selector.split('-')[1];

                        // Tambahkan atribut ke option terpilih
                        const option = $select.find('option[value="' + data.id + '"]');
                        option.attr('data-prona', data.data_prona);
                        option.attr('data-stdqu', data.data_stdqu);

                        // Panggil update label
                        updateBarangLabel(index);
                    });

                    $select.on('select2:clear', function (e) {
                        const index = selector.split('-')[1];
                        $(`#barang-label-${index}`).text('');
                        $(`#stdqu-${index}`).val('');
                        $(`#qty-label-${index}`).text('');
                    });


                    // kalau ada old value, tambahkan manual ke select biar tampil
                    if (oldVal) {
                        const oldText = $select.attr('data-old-text') || oldVal;
                        const oldStdqu = $select.attr('data-old-stdqu') || '';

                        const option = new Option(oldText, oldVal, true, true);
                        $select.append(option).trigger('change');

                        // update hidden field juga
                        $(`#stdqu-${index}`).val(oldStdqu);
                        $(`#qty-label-${index}`).text(oldStdqu);
                        $(`#barang-label-${index}`).text(`(${oldText})`);
                    }
                }

                // Simpan global function
                window.initSelect2Barang = initSelect2Barang;

                // Jalankan setelah semua elemen ada
                $(window).on('load', function () {
                    $('[id^=opron-]').each(function () {
                        const id = $(this).attr('id').split('-')[1];
                        initSelect2Barang(id);
                    });
                });
            });
        </script>

        {{-- formating currency input --}}
        <script>
            function formatCurrency(value, currency) {
                if (!value || isNaN(value)) return "";
                return new Intl.NumberFormat("id-ID", {
                    style: "currency",
                    currency: currency,
                    minimumFractionDigits: 2,
                    maximumFractionDigits: 2
                }).format(value);
            }

            function cleanNumber(str) {
                return str.replace(/[^0-9,.-]/g, "").replace(",", ".");
            }

            // formatter untuk single field (display + raw)
            function attachCurrencyFormatter(displayId, hiddenId, currencySelect, forceCurrency = null) {
                const display = document.getElementById(displayId);
                const hidden = document.getElementById(hiddenId);

                if (!display || !hidden) return;

                // input -> update raw
                display.addEventListener("input", (e) => {
                    let raw = cleanNumber(e.target.value);
                    let value = parseFloat(raw);
                    hidden.value = !isNaN(value) ? value : "";
                });

                // blur -> tampilkan format
                display.addEventListener("blur", (e) => {
                    if (hidden.value) {
                        e.target.value = formatCurrency(
                            hidden.value,
                            forceCurrency ? forceCurrency : currencySelect.value
                        );
                    }
                });

                // focus -> tampilkan angka mentah
                display.addEventListener("focus", (e) => {
                    if (hidden.value) {
                        e.target.value = hidden.value;
                    }
                });

                // load awal
                if (hidden.value) {
                    display.value = formatCurrency(
                        hidden.value,
                        forceCurrency ? forceCurrency : currencySelect.value
                    );
                }
            }

            function attachPriceEvents(input, hidden, currencySelect) {
                input.addEventListener("input", (e) => {
                    let raw = e.target.value.replace(/,/g, ".").replace(/[^\d.]/g, "");
                    let parts = raw.split(".");
                    let intPart = parts[0] || "0";
                    let decPart = parts[1] ? "." + parts[1].slice(0, 2) : "";

                    hidden.value = parseFloat(intPart + decPart);
                });

                input.addEventListener("blur", (e) => {
                    if (hidden.value) {
                        e.target.value = formatCurrency(hidden.value, currencySelect.value);
                    }
                });

                input.addEventListener("focus", (e) => {
                    if (hidden.value) {
                        e.target.value = hidden.value;
                    }
                });
            }

            function initPriceFormatter() {
                const currencySelect = document.getElementById("currency");

                // Format semua price[]
                document.querySelectorAll(".price-input").forEach((input) => {
                    const index = input.id.split("-")[1];
                    const hidden = document.getElementById("priceraw-" + index);

                    attachPriceEvents(input, hidden, currencySelect);

                    if (hidden && hidden.value) {
                        input.value = formatCurrency(hidden.value, currencySelect.value);
                    }
                });
            }

            document.addEventListener("DOMContentLoaded", () => {
                const currencySelect = document.getElementById("currency");

                // init freight_cost -> ikut currency yang dipilih
                attachCurrencyFormatter("freight_cost_display", "freight_cost", currencySelect);

                // init currency_rate -> selalu pakai IDR
                attachCurrencyFormatter("currency_rate_display", "currency_rate", currencySelect, "IDR");

                // init semua price[]
                initPriceFormatter();

                // kalau currency ganti -> reformat semua field
                $('#currency').on('change', function () {
                    const newCurrency = $(this).val();

                    // reformat freight cost
                    const freightHidden = document.getElementById("freight_cost");
                    const freightDisplay = document.getElementById("freight_cost_display");
                    if (freightHidden && freightHidden.value) {
                        freightDisplay.value = formatCurrency(freightHidden.value, newCurrency);
                    }

                    // reformat semua price
                    document.querySelectorAll(".price-input").forEach((input) => {
                        const index = input.id.split("-")[1];
                        const hidden = document.getElementById("priceraw-" + index);
                        if (hidden.value) {
                            input.value = formatCurrency(hidden.value, newCurrency);
                        }
                    });

                    // kurs tetap IDR
                    const rateHidden = document.getElementById("currency_rate");
                    const rateDisplay = document.getElementById("currency_rate_display");
                    if (rateHidden && rateHidden.value) {
                        rateDisplay.value = formatCurrency(rateHidden.value, "IDR");
                    }
                });
            });
        </script>

        {{-- Formating currency + raw --}}
        <script>
            function getLocale(currency) {
                switch(currency) {
                    case "CHF": return "fr-CH";
                    case "EUR": return "de-DE";
                    case "GBP": return "en-GB";
                    case "IDR": return "id-ID";
                    case "MYR": return "ms-MY";
                    case "SGD": return "en-SG";
                    case "USD": return "en-US";
                    case "JPY": return "ja-JP";
                    default: return "en-US";
                }
            }

            function formatCurrency(value, currency) {
                if (value === "" || value === null || isNaN(value)) return "";
                if (!currency) return value;

                const locale = getLocale(currency);

                return new Intl.NumberFormat(locale, {
                    style: 'currency',
                    currency: currency,
                    minimumFractionDigits: 2,
                    maximumFractionDigits: 2
                }).format(value);
            }

            function attachPriceEvents(input, hidden, currencySelect) {
                input.addEventListener("input", (e) => {
                    const currency = currencySelect.value;
                    let raw = e.target.value.replace(/,/g, ".").replace(/[^\d.]/g, "");
                    let parts = raw.split(".");
                    let intPart = parts[0] || "0";
                    let decPart = parts[1] ? "." + parts[1].slice(0, 2) : "";

                    hidden.value = parseFloat(intPart + decPart);
                });

                input.addEventListener("blur", (e) => {
                    const currency = currencySelect.value;
                    if (hidden.value) {
                        e.target.value = formatCurrency(hidden.value, currency);
                    }
                });

                input.addEventListener("focus", (e) => {
                    if (hidden.value) {
                        e.target.value = hidden.value; // tampilkan raw
                    }
                });
            }

            function initPriceFormatter() {
                const currencySelect = document.getElementById("currency");
                document.querySelectorAll(".price-input").forEach((input) => {
                    const index = input.id.split("-")[1];
                    const hidden = document.getElementById("priceraw-" + index);

                    attachPriceEvents(input, hidden, currencySelect);

                    // kalau ada old value -> langsung formatkan
                    if (hidden && hidden.value) {
                        input.value = formatCurrency(hidden.value, currencySelect.value);
                    }
                });

                // kalau currency ganti -> reformat semua input
                $('#currency').on('change', function () {
                    const newCurrency = $(this).val();
                    if (!newCurrency) return;

                    document.querySelectorAll(".price-input").forEach((input) => {
                        const index = input.id.split("-")[1];
                        const hidden = document.getElementById("priceraw-" + index);
                        if (hidden.value) {
                            input.value = formatCurrency(hidden.value, newCurrency);
                        }
                    });
                });
            }

            // jalankan pas load
            document.addEventListener("DOMContentLoaded", initPriceFormatter);
        </script>

        {{-- Nama Accordion ambil nama produk --}}
        <script>
            function updateBarangLabel(index) {
                const select = document.getElementById(`opron-${index}`);
                const selectedOption = select.options[select.selectedIndex];
                const opron = selectedOption ? selectedOption.value : "";
                const prona = selectedOption ? selectedOption.getAttribute("data-prona") : "";
                const stdqu = selectedOption ? selectedOption.getAttribute("data-stdqu") : "";

                const labelSpan = document.getElementById(`barang-label-${index}`);
                if (labelSpan) {
                    labelSpan.textContent = `(${opron} - ${prona ? `${prona})` : ""}`;
                }

                const qtyLabel = document.getElementById(`qty-label-${index}`);
                if (qtyLabel) {
                    qtyLabel.textContent = stdqu;
                }

                const stdquInput = document.getElementById(`stdqu-${index}`);
                if (stdquInput) {
                    stdquInput.value = stdqu || '';
                }
            }
        </script>

        {{-- add barang --}}
        <script>
            // Mulai dari jumlah data lama (kalau ada old input dari validasi gagal)
            let barangIndex = {{ count(old('opron', [null])) }};

            // Function Tambah Barang
            function addBarang() {
                const accordion = document.getElementById('accordionPoBarang');

                const newItem = document.createElement('div');
                newItem.classList.add('accordion-item');
                newItem.id = `accordion-item-${barangIndex}`;

                newItem.innerHTML = `
                    <h2 class="accordion-header d-flex justify-content-between align-items-center" id="heading-${barangIndex}">
                        <button class="accordion-button collapsed" type="button"
                                data-bs-toggle="collapse" data-bs-target="#barang-${barangIndex}"
                                aria-expanded="false" aria-controls="barang-${barangIndex}">
                            <span id="barang-label-${barangIndex}">
                                {{ optional($products->firstWhere('opron', $oldOpron))->opron }}  {{ optional($products->firstWhere('opron', $oldOpron))->prona }}
                            </span>
                        </button>
                        <button type="button" class="btn btn-sm btn-danger mx-2"  onclick="removeBarang(${barangIndex})">
                            <i class="bi bi-trash-fill"></i>
                        </button>
                    </h2>
                    <div id="barang-${barangIndex}" class="accordion-collapse collapse"
                        aria-labelledby="heading-${barangIndex}" data-bs-parent="#accordionPoBarang">
                        <div class="accordion-body">
                            <div class="row">
                                <div class="col-md-6 mt-3">
                                    <label for="opron-${barangIndex}" class="form-label">Barang PO <span class="text-danger">*</span></label>
                                    <select class="select2 form-control" name="opron[]" id="opron-${barangIndex}" required></select>
                                    <input type="text" name="stdqu[]" id="stdqu-${barangIndex}" hidden>
                                </div>

                                <div class="col-md-6 mt-3">
                                    <label for="price-${barangIndex}" class="form-label">Harga Barang <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="price-${barangIndex}"
                                        placeholder="Cth : 1000000" required>
                                    <input type="text" name="price[]" id="priceraw-${barangIndex}" hidden>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-4 mt-3">
                                    <label for="poqty-${barangIndex}" class="form-label">Qty <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <input type="number" class="form-control" name="poqty[]" id="poqty-${barangIndex}" min="0"
                                            placeholder="Cth : 10" required>
                                        <span class="input-group-text" id="qty-label-${barangIndex}">
                                        </span>
                                    </div>
                                </div>

                                <div class="col-md-4 mt-3">
                                    <label for="weigh-${barangIndex}" class="form-label">Berat Barang (Kg)</label>
                                    <input type="text" class="form-control" name="weigh[]" id="weigh-${barangIndex}"
                                        placeholder="Cth : 10.5" oninput="this.value = this.value.replace(/[^0-9.]/g, '')">
                                </div>

                                <div class="col-md-4 mt-3">
                                    <label for="odisp-${barangIndex}" class="form-label">Diskon (%)</label>
                                    <input type="text" class="form-control" name="odisp[]" id="odisp-${barangIndex}"
                                        placeholder="Cth : 5.5" value="0" oninput="this.value = this.value.replace(/[^0-9.]/g, '')">
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mt-3">
                                    <label for="edeld-${barangIndex}" class="form-label">ETD <span class="text-danger">*</span></label>
                                    <input type="date" class="form-control" name="edeld[]" id="edeld-${barangIndex}" min="{{ date('Y-m-d') }}" required>
                                </div>

                                <div class="col-md-6 mt-3">
                                    <label for="earrd-${barangIndex}" class="form-label">ETA</label>
                                    <input type="date" class="form-control" name="earrd[]" id="earrd-${barangIndex}" min="{{ date('Y-m-d') }}">
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-4 mt-3">
                                    <label for="hsn-${barangIndex}" class="form-label">HS Code</label>
                                    <input type="number" class="form-control hsn-input" name="hsn[]" id="hsn-${barangIndex}" placeholder="Cth : 123" min="0" disabled>
                                </div>

                                <div class="col-md-4 mt-3">
                                    <label for="bm-${barangIndex}" class="form-label">BM (%)</label>
                                    <input type="text" class="form-control bm-input" name="bm[]" id="bm-${barangIndex}" placeholder="Cth : 1.5" value="0"
                                        oninput="this.value = this.value.replace(/[^0-9.]/g, '')" disabled>
                                </div>

                                <div class="col-md-4 mt-3">
                                    <label for="pphd-${barangIndex}" class="form-label">PPH (%)</label>
                                    <input type="text" class="form-control" name="pphd[]" id="pphd-${barangIndex}" placeholder="Cth : 11" value="0"
                                        oninput="this.value = this.value.replace(/[^0-9.]/g, '')">
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12 mt-3">
                                    <label for="noted-${barangIndex}" class="form-label">Catatan</label>
                                    <textarea class="form-control" name="noted[]" id="noted-${barangIndex}" maxlength="200" placeholder="Cth : note"></textarea>
                                    <div class="form-text text-danger text-end" style="font-size: 0.7rem;">
                                        Maksimal 200 karakter
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                `;

                accordion.appendChild(newItem);

                // otomatis buka accordion yang baru dibuat
                const collapse = new bootstrap.Collapse(document.getElementById(`barang-${barangIndex}`), { show: true });

                // cek potype untuk toggle HSN dan BM
                if (typeof toggleHSBM === 'function') {
                    toggleHSBM();
                }

                initSelect2Barang(barangIndex);


                // panggil initSelect2Barang supaya pakai AJAX
                if (typeof initSelect2Barang === 'function') {
                    initSelect2Barang(barangIndex);
                }

                const newPrice = document.getElementById(`price-${barangIndex}`);
                const newHidden = document.getElementById(`priceraw-${barangIndex}`);
                const currencySelect = document.getElementById("currency");

                if (newPrice && newHidden) {
                    attachPriceEvents(newPrice, newHidden, currencySelect);
                }

                barangIndex++;
            }

            // Hapus Barang
            function removeBarang(index) {
                const item = document.getElementById(`accordion-item-${index}`);
                if (item) {
                    item.remove();
                }
            }
        </script>

        {{-- Modal Konfirmasi simpan data --}}
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const form = document.getElementById('form-po');

                if (!form) return; 

                form.addEventListener('submit', function (e) {
                    e.preventDefault();

                    // Jalankan validasi 
                    if (!form.checkValidity()) {
                        form.classList.add('was-validated');
                        return;
                    }

                    // SweetAlert
                    Swal.fire({
                        title: 'Konfirmasi Simpan',
                        text: 'Apakah Anda yakin ingin menyimpan data Purchase Order ini?',
                        icon: 'question',
                        showCancelButton: true,
                        confirmButtonText: 'Ya, Simpan!',
                        cancelButtonText: 'Batal',
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            Swal.fire({
                                title: 'Menyimpan...',
                                text: 'Mohon tunggu sebentar.',
                                icon: 'info',
                                allowOutsideClick: false,
                                allowEscapeKey: false,
                                showConfirmButton: false,
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