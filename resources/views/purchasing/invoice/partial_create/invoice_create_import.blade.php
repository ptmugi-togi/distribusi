<div class="row">
    <div class="col-md-6 mt-3">
        <label for="supno" class="form-label">Supplier <span class="text-danger">*</span></label>
        <select class="select2 form-control" name="supno" id="supno-import" required>
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
        <label for="invno" class="form-label">Invoice no.</label><span class="text-danger"> *</span>
        <input type="number" class="form-control" name="invno" id="invno-import" value="{{ old('invno') }}" oninput="this.value = this.value.replace(/[^0-9]/g, '')" required>
        @error('invno')
            <span class="text-danger">
                <strong>{{ $message }}</strong>
            </span>
        @enderror
    </div>

    <div class="col-md-6 mt-3">
        <label for="invdt" class="form-label">Invoice Date</label><span class="text-danger"> *</span>
        <input type="date" class="form-control" name="invdt" id="invdt-import" value="{{ old('invdt') }}">
    </div>

    <div class="col-md-6 mt-3">
        <label for="duedt" class="form-label">Due Date</label><span class="text-danger"> *</span>
        <input type="date" class="form-control" name="duedt" id="duedt-import" value="{{ old('duedt') }}">
    </div>

    <div class="col-md-6 mt-3">
        <input type="text" name="braco" id="braco-import" value="PST" hidden>
        <input type="text" name="formc" id="formc-import" value="RI" hidden>

        <label for="rinum" class="form-label">Receipt Number</label><span class="text-danger"> *</span>
        <select class="form-select select2" name="rinum" id="rinum-import" required>
            <option value="">Pilih Supplier Terlebih Dahulu</option>
        </select>
    </div>

    <div class="col-md-6 mt-3">
        <label for="blnum" class="form-label">BL / AWB no.</label><span class="text-danger"> *</span>
        <input type="text" class="form-control" name="blnum" id="blnum-import" value="{{ old('blnum') }}" readonly style="background-color: #e9ecef">
    </div>

    <div class="col-md-6 mt-3">
        <label for="curco" class="form-label">Currency Code</label><span class="text-danger"> *</span>
        <select class="select2 form-control currency-selector" name="curco" id="currency-import" style="width: 100%;" required>
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
        <label for="tfreight" class="form-label">Freight</label><span class="text-danger"> *</span>
        <input type="text" class="form-control currency" name="tfreight" id="tfreight-import" value="{{ old('tfreight') }}">
    </div>
</div>

<hr class="my-4">

<div class="row">
    <h3 class="my-2">Invoice Detail</h3>
    <div class="accordion" id="accordionInvoiceImport">
      @foreach (old('opron', [null]) as $i => $oldOpron)
            @include('purchasing.invoice.partial_create.invoice_create_detail_import', ['i' => $i, 'oldOpron' => $oldOpron])
        @endforeach
    </div>
</div>


<div class="text-end">
    <button type="button" class="btn mt-3" style="background-color: #4456f1; color: #fff" onclick="addInvoiceImport()">Tambah Detail Invoice</button>
</div>