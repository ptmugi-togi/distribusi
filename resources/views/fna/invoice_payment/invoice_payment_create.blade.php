@extends('layout.main')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/global.css') }}">
@endpush

@section('container')
<main id="main" class="main">
  <div class="d-flex justify-content-between align-items-center">
    <div class="pagetitle">
      <h1>Tambah Invoice Payment</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
          <li class="breadcrumb-item"><a href="{{ route('invoice_payment.index') }}">List Invoice Payment</a></li>
          <li class="breadcrumb-item active">Invoice Payment Create</li>
        </ol>
      </nav>
    </div>
    <div class="card">
      <h5 class="p-2"><b>Branch : {{ auth()->user()->cabang }}</b></h5>
    </div>
  </div>

  <section class="section">
    <form id="form-invoice-payment" action="{{ route('invoice_payment.store') }}" method="POST">
      @csrf

      <input type="hidden" name="braco" id="braco" value="{{ auth()->user()->cabang }}">
      <input type="hidden" class="form-control" id="formc" name="formc" value="PC">

      <div class="row">
        <div class="col-md-6 mt-3">
          <label for="vcrno" class="form-label">Voucher No.</label><span class="text-danger"> *</span>
          <input type="text" id="vcrno" class="form-control" readonly style="background-color: #E9ECEF;">
          <input type="hidden" name="vcrno" id="vcrno_raw" value="{{ old('vcrno') }}">
        </div>

        <input type="hidden" name="priod" id="priod" value="{{ old('priod') }}">

        <div class="col-md-6 mt-3">
          <label for="pdate" class="form-label">Voucher Date</label><span class="text-danger"> *</span>
          <input type="date" class="form-control" name="pdate" id="pdate" value="{{ old('pdate', date('Y-m-d')) }}" required min="{{ $minDate }}">
        </div>

        <div class="col-md-6 mt-3">
          <label for="iorno" class="form-label">IOR No.</label>
          <input type="text" class="form-control" name="iorno" id="iorno" value="{{ old('iorno') }}">
        </div>

        <div class="col-md-6 mt-3">
          <label for="curco" class="form-label">Currency</label>
          <input type="text" class="form-control" name="curco" id="curco" value="{{ old('curco') }}" readonly style="background-color: #E9ECEF">
        </div>

        <div class="col-md-6 mt-3">
          <label for="prate" class="form-label">Kurs</label>
          <input type="text" class="form-control" name="prate_display" id="prate_display" value="{{ old('prate_display') }}">
          <input type="hidden" class="form-control" name="prate" id="prate_raw" value="{{ old('prate') }}">
        </div>
                
        <div class="col-md-6 mt-3">
          <label for="total" class="form-label">Total Amount</label>
          <input type="text" class="form-control price-input" id="total" value="{{ old('total') }}" readonly style="background-color: #E9ECEF">
          <input type="hidden" name="total" id="total_raw">
        </div>

        <div class="col-md-12 mt-3">
          <label for="noteh" class="form-label">Voucher Notes</label>
          <textarea class="form-control" name="noteh" id="noteh" rows="4">{{ old('noteh') }}</textarea>
        </div>
      </div>

      <div class="detail mt-5">
        @include('fna.invoice_payment.partial_create.invoice_payment_create_detail')
      </div>

      <div class="mt-3 d-flex justify-content-between">
        <a href="{{ route('invoice_payment.index') }}" class="btn btn-secondary">Kembali</a>
        <button type="submit" class="btn btn-primary">Simpan Data</button>
      </div>
    </form>
  </section>
</main>

    @push('scripts')
      {{-- ambil priod dari yyyymm pdate --}}
      <script>
        document.getElementById('pdate').addEventListener('change', function () {
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

              $('.select2').select2({
                  width:'100%',
                  theme:'bootstrap-5'
              });

              let pdate = $('#pdate').val();
              let formc = $('#formc').val();

              if(pdate && formc){
                  generateInv(pdate, formc);
                  generatePriod(pdate);
              }

              $('.invoice-select').each(function () {
                  loadInvoice($(this));
              });

              const prateDisplay = document.getElementById('prate_display');
          });

          // generate vcrno
          function generateInv(pdate, formc){
              $.get("{{ route('generate-invoice-payment-no') }}", {formc, pdate}, function(res){

                  let display = formc + '-' + res;

                  $('#vcrno').val(display);
                  $('#vcrno_raw').val(res);
              });
          }

          function generatePriod(pdate){
              if(!pdate) return;

              let year = pdate.substring(0, 4);
              let month = pdate.substring(5, 7);

              let priod = year + month;

              $('#priod').val(priod);
          }

          $('#pdate').on('change', function(){
              let pdate = $(this).val();
              let formc = $('#formc').val();

              if(pdate && formc){
                  generateInv(pdate, formc);
                  generatePriod(pdate);
              }
          });

          // prate change
          function toggleprateField(curco, prate = null){
              const prateDisplay = $('#prate_display');
              const prateRaw = $('#prate_raw');

              if(curco === 'IDR'){
                  prateDisplay.prop('readonly', true);
                  prateDisplay.css('background-color', '#E9ECEF');

                  prateRaw.val(1);
                  prateDisplay.val(formatRupiah(1));

              } else {
                  prateDisplay.prop('readonly', false);
                  prateDisplay.css('background-color', '');

                  if(prate){
                      prateRaw.val(prate);
                      prateDisplay.val(formatRupiah(prate));
                  } else {
                      prateRaw.val('');
                      prateDisplay.val('');
                  }
              }
          }

          // format currency from db
          function formatCurrency(value, currency){

              if(!currency) currency = 'IDR';

              let fraction = currency === 'IDR' ? 0 : 2;

              return new Intl.NumberFormat(
                  currency === 'IDR' ? 'id-ID' : 'en-US',
                  {
                      style: 'currency',
                      currency: currency,
                      minimumFractionDigits: fraction,
                      maximumFractionDigits: fraction
                  }
              ).format(value);
          }

          // format rupiah only
          function formatRupiah(value) {
              if (!value) return '';

              return new Intl.NumberFormat('id-ID', {
                  style: 'currency',
                  currency: 'IDR',
                  minimumFractionDigits: 0
              }).format(value);
          }

          const prateDisplay = document.getElementById('prate_display');
          const prateRaw = document.getElementById('prate_raw');

          prateDisplay.addEventListener('input', function(e){

              let value = e.target.value.replace(/[^\d]/g, '');

              prateRaw.value = value || '';

              e.target.value = value;
          });

          prateDisplay.addEventListener('blur', function(e){

              let value = prateRaw.value;

              if(value){
                  e.target.value = formatRupiah(value);
              }
          });

          prateDisplay.addEventListener('focus', function(e){

              let value = prateRaw.value;

              if(value){
                  e.target.value = value;
              }
          });

          // SweetAlert confirm submit
          document.addEventListener("DOMContentLoaded", function() {
              const form = document.getElementById('form-invoice-payment');
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
