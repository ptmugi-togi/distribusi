<script>
window.addIA = function(){

    const i = $('#accordionBbm .accordion-item').length;
    const pono = "{{ $bbm->refno }}"; // untuk IA ini PO No
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
                    
                    <input type="text" name="invno[]" hidden value="${pono}">

                    <div class="col-md-6 mt-3">
                        <label class="form-label">Barang (PO)</label><span class="text-danger"> *</span>
                        <select class="select2 form-control opron-editIA" name="opron[]" id="opron-${i}" required>
                            <option value="" disabled selected>Pilih Barang (PO)</option>
                        </select>
                    </div>

                    <div class="col-md-6 mt-3">
                        <label class="form-label">PO Quantity</label>
                        <div class="input-group">
                            <input type="number" class="form-control inqty-editIA" id="inqty-${i}" readonly style="background-color:#e9ecef;">
                            <span class="input-group-text unit-label-editIA"></span>
                            <input type="text" class="stdqt-editIA" name="stdqt[]" id="stdqt-${i}" hidden>
                        </div>
                    </div>

                    <div class="col-md-6 mt-3">
                        <label class="form-label">Receipt Quantity</label><span class="text-danger"> *</span>
                        <div class="input-group">
                            <input type="number" class="form-control trqty-editIA" id="trqty-${i}" name="trqty[]" min="1" required
                            oninput="
                                this.value = this.value.replace(/[^0-9]/g, '');
                                const inqty = Number(document.getElementById('inqty-${i}')?.value || 0);
                                if (Number(this.value) > inqty) {
                                    Swal.fire({
                                        title: 'Peringatan',
                                        text: 'Jumlah Receipt qty tidak boleh lebih banyak dari jumlah Invoice qty',
                                        icon: 'error'
                                    });
                                    this.value = inqty;
                                }
                            ">
                            <span class="input-group-text unit-label-editIA"></span>
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
                        <label class="form-label">PO No.</label>
                        <input type="text" class="form-control" name="pono[]" value="${pono}" readonly style="background-color:#e9ecef;">
                    </div>

                    <div class="col-md-6 mt-3">
                        <label class="form-label">Warehouse Location</label><span class="text-danger">*</span>
                        <select class="form-control select2" name="locco[]" id="locco-${i}" required>
                            <option value="" disabled selected>Pilih Warehouse terlebih dahulu</option>
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

    // load barang based on PO
    $.get(`{{ url('/get-barang') }}/${pono}?formc=IA`, function(data){
        const sel = $(`#opron-${i}`);
        data.forEach(item => {
            sel.append(`<option value="${item.opron}" data-qty="${item.inqty}" data-stdqt="${item.stdqt}" data-pono="${item.pono}">${item.opron} - ${item.prona}</option>`)
        });
    });

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
    function setAccordionTitleIA(item){
        const text = item.find('select[name*="opron"] option:selected').text() || '';
        item.find('.accordion-title').text(text ? `Product : ${text}` : `Product : -`);
    }

    // listen IA
    $(document).on('change','select[name*="opron"]', function(){
        const item = $(this).closest('.accordion-item');
        setAccordionTitleIA(item);
    });

    // panggil pas baru append ia
    setTimeout(() => {
        setAccordionTitleIA($('#accordion-item-'+i));
    },100);
</script>