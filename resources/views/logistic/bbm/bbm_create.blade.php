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

        <div class="col-md-6 mt-4">
            <div class="form-check">
                <input class="form-check-input noPoInv-checkbox" type="checkbox" value="1" name="noPoInv" id="noPoInv">
                <label class="form-check-label" for="noPoInv">
                    Without PO / Invoice
                </label>
            </div>
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
          let isNoPoInv = false;

          function applyNoPoInvMode() {
            if(isNoPoInv) {
                  $('#refcno_ia').prop('disabled', true).val('');
                  $('#refcno_ib').prop('disabled', true).val('');
                  $('.invno-ib').each(function(i){
                      $(this).prop('disabled', true);
                      $(this).html(`<option value="-" selected>-</option>`);

                      $(`input.invno-hidden[data-index="${i}"]`).val('-');
                  });


                  $('input[name="refcno"]').val('-');
                  $('input[name="refno"]').val('-');
                  $('input[name="invno"]').val('-');

                  $('#supplier_ia').prop('disabled', false);
                  $('#supplier_ib').prop('disabled', false);
              } else {
                  $('#refcno_ia').prop('disabled', false);
                  $('#refcno_ib').prop('disabled', false);
                  $('.invno-ib').prop('readonly', false);

                  $('input[name="refcno"]').val('');
                  $('input[name="refno"]').val('');
                  $('input[name="invno"]').val('');

                  // supplier disabled lagi
                  $('#supplier_ia').prop('disabled', true).val('');
                  $('#supplier_ib').prop('disabled', true).val('');
              }
          }

          $(document).ready(function(){
              $('.select2').select2({ width: '100%', theme: 'bootstrap-5' });

              // restore old
              const oldFormc = @json(old('formc'));
              if(oldFormc){ $('#formc').val(oldFormc).trigger('change'); }
          });

          // generate trano
          $('#formc, #warco').on('change', function(){
              let warco = $('#warco').val();
              let formc = $('#formc').val();

              if(warco && formc){
                  $.get("{{ route('generate-trano') }}", {formc, warco}, function(res){
                      $('#trano').val(res);
                  });
              }
          });

          // checkbox po / invoice
          $('#noPoInv').on('change', function(){
              isNoPoInv = $(this).is(':checked');
              
              applyNoPoInvMode();

              if(isNoPoInv){
                  loadMasterProductAll();
              }else{
                $('select.opron-ia, select.opron-ib').each(function(){
                    $(this).select2('destroy');
                    $(this).select2({ width:'100%', theme:'bootstrap-5' });
                    
                    // ambil sesuai pono atau invno lagi
                    const pono = $('#refcno_ia_submit').val();
                    if(pono){
                        $('select.opron-ia').each(function(i){
                            const $sel = $(this);
                            $sel.empty().append('<option value="">Loading...</option>');
                            $.get(`{{ url('/get-barang') }}/${pono}?formc=IA`, function(data){
                                $sel.empty().append('<option value="" disabled selected>Pilih Barang (PO)</option>');
                                data.forEach(item=>{
                                    $sel.append(`<option value="${item.opron}" data-qty="${item.inqty}" data-stdqt="${item.stdqt}" data-pono="${item.pono}">${item.opron} - ${item.prona}</option>`);
                                });
                            });
                        });
                    }

                    $('select.invno-ib').each(function(){
                        if($(this).val()){
                            $(this).trigger('change');
                        }
                    });
                });
              }
          });

          // ambil master product jika nopoinv checked
          function loadMasterProductAll(){
            $('select.opron-ia, select.opron-ib').each(function(){
                $(this).select2({
                    placeholder: 'Pilih Barang',
                    theme: 'bootstrap-5',
                    width: '100%',
                    allowClear: true,
                    ajax: {
                        url: '{{ route("api.products") }}',
                        dataType: 'json',
                        delay: 250,
                        data: function(params){
                            return { q: params.term || '', page: params.page || 1 };
                        },
                        processResults: function(data){
                            return {
                                results: (data.results || []).map(item => ({
                                    id: item.id,
                                    text: item.text,
                                    stdqt: item.data_stdqu
                                })),
                                pagination: { more: data.pagination.more }
                            };
                        }
                    },
                    minimumInputLength: 0,
                    templateResult: function (data) {
                        if (!data.id) return data.text;
                        const el = data.element;
                        if (el) $(el).attr('data-stdqt', data.stdqt || '');
                        return data.text;
                    },
                    templateSelection: function (data) {
                        if (!data.id) return data.text;
                        const el = data.element;
                        if (el) $(el).attr('data-stdqt', data.stdqt || '');
                        return data.text;
                    }
                });
            });
        }

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
              applyNoPoInvMode();
          });

          // ubah nama accordion 
          function setAccordionTitle(item){
              const formc = $('#formc').val();

              const prona = item.find('select[name*="opron"] option:selected').text() || '';
              item.find('.accordion-title').text(prona ? `Product : ${prona}` : '-');
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
