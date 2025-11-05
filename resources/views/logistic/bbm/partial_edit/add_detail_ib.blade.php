<script>

    let bbmDetail = document.querySelectorAll('#accordionBbm .accordion-item').length || 0;

    function addIB() {

        const accordion = document.getElementById('accordionBbm');

        const warco = $('#warco').val();
        const refcno = "{{ $bbm->refno }}";

        const newItem = document.createElement('div');
        newItem.classList.add('accordion-item');
        newItem.id = `accordion-item-${bbmDetail}`;

        newItem.innerHTML = `
            <h2 class="accordion-header d-flex justify-content-between align-items-center" id="heading-${bbmDetail}">
                <button class="accordion-button collapsed" type="button"
                        data-bs-toggle="collapse" data-bs-target="#details-${bbmDetail}"
                        aria-expanded="false" aria-controls="details-${bbmDetail}" data-bs-parent="#accordionBbm">
                </button>
                <button type="button" class="btn btn-sm btn-danger mx-2" onclick="removebbmDetail(${bbmDetail})">
                    <i class="bi bi-trash-fill"></i>
                </button>
            </h2>

            <div id="details-${bbmDetail}" class="accordion-collapse collapse"
                aria-labelledby="heading-${bbmDetail}" data-bs-parent="#accordionBbm">
                <div class="accordion-body">
                     <div class="row">
                        <div class="col-md-6 mt-3">
                            <label for="invno" class="form-label">Invoice No.</label><span class="text-danger"> *</span>
                            <select class="select2 form-control" name="invno[]" id="invno-${bbmDetail}" required>
                                <option value="" disabled selected>Silahkan Pilih Receiving Instruction terlebih dahulu</option>
                            </select>
                        </div>

                        <div class="col-md-6 mt-3">
                            <label for="opron" class="form-label">Barang</label><span class="text-danger"> *</span>
                            <select class="select2 form-control" name="opron[]" id="opron-${bbmDetail}" required>
                                <option value="" disabled selected>Silahkan Pilih Invoice No. terlebih dahulu</option>
                            </select>
                        </div>

                        <div class="col-md-6 mt-3">
                            <label for="inqty-${bbmDetail}" class="form-label">Invoice Quantity</label>
                            <div class="input-group">
                                <input type="number" class="form-control" id="inqty-${bbmDetail}" style="background-color: #e9ecef;" readonly>
                                <span class="input-group-text unit-label"></span>
                                <input type="text" id="stdqt-${bbmDetail}" class="stdqu-input" name="stdqt[]" hidden>
                            </div>
                            <div class="form-check mt-3">
                                <input class="form-check-input nolot-checkbox" type="checkbox" value="1" name="nolot[${bbmDetail}]" id="nolot-${bbmDetail}">
                                <label class="form-check-label" for="nolot-${bbmDetail}">
                                    Without Serial / Batch No
                                </label>
                            </div>
                        </div>

                        <div class="col-md-6 mt-3">
                            <label for="trqty-${bbmDetail}" class="form-label">Receipt Quantity</label><span class="text-danger"> *</span>
                            <div class="input-group">
                                <input type="number" class="form-control" id="trqty-${bbmDetail}" name="trqty[]"
                                oninput="
                                    this.value = this.value.replace(/[^0-9]/g, '');
                                    const inqty = Number(document.getElementById('inqty-${bbmDetail}')?.value || 0);
                                    if (Number(this.value) > inqty) {
                                        Swal.fire({
                                            title: 'Peringatan',
                                            text: 'Jumlah Receipt qty tidak boleh lebih banyak dari jumlah Invoice qty',
                                            icon: 'error'
                                        });
                                        this.value = inqty;
                                    }
                                ">
                                <span class="input-group-text unit-label"></span>
                            </div>
                        </div>

                        <div class="col-md-6 mt-3 lot-section">
                            <label for="lotno-${bbmDetail}" class="form-label">Serial / Batch No.</label>
                            <input type="text" class="form-control lotno-input" name="lotno[]" id="lotno-${bbmDetail}">
                        </div>

                        <div class="col-md-6 mt-3 lot-section">
                            <label for="lotnoend-${bbmDetail}" class="form-label">Serial / Batch No. (Akhir)</label>
                            <input type="text" class="form-control" name="lotnoend[]" id="lotnoend-${bbmDetail}" readonly style="background-color: #e9ecef">
                        </div>

                        <div class="col-md-6 mt-3">
                            <label for="pono-${bbmDetail}" class="form-label">PO No.</label>
                            <input type="text" class="form-control" name="pono[]" id="pono-${bbmDetail}" readonly style="background-color: #e9ecef">
                        </div>

                        <div class="col-md-6 mt-3">
                            <label for="locco" class="form-label">Warehouse Location</label><span class="text-danger"> *</span>
                            <select class="form-control select2" name="locco[]" id="locco-${bbmDetail}" required>
                                <option value="" disabled selected>Pilih Warehouse terlebih dahulu</option>
                            </select>
                        </div>

                        <div class="col-md-12 mt-3">
                            <label for="noted" class="form-label">Notes</label>
                            <textarea 
                                type="text" class="form-control" name="noted[]" id="noted" maxlength="200"></textarea>
                            <div class="form-text text-danger text-end" style="font-size: 0.7rem;">
                                Maksimal 200 karakter
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        `;

        accordion.appendChild(newItem);

        // otomatis buka accordion yang baru dibuat
        const collapse = new bootstrap.Collapse(document.getElementById(`details-${bbmDetail}`), { show: true });

        // re-init select2
        $(`#invno-${bbmDetail}`).select2({ theme: 'bootstrap-5', width: '100%' });
        $(`#opron-${bbmDetail}`).select2({ theme: 'bootstrap-5', width: '100%' });
        $(`#locco-${bbmDetail}`).select2({ theme: 'bootstrap-5', width: '100%' });

        // Ambil data invoice berdasarkan Receiving Instruction
        if (refcno) {
            const $invSelect = $(`#invno-${bbmDetail}`);
            $invSelect.html('<option value="">Loading...</option>');

            $.ajax({
                url: `/get-invoice/${refcno}`,
                type: 'GET',
                dataType: 'json',
                success: function (response) {
                    const data = response.data || response;
                    if (data.length > 0) {
                        let options = '<option value="" disabled selected>Pilih Invoice No.</option>';
                        data.forEach(item => {
                            options += `<option value="${item.invno}">${item.invno}</option>`;
                        });

                        $invSelect.html(options).trigger('change.select2');

                        const oldInvno = @json(old('invno', []));
                        if (oldInvno[bbmDetail]) {
                            invSelect.val(oldInvno[bbmDetail]).trigger('change');
                        }
                    } else {
                        $invSelect.html('<option value="">Tidak ada Invoice untuk Receiving Instruction ini</option>');
                    }
                },
                error: function () {
                    $invSelect.html('<option value="">Gagal memuat data Invoice</option>');
                }
            });
        }

        // Ambil lokasi berdasarkan Warehouse
        if (warco) {
            const $locSelect = $(`#locco-${bbmDetail}`);
            $locSelect.html('<option value="">Loading...</option>');

            $.ajax({
                url: `/get-locco/${warco}`,
                type: 'GET',
                dataType: 'json',
                success: function (response) {
                    const data = response.data || response;
                    if (data.length > 0) {
                        let options = '<option value="" disabled selected>Pilih Lokasi</option>';
                        data.forEach(item => {
                            options += `<option value="${item.locco}">${item.locco}</option>`;
                        });
                        
                        $locSelect.html(options).trigger('change.select2');

                        const oldLocco = @json(old('locco', []));
                        if (oldLocco[bbmDetail]) {
                            locSelect.val(oldLocco[bbmDetail]).trigger('change');
                        }
                    } else {
                        $locSelect.html('<option value="">Tidak ada lokasi untuk warehouse ini</option>');
                    }
                },
                error: function () {
                    $locSelect.html('<option value="">Gagal memuat data Lokasi</option>');
                }
            });
        }

        bbmDetail++;
    }

    function removebbmDetail(bbmDetail) {
        const accordionItem = document.getElementById(`accordion-item-${bbmDetail}`);
        accordionItem.remove();
    }

</script>

<script>
    function setAccordionTitleIB(item){
        const invno = item.find('select[name*="invno"]').val() || '';
        item.find('.accordion-title').text(invno ? `Invoice : ${invno}` : `Invoice : -`);
    }

    // listen IB
    $(document).on('change','select[name*="invno"]', function(){
        const item = $(this).closest('.accordion-item');
        setAccordionTitleIB(item);
    });

    setTimeout(() => {
        setAccordionTitleIB($('#accordion-item-'+i));
    },100);
</script>
