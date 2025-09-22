@extends('layout.main')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/purchasing/tpo.css') }}">
@endpush

@section('container')
    <main id="main" class="main">
        <div class="pagetitle">
            <h1>Tambah Data PO</h1>
            <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('tpo.index') }}">List PO</a></li>
                <li class="breadcrumb-item active">PO Create</li>
            </ol>
            </nav>
        </div>

        <section class="section">
            <form action="{{ route('tpo.store') }}" method="POST">
                @csrf
                <div class="row">
                    <div class="col-md-6 mt-3">
                        <label for="potype" class="form-label">Tipe PO</label><span class="text-danger"> *</span>
                        <select class="select2 form-control" name="potype" id="potype" style="width: 100%;" required>
                            <option value="" {{ old('potype') == '' ? 'selected' : '' }} disabled selected>Silahkan pilih Tipe PO</option>
                            <option value="Lokal" {{ old('potype') == 'Lokal' ? 'selected' : '' }}>Lokal</option>
                            <option value="Import" {{ old('potype') == 'Import' ? 'selected' : '' }}>Import</option>
                            <option value="Inventaris" {{ old('potype') == 'Inventaris' ? 'selected' : '' }}>Inventaris</option>
                        </select>
                    </div>
                    
                    <input type="text" name="formc" id="formc" value="{{ old('formc') }}" hidden>

                    <div class="col-md-6 mt-3">
                        <label for="pono" class="form-label">Nomor PO</label><span class="text-danger"> *</span>
                        <input type="text" class="form-control" placeholder="Cth : BAxxx-2xxx" name="pono" id="pono" value="{{ old('pono') }}" required>
                        @error('pono')
                            <span class="text-danger">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>

                    <div class="col-md-6 mt-3">
                        <label for="podat" class="form-label">Tanggal PO</label><span class="text-danger"> *</span>
                        <input type="date" class="form-control"
                            name="podat" id="podat"
                            value="{{ old('podat') }}"
                            required min="{{ date('Y-m-d') }}">
                        @error('podat')
                            <small class="text-danger"><strong>{{ $message }}</strong></small>
                        @enderror
                    </div>

                    <div class="col-md-6 mt-3">
                        <label for="supno" class="form-label">Supplier <span class="text-danger">*</span></label>
                        <select class="select2 form-control" name="supno" id="supno" required>
                            <option value="" disabled {{ old('supno') ? '' : 'selected' }}>Silahkan pilih Supplier</option>
                            @foreach($vendors as $v)
                                <option
                                    value="{{ $v->supno }}"
                                    {{ old('supno') == $v->supno ? 'selected' : '' }}>
                                    {{ $v->supno }} - {{ $v->supna }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-6 mt-3">
                        <label for="topay" class="form-label">Term Of Payment</label><span class="text-danger"> *</span>
                        <input type="number" class="form-control" placeholder="Cth : 30" name="topay" id="topay" value="{{ old('topay') }}" required>
                    </div>

                    <div class="col-md-6 mt-3">
                        <label for="tdesc" class="form-label">Deskripsi Term Of Payment</label>
                        <input type="text" class="form-control" placeholder="Cth : Bulan Kredit" name="tdesc" id="tdesc" value="{{ old('tdesc') }}">
                    </div>

                    <div class="col-md-6 mt-3">
                        <label for="curco" class="form-label">Currency Code</label><span class="text-danger"> *</span>
                        <select class="select2 form-control" name="curco" id="curco" style="width: 100%;" required>
                            <option value="" {{ old('curco') == '' ? 'selected' : '' }} disabled selected>Silahkan pilih Currency Code</option>
                            <option value="CHF" {{ old('curco') == 'CHF' ? 'selected' : '' }}>CHF (Franc Swiss)</option>
                            <option value="EUR" {{ old('curco') == 'EUR' ? 'selected' : '' }}>EUR (Euro)</option>
                            <option value="GBP" {{ old('curco') == 'GBP' ? 'selected' : '' }}>GBP (Pound Sterling)</option>
                            <option value="IDR" {{ old('curco') == 'IDR' ? 'selected' : '' }}>IDR (Rupiah Indonesia)</option>
                            <option value="MYR" {{ old('curco') == 'MYR' ? 'selected' : '' }}>MYR (Ringgit Malaysia)</option>
                            <option value="SGD" {{ old('curco') == 'SGD' ? 'selected' : '' }}>SGD (Dollar Singapura)</option>
                            <option value="USD" {{ old('curco') == 'USD' ? 'selected' : '' }}>USD (Dollar Amerika Serikat)</option>
                        </select>
                    </div>

                    <div class="col-md-6 mt-3">
                        <label for="shvia" class="form-label">Pengiriman</label><span class="text-danger"> *</span>
                        <select class="select2 form-control" name="shvia" id="shvia" style="width: 100%;" required>
                            <option value="" {{ old('shvia') ? '' : 'selected' }} disabled selected>Silahkan pilih Pengiriman</option>
                            <option value="DARAT" {{ old('shvia') == 'DARAT' ? 'selected' : '' }}>DARAT</option>
                            <option value="LAUT" {{ old('shvia') == 'LAUT' ? 'selected' : '' }}>LAUT</option>
                            <option value="UDARA" {{ old('shvia') == 'UDARA' ? 'selected' : '' }}>UDARA</option>
                        </select>
                    </div>

                    <div class="col-md-6 mt-3">
                        <label for="delco" class="form-label">Kode Penerima</label>
                        <select class="select2 form-control" name="delco" id="delco" style="width: 100%;">
                            <option value="" {{ old('delco') ? '' : 'selected' }} disabled selected>Silahkan pilih Kode Penerima</option>
                            <option value="PST" {{ old('delco') == 'PST' ? 'selected' : '' }}>PST (Pusat)</option>
                            <option value="CKG" {{ old('delco') == 'CKG' ? 'selected' : '' }}>CKG (Cakung)</option>
                            <option value="D3" {{ old('delco') == 'D3' ? 'selected' : '' }}>D3 (Duren 3)</option>
                        </select>
                    </div>

                    <div class="col-md-6 mt-3">
                        <label for="delnm" class="form-label">Nama Penerima</label>
                        <input type="text" class="form-control" placeholder="Cth : PT MUGI" name="delnm" id="delnm" value="{{ old('delnm') }}">
                    </div>

                    <div class="col-md-6 mt-3">
                        <label for="dconp" class="form-label">Kontak Penerima</label>
                        <input type="text" class="form-control" placeholder="Cth : PT MUGI" name="dconp" id="dconp" value="{{ old('dconp') }}">
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-3 mt-3">
                        <label for="diper" class="form-label">Diskon (%)</label>
                        <input type="text" class="form-control" placeholder="Cth : 1.25" name="diper" id="diper" value="{{ old('diper', 0) }}" oninput="this.value = this.value.replace(/[^0-9.]/g, '')">
                    </div>
                    <div class="col-md-3 mt-3">
                        <label for="vatax" class="form-label">Tax Rate (%)</label>
                        <input type="text" class="form-control" placeholder="Cth : 5.5" name="vatax" id="vatax" value="{{ old('vatax', 0) }}" oninput="this.value = this.value.replace(/[^0-9.]/g, '')">
                    </div>

                    <div class="col-md-3 mt-3">
                        <label for="pph" class="form-label">PPH (%)</label>
                        <input type="text" class="form-control" placeholder="Cth : 10" name="pph" id="pph" value="{{ old('pph', 0) }}" oninput="this.value = this.value.replace(/[^0-9.]/g, '')">
                    </div>

                    <div class="col-md-3 mt-3">
                        <label for="stamp" class="form-label">Meterai</label>
                        <input type="number" class="form-control" placeholder="Cth : 10000" name="stamp" id="stamp" value="{{ old('stamp', 0) }}" >
                    </div>
                    <div class="col-md-12 mt-3">
                        <label for="noteh" class="form-label">Catatan</label>
                        <textarea 
                            type="text" class="form-control" placeholder="Cth : note" name="noteh" id="noteh" maxlength="200"
                        >{{ old('noteh') }}</textarea>
                        <div class="form-text text-danger text-end" style="font-size: 0.7rem;">
                            Maksimal 200 karakter
                        </div>
                    </div>
                </div>


                <hr class="my-4">

                <div class="row">
                    <h3 class="my-2">Detail Barang PO</h3>
                    
                    <div id="barang_po">
                        <div class="accordion" id="accordionPoBarang">
                            @foreach(old('opron', [null]) as $i => $oldOpron)
                                @include('purchasing.tpo.partial.tpo_create_detail', ['i' => $i, 'oldOpron' => $oldOpron, 'products' => $products])
                            @endforeach
                        </div>
                    </div>
                </div>
 
                <div class="text-end">
                    <button type="button" class="btn mt-3" style="background-color: #4456f1; color: #fff" onclick="addBarang()">Tambah Barang</button>
                </div>

                <div class="mt-3 d-flex justify-content-between">
                    <a href="{{ route('tpo.index') }}" class="btn btn-secondary">Kembali</a>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
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

        {{-- Nama Accordion ambil nama produk --}}
        <script>
            function updateBarangLabel(index) {
            const select = document.getElementById(`opron-${index}`);
            const selectedOption = select.options[select.selectedIndex];
            const opron = selectedOption ? selectedOption.value : "";
            const prona = selectedOption ? selectedOption.getAttribute("data-prona") : "";
            const labelSpan = document.getElementById(`barang-label-${index}`);
            if (labelSpan) {
                labelSpan.textContent = `(${opron} - ${prona ? `${prona})` : ""}`;
            }
        }
        </script>

        <script>
            // Mulai dari jumlah data lama (kalau ada old input dari validasi gagal)
            let barangIndex = {{ count(old('opron', [null])) }};

            // Function Tambah Barang
            function addBarang() {
                const accordion = document.getElementById('accordionPoBarang');

                const newItem = document.createElement('div');
                newItem.classList.add('accordion-item');
                newItem.id = `accordion-item-${barangIndex}`;

                newItem.innerHTML = `
                    <h2 class="accordion-header d-flex justify-content-between align-items-center" id="heading-${barangIndex}">
                        <button class="accordion-button collapsed" type="button"
                                data-bs-toggle="collapse" data-bs-target="#barang-${barangIndex}"
                                aria-expanded="false" aria-controls="barang-${barangIndex}">
                            Barang PO&nbsp<span id="barang-label-${barangIndex}">
                                {{ optional($products->firstWhere('opron', $oldOpron))->opron }}  {{ optional($products->firstWhere('opron', $oldOpron))->prona }}
                            </span>
                        </button>
                        <button type="button" class="btn btn-sm btn-danger mx-2"  onclick="removeBarang(${barangIndex})">
                            <i class="bi bi-trash-fill"></i>
                        </button>
                    </h2>
                    <div id="barang-${barangIndex}" class="accordion-collapse collapse"
                        aria-labelledby="heading-${barangIndex}" data-bs-parent="#accordionPoBarang">
                        <div class="accordion-body">
                            <div class="row">
                                <div class="col-md-6 mt-3">
                                    <label for="opron-${barangIndex}" class="form-label">Barang PO <span class="text-danger">*</span></label>
                                    <select class="select2 form-control" name="opron[]" id="opron-${barangIndex}" onchange="updateBarangLabel(${barangIndex})" required>
                                        <option value="" disabled {{ !$oldOpron ? 'selected' : '' }}>Silahkan pilih Barang</option>
                                        @foreach($products as $p)
                                            <option value="{{ $p->opron }}" 
                                                data-prona="{{ $p->prona }}" 
                                                {{ $oldOpron == $p->opron ? 'selected' : '' }}>
                                                {{ $p->opron }} - {{ $p->prona }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-md-6 mt-3">
                                    <label for="price-${barangIndex}" class="form-label">Harga Barang <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="price[]" id="price-${barangIndex}"
                                        placeholder="Cth : 1000000" required>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-4 mt-3">
                                    <label for="poqty-${barangIndex}" class="form-label">Qty <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control" name="poqty[]" id="poqty-${barangIndex}"
                                        placeholder="Cth : 10" required>
                                </div>

                                <div class="col-md-4 mt-3">
                                    <label for="weigh-${barangIndex}" class="form-label">Berat Barang (Kg)</label>
                                    <input type="text" class="form-control" name="weigh[]" id="weigh-${barangIndex}"
                                        placeholder="Cth : 10.5" oninput="this.value = this.value.replace(/[^0-9.]/g, '')">
                                </div>

                                <div class="col-md-4 mt-3">
                                    <label for="odisp-${barangIndex}" class="form-label">Diskon (%)</label>
                                    <input type="text" class="form-control" name="odisp[]" id="odisp-${barangIndex}"
                                        placeholder="Cth : 5.5" value="0" oninput="this.value = this.value.replace(/[^0-9.]/g, '')">
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mt-3">
                                    <label for="edeld-${barangIndex}" class="form-label">Ekspetasi Tanggal Pengiriman</label>
                                    <input type="date" class="form-control" name="edeld[]" id="edeld-${barangIndex}" min="{{ date('Y-m-d') }}" required>
                                </div>

                                <div class="col-md-6 mt-3">
                                    <label for="earrd-${barangIndex}" class="form-label">Ekspetasi Tanggal Kedatangan</label>
                                    <input type="date" class="form-control" name="earrd[]" id="earrd-${barangIndex}" min="{{ date('Y-m-d') }}" required>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-3 mt-3">
                                    <label for="hsn-${barangIndex}" class="form-label">HS Code</label>
                                    <input type="number" class="form-control" name="hsn[]" id="hsn-${barangIndex}" placeholder="Cth : 123">
                                </div>

                                <div class="col-md-3 mt-3">
                                    <label for="bm-${barangIndex}" class="form-label">BM (%)</label>
                                    <input type="text" class="form-control" name="bm[]" id="bm-${barangIndex}" placeholder="Cth : 1.5" value="0"
                                        oninput="this.value = this.value.replace(/[^0-9.]/g, '')">
                                </div>

                                <div class="col-md-3 mt-3">
                                    <label for="bmt-${barangIndex}" class="form-label">BMT (%)</label>
                                    <input type="text" class="form-control" name="bmt[]" id="bmt-${barangIndex}" placeholder="Cth : 0.5" value="0"
                                        oninput="this.value = this.value.replace(/[^0-9.]/g, '')">
                                </div>

                                <div class="col-md-3 mt-3">
                                    <label for="pphd-${barangIndex}" class="form-label">PPH (%)</label>
                                    <input type="text" class="form-control" name="pphd[]" id="pphd-${barangIndex}" placeholder="Cth : 11" value="0"
                                        oninput="this.value = this.value.replace(/[^0-9.]/g, '')">
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12 mt-3">
                                    <label for="noted-${barangIndex}" class="form-label">Catatan</label>
                                    <textarea class="form-control" name="noted[]" id="noted-${barangIndex}" maxlength="200" placeholder="Cth : note"></textarea>
                                    <div class="form-text text-danger text-end" style="font-size: 0.7rem;">
                                        Maksimal 200 karakter
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                `;

                accordion.appendChild(newItem);

                // re-init select2 biar dropdown tetap jalan
                $(`#opron-${barangIndex}`).select2({ 
                    theme: 'bootstrap-5',
                    width: '100%' 
                });

                barangIndex++;
            }

            // Hapus Barang
            function removeBarang(index) {
                const item = document.getElementById(`accordion-item-${index}`);
                if (item) {
                    item.remove();
                }
            }
        </script>
    @endpush

@endsection
