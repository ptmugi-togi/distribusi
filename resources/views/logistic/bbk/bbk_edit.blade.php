@extends('layout.main')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/global.css') }}">
@endpush

@section('container')
<main id="main" class="main">
    <div class="d-flex justify-content-between align-items-center">
        <div class="pagetitle">
            <h1>Edit Data BBK</h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('bbk.index') }}">List BBK</a></li>
                    <li class="breadcrumb-item active">BBK Edit</li>
                </ol>
            </nav>
        </div>

        <div class="card">
            <h5 class="p-2"><b>Branch : {{ auth()->user()->cabang }}</b></h5>
        </div>
    </div>

    <section class="section">
        <form id="form-bbk" action="{{ route('bbk.update', $bbk->bbkid) }}" method="POST">
            @csrf
            @method('PUT')

            <input type="text" name="braco" id="braco" value="{{ auth()->user()->cabang }}" hidden>

            <div class="row">
                <div class="col-md-6 mt-3">
                    <label class="form-label">Formc</label>
                    <input type="text" class="form-control" name="formc" id="formc" 
                        value="{{ old('formc', $bbk->formc ?? '') }} ({{ $bbk->desc_c ?? '' }})"
                        readonly 
                        style="background-color:#e9ecef;">
                </div>

                <div class="col-md-6 mt-3">
                    <label class="form-label">Warehouse</label>
                    <input type="text" class="form-control" name="warco" id="warco" value="{{ $bbk->warco }}" readonly style="background-color:#e9ecef">
                </div>

                <div class="col-md-6 mt-3">
                    <label class="form-label">Stock Receipt No.</label>
                    <input type="text" class="form-control" name="trano" value="{{ $bbk->trano }}" readonly style="background-color:#e9ecef">
                </div>

                <div class="col-md-6 mt-3">
                    <label class="form-label">Stock Receipt Date</label>
                    <input type="date" class="form-control" name="tradt" id="tradt"
                        value="{{ old('tradt', \Carbon\Carbon::parse($bbk->tradt ?? now())->format('Y-m-d')) }}"
                        required min="{{ date('Y-m-01') }}" readonly style="background-color:#e9ecef">
                </div>


                <div class="col-md-12 mt-3">
                    <label class="form-label">Notes</label>
                    <textarea class="form-control" name="noteh" id="noteh" maxlength="200">{{ old('noteh', $bbk->noteh ?? '') }}</textarea>
                    <div class="form-text text-danger text-end" style="font-size: .7rem;">Maksimal 200 karakter</div>
                    @error('noteh')
                        <span class="text-danger"><strong>{{ $message }}</strong></span>
                    @enderror
                </div>
            </div>

            <div class="row">
                    <h3 class="my-2">BBK Detail</h3>
                    <div class="accordion" id="accordionBbk">
                        @foreach ($details as $i => $d)
                            <div class="accordion-item" id="accordion-item-{{ $i }}">
                                <h2 class="accordion-header d-flex justify-content-between align-items-center" id="heading-{{ $i }}">
                                    <button class="accordion-button {{ $i > 0 ? 'collapsed' : '' }}" type="button"
                                            data-bs-toggle="collapse" data-bs-target="#details-{{ $i }}"
                                            aria-expanded="{{ $i == 0 ? 'true' : 'false' }}" data-bs-parent="#accordionBbk">
                                                {{ 'Product: ' . $d->opron . ' - ' . $d->prona ?? 'Detail ' . ($i+1) }}
                                    </button>
                                    @if($i > 0)
                                        <button type="button" class="btn btn-sm btn-danger mx-2" onclick="removebbkDetail({{ $i }})">
                                            <i class="bi bi-trash-fill"></i>
                                        </button>
                                    @endif
                                </h2>

                                <div id="details-{{ $i }}" class="accordion-collapse collapse {{ $i == 0 ? 'show' : '' }}">
                                    <div class="accordion-body">
                                        <div class="row">

                                            <div class="col-md-6 mt-3">
                                                <label for="opron" class="form-label">Barang</label><span class="text-danger"> *</span>
                                                <select class="select2 form-control" name="opron[]" id="opron-{{ $i }}" required>
                                                    <option value="{{ $d->opron }}" selected>{{ $d->opron }} - {{ $d->prona }}</option>
                                                </select>
                                            </div>
                                            
                                            <input type="text" id="stdqt-{{ $i }}" class="stdqu-input" name="stdqt[]" value="{{ old('stdqt.'. $i, $d->qunit ?? '') }}" hidden>

                                            <div class="col-md-6 mt-3">
                                                <label for="trqty-{{ $i }}" class="form-label">Receipt Quantity</label><span class="text-danger"> *</span>
                                                <div class="input-group">
                                                    <input type="number" class="form-control" id="trqty-{{ $i }}" name="trqty[]"
                                                        value="{{ old('trqty.'. $i, $d->trqty ?? '') }}"
                                                        oninput="
                                                            this.value = this.value.replace(/[^0-9]/g, '');
                                                            const inqty = Number(document.getElementById('inqty-{{ $i }}')?.value || 0);
                                                            if(!inqty || inqty <= 0){ return; }
                                                            if (Number(this.value) > inqty) {
                                                                Swal.fire({
                                                                    title: 'Peringatan',
                                                                    text: 'Jumlah Receipt qty tidak boleh lebih banyak dari jumlah Invoice qty',
                                                                    icon: 'error'
                                                                });
                                                                this.value = inqty;
                                                            }
                                                        ">
                                                    <span class="input-group-text unit-label">{{ $d->qunit }}</span>
                                                </div>
                                            </div>

                                            <div class="col-md-6 mt-3">
                                                <label for="lotno-{{ $i }}" class="form-label">Serial / Batch No.</label><span class="text-danger"> *</span>
                                                <input type="text" class="form-control" name="lotno[]" id="lotno-{{ $i }}"
                                                    value="{{ old('lotno.'. $i, $d->lotno ?? '') }}" required>
                                            </div>

                                            <div class="col-md-6 mt-3">
                                                <label for="locco-{{ $i }}" class="form-label">Warehouse Location</label><span class="text-danger"> *</span>
                                                <select class="form-control select2 locco-select" name="locco[]" id="locco-{{ $i }}" data-selected="{{ $d->locco }}" required>
                                                    <option value="{{ $d->locco }}" selected>{{ $d->locco }}</option>
                                                </select>
                                            </div>

                                            <div class="col-md-12 mt-3">
                                                <label for="noted" class="form-label">Notes</label>
                                                <textarea type="text" class="form-control" name="noted[]" id="noted" maxlength="200">{{ old('noted.'. $i, $d->noted ?? '') }}</textarea>
                                                <div class="form-text text-danger text-end" style="font-size: 0.7rem;">
                                                    Maksimal 200 karakter
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
            </div>

            @if ($bbk->formc == 'OF')
                <div class="text-end">
                    <button type="button" class="btn mt-3" style="background-color:#4456f1;color:#fff" onclick="addOF()">Tambah Detail BBM</button>
                </div>
            @endif

            <div class="mt-3 d-flex justify-content-between">
                <a href="{{ route('bbk.index') }}" class="btn btn-secondary">Kembali</a>
                <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
            </div>
        </form>
    </section>
