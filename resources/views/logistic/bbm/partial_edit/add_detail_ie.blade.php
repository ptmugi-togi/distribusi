<script>
    // load barang based on OE
    function loadOpronByOA(braco, warco, trano, idx = null) {
        const $targets = (idx !== null)
            ? $(`#opron-${idx}`)
            : $('select[name="opron[]"]');
    
        $targets.prop('disabled', true)
                .html('<option>Loading...</option>');
    
        $.ajax({
            url: "{{ route('get.opron.by.oe') }}",
            type: "GET",
            data: {
                trano,
                braco: "{{ $bbm->braco }}",
                warco: "{{ $bbm->warco }}"
             },
            success: function (response) {
    
                $targets.each(function () {
                    const $sel = $(this);
                    const oldValue = $sel.data('old') || $sel.val();
    
                    $sel.empty();
    
                    if (!response || response.length === 0) {
                        $sel.append('<option disabled selected>Tidak ada barang tersedia</option>');
                        return;
                    }
    
                    $sel.append('<option disabled selected>Pilih Barang</option>');
    
                    response.forEach(item => {
                        $sel.append(`
                            <option 
                                value="${item.opron}"
                                data-qty="${item.trqty}"
                                data-stdqt="${item.qunit}"
                                data-lotno="${item.lotno}"
                                data-locco="${item.locco}"
                            >
                                ${item.opron} - ${item.prona} (${item.lotno})
                            </option>
                        `);
                    });
    
                    // restore selected value kalau ada
                    if (oldValue && response.some(r => r.opron === oldValue)) {
                        $sel.val(oldValue);
                    }
    
                    $sel.prop('disabled', false).trigger('change');
                });
            },
            error: function () {
                $targets.html('<option>Gagal memuat data</option>');
            }
        });
    }

    // load detail barang based on OE
    $(document).on('change', '.opron-editIE', function () {
        const $sel = $(this);
        const selected = $sel.find(':selected');

        const idx = this.id.split('-').pop();

        const qty = selected.data('qty');
        const stdqt = selected.data('stdqt');
        const lotno = selected.data('lotno');
        const locco = selected.data('locco');

        $('#stdqt-' + idx).val(stdqt);

        $('#trqty-' + idx).val(qty);
        $('#lotno-' + idx).val(lotno);

        $('#trqty-' + idx)
            .closest('.input-group')
            .find('.unit-label-editIE')
            .text(stdqt);

        $('#locco-' + idx).val(locco);
    });
</script>
<script>

    const trano = "{{ $bbm->refno }}";

    window.addIE = function(){

        const i = $('#accordionBbm .accordion-item').length;
        const refno = "{{ $bbm->refno }}";
        const warco = "{{ $bbm->warco }}";

        let dtl = `
        <div class="accordion-item" id="accordion-item-${i}">
            <h2 class="accordion-header d-flex justify-content-between align-items-center" id="heading-${i}">
                <button class="accordion-button collapsed" type="button"
                    data-bs-toggle="collapse" data-bs-target="#details-${i}"
                    aria-expanded="false" aria-controls="details-${i}">
                    <span class="accordion-title"></span>
                </button>
                <button type="button" class="btn btn-sm btn-danger mx-2" onclick="removebbmDetail(${i})">
                    <i class="bi bi-trash-fill"></i>
                </button>
            </h2>

            <div id="details-${i}" class="accordion-collapse collapse" data-bs-parent="#accordionBbm">
                <div class="accordion-body">
                    <div class="row">
                        
                        <input type="text" name="invno[]" hidden value="${refno}">

                        <div class="col-md-6 mt-3">
                            <label class="form-label">Barang</label><span class="text-danger"> *</span>
                            <select class="select2 form-control opron-editIE" name="opron[]" id="opron-${i}" required>
                                <option value="" disabled selected>Pilih Barang</option>
                            </select>
                        </div>

                        <div class="col-md-6 mt-3">
                            <label class="form-label">Receipt Quantity</label><span class="text-danger"> *</span>
                            <div class="input-group">
                                <input type="number" class="form-control trqty-editIE" id="trqty-${i}" name="trqty[]" min="1" required
                                oninput="this.value = this.value.replace(/[^0-9]/g, '');">
                                <span class="input-group-text unit-label-editIE"></span>
                            </div>
                        </div>

                        <div class="col-md-6 mt-3 lot-section">
                            <label class="form-label">Serial / Batch No.</label>
                            <input type="text" class="form-control lotno-input" name="lotno[]" id="lotno-${i}" readonly style="background-color:#e9ecef;">
                        </div>

                        <div class="col-md-6 mt-3">
                            <label class="form-label">Warehouse Location</label><span class="text-danger">*</span>
                            <input type="text" class="form-control locco-input" name="locco[]" id="locco-${i}" readonly style="background-color:#e9ecef;">
                        </div>

                        <input type="text" class="stdqt-input" name="stdqt[]" id="stdqt-${i}" hidden>
                        <input type="text" class="invno-input" name="invno[]" id="invno-${i}" value="{{ $bbm->refno }}" hidden>

                        <div class="col-md-12 mt-3">
                            <label class="form-label">Notes</label>
                            <textarea class="form-control" name="noted[]" maxlength="200"></textarea>
                        </div>

                    </div>
                </div>
            </div>
        </div>`;

        $('#accordionBbm').append(dtl);
        $('.select2').select2({ width:'100%', theme:'bootstrap-5' });

        // otomatis buka accordion yang baru dibuat
        const collapse = new bootstrap.Collapse(document.getElementById(`details-${i}`), { show: true });

        loadOpronByOA(braco, warco, trano, i);
    }
</script>

<script>
    window.removebbmDetail = function(i){
        $(`#accordion-item-${i}`).remove();
    }
</script>

<script>
    function setAccordionTitleIE(item){
        const text = item.find('select[name*="opron"] option:selected').text() || '';
        item.find('.accordion-title').text(text ? `Product : ${text}` : `Product : -`);
    }

    // listen IE
    $(document).on('change','select[name*="opron"]', function(){
        const item = $(this).closest('.accordion-item');
        setAccordionTitleIE(item);
    });

</script>