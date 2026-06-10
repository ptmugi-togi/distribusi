@extends('layout.main')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/global.css') }}">
@endpush

@section('container')
<main id="main" class="main">
  <div class="d-flex justify-content-between align-items-center">
    <div class="pagetitle">
      <h1>Tambah Data Maintenance Contract</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
          <li class="breadcrumb-item"><a href="{{ route('maintenance_contract.index') }}">List Maintenance Contract</a></li>
          <li class="breadcrumb-item active">Maintenance Contract Create</li>
        </ol>
      </nav>
    </div>
    <div class="card">
      <h5 class="p-2"><b>Branch : {{ auth()->user()->cabang }}</b></h5>
    </div>
  </div>

  <section class="section">
    <form id="form-mc" action="{{ route('maintenance_contract.store') }}" method="POST">
      @csrf

      <input type="text" name="braco" id="braco" value="{{ auth()->user()->cabang }}" hidden>

      <div class="row">
        <input type="text" class="form-control" id="formc" name="formc" id="formc-store" value="MC" hidden>

        <div class="col-md-6 mt-3">
          <label for="refno" class="form-label">MC No.</label><span class="text-danger"> *</span>
          <input type="text" class="form-control" name="refno" id="refno" value="{{ old('refno') }}" required>
        </div>

        <input type="hidden" name="priod" id="priod" value="{{ old('priod') }}">

        <div class="col-md-6 mt-3">
          <label for="mcdat" class="form-label">MC Date</label><span class="text-danger"> *</span>
          <input type="date" class="form-control" name="mcdat" id="mcdat" value="{{ old('mcdat') }}" required min="{{ $minDate }}">
        </div>
        
        <div class="col-md-6 mt-3">
          <label for="cuspo" class="form-label">Customer PO</label>
          <input class="form-control" name="cuspo" id="cuspo" value="{{ old('cuspo') }}">
        </div>
        
        <div class="col-md-6 mt-3">
          <label for="depo" class="form-label">Depo</label><span class="text-danger"> *</span>
          <select name="depo" id="depo" class="form-control select2" required>
            <option value="" disabled selected>Pilih Depo</option>
            @foreach ($depo as $depo)
                <option value="{{ $depo->depo }}" {{ old('depo') == $depo->depo ? 'selected' : '' }}>
                  {{ $depo->depo }} - {{ $depo->name }}
                </option>
            @endforeach
          </select>
        </div>

        <div class="col-md-6 mt-3">
          <label for="cusno" class="form-label">Customer</label><span class="text-danger"> *</span>
          <select name="cusno" id="cusno" class="form-control select2" required>
            <option value="" disabled selected>Silahkan Pilih Customer</option>
            @foreach ($customers as $cust)
                <option value="{{ $cust->cusno }}" {{ old('cusno') == $cust->cusno ? 'selected' : '' }}>
                  {{ $cust->cusno }} - {{ $cust->cusna }}
                </option>
            @endforeach
          </select>
        </div>

        <div class="col-md-6 mt-3">
          <label for="cusna" class="form-label">Customer Name</label>
          <input class="form-control" name="cusna" id="cusna" value="{{ old('cusna') }}" readonly style="background-color:#e9ecef">
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
            <label class="form-label">Official Discount</label>
            <input type="text" id="odisa_display" class="form-control total-display" readonly style="background-color:#e9ecef">
            <input type="hidden" name="odisa" id="odisa" class="price-raw">
        </div>

        <div class="col-md-6 mt-3">
            <label class="form-label">MC Period <span class="text-danger">*</span></label>

            <div class="row g-2">
                <div class="col">
                    <input type="date" class="form-control" name="mcpriods" id="mcpriods" value="{{ old('mcpriods') }}" required min="{{ $minDate }}">
                </div>

                <div class="col">
                    <input type="date" class="form-control" name="mcpriode" id="mcpriode" value="{{ old('mcpriode') }}" required min="{{ $minDate }}">
                </div>
            </div>
        </div>

        <div class="col-md-6 mt-3">
            <label class="form-label">Gross Amount</label>
            <input type="text" id="gramt_display" class="form-control total-display" readonly style="background-color:#e9ecef">
            <input type="hidden" name="gramt" id="gramt" class="price-raw">
        </div>

        <div class="col-md-6 mt-3">
            <label class="form-label">Net Amount</label>
            <input type="text" id="ntamt_display" class="form-control total-display" readonly style="background-color:#e9ecef">
            <input type="hidden" name="ntamt" id="ntamt" class="price-raw">
        </div>

        <div class="col-md-6 mt-3">
            <label class="form-label">Down Payment</label>
            <input type="text" id="dpamt_display" class="form-control total-display">
            <input type="hidden" name="dpamt" id="dpamt" class="price-raw">
        </div>

        <div class="col-md-6 mt-3">
            <label class="form-label">VAT ({{$tax->taxes}}%)</label>
            <input type="text" id="txamt_display" class="form-control total-display" readonly style="background-color:#e9ecef">
            <input type="hidden" name="txamt" id="txamt" class="price-raw">
            <input type="hidden" name="vatax" id="vatax" value="{{$tax->taxes}}">
        </div>

        <div class="col-md-6 mt-3">
            <label class="form-label">MC Amount</label>
            <input type="text" id="blamt_display" class="form-control total-display" readonly style="background-color:#e9ecef">
            <input type="hidden" name="blamt" id="blamt" class="price-raw">
        </div>

        <div class="col-md-6 mt-3">
          <label for="opten" class="form-label">Option Entry</label>
          <select class="form-control select2" name="opten" id="opten" value="{{ old('opten') }}">
            <option value="1" selected>1 - MC Product</option>
            <option value="2">2 - MC Term of Payment</option>
          </select>
        </div>
        
        <div class="col-md-6 mt-3">
          <label for="billadr" class="form-label">Billing Address</label>
          <textarea class="form-control" name="billadr" id="billadr" rows="2" readonly style="background-color:#e9ecef">{{ old('billadd') }}</textarea>
        </div>

        <div class="col-md-6 mt-3">
            <label class="form-label">NPWP</label>
            <input type="text" id="npwp" name="npwp" class="form-control" readonly style="background-color:#e9ecef">
        </div>

        <div class="col-md-12 mt-3">
          <label for="intxt" class="form-label">Notes</label>
          <textarea class="form-control" name="intxt" id="intxt" rows="2">{{ old('intxt') }}</textarea>
        </div>
      </div>

      <div id="section-detail">
        @include('teknik.maintenance_contract.partial_create.detail_product')
      </div>

      <div id="section-phase">
        @include('teknik.maintenance_contract.partial_create.termin_phase')
      </div>

      <div class="mt-3 d-flex justify-content-between">
        <a href="{{ route('maintenance_contract.index') }}" class="btn btn-secondary">Kembali</a>
        <button type="submit" class="btn btn-primary">Simpan Data</button>
      </div>
    </form>
  </section>
