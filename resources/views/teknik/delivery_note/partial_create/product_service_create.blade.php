<hr>
<div class="row">
    <h4 class="my-2">Detail</h4>

    <div class="accordion" id="accordionProduct">

        <div class="accordion-item" id="row_0">

            <h2 class="accordion-header d-flex align-items-center">
                <button class="accordion-button accordion-title" type="button" data-bs-toggle="collapse" data-bs-target="#collapse0">
                </button>
            </h2>

            <div id="collapse0" class="accordion-collapse collapse show">
                <div class="accordion-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Product</label>
                            <select name="opron[]" id="opron_0" class="form-control select2 product-select" data-index="0">
                                <option value="">Loading...</option>
                            </select>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Quantity</label>

                            <div class="input-group">
                                <input type="number" name="quantity_service[]" id="quantity_service_0" class="form-control">
                                <span class="input-group-text stdqu-label" id="stdqu_label_0"></span>
                                <input type="hidden" name="stdqu[]" id="stdqu_0" class="form-control stdqu">
                            </div>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Serial No</label>
                            <input type="text" name="lotno[]" id="lotno_0" class="form-control">
                        </div>

                        <div class="col-md-12 mb-3">
                            <label class="form-label">Service Type</label>
                            <div id="service_container_0">
                                <div class="row g-2 service-row mb-2">
                                    <div class="col-md-7">
                                        <select name="tofee[0][]" class="form-control select2 service-select">
                                            <option value="" disabled selected>Pilih Service</option>
                                            @foreach ($serviceType as $service)
                                                <option value="{{ $service->tofee }}"
                                                        data-serty="{{ $service->serty }}"
                                                        data-descr="{{ $service->descr }}"
                                                    >
                                                    {{ $service->tofee }} - {{ $service->descr }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <input type="hidden" name="serty[0][]" class="serty-input">
                                        <input type="hidden" name="descr[0][]" class="descr-input">
                                    </div>

                                    <div class="col-md-4">
                                        <input type="text" class="form-control fee-display price-display" placeholder="Fee">
                                        <input type="hidden" name="fee[0][]" class="fee-input price-raw">
                                    </div>

                                    <div class="col-md-1 d-grid">
                                        <button type="button" class="btn btn-danger remove-service">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <button type="button" class="btn btn-sm btn-primary mt-2 add-service" data-index="0">
                                + Add Service
                            </button>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Total Fee</label>
                            <input type="text" id="totalfee_display_0" class="form-control price-display" readonly style="background-color:#e9ecef">
                            <input type="hidden" name="totalfee[]" id="totalfee_0" class="price-raw">
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Discount</label>
                            <input type="text" class="form-control odisa-service-display price-display">
                            <input type="hidden" name="odisa_service[]" id="odisa_service_0" class="odisa-service-input price-raw">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="text-end mt-3">
        <button type="button" class="btn btn-primary" id="btn-add-row">
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

        $(document).on('change', '.service-select', function(){
            let selected = $(this).find(':selected');
            let serty = selected.data('serty') || '';
            let descr = selected.data('descr') || '';

            $(this).closest('.service-row').find('.serty-input').val(serty);
            $(this).closest('.service-row').find('.descr-input').val(descr);
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

        function updateTotalFee(){

            $('.accordion-item').each(function(){
                let row = $(this);
                let total = 0;

                row.find('.fee-input').each(function(){
                    total += parseFloat($(this).val()) || 0;
                });

                let totalRaw = row.find('input[name="totalfee[]"]');

                let totalDisplay = row.find('[id^="totalfee_display_"]');

                totalRaw.val(total);

                totalDisplay.val(formatNumber(total));
            });
            updateHeaderSummary();
        }

        $(document).on('input', '.fee-display', function(){
                    updateTotalFee();
                });

                $('.price-raw').each(function(){

            let raw = $(this).val();

            if(raw){

                $(this)
                    .closest('.col-md-4, .col-md-6')
                    .find('.price-display, .totalfee-display')
                    .val(formatNumber(raw));
            }
        });
    </script>
    <script>
        function getNextDetailIndex(){

            let used = [];

            $('[id^="row_"]').each(function(){
                let id = $(this).attr('id').split('_')[1];
                used.push(parseInt(id));
            });

            let i = 0;

            while(used.includes(i)){
                i++;
            }

            return i;
        }

        $('#btn-add-row').click(function(){

            let detailIndex = getNextDetailIndex();

            let html = `
            <div class="accordion-item mb-3" id="row_${detailIndex}">
                <h2 class="accordion-header d-flex align-items-center">
                    <button class="accordion-button accordion-title collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse${detailIndex}">
                    </button>
                    <button type="button" class="btn btn-danger btn-sm ms-2" onclick="removeRow(${detailIndex})">
                        <i class="bi bi-trash"></i>
                    </button>
                </h2>

                <div id="collapse${detailIndex}" class="accordion-collapse collapse show">
                    <div class="accordion-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Product</label>
                                <select name="opron[]" id="opron_${detailIndex}" class="form-control select2 product-select" data-index="${detailIndex}">
                                    <option value="">Loading...</option>
                                </select>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Quantity</label>
                                <div class="input-group">
                                    <input type="number" name="quantity_service[]" id="quantity_service_${detailIndex}" class="form-control">
                                    <span class="input-group-text" id="stdqu_label_${detailIndex}">
                                    </span>
                                    <input type="hidden" name="stdqu[]" id="stdqu_${detailIndex}" value="PCS">
                                </div>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Serial No</label>
                                <input type="text" name="lotno[]" id="lotno_${detailIndex}" class="form-control">
                            </div>

                            <div class="col-md-12 mb-3">
                                <label class="form-label">Service Type</label>
                                <div id="service_container_${detailIndex}">
                                    <div class="row g-2 service-row mb-2">
                                        <div class="col-md-7">
                                            <select name="tofee[${detailIndex}][]" class="form-control select2 service-select">
                                                <option value="" disabled selected>Pilih Service</option>
                                                @foreach ($serviceType as $service)
                                                    <option value="{{ $service->tofee }}"
                                                            data-serty="{{ $service->serty }}"
                                                            data-descr="{{ $service->descr }}"
                                                        >
                                                        {{ $service->tofee }} - {{ $service->descr }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            <input type="hidden" name="serty[${detailIndex}][]" class="serty-input">
                                            <input type="hidden" name="descr[${detailIndex}][]" class="descr-input">
                                        </div>

                                        <div class="col-md-4">
                                            <input type="text" class="form-control fee-display price-display" placeholder="Fee">
                                            <input type="hidden" name="fee[${detailIndex}][]" class="fee-input price-raw">
                                        </div>

                                        <div class="col-md-1 d-grid">
                                            <button type="button" class="btn btn-danger remove-service" data-index="${detailIndex}">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>

                                <button type="button" class="btn btn-primary mt-2 add-service" data-index="${detailIndex}">
                                    + Add Service
                                </button>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Total Fee</label>
                                <input type="text" id="totalfee_display_${detailIndex}" class="form-control price-display" readonly style="background-color:#e9ecef">
                                <input type="hidden" name="totalfee[]" id="totalfee_${detailIndex}" class="price-raw">
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Discount</label>
                                <input type="text" class="form-control odisa-service-display price-display">
                                <input type="hidden" name="odisa_service[]" id="odisa_service_${detailIndex}" class="odisa-service-input price-raw">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            `;

            $('#accordionProduct').append(html);

            initProductSelect($('#opron_' + detailIndex));

            $('#row_' + detailIndex)
                .find('.service-select')
                .select2({
                    width:'100%',
                    theme:'bootstrap-5',
                    placeholder:'Pilih Service'
                });
        });

        $(document).on('click', '.add-service', function(){

            let index = $(this).data('index');

            let html = `
                <div class="row g-2 service-row mb-2">
                    <div class="col-md-7">
                        <select name="tofee[${index}][]" class="form-control select2 service-select">
                            <option value="" disabled selected>Pilih Service</option>
                            @foreach ($serviceType as $service)
                                <option value="{{ $service->tofee }}"
                                    data-serty="{{ $service->serty }}"
                                    data-descr="{{ $service->descr }}"
                                >
                                    {{ $service->tofee }} - {{ $service->descr }}
                                </option>
                            @endforeach
                        </select>
                        <input type="hidden" name="serty[${index}][]" class="serty-input">
                        <input type="hidden" name="descr[${index}][]" class="descr-input">
                    </div>

                    <div class="col-md-4">
                        <input type="text" class="form-control fee-display price-display" placeholder="Fee">
                        <input type="hidden" name="fee[${index}][]" class="fee-input price-raw">
                    </div>

                    <div class="col-md-1 d-grid">
                        <button type="button" class="btn btn-danger remove-service">
                            <i class="bi bi-trash"></i>
                        </button>
                    </div>
                </div>
            `;

            $('#service_container_' + index).append(html);

            $('#service_container_' + index)
                .find('.service-select')
                .last()
                .select2({
                    width:'100%',
                    theme:'bootstrap-5',
                    placeholder:'Pilih Service'
                });
        });

        $(document).on('click', '.remove-service', function(){
            $(this).closest('.service-row').remove();

            updateTotalFee();
        });

        function removeRow(index){

            $('#row_' + index).remove();
        }
    </script>
@endpush