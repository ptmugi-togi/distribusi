@extends('layout.main')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/global.css') }}">
@endpush

@section('container')
<main id="main" class="main">
    <div class="d-flex justify-content-between align-items-center">
        <div class="pagetitle">
            <h1>Edit Data BBM</h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('bbm.index') }}">List BBM</a></li>
                    <li class="breadcrumb-item active">BBM Edit</li>
                </ol>
            </nav>
        </div>

        <div class="card">
            <h5 class="p-2"><b>Branch : {{ auth()->user()->cabang }}</b></h5>
        </div>
    </div>

    <section class="section">
        <form id="form-bbm" action="{{ route('bbm.update', $bbm->bbmid) }}" method="POST">
            @csrf
            @method('PUT')

            <input type="text" name="braco" value="{{ auth()->user()->cabang }}" hidden>

            <div class="row">
                <div class="col-md-6 mt-3">
                    <label class="form-label">Formc</label>
                    <input type="text" class="form-control" name="formc" id="formc" 
                        value="{{ old('formc', $bbm->formc ?? '') }} ({{ $bbm->desc_c ?? '' }})"
                        readonly 
                        style="background-color:#e9ecef;">
                </div>

                <div class="col-md-6 mt-3">
                    <label class="form-label">Warehouse</label>
                    <input type="text" class="form-control" name="warco" id="warco" value="{{ $bbm->warco }}" readonly style="background-color:#e9ecef">
                </div>

                <div class="col-md-6 mt-3">
                    <label class="form-label">Stock Receipt No.</label>
                    <input type="text" class="form-control" name="trano" value="{{ $bbm->trano }}" readonly style="background-color:#e9ecef">
                </div>

                <div class="col-md-6 mt-3">
                    <label class="form-label">Stock Receipt Date</label>
                    <input type="date" class="form-control" name="tradt" id="tradt"
                        value="{{ old('tradt', \Carbon\Carbon::parse($bbm->tradt ?? now())->format('Y-m-d')) }}"
                        required min="{{ date('Y-m-01') }}" readonly style="background-color:#e9ecef">
                </div>

                @if ($bbm->formc == 'IB')
                    <div class="col-md-6 mt-3">
                        <label class="form-label">Receiving Instruction</label>
                        <input type="text" class="form-control" id="reffc" value="{{ $bbm->reffc }} {{ $bbm->refno }}" readonly style="background-color:#e9ecef">
                    </div>
                @elseif ($bbm->formc == 'PO')
                    <div class="col-md-6 mt-3">
                        <label class="form-label">PO No</label>
                        <input type="text" class="form-control" id="reffc" value="{{ $bbm->refno }}" readonly style="background-color:#e9ecef">
                    </div>
                @endif

                @if ($bbm->formc != 'IF')
                    <div class="col-md-6 mt-3">
                        <label class="form-label">Supplier</label>
                        <input type="text" class="form-control" id="supplier"
                            value="{{ old('supno', ($bbm->supno ?? '').(($bbm->supno??'') && ($bbm->supna??'') ? ' - ' : '').($bbm->supna ?? '')) }}"
                            readonly style="background-color:#e9ecef;">
                        <input type="text" class="form-control" name="supno" id="supno" value="{{ old('supno', $bbm->supno ?? '') }}" hidden>
                    </div>
                @endif    

                @if ($bbm->formc == 'IB')
                    <div class="col-md-6 mt-3">
                        <label class="form-label">BL No.</label>
                        <input type="text" class="form-control" name="blnum" id="blnum" value="{{ old('blnum', $bbm->blnum ?? '') }}" readonly style="background-color:#e9ecef">
                    </div>

                    <div class="col-md-6 mt-3">
                        <label class="form-label">Vessel</label>
                        <input type="text" class="form-control" name="vesel" id="vesel" value="{{ old('vesel', $bbm->vesel ?? '') }}" readonly style="background-color:#e9ecef">
                    </div>
                @endif

                <div class="col-md-12 mt-3">
                    <label class="form-label">Notes</label>
                    <textarea class="form-control" name="noteh" id="noteh" maxlength="200">{{ old('noteh', $bbm->noteh ?? '') }}</textarea>
                    <div class="form-text text-danger text-end" style="font-size: .7rem;">Maksimal 200 karakter</div>
                    @error('noteh')
                        <span class="text-danger"><strong>{{ $message }}</strong></span>
                    @enderror
                </div>
            </div>

            <div class="row">
                    <h3 class="my-2">BBM Detail</h3>
                    <div class="accordion" id="accordionBbm">
                        @foreach ($details as $i => $d)
                            <div class="accordion-item" id="accordion-item-{{ $i }}">
                                <h2 class="accordion-header d-flex justify-content-between align-items-center" id="heading-{{ $i }}">
                                    <button class="accordion-button {{ $i > 0 ? 'collapsed' : '' }}" type="button"
                                            data-bs-toggle="collapse" data-bs-target="#details-{{ $i }}"
                                            aria-expanded="{{ $i == 0 ? 'true' : 'false' }}" data-bs-parent="#accordionBbm">
                                                {{ 'Product: ' . $d->opron . ' - ' . $d->prona ?? 'Detail ' . ($i+1) }}
                                    </button>
                                    @if($i > 0)
                                        <button type="button" class="btn btn-sm btn-danger mx-2" onclick="removebbmDetail({{ $i }})">
                                            <i class="bi bi-trash-fill"></i>
                                        </button>
                                    @endif
                                </h2>

                                <div id="details-{{ $i }}" class="accordion-collapse collapse {{ $i == 0 ? 'show' : '' }}">
                                    <div class="accordion-body">
                                        <div class="row">
                                            @if($bbm->formc == 'IA')
                                                <input type="text" name="invno[]" value="{{ $bbm->refno }}" hidden>
                                            @endif

                                            @if ($bbm->formc =='IB')
                                                <div class="col-md-6 mt-3">
                                                    <label for="invno" class="form-label">Invoice No.</label>
                                                    <select class="select2 form-control" name="invno[]" id="invno-{{ $i }}" {{ (!$bbm->refno || $bbm->refno == '-') ? 'disabled' : '' }}>
                                                        <option value="{{ $d->invno }}" selected>{{ $d->invno }}</option>
                                                    </select>
                                                </div>
                                            @endif

                                            <div class="col-md-6 mt-3">
                                                <label for="opron" class="form-label">Barang</label><span class="text-danger"> *</span>
                                                <select class="select2 form-control" name="opron[]" id="opron-{{ $i }}" required>
                                                    <option value="{{ $d->opron }}" selected>{{ $d->opron }} - {{ $d->prona }}</option>
                                                </select>
                                            </div>
                                            
                                            @if($bbm->formc == 'IB')
                                                <div class="col-md-6 mt-3">
                                                    <label for="inqty-{{ $i }}" class="form-label">Invoice Quantity</label>
                                                    <div class="input-group">
                                                        <input type="number" class="form-control" id="inqty-{{ $i }}"
                                                            style="background-color: #e9ecef;"
                                                            value="{{ old('inqty.'. $i, $d->inqty ?? '') }}" readonly>
                                                        <span class="input-group-text unit-label">{{ $d->qunit }}</span>
                                                    </div>
                                                </div>
                                            @elseif ($bbm->formc == 'PO')
                                                <div class="col-md-6 mt-3">
                                                    <label for="inqty-{{ $i }}" class="form-label">PO Quantity</label>
                                                    <div class="input-group">
                                                        <input type="number" class="form-control" id="inqty-{{ $i }}"
                                                            style="background-color: #e9ecef;"
                                                            value="{{ old('inqty.'. $i, $d->poqty ?? '') }}" readonly>
                                                        <span class="input-group-text unit-label">{{ $d->qunit }}</span>
                                                    </div>
                                                </div>
                                            @endif

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

                                            @if ($bbm->formc != 'IF')
                                                <div class="col-md-6 mt-3">
                                                    <label for="pono-{{ $i }}" class="form-label">PO No.</label>
                                                    <input type="text" class="form-control" name="pono[]" id="pono-{{ $i }}"
                                                        value="{{ $d->pono ?? $d->pono ?? '' }}"
                                                        readonly style="background-color: #e9ecef">
                                                </div>
                                            @endif

                                            <div class="col-md-6 mt-3">
                                                <label for="lotno-{{ $i }}" class="form-label">Serial / Batch No.</label>
                                                <input type="text" class="form-control" name="lotno[]" id="lotno-{{ $i }}"
                                                    value="{{ old('lotno.'. $i, $d->lotno ?? '') }}">
                                            </div>

                                            <div class="col-md-6 mt-3">
                                                <label for="locco-{{ $i }}" class="form-label">Warehouse Location</label>
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

            @if($bbm->formc == 'IB')
                <div class="text-end">
                    <button type="button" class="btn mt-3" style="background-color:#4456f1;color:#fff" onclick="addIB()">Tambah Detail BBM</button>
                </div>    
            @elseif($bbm->formc == 'IA')
                <div class="text-end">
                    <button type="button" class="btn mt-3" style="background-color:#4456f1;color:#fff" onclick="addIA()">Tambah Detail BBM</button>
                </div>    
            @elseif($bbm->formc == 'IF')
                <div class="text-end">
                    <button type="button" class="btn mt-3" style="background-color:#4456f1;color:#fff" onclick="addIF()">Tambah Detail BBM</button>
                </div>
            @endif

            <div class="mt-3 d-flex justify-content-between">
                <a href="{{ route('bbm.index') }}" class="btn btn-secondary">Kembali</a>
                <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
            </div>
        </form>
    </section>
