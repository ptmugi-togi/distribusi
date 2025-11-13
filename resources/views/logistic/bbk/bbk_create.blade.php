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

      <div id="section-of" style="display:none;">
        @include('logistic.bbk.partial_create.bbk_create_of')
      </div>

      <div class="mt-3 d-flex justify-content-between">
        <a href="{{ route('bbk.index') }}" class="btn btn-secondary">Kembali</a>
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

                  $('input[name="refcno"]').val('-');
                  $('input[name="refno"]').val('-');

                  $('#supplier_ia').prop('disabled', false);
              } else {

                  $('input[name="refcno"]').val('');
                  $('input[name="refno"]').val('');

                  // supplier disabled lagi
                  $('#supplier_ia').prop('disabled', true).val('');
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
                let braco = $('#braco').val();
                let warco = $('#warco').val();
                let formc = $('#formc').val();

                if(warco && formc){
                    $.get("{{ route('generate-trano-bbk') }}", {formc, warco}, function(res){
                        $('#trano').val(res);
                    });
                }
            });

          // ambil master product
          function loadMasterProductAll(){
            $('select.opron-ia, select.opron-ib, select.opron-if').each(function(){
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

              if(formc === 'OF'){
                $('#section-of').fadeIn();
                $('#section-of').find('[data-req="of"]').prop('required', true);
                $('#noPoInv').prop('checked', true).prop('disabled', true);
                loadMasterProductAll();
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
