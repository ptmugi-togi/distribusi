{{-- OF (LOCAL PURCHASE) --}}
<div class="row mt-4">

  <div class="col-md-12 mt-3">
    <label for="noteh_of" class="form-label">Notes</label>
    <textarea class="form-control" name="noteh" id="noteh_of" maxlength="200">{{ old('noteh') }}</textarea>
    <div class="form-text text-danger text-end" style="font-size:0.7rem;">Maksimal 200 karakter</div>
  </div>
</div>

<div class="row">
  <h4 class="my-2">BBK Detail (OF)</h4>
  <div class="accordion" id="accordionOF">
    @foreach (old('opron', [null]) as $i => $oldOpron)
      <div class="accordion-item" id="accordion-of-item-{{ $i }}">
        <h2 class="accordion-header d-flex justify-content-between align-items-center" id="heading-of-{{ $i }}">
          <button class="accordion-button {{ $i > 0 ? 'collapsed' : '' }}" type="button"
            data-bs-toggle="collapse" data-bs-target="#details-of-{{ $i }}"
            aria-expanded="{{ $i == 0 ? 'true' : 'false' }}" aria-controls="details-of-{{ $i }}">
            <span class="accordion-title"></span>
          </button>
          @if($i > 0)
            <button type="button" class="btn btn-sm btn-danger mx-2" onclick="removeOF({{ $i }})">
              <i class="bi bi-trash-fill"></i>
            </button>
          @endif
        </h2>

        <div id="details-of-{{ $i }}" class="accordion-collapse collapse {{ $i == 0 ? 'show' : '' }}"
          aria-labelledby="heading-of-{{ $i }}" data-bs-parent="#accordionOF">
          <div class="accordion-body">
            <div class="row">
                <div class="col-md-6 mt-3">
                    <label class="form-label">Warehouse Location</label><span class="text-danger"> *</span>
                    <select class="form-control select2" name="locco[]" id="locco-of-{{ $i }}" required>
                    <option value="" disabled selected>Pilih Warehouse terlebih dahulu</option>
                    </select>
                </div>

                <div class="col-md-6 mt-3">
                    <label class="form-label">Barang</label><span class="text-danger"> *</span>
                    <select class="select2 form-control opron-of" name="opron[]" id="opron-of-{{ $i }}" required>
                    <option value="" disabled {{ old('opron.'.$i) ? '' : 'selected' }}>Pilih Warehouse Location Terlebih Dahulu</option>
                    </select>
                </div>

                <div class="col-md-6 mt-3 lot-section">
                    <label for="lotno-of-{{ $i }}" class="form-label">Serial / Batch No.</label><span class="text-danger"> *</span>
                    <select class="form-select select2 lotno-select" name="lotno[]" id="lotno-of-{{ $i }}" required>
                        <option value="" disabled selected>Pilih Barang Terlebih Dahulu</option>
                    </select>
                </div>
                                    
                <div class="col-md-6 mt-3">
                    <label for="toqoh-of-{{ $i }}" class="form-label">Sisa Stok</label>
                    <div class="input-group">
                        <input type="text" class="form-control text-end" id="toqoh-of-{{ $i }}" placeholder="-" disabled>
                        <span class="input-group-text unit-label-of" id="toqoh-unit-of-{{ $i }}">-</span>
                    </div>
                </div>

                <div class="col-md-6 mt-3">
                    <label for="trqty-of-{{ $i }}" class="form-label">Receipt Quantity</label><span class="text-danger"> *</span>
                    <div class="input-group">
                        <input type="number" class="form-control trqty-of" id="trqty-of-{{ $i }}" name="trqty[]" value="{{ old('trqty.'.$i, 1) }}" min="1" required
                        oninput="this.value = this.value.replace(/[^0-9]/g, '');">
                        <span class="input-group-text unit-label-of"></span>
                    </div>
                </div>
                
                <input type="text" class="stdqt-of" name="stdqt[]" id="stdqt-of-{{ $i }}" hidden>

                <div class="col-md-12 mt-3">
                    <label class="form-label">Notes</label>
                    <textarea type="text" class="form-control" name="noted[]" id="noted-of-{{ $i }}" maxlength="200">{{ old('noted.'.$i) }}</textarea>
                    <div class="form-text text-danger text-end" style="font-size:0.7rem;">Maksimal 200 karakter</div>
                </div>
            </div>
          </div>
        </div>
      </div>
    @endforeach
  </div>

  <div class="text-end">
    <button type="button" class="btn mt-3" style="background-color:#4456f1;color:#fff" onclick="addOF()">Tambah Detail (OF)</button>
  </div>
</div>

