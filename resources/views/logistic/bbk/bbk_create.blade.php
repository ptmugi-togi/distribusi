@extends('layout.main')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/global.css') }}">
@endpush

@section('container')
<main id="main" class="main">
  <div class="d-flex justify-content-between align-items-center">
    <div class="pagetitle">
      <h1>Tambah Data BBK</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
          <li class="breadcrumb-item"><a href="{{ route('bbk.index') }}">List BBK</a></li>
          <li class="breadcrumb-item active">BBK Create</li>
        </ol>
      </nav>
    </div>
    <div class="card">
      <h5 class="p-2"><b>Branch : {{ auth()->user()->cabang }}</b></h5>
    </div>
  </div>

  <section class="section">
    <form id="form-bbk" action="{{ route('bbk.store') }}" method="POST">
      @csrf

      {{-- Global header (muncul dari awal) --}}
      <input type="text" name="braco" id="braco" value="{{ auth()->user()->cabang }}" hidden>

      <div class="row">
        <div class="col-md-6 mt-3">
          <label for="formc" class="form-label">BBK</label><span class="text-danger"> *</span>
          <select class="form-control select2" id="formc" name="formc" required>
            <option value="" disabled {{ old('formc') ? '' : 'selected' }}>Silahkan Pilih BBK</option>
            <option value="OF" {{ old('formc') == 'OF' ? 'selected' : '' }}>OF (ISSUE ADJUSTMENT)</option>
            <option value="OC" {{ old('formc') == 'OC' ? 'selected' : '' }}>OC (ISSUE TO WORKSHOP)</option>
            {{-- FormC lain nanti --}}
          </select>
        </div>

        <div class="col-md-6 mt-3">
          <label for="warco" class="form-label">Warehouse</label><span class="text-danger"> *</span>
          <select class="form-control select2" name="warco" id="warco" required>
            <option value="" disabled selected>Pilih Warehouse</option>
            @foreach ($mwarco as $m)
              @if ($m->braco == auth()->user()->cabang)
                <option value="{{ $m->warco }}" {{ old('warco') == $m->warco ? 'selected' : '' }}>
                    {{ $m->warco }}
                </option>
              @endif
            @endforeach
          </select>
        </div>

        <input type="text" name="priod" id="priod" value="{{ old('priod' ?? '') }}" hidden>
      </div>

      <div id="section-of" style="display:none;">
        @include('logistic.bbk.partial_create.bbk_create_of')
      </div>

      <div id="section-oc" style="display:none;">
        @include('logistic.bbk.partial_create.bbk_create_oc')
      </div>

      <div class="mt-3 d-flex justify-content-between">
        <a href="{{ route('bbk.index') }}" class="btn btn-secondary">Kembali</a>
        <button type="submit" class="btn btn-primary">Simpan Data</button>
      </div>
    </form>
  </section>
</main>

    @push('scripts')
        {{-- ambil priod dari yyyymm tradt --}}
        <script>
          $(document).on('change', '#tradt', function () {
              const tanggal = this.value;
              if (!tanggal) return;

              const year  = tanggal.substring(0, 4);
              const month = tanggal.substring(5, 7);

              $('#priod').val(year + month);
          });
        </script>
        
        <script>
          $(document).ready(function(){
              $('.select2').select2({ width: '100%', theme: 'bootstrap-5' });

              // restore old
              const oldFormc = @json(old('formc'));
              if(oldFormc){ $('#formc').val(oldFormc).trigger('change'); }
          });

          // generate trano
          $('#formc, #warco').on('change', function(){
              let braco = $('#braco').val();
              let warco = $('#warco').val();
              let formc = $('#formc').val();

              if(warco && formc){
                  $.get("{{ route('generate-trano-bbk') }}", {formc, warco}, function(res){
                      $('#trano').val(res);
                  });
              }
          });

          // switch section by FormC
          $('#formc').on('change', function(){
              const formc = $(this).val();

              if(formc === 'OF'){
                $('#section-of').fadeIn();
                $('#section-oc').remove();
                $('#section-of').find('[data-req="of"]').prop('required', true);
              }
              else if(formc === 'OC'){
                $('#section-oc').fadeIn();
                $('#section-of').remove();
                $('#section-oc').find('[data-req="oc"]').prop('required', true);
              }
              else{
                $('#section-of').fadeOut();
                $('#section-oc').fadeOut();
                $('#section-of').find('[data-req="of"]').prop('required', false);
                $('#section-oc').find('[data-req="oc"]').prop('required', false);
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
                      text: `Receipt Qty tidak boleh lebih dari ${maxIn}`
                  });
                  $(this).val(maxIn);
              }
          });

          // SweetAlert confirm submit
          document.addEventListener("DOMContentLoaded", function() {
              const form = document.getElementById('form-bbk');
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
