<script>
    // add/remove row RA
    window.addRA = function(){
        const i = $('#accordionRA .accordion-item').length;
        const dtl = `
        <div class="accordion-item" id="accordion-ra-item-${i}">
            <h2 class="accordion-header d-flex justify-content-between align-items-center" id="heading-ra-${i}">
            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#details-ra-${i}" aria-expanded="false" aria-controls="details-ra-${i}"><span class="accordion-title"></span></button>
            <button type="button" class="btn btn-sm btn-danger mx-2" onclick="removeRA(${i})"><i class="bi bi-trash-fill"></i></button>
            </h2>
            <div id="details-ra-${i}" class="accordion-collapse collapse" aria-labelledby="heading-ra-${i}" data-bs-parent="#accordionRA">
                <div class="accordion-body">
                    <div class="row">
                        <div class="col-md-6 mt-3">
                            <label class="form-label">Barang</label><span class="text-danger"> *</span>
                            <select class="select2 form-control opron-ra" name="opron[]" id="opron-ra-${i}" required>
                            <option value="" disabled selected>Pilih Barang</option>
                            </select>
                        </div>
                        
                        <div class="col-md-6 mt-3">
                            <label class="form-label">Request Quantity</label><span class="text-danger"> *</span>
                            <div class="input-group">
                            <input type="number" class="form-control rqqty-ra" id="rqqty-ra-${i}" name="rqqty[]" min="1" required
                            oninput="this.value = this.value.replace(/[^0-9]/g, '');">
                            <span class="input-group-text unit-label-ra"></span>
                            </div>
                        </div>

                        <div class="col-md-6 mt-3">
                            <label class="form-label">Expected Arrival</label><span class="text-danger"> *</span>
                            <input type="date" class="form-control" id="eariv-ra-${i}" name="eariv[]" required>
                        </div>

                        <div class="col-md-6 mt-3">
                            <label class="form-label">Alokasi</label><span class="text-danger"> *</span>
                            <input type="text" class="form-control" id="aloka-ra-${i}" name="aloka[]" required>
                        </div>

                        <div class="col-md-12 mt-3">
                            <label class="form-label">Notes</label>
                            <textarea class="form-control" name="noted[]" id="noted-ra-${i}" maxlength="200"></textarea>
                            <div class="form-text text-danger text-end" style="font-size:0.7rem;">Maksimal 200 karakter</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>`;
        $('#accordionRA').append(dtl);
        $('.select2').select2({ width:'100%', theme: 'bootstrap-5' });
        setTimeout(()=>{
            $(`#details-ra-${i}`).collapse('show');
        },100);

        loadMasterProductAll();
    }

    window.removeRA = function(i){
        $(`#accordion-ra-item-${i}`).remove();
    }
</script>