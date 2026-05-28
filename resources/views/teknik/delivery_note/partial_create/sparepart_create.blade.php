<hr>
<div class="row">
    <h4 class="my-2">Sparepart</h4>

    <div class="accordion" id="accordionSparepart">

        <div class="accordion-item" id="row_0">

            <h2 class="accordion-header d-flex align-items-center">
                <button class="accordion-button accordion-title" type="button" data-bs-toggle="collapse" data-bs-target="#collapse0">
                </button>
            </h2>

            <div id="collapse0" class="accordion-collapse collapse show">
                <div class="accordion-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Sparepart</label>
                            <select name="sparepart[]" id="sparepart_0" class="form-control select2 sparepart-select" data-index="0">
                                <option value="">Loading...</option>
                            </select>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Serial No</label>
                            <select name="lotnos[]" id="lotnos_0" class="form-control select2" data-index="0">
                                <option value="">Pilih Serial No</option>
                            </select>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Warehouse</label>
                            <input type="text" name="warco[]" id="warco_0" class="form-control" readonly style="background-color:#e9ecef">
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Location</label>
                            <input type="text" name="locco[]" id="locco_0" class="form-control" readonly style="background-color:#e9ecef">
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Quantity Used</label>

                            <div class="input-group">
                                <input type="number" name="quantity_sparepart[]" id="quantity_sparepart_0" class="form-control">
                                <span class="input-group-text qunit-sparepart-label" id="qunit_sparepart_label_0"></span>
                                <input type="hidden" name="qunit[]" id="qunit_sparepart_0" class="form-control qunit-sparepart">
                            </div>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Selling Price</label>
                            <input type="text" class="form-control sparepart-price-display price-display">
                            <input type="hidden" name="price[]" id="price_0" class="sparepart-price-input price-raw">
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Discount</label>
                            <input type="text" class="form-control odisa-sparepart-display price-display">
                            <input type="hidden" name="odisa_sparepart[]" id="odisa_sparepart_0" class="odisa-sparepart-input price-raw">
                        </div>

                        <div class="col-md-12 mt-3">
                            <label for="descr_sparepart" class="form-label">Description</label>
                            <textarea class="form-control" name="descr_sparepart[]" id="descr_0" rows="2">{{ old('descr_sparepart') }}</textarea>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="text-end mt-3">
        <button type="button" class="btn btn-primary" id="btn-add-sparepart">
            Tambah Sparepart
        </button>
    </div>
</div>

