<script>
    // add/remove row OC
    window.addOCInstallation = function(){
        const i = $('#accordionOCInstallation .accordion-item').length;
        const dtl = `
        <div class="accordion-item" id="accordion-oc-installation-${i}">
            <h2 class="accordion-header d-flex justify-content-between align-items-center" id="heading-oc-${i}">
            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#details-oc-${i}" aria-expanded="false" aria-controls="details-oc-${i}"><span class="accordion-title"></span></button>
            <button type="button" class="btn btn-sm btn-danger mx-2" onclick="removeOCInstallation(${i})"><i class="bi bi-trash-fill"></i></button>
            </h2>
            <div id="details-oc-${i}" class="accordion-collapse collapse" aria-labelledby="heading-oc-${i}" data-bs-parent="#accordionOCInstallation">
                <div class="accordion-body">
                    <div class="row">
                        <div class="col-md-6 mt-3">
                            <label class="form-label">Product</label><span class="text-danger"> *</span>
                            <select class="select2 form-control opron-oc" name="opron[]" id="opron-oc-${i}" required>
                            <option value="" disabled selected>Pilih Product</option>
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

                        <div class="col-md-12 mt-3">
                            <button type="button"
                                class="btn btn-sm btn-primary d-none"
                                id="btn-bom-${i}"
                                onclick="openBomModal(${i})">
                                Lihat Consist of Goods
                            </button>

                            <div id="bom-hidden-${i}"></div>
                        </div>

                        <div class="col-md-6 mt-3">
                            <label for="price-oc-${i}" class="form-label">Price / Unit</label><span class="text-danger"> *</span>
                            <input type="text" class="form-control price-input" id="price_display_oc_${i}" data-raw-target="price_raw_oc_${i}" required>

                            <input type="text" name="price[]" id="price_raw_oc_${i}" hidden>
                        </div>

                        <div class="col-md-6 mt-3">
                            <label class="form-label">Price List / Unit</label><span class="text-danger"> *</span>

                            <input type="text" class="form-control price-input" id="plist_display_oc_${i}" data-raw-target="plist_raw_oc_${i}" required>

                            <input type="text" name="plist[]" id="plist_raw_oc_${i}" hidden>
                        </div>

                        <div class="col-md-6 mt-3">
                            <label class="form-label">Discount / Unit</label>

                            <input type="text" class="form-control price-input" id="odisa_display_oc_${i}" data-raw-target="odisa_raw_oc_${i}">

                            <input type="text" name="odisa_ins[]" id="odisa_raw_oc_${i}" hidden>
                        </div>

                        <div class="col-md-6 mt-3">
                            <label class="form-label">Jasa Teknik / Unit</label>

                            <input type="text" class="form-control price-input" id="teknik_display_oc_${i}" data-raw-target="teknik_raw_oc_${i}">

                            <input type="text" name="teknik[]" id="teknik_raw_oc_${i}" hidden>
                        </div>

                        <div class="col-md-6 mt-3">
                            <label for="putama-oc-${i}" class="form-label">Klasifikasi Produk</label><span class="text-danger"> *</span>
                            <select name="putama[]" class="form-control select2" id="putama-oc-${i}" required>
                                <option value="U">Utama</option>
                                <option value="N">Non Utama</option>
                            </select>
                        </div>

                        <div class="col-md-6 mt-3">
                            <label for="insby-oc-${i}" class="form-label">Install by Branch</label><span class="text-danger"> *</span>
                            <select name="insby[]" class="form-control select2" id="insby-oc-${i}" required>
                                <option value="{{ auth()->user()->cabang }}">{{ auth()->user()->cabang }}</option>
                                @foreach ($branches as $b)
                                    <option value="{{ $b->braco }}">
                                        {{ $b->braco }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-6 mt-3">
                            <label for="insdt-oc-${i}" class="form-label">Planned Installation Date</label>
                            <input type="date" class="form-control" name="insdt[]" id="insdt-oc-${i}"></input>
                        </div>

                        <hr class="my-4">
                        <h5>Installation Site</h5>

                        <div class="col-md-4 mt-3">
                            <label class="form-label">Installation Site</label><span class="text-danger"> *</span>
                            <select name="delto[]" id="delto-${i}" class="form-control select2 delto-select" data-index="${i}" required>
                                <option disabled selected>Pilih Site</option>
                            </select>
                        </div>

                        <div class="col-md-4 mt-3">
                            <label class="form-label">Name</label>
                            <input type="text" name="delto_name[]" id="delto_name-${i}" class="form-control" readonly style="background-color:#e9ecef">
                        </div>

                        <div class="col-md-4 mt-3">
                            <label class="form-label">Attn.</label>
                            <input type="text" name="delto_attn[]" id="delto_attn-${i}" class="form-control" readonly style="background-color:#e9ecef">
                        </div>

                        <div class="col-md-6 mt-3">
                            <label class="form-label">Provinsi</label>
                            <input type="text" name="delto_prov[]" id="delto_prov-${i}" class="form-control" readonly style="background-color:#e9ecef">
                        </div>

                        <div class="col-md-6 mt-3">
                            <label class="form-label">Kabupaten</label>
                            <input type="text" name="delto_kab[]" id="delto_kab-${i}" class="form-control" readonly style="background-color:#e9ecef">
                        </div>

                        <div class="col-md-6 mt-3">
                            <label class="form-label">Address</label>
                            <textarea name="delto_addrress[]" id="delto_addrress-${i}" class="form-control" readonly style="background-color:#e9ecef"></textarea>
                        </div>

                        <div class="col-md-6 mt-3">
                            <label class="form-label">Phone</label>
                            <input type="text" name="delto_phone[]" id="delto_phone-${i}" class="form-control" readonly style="background-color:#e9ecef">
                        </div>

                        <div class="col-md-12 mt-3">
                            <label class="form-label">Notes</label>
                            <textarea type="text" class="form-control" name="noted_installation[]" id="noted-oc-${i}" maxlength="200"></textarea>
                            <div class="form-text text-danger text-end" style="font-size:0.7rem;">Maksimal 200 karakter</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>`;
        $('#accordionOCInstallation').append(dtl);

        const $newSelect = $(`#opron-oc-${i}`);

        $('.select2').select2({ width: '100%', theme: 'bootstrap-5' });

        if($('#cusno').val()){
            $('#cusno').trigger('change');
        }

        loadMasterProductAll();

        calculateHeaderFromDetails();

        initPriceFormatter(document.getElementById(`accordion-oc-installation-${i}`));

        setTimeout(()=>{
            $(`#details-oc-${i}`).collapse('show');
        },100);
    }

    window.removeOCInstallation = function(i){
        $(`#accordion-oc-installation-${i}`).remove();
        calculateHeaderFromDetails();
    }
</script>