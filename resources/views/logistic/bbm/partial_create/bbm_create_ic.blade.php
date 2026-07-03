{{-- IC (Return) --}}
<div class="row mt-4">
    <div class="col-md-6 mt-3">
        <label for="reference" class="form-label">DO No.<span class="text-danger"> *</span></label>
        <select class="form-control select2" name="refcno" id="refcno_ic">
            <option value="" disabled selected>Pilih DO</option>
        </select>
        <input type="hidden" name="reffc" id="reffc-store">
        <input type="hidden" name="refno" id="refno-store">
        <input type="hidden" name="rfc01" id="rfc01-store">
        <input type="hidden" name="ref01" id="ref01-store">
    </div>
    
    <div class="col-md-6 mt-3">
        <label for="do-warco" class="form-label">DO Warco</label>
        <input type="text" class="form-control" name="do-warco" id="do-warco" value="{{ old('do-warco') }}" readonly style="background-color:#e9ecef;">
    </div>
    
    <div class="col-md-6 mt-3">
        <label for="cust" class="form-label">Customer</label>
        <input type="text" class="form-control" name="cust" id="cust" value="{{ old('cust') }}" readonly style="background-color:#e9ecef;">
        <input type="hidden" class="form-control" name="cusno" id="cusno" value="{{ old('cusno') }}" readonly style="background-color:#e9ecef;">
    </div>

    <div class="col-md-12 mt-3">
        <label for="noteh_ic" class="form-label">Notes</label>
        <textarea class="form-control" name="noteh" id="noteh_ic" maxlength="200">{{ old('noteh') }}</textarea>
        <div class="form-text text-danger text-end" style="font-size:0.7rem;">Maksimal 200 karakter</div>
    </div>
</div>

<div class="mt-4" id="detail-container" style="display:none;">
    <h5>BBM Detail (IC)</h5>

    <div class="table-responsive">
        <table class="table table-striped table-bordered align-middle">
            <thead>
                <tr>
                    <th width="50">Pilih</th>
                    <th>Barang</th>
                    <th>Qty DO</th>
                    <th>Qty Return</th>
                    <th>Unit</th>
                    <th>Lot No</th>
                    <th>Location</th>
                    <th>Notes</th>
                </tr>
            </thead>
            <tbody id="detail-body"></tbody>
        </table>
    </div>
</div>

