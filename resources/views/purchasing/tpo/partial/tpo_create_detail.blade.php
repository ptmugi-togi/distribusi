<div class="accordion-item" id="accordion-item-{{ $i }}">
    <h2 class="accordion-header d-flex justify-content-between align-items-center" id="heading-{{ $i }}">
        <button class="accordion-button {{ $i > 0 ? 'collapsed' : '' }}" type="button"
                data-bs-toggle="collapse" data-bs-target="#barang-{{ $i }}"
                aria-expanded="{{ $i == 0 ? 'true' : 'false' }}" aria-controls="barang-{{ $i }}">
            Barang PO
        </button>
        @if($i > 0)
            <button type="button" class="btn btn-sm btn-danger mx-2" onclick="removeBarang({{ $i }})">
                <i class="bi bi-trash-fill"></i>
            </button>
        @endif
    </h2>
    <div id="barang-{{ $i }}" class="accordion-collapse collapse {{ $i == 0 ? 'show' : '' }}"
        aria-labelledby="heading-{{ $i }}" data-bs-parent="#accordionPoBarang">
        <div class="accordion-body">
            <div class="row">
                <div class="col-md-6 mt-3">
                    <label for="opron-{{ $i }}" class="form-label">Barang PO <span class="text-danger">*</span></label>
                    <select class="select2 form-control" name="opron[]" id="opron-{{ $i }}" required>
                        <option value="" disabled {{ !$oldOpron ? 'selected' : '' }}>Silahkan pilih Barang</option>
                        @foreach($products as $p)
                            <option value="{{ $p->opron }}" {{ $oldOpron == $p->opron ? 'selected' : '' }}>
                                {{ $p->opron }} - {{ $p->prona }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-6 mt-3">
                    <label for="poqty-{{ $i }}" class="form-label">Qty <span class="text-danger">*</span></label>
                    <input type="number" class="form-control" name="poqty[]" id="poqty-{{ $i }}" 
                           placeholder="Cth : 10" value="{{ old('poqty.'.$i) }}" required>
                </div>

                <div class="col-md-6 mt-3">
                    <label for="price-{{ $i }}" class="form-label">Harga Barang <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" name="price[]" id="price-{{ $i }}" 
                           placeholder="Cth : 1000000" value="{{ old('price.'.$i) }}" required>
                </div>

                <div class="col-md-6 mt-3">
                    <label for="weigh-{{ $i }}" class="form-label">Berat Barang (Kg)</label>
                    <input type="number" class="form-control" name="weigh[]" id="weigh-{{ $i }}"
                           placeholder="Cth : 10" value="{{ old('weigh.'.$i) }}" required>
                </div>
                <div class="col-md-6 mt-3">
                    <label for="odisp-{{ $i }}" class="form-label">Diskon (%)</label>
                    <input type="number" class="form-control" name="odisp[]" id="odisp-{{ $i }}" 
                           placeholder="Cth : 5" value="{{ old('odisp.'.$i, 0) }}" >
                </div>

                <div class="col-md-6 mt-3">
                    <label for="edeld-{{ $i }}" class="form-label">Ekspetasi Tanggal Pengiriman</label>
                    <input type="date" class="form-control" name="edeld[]" id="edeld-{{ $i }}" 
                           value="{{ old('edeld.'.$i) }}" min="{{ date('Y-m-d') }}" required>
                </div>

                <div class="col-md-6 mt-3">
                    <label for="earrd-{{ $i }}" class="form-label">Ekspetasi Tanggal Kedatangan</label>
                    <input type="date" class="form-control" name="earrd[]" id="earrd-{{ $i }}" 
                           value="{{ old('earrd.'.$i) }}" min="{{ date('Y-m-d') }}" required>
                </div>

                <div class="col-md-6 mt-3">
                    <label for="hsn-{{ $i }}" class="form-label">HS Code</label>
                    <input type="number" class="form-control" placeholder="Cth : 123" name="hsn[]" id="hsn-{{ $i }}"
                           value="{{ old('hsn.'.$i) }}">
                </div>

                <div class="col-md-6 mt-3">
                    <label for="bm-{{ $i }}" class="form-label">BM (%)</label>
                    <input type="number" class="form-control" placeholder="Cth : 10" name="bm[]" id="bm-{{ $i }}"
                           value="{{ old('bm.'.$i, 0) }}">
                </div>

                <div class="col-md-6 mt-3">
                    <label for="bmt-{{ $i }}" class="form-label">BMT (%)</label>
                    <input type="number" class="form-control" placeholder="Cth : 10" name="bmt[]" id="bmt-{{ $i }}"
                           value="{{ old('bmt.'.$i, 0) }}">
                </div>

                <div class="col-md-6 mt-3">
                    <label for="pphd-{{ $i }}" class="form-label">PPH (%)</label>
                    <input type="number" class="form-control" placeholder="Cth : 5" name="pphd[]" id="pphd-{{ $i }}"
                           value="{{ old('pphd.'.$i, 0) }}">
                </div>

                <div class="col-md-12 mt-3">
                    <label for="noted-{{ $i }}" class="form-label">Catatan</label>
                    <textarea class="form-control" placeholder="Cth : note" name="noted[]" id="noted-{{ $i }}" maxlength="200">{{ old('noted.'.$i) }}</textarea>
                    <div class="form-text text-danger text-end" style="font-size: 0.7rem;">
                        Maksimal 200 karakter
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
