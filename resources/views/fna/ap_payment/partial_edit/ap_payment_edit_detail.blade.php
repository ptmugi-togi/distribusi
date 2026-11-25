<div class="row">
    <h4 class="my-2">AP Payment Detail</h4>
    <div class="accordion" id="accordionApPayment">
        @foreach ($header->details as $i => $dtl)
            @php
                $invnoTrim  = trim($dtl->invno);
                $invMaster  = $invoicesMap[$invnoTrim] ?? null;
                $curcoInv   = trim($dtl->curco ?? ($invMaster->curco ?? 'IDR'));
                $invAmount  = $invMaster ? (float) $invMaster->amount : (float) $dtl->payval;
                $invRate    = (float) $dtl->invrate;
                $payRate    = (float) $dtl->payrate;
                $payVal     = (float) $dtl->payval;
                $payIdr     = (float) $dtl->totalidr;
            @endphp

            <div class="accordion-item" id="accordion-ap-item-{{ $i }}">
                <h2 class="accordion-header d-flex justify-content-between align-items-center" id="heading-ap-{{ $i }}">
                    <button class="accordion-button {{ $i > 0 ? 'collapsed' : '' }}" type="button"
                        data-bs-toggle="collapse" data-bs-target="#details-ap-{{ $i }}"
                        aria-expanded="{{ $i == 0 ? 'true' : 'false' }}" aria-controls="details-ap-{{ $i }}">
                        <span class="accordion-title">Invoice: {{ $invnoTrim }} ({{ $curcoInv }})</span>
                    </button>
                    <button type="button" class="btn btn-sm btn-danger mx-2" onclick="removeApDetail({{ $i }})">
                        <i class="bi bi-trash-fill"></i>
                    </button>
                </h2>

                <div id="details-ap-{{ $i }}" class="accordion-collapse collapse {{ $i == 0 ? 'show' : '' }}"
                    aria-labelledby="heading-ap-{{ $i }}" data-bs-parent="#accordionApPayment">
                    <div class="accordion-body">
                        <div class="row">
                            <div class="col-md-6 mt-3">
                                <label class="form-label">Invoice</label><span class="text-danger"> *</span>
                                <select class="select2 form-control invoice-select" name="invno[]" id="invno-{{ $i }}"
                                    data-selected-invno="{{ $invnoTrim }}"
                                    data-existing-curco="{{ $curcoInv }}"
                                    data-existing-invrate="{{ $invRate }}"
                                    data-existing-payrate="{{ $payRate }}"
                                    data-existing-invamount="{{ $invAmount }}"
                                    data-existing-payval="{{ $payVal }}"
                                    required>
                                    <option value="{{ $invnoTrim }}" selected>
                                        {{ $invnoTrim }} ({{ $curcoInv }} - {{ $curcoInv === 'IDR' ? 'Rp ' . number_format($invAmount, 2, ',', '.') : '$' . number_format($invAmount, 2, '.', ',') }})
                                    </option>
                                </select>
                            </div>

                            <div class="col-md-6 mt-3">
                                <label class="form-label">Invoice Currency</label>
                                <input type="text" class="form-control curco-inv" name="curco_inv[]"
                                    value="{{ $curcoInv }}" readonly style="background-color:#e9ecef">
                            </div>

                            <div class="col-md-6 mt-3">
                                <label class="form-label">Rate Invoice</label>
                                <input type="text" class="form-control invrate-display"
                                    value="{{ 'Rp ' . number_format($invRate, 2, ',', '.') }}" readonly style="background-color:#e9ecef">
                                <input type="hidden" class="invrate" name="invrate[]" value="{{ $invRate }}">
                            </div>

                            <div class="col-md-6 mt-3">
                                <label class="form-label">Payment Rate</label>
                                <input type="text" class="form-control payrate-display"
                                    value="{{ 'Rp ' . number_format($payRate, 2, ',', '.') }}" readonly style="background-color:#e9ecef">
                                <input type="hidden" class="payrate" name="payrate[]" value="{{ $payRate }}">
                            </div>

                            <div class="col-md-4 mt-3">
                                <label class="form-label">Total Amount Invoice</label>
                                <input type="text" class="form-control inv-amount-display"
                                    value="{{ $curcoInv === 'IDR' ? 'Rp ' . number_format($invAmount, 2, ',', '.') : '$' . number_format($invAmount, 2, '.', ',') }}"
                                    readonly style="background-color:#e9ecef">
                                <input type="hidden" class="inv-amount" name="inv_amount[]" value="{{ $invAmount }}">
                            </div>

                            <div class="col-md-4 mt-3">
                                <label class="form-label">Total Payment</label><span class="text-danger"> *</span>
                                <input type="text" class="form-control pay-amount-display"
                                    value="{{ $curcoInv === 'IDR' ? 'Rp ' . number_format($payVal, 2, ',', '.') : '$' . number_format($payVal, 2, '.', ',') }}"
                                    required>
                                <input type="hidden" class="pay-amount" name="pay_amount[]" value="{{ $payVal }}">
                            </div>

                            <div class="col-md-4 mt-3">
                                <label class="form-label">Payment (IDR)</label>
                                <input type="text" class="form-control pay-idr-display"
                                    value="{{ 'Rp ' . number_format($payIdr, 2, ',', '.') }}" readonly style="background-color:#e9ecef;">
                                <input type="hidden" class="pay-idr" name="pay_idr[]" value="{{ $payIdr }}">
                            </div>

                            <div class="col-md-12 mt-3">
                                <label class="form-label">Notes</label>
                                <textarea class="form-control" name="noted[]" maxlength="200">{{ $dtl->noted }}</textarea>
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
            const supno = $(this).val();
            if (!supno) return;
            fetchSupplierInvoices(supno, false);
        });

        function fetchSupplierInvoices(supno, preserveExisting = false) {
            $.ajax({
                url: `/ap-payment/get-invoices/${supno}`,
                method: 'GET',
                success: function (res) {
                    if (res.success) {
                        window.supplierInvoices = res.data || [];
                        populateAllInvoiceDropdowns(null, preserveExisting);
                    }
                },
                error: function () {
                    alert('Gagal memuat daftar invoice untuk supplier ini.');
                }
            });
        }

        function populateAllInvoiceDropdowns(targetSelect = null, preserveExisting = false) {
            const selects = targetSelect ? $(targetSelect) : $('select.invoice-select');
            const invoices = window.supplierInvoices || [];

            selects.each(function () {
                const $sel = $(this);
                const selectedInvno = $sel.data('selected-invno') ? String($sel.data('selected-invno')).trim() : null;

                $sel.empty();

                // Gabungkan daftar invoice aktif dengan invoice lama yang tersimpan (jika invoice lama sudah lunas penuh)
                let combinedInvoices = [...invoices];
                if (preserveExisting && selectedInvno) {
                    const alreadyExists = combinedInvoices.some(inv => String(inv.invno).trim() === selectedInvno);
                    if (!alreadyExists) {
                        combinedInvoices.unshift({
                            invno: selectedInvno,
                            curco_inv: $sel.data('existing-curco') || 'IDR',
                            invrate: parseFloat($sel.data('existing-invrate')) || 1,
                            payrate: parseFloat($sel.data('existing-payrate')) || 1,
                            total_amount: parseFloat($sel.data('existing-invamount')) || 0,
                            sisa_bayar: parseFloat($sel.data('existing-payval')) || 0
                        });
                    }
                }

                if (combinedInvoices.length === 0) {
                    $sel.append('<option value="" disabled selected>No invoice found for this supplier</option>');
                    $sel.prop('disabled', true);
                } else {
                    $sel.prop('disabled', false);
                    $sel.append(`<option value="" disabled ${!preserveExisting ? 'selected' : ''}>Select Invoice</option>`);

                    const alreadyChosen = [];
                    $('select.invoice-select').not($sel).each(function () {
                        const val = $(this).val();
                        if (val) alreadyChosen.push(String(val).trim());
                    });

                    combinedInvoices
                        .filter(inv => preserveExisting || !alreadyChosen.includes(String(inv.invno).trim()))
                        .forEach(inv => {
                            const invNoStr = String(inv.invno).trim();
                            const isSelected = preserveExisting && (invNoStr === selectedInvno);
                            const label = `${invNoStr} (${inv.curco_inv} - ${formatMoney(parseFloat(inv.total_amount), inv.curco_inv)})`;
                            const option = new Option(label, invNoStr, isSelected, isSelected);
                            $(option).data('invoice', inv);
                            $sel.append(option);
                        });
                }

                if (!preserveExisting) {
                    resetDetailRow($sel.closest('.accordion-body'));
                }

                $sel.trigger('change.select2');
            });

            if (!preserveExisting) {
                calculateHeaderTotal();
            }
        }

        $(document).on('change', 'select.invoice-select', function () {
            const $sel = $(this);
            const $body = $sel.closest('.accordion-body');
            const $item = $sel.closest('.accordion-item');
            const invData = $sel.find('option:selected').data('invoice');

            if (!invData) return;

            const curcoInv   = invData.curco_inv || 'IDR';
            const invRate    = parseFloat(invData.invrate) || 1;
            const payRate    = parseFloat(invData.payrate) || 1;
            const invAmount  = parseFloat(invData.total_amount) || 0;
            const defaultPay = (invData.sisa_bayar !== undefined) ? parseFloat(invData.sisa_bayar) : invAmount;

            $body.find('.curco-inv').val(curcoInv);

            $body.find('.invrate').val(invRate);
            $body.find('.invrate-display').val(formatIDR(invRate));

            $body.find('.payrate').val(payRate);
            $body.find('.payrate-display').val(formatIDR(payRate));

            $body.find('.inv-amount').val(invAmount);
            $body.find('.inv-amount-display').val(formatMoney(invAmount, curcoInv));

            $body.find('.pay-amount').val(defaultPay);
            $body.find('.pay-amount-display').val(formatMoney(defaultPay, curcoInv));

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
            const $body = $(this).closest('.accordion-body');
            const curcoInv = $body.find('.curco-inv').val() || 'IDR';
            const rawVal = parseFloat($body.find('.pay-amount').val());

            if (!isNaN(rawVal) && rawVal > 0) {
                $(this).val(formatMoney(rawVal, curcoInv));
            } else {
                $body.find('.pay-amount').val(0);
                $(this).val(formatMoney(0, curcoInv));
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
            $body.find('.curco-inv, .invrate, .invrate-display, .payrate, .payrate-display, .inv-amount, .inv-amount-display, .pay-amount, .pay-amount-display, .pay-idr, .pay-idr-display').val('');
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

            const i = Date.now(); // Gunakan timestamp unik agar index id tidak bentrok saat ada baris yang dihapus
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

                            <div class="col-md-4 mt-3">
                                <label class="form-label">Total Amount Invoice</label>
                                <input type="text" class="form-control inv-amount-display" readonly style="background-color:#e9ecef">
                                <input type="hidden" class="inv-amount" name="inv_amount[]">
                            </div>

                            <div class="col-md-4 mt-3">
                                <label class="form-label">Total Payment</label><span class="text-danger"> *</span>
                                <input type="text" class="form-control pay-amount-display" required>
                                <input type="hidden" class="pay-amount" name="pay_amount[]">
                            </div>

                            <div class="col-md-4 mt-3">
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

            populateAllInvoiceDropdowns($newSelect, false);

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