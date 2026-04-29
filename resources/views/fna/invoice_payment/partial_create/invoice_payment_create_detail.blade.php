<div class="row">
    <h4 class="my-2">Invoice Payment</h4>
    <div class="accordion" id="accordionInvoicePayment">
        @foreach (array_values(old('invno', [null])) as $i => $oldInvno)
        <div class="accordion-item" id="row_{{ $i }}">
            <h2 class="accordion-header d-flex align-items-center">
                <button class="accordion-button {{ $i > 0 ? 'collapsed' : '' }}" type="button" data-bs-toggle="collapse" data-bs-target="#collapse{{ $i }}">
                    Invoice {{ $i + 1 }}
                </button>

                @if($i > 0)
                <button type="button" class="btn btn-danger btn-sm ms-2" onclick="removeRow({{ $i }})">
                    <i class="bi bi-trash"></i>
                </button>
                @endif
            </h2>

            <div id="collapse{{ $i }}"
                class="accordion-collapse collapse {{ $i == 0 ? 'show' : '' }}">
                <div class="accordion-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Invoice No</label><span class="text-danger"> *</span>
                            <select name="invno[]" id="invno_{{ $i }}" class="form-control select2 invoice-select" data-index="{{ $i }}" required> <option value="">Loading...</option>
                            </select>
                            <input type="hidden" name="formc_inv[]" id="formc_inv_{{ $i }}">
                            <input type="hidden" name="invno_raw[]" id="invno_raw_{{ $i }}">
                            <input type="hidden" name="cusno[]" id="cusno_{{ $i }}">
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Due Date</label>
                            <input type="text" name="duedt[]" id="duedt_{{ $i }}" class="form-control" readonly style="background-color: #E9ECEF">
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Bill Amount</label>
                            <input type="text" id="blamt_{{ $i }}" class="form-control" readonly style="background-color: #E9ECEF">
                            <input type="hidden" name="blamt[]" id="blamt_raw_{{ $i }}">
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">A/R Value</label>
                            <input type="text" id="arval_{{ $i }}" class="form-control" readonly style="background-color: #E9ECEF">
                            <input type="hidden" name="arval[]" id="arval_raw_{{ $i }}">
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Payment Value</label>
                            <input type="text" id="pcval_{{ $i }}" class="form-control price-input" data-index="{{ $i }}">
                            <input type="hidden" name="pcval[]" id="pcval_raw_{{ $i }}">
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Payment Write Off</label>
                            <input type="text" id="pcwo_{{ $i }}" class="form-control price-input" data-index="{{ $i }}">
                            <input type="hidden" name="pcwo[]" id="pcwo_raw_{{ $i }}">
                        </div>

                        <div class="col-md-12 mt-3">
                            <label for="noted" class="form-label">Detail Notes</label>
                            <textarea class="form-control" name="noted[]" id="noted_{{ $i }}" rows="4"></textarea>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endforeach

    </div>

    <div class="text-end mt-3">
        <button type="button" class="btn" id="btn-add-row" style="background-color:#4456f1;color:#fff">
            Tambah Detail
        </button>
    </div>
</div>


@push('scripts')
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

$(document).ready(function(){

    $('.select2').select2({
        width:'100%',
        theme:'bootstrap-5'
    });

    $('.invoice-select').each(function(){
        loadInvoice($(this));
    });

});

function loadInvoice(el){

    el.prop('disabled', true);

    $.get("{{ route('get-invoice') }}", function(data){

        el.empty();
        el.append(`<option value="" disabled selected>Pilih Invoice</option>`);

        data.forEach(function(item){

            el.append(`
                <option value="${item.value}"
                    data-formc="${item.formc}"
                    data-invno="${item.invno}"
                    data-duedt="${item.duedt}"
                    data-blamt="${item.blamt}"
                    data-arval="${item.arval}"
                    data-cusno="${item.cusno}"
                    data-cusna="${item.cusna}"
                    data-sreno="${item.sreno}"
                    data-curco="${item.curco}"
                    data-crate="${item.crate}"
                    >
                    ${item.text} (${item.cusna})
                </option>
            `);

        });

        el.prop('disabled', false);
    });
}

$(document).on('change','.invoice-select',function(){
    let index = $(this).data('index');
    let selected = $(this).find(':selected');

    let curco = selected.data('curco') || 'IDR';
    let prate = selected.data('crate') || 1;

    $('#formc_inv_' + index).val(selected.data('formc'));
    $('#invno_raw_' + index).val(selected.data('invno'));
    $('#duedt_' + index).val(selected.data('duedt'));
    $('#cusno_' + index).val(selected.data('cusno'));

    $('#blamt_' + index).val(formatCurrency(selected.data('blamt'), curco));
    $('#blamt_raw_' + index).val(selected.data('blamt'));

    $('#arval_' + index).val(formatCurrency(selected.data('arval'), curco));
    $('#arval_raw_' + index).val(selected.data('arval'));

    $('#curco').val(curco);
    $('#prate_raw').val(prate);
    $('#prate_display').val(formatRupiah(prate));
    toggleprateField(curco, prate);
});

