{{-- OE (ISSUE WARRANTY CLAIM) --}}
<div class="row mt-4">
    <div class="col-md-6 mt-3">
        <label for="tradt" class="form-label">Stock Issue Date</label><span class="text-danger"> *</span>
        <input type="date" class="form-control" name="tradt" id="tradt" value="{{ old('tradt') }}" required min="{{ $minDate }}">
    </div>

    <div class="col-md-6 mt-3">
        <label for="supno" class="form-label">Supplier <span class="text-danger">*</span></label>
        <select class="select2 form-control" name="supno" id="supno" required>
            <option value="" disabled {{ old('supno') ? '' : 'selected' }}>Silahkan pilih Supplier</option>
            @foreach($vendors as $v)
                <option
                    value="{{ $v->supno }}"
                    {{ old('supno') == $v->supno ? 'selected' : '' }}>
                    {{ $v->supno }} - {{ $v->supna }}
                </option>
            @endforeach
        </select>
    </div>

    <div class="col-md-6 mt-3">
        <label for="ref01" class="form-label">Warranty Claim#</label><span class="text-danger"> *</span>
        <input type="text" class="form-control" name="ref01" id="ref01" value="{{ old('ref01') }}" required>
    </div>

    <div class="col-md-6 mt-3">
        <label for="isutn" class="form-label">Issue to name</label><span class="text-danger"> *</span>
        <input type="text" class="form-control" name="isutn" id="isutn" value="{{ old('isutn') }}" required>
    </div>

    <div class="col-md-6 mt-3">
        <label for="isua1" class="form-label">Issue to Address</label><span class="text-danger"> *</span>
        <input type="text" class="form-control" name="isua1" id="isua1" value="{{ old('isua1') }}" required>
    </div>

    <div class="col-md-12 mt-3">
        <label for="noteh_oe" class="form-label">Notes</label>
        <textarea class="form-control" name="noteh" id="noteh_oe" maxlength="200">{{ old('noteh') }}</textarea>
        <div class="form-text text-danger text-end" style="font-size:0.7rem;">Maksimal 200 karakter</div>
    </div>
</div>

<div class="row">
  <h4 class="my-2">BBK Detail (OE)</h4>
  <div class="accordion" id="accordionOE">
    @foreach (old('opron', [null]) as $i => $oldOpron)
      <div class="accordion-item" id="accordion-oe-item-{{ $i }}">
        <h2 class="accordion-header d-flex justify-content-between align-items-center" id="heading-oe-{{ $i }}">
          <button class="accordion-button {{ $i > 0 ? 'collapsed' : '' }}" type="button"
            data-bs-toggle="collapse" data-bs-target="#details-oe-{{ $i }}"
            aria-expanded="{{ $i == 0 ? 'true' : 'false' }}" aria-controls="details-oe-{{ $i }}">
            <span class="accordion-title"></span>
          </button>
          @if($i > 0)
            <button type="button" class="btn btn-sm btn-danger mx-2" onclick="removeOE({{ $i }})">
              <i class="bi bi-trash-fill"></i>
            </button>
          @endif
        </h2>

        <div id="details-oe-{{ $i }}" class="accordion-collapse collapse {{ $i == 0 ? 'show' : '' }}"
          aria-labelledby="heading-oe-{{ $i }}" data-bs-parent="#accordionOE">
          <div class="accordion-body">
            <div class="row">
                <div class="col-md-6 mt-3">
                    <label class="form-label">Warehouse Location</label><span class="text-danger"> *</span>
                    <select class="form-control select2" name="locco[]" id="locco-oe-{{ $i }}" required>
                    <option value="" disabled selected>Pilih Warehouse terlebih dahulu</option>
                    </select>
                </div>

                <div class="col-md-6 mt-3">
                    <label class="form-label">Barang</label><span class="text-danger"> *</span>
                    <select class="select2 form-control opron-oe" name="opron[]" id="opron-oe-{{ $i }}" required>
                    <option value="" disabled {{ old('opron.'.$i) ? '' : 'selected' }}>Pilih Warehouse Location Terlebih Dahulu</option>
                    </select>
                </div>

                <div class="col-md-6 mt-3 lot-section">
                    <label for="lotno-oe-{{ $i }}" class="form-label">Serial / Batch No.</label><span class="text-danger"> *</span>
                    <select class="form-select select2 lotno-select" name="lotno[]" id="lotno-oe-{{ $i }}" required>
                        <option value="" disabled selected>Pilih Barang Terlebih Dahulu</option>
                    </select>
                </div>
                                    
                <div class="col-md-6 mt-3">
                    <label for="toqoh-oe-{{ $i }}" class="form-label">Sisa Stok</label>
                    <div class="input-group">
                        <input type="text" class="form-control text-end" id="toqoh-oe-{{ $i }}" placeholder="-" disabled>
                        <span class="input-group-text unit-label-oe" id="toqoh-unit-oe-{{ $i }}">-</span>
                    </div>
                </div>

                <div class="col-md-6 mt-3">
                    <label for="trqty-oe-{{ $i }}" class="form-label">Issue Quantity</label><span class="text-danger"> *</span>
                    <div class="input-group">
                        <input type="number" class="form-control trqty-oe" id="trqty-oe-{{ $i }}" name="trqty[]" value="{{ old('trqty.'.$i, 1) }}" min="1" required
                        oninput="this.value = this.value.replace(/[^0-9]/g, '');">
                        <span class="input-group-text unit-label-oe"></span>
                    </div>
                </div>
                
                <input type="text" class="stdqt-oe" name="stdqt[]" id="stdqt-oe-{{ $i }}" hidden>

                <div class="col-md-12 mt-3">
                    <label class="form-label">Notes</label>
                    <textarea type="text" class="form-control" name="noted[]" id="noted-oe-{{ $i }}" maxlength="200">{{ old('noted.'.$i) }}</textarea>
                    <div class="form-text text-danger text-end" style="font-size:0.7rem;">Maksimal 200 karakter</div>
                </div>
            </div>
          </div>
        </div>
      </div>
    @endforeach
  </div>

  <div class="text-end">
    <button type="button" class="btn mt-3" style="background-color:#4456f1;color:#fff" onclick="addOE()">Tambah Detail (OE)</button>
  </div>
