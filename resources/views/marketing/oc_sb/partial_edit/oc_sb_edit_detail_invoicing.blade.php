{{-- Detail Invoicing --}}
<div class="row mt-4">
    <h4 class="my-2">OC Invoicing (Edit)</h4>

    <div class="accordion" id="accordionOCInvoicing">
        @foreach ($detailsInvoicing as $i => $dinv)
        <div class="accordion-item" id="accordion-oc-invoicing-{{ $i }}">
            <h2 class="accordion-header d-flex justify-content-between align-items-center">
                <button class="accordion-button {{ $i > 0 ? 'collapsed' : '' }}" type="button" data-bs-toggle="collapse" data-bs-target="#details-oc-invoicing-{{ $i }}">
                    <span class="accordion-title">
                        Payment Phase : {{ $dinv->phase }}
                    </span>
                </button>

                @if($i > 0)
                <button type="button" class="btn btn-sm btn-danger mx-2" onclick="removeOCInvoicing({{ $i }})">
                    <i class="bi bi-trash-fill"></i>
                </button>
                @endif
            </h2>

            <div id="details-oc-invoicing-{{ $i }}" class="accordion-collapse collapse {{ $i == 0 ? 'show' : '' }}" data-bs-parent="#accordionOCInvoicing">
                <div class="accordion-body">
                    <div class="row">
                        <input type="hidden" name="dinv_id[]" value="{{ $dinv->id }}">

                        <div class="col-md-6 mt-3">
                            <label class="form-label">Deskripsi Termin</label><span class="text-danger">*</span>
                            <input type="text" class="form-control" name="descr[]" id="descr_oc_{{ $i }}" value="{{ $dinv->descr }}" required>
                        </div>

                        <div class="col-md-6 mt-3">
                            <label class="form-label">Term Percentage (%)</label><span class="text-danger">*</span>
                            <input type="number" class="form-control" name="toppc[]" id="toppc_oc_{{ $i }}" value="{{ $dinv->toppc }}" oninput="this.value=this.value.replace(/[^0-9]/g,''); validateTermPercentage(this)" required>
                        </div>

                        <div class="col-md-6 mt-3">
                            <label class="form-label">Gross Amount</label>
                            <input type="text" class="form-control price-input" id="gross_display_oc_{{ $i }}" data-raw-target="gross_raw_oc_{{ $i }}" readonly style="background-color:#e9ecef">

                            <input type="hidden" name="gross[]" id="gross_raw_oc_{{ $i }}" value="{{ $dinv->gross }}">
                        </div>

                        <div class="col-md-6 mt-3">
                            <label class="form-label">Official Discount</label>
                            <input type="text" class="form-control price-input" id="odisa_display_oc_{{ $i }}" data-raw-target="odisa_raw_oc_{{ $i }}" readonly style="background-color:#e9ecef">

                            <input type="hidden" name="odisa[]" id="odisa_raw_oc_{{ $i }}" value="{{ $dinv->odisa }}">
                        </div>

                        <div class="col-md-6 mt-3">
                            <label class="form-label">Net Amount</label>
                            <input type="text" class="form-control price-input" id="ntamt_display_oc_{{ $i }}" data-raw-target="ntamt_raw_oc_{{ $i }}" readonly style="background-color:#e9ecef">

                            <input type="hidden" name="ntamt[]" id="ntamt_raw_oc_{{ $i }}" value="{{ $dinv->ntamt }}">
                        </div>

                        <div class="col-md-6 mt-3">
                            <label class="form-label">Billing Amount</label>
                            <input type="text" class="form-control price-input" id="blamt_display_oc_{{ $i }}" data-raw-target="blamt_raw_oc_{{ $i }}" readonly style="background-color:#e9ecef">

                            <input type="hidden" name="blamt[]" id="blamt_raw_oc_{{ $i }}" value="{{ $dinv->blamt }}">
                        </div>

                        <div class="col-md-6 mt-3">
                            <label class="form-label">Extra Discount</label>
                            <input type="text" class="form-control price-input" id="ebamt_display_oc_{{ $i }}" data-raw-target="ebamt_raw_oc_{{ $i }}" readonly style="background-color:#e9ecef">

                            <input type="hidden" name="ebamt[]" id="ebamt_raw_oc_{{ $i }}" value="{{ $dinv->ebamt }}">
                        </div>

                        <div class="col-md-6 mt-3">
                            <label class="form-label">Plan Invoicing</label><span class="text-danger">*</span>
                            <input type="date" class="form-control" name="billd[]" value="{{ $dinv->billd }}" required>
                        </div>

                        <div class="col-md-12 mt-3">
                            <label class="form-label">Notes</label>
                            <textarea class="form-control" name="noted_invoicing[]" maxlength="200">{{ $dinv->noted }}</textarea>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endforeach

    </div>

    <div class="text-end">
        <button type="button" id="btn-add-phase" class="btn mt-3" style="background-color:#4456f1;color:#fff" onclick="addOCInvoicing()">
            Tambah Detail Invoicing
        </button>
    </div>
</div>

@push('scripts')
        {{-- script invoicing --}}
    <script>
        $(document).ready(function () {
            validateQuota();

            $('[id^="smqtb"]').each(function(){
                if($(this).val()){
                    $(this).trigger('change', [true]);
                }
            });
        });

        // {{-- hitungan term --}}
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
        }

        // {{-- hitungan otomatis untuk gross, official discount, net amt, bill amt, extradics --}}
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

        // {{-- validate quota --}}
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

            if (total === 100) {
                $('#btn-save').prop('disabled', false);
            } else {
                $('#btn-save').prop('disabled', true);
            }
        }

        // {{-- get sales per branch invoicing --}}
        $(document).on('change', '[id^="smqtb"]', function () {

            const id = $(this).attr('id'); 
            const quotaNumber = id.match(/\d+/)[0];
            const branchCode = $(this).val();
            const salesSelect = $(`#smqts${quotaNumber}-oc`);
            const currentSales = salesSelect.val();

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

                        const selected = currentSales == item.sreno ? 'selected' : '';

                        options += `<option value="${item.sreno}" ${selected}>
                                        ${item.sreno} - ${item.srena}
                                    </option>`;
                    });

                    salesSelect.html(options).trigger('change');
                }
            });
        });
    </script>
@endpush