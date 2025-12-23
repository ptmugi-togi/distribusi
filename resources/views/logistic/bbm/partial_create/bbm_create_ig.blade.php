{{-- IG (FG TRANSFER FROM BRANCH) --}}
<div class="row mt-4">
  <h4 class="my-2">Header (Local / IG)</h4>

  <div class="col-md-6 mt-3">
    <label for="refcno_ig" class="form-label">PO No</label><span class="text-danger"> *</span>
    <select class="form-control select2" id="refcno_ig">
      <option value="" disabled selected>Pilih PO No</option>
    </select>
    {{-- nilai yang disubmit --}}
    <input type="text" name="refcno" id="refcno_ig_submit" hidden>
    <input type="text" name="reffc" value="IG" hidden>
    <input type="text" name="refno" id="refno_ig" hidden>
  </div>

  <div class="col-md-6 mt-3">
      <label for="supplier_ig" class="form-label">Supplier</label>
      <select class="form-control select2" id="supplier_ig" disabled>
          <option value="" disabled selected>Pilih Supplier</option>
          @foreach ($vendors->where('vgrp', 'L') as $v)
              <option value="{{ $v->supno }}">{{ $v->supno }} - {{ $v->supna }}</option>
          @endforeach
      </select>
      <input type="text" name="supno" id="supno_ig" hidden>
  </div>

  <div class="col-md-12 mt-3">
    <label for="noteh_ig" class="form-label">Notes</label>
    <textarea class="form-control" name="noteh" id="noteh_ig" maxlength="200">{{ old('noteh') }}</textarea>
    <div class="form-text text-danger text-end" style="font-size:0.7rem;">Maksimal 200 karakter</div>
  </div>
</div>

