@extends('layout.main')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/global.css') }}">
@endpush

@section('container')
    <main id="main" class="main">
        <div class="d-flex justify-content-between align-items-center">
            <div class="pagetitle">
                <h1>Edit Delivery Note</h1>
            </div>

            <div class="card">
                <h5 class="p-2"><b>Branch : {{ auth()->user()->cabang }}</b></h5>
            </div>
        </div>

        <section class="section">
            <form id="form-do" action="{{ route('delivery_note.update', $dn->dnid) }}" method="POST">
                @csrf
                @method('PUT')

                <input type="hidden" name="braco" id="braco" value="{{ old('braco', $dn->braco) }}">
                <input type="hidden" name="formc" id="formc" value="{{ old('formc', $dn->formc) }}">
                <input type="hidden" name="priod" id="priod" value="{{ old('priod', $dn->priod) }}">

                <div class="row">
                    <div class="col-md-6 mt-3">
                        <label class="form-label">D/N No.</label>
                        <input type="text" class="form-control" name="dnnum" id="dnnum" value="{{ old('dnnum', $dn->dnnum) }}" readonly style="background-color:#e9ecef">
                    </div>

                    <div class="col-md-6 mt-3">
                        <label class="form-label">D/N Date</label>
                        <input type="date" class="form-control" name="dndat" id="dndat" value="{{ old('dndat', $dn->dndat) }}">
                    </div>

                    <div class="col-md-6 mt-3">
                        <label class="form-label">Depo</label>
                        <select name="depo" id="depo" class="form-control select2">
                            @foreach ($depo as $d)
                                <option value="{{ $d->depo }}" {{ old('depo', $dn->depo) == $d->depo ? 'selected' : '' }}>
                                    {{ $d->depo }} - {{ $d->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-6 mt-3">
                        <label class="form-label">Customer</label>
                        <select name="cusno" id="cusno" class="form-control select2">
                            @foreach ($customers as $cust)
                                <option value="{{ $cust->cusno }}" {{ old('cusno', $dn->cusno) == $cust->cusno ? 'selected' : '' }}>
                                    {{ $cust->cusno }} - {{ $cust->cusna }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-6 mt-3">
                        <label class="form-label">Billing Contact</label>
                        <input class="form-control" name="billcon" id="billcon" value="{{ old('billcon', $customer_detail->billn ?? '') }}" readonly style="background-color:#e9ecef">
                    </div>

                    <div class="col-md-6 mt-3">
                        <label class="form-label">Billing Address</label>
                        <textarea class="form-control" name="billadr" id="billadr" rows="2" readonly style="background-color:#e9ecef">{{ old('billadr', $billAddress) }}</textarea>
                    </div>

                    <div class="col-md-3 mt-3">
                        <label class="form-label">Delivery To</label>
                        <select class="form-control select2" name="shpto" id="shpto">
                            @foreach($shiptos as $shipto)
                                <option value="{{ $shipto->shpto }}"
                                    data-contact="{{ $shipto->shpnm }}"
                                    data-address="{{ $shipto->deliveryaddress }}"
                                    {{ old('shpto', $dn->delto) == $shipto->shpto ? 'selected' : '' }}>
                                    {{ $shipto->shpto }} - {{ $shipto->shpnm }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-3 mt-3">
                        <label class="form-label">Delivery Contact</label>
                        <input class="form-control" name="delcon" id="delcon" value="{{ old('delcon', $selectedShipto->shpnm ?? '') }}" readonly style="background-color:#e9ecef">
                    </div>

                    <div class="col-md-6 mt-3">
                        <label class="form-label">Delivery Address</label>
                        <textarea class="form-control" name="deladr" id="deladr" rows="2" readonly style="background-color:#e9ecef">{{ old('deladr', $selectedShipto->deliveryaddress ?? '') }}</textarea>
                    </div>

                    <div class="col-md-6 mt-3">
                        <label class="form-label">Currency</label>
                        <select class="form-control select2" name="curco" id="curco">
                            <option value="IDR" {{ old('curco', $dn->curco) == 'IDR' ? 'selected' : '' }}>IDR</option>
                            <option value="USD" {{ old('curco', $dn->curco) == 'USD' ? 'selected' : '' }}>USD</option>
                        </select>
                    </div>

                    <div class="col-md-6 mt-3">
                        <label class="form-label">Currency Rate</label>
                        <input type="text" class="form-control" id="crate_display">
                        <input type="hidden" name="crate" id="crate_raw" value="{{ old('crate', $dn->crate) }}">
                    </div>

                    <div class="col-md-6 mt-3">
                        <label class="form-label">Service Fee</label>
                        <input type="text" id="totalservice_display" class="form-control total-display" readonly style="background-color:#e9ecef">
                        <input type="hidden" name="totalservice" id="totalservice" value="{{ old('totalservice', $services->sum('gramt')) }}">
                    </div>

                    <div class="col-md-6 mt-3">
                        <label class="form-label">Sparepart</label>
                        <input type="text" id="totalsparepart_display" class="form-control total-display" readonly style="background-color:#e9ecef">
                        <input type="hidden" name="totalsparepart" id="totalsparepart" value="{{ old('totalsparepart', $spareparts->sum('gramt')) }}">
                    </div>

                    <div class="col-md-6 mt-3">
                        <label class="form-label">Off Discount</label>
                        <input type="text" id="odisa_display" class="form-control total-display" readonly style="background-color:#e9ecef">
                        <input type="hidden" name="odisa" id="odisa" value="{{ old('odisa', $dn->odisa) }}">
                    </div>

                    <div class="col-md-6 mt-3">
                        <label class="form-label">Net Amount</label>
                        <input type="text" id="ntamt_display" class="form-control total-display" readonly style="background-color:#e9ecef">
                        <input type="hidden" name="ntamt" id="ntamt" value="{{ old('ntamt', $dn->ntamt) }}">
                    </div>

                    <div class="col-md-6 mt-3">
                        <label class="form-label">Down Payment</label>
                        <input type="text" id="dpamt_display" class="form-control price-display">
                        <input type="hidden" name="dpamt" id="dpamt" class="price-raw" value="{{ old('dpamt', $dn->dpamt) }}">
                    </div>

                    <div class="col-md-6 mt-3">
                        <label class="form-label">VAT ({{ $tax->taxes }}%)</label>
                        <input type="text" id="txamt_display" class="form-control total-display" readonly style="background-color:#e9ecef">
                        <input type="hidden" name="txamt" id="txamt" value="{{ old('txamt', $dn->txamt) }}">
                        <input type="hidden" name="vatax" id="vatax" value="{{ old('vatax', $dn->vatax) }}">
                    </div>

                    <div class="col-md-6 mt-3">
                        <label class="form-label">Billing Amount</label>
                        <input type="text" id="blamt_display" class="form-control total-display" readonly style="background-color:#e9ecef">
                        <input type="hidden" name="blamt" id="blamt" value="{{ old('blamt', $dn->blamt) }}">
                    </div>

                    <div class="col-md-6 mt-3">
                        <label class="form-label">Quotation</label>
                        <input class="form-control" name="quote" id="quote" value="{{ old('quote', $dn->quote) }}">
                    </div>

                    <div class="col-md-6 mt-3">
                        <label class="form-label">Customer PO</label>
                        <input class="form-control" name="cuspo" id="cuspo" value="{{ old('cuspo', $dn->cuspo) }}">
                    </div>

                    <div class="col-md-12 mt-3">
                        <label class="form-label">Notes</label>
                        <textarea class="form-control" name="intxt" id="intxt" rows="2">{{ old('intxt', $dn->intxt) }}</textarea>
                    </div>
                </div>

                <hr>

                <div class="row">
                    <h4 class="my-2">Detail Service</h4>

                    <div class="accordion" id="accordionProduct">
                        @foreach($services as $i => $service)
                            @php
                                $fees = $serviceFees[$service->dnlin] ?? collect();
                            @endphp

                            <div class="accordion-item" id="row_{{ $i }}">
                                <h2 class="accordion-header d-flex align-items-center">
                                    <button class="accordion-button accordion-title {{ $i > 0 ? 'collapsed' : '' }}" type="button" data-bs-toggle="collapse" data-bs-target="#collapse{{ $i }}">
                                        Product : {{ $service->opron }} - {{ $service->prona }}
                                    </button>

                                    <button type="button" class="btn btn-danger btn-sm ms-2" onclick="removeRow({{ $i }})">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </h2>

                                <div id="collapse{{ $i }}" class="accordion-collapse collapse {{ $i == 0 ? 'show' : '' }}">
                                    <div class="accordion-body">
                                        <div class="row">
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label">Product</label>
                                                <select name="opron[]" id="opron_{{ $i }}" class="form-control select2 product-select" data-index="{{ $i }}">
                                                    <option value="{{ $service->opron }}" selected>{{ $service->opron }} - {{ $service->prona }}</option>
                                                </select>
                                            </div>

                                            <div class="col-md-6 mb-3">
                                                <label class="form-label">Quantity</label>
                                                <div class="input-group">
                                                    <input type="number" name="quantity_service[]" id="quantity_service_{{ $i }}" class="form-control" value="{{ $service->trqty }}">
                                                    <span class="input-group-text stdqu-label" id="stdqu_label_{{ $i }}">{{ $service->stdqu }}</span>
                                                    <input type="hidden" name="stdqu[]" id="stdqu_{{ $i }}" class="stdqu" value="{{ $service->stdqu }}">
                                                </div>
                                            </div>

                                            <div class="col-md-6 mb-3">
                                                <label class="form-label">Serial No</label>
                                                <input type="text" name="lotno[]" id="lotno_{{ $i }}" class="form-control" value="{{ $service->lotno }}">
                                            </div>

                                            <div class="col-md-12 mb-3">
                                                <label class="form-label">Service Type</label>
                                                <div id="service_container_{{ $i }}">
                                                    @foreach($fees as $fee)
                                                        <div class="row g-2 service-row mb-2">
                                                            <div class="col-md-7">
                                                                <select name="tofee[{{ $i }}][]" class="form-control select2 service-select">
                                                                    @foreach($serviceType as $type)
                                                                        <option value="{{ $type->tofee }}"
                                                                            data-serty="{{ $type->serty }}"
                                                                            data-descr="{{ $type->descr }}"
                                                                            {{ $fee->tofee == $type->tofee && $fee->serty == $type->serty ? 'selected' : '' }}>
                                                                            {{ $type->tofee }} - {{ $type->descr }}
                                                                        </option>
                                                                    @endforeach
                                                                </select>

                                                                <input type="hidden" name="serty[{{ $i }}][]" class="serty-input" value="{{ $fee->serty }}">
                                                                <input type="hidden" name="descr[{{ $i }}][]" class="descr-input" value="{{ $fee->descr }}">
                                                            </div>

                                                            <div class="col-md-4">
                                                                <input type="text" class="form-control fee-display price-display" value="{{ number_format($fee->gramt ?? 0, 0, ',', '.') }}">
                                                                <input type="hidden" name="fee[{{ $i }}][]" class="fee-input price-raw" value="{{ $fee->gramt ?? 0 }}">
                                                            </div>

                                                            <div class="col-md-1 d-grid">
                                                                <button type="button" class="btn btn-danger remove-service">
                                                                    <i class="bi bi-trash"></i>
                                                                </button>
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                </div>

                                                <button type="button" class="btn btn-sm btn-primary mt-2 add-service" data-index="{{ $i }}">
                                                    + Add Service
                                                </button>
                                            </div>

                                            <div class="col-md-6 mb-3">
                                                <label class="form-label">Total Fee</label>
                                                <input type="text" id="totalfee_display_{{ $i }}" class="form-control total-display" readonly style="background-color:#e9ecef" value="{{ number_format($service->gramt ?? 0, 0, ',', '.') }}">
                                                <input type="hidden" name="totalfee[]" id="totalfee_{{ $i }}" value="{{ $service->gramt ?? 0 }}">
                                            </div>

                                            <div class="col-md-6 mb-3">
                                                <label class="form-label">Discount</label>
                                                <input type="text" class="form-control odisa-service-display price-display" value="{{ number_format($service->odisa ?? 0, 0, ',', '.') }}">
                                                <input type="hidden" name="odisa_service[]" id="odisa_service_{{ $i }}" class="odisa-service-input price-raw" value="{{ $service->odisa ?? 0 }}">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="text-end mt-3">
                        <button type="button" class="btn btn-primary" id="btn-add-row">
                            Tambah Detail
                        </button>
                    </div>
                </div>

                <hr>

                <div class="row">
                    <h4 class="my-2">Sparepart</h4>

                    <div class="accordion" id="accordionSparepart">
                        @foreach($spareparts as $i => $sparepart)
                            <div class="accordion-item" id="sparepart_row_{{ $i }}">
                                <h2 class="accordion-header d-flex align-items-center">
                                    <button class="accordion-button accordion-title {{ $i > 0 ? 'collapsed' : '' }}" type="button" data-bs-toggle="collapse" data-bs-target="#sparepartCollapse{{ $i }}">
                                        Sparepart : {{ $sparepart->opron }} - {{ $sparepart->prona }}
                                    </button>

                                    <button type="button" class="btn btn-danger btn-sm ms-2" onclick="removeSparepartRow({{ $i }})">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </h2>

                                <div id="sparepartCollapse{{ $i }}" class="accordion-collapse collapse {{ $i == 0 ? 'show' : '' }}">
                                    <div class="accordion-body">
                                        <div class="row">
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label">Sparepart</label>
                                                <select name="sparepart[]" id="sparepart_{{ $i }}" class="form-control select2 sparepart-select" data-index="{{ $i }}">
                                                    <option value="{{ $sparepart->opron }}" selected>{{ $sparepart->opron }} - {{ $sparepart->prona }}</option>
                                                </select>
                                            </div>

                                            <div class="col-md-6 mb-3">
                                                <label class="form-label">Serial No</label>
                                                <select name="lotnos[]" id="lotnos_{{ $i }}" class="form-control select2" data-index="{{ $i }}">
                                                    @foreach(($sparepartLotnos[$sparepart->opron] ?? []) as $lot)
                                                        <option value="{{ $lot->lotno }}"
                                                            data-warco="{{ $lot->warco }}"
                                                            data-locco="{{ $lot->locco }}"
                                                            data-toqoh="{{ $lot->toqoh }}"
                                                            data-qunit="{{ $lot->qunit }}"
                                                            {{ $sparepart->lotno == $lot->lotno ? 'selected' : '' }}>
                                                            {{ $lot->lotno }} ({{ $lot->toqoh }} {{ $lot->qunit }})
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            <div class="col-md-6 mb-3">
                                                <label class="form-label">Warehouse</label>
                                                <input type="text" name="warco[]" id="warco_{{ $i }}" class="form-control" value="{{ $sparepart->warco }}" readonly style="background-color:#e9ecef">
                                            </div>

                                            <div class="col-md-6 mb-3">
                                                <label class="form-label">Location</label>
                                                <input type="text" name="locco[]" id="locco_{{ $i }}" class="form-control" value="{{ $sparepart->locco }}" readonly style="background-color:#e9ecef">
                                            </div>

                                            <div class="col-md-6 mb-3">
                                                <label class="form-label">Quantity Used</label>
                                                <div class="input-group">
                                                    <input type="number" name="quantity_sparepart[]" id="quantity_sparepart_{{ $i }}" class="form-control" value="{{ $sparepart->trqty }}">
                                                    <span class="input-group-text qunit-sparepart-label" id="qunit_sparepart_label_{{ $i }}">{{ $sparepart->qunit }}</span>
                                                    <input type="hidden" name="qunit[]" id="qunit_sparepart_{{ $i }}" value="{{ $sparepart->qunit }}">
                                                </div>
                                            </div>

                                            <div class="col-md-6 mb-3">
                                                <label class="form-label">Selling Price</label>
                                                <input type="text" class="form-control sparepart-price-display price-display" value="{{ number_format($sparepart->price ?? 0, 0, ',', '.') }}">
                                                <input type="hidden" name="price[]" id="price_{{ $i }}" class="sparepart-price-input price-raw" value="{{ $sparepart->price ?? 0 }}">
                                            </div>

                                            <div class="col-md-6 mb-3">
                                                <label class="form-label">Discount</label>
                                                <input type="text" class="form-control odisa-sparepart-display price-display" value="{{ number_format($sparepart->odisa ?? 0, 0, ',', '.') }}">
                                                <input type="hidden" name="odisa_sparepart[]" id="odisa_sparepart_{{ $i }}" class="odisa-sparepart-input price-raw" value="{{ $sparepart->odisa ?? 0 }}">
                                            </div>

                                            <div class="col-md-12 mt-3">
                                                <label class="form-label">Description</label>
                                                <textarea class="form-control" name="descr_sparepart[]" id="descr_sparepart_{{ $i }}" rows="2">{{ $sparepart->descr }}</textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="text-end mt-3">
                        <button type="button" class="btn btn-primary" id="btn-add-sparepart">
                            Tambah Sparepart
                        </button>
                    </div>
                </div>

                <div class="mt-3 d-flex justify-content-between">
                    <a href="{{ route('delivery_note.index') }}" class="btn btn-secondary">Kembali</a>
                    <button type="submit" class="btn btn-primary">Update Data</button>
                </div>
            </form>
        </section>
    </main>

    @push('scripts')
        <script>
            $(document).ready(function(){

                $('.select2').not('.product-select, .sparepart-select').select2({
                    width:'100%',
                    theme:'bootstrap-5'
                });

                setTimeout(function(){

                    $('select.product-select').each(function(){

                        if($(this).hasClass('select2-hidden-accessible')){
                            $(this).select2('destroy');
                        }

                        initProductSelect($(this));
                    });

                    $('select.sparepart-select').each(function(){

                        if($(this).hasClass('select2-hidden-accessible')){
                            $(this).select2('destroy');
                        }

                        initSparepartSelect($(this));
                    });

                    $('select[name="lotnos[]"]').each(function(){
                        let index = $(this).data('index');
                        let selected = $(this).find(':selected');

                        $('#warco_' + index).val(selected.data('warco') || '');
                        $('#locco_' + index).val(selected.data('locco') || '');
                        $('#qunit_sparepart_' + index).val(selected.data('qunit') || '');
                        $('#qunit_sparepart_label_' + index).text(selected.data('qunit') || '');
                    });

                    toggleCrateField($('#curco').val(), $('#crate_raw').val());
                    formatAllExistingRaw();
                    updateTotalFee();
                    updateHeaderSummary();

                }, 300);
            });

            $('#shpto').on('change', function(){
                let selected = $(this).find(':selected');

                $('#deladr').val(selected.data('address') || '');
                $('#delcon').val(selected.data('contact') || '');
            });

            function toggleCrateField(curco, crate){
                const crateDisplay = document.getElementById('crate_display');

                if(curco === 'IDR'){
                    crateDisplay.setAttribute('readonly', true);
                    crateDisplay.style.backgroundColor = '#e9ecef';

                    $('#crate_raw').val(1);
                    $('#crate_display').val(formatRupiah(1));
                }else{
                    crateDisplay.removeAttribute('readonly');
                    crateDisplay.style.backgroundColor = '';

                    if(crate){
                        $('#crate_raw').val(crate);
                        $('#crate_display').val(formatRupiah(crate));
                    }else{
                        $('#crate_raw').val('');
                        $('#crate_display').val('');
                    }
                }
            }

            $('#curco').on('change', function(){
                let curco = $(this).val();

                $.get("{{ route('get-currency-rate-dn') }}", { curco }, function(res){
                    toggleCrateField(curco, res.crate);
                    updateHeaderSummary();
                });
            });

            function formatNumber(value){
                if(value === null || value === undefined || value === '') return '';

                let number = parseFloat(value.toString().replace(/[^\d.-]/g, ''));

                if(isNaN(number)) return '';

                let curco = $('#curco').val() || 'IDR';
                let locale = curco === 'IDR' ? 'id-ID' : 'en-US';
                let fraction = curco === 'IDR' ? 0 : 2;

                return new Intl.NumberFormat(locale, {
                    style: 'currency',
                    currency: curco,
                    minimumFractionDigits: fraction,
                    maximumFractionDigits: fraction
                }).format(number);
            }

            function formatRupiah(value){
                if(value === null || value === undefined || value === '') return '';

                let number = parseFloat(value.toString().replace(/[^\d.-]/g, ''));

                if(isNaN(number)) return '';

                return new Intl.NumberFormat('id-ID', {
                    style:'currency',
                    currency:'IDR',
                    minimumFractionDigits:0,
                    maximumFractionDigits:0
                }).format(number);
            }

            function formatAllExistingRaw(){
                $('.price-raw').each(function(){
                    let raw = $(this).val();

                    if(raw){
                        $(this)
                            .closest('.col-md-4, .col-md-6')
                            .find('.price-display')
                            .val(formatNumber(raw));
                    }
                });

                $('.total-display').each(function(){
                    let id = $(this).attr('id');

                    if(!id) return;

                    let rawId = id.replace('_display', '');
                    let raw = $('#' + rawId).val();

                    if(raw){
                        $(this).val(formatNumber(raw));
                    }
                });
            }

            $(document).on('input', '.price-display', function(){
                let display = $(this);
                let raw = display.closest('.col-md-4, .col-md-6').find('.price-raw');

                let value = display.val().replace(/[^0-9.]/g, '');
                let parts = value.split('.');

                if(parts.length > 2){
                    value = parts[0] + '.' + parts.slice(1).join('');
                }

                raw.val(value);
                display.val(value);
            });

            $(document).on('focus', '.price-display', function(){
                let display = $(this);
                let raw = display.closest('.col-md-4, .col-md-6').find('.price-raw');

                let value = raw.val();

                if($('#curco').val() === 'IDR'){
                    value = parseFloat(value || 0).toString().replace(/\.0+$/, '');
                }

                display.val(value);
            });

            $(document).on('blur', '.price-display', function(){
                let display = $(this);
                let raw = display.closest('.col-md-4, .col-md-6').find('.price-raw');

                if(raw.val()){
                    display.val(formatNumber(raw.val()));
                }
            });

            function initProductSelect(el){
                el.select2({
                    placeholder:'Pilih Barang',
                    theme:'bootstrap-5',
                    width:'100%',
                    allowClear:true,
                    ajax:{
                        url:'{{ route("api.products") }}',
                        dataType:'json',
                        delay:250,
                        data:function(params){
                            return {
                                q: params.term || '',
                                page: params.page || 1
                            };
                        },
                        processResults:function(data){
                            return {
                                results:(data.results || []).map(item => ({
                                    id:item.id,
                                    text:item.text,
                                    prona:item.data_prona,
                                    stdqu:item.data_stdqu
                                })),
                                pagination:{
                                    more:data.pagination.more
                                }
                            };
                        }
                    },
                    minimumInputLength:0
                });
            }

            function initSparepartSelect(el){
                el.select2({
                    placeholder:'Pilih Barang',
                    theme:'bootstrap-5',
                    width:'100%',
                    allowClear:true,
                    ajax:{
                        url:'{{ route("api.spareparts") }}',
                        dataType:'json',
                        delay:250,
                        data:function(params){
                            return {
                                q: params.term || '',
                                page: params.page || 1
                            };
                        },
                        processResults:function(data){
                            return {
                                results:(data.results || []).map(item => ({
                                    id:item.id,
                                    text:item.text,
                                    prona:item.data_prona,
                                    stdqu:item.data_stdqu
                                })),
                                pagination:{
                                    more:data.pagination.more
                                }
                            };
                        }
                    },
                    minimumInputLength:0
                });
            }

            $(document).on('change', '.product-select', function(){
                let index = $(this).data('index');
                let data = $(this).select2('data')[0];

                $('#prona_' + index).val(data.prona || '');
                $('#stdqu_' + index).val(data.stdqu || '');
                $('#stdqu_label_' + index).text(data.stdqu || '');

                $(this).closest('.accordion-item').find('.accordion-title')
                    .text(data.text ? 'Product : ' + data.text : 'Product');
            });

            $(document).on('change', '.service-select', function(){
                let selected = $(this).find(':selected');

                $(this).closest('.service-row').find('.serty-input').val(selected.data('serty') || '');
                $(this).closest('.service-row').find('.descr-input').val(selected.data('descr') || '');
            });

            function updateTotalFee(){
                $('#accordionProduct .accordion-item').each(function(){
                    let row = $(this);
                    let total = 0;

                    row.find('.fee-input').each(function(){
                        total += parseFloat($(this).val()) || 0;
                    });

                    row.find('input[name="totalfee[]"]').val(total);
                    row.find('[id^="totalfee_display_"]').val(formatNumber(total));
                });

                updateHeaderSummary();
            }

            function updateHeaderSummary(){
                let totalService = 0;

                $('input[name="totalfee[]"]').each(function(){
                    totalService += parseFloat($(this).val()) || 0;
                });

                $('#totalservice').val(totalService);
                $('#totalservice_display').val(formatNumber(totalService));

                let totalSparepart = 0;

                $('#accordionSparepart .accordion-item').each(function(){
                    let qty = parseFloat($(this).find('input[name="quantity_sparepart[]"]').val()) || 0;
                    let price = parseFloat($(this).find('.sparepart-price-input').val()) || 0;

                    totalSparepart += qty * price;
                });

                $('#totalsparepart').val(totalSparepart);
                $('#totalsparepart_display').val(formatNumber(totalSparepart));

                let totalDiscount = 0;

                $('.odisa-service-input, .odisa-sparepart-input').each(function(){
                    totalDiscount += parseFloat($(this).val()) || 0;
                });

                $('#odisa').val(totalDiscount);
                $('#odisa_display').val(formatNumber(totalDiscount));

                let downPayment = parseFloat($('#dpamt').val()) || 0;

                let netBefore = totalService + totalSparepart - totalDiscount;

                $('#ntamt').val(netBefore);
                $('#ntamt_display').val(formatNumber(netBefore));

                let taxPercent = parseFloat($('#vatax').val()) || 0;
                let txamt = netBefore * (taxPercent / 100);

                $('#txamt').val(txamt);
                $('#txamt_display').val(formatNumber(txamt));

                let billingAmount = netBefore + txamt - downPayment;

                $('#blamt').val(billingAmount);
                $('#blamt_display').val(formatNumber(billingAmount));
            }

            $(document).on('input', '.fee-display', function(){
                updateTotalFee();
            });

            $(document).on('input', '.sparepart-price-display, input[name="quantity_sparepart[]"], .odisa-service-display, .odisa-sparepart-display, #dpamt_display', function(){
                updateHeaderSummary();
            });

            function getNextDetailIndex(){
                let used = [];

                $('#accordionProduct .accordion-item').each(function(){
                    let id = $(this).attr('id').split('_')[1];
                    used.push(parseInt(id));
                });

                let i = 0;

                while(used.includes(i)){
                    i++;
                }

                return i;
            }

            $('#btn-add-row').click(function(){
                let detailIndex = getNextDetailIndex();

                let html = `
                <div class="accordion-item" id="row_${detailIndex}">
                    <h2 class="accordion-header d-flex align-items-center">
                        <button class="accordion-button accordion-title collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse${detailIndex}">
                            Product
                        </button>

                        <button type="button" class="btn btn-danger btn-sm ms-2" onclick="removeRow(${detailIndex})">
                            <i class="bi bi-trash"></i>
                        </button>
                    </h2>

                    <div id="collapse${detailIndex}" class="accordion-collapse collapse show">
                        <div class="accordion-body">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Product</label>
                                    <select name="opron[]" id="opron_${detailIndex}" class="form-control select2 product-select" data-index="${detailIndex}">
                                        <option value="">Loading...</option>
                                    </select>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Quantity</label>
                                    <div class="input-group">
                                        <input type="number" name="quantity_service[]" id="quantity_service_${detailIndex}" class="form-control">
                                        <span class="input-group-text stdqu-label" id="stdqu_label_${detailIndex}"></span>
                                        <input type="hidden" name="stdqu[]" id="stdqu_${detailIndex}" class="stdqu">
                                    </div>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Serial No</label>
                                    <input type="text" name="lotno[]" id="lotno_${detailIndex}" class="form-control">
                                </div>

                                <div class="col-md-12 mb-3">
                                    <label class="form-label">Service Type</label>
                                    <div id="service_container_${detailIndex}">
                                    </div>

                                    <button type="button" class="btn btn-primary mt-2 add-service" data-index="${detailIndex}">
                                        + Add Service
                                    </button>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Total Fee</label>
                                    <input type="text" id="totalfee_display_${detailIndex}" class="form-control total-display" readonly style="background-color:#e9ecef">
                                    <input type="hidden" name="totalfee[]" id="totalfee_${detailIndex}">
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Discount</label>
                                    <input type="text" class="form-control odisa-service-display price-display">
                                    <input type="hidden" name="odisa_service[]" id="odisa_service_${detailIndex}" class="odisa-service-input price-raw">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                `;

                $('#accordionProduct').append(html);

                initProductSelect($('#opron_' + detailIndex));
            });

            $(document).on('click', '.add-service', function(){
                let index = $(this).data('index');

                let html = `
                <div class="row g-2 service-row mb-2">
                    <div class="col-md-7">
                        <select name="tofee[${index}][]" class="form-control select2 service-select">
                            <option value="" disabled selected>Pilih Service</option>
                            @foreach ($serviceType as $service)
                                <option value="{{ $service->tofee }}" data-serty="{{ $service->serty }}" data-descr="{{ $service->descr }}">
                                    {{ $service->tofee }} - {{ $service->descr }}
                                </option>
                            @endforeach
                        </select>

                        <input type="hidden" name="serty[${index}][]" class="serty-input">
                        <input type="hidden" name="descr[${index}][]" class="descr-input">
                    </div>

                    <div class="col-md-4">
                        <input type="text" class="form-control fee-display price-display" placeholder="Fee">
                        <input type="hidden" name="fee[${index}][]" class="fee-input price-raw">
                    </div>

                    <div class="col-md-1 d-grid">
                        <button type="button" class="btn btn-danger remove-service">
                            <i class="bi bi-trash"></i>
                        </button>
                    </div>
                </div>
                `;

                $('#service_container_' + index).append(html);

                $('#service_container_' + index).find('.service-select').last().select2({
                    width:'100%',
                    theme:'bootstrap-5',
                    placeholder:'Pilih Service'
                });
            });

            $(document).on('click', '.remove-service', function(){
                $(this).closest('.service-row').remove();
                updateTotalFee();
            });

            function removeRow(index){
                $('#row_' + index).remove();
                updateTotalFee();
            }

            function getNextSparepartIndex(){
                let used = [];

                $('#accordionSparepart .accordion-item').each(function(){
                    let id = $(this).attr('id').split('_')[2];
                    used.push(parseInt(id));
                });

                let i = 0;

                while(used.includes(i)){
                    i++;
                }

                return i;
            }

            $('#btn-add-sparepart').click(function(){
                let index = getNextSparepartIndex();

                let html = `
                <div class="accordion-item" id="sparepart_row_${index}">
                    <h2 class="accordion-header d-flex align-items-center">
                        <button class="accordion-button accordion-title collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#sparepartCollapse${index}">
                            Sparepart
                        </button>

                        <button type="button" class="btn btn-danger btn-sm ms-2" onclick="removeSparepartRow(${index})">
                            <i class="bi bi-trash"></i>
                        </button>
                    </h2>

                    <div id="sparepartCollapse${index}" class="accordion-collapse collapse show">
                        <div class="accordion-body">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Sparepart</label>
                                    <select name="sparepart[]" id="sparepart_${index}" class="form-control select2 sparepart-select" data-index="${index}">
                                        <option value="">Loading...</option>
                                    </select>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Serial No</label>
                                    <select name="lotnos[]" id="lotnos_${index}" class="form-control select2" data-index="${index}">
                                        <option value="">Pilih Serial No</option>
                                    </select>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Warehouse</label>
                                    <input type="text" name="warco[]" id="warco_${index}" class="form-control" readonly style="background-color:#e9ecef">
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Location</label>
                                    <input type="text" name="locco[]" id="locco_${index}" class="form-control" readonly style="background-color:#e9ecef">
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Quantity Used</label>
                                    <div class="input-group">
                                        <input type="number" name="quantity_sparepart[]" id="quantity_sparepart_${index}" class="form-control">
                                        <span class="input-group-text qunit-sparepart-label" id="qunit_sparepart_label_${index}"></span>
                                        <input type="hidden" name="qunit[]" id="qunit_sparepart_${index}">
                                    </div>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Selling Price</label>
                                    <input type="text" class="form-control sparepart-price-display price-display">
                                    <input type="hidden" name="price[]" id="price_${index}" class="sparepart-price-input price-raw">
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Discount</label>
                                    <input type="text" class="form-control odisa-sparepart-display price-display">
                                    <input type="hidden" name="odisa_sparepart[]" id="odisa_sparepart_${index}" class="odisa-sparepart-input price-raw">
                                </div>

                                <div class="col-md-12 mt-3">
                                    <label class="form-label">Description</label>
                                    <textarea class="form-control" name="descr_sparepart[]" id="descr_sparepart_${index}" rows="2"></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                `;

                $('#accordionSparepart').append(html);

                initSparepartSelect($('#sparepart_' + index));

                $('#lotnos_' + index).select2({
                    width:'100%',
                    theme:'bootstrap-5',
                    placeholder:'Pilih Serial No'
                });
            });

            $(document).on('change', '.sparepart-select', function(){
                let index = $(this).data('index');
                let data = $(this).select2('data')[0];

                $('#sparepart_prona_' + index).val(data.prona || '');
                $('#qunit_sparepart_' + index).val(data.stdqu || '');
                $('#qunit_sparepart_label_' + index).text(data.stdqu || '');

                $(this).closest('.accordion-item').find('.accordion-title')
                    .text(data.text ? 'Sparepart : ' + data.text : 'Sparepart');

                loadLotno(index, data.id);
            });

            function loadLotno(index, sparepart){
                let lotnoSelect = $('#lotnos_' + index);

                lotnoSelect.empty().append(`<option value="">Loading...</option>`);

                $.get("{{ route('get-lotno-dn') }}", { sparepart }, function(data){
                    lotnoSelect.empty().append(`<option value="" disabled selected>Pilih Serial No</option>`);

                    data.forEach(function(item){
                        lotnoSelect.append(`
                            <option value="${item.lotno}"
                                data-warco="${item.warco}"
                                data-locco="${item.locco}"
                                data-toqoh="${item.toqoh}"
                                data-qunit="${item.qunit}">
                                ${item.lotno} (${item.toqoh} ${item.qunit})
                            </option>
                        `);
                    });

                    lotnoSelect.trigger('change.select2');
                });
            }

            $(document).on('change', 'select[name="lotnos[]"]', function(){
                let index = $(this).data('index');
                let selected = $(this).find(':selected');

                $('#warco_' + index).val(selected.data('warco') || '');
                $('#locco_' + index).val(selected.data('locco') || '');
            });

            $(document).on('input', 'input[name="quantity_sparepart[]"]', function(){
                let input = $(this);
                let row = input.closest('.accordion-item');
                let lotno = row.find('select[name="lotnos[]"] option:selected');

                let toqoh = parseFloat(lotno.data('toqoh')) || 0;
                let qty = parseFloat(input.val()) || 0;

                if(toqoh > 0 && qty > toqoh){
                    Swal.fire({
                        icon:'error',
                        title:'Qty Melebihi Stock',
                        text:`Maximum stock hanya ${toqoh}`
                    });

                    input.val(toqoh);
                }

                updateHeaderSummary();
            });

            function removeSparepartRow(index){
                $('#sparepart_row_' + index).remove();
                updateHeaderSummary();
            }

            document.addEventListener("DOMContentLoaded", function(){
                const form = document.getElementById('form-do');

                form.addEventListener('submit', function(e){
                    e.preventDefault();

                    if(!form.checkValidity()){
                        form.classList.add('was-validated');
                        return;
                    }

                    Swal.fire({
                        title:'Konfirmasi Update',
                        text:'Apakah Anda yakin ingin mengupdate data ini?',
                        icon:'question',
                        showCancelButton:true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText:'Ya, Update!',
                        cancelButtonText:'Batal'
                    }).then((res)=>{
                        if(res.isConfirmed){
                            form.submit();
                        }
                    });
                });
            });
        </script>
    @endpush
@endsection