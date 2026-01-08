<div class="row">
  <h4 class="my-2">WO Detail</h4>
  <div class="accordion" id="accordionRA">
    @foreach (old('opron', [null]) as $i => $oldOpron)
      <div class="accordion-item" id="accordion-ra-item-{{ $i }}">
        <h2 class="accordion-header d-flex justify-content-between align-items-center" id="heading-ra-{{ $i }}">
          <button class="accordion-button {{ $i > 0 ? 'collapsed' : '' }}" type="button"
            data-bs-toggle="collapse" data-bs-target="#details-ra-{{ $i }}"
            aria-expanded="{{ $i == 0 ? 'true' : 'false' }}" aria-controls="details-ra-{{ $i }}">
            <span class="accordion-title"></span>
          </button>
          @if($i > 0)
            <button type="button" class="btn btn-sm btn-danger mx-2" onclick="removeRA({{ $i }})">
              <i class="bi bi-trash-fill"></i>
            </button>
          @endif
        </h2>

        <div id="details-ra-{{ $i }}" class="accordion-collapse collapse {{ $i == 0 ? 'show' : '' }}"
          aria-labelledby="heading-ra-{{ $i }}" data-bs-parent="#accordionRA">
          <div class="accordion-body">
            <div class="row">
                <div class="col-md-6 mt-3">
                    <label class="form-label">Barang</label><span class="text-danger"> *</span>
                    <select class="select2 form-control opron-ra" name="opron[]" id="opron-ra-{{ $i }}" required>
                    <option value="" disabled {{ old('opron.'.$i) ? '' : 'selected' }}>Pilih Barang</option>
                    </select>
                </div>

                <div class="col-md-6 mt-3">
                    <label for="outqt-ra-{{ $i }}" class="form-label">Quantity</label><span class="text-danger"> *</span>
                    <div class="input-group">
                        <input type="number" class="form-control outqt-ra" id="outqt-ra-{{ $i }}" name="outqt[]" value="{{ old('outqt.'.$i) }}" min="1" required
                        oninput="this.value = this.value.replace(/[^0-9]/g, '');">
                        <span class="input-group-text unit-label-ra"></span>
                    </div>
                    <input type="text" class="stdqu-ra" name="stdqu[]" id="stdqu-ra-{{ $i }}" hidden>
                </div>

                <div class="col-md-12 mt-3">
                    <label class="form-label">Notes</label>
                    <textarea type="text" class="form-control" name="noted[]" id="noted-ra-{{ $i }}" maxlength="200">{{ old('noted.'.$i) }}</textarea>
                    <div class="form-text text-danger text-end" style="font-size:0.7rem;">Maksimal 200 karakter</div>
                </div>
            </div>
          </div>
        </div>
      </div>
    @endforeach
  </div>

  <div class="text-end">
    <button type="button" class="btn mt-3" style="background-color:#4456f1;color:#fff" onclick="addRA()">Tambah Detail </button>
  </div>
</div>

@push('scripts')
<script>
    // add/remove row RA
    window.addRA = function(){
        const i = $('#accordionRA .accordion-item').length;
        const dtl = `
        <div class="accordion-item" id="accordion-ra-item-${i}">
            <h2 class="accordion-header d-flex justify-content-between align-items-center" id="heading-ra-${i}">
            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#details-ra-${i}" aria-expanded="false" aria-controls="details-ra-${i}"><span class="accordion-title"></span></button>
            <button type="button" class="btn btn-sm btn-danger mx-2" onclick="removeRA(${i})"><i class="bi bi-trash-fill"></i></button>
            </h2>
            <div id="details-ra-${i}" class="accordion-collapse collapse" aria-labelledby="heading-ra-${i}" data-bs-parent="#accordionRA">
                <div class="accordion-body">
                    <div class="row">
                        <div class="col-md-6 mt-3">
                            <label class="form-label">Barang</label><span class="text-danger"> *</span>
                            <select class="select2 form-control opron-ra" name="opron[]" id="opron-ra-${i}" required>
                            <option value="" disabled selected>Pilih Barang</option>
                            </select>
                        </div>
                        
                        <div class="col-md-6 mt-3">
                            <label class="form-label">Quantity</label><span class="text-danger"> *</span>
                            <div class="input-group">
                            <input type="number" class="form-control outqt-ra" id="outqt-ra-${i}" name="outqt[]" min="1" required
                            oninput="this.value = this.value.replace(/[^0-9]/g, '');">
                            <span class="input-group-text unit-label-ra"></span>
                            </div>
                            <input type="text" class="stdqu-ra" name="stdqu[]" id="stdqu-ra-${i}" hidden>
                        </div>

                        <div class="col-md-12 mt-3">
                            <label class="form-label">Notes</label>
                            <textarea class="form-control" name="noted[]" id="noted-ra-${i}" maxlength="200"></textarea>
                            <div class="form-text text-danger text-end" style="font-size:0.7rem;">Maksimal 200 karakter</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>`;
        $('#accordionRA').append(dtl);

        const $newSelect = $(`#opron-ra-${i}`);

        applyOpronMode($newSelect);

        setTimeout(()=>{
            $(`#details-ra-${i}`).collapse('show');
        },100);
    }

    window.removeRA = function(i){
        $(`#accordion-ra-item-${i}`).remove();
    }
</script>
@endpush

