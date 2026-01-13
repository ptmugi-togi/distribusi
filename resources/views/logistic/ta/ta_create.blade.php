@extends('layout.main')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/global.css') }}">
@endpush

@section('container')
<main id="main" class="main">
  <div class="d-flex justify-content-between align-items-center">
    <div class="pagetitle">
      <h1>Tambah Data TA</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
          <li class="breadcrumb-item"><a href="{{ route('ta.index') }}">List TA</a></li>
          <li class="breadcrumb-item active">TA Create</li>
        </ol>
      </nav>
    </div>
    <div class="card">
      <h5 class="p-2"><b>Branch : {{ auth()->user()->cabang }}</b></h5>
    </div>
  </div>

  <section class="section">
    <form id="form-ta" action="{{ route('ta.store') }}" method="POST">
      @csrf

      {{-- Global header (muncul dari awal) --}}
      <input type="text" name="braco" id="braco" value="{{ auth()->user()->cabang }}" hidden>

      <div class="row">
        <div class="col-md-6 mt-3">
          <label for="formc" class="form-label">TA</label><span class="text-danger"> *</span>
          <input type="text" class="form-control" value="TA (TRANSFER NOTE)" disabled>
          <input type="text" class="form-control" id="formc" name="formc" id="formc-store" value="TA" hidden>
        </div>

        <div class="col-md-6 mt-3">
          <label for="warco" class="form-label">Warehouse</label><span class="text-danger"> *</span>
          <select class="form-control select2" name="warco" id="warco" required>
            <option value="" disabled selected>Pilih Warehouse</option>
            @foreach ($mwarco as $m)
              <option value="{{ $m->warco }}" {{ old('warco') == $m->warco ? 'selected' : '' }}>
                {{ $m->warco }}
              </option>
            @endforeach
          </select>
        </div>

        <div class="col-md-6 mt-3">
          <label for="trano" class="form-label">Transfer Note No.</label><span class="text-danger"> *</span>
          <input type="text" class="form-control" name="trano" id="trano" value="{{ old('trano') }}" required readonly style="background-color:#e9ecef">
        </div>

        <input type="text" name="priod" id="priod" value="{{ old('priod') }}" hidden>

        <div class="col-md-6 mt-3">
          <label for="tradt" class="form-label">Transfer Note Date</label><span class="text-danger"> *</span>
          <input type="date" class="form-control" name="tradt" id="tradt" value="{{ old('tradt') }}" required min="{{ $minDate }}">
        </div>

        <div class="col-md-6 mt-3">
          <label for="rqbrc" class="form-label">Request by Branch</label><span class="text-danger"> *</span>
          <select name="rqbrc" id="rqbrc" class="form-control select2" required>
            <option value="" disabled selected>Pilih Branch</option>
            @foreach ($mbranch as $m)
              <option value="{{ $m->braco }}" {{ old('rqbrc') == $m->braco ? 'selected' : '' }}>
                {{ $m->braco }}
              </option>
            @endforeach
          </select>
        </div>

        <div class="col-md-6 mt-3">
          <label for="sano" class="form-label">Stock Requisitoin No.</label><span class="text-danger"> *</span>
          <select name="sano" id="sano" class="form-control select2" required>
            <option value="" disabled selected>Pilih Request by Branch Terlebih Dahulu</option>
          </select>
          <input type="text" id="rfc01" name="rfc01" value="{{ old('rfc01') }}" hidden>
          <input type="text" id="ref01" name="ref01" value="{{ old('ref01') }}" hidden>
        </div>

        <div class="col-md-6 mt-3">
          <label for="exped" class="form-label">Expediter</label><span class="text-danger"> *</span>
          <select name="exped" id="exped" class="form-control select2" required>
            <option value="" disabled selected>Pilih Expediter</option>
            @foreach ($mexped as $mexp)
                <option value="{{ $mexp->ename }}" {{ old('exped') == $mexp->ename ? 'selected' : '' }}>
                  {{ $mexp->ename }}
                </option>
            @endforeach
          </select>
        </div>

        <div class="col-md-6 mt-3">
          <label for="trana" class="form-label">Transfer To Name</label>
          <input type="text" class="form-control" name="trana" id="trana" value="{{ old('trana') }}" disabled>
        </div>

        <div class="col-md-6 mt-3">
          <label for="tradres" class="form-label">Transfer To Address</label>
          <textarea class="form-control" name="tradres" id="tradres" rows="2" disabled>{{ old('tradres') }}</textarea>
        </div>

        <div class="col-md-6 mt-3">
          <label for="noteh" class="form-label">Notes</label>
          <textarea class="form-control" name="noteh" id="noteh" rows="2">{{ old('noteh') }}</textarea>
        </div>
      </div>

      <div id="section-ta">
        @include('logistic.ta.partial_create.ta_create_detail')
      </div>

      <div class="mt-3 d-flex justify-content-between">
        <a href="{{ route('ta.index') }}" class="btn btn-secondary">Kembali</a>
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
              $('.select2').select2({ width: '100%', theme: 'bootstrap-5' });

              let oldwarco = "{{ old('warco') }}";

              if(oldwarco){
                  $('#warco').val(oldwarco).trigger('change');
              }

              let oldrqbrc = "{{ old('rqbrc') }}";

              if(oldrqbrc){
                  $('#rqbrc').val(oldrqbrc).trigger('change');
              }
          });

          // generate trano
          $('#warco, #tradt').on('change', function(){
              let braco = $('#braco').val();
              let warco = $('#warco').val();
              let formc = $('#formc').val();
              let tradt = $('#tradt').val();

              if(warco && formc && tradt){
                  $.get("{{ route('generate-trano-ta') }}", {formc, warco, tradt}, function(res){
                      $('#trano').val(res);
                  });
              }
          });

          // ambil nomor SA sesuai rqbrc
          $('#rqbrc').on('change', function(){
              let rqbrc = $(this).val();
              $.get("{{ route('get-sa') }}", {rqbrc}, function(res){
                  $('#sano').empty().append('<option value="" disabled selected>Pilih Stock Requisition</option>');
                  res.sa.forEach(item => {
                      $('#sano').append(`<option value="${item.bpbid}" data-formc="${item.formc}" data-reqno="${item.reqno}">${item.formc} - ${item.reqno}</option>`);
                  });

                  // ambil trana dan tradres sesuai rqbrc
                  if(res.braco){
                      $('#trana').val(res.braco.brana);
                      $('#tradres').val(res.braco.address);
                  }
              });
              $('.opron-ta').empty().append('<option value="" disabled selected>Pilih Requisition Terlebih Dahulu</option>');
          });

          // ubah nama accordion 
          function setAccordionTitle(item){
              const prona = item.find('select[name*="opron"] option:selected').text() || '';
              item.find('.accordion-title').text(prona ? `Product : ${prona}` : '-');
          }

          // change listener IA
          $(document).on('change','select[name*="opron"]', function(){
              setAccordionTitle($(this).closest('.accordion-item'));
          });

          // sweetalert qty input
          $(document).on('input', 'input[name="trqty[]"]', function() {
              const id = $(this).attr('id');
              const index = id.split('-').pop();
              let maxIn = Number($(`#inqty-ia-${index}`).val());
              if(!maxIn) maxIn = Number($(`#inqty-ib-${index}`).val());

              if(!maxIn || isNaN(maxIn) || maxIn <= 0){
                  return; 
              }

              if(Number($(this).val()) > maxIn){
                  Swal.fire({
                      icon: 'error',
                      title: 'Qty Melebihi Batas',
                      text: `Issue Qty tidak boleh lebih dari ${maxIn}`
                  });
                  $(this).val(maxIn);
              }
          });

          // SweetAlert confirm submit
          document.addEventListener("DOMContentLoaded", function() {
              const form = document.getElementById('form-ta');
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
