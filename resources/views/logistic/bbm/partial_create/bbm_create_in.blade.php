{{-- IN (Return) --}}
<div class="row mt-4">
    <div class="col-md-6 mt-3">
        <label for="reference" class="form-label">DO No.<span class="text-danger"> *</span></label>
        <select class="form-control select2" name="refcno" id="refcno">
            <option value="" disabled selected>Pilih DO</option>
        </select>
        <input type="hidden" name="reffc" id="reffc-store">
        <input type="hidden" name="refno" id="refno-store">
        <input type="hidden" name="rfc01" id="rfc01-store">
        <input type="hidden" name="ref01" id="ref01-store">
    </div>
    
    <div class="col-md-6 mt-3">
        <label for="do_warco" class="form-label">DO Warco</label>
        <input type="text" class="form-control" name="do_warco" id="do_warco" value="{{ old('do_warco') }}" readonly style="background-color:#e9ecef;">
    </div>
    
    <div class="col-md-6 mt-3">
        <label for="cust" class="form-label">Customer</label>
        <input type="text" class="form-control" name="cust" id="cust" value="{{ old('cust') }}" readonly style="background-color:#e9ecef;">
        <input type="hidden" class="form-control" name="cusno" id="cusno" value="{{ old('cusno') }}" readonly style="background-color:#e9ecef;">
    </div>

    <div class="col-md-12 mt-3">
        <label for="noteh_in" class="form-label">Notes</label>
        <textarea class="form-control" name="noteh" id="noteh_in" maxlength="200">{{ old('noteh') }}</textarea>
        <div class="form-text text-danger text-end" style="font-size:0.7rem;">Maksimal 200 karakter</div>
    </div>
</div>

<div class="mt-4" id="detail-container" style="display:none;">
    <h5>BBM Detail (IN)</h5>

    <div class="table-responsive">
        <table class="table table-striped table-bordered align-middle">
            <thead>
                <tr>
                    <th>Barang</th>
                    <th>Qty</th>
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
        const $ref = $('#refcno');

        // reset dulu
        $ref
            .prop('disabled', true)
            .html('<option disabled selected>Pilih Warehouse terlebih dahulu</option>');

        if (formc !== 'IN' || !warco) {
            return;
        }

        $ref.html('<option disabled selected>Loading...</option>');

        $.ajax({
            url: "{{ route('get.do.bbm') }}",
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
        const warco = $('#do_warco').val();

        const $targets = idx !== null
            ? $(`#opron-in-${idx}`)
            : $('.opron-in');

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
            url: "{{ route('get.opron.by.do.bbm') }}",
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

                response.forEach(item => {
                    $body.append(`
                        <tr>
                            <td>
                                ${item.opron} - ${item.prona}

                                <input type="hidden" name="opron[]" value="${item.opron}">
                                <input type="hidden" name="trqty[]" value="${item.trqty}">
                                <input type="hidden" name="stdqt[]" value="${item.qunit}">
                                <input type="hidden" name="lotno[]" value="${item.lotno}">
                                <input type="hidden" name="locco[]" value="${item.locco}">
                                <input type="hidden" name="nolot[]" value="0">
                                <input type="hidden" name="stock[]" value="${item.trqty}">
                                <input type="hidden" name="invno[]" class="invno-in" id="invno-in" value="${$('#refcno').val()||''}" hidden>
                            </td>

                            <td>${item.trqty}</td>
                            <td>${item.qunit}</td>
                            <td>${item.lotno ?? '-'}</td>
                            <td>${item.locco}</td>

                            <td>
                                <textarea
                                    class="form-control"
                                    name="noted[]"
                                    maxlength="200" readonly style="background-color:#e9ecef;">${item.noted ?? '-'}</textarea>
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

    // function pilih barang (IN)
    $(document).on('change', '#refcno, #warco', function () {
        if (this.id === 'refcno') {
            const $selected = $('#refcno').find(':selected');
            $('#reffc-store').val($selected.data('reffc'));
            $('#refno-store').val($selected.data('refno'));
            $('#rfc01-store').val($selected.data('rfc01'));
            $('#ref01-store').val($selected.data('ref01'));
            $('#do_warco').val($selected.data('warco'));
            $('#cusno').val($selected.data('cusno'));
            $('#cust').val($selected.data('cust'));
        }

        const trano = $('#refcno').val();
        loadOpronByDo(trano);
    });

    // detail barang (IN)
    $(document).on('change', 'select.opron-in', function(){
        const $opt = $(this).find(':selected');
        const idx = this.id.split('-').pop();
        const stdqt = $opt.data('stdqt');
        const trqty = $opt.data('qty');
        const lotno = $opt.data('lotno');
        const locco = $opt.data('locco');

        $(`#lotno-in-${idx}`).val(lotno);
        $(`#locco-in-${idx}`).val(locco);

        $(`#stdqt-in-${idx}`).val(stdqt);
        $(`#trqty-in-${idx}`).val(trqty);
        $(`#trqty-in-${idx}`).next('.input-group-text').text(stdqt);
    });
</script>
@endpush
