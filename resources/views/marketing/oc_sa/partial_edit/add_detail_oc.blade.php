<script>
    // add/remove row OC
    window.addOC = function(){
        const i = $('#accordionOC .accordion-item').length;
        const dtl = `
        <div class="accordion-item" id="accordion-oc-item-${i}">
            <h2 class="accordion-header d-flex justify-content-between align-items-center" id="heading-oc-${i}">
            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#details-oc-${i}" aria-expanded="false" aria-controls="details-oc-${i}"><span class="accordion-title"></span></button>
            <button type="button" class="btn btn-sm btn-danger mx-2" onclick="removeOC(${i})"><i class="bi bi-trash-fill"></i></button>
            </h2>
            <div id="details-oc-${i}" class="accordion-collapse collapse" aria-labelledby="heading-oc-${i}" data-bs-parent="#accordionOC">
                <div class="accordion-body">
                    <div class="row">
                        <div class="col-md-6 mt-3">
                            <label class="form-label">Barang</label><span class="text-danger"> *</span>
                            <select class="select2 form-control opron-oc" name="opron[]" id="opron-oc-${i}" required>
                            <option value="" disabled selected>Pilih Barang</option>
                            </select>
                            <input type="text" class="prona-oc" name="prona[]" id="prona-oc-${i}" value="" hidden>
                        </div>
                        
                        <div class="col-md-6 mt-3">
                            <label for="qtyor-oc-${i}" class="form-label">Order Quantity</label><span class="text-danger"> *</span>
                            <div class="input-group">
                                <input type="number" class="form-control qtyor-oc" id="qtyor-oc-${i}" name="qtyor[]"
                                oninput="this.value = this.value.replace(/[^0-9]/g, '');" min="1" required>
                                <span class="input-group-text unit-label-oc"></span>
                            </div>
                            <input type="text" class="stdqu-oc" name="stdqu[]" id="stdqu-oc-${i}" hidden>
                        </div>

                        <div class="col-md-6 mt-3">
                            <label for="rqeta-oc-${i}" class="form-label">Request ETA</label><span class="text-danger"> *</span>
                            <input type="date" class="form-control rqeta-oc" name="rqeta[]" id="rqeta-oc-${i}" required>
                        </div>

                        <div class="col-md-6 mt-3">
                            <label for="whetd-oc-${i}" class="form-label">ETD by W/H</label><span class="text-danger"> *</span>
                            <input type="date" class="form-control whetd-oc" name="whetd[]" id="whetd-oc-${i}" required>
                        </div>

                        <div class="col-md-6 mt-3">
                            <label for="price-oc-${i}" class="form-label">Selling Price</label><span class="text-danger"> *</span>
                            <input type="text" class="form-control price-input" name="price[]" id="price_oc_${i}" required>
                        </div>

                        <div class="col-md-6 mt-3">
                            <label class="form-label">Price List/Unit</label><span class="text-danger"> *</span>
                            <input type="text" class="form-control price-input" name="plist[]" id="plist_oc_${i}" required>
                        </div>

                        <div class="col-md-6 mt-3">
                            <label class="form-label">Discount/Unit</label>
                            <input type="text" class="form-control price-input" name="odisa[]" id="odisa_oc_${i}">
                        </div>

                        <div class="col-md-6 mt-3">
                            <label class="form-label">Jasa Teknik/Unit</label>
                            <input type="text" class="form-control price-input" name="teknik[]" id="teknik_oc_${i}">
                        </div>

                        <div class="col-md-6 mt-3">
                            <label for="total-oc-${i}" class="form-label">Source of Goods</label><span class="text-danger"> *</span>
                            <select name="srcog[]" class="form-control select2" id="srcog-oc-${i}" required>
                                <option value="" disabled {{ old('srcog.'.$i) ? '' : 'selected' }}>Silahkan Pilih Source of Goods</option>
                                <option value="1" {{ old('srcog.'.$i) == '1' ? 'selected' : '' }}>1. Branch's Stock</option>
                                <option value="2" {{ old('srcog.'.$i) == '2' ? 'selected' : '' }}>2. Request to Head Office</option>
                            </select>
                        </div>

                        <div class="col-md-6 mt-3">
                            <label for="putama-oc-${i}" class="form-label">Klasifikasi Produk</label><span class="text-danger"> *</span>
                            <select name="putama[]" class="form-control select2" id="putama-oc-${i}" required>
                                <option value="" disabled {{ old('putama.'.$i) ? '' : 'selected' }}>Silahkan Pilih Klasifikasi Produk</option>
                                <option value="U" {{ old('putama.'.$i) == 'U' ? 'selected' : '' }}>Utama</option>
                                <option value="N" {{ old('putama.'.$i) == 'N' ? 'selected' : '' }}>Non Utama</option>
                            </select>
                        </div>

                        <div class="col-md-12 mt-3">
                            <label class="form-label">Notes</label>
                            <textarea type="text" class="form-control" name="noted[]" id="noted-oc-${i}" maxlength="200">{{ old('noted.'.$i) }}</textarea>
                            <div class="form-text text-danger text-end" style="font-size:0.7rem;">Maksimal 200 karakter</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>`;
        $('#accordionOC').append(dtl);

        const rowEl = document.getElementById(`accordion-oc-item-${i}`);

        loadMasterProductAll();

        $(`#srcog-oc-${i}`).select2({ width: '100%', theme: 'bootstrap-5' });
        $(`#putama-oc-${i}`).select2({ width: '100%', theme: 'bootstrap-5' });

        applyCurrencyFormatter(rowEl);

        setTimeout(()=>{
            $(`#details-oc-${i}`).collapse('show');
        },100);
    }

    window.removeOC = function(i){
        $(`#accordion-oc-item-${i}`).remove();
    }
</script>