@extends('layout.main')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/global.css') }}">
@endpush

@section('container')
<main id="main" class="main">
    <div class="d-flex justify-content-between align-items-center">
        <div class="pagetitle">
            <h1>Edit WO ({{ $wo->woid }})</h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('wo.index') }}">List WO</a></li>
                    <li class="breadcrumb-item active">Edit WO</li>
                </ol>
            </nav>
        </div>
    </div>

    <section class="section">
        <form id="form-wo" action="{{ route('wo.update', $wo->woid) }}" method="POST">
            @csrf
            @method('PUT')
            {{-- Header --}}
            <div class="card p-3 shadow-sm">
                <input type="text" id="braco" name="braco" value="{{ auth()->user()->cabang }}" hidden>
                <input type="text" id="formc" name="formc" value="WO" hidden>

                <div class="row">
                    <div class="col-md-6 mt-3">
                        <label for="wonum" class="form-label">Work Order No.</label>
                        <input type="text" class="form-control" id="wonum" name="wonum" value="{{ $wo->wonum }}" readonly style="background-color:#e9ecef">
                    </div>

                    <div class="col-md-6 mt-3">
                        <label for="wodat" class="form-label">Work Order Date</label>
                        <input type="text" class="form-control" id="wodat" value="{{ \Carbon\Carbon::parse($wo->wodat)->format('d/m/Y') }}" disabled>
                    </div>

                    <div class="col-md-6 mt-3">
                        <label for="reqbr" class="form-label">Request By Branch</label>
                        <input type="text" class="form-control" id="reqbr" value="{{ $wo->reqbr }}" disabled>
                    </div>

                    <div class="col-md-6 mt-3">
                        <label for="ppose" class="form-label">Purpose</label>
                        <input type="text" class="form-control" id="ppose" value="{{ $wo->ppose }}" disabled>
                    </div>

                    <div class="col-md-6 mt-3">
                        <label for="reqby" class="form-label">Request By</label>
                        <input type="text" class="form-control" id="reqby" value="{{ $wo->reqby }}" disabled>
                    </div>

                    <div class="col-md-6 mt-3">
                        <label for="reqdt" class="form-label">Request Date</label>
                        <input type="text" class="form-control" id="reqdt" value="{{ \Carbon\Carbon::parse($wo->reqdt)->format('d/m/Y') }}" disabled>
                    </div>

                    <div class="col-md-6 mt-3">
                        <label for="cusna" class="form-label">Customer</label>
                        <input type="text" class="form-control" id="cusna" value="{{ $wo->cusna }}" disabled>
                    </div>

                    <div class="col-md-6 mt-3">
                        <label for="sorno" class="form-label">Order Confirmation Number</label>
                        <input type="text" class="form-control" id="sorno" value="{{ $wo->sorno }}" disabled>
                    </div>

                    <div class="col-md-6 mt-3">
                        <label for="costc" class="form-label">Const Center</label>
                        <input type="text" class="form-control" id="costc" value="{{ $wo->costc }}" disabled>
                    </div>

                    <div class="col-md-6 mt-3">
                        <label for="fdate" class="form-label">Finish Schedule</label>
                        <input type="text" class="form-control" id="fdate" value="{{ \Carbon\Carbon::parse($wo->fdate)->format('d/m/Y') }}" disabled>
                    </div>

                    <div class="col-md-12 mt-3">
                        <label class="form-label">Notes</label>
                        <textarea type="text" class="form-control" id="noteh" name="noteh" maxlength="200">{{ $wo->noteh }}</textarea>
                    </div>
                </div>
            </div>

            {{-- Detail --}}
            <div class="row mt-4">
                <h3>WO Detail</h3>
                <div class="accordion" id="accordionWo">
                    @foreach ($wo->wodtls as $i => $detail)
                    <div class="accordion-item" id="accordion-item-{{ $i }}">
                        <h2 class="accordion-header d-flex justify-content-between align-items-center" id="heading-{{ $i }}">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                data-bs-target="#collapse-{{ $i }}" aria-expanded="false">
                                   <span class="accordion-title">Product: {{ $detail->outpr }} - {{ $detail->mpromas->prona }}</span>
                            </button>
                            <button type="button" class="btn btn-sm btn-danger mx-2" onclick="removeDetail({{ $i }})">
                                <i class="bi bi-trash-fill"></i>
                            </button>
                        </h2>
                        <div id="collapse-{{ $i }}" class="accordion-collapse collapse"
                            aria-labelledby="heading-{{ $i }}">
                            <div class="accordion-body">
                                <div class="row">
                                    <div class="col-md-6 mt-3">
                                        <label class="form-label">Barang</label>
                                        <select class="form-control opron select2"
                                                id="opron-{{ $i }}"
                                                name="opron[]"
                                                data-selected="{{ $detail->outpr }}"
                                                data-text="{{ $detail->outpr }} - {{ $detail->mpromas->prona }}"
                                                data-stdqu="{{ $detail->stdqu }}">
                                        </select>
                                    </div>

                                    <div class="col-md-6 mt-3">
                                        <label class="form-label">Request Quantity</label>
                                        <div class="input-group">
                                            <input type="number" class="form-control outqt" id="outqt-{{ $i }}" name="outqt[]" value="{{ $detail->outqt }}" min="1" required
                                            oninput="this.value = this.value.replace(/[^0-9]/g, '');">
                                            <span class="input-group-text unit-label">{{ $detail->stdqu }}</span>
                                        </div>
                                        <input type="text" class="stdqu" name="stdqu[]" id="stdqu-{{ $i }}" hidden>
                                    </div>

                                    <div class="col-md-6 mt-3">
                                        <label class="form-label">Receive Quantity</label>
                                        <div class="input-group">
                                            <input type="text" class="form-control" value="{{ $detail->acqty }}" disabled>
                                            <span class="input-group-text">{{ $detail->stdqu }}</span>
                                        </div>
                                    </div>

                                    <div class="col-md-12 mt-3">
                                        <label class="form-label">Notes</label>
                                        <textarea class="form-control" id="noted-{{ $i }}" name="noted[]" maxlength="200">{{ $detail->noted }}</textarea>
                                        <div class="form-text text-danger text-end" style="font-size:0.7rem;">Maksimal 200 karakter</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
                <div class="text-end">
                    <button type="button" class="btn mt-3" style="background-color:#4456f1;color:#fff" onclick="addDetail()">Tambah Detail</button>
                </div>
            </div>

            <div class="mt-3 d-flex justify-content-between">
                <a href="{{ route('wo.index') }}" class="btn btn-secondary">Kembali</a>
                <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
            </div>
        </form>
    </section>
