@extends('layout.main')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/purchasing/tpo.css') }}">
@endpush

@section('container')
    <main id="main" class="main">
        <div class="pagetitle">
            <h1>Edit Data PO "{{ $tpohdr->pono }}"</h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('tpo.index') }}">List PO</a></li>
                    <li class="breadcrumb-item active">Edit PO</li>
                </ol>
            </nav>
        </div>

        <section class="section">
            <form id="form-edit-po" action="{{ route('tpo.update', $tpohdr->pono) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="row">
                    <div class="col-md-6 mt-3">
                        <label class="form-label">Tipe PO</label><span class="text-danger"> *</span>
                        <select id="potype" class="select2 form-control" name="potype" required>
                            <option value="Lokal" {{ old('potype',$tpohdr->potype)=='Lokal'?'selected':'' }}>Lokal</option>
                            <option value="Import" {{ old('potype',$tpohdr->potype)=='Import'?'selected':'' }}>Import</option>
                            <option value="Inventaris" {{ old('potype',$tpohdr->potype)=='Inventaris'?'selected':'' }}>Inventaris</option>
                        </select>
                    </div>

                    <input type="text" name="formc" id="formc" value="{{ old('formc', $tpohdr->formc) }}" hidden>

                    <div class="col-md-6 mt-3">
                        <label class="form-label">Nomor PO</label><span class="text-danger"> *</span>
                        <input type="text" class="form-control" name="pono" value="{{ old('pono',$tpohdr->pono) }}" readonly>
                    </div>

                    <div class="col-md-6 mt-3">
                        <label class="form-label">Tanggal PO</label><span class="text-danger"> *</span>
                        <input type="date" class="form-control" name="podat" value="{{ old('podat',$tpohdr->podat) }}" required>
                    </div>

                    <div class="col-md-6 mt-3">
                        <label class="form-label">Supplier</label><span class="text-danger"> *</span>
                        <select class="select2 form-control" name="supno" required>
                            <option value="" disabled>Pilih Supplier</option>
                            @foreach($vendors as $v)
                                <option value="{{ $v->supno }}" {{ old('supno',$tpohdr->supno)==$v->supno?'selected':'' }}>
                                    {{ $v->supno }} - {{ $v->supna }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-6 mt-3">
                        <label class="form-label">Term Of Payment</label><span class="text-danger"> *</span>
                        <input type="number" class="form-control" name="topay" value="{{ old('topay',$tpohdr->topay) }}" required>
                    </div>

                    <div class="col-md-6 mt-3">
                        <label class="form-label">Deskripsi Term Of Payment</label><span class="text-danger"> *</span>
                        <input type="text" class="form-control" name="tdesc" value="{{ old('tdesc',$tpohdr->tdesc) }}">
                    </div>

                    <div class="col-md-6 mt-3">
                        <label class="form-label">Currency Code</label><span class="text-danger"> *</span>
                        <select class="select2 form-control" name="curco" required>
                            <option value="IDR" {{ old('curco',$tpohdr->curco)=='IDR'?'selected':'' }}>IDR</option>
                            <option value="USD" {{ old('curco',$tpohdr->curco)=='USD'?'selected':'' }}>USD</option>
                            <option value="EUR" {{ old('curco',$tpohdr->curco)=='EUR'?'selected':'' }}>EUR</option>
                            <option value="GBP" {{ old('curco',$tpohdr->curco)=='GBP'?'selected':'' }}>GBP</option>
                        </select>
                    </div>

                    <div class="col-md-6 mt-3">
                        <label class="form-label">Pengiriman</label><span class="text-danger"> *</span>
                        <select class="select2 form-control" name="shvia" required>
                            <option value="DARAT" {{ old('shvia',$tpohdr->shvia)=='DARAT'?'selected':'' }}>DARAT</option>
                            <option value="LAUT" {{ old('shvia',$tpohdr->shvia)=='LAUT'?'selected':'' }}>LAUT</option>
                            <option value="UDARA" {{ old('shvia',$tpohdr->shvia)=='UDARA'?'selected':'' }}>UDARA</option>
                        </select>
                    </div>

                    <div class="col-md-6 mt-3">
                        <label for="branch" class="form-label">Branch</label>
                        <input style="background-color: #e9ecef" type="text" class="form-control"  name="branch" id="branch" value="PST" readonly>
                    </div>

                    <div class="col-md-6 mt-3">
                        <label for="delco" class="form-label">Kode Penerima</label>
                        <select class="select2 form-control" name="delco" id="delco" style="width: 100%;">
                            <option value="" {{ old('delco', $tpohdr->delco) ? '' : 'selected' }} disabled selected>Silahkan pilih Kode Penerima</option>
                            <option value="PST" {{ old('delco', $tpohdr->delco) == 'PST' ? 'selected' : '' }}>PST (Pusat)</option>
                            <option value="CKG" {{ old('delco', $tpohdr->delco) == 'CKG' ? 'selected' : '' }}>CKG (Cakung)</option>
                            <option value="D3" {{ old('delco', $tpohdr->delco) == 'D3' ? 'selected' : '' }}>D3 (Duren 3)</option>
                        </select>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4 mt-3">
                        <label class="form-label">Diskon (%)</label>
                        <input type="text" class="form-control" name="diper" value="{{ old('diper',$tpohdr->diper) }}" oninput="this.value = this.value.replace(/[^0-9.]/g, '')">
                    </div>

                    <div class="col-md-4 mt-3">
                        <label class="form-label">Tax Rate (%)</label>
                        <input type="text" class="form-control" name="vatax" value="{{ old('vatax',$tpohdr->vatax) }}" oninput="this.value = this.value.replace(/[^0-9.]/g, '')">
                    </div>

                    <div class="col-md-4 mt-3">
                        <label class="form-label">Meterai</label>
                        <input type="number" class="form-control" name="stamp" value="{{ old('stamp',$tpohdr->stamp) }}">
                    </div>

                    <div class="col-md-12 mt-3">
                        <label class="form-label">Catatan</label>
                        <textarea class="form-control" name="noteh" maxlength="200">{{ old('noteh',$tpohdr->noteh) }}</textarea>
                    </div>
                </div>

                <hr class="my-4">

                
                <div class="row">
                    <h3 class="my-2">Detail Barang PO</h3>
                    
                    <div id="barang_po">
                        <div class="accordion" id="accordionPoBarang">
                            @foreach($tpohdr->tpodtl as $i => $d)
                            <div class="accordion-item" id="accordion-item-{{ $i }}">
                                <input type="hidden" name="idpo[]" value="{{ $d->idpo }}">
                                <h2 class="accordion-header d-flex justify-content-between align-items-center" id="heading-{{ $i }}">
                                    <button class="accordion-button {{ $i>0?'collapsed':'' }}" type="button"
                                            data-bs-toggle="collapse" data-bs-target="#barang-{{ $i }}"
                                            aria-expanded="{{ $i==0?'true':'false' }}" aria-controls="barang-{{ $i }}">
                                        <span id="barang-label-{{ $i }}">
                                            ({{ $d->opron }} - {{ $d->prona }})
                                        </span>
                                    </button>
                                    @if($i > 0)
                                    <button type="button" class="btn btn-sm btn-danger mx-2" onclick="removeBarang({{ $i }})">
                                        <i class="bi bi-trash-fill"></i>
                                    </button>
                                    @endif
                                </h2>

                                <div id="barang-{{ $i }}" class="accordion-collapse collapse {{ $i==0?'show':'' }}">
                                    <div class="accordion-body">
                                        <div class="row">
                                            <div class="col-md-6 mt-3">
                                                <label class="form-label">Barang</label><span class="text-danger"> *</span>
                                                <select class="select2 form-control" name="opron[]" id="opron-{{ $i }}" onchange="updateBarangLabel({{ $i }})" required>
                                                    @foreach($products as $p)
                                                    <option value="{{ $p->opron }}" data-prona="{{ $p->prona }}" data-stdqu="{{ $p->stdqu }}" {{ $d->opron==$p->opron?'selected':'' }}>
                                                        {{ $p->opron }} - {{ $p->prona }}
                                                    </option>
                                                    @endforeach
                                                </select>
                                                <input type="text" name="stdqu[]" id="stdqu-{{ $i }}" value="{{ $d->stdqu }}" hidden>
                                            </div>
                                            <div class="col-md-6 mt-3">
                                                <label class="form-label">Harga</label><span class="text-danger"> *</span>
                                                <input type="text" class="form-control" name="price[]" value="{{ $d->price }}" oninput="this.value = this.value.replace(/[^0-9.]/g, '')">
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-4 mt-3">
                                                <label class="form-label">Qty</label><span class="text-danger"> *</span>
                                                <div class="input-group">
                                                    <input type="number" class="form-control" name="poqty[]" id="poqty-{{ $i }}"
                                                        value="{{ $d->poqty }}" required>
                                                    <span class="input-group-text" id="qty-label-{{ $i }}">
                                                        {{ optional($products->firstWhere('opron', $d->opron))->stdqu ?? '' }}
                                                    </span>
                                                </div>
                                            </div>
                                            <div class="col-md-4 mt-3">
                                                <label class="form-label">Berat (Kg)</label>
                                                <input type="text" class="form-control" name="weigh[]" value="{{ $d->berat }}" oninput="this.value = this.value.replace(/[^0-9.]/g, '')">
                                            </div>
                                            <div class="col-md-4 mt-3">
                                                <label class="form-label">Diskon (%)</label>
                                                <input type="text" class="form-control" name="odisp[]" value="{{ $d->odisp }}" oninput="this.value = this.value.replace(/[^0-9.]/g, '')">
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-6 mt-3">
                                                <label class="form-label">Tanggal Pengiriman <span class="text-danger">*</span></label>
                                                <input type="date" class="form-control" name="edeld[]" value="{{ $d->edeld }}">
                                            </div>
                                            <div class="col-md-6 mt-3">
                                                <label class="form-label">Tanggal Kedatangan <span class="text-danger">*</span></label>
                                                <input type="date" class="form-control" name="earrd[]" value="{{ $d->earrd }}">
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-3 mt-3">
                                                <label class="form-label">HSN</label>
                                                <input type="number" class="form-control" name="hsn[]" value="{{ $d->hsn }}">
                                            </div>
                                            <div class="col-md-3 mt-3">
                                                <label class="form-label">BM</label>
                                                <input type="text" class="form-control" name="bm[]" value="{{ $d->bm }}" oninput="this.value = this.value.replace(/[^0-9.]/g, '')">
                                            </div>
                                            <div class="col-md-3 mt-3">
                                                <label class="form-label">BMT</label>
                                                <input type="text" class="form-control" name="bmt[]" value="{{ $d->bmt }}" oninput="this.value = this.value.replace(/[^0-9.]/g, '')">
                                            </div>
                                            <div class="col-md-3 mt-3">
                                                <label class="form-label">PPH</label>
                                                <input type="text" class="form-control" name="pphd[]" value="{{ $d->pphd }}" oninput="this.value = this.value.replace(/[^0-9.]/g, '')">
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-12 mt-3">
                                                <label class="form-label">Catatan</label>
                                                <textarea class="form-control" name="noted[]">{{ $d->noted }}</textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <div class="text-end">
                    <button type="button" class="btn mt-3" style="background-color:#4456f1;color:#fff" onclick="addBarang()">Tambah Barang</button>
                </div>

                <div class="mt-3 d-flex justify-content-between">
                    <a href="{{ route('tpo.index') }}" class="btn btn-secondary">Kembali</a>
                    <button type="submit" class="btn btn-primary">Perbaharui Data</button>
                </div>
            </form>

            {{-- modal konfirmasi edit data --}}
            <div class="modal fade" id="modalKonfirmasi" tabindex="-1">
                <div class="modal-dialog">
                    <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Konfirmasi Ubah Data</h5>
                    </div>
                    <div class="modal-body">
                        <p>Apakah anda yakin ingin mengubah data?</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="button" id="btnKonfirmasiEdit" class="btn btn-primary">Ya, Ubah Data</button>
                    </div>
                    </div>
                </div>
            </div>
        </section>
    </main>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const formc = document.getElementById('formc');
                const map = { Lokal: 'PO', Import: 'PI', Inventaris: 'PN' };

                $('#potype').on('change', function () {
                    formc.value = map[this.value] || '';
                });
            });
        </script>

        {{-- ambil nama produk pas awal load halaman --}}
        <script>
            document.addEventListener("DOMContentLoaded", () => {
                @foreach($tpohdr->tpodtl as $i => $d)
                    updateBarangLabel({{ $i }});
                @endforeach
            });
        </script>

        {{-- Nama Accordion ambil nama produk --}}
        <script>
            let barangIndex = {{ count($tpohdr->tpodtl) }};

            function addBarang() {
                const accordion = document.getElementById('accordionPoBarang');
                const newItem = document.createElement('div');
                newItem.classList.add('accordion-item');
                newItem.id = `accordion-item-${barangIndex}`;

                newItem.innerHTML = `
                    <input type="hidden" name="idpo[]" value="">
                    <h2 class="accordion-header d-flex justify-content-between align-items-center" id="heading-${barangIndex}">
                        <button class="accordion-button collapsed" type="button"
                                data-bs-toggle="collapse" data-bs-target="#barang-${barangIndex}"
                                aria-expanded="false" aria-controls="barang-${barangIndex}">
                            <span id="barang-label-${barangIndex}"></span>
                        </button>
                        <button type="button" class="btn btn-sm btn-danger mx-2" onclick="removeBarang(${barangIndex})">
                            <i class="bi bi-trash-fill"></i>
                        </button>
                    </h2>
                    <div id="barang-${barangIndex}" class="accordion-collapse collapse">
                        <div class="accordion-body">
                            <div class="row">
                                <div class="col-md-6 mt-3">
                                    <label class="form-label">Barang</label>
                                    <select class="select2 form-control" name="opron[]" id="opron-${barangIndex}" onchange="updateBarangLabel(${barangIndex})">
                                        <option value="" disabled selected>Pilih Barang</option>
                                        @foreach($products as $p)
                                        <option value="{{ $p->opron }}" data-prona="{{ $p->prona }}">{{ $p->opron }} - {{ $p->prona }}</option>
                                        @endforeach
                                    </select>
                                    <input type="text" name="stdqu[]" id="stdqu-${barangIndex}" hidden>
                                </div>
                                <div class="col-md-6 mt-3">
                                    <label class="form-label">Qty</label>
                                    <div class="input-group">
                                        <input type="number" class="form-control" name="poqty[]" id="poqty-${barangIndex}" placeholder="Cth : 10">
                                        <span class="input-group-text" id="qty-label-${barangIndex}"></span>
                                    </div>
                                </div>
                                <div class="col-md-6 mt-3">
                                    <label class="form-label">Harga</label>
                                    <input type="text" class="form-control" name="price[]" placeholder="Cth : 1000000">
                                </div>
                                <div class="col-md-6 mt-3">
                                    <label class="form-label">Berat (Kg)</label>
                                    <input type="number" class="form-control" name="weigh[]" placeholder="Cth : 10">
                                </div>
                                <div class="col-md-6 mt-3">
                                    <label class="form-label">Diskon (%)</label>
                                    <input type="number" class="form-control" name="odisp[]" value="0">
                                </div>
                                <div class="col-md-6 mt-3">
                                    <label class="form-label">Tanggal Pengiriman</label>
                                    <input type="date" class="form-control" name="edeld[]">
                                </div>
                                <div class="col-md-6 mt-3">
                                    <label class="form-label">Tanggal Kedatangan</label>
                                    <input type="date" class="form-control" name="earrd[]">
                                </div>
                                <div class="col-md-6 mt-3">
                                    <label class="form-label">HSN</label>
                                    <input type="number" class="form-control" name="hsn[]">
                                </div>
                                <div class="col-md-6 mt-3">
                                    <label class="form-label">BM</label>
                                    <input type="number" class="form-control" name="bm[]" value="0">
                                </div>
                                <div class="col-md-6 mt-3">
                                    <label class="form-label">BMT</label>
                                    <input type="number" class="form-control" name="bmt[]" value="0">
                                </div>
                                <div class="col-md-6 mt-3">
                                    <label class="form-label">PPH</label>
                                    <input type="number" class="form-control" name="pphd[]" value="0">
                                </div>
                                <div class="col-md-12 mt-3">
                                    <label class="form-label">Catatan</label>
                                    <textarea class="form-control" name="noted[]" maxlength="200"></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                `;

                accordion.appendChild(newItem);

                // aktifkan select2
                $(`#opron-${barangIndex}`).select2({ theme: 'bootstrap-5', width: '100%' });

                barangIndex++;
            }

            function removeBarang(index) {
                const item = document.getElementById(`accordion-item-${index}`);
                if (item) { item.remove(); }
            }

            function updateBarangLabel(index) {
                const select = document.getElementById(`opron-${index}`);
                const selectedOption = select.options[select.selectedIndex];
                const opron = selectedOption ? selectedOption.value : "";
                const prona = selectedOption ? selectedOption.getAttribute("data-prona") : "";
                const stdqu = selectedOption ? selectedOption.getAttribute("data-stdqu") : "";

                const labelSpan = document.getElementById(`barang-label-${index}`);

                if (labelSpan) {
                    labelSpan.textContent = opron
                        ? `(${opron}${prona ? ' - ' + prona : ''})`
                        : "";
                }

                const qtyLabel = document.getElementById(`qty-label-${index}`);
                if (qtyLabel) {
                    qtyLabel.textContent = stdqu || '';
                }

                const stdquInput = document.getElementById(`stdqu-${index}`);
                if (stdquInput) {
                    stdquInput.value = stdqu || '';
                }
            }
        </script>
        {{-- Modal Konfirmasi edit data --}}
        <script>
            const form = document.getElementById('form-edit-po');
            const btnKonfirmasi = document.getElementById('btnKonfirmasiEdit');

            // cegah submit default
            form.addEventListener('submit', function (e) {
            e.preventDefault();

            // jalankan validasi browser dulu
            if (form.checkValidity()) {
                // tampilkan modal konfirmasi
                const modal = new bootstrap.Modal(document.getElementById('modalKonfirmasi'));
                modal.show();
            } else {
                // trigger bootstrap validation styling
                form.classList.add('was-validated');
            }
            });

            // kalau user setuju simpan
            btnKonfirmasi.addEventListener('click', () => {
            form.submit(); // submit form beneran
            });
        </script>
    @endpush
@endsection
