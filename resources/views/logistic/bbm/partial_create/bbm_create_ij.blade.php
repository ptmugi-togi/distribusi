{{-- IJ ( EX WO ) --}}
<div class="row mt-4">
    <div class="col-md-6 mt-3">
        <label class="form-label">Production Order</label><span class="text-danger"> *</span>
        <select class="form-control select2" name="refcno" id="refcno_ij">
            <option value="" disabled selected>Pilih Production Order</option>
        </select>
        <input type="text" name="reffc" id="reffc-store" hidden>
        <input type="text" name="refno" id="refno-store" hidden>
    </div>

    <div class="col-md-12 mt-3">
        <label for="noteh_ij" class="form-label">Notes</label>
        <textarea class="form-control" name="noteh" id="noteh_ij" maxlength="200">{{ old('noteh') }}</textarea>
        <div class="form-text text-danger text-end" style="font-size:0.7rem;">Maksimal 200 karakter</div>
    </div>
</div>

<div class="row">
  <h4 class="my-2">BBM Detail (IJ)</h4>
  <div class="accordion" id="accordionIJ">
    @foreach (old('opron', [null]) as $i => $oldOpron)
      <div class="accordion-item" id="accordion-ij-item-{{ $i }}">
        <h2 class="accordion-header d-flex justify-content-between align-items-center" id="heading-ij-{{ $i }}">
          <button class="accordion-button {{ $i > 0 ? 'collapsed' : '' }}" type="button"
            data-bs-toggle="collapse" data-bs-target="#details-ij-{{ $i }}"
            aria-expanded="{{ $i == 0 ? 'true' : 'false' }}" aria-controls="details-ij-{{ $i }}">
            <span class="accordion-title"></span>
          </button>
          @if($i > 0)
            <button type="button" class="btn btn-sm btn-danger mx-2" onclick="removeIL({{ $i }})">
              <i class="bi bi-trash-fill"></i>
            </button>
          @endif
        </h2>

        <div id="details-ij-{{ $i }}" class="accordion-collapse collapse {{ $i == 0 ? 'show' : '' }}"
          aria-labelledby="heading-ij-{{ $i }}" data-bs-parent="#accordionIJ">
          <div class="accordion-body">
            <div class="row">
                <input type="text" name="invno[]" class="invno-ij" id="invno-ij-{{ $i }}" value="-" hidden>

                <div class="col-md-6 mt-3">
                    <label class="form-label">Barang</label><span class="text-danger"> *</span>
                    <select class="select2 form-control opron-ij" name="opron[]" id="opron-ij-{{ $i }}" required>
                    <option value="" disabled {{ old('opron.'.$i) ? '' : 'selected' }}>Pilih Barang</option>
                    </select>
                </div>

                <input type="text" class="stdqt-ij" name="stdqt[]" id="stdqt-ij-{{ $i }}" hidden>

                <div class="col-md-6 mt-3">
                    <label for="trqty-ij-{{ $i }}" class="form-label">Transfer Quantity</label><span class="text-danger"> *</span>
                    <div class="input-group">
                        <input type="number" class="form-control trqty-ij" id="trqty-ij-{{ $i }}" name="trqty[]" value="{{ old('trqty.'.$i) }}" required
                        oninput="this.value = this.value.replace(/[^0-9]/g, '');">
                        <span class="input-group-text unit-label-ij"></span>
                    </div>
                </div>

                <div class="col-md-6 mt-4">
                    <div class="form-check mt-3">
                        <input class="form-check-input nolot-checkbox" type="checkbox" value="1" name="nolot[{{ $i }}]" id="nolot-{{ $i }}">
                        <label class="form-check-label" for="nolot-{{ $i }}">
                            Without Serial / Batch No
                        </label>
                    </div>
                </div>

                <div class="col-md-6 mt-3 lot-section">
                    <label for="lotno-ij-{{ $i }}" class="form-label">Serial / Batch No.</label>
                    <input type="text" class="form-control lotno-input" name="lotno[]" id="lotno-ij-{{ $i }}" value="{{ old('lotno.'.$i) }}">
                </div>

                <div class="col-md-6 mt-3 lot-section">
                    <label for="lotnoend-ij-{{ $i }}" class="form-label">Serial / Batch No. (Akhir)</label>
                    <input type="text" class="form-control lotnoend-ij" name="lotnoend[]" id="lotnoend-ij-{{ $i }}" readonly style="background-color:#e9ecef;" value="{{ old('lotnoend.'.$i) }}">
                </div>

                <div class="col-md-6 mt-3">
                    <label class="form-label">Location</label>
                    <select class="form-control select2" name="locco[]" id="locco-ij-{{ $i }}" required>
                    <option value="" disabled selected>Pilih Warehouse terlebih dahulu</option>
                    </select>
                </div>

                <div class="col-md-12 mt-3">
                    <label class="form-label">Notes</label>
                    <textarea type="text" class="form-control" name="noted[]" id="noted-ij-{{ $i }}" maxlength="200">{{ old('noted.'.$i) }}</textarea>
                    <div class="form-text text-danger text-end" style="font-size:0.7rem;">Maksimal 200 karakter</div>
                </div>
            </div>
          </div>
        </div>
      </div>
    @endforeach
  </div>

  <div class="text-end">
    <button type="button" class="btn mt-3" style="background-color:#4456f1;color:#fff" onclick="addIJ()">Tambah Detail (IJ)</button>
  </div>
