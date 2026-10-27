<div class="row">
    <div class="col-md-6 mt-3">
        <label for="formc" class="form-label">Form Code</label><span class="text-danger"> *</span>
        <input type="text" class="form-control" value="DO (Delivery Order)" disabled>
        <input type="hidden" class="form-control" id="formc" name="formc_store" id="formc-store" value="DO">
    </div>

    <div class="col-md-6 mt-3">
        <label for="tradt" class="form-label">DO Date</label><span class="text-danger"> *</span>
        <input type="date" class="form-control" name="tradt" id="tradt" value="{{ old('tradt') }}" required min="{{ $minDate }}">
    </div>

    <div class="col-md-6 mt-3">
        <label for="ocno" class="form-label">OC No.</label><span class="text-danger"> *</span>
        <select name="ocno" id="ocno" class="form-control select2" required>
        <option value="" disabled selected>Silahkan Pilih OC</option>
        </select>
        <input type="hidden" id="rfc01" name="rfc01" value="{{ old('rfc01') }}">
        <input type="hidden" id="ref01" name="ref01" value="{{ old('ref01') }}">
    </div>

    <div class="col-md-6 mt-3">
        <label for="exped" class="form-label">Expediter</label><span class="text-danger"> *</span>
        <select name="exped" id="exped-do" class="form-control select2" required>
        <option value="" disabled selected>Pilih Expediter</option>
        @foreach ($mexped as $mexp)
            <option value="{{ $mexp->ename }}" {{ old('exped') == $mexp->ename ? 'selected' : '' }}>
                {{ $mexp->ename }}
            </option>
        @endforeach
        </select>
    </div>

    <div class="col-md-12 mt-3">
        <label for="noteh" class="form-label">Notes</label>
        <textarea class="form-control" name="noteh" id="noteh" rows="2">{{ old('noteh') }}</textarea>
    </div>
</div>

<div class="Address">
    <hr>
    <h5>Address</h5>
    <div id="section-address">
        <div class="row">
        <input type="text" name="cusno" id="cusno" value="{{ old('cusno') }}" hidden>
        <input type="text" name="cuspo" id="cuspo" value="{{ old('cuspo') }}" hidden>
        <div class="col-md-4 mt-3">
            <label for="shpto" class="form-label">Deliver To</label>
            <select name="shpto" id="shpto" class="form-control select2"></select>
        </div>

        <div class="col-md-4 mt-3">
            <label for="shpto_name" class="form-label">Deliver To Name</label>
            <input type="text" class="form-control" name="shpto_name" id="shpto_name" value="{{ old('shpto_name') }}" disabled>
        </div>

        <div class="col-md-4 mt-3">
            <label for="shpto_attn" class="form-label">Attn</label>
            <input type="text" class="form-control" name="shpto_attn" id="shpto_attn" value="{{ old('shpto_attn') }}" disabled>
        </div>

        <div class="col-md-4 mt-3">
            <label for="shpto_prov" class="form-label">Provinsi</label>
            <input type="text" class="form-control" name="shpto_prov" id="shpto_prov" value="{{ old('shpto_prov') }}" disabled>
        </div>

        <div class="col-md-4 mt-3">
            <label for="shpto_kab" class="form-label">Kabupaten</label>
            <input type="text" class="form-control" name="shpto_kab" id="shpto_kab" value="{{ old('shpto_kab') }}" disabled>
        </div>

        <div class="col-md-4 mt-3">
            <label for="shpto_phone" class="form-label">Phone</label>
            <input type="text" class="form-control" name="shpto_phone" id="shpto_phone" value="{{ old('shpto_phone') }}" disabled>
        </div>

        <div class="col-md-12 mt-3">
            <label for="shpto_address" class="form-label">Deliver To Address</label>
            <textarea class="form-control" name="shpto_address" id="shpto_address" rows="2" disabled>{{ old('shpto_address') }}</textarea>
        </div>
        </div>
    </div>
</div>
<div id="section-do" class="mt-3">
    @include('logistic.bbk.partial_create.detail_bbk_create_do')
</div>

