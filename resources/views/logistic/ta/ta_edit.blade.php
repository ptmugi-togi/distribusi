@extends('layout.main')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/global.css') }}">
@endpush

@section('container')
<main id="main" class="main">
    <div class="d-flex justify-content-between align-items-center">
        <div class="pagetitle">
            <h1>Edit TA</h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('ta.index') }}">List TA</a></li>
                    <li class="breadcrumb-item active">Edit TA</li>
                </ol>
            </nav>
        </div>
    </div>

    <section class="section">
        <form id="form-edit-ta" action="{{ route('ta.update', $ta->bbkid) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="card p-3 shadow-sm">
                {{-- Header --}}
                <div class="row">
                    <div class="col-md-6 mt-3">
                        <label class="form-label">Formc</label>
                        <input type="text" class="form-control" value="{{ $ta->formc }}" disabled>
                    </div>

                    <div class="col-md-6 mt-3">
                        <label class="form-label">Warehouse</label>
                        <input type="text" class="form-control" value="{{ $ta->warco }}" disabled>
                    </div>

                    <div class="col-md-6 mt-3">
                        <label class="form-label">Transfer Note No.</label>
                        <input type="text" class="form-control" value="{{ $ta->trano }}" disabled>
                    </div>

                    <div class="col-md-6 mt-3">
                        <label class="form-label">Transfer Note Date</label>
                        <input type="date" class="form-control" value="{{ $ta->tradt }}" disabled>
                    </div>

                    <div class="col-md-6 mt-3">
                        <label class="form-label">Request by Branch</label>
                        <input type="text" class="form-control" value="{{ $ta->rqbrc }}" disabled>
                    </div>

                    <div class="col-md-6 mt-3">
                        <label class="form-label">Stock Requisition No.</label>
                        <input type="text" class="form-control" value="{{ $ta->rfc01 }} - {{ $ta->ref01 }}" disabled>
                    </div>

                    <div class="col-md-6 mt-3">
                        <label class="form-label">Expediter</label>
                        <input type="text" class="form-control" value="{{ $ta->exped }}" disabled>
                    </div>

                    <div class="col-md-6 mt-3">
                        <label class="form-label">Transfer to Name</label>
                        <input type="text" class="form-control" value="{{ $ta->mbranch->brana }}" disabled>
                    </div>

                    <div class="col-md-6 mt-3">
                        <label class="form-label">Transfer to Address</label>
                        <textarea class="form-control" rows="2" disabled>{{ $ta->mbranch->address }}</textarea>
                    </div>

                    <div class="col-md-6 mt-3">
                        <label class="form-label">Notes</label>
                        <textarea name="noteh" class="form-control" rows="2">{{ $ta->noteh }}</textarea>
                    </div>
                </div>

                {{-- Detail --}}
                <div class="row mt-4">
                    <h3>TA Detail</h3>
                    <div class="accordion" id="accordionTA">
                        @foreach ($tadtls as $i => $detail)
                        <div class="accordion-item" id="accordion-item-{{ $i }}">
                            <h2 class="accordion-header d-flex justify-content-between align-items-center" id="heading-{{ $i }}">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse-{{ $i }}">
                                    Product: {{ $detail->opron }} - {{ $detail->mpromas->prona }}
                                </button>
                                <button type="button" class="btn btn-sm btn-danger mx-2" onclick="removetaDetail({{ $i }})">
                                    <i class="bi bi-trash-fill"></i>
                                </button>
                            </h2>
                            <div id="collapse-{{ $i }}" class="accordion-collapse collapse">
                                <div class="accordion-body">
                                    <div class="row">
                                        <div class="col-md-6 mt-3">
                                            <label class="form-label">Barang</label>
                                            <input type="text" name="opron[]" class="form-control" value="{{ $detail->opron }}" required readonly style="background-color:#e9ecef">
                                        </div>

                                        <div class="col-md-6 mt-3">
                                            <label class="form-label">Serial / Batch No.</label>
                                            <input type="text" name="lotno[]" class="form-control" value="{{ $detail->lotno }}" required>
                                        </div>

                                        <div class="col-md-6 mt-3">
                                            <label class="form-label">Issue Quantity</label>
                                            <div class="input-group">
                                                <input type="text" name="trqty[]" class="form-control" value="{{ $detail->trqty }}" required>
                                                <span class="input-group-text">{{ $detail->qunit }}</span>
                                                <input type="text" id="qunit-ta" name="qunit[]" value="{{ $detail->qunit }}" hidden>
                                            </div>
                                        </div>

                                        <div class="col-md-6 mt-3">
                                            <label class="form-label">Warehouse Location</label>
                                            <input type="text" name="locco[]" class="form-control" value="{{ $detail->locco }}" required>
                                        </div>

                                        <div class="col-md-12 mt-3">
                                            <label class="form-label">Notes</label>
                                            <textarea name="noted[]" class="form-control">{{ $detail->noted }}</textarea>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    <div class="text-end">
                        <button type="button" class="btn mt-3" style="background-color:#4456f1;color:#fff" onclick="addTA()">Tambah Detail TA</button>
                    </div>
                </div>
                @include('logistic.ta.partial_edit.ta_add_detail')
                <div class="mt-4 d-flex justify-content-between">
                    <a href="{{ route('ta.index') }}" class="btn btn-secondary">Kembali</a>
                    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                </div>

            </div>
        </form>
    </section>
</main>
    @push('scripts')
        <script>
            $(document).ready(function() {
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
                    let maxIn = Number($(`#rqqty-ta-${index}`).val());
                    if(!maxIn) maxIn = Number($(`#rqqty-ta-${index}`).val());

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
                const form = document.getElementById('form-edit-ta');
                form.addEventListener('submit', function (e) {
                    e.preventDefault();
                    if (!form.checkValidity()) { form.classList.add('was-validated'); return; }
                    Swal.fire({
                        title: 'Konfirmasi Ubah',
                        text: 'Apakah Anda yakin ingin mengubah data ini?',
                        icon: 'question',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Ya, Ubah Data!',
                        cancelButtonText: 'Batal'
                    }).then((res)=>{
                        if(res.isConfirmed){
                        Swal.fire({ title:'Mengubah...', text:'Mohon tunggu sebentar', icon:'info', showConfirmButton:false, allowOutsideClick:false, allowEscapeKey:false, didOpen:()=>Swal.showLoading() });
                        form.submit();
                        }
                    });
                });
            })
        </script>
    @endpush
@endsection

