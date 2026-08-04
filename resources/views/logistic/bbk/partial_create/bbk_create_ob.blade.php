{{-- OB (LOCAL PURCHASE) --}}
<div class="row mt-4">
    <div class="col-md-6 mt-3">
        <label for="tradt" class="form-label">Stock Issue Date</label><span class="text-danger"> *</span>
        <input type="date" class="form-control" name="tradt" id="tradt" value="{{ old('tradt') }}" required min="{{ $minDate }}">
    </div>

    <div class="col-md-6 mt-3">
        <label for="refcno" class="form-label">Reference</label>
        <div class="input-group">
            <span class="input-group-text" id="basic-addon1">WO</span>
            <input type="text" class="form-control" name="rfc01" id="rfc01" value="WO" hidden>
            <input type="text" class="form-control" name="ref01" id="ref01" placeholder="25XXXX" value="{{ old('ref01') }}" maxlength="6">
        </div>
    </div>

    <div class="col-md-6 mt-3">
        <label for="kdprod" class="form-label">Kode Prod</label><span class="text-danger"> *</span>
        <input type="text" class="form-control" name="kdprod" id="kdprod" value="{{ old('kdprod') }}" required>
    </div>

    <div class="col-md-6 mt-3">
        <label for="isutn" class="form-label">Issue to Name</label><span class="text-danger"> *</span>
        <input type="text" class="form-control" name="isutn" id="isutn" value="{{ old('isutn') }}" required>
    </div>

    <div class="col-md-12 mt-3">
        <label for="noteh_ob" class="form-label">Notes</label>
        <textarea class="form-control" name="noteh" id="noteh_ob" maxlength="200">{{ old('noteh') }}</textarea>
        <div class="form-text text-danger text-end" style="font-size:0.7rem;">Maksimal 200 karakter</div>
    </div>
</div>

<div class="row">
  <h4 class="my-2">BBK Detail (OB)</h4>
  <div class="accordion" id="accordionOB">
    @foreach (old('opron', [null]) as $i => $oldOpron)
      <div class="accordion-item" id="accordion-ob-item-{{ $i }}">
        <h2 class="accordion-header d-flex justify-content-between align-items-center" id="heading-ob-{{ $i }}">
          <button class="accordion-button {{ $i > 0 ? 'collapsed' : '' }}" type="button"
            data-bs-toggle="collapse" data-bs-target="#details-ob-{{ $i }}"
            aria-expanded="{{ $i == 0 ? 'true' : 'false' }}" aria-controls="details-ob-{{ $i }}">
            <span class="accordion-title"></span>
          </button>
          @if($i > 0)
            <button type="button" class="btn btn-sm btn-danger mx-2" onclick="removeOB({{ $i }})">
              <i class="bi bi-trash-fill"></i>
            </button>
          @endif
        </h2>

        <div id="details-ob-{{ $i }}" class="accordion-collapse collapse {{ $i == 0 ? 'show' : '' }}"
          aria-labelledby="heading-ob-{{ $i }}" data-bs-parent="#accordionOB">
          <div class="accordion-body">
            <div class="row">
                <div class="col-md-6 mt-3">
                    <label class="form-label">Barang</label><span class="text-danger"> *</span>
                    <select class="select2 form-control opron-ob" name="opron[]" id="opron-ob-{{ $i }}" required>
                    <option value="" disabled {{ old('opron.'.$i) ? '' : 'selected' }}>Pilih Warehouse Location Terlebih Dahulu</option>
                    </select>
                </div>
                                    
                <div class="col-md-6 mt-3">
                    <label for="toqoh-ob-{{ $i }}" class="form-label">Stock on Hand</label>
                    <div class="input-group">
                        <input type="text" class="form-control text-end" id="toqoh-ob-{{ $i }}" placeholder="-" disabled>
                        <span class="input-group-text unit-label-ob" id="toqoh-unit-ob-{{ $i }}">-</span>
                    </div>
                </div>

                <div class="col-md-6 mt-3">
                    <label for="trqty-ob-{{ $i }}" class="form-label">Issue Quantity</label><span class="text-danger"> *</span>
                    <div class="input-group">
                        <input type="number" class="form-control trqty-ob" id="trqty-ob-{{ $i }}" name="trqty[]" value="{{ old('trqty.'.$i) }}" min="1" required
                        oninput="this.value = this.value.replace(/[^0-9]/g, '');">
                        <span class="input-group-text unit-label-ob"></span>
                    </div>
                </div>

                <input type="text" class="nolot-ob" name="nolot[]" id="nolot-ob-{{ $i }}" value="1" hidden>

                <input type="text" class="lotno-ob" name="lotno[]" id="lotno-ob-{{ $i }}" value="-" hidden>
                
                <input type="text" class="stdqt-ob" name="stdqt[]" id="stdqt-ob-{{ $i }}" hidden>
                
                <input type="text" class="locco-ob" name="locco[]" id="locco-ob-{{ $i }}" value="000001" hidden>

                <div class="col-md-12 mt-3">
                    <label class="form-label">Notes</label>
                    <textarea type="text" class="form-control" name="noted[]" id="noted-ob-{{ $i }}" maxlength="200">{{ old('noted.'.$i) }}</textarea>
                    <div class="form-text text-danger text-end" style="font-size:0.7rem;">Maksimal 200 karakter</div>
                </div>
            </div>
          </div>
        </div>
      </div>
    @endforeach
  </div>

  <div class="text-end">
    <button type="button" class="btn mt-3" style="background-color:#4456f1;color:#fff" onclick="addOB()">Tambah Detail (OB)</button>
  </div>
