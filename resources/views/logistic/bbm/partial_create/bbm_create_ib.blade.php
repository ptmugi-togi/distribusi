{{-- IB (IMPORT) --}}
<div class="row mt-4">
  <h4 class="my-2">Header (Import / IB)</h4>

  <div class="col-md-6 mt-3">
    <label for="refcno_ib" class="form-label">Receiving Instruction</label><span class="text-danger"> *</span>
    <select class="form-control select2" id="refcno_ib" name="refcno">
      <option value="" disabled selected>Pilih Receiving Instruction</option>
      @foreach ($tsupih as $t)
        <option value="{{ $t->rinum }}"
          data-reffc="{{ $t->formc }}"
          data-refno="{{ $t->rinum }}"
          data-supno="{{ $t->supno }}"
          data-supna="{{ $t->supna }}"
          data-blnum="{{ $t->blnum }}"
          data-vesel="{{ $t->vesel }}">
          {{ $t->formc }} {{ $t->rinum }}
        </option>
      @endforeach
    </select>
    <input type="text" name="reffc" id="reffc_ib" hidden>
    <input type="text" name="refno" id="refno_ib" hidden>
  </div>

  <div class="col-md-6 mt-3">
      <label for="supplier_ia" class="form-label">Supplier</label>
      <select class="form-control select2" id="supplier_ib" disabled>
          <option value="" disabled selected>Pilih Supplier</option>
          @foreach ($vendors->where('vgrp', 'I') as $v)
              <option value="{{ $v->supno }}">{{ $v->supno }} - {{ $v->supna }}</option>
          @endforeach
      </select>
      <input type="text" name="supno" id="supno_ib" hidden>
  </div>

  <div class="col-md-6 mt-3">
    <label class="form-label">BL No.</label>
    <input type="text" class="form-control" name="blnum" id="blnum_ib" readonly style="background-color:#e9ecef">
  </div>

  <div class="col-md-6 mt-3">
    <label class="form-label">Vessel</label>
    <input type="text" class="form-control" name="vesel" id="vesel_ib" readonly style="background-color:#e9ecef">
  </div>

  <div class="col-md-12 mt-3">
    <label class="form-label">Notes</label>
    <textarea class="form-control" name="noteh" id="noteh_ib" maxlength="200">{{ old('noteh') }}</textarea>
    <div class="form-text text-danger text-end" style="font-size:0.7rem;">Maksimal 200 karakter</div>
  </div>
</div>