@push('scripts')
    <script>
        $(document).ready(function(){

            $('.select2').select2({
                width:'100%',
                theme:'bootstrap-5'
            });

            setTimeout(function(){
                refreshAccordionSparepartTitles();
                loadMasterSparepartAll();
                restoreUnitSparepartLabels();
            }, 500);
        });

        function initSparepartSelect(el){
            el.select2({
                placeholder: 'Pilih Barang',
                theme: 'bootstrap-5',
                width: '100%',
                allowClear: true,

                ajax: {
                    url: '{{ route("api.spareparts") }}',
                    dataType: 'json',
                    delay: 250,

                    data: function(params){
                        return {
                            q: params.term || '',
                            page: params.page || 1
                        };
                    },

                    processResults: function(data){

                        return {
                            results: (data.results || []).map(item => ({
                                id: item.id,
                                text: item.text,
                                prona: item.data_prona,
                                stdqu: item.data_stdqu,
                                locco: item.data_locco
                            })),

                            pagination: {
                                more: data.pagination.more
                            }
                        };
                    }
                },
                minimumInputLength: 0
            });
        }
        
        function loadMasterSparepartAll(){
            $('select.sparepart-select').each(function(){
                initSparepartSelect($(this));
            });
        }

        function restoreUnitSparepartLabels(){
            $('.opron').each(function(){

                const $select = $(this);
                const $body = $select.closest('.accordion-body');

                const stdqu = $body.find('.qunit-sparepart').val();

                if(stdqu){
                    $body.find('.qunit-sparepart-label').text(stdqu);
                }
            });
        }

        function refreshAccordionSparepartTitles() {

            $('.opron').each(function(){

                const select = $(this);
                const row = select.closest('.accordion-item');
                const headerText = row.find('.accordion-title');

                const selectedText = select.find('option:selected').text();

                if (selectedText) {
                    headerText.text(selectedText);
                } else {
                    headerText.text('Detail Item');
                }
            });
        }

        function loadLotno(index, sparepart){
            let lotnoSelect = $('#lotnos_' + index);
            lotnoSelect.empty();
            lotnoSelect.append(`
                <option value="">
                    Loading...
                </option>
            `);

            $.get("{{ route('get-lotno-dn') }}", {
                sparepart: sparepart
            }, function(data){

                lotnoSelect.empty();

                lotnoSelect.append(`
                    <option value="" disabled selected>Pilih Serial No</option>
                `);

                data.forEach(function(item){

                    lotnoSelect.append(`
                        <option
                            value="${item.lotno}"

                            data-warco="${item.warco}"
                            data-locco="${item.locco}"
                            data-toqoh="${item.toqoh}"
                            data-qunit="${item.qunit}"
                        >
                            ${item.lotno} (${item.toqoh} ${item.qunit})
                        </option>
                    `);
                });

                lotnoSelect.trigger('change');
            });
        }

        $(document).on('change', '.sparepart-select', function(){
            let index = $(this).data('index');
            let data = $(this).select2('data')[0];
            // stdqu
            $('#qunit_sparepart_' + index).val(
                data.stdqu || ''
            );

            $('#qunit_sparepart_label_' + index).text(
                data.stdqu || ''
            );

            $(this)
                .closest('.accordion-item')
                .find('.accordion-title')
                .text(
                    data.prona
                        ? `Sparepart : ${data.prona}`
                        : 'Sparepart'
                );

            // load lotno
            loadLotno(index, data.id);
        });

        $(document).on('change', 'select[name="lotnos[]"]', function(){
            let index = $(this).data('index');
            let selected = $(this).find(':selected');

            $('#warco_' + index).val(
                selected.data('warco') || ''
            );

            $('#locco_' + index).val(
                selected.data('locco') || ''
            );
        });

        $(document).on('input', 'input[name="quantity_sparepart[]"]', function(){
            let input = $(this);
            let row = input.closest('.accordion-item');
            let lotno = row.find('select[name="lotnos[]"] option:selected');
            let toqoh = parseFloat(lotno.data('toqoh')) || 0
            let qty = parseFloat(input.val()) || 0;

            if(qty > toqoh){
                Swal.fire({
                    icon: 'error',
                    title: 'Qty Melebihi Stock',
                    text: `Maximum stock hanya ${toqoh}`
                });
                input.val(toqoh);
            }
        });
    </script>

    <script>
        function getNextSparepartIndex(){
            let used = [];

            $('#accordionSparepart .accordion-item').each(function(){
                let id = $(this).attr('id').split('_')[1];
                used.push(parseInt(id));
            });

            let i = 0;

            while(used.includes(i)){
                i++;
            }

            return i;
        }

        $('#btn-add-sparepart').click(function(){

            let index = getNextSparepartIndex();

            let html = `
            <div class="accordion-item mb-3" id="row_${index}">

                <h2 class="accordion-header d-flex align-items-center">
                    <button class="accordion-button accordion-title collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse${index}">
                    </button>

                    <button type="button"
                        class="btn btn-danger btn-sm ms-2"
                        onclick="removeSparepartRow(${index})">
                        <i class="bi bi-trash"></i>
                    </button>
                </h2>

                <div id="collapse${index}" class="accordion-collapse collapse show">

                    <div class="accordion-body">

                        <div class="row">

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Sparepart</label>

                                <select name="sparepart[]" id="sparepart_${index}" class="form-control select2 sparepart-select" data-index="${index}">
                                    <option value="">Loading...</option>
                                </select>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Serial No</label>

                                <select name="lotnos[]" id="lotnos_${index}" class="form-control select2" data-index="${index}">
                                    <option value="">
                                        Pilih Serial No
                                    </option>
                                </select>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Warehouse</label>

                                <input type="text" name="warco[]" id="warco_${index}" class="form-control" readonly style="background-color:#e9ecef">
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Location</label>

                                <input type="text" name="locco[]" id="locco_${index}" class="form-control" readonly style="background-color:#e9ecef">
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Quantity Used</label>

                                <div class="input-group">
                                    <input type="number" name="quantity_sparepart[]" id="quantity_sparepart_${index}" class="form-control">
                                    <span class="input-group-text qunit-sparepart-label" id="qunit_sparepart_label_${index}"></span>
                                    <input type="hidden" name="qunit[]" id="qunit_sparepart_${index}" class="form-control qunit-sparepart">
                                </div>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Selling Price</label>
                                <input type="text" class="form-control sparepart-price-display price-display" 
                            <input type="hidden" name="price[]" id="price_${index}" class="sparepart-price-input price-raw">
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Discount</label>
                                <input type="text" class="form-control odisa-sparepart-display price-display">
                                <input type="hidden" name="odisa_sparepart[]" id="odisa_sparepart_${index}" class="odisa-sparepart-input price-raw">
                            </div>

                            <div class="col-md-12 mt-3">
                                <label class="form-label">Description</label>
                                <textarea class="form-control" name="descr_sparepart[]" id="descr_${index}" rows="2"></textarea>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            `;

            $('#accordionSparepart').append(html);

            // init sparepart select
            initSparepartSelect($('#sparepart_' + index));

            // init select2 lotno
            $('#lotnos_' + index).select2({
                width:'100%',
                theme:'bootstrap-5',
                placeholder:'Pilih Serial No'
            });
        });

        function removeSparepartRow(index){
            $('#row_' + index).remove();
        }
    </script>
@endpush