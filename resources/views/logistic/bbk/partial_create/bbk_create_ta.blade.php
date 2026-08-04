<div class="row">
    <div class="col-md-6 mt-3">
        <label for="tradt" class="form-label">Transfer Note Date</label><span class="text-danger"> *</span>
        <input type="date" class="form-control" name="tradt" id="tradt" value="{{ old('tradt') }}" required min="{{ $minDate }}">
    </div>

    <div class="col-md-6 mt-3">
        <label for="rqbrc" class="form-label">Request by Branch</label><span class="text-danger"> *</span>
        <select name="rqbrc" id="rqbrc" class="form-control select2" required>
        <option value="" disabled selected>Pilih Branch</option>
        @foreach ($mbranch as $m)
            <option value="{{ $m->braco }}" {{ old('rqbrc') == $m->braco ? 'selected' : '' }}>
            {{ $m->braco }}
            </option>
        @endforeach
        </select>
    </div>

    <div class="col-md-6 mt-3">
        <label for="sano" class="form-label">Stock Requisitoin No.</label><span class="text-danger"> *</span>
        <select name="sano" id="sano" class="form-control select2" required>
        <option value="" disabled selected>Pilih Request by Branch Terlebih Dahulu</option>
        </select>
        <input type="text" id="rfc01" name="rfc01" value="{{ old('rfc01') }}" hidden>
        <input type="text" id="ref01" name="ref01" value="{{ old('ref01') }}" hidden>
    </div>

    <div class="col-md-6 mt-3">
        <label for="exped" class="form-label">Expediter</label><span class="text-danger"> *</span>
        <select name="exped" id="exped" class="form-control select2" required>
        <option value="" disabled selected>Pilih Expediter</option>
        @foreach ($mexped as $mexp)
            <option value="{{ $mexp->ename }}" {{ old('exped') == $mexp->ename ? 'selected' : '' }}>
                {{ $mexp->ename }}
            </option>
        @endforeach
        </select>
    </div>

    <div class="col-md-6 mt-3">
        <label for="trana" class="form-label">Transfer To Name</label>
        <input type="text" class="form-control" name="trana" id="trana" value="{{ old('trana') }}" disabled>
    </div>

    <div class="col-md-6 mt-3">
        <label for="tradres" class="form-label">Transfer To Address</label>
        <textarea class="form-control" name="tradres" id="tradres" rows="2" disabled>{{ old('tradres') }}</textarea>
    </div>

    <div class="col-md-12 mt-3">
        <label for="noteh" class="form-label">Notes</label>
        <textarea class="form-control" name="noteh" id="noteh" rows="2">{{ old('noteh') }}</textarea>
    </div>
</div>

<div id="section-ta" class="mt-3">
    @include('logistic.bbk.partial_create.detail_bbk_create_ta')
</div>

@push('scripts')
    <script>
        $(document).ready(function(){
            let oldrqbrc = "{{ old('rqbrc') }}";

            if(oldrqbrc){
                $('#rqbrc').val(oldrqbrc).trigger('change');
            }
        });

        // generate trano
        $('#warco, #tradt').on('change', function(){
            let braco = $('#braco').val();
            let warco = $('#warco').val();
            let formc = $('#formc').val();
            let tradt = $('#tradt').val();

            if(warco && formc && tradt){
                $.get("{{ route('generate-trano-ta') }}", {formc, warco, tradt}, function(res){
                    $('#trano').val(res);
                });
            }
        });

        // ambil nomor SA sesuai rqbrc
        $('#rqbrc').on('change', function(){
            let rqbrc = $(this).val();
            $.get("{{ route('get-sa') }}", {rqbrc}, function(res){
                $('#sano').empty().append('<option value="" disabled selected>Pilih Stock Requisition</option>');
                res.sa.forEach(item => {
                    $('#sano').append(`<option value="${item.bpbid}" data-formc="${item.formc}" data-reqno="${item.reqno}">${item.formc} - ${item.reqno}</option>`);
                });

                // ambil trana dan tradres sesuai rqbrc
                if(res.braco){
                    $('#trana').val(res.braco.brana);
                    $('#tradres').val(res.braco.address);
                }
            });
            $('.opron-ta').empty().append('<option value="" disabled selected>Pilih Requisition Terlebih Dahulu</option>');
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
