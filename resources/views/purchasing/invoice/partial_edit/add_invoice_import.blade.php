<script>
    let invoiceImportIndex = document.querySelectorAll('#accordionInvoiceImport .accordion-item').length || 0;

    function addInvoiceImport() {
        const accordion = document.getElementById('accordionInvoiceImport');
        const currentIndex = invoiceImportIndex;

        const newItem = document.createElement('div');
        newItem.classList.add('accordion-item');
        newItem.id = `accordion-item-${currentIndex}`;

        newItem.innerHTML = `
            <h2 class="accordion-header d-flex justify-content-between align-items-center" id="heading-${currentIndex}">
                <button class="accordion-button collapsed" type="button"
                        data-bs-toggle="collapse" data-bs-target="#barang-${currentIndex}"
                        aria-expanded="false" aria-controls="barang-${currentIndex}">
                </button>
                <button type="button" class="btn btn-sm btn-danger mx-2" onclick="removeInvoiceImport(${currentIndex})">
                    <i class="bi bi-trash-fill"></i>
                </button>
            </h2>

            <div id="barang-${currentIndex}" class="accordion-collapse collapse"
                aria-labelledby="heading-${currentIndex}" data-bs-parent="#accordionInvoiceImport">
                <div class="accordion-body">
                    <div class="row">
                        <div class="col-md-6 mt-3">
                            <label for="import-pono-${currentIndex}" class="form-label">No. PO <span class="text-danger">*</span></label>
                            <select class="select2 form-control" name="pono[]" id="import-pono-${currentIndex}" required>
                                <option value="" disabled selected>Silahkan pilih Supplier terlebih dahulu</option>
                            </select>
                        </div>

                        <div class="col-md-6 mt-3">
                            <label for="import-opron-${currentIndex}" class="form-label">Barang <span class="text-danger">*</span></label>
                            <select class="select2 form-control opron-select" name="opron[]" id="import-opron-${currentIndex}" required>
                                <option value="" disabled selected>Silahkan pilih PO no. terlebih dahulu</option>
                            </select>
                        </div>

                        <div class="col-md-6 mt-3">
                            <label for="poqty-${currentIndex}" class="form-label">PO Quantity</label>
                            <div class="input-group">
                                <input type="text" class="form-control poqty" id="poqty-${currentIndex}" style="background-color: #e9ecef;" readonly>
                                <span class="input-group-text unit-label"></span>
                                <input type="text" name="stdqt[]" class="stdqu-input" hidden>
                            </div>
                        </div>

                        <div class="col-md-6 mt-3">
                            <label for="inqty-${currentIndex}" class="form-label">Invoice Quantity</label>
                            <div class="input-group">
                                <input type="text" class="form-control" name="inqty[]" id="inqty-${currentIndex}"
                                oninput="let poqty = document.getElementById('poqty-${currentIndex}').value; if (Number(this.value) > Number(poqty)) { Swal.fire({ title: 'Peringatan', text: 'Jumlah Invoice qty tidak boleh lebih besar dari jumlah PO qty', icon: 'error' }); this.value = poqty; }">
                                <span class="input-group-text unit-label"></span>
                            </div>
                        </div>

                        <div class="col-md-4 mt-3">
                            <label for="price-${currentIndex}" class="form-label">PO Price/unit</label>
                            <input type="text" class="form-control currency" name="price[]" id="price-${currentIndex}" style="background-color: #e9ecef;" readonly>
                        </div>

                        <div class="col-md-4 mt-3">
                            <label for="inprc-${currentIndex}" class="form-label">Invoice Price/unit</label>
                            <input type="text" class="form-control currency" name="inprc[]" id="inprc-${currentIndex}">
                        </div>

                        <div class="col-md-4 mt-3">
                            <label for="inamt-${currentIndex}" class="form-label">Invoice Amount</label>
                            <input type="text" class="form-control currency" name="inamt[]" id="inamt-${currentIndex}" style="background-color: #e9ecef;" readonly>
                        </div>

                        <div class="col-md-6 mt-3">
                            <label for="ewprc-${currentIndex}" class="form-label">Ex-work Price</label>
                            <input type="text" class="form-control currency" name="ewprc[]" id="ewprc-${currentIndex}">
                        </div>

                        <div class="col-md-6 mt-3">
                            <label for="fobch-${currentIndex}" class="form-label">FOB Charges</label>
                            <input type="text" class="form-control currency" name="fobch[]" id="fobch-${currentIndex}">
                        </div>

                        <div class="col-md-6 mt-3">
                            <label for="incst-${currentIndex}" class="form-label">Insurance</label>
                            <input type="text" class="form-control currency" name="incst[]" id="incst-${currentIndex}">
                        </div>

                        <div class="col-md-6 mt-3">
                            <label for="hsn-${currentIndex}" class="form-label">HS no.</label>
                            <select class="select2 form-control hsn-select" name="hsn[]" id="hsn-${currentIndex}">
                                <option value="" disabled selected>Pilih HS no.</option>
                                @foreach ($hsnList as $h)
                                    <option value="{{ $h->hsn }}" data-bm="{{ $h->bm }}">{{ $h->hsn }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-6 mt-3">
                            <label for="bm-${currentIndex}" class="form-label">BM (%)</label>
                            <input type="number" class="form-control" name="bm[]" id="bm-${currentIndex}" readonly style="background-color:#e9ecef;">
                        </div>

                        <div class="col-md-2 mt-3">
                            <label for="ppn-${currentIndex}" class="form-label">PPn (%)</label>
                            <input type="number" class="form-control" name="ppn[]" id="ppn-${currentIndex}" oninput="this.value = this.value.replace(/[^0-9.]/g, '')">
                        </div>

                        <div class="col-md-2 mt-3">
                            <label for="ppnbm-${currentIndex}" class="form-label">PPnBM (%)</label>
                            <input type="number" class="form-control" name="ppnbm[]" id="ppnbm-${currentIndex}" oninput="this.value = this.value.replace(/[^0-9.]/g, '')">
                        </div>

                        <div class="col-md-2 mt-3">
                            <label for="pph-${currentIndex}" class="form-label">PPh (%)</label>
                            <input type="text" class="form-control" name="pph[]" id="pph-${currentIndex}" oninput="this.value = this.value.replace(/[^0-9,.]/g, '')" onblur="this.value = this.value.replace(',', '.')">
                        </div>
                    </div>
                </div>
            </div>
        `;

        accordion.appendChild(newItem);

        // otomatis buka accordion yang baru dibuat
        const collapse = new bootstrap.Collapse(document.getElementById(`barang-${currentIndex}`), { show: true });

        // re-init select2
        $(`#import-pono-${currentIndex}`).select2({ theme: 'bootstrap-5', width: '100%' });
        $(`#import-opron-${currentIndex}`).select2({ theme: 'bootstrap-5', width: '100%' });
        $(`#hsn-${currentIndex}`).select2({ theme: 'bootstrap-5', width: '100%' });

        const supno = $('select[name="supno"]').val();
        const $ponoSelect = $(`#import-pono-${currentIndex}`);

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
        document.querySelectorAll(`#accordion-item-${currentIndex} .currency`).forEach(function(input) {
            const rawValue = input.value.replace(/[^\d]/g, '');
            if (rawValue) {
                input.value = new Intl.NumberFormat('id-ID', {
                    style: 'currency',
                    currency: currencySelect,
                    minimumFractionDigits: 0
                }).format(rawValue);
            }
        });

        invoiceImportIndex++;
    }

    function removeInvoiceImport(index) {
        const item = document.getElementById(`accordion-item-${index}`);
        if (item) item.remove();
    }
</script>