</div>

@push('scripts')
<script>
    // PILIH BARANG
    $(document).on('change', '.opron-ob', function () {
        const $opt = $(this).find(':selected');
        const idx   = this.id.split('-').pop();
        
        const braco = $('#braco').val();
        const warco = $('#warco').val();
        const opron = $(this).val();

        const qty   = $opt.data('qty') || 0;
        const stdqt = $opt.data('stdqt') || '-';

        $(`#inqty-ob-${idx}`).val(qty);
        $(`#trqty-ob-${idx}`).next('.input-group-text').text(stdqt);
        $(`#stdqt-ob-${idx}`).val(stdqt);

        const $toqohInput = $(`#toqoh-ob-${idx}`);
        const $toqohUnit  = $(`#toqoh-unit-ob-${idx}`);

        $toqohInput.val('...');
        $toqohUnit.text('-');

        $.get(`/get-stobl-ob/${braco}/${warco}/${opron}`, function (data) {

            if (data && data.toqoh !== undefined) {
                $toqohInput.val(data.toqoh);
                $toqohUnit.text(data.qunit || '-');
            } else {
                $toqohInput.val(0);
                $toqohUnit.text('-');

                Swal.fire({
                    icon: 'warning',
                    title: 'Stok Kosong',
                    text: 'Stok barang tidak tersedia di gudang ini.'
                });
            }

        }).fail(() => {
            $toqohInput.val('-');
            $toqohUnit.text('-');

            Swal.fire({
                icon: 'error',
                title: 'Gagal Ambil Stok',
                text: 'Terjadi kesalahan saat mengambil stock on hand.'
            });
        });
    });

    // VALIDASI INPUT QTY
    $(document).on('input', '.trqty-ob', function() {
        const idx = this.id.split('-').pop();
        const qty = parseFloat($(this).val()) || 0;
        const max = parseFloat($(`#toqoh-ob-${idx}`).val()) || 0;

        if (qty > max) {
            Swal.fire({
            icon: 'error',
            title: 'Qty Melebihi Stok',
            text: `Jumlah input (${qty}) melebihi stok tersedia (${max}).`
            });
            $(this).val(max);
        }
    });


    // add/remove row OB
    window.addOB = function(){
        const i = $('#accordionOB .accordion-item').length;
        const dtl = `
        <div class="accordion-item" id="accordion-ob-item-${i}">
            <h2 class="accordion-header d-flex justify-content-between align-items-center" id="heading-ob-${i}">
            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#details-ob-${i}" aria-expanded="false" aria-controls="details-ob-${i}"><span class="accordion-title"></span></button>
            <button type="button" class="btn btn-sm btn-danger mx-2" onclick="removeOB(${i})"><i class="bi bi-trash-fill"></i></button>
            </h2>
            <div id="details-ob-${i}" class="accordion-collapse collapse" aria-labelledby="heading-ob-${i}" data-bs-parent="#accordionOB">
            <div class="accordion-body">
                <div class="row">
                <div class="col-md-6 mt-3">
                    <label class="form-label">Barang</label><span class="text-danger"> *</span>
                    <select class="select2 form-control opron-ob" name="opron[]" id="opron-ob-${i}" required>
                    <option value="" disabled selected>Pilih Warehouse Location Terlebih Dahulu</option>
                    </select>
                </div>

                <div class="col-md-6 mt-3">
                    <label for="toqoh-ob-${i}" class="form-label">Stock on Hand</label>
                    <div class="input-group">
                        <input type="text" class="form-control text-end" id="toqoh-ob-${i}" placeholder="-" disabled>
                        <span class="input-group-text unit-label-ob" id="toqoh-unit-ob-${i}">-</span>
                    </div>
                </div>
                
                <input type="text" class="nolot-ob" name="nolot[]" id="nolot-ob-${i}" value="1" hidden>
                
                <input type="text" class="lotno-ob" name="lotno[]" id="lotno-ob-${i}" value="-" hidden>

                <input type="text" class="stdqt-ob" name="stdqt[]" id="stdqt-ob-${i}" hidden>

                <input type="text" class="locco-ob" name="locco[]" id="locco-ob-${i}" value="000001" hidden>

                <div class="col-md-6 mt-3">
                    <label class="form-label">Issue Quantity</label><span class="text-danger"> *</span>
                    <div class="input-group">
                    <input type="number" class="form-control trqty-ob" id="trqty-ob-${i}" name="trqty[]" min="1" required
                    oninput="this.value = this.value.replace(/[^0-9]/g, '');">
                    <span class="input-group-text unit-label-ob"></span>
                    </div>
                </div>

                <div class="col-md-12 mt-3">
                    <label class="form-label">Notes</label>
                    <textarea class="form-control" name="noted[]" id="noted-ob-${i}" maxlength="200"></textarea>
                    <div class="form-text text-danger text-end" style="font-size:0.7rem;">Maksimal 200 karakter</div>
                </div>

                </div>
            </div>
            </div>
        </div>`;
        $('#accordionOB').append(dtl);
        $('.select2').select2({ width:'100%', theme: 'bootstrap-5' });
        setTimeout(()=>{
            $(`#details-ob-${i}`).collapse('show');
        },100);

        loadMasterProductAll();
    }

    window.removeOB = function(i){
        $(`#accordion-ob-item-${i}`).remove();
    }
</script>
@endpush