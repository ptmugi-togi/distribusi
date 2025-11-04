@extends('layout.main')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/global.css') }}">
@endpush

@section('container')
<main id="main" class="main">
  <div class="d-flex justify-content-between align-items-center">
    <div class="pagetitle">
      <h1>Tambah Data BBM</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
          <li class="breadcrumb-item"><a href="{{ route('bbm.index') }}">List BBM</a></li>
          <li class="breadcrumb-item active">BBM Create</li>
        </ol>
      </nav>
    </div>
    <div class="card">
      <h5 class="p-2"><b>Branch : {{ auth()->user()->cabang }}</b></h5>
    </div>
  </div>

  <section class="section">
    <form id="form-bbm" action="{{ route('bbm.store') }}" method="POST">
      @csrf

      {{-- Global header (muncul dari awal) --}}
      <input type="text" name="braco" value="{{ auth()->user()->cabang }}" hidden>

      <div class="row">
        <div class="col-md-6 mt-3">
          <label for="formc" class="form-label">Formc</label><span class="text-danger"> *</span>
          <select class="form-control select2" id="formc" name="formc" required>
            <option value="" disabled {{ old('formc') ? '' : 'selected' }}>Silahkan Pilih Formc</option>
            <option value="IA" {{ old('formc') == 'IA' ? 'selected' : '' }}>IA (BBM - LOCAL PURCHASE)</option>
            <option value="IB" {{ old('formc') == 'IB' ? 'selected' : '' }}>IB (BBM - IMPORT)</option>
            {{-- FormC lain nanti --}}
          </select>
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
          <label for="trano" class="form-label">Stock Receipt No.</label><span class="text-danger"> *</span>
          <input type="text" class="form-control" name="trano" id="trano" value="{{ old('trano', $trano ?? '') }}" required readonly style="background-color:#e9ecef">
        </div>

        <input type="text" name="priod" id="priod" value="{{ $priod }}" hidden>

        <div class="col-md-6 mt-3">
          <label for="tradt" class="form-label">Stock Receipt Date</label><span class="text-danger"> *</span>
          <input type="date" class="form-control" name="tradt" id="tradt" value="{{ old('tradt') }}" required min="{{ $minDate }}">
        </div>
      </div>

      {{-- SECTION IA (LOCAL) --}}
      <div id="section-local" style="display:none;">
        @include('logistic.bbm.partial_create.bbm_create_ia')
      </div>

      {{-- SECTION IB (IMPORT) --}}
      <div id="section-import" style="display:none;">
        @include('logistic.bbm.partial_create.bbm_create_ib')
      </div>

      <div class="mt-3 d-flex justify-content-between">
        <a href="{{ route('bbm.index') }}" class="btn btn-secondary">Kembali</a>
        <button type="submit" class="btn btn-primary">Simpan Data</button>
      </div>
    </form>
  </section>
</main>

    @push('scripts')
        <script>
        $(document).ready(function(){
            $('.select2').select2({ width: '100%', theme: 'bootstrap-5' });

            // restore old
            const oldFormc = @json(old('formc'));
            if(oldFormc){ $('#formc').val(oldFormc).trigger('change'); }
        });

        // switch section by FormC
        $('#formc').on('change', function(){
            const formc = $(this).val();
            $('#section-local, #section-import').hide();

            $('#section-local').find('[required]').prop('required', false);
            $('#section-import').find('[required]').prop('required', false);

            if(formc === 'IA'){
              $('#section-import').remove();
              $('#section-local').fadeIn();
              $('#section-local').find('[data-req="ia"]').prop('required', true);
            }else if(formc === 'IB'){
              $('#section-local').remove();
              $('#section-import').fadeIn();
              $('#section-import').find('[data-req="ib"]').prop('required', true);
            }
        });

        // ubah nama accordion 
        function setAccordionTitle(item){
            const formc = $('#formc').val();

            if(formc === 'IB'){
                const invno = item.find('select[name*="invno"]').val() || '';
                item.find('.accordion-title').text(invno ? `Invoice : ${invno}` : '-');
            }

            if(formc === 'IA'){
                const prona = item.find('select[name*="opron"] option:selected').text() || '';
                item.find('.accordion-title').text(prona ? `Product : ${prona}` : '-');
            }
        }

        // change listener IB
        $(document).on('change','select[name*="invno"]', function(){
            setAccordionTitle($(this).closest('.accordion-item'));
        });

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
            const form = document.getElementById('form-bbm');
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