@push('scripts')
<script>
    // checkbox lotno
    $(document).on('change', '.nolot-checkbox', function(){
        let container   = $(this).closest('.row, .accordion-body'); 
        if($(this).is(':checked')){
            container.find('.lot-section').hide();
            container.find('.lotno-input').val('-'); // default supaya backend ga error
        }else{
            container.find('.lot-section').show();
            container.find('.lotno-input').val('');
        }
    });

    $(document).on('change', '#formc, #warco', function () {
        const formc = $('#formc').val();
        const warco = $('#warco').val();
        const $ref = $('#refcno_ic');

        // reset dulu
        $ref
            .prop('disabled', true)
            .html('<option disabled selected>Pilih Warehouse terlebih dahulu</option>');

        if (formc !== 'IC' || !warco) {
            return;
        }

        $ref.html('<option disabled selected>Loading...</option>');

        $.ajax({
            url: "{{ route('get.do.bbmIc') }}",
            type: "GET",
            data: { formc, warco },
            success(response) {
                $ref.empty();

                if (!Array.isArray(response) || response.length === 0) {
                    $ref.append('<option disabled selected>Tidak ada data DO</option>');
                    return;
                }

                $ref.append('<option disabled selected>Pilih DO</option>');

                response.forEach(item => {
                    $ref.append(`
                        <option value="${item.trano}"
                            data-reffc="${item.formc}"
                            data-refno="${item.trano}"
                            data-warco="${item.warco}"
                            data-cust="${item.cusno} - ${item.cusna}"
                            data-cusno="${item.cusno}"
                            data-rfc01="${item.rfc01}"
                            data-ref01="${item.ref01}">
                            ${item.formc} - ${item.trano}
                        </option>
                    `);
                });

                $ref.prop('disabled', false);
            },
            error() {
                $ref
                    .html('<option disabled selected>Gagal mengambil data</option>')
                    .prop('disabled', false);
            }
        });
    });

    // fucntion pilih barang (IN)
    function loadOpronByDo(trano, idx = null) {
        const warco = $('#warco').val();

        const $targets = idx !== null
            ? $(`#opron-ic-${idx}`)
            : $('.opron-ic');

        // guard clause: wajib lengkap
        if (!trano) {
            $targets
                .prop('disabled', true)
                .html('<option value="" disabled selected>Pilih DO terlebih dahulu</option>');
            return;
        }

        if (!warco) {
            $targets
                .prop('disabled', true)
                .html('<option value="" disabled selected>Pilih Warehouse terlebih dahulu</option>');
            return;
        }

        $targets
            .prop('disabled', true)
            .html('<option value="" disabled selected>Loading...</option>');

        $.ajax({
            url: "{{ route('get.opron.by.do.bbmIc') }}",
            type: "GET",
            data: {
                braco: $('#braco').val(),
                formc: 'DO',
                trano: $('#refno-store').val(),
                warco: warco
            },
            success(response) {
                const $body = $('#detail-body');
                $body.empty();

                if (!response.length) {
                    $('#detail-container').hide();
                    return;
                }

                response.forEach((item, i) => {
                    $body.append(`
                        <tr>
                            <td class="text-center">
                                <input
                                    type="checkbox"
                                    class="form-check-input item-check"
                                    data-row="${i}">
                            </td>

                            <td>
                                ${item.opron} - ${item.prona}
                                <input type="hidden" name="opron[]" value="${item.opron}" class="opron-${i}" disabled>
                                <input type="hidden" name="stdqt[]" value="${item.qunit}" class="stdqt-${i}" disabled>
                                <input type="hidden" name="lotno[]" value="${item.lotno}" class="lotno-${i}" disabled>
                                <input type="hidden" name="locco[]" value="${item.locco}" class="locco-${i}" disabled>
                                <input type="hidden" name="nolot[]" value="0" class="nolot-${i}" disabled>
                                <input type="hidden" name="invno[]" value="${$('#refcno_ic').val()}" class="invno-${i}" disabled>
                            </td>

                            <td>${item.trqty}</td>

                            <td width="150">
                                <input type="number" class="form-control qty-input" name="trqty[]" value="${item.trqty}" min="1" max="${item.trqty}" data-max="${item.trqty}" data-row="${i}" disabled>
                            </td>
                            
                            <td>${item.qunit}</td>
                            <td>${item.lotno ?? '-'}</td>
                            <td>${item.locco}</td>

                            <td>
                                <textarea class="form-control noted-${i}" name="noted[]" readonly style="background-color:#e9ecef;" maxlength="200" disabled>${item.noted ?? '-'}</textarea>
                            </td>
                        </tr>
                    `);
                });

                $('#detail-container').show();
            },
            error() {
                $targets
                    .html('<option value="" disabled selected>Gagal memuat data</option>')
                    .prop('disabled', false);
                }
            });
    }

    $(document).on('change', '.item-check', function () {
        const row = $(this).data('row');
        const checked = $(this).is(':checked');

        $(`.opron-${row}`).prop('disabled', !checked);
        $(`.stdqt-${row}`).prop('disabled', !checked);
        $(`.lotno-${row}`).prop('disabled', !checked);
        $(`.locco-${row}`).prop('disabled', !checked);
        $(`.nolot-${row}`).prop('disabled', !checked);
        $(`.stock-${row}`).prop('disabled', !checked);
        $(`.invno-${row}`).prop('disabled', !checked);
        $(`.noted-${row}`).prop('disabled', !checked);

        $(`.qty-input[data-row="${row}"]`)
            .prop('disabled', !checked);
            
        if (checked) {
            const $qty = $(`.qty-input[data-row="${row}"]`);

            $qty
                .prop('disabled', false)
                .focus()
                .select();
        }
    });

    $(document).on('input', '.qty-input', function () {
        let qty = parseFloat($(this).val()) || 0;
        const max = parseFloat($(this).data('max'));

        if (qty <= 0) {
            $(this).val(1);

            Swal.fire({
                icon: 'warning',
                title: 'Qty tidak valid',
                text: 'Qty minimal adalah 1.'
            });

            return;
        }

        if (qty > max) {
            $(this).val(max);

            Swal.fire({
                icon: 'warning',
                title: 'Qty melebihi stock',
                text: `Qty maksimal yang dapat direturn adalah ${max}.`
            });
        }
    });

    // function pilih barang (IN)
    $(document).on('change', '#refcno_ic, #warco', function () {
        if (this.id === 'refcno_ic') {
            const $selected = $('#refcno_ic').find(':selected');
            $('#reffc-store').val($selected.data('reffc'));
            $('#refno-store').val($selected.data('refno'));
            $('#rfc01-store').val($selected.data('rfc01'));
            $('#ref01-store').val($selected.data('ref01'));
            $('#do-warco').val($selected.data('warco'));
            $('#cusno').val($selected.data('cusno'));
            $('#cust').val($selected.data('cust'));
        }

        const trano = $('#refcno_ic').val();
        loadOpronByDo(trano);
    });

    // detail barang (IN)
    $(document).on('change', 'select.opron-ic', function(){
        const $opt = $(this).find(':selected');
        const idx = this.id.split('-').pop();
        const stdqt = $opt.data('stdqt');
        const trqty = $opt.data('qty');
        const lotno = $opt.data('lotno');
        const locco = $opt.data('locco');

        $(`#lotno-ic-${idx}`).val(lotno);
        $(`#locco-ic-${idx}`).val(locco);

        $(`#stdqt-ic-${idx}`).val(stdqt);
        $(`#trqty-ic-${idx}`).val(trqty);
        $(`#trqty-ic-${idx}`).next('.input-group-text').text(stdqt);
    });
</script>
@endpush
