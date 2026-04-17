<div class="row">
  <h4 class="my-2">OC Detail</h4>
  <div class="accordion" id="accordionOC">
    @foreach (old('opron', [null]) as $i => $oldOpron)
      <div class="accordion-item" id="accordion-oc-item-{{ $i }}">
        <h2 class="accordion-header d-flex justify-content-between align-items-center" id="heading-oc-{{ $i }}">
          <button class="accordion-button {{ $i > 0 ? 'collapsed' : '' }}" type="button"
            data-bs-toggle="collapse" data-bs-target="#details-oc-{{ $i }}"
            aria-expanded="{{ $i == 0 ? 'true' : 'false' }}" aria-controls="details-oc-{{ $i }}">
            <span class="accordion-title"></span>
          </button>
          @if($i > 0)
            <button type="button" class="btn btn-sm btn-danger mx-2" onclick="removeOC({{ $i }})">
              <i class="bi bi-trash-fill"></i>
            </button>
          @endif
        </h2>

        <div id="details-oc-{{ $i }}" class="accordion-collapse collapse {{ $i == 0 ? 'show' : '' }}"
          aria-labelledby="heading-oc-{{ $i }}" data-bs-parent="#accordionOC">
          <div class="accordion-body">
            <div class="row">
                <div class="col-md-6 mt-3">
                    <label class="form-label">Product</label><span class="text-danger"> *</span>
                    <select class="select2 form-control opron-oc" name="opron[]" id="opron-oc-{{ $i }}" required>
                        <option value="" disabled {{ old('opron.'.$i) ? '' : 'selected' }}>Pilih Barang</option>
                        @if(old('opron.'.$i))
                            <option value="{{ old('opron.'.$i) }}" selected>
                                {{ old('prona.'.$i) ?? old('opron.'.$i) }}
                            </option>
                        @endif
                    </select>
                    <input type="text" class="prona-oc" name="prona[]" id="prona-oc-{{ $i }}" value="{{ old('prona.'.$i) }}" hidden>
                </div>

                <div class="col-md-6 mt-3">
                    <label for="qtyor-oc-{{ $i }}" class="form-label">Order Quantity</label><span class="text-danger"> *</span>
                    <div class="input-group">
                        <input type="number" class="form-control qtyor-oc" id="qtyor-oc-{{ $i }}" name="qtyor[]" value="{{ old('qtyor.'.$i) }}" min="1" required
                        oninput="this.value = this.value.replace(/[^0-9]/g, '');">
                        <span class="input-group-text unit-label-oc"></span>
                    </div>
                    <input type="text" class="stdqu-oc" name="stdqu[]" value="{{ old('stdqu.'.$i) }}" id="stdqu-oc-{{ $i }}" hidden>
                </div>

                <div class="col-md-6 mt-3">
                    <label for="rqeta-oc-{{ $i }}" class="form-label">Request ETA</label><span class="text-danger"> *</span>
                    <input type="date" class="form-control rqeta-oc" name="rqeta[]" id="rqeta-oc-{{ $i }}" value="{{ old('rqeta.'.$i) }}" required>
                </div>

                <div class="col-md-6 mt-3">
                    <label for="whetd-oc-{{ $i }}" class="form-label">ETD by W/H</label><span class="text-danger"> *</span>
                    <input type="date" class="form-control whetd-oc" name="whetd[]" id="whetd-oc-{{ $i }}" value="{{ old('whetd.'.$i) }}" required>
                </div>

                <div class="col-md-6 mt-3">
                    <label for="price-oc-{{ $i }}" class="form-label">Selling Price</label><span class="text-danger"> *</span>
                    <input type="text" class="form-control price-input" id="price_display_oc_{{ $i }}" value="{{ old('price.'.$i) ? number_format(old('price.'.$i), 2, '.', '') : '' }}" data-raw-target="price_raw_oc_{{ $i }}" required>

                    <input type="text" name="price[]" id="price_raw_oc_{{ $i }}" value="{{ old('price.'.$i) }}" hidden>
                </div>

                <div class="col-md-6 mt-3">
                    <label for="plist-oc-{{ $i }}" class="form-label">Price List/Unit</label><span class="text-danger"> *</span>
                    <input type="text" class="form-control price-input" id="plist_display_oc_{{ $i }}" value="{{ old('plist.'.$i) ? number_format(old('plist.'.$i), 2, '.', '') : '' }}" data-raw-target="plist_raw_oc_{{ $i }}" required>
                    <input type="text" name="plist[]" id="plist_raw_oc_{{ $i }}" value="{{ old('plist.'.$i) }}" hidden>
                </div>

                <div class="col-md-6 mt-3">
                    <label for="odisa-oc-{{ $i }}" class="form-label">Discount/Unit</label>
                    <input type="text" class="form-control price-input" id="odisa_display_oc_{{ $i }}" value="{{ old('odisa.'.$i) ? number_format(old('odisa.'.$i), 2, '.', '') : '' }}" data-raw-target="odisa_raw_oc_{{ $i }}">
                    <input type="text" name="odisa[]" id="odisa_raw_oc_{{ $i }}" value="{{ old('odisa.'.$i) }}" hidden>
                </div>

                <div class="col-md-6 mt-3">
                    <label for="teknik-oc-{{ $i }}" class="form-label">Jasa Teknik/Unit</label>
                    <input type="text" class="form-control price-input" id="teknik_display_oc_{{ $i }}" value="{{ old('teknik.'.$i) ? number_format(old('teknik.'.$i), 2, '.', '') : '' }}" data-raw-target="teknik_raw_oc_{{ $i }}">
                    <input type="text" name="teknik[]" id="teknik_raw_oc_{{ $i }}" value="{{ old('teknik.'.$i) }}" hidden>
                </div>

                <div class="col-md-6 mt-3">
                    <label for="total-oc-{{ $i }}" class="form-label">Source of Goods</label><span class="text-danger"> *</span>
                    <select name="srcog[]" class="form-control select2" id="srcog-oc-{{ $i }}" required>
                        <option value="" disabled {{ old('srcog.'.$i, '') === '' ? 'selected' : '' }}>Silahkan Pilih Source of Goods</option>
                        <option value="1" {{ old('srcog.'.$i) == 1 ? 'selected' : '' }}>1. Branch's Stock</option>
                        <option value="2" {{ old('srcog.'.$i) == 2 ? 'selected' : '' }}>2. Request to Head Office</option>
                    </select>
                </div>

                <div class="col-md-6 mt-3">
                    <label for="putama-oc-{{ $i }}" class="form-label">Klasifikasi Produk</label><span class="text-danger"> *</span>
                    <select name="putama[]" class="form-control select2" id="putama-oc-{{ $i }}" required>
                        <option value="U" {{ old('putama.'.$i) == 1 ? 'selected' : '' }}>Utama</option>
                        <option value="N" {{ old('putama.'.$i) == 2 ? 'selected' : '' }}>Non Utama</option>
                    </select>
                </div>

                <div class="col-md-12 mt-3">
                    <label class="form-label">Notes</label>
                    <textarea type="text" class="form-control" name="noted[]" id="noted-oc-{{ $i }}" maxlength="200">{{ old('noted.'.$i) }}</textarea>
                    <div class="form-text text-danger text-end" style="font-size:0.7rem;">Maksimal 200 karakter</div>
                </div>
            </div>
          </div>
        </div>
      </div>
    @endforeach
  </div>

  <div class="text-end">
    <button type="button" class="btn mt-3" style="background-color:#4456f1;color:#fff" onclick="addOC()">Tambah Detail </button>
  </div>
