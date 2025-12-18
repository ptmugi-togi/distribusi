<script>
window.addOB = function(){

    const i = $('#accordionBbk .accordion-item').length;
    const warco = "{{ $bbk->warco }}";

    let dtl = `
    <div class="accordion-item" id="accordion-item-${i}">
        <h2 class="accordion-header d-flex justify-content-between align-items-center" id="heading-${i}">
            <button class="accordion-button collapsed" type="button"
                data-bs-toggle="collapse" data-bs-target="#details-${i}"
                aria-expanded="false" aria-controls="details-${i}">
                <span class="accordion-title"></span>
            </button>
            <button type="button" class="btn btn-sm btn-danger mx-2" onclick="removebbkDetail(${i})">
                <i class="bi bi-trash-fill"></i>
            </button>
        </h2>

        <div id="details-${i}" class="accordion-collapse collapse" data-bs-parent="#accordionBbk">
            <div class="accordion-body">
                <div class="row">
                    <div class="col-md-6 mt-3">
                        <label class="form-label">Barang</label><span class="text-danger"> *</span>
                        <select class="select2 form-control opron-editOB" name="opron[]" id="opron-${i}" required>
                            <option value="" disabled selected>Pilih Barang</option>
                        </select>
                    </div>

                    <div class="col-md-6 mt-3">
                        <label for="toqoh-ob-${i}" class="form-label">Sisa Stok</label>
                        <div class="input-group">
                            <input type="text" class="form-control text-end" id="toqoh-ob-${i}" placeholder="-" disabled>
                            <span class="input-group-text unit-label-ob" id="toqoh-unit-ob-${i}">-</span>
                        </div>
                    </div>

                    <div class="col-md-6 mt-3">
                        <label class="form-label">Issue Quantity</label><span class="text-danger"> *</span>
                        <div class="input-group">
                            <input type="number" class="form-control trqty-editOB" id="trqty-${i}" name="trqty[]" min="1" required
                            oninput="this.value = this.value.replace(/[^0-9]/g, '');">
                            <span class="input-group-text unit-label-ob"></span>
                            <input type="text" class="stdqt-editOB" name="stdqt[]" id="stdqt-${i}" hidden>
                            <input type="text" class="lotno-editOB" name="lotno[]" id="lotno-${i}" value="-" hidden>
                            <input type="text" class="locco-editOB" name="locco[]" id="locco-${i}" value="000001" hidden>
                        </div>
                    </div>

                    <div class="col-md-12 mt-3">
                        <label class="form-label">Notes</label>
                        <textarea class="form-control" name="noted[]" maxlength="200"></textarea>
                    </div>

                </div>
            </div>
        </div>
    </div>`;

    $('#accordionBbk').append(dtl);
    $('.select2').select2({ width:'100%', theme:'bootstrap-5' });

    // otomatis buka accordion yang baru dibuat
    const collapse = new bootstrap.Collapse(document.getElementById(`details-${i}`), { show: true });

    loadMasterProductAll();

    // VALIDASI INPUT QTY
    $(document).on('input', '.trqty-editOB', function() {
        const idx = this.id.split('-').pop();
        const qty = parseFloat($(this).val()) || 0;
        const max = parseFloat($(`#toqoh-ob-${idx}`).val()) || 0;

        if (qty > max) {
            Swal.fire({
            icon: 'error',
            title: 'Qty Melebihi Stok',
            text: `Jumlah input quantity melebihi stok yang tersedia.`
            });
            $(this).val(max);
        }
    });

};

window.removebbkDetail = function(i){
    $(`#accordion-item-${i}`).remove();
}

$(document).on('change', '.opron-editOB', function () {
    const idx   = this.id.split('-').pop();
    const braco = $('#braco').val();
    const warco = $('#warco').val();
    const opron = $(this).val();

    const $toqohInput = $(`#toqoh-ob-${idx}`);
    const $toqohUnit  = $(`#toqoh-unit-ob-${idx}`);
    const $stdqtInput = $(`#stdqt-${idx}`);

    $toqohInput.val('...');
    $toqohUnit.text('-');

    $.get(`/get-stobl-ob/${braco}/${warco}/${opron}`, function (data) {
        $toqohInput.val(data?.toqoh ?? 0);
        $toqohUnit.text(data?.qunit ?? '-');
        $stdqtInput.val(data?.qunit ?? '-');
    });
});

$(document).on('input', '.trqty-editOB', function() {
    const idx = this.id.split('-').pop();
    const qty = parseFloat($(this).val()) || 0;
    const max = parseFloat($(`#toqoh-ob-${idx}`).val()) || 0;

    if (qty > max) {
        Swal.fire({
            icon: 'error',
            title: 'Qty Melebihi Stok',
            text: 'Jumlah input quantity melebihi stok yang tersedia.'
        });
        $(this).val(max);
    }
});

</script>

<script>
    function setAccordionTitleOB(item){
        const text = item.find('select[name*="opron"] option:selected').text() || '';
        item.find('.accordion-title').text(text ? `Product : ${text}` : `Product : -`);
    }

    // listen OB
    $(document).on('change','select[name*="opron"]', function(){
        const item = $(this).closest('.accordion-item');
        setAccordionTitleOB(item);
    });
</script>