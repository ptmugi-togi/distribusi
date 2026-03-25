{{-- DO (Delivery Order) --}}
<div class="row">
  <h4 class="my-2">DO Detail</h4>
  <div class="accordion" id="accordionDO">
    @foreach (old('opron', [null]) as $i => $oldOpron)
      <div class="accordion-item" id="accordion-do-item-{{ $i }}">
        <h2 class="accordion-header d-flex justify-content-between align-items-center" id="heading-do-{{ $i }}">
          <button class="accordion-button {{ $i > 0 ? 'collapsed' : '' }}" type="button"
            data-bs-toggle="collapse" data-bs-target="#details-do-{{ $i }}"
            aria-expanded="{{ $i == 0 ? 'true' : 'false' }}" aria-controls="details-do-{{ $i }}">
            <span class="accordion-title"></span>
          </button>
          @if($i > 0)
            <button type="button" class="btn btn-sm btn-danger mx-2" onclick="removeDO({{ $i }})">
              <i class="bi bi-trash-fill"></i>
            </button>
          @endif
        </h2>

        <div id="details-do-{{ $i }}" class="accordion-collapse collapse {{ $i == 0 ? 'show' : '' }}"
          aria-labelledby="heading-do-{{ $i }}" data-bs-parent="#accordionDO">
          <div class="accordion-body">
            <div class="row">
                <div class="col-md-6 mt-3">
                    <label class="form-label">Barang</label><span class="text-danger"> *</span>
                    <select class="select2 form-control opron-do" name="opron[]" id="opron-do-{{ $i }}" required>
                        <option value="" disabled {{ old('opron.'.$i) ? '' : 'selected' }}>Pilih No OC Terlebih Dahulu</option>
                    </select>
                </div>

                <div class="col-md-3 mt-3">
                    <label for="rqqty-do-{{ $i }}" class="form-label">Outstanding Quantity</label>
                    <div class="input-group">
                        <input type="number" class="form-control rqqty-do" id="rqqty-do-{{ $i }}" name="rqqty[]" value="{{ old('rqqty.'.$i) }}" min="1" required
                        oninput="this.value = this.value.replace(/[^0-9]/g, '');" disabled>
                        <span id="unit-label-oc-{{ $i }}" class="input-group-text unit-label-do"></span>
                        <input type="text" class="qunit-do" name="qunit[]" id="qunit-do-{{ $i }}" hidden>
                    </div>
                </div>

                <div class="col-md-3 mt-3">
                    <label for="trqty-do-{{ $i }}" class="form-label">Stock Available</label>
                    <div class="input-group">
                        <input type="number" class="form-control toqoh-do" id="toqoh-do-{{ $i }}" name="toqoh[]" value="{{ old('toqoh.'.$i) }}" disabled>
                        <span id="unit-label-st-{{ $i }}" class="input-group-text unit-label-do"></span>
                    </div>
                </div>

                <div class="col-md-6 mt-3">
                    <label class="form-label">Serial Number</label><span class="text-danger"> *</span>
                    <select class="select2 form-control lotno-do" name="lotno[]" id="lotno-do-{{ $i }}" required>
                        <option value="" disabled {{ old('lotno.'.$i) ? '' : 'selected' }}>Pilih Barang Terlebih Dahulu</option>
                    </select>
                </div>

                <div class="col-md-6 mt-3">
                    <label for="trqty-do-{{ $i }}" class="form-label">Issue Quantity</label><span class="text-danger"> *</span>
                    <div class="input-group">
                        <input type="number" class="form-control trqty-do" id="trqty-do-{{ $i }}" name="trqty[]" value="{{ old('trqty.'.$i) }}" min="1" required
                        oninput="this.value = this.value.replace(/[^0-9]/g, '');">
                        <span id="unit-label-do-{{ $i }}" class="input-group-text unit-label-do"></span>
                    </div>
                </div>

                <div class="col-md-6 mt-3">
                    <label for="locco-do-{{ $i }}" class="form-label">Warehouse Location</label><span class="text-danger"> *</span>
                    <input type="text" class="form-control locco-do" id="locco-do-{{ $i }}" name="locco[]" value="{{ old('aloka.'.$i) }}" required readonly style="background-color:#e9ecef">
                </div>
                
                <div class="col-md-12 mt-3">
                    <label class="form-label">Notes</label>
                    <textarea type="text" class="form-control" name="noted[]" id="noted-do-{{ $i }}" maxlength="200">{{ old('noted.'.$i) }}</textarea>
                    <div class="form-text text-danger text-end" style="font-size:0.7rem;">Maksimal 200 karakter</div>
                </div>
            </div>
          </div>
        </div>
      </div>
    @endforeach
  </div>

  <div class="text-end">
    <button type="button" class="btn mt-3" style="background-color:#4456f1;color:#fff" onclick="addDO()">Tambah Detail DO</button>
  </div>
</div>