</div>

@push('scripts')
<script>
    $(document).on('select2:select', 'select.opron-oc', function (e) {
        const data = e.params.data;

        const $select = $(this);
        const $accBody = $select.closest('.accordion-body');

        const $stdqu = $accBody.find('.stdqu-oc');
        const $unitLabel = $accBody.find('.unit-label-oc');
        const $prona = $accBody.find('.prona-oc');

        const stdqt = data.stdqt || '';
        const prona = data.prona || '';

        $stdqu.val(stdqt);
        $unitLabel.text(stdqt);
        $prona.val(data.prona);

        $accBody.closest('.accordion-item')
            .find('.accordion-title')
            .text(data.text || '');
    });

    // reset
    $(document).on('select2:clear', 'select.opron-oc', function () {
        const $select = $(this);
        const $accBody = $select.closest('.accordion-body');

        $accBody.find('.stdqu-oc').val('');
        $accBody.find('.unit-label-oc').text('');
        $accBody.find('.prona-oc').val('');

        $accBody.closest('.accordion-item')
            .find('.accordion-title')
            .text('');
    });
</script>
<script>
    // add/remove row OC
    window.addOC = function(){
        const i = $('#accordionOC .accordion-item').length;
        const dtl = `
        <div class="accordion-item" id="accordion-oc-item-${i}">
            <h2 class="accordion-header d-flex justify-content-between align-items-center" id="heading-oc-${i}">
            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#details-oc-${i}" aria-expanded="false" aria-controls="details-oc-${i}"><span class="accordion-title"></span></button>
            <button type="button" class="btn btn-sm btn-danger mx-2" onclick="removeOC(${i})"><i class="bi bi-trash-fill"></i></button>
            </h2>
            <div id="details-oc-${i}" class="accordion-collapse collapse" aria-labelledby="heading-oc-${i}" data-bs-parent="#accordionOC">
                <div class="accordion-body">
                    <div class="row">
                        <div class="col-md-6 mt-3">
                            <label class="form-label">Barang</label><span class="text-danger"> *</span>
                            <select class="select2 form-control opron-oc" name="opron[]" id="opron-oc-${i}" required>
                            <option value="" disabled selected>Pilih Barang</option>
                            </select>
                            <input type="text" class="prona-oc" name="prona[]" id="prona-oc-${i}" value="" hidden>
                        </div>
                        
                        <div class="col-md-6 mt-3">
                            <label for="qtyor-oc-${i}" class="form-label">Order Quantity</label><span class="text-danger"> *</span>
                            <div class="input-group">
                                <input type="number" class="form-control qtyor-oc" id="qtyor-oc-${i}" name="qtyor[]"
                                oninput="this.value = this.value.replace(/[^0-9]/g, '');" min="1" required>
                                <span class="input-group-text unit-label-oc"></span>
                            </div>
                            <input type="text" class="stdqu-oc" name="stdqu[]" id="stdqu-oc-${i}" hidden>
                        </div>

                        <div class="col-md-6 mt-3">
                            <label for="rqeta-oc-${i}" class="form-label">Request ETA</label><span class="text-danger"> *</span>
                            <input type="date" class="form-control rqeta-oc" name="rqeta[]" id="rqeta-oc-${i}" required>
                        </div>

                        <div class="col-md-6 mt-3">
                            <label for="whetd-oc-${i}" class="form-label">ETD by W/H</label><span class="text-danger"> *</span>
                            <input type="date" class="form-control whetd-oc" name="whetd[]" id="whetd-oc-${i}" required>
                        </div>

                        <div class="col-md-6 mt-3">
                            <label for="price-oc-${i}" class="form-label">Selling Price</label><span class="text-danger"> *</span>
                            <input type="text" class="form-control price-input" id="price_display_oc_${i}" data-raw-target="price_raw_oc_${i}" required>

                            <input type="text" name="price[]" id="price_raw_oc_${i}" hidden>
                        </div>

                        <div class="col-md-6 mt-3">
                            <label class="form-label">Price List/Unit</label><span class="text-danger"> *</span>

                            <input type="text" class="form-control price-input" id="plist_display_oc_${i}" data-raw-target="plist_raw_oc_${i}" required>

                            <input type="text" name="plist[]" id="plist_raw_oc_${i}" hidden>
                        </div>

                        <div class="col-md-6 mt-3">
                            <label class="form-label">Discount/Unit</label>

                            <input type="text" class="form-control price-input" id="odisa_display_oc_${i}" data-raw-target="odisa_raw_oc_${i}">

                            <input type="text" name="odisa[]" id="odisa_raw_oc_${i}" hidden>
                        </div>

                        <div class="col-md-6 mt-3">
                            <label class="form-label">Jasa Teknik/Unit</label>

                            <input type="text" class="form-control price-input" id="teknik_display_oc_${i}" data-raw-target="teknik_raw_oc_${i}">

                            <input type="text" name="teknik[]" id="teknik_raw_oc_${i}" hidden>
                        </div>

                        <div class="col-md-6 mt-3">
                            <label for="total-oc-${i}" class="form-label">Source of Goods</label><span class="text-danger"> *</span>
                            <select name="srcog[]" class="form-control select2" id="srcog-oc-${i}" required>
                                <option value="" disabled {{ old('srcog.'.$i) ? '' : 'selected' }}>Silahkan Pilih Source of Goods</option>
                                <option value="1" {{ old('srcog.'.$i) == '1' ? 'selected' : '' }}>1. Branch's Stock</option>
                                <option value="2" {{ old('srcog.'.$i) == '2' ? 'selected' : '' }}>2. Request to Head Office</option>
                            </select>
                        </div>

                        <div class="col-md-6 mt-3">
                            <label for="putama-oc-${i}" class="form-label">Klasifikasi Produk</label><span class="text-danger"> *</span>
                            <select name="putama[]" class="form-control select2" id="putama-oc-${i}" required>
                                <option value="U" {{ old('putama.'.$i) == '1' ? 'selected' : '' }}>Utama</option>
                                <option value="N" {{ old('putama.'.$i) == '2' ? 'selected' : '' }}>Non Utama</option>
                            </select>
                        </div>

                        <div class="col-md-12 mt-3">
                            <label class="form-label">Notes</label>
                            <textarea type="text" class="form-control" name="noted[]" id="noted-oc-${i}" maxlength="200">{{ old('noted.'.$i) }}</textarea>
                            <div class="form-text text-danger text-end" style="font-size:0.7rem;">Maksimal 200 karakter</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>`;
        $('#accordionOC').append(dtl);

        const $newSelect = $(`#opron-oc-${i}`);

        $('.select2').select2({ width: '100%', theme: 'bootstrap-5' });

        loadMasterProductAll();

        initPriceFormatter(document.getElementById(`accordion-oc-item-${i}`));

        setTimeout(()=>{
            $(`#details-oc-${i}`).collapse('show');
        },100);
    }

    window.removeOC = function(i){
        $(`#accordion-oc-item-${i}`).remove();
    }
</script>
@endpush

