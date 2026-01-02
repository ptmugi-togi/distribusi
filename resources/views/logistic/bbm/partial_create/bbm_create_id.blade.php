{{-- ID ( EX PINJAMAN) --}}
<div class="row mt-4">
    <div class="col-md-6 mt-3">
        <label class="form-label">SIN "pinjaman"</label><span class="text-danger"> *</span>
        <select class="form-control select2" name="refcno" id="refcno_id">
            <option value="" disabled selected>Pilih SIN "pinjaman"</option>
        </select>
        <input type="text" name="reffc" id="reffc-store" hidden>
        <input type="text" name="refno" id="refno-store" hidden>
    </div>

    <div class="col-md-6 mt-3">
      <label for="isutn" class="form-label">Dipinjam Oleh</label><span class="text-danger"> *</span>
      <input type="text" class="form-control" name="isutn" id="isutn_id" value="{{ old('isutn') }}" readonly style="background-color:#e9ecef;">
    </div>

    <div class="col-md-12 mt-3">
        <label for="noteh_id" class="form-label">Notes</label>
        <textarea class="form-control" name="noteh" id="noteh_id" maxlength="200">{{ old('noteh') }}</textarea>
        <div class="form-text text-danger text-end" style="font-size:0.7rem;">Maksimal 200 karakter</div>
    </div>
</div>

<div class="row">
  <h4 class="my-2">BBM Detail (ID)</h4>
  <div class="accordion" id="accordionID">
    @foreach (old('opron', [null]) as $i => $oldOpron)
      <div class="accordion-item" id="accordion-id-item-{{ $i }}">
        <h2 class="accordion-header d-flex justify-content-between align-items-center" id="heading-id-{{ $i }}">
          <button class="accordion-button {{ $i > 0 ? 'collapsed' : '' }}" type="button"
            data-bs-toggle="collapse" data-bs-target="#details-id-{{ $i }}"
            aria-expanded="{{ $i == 0 ? 'true' : 'false' }}" aria-controls="details-id-{{ $i }}">
            <span class="accordion-title"></span>
          </button>
          @if($i > 0)
            <button type="button" class="btn btn-sm btn-danger mx-2" onclick="removeIL({{ $i }})">
              <i class="bi bi-trash-fill"></i>
            </button>
          @endif
        </h2>

        <div id="details-id-{{ $i }}" class="accordion-collapse collapse {{ $i == 0 ? 'show' : '' }}"
          aria-labelledby="heading-id-{{ $i }}" data-bs-parent="#accordionID">
          <div class="accordion-body">
            <div class="row">
              <input type="text" name="invno[]" class="invno-id" id="invno-id-{{ $i }}" value="-" hidden>

              <div class="col-md-6 mt-3">
                <label class="form-label">Barang</label><span class="text-danger"> *</span>
                <select class="select2 form-control opron-id" name="opron[]" id="opron-id-{{ $i }}" required>
                  <option value="" disabled {{ old('opron.'.$i) ? '' : 'selected' }}>Pilih Barang</option>
                </select>
              </div>

              <input type="text" class="stdqt-id" name="stdqt[]" id="stdqt-id-{{ $i }}" hidden>

              <div class="col-md-6 mt-3">
                  <label for="trqty-id-{{ $i }}" class="form-label">Receipt Quantity</label><span class="text-danger"> *</span>
                  <div class="input-group">
                    <input type="number" class="form-control trqty-id" id="trqty-id-{{ $i }}" name="trqty[]" value="{{ old('trqty.'.$i) }}" required
                    oninput="this.value = this.value.replace(/[^0-9]/g, '');">
                    <span class="input-group-text unit-label-id"></span>
                  </div>
              </div>

              <div class="col-md-6 mt-3 lot-section">
                  <label for="lotno-id-{{ $i }}" class="form-label">Serial / Batch No.</label>
                  <input type="text" class="form-control lotno-input" name="lotno[]" id="lotno-id-{{ $i }}" value="{{ old('lotno.'.$i) }}" readonly style="background-color:#e9ecef;">
              </div>

              <div class="col-md-6 mt-3">
                <label class="form-label">Warehouse Location</label>
                <input type="text" class="form-control" name="locco[]" id="locco-id-{{ $i }}" value="{{ old('locco.'.$i) }}" required readonly style="background-color:#e9ecef;">
              </div>

              <div class="col-md-12 mt-3">
                <label class="form-label">Notes</label>
                <textarea type="text" class="form-control" name="noted[]" id="noted-id-{{ $i }}" maxlength="200">{{ old('noted.'.$i) }}</textarea>
                <div class="form-text text-danger text-end" style="font-size:0.7rem;">Maksimal 200 karakter</div>
              </div>

            </div>
          </div>
        </div>
      </div>
    @endforeach
  </div>

  <div class="text-end">
    <button type="button" class="btn mt-3" style="background-color:#4456f1;color:#fff" onclick="addID()">Tambah Detail (ID)</button>
  </div>
</div>