</main>

    @push('scripts')
        @if($bbm->formc == 'IA')
            @include('logistic.bbm.partial_edit.add_detail_ia')
        @elseif($bbm->formc == 'IB')
            @include('logistic.bbm.partial_edit.add_detail_ib')
        @elseif($bbm->formc == 'IF')
            @include('logistic.bbm.partial_edit.add_detail_if')
        @endif

        {{-- simpan warehouse & refno --}}
        <script>
            let selectedWarehouse = "{{ $bbm->warco }}";
            let selectedReceivingInstruction = "{{ $bbm->refno }}";
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
                const oldRefcno = @json(old('refcno'));
                const oldLocco = @json(old('locco'));

                // Tunggu sampai semua select2 siap
                setTimeout(() => {
                    // Restore Formc
                    if(oldFormc) $('#formc').val(oldFormc).trigger('change');

                    if(oldWarco) $('#warco').val(oldWarco).trigger('change');

                    if(oldRefcno) $('#refcno').val(oldRefcno).trigger('change');

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

        {{-- ambil reffc dan refno dari refcno --}}
        <script>
            $('#refcno').on('change', function() {
                const selected = $(this).find(':selected');
                const formc = selected.data('reffc');
                const rinum = selected.data('refno');
                const supno = selected.data('supno');
                const supna = selected.data('supna');
                const blnum = selected.data('blnum');
                const vesel = selected.data('vesel');

                $('#reffc').val(formc);
                $('#refno').val(rinum);
                $('#supplier').val(supno && supna ? `${supno} - ${supna}` : '');
                $('#supno').val(supno);
                $('#blnum').val(blnum);
                $('#vesel').val(vesel);
            });
            $('#refcno').on('select2:clear', function() {
                $('#reffc, #refno, #supno', '#supplier', '#blnum', '#vesel').val('');
            });
        </script>

        {{-- ambil data dari RI dan invno --}}
        <script>
            // Saat Receiving Instruction berubah -> ambil invoice
            $('#refcno').on('change', function () {
                const rinum = $(this).val();

                $('select[name="invno[]"]').each(function (i) {
                    const select = $(this);
                    select.empty().append('<option value="">Loading...</option>');

                    $.ajax({
                        url: `/get-invoice/${rinum}`,
                        type: 'GET',
                        success: function (data) {
                            select.empty().append('<option value="" disabled selected>Pilih Invoice No.</option>');
                            data.forEach(function (item) {
                                select.append(`<option value="${item.invno}">${item.invno}</option>`);
                            });

                            const oldInvno = @json(old('invno', []));
                            if (oldInvno[i]) {
                                select.val(oldInvno[i]).trigger('change');
                            }
                        }
                    });
                });
            });

            // Saat invoice berubah -> ambil barang
            $(document).on('change', 'select[name="invno[]"]', function () {
                const invno = $(this).val();
                const index = $(this).attr('id').split('-')[1];
                const barangSelect = $(`#opron-${index}`);

                barangSelect.empty().append('<option value="">Loading...</option>');

                $.ajax({
                    url: `/get-barang/${invno}`,
                    type: 'GET',
                    success: function (data) {
                        barangSelect.empty().append('<option value="" disabled selected>Pilih Barang</option>');
                        data.forEach(function (item) {
                            barangSelect.append(
                                `<option value="${item.opron}"
                                    data-qty="${item.inqty}"
                                    data-stdqt="${item.stdqt}"
                                    data-pono="${item.pono}">
                                    ${item.opron} - ${item.prona}
                                </option>`
                            );
                        });

                        const oldOpron = @json(old('opron', []));
                        if (oldOpron[index]) {
                            barangSelect.val(oldOpron[index]).trigger('change');
                        }
                    }
                });
            });

            // Saat barang dipilih -> isi field inqty dan unit
            $(document).on('change', 'select[name="opron[]"]', function () {
                const selected = $(this).find(':selected');
                const index = $(this).attr('id').split('-')[1];
                const opron = $(this).val();
                const qty = selected.data('qty');
                const stdqt = selected.data('stdqt');
                const pono = selected.data('pono');

                $(`#inqty-${index}`).val(qty);
                $(`#inqty-${index}`).next('.input-group-text').text(stdqt);
                $(`#trqty-${index}`).next('.input-group-text').text(stdqt);
                $(`#stdqt-${index}`).val(stdqt);
                $(`#opron-input-${index}`).val(opron);
                $(`#pono-${index}`).val(pono);
            });

            // checkbox lotno
            $(document).on('change', '.nolot-checkbox', function(){
                let container   = $(this).closest('.row, .accordion-body'); 
                if($(this).is(':checked')){
                    container.find('.lot-section').hide();
                    container.find('.lotno-input').val('-'); // default supaya backend ga error
                }else{
                    container.find('.lot-section').show();
                    container.find('.lotno-input').val('');
                }
            });

            // saat warehouse dipilih -> isi select locco
            $('#warco').on('change', function () {
                const warco = $(this).val();
                const loccoSelect = $('#locco');

                loccoSelect.empty().append('<option value="">Loading...</option>');

                $.ajax({
                    url: `/get-locco/${warco}`,
                    type: 'GET',
                    success: function (data) {
                        loccoSelect.empty().append('<option value="" disabled selected>Pilih Lokasi</option>');
                        data.forEach(function (item) {
                            loccoSelect.append(
                                `<option value="${item.locco}">${item.locco}</option>`
                            );
                        });
                        loccoSelect.trigger('change.select2');

                        $('select[name="locco[]"]').each(function () {
                            const loccoSelectDetail = $(this);
                            loccoSelectDetail.empty().append('<option value="" disabled selected>Pilih Lokasi</option>');
                            data.forEach(function (item) {
                                loccoSelectDetail.append(
                                    `<option value="${item.locco}">${item.locco}</option>`
                                );
                            });
                            loccoSelectDetail.trigger('change.select2');
                        });

                        // kalau ada oldlocco
                        const oldLocco = @json(old('locco', []));
                        $('select[name="locco[]"]').each(function (i) {
                            if (oldLocco[i]) {
                                $(this).val(oldLocco[i]).trigger('change');
                            }
                        });
                    },
                    error: function () {
                        loccoSelect.empty().append('<option value="" disabled selected>Gagal memuat lokasi</option>');
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

            $(document).ready(function(){
                applyEditProductSource();
            });

            function applyEditProductSource(){

                const pono = "{{ $bbm->refno }}"; // ini PO atau INVNO tergantung form type

                // reset select dulu
                $('select.opron-editIA, select.opron-editIB').each(function(){
                    $(this).select2('destroy');
                    $(this).select2({ width:'100%', theme:'bootstrap-5' });
                });

                // fallback
                if(!pono || pono.trim() === '' || pono.trim() === '-' ){
                    loadMasterProductAll();
                    return;
                }

                // kalau refno ada → load product dari PO
                $.get(`{{ url('/get-barang') }}/${pono}?formc=IA`, function(data){
                    $('select.opron-editIA').each(function(){
                        const sel = $(this);
                        data.forEach(item => {
                            sel.append(`<option value="${item.opron}" data-qty="${item.inqty}" data-stdqt="${item.stdqt}">${item.opron} - ${item.prona}</option>`)
                        });
                    });
                });

                // kalau ada IB logic nanti tambahin untuk load invoice nya
            }
        </script>

        @if (empty($bbm->refno) || empty($bbm->pono))
        <script>
        $(document).ready(function() {
            const sel = $('#opron-{{ $i }}');
            const currentVal = sel.val(); // ambil value lama (barang yang sudah tersimpan)

            $.get('{{ url("/get-product-all") }}', function(data) {
                // tambahkan semua produk ke select (tapi jangan reset, biar data lama tetap ada)
                data.forEach(item => {
                    // cek supaya gak nambah option duplikat
                    if (sel.find(`option[value="${item.opron}"]`).length === 0) {
                        sel.append(`<option value="${item.opron}">${item.opron} - ${item.opname}</option>`);
                    }
                });

                // set kembali ke barang lama kalau ada
                if (currentVal) {
                    sel.val(currentVal).trigger('change');
                }
            });
        });
        </script>
        @endif

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
                const form = document.getElementById('form-bbm');

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
