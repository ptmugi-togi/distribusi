{{-- IA (LOCAL PURCHASE) --}}
<div class="row mt-4">
  <h4 class="my-2">Header (Local / IA)</h4>

  <div class="col-md-6 mt-3">
    <label for="refcno_ia" class="form-label">PO No</label><span class="text-danger"> *</span>
    <select class="form-control select2" id="refcno_ia">
      <option value="" disabled selected>Pilih PO No</option>
    </select>
    {{-- nilai yang disubmit --}}
    <input type="text" name="refcno" id="refcno_ia_submit" hidden>
    <input type="text" name="reffc" value="IA" hidden>
    <input type="text" name="refno" id="refno_ia" hidden>
  </div>

  <div class="col-md-6 mt-3">
    <label class="form-label">Supplier</label>
    <input type="text" class="form-control" id="supplier_ia" readonly style="background-color:#e9ecef;">
    <input type="text" name="supno" id="supno_ia" data-req="ia" hidden>
  </div>

  <div class="col-md-12 mt-3">
    <label for="noteh_ia" class="form-label">Notes</label>
    <textarea class="form-control" name="noteh" id="noteh_ia" maxlength="200">{{ old('noteh') }}</textarea>
    <div class="form-text text-danger text-end" style="font-size:0.7rem;">Maksimal 200 karakter</div>
  </div>
</div>

<div class="row">
  <h4 class="my-2">BBM Detail (IA)</h4>
  <div class="accordion" id="accordionIA">
    @foreach (old('opron', [null]) as $i => $oldOpron)
      <div class="accordion-item" id="accordion-ia-item-{{ $i }}">
        <h2 class="accordion-header d-flex justify-content-between align-items-center" id="heading-ia-{{ $i }}">
          <button class="accordion-button {{ $i > 0 ? 'collapsed' : '' }}" type="button"
            data-bs-toggle="collapse" data-bs-target="#details-ia-{{ $i }}"
            aria-expanded="{{ $i == 0 ? 'true' : 'false' }}" aria-controls="details-ia-{{ $i }}">
            <span class="accordion-title"></span>
          </button>
          @if($i > 0)
            <button type="button" class="btn btn-sm btn-danger mx-2" onclick="removeIA({{ $i }})">
              <i class="bi bi-trash-fill"></i>
            </button>
          @endif
        </h2>

        <div id="details-ia-{{ $i }}" class="accordion-collapse collapse {{ $i == 0 ? 'show' : '' }}"
          aria-labelledby="heading-ia-{{ $i }}" data-bs-parent="#accordionIA">
          <div class="accordion-body">
            <div class="row">

              {{-- INVNO tidak digunakan di IA, tapi backend butuh invno[] -> isi PONO biar aman --}}
              <input type="text" name="invno[]" class="invno-ia" id="invno-ia-{{ $i }}" value="{{ old('refcno') }}" hidden>

              <div class="col-md-6 mt-3">
                <label class="form-label">Barang (dari PO)</label><span class="text-danger"> *</span>
                <select class="select2 form-control opron-ia" name="opron[]" id="opron-ia-{{ $i }}" required>
                  <option value="" disabled {{ old('opron.'.$i) ? '' : 'selected' }}>Pilih PO No terlebih dahulu</option>
                </select>
              </div>

              <div class="col-md-6 mt-3">
                <label for="inqty-ia-{{ $i }}" class="form-label">PO Quantity</label>
                <div class="input-group">
                  <input type="number" class="form-control inqty-ia" id="inqty-ia-{{ $i }}" readonly style="background-color:#e9ecef;" value="{{ old('inqty.'.$i) }}">
                  <span class="input-group-text unit-label-ia"></span>
                  <input type="text" class="stdqt-ia" name="stdqt[]" id="stdqt-ia-{{ $i }}" hidden>
                </div>
              </div>

              <div class="col-md-6 mt-3">
                  <label for="trqty-ia-{{ $i }}" class="form-label">Receipt Quantity</label><span class="text-danger"> *</span>
                  <div class="input-group">
                    <input type="number" class="form-control trqty-ia" id="trqty-ia-{{ $i }}" name="trqty[]" value="{{ old('trqty.'.$i, 1) }}" min="1" required>
                    <span class="input-group-text unit-label-ia"></span>
                  </div>
              </div>
                
              <div class="col-md-6 mt-4">
                  <div class="form-check mt-3">
                      <input class="form-check-input nolot-checkbox" type="checkbox" value="1" name="nolot[{{ $i }}]" id="nolot-{{ $i }}">
                      <label class="form-check-label" for="nolot-{{ $i }}">
                          Non Lot / Without Serial
                      </label>
                  </div>
              </div>

              <div class="col-md-6 mt-3 lot-section">
                  <label for="lotno-ia-{{ $i }}" class="form-label">Serial / Batch No.</label>
                  <input type="text" class="form-control lotno-input" name="lotno[]" id="lotno-ia-{{ $i }}" value="{{ old('lotno.'.$i) }}">
              </div>

              <div class="col-md-6 mt-3 lot-section">
                  <label for="lotnoend-ia-{{ $i }}" class="form-label">Serial / Batch No. (Akhir)</label>
                  <input type="text" class="form-control lotnoend-ia" name="lotnoend[]" id="lotnoend-ia-{{ $i }}" readonly style="background-color:#e9ecef;" value="{{ old('lotnoend.'.$i) }}">
              </div>

              <div class="col-md-6 mt-3">
                <label for="pono-ia-{{ $i }}" class="form-label">PO No.</label>
                <input type="text" class="form-control" name="pono[]" id="pono-ia-{{ $i }}" value="{{ old('pono.'.$i) }}" readonly style="background-color:#e9ecef">
              </div>

              <div class="col-md-6 mt-3">
                <label class="form-label">Warehouse Location</label><span class="text-danger"> *</span>
                <select class="form-control select2" name="locco[]" id="locco-ia-{{ $i }}" required>
                  <option value="" disabled selected>Pilih Warehouse terlebih dahulu</option>
                </select>
              </div>

              <div class="col-md-12 mt-3">
                <label class="form-label">Notes</label>
                <textarea type="text" class="form-control" name="noted[]" id="noted-ia-{{ $i }}" maxlength="200">{{ old('noted.'.$i) }}</textarea>
                <div class="form-text text-danger text-end" style="font-size:0.7rem;">Maksimal 200 karakter</div>
              </div>

            </div>
          </div>
        </div>
      </div>
    @endforeach
  </div>

  <div class="text-end">
    <button type="button" class="btn mt-3" style="background-color:#4456f1;color:#fff" onclick="addIA()">Tambah Detail (IA)</button>
  </div>