<div class="row">
  <h4 class="my-2">BBM Detail (IB)</h4>
  <div class="accordion" id="accordionIB">
    @foreach (old('invno', [null]) as $i => $oldInvno)
      <div class="accordion-item" id="accordion-ib-item-{{ $i }}">
        <h2 class="accordion-header d-flex justify-content-between align-items-center" id="heading-ib-{{ $i }}">
          <button class="accordion-button {{ $i > 0 ? 'collapsed' : '' }}" type="button"
            data-bs-toggle="collapse" data-bs-target="#details-ib-{{ $i }}"
            aria-expanded="{{ $i == 0 ? 'true' : 'false' }}" aria-controls="details-ib-{{ $i }}">
            <span class="accordion-title"></span>
          </button>
          @if($i > 0)
            <button type="button" class="btn btn-sm btn-danger mx-2" onclick="removeIB({{ $i }})">
              <i class="bi bi-trash-fill"></i>
            </button>
          @endif
        </h2>

        <div id="details-ib-{{ $i }}" class="accordion-collapse collapse {{ $i == 0 ? 'show' : '' }}"
          aria-labelledby="heading-ib-{{ $i }}" data-bs-parent="#accordionIB">
          <div class="accordion-body">
            <div class="row">
              <div class="col-md-6 mt-3">
                <label class="form-label">Invoice No.</label>
                <select class="select2 form-control invno-ib" id="invno-ib-{{ $i }}" required>
                  <option value="" disabled {{ old('invno.'.$i) ? '' : 'selected' }}>Silahkan Pilih RI terlebih dahulu</option>
                </select>
                <input type="text" class="invno-hidden" name="invno[]" id="invno-ib-hidden-{{ $i }}" data-index="{{ $i }}">
              </div>

              <div class="col-md-6 mt-3">
                <label class="form-label">Barang</label><span class="text-danger"> *</span>
                <select class="select2 form-control opron-ib" name="opron[]" id="opron-ib-{{ $i }}" required>
                  <option value="" disabled {{ old('opron.'.$i) ? '' : 'selected' }}>Pilih Barang</option>
                </select>
              </div>

              <div class="col-md-6 mt-3">
                <label class="form-label">Invoice Quantity</label>
                <div class="input-group">
                  <input type="number" class="form-control inqty-ib" id="inqty-ib-{{ $i }}" readonly style="background-color:#e9ecef;" value="{{ old('inqty.'.$i) }}">
                  <span class="input-group-text unit-label-ib"></span>
                  <input type="text" class="stdqt-ib" name="stdqt[]" id="stdqt-ib-{{ $i }}" hidden>
                </div>
                <div class="form-check mt-3">
                    <input class="form-check-input nolot-checkbox" type="checkbox" value="1" name="nolot[{{ $i }}]" id="nolot-{{ $i }}">
                    <label class="form-check-label" for="nolot-{{ $i }}">
                        Without Serial / Batch No
                    </label>
                </div>
              </div>

              <div class="col-md-6 mt-3">
                  <label for="trqty-ib-{{ $i }}" class="form-label">Receipt Quantity</label><span class="text-danger"> *</span>
                  <div class="input-group">
                    <input type="number" class="form-control trqty-ib" id="trqty-ib-{{ $i }}" name="trqty[]" value="{{ old('trqty.'.$i, 1) }}" min="1" required
                    oninput="
                        this.value = this.value.replace(/[^0-9]/g, '');
                        const inqty = Number(document.getElementById('inqty-ia-{{ $i }}')?.value || 0);

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
                    <span class="input-group-text unit-label-ib"></span>
                  </div>
              </div>
              
              <div class="col-md-6 mt-3 lot-section">
                <label for="lotno-ib-{{ $i }}" class="form-label">Serial / Batch No.</label>
                <input type="text" class="form-control lotno-input" name="lotno[]" id="lotno-ib-{{ $i }}" value="{{ old('lotno.'.$i) }}">
              </div>

              <div class="col-md-6 mt-3 lot-section">
                  <label for="lotnoend-ib-{{ $i }}" class="form-label">Serial / Batch No. (Akhir)</label>
                  <input type="text" class="form-control lotnoend-ib" name="lotnoend[]" id="lotnoend-ib-{{ $i }}" readonly style="background-color:#e9ecef;" value="{{ old('lotnoend.'.$i) }}">
              </div>

              <div class="col-md-6 mt-3">
                <label class="form-label">PO No.</label>
                <input type="text" class="form-control" name="pono[]" id="pono-ib-{{ $i }}" value="{{ old('pono.'.$i) }}" readonly style="background-color:#e9ecef">
              </div>

              <div class="col-md-6 mt-3">
                <label class="form-label">Warehouse Location</label><span class="text-danger"> *</span>
                <select class="form-control select2" name="locco[]" id="locco-ib-{{ $i }}" required>
                  <option value="" disabled selected>Pilih Warehouse terlebih dahulu</option>
                </select>
              </div>

              <div class="col-md-12 mt-3">
                <label class="form-label">Notes</label>
                <textarea class="form-control" name="noted[]" id="noted-ib-{{ $i }}" maxlength="200">{{ old('noted.'.$i) }}</textarea>
                <div class="form-text text-danger text-end" style="font-size:0.7rem;">Maksimal 200 karakter</div>
              </div>

            </div>
          </div>
        </div>
      </div>
    @endforeach
  </div>

  <div class="text-end">
    <button type="button" class="btn mt-3" style="background-color:#4456f1;color:#fff" onclick="addIB()">Tambah Detail (IB)</button>
  </div>
</div>

