<script>
    // mulai dari jumlah invoice lama (agar old() juga keitung)
    let invoiceIndex = {{ count(old('opron', [null])) }};

    function addInvoiceLocInv() {
        const accordion = document.getElementById('accordionInvoiceLocInv');

        const newItem = document.createElement('div');
        newItem.classList.add('accordion-item');
        newItem.id = `accordion-item-${invoiceIndex}`;

        newItem.innerHTML = `
            <h2 class="accordion-header d-flex justify-content-between align-items-center" id="heading-${invoiceIndex}">
                <button class="accordion-button collapsed" type="button"
                        data-bs-toggle="collapse" data-bs-target="#barang-${invoiceIndex}"
                        aria-expanded="false" aria-controls="barang-${invoiceIndex}">
                </button>
                <button type="button" class="btn btn-sm btn-danger mx-2" onclick="removeInvoice(${invoiceIndex})">
                    <i class="bi bi-trash-fill"></i>
                </button>
            </h2>
            <div id="barang-${invoiceIndex}" class="accordion-collapse collapse"
                aria-labelledby="heading-${invoiceIndex}" data-bs-parent="#accordionInvoiceLocInv">
                <div class="accordion-body">
                    <div class="row">
                        <div class="col-md-6 mt-3">
                            <label class="form-label">No. PO <span class="text-danger">*</span></label>
                            <select class="select2 form-control" name="pono[]" id="locinv-pono-${invoiceIndex}" required>
                                <option value="" disabled selected>Silahkan pilih Supplier terlebih dahulu</option>
                            </select>
                        </div>

                        <div class="col-md-6 mt-3">
                            <label class="form-label">Barang <span class="text-danger">*</span></label>
                            <select class="select2 form-control" name="opron[]" id="locinv-opron-${invoiceIndex}" required>
                                <option value="" disabled selected>Silahkan pilih PO No. terlebih dahulu</option>
                            </select>
                        </div>

                        <div class="col-md-6 mt-3">
                            <label for="poqty-${invoiceIndex}" class="form-label">PO Quantity</label>
                            <div class="input-group">
                                <input type="text" class="form-control poqty" style="background-color: #e9ecef;" name="poqty[]" id="poqty-${invoiceIndex}" readonly>
                                <span class="input-group-text unit-label"></span>
                                <input type="text" name="stdqt[]" class="stdqu-input" hidden>
                            </div>
                        </div>

                        <div class="col-md-6 mt-3">
                            <label for="inqty-${invoiceIndex}" class="form-label">Invoice Quantity</label>
                            <div class="input-group">
                                <input type="text" class="form-control" name="inqty[]" id="inqty-${invoiceIndex}"
                                oninput="let poqty = document.getElementById('poqty-${invoiceIndex }').value; if (Number(this.value) > Number(poqty)) { Swal.fire({ title: 'Peringatan', text: 'Jumlah Invoice qty tidak boleh lebih besar dari jumlah PO qty', icon: 'error' }); this.value = poqty; }">
                                <span class="input-group-text unit-label"></span>
                            </div>
                        </div>

                        <div class="col-md-4 mt-3">
                            <label class="form-label">PO Price/unit</label>
                            <input type="text" class="form-control currency" style="background-color: #e9ecef;" name="price[]" id="price-${invoiceIndex}" readonly>
                        </div>

                        <div class="col-md-4 mt-3">
                            <label class="form-label">Invoice Price/unit</label>
                            <input type="text" class="form-control currency" name="inprc[]" id="inprc-${invoiceIndex}">
                        </div>

                        <div class="col-md-4 mt-3">
                            <label for="inamt" class="form-label">Invoice Amount</label>
                            <input type="text" class="form-control currency" name="inamt[]" id="inamt-${invoiceIndex}" style="background-color: #e9ecef;" readonly>
                        </div>

                        <div class="col-md-4 mt-3">
                            <label class="form-label">PPn (%)</label>
                            <input type="number" class="form-control" name="ppn[]" id="ppn-${invoiceIndex}" oninput="this.value = this.value.replace(/[^0-9.]/g, '')">
                        </div>

                        <div class="col-md-4 mt-3">
                            <label class="form-label">PPnBM (%)</label>
                            <input type="number" class="form-control" name="ppnbm[]" id="ppnbm-${invoiceIndex}" oninput="this.value = this.value.replace(/[^0-9.]/g, '')">
                        </div>

                        <div class="col-md-4 mt-3">
                            <label class="form-label">PPh (%)</label>
                            <input type="text" class="form-control" name="pph[]" id="pph-${invoiceIndex}" oninput="this.value = this.value.replace(/[^0-9.,]/g, '')" onblur="this.value = this.value.replace(',', '.')">
                        </div>
                    </div>
                </div>
            </div>
        `;

        accordion.appendChild(newItem);

        // otomatis buka accordion yang baru dibuat
        const collapse = new bootstrap.Collapse(document.getElementById(`barang-${invoiceIndex}`), { show: true });

        // re-init select2 & currency formatting untuk elemen baru
        $(`#locinv-pono-${invoiceIndex}`).select2({ theme: 'bootstrap-5', width: '100%' });
        $(`#locinv-opron-${invoiceIndex}`).select2({ theme: 'bootstrap-5', width: '100%' });

        const supno = selectedSupplier || $('select[name="supno"]').val();
        const $ponoSelect = $(`#locinv-pono-${invoiceIndex}`);

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
        document.querySelectorAll(`#accordion-item-${invoiceIndex} .currency`).forEach(function(input) {
            const rawValue = input.value.replace(/[^\d]/g, '');
            if (rawValue) {
                input.value = new Intl.NumberFormat('id-ID', {
                    style: 'currency',
                    currency: currencySelect,
                    minimumFractionDigits: 0
                }).format(rawValue);
            }
        });

        invoiceIndex++;
    }

    function removeInvoice(index) {
        const item = document.getElementById(`accordion-item-${index}`);
        if (item) item.remove();
    }
</script>