@push('scripts')
    <script>
        $(document).ready(function(){
            $('.select2').select2({ width: '100%', theme: 'bootstrap-5' });

            $.get("{{ route('get-oc') }}", function(data){
                let ocno = $('#ocno');
                ocno.empty();
                ocno.append('<option value="" disabled selected>Silahkan Pilih OC</option>');

                data.forEach(function(item){
                    ocno.append(`<option 
                        value="${item.value}"
                        data-cusno="${item.cusno}"
                        data-cuspo="${item.cuspo}"
                        data-shpto="${item.shpto}"
                        data-shpnm="${item.shpnm}"
                        data-contp="${item.contp}"
                        data-province="${item.province}"
                        data-kabupaten="${item.kabupaten}"
                        data-phone="${item.phone}"
                        data-address="${item.address}"
                    >
                        ${item.text} (${item.cust})
                    </option>`);
                });
            });
        });

        // PILIH BARANG
        $('#ocno').on('change', function(){

            let selected = $(this).find(':selected');
            let selectedText = $("#ocno option:selected").text();
            let parts = selectedText.split(' - ');

            let type = parts[0] || '';
            let sorno = parts[1] ? parts[1].split('(')[0].trim() : '';

            $('#rfc01').val(type);
            $('#ref01').val(sorno);

            $('#cusno').val(selected.data('cusno'));
            $('#cuspo').val(selected.data('cuspo'));
            $('#shpto').val(selected.data('shpto'));
            $('#shpto_name').val(selected.data('shpnm'));
            $('#shpto_attn').val(selected.data('contp'));
            $('#shpto_prov').val(selected.data('province'));
            $('#shpto_kab').val(selected.data('kabupaten'));
            $('#shpto_phone').val(selected.data('phone'));
            $('#shpto_address').val(selected.data('address'));

            let cusno = selected.data('cusno');
            let defaultShpto = selected.data('shpto');

            $.get("{{ route('get-shipto-by-cusno') }}", { cusno }, function(res){
                let $shpto = $('#shpto');
                $shpto.empty();

                if(!res.length){
                    $shpto.append('<option disabled selected>Tidak ada alamat</option>');
                    return;
                }

                $shpto.append('<option value="" disabled>Pilih Deliver To</option>');

                res.forEach(item => {
                    $shpto.append(`
                        <option value="${item.shpto}"
                            data-shpnm="${item.shpnm}"
                            data-contp="${item.contp}"
                            data-province="${item.province}"
                            data-kabupaten="${item.kabupaten}"
                            data-phone="${item.phone}"
                            data-address="${item.address}"
                            ${item.shpto == defaultShpto ? 'selected' : ''}
                        >
                            ${item.shpto}
                        </option>
                    `);
                });
                $shpto.trigger('change.select2');
            });

            let $itemSelect = $('.opron-do');

            // reset select2 dengan benar
            $itemSelect.empty().append('<option>Loading...</option>');
            $itemSelect.trigger('change.select2');

            $.get("{{ route('get-barang-oc') }}", {type, sorno}, function(res){

                $itemSelect.empty();

                if(!res.length){
                    $itemSelect.append('<option disabled selected>Tidak ada barang</option>');
                    return;
                }

                $itemSelect.append('<option value="" disabled selected>Pilih Barang</option>');

                res.forEach(item => {
                    $itemSelect.append(`
                        <option value="${item.opron}" 
                                data-qty="${item.qty}" 
                                data-stdqu="${item.stdqu}"
                                data-toqoh="${item.toqoh}">
                            ${item.opron} - ${item.prona}
                        </option>
                    `);
                });

                // refresh select2 clean
                $itemSelect.val(null).trigger('change.select2');
            });
        });

        $('#shpto').on('change', function(){
            let selected = $(this).find(':selected');

            $('#shpto_name').val(selected.data('shpnm'));
            $('#shpto_attn').val(selected.data('contp'));
            $('#shpto_prov').val(selected.data('province'));
            $('#shpto_kab').val(selected.data('kabupaten'));
            $('#shpto_phone').val(selected.data('phone'));
            $('#shpto_address').val(selected.data('address'));
        });

        // ubah nama accordion 
        function setAccordionTitle(item){
            const prona = item.find('select[name*="opron"] option:selected').text() || '';
            item.find('.accordion-title').text(prona ? `Product : ${prona}` : '-');
        }

        // change listener IA
        $(document).on('change','select[name*="opron"]', function(){
            setAccordionTitle($(this).closest('.accordion-item'));
        });

        // sweetalert qty input
        $(document).on('input', 'input[name="trqty[]"]', function() {
            const id = $(this).attr('id');
            const index = id.split('-').pop();
            let maxIn = Number($(`#inqty-ia-${index}`).val());
            if(!maxIn) maxIn = Number($(`#inqty-ib-${index}`).val());

            if(!maxIn || isNaN(maxIn) || maxIn <= 0){
                return; 
            }

            if(Number($(this).val()) > maxIn){
                Swal.fire({
                    icon: 'error',
                    title: 'Qty Melebihi Batas',
                    text: `Issue Qty tidak boleh lebih dari ${maxIn}`
                });
                $(this).val(maxIn);
            }
        });
    </script>
@endpush