</main>

    @push('scripts')
        {{-- ambil priod dari yyyymm mcdat --}}
        <script>
            document.getElementById('mcdat').addEventListener('change', function () {
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

                let curco = $('#curco').val();
                let crate = $('#crate_raw').val();

                toggleCrateField(curco, crate);
                toggleAddPhaseButton();
            });

            let customerAddress = {};
            let customerShiptos = [];

            $('#cusno').on('change', function(){
                let cusno = $(this).val();

                if(cusno){
                    $.get("{{ route('get-customer-address-mc') }}", { cusno }, function(res){

                        customerAddress = res;
                        customerShiptos = res.shiptos || [];

                        $('#billadr').val(res.address || '');
                        $('#cusna').val(res.cusna || '');

                        $('select[name="shpto[]"]').each(function(){
                            fillShiptoOptions($(this));
                        });
                    });
                }
            });

            function fillShiptoOptions(select){
                select.empty();

                select.append(`
                    <option value="" disabled selected>Pilih Delivery To</option>
                `);

                $.each(customerShiptos, function(i, item){
                    select.append(`
                        <option 
                            value="${item.shpto}"
                            data-address="${item.deliveryaddress ?? ''}"
                            data-contact="${item.contp ?? ''}"
                            data-phone="${item.phone ?? ''}"
                        >
                            ${item.shpto} - ${item.shpnm}
                        </option>
                    `);
                });

                select.trigger('change.select2');
            }

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

            function updateHeaderSummary(){
                let grossProduct = 0;

                $('input[name="gramt_product[]"]').each(function(){
                    grossProduct += parseNumber($(this).val());
                });

                let discount = parseNumber($('#odisa').val());
                let downPayment = parseNumber($('#dpamt').val());
                let taxPercent = parseNumber($('#vatax').val());

                let netAmount = grossProduct - discount;
                let vatAmount = netAmount * taxPercent / 100;
                let mcAmount = netAmount + vatAmount - downPayment;

                setMoney('#gramt', '#gramt_display', grossProduct);
                setMoney('#ntamt', '#ntamt_display', netAmount);
                setMoney('#txamt', '#txamt_display', vatAmount);
                setMoney('#blamt', '#blamt_display', mcAmount);

                updateTerminPhase();
            }

            function updateTerminPhase(){
                let headerGross = parseNumber($('#gramt').val());
                let headerDiscount = parseNumber($('#odisa').val());
                let taxPercent = parseNumber($('#vatax').val());

                let totalPercent = 0;

                $('input[name="toppc[]"]').each(function(){
                    let topInput = $(this);
                    let top = parseNumber(topInput.val());

                    totalPercent += top;

                    if(totalPercent > 100){
                        let allowed = top - (totalPercent - 100);
                        allowed = Math.max(0, allowed);

                        topInput.val(allowed);
                        top = allowed;
                        totalPercent = 100;
                    }

                    let row = topInput.closest('.accordion-item');

                    let gross = headerGross * top / 100;
                    let discount = headerDiscount * top / 100;
                    let net = gross - discount;
                    let vat = net * taxPercent / 100;
                    let billing = net + vat;

                    row.find('input[name="gramt_termin[]"]').val(gross);
                    row.find('[id^="gramt_termin_display_"]').val(formatNumber(gross));

                    row.find('input[name="odisa_termin[]"]').val(discount);
                    row.find('[id^="odisa_termin_display_"]').val(formatNumber(discount));

                    row.find('input[name="ntamt_termin[]"]').val(net);
                    row.find('[id^="ntamt_termin_display_"]').val(formatNumber(net));

                    row.find('input[name="txamt_termin[]"]').val(vat);
                    row.find('[id^="txamt_termin_display_"]').val(formatNumber(vat));

                    row.find('input[name="blamt_termin[]"]').val(billing);
                    row.find('[id^="blamt_termin_display_"]').val(formatNumber(billing));
                });

                toggleAddPhaseButton();
            }

            $(document).on('input', '.fee-display', function(){
                updateTotalFee();
            });

            $(document).on('input', '.sparepart-price-display', function(){
                updateHeaderSummary();
            });

            $(document).on('input', 'input[name="quantity_sparepart[]"]', function(){
                updateHeaderSummary();
            });

            $(document).on('input', '.odisa-service-display, .odisa-sparepart-display', function(){
                updateHeaderSummary();
            });

            $(document).on('input', '.price-display', function(){
                updateHeaderSummary();
            });

            $(document).on('input blur', 'input[name="gramt_product[]"], input[name="toppc[]"]', function(){
                updateHeaderSummary();
            });

            $(document).on('input', '#dpamt_display', function(){
                let value = $(this).val().replace(/[^\d]/g, '');

                $('#dpamt').val(value);
                $(this).val(formatNumber(value));

                updateHeaderSummary();
            });

            // SweetAlert confirm submit
            document.addEventListener("DOMContentLoaded", function() {
                const form = document.getElementById('form-mc');
                form.addEventListener('submit', function (e) {
                e.preventDefault();
                if (!form.checkValidity()) { form.classList.add('was-validated'); return; }
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
