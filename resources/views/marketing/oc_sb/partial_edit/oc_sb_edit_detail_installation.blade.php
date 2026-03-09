{{-- Detail Installation --}}
<div class="row">
    <h4 class="my-2">OC Installation (Edit)</h4>
    <div class="accordion" id="accordionOCInstallation">

        @foreach ($ocsb->ocsbdtls as $i => $detail)
        <div class="accordion-item" id="accordion-oc-installation-{{ $i }}">
            <h2 class="accordion-header d-flex justify-content-between align-items-center">
                <button class="accordion-button {{ $i > 0 ? 'collapsed' : '' }}" type="button"
                        data-bs-toggle="collapse"
                        data-bs-target="#details-oc-{{ $i }}">
                    <span class="accordion-title">
                        {{ $detail->opron }} - {{ $detail->mpromas->prona }}
                    </span>
                </button>

                @if($i > 0)
                <button type="button"
                        class="btn btn-sm btn-danger mx-2"
                        onclick="removeOCInstallation({{ $i }})">
                    <i class="bi bi-trash-fill"></i>
                </button>
                @endif
            </h2>

            <div id="details-oc-{{ $i }}"
                class="accordion-collapse collapse {{ $i == 0 ? 'show' : '' }}"
                data-bs-parent="#accordionOCInstallation">

                <div class="accordion-body">
                    <div class="row">

                        <div class="col-md-6 mt-3">
                            <label class="form-label">Product</label><span class="text-danger"> *</span>
                            <select class="select2 form-control opron-oc" name="opron[]" id="opron-oc-{{ $i }}" required>
                                <option value="{{ $detail->opron }}" selected>
                                    {{ $detail->opron }} - {{ $detail->mpromas->prona }}
                                </option>
                            </select>

                            <input type="text" id="prona-oc-{{ $i }}" class="prona-oc" name="prona[]" value="{{ $detail->mpromas->prona }}" hidden>
                        </div>

                        <div class="col-md-6 mt-3">
                            <label class="form-label">Order Quantity</label><span class="text-danger"> *</span>
                            <div class="input-group">
                                <input type="number" id="qtyor-oc-{{ $i }}" class="form-control qtyor-oc" name="qtyor[]" value="{{ $detail->qtyor }}" min="1" required>
                                <span class="input-group-text unit-label-oc">
                                    {{ $detail->stdqu }}
                                </span>
                            </div>

                            <input type="hidden" id="stdqu-oc-{{ $i }}" class="stdqu-oc" name="stdqu[]" value="{{ $detail->stdqu }}">
                        </div>

                        <div class="col-md-12 mt-3">

                            <button type="button"
                                    class="btn btn-sm btn-primary d-none"
                                    id="btn-bom-{{ $i }}"
                                    onclick="openBomModal({{ $i }})">
                                Lihat Consist of Goods
                            </button>

                            <div id="bom-hidden-{{ $i }}"></div>

                        </div>

                        <div class="col-md-6 mt-3">
                            <label class="form-label">Price / Unit</label><span class="text-danger"> *</span>
                            <input type="text" class="form-control price-input" id="price_display_oc_{{ $i }}" value="{{ $detail->price }}" data-raw-target="price_raw_oc_{{ $i }}" required>
                            <input type="text" name="price[]" id="price_raw_oc_{{ $i }}" value="{{ $detail->price }}" hidden>
                        </div>


                        <div class="col-md-6 mt-3">
                            <label class="form-label">Price List / Unit</label><span class="text-danger"> *</span>
                            <input type="text" class="form-control price-input" id="plist_display_oc_{{ $i }}" value="{{ $detail->plist }}" data-raw-target="plist_raw_oc_{{ $i }}" required>
                            <input type="text" name="plist[]" id="plist_raw_oc_{{ $i }}" value="{{ $detail->plist }}" hidden>
                        </div>

                        <div class="col-md-6 mt-3">
                            <label class="form-label">Discount / Unit</label>
                            <input type="text" class="form-control price-input" id="odisa_display_oc_{{ $i }}" value="{{ $detail->odisa }}" data-raw-target="odisa_raw_oc_{{ $i }}">
                            <input type="text" name="odisa_ins[]" id="odisa_raw_oc_{{ $i }}" value="{{ $detail->odisa }}" hidden>
                        </div>

                        <div class="col-md-6 mt-3">
                            <label class="form-label">Jasa Teknik / Unit</label>
                            <input type="text" class="form-control price-input" id="teknik_display_oc_{{ $i }}" value="{{ $detail->teknik }}" data-raw-target="teknik_raw_oc_{{ $i }}">
                            <input type="text" name="teknik[]" id="teknik_raw_oc_{{ $i }}" value="{{ $detail->teknik }}" hidden>
                        </div>

                        <div class="col-md-6 mt-3">
                            <label class="form-label">Klasifikasi Produk</label><span class="text-danger"> *</span>
                            <select name="putama[]" id="putama-oc-{{ $i }}" class="form-control select2" required>
                                <option value="U" {{ $detail->putama == 'U' ? 'selected' : '' }}>Utama</option>
                                <option value="N" {{ $detail->putama == 'N' ? 'selected' : '' }}>Non Utama</option>
                            </select>
                        </div>

                        <div class="col-md-6 mt-3">
                            <label for="insby-oc-{{ $i }}" class="form-label">Install by Branch</label><span class="text-danger"> *</span>
                            <select name="insby[]" class="form-control select2" id="insby-oc-{{ $i }}" required>
                                <option value="{{ $detail->insby }}" selected>
                                @foreach ($branches as $b)
                                    <option value="{{ $b->braco }}" {{ $detail->insby == $b->braco ? 'selected' : '' }}>
                                        {{ $b->braco }} - {{ $b->brana }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-6 mt-3">
                            <label for="insdt-oc-{{ $i }}" class="form-label">Planned Installation Date</label>
                            <input type="date" class="form-control" name="insdt[]" id="insdt" value="{{ $detail->insdt }}">
                        </div>

                        <hr class="my-4">

                        <h5>Installation Site</h5>

                        <div class="col-md-4 mt-3">
                            <label class="form-label">Installation Site</label><span class="text-danger"> *</span>
                            <select name="delto[]" id="delto-{{ $i }}" class="form-control select2 delto-select" data-index="{{ $i }}" required>
                                <option value="{{ $detail->delto }}" selected>
                                    {{ $detail->delto }}
                                </option>
                            </select>
                        </div>

                        <div class="col-md-4 mt-3">
                            <label class="form-label">Name</label>
                            <input type="text" id="delto_name-{{ $i }}" name="delto_name[]" class="form-control" value="{{ $detail->site->shpnm }}" readonly style="background-color: #e9ecef">
                        </div>

                        <div class="col-md-4 mt-3">
                            <label class="form-label">Attn.</label>
                            <input type="text" name="delto_attn[]" id="delto_attn-{{ $i }}" class="form-control" value="{{ $detail->site->contp }}" readonly style="background-color: #e9ecef">
                        </div>

                        <div class="col-md-6 mt-3">
                            <label class="form-label">Provinsi</label>
                            <input type="text" name="delto_prov[]" id="delto_prov-{{ $i }}" class="form-control" value="{{ $detail->site->province }}" readonly style="background-color: #e9ecef">
                        </div>

                        <div class="col-md-6 mt-3">
                            <label class="form-label">Kabupaten</label>
                            <input type="text" name="delto_kab[]" id="delto_kab-{{ $i }}" class="form-control" value="{{ $detail->site->kabupaten }}" readonly style="background-color: #e9ecef">
                        </div>

                        <div class="col-md-6 mt-3">
                            <label class="form-label">Address</label>
                            <textarea name="delto_addrress[]" id="delto_addrress-{{ $i }}" class="form-control" readonly style="background-color: #e9ecef">{{ $detail->site->deliveryaddress }}</textarea>
                        </div>

                        <div class="col-md-6 mt-3">
                            <label class="form-label">Phone</label>
                            <input type="text" name="delto_phone[]" id="delto_phone-{{ $i }}" class="form-control" value="{{ $detail->site->phone }}" readonly style="background-color: #e9ecef">
                        </div>

                        <div class="col-md-12 mt-3">
                            <label class="form-label">Notes</label>
                            <textarea class="form-control" id="noted-oc-{{ $i }}" name="noted_installation[]" maxlength="200">{{ $detail->noted }}</textarea>
                            <div class="form-text text-danger text-end" style="font-size:0.7rem;"> Maksimal 200 karakter
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <div class="text-end">
        <button type="button" class="btn mt-3" style="background-color:#4456f1;color:#fff" onclick="addOCInstallation()">
            Tambah Detail Installation
        </button>
    </div>
</div>

{{-- modal sub product --}}
<div class="modal fade" id="modalSubOpron" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg">
        <div class="modal-content">
        <div class="modal-header bg-info text-white">
            <h5 class="modal-title">Consist of Goods</h5>
            <button class="btn-close" type="button" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body p-0">
            <table class="table table-bordered mb-0">
            <thead class="table-info">
                <tr>
                <th>PRODUCT#</th>
                <th>PRODUCT NAME</th>
                <th>QTY</th>
                <th>UNIT</th>
                </tr>
            </thead>
            <tbody id="bomBody"></tbody>
            </table>
        </div>
        </div>
    </div>
</div>

@push('scripts')
{{-- script installation --}}
<script>
    let bomCache = {};
    let bomAutoOpened = {};

    $(document).ready(function () {
        $('.qtyor-oc').trigger('input');
        @foreach($ocsb->ocsbdtls as $i => $detail)
            @php
                $bom = $bomList[$detail->opron] ?? collect();
            @endphp

            bomCache[{{ $i }}] = [
                @foreach($bom as $b)
                    {
                        matno: "{{ $b->opron }}",
                        prona: "{{ $b->prona }}",
                        rqqty: "{{ $b->bsqty }}",
                        stdqu: "{{ $b->stdqu }}"
                    },
                @endforeach
            ];

            @if($bom->isNotEmpty())
                $('#btn-bom-{{ $i }}').removeClass('d-none');
            @endif
        @endforeach
    });

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

    // {{-- isian modal sub-product --}}
    function openBomModal(index) {

        const rows = bomCache[index] || [];
        const qtyor = parseFloat($(`#qtyor-oc-${index}`).val()) || 0;

        let html = '';

        rows.forEach(r => {

            const baseQty = parseFloat(r.rqqty) || 0;

            const finalQty = qtyor > 0
                ? baseQty * qtyor
                : baseQty;

            html += `
            <tr>
                <td>${r.matno}</td>
                <td>${r.prona}</td>
                <td class="text-end">${finalQty}</td>
                <td>${r.stdqu}</td>
            </tr>`;
        });

        $('#bomBody').html(html);
        $('#modalSubOpron').modal('show');
    }

    $(document).on('input', '.qtyor-oc', function () {

        const index = $(this).attr('id').split('-').pop();
        const qtyor = parseFloat($(this).val()) || 0;

        const rows = bomCache[index] || [];
        const $hidden = $(`#bom-hidden-${index}`);

        let hiddenHtml = '';

        rows.forEach((r, i) => {

            hiddenHtml += `
                <input type="hidden" name="bom[${index}][${i}][matno]" value="${r.matno}">
                <input type="hidden" name="bom[${index}][${i}][prona]" value="${r.prona}">
                <input type="hidden" name="bom[${index}][${i}][qty]" value="${r.rqqty}">
                <input type="hidden" name="bom[${index}][${i}][bsqty]" value="${r.rqqty}">
                <input type="hidden" name="bom[${index}][${i}][unit]" value="${r.stdqu}">
            `;
        });

        $hidden.html(hiddenHtml);

    });

    // {{-- simpan sub-product --}}
    $(document).on('select2:select', 'select.opron-oc', function (e) {

        const opron = e.params.data.id;
        const $select = $(this);
        const $accBody = $select.closest('.accordion-body');
        const index = $select.attr('id').split('-').pop();

        const $btn = $accBody.find(`#btn-bom-${index}`);
        const $hidden = $accBody.find(`#bom-hidden-${index}`);

        bomCache[index] = [];
        bomAutoOpened[index] = false;

        $btn.addClass('d-none');
        $hidden.html('');

        $.get(`{{ route('get-sub-product') }}`, { opron }).done(res => {

            if (res.length === 0) return;

            bomCache[index] = res;

            let hiddenHtml = '';
           res.forEach((r, i) => {

            const qtyor   = parseFloat($(`#qtyor-oc-${index}`).val()) || 0;
            const baseQty = parseFloat(r.rqqty) || 0;
            const finalQty = qtyor > 0 ? baseQty * qtyor : baseQty;

            hiddenHtml += `
                <input type="hidden" name="bom[${index}][${i}][matno]" value="${r.matno}">
                <input type="hidden" name="bom[${index}][${i}][prona]" value="${r.prona}">
                <input type="hidden" name="bom[${index}][${i}][qty]" value="${finalQty}">
                <input type="hidden" name="bom[${index}][${i}][bsqty]" value="${baseQty}">
                <input type="hidden" name="bom[${index}][${i}][unit]" value="${r.stdqu}">
            `;
        });

            $hidden.html(hiddenHtml);
            $btn.removeClass('d-none');

            if (!bomAutoOpened[index]) {
                openBomModal(index);
                bomAutoOpened[index] = true;
            }
        });
    });
</script>
<script>
    @foreach($ocsb->ocsbdtls as $i => $detail)

        @php
            $bom = $bomList[$detail->opron] ?? collect();
        @endphp

        bomCache[{{ $i }}] = [
            @foreach($bom as $b)
                {
                    matno: "{{ $b->opron }}",
                    prona: "{{ $b->prona }}",
                    rqqty: "{{ $b->bsqty }}",
                    stdqu: "{{ $b->stdqu }}"
                },
            @endforeach
        ];

        @if($bom->isNotEmpty())
            $('#btn-bom-{{ $i }}').removeClass('d-none');
        @endif

    @endforeach
</script>
@endpush