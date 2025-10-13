@extends('layout.main')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/purchasing/invoice.css') }}">
@endpush

@section('container')
    <main id="main" class="main">
    <div class="pagetitle">
        <h1>Edit Invoice</h1>
        <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
            <li class="breadcrumb-item"><a href="{{ route('invoice.index') }}">List Invoice</a></li>
            <li class="breadcrumb-item active">Edit</li>
        </ol>
        </nav>
    </div>

    <section class="section">
        <form id="form-invoice" action="{{ route('invoice.update', $invoice->invno) }}" method="POST">
        @csrf
        @method('PUT')

        {{-- Header --}}
        <div class="card p-4 shadow-sm">
            <div class="row">
            <div class="col-md-6 mt-2">
                <label class="form-label">PO Type</label>
                <input type="text" class="form-control" name="potyp" id="potyp"
                value="{{ $invoice->details->first()->potyp }}" readonly style="background-color:#e9ecef;">
            </div>

            <div class="col-md-6 mt-2">
                <label class="form-label">Invoice No.</label>
                <input type="text" class="form-control" name="invno" value="{{ $invoice->invno }}" readonly style="background-color:#e9ecef;">
            </div>
            
            <div class="col-md-6 mt-2">
                <label class="form-label">Invoice Date</label>
                <input type="date" class="form-control" name="invdt" value="{{ $invoice->invdt }}" readonly style="background-color:#e9ecef;">
            </div>

            <div class="col-md-6 mt-2">
                <label class="form-label">Due Date</label>
                <input type="date" class="form-control" name="duedt" value="{{ $invoice->duedt }}" readonly style="background-color:#e9ecef;">
            </div>

            <div class="col-md-6 mt-2">
                <label class="form-label">Supplier</label>
                <input type="text" class="form-control" name="supno" value="{{ $invoice->supno }}" hidden>
                <input type="text" class="form-control" value="{{ $invoice->supno }} - {{ $invoice->vendor->supna }}" readonly style="background-color:#e9ecef;">
            </div>
            
            <div class="col-md-6 mt-2">
                <label class="form-label">Currency</label>
                <input type="text" class="form-control" name="curco" value="{{ $invoice->curco }}" readonly style="background-color:#e9ecef;">
            </div>

            @if($invoice->details->first()->potyp === 'PI')
            <div class="col-md-6 mt-2">
                <label class="form-label">BL / AWB No.</label>
                <input type="text" class="form-control" name="blnum" value="{{ $invoice->blnum }}" readonly style="background-color:#e9ecef;">
            </div>

            <div class="col-md-6 mt-2">
                <label class="form-label">Freight</label>
                <input type="text" class="form-control currency" name="tfreight" value="{{ $invoice->tfreight }}" readonly style="background-color:#e9ecef;">
            </div>
            @endif
            </div>

            <hr class="my-4">

            {{-- ===== DETAIL SECTION ===== --}}
            <h4 class="mb-3">Invoice Detail</h4>

            @php $potyp = $invoice->details->first()->potyp; @endphp

            @if($potyp === 'PI')
            <div id="content-import">
                <div class="accordion" id="accordionInvoiceImport">
                    @foreach($details as $i => $d)
                        <div class="accordion-item" id="import-item-{{ $i }}">
                            <h2 class="accordion-header" id="heading-import-{{ $i }}">
                                <button class="accordion-button {{ $i > 0 ? 'collapsed' : '' }}" type="button"
                                        data-bs-toggle="collapse" data-bs-target="#import-body-{{ $i }}"
                                        aria-expanded="{{ $i == 0 ? 'true' : 'false' }}" aria-controls="import-body-{{ $i }}">
                                    {{ $d->opron ?? 'Line' }}
                                </button>
                            </h2>

                            <div id="import-body-{{ $i }}" class="accordion-collapse collapse {{ $i == 0 ? 'show' : '' }}"
                                aria-labelledby="heading-import-{{ $i }}" data-bs-parent="#accordionInvoiceImport">
                                <div class="accordion-body">
                                    <div class="row">

                                        {{-- NO PO --}}
                                        <div class="col-md-6 mt-3">
                                            <label class="form-label">No. PO <span class="text-danger">*</span></label>
                                            <select class="select2 form-control" name="pono[]" id="import-pono-{{ $i }}" data-value="{{ $d->pono }}" required>
                                                <option value="" disabled {{ old('pono.' . $i) ? '' : 'selected' }}>
                                                    Silahkan pilih Supplier terlebih dahulu
                                                </option>
                                            </select>
                                            <input type="text" name="id_su[]" value="{{ $d->id_su ?? '' }}" hidden>
                                        </div>

                                        {{-- BARANG --}}
                                        <div class="col-md-6 mt-3">
                                            <label class="form-label">Barang <span class="text-danger">*</span></label>
                                            <select class="select2 form-control opron-select" name="opron[]" id="import-opron-{{ $i }}" data-value="{{ $d->opron }}" required>
                                                <option value="" disabled {{ old('opron.' . $i) ? '' : 'selected' }}>
                                                    Silahkan pilih PO No. terlebih dahulu
                                                </option>
                                            </select>
                                        </div>

                                        {{-- PO Quantity --}}
                                        <div class="col-md-6 mt-3">
                                            <label class="form-label">PO Quantity</label>
                                            <div class="input-group">
                                                <input type="text" class="form-control poqty" style="background-color: #e9ecef;"
                                                    name="poqty[]" id="poqty-{{ $i }}" value="{{ $d->poqty ?? '' }}" readonly>
                                                <span class="input-group-text unit-label">{{ $d->stdqu ?? '' }}</span>
                                                <input type="hidden" name="stdqt[]" class="stdqu-input" value="{{ $d->stdqu ?? '' }}">
                                            </div>
                                        </div>

                                        {{-- Invoice Quantity --}}
                                        <div class="col-md-6 mt-3">
                                            <label class="form-label">Invoice Quantity</label>
                                            <div class="input-group">
                                                <input type="text" class="form-control" name="inqty[]" id="inqty-{{ $i }}" value="{{ $d->inqty ?? '' }}">
                                                <span class="input-group-text unit-label">{{ $d->stdqu ?? '' }}</span>
                                            </div>
                                        </div>

                                        {{-- Invoice Price --}}
                                        <div class="col-md-6 mt-3">
                                            <label class="form-label">Invoice Price</label>
                                            <input type="text" class="form-control currency" style="background-color: #e9ecef;"
                                                name="netpr[]" id="netpr-{{ $i }}" value="{{ $d->netpr ?? '' }}" readonly>
                                        </div>

                                        {{-- Invoice Amount --}}
                                        <div class="col-md-6 mt-3">
                                            <label class="form-label">Invoice Amount</label>
                                            <input type="text" class="form-control currency" name="inprc[]" id="inprc-{{ $i }}" value="{{ $d->inprc ?? '' }}">
                                        </div>

                                        {{-- Ex-Work Price --}}
                                        <div class="col-md-6 mt-3">
                                            <label class="form-label">Ex-Work Price</label>
                                            <input type="text" class="form-control currency" name="ewprc[]" value="{{ $d->ewprc ?? '' }}">
                                        </div>

                                        {{-- FOB Charges --}}
                                        <div class="col-md-6 mt-3">
                                            <label class="form-label">FOB Charges</label>
                                            <input type="text" class="form-control currency" name="fobch[]" value="{{ $d->fobch ?? '' }}">
                                        </div>

                                        {{-- Insurance --}}
                                        <div class="col-md-6 mt-3">
                                            <label class="form-label">Insurance</label>
                                            <input type="text" class="form-control currency" name="incst[]" value="{{ $d->incst ?? '' }}">
                                        </div>

                                        {{-- HS No. --}}
                                        <div class="col-md-6 mt-3">
                                            <label class="form-label">HS No.</label>
                                            <select class="select2 form-select hsn-select" name="hsn[]">
                                                @foreach ($hsnList as $h)
                                                    <option value="{{ $h->hsn }}" data-bm="{{ $h->bm }}"
                                                        {{ $d->hsn == $h->hsn ? 'selected' : '' }}>
                                                        {{ $h->hsn }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>

                                        {{-- BM --}}
                                        <div class="col-md-6 mt-3">
                                            <label class="form-label">BM (%)</label>
                                            <input type="number" class="form-control" name="bm[]" value="{{ $d->bm ?? '' }}">
                                        </div>

                                        {{-- Pajak --}}
                                        <div class="col-md-2 mt-3">
                                            <label class="form-label">PPn (%)</label>
                                            <input type="number" class="form-control" name="ppn[]" value="{{ $d->ppn ?? '' }}">
                                        </div>
                                        <div class="col-md-2 mt-3">
                                            <label class="form-label">PPnBM (%)</label>
                                            <input type="number" class="form-control" name="ppnbm[]" value="{{ $d->ppnbm ?? '' }}">
                                        </div>
                                        <div class="col-md-2 mt-3">
                                            <label class="form-label">PPh (%)</label>
                                            <input type="number" class="form-control" name="pph[]" value="{{ $d->pph ?? '' }}">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="text-end">
                    <button type="button" class="btn mt-3"
                            style="background-color: #4456f1; color: #fff"
                            onclick="addInvoiceImport()">Tambah Detail Invoice</button>
                </div>
            </div>
            
            @else

            <div id="content-loc-inv">
                <div class="accordion" id="accordionInvoiceLocInv">
                    @foreach($details as $i => $d)
                    <div class="accordion-item" id="accordion-item-{{ $i }}">
                        <h2 class="accordion-header d-flex justify-content-between align-items-center" id="heading-{{ $i }}">
                            <button class="accordion-button {{ $i > 0 ? 'collapsed' : '' }}" type="button"
                                    data-bs-toggle="collapse" data-bs-target="#barang-{{ $i }}"
                                    aria-expanded="{{ $i == 0 ? 'true' : 'false' }}" aria-controls="barang-{{ $i }}">
                                {{ $d->opron ?? 'Line' }}
                            </button>
                        </h2>

                        <div id="barang-{{ $i }}" class="accordion-collapse collapse {{ $i == 0 ? 'show' : '' }}"
                            aria-labelledby="heading-{{ $i }}" data-bs-parent="#accordionPoBarang">
                            <div class="accordion-body">
                                <div class="row">
                                    {{-- NO PO --}}
                                    <div class="col-md-6 mt-3">
                                        <label class="form-label">No. PO <span class="text-danger">*</span></label>
                                        <select class="select2 form-control" name="pono[]" id="locinv-pono-{{ $i }}" data-value="{{ $d->pono }}" required>
                                            <option value="" disabled {{ old('pono.'. $i) ? '' : 'selected' }}>
                                                Silahkan pilih Supplier terlebih dahulu
                                            </option>
                                        </select>
                                        <input type="text" name="id_su[]" value="{{ $d->id_su ?? '' }}" hidden>
                                    </div>

                                    {{-- BARANG --}}
                                    <div class="col-md-6 mt-3">
                                        <label class="form-label">Barang <span class="text-danger">*</span></label>
                                        <select class="select2 form-control opron-select" name="opron[]" id="locinv-opron-{{ $i }}" data-value="{{ $d->opron }}" required>
                                            <option value="" disabled {{ old('opron.'. $i) ? '' : 'selected' }}>
                                                Silahkan pilih PO No. terlebih dahulu
                                            </option>
                                        </select>
                                    </div>

                                    {{-- PO Quantity --}}
                                    <div class="col-md-6 mt-3">
                                        <label class="form-label">PO Quantity</label>
                                        <div class="input-group">
                                            <input type="text" class="form-control poqty" style="background-color: #e9ecef;" name="poqty[]" id="poqty-{{ $i }}" value="{{ $d->poqty ?? '' }}" readonly>
                                            <span class="input-group-text unit-label">{{ $d->stdqu ?? '' }}</span>
                                            <input type="hidden" name="stdqt[]" class="stdqu-input" value="{{ $d->stdqu ?? '' }}">
                                        </div>
                                    </div>

                                    {{-- Invoice Quantity --}}
                                    <div class="col-md-6 mt-3">
                                        <label class="form-label">Invoice Quantity</label>
                                        <div class="input-group">
                                            <input type="text" class="form-control" name="inqty[]" id="inqty-{{ $i }}" value="{{ $d->inqty ?? '' }}">
                                            <span class="input-group-text unit-label">{{ $d->stdqu ?? '' }}</span>
                                        </div>
                                    </div>

                                    {{-- Invoice Price --}}
                                    <div class="col-md-6 mt-3">
                                        <label class="form-label">Invoice Price</label>
                                        <input type="text" class="form-control currency" style="background-color: #e9ecef;" name="netpr[]" id="netpr-{{ $i }}" value="{{ $d->netpr ?? '' }}" readonly>
                                    </div>

                                    {{-- Invoice Amount --}}
                                    <div class="col-md-6 mt-3">
                                        <label class="form-label">Invoice Amount</label>
                                        <input type="text" class="form-control currency" name="inprc[]" id="inprc-{{ $i }}" value="{{ $d->inprc ?? '' }}">
                                    </div>

                                    {{-- Pajak --}}
                                    <div class="col-md-4 mt-3">
                                        <label class="form-label">PPn (%)</label>
                                        <input type="number" class="form-control" name="ppn[]" value="{{ $d->ppn ?? '' }}">
                                    </div>
                                    <div class="col-md-4 mt-3">
                                        <label class="form-label">PPnBM (%)</label>
                                        <input type="number" class="form-control" name="ppnbm[]" value="{{ $d->ppnbm ?? '' }}">
                                    </div>
                                    <div class="col-md-4 mt-3">
                                        <label class="form-label">PPh (%)</label>
                                        <input type="number" class="form-control" name="pph[]" value="{{ $d->pph ?? '' }}">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    @endforeach
                </div>

                <div class="text-end">
                    <button type="button" class="btn mt-3" style="background-color: #4456f1; color: #fff" onclick="addInvoiceLocInv()">Tambah Detail Invoice</button>
                </div>
            </div>
            @endif

            {{-- BUTTONS --}}
            <div class="mt-4 d-flex justify-content-between">
            <a href="{{ route('invoice.index') }}" class="btn btn-secondary">Kembali</a>
            <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
            </div>
        </div>
        </form>
    </section>
    </main>

    {{-- include reusable add scripts --}}
    @include('purchasing.invoice.partial_create.add_invoice_import')
    @include('purchasing.invoice.partial_create.add_invoice_loc_inv')

    @push('scripts')
        {{-- oldvalue --}}
        <script>
            $(document).ready(function () {
                $('.select2').select2({ width: '100%' });

                const oldPono = @json(old('pono', []));
                const oldOpron = @json(old('opron', []));
                const oldHsn = @json(old('hsn', []));
                const oldPotype = @json(old('potype'));

                // ✅ ambil supno & curco dari input readonly
                const supno = $('input[name="supno"]').val();
                const curco = $('input[name="curco"]').val() || 'IDR';

                let $formSection;

                if (oldPotype === 'PI') {
                    $formSection = $('#content-import');
                } else if (oldPotype === 'PO' || oldPotype === 'PN') {
                    $formSection = $('#content-loc-inv');
                } else {
                    return;
                }

                // ✅ pakai supno dari input (bukan select)
                if (!supno) return;
                $.getJSON(`/get-po-by-supplier/${supno}`).then(async (response) => {
                    if (!response.success || response.data.length === 0) return;

                    let poOptions = '<option value="" disabled>Pilih No. PO</option>';
                    response.data.forEach(po => {
                        poOptions += `<option value="${po.pono}">${po.pono}</option>`;
                    });

                    $formSection.find('select[name="pono[]"]').each(function (i) {
                        $(this).html(poOptions).trigger('change.select2');
                    });

                    // Loop setiap baris yang ada old value
                    for (let i = 0; i < oldPono.length; i++) {
                        const pono = oldPono[i];
                        const opron = oldOpron[i];
                        const hsn = oldHsn[i];
                        if (!pono) continue;

                        const $accordion = $formSection.find('.accordion-item').eq(i);
                        const $ponoSelect = $accordion.find('select[name="pono[]"]');
                        const $opronSelect = $accordion.find('select[name="opron[]"]');
                        const $hsnSelect = $accordion.find('select[name="hsn[]"]');

                        $ponoSelect.val(pono).trigger('change.select2');

                        const res = await $.getJSON(`/get-items-by-po/${pono}`);
                        if (res.success && res.data.length > 0) {
                            let opronOptions = '<option value="" disabled>Pilih Barang</option>';
                            res.data.forEach(item => {
                                opronOptions += `
                                    <option value="${item.opron}"
                                        data-qty="${item.poqty}"
                                        data-price="${item.netpr}"
                                        data-stdqu="${item.stdqu}">
                                        ${item.opron} - ${item.prona}
                                    </option>`;
                            });
                            $opronSelect.html(opronOptions).trigger('change.select2');

                            if (opron) {
                                $opronSelect.val(opron).trigger('change');
                                $opronSelect.trigger({
                                    type: 'select2:select',
                                    params: { data: { id: opron, text: $opronSelect.find(':selected').text() } }
                                });
                            }

                            const selected = $opronSelect.find(':selected');
                            const qty = selected.data('qty') || '';
                            const stdqu = selected.data('stdqu') || '';
                            const price = selected.data('price') || '';

                            const $body = $accordion.find('.accordion-body');
                            $body.find('.poqty').val(qty);
                            $body.find('.unit-label').text(stdqu);
                            $body.find('.stdqu-input').val(stdqu);
                            $body.find('input[name="netpr[]"]').val(price);

                            // format currency pakai curco input
                            if (typeof window.formatAllCurrency === 'function') {
                                setTimeout(() => window.formatAllCurrency(curco), 0);
                            }
                        }

                        if (hsn && $hsnSelect.length) {
                            $hsnSelect.val(hsn).trigger('change.select2');
                        }
                    }
                });
            });
        </script>

        {{-- pas awal ambil dari db menyesuaikan supplier, pono, opron --}}
        <script>
            $(document).ready(function () {
                $('.select2').select2({ width: '100%' });

                const supno = $('input[name="supno"]').val();
                const curco = $('input[name="curco"]').val() || 'IDR';
                if (supno) preloadPoAndBarang(supno);

                // ✅ kalau tambah detail, tetap pakai supno & curco
                $(document).on('click', 'button[onclick^="addInvoice"]', function () {
                    if (supno) preloadPoAndBarang(supno);
                    if (typeof window.formatAllCurrency === 'function') {
                        setTimeout(() => window.formatAllCurrency(curco), 200);
                    }
                });

                async function preloadPoAndBarang(supno) {
                    try {
                        const poRes = await $.getJSON(`/get-po-by-supplier/${supno}`);
                        if (!poRes.success) return;

                        let poOptions = '<option value="" disabled selected>Pilih No. PO</option>';
                        poRes.data.forEach(po => {
                            poOptions += `<option value="${po.pono}">${po.pono}</option>`;
                        });

                        $('select[name="pono[]"]').each(async function (i) {
                            const $ponoSelect = $(this);
                            const oldPono = $ponoSelect.data('value');
                            $ponoSelect.html(poOptions);

                            if (oldPono && poRes.data.some(p => p.pono === oldPono)) {
                                $ponoSelect.val(oldPono).trigger('change.select2');
                                await preloadBarangByPono($ponoSelect, oldPono, i);
                            } else {
                                $ponoSelect.val('').trigger('change.select2');
                            }
                        });
                    } catch (err) {
                        console.error('Gagal preload PO:', err);
                    }
                }

                async function preloadBarangByPono($ponoSelect, pono, index) {
                    const $body = $ponoSelect.closest('.accordion-body');
                    const $opronSelect = $body.find('select[name="opron[]"]');
                    const oldOpron = $opronSelect.data('value');
                    try {
                        const res = await $.getJSON(`/get-items-by-po/${pono}`);
                        if (!res.success) return;

                        let opronOptions = '<option value="" disabled selected>Pilih Barang</option>';
                        res.data.forEach(item => {
                            opronOptions += `
                                <option value="${item.opron}"
                                    data-qty="${item.poqty}"
                                    data-price="${item.netpr}"
                                    data-stdqu="${item.stdqu}">
                                    ${item.opron} - ${item.prona}
                                </option>`;
                        });

                        $opronSelect.html(opronOptions);

                        if (oldOpron && res.data.some(it => it.opron === oldOpron)) {
                            $opronSelect.val(oldOpron).trigger('change.select2');
                            const selected = $opronSelect.find(':selected');
                            const qty = selected.data('qty') || '';
                            const stdqu = selected.data('stdqu') || '';
                            const price = selected.data('price') || '';

                            $body.find('.poqty').val(qty);
                            $body.find('.unit-label').text(stdqu);
                            $body.find('.stdqu-input').val(stdqu);
                            const $priceInput = $body.find('input[name="netpr[]"]');
                            $priceInput.val(price);

                            // ubah header accordion
                            const label = `${selected.val()} - ${selected.text().split(' - ')[1] || ''}`;
                            $body.closest('.accordion-item').find('.accordion-button')
                                .contents().filter(function () { return this.nodeType === 3; })
                                .first().replaceWith(` ${label}`);

                            // format ulang currency pakai curco
                            if (typeof window.formatAllCurrency === 'function') {
                                setTimeout(() => window.formatAllCurrency(curco), 0);
                            }
                        }
                    } catch (err) {
                        console.error('Gagal preload barang:', err);
                    }
                }
            });
        </script>

        {{-- kalau edit pono dan oprpon --}}
        <script>
            $(document).ready(function () {
                const curco = $('input[name="curco"]').val() || 'IDR';

                // kalau ubah pono
                $(document).on('change', 'select[name="pono[]"]', async function () {
                    const $ponoSelect = $(this);
                    const pono = $ponoSelect.val();
                    const $body = $ponoSelect.closest('.accordion-body');
                    const $opron = $body.find('select[name="opron[]"]');

                    $opron.html('<option value="">Loading Barang...</option>').trigger('change.select2');
                    $body.find('.poqty').val('');
                    $body.find('.unit-label').text('');
                    $body.find('input[name="netpr[]"]').val('');
                    $body.find('.stdqu-input').val('');

                    if (!pono) return;

                    try {
                        const res = await $.getJSON(`/get-items-by-po/${pono}`);
                        if ($ponoSelect.val() !== pono) return;

                        if (res.success && res.data.length > 0) {
                            let options = '<option value="" disabled selected>Pilih Barang</option>';
                            res.data.forEach(item => {
                                options += `
                                    <option value="${item.opron}"
                                        data-qty="${item.poqty}"
                                        data-price="${item.netpr}"
                                        data-stdqu="${item.stdqu}">
                                        ${item.opron} - ${item.prona}
                                    </option>`;
                            });
                            $opron.html(options).val('').trigger('change.select2');
                        } else {
                            $opron.html('<option value="">Tidak ada barang untuk PO ini</option>').trigger('change.select2');
                        }
                    } catch (e) {
                        $opron.html('<option value="">Gagal memuat barang</option>').trigger('change.select2');
                    }
                });

                // kalau ubah barang
                $(document).on('change', 'select[name="opron[]"]', function () {
                    const $sel = $(this).find(':selected');
                    const qty   = $sel.data('qty')   || '';
                    const stdqu = $sel.data('stdqu') || '';
                    const price = $sel.data('price') || '';

                    const $body = $(this).closest('.accordion-body');
                    $body.find('.poqty').val(qty);
                    $body.find('.unit-label').text(stdqu);
                    $body.find('.stdqu-input').val(stdqu);
                    $body.find('input[name="netpr[]"]').val(price);

                    const label = `${$sel.val()} - ${($sel.text().split(' - ')[1] || '')}`;
                    $body.closest('.accordion-item').find('.accordion-button')
                        .contents().filter(function(){ return this.nodeType === 3; })
                        .first().replaceWith(` ${label}`);

                    // format ulang currency pakai curco
                    if (typeof formatAllCurrency === 'function') {
                        setTimeout(() => formatAllCurrency(curco), 0);
                    }
                });
            });
        </script>

        {{-- format currency --}}
        <script>
            document.addEventListener("DOMContentLoaded", () => {
                const localeMap = {
                    IDR:'id-ID', USD:'en-US', EUR:'de-DE', GBP:'en-GB',
                    MYR:'ms-MY', SGD:'en-SG', CHF:'de-CH'
                };

                window.parseCurrencyString = function(str) {
                    if (!str) return 0;
                    let clean = String(str).replace(/[^\d.,-]/g, '');
                    if (clean.includes(',') && clean.includes('.')) {
                        if (clean.lastIndexOf(',') > clean.lastIndexOf('.')) {
                            clean = clean.replace(/\./g, '').replace(',', '.'); // 1.234,56 -> 1234.56
                        } else {
                            clean = clean.replace(/,/g, '');                    // 1,234.56 -> 1234.56
                        }
                    } else if (clean.includes(',')) {
                        const commaCount = (clean.match(/,/g) || []).length;
                        clean = (commaCount === 1 && clean.indexOf(',') > clean.length - 4)
                            ? clean.replace(',', '.')
                            : clean.replace(/,/g, '');
                    } else {
                        clean = clean.replace(/[^\d.-]/g, '');
                    }
                    const number = parseFloat(clean);
                    return isNaN(number) ? 0 : number;
                };

                // formatter tunggal (SGD => selalu tampil kode "SGD", selain itu simbol default)
                function formatCurrency(value, currencyCode) {
                    const locale = localeMap[currencyCode] || 'id-ID';
                    const num = typeof value === 'number' ? value : parseCurrencyString(value);

                    const optsBase = {
                        style: 'currency',
                        currency: currencyCode,
                        minimumFractionDigits: 2
                    };

                    // Khusus SGD -> pakai kode "SGD", bukan simbol "$"
                    const opts = (currencyCode === 'SGD')
                        ? { ...optsBase, currencyDisplay: 'code' }
                        : optsBase;

                    return new Intl.NumberFormat(locale, opts).format(num);
                }

                // curco diambil dari input, bukan select
                window.formatAllCurrency = function(forcedCur) {
                    const cur = forcedCur || ($('input[name="curco"]').val() || 'IDR');
                    document.querySelectorAll('.currency').forEach(el => {
                        const parsed = parseCurrencyString(el.value);
                        // Selalu format ulang, jangan di-skip meski sudah mengandung simbol
                        if (!isNaN(parsed)) el.value = formatCurrency(parsed, cur);
                    });
                };

                // Edit bebas angka saat fokus
                $(document).on('focus', '.currency', function () {
                    const num = parseCurrencyString(this.value);
                    this.value = num || '';
                });

                // Saat blur -> format sesuai curco (dengan aturan SGD)
                $(document).on('blur', '.currency', function () {
                    const currencyCode = $('input[name="curco"]').val() || 'IDR';
                    const parsed = parseCurrencyString(this.value);
                    if (this.value !== '' && !isNaN(parsed)) {
                        this.value = formatCurrency(parsed, currencyCode);
                    }
                });

                formatAllCurrency($('input[name="curco"]').val());

            });
        </script>

        {{-- select2 init --}}
        <script>
            $(document).on('shown.bs.collapse', '.accordion-collapse', function () {
                // Re-init Select2 setiap kali panel accordion terbuka
                $(this).find('select.select2').each(function() {
                    if (!$(this).data('select2')) {
                        $(this).select2({ theme: 'bootstrap-5', width: '100%' });
                    } else {
                        $(this).select2('destroy').select2({ theme: 'bootstrap-5', width: '100%' });
                    }
                });
            });
        </script>


        {{-- sweetalert konfirmasi & sukses --}}
        <script>
            document.addEventListener("DOMContentLoaded", function() {
                const form = document.getElementById('form-invoice');

                form.addEventListener('submit', function(e) {
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

                                    // hapus formating sebelum kirim
                                    function parseCurrencyString(str) {
                                        if (!str) return 0;
                                        let clean = String(str).replace(/[^\d.,-]/g, '');
                                        if (clean.includes(',') && clean.includes('.')) {
                                            if (clean.lastIndexOf(',') > clean.lastIndexOf('.'))
                                                clean = clean.replace(/\./g, '').replace(',', '.');
                                            else
                                                clean = clean.replace(/,/g, '');
                                        } else if (clean.includes(',')) {
                                            const commaCount = (clean.match(/,/g) || []).length;
                                            clean = (commaCount === 1 && clean.indexOf(',') > clean.length - 4)
                                                ? clean.replace(',', '.')
                                                : clean.replace(/,/g, '');
                                        } else {
                                            clean = clean.replace(/[^\d.-]/g, '');
                                        }
                                        const number = parseFloat(clean);
                                        return isNaN(number) ? 0 : number;
                                    }

                                    document.querySelectorAll('.currency').forEach(el => {
                                        const before = el.value;
                                        const parsed = parseCurrencyString(before);
                                        el.value = parsed.toString();
                                        console.log('💰 Currency cleaned:', el.name, '| Before:', before, '| After:', el.value);
                                    });

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
