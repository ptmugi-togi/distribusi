<hr>
<div class="row">
    <h4 class="my-2">Termin Pembayaran</h4>

    <div class="accordion" id="accordionPhase">

        <div class="accordion-item" id="rowPhase_0">

            <h2 class="accordion-header d-flex align-items-center">
                <button class="accordion-button accordion-title" type="button" data-bs-toggle="collapse" data-bs-target="#collapse0">
                    Phase 1
                </button>
            </h2>

            <div id="collapse0" class="accordion-collapse collapse show">
                <div class="accordion-body">
                    <div class="row">
                        <div class="col-md-6 mt-3">
                            <label class="form-label">Description</label>
                            <input type="text" name="desc[]" id="desc_0" class="form-control">
                        </div>

                        <div class="col-md-6 mt-3">
                            <label class="form-label">Term of Payment (%)</label>
                            <input type="text" name="toppc[]" id="toppc_0" class="form-control">
                        </div>
                        
                        <div class="col-md-6 mt-3">
                            <label class="form-label">Gross Amount</label>
                            <input type="text" id="gramt_termin_display_0" class="form-control price-display" readonly style="background-color:#e9ecef">
                            <input type="hidden" name="gramt_termin[]" id="gramt_termin_0" class="price-raw">
                        </div>
                        
                        <div class="col-md-6 mt-3">
                            <label class="form-label">Official Discount</label>
                            <input type="text" id="odisa_termin_display_0" class="form-control price-display" readonly style="background-color:#e9ecef">
                            <input type="hidden" name="odisa_termin[]" id="odisa_termin_0" class="price-raw">
                        </div>
                        
                        <div class="col-md-6 mt-3">
                            <label class="form-label">Net Amount</label>
                            <input type="text" id="ntamt_termin_display_0" class="form-control price-display" readonly style="background-color:#e9ecef">
                            <input type="hidden" name="ntamt_termin[]" id="ntamt_termin_0" class="price-raw">
                        </div>
                        
                        <div class="col-md-6 mt-3">
                            <label class="form-label">VAT ({{$tax->taxes}}%)</label>
                            <input type="text" id="txamt_termin_display_0" class="form-control price-display" readonly style="background-color:#e9ecef">
                            <input type="hidden" name="txamt_termin[]" id="txamt_termin_0" class="price-raw">
                        </div>
                        
                        <div class="col-md-6 mt-3">
                            <label class="form-label">Billing Amount</label>
                            <input type="text" id="blamt_termin_display_0" class="form-control price-display" readonly style="background-color:#e9ecef">
                            <input type="hidden" name="blamt_termin[]" id="blamt_termin_0" class="price-raw">
                        </div>
                        
                        <div class="col-md-6 mt-3">
                            <label class="form-label">Billing Date</label><span class="text-danger"> *</span>
                            <input type="date" id="billd" name="billd[]" class="form-control" min="{{ $minDate }}" required>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="text-end mt-3">
        <button type="button" class="btn btn-primary" id="btn-add-row-phase">
            Tambah Detail
        </button>
        <div>
            <small id="top-warning" class="text-danger d-none">
                Total Term of Payment sudah mencapai 100%
            </small>
        </div>
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

        function updategramt_termin(){

            $('.accordion-item').each(function(){
                let row = $(this);
                let total = 0;

                row.find('.fee-input').each(function(){
                    total += parseFloat($(this).val()) || 0;
                });

                let totalRaw = row.find('input[name="gramt_termin[]"]');

                let totalDisplay = row.find('[id^="gramt_termin_display_"]');

                totalRaw.val(total);

                totalDisplay.val(formatNumber(total));
            });
            updateHeaderSummary();
        }

        $(document).on('input', '.fee-display', function(){
                    updategramt_termin();
                });

                $('.price-raw').each(function(){

            let raw = $(this).val();

            if(raw){

                $(this)
                    .closest('.col-md-4, .col-md-6')
                    .find('.price-display, .gramt_termin-display')
                    .val(formatNumber(raw));
            }
        });
    </script>
    <script>
        function getNextPhaseIndex(){
            let used = [];

            $('[id^="rowPhase_"]').each(function(){
                let id = $(this).attr('id').split('_')[1];
                used.push(parseInt(id));
            });

            let i = 0;
            while(used.includes(i)){
                i++;
            }

            return i;
        }

        $('#btn-add-row-phase').click(function(){

            let detailIndex = getNextPhaseIndex();

            let html = `
            <div class="accordion-item mb-3" id="rowPhase_${detailIndex}">
                <h2 class="accordion-header d-flex align-items-center">
                    <button class="accordion-button accordion-title collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse${detailIndex}">
                        Phase ${detailIndex + 1}
                    </button>
                    <button type="button" class="btn btn-danger btn-sm ms-2" onclick="removePhaseRow(${detailIndex})">
                        <i class="bi bi-trash"></i>
                    </button>
                </h2>

                <div id="collapse${detailIndex}" class="accordion-collapse collapse show">
                    <div class="accordion-body">
                        <div class="row">
                            <div class="col-md-6 mt-3">
                                <label class="form-label">Description</label>
                                <input type="text" name="desc[]" id="desc_${detailIndex}" class="form-control">
                            </div>

                            <div class="col-md-6 mt-3">
                                <label class="form-label">Term of Payment (%)</label>
                                <input type="text" name="toppc[]" id="toppc_${detailIndex}" class="form-control">
                            </div>
                            
                            <div class="col-md-6 mt-3">
                                <label class="form-label">Gross Amount</label>
                                <input type="text" id="gramt_termin_display_${detailIndex}" class="form-control price-display" readonly style="background-color:#e9ecef">
                                <input type="hidden" name="gramt_termin[]" id="gramt_termin_${detailIndex}" class="price-raw">
                            </div>
                            
                            <div class="col-md-6 mt-3">
                                <label class="form-label">Official Discount</label>
                                <input type="text" id="odisa_termin_display_${detailIndex}" class="form-control price-display" readonly style="background-color:#e9ecef">
                                <input type="hidden" name="odisa_termin[]" id="odisa_termin_${detailIndex}" class="price-raw">
                            </div>
                            
                            <div class="col-md-6 mt-3">
                                <label class="form-label">Net Amount</label>
                                <input type="text" id="ntamt_termin_display_${detailIndex}" class="form-control price-display" readonly style="background-color:#e9ecef">
                                <input type="hidden" name="ntamt_termin[]" id="ntamt_termin_${detailIndex}" class="price-raw">
                            </div>
                            
                            <div class="col-md-6 mt-3">
                                <label class="form-label">VAT ({{$tax->taxes}}%)</label>
                                <input type="text" id="txamt_termin_display_${detailIndex}" class="form-control price-display" readonly style="background-color:#e9ecef">
                                <input type="hidden" name="txamt_termin[]" id="txamt_termin_${detailIndex}" class="price-raw">
                            </div>
                            
                            <div class="col-md-6 mt-3">
                                <label class="form-label">Billing Amount</label>
                                <input type="text" id="blamt_termin_display_${detailIndex}" class="form-control price-display" readonly style="background-color:#e9ecef">
                                <input type="hidden" name="blamt_termin[]" id="blamt_termin_${detailIndex}" class="price-raw">
                            </div>
                            
                            <div class="col-md-6 mt-3">
                                <label class="form-label">Billing Date</label><span class="text-danger"> *</span>
                                <input type="date" id="billd_${detailIndex}" name="billd[]" class="form-control" min="{{ $minDate }}" required>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            `;

            $('#accordionPhase').append(html);
        });

        function toggleAddPhaseButton(){
            let totalPercent = 0;

            $('input[name="toppc[]"]').each(function(){
                totalPercent += parseFloat($(this).val()) || 0;
            });

            if(totalPercent >= 100){
                $('#btn-add-row-phase')
                    .prop('disabled', true)
                    .removeClass('btn-primary')
                    .addClass('btn-secondary');

                $('#top-warning').removeClass('d-none');
            }else{
                $('#btn-add-row-phase')
                    .prop('disabled', false)
                    .removeClass('btn-secondary')
                    .addClass('btn-primary');

                $('#top-warning').addClass('d-none');
            }
        }

        $(document).on('input', 'input[name="toppc[]"]', function(){

            let current = $(this);

            let totalOther = 0;

            $('input[name="toppc[]"]').not(current).each(function(){
                totalOther += parseFloat($(this).val()) || 0;
            });

            let currentValue = parseFloat(current.val()) || 0;

            let maxAllowed = 100 - totalOther;

            if(currentValue > maxAllowed){
                current.val(maxAllowed);
            }

            updateTerminPhase();
        });

        function removePhaseRow(index){
            $('#rowPhase_' + index).remove();
            
            toggleAddPhaseButton();
            updateTerminPhase();
        }
    </script>
@endpush