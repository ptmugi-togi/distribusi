{{-- IL ( EX OTHER BRANCH) --}}
<div class="row mt-4">
    <div class="col-md-6 mt-3">
        <label class="form-label">Stock Requisition No.</label><span class="text-danger"> *</span>
        <select class="form-control select2" name="refcno" id="refcno">
            <option value="" disabled selected>Pilih Stock Requisition No.</option>
            @foreach ($tsreqh as $treq)
                <option value="{{ $treq->formc }}" {{ old('formc') == $treq->formc ? 'selected' : '' }}
                    data-reffc="{{ $treq->formc }}" data-refno="{{ $treq->reqno }}">
                    {{ $treq->formc }} - {{ $treq->reqno }}
              </option>
            @endforeach
        </select>
        <input type="text" name="reffc" id="reffc-store" hidden>
        <input type="text" name="refno" id="refno-store" hidden>
    </div>

    <div class="col-md-6 mt-3">
        <label class="form-label">Transfer Note No.</label><span class="text-danger"> *</span>
        <select class="form-control select2" name="tnfcdnum" id="tnfcdnum">
            <option value="" disabled selected>Pilih Stock Requisition No. terlebih dahulu</option>
        </select>
        <input type="text" name="tnfcd" id="tnfcd-store" hidden>
        <input type="text" name="tnnum" id="tnnum-store" hidden>
    </div>

    <div class="col-md-12 mt-3">
        <label for="noteh_if" class="form-label">Notes</label>
        <textarea class="form-control" name="noteh" id="noteh_if" maxlength="200">{{ old('noteh') }}</textarea>
        <div class="form-text text-danger text-end" style="font-size:0.7rem;">Maksimal 200 karakter</div>
    </div>
</div>

<div class="row">
  <h4 class="my-2">BBM Detail (IL)</h4>
  <div class="accordion" id="accordionIL">
    @foreach (old('opron', [null]) as $i => $oldOpron)
      <div class="accordion-item" id="accordion-il-item-{{ $i }}">
        <h2 class="accordion-header d-flex justify-content-between align-items-center" id="heading-il-{{ $i }}">
          <button class="accordion-button {{ $i > 0 ? 'collapsed' : '' }}" type="button"
            data-bs-toggle="collapse" data-bs-target="#details-il-{{ $i }}"
            aria-expanded="{{ $i == 0 ? 'true' : 'false' }}" aria-controls="details-il-{{ $i }}">
            <span class="accordion-title"></span>
          </button>
          @if($i > 0)
            <button type="button" class="btn btn-sm btn-danger mx-2" onclick="removeIL({{ $i }})">
              <i class="bi bi-trash-fill"></i>
            </button>
          @endif
        </h2>

        <div id="details-il-{{ $i }}" class="accordion-collapse collapse {{ $i == 0 ? 'show' : '' }}"
          aria-labelledby="heading-il-{{ $i }}" data-bs-parent="#accordionIL">
          <div class="accordion-body">
            <div class="row">
              <input type="text" name="invno[]" class="invno-il" id="invno-il-{{ $i }}" value="-" hidden>

              <div class="col-md-6 mt-3">
                <label class="form-label">Barang</label><span class="text-danger"> *</span>
                <select class="select2 form-control opron-il" name="opron[]" id="opron-il-{{ $i }}" required>
                  <option value="" disabled {{ old('opron.'.$i) ? '' : 'selected' }}>Pilih Barang</option>
                </select>
              </div>

              <input type="text" class="stdqt-il" name="stdqt[]" id="stdqt-il-{{ $i }}" hidden>

              <div class="col-md-6 mt-3">
                  <label for="trqty-il-{{ $i }}" class="form-label">Receipt Quantity</label><span class="text-danger"> *</span>
                  <div class="input-group">
                    <input type="number" class="form-control trqty-il" id="trqty-il-{{ $i }}" name="trqty[]" value="{{ old('trqty.'.$i) }}" required
                    oninput="this.value = this.value.replace(/[^0-9]/g, '');" readonly style="background-color:#e9ecef;">
                    <span class="input-group-text unit-label-il"></span>
                  </div>
              </div>

              <div class="col-md-6 mt-3 lot-section">
                  <label for="lotno-il-{{ $i }}" class="form-label">Serial / Batch No.</label>
                  <input type="text" class="form-control lotno-input" name="lotno[]" id="lotno-il-{{ $i }}" value="{{ old('lotno.'.$i) }}" readonly style="background-color:#e9ecef;">
              </div>

              <div class="col-md-6 mt-3">
                <label class="form-label">Warehouse Location</label>
                <input type="text" class="form-control" name="locco[]" id="locco-il-{{ $i }}" value="{{ old('locco.'.$i) }}" required readonly style="background-color:#e9ecef;">
              </div>

              <div class="col-md-12 mt-3">
                <label class="form-label">Notes</label>
                <textarea type="text" class="form-control" name="noted[]" id="noted-il-{{ $i }}" maxlength="200">{{ old('noted.'.$i) }}</textarea>
                <div class="form-text text-danger text-end" style="font-size:0.7rem;">Maksimal 200 karakter</div>
              </div>

            </div>
          </div>
        </div>
      </div>
    @endforeach
  </div>

  <div class="text-end">
    <button type="button" class="btn mt-3" style="background-color:#4456f1;color:#fff" onclick="addIL()">Tambah Detail (IL)</button>
  </div>
