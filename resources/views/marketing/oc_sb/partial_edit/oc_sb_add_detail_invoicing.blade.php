<script>
    // add/remove row OC
    window.addOCInvoicing = function(){
        if (calculateTotalTerm() >= 100) {
            Swal.fire({
                icon: 'warning',
                title: 'Term Percentage Melebihi 100%',
                text: 'Total Term Percentage tidak boleh lebih dari 100%',
                confirmButtonColor: '#4456f1'
            });
            return;
        }
        const i = $('#accordionOCInvoicing .accordion-item').length;
        const dtl = `
        <div class="accordion-item" id="accordion-oc-invoicing-${i}">
            <h2 class="accordion-header d-flex justify-content-between align-items-center" id="heading-oc-${i}">
            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#details-oc-invoicing-${i}" aria-expanded="false" aria-controls="details-oc-invoicing-${i}"><span class="accordion-title">Payment Phase : ${i + 1}</span></button>
            <button type="button" class="btn btn-sm btn-danger mx-2" onclick="removeOCInvoicing(${i})"><i class="bi bi-trash-fill"></i></button>
            </h2>
            <div id="details-oc-invoicing-${i}" class="accordion-collapse collapse" aria-labelledby="heading-oc-${i}" data-bs-parent="#accordionOCInvoicing">
                <div class="accordion-body">
                    <div class="row">
                        <div class="col-md-6 mt-3">
                            <label for="descr" class="form-label">Deskripsi Termin</label><span class="text-danger"> *</span>
                            <input type="text" class="form-control" name="descr[]" id="descr_oc_${i}" required>
                        </div>

                        <div class="col-md-6 mt-3">
                            <label for="toppc" class="form-label">Term Percentage (%)</label><span class="text-danger"> *</span>
                            <input type="number" class="form-control" name="toppc[]" id="toppc_oc_${i}" oninput="this.value = this.value.replace(/[^0-9]/g, ''); validateTermPercentage(this)" required>
                        </div>
                        
                        <div class="col-md-6 mt-3">
                            <label class="form-label">Gross Amount <span class="text-danger">*</span></label>
                            <input type="text" class="form-control price-input" id="gross_display_oc_${i}" data-raw-target="gross_raw_oc_${i}" readonly style="background-color:#e9ecef">

                            <input type="text" name="gross[]" id="gross_raw_oc_${i}" hidden>
                        </div>

                        <div class="col-md-6 mt-3">
                            <label class="form-label">Official Discount</label>
                            <input type="text" class="form-control price-input" id="odisa_display_oc_${i}" data-raw-target="odisa_raw_oc_${i}" readonly style="background-color:#e9ecef">

                            <input type="text" name="odisa[]" id="odisa_raw_oc_${i}" hidden>
                        </div>

                        <div class="col-md-6 mt-3">
                            <label class="form-label">Net Amount <span class="text-danger">*</span></label>
                            <input type="text" class="form-control price-input" id="ntamt_display_oc_${i}" data-raw-target="ntamt_raw_oc_${i}" readonly style="background-color:#e9ecef">

                            <input type="text" name="ntamt[]" id="ntamt_raw_oc_${i}" hidden>
                        </div>

                        <div class="col-md-6 mt-3">
                            <label class="form-label">Billing Amount <span class="text-danger">*</span></label>
                            <input type="text" class="form-control price-input" id="blamt_display_oc_${i}" data-raw-target="blamt_raw_oc_${i}" readonly style="background-color:#e9ecef">

                            <input type="text" name="blamt[]" id="blamt_raw_oc_${i}" hidden>
                        </div>

                        <div class="col-md-6 mt-3">
                            <label class="form-label">Extra Discount</label>
                            <input type="text" class="form-control price-input" id="edisa_display_oc_${i}" data-raw-target="edisa_raw_oc_${i}" readonly style="background-color:#e9ecef">

                            <input type="text" name="edisa[]" id="edisa_raw_oc_${i}" hidden>
                        </div>

                        <div class="col-md-6 mt-3">
                            <label for="billd" class="form-label">Plan Invoicing</label><span class="text-danger"> *</span>
                            <input type="date" class="form-control" name="billd[]" id="billd-oc-${i}" required>
                        </div>

                        <div class="col-md-12 mt-3">
                            <label class="form-label">Notes</label>
                            <textarea type="text" class="form-control" name="noted_invoicing[]" id="noted-oc-${i}" maxlength="200"></textarea>
                            <div class="form-text text-danger text-end" style="font-size:0.7rem;">Maksimal 200 karakter</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>`;
        $('#accordionOCInvoicing').append(dtl);

        if (i > 0) {
            for (let q = 1; q <= 5; q++) {

                // COPY SPLIT
                let prevSplit = $(`#smqp${q}-oc-${i-1}`).val();
                if (prevSplit) {
                    $(`#smqp${q}-oc-${i}`).val(prevSplit);
                }

                // COPY BRANCH
                let prevBranch = $(`#smqtb${q}-oc-${i-1}`).val();
                if (prevBranch) {
                    $(`#smqtb${q}-oc-${i}`)
                        .val(prevBranch)
                        .trigger('change');
                }

                // COPY SALES
                let prevSales = $(`#smqts${q}-oc-${i-1}`).val();
                if (prevSales) {

                    setTimeout(() => {
                        $(`#smqts${q}-oc-${i}`)
                            .val(prevSales)
                            .trigger('change');
                    }, 300);
                }
            }

            validateQuota(i, { target: null });
        }

        const $newSelect = $(`#opron-oc-${i}`);

        $(`#accordion-oc-invoicing-${i} .select2`).each(function () {
            if (!$(this).hasClass("select2-hidden-accessible")) {
                $(this).select2({
                    width: '100%',
                    theme: 'bootstrap-5'
                });
            }
        });

        loadMasterProductAll();

        initPriceFormatter(document.getElementById(`accordion-oc-invoicing-${i}`));

        setTimeout(()=>{
            $(`#details-oc-invoicing-${i}`).collapse('show');
        },100);
    }

    window.removeOCInvoicing = function(i){
        $(`#accordion-oc-invoicing-${i}`).remove();
    }
</script>