{{-- IE ( Warranty Claim ) --}}
<div class="row mt-4">
    <div class="col-md-6 mt-3">
        <label class="form-label">Reference</label><span class="text-danger"> *</span>
        <select class="form-control select2" name="refcno" id="refcno_ie">
            <option value="" disabled selected>Pilih Reference</option>
        </select>
        <input type="text" name="reffc" id="reffc-store" hidden>
        <input type="text" name="refno" id="refno-store" hidden>
    </div>

    <div class="col-md-6 mt-3">
      <label for="isutn" class="form-label">Warranty Claim#</label><span class="text-danger"> *</span>
      <input type="text" class="form-control" name="isutn" id="isutn_ie" value="{{ old('isutn') }}" readonly style="background-color:#e9ecef;">
    </div>

    <div class="col-md-12 mt-3">
        <label for="noteh_ie" class="form-label">Notes</label>
        <textarea class="form-control" name="noteh" id="noteh_ie" maxlength="200">{{ old('noteh') }}</textarea>
        <div class="form-text text-danger text-end" style="font-size:0.7rem;">Maksimal 200 karakter</div>
    </div>
</div>

<div class="row">
  <h4 class="my-2">BBM Detail (IE)</h4>
  <div class="accordion" id="accordionIE">
    @foreach (old('opron', [null]) as $i => $oldOpron)
      <div class="accordion-item" id="accordion-ie-item-{{ $i }}">
        <h2 class="accordion-header d-flex justify-content-between align-items-center" id="heading-ie-{{ $i }}">
          <button class="accordion-button {{ $i > 0 ? 'collapsed' : '' }}" type="button"
            data-bs-toggle="collapse" data-bs-target="#details-ie-{{ $i }}"
            aria-expanded="{{ $i == 0 ? 'true' : 'false' }}" aria-controls="details-ie-{{ $i }}">
            <span class="accordion-title"></span>
          </button>
          @if($i > 0)
            <button type="button" class="btn btn-sm btn-danger mx-2" onclick="removeIL({{ $i }})">
              <i class="bi bi-trash-fill"></i>
            </button>
          @endif
        </h2>

        <div id="details-ie-{{ $i }}" class="accordion-collapse collapse {{ $i == 0 ? 'show' : '' }}"
          aria-labelledby="heading-ie-{{ $i }}" data-bs-parent="#accordionIE">
          <div class="accordion-body">
            <div class="row">
              <input type="text" name="invno[]" class="invno-ie" id="invno-ie-{{ $i }}" value="-" hidden>

              <div class="col-md-6 mt-3">
                <label class="form-label">Barang</label><span class="text-danger"> *</span>
                <select class="select2 form-control opron-ie" name="opron[]" id="opron-ie-{{ $i }}" required>
                  <option value="" disabled {{ old('opron.'.$i) ? '' : 'selected' }}>Pilih Barang</option>
                </select>
              </div>

              <input type="text" class="stdqt-ie" name="stdqt[]" id="stdqt-ie-{{ $i }}" hidden>

              <div class="col-md-6 mt-3">
                  <label for="trqty-ie-{{ $i }}" class="form-label">Receipt Quantity</label><span class="text-danger"> *</span>
                  <div class="input-group">
                    <input type="number" class="form-control trqty-ie" id="trqty-ie-{{ $i }}" name="trqty[]" value="{{ old('trqty.'.$i) }}" required
                    oninput="this.value = this.value.replace(/[^0-9]/g, '');">
                    <span class="input-group-text unit-label-ie"></span>
                  </div>
              </div>

              <div class="col-md-6 mt-3 lot-section">
                  <label for="lotno-ie-{{ $i }}" class="form-label">Serial / Batch No.</label>
                  <input type="text" class="form-control lotno-input" name="lotno[]" id="lotno-ie-{{ $i }}" value="{{ old('lotno.'.$i) }}">
              </div>

              <div class="col-md-6 mt-3">
                <label class="form-label">Warehouse Location</label>
                <input type="text" class="form-control" name="locco[]" id="locco-ie-{{ $i }}" value="{{ old('locco.'.$i) }}" required readonly style="background-color:#e9ecef;">
              </div>

              <div class="col-md-12 mt-3">
                <label class="form-label">Notes</label>
                <textarea type="text" class="form-control" name="noted[]" id="noted-ie-{{ $i }}" maxlength="200">{{ old('noted.'.$i) }}</textarea>
                <div class="form-text text-danger text-end" style="font-size:0.7rem;">Maksimal 200 karakter</div>
              </div>

            </div>
          </div>
        </div>
      </div>
    @endforeach
  </div>

  <div class="text-end">
    <button type="button" class="btn mt-3" style="background-color:#4456f1;color:#fff" onclick="addIE()">Tambah Detail (IE)</button>
  </div>
