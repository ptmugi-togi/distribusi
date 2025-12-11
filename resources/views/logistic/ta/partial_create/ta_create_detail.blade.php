{{-- TA (STOCK REQUISITION) --}}
<div class="row">
  <h4 class="my-2">TA Detail</h4>
  <div class="accordion" id="accordionTA">
    @foreach (old('opron', [null]) as $i => $oldOpron)
      <div class="accordion-item" id="accordion-ta-item-{{ $i }}">
        <h2 class="accordion-header d-flex justify-content-between align-items-center" id="heading-ta-{{ $i }}">
          <button class="accordion-button {{ $i > 0 ? 'collapsed' : '' }}" type="button"
            data-bs-toggle="collapse" data-bs-target="#details-ta-{{ $i }}"
            aria-expanded="{{ $i == 0 ? 'true' : 'false' }}" aria-controls="details-ta-{{ $i }}">
            <span class="accordion-title"></span>
          </button>
          @if($i > 0)
            <button type="button" class="btn btn-sm btn-danger mx-2" onclick="removeTA({{ $i }})">
              <i class="bi bi-trash-fill"></i>
            </button>
          @endif
        </h2>

        <div id="details-ta-{{ $i }}" class="accordion-collapse collapse {{ $i == 0 ? 'show' : '' }}"
          aria-labelledby="heading-ta-{{ $i }}" data-bs-parent="#accordionTA">
          <div class="accordion-body">
            <div class="row">
                <div class="col-md-6 mt-3">
                    <label class="form-label">Barang</label><span class="text-danger"> *</span>
                    <select class="select2 form-control opron-ta" name="opron[]" id="opron-ta-{{ $i }}" required>
                        <option value="" disabled {{ old('opron.'.$i) ? '' : 'selected' }}>Pilih Stock Requisition Terlebih Dahulu</option>
                    </select>
                </div>

                <div class="col-md-6 mt-3">
                    <label class="form-label">Serial Number</label><span class="text-danger"> *</span>
                    <select class="select2 form-control lotno-ta" name="lotno[]" id="lotno-ta-{{ $i }}" required>
                        <option value="" disabled {{ old('lotno.'.$i) ? '' : 'selected' }}>Pilih Barang Terlebih Dahulu</option>
                    </select>
                </div>

                <div class="col-md-6 mt-3">
                    <label for="rqqty-ta-{{ $i }}" class="form-label">Outstanding Quantity</label>
                    <div class="input-group">
                        <input type="number" class="form-control rqqty-ta" id="rqqty-ta-{{ $i }}" name="rqqty[]" value="{{ old('rqqty.'.$i) }}" min="1" required
                        oninput="this.value = this.value.replace(/[^0-9]/g, '');" disabled>
                        <span id="unit-label-tao-{{ $i }}" class="input-group-text unit-label-ta"></span>
                        <input type="text" class="qunit-ta" name="qunit[]" id="qunit-ta-{{ $i }}" hidden>
                    </div>
                </div>

                <div class="col-md-6 mt-3">
                    <label for="trqty-ta-{{ $i }}" class="form-label">Issue Quantity</label><span class="text-danger"> *</span>
                    <div class="input-group">
                        <input type="number" class="form-control trqty-ta" id="trqty-ta-{{ $i }}" name="trqty[]" value="{{ old('trqty.'.$i) }}" min="1" required
                        oninput="this.value = this.value.replace(/[^0-9]/g, '');">
                        <span id="unit-label-ta-{{ $i }}" class="input-group-text unit-label-ta"></span>
                    </div>
                </div>

                <div class="col-md-6 mt-3">
                    <label for="locco-ta-{{ $i }}" class="form-label">Warehouse Location</label><span class="text-danger"> *</span>
                    <input type="text" class="form-control locco-ta" id="locco-ta-{{ $i }}" name="locco[]" value="{{ old('aloka.'.$i) }}" required readonly style="background-color:#e9ecef">
                </div>
                
                <div class="col-md-12 mt-3">
                    <label class="form-label">Notes</label>
                    <textarea type="text" class="form-control" name="noted[]" id="noted-ta-{{ $i }}" maxlength="200">{{ old('noted.'.$i) }}</textarea>
                    <div class="form-text text-danger text-end" style="font-size:0.7rem;">Maksimal 200 karakter</div>
                </div>
            </div>
          </div>
        </div>
      </div>
    @endforeach
  </div>

  <div class="text-end">
    <button type="button" class="btn mt-3" style="background-color:#4456f1;color:#fff" onclick="addTA()">Tambah Detail TA</button>
  </div>
