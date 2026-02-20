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

                    <input type="text" class="form-control price-input" id="edisa_display_oc_{{ $i }}" value="{{ old('edisa.'.$i) ? number_format(old('edisa.'.$i), 2, '.', '') : '' }}" data-raw-target="edisa_raw_oc_{{ $i }}" readonly style="background-color:#e9ecef">

                    <input type="text" name="edisa[]" id="edisa_raw_oc_{{ $i }}" value="{{ old('edisa.'.$i) }}" hidden>
                </div>

                <div class="col-md-6 mt-3">
                    <label for="billd" class="form-label">Plan Invoicing</label><span class="text-danger"> *</span>
                    <input type="date" class="form-control" name="billd[]" id="billd" value="{{ old('billd.' .$i) }}" required>
                </div>

                <div class="col-md-3 mt-3">
                    <h5 style="margin-top: 35px">Quota 1 :</h5>
                </div>
                <div class="col-md-3 mt-3">
                    <label for="smqp1-oc-{{ $i }}" class="form-label">Split (%)</label>
                    <input type="number" name="smqp1[]" class="form-control" id="smqp1-oc-{{ $i }}" value="{{ old('smqp1.'.$i) }}" oninput="this.value = this.value.replace(/[^0-9.]/g, ''); validateQuota({{ $i }}, event)">
                </div>
                <div class="col-md-3 mt-3">
                    <label for="smqtb1-oc-{{ $i }}" class="form-label">Branch</label>
                    <select name="smqtb1[]" id="smqtb1-oc-{{ $i }}" class="form-control select2">
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
                    <select name="smqts1[]" id="smqts1-oc-{{ $i }}" class="form-control select2">
                        <option value="" disabled {{ old('smqts1.' .$i) ? '' : 'selected' }}>Silahkan Pilih Sales Rep</option>
                    </select>
                </div>

                <div class="col-md-3 mt-3">
                    <h5 style="margin-top: 35px">Quota 2 :</h5>
                </div>
                <div class="col-md-3 mt-3">
                    <label for="smqp2-oc-{{ $i }}" class="form-label">Split (%)</label>
                    <input type="number" name="smqp2[]" class="form-control" id="smqp2-oc-{{ $i }}" value="{{ old('smqp2.'.$i) }}" oninput="this.value = this.value.replace(/[^0-9.]/g, ''); validateQuota({{ $i }}, event)">
                </div>
                <div class="col-md-3 mt-3">
                    <label for="smqtb2-oc-{{ $i }}" class="form-label">Branch</label>
                    <select name="smqtb2[]" id="smqtb2-oc-{{ $i }}" class="form-control select2">
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
                    <select name="smqts2[]" id="smqts2-oc-{{ $i }}" class="form-control select2">
                        <option value="" disabled {{ old('smqts2.' .$i) ? '' : 'selected' }}>Silahkan Pilih Sales Rep</option>
                    </select>
                </div>

                <div class="col-md-3 mt-3">
                    <h5 style="margin-top: 35px">Quota 3 :</h5>
                </div>
                <div class="col-md-3 mt-3">
                    <label for="smqp3-oc-{{ $i }}" class="form-label">Split (%)</label>
                    <input type="number" name="smqp3[]" class="form-control" id="smqp3-oc-{{ $i }}" value="{{ old('smqp3.'.$i) }}" oninput="this.value = this.value.replace(/[^0-9.]/g, ''); validateQuota({{ $i }}, event)">
                </div>
                <div class="col-md-3 mt-3">
                    <label for="smqtb3-oc-{{ $i }}" class="form-label">Branch</label>
                    <select name="smqtb3[]" id="smqtb3-oc-{{ $i }}" class="form-control select2">
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
                    <select name="smqts3[]" id="smqts3-oc-{{ $i }}" class="form-control select2">
                        <option value="" disabled {{ old('smqts3.' .$i) ? '' : 'selected' }}>Silahkan Pilih Sales Rep</option>
                    </select>
                </div>

                <div class="col-md-3 mt-3">
                    <h5 style="margin-top: 35px">Quota 4 :</h5>
                </div>
                <div class="col-md-3 mt-3">
                    <label for="smqp4-oc-{{ $i }}" class="form-label">Split (%)</label>
                    <input type="number" name="smqp4[]" class="form-control" id="smqp4-oc-{{ $i }}" value="{{ old('smqp4.'.$i) }}" oninput="this.value = this.value.replace(/[^0-9.]/g, ''); validateQuota({{ $i }}, event)">
                </div>
                <div class="col-md-3 mt-3">
                    <label for="smqtb4-oc-{{ $i }}" class="form-label">Branch</label>
                    <select name="smqtb4[]" id="smqtb4-oc-{{ $i }}" class="form-control select2">
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
                    <select name="smqts4[]" id="smqts4-oc-{{ $i }}" class="form-control select2">
                        <option value="" disabled {{ old('smqts4.' .$i) ? '' : 'selected' }}>Silahkan Pilih Sales Rep</option>
                    </select>
                </div>

                <div class="col-md-3 mt-3">
                    <h5 style="margin-top: 35px">Quota 5 :</h5>
                </div>
                <div class="col-md-3 mt-3">
                    <label for="smqp5-oc-{{ $i }}" class="form-label">Split (%)</label>
                    <input type="number" name="smqp5[]" class="form-control" id="smqp5-oc-{{ $i }}" value="{{ old('smqp5.'.$i) }}" oninput="this.value = this.value.replace(/[^0-9.]/g, ''); validateQuota({{ $i }}, event)">
                </div>
                <div class="col-md-3 mt-3">
                    <label for="smqtb5-oc-{{ $i }}" class="form-label">Branch</label>
                    <select name="smqtb5[]" id="smqtb5-oc-{{ $i }}" class="form-control select2">
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
                    <select name="smqts5[]" id="smqts5-oc-{{ $i }}" class="form-control select2">
                        <option value="" disabled {{ old('smqts5.' .$i) ? '' : 'selected' }}>Silahkan Pilih Sales Rep</option>
                    </select>
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
        $(`#edisa_raw_oc_${phaseIndex}`).val(edisaMaster * ratio);

        // Set Value ke form detail invoicing 
        $(`#gross_display_oc_${phaseIndex}`).trigger('input');
        $(`#odisa_display_oc_${phaseIndex}`).trigger('input');
        $(`#ntamt_display_oc_${phaseIndex}`).trigger('input');
        $(`#blamt_display_oc_${phaseIndex}`).trigger('input');
        $(`#edisa_display_oc_${phaseIndex}`).trigger('input');

        // kalau pakai formatter
        initPriceFormatter(document.getElementById(`accordion-oc-invoicing-${phaseIndex}`));
    }