<div class="row">
  <h4 class="my-2">BBM Detail (IG)</h4>
  <div class="accordion" id="accordionIG">
    @foreach (old('opron', [null]) as $i => $oldOpron)
      <div class="accordion-item" id="accordion-ig-item-{{ $i }}">
        <h2 class="accordion-header d-flex justify-content-between align-items-center" id="heading-ig-{{ $i }}">
          <button class="accordion-button {{ $i > 0 ? 'collapsed' : '' }}" type="button"
            data-bs-toggle="collapse" data-bs-target="#details-ig-{{ $i }}"
            aria-expanded="{{ $i == 0 ? 'true' : 'false' }}" aria-controls="details-ig-{{ $i }}">
            <span class="accordion-title"></span>
          </button>
          @if($i > 0)
            <button type="button" class="btn btn-sm btn-danger mx-2" onclick="removeIG({{ $i }})">
              <i class="bi bi-trash-fill"></i>
            </button>
          @endif
        </h2>

        <div id="details-ig-{{ $i }}" class="accordion-collapse collapse {{ $i == 0 ? 'show' : '' }}"
          aria-labelledby="heading-ig-{{ $i }}" data-bs-parent="#accordionIG">
          <div class="accordion-body">
            <div class="row">

              {{-- INVNO tidak digunakan di IG, tapi backend butuh invno[] -> isi PONO biar aman --}}
              <input type="text" name="invno[]" class="invno-ig" id="invno-ig-{{ $i }}" value="{{ old('refcno') }}" hidden>

              <div class="col-md-6 mt-3">
                <label class="form-label">Barang</label><span class="text-danger"> *</span>
                <select class="select2 form-control opron-ig" name="opron[]" id="opron-ig-{{ $i }}" required>
                  <option value="" disabled {{ old('opron.'.$i) ? '' : 'selected' }}>Pilih Barang</option>
                </select>
              </div>

              <div class="col-md-6 mt-3">
                <label for="inqty-ig-{{ $i }}" class="form-label">PO Quantity</label>
                <div class="input-group">
                  <input type="number" class="form-control inqty-ig" id="inqty-ig-{{ $i }}" readonly style="background-color:#e9ecef;" value="{{ old('inqty.'.$i) }}">
                  <span class="input-group-text unit-label-ig"></span>
                  <input type="text" class="stdqt-ig" name="stdqt[]" id="stdqt-ig-{{ $i }}" hidden>
                </div>
              </div>

              <div class="col-md-6 mt-3">
                  <label for="trqty-ig-{{ $i }}" class="form-label">Receipt Quantity</label><span class="text-danger"> *</span>
                  <div class="input-group">
                    <input type="number" class="form-control trqty-ig" id="trqty-ig-{{ $i }}" name="trqty[]" value="{{ old('trqty.'.$i, 1) }}" min="1" required
                    oninput="
                        this.value = this.value.replace(/[^0-9]/g, '');
                        const inqty = Number(document.getElementById('inqty-ig-{{ $i }}')?.value || 0);

                        // kalau gak ada qty PO → jangan validasi
                        if(!inqty || inqty <= 0){ return; }

                        if (Number(this.value) > inqty) {
                            Swal.fire({
                                title: 'Peringatan',
                                text: 'Jumlah Receipt qty tidak boleh lebih banyak dari jumlah PO qty',
                                icon: 'error'
                            });
                            this.value = inqty;
                        }
                    ">
                    <span class="input-group-text unit-label-ig"></span>
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
                  <label for="lotno-ig-{{ $i }}" class="form-label">Serial / Batch No.</label>
                  <input type="text" class="form-control lotno-input" name="lotno[]" id="lotno-ig-{{ $i }}" value="{{ old('lotno.'.$i) }}">
              </div>

              <div class="col-md-6 mt-3 lot-section">
                  <label for="lotnoend-ig-{{ $i }}" class="form-label">Serial / Batch No. (Akhir)</label>
                  <input type="text" class="form-control lotnoend-ig" name="lotnoend[]" id="lotnoend-ig-{{ $i }}" readonly style="background-color:#e9ecef;" value="{{ old('lotnoend.'.$i) }}">
              </div>

              <div class="col-md-6 mt-3">
                <label for="pono-ig-{{ $i }}" class="form-label">PO No.</label>
                <input type="text" class="form-control" name="pono[]" id="pono-ig-{{ $i }}" value="{{ old('pono.'.$i) }}" readonly style="background-color:#e9ecef">
              </div>

              <div class="col-md-6 mt-3">
                <label class="form-label">Warehouse Location</label><span class="text-danger"> *</span>
                <select class="form-control select2" name="locco[]" id="locco-ig-{{ $i }}" required>
                  <option value="" disabled selected>Pilih Warehouse terlebih dahulu</option>
                </select>
              </div>

              <div class="col-md-12 mt-3">
                <label class="form-label">Notes</label>
                <textarea type="text" class="form-control" name="noted[]" id="noted-ig-{{ $i }}" maxlength="200">{{ old('noted.'.$i) }}</textarea>
                <div class="form-text text-danger text-end" style="font-size:0.7rem;">Maksimal 200 karakter</div>
              </div>

            </div>
          </div>
        </div>
      </div>
    @endforeach
  </div>

  <div class="text-end">
    <button type="button" class="btn mt-3" style="background-color:#4456f1;color:#fff" onclick="addIG()">Tambah Detail (IG)</button>
  </div>
</div>

