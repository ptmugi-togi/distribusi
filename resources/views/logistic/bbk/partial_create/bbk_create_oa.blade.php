{{-- OA (DIPINJAM / DEMO) --}}
<div class="row mt-4">
    <div class="col-md-6 mt-3">
        <label for="trano" class="form-label">Stock Issue Note No.</label><span class="text-danger"> *</span>
        <input type="text" class="form-control" name="trano" id="trano" value="{{ old('trano', $trano ?? '') }}" required readonly style="background-color:#e9ecef">
    </div>

    <div class="col-md-6 mt-3">
        <label for="tradt" class="form-label">Stock Issue Date</label><span class="text-danger"> *</span>
        <input type="date" class="form-control" name="tradt" id="tradt" value="{{ old('tradt') }}" required min="{{ $minDate }}">
    </div>

    <div class="col-md-6 mt-3">
        <label for="isutn" class="form-label">Borrowed By</label><span class="text-danger"> *</span>
        <input type="text" class="form-control" name="isutn" id="isutn" value="{{ old('isutn') }}" required>
    </div>

    <div class="col-md-12 mt-3">
        <label for="noteh_oa" class="form-label">Notes</label>
        <textarea class="form-control" name="noteh" id="noteh_oa" maxlength="200">{{ old('noteh') }}</textarea>
        <div class="form-text text-danger text-end" style="font-size:0.7rem;">Maksimal 200 karakter</div>
    </div>
</div>

<div class="row">
  <h4 class="my-2">BBK Detail (OA)</h4>
  <div class="accordion" id="accordionOA">
    @foreach (old('opron', [null]) as $i => $oldOpron)
      <div class="accordion-item" id="accordion-oa-item-{{ $i }}">
        <h2 class="accordion-header d-flex justify-content-between align-items-center" id="heading-oa-{{ $i }}">
          <button class="accordion-button {{ $i > 0 ? 'collapsed' : '' }}" type="button"
            data-bs-toggle="collapse" data-bs-target="#details-oa-{{ $i }}"
            aria-expanded="{{ $i == 0 ? 'true' : 'false' }}" aria-controls="details-oa-{{ $i }}">
            <span class="accordion-title"></span>
          </button>
          @if($i > 0)
            <button type="button" class="btn btn-sm btn-danger mx-2" onclick="removeOA({{ $i }})">
              <i class="bi bi-trash-fill"></i>
            </button>
          @endif
        </h2>

        <div id="details-oa-{{ $i }}" class="accordion-collapse collapse {{ $i == 0 ? 'show' : '' }}"
          aria-labelledby="heading-oa-{{ $i }}" data-bs-parent="#accordionOA">
          <div class="accordion-body">
            <div class="row">
                <div class="col-md-6 mt-3">
                    <label class="form-label">Barang</label><span class="text-danger"> *</span>
                    <select class="select2 form-control opron-oa" name="opron[]" id="opron-oa-{{ $i }}" required>
                    <option value="" disabled {{ old('opron.'.$i) ? '' : 'selected' }}>Pilih Barang</option>
                    </select>
                </div>

                <div class="col-md-6 mt-3 lot-section">
                    <label for="lotno-oa-{{ $i }}" class="form-label">Serial / Batch No.</label><span class="text-danger"> *</span>
                    <select class="form-select select2 lotno-select" name="lotno[]" id="lotno-oa-{{ $i }}" required>
                        <option value="" disabled selected>Pilih Barang Terlebih Dahulu</option>
                    </select>
                </div>

                <div class="col-md-6 mt-3">
                    <label class="form-label">Warehouse Location</label><span class="text-danger"> *</span>
                    <input type="text" class="form-control" name="locco[]" id="locco-oa-{{ $i }}" value="{{ old('locco.'.$i) }}" required readonly style="background-color:#e9ecef">
                </div>
                                    
                <div class="col-md-6 mt-3">
                    <label for="toqoh-oa-{{ $i }}" class="form-label">Stok</label>
                    <div class="input-group">
                        <input type="text" class="form-control text-end" id="toqoh-oa-{{ $i }}" placeholder="-" disabled>
                        <span class="input-group-text unit-label-oa" id="toqoh-unit-oa-{{ $i }}">-</span>
                    </div>
                </div>

                <div class="col-md-6 mt-3">
                    <label for="trqty-oa-{{ $i }}" class="form-label">Borrowed Quantity</label><span class="text-danger"> *</span>
                    <div class="input-group">
                        <input type="number" class="form-control trqty-oa" id="trqty-oa-{{ $i }}" name="trqty[]" value="{{ old('trqty.'.$i, 1) }}" min="1" required
                        oninput="this.value = this.value.replace(/[^0-9]/g, '');">
                        <span class="input-group-text unit-label-oa"></span>
                    </div>
                </div>
                
                <input type="text" class="stdqt-oa" name="stdqt[]" id="stdqt-oa-{{ $i }}" hidden>

                <div class="col-md-12 mt-3">
                    <label class="form-label">Notes</label>
                    <textarea type="text" class="form-control" name="noted[]" id="noted-oa-{{ $i }}" maxlength="200">{{ old('noted.'.$i) }}</textarea>
                    <div class="form-text text-danger text-end" style="font-size:0.7rem;">Maksimal 200 karakter</div>
                </div>
            </div>
          </div>
        </div>
      </div>
    @endforeach
  </div>

  <div class="text-end">
    <button type="button" class="btn mt-3" style="background-color:#4456f1;color:#fff" onclick="addOA()">Tambah Detail (OA)</button>
  </div>
