@extends('layout.main')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/global.css') }}">
@endpush

@section('container')
    <main id="main" class="main">
        <div class="d-flex justify-content-between align-items-center">
            <div class="pagetitle">
                <h1>Tambah Data Service Invoice Releaes</h1>
                <nav>
                    <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('maintenance_contract.index') }}">List Service Invoice Releaes</a></li>
                    <li class="breadcrumb-item active">Service Invoice Releaes Create</li>
                    </ol>
                </nav>
            </div>
            <div class="card">
                <h5 class="p-2"><b>Branch : {{ auth()->user()->cabang }}</b></h5>
            </div>
        </div>

        <section class="section">
            <form id="form-mc" action="{{ route('service_invoice_release.store') }}" method="POST">
                @csrf

                <input type="text" name="braco" id="braco" value="{{ auth()->user()->cabang }}" hidden>

                <div class="row">
                    <input type="text" class="form-control" id="formc" name="formc" id="formc-store" value="SD" hidden>
                    
                    <div class="col-md-6 mt-3">
                        <label for="invdt" class="form-label">Invoice Date</label><span class="text-danger"> *</span>
                        <input type="date" class="form-control" name="invdt" id="invdt" value="{{ old('invdt') }}" required min="{{ $minDate }}">
                    </div>

                    <div class="col-md-6 mt-3">
                        <label for="duedt" class="form-label">Due Date</label><span class="text-danger"> *</span>
                        <input type="date" class="form-control" name="duedt" id="duedt" value="{{ old('duedt') }}" required min="{{ $minDate }}">
                    </div>

                    <div class="col-md-6 mt-3">
                        <label for="invno" class="form-label">Invoice No.</label><span class="text-danger"> *</span>
                        <input type="text" class="form-control" id="invno" name="invno" id="invno" value="{{ old('invno') }}" readonly style="background-color:#e9ecef">
                    </div>

                    <div class="col-md-6 mt-3">
                        <label for="refno" class="form-label">DN No.</label><span class="text-danger"> *</span>
                        <select class="form-control select2" id="refno" name="refno">
                            <option value="" disabled {{ old('refno') ? '' : 'selected' }}>Silahkan Pilih DN</option>
                        </select>
                        <input type="hidden" class="form-control" name="dorfc" id="dorfc" value="{{ old('dorfc') }}" required>
                        <input type="hidden" class="form-control" name="donom" id="donom" value="{{ old('donom') }}" required>
                    </div>
                    
                    <div class="col-md-6 mt-3">
                        <label for="cust" class="form-label">Customer Name</label>
                        <input type="text" class="form-control" name="cust" id="cust" value="{{ old('cust') }}" readonly style="background-color:#e9ecef">
                        <input type="hidden" class="form-control" name="cusno" id="cusno" value="{{ old('cusno') }}">
                    </div>

                    <input type="hidden" name="priod" id="priod" value="{{ old('priod') }}">
                    
                    <div class="col-md-6 mt-3">
                        <label for="cuspo" class="form-label">Customer PO</label>
                        <input class="form-control" name="cuspo" id="cuspo" value="{{ old('cuspo') }}" readonly style="background-color:#e9ecef">
                    </div>
                    
                    <div class="col-md-6 mt-3">
                    <label for="curco" class="form-label">Currency</label>
                    <select class="form-control select2" name="curco" id="curco" value="{{ old('curco') }}">
                        <option value="IDR" selected>IDR</option>
                        <option value="USD">USD</option>
                    </select>
                    </div>

                    <div class="col-md-6 mt-3">
                    <label for="crate" class="form-label">Currency Rate</label>
                    <input type="text" class="form-control" id="crate_display">
                    <input type="hidden" class="form-control" name="crate" id="crate_raw" value="{{ old('crate', 1) }}">
                    </div>

                    <div class="col-md-6 mt-3">
                        <label class="form-label">Gross Amount</label>
                        <input type="text" id="gramt_display" class="form-control total-display" readonly style="background-color:#e9ecef">
                        <input type="hidden" name="gramt" id="gramt" class="price-raw">
                    </div>

                    <div class="col-md-6 mt-3">
                        <label class="form-label">Off Dicount</label>
                        <input type="text" id="odisa_display" class="form-control total-display" readonly style="background-color:#e9ecef">
                        <input type="hidden" name="odisa" id="odisa" class="price-raw">
                        <input type="hidden" name="odisp" id="odisp" class="price-raw">
                    </div>

                    <div class="col-md-6 mt-3">
                        <label class="form-label">Net Amount</label>
                        <input type="text" id="ntamt_display" class="form-control total-display" readonly style="background-color:#e9ecef">
                        <input type="hidden" name="ntamt" id="ntamt" class="price-raw">
                    </div>

                    <div class="col-md-6 mt-3">
                        <label class="form-label">Down Payment</label>
                        <input type="text" id="dpamt_display" class="form-control total-display" readonly style="background-color:#e9ecef">
                        <input type="hidden" name="dpamt" id="dpamt" class="price-raw">
                    </div>

                    <div class="col-md-6 mt-3">
                        <label class="form-label" id="vat_label">VAT (%)</label>
                        <input type="text" id="txamt_display" class="form-control total-display" readonly style="background-color:#e9ecef">
                        <input type="hidden" name="txamt" id="txamt" class="price-raw">
                        <input type="hidden" name="vatax" id="vatax" value="{{ old('vatax') }}">
                    </div>

                    <div class="col-md-6 mt-3">
                        <label class="form-label">Billing Amount</label>
                        <input type="text" id="blamt_display" class="form-control total-display" readonly style="background-color:#e9ecef">
                        <input type="hidden" name="blamt" id="blamt" class="price-raw">
                    </div>

                    <div class="col-md-6 mt-3">
                        <label for="address_source" class="form-label">Select Address</label>
                        <select id="address_source" name="address_source" class="form-control select2">
                            <option value="">Silahkan Pilih Alamat</option>
                        </select>
                    </div>
                    
                    <div class="col-md-6 mt-3">
                        <label for="billadr" class="form-label">Invoice Address</label>
                        <textarea class="form-control" name="billadr" id="billadr" rows="2" readonly style="background-color:#e9ecef">{{ old('billadd') }}</textarea>
                    </div>

                    <div class="col-md-12 mt-3">
                        <label for="noteh" class="form-label">Notes</label>
                        <textarea class="form-control" name="noteh" id="noteh" rows="2">{{ old('noteh') }}</textarea>
                    </div>
                </div>

                <div class="mt-3 d-flex justify-content-between">
                    <a href="{{ route('maintenance_contract.index') }}" class="btn btn-secondary">Kembali</a>
                    <button type="submit" class="btn btn-primary">Release Invoice</button>
                </div>
                @include('teknik.service_invoice_release.partial_create.store_detail')
            </form>
        </section>
    </main>


    @push('scripts')
        {{-- ambil priod dari yyyymm invdt --}}
        <script>
            document.getElementById('invdt').addEventListener('change', function () {
                let tanggal = this.value;

                if (tanggal) {
                    let year = tanggal.substring(0, 4);
                    let month = tanggal.substring(5, 7);

                    let priod = year + month;
                    document.getElementById('priod').value = priod;
                }
            });
            </script>
            
            <script>
            $(document).ready(function(){
                $('.select2').select2({ width: '100%', theme: 'bootstrap-5' });

                setTimeout(function(){

                    $('#refno').select2({
                        theme:'bootstrap-5',
                        width:'100%',
                        placeholder:'Silahkan Pilih DN',

                        ajax:{
                            url:"{{ url('/search-dn') }}",
                            type:"GET",
                            dataType:"json",
                            delay:250,

                            data:function(params){
                                return {
                                    search: params.term || ''
                                };
                            },

                            processResults:function(data){
                                return {
                                    results:data.map(function(item){
                                        return {
                                            id:item.dnid, // tetap simpan id asli
                                            text:item.formc + ' - ' + item.dnnum
                                        }
                                    })
                                };
                            }
                        }
                    });

                },500);

                let curco = $('#curco').val();
                let crate = $('#crate_raw').val();

                toggleCrateField(curco, crate);
            });

            $('#invdt').on('change', function(){
                let invdt = $(this).val();

                if(invdt){
                    $.get(
                        "{{ route('service_invoice_release.generate-invno') }}",
                        {
                            formc: 'SD',
                            invdt: invdt
                        },
                        function(res){
                            $('#invno').val(res);
                        }
                    );
                }
            });

            let mcusAddress = '';
            let deliveryAddress = '';

            $('#refno').on('select2:select', function(e){
                let dnid = e.params.data.id;
                $.get(
                    "{{ url('/get-dn') }}/"+dnid,
                    function(res){
                       
                        let dn = res.dn;

                        $('#dorfc').val(dn.formc);
                        $('#donom').val(dn.dnnum);

                        $('#cust').val(dn.cusna);
                        $('#cusno').val(dn.cusno);
                        $('#cuspo').val(dn.cuspo);

                        // VAT
                        $('#vatax').val(dn.vatax);
                        $('#vat_label').text('VAT (' + dn.vatax + '%)');

                        mcusAddress = [
                            dn.offad,
                            dn.offad2,
                            dn.offad3,
                            dn.offad4
                        ]
                        .filter(Boolean)
                        .join(' ');

                        $('#address_source').empty();

                        // customer address
                        $('#address_source').append(`
                            <option value="0">
                                Customer Address
                            </option>
                        `);

                        // delivery address
                        $.each(res.delivery, function(i,item){

                            $('#address_source').append(`
                                <option 
                                    value="${i + 1}"
                                    data-address="${item.deliveryaddress}">
                                    Bill To ${i + 1} - ${item.deliveryaddress}
                                </option>
                            `);

                        });


                        $('#address_source').select2({
                            width:'100%',
                            theme:'bootstrap-5'
                        });

                        // default
                        $('#address_source').val('0')
                            .trigger('change');

                        $('#gramt').val(dn.gramt || 0);
                        $('#ntamt').val(dn.ntamt || 0);
                        $('#dpamt').val(dn.dpamt || 0);
                        $('#txamt').val(dn.txamt || 0);
                        $('#odisa').val(dn.odisa || 0);
                        $('#blamt').val(dn.blamt || 0);

                        let odisp = 0;

                        if(dn.gramt > 0){
                            odisp = (parseFloat(dn.odisa) / parseFloat(dn.gramt)) * 100;
                        }

                        $('#odisp').val(odisp.toFixed(2));


                        $('#gramt_display')
                            .val(formatNumber(dn.gramt || 0));

                        $('#ntamt_display')
                            .val(formatNumber(dn.ntamt || 0));

                        $('#txamt_display')
                            .val(formatNumber(dn.txamt || 0));

                        $('#odisa_display')
                            .val(formatNumber(dn.odisa || 0));

                        $('#blamt_display')
                            .val(formatNumber(dn.blamt || 0));
                    }
                );

                $.get("{{ url('/get-dn-detail') }}/"+dnid,function(res){
                        let tdna = '';
                        let tdnb = '';
                        let tdnc = '';

                        $.each(res.tdna,function(i,item){
                            tdna += `
                                <input type="hidden" name="tdna_dnlin[]" value="${item.dnlin}">
                                <input type="hidden" name="tdna_tofee[]" value="${item.tofee}">
                                <input type="hidden" name="tdna_descr[]" value="${item.descr}">
                                <input type="hidden" name="tdna_opron[]" value="${item.opron}">
                                <input type="hidden" name="tdna_trqty[]" value="${item.trqty}">
                                <input type="hidden" name="tdna_lotno[]" value="${item.lotno}">
                                <input type="hidden" name="tdna_gramt[]" value="${item.gramt}">
                                <input type="hidden" name="tdna_odisp[]" value="${item.odisp}">
                                <input type="hidden" name="tdna_netbe[]" value="${item.netbe}">
                            `;
                        });

                        $.each(res.tdnb,function(i,item){
                            tdnb += `
                                <input type="hidden" name="tdnb_dnlin[]" value="${item.dnlin}">
                                <input type="hidden" name="tdnb_serty[]" value="${item.serty}">
                                <input type="hidden" name="tdnb_tofee[]" value="${item.tofee}">
                                <input type="hidden" name="tdnb_gramt[]" value="${item.gramt}">
                                <input type="hidden" name="tdnb_odisp[]" value="${item.odisp}">
                                <input type="hidden" name="tdnb_odisa[]" value="${item.odisa}">
                                <input type="hidden" name="tdnb_netbe[]" value="${item.net}">
                            `;
                        });

                        $.each(res.tdnc,function(i,item){
                            tdnc += `
                                <input type="hidden" name="tdnc_opron[]" value="${item.opron}">
                                <input type="hidden" name="tdnc_price[]" value="${item.price}">
                                <input type="hidden" name="tdnc_trqty[]" value="${item.trqty}">
                                <input type="hidden" name="tdnc_lotno[]" value="${item.lotno}">
                                <input type="hidden" name="tdnc_gramt[]" value="${item.gramt}">
                                <input type="hidden" name="tdnc_odisa[]" value="${item.odisa}">
                                <input type="hidden" name="tdnc_odisp[]" value="${item.odisp}">
                                <input type="hidden" name="tdnc_netbe[]" value="${item.netbe}">
                            `;
                        });

                        $('#tdna-container').html(tdna);
                        $('#tdnb-container').html(tdnb);
                        $('#tdnc-container').html(tdnc);
                    }
                );
            });

            $('#address_source').on('change',function(){
                let val = $(this).val();
                if(val == "0"){
                    $('#billadr').val(mcusAddress);
                }else{
                    let selected = $(this).find(':selected');
                    $('#billadr').val(
                        selected.data('address')
                    );
                }
            });

            // crate change
            function formatNumber(value){
                if(value === null || value === undefined || value === ''){
                    return '';
                }

                let number = parseFloat(
                    value.toString().replace(/[^\d.-]/g, '')
                );

                if(isNaN(number)){
                    return '';
                }

                let curco = $('#curco').val() || 'IDR';

                let locale = curco === 'IDR'
                    ? 'id-ID'
                    : 'en-US';

                let fraction = curco === 'IDR'
                    ? 0
                    : 2;

                return new Intl.NumberFormat(locale, {
                    style: 'currency',
                    currency: curco,
                    minimumFractionDigits: fraction,
                    maximumFractionDigits: fraction
                }).format(number);
            }

            function formatRupiah(value){
                if(value === null || value === undefined || value === ''){
                    return '';
                }

                let number = parseFloat(
                    value.toString().replace(/[^\d.-]/g, '')
                );

                if(isNaN(number)){
                    return '';
                }

                return new Intl.NumberFormat('id-ID', {
                    style: 'currency',
                    currency: 'IDR',
                    minimumFractionDigits: 0,
                    maximumFractionDigits: 0
                }).format(number);
            }

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

            // currency change
            $('#curco').on('change', function(){

                let curco = $(this).val();

                if(curco){
                    $.get("{{ route('get-currency-rate-dn') }}", { curco }, function(res){
                        toggleCrateField(curco, res.crate);
                    });
                }
            });

            const crateDisplay = document.getElementById('crate_display');
            const crateRaw = document.getElementById('crate_raw');

            crateDisplay.addEventListener('input', function(e){

                let value = e.target.value.replace(/[^\d]/g, '');

                crateRaw.value = value || '';

                e.target.value = formatRupiah(value);
            });

            crateDisplay.addEventListener('blur', function(e){

                let value = crateRaw.value;

                if(value){
                    e.target.value = formatRupiah(value);
                }
            });

            crateDisplay.addEventListener('focus', function(e){

                let value = crateRaw.value;

                if(value){
                    e.target.value = formatRupiah(value);
                }
            });

            function parseNumber(value){
                return parseFloat((value || '').toString().replace(/[^\d.-]/g, '')) || 0;
            }

            function setMoney(rawSelector, displaySelector, value){
                $(rawSelector).val(value);
                $(displaySelector).val(formatNumber(value));
            }

            // SweetAlert confirm submit
            document.addEventListener("DOMContentLoaded", function() {
                const form = document.getElementById('form-mc');
                form.addEventListener('submit', function (e) {
                e.preventDefault();
                if (!form.checkValidity()) { form.classList.add('was-validated'); return; }
                Swal.fire({
                    title: 'Konfirmasi Release',
                    text: 'Apakah Anda yakin ingin release invoice ini?',
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Ya, Release!',
                    cancelButtonText: 'Batal'
                }).then((res)=>{
                    if(res.isConfirmed){
                    Swal.fire({ title:'Release...', text:'Mohon tunggu sebentar', icon:'info', showConfirmButton:false, allowOutsideClick:false, allowEscapeKey:false, didOpen:()=>Swal.showLoading() });
                    form.submit();
                    }
                });
                });
            });
        </script>
    @endpush
@endsection