@push('scripts')
<script>
  let isLoadingOpronOA = false;
  // get OA
  $(document).on('change', '#formc, #warco', function () {
      const formc = $('#formc').val();
      const warco = $('#warco').val();
      const $ref = $('#refcno_id');

      // reset dulu
      $ref
          .prop('disabled', true)
          .html('<option disabled selected>Pilih Warehouse terlebih dahulu</option>');

      if (formc !== 'ID' || !warco) {
          return;
      }

      $ref.html('<option disabled selected>Loading...</option>');

      $.ajax({
          url: "{{ route('get.oa') }}",
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

  $(document).on('change', '#refcno_id', function () {
      const selected = $(this).find(':selected');

      const reffc = selected.data('reffc');
      const refno = selected.data('refno');
      const isutn = selected.data('isutn');
      const braco = $('#braco').val();
      const warco = $('#warco').val();
      const trano = $(this).val();

      $('#isutn_id').val(isutn);
      $('#reffc-store').val(reffc);
      $('#refno-store').val(refno);
      loadOpronByOA(braco, warco, trano);
  });

  // fucntion pilih barang (ID)
  function loadOpronByOA(braco, warco, trano, idx = null) {
    if (isLoadingOpronOA) return;
    isLoadingOpronOA = true;

    const $targets = (idx !== null)
        ? $(`#opron-id-${idx}`)
        : $('.opron-id');

    $targets.each(function () {
        const $sel = $(this);

        if ($sel.hasClass('select2-hidden-accessible')) {
            $sel.select2('destroy');
        }

        $sel.prop('disabled', true).empty();
    });

    $.ajax({
        url: "{{ route('get.opron.by.oa') }}",
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
            isLoadingOpronOA = false;
        },

        error: function () {
          isLoadingOpronOA = false;
          $('.opron-id').html('<option>Gagal memuat data</option>').prop('disabled', false);
        }
    });
  }

  $(document).on('change', '.opron-id', function () {
    if (isLoadingOpronOA) return;
      const $sel = $(this);
      const selected = $sel.find(':selected');

      const idx = this.id.split('-').pop();

      const qty = selected.data('qty');
      const stdqt = selected.data('stdqt');
      const lotno = selected.data('lotno');
      const locco = selected.data('locco');

      $('#stdqt-id-' + idx).val(stdqt);

      $('#trqty-id-' + idx).val(qty);
      $('#lotno-id-' + idx).val(lotno);

      $('#trqty-id-' + idx)
          .closest('.input-group')
          .find('.unit-label-id')
          .text(stdqt);

      $('#locco-id-' + idx).val(locco);
  });

  // add/remove row ID
  window.addID = function(){
    const i = $('#accordionID .accordion-item').length;
    const dtl = `
      <div class="accordion-item" id="accordion-id-item-${i}">
        <h2 class="accordion-header d-flex justify-content-between align-items-center" id="heading-id-${i}">
          <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#details-id-${i}" aria-expanded="false" aria-controls="details-id-${i}"><span class="accordion-title"></span></button>
          <button type="button" class="btn btn-sm btn-danger mx-2" onclick="removeID(${i})"><i class="bi bi-trash-fill"></i></button>
        </h2>
        <div id="details-id-${i}" class="accordion-collapse collapse" aria-labelledby="heading-id-${i}" data-bs-parent="#accordionID">
          <div class="accordion-body">
            <div class="row">
              <input type="text" name="invno[]" class="invno-id" id="invno-id-${i}" value="-" hidden>

              <div class="col-md-6 mt-3">
                <label class="form-label">Barang</label><span class="text-danger"> *</span>
                <select class="select2 form-control opron-id" name="opron[]" id="opron-id-${i}" required>
                  <option value="" disabled selected>Pilih Barang</option>
                </select>
              </div>

              <div class="col-md-6 mt-3">
                <label class="form-label">Receipt Quantity</label><span class="text-danger"> *</span>
                <div class="input-group">
                  <input type="number" class="form-control trqty-id" id="trqty-id-${i}" name="trqty[]" required
                  oninput="this.value = this.value.replace(/[^0-9]/g, '');">
                  <span class="input-group-text unit-label-id"></span>
                </div>
              </div>

              <input type="text" class="stdqt-id" name="stdqt[]" id="stdqt-id-${i}" hidden>

              <div class="col-md-6 mt-3 lot-section">
                <label class="form-label">Serial / Batch No.</label>
                <input type="text" class="form-control" name="lotno[]" id="lotno-id-${i}" readonly style="background-color:#e9ecef;">
              </div>

              <div class="col-md-6 mt-3">
                <label class="form-label">Warehouse Location</label>
                <input type="text" class="form-control" name="locco[]" id="locco-id-${i}" required readonly style="background-color:#e9ecef;">
              </div>

              <div class="col-md-12 mt-3">
                <label class="form-label">Notes</label>
                <textarea class="form-control" name="noted[]" id="noted-id-${i}" maxlength="200"></textarea>
                <div class="form-text text-danger text-end" style="font-size:0.7rem;">Maksimal 200 karakter</div>
              </div>

            </div>
          </div>
        </div>
      </div>`;
    $('#accordionID').append(dtl);
    $(`#opron-id-${i}`).select2({ width:'100%', theme: 'bootstrap-5' });
    setTimeout(()=>{
        $(`#details-id-${i}`).collapse('show');
    },100);

    const braco = $('#braco').val();
    const warco = $('#warco').val();
    const tranoSelected = $('#refcno_id').val();
    if (tranoSelected) {
        loadOpronByOA(braco, warco, tranoSelected, i);
    }
  }

  window.removeID = function(i){
    $(`#accordion-id-item-${i}`).remove();
  }
</script>
@endpush