</div>

@push('scripts')
<script>
    // PILIH BARANG
    $(document).on('change', '.opron-oa', function() {
        const $opt = $(this).find(':selected');
        const idx = this.id.split('-').pop();
        const braco = $('#braco').val();
        const warco = $('#warco').val();
        const opron = $(this).val();
        
        const locco = $opt.data('locco') || '';
        const qty = $opt.data('qty') || 0;
        const stdqt = $opt.data('stdqt') || '-';

        $(`#locco-oa-${idx}`).val(locco);
        $(`#inqty-oa-${idx}`).val(qty);
        $(`#trqty-oa-${idx}`).next('.input-group-text').text(stdqt);

        // ambil data lot
        const $lotSelect = $(`#lotno-oa-${idx}`);
        const $toqohInput = $(`#toqoh-oa-${idx}`);
        const $toqohUnit = $(`.unit-label-oa`);

        $lotSelect.prop('disabled', true).html('<option>Memuat Stok Barang...</option>');
        $.get(`/get-stobl/${braco}/${warco}/${opron}`, function(data) {
            $lotSelect.empty();

            if (data.length > 0) {
            $lotSelect.append('<option value="" disabled selected>Pilih SN / Batch No</option>');
            data.forEach(item => {
                $lotSelect.append(`
                <option value="${item.lotno}" data-toqoh="${item.toqoh}" data-stdqt="${item.qunit}" data-locco="${item.locco}">
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
            $(`#toqoh-oa-${idx}`).val('-');
            $(`#toqoh-unit-oa-${idx}`).text('-');
            $(`#trqty-oa-${idx}`).next('.unit-label-oa').text('-');
        });
    });

    // PILIH LOT
    $(document).on('change', '.lotno-select', function() {
        const idx = this.id.split('-').pop();
        const $opt = $(this).find(':selected');
        const toqoh = $opt.data('toqoh') || 0;
        const stdqt = $opt.data('stdqt') || '-';
        const locco = $opt.data('locco') || '';

        $(`#stdqt-oa-${idx}`).val(stdqt);
        $(`#toqoh-oa-${idx}`).val(toqoh);
        $(`#locco-oa-${idx}`).val(locco);
        $(`#toqoh-unit-oa-${idx}`).text(stdqt);
        $(`#trqty-oa-${idx}`).next('.unit-label-oa').text(stdqt);
    });

    // VALIDASI INPUT QTY
    $(document).on('input', '.trqty-oa', function() {
        const idx = this.id.split('-').pop();
        const qty = parseFloat($(this).val()) || 0;
        const max = parseFloat($(`#toqoh-oa-${idx}`).val()) || 0;

        if (qty > max) {
            Swal.fire({
            icon: 'error',
            title: 'Qty Melebihi Stok',
            text: `Jumlah input (${qty}) melebihi stok tersedia (${max}).`
            });
            $(this).val(max);
        }
    });


    // add/remove row OA
    window.addOA = function(){
        const i = $('#accordionOA .accordion-item').length;
        const dtl = `
        <div class="accordion-item" id="accordion-oa-item-${i}">
            <h2 class="accordion-header d-flex justify-content-between align-items-center" id="heading-oa-${i}">
            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#details-oa-${i}" aria-expanded="false" aria-controls="details-oa-${i}"><span class="accordion-title"></span></button>
            <button type="button" class="btn btn-sm btn-danger mx-2" onclick="removeOA(${i})"><i class="bi bi-trash-fill"></i></button>
            </h2>
            <div id="details-oa-${i}" class="accordion-collapse collapse" aria-labelledby="heading-oa-${i}" data-bs-parent="#accordionOA">
            <div class="accordion-body">
                <div class="row">
                    <div class="col-md-6 mt-3">
                        <label class="form-label">Barang</label><span class="text-danger"> *</span>
                        <select class="select2 form-control opron-oa" name="opron[]" id="opron-oa-${i}" required>
                        <option value="" disabled selected>Pilih Warehouse Location Terlebih Dahulu</option>
                        </select>
                    </div>
                    
                    <div class="col-md-6 mt-3 lot-section">
                        <label for="lotno-oa-${i}" class="form-label">Serial / Batch No.</label><span class="text-danger"> *</span>
                        <select class="form-select select2 lotno-select" name="lotno[]" id="lotno-oa-${i}" required>
                            <option value="" disabled selected>Pilih SN / Batch No</option>
                        </select>
                    </div>

                    <div class="col-md-6 mt-3">
                        <label class="form-label">Warehouse Location</label><span class="text-danger"> *</span>
                        <input type="text" class="form-control" name="locco[]" id="locco-oa-${i}" value="" required readonly style="background-color:#e9ecef">
                    </div>

                    <div class="col-md-6 mt-3">
                        <label for="toqoh-oa-${i}" class="form-label">Sisa Stok</label>
                        <div class="input-group">
                            <input type="text" class="form-control text-end" id="toqoh-oa-${i}" placeholder="-" disabled>
                            <span class="input-group-text unit-label-oa" id="toqoh-unit-oa-${i}">-</span>
                        </div>
                    </div>
                    
                    <input type="text" class="stdqt-oa" name="stdqt[]" id="stdqt-oa-${i}" hidden>

                    <div class="col-md-6 mt-3">
                        <label class="form-label">Borrowed Quantity</label><span class="text-danger"> *</span>
                        <div class="input-group">
                        <input type="number" class="form-control trqty-oa" id="trqty-oa-${i}" name="trqty[]" value="1" min="1" required
                        oninput="this.value = this.value.replace(/[^0-9]/g, '');">
                        <span class="input-group-text unit-label-oa"></span>
                        </div>
                    </div>

                    <div class="col-md-12 mt-3">
                        <label class="form-label">Notes</label>
                        <textarea class="form-control" name="noted[]" id="noted-oa-${i}" maxlength="200"></textarea>
                        <div class="form-text text-danger text-end" style="font-size:0.7rem;">Maksimal 200 karakter</div>
                    </div>

                </div>
            </div>
            </div>
        </div>`;
        $('#accordionOA').append(dtl);
        $('.select2').select2({ width:'100%', theme: 'bootstrap-5' });
        setTimeout(()=>{
            $(`#details-oa-${i}`).collapse('show');
        },100);

        loadMasterProductAll();
    }

    window.removeOA = function(i){
        $(`#accordion-oa-item-${i}`).remove();
    }
</script>
@endpush