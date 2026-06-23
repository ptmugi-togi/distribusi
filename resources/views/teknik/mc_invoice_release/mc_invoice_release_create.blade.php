@extends('layout.main')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/global.css') }}">
@endpush

@section('container')
    <main id="main" class="main">
        <div class="d-flex justify-content-between align-items-center">
            <div class="pagetitle">
                <h1>Tambah Data MC Invoice Releaes</h1>
                <nav>
                    <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('mc_invoice_release.index') }}">List MC Invoice Releaes</a></li>
                    <li class="breadcrumb-item active">MC Invoice Releaes Create</li>
                    </ol>
                </nav>
            </div>
            <div class="card">
                <h5 class="p-2"><b>Branch : {{ auth()->user()->cabang }}</b></h5>
            </div>
        </div>

        <section class="section">
            <form id="form-mc" action="{{ route('mc_invoice_release.store') }}" method="POST">
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
                        <label for="refno" class="form-label">MC No.</label><span class="text-danger"> *</span>
                        <select class="form-control select2" id="refno" name="refno">
                            <option value="" disabled {{ old('refno') ? '' : 'selected' }}>Silahkan Pilih MC</option>
                        </select>
                        <input type="hidden" class="form-control" name="dorfc" id="dorfc" value="{{ old('dorfc') }}" required>
                        <input type="hidden" class="form-control" name="donom" id="donom" value="{{ old('donom') }}" required>
                        <input type="hidden" class="form-control" name="divco" id="divco" value="{{ old('divco') }}" required>
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

                    {{-- table pilih phaes --}}
                    <div class="col-md-12 mt-3" id="phase-container" style="display:none">
                        <label class="form-label">Select Phase</label>

                        <input type="hidden" name="phase" id="phase" value="{{ old('phase') }}">
                        <table class="table table-bordered table-striped" id="phase-table">
                            <thead>
                                <tr>
                                    <th>Select</th>
                                    <th>Phase</th>
                                    <th>Termin</th>
                                    <th>Gross</th>
                                    <th>Discount</th>
                                    <th>Net</th>
                                    <th>VAT</th>
                                    <th>Billing</th>
                                    <th>No Invoice</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>

                    <div id="product-container" class="product-container"></div>

                    <input type="hidden" class="form-control" name="toppc" id="toppc" value="{{ old('toppc') }}">

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
                        placeholder:'Silahkan Pilih MC',

                        ajax:{
                            url:"{{ url('/search-mc') }}",
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
                                            id:item.mcid, // tetap simpan id asli
                                            text:item.formc + ' - ' + item.refno
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
                        "{{ route('mc_invoice_release.generate-invno') }}",
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
                let mcid = e.params.data.id;
                $.get("{{ url('/get-mc') }}/"+mcid,function(res){
                       
                        let mc = res.mc;

                        $('#dorfc').val(mc.formc);
                        $('#donom').val(mc.refno);
                        $('#divco').val(mc.depo);

                        $('#cust').val(mc.cusna);
                        $('#cusno').val(mc.cusno);
                        $('#cuspo').val(mc.mcnom);

                        // VAT
                        $('#vatax').val(mc.vatax);
                        $('#vat_label').text('VAT (' + mc.vatax + '%)');

                        mcusAddress = [
                            mc.offad,
                            mc.offad2,
                            mc.offad3,
                            mc.offad4
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
                    }
                );

                $.get("{{ url('/get-mc-detail') }}/"+mcid, function(res){
                        let html = '';
                        let firstAvailable = true;

                        $.each(res.detail,function(i,item){

                            let disabled = '';
                            let checked = '';

                            if(item.sts01 === 'I'){
                                disabled = 'disabled';
                            }else if(firstAvailable){
                                checked = 'checked';
                                firstAvailable = false;
                            }

                            html += `
                            <tr>
                                <td>
                                    <input 
                                    type="radio"
                                    class="phase-select"
                                    name="phase_select"
                                    value="${item.phase}"
                                    ${checked}
                                    ${disabled}
                                    data-phase="${item.phase}"
                                    data-toppc="${item.toppc}"
                                    data-gramt="${item.gramt}"
                                    data-odisa="${item.odisa}"
                                    data-ntamt="${item.ntamt}"
                                    data-txamt="${item.txamt}"
                                    data-blamt="${item.blamt}">
                                </td>

                                <td>${item.phase}</td>
                                <td>${item.toppc}%</td>
                                <td>${formatNumber(item.gramt)}</td>
                                <td>${formatNumber(item.odisa)}</td>
                                <td>${formatNumber(item.ntamt)}</td>
                                <td>${formatNumber(item.txamt)}</td>
                                <td>${formatNumber(item.blamt)}</td>
                                <td>${item.invfc ?? '-'} - ${item.invno ?? '-'}</td>
                            </tr>
                            `;
                        });

                        $('#phase-table tbody').html(html);

                        $('#phase-container').show();

                        $('.phase-select:checked').trigger('change');
                    }
                );

                $.get("{{ url('/get-mc-product') }}/"+mcid,function(res){
                        let html = '';

                        $.each(res.product,function(i,item){

                            html += `

                            <div class="row">
                                <div class="col-md-6 mt-3">
                                    <label for="product_name" class="form-label">Product Name ${i + 1}</label>
                                    <input type="text" class="form-control" value="${item.opron} - ${item.prona}" readonly style="background-color:#e9ecef">
                                    <input type="hidden" class="form-control" name="product_opron[]" value="${item.opron}" readonly style="background-color:#e9ecef">
                                </div>

                                <div class="col-md-6 mt-3">
                                    <label for="product_lotno" class="form-label">Product lotno ${i + 1}</label>
                                    <input type="text" class="form-control" name="product_lotno[]" value="${item.lotno}" readonly style="background-color:#e9ecef">
                                </div>

                                <input type="hidden" name="product_trqty[]" value="1">
                            </div>
                            `;
                        });

                        $('#product-container').html(html);

                        $('#product-container').show();
                    }
                );
            });

            $(document).on('change','.phase-select',function(){
                if($(this).is(':disabled')){
                    return false;
                }
                let row = $(this);
                $('#phase').val(row.data('phase'));
                $('#gramt').val(row.data('gramt'));
                $('#odisa').val(row.data('odisa'));
                $('#ntamt').val(row.data('ntamt'));
                $('#txamt').val(row.data('txamt'));
                $('#blamt').val(row.data('blamt'));
                $('#toppc').val(row.data('toppc'));
                $('#gramt_display').val(formatNumber(row.data('gramt')));
                $('#odisa_display').val(formatNumber(row.data('odisa')));
                $('#ntamt_display').val(formatNumber(row.data('ntamt')));
                $('#txamt_display').val(formatNumber(row.data('txamt')));
                $('#blamt_display').val(formatNumber(row.data('blamt')));
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
