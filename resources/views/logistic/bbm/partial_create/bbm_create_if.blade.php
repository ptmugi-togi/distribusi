{{-- IF (LOCAL PURCHASE) --}}
<div class="row mt-4">

  <div class="col-md-12 mt-3">
    <label for="noteh_if" class="form-label">Notes</label>
    <textarea class="form-control" name="noteh" id="noteh_if" maxlength="200">{{ old('noteh') }}</textarea>
    <div class="form-text text-danger text-end" style="font-size:0.7rem;">Maksimal 200 karakter</div>
  </div>
</div>

<div class="row">
  <h4 class="my-2">BBM Detail (IF)</h4>
  <div class="accordion" id="accordionIF">
    @foreach (old('opron', [null]) as $i => $oldOpron)
      <div class="accordion-item" id="accordion-if-item-{{ $i }}">
        <h2 class="accordion-header d-flex justify-content-between align-items-center" id="heading-if-{{ $i }}">
          <button class="accordion-button {{ $i > 0 ? 'collapsed' : '' }}" type="button"
            data-bs-toggle="collapse" data-bs-target="#details-if-{{ $i }}"
            aria-expanded="{{ $i == 0 ? 'true' : 'false' }}" aria-controls="details-if-{{ $i }}">
            <span class="accordion-title"></span>
          </button>
          @if($i > 0)
            <button type="button" class="btn btn-sm btn-danger mx-2" onclick="removeIF({{ $i }})">
              <i class="bi bi-trash-fill"></i>
            </button>
          @endif
        </h2>

        <div id="details-if-{{ $i }}" class="accordion-collapse collapse {{ $i == 0 ? 'show' : '' }}"
          aria-labelledby="heading-if-{{ $i }}" data-bs-parent="#accordionIF">
          <div class="accordion-body">
            <div class="row">

              {{-- INVNO tidak digunakan di IF, tapi backend butuh invno[] -> isi PONO biar aman --}}
              <input type="text" name="invno[]" class="invno-if" id="invno-if-{{ $i }}" value="{{ old('refcno') }}" hidden>

              <div class="col-md-6 mt-3">
                <label class="form-label">Barang</label><span class="text-danger"> *</span>
                <select class="select2 form-control opron-if" name="opron[]" id="opron-if-{{ $i }}" required>
                  <option value="" disabled {{ old('opron.'.$i) ? '' : 'selected' }}>Pilih Barang</option>
                </select>
              </div>

              <input type="text" class="stdqt-if" name="stdqt[]" id="stdqt-if-{{ $i }}" hidden>

              <div class="col-md-6 mt-3">
                  <label for="trqty-if-{{ $i }}" class="form-label">Receipt Quantity</label><span class="text-danger"> *</span>
                  <div class="input-group">
                    <input type="number" class="form-control trqty-if" id="trqty-if-{{ $i }}" name="trqty[]" value="{{ old('trqty.'.$i, 1) }}" min="1" required
                    oninput="this.value = this.value.replace(/[^0-9]/g, '');">
                    <span class="input-group-text unit-label-if"></span>
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
                  <label for="lotno-if-{{ $i }}" class="form-label">Serial / Batch No.</label>
                  <input type="text" class="form-control lotno-input" name="lotno[]" id="lotno-if-{{ $i }}" value="{{ old('lotno.'.$i) }}">
              </div>

              <div class="col-md-6 mt-3 lot-section">
                  <label for="lotnoend-if-{{ $i }}" class="form-label">Serial / Batch No. (Akhir)</label>
                  <input type="text" class="form-control lotnoend-if" name="lotnoend[]" id="lotnoend-if-{{ $i }}" readonly style="background-color:#e9ecef;" value="{{ old('lotnoend.'.$i) }}">
              </div>

              <div class="col-md-6 mt-3">
                <label for="pono-if-{{ $i }}" class="form-label">PO No.</label>
                <input type="text" class="form-control" name="pono[]" id="pono-if-{{ $i }}" value="{{ old('pono.'.$i) }}" readonly style="background-color:#e9ecef">
              </div>

              <div class="col-md-6 mt-3">
                <label class="form-label">Warehouse Location</label><span class="text-danger"> *</span>
                <select class="form-control select2" name="locco[]" id="locco-if-{{ $i }}" required>
                  <option value="" disabled selected>Pilih Warehouse terlebih dahulu</option>
                </select>
              </div>

              <div class="col-md-12 mt-3">
                <label class="form-label">Notes</label>
                <textarea type="text" class="form-control" name="noted[]" id="noted-if-{{ $i }}" maxlength="200">{{ old('noted.'.$i) }}</textarea>
                <div class="form-text text-danger text-end" style="font-size:0.7rem;">Maksimal 200 karakter</div>
              </div>

            </div>
          </div>
        </div>
      </div>
    @endforeach
  </div>

  <div class="text-end">
    <button type="button" class="btn mt-3" style="background-color:#4456f1;color:#fff" onclick="addIF()">Tambah Detail (IF)</button>
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

  // pilih barang (IF)
  $(document).on('change', 'select.opron-if', function(){
    const $opt = $(this).find(':selected');
    const idx = this.id.split('-').pop();
    const qty = $opt.data('qty') || 0; 
    const pono = $opt.data('pono') || '-';
    const stdqt = $opt.data('stdqt');

    $(`#inqty-if-${idx}`).val(qty);
    $(`#stdqt-if-${idx}`).val(stdqt);
    $(`#trqty-if-${idx}`).next('.input-group-text').text(stdqt);
    $(`#inqty-if-${idx}`).next('.input-group-text').text(stdqt);
    $(`#pono-if-${idx}`).val(pono);
  });

  // locco by warco (IF)
  $('#warco').on('change', function(){
    const warco = $(this).val();
    $('select[id^="locco-if-"]').each(function(){
      const $sel = $(this);
      $sel.empty().append('<option value="">Loading...</option>');
      $.get(`{{ url('/get-locco') }}/${warco}`, function(data){
        $sel.empty().append('<option disabled selected>Pilih Lokasi</option>');
        data.forEach(item => $sel.append(`<option value="${item.locco}">${item.locco}</option>`));
        $sel.trigger('change.select2');
      });
    });
  });

  // auto lot end (IF)
  $(document).on('input', 'input[id^="lotno-if-"], input[id^="trqty-if-"]', function(){
    const idx = this.id.split('-').pop();
    const lotStart = $(`#lotno-if-${idx}`).val();
    const trqty = parseInt($(`#trqty-if-${idx}`).val()) || 0;
    if(!lotStart || trqty<=0){ $(`#lotnoend-if-${idx}`).val(''); return; }

    const matches = [...lotStart.matchAll(/\d+/g)];
    if(matches.length===0){ $(`#lotnoend-if-${idx}`).val(lotStart); return; }

    let chosen = (matches.length===1) ? matches[0] : matches.reduce((p,c)=> (c[0].length<=p[0].length ? c : p));
    const number = parseInt(chosen[0]), next = number + trqty - 1;
    const paddedNext = String(next).padStart(chosen[0].length,'0');
    const endStr = lotStart.slice(0, chosen.index) + paddedNext + lotStart.slice(chosen.index + chosen[0].length);
    $(`#lotnoend-if-${idx}`).val(endStr);
  });

  // add/remove row IF
  window.addIF = function(){
    const i = $('#accordionIF .accordion-item').length;
    const dtl = `
      <div class="accordion-item" id="accordion-if-item-${i}">
        <h2 class="accordion-header d-flex justify-content-between align-items-center" id="heading-if-${i}">
          <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#details-if-${i}" aria-expanded="false" aria-controls="details-if-${i}"><span class="accordion-title"></span></button>
          <button type="button" class="btn btn-sm btn-danger mx-2" onclick="removeIF(${i})"><i class="bi bi-trash-fill"></i></button>
        </h2>
        <div id="details-if-${i}" class="accordion-collapse collapse" aria-labelledby="heading-if-${i}" data-bs-parent="#accordionIF">
          <div class="accordion-body">
            <div class="row">

              <input type="text" name="invno[]" class="invno-if" id="invno-if-${i}" value="${$('#refcno_if_submit').val()||''}" hidden>

              <div class="col-md-6 mt-3">
                <label class="form-label">Barang</label><span class="text-danger"> *</span>
                <select class="select2 form-control opron-if" name="opron[]" id="opron-if-${i}" required>
                  <option value="" disabled selected>Pilih Barang</option>
                </select>
              </div>

              <div class="col-md-6 mt-3">
                <label class="form-label">Receipt Quantity</label><span class="text-danger"> *</span>
                <div class="input-group">
                  <input type="number" class="form-control trqty-if" id="trqty-if-${i}" name="trqty[]" value="1" min="1" required
                  oninput="this.value = this.value.replace(/[^0-9]/g, '');">
                  <span class="input-group-text unit-label-if"></span>
                </div>
              </div>

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
                <input type="text" class="form-control" name="lotno[]" id="lotno-if-${i}">
              </div>

              <div class="col-md-6 mt-3 lot-section">
                <label class="form-label">Serial / Batch No. (Akhir)</label>
                <input type="text" class="form-control lotnoend-if" name="lotnoend[]" id="lotnoend-if-${i}" readonly style="background-color:#e9ecef;">
              </div>

              <div class="col-md-6 mt-3">
                <label class="form-label">PO No.</label>
                <input type="text" class="form-control" name="pono[]" id="pono-if-${i}" readonly style="background-color:#e9ecef" value="${$('#refcno_if_submit').val()||''}">
              </div>

              <div class="col-md-6 mt-3">
                <label class="form-label">Warehouse Location</label><span class="text-danger"> *</span>
                <select class="form-control select2" name="locco[]" id="locco-if-${i}" required>
                  <option value="" disabled selected>Pilih Warehouse terlebih dahulu</option>
                </select>
              </div>

              <div class="col-md-12 mt-3">
                <label class="form-label">Notes</label>
                <textarea class="form-control" name="noted[]" id="noted-if-${i}" maxlength="200"></textarea>
                <div class="form-text text-danger text-end" style="font-size:0.7rem;">Maksimal 200 karakter</div>
              </div>

            </div>
          </div>
        </div>
      </div>`;
    $('#accordionIF').append(dtl);
    $('.select2').select2({ width:'100%', theme: 'bootstrap-5' });
    setTimeout(()=>{
        $(`#details-if-${i}`).collapse('show');
    },100);

    // kalau WARCO sudah dipilih -> load warehouse ke row baru IB juga
    const warco = $('#warco').val();
    if(warco){
      const $sel = $(`#locco-if-${i}`);
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
    $(`#accordion-if-item-${i}`).remove();
  }
</script>
@endpush
