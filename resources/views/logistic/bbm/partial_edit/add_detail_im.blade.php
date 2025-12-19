<script>
window.addIM = function(){

    const i = $('#accordionBbm .accordion-item').length;
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
                    <div class="col-md-6 mt-3">
                        <label class="form-label">Barang</label><span class="text-danger"> *</span>
                        <select class="select2 form-control opron-editIM" name="opron[]" id="opron-${i}" required>
                            <option value="" disabled selected>Pilih Barang</option>
                        </select>
                    </div>

                    <div class="col-md-6 mt-3">
                        <label class="form-label">Receipt Quantity</label><span class="text-danger"> *</span>
                        <div class="input-group">
                            <input type="number" class="form-control trqty-editIM" id="trqty-${i}" name="trqty[]" min="1" required
                            oninput="this.value = this.value.replace(/[^0-9]/g, '');">
                            <span class="input-group-text unit-label-editIM"></span>
                            <input type="text" class="stdqt-editIM" name="stdqt[]" id="stdqt-${i}" hidden>
                        </div>
                    </div>

                    <input type="text" class="nolot-editIM" name="nolot[]" id="nolot-${i}" value="1" hidden>
                
                    <input type="text" class="lotno-editIM" name="lotno[]" id="lotno-${i}" value="-" hidden>

                    <input type="text" class="stdqt-editIM" name="stdqt[]" id="stdqt-${i}" hidden>

                    <input type="text" class="locco-editIM" name="locco[]" id="locco-${i}" value="000001" hidden>

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

    const trano = "{{ $bbm->refno }}";

    // fucntion pilih barang (IM)
    function loadOpronByOB(trano, idx = null) {
        const warco = $('#warco').val();

        const $targets = idx !== null
            ? $(`#opron-${idx}`)
            : $('.opron-editIM');

        // guard clause: wajib lengkap
        if (!trano || !warco) {
            $targets
                .prop('disabled', true)
                .html('<option value="" disabled selected>Pilih Reference & Warehouse terlebih dahulu</option>');
            return;
        }

        $targets
            .prop('disabled', true)
            .html('<option value="" disabled selected>Loading...</option>');

        $.ajax({
            url: "{{ route('get.opron.by.ob') }}",
            type: "GET",
            data: { trano, warco },
            success(response) {
                $targets.each(function () {
                    const $sel = $(this);
                    const oldValue = $sel.val();

                    if (!Array.isArray(response) || response.length === 0) {
                        $sel
                            .html('<option value="" disabled selected>Tidak ada barang tersedia</option>')
                            .prop('disabled', true);
                        return;
                    }

                    $sel.empty().append('<option value="" disabled selected>Pilih Barang</option>');

                    response.forEach(item => {
                        $sel.append(`
                            <option
                                value="${item.opron}"
                                data-qty="${item.trqty}"
                                data-stdqt="${item.qunit}"
                                data-lotno="${item.lotno}"
                                data-locco="${item.locco}">
                                ${item.opron} - ${item.prona}
                            </option>
                        `);
                    });

                    if (oldValue && response.some(item => item.opron === oldValue)) {
                        $sel.val(oldValue);
                    }

                    $sel.prop('disabled', false);
                });
            },
            error() {
                $targets
                    .html('<option value="" disabled selected>Gagal memuat data</option>')
                    .prop('disabled', false);
                }
            });
    }

    loadOpronByOB(trano, i);
};

window.removebbmDetail = function(i){
    $(`#accordion-item-${i}`).remove();
}
</script>

<script>
    function setAccordionTitleIM(item){
        const text = item.find('select[name*="opron"] option:selected').text() || '';
        item.find('.accordion-title').text(text ? `Product : ${text}` : `Product : -`);
    }

    // listen IM
    $(document).on('change','select[name*="opron"]', function(){
        const item = $(this).closest('.accordion-item');
        setAccordionTitleIM(item);
    });

    // panggil pas baru append IM
    setTimeout(() => {
        setAccordionTitleIM($('#accordion-item-'+i));
    },100);
</script>