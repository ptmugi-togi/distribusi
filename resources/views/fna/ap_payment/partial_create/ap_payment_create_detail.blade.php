<div class="row">
    <h4 class="my-2">AP Payment Detail</h4>
    <div class="accordion" id="accordionApPayment">
        @foreach (old('invno', [null]) as $i => $oldInv)
            <div class="accordion-item" id="accordion-ap-item-{{ $i }}">
                <h2 class="accordion-header d-flex justify-content-between align-items-center" id="heading-ap-{{ $i }}">
                    <button class="accordion-button {{ $i > 0 ? 'collapsed' : '' }}" type="button"
                        data-bs-toggle="collapse" data-bs-target="#details-ap-{{ $i }}"
                        aria-expanded="{{ $i == 0 ? 'true' : 'false' }}" aria-controls="details-ap-{{ $i }}">
                        <span class="accordion-title">{{ $oldInv ? 'Invoice: ' . $oldInv : 'Detail Invoice #' . ($i + 1) }}</span>
                    </button>
                    @if($i > 0)
                        <button type="button" class="btn btn-sm btn-danger mx-2" onclick="removeApDetail({{ $i }})">
                            <i class="bi bi-trash-fill"></i>
                        </button>
                    @endif
                </h2>

                <div id="details-ap-{{ $i }}" class="accordion-collapse collapse {{ $i == 0 ? 'show' : '' }}"
                    aria-labelledby="heading-ap-{{ $i }}" data-bs-parent="#accordionApPayment">
                    <div class="accordion-body">
                        <div class="row">
                            <div class="col-md-6 mt-3">
                                <label class="form-label">Invoice</label><span class="text-danger"> *</span>
                                <select class="select2 form-control invoice-select" name="invno[]" id="invno-{{ $i }}" required>
                                    <option value="" disabled selected>Select Supplier First</option>
                                </select>
                            </div>

                            <div class="col-md-6 mt-3">
                                <label class="form-label">Invoice Currency</label>
                                <input type="text" class="form-control curco-inv" name="curco_inv[]" readonly style="background-color:#e9ecef">
                            </div>

                            <div class="col-md-6 mt-3">
                                <label class="form-label">Rate Invoice</label>
                                <input type="text" class="form-control invrate-display" readonly style="background-color:#e9ecef">
                                <input type="hidden" class="invrate" name="invrate[]">
                            </div>

                            <div class="col-md-6 mt-3">
                                <label class="form-label">Payment Rate</label>
                                <input type="text" class="form-control payrate-display" readonly style="background-color:#e9ecef">
                                <input type="hidden" class="payrate" name="payrate[]">
                            </div>

                            <div class="col-md-6 mt-3">
                                <label class="form-label">Total Amount Invoice</label>
                                <input type="text" class="form-control inv-amount-display" readonly style="background-color:#e9ecef">
                                <input type="hidden" class="inv-amount" name="inv_amount[]">
                            </div>

                            <div class="col-md-6 mt-3">
                                <label class="form-label">Outstanding Amount</label>
                                <input type="text" class="form-control outstanding-amount-display" readonly style="background-color:#e9ecef">
                                <input type="hidden" class="outstanding-amount">
                            </div>

                            <div class="col-md-6 mt-3">
                                <label class="form-label">Total Payment</label><span class="text-danger"> *</span>
                                <input type="text" class="form-control pay-amount-display" required>
                                <input type="hidden" class="pay-amount" name="pay_amount[]">
                            </div>

                            <div class="col-md-6 mt-3">
                                <label class="form-label">Payment (IDR)</label>
                                <input type="text" class="form-control pay-idr-display" readonly style="background-color:#e9ecef;">
                                <input type="hidden" class="pay-idr" name="pay_idr[]">
                            </div>

                            <div class="col-md-12 mt-3">
                                <label class="form-label">Notes</label>
                                <textarea class="form-control" name="noted[]" maxlength="200">{{ old('noted.'.$i) }}</textarea>
                                <div class="form-text text-danger text-end" style="font-size:0.7rem;">Maksimal 200 karakter</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <div class="text-end">
        <button type="button" class="btn mt-3" style="background-color:#4456f1;color:#fff" onclick="addApDetail()">
            Tambah Detail
        </button>
    </div>
