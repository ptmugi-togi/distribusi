{{-- IM (Return) --}}
<div class="row mt-4">
    <div class="col-md-6 mt-3">
        <label for="reference" class="form-label">Reference<span class="text-danger"> *</span></label>
        <select class="form-control select2" name="refcno" id="refcno">
            <option value="" disabled selected>Pilih Reference</option>
        </select>
        <input type="text" name="reffc" id="reffc-store" hidden>
        <input type="text" name="refno" id="refno-store" hidden>
    </div>

    <div class="col-md-12 mt-3">
        <label for="noteh_im" class="form-label">Notes</label>
        <textarea class="form-control" name="noteh" id="noteh_im" maxlength="200">{{ old('noteh') }}</textarea>
        <div class="form-text text-danger text-end" style="font-size:0.7rem;">Maksimal 200 karakter</div>
    </div>
</div>

<div class="row">
  <h4 class="my-2">BBM Detail (IM)</h4>
  <div class="accordion" id="accordionIM">
    @foreach (old('opron', [null]) as $i => $oldOpron)
      <div class="accordion-item" id="accordion-im-item-{{ $i }}">
        <h2 class="accordion-header d-flex justify-content-between align-items-center" id="heading-im-{{ $i }}">
          <button class="accordion-button {{ $i > 0 ? 'collapsed' : '' }}" type="button"
            data-bs-toggle="collapse" data-bs-target="#details-im-{{ $i }}"
            aria-expanded="{{ $i == 0 ? 'true' : 'false' }}" aria-controls="details-im-{{ $i }}">
            <span class="accordion-title"></span>
          </button>
          @if($i > 0)
            <button type="button" class="btn btn-sm btn-danger mx-2" onclick="removeIM({{ $i }})">
              <i class="bi bi-trash-fill"></i>
            </button>
          @endif
        </h2>

        <div id="details-im-{{ $i }}" class="accordion-collapse collapse {{ $i == 0 ? 'show' : '' }}"
          aria-labelledby="heading-im-{{ $i }}" data-bs-parent="#accordionIM">
          <div class="accordion-body">
            <div class="row">
                {{-- INVNO tidak digunakan di IM, tapi backend butuh invno[] -> isi PONO biar aman --}}
                <input type="text" name="invno[]" class="invno-im" id="invno-im-{{ $i }}" value="{{ old('refcno') }}" hidden>

                <div class="col-md-6 mt-3">
                    <label class="form-label">Barang</label><span class="text-danger"> *</span>
                    <select class="select2 form-control opron-im" name="opron[]" id="opron-im-{{ $i }}" required>
                    <option value="" disabled {{ old('opron.'.$i) ? '' : 'selected' }}>Pilih Barang</option>
                    </select>
                </div>

                <input type="number" class="stock-im" name="stock[]" id="stock-im-{{ $i }}" hidden>

                <div class="col-md-6 mt-3">
                    <label for="trqty-im-{{ $i }}" class="form-label">Receipt Quantity</label><span class="text-danger"> *</span>
                    <div class="input-group">
                    <input type="number" class="form-control trqty-im" id="trqty-im-{{ $i }}" name="trqty[]" value="{{ old('trqty.'.$i) }}" min="1" required
                    oninput="this.value = this.value.replace(/[^0-9]/g, '');">
                    <span class="input-group-text unit-label-im"></span>
                    </div>
                </div>

                <input type="text" class="nolot-im" name="nolot[]" id="nolot-im-{{ $i }}" value="1" hidden>

                <input type="text" class="lotno-im" name="lotno[]" id="lotno-im-{{ $i }}" value="-" hidden>
                
                <input type="text" class="stdqt-im" name="stdqt[]" id="stdqt-im-{{ $i }}" hidden>
                
                <input type="text" class="locco-im" name="locco[]" id="locco-im-{{ $i }}" value="000001" hidden>

              <div class="col-md-12 mt-3">
                <label class="form-label">Notes</label>
                <textarea type="text" class="form-control" name="noted[]" id="noted-im-{{ $i }}" maxlength="200">{{ old('noted.'.$i) }}</textarea>
                <div class="form-text text-danger text-end" style="font-size:0.7rem;">Maksimal 200 karakter</div>
              </div>

            </div>
          </div>
        </div>
      </div>
    @endforeach
  </div>

  <div class="text-end">
    <button type="button" class="btn mt-3" style="background-color:#4456f1;color:#fff" onclick="addIM()">Tambah Detail (IM)</button>
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

    $(document).on('change', '#formc, #warco', function () {
        const formc = $('#formc').val();
        const warco = $('#warco').val();
        const $ref = $('#refcno');

        // reset dulu
        $ref
            .prop('disabled', true)
            .html('<option disabled selected>Pilih Warehouse terlebih dahulu</option>');

        if (formc !== 'IM' || !warco) {
            return;
        }

        $ref.html('<option disabled selected>Loading...</option>');

        $.ajax({
            url: "{{ route('get.ob') }}",
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
                            data-refno="${item.trano}">
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

    // fucntion pilih barang (IM)
    function loadOpronByOB(trano, idx = null) {
        const warco = $('#warco').val();

        const $targets = idx !== null
            ? $(`#opron-im-${idx}`)
            : $('.opron-im');

        // guard clause: wajib lengkap
        if (!trano || !warco) {
            $targets
                .prop('disabled', true)
                .html('<option value="" disabled selected>Pilih Reference & Warehouse terlebih dahulu</option>');
            return;
        }

        $targets
            .prop('disabled', true)
            .html('<option value="" disabled selected>Loading...</option>');

        $.ajax({
            url: "{{ route('get.opron.by.ob') }}",
            type: "GET",
            data: { trano, warco },
            success(response) {
                $targets.each(function () {
                    const $sel = $(this);
                    const oldValue = $sel.val();

                    if (!Array.isArray(response) || response.length === 0) {
                        $sel
                            .html('<option value="" disabled selected>Tidak ada barang tersedia</option>')
                            .prop('disabled', true);
                        return;
                    }

                    $sel.empty().append('<option value="" disabled selected>Pilih Barang</option>');

                    response.forEach(item => {
                        $sel.append(`
                            <option
                                value="${item.opron}"
                                data-qty="${item.trqty}"
                                data-stdqt="${item.qunit}"
                                data-lotno="${item.lotno}"
                                data-locco="${item.locco}">
                                ${item.opron} - ${item.prona}
                            </option>
                        `);
                    });

                    if (oldValue && response.some(item => item.opron === oldValue)) {
                        $sel.val(oldValue);
                    }

                    $sel.prop('disabled', false);
                });
            },
            error() {
                $targets
                    .html('<option value="" disabled selected>Gagal memuat data</option>')
                    .prop('disabled', false);
                }
            });
    }

    // function pilih barang (IM)
    $(document).on('change', '#refcno, #warco', function () {
        if (this.id === 'refcno') {
            const $selected = $('#refcno').find(':selected');
            $('#reffc-store').val($selected.data('reffc'));
            $('#refno-store').val($selected.data('refno'));
        }

        const trano = $('#refcno').val();
        loadOpronByOB(trano);
    });

    // detail barang (IM)
    $(document).on('change', 'select.opron-im', function(){
        const $opt = $(this).find(':selected');
        const idx = this.id.split('-').pop();
        const stdqt = $opt.data('stdqt');
        const stock = $opt.data('qty');

        $(`#stdqt-im-${idx}`).val(stdqt);
        $(`#stock-im-${idx}`).val(stock);
        $(`#trqty-im-${idx}`).next('.input-group-text').text(stdqt);
    });

    // VALIDASI INPUT QTY
    $(document).on('input', '.trqty-im', function() {
        const idx = this.id.split('-').pop();
        const qty = parseFloat($(this).val()) || 0;
        const max = parseFloat($(`#stock-im-${idx}`).val()) || 0;

        if (qty > max) {
            Swal.fire({
            icon: 'error',
            title: 'Qty Melebihi Stok',
            text: `Jumlah input (${qty}) melebihi stok tersedia (${max}).`
            });
            $(this).val(max);
        }
    });

    // add/remove row IM
    window.addIM = function(){
        const i = $('#accordionIM .accordion-item').length;
        const dtl = `
        <div class="accordion-item" id="accordion-im-item-${i}">
            <h2 class="accordion-header d-flex justify-content-between align-items-center" id="heading-im-${i}">
            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#details-im-${i}" aria-expanded="false" aria-controls="details-im-${i}"><span class="accordion-title"></span></button>
            <button type="button" class="btn btn-sm btn-danger mx-2" onclick="removeIM(${i})"><i class="bi bi-trash-fill"></i></button>
            </h2>
            <div id="details-im-${i}" class="accordion-collapse collapse" aria-labelledby="heading-im-${i}" data-bs-parent="#accordionIM">
            <div class="accordion-body">
                <div class="row">

                <input type="text" name="invno[]" class="invno-im" id="invno-im-${i}" value="${$('#refcno_im_submit').val()||''}" hidden>

                <div class="col-md-6 mt-3">
                    <label class="form-label">Barang</label><span class="text-danger"> *</span>
                    <select class="select2 form-control opron-im" name="opron[]" id="opron-im-${i}" required>
                    <option value="" disabled selected>Pilih Barang</option>
                    </select>
                </div>

                <input type="text" class="stock-im" name="stock[]" id="stock-im-${i}" hidden>

                <div class="col-md-6 mt-3">
                    <label class="form-label">Receipt Quantity</label><span class="text-danger"> *</span>
                    <div class="input-group">
                    <input type="number" class="form-control trqty-im" id="trqty-im-${i}" name="trqty[]" value="1" min="1" required
                    oninput="this.value = this.value.replace(/[^0-9]/g, '');">
                    <span class="input-group-text unit-label-im"></span>
                    </div>
                </div>

                <input type="text" class="nolot-im" name="nolot[]" id="nolot-im-${i}" value="1" hidden>
                
                <input type="text" class="lotno-im" name="lotno[]" id="lotno-im-${i}" value="-" hidden>

                <input type="text" class="stdqt-im" name="stdqt[]" id="stdqt-im-${i}" hidden>

                <input type="text" class="locco-im" name="locco[]" id="locco-im-${i}" value="000001" hidden>

                <div class="col-md-12 mt-3">
                    <label class="form-label">Notes</label>
                    <textarea class="form-control" name="noted[]" id="noted-im-${i}" maxlength="200"></textarea>
                    <div class="form-text text-danger text-end" style="font-size:0.7rem;">Maksimal 200 karakter</div>
                </div>

                </div>
            </div>
            </div>
        </div>`;
        $('#accordionIM').append(dtl);
        $('.select2').select2({ width:'100%', theme: 'bootstrap-5' });
        setTimeout(()=>{
            $(`#details-im-${i}`).collapse('show');
        },100);

        const trano = $('#refcno').val();
        loadOpronByOB(trano, i);
    }

    window.removeIM = function(i){
        $(`#accordion-im-item-${i}`).remove();
    }
</script>
@endpush
