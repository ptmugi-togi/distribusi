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
                            <input type="text" class="form-control" name="descr[]" id="descr_oc_${i}" value="{{ old('descr.' .$i) }}" required>
                        </div>

                        <div class="col-md-6 mt-3">
                            <label for="toppc" class="form-label">Term Percentage (%)</label><span class="text-danger"> *</span>
                            <input type="number" class="form-control" name="toppc[]" id="toppc_oc_${i}" value="{{ old('toppc.' .$i) }}" oninput="this.value = this.value.replace(/[^0-9]/g, ''); validateTermPercentage(this)" required>
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
                            <input type="date" class="form-control" name="billd[]" id="billd-oc-${i}" value="{{ old('billd.' . $i) }}" required>
                        </div>

                        <div class="col-md-3 mt-3">
                            <h5 style="margin-top: 35px">Quota 1 :</h5>
                        </div>
                        <div class="col-md-3 mt-3">
                            <label for="smqp1-oc-${i}" class="form-label">Split (%)</label>
                            <input type="number" name="smqp1[]" class="form-control" id="smqp1-oc-${i}" value="{{ old('smqp1.'.$i) }}" oninput="this.value = this.value.replace(/[^0-9.]/g, ''); validateQuota(${i}, event)">
                        </div>
                        <div class="col-md-3 mt-3">
                            <label for="smqtb1-oc-${i}" class="form-label">Branch</label>
                            <select name="smqtb1[]" id="smqtb1-oc-${i}" class="form-control select2">
                                <option value="" disabled {{ old('smqtb1.' .$i) ? '' : 'selected' }}>Silahkan Pilih Branch</option>
                                @foreach ($branches as $b)
                                    <option value="{{ $b->braco }}" {{ old('smqtb1.' .$i) == $b->braco ? 'selected' : '' }}>
                                        {{ $b->braco }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3 mt-3">
                            <label for="smqts1" class="form-label">Sales Rep.</label>
                            <select name="smqts1[]" id="smqts1-oc-${i}" class="form-control select2">
                                <option value="" disabled {{ old('smqts1.' .$i) ? '' : 'selected' }}>Silahkan Pilih Sales Rep</option>
                            </select>
                        </div>

                        <div class="col-md-3 mt-3">
                            <h5 style="margin-top: 35px">Quota 2 :</h5>
                        </div>
                        <div class="col-md-3 mt-3">
                            <label for="smqp2-oc-${i}" class="form-label">Split (%)</label>
                            <input type="number" name="smqp2[]" class="form-control" id="smqp2-oc-${i}" value="{{ old('smqp2.'.$i) }}" oninput="this.value = this.value.replace(/[^0-9.]/g, ''); validateQuota(${i}, event)">
                        </div>
                        <div class="col-md-3 mt-3">
                            <label for="smqtb2-oc-${i}" class="form-label">Branch</label>
                            <select name="smqtb2[]" id="smqtb2-oc-${i}" class="form-control select2">
                                <option value="" disabled {{ old('smqtb2.' .$i) ? '' : 'selected' }}>Silahkan Pilih Branch</option>
                                @foreach ($branches as $b)
                                    <option value="{{ $b->braco }}" {{ old('smqtb2.' .$i) == $b->braco ? 'selected' : '' }}>
                                        {{ $b->braco }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3 mt-3">
                            <label for="smqts2" class="form-label">Sales Rep.</label>
                            <select name="smqts2[]" id="smqts2-oc-${i}" class="form-control select2">
                                <option value="" disabled {{ old('smqts2.' .$i) ? '' : 'selected' }}>Silahkan Pilih Sales Rep</option>
                            </select>
                        </div>

                        <div class="col-md-3 mt-3">
                            <h5 style="margin-top: 35px">Quota 3 :</h5>
                        </div>
                        <div class="col-md-3 mt-3">
                            <label for="smqp3-oc-${i}" class="form-label">Split (%)</label>
                            <input type="number" name="smqp3[]" class="form-control" id="smqp3-oc-${i}" value="{{ old('smqp3.'.$i) }}" oninput="this.value = this.value.replace(/[^0-9.]/g, ''); validateQuota(${i}, event)">
                        </div>
                        <div class="col-md-3 mt-3">
                            <label for="smqtb3-oc-${i}" class="form-label">Branch</label>
                            <select name="smqtb3[]" id="smqtb3-oc-${i}" class="form-control select2">
                                <option value="" disabled {{ old('smqtb3.' .$i) ? '' : 'selected' }}>Silahkan Pilih Branch</option>
                                @foreach ($branches as $b)
                                    <option value="{{ $b->braco }}" {{ old('smqtb3.' .$i) == $b->braco ? 'selected' : '' }}>
                                        {{ $b->braco }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3 mt-3">
                            <label for="smqts3" class="form-label">Sales Rep.</label>
                            <select name="smqts3[]" id="smqts3-oc-${i}" class="form-control select2">
                                <option value="" disabled {{ old('smqts3.' .$i) ? '' : 'selected' }}>Silahkan Pilih Sales Rep</option>
                            </select>
                        </div>

                        <div class="col-md-3 mt-3">
                            <h5 style="margin-top: 35px">Quota 4 :</h5>
                        </div>
                        <div class="col-md-3 mt-3">
                            <label for="smqp4-oc-${i}" class="form-label">Split (%)</label>
                            <input type="number" name="smqp4[]" class="form-control" id="smqp4-oc-${i}" value="{{ old('smqp4.'.$i) }}" oninput="this.value = this.value.replace(/[^0-9.]/g, ''); validateQuota(${i}, event)">
                        </div>
                        <div class="col-md-3 mt-3">
                            <label for="smqtb4-oc-${i}" class="form-label">Branch</label>
                            <select name="smqtb4[]" id="smqtb4-oc-${i}" class="form-control select2">
                                <option value="" disabled {{ old('smqtb4.' .$i) ? '' : 'selected' }}>Silahkan Pilih Branch</option>
                                @foreach ($branches as $b)
                                    <option value="{{ $b->braco }}" {{ old('smqtb4.' .$i) == $b->braco ? 'selected' : '' }}>
                                        {{ $b->braco }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3 mt-3">
                            <label for="smqts4" class="form-label">Sales Rep.</label>
                            <select name="smqts4[]" id="smqts4-oc-${i}" class="form-control select2">
                                <option value="" disabled {{ old('smqts4.' .$i) ? '' : 'selected' }}>Silahkan Pilih Sales Rep</option>
                            </select>
                        </div>

                        <div class="col-md-3 mt-3">
                            <h5 style="margin-top: 35px">Quota 5 :</h5>
                        </div>
                        <div class="col-md-3 mt-3">
                            <label for="smqp5-oc-${i}" class="form-label">Split (%)</label>
                            <input type="number" name="smqp5[]" class="form-control" id="smqp5-oc-${i}" value="{{ old('smqp5.'.$i) }}" oninput="this.value = this.value.replace(/[^0-9.]/g, ''); validateQuota(${i}, event)">
                        </div>
                        <div class="col-md-3 mt-3">
                            <label for="smqtb5-oc-${i}" class="form-label">Branch</label>
                            <select name="smqtb5[]" id="smqtb5-oc-${i}" class="form-control select2">
                                <option value="" disabled {{ old('smqtb5.' .$i) ? '' : 'selected' }}>Silahkan Pilih Branch</option>
                                @foreach ($branches as $b)
                                    <option value="{{ $b->braco }}" {{ old('smqtb5.' .$i) == $b->braco ? 'selected' : '' }}>
                                        {{ $b->braco }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3 mt-3">
                            <label for="smqts5" class="form-label">Sales Rep.</label>
                            <select name="smqts5[]" id="smqts5-oc-${i}" class="form-control select2">
                                <option value="" disabled {{ old('smqts5.' .$i) ? '' : 'selected' }}>Silahkan Pilih Sales Rep</option>
                            </select>
                        </div>

                        <div class="col-md-12 mt-3">
                            <label class="form-label">Notes</label>
                            <textarea type="text" class="form-control" name="noted_invoicing[]" id="noted-oc-${i}" maxlength="200">{{ old('noted_invoicing.'.$i) }}</textarea>
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