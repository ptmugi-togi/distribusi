<script>
let isLoadingOpronWO = false;
// fucntion pilih barang (IJ)
function loadOpronByWO(braco, wonum, idx = null) {
    if (isLoadingOpronWO) return;
    isLoadingOpronWO = true;

    const $targets = (idx !== null)
        ? $(`#opron-${idx}`)
        : $('select.opron-editIJ');

    $targets.each(function () {
        const $sel = $(this);

        if ($sel.hasClass('select2-hidden-accessible')) {
            $sel.select2('destroy');
        }

        $sel.prop('disabled', true).empty();
    });

    $.ajax({
        url: "{{ route('get.opron.by.wo') }}",
        type: "GET",
        data: { wonum, braco },
        success(response) {

            $targets.each(function () {
                const $sel = $(this);
                const oldValue = $sel.data('old'); // <<< PENTING

                $sel.append('<option value="" disabled selected>Pilih Barang</option>');

                response.forEach(item => {
                    $sel.append(`
                        <option value="${item.outpr}"
                            data-qty="${item.outqt}"
                            data-stdqt="${item.stdqu}">
                            ${item.outpr} - ${item.prona}
                        </option>
                    `);
                });

                if (oldValue) {
                    $sel.val(oldValue).trigger('change');
                }

                $sel.prop('disabled', false).select2({
                    width: '100%',
                    theme: 'bootstrap-5'
                });
            });

            isLoadingOpronWO = false;
        },
        error() {
            isLoadingOpronWO = false;
            alert('Gagal load barang dari WO');
        }
    });
}

$(document).ready(function () {
    const braco = "{{ $bbm->braco }}";
    const wonum = "{{ $bbm->refno }}";

    if (wonum && wonum !== '-') {
        loadOpronByWO(braco, wonum);
    }
});

window.addIJ = function(){

    const i = $('#accordionBbm .accordion-item').length;
    const wonum = "{{ $bbm->refno }}";
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
                    
                    <input type="text" name="invno[]" hidden value="${wonum}">

                    <div class="col-md-6 mt-3">
                        <label class="form-label">Barang</label><span class="text-danger"> *</span>
                        <select class="select2 form-control opron-editIJ" name="opron[]" id="opron-${i}" required>
                            <option value="" disabled selected>Pilih Barang</option>
                        </select>
                    </div>

                    <div class="col-md-6 mt-3">
                        <label class="form-label">Receipt Quantity</label><span class="text-danger"> *</span>
                        <div class="input-group">
                            <input type="number" class="form-control trqty-editIJ" id="trqty-${i}" name="trqty[]" min="1" required
                            oninput="
                                this.value = this.value.replace(/[^0-9]/g, '');
                                const inqty = Number(document.getElementById('inqty-${i}')?.value || 0);
                                if(!inqty || inqty <= 0){ return; }
                                if (Number(this.value) > inqty) {
                                    Swal.fire({
                                        title: 'Peringatan',
                                        text: 'Jumlah Receipt qty tidak boleh lebih banyak dari jumlah Invoice qty',
                                        icon: 'error'
                                    });
                                    this.value = inqty;
                                }
                            ">
                            <span class="input-group-text unit-label-editIJ"></span>
                            <input type="text" class="stdqt-editIJ" name="stdqt[]" id="stdqt-${i}" hidden>
                        </div>
                    </div>

                    <div class="col-md-6 mt-4">
                        <div class="form-check mt-3">
                            <input class="form-check-input nolot-checkbox" type="checkbox" value="1" name="nolot[${i}]" id="nolot-[${i}]">
                            <label class="form-check-label" for="nolot-${i}">
                                Without Serial / Batch No
                            </label>
                        </div>
                    </div>

                    <div class="col-md-6 mt-3 lot-section">
                        <label class="form-label">Serial / Batch No.</label>
                        <input type="text" class="form-control lotno-input" name="lotno[]" id="lotno-${i}">
                    </div>

                    <div class="col-md-6 mt-3 lot-section">
                        <label class="form-label">Serial / Batch No. (Akhir)</label>
                        <input type="text" class="form-control" name="lotnoend[]" id="lotnoend-${i}" readonly style="background-color:#e9ecef;">
                    </div>

                    <div class="col-md-6 mt-3">
                        <label class="form-label">Warehouse Location</label><span class="text-danger">*</span>
                        <select class="form-control select2" name="locco[]" id="locco-${i}" required>
                            <option value="" disabled selected>Pilih Lokasi</option>
                        </select>
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

    // load barang based on WO
    loadOpronByWO("{{ $bbm->braco }}", wonum, i);

    // load warehouse
    $.get(`{{ url('/get-locco') }}/${warco}`, function(data){
        const sel = $(`#locco-${i}`);
        data.forEach(item => sel.append(`<option value="${item.locco}">${item.locco}</option>`));
    });

};

window.removebbmDetail = function(i){
    $(`#accordion-item-${i}`).remove();
}
</script>

<script>
    function setAccordionTitleIJ(item){
        const text = item.find('select[name*="opron"] option:selected').text() || '';
        item.find('.accordion-title').text(text ? `Product : ${text}` : `Product : -`);
    }

    // listen IJ
    $(document).on('change','select[name*="opron"]', function(){
        const item = $(this).closest('.accordion-item');
        setAccordionTitleIJ(item);
    });

    // panggil pas baru append ij
    setTimeout(() => {
        setAccordionTitleIJ($('#accordion-item-'+i));
    },100);
</script>