@push('scripts')
<script>
  // load PO list (IG)
  loadPOList('#refcno_ig');

  // on open IG section
  $(document).on('change','#formc', function(){
    if($(this).val()==='IG'){ loadPOList(); }
  });

  // buat kirim supno ke db
  $('#supplier_ig').on('change', function(){
      $('#supno_ig').val($(this).val());
  });

  // saat PO berubah -> isi supplier, set hidden, load barang (PO detail)
  $('#refcno_ig').on('change', function(){
    const pono = $(this).val();
    $('#refcno_ig_submit').val(pono);
    $('#refno_ig').val(pono);

    // supplier by PO
    $.get(`{{ url('/get-po-supplier') }}/${pono}`, function(res){
      const labelTxt = res?.supno && res?.supna ? `${res.supno} - ${res.supna}` : (res?.supno || '');

      $('#supplier_ig').val(res?.supno).trigger('change');

      $('#supno_ig').val(res?.supno || ''); 
    });

    // load barang untuk semua row accordion IG
    $('select.opron-ig').each(function(i){
      const $sel = $(this);
      $sel.empty().append('<option value="">Loading...</option>');
      $.ajax({
        url: `{{ url('/get-barang') }}/${pono}?formc=IG`,
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
    $('.invno-ig').val(pono);
    $('[id^="pono-ig-"]').val(pono);
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

  // pilih barang (IG)
  $(document).on('change', 'select.opron-ig', function(){
    const $opt = $(this).find(':selected');
    const idx = this.id.split('-').pop();
    const qty = $opt.data('qty') || 0; 
    const pono = $opt.data('pono') || '-';
    const stdqt = $opt.data('stdqt');

    $(`#inqty-ig-${idx}`).val(qty);
    $(`#stdqt-ig-${idx}`).val(stdqt);
    $(`#trqty-ig-${idx}`).next('.input-group-text').text(stdqt);
    $(`#inqty-ig-${idx}`).next('.input-group-text').text(stdqt);
    $(`#pono-ig-${idx}`).val(pono);
  });

  // locco by warco (IG)
  $('#warco').on('change', function(){
    const warco = $(this).val();
    $('select[id^="locco-ig-"]').each(function(){
      const $sel = $(this);
      $sel.empty().append('<option value="">Loading...</option>');
      $.get(`{{ url('/get-locco') }}/${warco}`, function(data){
        $sel.empty().append('<option disabled selected>Pilih Lokasi</option>');
        data.forEach(item => $sel.append(`<option value="${item.locco}">${item.locco}</option>`));
        $sel.trigger('change.select2');
      });
    });
  });

  // auto lot end (IG)
  $(document).on('input', 'input[id^="lotno-ig-"], input[id^="trqty-ig-"]', function(){
    const idx = this.id.split('-').pop();
    const lotStart = $(`#lotno-ig-${idx}`).val();
    const trqty = parseInt($(`#trqty-ig-${idx}`).val()) || 0;
    if(!lotStart || trqty<=0){ $(`#lotnoend-ig-${idx}`).val(''); return; }

    const matches = [...lotStart.matchAll(/\d+/g)];
    if(matches.length===0){ $(`#lotnoend-ig-${idx}`).val(lotStart); return; }

    let chosen = (matches.length===1) ? matches[0] : matches.reduce((p,c)=> (c[0].length<=p[0].length ? c : p));
    const number = parseInt(chosen[0]), next = number + trqty - 1;
    const paddedNext = String(next).padStart(chosen[0].length,'0');
    const endStr = lotStart.slice(0, chosen.index) + paddedNext + lotStart.slice(chosen.index + chosen[0].length);
    $(`#lotnoend-ig-${idx}`).val(endStr);
  });

  // add/remove row IG
  window.addIG = function(){
    const i = $('#accordionIG .accordion-item').length;
    const dtl = `
      <div class="accordion-item" id="accordion-ig-item-${i}">
        <h2 class="accordion-header d-flex justify-content-between align-items-center" id="heading-ig-${i}">
          <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#details-ig-${i}" aria-expanded="false" aria-controls="details-ig-${i}"><span class="accordion-title"></span></button>
          <button type="button" class="btn btn-sm btn-danger mx-2" onclick="removeIG(${i})"><i class="bi bi-trash-fill"></i></button>
        </h2>
        <div id="details-ig-${i}" class="accordion-collapse collapse" aria-labelledby="heading-ig-${i}" data-bs-parent="#accordionIG">
          <div class="accordion-body">
            <div class="row">

              <input type="text" name="invno[]" class="invno-ig" id="invno-ig-${i}" value="${$('#refcno_ig_submit').val()||''}" hidden>

              <div class="col-md-6 mt-3">
                <label class="form-label">Barang</label><span class="text-danger"> *</span>
                <select class="select2 form-control opron-ig" name="opron[]" id="opron-ig-${i}" required>
                  <option value="" disabled selected>Pilih Barang</option>
                </select>
              </div>

              <div class="col-md-6 mt-3">
                <label class="form-label">PO Quantity</label>
                <div class="input-group">
                  <input type="number" class="form-control inqty-ig" id="inqty-ig-${i}" readonly style="background-color:#e9ecef;">
                  <span class="input-group-text unit-label-ig"></span>
                  <input type="text" class="stdqt-ig" name="stdqt[]" id="stdqt-ig-${i}" hidden>
                </div>
              </div>

              <div class="col-md-6 mt-3">
                <label class="form-label">Receipt Quantity</label><span class="text-danger"> *</span>
                <div class="input-group">
                  <input type="number" class="form-control trqty-ig" id="trqty-ig-${i}" name="trqty[]" value="1" min="1" required
                  oninput="
                      this.value = this.value.replace(/[^0-9]/g, '');
                      const inqty = Number(document.getElementById('inqty-ig-{{ $i }}')?.value || 0);

                      // kalau gak ada qty PO → jangan validasi
                      if(!inqty || inqty <= 0){ return; }

                      if (Number(this.value) > inqty) {
                          Swal.fire({
                              title: 'Peringatan',
                              text: 'Jumlah Receipt qty tidak boleh lebih banyak dari jumlah PO qty',
                              icon: 'error'
                          });
                          this.value = inqty;
                      }
                  ">
                  <span class="input-group-text unit-label-ig"></span>
                </div>
              </div>

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
                <input type="text" class="form-control" name="lotno[]" id="lotno-ig-${i}">
              </div>

              <div class="col-md-6 mt-3 lot-section">
                <label class="form-label">Serial / Batch No. (Akhir)</label>
                <input type="text" class="form-control lotnoend-ig" name="lotnoend[]" id="lotnoend-ig-${i}" readonly style="background-color:#e9ecef;">
              </div>

              <div class="col-md-6 mt-3">
                <label class="form-label">PO No.</label>
                <input type="text" class="form-control" name="pono[]" id="pono-ig-${i}" readonly style="background-color:#e9ecef" value="${$('#refcno_ig_submit').val()||''}">
              </div>

              <div class="col-md-6 mt-3">
                <label class="form-label">Warehouse Location</label><span class="text-danger"> *</span>
                <select class="form-control select2" name="locco[]" id="locco-ig-${i}" required>
                  <option value="" disabled selected>Pilih Warehouse terlebih dahulu</option>
                </select>
              </div>

              <div class="col-md-12 mt-3">
                <label class="form-label">Notes</label>
                <textarea class="form-control" name="noted[]" id="noted-ig-${i}" maxlength="200"></textarea>
                <div class="form-text text-danger text-end" style="font-size:0.7rem;">Maksimal 200 karakter</div>
              </div>

            </div>
          </div>
        </div>
      </div>`;
    $('#accordionIG').append(dtl);
    $('.select2').select2({ width:'100%', theme: 'bootstrap-5' });
    setTimeout(()=>{
        $(`#details-ig-${i}`).collapse('show');
    },100);

    // kalau PO sudah dipilih, load barang ke row baru
    const pono = $('#refcno_ig_submit').val();
    if(pono){
      const $sel = $(`#opron-ig-${i}`);
      $sel.empty().append('<option value="">Loading...</option>');
      $.get(`{{ url('/get-barang') }}/${pono}?formc=IG`, function(data){
        $sel.empty().append('<option value="" disabled selected>Pilih Barang</option>');
        data.forEach(item => $sel.append(`<option value="${item.opron}" data-qty="${item.inqty}" data-stdqt="${item.stdqt}" data-pono="${item.pono}">${item.opron} - ${item.prona}</option>`));
      });
    }

    // kalau WARCO sudah dipilih -> load warehouse ke row baru IB juga
    const warco = $('#warco').val();
    if(warco){
      const $sel = $(`#locco-ig-${i}`);
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

  window.removeIG = function(i){
    $(`#accordion-ig-item-${i}`).remove();
  }
</script>
@endpush
