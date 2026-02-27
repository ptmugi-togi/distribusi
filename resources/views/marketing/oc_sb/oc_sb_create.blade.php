@extends('layout.main')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/global.css') }}">
@endpush

@section('container')
<main id="main" class="main">
    <div class="d-flex justify-content-between align-items-center">
        <div class="pagetitle">
        <h1>Tambah Data OC PROJECT (SB)</h1>
        <nav>
            <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
            <li class="breadcrumb-item"><a href="{{ route('oc_sb.index') }}">List OC PROJECT (SB)</a></li>
            <li class="breadcrumb-item active">OC PROJECT (SB) Create</li>
            </ol>
        </nav>
        </div>
    </div>

    <section class="section">
        <form id="form-oc" action="{{ route('oc_sb.store') }}" method="POST">
            @csrf

            <div class="row">
            <input type="text" class="form-control" id="formc" name="formc" value="SB" hidden>
            <input type="text" class="form-control" id="braco" name="braco" value="{{ auth()->user()->cabang }}" hidden>
                
            <div class="col-md-4 mt-3">
                <label for="sorno" class="form-label">OC No.</label><span class="text-danger"> *</span>
                <input type="text" class="form-control" name="sorno" id="sorno" value="{{ old('sorno') }}" required>
                @error('sorno')
                    <span class="text-danger">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>

            <div class="col-md-4 mt-3">
                <label for="sordt" class="form-label">OC Date</label><span class="text-danger"> *</span>
                <input type="date" class="form-control" name="sordt" id="sordt" value="{{ old('sordt') }}" min="{{ $minDate }}" required>
                <input type="text" name="priod" id="priod" value="{{ old('priod' ?? '') }}" hidden>
            </div>

            <div class="col-md-4 mt-3">
                <label for="depo" class="form-label">Depo</label>
                 <select name="depo" id="depo" class="form-control select2"
                 
                 {{ $depo->isEmpty() ? 'disabled' : '' }}>

                    @if($depo->isEmpty())
                        <option value="" selected>Depo tidak tersedia</option>
                    @else
                        <option value="" disabled {{ old('depo') ? '' : 'selected' }}>
                            Silahkan Pilih Depo
                        </option>

                    @foreach ($depo as $d)
                        <option value="{{ $d->depo }}" 
                            {{ old('depo') == $d->depo ? 'selected' : '' }}>
                            {{ $d->depo }} - {{ $d->name }}
                        </option>
                    @endforeach
                @endif
            </select>
            </div>

            <div class="col-md-6 mt-3">
                <label for="cusno" class="form-label">Customer</label><span class="text-danger"> *</span>
                <select name="cusno" id="cusno" class="form-control select2">
                    <option value="" disabled {{ old('cusno') ? '' : 'selected' }}>Silahkan Pilih Customer</option>
                    @foreach ($customer as $c)
                        <option value="{{ $c->cusno }}" {{ old('cusno') == $c->cusno ? 'selected' : '' }}>
                            {{ $c->cusno }} - {{ $c->title }} {{ $c->cusna }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-6 mt-3">
                <label for="sreno" class="form-label">Sales Rep.</label><span class="text-danger"> *</span>
                <select name="sreno" id="sreno" class="form-control select2">
                    <option value="" disabled {{ old('sreno') ? '' : 'selected' }}>Silahkan Pilih Sales Rep</option>
                    @foreach ($sales as $s)
                        <option value="{{ $s->sreno }}" {{ old('sreno') == $s->sreno ? 'selected' : '' }}>
                            {{ $s->sreno }} - {{ $s->srena }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-4 mt-3">
                <label for="pcuto" class="form-label">Plan Cut Off</label><span class="text-danger"> *</span>
                <input type="date" class="form-control" name="pcuto" id="pcuto" value="{{ old('pcuto') }}" required>
            </div>

            <div class="col-md-4 mt-3">
                <label for="curco" class="form-label">Currency Code</label><span class="text-danger"> *</span>
                <select name="curco" id="curco" class="form-control select2">
                    <option value="" disabled {{ old('curco') ? '' : 'selected' }}>Silahkan Pilih Currency</option>
                    @foreach ($currency as $curr)
                        @if (in_array($curr->curco, ['IDR', 'USD']))
                            <option value="{{ $curr->curco }}"
                                {{ old('curco', 'IDR') == $curr->curco ? 'selected' : '' }}>
                                {{ $curr->curco }} - {{ $curr->desc_curco }}
                            </option>
                        @endif
                    @endforeach
                </select>
            </div>

            <div class="col-md-4 mt-3">
                <label for="crate" class="form-label">Currency Rate</label><span class="text-danger"> *</span>
                <input type="text" class="form-control" name="crate_d" id="currency_rate_display" value="{{ old('crate_d') }}" required readonly style="background-color:#e9ecef">
                <input type="text" class="form-control" name="crate" id="currency_rate" value="{{ old('crate') }}" required hidden>
            </div>

            <div class="col-md-4 mt-3">
                <label for="gross" class="form-label">Gross Value</label>
                <input type="text" class="form-control price-input" id="gross_display" value="{{ old('gross_hdr') ? number_format(old('gross_hdr'), 2, '.', '') : '' }}" data-raw-target="gross_raw" readonly style="background-color:#e9ecef">

                <input type="text" name="gross_hdr" id="gross_raw" value="{{ old('gross_hdr') }}" hidden>
            </div>

            <div class="col-md-4 mt-3">
                <label for="odisa_hdr" class="form-label">Official Discount</label>
                <input type="text" class="form-control price-input" id="odisa_display" value="{{ old('odisa_hdr') ? number_format(old('odisa_hdr'), 2, '.', '') : '' }}" data-raw-target="odisa_raw_hdr" readonly style="background-color:#e9ecef">

                <input type="text" name="odisa_hdr" id="odisa_raw_hdr" value="{{ old('odisa_hdr') }}" hidden>
            </div>
            
            <div class="col-md-4 mt-3">
                <label for="insfe" class="form-label">Installation</label>
                <input type="text" class="form-control price-input" id="insfe_display" value="{{ old('insfe') ? number_format(old('insfe'), 2, '.', '') : '' }}" data-raw-target="insfe_raw">

                <input type="text" name="insfe" id="insfe_raw" value="{{ old('insfe') }}" hidden>
            </div>

            <div class="col-md-6 mt-3">
                <label for="vatax" class="form-label">VAT {{ $taxes->taxes }}%</label>
                <input type="text" id="tax_percent" name="vatax" value="{{ $taxes->taxes }}" hidden>
                <input type="text" class="form-control price-input" id="vatax_display" value="{{ old('vatax') ? number_format(old('vatax'), 2, '.', '') : '' }}" data-raw-target="vatax_raw" readonly style="background-color:#e9ecef">

                <input type="text" name="vtamt" id="vatax_raw" value="{{ old('vatax') }}" hidden>
            </div>

            <div class="col-md-6 mt-3">
                <label for="billv_hdr" class="form-label">Billing Amount</label>
                <input type="text" class="form-control price-input" id="billv_display" value="{{ old('billv_hdr') ? number_format(old('billv_hdr'), 2, '.', '') : '' }}" data-raw-target="billv_raw" readonly style="background-color:#e9ecef">

                <input type="text" name="billv_hdr" id="billv_raw" value="{{ old('billv_hdr') }}" hidden>
            </div>
            
            <div class="col-md-6 mt-3">
                <label for="nodeb" class="form-label">Disposisi EB#</label><span class="text-danger"> *</span>
                <input type="text" class="form-control" name="nodeb" id="nodeb" value="{{ old('nodeb') }}" required>
            </div>
            
            <div class="col-md-6 mt-3">
                <label for="edisa_hdr" class="form-label">EB</label>
                <input type="text" class="form-control price-input" id="edisa_display" value="{{ old('edisa_hdr') ? number_format(old('edisa_hdr'), 2, '.', '') : '' }}" data-raw-target="edisa_raw">

                <input type="text" name="edisa_hdr" id="edisa_raw" value="{{ old('edisa_hdr') }}" hidden>
            </div>

            <div class="col-md-6 mt-3">
                <label for="cuspo" class="form-label">Customer PO</label><span class="text-danger"> *</span>
                <input type="text" class="form-control" name="cuspo" id="cuspo" value="{{ old('cuspo') }}" required>
            </div>

            <div class="col-md-12 mt-3"> 
                <label class="form-label">Notes</label>
                <textarea type="text" class="form-control" name="noteh" id="noteh" maxlength="200">{{ old('noteh') }}</textarea>
                <div class="form-text text-danger text-end" style="font-size:0.7rem;">Maksimal 200 karakter</div>
            </div>

            <div class="detail my-3">
                @include('marketing.oc_sb.partial_create.oc_sb_create_detail_installation')
            </div>

            <div class="invoicing my-3">
                @include('marketing.oc_sb.partial_create.oc_sb_create_detail_invoicing')
            </div>

            <div class="mt-3 d-flex justify-content-between">
                <a href="{{ route('oc.index') }}" class="btn btn-secondary">Kembali</a>
                <button type="submit" class="btn btn-primary">Simpan Data</button>
            </div>
        </form>
    </section>
</main>

@push('scripts')
    <script>
        $(document).ready(function() {
            $('.select2').select2({ width: '100%', theme: 'bootstrap-5' });

            setTimeout(function(){
                refreshAccordionTitles();
                loadMasterProductAll();
            }, 500);

            if (!$('#curco').val()) {
                $('#curco').val('IDR').trigger('change.select2');
            } else {
                $('#curco').trigger('change');
            }

            // buat cusno dan delto
            const oldCusno = "{{ old('cusno') }}";
            window.oldDeltoArray = @json(old('delto', []));

            if (oldCusno) {
                $.ajax({
                    url: `/get-mstmas-delto`,
                    method: 'GET',
                    data: { cusno: oldCusno },
                    success: function (res) {

                        if (!res.success) return;

                        const list = res.data || [];

                        $('.delto-select').each(function(){

                            const index = $(this).data('index');
                            let options = '<option disabled>Pilih Site</option>';

                            list.forEach(item => {
                                const selected = (window.oldDeltoArray[index] == item.shpto) ? 'selected' : '';
                                options += `<option value="${item.shpto}" ${selected}>${item.shpto}</option>`;
                            });

                            $('#delto-' + index).html(options).trigger('change');

                            if(window.oldDeltoArray[index]){
                                $('#delto-' + index).trigger('change');
                            }

                        });
                    }
                });
            }

            // old opron + prona for accordion
            const oldProducts = @json(old('opron', []));
            const oldPronas   = @json(old('prona', []));

            $('.opron-oc').each(function(index){

                if(oldProducts[index]){
                    const option = new Option(
                        oldProducts[index] + ' - ' + (oldPronas[index] ?? ''),
                        oldProducts[index],
                        true,
                        true
                    );
                    $(this).append(option);
                }

            });

            $('.stdqu-oc').each(function(index){
                const val = $(this).val();
                if(val){
                    $(this).closest('.accordion-body')
                        .find('.unit-label-oc')
                        .text(val);
                }
            });

            $('[id^="smqtb"]').each(function(){
                if($(this).val()){
                    $(this).trigger('change');
                }
            });
        });

        $('#sordt').on('change', function(){
            let sordt = $('#sordt').val();

            if (!sordt) return;

            const year  = sordt.substring(0, 4);
            const month = sordt.substring(5, 7);

            $('#priod').val(year + month);
        });


        $('#curco').on('change', function(){
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

        function loadMasterProductAll(){
            $('select.opron-oc').each(function(){
                $(this).select2({
                    placeholder: 'Pilih Barang',
                    theme: 'bootstrap-5',
                    width: '100%',
                    allowClear: true,
                    ajax: {
                        url: '{{ route("api.products") }}',
                        dataType: 'json',
                        delay: 250,
                        data: function(params){
                            return { q: params.term || '', page: params.page || 1 };
                        },
                        processResults: function(data){
                            return {
                                results: (data.results || []).map(item => ({
                                    id: item.id,
                                    text: item.text,
                                    prona: item.data_prona,
                                    stdqt: item.data_stdqu,
                                    locco: item.data_locco
                                })),
                                pagination: { more: data.pagination.more }
                            };
                        }
                    },
                    minimumInputLength: 0,
                    templateResult: function (data) {
                        if (!data.id) return data.text;
                        const el = data.element;
                        if (el) $(el).attr('data-stdqt', data.stdqt || '');
                        return data.text;
                    },
                    templateSelection: function (data) {
                        if (!data.id) return data.text;
                        const el = data.element;
                        if (el) $(el).attr('data-stdqt', data.stdqt || '');
                        return data.text;
                    }
                });
            });
        }

        function refreshAccordionTitles() {

            $('.opron-oc').each(function(){

                const select = $(this);
                const row = select.closest('.accordion-item');
                const headerText = row.find('.accordion-title');

                const selectedText = select.find('option:selected').text();

                if (selectedText) {
                    headerText.text(selectedText);
                } else {
                    headerText.text('Detail Item');
                }
            });
        }
    </script>

    {{-- calculate --}}
    <script>
        function calculateHeaderFromDetails() {

            let totalGross = 0;
            let totalDiscount = 0;

            $('#accordionOCInstallation .accordion-item').each(function(){

                const qty = parseFloat($(this).find('.qtyor-oc').val()) || 0;
                const price = parseFloat($(this).find('[id^="price_raw_oc_"]').val()) || 0;
                const discountPerUnit = parseFloat($(this).find('[id^="odisa_raw_oc_"]').val()) || 0;

                const grossDetail = price * qty;
                const discountDetail = discountPerUnit * qty;

                totalGross += grossDetail;
                totalDiscount += discountDetail;
            });

            const currency = $('#curco').val();

            $('#gross_raw').val(totalGross.toFixed(2));
            $('#odisa_raw_hdr').val(totalDiscount.toFixed(2));

            $('#gross_display').val(formatCurrency(totalGross, currency));
            $('#odisa_display').val(formatCurrency(totalDiscount, currency));

            calculateVat();
            calculateBilling();
        }
    </script>
    <script>
        function calculateVat() {

            const gross   = parseFloat($('#gross_raw').val()) || 0;
            const discount = parseFloat($('#odisa_raw_hdr').val()) || 0;
            const installation = parseFloat($('#insfe_raw').val()) || 0;
            const taxPct  = parseFloat($('#tax_percent').val()) || 0;
            const curco   = $('#curco').val();

            const dpp = gross - discount + installation;

            if (dpp <= 0) {
                $('#vatax_raw').val('');
                $('#vatax_display').val('');
                return;
            }

            const vat = dpp * (taxPct / 100);

            $('#vatax_raw').val(vat.toFixed(2));
            $('#vatax_display').val(formatCurrency(vat, curco));
        }

        function calculateBilling() {

            const gross = parseFloat($('#gross_raw').val()) || 0;
            const disc  = parseFloat($('#odisa_raw_hdr').val()) || 0;
            const installation  = parseFloat($('#insfe_raw').val()) || 0;
            const vat   = parseFloat($('#vatax_raw').val()) || 0;
            const curco = $('#curco').val();

            const bill = gross - disc + installation + vat;

            $('#billv_raw').val(bill.toFixed(2)).trigger('change');
            $('#billv_display').val(formatCurrency(bill, curco));
        }

        $('#curco').on('change', function(){
            calculateVat();
            calculateBilling();
        });

        // Trigger kalau qty berubah
        $(document).on('input', '.qtyor-oc', function(){
            calculateHeaderFromDetails();
        });

        // Trigger kalau price berubah
        $(document).on('input', '[id^="price_display_oc_"]', function(){
            setTimeout(calculateHeaderFromDetails, 50);
        });

        // Trigger kalau discount berubah
        $(document).on('input', '[id^="odisa_display_oc_"]', function(){
            setTimeout(calculateHeaderFromDetails, 50);
        });

        // Trigger kalau installation header berubah
        $(document).on('input', '#insfe_display', function(){
            setTimeout(function(){
                calculateVat();
                calculateBilling();
            }, 50);
        });

        $('#curco').on('change', function(){
            calculateVat();
            calculateBilling();
        });

        // auto hitung jika ada old value
        document.addEventListener("DOMContentLoaded", function(){
            calculateVat();
        });
    </script>

    {{-- script untuk address --}}
    <script>
        $('#cusno').on('change', function () {

            const cusno = $(this).val();
            if (!cusno) return;

            const oldDeltoArray = @json(old('delto', []));

            $.get('/get-mstmas-delto', { cusno }, function(res){

                if(!res.success) return;

                $('.delto-select').each(function(){
                    const index = $(this).data('index');
                    let options = '<option disabled>Pilih Site</option>';

                    res.data.forEach(item=>{
                        const selected = (oldDeltoArray[index] == item.shpto) ? 'selected' : '';
                        options += `<option value="${item.shpto}" ${selected}>${item.shpto}</option>`;
                    });

                    $('#delto-' + index).html(options).trigger('change');

                    if(oldDeltoArray[index]){
                        $('#delto-' + index).trigger('change');
                    }
                });
            });
        });

        $(document).on('change', '.delto-select', function(){

            const cusno = $('#cusno').val();
            const delto = $(this).val();
            const index = $(this).data('index');

            if (!cusno || !delto) return;

            $.get('/get-mstmas-detail', { cusno, delto }, function(res){

                if(!res.success) return;

                const d = res.data;

                $('#delto_name-' + index).val(d.shpnm ?? '-');
                $('#delto_attn-' + index).val(d.contp ?? '-');
                $('#delto_prov-' + index).val(d.province ?? '-');
                $('#delto_kab-' + index).val(d.kabupaten ?? '-');
                $('#delto_addrress-' + index).val(d.deliveryaddress ?? '-');
                $('#delto_phone-' + index).val(d.phone ?? '-');
            });
        });
    </script>

    {{-- Formating currency + raw --}}
    <script>
        function getLocale(currency) {
            switch(currency) {
                case "IDR": return "id-ID";
                case "USD": return "en-US";
                default: return "en-US";
            }
        }

        function formatCurrency(value, currency) {
            if (value === "" || value === null || isNaN(value)) return "";
            if (!currency) return value;

            const locale = getLocale(currency);

            const fraction = (currency === "IDR") ? 0 : 2;

            return new Intl.NumberFormat(locale, {
                style: 'currency',
                currency: currency,
                minimumFractionDigits: fraction,
                maximumFractionDigits: fraction
            }).format(value);
        }

        function attachPriceEvents(input, hidden, currencySelect) {
            if (input.dataset.priceAttached === "1") return;
            input.dataset.priceAttached = "1";

            input.addEventListener("keypress", (e) => {
                const allowed = /[\d.,]/;
                if (!allowed.test(e.key)) e.preventDefault();
            });

            input.addEventListener("input", (e) => {
                const currency = currencySelect.value;

                let display = e.target.value.replace(/[^\d.,]/g, "");
                e.target.value = display;

                if (currency === "IDR") {
                    const rawInt = display.replace(/[.,]/g, "");
                    hidden.value = rawInt ? parseInt(rawInt, 10) : "";
                    $(hidden).trigger('change');
                    calculateVat();
                    calculateBilling();
                    return;
                }

                const lastComma = display.lastIndexOf(",");
                const lastDot = display.lastIndexOf(".");
                const decPos = Math.max(lastComma, lastDot);

                let intPart = display;
                let decPart = "";

                if (decPos !== -1) {
                    intPart = display.slice(0, decPos);
                    decPart = display.slice(decPos + 1);
                }

                intPart = intPart.replace(/[.,]/g, "");

                // decimal max 2 digit
                decPart = decPart.replace(/[^\d]/g, "").slice(0, 2);

                const normalized = intPart + (decPart ? "." + decPart : "");

                hidden.value = normalized ? parseFloat(normalized) : "";
                $(hidden).trigger('change');
                calculateVat();
                calculateBilling();
            });


            input.addEventListener("blur", (e) => {
                const currency = currencySelect.value;
                if (hidden.value !== "" && hidden.value !== null) {
                    e.target.value = formatCurrency(hidden.value, currency);
                }
            });

            input.addEventListener("focus", (e) => {
                if (hidden.value !== "" && hidden.value !== null) {
                    e.target.value = hidden.value; // show raw
                }
            });
        }


        function initPriceFormatter(container = document) {
            const currencySelect = document.getElementById("curco");
            if (!currencySelect) return;

            container.querySelectorAll(".price-input").forEach((input) => {
                const rawTarget = input.dataset.rawTarget;
                if (!rawTarget) return;

                const hidden = document.getElementById(rawTarget);
                if (!hidden) return;

                attachPriceEvents(input, hidden, currencySelect);

                // old value -> format display
                if (hidden.value) {
                    input.value = formatCurrency(hidden.value, currencySelect.value);
                }
            });
        }

        document.addEventListener("DOMContentLoaded", function() {
            initPriceFormatter();

            $('#curco').on('change', function () {
                const newCurrency = $(this).val();
                if (!newCurrency) return;

                document.querySelectorAll(".price-input").forEach((input) => {
                    const rawTarget = input.dataset.rawTarget;
                    if (!rawTarget) return;

                    const hidden = document.getElementById(rawTarget);
                    if (!hidden) return;

                    if (hidden.value) {
                        input.value = formatCurrency(hidden.value, newCurrency);
                    }
                });
            });
        });
    </script>

    <script>
        // SweetAlert confirm submit
        document.addEventListener("DOMContentLoaded", function() {
            const form = document.getElementById('form-oc');
            form.addEventListener('submit', function (e) {
            e.preventDefault();
            Swal.fire({
                title: 'Konfirmasi Simpan',
                text: 'Apakah Anda yakin ingin menyimpan data ini?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, Simpan!',
                cancelButtonText: 'Batal'
            }).then((res)=>{
                if(res.isConfirmed){
                Swal.fire({ title:'Menyimpan...', text:'Mohon tunggu sebentar', icon:'info', showConfirmButton:false, allowOutsideClick:false, allowEscapeKey:false, didOpen:()=>Swal.showLoading() });
                form.submit();
                }
            });
            });
        });
    </script>
@endpush
@endsection