</div>

@push('scripts')
<script>
    $(document).ready(function(){
        $('.select2').select2({ width:'100%', theme: 'bootstrap-5' });
    })

    // PILIH BARANG
    $('#sano').on('change', function () { 
        let sano = $(this).val();
        let $itemSelect = $('.opron-ta');
        let formc = $(this).find(':selected').data('formc');
        let reqno = $(this).find(':selected').data('reqno');

        $itemSelect.html('<option>Loading...</option>');

        $.get('/get-barang-ra/' + sano, function(res) {
            $itemSelect.empty();
            
            $itemSelect.append('<option value="" disabled selected>Pilih Barang</option>');

            res.forEach(item => {
                $itemSelect.append(`
                    <option value="${item.opron}" data-rqqty="${item.rqqty}" data-stdqu="${item.stdqu}">
                        ${item.opron} - ${item.prona}
                    </option>
                `);
            });
        });
        
        $('#rfc01').val(formc);
        $('#ref01').val(reqno);
    });

    // PILIH LOTNO
    $(document).on('change', '.opron-ta', function () {
        const opron = $(this).val();
        const idx = this.id.split('-').pop();
        const selected = $(this).find('option:selected');
        const unit = selected.data('stdqu') ?? '-';
        const ra_id = $('#sano').val();

        const $itemSelect = $(`#lotno-ta-${idx}`);
        $itemSelect.html('<option>Loading...</option>').prop('disabled', true);

        $.get(`/get-lotno/${ra_id}/${opron}`, function (res) {
            $itemSelect.empty();

            if (!res.length) {
                $itemSelect.append('<option disabled selected>Tidak ada stok</option>');
                return;
            }

            $itemSelect.append('<option disabled selected>Pilih Serial Number</option>');
            $itemSelect.prop('disabled', false);

            res.forEach(item => {
                $itemSelect.append(`
                    <option value="${item.lotno}"
                            data-toqoh="${item.toqoh}"
                            data-locco="${item.locco}">
                        ${item.lotno} (Stok: ${item.toqoh})
                    </option>
                `);
            });
        })
        $(`#unit-label-tao-${idx}`).text(unit);
        $(`#unit-label-ta-${idx}`).text(unit);
        $(`#qunit-ta-${idx}`).val(unit);

        $(`#rqqty-ta-${idx}`).val('');
        $(`#locco-ta-${idx}`).val('');
    });

    // Ketika user pilih LOTNO -> update qty & unit
    $(document).on('change', '[id^="lotno-ta-"]', function () {
        const idx = this.id.split('-').pop();
        const selected = $(this).find('option:selected');

        const qty = selected.data('toqoh') ?? '';
        const locco = selected.data('locco') ?? '-';

        $(`#rqqty-ta-${idx}`).val(qty);
        $(`#locco-ta-${idx}`).val(locco);
    });

    $(document).on('input', '.trqty-ta', function() {
        const idx = this.id.split('-').pop();
        const qty = parseFloat($(this).val()) || 0;
        const max = parseFloat($(`#rqqty-ta-${idx}`).val()) || 0;

        if (qty > max) {
            Swal.fire({
            icon: 'error',
            title: 'Qty Melebihi Stok',
            text: `Jumlah input (${qty}) melebihi stok tersedia (${max}).`
            });
            $(this).val(max);
        }
    });

    // add/remove row TA
    window.addTA = function(){
        const sano = $('#sano').val();
        const i = $('#accordionTA .accordion-item').length;
        const dtl = `
        <div class="accordion-item" id="accordion-ta-item-${i}">
            <h2 class="accordion-header d-flex justify-content-between align-items-center" id="heading-ta-${i}">
            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#details-ta-${i}" aria-expanded="false" aria-controls="details-ta-${i}"><span class="accordion-title"></span></button>
            <button type="button" class="btn btn-sm btn-danger mx-2" onclick="removeTA(${i})"><i class="bi bi-trash-fill"></i></button>
            </h2>
            <div id="details-ta-${i}" class="accordion-collapse collapse" aria-labelledby="heading-ta-${i}" data-bs-parent="#accordionTA">
                <div class="accordion-body">
                    <div class="row">
                        <div class="col-md-6 mt-3">
                            <label class="form-label">Barang</label><span class="text-danger"> *</span>
                            <select class="select2 form-control opron-ta" name="opron[]" id="opron-ta-${i}" required>
                                <option value="" disabled selected>Pilih Stock Requisition Terlebih Dahulu</option>
                            </select>
                        </div>
                        
                        <div class="col-md-6 mt-3">
                            <label class="form-label">Serial Number</label><span class="text-danger"> *</span>
                            <select class="select2 form-control lotno-ta" name="lotno[]" id="lotno-ta-${i}" required>
                                <option value="" disabled selected>Pilih Barang Terlebih Dahulu</option>
                            </select>
                        </div>

                        <div class="col-md-6 mt-3">
                            <label for="rqqty-ta-${i}" class="form-label">Outstanding Quantity</label>
                            <div class="input-group">
                                <input type="number" class="form-control rqqty-ta" id="rqqty-ta-${i}" name="rqqty[]" value="{{ old('rqqty.'.$i) }}" min="1" required
                                oninput="this.value = this.value.replace(/[^0-9]/g, '');" disabled>
                                <span id="unit-label-tao-${i}" class="input-group-text unit-label-ta"></span>
                                <input type="text" id="qunit-ta-${i}" name="qunit[]" hidden>
                            </div>
                        </div>

                        <div class="col-md-6 mt-3">
                            <label for="trqty-ta-${i}" class="form-label">Issue Quantity</label><span class="text-danger"> *</span>
                            <div class="input-group">
                                <input type="number" class="form-control trqty-ta" id="trqty-ta-${i}" name="trqty[]" value="{{ old('trqty.'.$i) }}" min="1" required
                                oninput="this.value = this.value.replace(/[^0-9]/g, '');">
                                <span id="unit-label-ta-${i}" class="input-group-text unit-label-ta"></span>
                            </div>
                        </div>

                        <div class="col-md-6 mt-3">
                            <label for="locco-ta-${i}" class="form-label">Warehouse Location</label><span class="text-danger"> *</span>
                            <input type="text" class="form-control locco-ta" id="locco-ta-${i}" name="locco[]" value="{{ old('aloka.'.$i) }}" required readonly style="background-color:#e9ecef">
                        </div>

                        <div class="col-md-12 mt-3">
                            <label class="form-label">Notes</label>
                            <textarea class="form-control" name="noted[]" id="noted-ta-${i}" maxlength="200"></textarea>
                            <div class="form-text text-danger text-end" style="font-size:0.7rem;">Maksimal 200 karakter</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>`;
        $('#accordionTA').append(dtl);
        $('.select2').select2({ width:'100%', theme: 'bootstrap-5' });
        setTimeout(()=>{
            $(`#details-ta-${i}`).collapse('show');
        },100);
        const $itemSelect = $(`#opron-ta-${i}`);

        $itemSelect.html('<option>Loading...</option>');

        $.get('/get-barang-ra/' + sano, function(res) {
            $itemSelect.empty();
            $itemSelect.append('<option value="" disabled selected>Pilih Barang</option>');

            res.forEach(item => {
                $itemSelect.append(`
                    <option value="${item.opron}" data-rqqty="${item.rqqty}" data-stdqu="${item.stdqu}">
                        ${item.opron} - ${item.prona}
                    </option>
                `);
            });
        });
    }

    window.removeTA = function(i){
        $(`#accordion-ta-item-${i}`).remove();
    }
</script>
@endpush

