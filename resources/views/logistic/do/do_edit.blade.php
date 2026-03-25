@extends('layout.main')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/global.css') }}">
@endpush

@section('container')
<main id="main" class="main">
    <div class="d-flex justify-content-between align-items-center">
        <div class="pagetitle">
            <h1>Edit DO</h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('do.index') }}">List DO</a></li>
                    <li class="breadcrumb-item active">Edit DO</li>
                </ol>
            </nav>
        </div>
    </div>

    <section class="section">
        <form id="form-edit-do" action="{{ route('do.update', $do->bbkid) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="card p-3 shadow-sm">
                {{-- Header --}}
                <div class="row">
                    <input type="text" class="form-control" id="braco" name="braco" value="{{ $do->braco }}" hidden>
                    <div class="col-md-6 mt-3">
                        <label class="form-label">Form Code</label>
                        <input type="text" class="form-control" value="{{ $do->formc }}" disabled>
                    </div>

                    <div class="col-md-6 mt-3">
                        <label class="form-label">DO No.</label>
                        <input type="text" class="form-control" value="{{ $do->trano }}" disabled>
                    </div>

                    <div class="col-md-6 mt-3">
                        <label class="form-label">DO Date</label>
                        <input type="date" class="form-control" value="{{ $do->tradt }}" disabled>
                    </div>

                    <div class="col-md-6 mt-3">
                        <label class="form-label">OC No.</label>
                        <input type="text" class="form-control" value="{{ $do->rfc01 }} - {{ $do->ref01 }}" disabled>
                        <input type="hidden" id="rfc01" name="rfc01" value="{{ $do->rfc01 }}">
                        <input type="hidden" id="ref01" name="ref01" value="{{ $do->ref01 }}">
                    </div>

                    <div class="col-md-6 mt-3">
                        <label class="form-label">Expediter</label>
                        <input type="text" class="form-control" value="{{ $do->exped }}" disabled>
                    </div>

                    <div class="col-md-6 mt-3">
                        <label class="form-label">Notes</label>
                        <textarea name="noteh" class="form-control" rows="2">{{ $do->noteh }}</textarea>
                    </div>
                </div>

                {{-- Detail --}}
                <div class="row mt-4">
                    <h3>DO Detail</h3>
                    <div class="accordion" id="accordionDO">
                        @foreach ($dodtls as $i => $detail)
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
                                            <input type="text" class="form-control" value="{{ $detail->opron }} - {{ $detail->mpromas->prona }}" required readonly style="background-color:#e9ecef">
                                            <input type="text" name="opron[]" class="form-control" value="{{ $detail->opron }}" hidden>
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
                                                <input type="text" id="qunit-do" name="qunit[]" value="{{ $detail->qunit }}" hidden>
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
                        <button type="button" class="btn mt-3" style="background-color:#4456f1;color:#fff" onclick="addDO()">Tambah Detail DO</button>
                    </div>
                </div>
                @include('logistic.do.partial_edit.do_add_detail')
                <div class="mt-4 d-flex justify-content-between">
                    <a href="{{ route('do.index') }}" class="btn btn-secondary">Kembali</a>
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
                $(document).on('input', '.trqty-do', function() {
                    const idx = this.id.split('-').pop();
                    const qty = parseFloat($(this).val()) || 0;

                    const maxOrder = parseFloat($(`#rqqty-do-${idx}`).val()) || 0;
                    const maxStock = parseFloat($(`#toqoh-do-${idx}`).val()) || 0;

                    if (qty > maxOrder) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Qty Melebihi Batas',
                            text: `DO Qty Melebihi OC QTY.`
                        });
                        $(this).val(maxOrder);
                    }

                    if (qty > maxStock) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Qty Melebihi Batas',
                            text: `DO Qty Melebihi Stock.`
                        });
                        $(this).val(maxStock);
                    }
                });

                // SweetAlert confirm submit
                const form = document.getElementById('form-edit-do');
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

