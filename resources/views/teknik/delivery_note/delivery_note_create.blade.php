@extends('layout.main')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/global.css') }}">
@endpush

@section('container')
<main id="main" class="main">
  <div class="d-flex justify-content-between align-items-center">
    <div class="pagetitle">
      <h1>Tambah Data Delivery Note</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
          <li class="breadcrumb-item"><a href="{{ route('delivery_note.index') }}">List Delivery Note</a></li>
          <li class="breadcrumb-item active">Delivery Note Create</li>
        </ol>
      </nav>
    </div>
    <div class="card">
      <h5 class="p-2"><b>Branch : {{ auth()->user()->cabang }}</b></h5>
    </div>
  </div>

  <section class="section">
    <form id="form-do" action="{{ route('delivery_note.store') }}" method="POST">
      @csrf

      {{-- Global header (muncul dari awal) --}}
      <input type="text" name="braco" id="braco" value="{{ auth()->user()->cabang }}" hidden>

      <div class="row">
        <input type="text" class="form-control" id="formc" name="formc" id="formc-store" value="DN" hidden>

        <div class="col-md-6 mt-3">
          <label for="dnnum" class="form-label">D/N No.</label><span class="text-danger"> *</span>
          <input type="text" class="form-control" name="dnnum" id="dnnum" value="{{ old('dnnum') }}" required readonly style="background-color:#e9ecef">
        </div>

        <input type="hidden" name="priod" id="priod" value="{{ old('priod') }}">

        <div class="col-md-6 mt-3">
          <label for="dndat" class="form-label">D/N Date</label><span class="text-danger"> *</span>
          <input type="date" class="form-control" name="dndat" id="dndat" value="{{ old('dndat') }}" required min="{{ $minDate }}">
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
          <label for="billcon" class="form-label">Billing Contact</label>
          <input class="form-control" name="billcon" id="billcon" value="{{ old('billcon') }}" readonly style="background-color:#e9ecef">
        </div>

        <div class="col-md-6 mt-3">
          <label for="billadr" class="form-label">Billing Address</label><span class="text-danger"> *</label>
          <textarea class="form-control" name="billadr" id="billadr" rows="2" required readonly style="background-color:#e9ecef">{{ old('billadd') }}</textarea>
        </div>

        <div class="col-md-3 mt-3">
          <label for="shpto" class="form-label">Delivery To</label>
          <select class="form-control select2" name="shpto" id="shpto" value="{{ old('shpto') }}">
            <Option value="" disabled selected>Pilih Delivery To</Option>
          </select>
        </div>
        
        <div class="col-md-3 mt-3">
          <label for="delcon" class="form-label">Delivery Contact</label>
          <input class="form-control" name="delcon" id="delcon" value="{{ old('delcon') }}" readonly style="background-color:#e9ecef">
        </div>

        <div class="col-md-6 mt-3">
          <label for="deladr" class="form-label">Delivery Address</label><span class="text-danger"> *</label>
          <textarea class="form-control" name="deladr" id="deladr" rows="2" required readonly style="background-color:#e9ecef">{{ old('deladd') }}</textarea>
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
            <label class="form-label">Service Fee</label>
            <input type="text" id="totalservice_display" class="form-control total-display" readonly style="background-color:#e9ecef">
            <input type="hidden" name="totalservice" id="totalservice" class="price-raw">
        </div>

        <div class="col-md-6 mt-3">
            <label class="form-label">Sparepart</label>
            <input type="text" id="totalsparepart_display" class="form-control total-display" readonly style="background-color:#e9ecef">
            <input type="hidden" name="totalsparepart" id="totalsparepart" class="price-raw">
        </div>

        <div class="col-md-6 mt-3">
            <label class="form-label">Off Discount</label>
            <input type="text" id="odisa_display" class="form-control total-display" readonly style="background-color:#e9ecef">
            <input type="hidden" name="odisa" id="odisa" class="price-raw">
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
            <label class="form-label">Billing Amount</label>
            <input type="text" id="blamt_display" class="form-control total-display" readonly style="background-color:#e9ecef">
            <input type="hidden" name="blamt" id="blamt" class="price-raw">
        </div>

        <div class="col-md-6 mt-3">
          <label for="quote" class="form-label">Quotation</label>
          <input class="form-control" name="quote" id="quote" value="{{ old('quote') }}">
        </div>

        <div class="col-md-6 mt-3">
          <label for="cuspo" class="form-label">Customer PO</label>
          <input class="form-control" name="cuspo" id="cuspo" value="{{ old('cuspo') }}">
        </div>

        <div class="col-md-12 mt-3">
          <label for="intxt" class="form-label">Notes</label>
          <textarea class="form-control" name="intxt" id="intxt" rows="2">{{ old('intxt') }}</textarea>
        </div>
      </div>

      <div id="section-service">
        @include('teknik.delivery_note.partial_create.product_service_create')
      </div>

      <div id="section-sparepart">
        @include('teknik.delivery_note.partial_create.sparepart_create')
      </div>

      <div class="mt-3 d-flex justify-content-between">
        <a href="{{ route('delivery_note.index') }}" class="btn btn-secondary">Kembali</a>
        <button type="submit" class="btn btn-primary">Simpan Data</button>
      </div>
    </form>
  </section>