</main>

    @push('scripts')

        @include('logistic.bbk.partial_edit.add_detail_of')
        
        {{-- simpan warehouse --}}
        <script>
            let selectedWarehouse = "{{ $bbk->warco }}";
        </script>

        {{-- simpan pilihan warehouse  --}}
        <script>
            $(document).on('change', 'select[name="warco"]', function () {
                selectedWarehouse = $(this).val();
            });

            // simpan pilihan receiving instruction
            $(document).on('change', 'select[name="refcno"]', function () {
                selectedReceivingInstruction = $(this).val();
            });
        </script>

        {{-- old value --}}
        <script>
            $(document).ready(function () {
                // Inisialisasi select2
                $('.select2').select2({ width: '100%' });

                // Ambil old value dari Laravel
                const oldFormc = @json(old('formc'));
                const oldWarco = @json(old('warco'));
                const oldLocco = @json(old('locco'));

                // Tunggu sampai semua select2 siap
                setTimeout(() => {
                    // Restore Formc
                    if(oldFormc) $('#formc').val(oldFormc).trigger('change');

                    if(oldWarco) $('#warco').val(oldWarco).trigger('change');


                    if(oldLocco) $('#locco').val(oldLocco).trigger('change');
                }, 500); // delay 0.5 detik biar select2 udah siap

                // saat warehouse dipilih -> isi semua select locco
                $('#warco').on('change', function () {
                    const warco = $(this).val();

                    // Kosongkan semua dropdown dulu
                    $('.locco-select').each(function () {
                        $(this).empty().append('<option value="">Loading...</option>');
                    });

                    // Ambil data lokasi dari server
                    $.ajax({
                        url: `/get-locco/${warco}`,
                        type: 'GET',
                        success: function (data) {
                            $('.locco-select').each(function () {
                                const loccoSelect = $(this);
                                const selectedLocco = loccoSelect.data('selected'); // ambil data-selected

                                loccoSelect.empty().append('<option value="" disabled selected>Pilih Lokasi</option>');

                                data.forEach(function (item) {
                                    loccoSelect.append(
                                        `<option value="${item.locco}" ${item.locco == selectedLocco ? 'selected' : ''}>
                                            ${item.locco}
                                        </option>`
                                    );
                                });

                                loccoSelect.trigger('change.select2');
                            });
                        },
                        error: function () {
                            $('.locco-select').each(function () {
                                $(this).empty().append('<option value="" disabled selected>Gagal memuat lokasi</option>');
                            });
                        }
                    });
                });

                $(document).ready(function () {
                    const warco = $('#warco').val();
                    if (warco) {
                        $('#warco').trigger('change');
                    }
                });

            });
        </script>

        {{-- ambil data barang jika tidak ada pono atau invno --}}
        <script>
            function loadMasterProductAll(){
                $('select.opron-editIA, select.opron-editIB, select.opron-editIF').each(function(){
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
        </script>

        {{-- otomatis ambil lotno akhir --}}
        <script>
            $(document).on('input', 'input[name="lotno[]"], input[name="trqty[]"]', function () {
                const index = $(this).attr('id').split('-')[1];
                const lotStart = $(`#lotno-${index}`).val();
                const trqty = parseInt($(`#trqty-${index}`).val()) || 0;

                if (!lotStart || trqty <= 0) {
                    $(`#lotnoend-${index}`).val('');
                    return;
                }

                // Cari semua angka di string
                const matches = [...lotStart.matchAll(/\d+/g)];
                if (matches.length === 0) {
                    $(`#lotnoend-${index}`).val(lotStart);
                    return;
                }

                let chosenMatch;

                if (matches.length === 1) {
                    // Cuma satu angka → pakai itu
                    chosenMatch = matches[0];
                } else {
                    // Lebih dari 1 angka → pilih angka terakhir yang "kemungkinan serial"
                    // misal 2024-0005 → ambil 0005 karena lebih pendek dari 2024
                    chosenMatch = matches.reduce((prev, curr) => {
                        return curr[0].length <= prev[0].length ? curr : prev;
                    });
                }

                const number = parseInt(chosenMatch[0]);
                const nextNumber = number + trqty - 1;

                // Pertahankan jumlah digit nol di depan
                const paddedNext = String(nextNumber).padStart(chosenMatch[0].length, '0');

                // Ganti hanya angka yang dipilih
                const lotEnd =
                    lotStart.slice(0, chosenMatch.index) +
                    paddedNext +
                    lotStart.slice(chosenMatch.index + chosenMatch[0].length);

                $(`#lotnoend-${index}`).val(lotEnd);
            });
        </script>
    
        {{-- modal konfirmasi simpan data --}}
        <script>
            document.addEventListener("DOMContentLoaded", function() {
                const form = document.getElementById('form-bbk');

                form.addEventListener('submit', function (e) {
                    e.preventDefault();

                    if (!form.checkValidity()) {
                        form.classList.add('was-validated');
                        return;
                    }

                    Swal.fire({
                        title: 'Konfirmasi Simpan',
                        text: 'Apakah Anda yakin ingin menyimpan data ini?',
                        icon: 'question',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Ya, Simpan!',
                        cancelButtonText: 'Batal'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            Swal.fire({
                                title: 'Menyimpan...',
                                text: 'Mohon tunggu sebentar',
                                icon: 'info',
                                showConfirmButton: false,
                                allowOutsideClick: false,
                                allowEscapeKey: false,
                                didOpen: () => {
                                    Swal.showLoading();

                                    form.submit();
                                }
                            });
                        }
                    });
                });
            });
        </script>        
    @endpush

@endsection