</div>

@push('scripts')
<script>
    // PILIH WARCO (load lokasi)
    $('#warco').on('change', function() {
        const warco = $(this).val();
        $('select[id^="locco-oe-"]').each(function() {
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
    $(document).on('change', 'select[id^="locco-oe-"]', function () {
        const idx = this.id.split('-').pop(); // ambil index accordion
        const braco = $('#braco').val();
        const warco = $('#warco').val();
        const locco = $(this).val();

        if (!braco || !warco || !locco) return;

        const $barangSelect = $(`#opron-oe-${idx}`);
        $barangSelect.prop('disabled', true).html('<option>Memuat barang...</option>');

        $.get(`/get-barang/${braco}/${warco}/${locco}`, function (data) {
            $barangSelect.empty();
            if (data.length === 0) {
                $barangSelect.append('<option value="" disabled selected>Tidak ada barang di lokasi ini</option>');
            } else {
                $barangSelect.empty().append('<option value="" disabled selected>Pilih Barang</option>');
            }
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
    $(document).on('change', '.opron-oe', function() {
        const $opt = $(this).find(':selected');
        const idx = this.id.split('-').pop();
        const braco = $('#braco').val();
        const warco = $('#warco').val();
        const locco = $(`#locco-oe-${idx}`).val();
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

        $(`#inqty-oe-${idx}`).val(qty);
        $(`#trqty-oe-${idx}`).next('.input-group-text').text(stdqt);

        // ambil data lot
        const $lotSelect = $(`#lotno-oe-${idx}`);
        const $toqohInput = $(`#toqoh-oe-${idx}`);
        const $toqohUnit = $(`.unit-label-oe`);

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

        $(`#stdqt-oe-${idx}`).val(stdqt);
        $(`#toqoh-oe-${idx}`).val(toqoh);
        $(`.unit-label-oe`).text(stdqt);
    });

    // VALIDASI INPUT QTY
    $(document).on('input', '.trqty-oe', function() {
        const idx = this.id.split('-').pop();
        const qty = parseFloat($(this).val()) || 0;
        const max = parseFloat($(`#toqoh-oe-${idx}`).val()) || 0;

        if (qty > max) {
            Swal.fire({
            icon: 'error',
            title: 'Qty Melebihi Stok',
            text: `Jumlah input (${qty}) melebihi stok tersedia (${max}).`
            });
            $(this).val(max);
        }
    });


    // add/remove row OE
    window.addOE = function(){
        const i = $('#accordionOE .accordion-item').length;
        const dtl = `
        <div class="accordion-item" id="accordion-oe-item-${i}">
            <h2 class="accordion-header d-flex justify-content-between align-items-center" id="heading-oe-${i}">
            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#details-oe-${i}" aria-expanded="false" aria-controls="details-oe-${i}"><span class="accordion-title"></span></button>
            <button type="button" class="btn btn-sm btn-danger mx-2" onclick="removeOE(${i})"><i class="bi bi-trash-fill"></i></button>
            </h2>
            <div id="details-oe-${i}" class="accordion-collapse collapse" aria-labelledby="heading-oe-${i}" data-bs-parent="#accordionOE">
            <div class="accordion-body">
                <div class="row">

                <div class="col-md-6 mt-3">
                    <label class="form-label">Warehouse Location</label><span class="text-danger"> *</span>
                    <select class="form-control select2" name="locco[]" id="locco-oe-${i}" required>
                    <option value="" disabled selected>Pilih Warehouse terlebih dahulu</option>
                    </select>
                </div>

                <div class="col-md-6 mt-3">
                    <label class="form-label">Barang</label><span class="text-danger"> *</span>
                    <select class="select2 form-control opron-oe" name="opron[]" id="opron-oe-${i}" required>
                    <option value="" disabled selected>Pilih Warehouse Location Terlebih Dahulu</option>
                    </select>
                </div>
                
                <div class="col-md-6 mt-3 lot-section">
                    <label for="lotno-oe-${i}" class="form-label">Serial / Batch No.</label><span class="text-danger"> *</span>
                    <select class="form-select select2 lotno-select" name="lotno[]" id="lotno-oe-${i}" required>
                        <option value="" disabled selected>Pilih SN / Batch No</option>
                    </select>
                </div>

                <div class="col-md-6 mt-3">
                    <label for="toqoh-oe-${i}" class="form-label">Sisa Stok</label>
                    <div class="input-group">
                        <input type="text" class="form-control text-end" id="toqoh-oe-${i}" placeholder="-" disabled>
                        <span class="input-group-text unit-label-oe" id="toqoh-unit-oe-${i}">-</span>
                    </div>
                </div>
                
                <input type="text" class="stdqt-oe" name="stdqt[]" id="stdqt-oe-${i}" hidden>

                <div class="col-md-6 mt-3">
                    <label class="form-label">Issue Quantity</label><span class="text-danger"> *</span>
                    <div class="input-group">
                    <input type="number" class="form-control trqty-oe" id="trqty-oe-${i}" name="trqty[]" value="1" min="1" required
                    oninput="this.value = this.value.replace(/[^0-9]/g, '');">
                    <span class="input-group-text unit-label-oe"></span>
                    </div>
                </div>

                <div class="col-md-12 mt-3">
                    <label class="form-label">Notes</label>
                    <textarea class="form-control" name="noted[]" id="noted-oe-${i}" maxlength="200"></textarea>
                    <div class="form-text text-danger text-end" style="font-size:0.7rem;">Maksimal 200 karakter</div>
                </div>

                </div>
            </div>
            </div>
        </div>`;
        $('#accordionOE').append(dtl);
        $('.select2').select2({ width:'100%', theme: 'bootstrap-5' });
        setTimeout(()=>{
            $(`#details-oe-${i}`).collapse('show');
        },100);

        // kalau WARCO sudah dipilih -> load warehouse ke row baru IB juga
        const warco = $('#warco').val();
        if(warco){
        const $sel = $(`#locco-oe-${i}`);
        $sel.empty().append('<option value="">Loading...</option>');
        $.get(`{{ url('/get-locco') }}/${warco}`, function(data){
            $sel.empty().append('<option disabled selected>Pilih Lokasi</option>');
            data.forEach(item => $sel.append(`<option value="${item.locco}">${item.locco}</option>`));
            $sel.trigger('change.select2');
        });
        }
    }

    window.removeOE = function(i){
        $(`#accordion-oe-item-${i}`).remove();
    }
</script>
@endpush

