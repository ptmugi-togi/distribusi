<div class="accordion-item" id="accordion-item-{{ $i }}">
    <h2 class="accordion-header d-flex justify-content-between align-items-center" id="heading-{{ $i }}">
        <button class="accordion-button {{ $i > 0 ? 'collapsed' : '' }}" type="button"
                data-bs-toggle="collapse" data-bs-target="#barang-{{ $i }}"
                aria-expanded="{{ $i == 0 ? 'true' : 'false' }}" aria-controls="barang-{{ $i }}">
        </button>
    </h2>
    <div id="barang-{{ $i }}" class="accordion-collapse collapse {{ $i == 0 ? 'show' : '' }}"
        aria-labelledby="heading-{{ $i }}" data-bs-parent="#accordionPoBarang">
        <div class="accordion-body">
            <div class="row">
                <div class="col-md-6 mt-3">
                    <label for="pono" class="form-label">No. PO <span class="text-danger">*</span></label>
                    <select class="select2 form-control" name="pono[]" id="import-pono-{{ $i }}" required>
                        <option value="" disabled {{ old('pono.'. $i) ? '' : 'selected' }}>Silahkan pilih Supplier terlebih dahulu</option>
                    </select>
                </div>

                <div class="col-md-6 mt-3">
                    <label for="opron" class="form-label">Barang <span class="text-danger">*</span></label>
                    <select class="select2 form-control opron-select" name="opron[]" id="import-opron-{{ $i }}" required>
                        <option value="" disabled {{ old('opron.'. $i) ? '' : 'selected' }}>Silahkan pilih PO no. terlebih dahulu</option>
                    </select>
                </div>

                <div class="col-md-6 mt-3">
                    <label for="import-poqty-{{ $i }}" class="form-label">PO Quantity</label>
                    <div class="input-group">
                        <input type="number" class="form-control poqty" id="import-poqty-{{ $i }}" style="background-color: #e9ecef;" value="{{ old('poqty.'. $i) }}" readonly>
                        <span class="input-group-text unit-label"></span>
                        <input type="text" name="stdqt[]" class="stdqu-input" value="{{ old('stdqt.'. $i) }}" hidden>
                    </div>
                </div>

                <div class="col-md-6 mt-3">
                    <label for="inqty-{{ $i }}" class="form-label">Invoice Quantity</label>
                    <div class="input-group">
                        <input type="number" class="form-control" name="inqty[]" id="inqty-{{ $i }}" value="{{ old('inqty.'. $i) }}"
                        oninput="let poqty = document.getElementById('import-poqty-{{ $i }}').value; if (Number(this.value) > Number(poqty)) { Swal.fire({ title: 'Peringatan', text: 'Jumlah Invoice qty tidak boleh lebih besar dari jumlah PO qty', icon: 'error' }); this.value = poqty; }">
                        <span class="input-group-text unit-label"></span>
                    </div>
                </div>

                <div class="col-md-4 mt-3">
                    <label for="price" class="form-label">PO Price/unit</label>
                    <input type="text" class="form-control currency" name="price[]" id="price-{{ $i }}" value="{{ old('price.'. $i) }}" style="background-color: #e9ecef;" readonly>
                </div>

                <div class="col-md-4 mt-3">
                    <label for="inprc" class="form-label">Invoice Price/unit</label>
                    <input type="text" class="form-control currency" name="inprc[]" id="inprc-{{ $i }}" value="{{ old('inprc.'. $i) }}">
                </div>

                <div class="col-md-4 mt-3">
                    <label for="inamt" class="form-label">Invoice Amount</label>
                    <input type="text" class="form-control currency" name="inamt[]" id="inamt-{{ $i }}" value="{{ old('inamt.'. $i) }}" style="background-color: #e9ecef;" readonly>
                </div>

                <div class="col-md-6 mt-3">
                    <label for="ewprc" class="form-label">Ex-work Price</label>
                    <input type="text" class="form-control currency" name="ewprc[]" id="ewprc-{{ $i }}" value="{{ old('ewprc.'. $i) }}">
                </div>

                <div class="col-md-6 mt-3">
                    <label for="fobch" class="form-label">FOB Charges</label>
                    <input type="text" class="form-control currency" name="fobch[]" id="fobch-{{ $i }}" value="{{ old('fobch.'. $i) }}">
                </div>

                <div class="col-md-6 mt-3">
                    <label for="incst" class="form-label">Insurance</label>
                    <input type="text" class="form-control currency" name="incst[]" id="incst-{{ $i }}" value="{{ old('incst.'. $i) }}">
                </div>

                <div class="col-md-6 mt-3">
                    <label for="hsn" class="form-label">HS no.</label>
                    <select class="select2 form-control hsn-select" name="hsn[]" id="hsn-{{ $i }}">
                        <option value="" disabled {{ old('hsn.'. $i) ? '' : 'selected' }}>Pilih HS no.</option>
                        @foreach ($hsnList as $h )
                            <option value="{{ $h->hsn }}" data-bm="{{ $h->bm }}"
                                {{ old('hsn.' . $i) == $h->hsn ? 'selected' : '' }}>
                                {{ $h->hsn }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-6 mt-3">
                    <label for="bm" class="form-label">BM (%)</label>
                    <input type="number" class="form-control" name="bm[]" id="bm-{{ $i }}" value="{{ old('bm.'. $i) }}" oninput="this.value = this.value.replace(/[^0-9.]/g, '')">
                </div>

                <div class="col-md-2 mt-3">
                    <label for="ppn" class="form-label">PPn (%)</label>
                    <input type="number" class="form-control" name="ppn[]" id="ppn-{{ $i }}" value="{{ old('ppn.'. $i) }}" oninput="this.value = this.value.replace(/[^0-9.]/g, '')">
                </div>

                <div class="col-md-2 mt-3">
                    <label for="ppnbm" class="form-label">PPnBM (%)</label>
                    <input type="number" class="form-control" name="ppnbm[]" id="ppnbm-{{ $i }}" value="{{ old('ppnbm.'. $i) }}" oninput="this.value = this.value.replace(/[^0-9.]/g, '')">
                </div>

                <div class="col-md-2 mt-3">
                    <label for="pph" class="form-label">PPh (%)</label>
                    <input type="text" class="form-control" name="pph[]" id="pph-{{ $i }}" value="{{ old('pph.'. $i) }}" oninput="this.value = this.value.replace(/[^0-9,.]/g, '')" onblur="this.value = this.value.replace(',', '.')">
                </div>
            </div>
        </div>
    </div>
</div>