@push('scripts')
<script>
    // PILIH WARCO (load lokasi)
    $('#warco').on('change', function() {
        const warco = $(this).val();
        $('select[id^="locco-of-"]').each(function() {
            const $sel = $(this);
            $sel.prop('disabled', true).html('<option>Memuat lokasi...</option>');

            $.get(`/get-locco/${warco}`, function(data) {
            $sel.empty().append('<option value="" disabled selected>Pilih Lokasi</option>');
            data.forEach(item => {
                $sel.append(`<option value="${item.locco}">${item.locco}</option>`);
            });
            $sel.prop('disabled', false);
            // default pilih locco pertama
            if (data.length > 0) {
                $sel.val(data[0].locco);
                $sel.trigger('change');
            }
            }).fail(() => {
            Swal.fire({
                icon: 'error',
                title: 'Gagal Mengambil Lokasi',
                text: 'Terjadi kesalahan saat mengambil data lokasi gudang.'
            });
            $sel.html('<option value="" disabled selected>Gagal ambil lokasi</option>').prop('disabled', false);
            });
        });
    });

    // Ketika locco (Warehouse Location) berubah -> load barang
    $(document).on('change', 'select[id^="locco-of-"]', function () {
        const idx = this.id.split('-').pop(); // ambil index accordion
        const braco = $('#braco').val();
        const warco = $('#warco').val();
        const locco = $(this).val();

        if (!braco || !warco || !locco) return;

        const $barangSelect = $(`#opron-of-${idx}`);
        $barangSelect.prop('disabled', true).html('<option>Memuat barang...</option>');

        // GET: /get-barang/{braco}/{warco}/{locco}
        $.get(`/get-barang/${braco}/${warco}/${locco}`, function (data) {
            $barangSelect.empty().append('<option value="" disabled selected>Pilih Barang</option>');
            data.forEach(item => {
                $barangSelect.append(`
                    <option value="${item.opron}" 
                            data-qty="${item.qty}" 
                            data-stdqt="${item.stdqt}">
                        ${item.opron} - ${item.prona}
                    </option>
                `);
            });
        })
        .fail(() => {
            Swal.fire({
                icon: 'error',
                title: 'Gagal Ambil Data Barang',
                text: 'Terjadi kesalahan saat memuat data barang untuk lokasi ini.'
            });
            $barangSelect.html('<option value="" disabled selected>Gagal ambil barang</option>');
        })
        .always(() => {
            $barangSelect.prop('disabled', false);
        });
    });


    // PILIH BARANG
    $(document).on('change', '.opron-of', function() {
        const $opt = $(this).find(':selected');
        const idx = this.id.split('-').pop();
        const braco = $('#braco').val();
        const warco = $('#warco').val();
        const locco = $(`#locco-of-${idx}`).val();
        const opron = $(this).val();

        if (!warco) {
            Swal.fire({
            icon: 'warning',
            title: 'Pilih Gudang Dulu',
            text: 'Silakan pilih gudang (warco) sebelum memilih barang.'
            });
            $(this).val('').trigger('change');
            return;
        }

        if (!locco) {
            Swal.fire({
            icon: 'warning',
            title: 'Pilih Lokasi Dulu',
            text: 'Silakan pilih lokasi (locco) sebelum memilih barang.'
            });
            $(this).val('').trigger('change');
            return;
        }

        const qty = $opt.data('qty') || 0;
        const stdqt = $opt.data('stdqt') || '-';

        $(`#inqty-of-${idx}`).val(qty);
        $(`#trqty-of-${idx}`).next('.input-group-text').text(stdqt);

        // ambil data lot
        const $lotSelect = $(`#lotno-of-${idx}`);
        const $toqohInput = $(`#toqoh-of-${idx}`);
        const $toqohUnit = $(`.unit-label-of`);

        $lotSelect.prop('disabled', true).html('<option>Memuat Stok Barang...</option>');
        $.get(`/get-stobl/${braco}/${warco}/${opron}`, function(data) {
            $lotSelect.empty();

            if (data.length > 0) {
            $lotSelect.append('<option value="" disabled selected>Pilih SN / Batch No</option>');
            data.forEach(item => {
                $lotSelect.append(`
                <option value="${item.lotno}" data-toqoh="${item.toqoh}" data-stdqt="${item.qunit}">
                    ${item.lotno} (Stok: ${item.toqoh})
                </option>
                `);
            });
            } else {
            $lotSelect.append('<option value="" disabled selected>Tidak ada stok untuk barang ini</option>');
            Swal.fire({
                icon: 'warning',
                title: 'Stok Kosong',
                text: 'Tidak ada stok barang ini di gudang yang dipilih.'
            });
            }
        }).fail(() => {
            Swal.fire({
            icon: 'error',
            title: 'Gagal Mengambil Data SN atau Batch No',
            text: 'Terjadi kesalahan saat mengambil data stok.'
            });
            $lotSelect.html('<option>Gagal ambil data</option>');
        }).always(() => {
            $lotSelect.prop('disabled', false);
            $toqohInput.val('-');
            $toqohUnit.text('-');
        });
    });

    // PILIH LOT
    $(document).on('change', '.lotno-select', function() {
        const idx = this.id.split('-').pop();
        const $opt = $(this).find(':selected');
        const toqoh = $opt.data('toqoh') || 0;
        const stdqt = $opt.data('stdqt') || '-';

        $(`#stdqt-of-${idx}`).val(stdqt);
        $(`#toqoh-of-${idx}`).val(toqoh);
        $(`.unit-label-of`).text(stdqt);
    });

    // VALIDASI INPUT QTY
    $(document).on('input', '.trqty-of', function() {
        const idx = this.id.split('-').pop();
        const qty = parseFloat($(this).val()) || 0;
        const max = parseFloat($(`#toqoh-of-${idx}`).val()) || 0;

        if (qty > max) {
            Swal.fire({
            icon: 'error',
            title: 'Qty Melebihi Stok',
            text: `Jumlah input (${qty}) melebihi stok tersedia (${max}).`
            });
            $(this).val(max);
        }
    });


    // add/remove row OF
    window.addOF = function(){
        const i = $('#accordionOF .accordion-item').length;
        const dtl = `
        <div class="accordion-item" id="accordion-of-item-${i}">
            <h2 class="accordion-header d-flex justify-content-between align-items-center" id="heading-of-${i}">
            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#details-of-${i}" aria-expanded="false" aria-controls="details-of-${i}"><span class="accordion-title"></span></button>
            <button type="button" class="btn btn-sm btn-danger mx-2" onclick="removeOF(${i})"><i class="bi bi-trash-fill"></i></button>
            </h2>
            <div id="details-of-${i}" class="accordion-collapse collapse" aria-labelledby="heading-of-${i}" data-bs-parent="#accordionOF">
            <div class="accordion-body">
                <div class="row">

                <div class="col-md-6 mt-3">
                    <label class="form-label">Warehouse Location</label><span class="text-danger"> *</span>
                    <select class="form-control select2" name="locco[]" id="locco-of-${i}" required>
                    <option value="" disabled selected>Pilih Warehouse terlebih dahulu</option>
                    </select>
                </div>

                <div class="col-md-6 mt-3">
                    <label class="form-label">Barang</label><span class="text-danger"> *</span>
                    <select class="select2 form-control opron-of" name="opron[]" id="opron-of-${i}" required>
                    <option value="" disabled selected>Pilih Warehouse Location Terlebih Dahulu</option>
                    </select>
                </div>
                
                <div class="col-md-6 mt-3 lot-section">
                    <label for="lotno-of-${i}" class="form-label">Serial / Batch No.</label><span class="text-danger"> *</span>
                    <select class="form-select select2 lotno-select" name="lotno[]" id="lotno-of-${i}" required>
                        <option value="" disabled selected>Pilih SN / Batch No</option>
                    </select>
                </div>

                <div class="col-md-6 mt-3">
                    <label for="toqoh-of-${i}" class="form-label">Sisa Stok</label>
                    <div class="input-group">
                        <input type="text" class="form-control text-end" id="toqoh-of-${i}" placeholder="-" disabled>
                        <span class="input-group-text unit-label-of" id="toqoh-unit-of-${i}">-</span>
                    </div>
                </div>
                
                <input type="text" class="stdqt-of" name="stdqt[]" id="stdqt-of-${i}" hidden>

                <div class="col-md-6 mt-3">
                    <label class="form-label">Receipt Quantity</label><span class="text-danger"> *</span>
                    <div class="input-group">
                    <input type="number" class="form-control trqty-of" id="trqty-of-${i}" name="trqty[]" value="1" min="1" required
                    oninput="this.value = this.value.replace(/[^0-9]/g, '');">
                    <span class="input-group-text unit-label-of"></span>
                    </div>
                </div>

                <div class="col-md-12 mt-3">
                    <label class="form-label">Notes</label>
                    <textarea class="form-control" name="noted[]" id="noted-of-${i}" maxlength="200"></textarea>
                    <div class="form-text text-danger text-end" style="font-size:0.7rem;">Maksimal 200 karakter</div>
                </div>

                </div>
            </div>
            </div>
        </div>`;
        $('#accordionOF').append(dtl);
        $('.select2').select2({ width:'100%', theme: 'bootstrap-5' });
        setTimeout(()=>{
            $(`#details-of-${i}`).collapse('show');
        },100);

        // kalau WARCO sudah dipilih -> load warehouse ke row baru IB juga
        const warco = $('#warco').val();
        if(warco){
        const $sel = $(`#locco-of-${i}`);
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

    window.removeOF = function(i){
        $(`#accordion-of-item-${i}`).remove();
    }
</script>
@endpush

