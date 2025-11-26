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


    <form id="form-bpb" action="{{ route('bpb.update', $bpb->bpbid) }}" method="POST">
        @csrf
        @method('PUT')

        <input type="text" name="braco" id="braco" value="{{ auth()->user()->cabang }}" hidden>

        <div class="row">
            <div class="col-md-6 mt-3">
            <label for="formc" class="form-label">BPB (Bon Permintaan Barang)</label>
            <input type="text" class="form-control" id="formc" value="RA (Stock Requisition)" disabled>
            <input type="text" class="form-control" name="formc" id="formc" value="RA" hidden>
            </div>

            <div class="col-md-6 mt-3">
            <label for="reqno" class="form-label">Requisition No.</label>
            <input type="text" class="form-control" name="reqno" id="reqno" value="{{ $bpb->reqno ?? '' }}" readonly style="background-color:#e9ecef">
            </div>

            <div class="col-md-6 mt-3">
            <label for="reqdt" class="form-label">Requisition Date</label>
            <input type="date" class="form-control" id="reqdt" value="{{ $bpb->reqdt ?? '' }}" disabled>
            </div>

            <div class="col-md-6 mt-3">
            <label for="sorfcno" class="form-label">Order Confirmation</label>
            <div class="d-flex">
                <input type="text" class="form-control" id="sorfc" placeholder="cth: SA" value="{{ $bpb->sorfc }}{{ $bpb->sorno }}" disabled>
            </div>
            </div>

            <div class="col-md-6 mt-3">
                <label for="rqfor" class="form-label">Request For</label>
                <input type="text" class="form-control" id="rqfor" value="{{ 'rqfor', $bpb->rqfor ?? '' }}" disabled>
            </div>

            <div class="col-md-6 mt-3">
                <label for="reqto" class="form-label">Request To Branch</label>
                <input type="text" class="form-control" id="reqto" value="{{ $bpb->reqto ?? '' }}" disabled>
            </div>

            <div class="col-md-6 mt-3">
                <label for="reqtn" class="form-label">Attn.</label>
                <input type="text" class="form-control" id="reqtn" value="{{ $bpb->reqtn ?? '' }}" disabled>
            </div>

            <div class="col-md-6 mt-3">
                <label for="delto" class="form-label">Delivery To W/H</label>
                <input type="text" class="form-control" id="delto" value="{{ $bpb->delto == 'W' ? 'W (Warehouse)' : ($bpb->delto == 'C' ? 'C (Customer)' : '') }}" disabled>
            </div>

            <div class="col-md-6 mt-3">
                <label for="delco" class="form-label">Delivery Code</label>
                <input type="text" class="form-control" id="delco" value="{{ $bpb->delco ?? '' }}" disabled>
            </div>

            <div class="col-md-6 mt-3">
                <label for="warna" class="form-label">Ship to Name</label>
                <input type="text" class="form-control" id="warna" value="{{ $bpb->mwarco->warna }}" disabled>
            </div>

            <div class="col-md-6 mt-3">
                <label for="address" class="form-label">Address</label>
                <input type="text" class="form-control" id="address" value="{{ $bpb->mwarco->address }}" disabled>
            </div>

            <div class="col-md-6 mt-3">
                <label for="contp" class="form-label">Contact Person</label>
                <input type="text" class="form-control" id="contp" value="{{ $bpb->contp }}" disabled>
            </div>

            <div class="col-md-12 mt-3">
                <label class="form-label">Notes</label>
                <textarea type="text" class="form-control" name="noteh" id="noteh" maxlength="200">{{ $bpb->noteh }}</textarea>
                <div class="form-text text-danger text-end" style="font-size:0.7rem;">Maksimal 200 karakter</div>
            </div>
        </div>

        {{-- Detail --}}
        <div class="row mt-4">
            <h3>BPB Detail</h3>
            <div class="accordion" id="accordionRA">
                @foreach ($bpb->bpbdtls as $i => $detail)
                <div class="accordion-item" id="accordion-ra-item-{{ $i }}">
                    <h2 class="accordion-header d-flex justify-content-between align-items-center" id="heading-{{ $i }}">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                            data-bs-target="#collapse-{{ $i }}" aria-expanded="false">
                                Product: {{ $detail->opron }} - {{ $detail->mpromas->prona }}
                        </button>
                        <button type="button" class="btn btn-sm btn-danger mx-2" onclick="removeRA({{ $i }})">
                            <i class="bi bi-trash-fill"></i>
                        </button>
                    </h2>
                    <div id="collapse-{{ $i }}" class="accordion-collapse collapse"
                        aria-labelledby="heading-{{ $i }}">
                        <div class="accordion-body">
                            <div class="row">
                                <div class="col-md-6 mt-3">
                                    <label class="form-label">Barang</label>
                                    <input type="text" class="form-control" value="{{ $detail->opron }} - {{ $detail->mpromas->prona }}" disabled>
                                    <input type="text" class="form-control" name="opron[]" value="{{ $detail->opron }}" hidden>
                                </div>

                                <div class="col-md-6 mt-3">
                                    <label class="form-label">Request Quantity</label>
                                    <div class="input-group">
                                        <input type="text" class="form-control" name="rqqty[]" value="{{ old('rqqty.'.$i, $detail->rqqty) }}">
                                        <span class="input-group-text">{{ $detail->mpromas->stdqu }}</span>
                                    </div>
                                </div>

                                <div class="col-md-6 mt-3">
                                    <label class="form-label">Expected Arrival</label>
                                    <input type="date" class="form-control" name="eariv[]" value="{{ old('eariv.'.$i, $detail->eariv) }}">
                                </div>

                                <div class="col-md-6 mt-3">
                                    <label class="form-label">Alokasi</label>
                                    <input type="text" class="form-control" name="aloka[]" value="{{ old('aloka.'.$i, $detail->aloka) }}">
                                </div>

                                <div class="col-md-12 mt-3">
                                    <label class="form-label">Notes</label>
                                    <textarea class="form-control" name="noted[]" id="noted-bpb-{{ $i }}" maxlength="200">{{ old('noted.'.$i, $detail->noted) }}</textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            <div class="text-end">
                <button type="button" class="btn mt-3" style="background-color:#4456f1;color:#fff" onclick="addRA()">Tambah Detail (RA)</button>
            </div>
        </div>

        <div class="mt-3 d-flex justify-content-between">
            <a href="{{ route('bpb.index') }}" class="btn btn-secondary">Kembali</a>
            <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
        </div>
    </form>
  </section>
</main>

@include('logistic.bpb.partial_edit.bpb_edit_ra')

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