@extends('layout.main')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/global.css') }}">
@endpush

@section('container')
<main id="main" class="main">
    <div class="d-flex justify-content-between align-items-center">
        <div class="pagetitle">
        <h1>Tambah Data OC Retail (SA)</h1>
        <nav>
            <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
            <li class="breadcrumb-item"><a href="{{ route('oc.index') }}">List OC Retail (SA)</a></li>
            <li class="breadcrumb-item active">OC Retail (SA) Create</li>
            </ol>
        </nav>
        </div>
    </div>

    <section class="section">
        <form id="form-oc" action="{{ route('oc.store') }}" method="POST">
            @csrf

            <div class="row">
            <input type="text" class="form-control" id="formc" name="formc" value="SA" hidden>
            <input type="text" class="form-control" id="braco" name="braco" value="{{ auth()->user()->cabang }}" hidden>
                
            <div class="col-md-6 mt-3">
                <label for="sorno" class="form-label">OC No.</label><span class="text-danger"> *</span>
                <input type="text" class="form-control" name="sorno" id="sorno" value="{{ old('sorno') }}" required readonly style="background-color:#e9ecef">
            </div>

            <div class="col-md-6 mt-3">
                <label for="sordt" class="form-label">OC Date</label><span class="text-danger"> *</span>
                <input type="date" class="form-control" name="sordt" id="sordt" value="{{ old('sordt') }}" min="{{ $minDate }}" required>
                <input type="text" name="priod" id="priod" value="{{ old('priod' ?? '') }}" hidden>
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
                <label for="topay" class="form-label">Payment Term (days)</label><span class="text-danger"> *</span>
                <input type="number" class="form-control" name="topay" id="topay" value="{{ old('topay') }}" required oninput="this.value = this.value.replace(/[^0-9]/g, '');">
            </div>

            <div class="col-md-8 mt-3">
                <label for="cuspo" class="form-label">Customer PO</label><span class="text-danger"> *</span>
                <input type="text" class="form-control" name="cuspo" id="cuspo" value="{{ old('cuspo') }}" required>
            </div>

            <div class="col-md-6 mt-3">
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

            <div class="col-md-6 mt-3">
                <label for="crate" class="form-label">Currency Rate</label><span class="text-danger"> *</span>
                <input type="text" class="form-control" name="crate_d" id="currency_rate_display" value="{{ old('crate_d') }}" required readonly style="background-color:#e9ecef">
                <input type="text" class="form-control" name="crate" id="currency_rate" value="{{ old('crate') }}" required hidden>
            </div>

            <div class="col-md-4 mt-3">
                <label for="ebtyp" class="form-label">EB Type</label>
                <select name="ebtyp" id="ebtype" class="form-control select2">
                    <option value="" disabled {{ old('ebtyp') ? '' : 'selected' }}>Silahkan Pilih EB Type</option>
                    <option value="P" {{ old('ebtyp') == 'P' ? 'selected' : '' }}>P - Percent (%)</option>
                    <option value="V" {{ old('ebtyp') == 'V' ? 'selected' : '' }}>V - Value</option>
                </select>
            </div>

            <div class="col-md-4 mt-3">
                <label for="edisp" class="form-label">EB (%)</label>
                <input type="text" class="form-control" name="edisp" id="edisp" value="{{ old('edisp') }}" oninput="this.value = this.value.replace(/[^0-9.]/g, '');">
            </div>

            <div class="col-md-4 mt-3">
                <label for="edisa" class="form-label">EB (Amount)</label>
                <input type="text" class="form-control price-input" id="edisa_display" value="{{ old('edisa') ? number_format(old('edisa'), 2, '.', '') : '' }}" data-raw-target="edisa_raw">

                <input type="text" name="edisa" id="edisa_raw" value="{{ old('edisa') }}" hidden>
            </div>

            <div class="col-md-6 mt-3">
                <label for="nodeb" class="form-label">Disposisi EB#</label><span class="text-danger"> *</span>
                <input type="text" class="form-control" name="nodeb" id="nodeb" value="{{ old('nodeb') }}" required>
            </div>

            <div class="col-md-6 mt-3">
                <label for="dpper" class="form-label">Down Payment (%)</label>
                <input type="text" class="form-control" name="dpper" id="dpper" value="{{ old('dpper') }}" oninput="this.value = this.value.replace(/[^0-9.]/g, '');">
            </div>

            <hr class="my-4">

            <div class="col-md-4 mt-3">
                <label for="sqper" class="form-label">Split Quota (%)</label>
                <input type="text" class="form-control" name="sqper" id="sqper" value="{{ old('sqper') }}" oninput="this.value = this.value.replace(/[^0-9.]/g, '');">
            </div>

            <div class="col-md-4 mt-3">
                <label for="sqtbr" class="form-label">To Branch</label>
                <select name="sqtbr" id="sqtbr" class="form-control select2">
                    <option value="" disabled {{ old('sqtbr') ? '' : 'selected' }}>Silahkan Pilih Branch</option>
                    @foreach ($branches as $b)
                        <option value="{{ $b->braco }}" {{ old('sqtbr') == $b->braco ? 'selected' : '' }}>
                            {{ $b->braco }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-4 mt-3">
                <label for="sqtsr" class="form-label">Sales Rep.</label>
                <select name="sqtsr" id="sqtsr" class="form-control select2">
                    <option value="" disabled {{ old('sqtsr') ? '' : 'selected' }}>Silahkan Pilih Sales Rep</option>
                    @foreach ($sales as $s)
                        <option value="{{ $s->sreno }}" {{ old('sreno') == $s->sreno ? 'selected' : '' }}>
                            {{ $s->sreno }} - {{ $s->srena }}
                        </option>
                    @endforeach
                </select>
            </div>

            <hr class="my-4">

            <h3>Address</h3>

            
            <div class="col-md-4 mt-3">
                <label for="delto" class="form-label">Delivery To</label><span class="text-danger"> *</span>
                <select name="delto" id="delto" class="form-control select2" required>
                    <option value="" selected disabled>Silahkan Pilih Delivery To</option>
                </select>
            </div>
            
            <div class="col-md-4 mt-3">
                <label for="delto_name" class="form-label">Name</label>
                <input type="text" class="form-control" name="delto_name" id="delto_name" value="{{ old('delto_name') }}" readonly style="background-color:#e9ecef">
            </div>
            
            <div class="col-md-4 mt-3">
                <label for="delto_attn" class="form-label">Attn.</label>
                <input type="text" class="form-control" name="delto_attn" id="delto_attn" value="{{ old('delto_attn') }}" readonly style="background-color:#e9ecef">
            </div>
            
            <div class="col-md-6 mt-3">
                <label for="delto_prov" class="form-label">Provinsi</label>
                <input type="text" class="form-control" name="delto_prov" id="delto_prov" value="{{ old('delto_prov') }}" readonly style="background-color:#e9ecef">
            </div>

            <div class="col-md-6 mt-3">
                <label for="delto_kab" class="form-label">Kabupaten</label>
                <input type="text" class="form-control" name="delto_kab" id="delto_kab" value="{{ old('delto_kab') }}" readonly style="background-color:#e9ecef">
            </div>

            <div class="col-md-6 mt-3">
                <label for="delto_addrress" class="form-label">Address</label>
                <textarea type="text" class="form-control" name="delto_addrress" id="delto_addrress" cols="30" rows="5" readonly style="background-color:#e9ecef">{{ old('delto_addrress') }}</textarea>
            </div>

            <div class="col-md-6 mt-3">
                <label for="delto_phone" class="form-label">Phone</label>
                <input type="text" class="form-control" name="delto_phone" id="delto_phone" value="{{ old('delto_phone') }}" readonly style="background-color:#e9ecef">
            </div>

            <div class="col-md-12 mt-3"> 
                <label for="noteh" class="form-label">Notes</label>
                <textarea type="text" class="form-control" name="noteh" id="noteh" cols="30" rows="3"></textarea>
            </div>

            <div class="detail my-3">
                @include('marketing.oc_sa.partial_create.oc_create_detail')
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

            $('select[name="srcog[]"]').each(function(){
                const val = $(this).find('option[selected]').val();
                if(val){
                    $(this).val(val).trigger('change.select2');
                }
            });

            $('#edisp').attr('disabled', true).val('');
            $('#edisa_display').attr('disabled', true).val('');
            $('#edisa_raw').attr('disabled', true).val('');

            if (!$('#curco').val()) {
                $('#curco').val('IDR').trigger('change.select2');
            } else {
                $('#curco').trigger('change');
            }

            // buat cusno dan delto
            const oldCusno = "{{ old('cusno') }}";
            const oldDelto = "{{ old('delto') }}";

            if (oldCusno) {
                // trigger load delivery list
                $.ajax({
                    url: `/get-mstmas-delto`,
                    method: 'GET',
                    data: { cusno: oldCusno },
                    success: function (res) {

                        if (!res.success) return;

                        const list = res.data || [];

                        let options = '<option value="" disabled>Silahkan pilih Delivery To</option>';

                        list.forEach(item => {
                            const selected = (item.shpto == oldDelto) ? 'selected' : '';
                            options += `<option value="${item.shpto}" ${selected}>${item.shpto}</option>`;
                        });

                        $('#delto').html(options).trigger('change.select2');

                        // kalau ada old delto, ambil detailnya
                        if (oldDelto) {
                            loadDeltoDetail(oldCusno, oldDelto);
                        }
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
        });

        $('#sordt').on('change', function(){
            let sordt = $('#sordt').val();

            if(sordt){
                $.get("{{ route('generate-ocnum') }}", {sordt}, function(res){
                    $('#sorno').val(res);
                });
            }

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

        $('#ebtype').on('change', function(){
            const ebtype = $(this).val();

            if (!ebtype) return;

            if (ebtype === 'P') {
                $('#edisp').attr('disabled', false);
                $('#edisa_display').attr('disabled', true).val('');
                $('#edisa_raw').attr('disabled', true).val('');
            } else if (ebtype === 'V') {
                $('#edisa_display').attr('disabled', false);
                $('#edisa_raw').attr('disabled', false);
                $('#edisp').attr('disabled', true).val('');
            } else {
                $('#edisp').attr('disabled', true).val('');
                $('#edisa_display').attr('disabled', true).val('');
                $('#edisa_raw').attr('disabled', true).val('');
            }
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

    {{-- script untuk address --}}
    <script>
        function resetDelto() {
            $('#delto').html('<option value="" selected disabled>Silahkan pilih Delivery To</option>');
            $('#delto').val(null).trigger('change');
        }

        function resetAddressFields() {
            $('#delto_name').val('');
            $('#delto_attn').val('');
            $('#delto_prov').val('');
            $('#delto_kab').val('');
            $('#delto_addrress').val('');
            $('#delto_phone').val('');
        }

        $('#cusno').on('change', function () {
            const cusno = $(this).val();
            if (!cusno) return;

            resetDelto();
            resetAddressFields();

            $.ajax({
                url: `/get-mstmas-delto`,
                method: 'GET',
                data: { cusno },
                success: function (res) {
                    if (!res.success) {
                        alert('Gagal mengambil Delivery To.');
                        return;
                    }

                    const list = res.data || [];

                    if (list.length === 0) {
                        alert('Data Alamat tidak ditemukan untuk customer ini.');
                        return;
                    }

                    let options = '<option value="" selected disabled>Silahkan pilih Delivery To</option>';

                    list.forEach(item => {
                        options += `<option value="${item.shpto}">${item.shpto}</option>`;
                    });

                    $('#delto').html(options);

                    $('#delto').trigger('change.select2');
                },
                error: function (xhr) {
                    console.error(xhr.responseText);
                    alert('Error saat ambil Data Alamat.');
                }
            });
        });

        $('#delto').on('change', function () {
            const cusno = $('#cusno').val();
            const delto = $(this).val();

            if (!cusno || !delto) return;

            resetAddressFields();

            $.ajax({
                url: `/get-mstmas-detail`,
                method: 'GET',
                data: { cusno, delto },
                success: function (res) {
                    if (!res.success) {
                        alert(res.message || 'Gagal ambil detail.');
                        return;
                    }

                    const d = res.data;

                    $('#delto_name').val(d.shpnm ?? '-');
                    $('#delto_attn').val(d.contp ?? '-');
                    $('#delto_prov').val(d.province ?? '-');
                    $('#delto_kab').val(d.kabupaten ?? '-');
                    $('#delto_addrress').val(d.deliveryaddress ?? '-');
                    $('#delto_phone').val(d.phone ?? '-');
                },
                error: function (xhr) {
                    console.error(xhr.responseText);
                    alert('Error saat ambil detail');
                }
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