$('#btn-add-row').click(function(){

    let detailIndex = getNextDetailIndex();

    let html = `
    <div class="accordion-item mb-3" id="row_${detailIndex}">

        <h2 class="accordion-header d-flex align-items-center">
            <button class="accordion-button collapsed"
                type="button"
                data-bs-toggle="collapse"
                data-bs-target="#collapse${detailIndex}">

                Invoice ${detailIndex + 1}
            </button>

            <button type="button" class="btn btn-danger btn-sm ms-2" onclick="removeRow(${detailIndex})">
                <i class="bi bi-trash"></i>
            </button>
        </h2>

        <div id="collapse${detailIndex}" class="accordion-collapse collapse show">
            <div class="accordion-body">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Invoice No</label><span class="text-danger"> *</span>
                        <select name="invno[]" id="invno_${detailIndex}" class="form-control select2 invoice-select" data-index="${detailIndex}">
                        </select>
                        <input type="hidden" name="formc_inv[]" id="formc_inv_${detailIndex}">
                        <input type="hidden" name="invno_raw[]" id="invno_raw_${detailIndex}">
                        <input type="hidden" name="cusno[]" id="cusno_${detailIndex}">
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Due Date</label>
                        <input type="text" name="duedt[]" id="duedt_${detailIndex}" class="form-control" readonly style="background-color: #E9ECEF">
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Bill Amount</label>
                        <input type="text" id="blamt_${detailIndex}" class="form-control" readonly style="background-color: #E9ECEF">
                        <input type="hidden" name="blamt[]" id="blamt_raw_${detailIndex}">
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">A/R Value</label>
                        <input type="text" id="arval_${detailIndex}" class="form-control" readonly style="background-color: #E9ECEF">
                        <input type="hidden" name="arval[]" id="arval_raw_${detailIndex}">
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Payment Value</label>
                        <input type="text" id="pcval_${detailIndex}" class="form-control price-input" data-index="${detailIndex}">
                        <input type="hidden" name="pcval[]" id="pcval_raw_${detailIndex}">
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Write Off</label>
                        <input type="text" id="pcwo_${detailIndex}" class="form-control price-input" data-index="${detailIndex}">
                        <input type="hidden" name="pcwo[]" id="pcwo_raw_${detailIndex}">
                    </div>
                    
                    <div class="col-md-12 mt-3">
                        <label for="noted" class="form-label">Detail Notes</label>
                        <textarea class="form-control" name="noted[]" id="noted_${detailIndex}" rows="4"></textarea>
                    </div>
                </div>
            </div>
        </div>
    </div>
    `;

    $('#accordionInvoicePayment').append(html);

    $('#invno_' + detailIndex).select2({
        width:'100%',
        theme:'bootstrap-5'
    });

    loadInvoice($('#invno_' + detailIndex));

    detailIndex++;

});

function removeRow(index){

    $('#row_' + index).remove();

    updateTotal();
}

$(document).on('focus', '.price-input', function () {

    let index = $(this).data('index');
    let field = $(this).attr('id').split('_')[0];

    let raw = $('#' + field + '_raw_' + index).val();

    if(raw){
        $(this).val(raw);
    }
});

$(document).on('input', '.price-input', function () {

    let index = $(this).data('index');
    let field = $(this).attr('id').split('_')[0];

    let value = $(this).val().replace(/\./g, '').replace(/[^0-9]/g, '');

    $(this).val(value);
    $('#' + field + '_raw_' + index).val(value);

    validateRow(index);
    updateTotal();
});

$(document).on('blur', '.price-input', function () {

    let index = $(this).data('index');
    let field = $(this).attr('id').split('_')[0];

    let raw = $('#' + field + '_raw_' + index).val();

    if(raw){
        let curco = $('#curco').val() || 'IDR';
        $(this).val(formatCurrency(raw, curco));
    }
});

function validateRow(index){
    let pcval = parseFloat($('#pcval_raw_' + index).val()) || 0;
    let pcwo  = parseFloat($('#pcwo_raw_' + index).val()) || 0;
    let arval = parseFloat($('#arval_raw_' + index).val()) || 0;

    if((pcval + pcwo) > arval){

        Swal.fire({
            icon:'error',
            title:'Melebihi A/R Value',
            text:'Payment + Write Off tidak boleh melebihi A/R Value'
        });

        $('#pcval_' + index).val('');
        $('#pcval_raw_' + index).val('');

        $('#pcwo_' + index).val('');
        $('#pcwo_raw_' + index).val('');

        updateTotal();
    }
}

function updateTotal(){

    let total = 0;
    let curco = $('#curco').val() || 'IDR';

    $('input[id^="pcval_raw_"]').each(function(){
        total += parseFloat($(this).val()) || 0;
    });

    $('input[id^="pcwo_raw_"]').each(function(){
        total += parseFloat($(this).val()) || 0;
    });

    $('#total_raw').val(total);
    $('#total').val(formatCurrency(total, curco));
}

function formatRupiah(angka){

    angka = angka || 0;

    return new Intl.NumberFormat('id-ID',{
        style:'currency',
        currency:'IDR',
        minimumFractionDigits:0
    }).format(angka);
}

</script>
@endpush