</div>

@push('scripts')
<script>
    let isLoadingOpronWO = false;

    // locco by warco (ij)
    $('#warco').on('change', function(){
        const warco = $(this).val();
        $('select[id^="locco-ij-"]').each(function(){
        const $sel = $(this);
        $sel.empty().append('<option value="">Loading...</option>');
        $.get(`{{ url('/get-locco') }}/${warco}`, function(data){
            $sel.empty().append('<option disabled selected>Pilih Lokasi</option>');
            data.forEach(item => $sel.append(`<option value="${item.locco}">${item.locco}</option>`));
            $sel.trigger('change.select2');
        });
        });
    });

    $(document).ready(function() {
        $('.select2').select2({ width: '100%', theme: 'bootstrap-5' });
        loadWO();
    })

    // get WO
    function loadWO() {
        const $ref = $('#refcno_ij');
        const braco = $('#braco').val();

        $ref.prop('disabled', true)
            .html('<option disabled selected>Loading Production Order...</option>');

        $.ajax({
            url: "{{ route('get.wo') }}",
            type: "GET",
            data: { braco: braco },
            success(response) {
                $ref.empty();

                if (!Array.isArray(response) || response.length === 0) {
                    $ref.append('<option disabled selected>Tidak ada WO tersedia</option>');
                    return;
                }

                $ref.append('<option disabled selected>Pilih Production Order</option>');

                response.forEach(item => {
                    $ref.append(`
                        <option value="${item.wonum}"
                            data-reffc="${item.formc}"
                            data-refno="${item.wonum}">
                            WO - ${item.wonum}
                        </option>
                    `);
                });

                $ref.prop('disabled', false);
            },
            error(xhr) {
                console.error(xhr.responseText);
                $ref.html('<option disabled selected>Gagal memuat WO</option>');
            }
        });
    }

    $(document).on('change', '#refcno_ij', function () {
        const selected = $(this).find(':selected');

        const reffc = selected.data('reffc');
        const refno = selected.data('refno');
        const isutn = selected.data('isutn');
        const braco = $('#braco').val();
        const wonum = $(this).val();

        $('#isutn_ij').val(isutn);
        $('#reffc-store').val(reffc);
        $('#refno-store').val(refno);
        loadOpronByWO(braco, wonum);
    });

    // fucntion pilih barang (IJ)
    function loadOpronByWO(braco, wonum, idx = null) {
        if (isLoadingOpronWO) return;
        isLoadingOpronWO = true;

        const $targets = (idx !== null)
            ? $(`#opron-ij-${idx}`)
            : $('.opron-ij');

        $targets.each(function () {
            const $sel = $(this);

            if ($sel.hasClass('select2-hidden-accessible')) {
                $sel.select2('destroy');
            }

            $sel.prop('disabled', true).empty();
        });

        $.ajax({
            url: "{{ route('get.opron.by.wo') }}",
            type: "GET",
            data: {
                wonum,
                braco,
            },
            success: function (response) {

                $targets.each(function () {
                    const $sel = $(this);
                    const oldValue = $sel.val();

                    if (!response || response.length === 0) {
                        $sel
                            .append('<option value="" disabled selected>Tidak ada barang tersedia</option>')
                            .prop('disabled', true);
                        return;
                    }

                    $sel.append('<option value="" disabled selected>Pilih Barang</option>');

                    response.forEach(item => {
                        $sel.append(`
                            <option 
                                value="${item.outpr}" 
                                data-qty="${item.outqt}" 
                                data-stdqt="${item.stdqu}"
                            >
                                ${item.outpr} - ${item.prona}
                            </option>
                        `);
                    });

                    if (oldValue && response.some(item => item.opron === oldValue)) {
                    $sel.val(oldValue);
                    }

                    $sel.prop('disabled', false);

                    $sel.select2({
                        width: '100%',
                        theme: 'bootstrap-5'
                    });
                });
                isLoadingOpronWO = false;
            },

            error: function () {
            isLoadingOpronWO = false;
            $('.opron-ij').html('<option>Gagal memuat data</option>').prop('disabled', false);
            }
        });
    }

    $(document).on('change', '.opron-ij', function () {
        if (isLoadingOpronWO) return;
        const $sel = $(this);
        const selected = $sel.find(':selected');

        const idx = this.id.split('-').pop();

        const qty = selected.data('qty');
        const stdqt = selected.data('stdqt');
        const lotno = selected.data('lotno');
        const locco = selected.data('locco');

        $('#stdqt-ij-' + idx).val(stdqt);

        $('#trqty-ij-' + idx).val(qty);
        $('#lotno-ij-' + idx).val(lotno);

        $('#trqty-ij-' + idx)
            .closest('.input-group')
            .find('.unit-label-ij')
            .text(stdqt);

        $('#locco-ij-' + idx).val(locco);
    });

    // auto lot end (IJ)
    $(document).on('input', 'input[id^="lotno-ij-"], input[id^="trqty-ij-"]', function(){
        const idx = this.id.split('-').pop();
        const lotStart = $(`#lotno-ij-${idx}`).val();
        const trqty = parseInt($(`#trqty-ij-${idx}`).val()) || 0;
        if(!lotStart || trqty<=0){ $(`#lotnoend-ij-${idx}`).val(''); return; }

        const matches = [...lotStart.matchAll(/\d+/g)];
        if(matches.length===0){ $(`#lotnoend-ij-${idx}`).val(lotStart); return; }

        let chosen = (matches.length===1) ? matches[0] : matches.reduce((p,c)=> (c[0].length<=p[0].length ? c : p));
        const number = parseInt(chosen[0]), next = number + trqty - 1;
        const paddedNext = String(next).padStart(chosen[0].length,'0');
        const endStr = lotStart.slice(0, chosen.index) + paddedNext + lotStart.slice(chosen.index + chosen[0].length);
        $(`#lotnoend-ij-${idx}`).val(endStr);
    });

    // add/remove row IJ
    window.addIJ = function(){
        const i = $('#accordionIJ .accordion-item').length;
        const dtl = `
        <div class="accordion-item" id="accordion-ij-item-${i}">
            <h2 class="accordion-header d-flex justify-content-between align-items-center" id="heading-ij-${i}">
            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#details-ij-${i}" aria-expanded="false" aria-controls="details-ij-${i}"><span class="accordion-title"></span></button>
            <button type="button" class="btn btn-sm btn-danger mx-2" onclick="removeIJ(${i})"><i class="bi bi-trash-fill"></i></button>
            </h2>
            <div id="details-ij-${i}" class="accordion-collapse collapse" aria-labelledby="heading-ij-${i}" data-bs-parent="#accordionIJ">
            <div class="accordion-body">
                <div class="row">
                <input type="text" name="invno[]" class="invno-ij" id="invno-ij-${i}" value="-" hidden>

                <div class="col-md-6 mt-3">
                    <label class="form-label">Barang</label><span class="text-danger"> *</span>
                    <select class="select2 form-control opron-ij" name="opron[]" id="opron-ij-${i}" required>
                    <option value="" disabled selected>Pilih Barang</option>
                    </select>
                </div>

                <div class="col-md-6 mt-3">
                    <label class="form-label">Transfer Quantity</label><span class="text-danger"> *</span>
                    <div class="input-group">
                    <input type="number" class="form-control trqty-ij" id="trqty-ij-${i}" name="trqty[]" required
                    oninput="this.value = this.value.replace(/[^0-9]/g, '');">
                    <span class="input-group-text unit-label-ij"></span>
                    </div>
                </div>

                <input type="text" class="stdqt-ij" name="stdqt[]" id="stdqt-ij-${i}" hidden>

                <div class="col-md-6 mt-4">
                    <div class="form-check mt-3">
                        <input class="form-check-input nolot-checkbox" type="checkbox" value="1" name="nolot[${i}]" id="nolot-[${i}]">
                        <label class="form-check-label" for="nolot-${i}">
                            Without Serial / Batch No
                        </label>
                    </div>
                </div>

                <div class="col-md-6 mt-3 lot-section">
                    <label class="form-label">Serial / Batch No.</label>
                    <input type="text" class="form-control" name="lotno[]" id="lotno-ij-${i}">
                </div>

                <div class="col-md-6 mt-3 lot-section">
                    <label class="form-label">Serial / Batch No. (Akhir)</label>
                    <input type="text" class="form-control lotnoend-ij" name="lotnoend[]" id="lotnoend-ij-${i}" readonly style="background-color:#e9ecef;">
                </div>

                <div class="col-md-6 mt-3">
                    <label class="form-label">Location</label>
                    <select class="form-control select2" name="locco[]" id="locco-ij-${i}" required>
                    <option value="" disabled selected>Pilih Warehouse terlebih dahulu</option>
                    </select>
                </div>

                <div class="col-md-12 mt-3">
                    <label class="form-label">Notes</label>
                    <textarea class="form-control" name="noted[]" id="noted-ij-${i}" maxlength="200"></textarea>
                    <div class="form-text text-danger text-end" style="font-size:0.7rem;">Maksimal 200 karakter</div>
                </div>

                </div>
            </div>
            </div>
        </div>`;
        $('#accordionIJ').append(dtl);
        $('.select2').select2({ width:'100%', theme: 'bootstrap-5' });
        setTimeout(()=>{
            $(`#details-ij-${i}`).collapse('show');
        },100);

        const braco = $('#braco').val();
        const wonumSelected = $('#refcno_ij').val();
        if (wonumSelected) {
            loadOpronByWO(braco, wonumSelected, i);
        }

        const warco = $('#warco').val();
        if(warco){
        const $sel = $(`#locco-ij-${i}`);
        $sel.empty().append('<option value="">Loading...</option>');
        $.get(`{{ url('/get-locco') }}/${warco}`, function(data){
            $sel.empty().append('<option disabled selected>Pilih Lokasi</option>');
            data.forEach(item => $sel.append(`<option value="${item.locco}">${item.locco}</option>`));
            $sel.trigger('change.select2');
        });
        }
    }

    window.removeIJ = function(i){
        $(`#accordion-ij-item-${i}`).remove();
    }
</script>
@endpush