@push('scripts')
<script>
    $(document).ready(function(){
        $('.select2').select2({ width:'100%', theme: 'bootstrap-5' });
    })

    // PILIH BARANG
    $('#ocno').on('change', function(){

        let selectedText = $("#ocno option:selected").text();
        let parts = selectedText.split(' - ');

        let type = parts[0] || '';
        let sorno = parts[1] ? parts[1].split('(')[0].trim() : '';

        $('#rfc01').val(type);
        $('#ref01').val(sorno);

        let $itemSelect = $('.opron-do');

        // reset select2 dengan benar
        $itemSelect.empty().append('<option>Loading...</option>');
        $itemSelect.trigger('change.select2');

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
                            data-stdqu="${item.stdqu}"
                            data-toqoh="${item.toqoh}">
                        ${item.opron} - ${item.prona}
                    </option>
                `);
            });

            // refresh select2 clean
            $itemSelect.val(null).trigger('change.select2');
        });

    });

    // PILIH LOTNO
    $(document).on('change', '.opron-do', function () {

        const opron = $(this).val();
        const idx = this.id.split('-').pop();

        const selected = $(this).find('option:selected');

        const unit = selected.data('stdqu') ?? '-';
        const qty  = selected.data('qty') ?? 0;
        const toqoh = selected.data('toqoh') ?? 0;

        $(`#rqqty-do-${idx}`).val(qty);

        // set unit
        $(`#unit-label-oc-${idx}`).text(unit);
        $(`#unit-label-st-${idx}`).text(unit);
        $(`#unit-label-do-${idx}`).text(unit);
        $(`#qunit-do-${idx}`).val(unit);
        $(`#toqoh-do-${idx}`).val(toqoh);
        $(`#locco-do-${idx}`).val('');

        const $itemSelect = $(`#lotno-do-${idx}`);

        $itemSelect.empty()
            .append('<option disabled selected>Loading...</option>')
            .prop('disabled', true)
            .trigger('change.select2');

        $.get("{{ route('get-lot-oc') }}", {opron}, function (res) {

            $itemSelect.empty();

            if (!res.length) {
                $itemSelect.append('<option disabled selected>Tidak ada stok</option>');
                return;
            }

            $itemSelect.prop('disabled', false);
            $itemSelect.append('<option value="" disabled selected>Pilih Serial Number</option>');

            res.forEach(item => {
                $itemSelect.append(`
                    <option value="${item.lotno}"
                            data-locco="${item.locco}">
                        ${item.lotno}
                    </option>
                `);
            });

            $itemSelect.val(null).trigger('change.select2');
        });

    });

    // Ketika user pilih LOTNO -> update qty & unit
    $(document).on('change', '[id^="lotno-do-"]', function () {
        const idx = this.id.split('-').pop();
        const selected = $(this).find('option:selected');

        const locco = selected.data('locco') ?? '-';

        $(`#locco-do-${idx}`).val(locco);
    });

    $(document).on('input', '.trqty-do', function() {
        const idx = this.id.split('-').pop();
        const qty = parseFloat($(this).val()) || 0;

        const maxOrder = parseFloat($(`#rqqty-do-${idx}`).val()) || 0;
        const maxStock = parseFloat($(`#toqoh-do-${idx}`).val()) || 0;

        if (qty > maxOrder) {
            Swal.fire({
                icon: 'error',
                title: 'Qty Melebihi Batas',
                text: `DO Qty Melebihi OC QTY.`
            });
            $(this).val(maxOrder);
        }

        if (qty > maxStock) {
            Swal.fire({
                icon: 'error',
                title: 'Qty Melebihi Batas',
                text: `DO Qty Melebihi Stock.`
            });
            $(this).val(maxStock);
        }
    });

    // add/remove row DO
    window.addDO = function(){
        const ocno = $('#ocno').val();
        const i = $('#accordionDO .accordion-item').length;
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
                                <option value="" disabled selected>Pilih No OC Terlebih Dahulu</option>
                            </select>
                        </div>

                        <div class="col-md-3 mt-3">
                            <label for="rqqty-do-${i}" class="form-label">Outstanding Quantity</label>
                            <div class="input-group">
                                <input type="number" class="form-control rqqty-do" id="rqqty-do-${i}" name="rqqty[]" value="{{ old('rqqty.'.$i) }}" min="1" required
                                oninput="this.value = this.value.replace(/[^0-9]/g, '');" disabled>
                                <span id="unit-label-oc-${i}" class="input-group-text unit-label-do"></span>
                                <input type="text" id="qunit-do-${i}" name="qunit[]" hidden>
                            </div>
                        </div>

                        <div class="col-md-3 mt-3">
                            <label for="trqty-do-${i}" class="form-label">Stock Available</label>
                            <div class="input-group">
                                <input type="number" class="form-control toqoh-do" id="toqoh-do-${i}" name="toqoh[]" value="{{ old('toqoh.'.$i) }}" disabled>
                                <span id="unit-label-st-${i}" class="input-group-text unit-label-do"></span>
                            </div>
                        </div>
                        
                        <div class="col-md-6 mt-3">
                            <label class="form-label">Serial Number</label><span class="text-danger"> *</span>
                            <select class="select2 form-control lotno-do" name="lotno[]" id="lotno-do-${i}" required>
                                <option value="" disabled selected>Pilih Barang Terlebih Dahulu</option>
                            </select>
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

        let type = $('#rfc01').val();
        let sorno = $('#ref01').val();
        const $itemSelect = $(`#opron-do-${i}`);

        $itemSelect.empty().append('<option>Loading...</option>');
        $itemSelect.trigger('change.select2');

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
                            data-stdqu="${item.stdqu}"
                            data-toqoh="${item.toqoh}">
                        ${item.opron} - ${item.prona}
                    </option>
                `);
            });

            // refresh select2 clean
            $itemSelect.val(null).trigger('change.select2');
        });
    }

    window.removeDO = function(i){
        $(`#accordion-do-item-${i}`).remove();
    }
</script>
@endpush