</div>

@push('scripts')
    <script>
        $('#supno').on('change', function () {
            const supno = ($(this).val() || '').trim();
            if (!supno) return;

            $.ajax({
                url: `/ap-payment/get-invoices/${encodeURIComponent(supno)}`,
                method: 'GET',
                success: function (res) {
                    if (res.success) {
                        window.supplierInvoices = res.data || [];
                        populateAllInvoiceDropdowns();
                    }
                },
                error: function () {
                    alert('Gagal memuat daftar invoice untuk supplier ini.');
                }
            });
        });

        function populateAllInvoiceDropdowns(targetSelect = null) {
            const selects = targetSelect ? $(targetSelect) : $('select.invoice-select');
            const invoices = window.supplierInvoices || [];

            selects.each(function () {
                const $sel = $(this);
                $sel.empty();

                // Saring invoice yang sudah dipilih di baris accordion lain agar tidak duplikat
                const alreadyChosen = [];
                $('select.invoice-select').not($sel).each(function () {
                    const val = $(this).val();
                    if (val) alreadyChosen.push(String(val).trim());
                });

                const availableInvoices = invoices.filter(inv => !alreadyChosen.includes(String(inv.invno).trim()));

                if (availableInvoices.length === 0) {
                    $sel.append('<option value="" disabled selected>No invoice found for this supplier</option>');
                    $sel.prop('disabled', true);
                } else {
                    $sel.prop('disabled', false);
                    $sel.append('<option value="" disabled selected>Select Invoice</option>');

                    availableInvoices.forEach(inv => {
                        const invNoStr = String(inv.invno).trim();
                        const label = `${invNoStr} (${inv.curco_inv} - ${formatMoney(parseFloat(inv.total_amount), inv.curco_inv)})`;
                        const option = new Option(label, invNoStr, false, false);
                        $(option).data('invoice', inv);
                        $sel.append(option);
                    });
                }

                resetDetailRow($sel.closest('.accordion-body'));
                $sel.trigger('change.select2');
            });

            calculateHeaderTotal();
        }

        $(document).on('change', 'select.invoice-select', function () {
            const $sel = $(this);
            const $body = $sel.closest('.accordion-body');
            const $item = $sel.closest('.accordion-item');
            const invData = $sel.find('option:selected').data('invoice');

            if (!invData) return;

            const curcoInv    = invData.curco_inv || 'IDR';
            const invRate     = parseFloat(invData.invrate) || 1;
            const payRate     = parseFloat(invData.payrate) || 1;
            const invAmount   = parseFloat(invData.total_amount) || 0;
            const paidAmount  = parseFloat(invData.payment) || 0;
            const outstanding = (invData.sisa_bayar !== undefined && invData.sisa_bayar !== null)
                ? parseFloat(invData.sisa_bayar)
                : Math.max(0, invAmount - paidAmount);

            $body.find('.curco-inv').val(curcoInv);

            $body.find('.invrate').val(invRate);
            $body.find('.invrate-display').val(formatIDR(invRate));

            $body.find('.payrate').val(payRate);
            $body.find('.payrate-display').val(formatIDR(payRate));

            $body.find('.inv-amount').val(invAmount);
            $body.find('.inv-amount-display').val(formatMoney(invAmount, curcoInv));

            // Isi raw & display Outstanding Amount
            $body.find('.outstanding-amount').val(outstanding);
            $body.find('.outstanding-amount-display').val(formatMoney(outstanding, curcoInv));

            // Default Total Payment = sisa tagihan (outstanding)
            $body.find('.pay-amount').val(outstanding);
            $body.find('.pay-amount-display').val(formatMoney(outstanding, curcoInv));

            $item.find('.accordion-title').text(`Invoice: ${invData.invno} (${curcoInv})`);

            calculateRowIDR($body);
        });

        $(document).on('focus', '.pay-amount-display', function () {
            const $body = $(this).closest('.accordion-body');
            const rawVal = $body.find('.pay-amount').val();
            if (rawVal !== '' && !isNaN(parseFloat(rawVal))) {
                $(this).val(rawVal);
            }
        });

        $(document).on('input', '.pay-amount-display', function () {
            const $body = $(this).closest('.accordion-body');
            let cleaned = $(this).val().replace(/[^0-9.,]/g, '');
            $(this).val(cleaned);

            let numericVal = parseFloat(cleaned.replace(/,/g, '.')) || 0;
            $body.find('.pay-amount').val(numericVal);

            calculateRowIDR($body);
        });

        $(document).on('blur', '.pay-amount-display', function () {
            const $input      = $(this);
            const $body       = $input.closest('.accordion-body');
            const curcoInv    = $body.find('.curco-inv').val() || 'IDR';
            const invno       = $body.find('select.invoice-select').val();
            const outstanding = parseFloat($body.find('.outstanding-amount').val()) || 0;
            const rawVal      = parseFloat($body.find('.pay-amount').val()) || 0;

            if (invno && rawVal > outstanding) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Melebihi Outstanding!',
                    html: `Total Payment untuk invoice <b>${invno}</b> (${formatMoney(rawVal, curcoInv)}) tidak boleh melebihi Outstanding Amount (${formatMoney(outstanding, curcoInv)}).`,
                    confirmButtonText: 'OK',
                    confirmButtonColor: '#3085d6'
                }).then(() => {
                    $body.find('.pay-amount').val(outstanding);
                    $input.val(formatMoney(outstanding, curcoInv));
                    calculateRowIDR($body);
                    $input.focus();
                });
                return;
            }

            if (!isNaN(rawVal) && rawVal > 0) {
                $input.val(formatMoney(rawVal, curcoInv));
            } else {
                $body.find('.pay-amount').val(0);
                $input.val(formatMoney(0, curcoInv));
            }
            calculateRowIDR($body);
        });

        function calculateRowIDR($body) {
            const curcoInv  = $body.find('.curco-inv').val();
            const payRate   = parseFloat($body.find('.payrate').val()) || 1;
            const payAmount = parseFloat($body.find('.pay-amount').val()) || 0;

            let idrValue = (curcoInv === 'IDR') ? payAmount : (payAmount * payRate);

            $body.find('.pay-idr').val(idrValue.toFixed(2));
            $body.find('.pay-idr-display').val(formatIDR(idrValue));

            calculateHeaderTotal();
        }

        function calculateHeaderTotal() {
            let grandTotalInvoiceIDR = 0;
            let grandTotalPayIDR = 0;

            $('#accordionApPayment .accordion-item').each(function () {
                const $body     = $(this).find('.accordion-body');
                const curcoInv  = $body.find('.curco-inv').val();
                const invAmount = parseFloat($body.find('.inv-amount').val()) || 0;
                const invRate   = parseFloat($body.find('.invrate').val()) || 1;
                const payIDR    = parseFloat($body.find('.pay-idr').val()) || 0;

                if (invAmount > 0) {
                    grandTotalInvoiceIDR += (curcoInv === 'IDR') ? invAmount : (invAmount * invRate);
                }

                if (payIDR > 0) {
                    grandTotalPayIDR += payIDR;
                }
            });

            $('#tpayment_raw').val(grandTotalInvoiceIDR.toFixed(2));
            $('#tpayment_display').val(formatIDR(grandTotalInvoiceIDR));

            $('#tpayment_detail_raw').val(grandTotalPayIDR.toFixed(2));
            $('#tpayment_detail_display').val(formatIDR(grandTotalPayIDR));
        }

        function resetDetailRow($body) {
            $body.find('.curco-inv, .invrate, .invrate-display, .payrate, .payrate-display, .inv-amount, .inv-amount-display, .outstanding-amount, .outstanding-amount-display, .pay-amount, .pay-amount-display, .pay-idr, .pay-idr-display').val('');
            $body.closest('.accordion-item').find('.accordion-title').text('Detail Invoice');
        }

        window.addApDetail = function () {
            if (!$('#supno').val()) {
                alert('Select Supplier first!');
                return;
            }

            if (!window.supplierInvoices || window.supplierInvoices.length === 0) {
                alert('No invoice found for this supplier!');
                return;
            }

            const i = Date.now();
            const rowNum = $('#accordionApPayment .accordion-item').length + 1;
            const html = `
            <div class="accordion-item" id="accordion-ap-item-${i}">
                <h2 class="accordion-header d-flex justify-content-between align-items-center" id="heading-ap-${i}">
                    <button class="accordion-button collapsed" type="button"
                        data-bs-toggle="collapse" data-bs-target="#details-ap-${i}"
                        aria-expanded="false" aria-controls="details-ap-${i}">
                        <span class="accordion-title">Detail Invoice #${rowNum}</span>
                    </button>
                    <button type="button" class="btn btn-sm btn-danger mx-2" onclick="removeApDetail(${i})">
                        <i class="bi bi-trash-fill"></i>
                    </button>
                </h2>

                <div id="details-ap-${i}" class="accordion-collapse collapse"
                    aria-labelledby="heading-ap-${i}" data-bs-parent="#accordionApPayment">
                    <div class="accordion-body">
                        <div class="row">
                            <div class="col-md-6 mt-3">
                                <label class="form-label">Invoice</label><span class="text-danger"> *</span>
                                <select class="select2 form-control invoice-select" name="invno[]" id="invno-${i}" required>
                                    <option value="" disabled selected>Select Invoice</option>
                                </select>
                            </div>

                            <div class="col-md-6 mt-3">
                                <label class="form-label">Invoice Currency</label>
                                <input type="text" class="form-control curco-inv" name="curco_inv[]" readonly style="background-color:#e9ecef">
                            </div>

                            <div class="col-md-6 mt-3">
                                <label class="form-label">Rate Invoice</label>
                                <input type="text" class="form-control invrate-display" readonly style="background-color:#e9ecef">
                                <input type="hidden" class="invrate" name="invrate[]">
                            </div>

                            <div class="col-md-6 mt-3">
                                <label class="form-label">Payment Rate</label>
                                <input type="text" class="form-control payrate-display" readonly style="background-color:#e9ecef">
                                <input type="hidden" class="payrate" name="payrate[]">
                            </div>

                            <div class="col-md-6 mt-3">
                                <label class="form-label">Total Amount Invoice</label>
                                <input type="text" class="form-control inv-amount-display" readonly style="background-color:#e9ecef">
                                <input type="hidden" class="inv-amount" name="inv_amount[]">
                            </div>

                            <div class="col-md-6 mt-3">
                                <label class="form-label">Outstanding Amount</label>
                                <input type="text" class="form-control outstanding-amount-display" readonly style="background-color:#e9ecef">
                                <input type="hidden" class="outstanding-amount">
                            </div>

                            <div class="col-md-6 mt-3">
                                <label class="form-label">Total Payment</label><span class="text-danger"> *</span>
                                <input type="text" class="form-control pay-amount-display" required>
                                <input type="hidden" class="pay-amount" name="pay_amount[]">
                            </div>

                            <div class="col-md-6 mt-3">
                                <label class="form-label">Payment (IDR)</label>
                                <input type="text" class="form-control pay-idr-display" readonly style="background-color:#e9ecef;">
                                <input type="hidden" class="pay-idr" name="pay_idr[]">
                            </div>

                            <div class="col-md-12 mt-3">
                                <label class="form-label">Notes</label>
                                <textarea class="form-control" name="noted[]" maxlength="200"></textarea>
                                <div class="form-text text-danger text-end" style="font-size:0.7rem;">Maksimal 200 karakter</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>`;

            $('#accordionApPayment').append(html);

            const $newSelect = $(`#invno-${i}`);
            $newSelect.select2({ width: '100%', theme: 'bootstrap-5' });

            populateAllInvoiceDropdowns($newSelect);

            setTimeout(() => {
                $(`#details-ap-${i}`).collapse('show');
            }, 100);
        };

        window.removeApDetail = function (i) {
            $(`#accordion-ap-item-${i}`).remove();
            calculateHeaderTotal();
        };
    </script>
@endpush