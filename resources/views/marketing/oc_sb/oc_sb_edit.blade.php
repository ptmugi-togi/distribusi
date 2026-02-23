@extends('layout.main')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/global.css') }}">
@endpush

@section('container')
<main id="main" class="main">
    <div class="d-flex justify-content-between align-items-center">
        <div class="pagetitle">
        <h1>Edit Data OC PROJECT (SB) ({{ $ocsb->ocsbid }})</h1>
        <nav>
            <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
            <li class="breadcrumb-item"><a href="{{ route('oc_sb.index') }}">List OC PROJECT (SB)</a></li>
            <li class="breadcrumb-item active">OC PROJECT (SB) Edit</li>
            </ol>
        </nav>
        </div>
    </div>

    <section class="section">
        <form id="form-oc" action="{{ route('oc_sb.update', $ocsb->ocsbid) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="row">
            <input type="text" class="form-control" id="formc" name="formc" value="{{ $ocsb->formc }}" hidden>
            <input type="text" class="form-control" id="braco" name="braco" value="{{ $ocsb->braco }}" hidden>
                
            <div class="col-md-4 mt-3">
                <label for="sorno" class="form-label">OC No.</label><span class="text-danger"> *</span>
                <input type="text" class="form-control" name="sorno" id="sorno" value="{{ $ocsb->sorno }}" required readonly style="background-color:#e9ecef">
            </div>

            <div class="col-md-4 mt-3">
                <label for="sordt" class="form-label">OC Date</label><span class="text-danger"> *</span>
                <input type="date" class="form-control" name="sordt" id="sordt" value="{{ $ocsb->sordt }}" min="{{ $minDate }}" required readonly style="background-color:#e9ecef">
                <input type="text" name="priod" id="priod" value="{{ $ocsb->priod }}" hidden>
            </div>

            <div class="col-md-4 mt-3">
                <label for="depo" class="form-label">Depo</label>
                <input type="text" class="form-control" name="depo" id="depo" value="{{ $ocsb->depo }}" readonly style="background-color:#e9ecef">
            </div>

            <div class="col-md-6 mt-3">
                <label for="cusno" class="form-label">Customer</label><span class="text-danger"> *</span>
                <input type="text" class="form-control" name="cusno" id="cusno_display" value="{{ $ocsb->cusno }} - {{ $ocsb->mcusmas->cusna }}" required readonly style="background-color:#e9ecef">
                <input type="text" class="form-control" id="cusno" value="{{ $ocsb->cusno }}" hidden>
            </div>

            <div class="col-md-6 mt-3">
                <label for="sreno" class="form-label">Sales Rep.</label><span class="text-danger"> *</span>
                <input type="text" class="form-control" name="sreno" id="sreno" value="{{ $ocsb->sreno }} - {{ $ocsb->msreno->srena }}" required readonly style="background-color:#e9ecef">
            </div>

            <div class="col-md-4 mt-3">
                <label for="pcuto" class="form-label">Plan Cut Off</label><span class="text-danger"> *</span>
                <input type="date" class="form-control" name="pcuto" id="pcuto" value="{{ $ocsb->pcuto }}" required readonly style="background-color:#e9ecef">
            </div>

            <div class="col-md-4 mt-3">
                <label for="curco" class="form-label">Currency Code</label><span class="text-danger"> *</span>
                <input type="text" class="form-control" name="curco" id="curco" value="{{ $ocsb->curco }}" required readonly style="background-color:#e9ecef">
            </div>

            <div class="col-md-4 mt-3">
                <label for="crate" class="form-label">Currency Rate</label><span class="text-danger"> *</span>
                <input type="text" class="form-control" name="crate_d" id="currency_rate_display" value="{{ $ocsb->crate }}" required readonly style="background-color:#e9ecef">
                <input type="text" class="form-control" name="crate" id="currency_rate" value="{{ $ocsb->crate }}" required hidden>
            </div>

            <div class="col-md-4 mt-3">
                <label for="gross" class="form-label">Gross Value</label>
                <input type="text" class="form-control price-input" id="gross_display" value="{{ $ocsb->gross ? number_format($ocsb->gross, 2, '.', '') : '' }}" data-raw-target="gross_raw" readonly style="background-color:#e9ecef">

                <input type="text" name="gross_hdr" id="gross_raw" value="{{ $ocsb->gross }}" hidden>
            </div>

            <div class="col-md-4 mt-3">
                <label for="odisa_hdr" class="form-label">Official Discount</label>
                <input type="text" class="form-control price-input" id="odisa_display" value="{{ $ocsb->odisa ? number_format($ocsb->odisa, 2, '.', '') : '' }}" data-raw-target="odisa_raw" readonly style="background-color:#e9ecef">

                <input type="text" name="odisa_hdr" id="odisa_raw" value="{{ $ocsb->odisa }}" hidden>
            </div>
            
            <div class="col-md-4 mt-3">
                <label for="insfe" class="form-label">Installation</label>
                <input type="text" class="form-control price-input" id="insfe_display" value="{{ $ocsb->insfe }}" data-raw-target="insfe_raw">

                <input type="text" name="insfe" id="insfe_raw" value="{{ $ocsb->insfe }}" hidden>
            </div>

            <div class="col-md-6 mt-3">
                <label for="vatax" class="form-label">VAT {{ $ocsb->vatax }}%</label>
                <input type="text" id="tax_percent" name="vatax" value="{{ $ocsb->vatax }}" hidden>
                <input type="text" class="form-control price-input" id="vatax_display" value="{{ $ocsb->vtamt }}" data-raw-target="vatax_raw" readonly style="background-color:#e9ecef">

                <input type="text" name="vtamt" id="vatax_raw" value="{{ $ocsb->vtamt }}" hidden>
            </div>

            <div class="col-md-6 mt-3">
                <label for="billv_hdr" class="form-label">Billing Amount</label>
                <input type="text" class="form-control price-input" id="billv_display" value="{{ $ocsb->billv }}" data-raw-target="billv_raw" readonly style="background-color:#e9ecef">

                <input type="text" name="billv_hdr" id="billv_raw" value="{{ $ocsb->billv }}" hidden>
            </div>
            
            <div class="col-md-6 mt-3">
                <label for="nodeb" class="form-label">Disposisi EB#</label><span class="text-danger"> *</span>
                <input type="text" class="form-control" name="nodeb" id="nodeb" value="{{ $ocsb->nodeb }}" required>
            </div>
            
            <div class="col-md-6 mt-3">
                <label for="edisa_hdr" class="form-label">EB</label>
                <input type="text" class="form-control price-input" id="edisa_display" value="{{ $ocsb->edisa ? number_format($ocsb->edisa, 2, '.', '') : '' }}" data-raw-target="edisa_raw">

                <input type="text" name="edisa_hdr" id="edisa_raw" value="{{ $ocsb->edisa }}" hidden>
            </div>

            <div class="col-md-6 mt-3">
                <label for="cuspo" class="form-label">Customer PO</label><span class="text-danger"> *</span>
                <input type="text" class="form-control" name="cuspo" id="cuspo" value="{{ $ocsb->cuspo }}" required>
            </div>

            <div class="col-md-12 mt-3"> 
                <label class="form-label">Notes</label>
                <textarea type="text" class="form-control" name="noteh" id="noteh" maxlength="200">{{ $ocsb->noteh }}</textarea>
                <div class="form-text text-danger text-end" style="font-size:0.7rem;">Maksimal 200 karakter</div>
            </div>

            @include('marketing.oc_sb.partial_edit.oc_sb_edit_detail_installation')

            {{-- Detail Invoicing --}}
            <div class="row mt-4">
                <h4 class="my-2">OC Invoicing (Edit)</h4>

                <div class="accordion" id="accordionOCInvoicing">
                    @foreach ($detailsInvoicing as $i => $dinv)
                    <div class="accordion-item" id="accordion-oc-invoicing-{{ $i }}">
                        <h2 class="accordion-header d-flex justify-content-between align-items-center">
                            <button class="accordion-button {{ $i > 0 ? 'collapsed' : '' }}" type="button" data-bs-toggle="collapse" data-bs-target="#details-oc-invoicing-{{ $i }}">
                                <span class="accordion-title">
                                    Payment Phase : {{ $dinv->phase }}
                                </span>
                            </button>

                            @if($i > 0)
                            <button type="button" class="btn btn-sm btn-danger mx-2" onclick="removeOCInvoicing({{ $i }})">
                                <i class="bi bi-trash-fill"></i>
                            </button>
                            @endif
                        </h2>

                        <div id="details-oc-invoicing-{{ $i }}" class="accordion-collapse collapse {{ $i == 0 ? 'show' : '' }}" data-bs-parent="#accordionOCInvoicing">
                            <div class="accordion-body">
                                <div class="row">
                                    <input type="hidden" name="dinv_id[]" value="{{ $dinv->id }}">

                                    <div class="col-md-6 mt-3">
                                        <label class="form-label">Deskripsi Termin</label><span class="text-danger">*</span>
                                        <input type="text" class="form-control" name="descr[]" id="descr_oc_{{ $i }}" value="{{ $dinv->descr }}" required>
                                    </div>

                                    <div class="col-md-6 mt-3">
                                        <label class="form-label">Term Percentage (%)</label><span class="text-danger">*</span>
                                        <input type="number" class="form-control" name="toppc[]" id="toppc_oc_{{ $i }}" value="{{ $dinv->toppc }}" oninput="this.value=this.value.replace(/[^0-9]/g,''); validateTermPercentage(this)" required>
                                    </div>

                                    <div class="col-md-6 mt-3">
                                        <label class="form-label">Gross Amount</label>
                                        <input type="text" class="form-control price-input" id="gross_display_oc_{{ $i }}" data-raw-target="gross_raw_oc_{{ $i }}" readonly style="background-color:#e9ecef">

                                        <input type="hidden" name="gross[]" id="gross_raw_oc_{{ $i }}" value="{{ $dinv->gross }}">
                                    </div>

                                    <div class="col-md-6 mt-3">
                                        <label class="form-label">Official Discount</label>
                                        <input type="text" class="form-control price-input" id="odisa_display_oc_{{ $i }}" data-raw-target="odisa_raw_oc_{{ $i }}" readonly style="background-color:#e9ecef">

                                        <input type="hidden" name="odisa[]" id="odisa_raw_oc_{{ $i }}" value="{{ $dinv->odisa }}">
                                    </div>

                                    <div class="col-md-6 mt-3">
                                        <label class="form-label">Net Amount</label>
                                        <input type="text" class="form-control price-input" id="ntamt_display_oc_{{ $i }}" data-raw-target="ntamt_raw_oc_{{ $i }}" readonly style="background-color:#e9ecef">

                                        <input type="hidden" name="ntamt[]" id="ntamt_raw_oc_{{ $i }}" value="{{ $dinv->ntamt }}">
                                    </div>

                                    <div class="col-md-6 mt-3">
                                        <label class="form-label">Billing Amount</label>
                                        <input type="text" class="form-control price-input" id="blamt_display_oc_{{ $i }}" data-raw-target="blamt_raw_oc_{{ $i }}" readonly style="background-color:#e9ecef">

                                        <input type="hidden" name="blamt[]" id="blamt_raw_oc_{{ $i }}" value="{{ $dinv->blamt }}">
                                    </div>

                                    <div class="col-md-6 mt-3">
                                        <label class="form-label">Extra Discount</label>
                                        <input type="text" class="form-control price-input" id="edisa_display_oc_{{ $i }}" data-raw-target="edisa_raw_oc_{{ $i }}" readonly style="background-color:#e9ecef">

                                        <input type="hidden" name="edisa[]" id="edisa_raw_oc_{{ $i }}" value="{{ $dinv->edisa }}">
                                    </div>

                                    <div class="col-md-6 mt-3">
                                        <label class="form-label">Plan Invoicing</label><span class="text-danger">*</span>
                                        <input type="date" class="form-control" name="billd[]" value="{{ $dinv->billd }}" required>
                                    </div>

                                    @for ($q = 1; $q <= 5; $q++)
                                        @php
                                            $percent = $dinv->{'smqp'.$q};
                                            $branch  = $dinv->{'smqtb'.$q};
                                            $sales   = $dinv->{'smqts'.$q};
                                        @endphp

                                        <div class="col-md-3 mt-3">
                                            <h5 style="margin-top:35px">Quota {{ $q }} :</h5>
                                        </div>

                                        <div class="col-md-3 mt-3">
                                            <label class="form-label">Split (%)</label>
                                            <input type="number" name="smqp{{ $q }}[]" class="form-control" id="smqp{{ $q }}-oc-{{ $i }}" value="{{ $percent }}" oninput="this.value=this.value.replace(/[^0-9.]/g,''); validateQuota({{ $i }},event)">
                                        </div>

                                        <div class="col-md-3 mt-3">
                                            <label class="form-label">Branch</label>
                                            <select name="smqtb{{ $q }}[]" id="smqtb{{ $q }}-oc-{{ $i }}" class="form-control select2">
                                                <option value="">Silahkan Pilih Branch</option>
                                                @foreach ($branches as $b)
                                                    <option value="{{ $b->braco }}"
                                                        {{ $branch == $b->braco ? 'selected' : '' }}>
                                                        {{ $b->braco }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="col-md-3 mt-3">
                                            <label class="form-label">Sales Rep.</label>
                                            <select name="smqts{{ $q }}[]" id="smqts{{ $q }}-oc-{{ $i }}" class="form-control select2">
                                                @if($sales)
                                                    <option value="{{ $sales }}" selected>{{ $sales }}</option>
                                                @else
                                                    <option value="">Silahkan Pilih Sales Rep</option>
                                                @endif
                                            </select>
                                        </div>
                                    @endfor

                                    <div class="col-md-12 mt-3">
                                        <label class="form-label">Notes</label>
                                        <textarea class="form-control" name="noted_invoicing[]" maxlength="200">{{ $dinv->noted }}</textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach

                </div>

                <div class="text-end">
                    <button type="button" id="btn-add-phase" class="btn mt-3" style="background-color:#4456f1;color:#fff" onclick="addOCInvoicing()">
                        Tambah Detail Invoicing
                    </button>
                </div>
            </div>

            <div class="mt-3 d-flex justify-content-between">
                <a href="{{ route('oc_sb.index') }}" class="btn btn-secondary">Kembali</a>
                <button type="submit" class="btn btn-primary">Simpan Data</button>
            </div>
        </form>
    </section>
</main>

@push('scripts')

    @include('marketing.oc_sb.partial_edit.oc_sb_add_detail_installation')
    @include('marketing.oc_sb.partial_edit.oc_sb_add_detail_invoicing')

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
            const cusno = $('#cusno').val();

            if (cusno) {
                $.get('/get-mstmas-delto', { cusno }, function(res){

                    if(!res.success) return;

                    $('.delto-select').each(function(){

                        const currentVal = $(this).val();
                        let options = '<option disabled>Pilih Site</option>';

                        res.data.forEach(item => {
                            const selected = (currentVal == item.shpto) ? 'selected' : '';
                            options += `<option value="${item.shpto}" ${selected}>${item.shpto}</option>`;
                        });

                        $(this).html(options).trigger('change');
                    });

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

            $('.accordion-item').each(function(){

                const qty = parseFloat($(this).find('.qtyor-oc').val()) || 0;
                const price = parseFloat($(this).find('[id^="price_raw_oc_"]').val()) || 0;
                const discountPerUnit = parseFloat($(this).find('[id^="odisp_raw_oc_"]').val()) || 0;

                const grossDetail = price * qty;
                const discountDetail = discountPerUnit * qty;

                totalGross += grossDetail;
                totalDiscount += discountDetail;
            });

            const currency = $('#curco').val();

            $('#gross_raw').val(totalGross.toFixed(2));
            $('#odisa_raw').val(totalDiscount.toFixed(2));

            $('#gross_display').val(formatCurrency(totalGross, currency));
            $('#odisa_display').val(formatCurrency(totalDiscount, currency));

            calculateVat();
            calculateBilling();
        }
    </script>
    <script>
        function calculateVat() {

            const gross   = parseFloat($('#gross_raw').val()) || 0;
            const discount = parseFloat($('#odisa_raw').val()) || 0;
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
            const disc  = parseFloat($('#odisa_raw').val()) || 0;
            const installation  = parseFloat($('#insfe_raw').val()) || 0;
            const vat   = parseFloat($('#vatax_raw').val()) || 0;
            const curco = $('#curco').val();

            const bill = gross - disc + installation + vat;

            $('#billv_raw').val(bill.toFixed(2));
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
        $(document).on('input', '[id^="odisp_display_oc_"]', function(){
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

                    if (currencySelect.value === "IDR") {
                        hidden.value = parseInt(parseFloat(hidden.value));
                    }

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

    {{-- script invoicing --}}
    <script>
        $(document).ready(function () {
            $('[id^="smqp1-oc-"]').each(function(){
                const id = $(this).attr('id');
                const phaseIndex = id.split('-').pop();
                validateQuota(phaseIndex);
            });

            $('[id^="smqtb"]').each(function(){
                if($(this).val()){
                    $(this).trigger('change', [true]);
                }
            });
        });

        // {{-- hitungan term --}}
        function calculateTotalTerm() {
            let total = 0;
            $('input[id^="toppc_oc_"]').each(function () {
                total += parseFloat($(this).val()) || 0;
            });
            return total;
        }

        function validateTermPercentage(input) {
            const total = calculateTotalTerm();
            const value = parseFloat($(input).val()) || 0;

            if (total > 100) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Term Percentage Melebihi 100%',
                    text: 'Total Term Percentage tidak boleh lebih dari 100%',
                    confirmButtonColor: '#4456f1'
                });
                $(input).val('');
                return;
            }

            // Disable tombol add jika sudah 100%
            if (calculateTotalTerm() >= 100) {
                $('button[onclick="addOCInvoicing()"]').prop('disabled', true);
            } else {
                $('button[onclick="addOCInvoicing()"]').prop('disabled', false);
            }

            const phaseIndex = $(input).attr('id').split('_').pop();

            calculatePhaseAmounts(phaseIndex);
        }

        // {{-- hitungan otomatis untuk gross, official discount, net amt, bill amt, extradics --}}
        function calculatePhaseAmounts(phaseIndex) {

            const percent = parseFloat($(`#toppc_oc_${phaseIndex}`).val()) || 0;
            const ratio = percent / 100;

            // get values header
            const grossMaster  = parseFloat($('#gross_raw').val()) || 0;
            const odisaMaster  = parseFloat($('#odisa_raw').val()) || 0;
            const billvMaster  = parseFloat($('#billv_raw').val()) || 0;
            const edisaMaster  = parseFloat($('#edisa_raw').val()) || 0;

            const netMaster = grossMaster - odisaMaster;

            // hitung berdasarkan ratio
            $(`#gross_raw_oc_${phaseIndex}`).val(grossMaster * ratio);
            $(`#odisa_raw_oc_${phaseIndex}`).val(odisaMaster * ratio);
            $(`#ntamt_raw_oc_${phaseIndex}`).val(netMaster * ratio);
            $(`#blamt_raw_oc_${phaseIndex}`).val(billvMaster * ratio);
            $(`#edisa_raw_oc_${phaseIndex}`).val(edisaMaster * ratio);

            // Set Value ke form detail invoicing 
            $(`#gross_display_oc_${phaseIndex}`).trigger('input');
            $(`#odisa_display_oc_${phaseIndex}`).trigger('input');
            $(`#ntamt_display_oc_${phaseIndex}`).trigger('input');
            $(`#blamt_display_oc_${phaseIndex}`).trigger('input');
            $(`#edisa_display_oc_${phaseIndex}`).trigger('input');

            // kalau pakai formatter
            initPriceFormatter(document.getElementById(`accordion-oc-invoicing-${phaseIndex}`));
        }

        // {{-- validate quota --}}
        function validateQuota(phaseIndex, event) {

            let total = 0;

            for (let i = 1; i <= 5; i++) {

                $(`#smqp${i}-oc-${phaseIndex}`).prop('disabled', false);

                $(`#smqtb${i}-oc-${phaseIndex}`)
                    .prop('disabled', false);

                $(`#smqts${i}-oc-${phaseIndex}`)
                    .prop('disabled', false);
            }

            for (let i = 1; i <= 5; i++) {

                let splitInput = $(`#smqp${i}-oc-${phaseIndex}`);
                let branchInput = $(`#smqtb${i}-oc-${phaseIndex}`);
                let salesInput  = $(`#smqts${i}-oc-${phaseIndex}`);

                let val = parseFloat(splitInput.val()) || 0;

                total += val;

                if (!val) {
                    branchInput.val('').prop('disabled', true).trigger('change');
                    salesInput.val('').prop('disabled', true).trigger('change');
                }

                if (total >= 100) {

                    for (let j = i + 1; j <= 5; j++) {

                        $(`#smqp${j}-oc-${phaseIndex}`)
                            .val('')
                            .prop('disabled', true);

                        $(`#smqtb${j}-oc-${phaseIndex}`)
                            .val('')
                            .prop('disabled', true)
                            .trigger('change');

                        $(`#smqts${j}-oc-${phaseIndex}`)
                            .val('')
                            .prop('disabled', true)
                            .trigger('change');
                    }

                    break;
                }
            }

            if (total > 100) {

                Swal.fire({
                    icon: 'warning',
                    title: 'Quota Melebihi 100%',
                    text: 'Total Quota tidak boleh lebih dari 100%',
                    confirmButtonColor: '#4456f1'
                });

                if (event && event.target) {
                    event.target.value = '';
                }

                validateQuota(phaseIndex);
                return;
            }
        }

        // {{-- get sales per branch invoicing --}}
        $(document).on('change', '[id^="smqtb"]', function (e, preserve = false) {

            const id = $(this).attr('id'); 
            const parts = id.split('-');
            const quotaPart = parts[0];
            const phaseIndex = parts[2];
            const quotaNumber = quotaPart.replace('smqtb','');
            const branchCode = $(this).val();

            const salesSelect = $(`#smqts${quotaNumber}-oc-${phaseIndex}`);
            const currentSales = salesSelect.val();

            if (!branchCode) {
                salesSelect.html('<option value="">Silahkan Pilih Sales Rep</option>');
                return;
            }

            $.ajax({
                url: '/get-sales-by-branch',
                type: 'GET',
                data: { branch: branchCode },
                success: function (res) {

                    let options = '<option value="" disabled selected>Silahkan Pilih Sales Rep</option>';

                    res.forEach(function (item) {

                        const selected =
                            preserve && currentSales == item.sreno
                                ? 'selected'
                                : '';

                        options += `<option value="${item.sreno}" ${selected}>
                                        ${item.sreno} - ${item.srena}
                                    </option>`;
                    });

                    salesSelect.html(options).trigger('change');
                }
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
