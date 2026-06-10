<hr>
<div class="row">
    <h4 class="my-2">Detail</h4>

    <div class="accordion" id="accordionProduct">

        <div class="accordion-item" id="rowProduct_0">

            <h2 class="accordion-header d-flex align-items-center">
                <button class="accordion-button accordion-title" type="button" data-bs-toggle="collapse" data-bs-target="#collapse0">
                </button>
            </h2>

            <div id="collapse0" class="accordion-collapse collapse show">
                <div class="accordion-body">
                    <div class="row">
                        <div class="col-md-6 mt-3">
                            <label class="form-label">Product</label>
                            <select name="opron[]" id="opron_0" class="form-control select2 product-select" data-index="0">
                                <option value="">Loading...</option>
                            </select>
                        </div>

                        <div class="col-md-6 mt-3">
                            <label class="form-label">Serial No</label>
                            <input type="text" name="lotno[]" id="lotno_0" class="form-control" value="{{ old('lotno.0') }}">
                        </div>
                                        
                        <div class="col-md-6 mt-3">
                            <label for="mcsts" class="form-label">MC Status</label>
                            <select class="form-control select2" name="mcsts[]" id="mcsts" value="{{ old('mcsts.0') }}">
                                <Option value="" disabled selected>Pilih MC Status</Option>
                                <Option value="R">Renewal</Option>
                                <Option value="G">Garantie</Option>
                                <Option value="C">Competitor Product</Option>
                                <Option value="O">Others</Option>
                            </select>
                        </div>
                        
                        <div class="col-md-6 mt-3">
                            <label class="form-label">Gross Amount</label>
                            <input type="text" id="gramt_product_display_0" class="form-control price-display">
                            <input type="hidden" name="gramt_product[]" id="gramt_product_0" class="price-raw" value="{{ old('gramt_product.0') }}">
                        </div>
                                        
                        <div class="col-md-6 mt-3">
                            <label for="shpto" class="form-label">Delivery To</label>
                            <select class="form-control select2" name="shpto[]" id="shpto_0" value="{{ old('shpto.0') }}">
                                <Option value="" disabled selected>Pilih Delivery To</Option>
                            </select>
                        </div>
                        
                        <div class="col-md-6 mt-3">
                            <label for="delcon" class="form-label">Delivery Contact</label>
                            <input class="form-control" name="delcon[]" id="delcon_0" value="{{ old('delcon.0') }}" readonly style="background-color:#e9ecef">
                        </div>

                        <div class="col-md-6 mt-3">
                            <label for="deladr" class="form-label">Delivery Address</label><span class="text-danger"> *</label>
                            <textarea class="form-control" name="deladr[]" id="deladr_0" rows="2" required readonly style="background-color:#e9ecef">{{ old('deladd.0') }}</textarea>
                            <input type="hidden" name="add01[]" id="add01_0" value="{{ old('add01.0') }}">
                            <input type="hidden" name="add02[]" id="add02_0" value="{{ old('add02.0') }}">
                            <input type="hidden" name="add03[]" id="add03_0" value="{{ old('add03.0') }}">
                            <input type="hidden" name="city[]" id="city_0" value="{{ old('city.0') }}">
                        </div>

                        <div class="col-md-6 mt-3">
                            <label for="phone" class="form-label">Phone</label><span class="text-danger"> *</label>
                            <input class="form-control" name="phone[]" id="phone_0" required readonly style="background-color:#e9ecef" value="{{ old('phone.0') }}"></input>
                        </div>

                        <div class="col-md-6 mt-3">
                            <label for="pvisi" class="form-label">Plan to Visit / Year</label><span class="text-danger"> *</label>
                            <input type="text" class="form-control" name="pvisi[]" id="pvisi_0" value="{{ old('pvisi.0') }}"></input>
                        </div>

                        <div class="col-md-6 mt-3">
                            <label for="fvisi" class="form-label">First Visit</label><span class="text-danger"> *</label>
                            <input type="date" class="form-control" name="fvisi[]" id="fvisi_0" value="{{ old('fvisi.0') }}" min="{{ $minDate }}"></input>
                        </div>

                        <div class="col-md-12 mt-3">
                            <label for="noted" class="form-label">Noted</label><span class="text-danger"> *</label>
                            <textarea class="form-control" name="noted[]" id="noted_0" rows="2">{{ old('noted.0') }}</textarea>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="text-end mt-3">
        <button type="button" class="btn btn-primary" id="btn-add-row-product">
            Tambah Detail
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
                refreshAccordionTitles();
                loadMasterProductAll();
                restoreUnitLabels();
            }, 500);
        });

        function initProductSelect(el){
            el.select2({
                placeholder: 'Pilih Barang',
                theme: 'bootstrap-5',
                width: '100%',
                allowClear: true,

                ajax: {
                    url: '{{ route("api.products") }}',
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
        
        function loadMasterProductAll(){
            $('select.product-select').each(function(){
                initProductSelect($(this));
            });
        }

        function restoreUnitLabels(){
            $('.product-select').each(function(){

                const $select = $(this);
                const $body = $select.closest('.accordion-body');

                const stdqu = $body.find('.stdqu').val();

                if(stdqu){
                    $body.find('.stdqu-label').text(stdqu);
                }
            });
        }

        function refreshAccordionTitles() {

            $('.product-select').each(function(){

                const select = $(this);
                const row = select.closest('.accordion-item');
                const headerText = row.find('.accordion-title');

                const selectedText = select.find('option:selected').text();

                if (selectedText) {
                    headerText.text('');
                } else {
                    headerText.text('Detail Item');
                }
            });
        }

        $(document).on('change','.product-select',function(){
            let index = $(this).data('index');
            let data = $(this).select2('data')[0];

            // stdqu
            $('#stdqu_' + index).val(
                data.stdqu || ''
            );

            // label qty
            $('#stdqu_label_' + index).text(
                data.stdqu || ''
            );

            $(this)
                .closest('.accordion-item')
                .find('.accordion-title')
                .text('Product : ' + data.text || 'Product');
        });

        $(document).on('change', 'select[name="shpto[]"]', function(){
            let selected = $(this).find(':selected');
            let row = $(this).closest('.accordion-item');

            row.find('textarea[name="deladr[]"]').val(selected.data('address') || '');

            row.find('input[name="add01[]"]').val(customerAddress.offad || '');
            row.find('input[name="add02[]"]').val(customerAddress.offad2 || '');
            row.find('input[name="add03[]"]').val(customerAddress.offad3 || '');
            row.find('input[name="city[]"]').val(customerAddress.offcy || '');

            row.find('input[name="delcon[]"]').val(selected.data('contact') || '');
            row.find('input[name="phone[]"]').val(selected.data('phone') || '');
        });

        // display currency
        function formatNumber(value){

            if(!value){
                return '';
            }

            return new Intl.NumberFormat('id-ID', {
                minimumFractionDigits: 0,
                maximumFractionDigits: 2
            }).format(value);
        }

        $(document).on('input', '.price-display', function(){

            let display = $(this);

            let raw = display
                .closest('.col-md-4, .col-md-6')
                .find('.price-raw');

            let value = display.val()
                .replace(/[^0-9.]/g, '');

            let parts = value.split('.');

            if(parts.length > 2){
                value = parts[0] + '.' + parts.slice(1).join('');
            }

            raw.val(value);

            display.val(value);
        });

        $(document).on('focus', '.price-display', function(){

            let display = $(this);

            let raw = display
                .closest('.col-md-4, .col-md-6')
                .find('.price-raw');

            display.val(raw.val());
        });

        $(document).on('blur', '.price-display', function(){

            let display = $(this);

            let raw = display
                .closest('.col-md-4, .col-md-6')
                .find('.price-raw');

            if(raw.val()){
                display.val(formatNumber(raw.val()));
            }
        });

        function updategramt_product(){

            $('.accordion-item').each(function(){
                let row = $(this);
                let total = 0;

                row.find('.fee-input').each(function(){
                    total += parseFloat($(this).val()) || 0;
                });

                let totalRaw = row.find('input[name="gramt_product[]"]');

                let totalDisplay = row.find('[id^="gramt_product_display_"]');

                totalRaw.val(total);

                totalDisplay.val(formatNumber(total));
            });
            updateHeaderSummary();
        }

        $(document).on('input', '.fee-display', function(){
                    updategramt_product();
                });

                $('.price-raw').each(function(){

            let raw = $(this).val();

            if(raw){

                $(this)
                    .closest('.col-md-4, .col-md-6')
                    .find('.price-display, .gramt_product-display')
                    .val(formatNumber(raw));
            }
        });
    </script>
    <script>
        function getNextDetailIndex(){

            let used = [];

            $('[id^="rowProduct_"]').each(function(){
                let id = $(this).attr('id').split('_')[1];
                used.push(parseInt(id));
            });

            let i = 0;

            while(used.includes(i)){
                i++;
            }

            return i;
        }

        $('#btn-add-row-product').click(function(){

            let detailIndex = getNextDetailIndex();

            let html = `
            <div class="accordion-item mb-3" id="rowProduct_${detailIndex}">
                <h2 class="accordion-header d-flex align-items-center">
                    <button class="accordion-button accordion-title collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse${detailIndex}">
                    </button>
                    <button type="button" class="btn btn-danger btn-sm ms-2" onclick="removeRowProduct(${detailIndex})">
                        <i class="bi bi-trash"></i>
                    </button>
                </h2>

                <div id="collapse${detailIndex}" class="accordion-collapse collapse show">
                    <div class="accordion-body">
                        <div class="row">
                            <div class="col-md-6 mt-3">
                                <label class="form-label">Product</label>
                                <select name="opron[]" id="opron_${detailIndex}" class="form-control select2 product-select" data-index="${detailIndex}">
                                    <option value="">Loading...</option>
                                </select>
                            </div>
                            
                            <div class="col-md-6 mt-3">
                                <label class="form-label">Serial No</label>
                                <input type="text" name="lotno[]" id="lotno_${detailIndex}" class="form-control">
                            </div>

                            <div class="col-md-6 mt-3">
                                <label for="mcsts" class="form-label">MC Status</label>
                                <select class="form-control select2" name="mcsts[]" id="mcsts_${detailIndex}">
                                    <Option value="" disabled selected>Pilih MC Status</Option>
                                    <Option value="R">Renewal</Option>
                                    <Option value="G">Garantie</Option>
                                    <Option value="C">Competitor Product</Option>
                                    <Option value="O">Others</Option>
                                </select>
                            </div>
                            
                            <div class="col-md-6 mt-3">
                                <label class="form-label">Gross Amount</label>
                                <input type="text" id="gramt_product_display_${detailIndex}" class="form-control price-display">
                                <input type="hidden" name="gramt_product[]" id="gramt_product_${detailIndex}" class="price-raw">
                            </div>
                                            
                            <div class="col-md-6 mt-3">
                                <label for="shpto" class="form-label">Delivery To</label>
                                <select class="form-control select2" name="shpto[]" id="shpto_${detailIndex}">
                                    <Option value="" disabled selected>Pilih Delivery To</Option>
                                </select>
                            </div>
                            
                            <div class="col-md-6 mt-3">
                                <label for="delcon" class="form-label">Delivery Contact</label>
                                <input class="form-control" name="delcon[]" id="delcon_${detailIndex}" readonly style="background-color:#e9ecef">
                            </div>

                            <div class="col-md-6 mt-3">
                                <label for="deladr" class="form-label">Delivery Address</label><span class="text-danger"> *</label>
                                <textarea class="form-control" name="deladr[]" id="deladr_${detailIndex}" rows="2" required readonly style="background-color:#e9ecef"></textarea>
                                <input type="hidden" name="add01[]" id="add01_${detailIndex}">
                                <input type="hidden" name="add02[]" id="add02_${detailIndex}">
                                <input type="hidden" name="add03[]" id="add03_${detailIndex}">
                                <input type="hidden" name="city[]" id="city_${detailIndex}">
                            </div>

                            <div class="col-md-6 mt-3">
                                <label for="phone" class="form-label">Phone</label><span class="text-danger"> *</label>
                                <input class="form-control" name="phone[]" id="phone_${detailIndex}" required readonly style="background-color:#e9ecef"></input>
                            </div>

                            <div class="col-md-6 mt-3">
                                <label for="pvisi" class="form-label">Plan to Visit / Year</label><span class="text-danger"> *</label>
                                <input type="text" class="form-control" name="pvisi[]" id="pvisi_${detailIndex}"></input>
                            </div>

                            <div class="col-md-6 mt-3">
                                <label for="fvisi" class="form-label">First Visit</label><span class="text-danger"> *</label>
                                <input type="date" class="form-control" name="fvisi[]" id="fvisi_${detailIndex}" min="{{ $minDate }}"></input>
                            </div>

                            <div class="col-md-12 mt-3">
                                <label for="noted" class="form-label">Noted</label><span class="text-danger"> *</label>
                                <textarea class="form-control" name="noted[]" id="noted_${detailIndex}" rows="2"></textarea>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            `;

            $('#accordionProduct').append(html);

            initProductSelect($('#opron_' + detailIndex));

            $('#shpto_' + detailIndex).select2({
                width:'100%',
                theme:'bootstrap-5'
            });

            fillShiptoOptions($('#shpto_' + detailIndex));
        });

        function removeRowProduct(index){
            $('#rowProduct_' + index).remove();
            updateHeaderSummary();
        }
    </script>
@endpush