</div>

@push('scripts')
<script>
  // load PO list (IA)
  function loadPOList(){
    $.get('{{ route('bbm.getPoList') }}', function(data){
      const $sel = $('#refcno_ia');
      $sel.empty().append('<option disabled selected>Pilih PO No</option>');
      data.forEach(d => $sel.append(`<option value="${d.pono}">${d.pono}</option>`));
    });
  }

  // on open IA section
  $(document).on('change','#formc', function(){
    if($(this).val()==='IA'){ loadPOList(); }
  });

  // saat PO berubah -> isi supplier, set hidden, load barang (PO detail)
  $('#refcno_ia').on('change', function(){
    const pono = $(this).val();
    $('#refcno_ia_submit').val(pono);
    $('#refno_ia').val(pono);

    // supplier by PO
    $.get(`{{ url('/get-po-supplier') }}/${pono}`, function(res){
      $('#supplier_ia').val(res?.supno && res?.supna ? `${res.supno} - ${res.supna}` : (res?.supno || ''));
      $('#supno_ia').val(res?.supno || '');
    });

    // load barang untuk semua row accordion IA
    $('select.opron-ia').each(function(i){
      const $sel = $(this);
      $sel.empty().append('<option value="">Loading...</option>');
      $.ajax({
        url: `{{ url('/get-barang') }}/${pono}?formc=IA`,
        type: 'GET',
        success: function (data) {
          $sel.empty().append('<option value="" disabled selected>Pilih Barang (PO)</option>');
          data.forEach(function (item) {
            $sel.append(
              `<option value="${item.opron}"
                data-qty="${item.inqty}"
                data-stdqt="${item.stdqt}"
                data-pono="${item.pono}">
                ${item.opron} - ${item.prona}
              </option>`
            );
          });
        }
      });
    });

    // set PONO ke semua hidden invno[] dan field PONO
    $('.invno-ia').val(pono);
    $('[id^="pono-ia-"]').val(pono);
  });

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

  // pilih barang (IA)
  $(document).on('change', 'select.opron-ia', function(){
    const $opt = $(this).find(':selected');
    const idx = this.id.split('-').pop();
    const qty = $opt.data('qty'), stdqt = $opt.data('stdqt'), pono = $opt.data('pono');

    $(`#inqty-ia-${idx}`).val(qty);
    $(`#stdqt-ia-${idx}`).val(stdqt);
    $(`#trqty-ia-${idx}`).next('.input-group-text').text(stdqt);
    $(`#inqty-ia-${idx}`).next('.input-group-text').text(stdqt);
    $(`#pono-ia-${idx}`).val(pono);
  });

  // locco by warco (IA)
  $('#warco').on('change', function(){
    const warco = $(this).val();
    $('select[id^="locco-ia-"]').each(function(){
      const $sel = $(this);
      $sel.empty().append('<option value="">Loading...</option>');
      $.get(`{{ url('/get-locco') }}/${warco}`, function(data){
        $sel.empty().append('<option disabled selected>Pilih Lokasi</option>');
        data.forEach(item => $sel.append(`<option value="${item.locco}">${item.locco}</option>`));
        $sel.trigger('change.select2');
      });
    });
  });

  // auto lot end (IA)
  $(document).on('input', 'input[id^="lotno-ia-"], input[id^="trqty-ia-"]', function(){
    const idx = this.id.split('-').pop();
    const lotStart = $(`#lotno-ia-${idx}`).val();
    const trqty = parseInt($(`#trqty-ia-${idx}`).val()) || 0;
    if(!lotStart || trqty<=0){ $(`#lotnoend-ia-${idx}`).val(''); return; }

    const matches = [...lotStart.matchAll(/\d+/g)];
    if(matches.length===0){ $(`#lotnoend-ia-${idx}`).val(lotStart); return; }

    let chosen = (matches.length===1) ? matches[0] : matches.reduce((p,c)=> (c[0].length<=p[0].length ? c : p));
    const number = parseInt(chosen[0]), next = number + trqty - 1;
    const paddedNext = String(next).padStart(chosen[0].length,'0');
    const endStr = lotStart.slice(0, chosen.index) + paddedNext + lotStart.slice(chosen.index + chosen[0].length);
    $(`#lotnoend-ia-${idx}`).val(endStr);
  });

  // add/remove row IA
  window.addIA = function(){
    const i = $('#accordionIA .accordion-item').length;
    const dtl = `
      <div class="accordion-item" id="accordion-ia-item-${i}">
        <h2 class="accordion-header d-flex justify-content-between align-items-center" id="heading-ia-${i}">
          <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#details-ia-${i}" aria-expanded="false" aria-controls="details-ia-${i}"><span class="accordion-title"></span></button>
          <button type="button" class="btn btn-sm btn-danger mx-2" onclick="removeIA(${i})"><i class="bi bi-trash-fill"></i></button>
        </h2>
        <div id="details-ia-${i}" class="accordion-collapse collapse" aria-labelledby="heading-ia-${i}" data-bs-parent="#accordionIA">
          <div class="accordion-body">
            <div class="row">

              <input type="text" name="invno[]" class="invno-ia" id="invno-ia-${i}" value="${$('#refcno_ia_submit').val()||''}" hidden>

              <div class="col-md-6 mt-3">
                <label class="form-label">Barang (dari PO)</label><span class="text-danger"> *</span>
                <select class="select2 form-control opron-ia" name="opron[]" id="opron-ia-${i}" required>
                  <option value="" disabled selected></option>
                </select>
              </div>

              <div class="col-md-6 mt-3">
                <label class="form-label">PO Quantity</label>
                <div class="input-group">
                  <input type="number" class="form-control inqty-ia" id="inqty-ia-${i}" readonly style="background-color:#e9ecef;">
                  <span class="input-group-text unit-label-ia"></span>
                  <input type="text" class="stdqt-ia" name="stdqt[]" id="stdqt-ia-${i}" hidden>
                </div>
              </div>

              <div class="col-md-6 mt-3">
                <label class="form-label">Receipt Quantity</label><span class="text-danger"> *</span>
                <div class="input-group">
                  <input type="number" class="form-control trqty-ia" id="trqty-ia-${i}" name="trqty[]" value="1" min="1" required>
                  <span class="input-group-text unit-label-ia"></span>
                </div>
              </div>

              <div class="col-md-6 mt-4">
                  <div class="form-check mt-3">
                      <input class="form-check-input nolot-checkbox" type="checkbox" value="1" name="nolot[${i}]" id="nolot-[${i}]">
                      <label class="form-check-label" for="nolot-${i}">
                          Non Lot / Without Serial
                      </label>
                  </div>
              </div>

              <div class="col-md-6 mt-3 lot-section">
                <label class="form-label">Serial / Batch No.</label>
                <input type="text" class="form-control" name="lotno[]" id="lotno-ia-${i}">
              </div>

              <div class="col-md-6 mt-3 lot-section">
                <label class="form-label">Serial / Batch No. (Akhir)</label>
                <input type="text" class="form-control lotnoend-ia" name="lotnoend[]" id="lotnoend-ia-${i}" readonly style="background-color:#e9ecef;">
              </div>

              <div class="col-md-6 mt-3">
                <label class="form-label">PO No.</label>
                <input type="text" class="form-control" name="pono[]" id="pono-ia-${i}" readonly style="background-color:#e9ecef" value="${$('#refcno_ia_submit').val()||''}">
              </div>

              <div class="col-md-6 mt-3">
                <label class="form-label">Warehouse Location</label><span class="text-danger"> *</span>
                <select class="form-control select2" name="locco[]" id="locco-ia-${i}" required>
                  <option value="" disabled selected>Pilih Warehouse terlebih dahulu</option>
                </select>
              </div>

              <div class="col-md-12 mt-3">
                <label class="form-label">Notes</label>
                <textarea class="form-control" name="noted[]" id="noted-ia-${i}" maxlength="200"></textarea>
                <div class="form-text text-danger text-end" style="font-size:0.7rem;">Maksimal 200 karakter</div>
              </div>

            </div>
          </div>
        </div>
      </div>`;
    $('#accordionIA').append(dtl);
    $('.select2').select2({ width:'100%', theme: 'bootstrap-5' });
    setTimeout(()=>{
        $(`#details-ia-${i}`).collapse('show');
    },100);

    // kalau PO sudah dipilih, load barang ke row baru
    const pono = $('#refcno_ia_submit').val();
    if(pono){
      const $sel = $(`#opron-ia-${i}`);
      $sel.empty().append('<option value="">Loading...</option>');
      $.get(`{{ url('/get-barang') }}/${pono}?formc=IA`, function(data){
        $sel.empty().append('<option value="" disabled selected>Pilih Barang (PO)</option>');
        data.forEach(item => $sel.append(`<option value="${item.opron}" data-qty="${item.inqty}" data-stdqt="${item.stdqt}" data-pono="${item.pono}">${item.opron} - ${item.prona}</option>`));
      });
    }

    // kalau WARCO sudah dipilih -> load warehouse ke row baru IB juga
    const warco = $('#warco').val();
    if(warco){
      const $sel = $(`#locco-ia-${i}`);
      $sel.empty().append('<option value="">Loading...</option>');
      $.get(`{{ url('/get-locco') }}/${warco}`, function(data){
        $sel.empty().append('<option disabled selected>Pilih Lokasi</option>');
        data.forEach(item => $sel.append(`<option value="${item.locco}">${item.locco}</option>`));
        $sel.trigger('change.select2');
      });
    }
  }

  window.removeIA = function(i){
    $(`#accordion-ia-item-${i}`).remove();
  }
</script>
@endpush