</script>

{{-- validate quota --}}
<script>
    function validateQuota(phaseIndex, event) {

        let total = 0;

        for (let i = 1; i <= 5; i++) {

            $(`#smqp${i}-oc-${phaseIndex}`).prop('disabled', false);

            $(`#smqtb${i}-oc-${phaseIndex}`)
                .prop('disabled', false);

            $(`#smqts${i}-oc-${phaseIndex}`)
                .prop('disabled', false);
        }

        for (let i = 1; i <= 5; i++) {

            let splitInput = $(`#smqp${i}-oc-${phaseIndex}`);
            let branchInput = $(`#smqtb${i}-oc-${phaseIndex}`);
            let salesInput  = $(`#smqts${i}-oc-${phaseIndex}`);

            let val = parseFloat(splitInput.val()) || 0;

            total += val;

            if (!val) {
                branchInput.val('').prop('disabled', true).trigger('change');
                salesInput.val('').prop('disabled', true).trigger('change');
            }

            if (total >= 100) {

                for (let j = i + 1; j <= 5; j++) {

                    $(`#smqp${j}-oc-${phaseIndex}`)
                        .val('')
                        .prop('disabled', true);

                    $(`#smqtb${j}-oc-${phaseIndex}`)
                        .val('')
                        .prop('disabled', true)
                        .trigger('change');

                    $(`#smqts${j}-oc-${phaseIndex}`)
                        .val('')
                        .prop('disabled', true)
                        .trigger('change');
                }

                break;
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

            validateQuota(phaseIndex);
            return;
        }
    }
</script>

{{-- get sales per branch invoicing --}}
<script>
    $(document).on('change', '[id^="smqtb"]', function () {

        const id = $(this).attr('id'); 

        const parts = id.split('-');
        const quotaPart = parts[0];
        const phaseIndex = parts[2];

        const quotaNumber = quotaPart.replace('smqtb','');

        const branchCode = $(this).val();

        const salesSelect = $(`#smqts${quotaNumber}-oc-${phaseIndex}`);

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

                salesSelect.html(options);

                const oldSalesAll = {
                    1: @json(old('smqts1', [])),
                    2: @json(old('smqts2', [])),
                    3: @json(old('smqts3', [])),
                    4: @json(old('smqts4', [])),
                    5: @json(old('smqts5', []))
                };

                const oldValue = oldSalesAll[quotaNumber]?.[phaseIndex];

                if(oldValue){
                    salesSelect.val(oldValue);
                }

                salesSelect.trigger('change');
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
                            <input type="number" name="smqp1[]" class="form-control" id="smqp1-oc-${i}" value="{{ old('smqp1.'.$i) }}" oninput="this.value = this.value.replace(/[^0-9.]/g, ''); validateQuota(${i}, event) validateQuota({{ $i }}, event)">
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
                            <input type="number" name="smqp2[]" class="form-control" id="smqp2-oc-${i}" value="{{ old('smqp2.'.$i) }}" oninput="this.value = this.value.replace(/[^0-9.]/g, ''); validateQuota({{ $i }}, event)">
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
                            <input type="number" name="smqp3[]" class="form-control" id="smqp3-oc-${i}" value="{{ old('smqp3.'.$i) }}" oninput="this.value = this.value.replace(/[^0-9.]/g, ''); validateQuota({{ $i }}, event)">
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
                            <input type="number" name="smqp4[]" class="form-control" id="smqp4-oc-${i}" value="{{ old('smqp4.'.$i) }}" oninput="this.value = this.value.replace(/[^0-9.]/g, ''); validateQuota({{ $i }}, event)">
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
                            <input type="number" name="smqp5[]" class="form-control" id="smqp5-oc-${i}" value="{{ old('smqp5.'.$i) }}" oninput="this.value = this.value.replace(/[^0-9.]/g, ''); validateQuota({{ $i }}, event)">
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

        toggleAddButton();

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
@endpush

