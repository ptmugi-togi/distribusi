@extends('layout.main')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/global.css') }}">
@endpush

@section('container')
<main id="main" class="main">
  <div class="d-flex justify-content-between align-items-center">
    <div class="pagetitle">
      <h1>Tambah Data BPB</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
          <li class="breadcrumb-item"><a href="{{ route('bpb.index') }}">List BPB</a></li>
          <li class="breadcrumb-item active">BPB Create</li>
        </ol>
      </nav>
    </div>
    <div class="card">
      <h5 class="p-2"><b>Branch : {{ auth()->user()->cabang }}</b></h5>
    </div>
  </div>

  <section class="section">
    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach($errors->all() as $e)
                    <li>{{ $e }}</li>
                @endforeach
            </ul>
        </div>
    @endif


    <form id="form-bpb" action="{{ route('bpb.store') }}" method="POST">
      @csrf

      {{-- Global header (muncul dari awal) --}}
      <input type="text" name="braco" id="braco" value="{{ auth()->user()->cabang }}" hidden>

      <div class="row">
        <div class="col-md-6 mt-3">
          <label for="formc" class="form-label">BPB (Bon Permintaan Barang)</label><span class="text-danger"> *</span>
          <input type="text" class="form-control" id="formc" value="RA (Stock Requisition)" required readonly style="background-color:#e9ecef">
          <input type="text" name="formc" id="formc-store" value="RA" hidden>
        </div>

        <div class="col-md-6 mt-3">
          <label for="reqno" class="form-label">Requisition No.</label><span class="text-danger"> *</span>
          <input type="text" class="form-control" name="reqno" id="reqno" value="{{ old('reqno') }}" required readonly style="background-color:#e9ecef">
        </div>

        <div class="col-md-6 mt-3">
          <label for="reqdt" class="form-label">Requisition Date</label><span class="text-danger"> *</span>
          <input type="date" class="form-control" name="reqdt" id="reqdt" value="{{ old('reqdt') }}" required min="{{ $minDate }}">
        </div>

        <div class="col-md-6 mt-3">
          <label for="sorfcno" class="form-label">Order Confirmation</label><span class="text-danger"> *</span>
          <div class="d-flex">
              <input type="text" class="form-control" name="sorfc" id="sorfc" placeholder="cth: SA" value="{{ old('sorfc', $sorfc ?? '') }}" required>
              <input type="text" class="form-control" name="sorno" id="sorno" placeholder="cth: 250001" value="{{ old('sorno', $sorno ?? '') }}">
          </div>
        </div>

        <div class="col-md-6 mt-3">
            <label for="rqfor" class="form-label">Request For</label><span class="text-danger"> *</span>
            <input type="text" class="form-control" name="rqfor" id="rqfor" value="{{ old('rqfor') }}" required>
        </div>

        <div class="col-md-6 mt-3">
            <label for="reqto" class="form-label">Request To Branch</label><span class="text-danger"> *</span>
            <select class="form-select select2" name="reqto" id="reqto">
                <option value="{{ auth()->user()->cabang }}" {{ old('reqto') == auth()->user()->cabang ? 'selected' : '' }}>
                    {{ auth()->user()->cabang }}
                </option>
                @foreach ($mbranch as $m)
                    @if($m->braco != 'PST')
                        <option value="{{ $m->braco }}" {{ old('reqto') == $m->braco ? 'selected' : '' }}>
                            {{ $m->braco }}
                        </option>
                    @endif
                @endforeach
            </select>
        </div>

        <div class="col-md-6 mt-3">
            <label for="reqtn" class="form-label">Attn.</label><span class="text-danger"> *</span>
            <input type="text" class="form-control" name="reqtn" id="reqtn" value="{{ old('reqtn') }}" required>
        </div>

        <div class="col-md-6 mt-3">
            <label for="delto" class="form-label">Delivery To W/H</label><span class="text-danger"> *</span>
            <select class="form-select select2" name="delto" id="delto">
                <option value="W" {{ old('delto') == 'W' ? 'selected' : '' }}>W (Warehouse)</option>
                <option value="C" {{ old('delto') == 'C' ? 'selected' : '' }}>C (Customer)</option>
            </select>
        </div>

        <div class="col-md-6 mt-3">
            <label for="delco" class="form-label">Delivery Code</label><span class="text-danger"> *</span>
            <select class="form-select select2" name="delco" id="delco">
                @foreach ($mwarco as $mw)
                    @if($mw->warco != 'PST')
                        <option value="{{ $mw->warco }}" {{ old('delco', $defaultDelCo) == $mw->warco ? 'selected' : '' }}>
                            {{ $mw->warco }}
                        </option>
                    @endif
                @endforeach
            </select>
        </div>

        <div class="col-md-6 mt-3">
            <label for="warna" class="form-label">Ship to Name</label>
            <input type="text" class="form-control" id="warna" value="{{ old('warna') }}" disabled>
        </div>

        <div class="col-md-6 mt-3">
            <label for="address" class="form-label">Address</label>
            <input type="text" class="form-control" name="address" id="address" value="{{ old('address') }}" disabled>
        </div>

        <div class="col-md-6 mt-3">
            <label for="contp" class="form-label">Contact Person</label>
            <input type="text" class="form-control" name="contp" id="contp" value="{{ old('contp') }}" readonly style="background-color:#e9ecef">
        </div>

        <div class="col-md-12 mt-3">
            <label class="form-label">Notes</label>
            <textarea type="text" class="form-control" name="noteh" id="noteh" maxlength="200">{{ old('noteh') }}</textarea>
            <div class="form-text text-danger text-end" style="font-size:0.7rem;">Maksimal 200 karakter</div>
        </div>
      </div>
      

      <div id="section-ra">
        @include('logistic.bpb.partial_create.bpb_create_ra')
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
          $(document).ready(function(){
            $('.select2').select2({ width: '100%', theme: 'bootstrap-5' });

            setTimeout(()=>{
                loadMasterProductAll();
            },100);

            let oldReqto = "{{ old('reqto') }}";

            if (oldReqto) {
                $('#reqto').val(oldReqto).trigger('change');
            }
          });

            // generate reqno
            $('#formc-store').on('change', function(){
                let braco = $('#braco').val();
                let formc = $('#formc-store').val();

                if(formc){
                    $.get("{{ route('generate-reqno-bpb') }}", {formc}, function(res){
                        $('#reqno').val(res);
                    });
                }
            });

            $('#formc-store').trigger('change');

            // fill address and ship to name dari warco
            $('#delco').on('change', function () {
                let code = $(this).val();

                $.ajax({
                    url: '/get-warco-detail/' + code,
                    type: 'GET',
                    success: function (data) {
                        $('#warna').val(data.warna);
                        $('#contp').val(data.contp);
                        $('#address').val(data.address);
                    }
                });
            });

            // jika ada default value delco
            $('#delco').trigger('change');

            // ambil master product
            function loadMasterProductAll(){
                $('select.opron-ra').each(function(){
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

            // ubah nama accordion 
            function setAccordionTitle(item){
                const prona = item.find('select[name*="opron"] option:selected').text() || '';
                item.find('.accordion-title').text(prona ? `Product : ${prona}` : '-');
            }

            // change listener IA
            $(document).on('change','select[name*="opron"]', function(){
                setAccordionTitle($(this).closest('.accordion-item'));
                
                const $opt = $(this).find(':selected');
                const stdqt = $opt.data('stdqt') || '-';

                $(`.unit-label-ra`).text(stdqt);
            });

            // SweetAlert confirm submit
            document.addEventListener("DOMContentLoaded", function() {
                const form = document.getElementById('form-bpb');
                form.addEventListener('submit', function (e) {
                e.preventDefault();
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