@push('scripts')
<script>
  // RI change -> isi supplier/BL/Vessel & load invoices
  $('#refcno_ib').on('change', function(){
    const sel = $(this).find(':selected');
    const formc = sel.data('reffc');
    const rinum = sel.data('refno');
    const supno = sel.data('supno');
    const supna = sel.data('supna');
    const blnum = sel.data('blnum');
    const vesel = sel.data('vesel');

    $('#reffc_ib').val(formc);
    $('#refno_ib').val(rinum);
    $('#supplier_ib').val(supno).trigger('change.select2');
    $('#supno_ib').val(supno);
    $('#blnum_ib').val(blnum);
    $('#vesel_ib').val(vesel);

    // load invoice ke semua detail row
    $('select.invno-ib').each(function(i){
      const $select = $(this);
      $select.empty().append('<option value="">Loading...</option>');
      $.get(`{{ url('/get-invoice') }}/${rinum}`, function (data) {
        $select.empty().append('<option value="" disabled selected>Pilih Invoice No.</option>');
        data.forEach(function (item) {
          $select.append(`<option value="${item.invno}">${item.invno}</option>`);
        });
      });
    });
  });

  // invoice -> load barang (filter: rcqty < inqty)
  $(document).on('change', 'select.invno-ib', function(){
    const invno = $(this).val();
    const idx = this.id.split('-').pop();
    const $barang = $(`#opron-ib-${idx}`);
    $barang.empty().append('<option value="">Loading...</option>');
    $.get(`{{ url('/get-barang') }}/${invno}?formc=IB`, function(data){
      $barang.empty().append('<option value="" disabled selected>Pilih Barang</option>');
      data.forEach(item=>{
        $barang.append(`<option value="${item.opron}" data-qty="${item.inqty}" data-stdqt="${item.stdqt}" data-pono="${item.pono}">${item.opron} - ${item.prona}</option>`);
      });
    });
  });

  // buat kirim supno ke db
  $('#supplier_ib').on('change', function(){
      $('#supno_ib').val($(this).val());
  });

  $(document).on('change', '.invno-ib', function(){
      const idx = this.id.split('-').pop();
      $('#invno-ib-hidden-' + idx).val($(this).val());
  });

  // barang -> fill otomatis qty/unit/pono
  $(document).on('change', 'select.opron-ib', function(){
    const sel = $(this).find(':selected');
    const idx = this.id.split('-').pop();
    const qty = sel.data('qty'), stdqt = sel.data('stdqt'), pono = sel.data('pono');
    $(`#inqty-ib-${idx}`).val(qty);
    $(`#stdqt-ib-${idx}`).val(stdqt);
    $(`#trqty-ib-${idx}`).next('.input-group-text').text(stdqt);
    $(`#inqty-ib-${idx}`).next('.input-group-text').text(stdqt);
    $(`#pono-ib-${idx}`).val(pono);
  });

  // locco by warco (IB)
  $('#warco').on('change', function(){
    const warco = $(this).val();
    $('select[id^="locco-ib-"]').each(function(){
      const $sel = $(this);
      $sel.empty().append('<option value="">Loading...</option>');
      $.get(`{{ url('/get-locco') }}/${warco}`, function(data){
        $sel.empty().append('<option disabled selected>Pilih Lokasi</option>');
        data.forEach(item => $sel.append(`<option value="${item.locco}">${item.locco}</option>`));
        $sel.trigger('change.select2');
      });
    });
  });

  // auto lot end (IB)
  $(document).on('input', 'input[id^="lotno-ib-"], input[id^="trqty-ib-"]', function(){
    const idx = this.id.split('-').pop();
    const lotStart = $(`#lotno-ib-${idx}`).val();
    const trqty = parseInt($(`#trqty-ib-${idx}`).val()) || 0;
    if(!lotStart || trqty<=0){ $(`#lotnoend-ib-${idx}`).val(''); return; }

    const matches = [...lotStart.matchAll(/\d+/g)];
    if(matches.length===0){ $(`#lotnoend-ib-${idx}`).val(lotStart); return; }

    let chosen = (matches.length===1) ? matches[0] : matches.reduce((p,c)=> (c[0].length<=p[0].length ? c : p));
    const number = parseInt(chosen[0]), next = number + trqty - 1;
    const paddedNext = String(next).padStart(chosen[0].length,'0');
    const endStr = lotStart.slice(0, chosen.index) + paddedNext + lotStart.slice(chosen.index + chosen[0].length);
    $(`#lotnoend-ib-${idx}`).val(endStr);
  });

  // add/remove row IB
  window.addIB = function(){
    const i = $('#accordionIB .accordion-item').length;
    const dtl = `
      <div class="accordion-item" id="accordion-ib-item-${i}">
        <h2 class="accordion-header d-flex justify-content-between align-items-center" id="heading-ib-${i}">
          <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#details-ib-${i}" aria-expanded="false" aria-controls="details-ib-${i}"><span class="accordion-title"></span></button>
          <button type="button" class="btn btn-sm btn-danger mx-2" onclick="removeIB(${i})"><i class="bi bi-trash-fill"></i></button>
        </h2>
        <div id="details-ib-${i}" class="accordion-collapse collapse" aria-labelledby="heading-ib-${i}" data-bs-parent="#accordionIB">
          <div class="accordion-body">
            <div class="row">
              <div class="col-md-6 mt-3">
                <label class="form-label">Invoice No.</label><span class="text-danger"> *</span>
                <select class="select2 form-control invno-ib" name="invno[]" id="invno-ib-${i}" required>
                  <option value="" disabled selected>Pilih RI terlebih dahulu</option>
                </select>
              </div>
              <div class="col-md-6 mt-3">
                <label class="form-label">Barang</label><span class="text-danger"> *</span>
                <select class="select2 form-control opron-ib" name="opron[]" id="opron-ib-${i}" required>
                  <option value="" disabled selected>Pilih Barang</option>
                </select>
              </div>
              <div class="col-md-6 mt-3">
                <label class="form-label">Invoice Quantity</label>
                <div class="input-group">
                  <input type="number" class="form-control inqty-ib" id="inqty-ib-${i}" readonly style="background-color:#e9ecef;">
                  <span class="input-group-text unit-label-ib"></span>
                  <input type="text" class="stdqt-ib" name="stdqt[]" id="stdqt-ib-${i}" hidden>
                </div>
                <div class="form-check mt-3">
                    <input class="form-check-input nolot-checkbox" type="checkbox" value="1" name="nolot[${i}]" id="nolot-${i}">
                    <label class="form-check-label" for="nolot-${i}">
                        Without Serial / Batch No
                    </label>
                </div>
              </div>
              <div class="col-md-6 mt-3">
                  <label for="trqty-ib-${i}" class="form-label">Receipt Quantity</label><span class="text-danger"> *</span>
                  <div class="input-group">
                    <input type="number" class="form-control trqty-ib" id="trqty-ib-${i}" name="trqty[]" value="{{ old('trqty.'.$i, 1) }}" min="1" required
                    oninput="
                        this.value = this.value.replace(/[^0-9]/g, '');
                        const inqty = Number(document.getElementById('inqty-ia-{{ $i }}')?.value || 0);

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
                    <span class="input-group-text unit-label-ib"></span>
                  </div>
              </div>
              <div class="col-md-6 mt-3 lot-section">
                <label for="lotno-ib-${i}" class="form-label">Serial / Batch No.</label>
                <input type="text" class="form-control lotno-input" name="lotno[]" id="lotno-ib-${i}" value="{{ old('lotno.'.$i) }}">
              </div>
              <div class="col-md-6 mt-3 lot-section">
                  <label for="lotnoend-ib-${i}" class="form-label">Serial / Batch No. (Akhir)</label>
                  <input type="text" class="form-control lotnoend-ib" name="lotnoend[]" id="lotnoend-ib-${i}" readonly style="background-color:#e9ecef;" value="{{ old('lotnoend.'.$i) }}">
              </div>
              <div class="col-md-6 mt-3">
                <label class="form-label">PO No.</label>
                <input type="text" class="form-control" name="pono[]" id="pono-ib-${i}" readonly style="background-color:#e9ecef">
              </div>
              <div class="col-md-6 mt-3">
                <label class="form-label">Warehouse Location</label><span class="text-danger"> *</span>
                <select class="form-control select2" name="locco[]" id="locco-ib-${i}" required>
                  <option value="" disabled selected>Pilih Warehouse terlebih dahulu</option>
                </select>
              </div>
              <div class="col-md-12 mt-3">
                <label class="form-label">Notes</label>
                <textarea class="form-control" name="noted[]" id="noted-ib-${i}" maxlength="200"></textarea>
                <div class="form-text text-danger text-end" style="font-size:0.7rem;">Maksimal 200 karakter</div>
              </div>
            </div>
          </div>
        </div>
      </div>`;
    $('#accordionIB').append(dtl);
    $('.select2').select2({ width: '100%', theme: 'bootstrap-5' });
    setTimeout(()=>{
        $(`#details-ib-${i}`).collapse('show');
    },100);

    // kalau RI sudah dipilih, load invoice ke row baru
    const rinum = $('#refno_ib').val();
    if(rinum){
      const $inv = $(`#invno-ib-${i}`);
      $inv.empty().append('<option value="">Loading...</option>');
      $.get(`{{ url('/get-invoice') }}/${rinum}`, function(data){
        $inv.empty().append('<option value="" disabled selected>Pilih Invoice No.</option>');
        data.forEach(item => $inv.append(`<option value="${item.invno}">${item.invno}</option>`));
      });
    }

    // kalau WARCO sudah dipilih -> load warehouse ke row baru IB juga
    const warco = $('#warco').val();
    if(warco){
      const $sel = $(`#locco-ib-${i}`);
      $sel.empty().append('<option value="">Loading...</option>');
      $.get(`{{ url('/get-locco') }}/${warco}`, function(data){
        $sel.empty().append('<option disabled selected>Pilih Lokasi</option>');
        data.forEach(item => $sel.append(`<option value="${item.locco}">${item.locco}</option>`));
        $sel.trigger('change.select2');
      });
    }
    applyNoPoInvMode();
  }

  window.removeIB = function(i){
    $(`#accordion-ib-item-${i}`).remove();
  }
</script>
@endpush
