<script>
    // add/remove row DO
    window.addDO = function(){
        const i = $('#accordionDO .accordion-item').length;

        const type = $('#rfc01').val();
        const sorno = $('#ref01').val();

        if(!type || !sorno){
            Swal.fire('Error', 'Pilih OC dulu!', 'error');
            return;
        }
        const dtl = `
        <div class="accordion-item" id="accordion-do-item-${i}">
            <h2 class="accordion-header d-flex justify-content-between align-items-center" id="heading-do-${i}">
            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#details-do-${i}" aria-expanded="false" aria-controls="details-do-${i}"><span class="accordion-title"></span></button>
            <button type="button" class="btn btn-sm btn-danger mx-2" onclick="removeDO(${i})"><i class="bi bi-trash-fill"></i></button>
            </h2>
            <div id="details-do-${i}" class="accordion-collapse collapse" aria-labelledby="heading-do-${i}" data-bs-parent="#accordionDO">
                <div class="accordion-body">
                    <div class="row">
                        <div class="col-md-6 mt-3">
                            <label class="form-label">Barang</label><span class="text-danger"> *</span>
                            <select class="select2 form-control opron-do" name="opron[]" id="opron-do-${i}" required>
                                <option value="" disabled selected>Pilih Stock Requisition Terlebih Dahulu</option>
                            </select>
                        </div>
                        
                        <div class="col-md-6 mt-3">
                            <label class="form-label">Serial Number</label><span class="text-danger"> *</span>
                            <select class="select2 form-control lotno-do" name="lotno[]" id="lotno-do-${i}" required>
                                <option value="" disabled selected>Pilih Barang Terlebih Dahulu</option>
                            </select>
                        </div>

                        <div class="col-md-6 mt-3">
                            <label for="rqqty-do-${i}" class="form-label">Outstanding Quantity</label>
                            <div class="input-group">
                                <input type="number" class="form-control rqqty-do" id="rqqty-do-${i}" name="rqqty[]" value="{{ old('rqqty.'.$i) }}" min="1" required
                                oninput="this.value = this.value.replace(/[^0-9]/g, '');" disabled>
                                <span id="unit-label-do-${i}" class="input-group-text unit-label-do"></span>
                                <input type="text" id="qunit-do-${i}" name="qunit[]" hidden>
                            </div>
                        </div>

                        <div class="col-md-6 mt-3">
                            <label for="trqty-do-${i}" class="form-label">Issue Quantity</label><span class="text-danger"> *</span>
                            <div class="input-group">
                                <input type="number" class="form-control trqty-do" id="trqty-do-${i}" name="trqty[]" value="{{ old('trqty.'.$i) }}" min="1" required
                                oninput="this.value = this.value.replace(/[^0-9]/g, '');">
                                <span id="unit-label-do-${i}" class="input-group-text unit-label-do"></span>
                            </div>
                        </div>

                        <div class="col-md-6 mt-3">
                            <label for="locco-do-${i}" class="form-label">Warehouse Location</label><span class="text-danger"> *</span>
                            <input type="text" class="form-control locco-do" id="locco-do-${i}" name="locco[]" value="{{ old('aloka.'.$i) }}" required readonly style="background-color:#e9ecef">
                        </div>

                        <div class="col-md-12 mt-3">
                            <label class="form-label">Notes</label>
                            <textarea class="form-control" name="noted[]" id="noted-do-${i}" maxlength="200"></textarea>
                            <div class="form-text text-danger text-end" style="font-size:0.7rem;">Maksimal 200 karakter</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>`;
        $('#accordionDO').append(dtl);
        $('.select2').select2({ width:'100%', theme: 'bootstrap-5' });
        setTimeout(()=>{
            $(`#details-do-${i}`).collapse('show');
        },100);
        const $itemSelect = $(`#opron-do-${i}`);

        $itemSelect.html('<option>Loading...</option>');

        $.get("{{ route('get-barang-oc') }}", {type, sorno}, function(res){
            $itemSelect.empty();
            if(!res.length){
                $itemSelect.append('<option disabled selected>Tidak ada barang</option>');
                return;
            }

            $itemSelect.append('<option value="" disabled selected>Pilih Barang</option>');

            res.forEach(item => {
                $itemSelect.append(`
                    <option value="${item.opron}" 
                            data-qty="${item.qty}" 
                            data-stdqu="${item.stdqu}">
                        ${item.opron} - ${item.prona}
                    </option>
                `);
            });

            $itemSelect.val('').trigger('change');
        });

        // PILIH OPRON -> ambil LOTNO + RQQTY + UNIT
        $(document).on('change', '.opron-do', function () {

            const opron = $(this).val();
            const idx = this.id.split('-').pop();

            const selected = $(this).find('option:selected');

            const unit = selected.data('stdqu') ?? '-';
            const qty  = selected.data('qty') ?? 0;

            $(`#rqqty-do-${idx}`).val(qty);

            // unit
            $(`#unit-label-tao-${idx}`).text(unit);
            $(`#unit-label-do-${idx}`).text(unit);
            $(`#qunit-do-${idx}`).val(unit);

            // reset loc
            $(`#locco-do-${idx}`).val('');

            const $lotno = $(`#lotno-do-${idx}`);

            $lotno.empty()
                .append('<option disabled selected>Loading...</option>')
                .prop('disabled', true)
                .trigger('change.select2');

            $.get("{{ route('get-lot-oc') }}", {opron}, function (res) {

                $lotno.empty();

                if (!res.length) {
                    $lotno.append('<option disabled selected>Tidak ada stok</option>');
                    return;
                }

                $lotno.prop('disabled', false);
                $lotno.append('<option value="" disabled selected>Pilih Serial Number</option>');

                res.forEach(item => {
                    $lotno.append(`
                        <option value="${item.lotno}"
                                data-locco="${item.locco}">
                            ${item.lotno}
                        </option>
                    `);
                });

                $lotno.val(null).trigger('change.select2');
            });
        });

        // PILIH LOTNO -> isi locco
        $(document).on('change', '.lotno-do', function () {

            const idx = this.id.split('-').pop();
            const selected = $(this).find('option:selected');

            const locco = selected.data('locco') ?? '-';

            $(`#locco-do-${idx}`).val(locco);
        });
    }
    
    window.removeDoDetail = function(i){
        $(`#accordion-item-${i}`).remove();
    }

    window.removeDO = function(i){
        $(`#accordion-do-item-${i}`).remove();
    }
</script>