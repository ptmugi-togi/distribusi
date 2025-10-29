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

                {{-- Header --}}
                <input type="text" name="braco" value="{{ auth()->user()->cabang }}" hidden>
                <div class="row">
                    <div class="col-md-6 mt-3">
                        <label for="formc" class="form-label">Formc</label><span class="text-danger"> *</span>
                        <select class="form-control select2" id="formc" name="formc" required>
                            <option value="" disabled {{ old('formc') ? '' : 'selected' }}>Silahkan Pilih Formc</option>
                            <option value="IA" {{ old('formc') == 'IA' ? 'selected' : '' }}>IA (BBM - LOCAL PURCHASE)</option>
                            <option value="IB" {{ old('formc') == 'IB' ? 'selected' : '' }}>IB (BBM - IMPORT)</option>
                            <option value="IC" {{ old('formc') == 'IC' ? 'selected' : '' }}>IC (BBM - EX CUSTOMER RETURN)</option>
                            <option value="ID" {{ old('formc') == 'ID' ? 'selected' : '' }}>ID (BBM - EX PINJAMAN)</option>
                            <option value="IE" {{ old('formc') == 'IE' ? 'selected' : '' }}>IE (BBM - EX RETURN TO SUPPLIER)</option>
                            <option value="IF" {{ old('formc') == 'IF' ? 'selected' : '' }}>IF (BBM - ADJUSTMENT)</option>
                            <option value="IG" {{ old('formc') == 'IG' ? 'selected' : '' }}>IG (BBM - FG TRANSFER FROM BRANCH)</option>
                            <option value="IH" {{ old('formc') == 'IH' ? 'selected' : '' }}>IH (BBM - RETURN FROM PRODUCTION)</option>
                            <option value="IJ" {{ old('formc') == 'IJ' ? 'selected' : '' }}>IJ (BBM - EX PRODUKSI)</option>
                            <option value="IK" {{ old('formc') == 'IK' ? 'selected' : '' }}>IK (BBM - EX MODIFIKASI)</option>
                            <option value="IL" {{ old('formc') == 'IL' ? 'selected' : '' }}>IL (BBM - EX OTHER BRANCH)</option>
                            <option value="IN" {{ old('formc') == 'IN' ? 'selected' : '' }}>IN (BBM - CANCELATION DO)</option>
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
                        <label for="trano" class="form-label">Stock Receipt No.</label><span class="text-danger"> *</span> {{-- NOMOR URUT 2 digit depan kode tahun sisanya nomor urut (ex: 250001) --}}
                        <input type="text" class="form-control" name="trano" id="trano" value="{{ old('trano', $trano ?? '') }}" required readonly style="background-color: #e9ecef">
                    </div>

                    <div class="col-md-6 mt-3">
                        <label for="tradt" class="form-label">Stock Receipt Date</label><span class="text-danger"> *</span>
                        <input type="date" class="form-control" name="tradt" id="tradt" value="{{ old('tradt') }}" required min="{{ date('Y-m-01') }}">
                    </div>

                    <div class="col-md-6 mt-3">
                        <label for="refcno" class="form-label">Receiving Instruction</label><span class="text-danger"> *</span>
                        <select class="form-control select2" id="refcno" name="refcno" required>
                            <option value="" disabled selected>Pilih Receiving Instruction</option>
                            @foreach ($tsupih as $t)
                                <option value="{{ $t->rinum }}" {{ old('rinum') == $t->rinum ? 'selected' : '' }}
                                    data-reffc = "{{ $t->formc }}"
                                    data-refno = "{{ $t->rinum }}"
                                    data-supno = "{{ $t->supno }}"
                                    data-supna = "{{ $t->supna }}"
                                    data-blnum = "{{ $t->blnum }}"
                                    data-vesel = "{{ $t->vesel }}">
                                   {{ $t->formc }} {{ $t->rinum }}
                                </option>
                            @endforeach
                        </select>
                        <input type="text" name="reffc" id="reffc" value="{{ old('reffc') }}" hidden>
                        <input type="text" name="refno" id="refno" value="{{ old('refno') }}" hidden>
                    </div>


                    <div class="col-md-6 mt-3">
                        <label for="supno" class="form-label">Supplier</label>
                        <input type="text" class="form-control" id="supplier" value="{{ old('supno') }}" readonly style="background-color:#e9ecef;">
                        <input type="text" class="form-control" name="supno" id="supno" value="{{ old('supno') }}" hidden>
                    </div>

                    <div class="col-md-6 mt-3">
                        <label for="blnum" class="form-label">BL No.</label>
                        <input type="text" class="form-control" name="blnum" id="blnum" value="{{ old('blnum') }}" readonly style="background-color: #e9ecef">
                    </div>

                    <div class="col-md-6 mt-3">
                        <label for="vesel" class="form-label">Vessel</label>
                        <input type="text" class="form-control" name="vesel" id="vesel" value="{{ old('vesel') }}" readonly style="background-color: #e9ecef">
                    </div>

                    <div class="col-md-12 mt-3">
                        <label for="noteh" class="form-label">Notes</label>
                        <textarea 
                            type="text" class="form-control" name="noteh" id="noteh" maxlength="200"
                        >{{ old('noteh') }}</textarea>
                        <div class="form-text text-danger text-end" style="font-size: 0.7rem;">
                            Maksimal 200 karakter
                        </div>
                        @error('noteh')
                            <span class="text-danger">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>

                <div class="row">
                    <h3 class="my-2">BBM Detail</h3>
                    <div class="accordion" id="accordionBbm">
                    @foreach (old('invno', [null]) as $i => $oldInvno)
                            <div class="accordion-item" id="accordion-item-{{ $i }}">
                            <h2 class="accordion-header d-flex justify-content-between align-items-center" id="heading-{{ $i }}">
                                <button class="accordion-button {{ $i > 0 ? 'collapsed' : '' }}" type="button"
                                        data-bs-toggle="collapse" data-bs-target="#details-{{ $i }}"
                                        aria-expanded="{{ $i == 0 ? 'true' : 'false' }}" aria-controls="details-{{ $i }}">
                                </button>
                                @if($i > 0)
                                    <button type="button" class="btn btn-sm btn-danger mx-2" onclick="removebbmDetail({{ $i }})">
                                        <i class="bi bi-trash-fill"></i>
                                    </button>
                                @endif
                            </h2>
                            <div id="details-{{ $i }}" class="accordion-collapse collapse {{ $i == 0 ? 'show' : '' }}"
                                aria-labelledby="heading-{{ $i }}" data-bs-parent="#accordionDetails">
                                <div class="accordion-body">
                                    <div class="row">
                                        <div class="col-md-6 mt-3">
                                            <label for="invno" class="form-label">Invoice No.</label><span class="text-danger"> *</span>
                                            <select class="select2 form-control" name="invno[]" id="invno-{{ $i }}" required>
                                                <option value="" disabled {{ old('invno.'. $i) ? '' : 'selected' }}>Silahkan Pilih Receiving Instruction terlebih dahulu</option>
                                            </select>
                                        </div>

                                        <div class="col-md-6 mt-3">
                                            <label for="opron" class="form-label">Barang</label><span class="text-danger"> *</span>
                                            <select class="select2 form-control" name="opron[]" id="opron-{{ $i }}" required>
                                                <option value="" disabled {{ old('opron.'. $i) ? '' : 'selected' }}>Silahkan Pilih Invoice No. terlebih dahulu</option>
                                            </select>
                                        </div>

                                        <div class="col-md-6 mt-3">
                                            <label for="inqty-{{ $i }}" class="form-label">Invoice Quantity</label>
                                            <div class="input-group">
                                                <input type="number" class="form-control" id="inqty-{{ $i }}" style="background-color: #e9ecef;" value="{{ old('inqty.'. $i) }}" readonly>
                                                <span class="input-group-text unit-label"></span>
                                                <input type="text" id="stdqt-{{ $i }}" class="stdqu-input" name="stdqt[]" value="{{ old('stdqt.'. $i) }}" hidden>
                                            </div>
                                        </div>

                                        <div class="col-md-6 mt-3">
                                            <label for="trqty-{{ $i }}" class="form-label">Receipt Quantity</label><span class="text-danger"> *</span>
                                            <div class="input-group">
                                                <input type="number" class="form-control" id="trqty-{{ $i }}" name="trqty[]" value="{{ old('trqty.'. $i, 1) }}" min="1"
                                                oninput="
                                                    this.value = this.value.replace(/[^0-9]/g, '');
                                                    const inqty = Number(document.getElementById('inqty-{{ $i }}')?.value || 0);
                                                    if (Number(this.value) > inqty) {
                                                        Swal.fire({
                                                            title: 'Peringatan',
                                                            text: 'Jumlah Receipt qty tidak boleh lebih banyak dari jumlah Invoice qty',
                                                            icon: 'error'
                                                        });
                                                        this.value = inqty;
                                                    }
                                                " required>
                                                <span class="input-group-text unit-label"></span>
                                            </div>
                                        </div>

                                        <div class="col-md-6 mt-3">
                                            <label for="lotno-{{ $i }}" class="form-label">Serial / Batch No.</label><span class="text-danger"> *</span>
                                            <input type="number" class="form-control" name="lotno[]" id="lotno-{{ $i }}" value="{{ old('lotno.'. $i) }}" oninput="this.value = this.value.replace(/[^0-9]/g, '');" required>
                                        </div>

                                        <div class="col-md-6 mt-3">
                                            <label for="lotnoend-{{ $i }}" class="form-label">Serial / Batch No. (Akhir)</label>
                                            <input type="number" class="form-control" name="lotnoend[]" id="lotnoend-{{ $i }}" readonly
                                                style="background-color: #e9ecef;" value="{{ old('lotnoend.'. $i) }}">
                                        </div>

                                        <div class="col-md-6 mt-3">
                                            <label for="pono-{{ $i }}" class="form-label">PO No.</label>
                                            <input type="text" class="form-control" name="pono[]" id="pono-{{ $i }}" value="{{ old('pono.'. $i) }}" readonly style="background-color: #e9ecef">
                                        </div>

                                        <div class="col-md-6 mt-3">
                                            <label for="locco" class="form-label">Warehouse Location</label><span class="text-danger"> *</span>
                                            <select class="form-control select2" name="locco[]" id="locco-{{ $i }}" required>
                                                <option value="" disabled selected>Pilih Warehouse terlebih dahulu</option>
                                            </select>
                                        </div>

                                        <div class="col-md-12 mt-3">
                                            <label for="noted" class="form-label">Notes</label>
                                            <textarea 
                                                type="text" class="form-control" name="noted[]" id="noted" maxlength="200"
                                            >{{ old('noted.'. $i) }}</textarea>
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

                <div class="text-end">
                    <button type="button" class="btn mt-3" style="background-color: #4456f1; color: #fff" onclick="addBbm()">Tambah Detail BBM</button>
                </div>

                <div class="mt-3 d-flex justify-content-between">
                    <a href="{{ route('bbm.index') }}" class="btn btn-secondary">Kembali</a>
                    <button type="submit" class="btn btn-primary">Simpan Data</button>
                </div>
            </form>
        </section>
    </main>

    @push('scripts')
        @include('logistic.bbm.partial_create.add_bbm')

        <script>
            let selectedWarehouse = null;
            let selectedReceivingInstruction = null;

            // simpan pilihan warehouse 
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
                const qty = selected.data('qty');
                const stdqt = selected.data('stdqt');
                const pono = selected.data('pono');

                $(`#inqty-${index}`).val(qty);
                $(`#inqty-${index}`).next('.input-group-text').text(stdqt);
                $(`#trqty-${index}`).next('.input-group-text').text(stdqt);
                $(`#stdqt-${index}`).val(stdqt);
                $(`#pono-${index}`).val(pono);
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

            // ubah judul accordion jadi invno
            $(document).on('change', 'select[name="invno[]"]', function () {
                const selectedOption = $(this).find(':selected');
                const invno = selectedOption.val() || '';
                const newLabel = invno ? `No Invoice: ${invno}` : '';

                const parentItem = $(this).closest('.accordion-item');
                const headerButton = parentItem.find('.accordion-button');

                // kalau belum ada teks, tambahkan
                if (headerButton.text().trim() === '') {
                    headerButton.append(` ${newLabel}`);
                } else {
                    // ganti teks yang lama dengan label baru
                    headerButton.contents().filter(function () {
                        return this.nodeType === 3;
                    }).first().replaceWith(` ${newLabel}`);
                }
            });
        </script>

        {{-- otomatis ambil lotno akhir --}}
        <script>
            $(document).on('input', 'input[name="lotno[]"], input[name="trqty[]"]', function () {
                const index = $(this).attr('id').split('-')[1];
                const lotStart = parseInt($(`#lotno-${index}`).val()) || 0;
                const trqty = parseInt($(`#trqty-${index}`).val()) || 0;

                if (lotStart > 0 && trqty > 0) {
                    const lotEnd = lotStart + trqty - 1;
                    $(`#lotnoend-${index}`).val(lotEnd);
                } else {
                    $(`#lotnoend-${index}`).val('');
                }
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