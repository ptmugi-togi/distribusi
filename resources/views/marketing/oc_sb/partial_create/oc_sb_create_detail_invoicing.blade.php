<div class="row">
  <h4 class="my-2">OC Invoicing</h4>
  <div class="accordion" id="accordionOCInvoicing">
    @foreach (old('toppc', [null]) as $i => $oldToppc)
      <div class="accordion-item" id="accordion-oc-invoicing-{{ $i }}">
        <h2 class="accordion-header d-flex justify-content-between align-items-center" id="heading-oc-{{ $i }}">
          <button class="accordion-button {{ $i > 0 ? 'collapsed' : '' }}" type="button"
            data-bs-toggle="collapse" data-bs-target="#details-oc-invoicing-{{ $i }}"
            aria-expanded="{{ $i == 0 ? 'true' : 'false' }}" aria-controls="details-oc-invoicing-{{ $i }}">
            <span class="accordion-title">Payment Phase : {{ $i + 1 }}</span>
          </button>
          @if($i > 0)
            <button type="button" class="btn btn-sm btn-danger mx-2" onclick="removeOCInvoicing({{ $i }})">
              <i class="bi bi-trash-fill"></i>
            </button>
          @endif
        </h2>

        <div id="details-oc-invoicing-{{ $i }}" class="accordion-collapse collapse {{ $i == 0 ? 'show' : '' }}"
          aria-labelledby="heading-oc-{{ $i }}" data-bs-parent="#accordionOCInvoicing">
          <div class="accordion-body">
            <div class="row">
                <div class="col-md-6 mt-3">
                    <label for="descr" class="form-label">Deskripsi Termin</label><span class="text-danger"> *</span>
                    <input type="text" class="form-control" name="descr[]" id="descr_oc_{{ $i }}" value="{{ old('descr.' .$i) }}" required>
                </div>

                <div class="col-md-6 mt-3">
                    <label for="toppc" class="form-label">Term Percentage (%)</label><span class="text-danger"> *</span>
                    <input type="number" class="form-control" name="toppc[]" id="toppc_oc_{{ $i }}" value="{{ old('toppc.' .$i) }}" oninput="this.value = this.value.replace(/[^0-9]/g, ''); validateTermPercentage(this)" required>
                </div>

                <div class="col-md-6 mt-3">
                    <label for="gross-oc-{{ $i }}" class="form-label">Gross Amount</label>
                    <input type="text" class="form-control price-input" id="gross_display_oc_{{ $i }}" value="{{ old('gross.'.$i) ? number_format(old('gross.' .$i), 2, '.', '') : '' }}" data-raw-target="gross_raw_oc_{{ $i }}" readonly style="background-color:#e9ecef">

                    <input type="text" name="gross[]" id="gross_raw_oc_{{ $i }}" value="{{ old('gross.'.$i) }}" hidden>
                </div>

                <div class="col-md-6 mt-3">
                    <label class="form-label">Official Discount</label>
                    <input type="text" class="form-control price-input" id="odisa_display_oc_{{ $i }}" value="{{ old('odisa.'.$i) ? number_format(old('odisa.'.$i), 2, '.', '') : '' }}" data-raw-target="odisa_raw_oc_{{ $i }}" readonly style="background-color:#e9ecef">

                    <input type="text" name="odisa[]" id="odisa_raw_oc_{{ $i }}" value="{{ old('odisa.'.$i) }}" hidden>
                </div>

                <div class="col-md-6 mt-3">
                    <label class="form-label">Net Amount <span class="text-danger">*</span></label>
                    <input type="text" class="form-control price-input" id="ntamt_display_oc_{{ $i }}" value="{{ old('ntamt.'.$i) ? number_format(old('ntamt.'.$i), 2, '.', '') : '' }}" data-raw-target="ntamt_raw_oc_{{ $i }}" readonly style="background-color:#e9ecef">

                    <input type="text" name="ntamt[]" id="ntamt_raw_oc_{{ $i }}" value="{{ old('ntamt.'.$i) }}" hidden>
                </div>

                <div class="col-md-6 mt-3">
                    <label class="form-label">Billing Amount <span class="text-danger">*</span></label>
                    <input type="text" class="form-control price-input" id="blamt_display_oc_{{ $i }}" value="{{ old('blamt.'.$i) ? number_format(old('blamt.'.$i), 2, '.', '') : '' }}" data-raw-target="blamt_raw_oc_{{ $i }}" readonly style="background-color:#e9ecef">

                    <input type="text" name="blamt[]" id="blamt_raw_oc_{{ $i }}" value="{{ old('blamt.'.$i) }}" hidden>
                </div>

                <div class="col-md-6 mt-3">
                    <label class="form-label">Extra Discount</label>

                    <input type="text" class="form-control price-input" id="ebamt_display_oc_{{ $i }}" value="{{ old('edisa.'.$i) ? number_format(old('edisa.'.$i), 2, '.', '') : '' }}" data-raw-target="ebamt_raw_oc_{{ $i }}" readonly style="background-color:#e9ecef">

                    <input type="text" name="ebamt[]" id="ebamt_raw_oc_{{ $i }}" value="{{ old('ebamt.'.$i) }}" hidden>
                </div>

                <div class="col-md-6 mt-3">
                    <label for="billd" class="form-label">Plan Invoicing</label><span class="text-danger"> *</span>
                    <input type="date" class="form-control" name="billd[]" id="billd-oc-{{ $i }}" value="{{ old('billd.' .$i) }}" required>
                </div>

                <div class="col-md-12 mt-3">
                    <label class="form-label">Notes</label>
                    <textarea type="text" class="form-control" name="noted_invoicing[]" id="noted-oc-{{ $i }}" maxlength="200">{{ old('noted_invoicing.'.$i) }}</textarea>
                    <div class="form-text text-danger text-end" style="font-size:0.7rem;">Maksimal 200 karakter</div>
                </div>
            </div>
          </div>
        </div>
      </div>
    @endforeach
  </div>

  <div class="text-end">
    <button type="button" id="btn-add-phase" class="btn mt-3" style="background-color:#4456f1;color:#fff" onclick="addOCInvoicing()">Tambah Detail Invoicing </button>
  </div>

  <div class="split">
    <hr>
    <div class="card p-3">
        <div class="row">
            <div class="col-md-3 mt-3">
                <h5 style="margin-top: 35px">Quota 1 :</h5>
            </div>
            <div class="col-md-3 mt-3">
                <label for="smqp1-oc" class="form-label">Split (%)</label>
                <input type="number" name="smqp1" class="form-control" id="smqp1-oc" value="{{ old('smqp1') }}" oninput="this.value = this.value.replace(/[^0-9.]/g, ''); validateQuota(event)">
            </div>
            <div class="col-md-3 mt-3">
                <label for="smqtb1-oc" class="form-label">Branch</label>
                <select name="smqtb1" id="smqtb1-oc" class="form-control select2">
                    <option value="" disabled {{ old('smqtb1') ? '' : 'selected' }}>Silahkan Pilih Branch</option>
                    @foreach ($branches as $b)
                        <option value="{{ $b->braco }}" {{ old('smqtb1') == $b->braco ? 'selected' : '' }}>
                            {{ $b->braco }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3 mt-3">
                <label for="smqts1" class="form-label">Sales Rep.</label>
                <select name="smqts1" id="smqts1-oc" class="form-control select2">
                    <option value="" disabled {{ old('smqts1') ? '' : 'selected' }}>Silahkan Pilih Sales Rep</option>
                </select>
            </div>
        
            <div class="col-md-3 mt-3">
                <h5 style="margin-top: 35px">Quota 2 :</h5>
            </div>
            <div class="col-md-3 mt-3">
                <label for="smqp2-oc" class="form-label">Split (%)</label>
                <input type="number" name="smqp2" class="form-control" id="smqp2-oc" value="{{ old('smqp2') }}" oninput="this.value = this.value.replace(/[^0-9.]/g, ''); validateQuota(event)">
            </div>
            <div class="col-md-3 mt-3">
                <label for="smqtb2-oc" class="form-label">Branch</label>
                <select name="smqtb2" id="smqtb2-oc" class="form-control select2">
                    <option value="" disabled {{ old('smqtb2') ? '' : 'selected' }}>Silahkan Pilih Branch</option>
                    @foreach ($branches as $b)
                        <option value="{{ $b->braco }}" {{ old('smqtb2') == $b->braco ? 'selected' : '' }}>
                            {{ $b->braco }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3 mt-3">
                <label for="smqts2" class="form-label">Sales Rep.</label>
                <select name="smqts2" id="smqts2-oc" class="form-control select2">
                    <option value="" disabled {{ old('smqts2') ? '' : 'selected' }}>Silahkan Pilih Sales Rep</option>
                </select>
            </div>
        
            <div class="col-md-3 mt-3">
                <h5 style="margin-top: 35px">Quota 3 :</h5>
            </div>
            <div class="col-md-3 mt-3">
                <label for="smqp3-oc" class="form-label">Split (%)</label>
                <input type="number" name="smqp3" class="form-control" id="smqp3-oc" value="{{ old('smqp3') }}" oninput="this.value = this.value.replace(/[^0-9.]/g, ''); validateQuota(event)">
            </div>
            <div class="col-md-3 mt-3">
                <label for="smqtb3-oc" class="form-label">Branch</label>
                <select name="smqtb3" id="smqtb3-oc" class="form-control select2">
                    <option value="" disabled {{ old('smqtb3') ? '' : 'selected' }}>Silahkan Pilih Branch</option>
                    @foreach ($branches as $b)
                        <option value="{{ $b->braco }}" {{ old('smqtb3') == $b->braco ? 'selected' : '' }}>
                            {{ $b->braco }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3 mt-3">
                <label for="smqts3" class="form-label">Sales Rep.</label>
                <select name="smqts3" id="smqts3-oc" class="form-control select2">
                    <option value="" disabled {{ old('smqts3') ? '' : 'selected' }}>Silahkan Pilih Sales Rep</option>
                </select>
            </div>
        
            <div class="col-md-3 mt-3">
                <h5 style="margin-top: 35px">Quota 4 :</h5>
            </div>
            <div class="col-md-3 mt-3">
                <label for="smqp4-oc" class="form-label">Split (%)</label>
                <input type="number" name="smqp4" class="form-control" id="smqp4-oc" value="{{ old('smqp4') }}" oninput="this.value = this.value.replace(/[^0-9.]/g, ''); validateQuota(event)">
            </div>
            <div class="col-md-3 mt-3">
                <label for="smqtb4-oc" class="form-label">Branch</label>
                <select name="smqtb4" id="smqtb4-oc" class="form-control select2">
                    <option value="" disabled {{ old('smqtb4') ? '' : 'selected' }}>Silahkan Pilih Branch</option>
                    @foreach ($branches as $b)
                        <option value="{{ $b->braco }}" {{ old('smqtb4') == $b->braco ? 'selected' : '' }}>
                            {{ $b->braco }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3 mt-3">
                <label for="smqts4" class="form-label">Sales Rep.</label>
                <select name="smqts4" id="smqts4-oc" class="form-control select2">
                    <option value="" disabled {{ old('smqts4') ? '' : 'selected' }}>Silahkan Pilih Sales Rep</option>
                </select>
            </div>
        
            <div class="col-md-3 mt-3">
                <h5 style="margin-top: 35px">Quota 5 :</h5>
            </div>
            <div class="col-md-3 mt-3">
                <label for="smqp5-oc" class="form-label">Split (%)</label>
                <input type="number" name="smqp5" class="form-control" id="smqp5-oc" value="{{ old('smqp5') }}" oninput="this.value = this.value.replace(/[^0-9.]/g, ''); validateQuota(event)">
            </div>
            <div class="col-md-3 mt-3">
                <label for="smqtb5-oc" class="form-label">Branch</label>
                <select name="smqtb5" id="smqtb5-oc" class="form-control select2">
                    <option value="" disabled {{ old('smqtb5') ? '' : 'selected' }}>Silahkan Pilih Branch</option>
                    @foreach ($branches as $b)
                        <option value="{{ $b->braco }}" {{ old('smqtb5') == $b->braco ? 'selected' : '' }}>
                            {{ $b->braco }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3 mt-3">
                <label for="smqts5" class="form-label">Sales Rep.</label>
                <select name="smqts5" id="smqts5-oc" class="form-control select2">
                    <option value="" disabled {{ old('smqts5') ? '' : 'selected' }}>Silahkan Pilih Sales Rep</option>
                </select>
            </div>
        </div>
    </div>
  </div>
</div>

@push('scripts')
<script>
    $(document).ready(function () {
        toggleAddButton();
    });
</script>

{{-- hitungan term --}}
<script>
    function toggleAddButton() {

        const total = calculateTotalTerm();

        if (total === 0) {
            $('#btn-add-phase').hide();
            return;
        }

        if (total >= 100) {
            $('#btn-add-phase').hide();
            return;
        }

        $('#btn-add-phase').show();
    }


    function calculateTotalTerm() {
        let total = 0;
        $('input[id^="toppc_oc_"]').each(function () {
            total += parseFloat($(this).val()) || 0;
        });
        return total;
    }

    function validateTermPercentage(input) {
        const total = calculateTotalTerm();
        const value = parseFloat($(input).val()) || 0;

        if (total > 100) {
            Swal.fire({
                icon: 'warning',
                title: 'Term Percentage Melebihi 100%',
                text: 'Total Term Percentage tidak boleh lebih dari 100%',
                confirmButtonColor: '#4456f1'
            });
            $(input).val('');
            return;
        }

        // Disable tombol add jika sudah 100%
        if (calculateTotalTerm() >= 100) {
            $('button[onclick="addOCInvoicing()"]').prop('disabled', true);
        } else {
            $('button[onclick="addOCInvoicing()"]').prop('disabled', false);
        }

        const phaseIndex = $(input).attr('id').split('_').pop();

        calculatePhaseAmounts(phaseIndex);

        toggleAddButton();
    }
</script>

{{-- hitungan otomatis untuk gross, official discount, net amt, bill amt, extradics --}}
<script>
    function calculatePhaseAmounts(phaseIndex) {

        const percent = parseFloat($(`#toppc_oc_${phaseIndex}`).val()) || 0;
        const ratio = percent / 100;

        // get values header
        const grossMaster  = parseFloat($('#gross_raw').val()) || 0;
        const odisaMaster  = parseFloat($('#odisa_raw').val()) || 0;
        const billvMaster  = parseFloat($('#billv_raw').val()) || 0;
        const edisaMaster  = parseFloat($('#edisa_raw').val()) || 0;

        const netMaster = grossMaster - odisaMaster;

        // hitung berdasarkan ratio
        $(`#gross_raw_oc_${phaseIndex}`).val(grossMaster * ratio);
        $(`#odisa_raw_oc_${phaseIndex}`).val(odisaMaster * ratio);
        $(`#ntamt_raw_oc_${phaseIndex}`).val(netMaster * ratio);
        $(`#blamt_raw_oc_${phaseIndex}`).val(billvMaster * ratio);
        $(`#ebamt_raw_oc_${phaseIndex}`).val(edisaMaster * ratio);

        // Set Value ke form detail invoicing 
        const currency = $('#curco').val();

        $(`#gross_display_oc_${phaseIndex}`).val(
            formatCurrency(grossMaster * ratio, currency)
        );

        $(`#odisa_display_oc_${phaseIndex}`).val(
            formatCurrency(odisaMaster * ratio, currency)
        );

        $(`#ntamt_display_oc_${phaseIndex}`).val(
            formatCurrency(netMaster * ratio, currency)
        );

        $(`#blamt_display_oc_${phaseIndex}`).val(
            formatCurrency(billvMaster * ratio, currency)
        );

        $(`#ebamt_display_oc_${phaseIndex}`).val(
            formatCurrency(edisaMaster * ratio, currency)
        );

        // kalau pakai formatter
        initPriceFormatter(document.getElementById(`accordion-oc-invoicing-${phaseIndex}`));
    }
</script>

{{-- validate quota --}}
<script>
    function validateQuota(event) {

        let total = 0;

        for (let i = 1; i <= 5; i++) {
            const val = parseFloat($(`#smqp${i}-oc`).val());
            if (!isNaN(val) && val > 0) {
                total += val;
            }
        }

        for (let i = 1; i <= 5; i++) {

            const splitInput  = $(`#smqp${i}-oc`);
            const branchInput = $(`#smqtb${i}-oc`);
            const salesInput  = $(`#smqts${i}-oc`);

            const val = parseFloat(splitInput.val());

            if (!isNaN(val) && val > 0) {
                branchInput.prop('disabled', false).trigger('change.select2');
                salesInput.prop('disabled', false).trigger('change.select2');
            } else {
                branchInput.val('').prop('disabled', true).trigger('change.select2');
                salesInput.val('').prop('disabled', true).trigger('change.select2');
            }

            if (total >= 100) {
                if (isNaN(val) || val === 0) {
                    splitInput.prop('disabled', true);
                }
            } else {
                splitInput.prop('disabled', false);
            }
        }

        if (total > 100) {
            Swal.fire({
                icon: 'warning',
                title: 'Quota Melebihi 100%',
                text: 'Total Quota tidak boleh lebih dari 100%',
                confirmButtonColor: '#4456f1'
            });

            if (event && event.target) {
                event.target.value = '';
            }
            validateQuota();
            return;
        }
    }
</script>

{{-- get sales per branch invoicing --}}
<script>
    $(document).on('change', '[id^="smqtb"]', function () {

        const id = $(this).attr('id'); 

        const quotaNumber = id.match(/\d+/)[0];

        const branchCode = $(this).val();

        const salesSelect = $(`#smqts${quotaNumber}-oc`);

        if (!branchCode) {
            salesSelect.html('<option value="" disabled selected>Silahkan Pilih Sales Rep</option>');
            return;
        }

        $.ajax({
            url: '/get-sales-by-branch',
            type: 'GET',
            data: { branch: branchCode },
            success: function (res) {

                let options = '<option value="" disabled selected>Silahkan Pilih Sales Rep</option>';

                res.forEach(function (item) {
                    options += `<option value="${item.sreno}">${item.sreno} - ${item.srena}</option>`;
                });

                salesSelect.html(options).trigger('change');
            }
        });

    });
</script>

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
                            <input type="text" class="form-control price-input" id="ebamt_display_oc_${i}" data-raw-target="ebamt_raw_oc_${i}" readonly style="background-color:#e9ecef">

                            <input type="text" name="ebamt[]" id="ebamt_raw_oc_${i}" hidden>
                        </div>

                        <div class="col-md-6 mt-3">
                            <label for="billd" class="form-label">Plan Invoicing</label><span class="text-danger"> *</span>
                            <input type="date" class="form-control" name="billd[]" id="billd-oc-${i}" value="{{ old('billd.' . $i) }}" required>
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

        const $newSelect = $(`#opron-oc-${i}`);

        $(`#accordion-oc-invoicing-${i} .select2`).each(function () {
            if (!$(this).hasClass("select2-hidden-accessible")) {
                $(this).select2({
                    width: '100%',
                    theme: 'bootstrap-5'
                });
            }
        });

        toggleAddButton();

        loadMasterProductAll();

        initPriceFormatter(document.getElementById(`accordion-oc-invoicing-${i}`));

        setTimeout(()=>{
            $(`#details-oc-invoicing-${i}`).collapse('show');
        },100);
    }

    window.removeOCInvoicing = function(i){
        $(`#accordion-oc-invoicing-${i}`).remove();

        toggleAddButton();

        $('button[onclick="addOCInvoicing()"]').prop('disabled', false);
    }
</script>
@endpush

