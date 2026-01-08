<script>
    // add/remove row Detail
    window.addDetail = function(){
        const i = $('#accordionWo .accordion-item').length;
        const dtl = `
        <div class="accordion-item" id="accordion-item-${i}">
            <h2 class="accordion-header d-flex justify-content-between align-items-center" id="heading-${i}">
            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#details-${i}" aria-expanded="false" aria-controls="details-${i}"><span class="accordion-title"></span></button>
            <button type="button" class="btn btn-sm btn-danger mx-2" onclick="removeDetail(${i})"><i class="bi bi-trash-fill"></i></button>
            </h2>
            <div id="details-${i}" class="accordion-collapse collapse" aria-labelledby="heading-${i}" data-bs-parent="#accordionWo">
                <div class="accordion-body">
                    <div class="row">
                        <div class="col-md-6 mt-3">
                            <label class="form-label">Barang</label><span class="text-danger"> *</span>
                            <select class="select2 form-control opron" name="opron[]" id="opron-${i}" required>
                            <option value="" disabled selected>Pilih Barang</option>
                            </select>
                        </div>
                        
                        <div class="col-md-6 mt-3">
                            <label class="form-label">Quantity</label><span class="text-danger"> *</span>
                            <div class="input-group">
                            <input type="number" class="form-control outqt" id="outqt-${i}" name="outqt[]" min="1" required
                            oninput="this.value = this.value.replace(/[^0-9]/g, '');">
                            <span class="input-group-text unit-label"></span>
                            </div>
                            <input type="text" class="stdqu" name="stdqu[]" id="stdqu-${i}" hidden>
                        </div>

                        <div class="col-md-12 mt-3">
                            <label class="form-label">Notes</label>
                            <textarea class="form-control" name="noted[]" id="noted-${i}" maxlength="200"></textarea>
                            <div class="form-text text-danger text-end" style="font-size:0.7rem;">Maksimal 200 karakter</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>`;
        $('#accordionWo').append(dtl);

        const $newSelect = $(`#opron-${i}`);

        applyOpronMode($newSelect);

        setTimeout(()=>{
            $(`#details-${i}`).collapse('show');
        },100);
    }

    window.removeDetail = function(i){
        $(`#accordion-item-${i}`).remove();
    }
</script>