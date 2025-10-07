<div class="row">
    <h3 class="my-2">Invoice Detail</h3>
    <div class="accordion" id="accordionInvoiceImport">
            @php
                $barangIndex = 0;
            @endphp
        <div class="accordion-item" id="accordion-item-{{ $barangIndex }}">
            <h2 class="accordion-header d-flex justify-content-between align-items-center" id="heading-{{ $barangIndex }}">
                <button class="accordion-button {{ $barangIndex > 0 ? 'collapsed' : '' }}" type="button"
                        data-bs-toggle="collapse" data-bs-target="#barang-{{ $barangIndex }}"
                        aria-expanded="{{ $barangIndex == 0 ? 'true' : 'false' }}" aria-controls="barang-{{ $barangIndex }}">
                </button>
            </h2>
            <div id="barang-{{ $barangIndex }}" class="accordion-collapse collapse {{ $barangIndex == 0 ? 'show' : '' }}"
                aria-labelledby="heading-{{ $barangIndex }}" data-bs-parent="#accordionPoBarang">
                <div class="accordion-body">
                    <div class="row">
                        <div class="col-md-6 mt-3">
                            <label for="pono" class="form-label">No. PO <span class="text-danger">*</span></label>
                            <select class="select2 form-control" name="pono[]" id="import-pono-{{ $barangIndex }}" required>
                                <option value="" disabled {{ old('pono') ? '' : 'selected' }}>Silahkan pilih No. PO</option>
                            </select>
                        </div>

                        <div class="col-md-6 mt-3">
                            <label for="opron" class="form-label">Barang <span class="text-danger">*</span></label>
                            <select class="select2 form-control opron-select" name="opron[]" id="import-opron-{{ $barangIndex }}" required>
                                <option value="" disabled {{ old('opron') ? '' : 'selected' }}>Silahkan pilih Barang</option>
                            </select>
                        </div>

                        <div class="col-md-6 mt-3">
                            <label for="poqty-{{ $barangIndex }}" class="form-label">PO Quantity</label>
                            <div class="input-group">
                                <input type="text" class="form-control" name="poqty[]" id="poqty-{{ $barangIndex }}" disabled>
                                <span class="input-group-text unit-label"></span>
                                <input type="text" name="stdqu[]" class="stdqu-input" value="{{ old('stdqu') }}" hidden>
                            </div>
                        </div>

                        <div class="col-md-6 mt-3">
                            <label for="inqty-{{ $barangIndex }}" class="form-label">Invoice Quantity</label>
                            <div class="input-group">
                                <input type="text" class="form-control" name="inqty[]" id="inqty-{{ $barangIndex }}" value="{{ old('inqty') }}">
                                <span class="input-group-text unit-label"></span>
                            </div>
                        </div>

                        <div class="col-md-6 mt-3">
                            <label for="netpr" class="form-label">Invoice Price</label>
                            <input type="text" class="form-control currency" name="netpr[]" id="netpr-{{ $barangIndex }}" value="{{ old('netpr') }}">
                        </div>

                        <div class="col-md-6 mt-3">
                            <label for="inprc" class="form-label">Invoice Amount</label>
                            <input type="text" class="form-control currency" name="inprc[]" id="inprc-{{ $barangIndex }}" value="{{ old('inprc') }}">
                        </div>

                        <div class="col-md-6 mt-3">
                            <label for="ewprc" class="form-label">Ex-work Price</label>
                            <input type="text" class="form-control currency" name="ewprc[]" id="ewprc-{{ $barangIndex }}" value="{{ old('ewprc') }}">
                        </div>

                        <div class="col-md-6 mt-3">
                            <label for="fobch" class="form-label">FOB Charges</label>
                            <input type="text" class="form-control currency" name="fobch[]" id="fobch-{{ $barangIndex }}" value="{{ old('fobch') }}">
                        </div>

                        <div class="col-md-6 mt-3">
                            <label for="incst" class="form-label">Insurance</label>
                            <input type="text" class="form-control currency" name="incst[]" id="incst-{{ $barangIndex }}" value="{{ old('incst') }}">
                        </div>

                        <div class="col-md-6 mt-3">
                            <label for="hsn" class="form-label">HS no.</label>
                            <select class="select2 form-control hsn-select" name="hsn" id="hsn-{{ $barangIndex }}">
                                <option value="" disabled {{ old('hsn') ? '' : 'selected' }}>Pilih HS no.</option>
                                @foreach ($hsnList as $h )
                                    <option value="{{ $h->hsn }}" data-bm="{{ $h->bm }}">
                                        {{ $h->hsn }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-6 mt-3">
                            <label for="bm" class="form-label">BM (%)</label>
                            <input type="number" class="form-control" name="bm[]" id="bm-{{ $barangIndex }}" value="{{ old('bm') }}" oninput="this.value = this.value.replace(/[^0-9.]/g, '')">
                        </div>

                        <div class="col-md-2 mt-3">
                            <label for="ppn" class="form-label">PPn (%)</label>
                            <input type="number" class="form-control" name="ppn[]" id="ppn-{{ $barangIndex }}" value="{{ old('ppn') }}" oninput="this.value = this.value.replace(/[^0-9.]/g, '')">
                        </div>

                        <div class="col-md-2 mt-3">
                            <label for="ppnbm" class="form-label">PPnBM (%)</label>
                            <input type="number" class="form-control" name="ppnbm[]" id="ppnbm-{{ $barangIndex }}" value="{{ old('ppnbm') }}" oninput="this.value = this.value.replace(/[^0-9.]/g, '')">
                        </div>

                        <div class="col-md-2 mt-3">
                            <label for="pph" class="form-label">PPh (%)</label>
                            <input type="number" class="form-control" name="pph[]" id="pph-{{ $barangIndex }}" value="{{ old('pph') }}" oninput="this.value = this.value.replace(/[^0-9.]/g, '')">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>