</div>

@push('scripts')
<script>

  // checkbox lotno
  $(document).on('change', '.nolot-checkbox', function(){
    let container   = $(this).closest('.row, .accordion-body'); 
    if($(this).is(':checked')){
        container.find('.lot-section').hide();
        container.find('.lotno-input').val('-'); // default supaya backend ga error
    }else{
        container.find('.lot-section').show();
        container.find('.lotno-input').val('');
    }
  });

  $('#tnfcdnum').on('change', function(){
    const tnfcd = $(this).find(':selected').data('tnfcd');
    const tnnum = $(this).find(':selected').data('tnnum');

    $('#tnfcd-store').val(tnfcd);
    $('#tnnum-store').val(tnnum);
  });

  $(document).on('change', '#refcno', function() {
      const reffc = $(this).find(':selected').data('reffc');
      const refno = $(this).find(':selected').data('refno');

      $('#reffc-store').val(reffc);
      $('#refno-store').val(refno);

      $('#tnfcdnum')
          .prop('disabled', true)
          .html('<option>Loading...</option>')
          .trigger('change');

      $.ajax({
        url: "{{ route('get.ta') }}",
        type: "GET",
        data: { refno: refno },
        success: function(response) {

            $('#tnfcdnum').empty();

            if (response.length === 0) {
                $('#tnfcdnum')
                    .append('<option value="" disabled selected>Tidak ada data Transfer Note</option>');
            } else {
                $('#tnfcdnum').append('<option value="" disabled selected>Pilih Transfer Note No.</option>');
                response.forEach(function(item) {
                    $('#tnfcdnum').append(`
                        <option value="${item.trano}" data-tnfcd="${item.formc}" data-tnnum="${item.trano}">
                            ${item.formc} - ${item.trano}
                        </option>
                    `);
                });
            }

            $('#tnfcdnum').prop('disabled', false).trigger('change');
        },

        error: function() {
            $('#tnfcdnum')
                .html('<option value="" disabled selected>Gagal mengambil data</option>')
                .prop('disabled', false);
        }
    });
  });

  // fucntion pilih barang (IL)
  function loadOpronByTA(trano, idx = null) {
    const $targets = (idx !== null)
        ? $(`#opron-il-${idx}`)
        : $('.opron-il');

    $targets.prop('disabled', true)
            .html('<option>Loading...</option>');

    $.ajax({
        url: "{{ route('get.opron.by.ta') }}",
        type: "GET",
        data: { trano: trano },
        success: function (response) {

            $targets.each(function () {
                const $sel = $(this);
                const oldValue = $sel.val();

                $sel.empty().append('<option value="" disabled selected>Pilih Barang</option>');

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
            });
        },

        error: function () {
            $('.opron-il').html('<option>Gagal memuat data</option>').prop('disabled', false);
        }
    });
  }

  $(document).on('change', '#tnfcdnum', function () {
      const trano = $(this).val();
      loadOpronByTA(trano);
  });

  $(document).on('change', '.opron-il', function () {
      const $sel = $(this);
      const selected = $sel.find(':selected');

      const idx = this.id.split('-').pop();

      const qty = selected.data('qty');
      const stdqt = selected.data('stdqt');
      const lotno = selected.data('lotno');
      const locco = selected.data('locco');

      $('#stdqt-il-' + idx).val(stdqt);

      $('#trqty-il-' + idx).val(qty);
      $('#lotno-il-' + idx).val(lotno);

      $('#trqty-il-' + idx)
          .closest('.input-group')
          .find('.unit-label-il')
          .text(stdqt);

      $('#locco-il-' + idx).val(locco);
  });

  // add/remove row IL
  window.addIL = function(){
    const i = $('#accordionIL .accordion-item').length;
    const dtl = `
      <div class="accordion-item" id="accordion-il-item-${i}">
        <h2 class="accordion-header d-flex justify-content-between align-items-center" id="heading-il-${i}">
          <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#details-il-${i}" aria-expanded="false" aria-controls="details-il-${i}"><span class="accordion-title"></span></button>
          <button type="button" class="btn btn-sm btn-danger mx-2" onclick="removeIL(${i})"><i class="bi bi-trash-fill"></i></button>
        </h2>
        <div id="details-il-${i}" class="accordion-collapse collapse" aria-labelledby="heading-il-${i}" data-bs-parent="#accordionIL">
          <div class="accordion-body">
            <div class="row">
              <input type="text" name="invno[]" class="invno-il" id="invno-il-${i}" value="-" hidden>

              <div class="col-md-6 mt-3">
                <label class="form-label">Barang</label><span class="text-danger"> *</span>
                <select class="select2 form-control opron-il" name="opron[]" id="opron-il-${i}" required>
                  <option value="" disabled selected>Pilih Barang</option>
                </select>
              </div>

              <div class="col-md-6 mt-3">
                <label class="form-label">Receipt Quantity</label><span class="text-danger"> *</span>
                <div class="input-group">
                  <input type="number" class="form-control trqty-il" id="trqty-il-${i}" name="trqty[]" required
                  oninput="this.value = this.value.replace(/[^0-9]/g, '');" readonly style="background-color:#e9ecef;">
                  <span class="input-group-text unit-label-il"></span>
                </div>
              </div>

              <input type="text" class="stdqt-il" name="stdqt[]" id="stdqt-il-${i}" hidden>

              <div class="col-md-6 mt-3 lot-section">
                <label class="form-label">Serial / Batch No.</label>
                <input type="text" class="form-control" name="lotno[]" id="lotno-il-${i}" readonly style="background-color:#e9ecef;">
              </div>

              <div class="col-md-6 mt-3">
                <label class="form-label">Warehouse Location</label>
                <input type="text" class="form-control" name="locco[]" id="locco-il-${i}" required readonly style="background-color:#e9ecef;">
              </div>

              <div class="col-md-12 mt-3">
                <label class="form-label">Notes</label>
                <textarea class="form-control" name="noted[]" id="noted-il-${i}" maxlength="200"></textarea>
                <div class="form-text text-danger text-end" style="font-size:0.7rem;">Maksimal 200 karakter</div>
              </div>

            </div>
          </div>
        </div>
      </div>`;
    $('#accordionIL').append(dtl);
    $('.select2').select2({ width:'100%', theme: 'bootstrap-5' });
    setTimeout(()=>{
        $(`#details-il-${i}`).collapse('show');
    },100);

    const tranoSelected = $('#tnfcdnum').val();
    if (tranoSelected) {
        loadOpronByTA(tranoSelected, i);
    }
  }

  window.removeIL = function(i){
    $(`#accordion-il-item-${i}`).remove();
  }
</script>
@endpush