</main>

@include('production.work_order.partial_edit.wo_edit_add_detail')

@push('scripts')
    <script>
        $(document).ready(function () {
            $('select.opron').each(function () {
                initOpronWithSelected($(this));
            });
        });

        // init opron awal load
        function initOpronWithSelected($select) {
            const selectedId   = $select.data('selected');
            const selectedText = $select.data('text');
            const stdqu        = $select.data('stdqu');

            if ($('#reqby').val() === '-' && selectedId) {
                const option = new Option(selectedText, selectedId, true, true);
                $select.append(option);
            }
            
            applyOpronMode($select);


            if (stdqu) {
                const $item = $select.closest('.accordion-item');
                $item.find('.unit-label').text(stdqu);
                $item.find('.stdqu').val(stdqu);
            }

            setAccordionTitle($select.closest('.accordion-item'));
        }

        function reqbyToBpbid(reqby){
            // RA/260001/PST
            if(!reqby || reqby === '-') return null;

            const parts = reqby.split('/');
            if(parts.length !== 3) return null;

            const number = parts[1];
            const branch = parts[2];

            return branch + 'RA' + number; // PSTRA260001
        }

        function applyOpronMode($select) {
            const reqby = $('#reqby').val();

            if ($select.hasClass('select2-hidden-accessible')) {
                $select.select2('destroy');
            }

            if (reqby === '-') {
                loadMasterProduct($select);
                return;
            }

            const bpbid = reqbyToBpbid(reqby);

            if (!bpbid) {
                $select.append('<option value="" disabled selected>Format RA tidak valid</option>');
                return;
            }

            $select.append('<option value="">Loading...</option>');

            $.get('/get-barang-ra/' + bpbid, function (data) {
                $select.empty().append('<option value="" disabled selected>Pilih Barang (RA)</option>');

                const selectedId = $select.data('selected');
                
                data.forEach(item => {
                    $select.append(`
                        <option value="${item.opron}"
                                data-qty="${item.rqqty}"
                                data-stdqu="${item.stdqu}">
                            ${item.opron} - ${item.prona}
                        </option>
                    `);
                });

                $select.select2({
                    width: '100%',
                    theme: 'bootstrap-5'
                });

                if (selectedId) {
                    $select.val(selectedId).trigger('change');
                }
            });
        }

        // ambil master product
        function loadMasterProduct($select){
            $select.select2({
                placeholder: 'Pilih Barang',
                theme: 'bootstrap-5',
                width: '100%',
                allowClear: true,
                ajax: {
                    url: '{{ route("api.products") }}',
                    dataType: 'json',
                    delay: 250,
                    data: function(params){
                        return {
                            q: params.term || '',
                            page: params.page || 1
                        };
                    },
                    processResults: function(data){
                        return {
                            results: (data.results || []).map(item => ({
                                id: item.id,
                                text: item.text,
                                stdqu: item.data_stdqu
                            })),
                            pagination: { more: data.pagination.more }
                        };
                    }
                }
            });
        }

        // ubah nama accordion 
        function setAccordionTitle(item){
            const prona = item.find('select[name*="opron"] option:selected').text() || '';
            item.find('.accordion-title').text(prona ? `Product : ${prona}` : '-');
        }

        $(document).on('select2:select', 'select.opron', function (e) {
            const $select = $(this);
            const data = e.params.data || {};
            const $accordionItem = $select.closest('.accordion-item');

            if (data.stdqu) {
                $select.attr('data-stdqu', data.stdqu);

                $accordionItem.find('.unit-label').text(data.stdqu);
                $accordionItem.find('.stdqu').val(data.stdqu);
            }

            setAccordionTitle($accordionItem);
        });

        $(document).on('change','select[name*="opron"]', function(){
            const $accordionItem = $(this).closest('.accordion-item');

            setAccordionTitle($accordionItem);

            const stdqu =
            $(this).find(':selected').data('stdqu')
            || $(this).attr('data-stdqu')
            || '-';

            $accordionItem.find('.unit-label').text(stdqu);

            $accordionItem.find('.stdqu').val(stdqu);
        });

        // SweetAlert confirm submit
        document.addEventListener("DOMContentLoaded", function() {
            const form = document.getElementById('form-wo');
            form.addEventListener('submit', function (e) {
            e.preventDefault();
            Swal.fire({
                title: 'Konfirmasi Ubah',
                text: 'Apakah Anda yakin ingin mengubah data ini?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, Ubah!',
                cancelButtonText: 'Batal'
            }).then((res)=>{
                if(res.isConfirmed){
                Swal.fire({ title:'Mengubah...', text:'Mohon tunggu sebentar', icon:'info', showConfirmButton:false, allowOutsideClick:false, allowEscapeKey:false, didOpen:()=>Swal.showLoading() });
                form.submit();
                }
            });
            });
        });
    </script>
@endpush
@endsection