</main>

    @push('scripts')
        {{-- ambil priod dari yyyymm dndat --}}
        <script>
            document.getElementById('dndat').addEventListener('change', function () {
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
            });

            // generate dnnum
            function generateDnnum(){
                let formc = $('#formc').val();
                let depo = $('#depo').val();
                let dndat = $('#dndat').val();

                if(formc && depo && dndat){
                    $.get("{{ route('generate-dnnum') }}", {
                        formc,
                        depo,
                        dndat
                    }, function(res){

                        $('#dnnum').val(res);
                    });
                }
            }

            $('#formc, #depo, #dndat').on('change', function(){
                generateDnnum();
            });

            $('#cusno').on('change', function(){
                let cusno = $(this).val();

                if(cusno){
                    $.get("{{ route('get-bill-address-dn') }}", { cusno }, function(res){

                        // billing
                        $('#billadr').val(res.billadr);
                        $('#billcon').val(res.billcon);

                        // reset shpto
                        $('#shpto').empty();
                        $('#shpto').append(
                            `<option value="" disabled selected>Pilih Delivery To</option>`
                        );

                        // isi option shpto
                        $.each(res.shiptos, function(i, item){

                            $('#shpto').append(`
                                <option 
                                    value="${item.shpto}"
                                    data-address="${item.deliveryaddress ?? ''}"
                                    data-contact="${item.contp ?? ''}"
                                >
                                    ${item.shpto} - ${item.shpnm}
                                </option>
                            `);
                        });

                        $('#shpto').trigger('change.select2');
                    });
                }
            });

            $('#shpto').on('change', function(){
                let selected = $(this).find(':selected');

                $('#deladr').val(selected.data('address'));
                $('#delcon').val(selected.data('contact'));
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

            function updateHeaderSummary(){
                let totalService = 0;

                $('input[name="totalfee[]"]').each(function(){

                    totalService += parseFloat($(this).val()) || 0;
                });

                $('#totalservice').val(totalService);

                $('#totalservice_display').val(
                    formatNumber(totalService)
                );

                let totalSparepart = 0;

                $('.accordion-item').each(function(){

                    let qty_sparepart = parseFloat(
                        $(this).find('input[name="quantity_sparepart[]"]').val()
                    ) || 0;

                    let price = parseFloat(
                        $(this).find('.sparepart-price-input').val()
                    ) || 0;

                    totalSparepart += qty_sparepart * price;
                });

                $('#totalsparepart').val(totalSparepart);

                $('#totalsparepart_display').val(
                    formatNumber(totalSparepart)
                );

                let totalDiscount = 0;

                $('.odisa-service-input, .odisa-sparepart-input').each(function(){
                    totalDiscount += parseFloat($(this).val()) || 0;
                });

                $('#odisa').val(totalDiscount);
                $('#odisa_display').val(formatNumber(totalDiscount));

                let downPayment = parseFloat(
                    $('#dpamt').val()
                ) || 0;

                let netBefore = (
                    totalService
                    + totalSparepart
                    - totalDiscount
                );

                $('#ntamt').val(netBefore);

                $('#ntamt_display').val(
                    formatNumber(netBefore)
                );

                let taxPercent = {{ $tax->taxes ?? 0 }};

                let txamt = netBefore * (taxPercent / 100);

                $('#txamt').val(txamt);

                $('#txamt_display').val(
                    formatNumber(txamt)
                );

                let billingAmount = netBefore + txamt - downPayment;

                $('#blamt').val(billingAmount);

                $('#blamt_display').val(
                    formatNumber(billingAmount)
                );
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

            // SweetAlert confirm submit
            document.addEventListener("DOMContentLoaded", function() {
                const form = document.getElementById('form-do');
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
