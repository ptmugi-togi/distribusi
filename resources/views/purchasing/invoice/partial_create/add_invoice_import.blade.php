<script>
    let invoiceImport = {{ count(old('opron', [null])) }};

    function addInvoiceImport() {
        const accordion = document.getElementById('accordionInvoiceImport');

        const newItem = document.createElement('div');
        newItem.classList.add('accordion-item');
        newItem.id = `accordion-item-${invoiceImport}`;

        newItem.innerHTML = `
            <h2 class="accordion-header d-flex justify-content-between align-items-center" id="heading-${invoiceImport}">
                <button class="accordion-button collapsed" type="button"
                        data-bs-toggle="collapse" data-bs-target="#barang-${invoiceImport}"
                        aria-expanded="false" aria-controls="barang-${invoiceImport}">
                </button>
                <button type="button" class="btn btn-sm btn-danger mx-2" onclick="removeInvoiceImport(${invoiceImport})">
                    <i class="bi bi-trash-fill"></i>
                </button>
            </h2>

            <div id="barang-${invoiceImport}" class="accordion-collapse collapse"
                aria-labelledby="heading-${invoiceImport}" data-bs-parent="#accordionInvoiceImport">
                <div class="accordion-body">
                    <div class="row">
                        <div class="col-md-6 mt-3">
                            <label for="import-pono-${invoiceImport}" class="form-label">No. PO <span class="text-danger">*</span></label>
                            <select class="select2 form-control" name="pono[]" id="import-pono-${invoiceImport}" required>
                                <option value="" disabled selected>Silahkan pilih Supplier terlebih dahulu</option>
                            </select>
                        </div>

                        <div class="col-md-6 mt-3">
                            <label for="import-opron-${invoiceImport}" class="form-label">Barang <span class="text-danger">*</span></label>
                            <select class="select2 form-control opron-select" name="opron[]" id="import-opron-${invoiceImport}" required>
                                <option value="" disabled selected>Silahkan pilih PO no. terlebih dahulu</option>
                            </select>
                        </div>

                        <div class="col-md-6 mt-3">
                            <label for="poqty-${invoiceImport}" class="form-label">PO Quantity</label>
                            <div class="input-group">
                                <input type="text" class="form-control poqty" id="poqty-${invoiceImport}" style="background-color: #e9ecef;" readonly>
                                <span class="input-group-text unit-label"></span>
                                <input type="text" name="stdqt[]" class="stdqu-input" hidden>
                            </div>
                        </div>

                        <div class="col-md-6 mt-3">
                            <label for="inqty-${invoiceImport}" class="form-label">Invoice Quantity</label>
                            <div class="input-group">
                                <input type="text" class="form-control" name="inqty[]" id="inqty-${invoiceImport}"
                                oninput="let poqty = document.getElementById('poqty-${invoiceImport }').value; if (Number(this.value) > Number(poqty)) { Swal.fire({ title: 'Peringatan', text: 'Jumlah Invoice qty tidak boleh lebih besar dari jumlah PO qty', icon: 'error' }); this.value = poqty; }">
                                <span class="input-group-text unit-label"></span>
                            </div>
                        </div>

                        <div class="col-md-4 mt-3">
                            <label for="price-${invoiceImport}" class="form-label">PO Price/unit</label>
                            <input type="text" class="form-control currency" name="price[]" id="price-${invoiceImport}" style="background-color: #e9ecef;" readonly>
                        </div>

                        <div class="col-md-4 mt-3">
                            <label for="inprc-${invoiceImport}" class="form-label">Invoice Price/unit</label>
                            <input type="text" class="form-control currency" name="inprc[]" id="inprc-${invoiceImport}">
                        </div>

                        <div class="col-md-4 mt-3">
                            <label for="inamt-${invoiceImport}" class="form-label">Invoice Amount</label>
                            <input type="text" class="form-control currency" name="inamt[]" id="inamt-${invoiceImport}" style="background-color: #e9ecef;" readonly>
                        </div>

                        <div class="col-md-6 mt-3">
                            <label for="ewprc-${invoiceImport}" class="form-label">Ex-work Price</label>
                            <input type="text" class="form-control currency" name="ewprc[]" id="ewprc-${invoiceImport}">
                        </div>

                        <div class="col-md-6 mt-3">
                            <label for="fobch-${invoiceImport}" class="form-label">FOB Charges</label>
                            <input type="text" class="form-control currency" name="fobch[]" id="fobch-${invoiceImport}">
                        </div>

                        <div class="col-md-6 mt-3">
                            <label for="incst-${invoiceImport}" class="form-label">Insurance</label>
                            <input type="text" class="form-control currency" name="incst[]" id="incst-${invoiceImport}">
                        </div>

                        <div class="col-md-6 mt-3">
                            <label for="hsn-${invoiceImport}" class="form-label">HS no.</label>
                            <select class="select2 form-control hsn-select" name="hsn[]" id="hsn-${invoiceImport}">
                                <option value="" disabled selected>Pilih HS no.</option>
                                @foreach ($hsnList as $h)
                                    <option value="{{ $h->hsn }}" data-bm="{{ $h->bm }}">{{ $h->hsn }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-6 mt-3">
                            <label for="bm-${invoiceImport}" class="form-label">BM (%)</label>
                            <input type="number" class="form-control" name="bm[]" id="bm-${invoiceImport}" readonly style="background-color:#e9ecef;">
                        </div>

                        <div class="col-md-2 mt-3">
                            <label for="ppn-${invoiceImport}" class="form-label">PPn (%)</label>
                            <input type="number" class="form-control" name="ppn[]" id="ppn-${invoiceImport}" oninput="this.value = this.value.replace(/[^0-9.]/g, '')">
                        </div>

                        <div class="col-md-2 mt-3">
                            <label for="ppnbm-${invoiceImport}" class="form-label">PPnBM (%)</label>
                            <input type="number" class="form-control" name="ppnbm[]" id="ppnbm-${invoiceImport}" oninput="this.value = this.value.replace(/[^0-9.]/g, '')">
                        </div>

                        <div class="col-md-2 mt-3">
                            <label for="pph-${invoiceImport}" class="form-label">PPh (%)</label>
                            <input type="text" class="form-control" name="pph[]" id="pph-${invoiceImport}" oninput="this.value = this.value.replace(/[^0-9,.]/g, '')" onblur="this.value = this.value.replace(',', '.')">
                        </div>
                    </div>
                </div>
            </div>
        `;

        accordion.appendChild(newItem);

        // otomatis buka accordion yang baru dibuat
        const collapse = new bootstrap.Collapse(document.getElementById(`barang-${invoiceImport}`), { show: true });

        // re-init select2
        $(`#import-pono-${invoiceImport}`).select2({ theme: 'bootstrap-5', width: '100%' });
        $(`#import-opron-${invoiceImport}`).select2({ theme: 'bootstrap-5', width: '100%' });
        $(`#hsn-${invoiceImport}`).select2({ theme: 'bootstrap-5', width: '100%' });

        const supno = selectedSupplier || $('select[name="supno"]').val();
        const $ponoSelect = $(`#import-pono-${invoiceImport}`);

        if (supno) {
            $ponoSelect.html('<option value="">Loading...</option>');
            $.ajax({
                url: `/get-po-by-supplier/${supno}`,
                type: 'GET',
                dataType: 'json',
                success: function (response) {
                    if (response.success && response.data.length > 0) {
                        let options = '<option value="" disabled selected>Pilih No. PO</option>';
                        response.data.forEach(po => {
                            options += `<option value="${po.pono}">${po.pono}</option>`;
                        });
                        $ponoSelect.html(options).trigger('change.select2');
                    } else {
                        $ponoSelect.html('<option value="">Tidak ada PO untuk supplier ini</option>');
                    }
                },
                error: function () {
                    $ponoSelect.html('<option value="">Gagal memuat data PO</option>');
                }
            });
        } else {
            $ponoSelect.html('<option value="">Pilih supplier dulu di header</option>');
        }

        // observer currency formatting biar langsung berlaku
        const currencySelect = $('.currency-selector').val() || 'IDR';
        document.querySelectorAll(`#accordion-item-${invoiceImport} .currency`).forEach(function(input) {
            const rawValue = input.value.replace(/[^\d]/g, '');
            if (rawValue) {
                input.value = new Intl.NumberFormat('id-ID', {
                    style: 'currency',
                    currency: currencySelect,
                    minimumFractionDigits: 0
                }).format(rawValue);
            }
        });

        invoiceImport++;
    }

function removeInvoiceImport(index) {
    const item = document.getElementById(`accordion-item-${index}`);
    if (item) item.remove();
}
</script>