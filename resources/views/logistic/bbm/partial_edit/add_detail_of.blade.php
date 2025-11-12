<script>
window.addOF = function(){

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
                        <label class="form-label">Warehouse Location</label><span class="text-danger">*</span>
                        <select class="form-control select2 locco-editOF" name="locco[]" id="locco-${i}" required>
                            <option value="" disabled selected>Pilih Lokasi</option>
                        </select>
                    </div>

                    <div class="col-md-6 mt-3">
                        <label class="form-label">Barang</label><span class="text-danger"> *</span>
                        <select class="select2 form-control opron-editOF" name="opron[]" id="opron-${i}" required>
                            <option value="" disabled selected>Pilih Barang</option>
                        </select>
                    </div>

                    <div class="col-md-6 mt-3 lot-section">
                        <label for="lotno-of-${i}" class="form-label">Serial / Batch No.</label>
                        <select class="form-select select2 lotno-select" name="lotno[]" id="lotno-of-${i}">
                            <option value="">Pilih SN / Batch No</option>
                        </select>
                    </div>

                    <div class="col-md-6 mt-3">
                        <label for="toqoh-of-${i}" class="form-label">Sisa Stok</label>
                        <div class="input-group">
                            <input type="text" class="form-control text-end" id="toqoh-of-${i}" placeholder="-" disabled>
                            <span class="input-group-text unit-label-of" id="toqoh-unit-of-${i}">-</span>
                        </div>
                    </div>

                    <div class="col-md-6 mt-3">
                        <label class="form-label">Receipt Quantity</label><span class="text-danger"> *</span>
                        <div class="input-group">
                            <input type="number" class="form-control trqty-editOF" id="trqty-${i}" name="trqty[]" min="1" required
                            oninput="this.value = this.value.replace(/[^0-9]/g, '');">
                            <span class="input-group-text unit-label-of"></span>
                            <input type="text" class="stdqt-editOF" name="stdqt[]" id="stdqt-${i}" hidden>
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

    $('#accordionBbm').append(dtl);
    $('.select2').select2({ width:'100%', theme:'bootstrap-5' });

    // otomatis buka accordion yang baru dibuat
    const collapse = new bootstrap.Collapse(document.getElementById(`details-${i}`), { show: true });

    // load warehouse
    $.get(`{{ url('/get-locco') }}/${warco}`, function(data){
        const sel = $(`#locco-${i}`);
        data.forEach(item => sel.append(`<option value="${item.locco}">${item.locco}</option>`));
    });

    // ambil data barang
    $(document).on('change', '.locco-editOF', function() {
        const idx = this.id.split('-').pop();
        const braco = "{{ $bbm->braco }}";
        const warco = "{{ $bbm->warco }}";
        const locco = $(this).val();
        const $barangSelect = $(`#opron-${idx}`);

        if (!locco) return;

        $barangSelect.html('<option>Memuat Barang...</option>').prop('disabled', true);

        $.get(`/get-barang/${braco}/${warco}/${locco}`, function(data) {
            $barangSelect.empty();
            if (data.length > 0) {
                $barangSelect.append('<option value="">Pilih Barang</option>');
                data.forEach(item => {
                    $barangSelect.append(`
                        <option value="${item.opron}"
                                data-qty="${item.qty}">
                            ${item.opron} - ${item.prona}
                        </option>
                    `);
                });
            } else {
                $barangSelect.append('<option value="">Tidak ada barang di lokasi ini</option>');
            }
        }).fail(() => {
            Swal.fire({ icon: 'error', title: 'Gagal Ambil Data Barang', text: 'Terjadi kesalahan di server.' });
        }).always(() => {
            $barangSelect.prop('disabled', false);
        });
    });

    // ambil detail barang
    $(document).on('change', '.opron-editOF', function() {
        const idx = this.id.split('-').pop();
        const $opt = $(this).find(':selected');
        const braco = "{{ $bbm->braco }}";
        const warco = "{{ $bbm->warco }}";
        const opron = $(this).val();

        const $lotSelect = $(`#lotno-of-${idx}`);
        $lotSelect.prop('disabled', true).html('<option>Memuat LOT...</option>');

        $.get(`/get-stobl/${braco}/${warco}/${opron}`, function(data) {
            $lotSelect.empty();
            if (data.length > 0) {
                $lotSelect.append('<option value="">Pilih SN / Batch No</option>');
                data.forEach(item => {
                    $lotSelect.append(`
                        <option value="${item.lotno}" data-toqoh="${item.toqoh}" data-stdqt="${item.qunit}">
                            ${item.lotno} (Stok: ${item.toqoh})
                        </option>
                    `);
                });
            } else {
                $lotSelect.append('<option value="">Tidak ada stok</option>');
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

        $(`#toqoh-of-${idx}`).val(toqoh);
        $(`#stdqt-${idx}`).val(stdqt);
        $(`.unit-label-of`).text(stdqt);
    });

    // VALIDASI INPUT QTY
    $(document).on('input', '.trqty-editOF', function() {
        const idx = this.id.split('-').pop();
        const qty = parseFloat($(this).val()) || 0;
        const max = parseFloat($(`#toqoh-of-${idx}`).val()) || 0;

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

window.removebbmDetail = function(i){
    $(`#accordion-item-${i}`).remove();
}
</script>

<script>
    function setAccordionTitleOF(item){
        const text = item.find('select[name*="opron"] option:selected').text() || '';
        item.find('.accordion-title').text(text ? `Product : ${text}` : `Product : -`);
    }

    // listen OF
    $(document).on('change','select[name*="opron"]', function(){
        const item = $(this).closest('.accordion-item');
        setAccordionTitleOF(item);
    });
</script>