<script>
window.addOA = function(){

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
                        <select class="select2 form-control opron-editOA" name="opron[]" id="opron-${i}" required>
                            <option value="" disabled selected>Pilih Barang</option>
                        </select>
                    </div>

                    <div class="col-md-6 mt-3 lot-section">
                        <label for="lotno-oa-${i}" class="form-label">Serial / Batch No.</label><span class="text-danger"> *</span>
                        <select class="form-select select2 lotno-select" name="lotno[]" id="lotno-oa-${i}" required>
                            <option value="">Pilih Barang Terlebih Dahulu</option>
                        </select>
                    </div>

                    <div class="col-md-6 mt-3">
                        <label class="form-label">Warehouse Location</label><span class="text-danger">*</span>
                        <input type="text" class="form-control" name="locco[]" id="locco-${i}" value="" required readonly style="background-color:#e9ecef">
                    </div>

                    <div class="col-md-6 mt-3">
                        <label for="toqoh-oa-${i}" class="form-label">Stok</label>
                        <div class="input-group">
                            <input type="text" class="form-control text-end" id="toqoh-oa-${i}" placeholder="-" disabled>
                            <span class="input-group-text unit-label-oa" id="toqoh-unit-oa-${i}">-</span>
                        </div>
                    </div>

                    <div class="col-md-6 mt-3">
                        <label class="form-label">Issue Quantity</label><span class="text-danger"> *</span>
                        <div class="input-group">
                            <input type="number" class="form-control trqty-editOA" id="trqty-${i}" name="trqty[]" min="1" required
                            oninput="this.value = this.value.replace(/[^0-9]/g, '');">
                            <span class="input-group-text unit-label-oa"></span>
                            <input type="text" class="stdqt-editOA" name="stdqt[]" id="stdqt-${i}" hidden>
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

    // ambil data barang
    loadMasterProductAll();

    // ambil detail barang
    $(document).on('change', '.opron-editOA', function() {
        const idx = this.id.split('-').pop();
        const $opt = $(this).find(':selected');
        const braco = "{{ $bbk->braco }}";
        const warco = "{{ $bbk->warco }}";
        const opron = $(this).val();

        const $lotSelect = $(`#lotno-oa-${idx}`);
        $lotSelect.prop('disabled', true).html('<option>Memuat Stok Barang...</option>');

        $.get(`/get-stobl/${braco}/${warco}/${opron}`, function(data) {
            $lotSelect.empty();
            if (data.length > 0) {
                $lotSelect.append('<option value="" disabled selected>Pilih SN / Batch No</option>');
                data.forEach(item => {
                    $lotSelect.append(`
                        <option value="${item.lotno}" data-toqoh="${item.toqoh}" data-stdqt="${item.qunit}" data-locco="${item.locco}">
                            ${item.lotno} (Stok: ${item.toqoh})
                        </option>
                    `);
                });
            } else {
                $lotSelect.append('<option value="" disabled selected>Tidak ada stok</option>');
                Swal.fire({ icon: 'warning', title: 'Stok Kosong', text: 'Barang ini tidak memiliki stok tersedia.' });
            }
        }).fail(() => {
            Swal.fire({ icon: 'error', title: 'Gagal Ambil Data SN / Batch', text: 'Terjadi kesalahan server.' });
            $lotSelect.html('<option>Gagal ambil data</option>');
        }).always(() => {
            $lotSelect.prop('disabled', false);
        });
    });

    // ubah data barang
    $(document).on('change', '.lotno-select', function() {
        const idx = this.id.split('-').pop();
        const $opt = $(this).find(':selected');
        const toqoh = $opt.data('toqoh') || 0;
        const stdqt = $opt.data('stdqt') || '-';
        const locco = $opt.data('locco') || '';
        
        $(`#toqoh-oa-${idx}`).val(toqoh);
        $(`#stdqt-${idx}`).val(stdqt);
        $(`#locco-${idx}`).val(locco);
        $(`#toqoh-unit-oa-${idx}`).text(stdqt);
        $(`#trqty-${idx}`).next('.unit-label-oa').text(stdqt);
    });

    // VALIDASI INPUT QTY
    $(document).on('input', '.trqty-editOA', function() {
        const idx = this.id.split('-').pop();
        const qty = parseFloat($(this).val()) || 0;
        const max = parseFloat($(`#toqoh-oa-${idx}`).val()) || 0;

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
</script>

<script>
    function setAccordionTitleOA(item){
        const text = item.find('select[name*="opron"] option:selected').text() || '';
        item.find('.accordion-title').text(text ? `Product : ${text}` : `Product : -`);
    }

    // listen OA
    $(document).on('change','select[name*="opron"]', function(){
        const item = $(this).closest('.accordion-item');
        setAccordionTitleOA(item);
    });
</script>