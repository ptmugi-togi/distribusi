{{-- IK (LOCAL PURCHASE) --}}
<div class="row mt-4">
    <div class="col-md-6 mt-3">
        <label for="reference" class="form-label">Reference<span class="text-danger"> *</span></label>
        <select class="form-control select2" name="refcno" id="refcno">
            <option value="" disabled selected>Pilih Reference</option>
        </select>
        <input type="text" name="refno" id="refno-store" hidden>
        <input type="text" name="reffc" id="reffc-store" hidden>
    </div>

    <div class="col-md-12 mt-3">
        <label for="noteh_if" class="form-label">Notes</label>
        <textarea class="form-control" name="noteh" id="noteh_if" maxlength="200">{{ old('noteh') }}</textarea>
        <div class="form-text text-danger text-end" style="font-size:0.7rem;">Maksimal 200 karakter</div>
    </div>
</div>

<div class="row">
  <h4 class="my-2">BBM Detail (IK)</h4>
  <div class="accordion" id="accordionIF">
    @foreach (old('opron', [null]) as $i => $oldOpron)
      <div class="accordion-item" id="accordion-ik-item-{{ $i }}">
        <h2 class="accordion-header d-flex justify-content-between align-items-center" id="heading-ik-{{ $i }}">
          <button class="accordion-button {{ $i > 0 ? 'collapsed' : '' }}" type="button"
            data-bs-toggle="collapse" data-bs-target="#details-ik-{{ $i }}"
            aria-expanded="{{ $i == 0 ? 'true' : 'false' }}" aria-controls="details-ik-{{ $i }}">
            <span class="accordion-title"></span>
          </button>
          @if($i > 0)
            <button type="button" class="btn btn-sm btn-danger mx-2" onclick="removeIF({{ $i }})">
              <i class="bi bi-trash-fill"></i>
            </button>
          @endif
        </h2>

        <div id="details-ik-{{ $i }}" class="accordion-collapse collapse {{ $i == 0 ? 'show' : '' }}"
          aria-labelledby="heading-ik-{{ $i }}" data-bs-parent="#accordionIF">
          <div class="accordion-body">
            <div class="row">

              {{-- INVNO tidak digunakan di IK, tapi backend butuh invno[] -> isi PONO biar aman --}}
              <input type="text" name="invno[]" class="invno-ik" id="invno-ik-{{ $i }}" value="{{ old('refcno') }}" hidden>

              <div class="col-md-6 mt-3">
                <label class="form-label">Barang</label><span class="text-danger"> *</span>
                <select class="select2 form-control opron-ik" name="opron[]" id="opron-ik-{{ $i }}" required>
                  <option value="" disabled {{ old('opron.'.$i) ? '' : 'selected' }}>Pilih Barang</option>
                </select>
              </div>

              <input type="text" class="stdqt-ik" name="stdqt[]" id="stdqt-ik-{{ $i }}" hidden>

              <div class="col-md-6 mt-3">
                  <label for="trqty-ik-{{ $i }}" class="form-label">Receipt Quantity</label><span class="text-danger"> *</span>
                  <div class="input-group">
                    <input type="number" class="form-control trqty-ik" id="trqty-ik-{{ $i }}" name="trqty[]" value="{{ old('trqty.'.$i, 1) }}" min="1" required
                    oninput="this.value = this.value.replace(/[^0-9]/g, '');">
                    <span class="input-group-text unit-label-ik"></span>
                  </div>
              </div>
                
              <div class="col-md-6 mt-3">
                  <div class="form-check">
                      <input class="form-check-input nolot-checkbox" type="checkbox" value="1" name="nolot[{{ $i }}]" id="nolot-{{ $i }}">
                      <label class="form-check-label" for="nolot-{{ $i }}">
                          Without Serial / Batch No
                      </label>
                  </div>
              </div>

              <div class="col-md-6"></div>

              <div class="col-md-6 mt-3 lot-section">
                  <label for="lotno-ik-{{ $i }}" class="form-label">Serial / Batch No.</label>
                  <input type="text" class="form-control lotno-input" name="lotno[]" id="lotno-ik-{{ $i }}" value="{{ old('lotno.'.$i) }}">
              </div>

              <div class="col-md-6 mt-3 lot-section">
                  <label for="lotnoend-ik-{{ $i }}" class="form-label">Serial / Batch No. (Akhir)</label>
                  <input type="text" class="form-control lotnoend-ik" name="lotnoend[]" id="lotnoend-ik-{{ $i }}" readonly style="background-color:#e9ecef;" value="{{ old('lotnoend.'.$i) }}">
              </div>

              <div class="col-md-6 mt-3" hidden>
                <label for="pono-ik-{{ $i }}" class="form-label">PO No.</label>
                <input type="text" class="form-control" name="pono[]" id="pono-ik-{{ $i }}" value="{{ old('pono.'.$i) }}" readonly style="background-color:#e9ecef">
              </div>

              <div class="col-md-6 mt-3">
                <label class="form-label">Warehouse Location</label><span class="text-danger"> *</span>
                <select class="form-control select2" name="locco[]" id="locco-ik-{{ $i }}" required>
                  <option value="" disabled selected>Pilih Warehouse terlebih dahulu</option>
                </select>
              </div>

              <div class="col-md-12 mt-3">
                <label class="form-label">Notes</label>
                <textarea type="text" class="form-control" name="noted[]" id="noted-ik-{{ $i }}" maxlength="200">{{ old('noted.'.$i) }}</textarea>
                <div class="form-text text-danger text-end" style="font-size:0.7rem;">Maksimal 200 karakter</div>
              </div>

            </div>
          </div>
        </div>
      </div>
    @endforeach
  </div>

  <div class="text-end">
    <button type="button" class="btn mt-3" style="background-color:#4456f1;color:#fff" onclick="addIF()">Tambah Detail (IK)</button>
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

  $(document).on('change', '#formc', function () {
      const formc = $(this).val();

      if (formc !== 'IK') return;

      $('#refcno')
          .prop('disabled', true)
          .html('<option disabled selected>Loading...</option>');

      $.ajax({
          url: "{{ route('get.oc') }}",
          type: "GET",
          data: { formc: formc },

          success: function (response) {
              $('#refcno').empty();

              if (!response || response.length === 0) {
                  $('#refcno').append(
                      '<option disabled selected>Tidak ada data Reference</option>'
                  );
                  return;
              }
              $('#refcno').append('<option disabled selected>Pilih Reference</option>');
              response.forEach(item => {
                  $('#refcno').append(`
                      <option value="${item.trano}"
                          data-reffc="${item.formc}"
                          data-refno="${item.trano}">
                          ${item.formc} - ${item.trano}
                      </option>
                  `);
              });

              $('#refcno').prop('disabled', false);
          },

          error: function () {
              $('#refcno')
                  .html('<option disabled selected>Gagal mengambil data</option>')
                  .prop('disabled', false);
          }
      });
  });

  $(document).on('change', '#refcno', function () {
    const reffc = $(this).find(':selected').data('reffc');
    const refno = $(this).find(':selected').data('refno');

    $('#reffc-store').val(reffc);
    $('#refno-store').val(refno);
  });

  // pilih barang (IK)
  $(document).on('change', 'select.opron-ik', function(){
    const $opt = $(this).find(':selected');
    const idx = this.id.split('-').pop();
    const qty = $opt.data('qty') || 0; 
    const pono = $opt.data('pono') || '-';
    const stdqt = $opt.data('stdqt');

    $(`#inqty-ik-${idx}`).val(qty);
    $(`#stdqt-ik-${idx}`).val(stdqt);
    $(`#trqty-ik-${idx}`).next('.input-group-text').text(stdqt);
    $(`#inqty-ik-${idx}`).next('.input-group-text').text(stdqt);
    $(`#pono-ik-${idx}`).val(pono);
  });

  // locco by warco (IK)
  $('#warco').on('change', function(){
    const warco = $(this).val();
    $('select[id^="locco-ik-"]').each(function(){
      const $sel = $(this);
      $sel.empty().append('<option value="">Loading...</option>');
      $.get(`{{ url('/get-locco') }}/${warco}`, function(data){
        $sel.empty().append('<option disabled selected>Pilih Lokasi</option>');
        data.forEach(item => $sel.append(`<option value="${item.locco}">${item.locco}</option>`));
        $sel.trigger('change.select2');
      });
    });
  });

  // auto lot end (IK)
  $(document).on('input', 'input[id^="lotno-ik-"], input[id^="trqty-ik-"]', function(){
    const idx = this.id.split('-').pop();
    const lotStart = $(`#lotno-ik-${idx}`).val();
    const trqty = parseInt($(`#trqty-ik-${idx}`).val()) || 0;
    if(!lotStart || trqty<=0){ $(`#lotnoend-ik-${idx}`).val(''); return; }

    const matches = [...lotStart.matchAll(/\d+/g)];
    if(matches.length===0){ $(`#lotnoend-ik-${idx}`).val(lotStart); return; }

    let chosen = (matches.length===1) ? matches[0] : matches.reduce((p,c)=> (c[0].length<=p[0].length ? c : p));
    const number = parseInt(chosen[0]), next = number + trqty - 1;
    const paddedNext = String(next).padStart(chosen[0].length,'0');
    const endStr = lotStart.slice(0, chosen.index) + paddedNext + lotStart.slice(chosen.index + chosen[0].length);
    $(`#lotnoend-ik-${idx}`).val(endStr);
  });

  // add/remove row IK
  window.addIF = function(){
    const i = $('#accordionIF .accordion-item').length;
    const dtl = `
      <div class="accordion-item" id="accordion-ik-item-${i}">
        <h2 class="accordion-header d-flex justify-content-between align-items-center" id="heading-ik-${i}">
          <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#details-ik-${i}" aria-expanded="false" aria-controls="details-ik-${i}"><span class="accordion-title"></span></button>
          <button type="button" class="btn btn-sm btn-danger mx-2" onclick="removeIF(${i})"><i class="bi bi-trash-fill"></i></button>
        </h2>
        <div id="details-ik-${i}" class="accordion-collapse collapse" aria-labelledby="heading-ik-${i}" data-bs-parent="#accordionIF">
          <div class="accordion-body">
            <div class="row">

              <input type="text" name="invno[]" class="invno-ik" id="invno-ik-${i}" value="${$('#refcno_if_submit').val()||''}" hidden>

              <div class="col-md-6 mt-3">
                <label class="form-label">Barang</label><span class="text-danger"> *</span>
                <select class="select2 form-control opron-ik" name="opron[]" id="opron-ik-${i}" required>
                  <option value="" disabled selected>Pilih Barang</option>
                </select>
              </div>

              <div class="col-md-6 mt-3">
                <label class="form-label">Receipt Quantity</label><span class="text-danger"> *</span>
                <div class="input-group">
                  <input type="number" class="form-control trqty-ik" id="trqty-ik-${i}" name="trqty[]" value="1" min="1" required
                  oninput="this.value = this.value.replace(/[^0-9]/g, '');">
                  <span class="input-group-text unit-label-ik"></span>
                </div>
              </div>

              <input type="text" class="stdqt-ik" name="stdqt[]" id="stdqt-ik-${i}" hidden>

              <div class="col-md-6 mt-3">
                  <div class="form-check">
                      <input class="form-check-input nolot-checkbox" type="checkbox" value="1" name="nolot[${i}]" id="nolot-[${i}]">
                      <label class="form-check-label" for="nolot-${i}">
                          Without Serial / Batch No
                      </label>
                  </div>
              </div>

              <div class="col-md-6"></div>

              <div class="col-md-6 mt-3 lot-section">
                <label class="form-label">Serial / Batch No.</label>
                <input type="text" class="form-control" name="lotno[]" id="lotno-ik-${i}">
              </div>

              <div class="col-md-6 mt-3 lot-section">
                <label class="form-label">Serial / Batch No. (Akhir)</label>
                <input type="text" class="form-control lotnoend-ik" name="lotnoend[]" id="lotnoend-ik-${i}" readonly style="background-color:#e9ecef;">
              </div>

              <div class="col-md-6 mt-3" hidden>
                <label class="form-label">PO No.</label>
                <input type="text" class="form-control" name="pono[]" id="pono-ik-${i}" readonly style="background-color:#e9ecef" value="${$('#refcno_if_submit').val()||''}">
              </div>

              <div class="col-md-6 mt-3">
                <label class="form-label">Warehouse Location</label><span class="text-danger"> *</span>
                <select class="form-control select2" name="locco[]" id="locco-ik-${i}" required>
                  <option value="" disabled selected>Pilih Warehouse terlebih dahulu</option>
                </select>
              </div>

              <div class="col-md-12 mt-3">
                <label class="form-label">Notes</label>
                <textarea class="form-control" name="noted[]" id="noted-ik-${i}" maxlength="200"></textarea>
                <div class="form-text text-danger text-end" style="font-size:0.7rem;">Maksimal 200 karakter</div>
              </div>

            </div>
          </div>
        </div>
      </div>`;
    $('#accordionIF').append(dtl);
    $('.select2').select2({ width:'100%', theme: 'bootstrap-5' });
    setTimeout(()=>{
        $(`#details-ik-${i}`).collapse('show');
    },100);

    // kalau WARCO sudah dipilih -> load warehouse ke row baru IB juga
    const warco = $('#warco').val();
    if(warco){
      const $sel = $(`#locco-ik-${i}`);
      $sel.empty().append('<option value="">Loading...</option>');
      $.get(`{{ url('/get-locco') }}/${warco}`, function(data){
        $sel.empty().append('<option disabled selected>Pilih Lokasi</option>');
        data.forEach(item => $sel.append(`<option value="${item.locco}">${item.locco}</option>`));
        $sel.trigger('change.select2');
      });
    }
    applyNoPoInvMode();

    if( $('#noPoInv').is(':checked') ){
        loadMasterProductAll();
    }
  }

  window.removeIF = function(i){
    $(`#accordion-ik-item-${i}`).remove();
  }
</script>
@endpush