</div>

@push('scripts')
<script>
  let isLoadingOpronOE = false;
  // get OE
  $(document).on('change', '#formc, #warco', function () {
      const formc = $('#formc').val();
      const warco = $('#warco').val();
      const $ref = $('#refcno_ie');

      // reset dulu
      $ref
          .prop('disabled', true)
          .html('<option disabled selected>Pilih Warehouse terlebih dahulu</option>');

      if (formc !== 'IE' || !warco) {
          return;
      }

      $ref.html('<option disabled selected>Loading...</option>');

      $.ajax({
          url: "{{ route('get.oe') }}",
          type: "GET",
          data: { formc, warco },
          success(response) {
              $ref.empty();

              if (!Array.isArray(response) || response.length === 0) {
                  $ref.append('<option disabled selected>Tidak ada data Reference</option>');
                  return;
              }

              $ref.append('<option disabled selected>Pilih Reference</option>');

              response.forEach(item => {
                  $ref.append(`
                      <option value="${item.trano}"
                          data-reffc="${item.formc}"
                          data-refno="${item.trano}"
                          data-isutn="${item.isutn}">
                          ${item.formc} - ${item.trano}
                      </option>
                  `);
              });

              $ref.prop('disabled', false);
          },
          error() {
              $ref
                  .html('<option disabled selected>Gagal mengambil data</option>')
                  .prop('disabled', false);
          }
      });
  });

  $(document).on('change', '#refcno_ie', function () {
      const selected = $(this).find(':selected');

      const reffc = selected.data('reffc');
      const refno = selected.data('refno');
      const isutn = selected.data('isutn');
      const braco = $('#braco').val();
      const warco = $('#warco').val();
      const trano = $(this).val();

      $('#isutn_ie').val(isutn);
      $('#reffc-store').val(reffc);
      $('#refno-store').val(refno);
      loadOpronByOE(braco, warco, trano);
  });

  // fucntion pilih barang (IE)
  function loadOpronByOE(braco, warco, trano, idx = null) {
    if (isLoadingOpronOE) return;
    isLoadingOpronOE = true;

    const $targets = (idx !== null)
        ? $(`#opron-ie-${idx}`)
        : $('.opron-ie');

    $targets.each(function () {
        const $sel = $(this);

        if ($sel.hasClass('select2-hidden-accessible')) {
            $sel.select2('destroy');
        }

        $sel.prop('disabled', true).empty();
    });

    $.ajax({
        url: "{{ route('get.opron.by.oe') }}",
        type: "GET",
        data: {
            trano,
            braco,
            warco
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
                            value="${item.opron}" 
                            data-qty="${item.trqty}" 
                            data-stdqt="${item.qunit}"
                            data-lotno="${item.lotno}"
                            data-locco="${item.locco}"
                        >
                            ${item.opron} - ${item.prona} (${item.lotno})
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
            isLoadingOpronOE = false;
        },

        error: function () {
          isLoadingOpronOE = false;
          $('.opron-ie').html('<option>Gagal memuat data</option>').prop('disabled', false);
        }
    });
  }

  $(document).on('change', '.opron-ie', function () {
    if (isLoadingOpronOE) return;
      const $sel = $(this);
      const selected = $sel.find(':selected');

      const idx = this.id.split('-').pop();

      const qty = selected.data('qty');
      const stdqt = selected.data('stdqt');
      const lotno = selected.data('lotno');
      const locco = selected.data('locco');

      $('#stdqt-ie-' + idx).val(stdqt);

      $('#trqty-ie-' + idx).val(qty);
      $('#lotno-ie-' + idx).val(lotno);

      $('#trqty-ie-' + idx)
          .closest('.input-group')
          .find('.unit-label-ie')
          .text(stdqt);

      $('#locco-ie-' + idx).val(locco);
  });

  // add/remove row IE
  window.addIE = function(){
    const i = $('#accordionIE .accordion-item').length;
    const dtl = `
      <div class="accordion-item" id="accordion-ie-item-${i}">
        <h2 class="accordion-header d-flex justify-content-between align-items-center" id="heading-ie-${i}">
          <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#details-ie-${i}" aria-expanded="false" aria-controls="details-ie-${i}"><span class="accordion-title"></span></button>
          <button type="button" class="btn btn-sm btn-danger mx-2" onclick="removeIE(${i})"><i class="bi bi-trash-fill"></i></button>
        </h2>
        <div id="details-ie-${i}" class="accordion-collapse collapse" aria-labelledby="heading-ie-${i}" data-bs-parent="#accordionIE">
          <div class="accordion-body">
            <div class="row">
              <input type="text" name="invno[]" class="invno-ie" id="invno-ie-${i}" value="-" hidden>

              <div class="col-md-6 mt-3">
                <label class="form-label">Barang</label><span class="text-danger"> *</span>
                <select class="select2 form-control opron-ie" name="opron[]" id="opron-ie-${i}" required>
                  <option value="" disabled selected>Pilih Barang</option>
                </select>
              </div>

              <div class="col-md-6 mt-3">
                <label class="form-label">Receipt Quantity</label><span class="text-danger"> *</span>
                <div class="input-group">
                  <input type="number" class="form-control trqty-ie" id="trqty-ie-${i}" name="trqty[]" required
                  oninput="this.value = this.value.replace(/[^0-9]/g, '');">
                  <span class="input-group-text unit-label-ie"></span>
                </div>
              </div>

              <input type="text" class="stdqt-ie" name="stdqt[]" id="stdqt-ie-${i}" hidden>

              <div class="col-md-6 mt-3 lot-section">
                <label class="form-label">Serial / Batch No.</label>
                <input type="text" class="form-control" name="lotno[]" id="lotno-ie-${i}">
              </div>

              <div class="col-md-6 mt-3">
                <label class="form-label">Warehouse Location</label>
                <input type="text" class="form-control" name="locco[]" id="locco-ie-${i}" required readonly style="background-color:#e9ecef;">
              </div>

              <div class="col-md-12 mt-3">
                <label class="form-label">Notes</label>
                <textarea class="form-control" name="noted[]" id="noted-ie-${i}" maxlength="200"></textarea>
                <div class="form-text text-danger text-end" style="font-size:0.7rem;">Maksimal 200 karakter</div>
              </div>

            </div>
          </div>
        </div>
      </div>`;
    $('#accordionIE').append(dtl);
    $(`#opron-ie-${i}`).select2({ width:'100%', theme: 'bootstrap-5' });
    setTimeout(()=>{
        $(`#details-ie-${i}`).collapse('show');
    },100);

    const braco = $('#braco').val();
    const warco = $('#warco').val();
    const tranoSelected = $('#refcno_ie').val();
    if (tranoSelected) {
        loadOpronByOE(braco, warco, tranoSelected, i);
    }
  }

  window.removeIE = function(i){
    $(`#accordion-ie-item-${i}`).remove();
  }
</script>
@endpush
