@extends('layout.main')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/global.css') }}">
@endpush

@section('container')
<main id="main" class="main">
  <div class="d-flex justify-content-between align-items-center">
    <div class="pagetitle">
      <h1>Tambah Data DO</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
          <li class="breadcrumb-item"><a href="{{ route('do.index') }}">List DO</a></li>
          <li class="breadcrumb-item active">DO Create</li>
        </ol>
      </nav>
    </div>
    <div class="card">
      <h5 class="p-2"><b>Branch : {{ auth()->user()->cabang }}</b></h5>
    </div>
  </div>

  <section class="section">
    <form id="form-do" action="{{ route('do.store') }}" method="POST">
      @csrf

      {{-- Global header (muncul dari awal) --}}
      <input type="text" name="braco" id="braco" value="{{ auth()->user()->cabang }}" hidden>

      <div class="row">
        <div class="col-md-6 mt-3">
          <label for="formc" class="form-label">Form Code</label><span class="text-danger"> *</span>
          <input type="text" class="form-control" value="DO (Delivery Order)" disabled>
          <input type="text" class="form-control" id="formc" name="formc" id="formc-store" value="DO" hidden>
        </div>

        <div class="col-md-6 mt-3">
          <label for="trano" class="form-label">DO No.</label><span class="text-danger"> *</span>
          <input type="text" class="form-control" name="trano" id="trano" value="{{ old('trano') }}" required readonly style="background-color:#e9ecef">
        </div>

        <input type="text" name="priod" id="priod" value="{{ old('priod') }}" hidden>

        <div class="col-md-6 mt-3">
          <label for="tradt" class="form-label">DO Date</label><span class="text-danger"> *</span>
          <input type="date" class="form-control" name="tradt" id="tradt" value="{{ old('tradt') }}" required min="{{ $minDate }}">
        </div>

        <div class="col-md-6 mt-3">
          <label for="ocno" class="form-label">OC No.</label><span class="text-danger"> *</span>
          <select name="ocno" id="ocno" class="form-control select2" required>
            <option value="" disabled selected>Silahkan Pilih OC</option>
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

        <div class="col-md-12 mt-3">
          <label for="noteh" class="form-label">Notes</label>
          <textarea class="form-control" name="noteh" id="noteh" rows="2">{{ old('noteh') }}</textarea>
        </div>
      </div>

      <div id="section-do">
        @include('logistic.do.partial_create.do_create_detail')
      </div>

      <div class="mt-3 d-flex justify-content-between">
        <a href="{{ route('do.index') }}" class="btn btn-secondary">Kembali</a>
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

                $.get("{{ route('get-oc') }}", function(data){
                    let ocno = $('#ocno');
                    ocno.empty();
                    ocno.append('<option value="" disabled selected>Silahkan Pilih OC</option>');

                    data.forEach(function(item){
                        ocno.append(`<option value="${item.value}">${item.text} (${item.cust})</option>`);
                    });
                });
          });

          // generate trano
          $('#tradt').on('change', function(){
              let braco = $('#braco').val();
              let formc = $('#formc').val();
              let tradt = $('#tradt').val();

              if(formc && tradt){
                  $.get("{{ route('generate-trano-do') }}", {formc, tradt}, function(res){
                      $('#trano').val(res);
                  });
              }
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
