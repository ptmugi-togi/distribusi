@extends('layout.main')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/global.css') }}">
@endpush

@section('container')
<main id="main" class="main">
  <div class="d-flex justify-content-between align-items-center">
    <div class="pagetitle">
      <h1>Tambah Writeoff A/R</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
          <li class="breadcrumb-item"><a href="{{ route('writeoff_ar.index') }}">List Writeoff A/R</a></li>
          <li class="breadcrumb-item active">Writeoff A/R Create</li>
        </ol>
      </nav>
    </div>
    <div class="card">
      <h5 class="p-2"><b>Branch : {{ auth()->user()->cabang }}</b></h5>
    </div>
  </div>

  <section class="section">
    <form id="form-invoice-payment" action="{{ route('writeoff_ar.store') }}" method="POST">
      @csrf

      <input type="hidden" name="braco" id="braco" value="{{ auth()->user()->cabang }}">
      <input type="hidden" class="form-control" id="formc" name="formc" value="WO">

      <div class="row">
        <div class="col-md-6 mt-3">
          <label for="vcrno" class="form-label">AR Write Off No.</label><span class="text-danger"> *</span>
          <input type="text" id="vcrno" class="form-control" readonly style="background-color: #E9ECEF;">
          <input type="hidden" name="vcrno" id="vcrno_raw" value="{{ old('vcrno') }}">
        </div>

        <input type="hidden" name="priod" id="priod" value="{{ old('priod') }}">

        <div class="col-md-6 mt-3">
          <label for="tradt" class="form-label">Write Off Date</label><span class="text-danger"> *</span>
          <input type="date" class="form-control" name="tradt" id="tradt" value="{{ old('tradt', date('Y-m-d')) }}" required min="{{ $minDate }}">
        </div>

        <div class="col-md-6 mt-3">
          <label for="refno" class="form-label">Reference No.</label>
          <input type="text" name="refno" id="refno" class="form-control" value="{{ old('refno') }}">
        </div>

        <div class="col-md-12 mt-3">
          <label for="noteh" class="form-label">WO Notes</label>
          <textarea class="form-control" name="noteh" id="noteh" rows="4">{{ old('noteh') }}</textarea>
        </div>
      </div>

      <div class="detail mt-5">
        @include('fna.writeoff_ar.partial_create.writeoff_ar_create_detail')
      </div>

      <div class="mt-3 d-flex justify-content-between">
        <a href="{{ route('writeoff_ar.index') }}" class="btn btn-secondary">Kembali</a>
        <button type="submit" class="btn btn-primary">Simpan Data</button>
      </div>
    </form>
  </section>
</main>

    @push('scripts')
      {{-- ambil priod dari yyyymm tradt --}}
      <script>
            document.getElementById('tradt').addEventListener('change', function () {
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

                let tradt = $('#tradt').val();
                let formc = $('#formc').val();

                if(tradt && formc){
                    generateInv(tradt, formc);
                    generatePriod(tradt);
                }

                $('.invoice-select').each(function () {
                    loadInvoice($(this));
                });

                const irateDisplay = document.getElementById('irate_display');
            });

            // generate vcrno
            function generateInv(tradt, formc){
                $.get("{{ route('generate-writeoff-ar-no') }}", {formc, tradt}, function(res){

                    let display = formc + '-' + res;

                    $('#vcrno').val(display);
                    $('#vcrno_raw').val(res);
                });
            }

            function generatePriod(tradt){
                if(!tradt) return;

                let year = tradt.substring(0, 4);
                let month = tradt.substring(5, 7);

                let priod = year + month;

                $('#priod').val(priod);
            }

            $('#tradt').on('change', function(){
                let tradt = $(this).val();
                let formc = $('#formc').val();

                if(tradt && formc){
                    generateInv(tradt, formc);
                    generatePriod(tradt);
                }
            });

            // irate change
            function toggleirateField(curco, irate = null){
                const irateDisplay = $('#irate_display');
                const irateRaw = $('#irate_raw');

                if(curco === 'IDR'){
                    irateDisplay.prop('readonly', true);
                    irateDisplay.css('background-color', '#E9ECEF');

                    irateRaw.val(1);
                    irateDisplay.val(formatRupiah(1));

                } else {
                    irateDisplay.prop('readonly', false);
                    irateDisplay.css('background-color', '');

                    if(irate){
                        irateRaw.val(irate);
                        irateDisplay.val(formatRupiah(irate));
                    } else {
                        irateRaw.val('');
                        irateDisplay.val('');
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


            $(document).on('input', '[id^="irate_"]:not([id^="irate_raw_"])', function () {
                let index = $(this).attr('id').split('_')[1];
                let value = $(this).val().replace(/[^\d]/g, '');

                $('#irate_raw_' + index).val(value);
                $(this).val(value);
            });

            $(document).on('blur', '[id^="irate_"]:not([id^="irate_raw_"])', function () {

                let index = $(this).attr('id').split('_')[1];
                let value = $('#irate_raw_' + index).val();

                if(value){
                    $(this).val(formatRupiah(value));
                }
            });

            $(document).on('focus', '[id^="irate_"]:not([id^="irate_raw_"])', function () {

                let index = $(this).attr('id').split('_')[1];
                let value = $('#irate_raw_' + index).val();

                if(value){
                    $(this).val(value);
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
