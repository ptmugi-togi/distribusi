<hr>
<div class="row">
    <h4 class="my-2">Detail</h4>

    <div class="accordion" id="accordionProduct">
        @foreach($mc->mcdtls as $i => $detail)
            <div class="accordion-item" id="rowProduct_{{ $i }}">

                <h2 class="accordion-header d-flex align-items-center">
                    <button class="accordion-button accordion-title {{ $i > 0 ? 'collapsed' : '' }}" type="button" data-bs-toggle="collapse" data-bs-target="#collapse{{ $i }}" >
                        Product {{ $i + 1 }} - {{ $detail->mpromas->prona ?? $detail->opron }}
                    </button>

                    @if($i > 0)
                        <button type="button" class="btn btn-danger btn-sm ms-2" onclick="removeRowProduct({{ $i }})" >
                            <i class="bi bi-trash"></i>
                        </button>
                    @endif
                </h2>

                <div id="collapse{{ $i }}" class="accordion-collapse collapse {{ $i == 0 ? 'show' : '' }}">
                    <div class="accordion-body">
                        <div class="row">
                            <div class="col-md-6 mt-3">
                                <label class="form-label">Product</label>

                                <select name="opron[]" id="opron_{{ $i }}" class="form-control select2 product-select" data-index="{{ $i }}" >
                                    <option value="{{ $detail->opron }}" selected>
                                        {{ $detail->opron }} - {{ $detail->mpromas->prona ?? '' }}
                                    </option>
                                </select>
                            </div>

                            <div class="col-md-6 mt-3">
                                <label class="form-label">Serial No</label>
                                <input type="text" name="lotno[]" id="lotno_{{ $i }}" class="form-control" value="{{ old("lotno.$i", $detail->lotno) }}" >
                            </div>

                            <div class="col-md-6 mt-3">
                                <label for="mcsts_{{ $i }}" class="form-label">MC Status</label>
                                <select class="form-control select2" name="mcsts[]" id="mcsts_{{ $i }}">
                                    <option value="" disabled {{ old("mcsts.$i", $detail->mcsts) ? '' : 'selected' }}>
                                        Pilih MC Status
                                    </option>
                                    <option value="R" {{ old("mcsts.$i", $detail->mcsts) == 'R' ? 'selected' : '' }}>Renewal</option>
                                    <option value="G" {{ old("mcsts.$i", $detail->mcsts) == 'G' ? 'selected' : '' }}>Garantie</option>
                                    <option value="C" {{ old("mcsts.$i", $detail->mcsts) == 'C' ? 'selected' : '' }}>Competitor Product</option>
                                    <option value="O" {{ old("mcsts.$i", $detail->mcsts) == 'O' ? 'selected' : '' }}>Others</option>
                                </select>
                            </div>

                            <div class="col-md-6 mt-3">
                                <label class="form-label">Gross Amount</label>
                                <input type="text" id="gramt_product_display_{{ $i }}" class="form-control price-display" value="{{ number_format(old("gramt_product.$i", $detail->price ?? 0), 0, ',', '.') }}" >
                                <input type="hidden" name="gramt_product[]" id="gramt_product_{{ $i }}" class="price-raw" value="{{ old("gramt_product.$i", $detail->price ?? 0) }}" >
                            </div>

                            <div class="col-md-6 mt-3">
                                <label for="shpto_{{ $i }}" class="form-label">Site</label>
                                <select class="form-control select2" name="shpto[]" id="shpto_{{ $i }}" data-selected="{{ old("shpto.$i", $detail->shpto ?? '') }}" >
                                    <option value="">Loading...</option>
                                </select>
                            </div>

                            <div class="col-md-6 mt-3">
                                <label for="delcon_{{ $i }}" class="form-label">Site Contact</label>
                                <input class="form-control" name="delcon[]" id="delcon_{{ $i }}" value="{{ old("delcon.$i", $detail->delcon) }}" readonly style="background-color:#e9ecef" >
                            </div>

                            <div class="col-md-6 mt-3">
                                <label for="deladr_{{ $i }}" class="form-label">Site Address</label>
                                <span class="text-danger"> *</span>
                                <textarea class="form-control" name="deladr[]" id="deladr_{{ $i }}" rows="2" required readonly style="background-color:#e9ecef" >{{ $detail->deladr ?? '' }}</textarea>
                            </div>

                            <div class="col-md-6 mt-3">
                                <label for="phone_{{ $i }}" class="form-label">Phone</label>
                                <span class="text-danger"> *</span>
                                <input class="form-control" name="phone[]" id="phone_{{ $i }}" required readonly style="background-color:#e9ecef" value="{{ old("phone.$i", $detail->phone) }}" >
                            </div>

                            <div class="col-md-6 mt-3">
                                <label for="pvisi_{{ $i }}" class="form-label">Plan to Visit / Year</label>
                                <span class="text-danger"> *</span>
                                <input type="text" class="form-control" name="pvisi[]" id="pvisi_{{ $i }}" value="{{ old("pvisi.$i", $detail->pvisi) }}" >
                            </div>

                            <div class="col-md-6 mt-3">
                                <label for="fvisi_{{ $i }}" class="form-label">First Visit</label>
                                <span class="text-danger"> *</span>
                                <input type="date" class="form-control" name="fvisi[]" id="fvisi_{{ $i }}" value="{{ old("fvisi.$i", $detail->fvisi) }}" min="{{ $minDate }}" >
                            </div>

                            <div class="col-md-12 mt-3">
                                <label for="noted_{{ $i }}" class="form-label">Noted</label>
                                <span class="text-danger"> *</span>
                                <textarea class="form-control" name="noted[]" id="noted_{{ $i }}" rows="2" >{{ old("noted.$i", $detail->noted) }}</textarea>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
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

            row.find('input[name="delcon[]"]').val(selected.data('contact') || '');
            row.find('input[name="phone[]"]').val(selected.data('phone') || '');

            applyShiptoToRow($(this));
        });

        function applyShiptoToRow(select){
            let selected = select.find(':selected');
            let row = select.closest('.accordion-item');

            row.find('textarea[name="deladr[]"]').val(selected.data('address') || '');

            row.find('input[name="delcon[]"]').val(selected.data('contact') || '');
            row.find('input[name="phone[]"]').val(selected.data('phone') || '');
        }

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
                                <label for="shpto" class="form-label">Site</label>
                                <select class="form-control select2" name="shpto[]" id="shpto_${detailIndex}" data-selected="">
                                    <option value="" disabled selected>Pilih Site</option>
                                </select>
                            </div>
                            
                            <div class="col-md-6 mt-3">
                                <label for="delcon" class="form-label">Site Contact</label>
                                <input class="form-control" name="delcon[]" id="delcon_${detailIndex}" readonly style="background-color:#e9ecef">
                            </div>

                            <div class="col-md-6 mt-3">
                                <label for="deladr" class="form-label">Site Address</label><span class="text-danger"> *</label>
                                <textarea class="form-control" name="deladr[]" id="deladr_${detailIndex}" rows="2" required readonly style="background-color:#e9ecef"></textarea>
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

            $('#mcsts_' + detailIndex).select2({
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