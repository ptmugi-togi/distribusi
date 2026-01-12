@extends('layout.main')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/global.css') }}">
@endpush

@section('container')
<main id="main" class="main">
  <div class="d-flex justify-content-between align-items-center">
    <div class="pagetitle">
      <h1>Tambah Data Work Order</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
          <li class="breadcrumb-item"><a href="{{ route('wo.index') }}">List Work Order</a></li>
          <li class="breadcrumb-item active">Work Order Create</li>
        </ol>
      </nav>
    </div>
  </div>

  <section class="section">
    <form id="form-wo" action="{{ route('wo.store') }}" method="POST">
        @csrf

        <div class="row">
        <input type="text" class="form-control" id="formc" name="formc" value="WO" hidden>
        <input type="text" class="form-control" id="braco" name="braco" value="PST" hidden>
            
        <div class="col-md-6 mt-3">
            <label for="wonum" class="form-label">Work Order</label><span class="text-danger"> *</span>
            <input type="text" class="form-control" name="wonum" id="wonum" value="{{ old('wonum') }}" required readonly style="background-color:#e9ecef">
        </div>

        <div class="col-md-6 mt-3">
            <label for="wodat" class="form-label">Work Order Date</label><span class="text-danger"> *</span>
            <input type="date" class="form-control" name="wodat" id="wodat" value="{{ old('wodat') }}" required min="{{ $minDate }}">
            <input type="text" name="priod" id="priod" value="{{ old('priod' ?? '') }}" hidden>
        </div>

        <div class="col-md-6 mt-3">
            <label for="reqbr" class="form-label">Request By Branch</label><span class="text-danger"> *</span>
            <select name="reqbr" id="reqbr" class="form-control select2">
                <option value="" disabled {{ old('reqbr') ? '' : 'selected' }}>Silahkan Pilih Branch</option>
                @foreach ($branches as $branch)
                    <option value="{{ $branch->braco }}" {{ old('braco') == $branch->braco ? 'selected' : '' }}>
                        {{ $branch->braco }} - {{ $branch->brana }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="col-md-6 mt-3">
            <label for="ppose" class="form-label">Purposes</label><span class="text-danger"> *</span>
            <select name="ppose" id="ppose" class="form-control select2">
            <option value="" disabled {{ old('ppose') ? '' : 'selected' }}>Silahkan Pilih Purposes</option>
            <option value="1" {{ old('ppose') == '1' ? 'selected' : '' }}>1 - Stock</option>
            <option value="2" {{ old('ppose') == '2' ? 'selected' : '' }}>2 - Sales</option>
            </select>
        </div>

        <div class="col-md-6 mt-3">
            <label for="refcno" class="form-label">Request By</label><span class="text-danger"> *</span>
            <select name="refcno" id="refcno" class="form-control select2" disabled>
                <option value="" disabled {{ old('refcno') ? '' : 'selected' }}>Silahkan Pilih BPB/Requisition</option>
            </select>
            <input type="text" id="reffc" name="reffc" value="{{ old('reffc') }}" hidden>
            <input type="text" id="refno" name="refno" value="{{ old('refno') }}" hidden>
            <div class="form-check">
                <label for="noBpb"></label>
                <input class="form-check-input noBpb-checkbox" type="checkbox" value="1" name="noBpb" id="noBpb">
                <label class="form-check-label" for="noBpb">
                    Without BPB
                </label>
            </div>
        </div>

        <div class="col-md-6 mt-3">
            <label for="reqdt" class="form-label">Request Date</label><span class="text-danger"> *</span>
            <input type="date" class="form-control" name="reqdt" id="reqdt" value="{{ old('reqdt') }}" required>
        </div>
        
        <div class="col-md-6 mt-3">
            <label for="cusna" class="form-label">Customer</label><span class="text-danger"> *</span>
            <input type="text" class="form-control" name="cusna" id="cusna" value="{{ old('cusna') }}" required>
        </div>

        <div class="col-md-6 mt-3">
            <label for="sorfcno" class="form-label">Order Confirmation Number</label><span class="text-danger"> *</span>
            <input type="text" class="form-control" name="sorfcno" id="sorfcno" value="{{ old('sorno') }}" required readonly style="background-color:#e9ecef">
            <input type="text" class="form-control" name="sorfc" id="sorfc" value="{{ old('sorno') }}" required hidden>
            <input type="text" class="form-control" name="sorno" id="sorno" value="{{ old('sorno') }}" required hidden>
        </div>

        <div class="col-md-6 mt-3">
            <label for="costc" class="form-label">Cost Center</label><span class="text-danger"> *</span>
            <select name="costc" id="costc" class="form-control select2">
                <option value="" disabled {{ old('costc') ? '' : 'selected' }}>Silahkan Pilih Cost Center</option>
                @foreach ($costc as $costc)
                    <option value="{{ $costc->costc }}" {{ old('costc') == $costc->costc ? 'selected' : '' }}>
                        {{ $costc->costc }} - {{ $costc->descr }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="col-md-6 mt-3">
            <label for="fdate" class="form-label">Finish Schedule</label><span class="text-danger"> *</span>
            <input type="date" class="form-control" id="fdate" name="fdate" value="{{ old('fdate') }}" required>
        </div>

        <div class="col-md-12 mt-3">
            <label class="form-label">Notes</label>
            <textarea type="text" class="form-control" name="noteh" id="noteh" maxlength="200">{{ old('noteh') }}</textarea>
            <div class="form-text text-danger text-end" style="font-size:0.7rem;">Maksimal 200 karakter</div>
        </div>
        </div>
      

        <div id="section-ra">
            @include('production.work_order.partial_create.wo_create_detail')
        </div>

        <div class="mt-3 d-flex justify-content-between">
            <a href="{{ route('wo.index') }}" class="btn btn-secondary">Kembali</a>
            <button type="submit" class="btn btn-primary">Simpan Data</button>
        </div>
    </form>
  </section>
</main>

@push('scripts')
    <script>
        $(document).ready(function(){
            $('.select2').select2({ width: '100%', theme: 'bootstrap-5' });
            generateWoNum();
            $('#refcno').prop('disabled', true);
            $('#noBpb').prop('checked', true).prop('disabled', true);
            $('#reqdt')
                .prop('readonly', true)
                .prop('disabled', true)
                .css('background-color', '#e9ecef');
            syncReqdtMode();
        });

        function generateWoNum() {
            $.get("{{ route('generate-wonum') }}", function (res) {
                $('#wonum').val(res);
            });
        }

        $(document).on('change', '#wodat', function () {
              const tanggal = this.value;
              if (!tanggal) return;

              const year  = tanggal.substring(0, 4);
              const month = tanggal.substring(5, 7);

              $('#priod').val(year + month);
        });

        let isnoBpb = true;

        function fillRefData() {
            const selected = $('#refcno').find(':selected');

            const cust  = selected.data('cust')  || '-';
            const sorfc = selected.data('sorfc') || '';
            const sorno = selected.data('sorno') || '';
            const reffc = selected.data('formc') || '';
            const refno = selected.data('reqno') || '';
            const reqdt = selected.data('reqdt') || '';

            $('#cusna').val(cust);
            $('#sorfc').val(sorfc);
            $('#sorno').val(sorno);
            $('#reffc').val(reffc);
            $('#refno').val(refno);
            $('#reqdt').val(reqdt);

            $('#sorfcno').val((sorfc && sorno) ? `${sorfc} ${sorno}` : '-');
        }

        $('#reqbr').on('change', function () {
            let reqbr = $(this).val();
            let raSelect = $('#refcno');

            resetAllAccordionTitle();
            refreshAllOpron();

            $('#cusna').val('-');
            $('#sorfc').val('');
            $('#sorno').val('');
            $('#sorfcno').val('-');

            raSelect.html('<option>Loading...</option>');

            if (reqbr) {
                $.get('/get-ra-wo/' + reqbr, function (data) {
                    raSelect.empty();
                    raSelect.append('<option value="" disabled selected>Pilih BPB/Requisition</option>');

                    data.forEach(function (item) {
                        raSelect.append(`
                            <option value="${item.reqno}"
                                data-formc="${item.formc}"
                                data-bpbid="${item.bpbid}"
                                data-cust="${item.rqfor}"
                                data-sorfc="${item.sorfc}"
                                data-sorno="${item.sorno}"
                                data-reqno="${item.reqno}"
                                data-reqdt="${item.reqdt}">
                                ${item.formc} ${item.reqno}
                            </option>
                        `);
                    });

                });
            } else {
                raSelect.html('<option value="" disabled selected>Pilih BPB/Requisition</option>');
            }
        });

        $(document).on('change', '#refcno', function () {
            fillRefData();
            syncReqdtMode();
        });

        function syncReqdtMode() {
            const ppose = $('#ppose').val();
            const useBpb = (ppose == '2' && !isnoBpb);

            // kalau belum pilih ppose, tetep lock
            if (!ppose) {
                $('#reqdt')
                    .val('')
                    .prop('disabled', true)
                    .prop('readonly', true)
                    .css('background-color', '#e9ecef');
                return;
            }

            $('#reqdt').prop('disabled', false);

            if (useBpb) {
                // reqdt dari BPB/RA readonly
                const selected = $('#refcno').find(':selected');
                const reqdt = selected.data('reqdt') || '';

                $('#reqdt')
                    .val(reqdt)
                    .prop('readonly', true)
                    .css('background-color', '#e9ecef');
            } else {
                // reqdt ikut wodat, bisa diedit
                const wodat = $('#wodat').val() || '';

                if (!$('#reqdt').val() || $('#reqdt').prop('readonly')) {
                    $('#reqdt').val(wodat);
                }

                $('#reqdt')
                    .prop('readonly', false)
                    .css('background-color', '');
            }
        }

        $('#ppose').on('change', function () {
            const ppose = $(this).val();

            if (ppose == '1') {
                isnoBpb = true;

                $('#noBpb')
                    .prop('checked', true)
                    .prop('disabled', true);

                $('#refcno')
                    .prop('disabled', true)
                    .val(null)
                    .trigger('change');

            } else if (ppose == '2') {
                isnoBpb = false;

                $('#noBpb')
                    .prop('checked', false)
                    .prop('disabled', false);

                $('#refcno')
                    .prop('disabled', false)
                    .val(null)
                    .trigger('change');
            }

            syncReqdtMode();

            refreshAllOpron();
        });

        $('#noBpb').on('change', function(){
            isnoBpb = $(this).is(':checked');

            const ppose = $('#ppose').val();

            // kalau SALES, atur refcno
            if (ppose == '2') {
                if (isnoBpb) {
                    $('#refcno')
                        .prop('disabled', true)
                        .val(null)
                        .trigger('change');
                } else {
                    $('#refcno')
                        .prop('disabled', false);
                }
            }

            syncReqdtMode();
            refreshAllOpron();
        });

        function applyOpronMode($select) {
            const ppose = $('#ppose').val();
            const bpbid = $('#refcno').find(':selected').data('bpbid');

            if ($select.hasClass('select2-hidden-accessible')) {
                $select.select2('destroy');
            }

            $select.empty();

            if (ppose == '1') {
                loadMasterProduct($select);
                return;
            }

            if (ppose == '2') {
                if (isnoBpb) {
                    loadMasterProduct($select);
                    return;
                }

                if (!bpbid) {
                    $select.append('<option value="" disabled selected>Pilih BPB/Requisition terlebih dulu</option>');
                    return;
                }

                $select.append('<option value="">Loading...</option>');

                $.get('/get-barang-ra/' + bpbid, function(data){
                    $select.empty().append('<option value="" disabled selected>Pilih Barang (RA)</option>');

                    data.forEach(item => {
                        $select.append(`
                            <option value="${item.opron}"
                                    data-qty="${item.rqqty}"
                                    data-stdqu="${item.stdqu}">
                                ${item.opron} - ${item.prona}
                            </option>
                        `);
                    });

                    $select.select2({ width:'100%', theme:'bootstrap-5' });
                });
            }
        }
        
        // ambil master product
        function loadMasterProduct($select){
            $select.select2({
                placeholder: 'Pilih Barang',
                theme: 'bootstrap-5',
                width: '100%',
                allowClear: true,
                ajax: {
                    url: '{{ route("api.products") }}',
                    dataType: 'json',
                    delay: 250,
                    data: function(params){
                        return {
                            q: params.term || '',
                            page: params.page || 1
                        };
                    },
                    processResults: function(data){
                        return {
                            results: (data.results || []).map(item => ({
                                id: item.id,
                                text: item.text,
                                stdqu: item.data_stdqu
                            })),
                            pagination: { more: data.pagination.more }
                        };
                    }
                }
            });
        }

        $('#noBpb').trigger('change');

        $('#refcno').on('change', function(){
            refreshAllOpron();
        });

        function refreshAllOpron(){
            $('select.opron-ra').each(function(){
                applyOpronMode($(this));
            });
        }

        // ubah nama accordion 
        function setAccordionTitle(item){
            const prona = item.find('select[name*="opron"] option:selected').text() || '';
            item.find('.accordion-title').text(prona ? `Product : ${prona}` : '-');
        }

        function resetAllAccordionTitle(){
            $('#accordionRA .accordion-item').each(function(){
                $(this).find('.accordion-title').text('-');
            });
        }

        $(document).on('select2:select', 'select.opron-ra', function (e) {
            const $select = $(this);
            const data = e.params.data || {};
            const $accordionItem = $select.closest('.accordion-item');

            if (data.stdqu) {
                $select.attr('data-stdqu', data.stdqu);

                $accordionItem.find('.unit-label-ra').text(data.stdqu);
                $accordionItem.find('.stdqu-ra').val(data.stdqu);
            }

            setAccordionTitle($accordionItem);
        });

        $(document).on('change','select[name*="opron"]', function(){
            const $accordionItem = $(this).closest('.accordion-item');

            setAccordionTitle($accordionItem);

            const stdqu =
            $(this).find(':selected').data('stdqu')
            || $(this).attr('data-stdqu')
            || '-';

            $accordionItem.find('.unit-label-ra').text(stdqu);

            $accordionItem.find('.stdqu-ra').val(stdqu);
        });


        // SweetAlert confirm submit
        document.addEventListener("DOMContentLoaded", function() {
            const form = document.getElementById('form-wo');
            form.addEventListener('submit', function (e) {
            e.preventDefault();
            Swal.fire({
                title: 'Konfirmasi Simpan',
                text: 'Apakah Anda yakin ingin menyimpan data ini?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, Simpan!',
                cancelButtonText: 'Batal'
            }).then((res)=>{
                if(res.isConfirmed){
                Swal.fire({ title:'Menyimpan...', text:'Mohon tunggu sebentar', icon:'info', showConfirmButton:false, allowOutsideClick:false, allowEscapeKey:false, didOpen:()=>Swal.showLoading() });
                form.submit();
                }
            });
            });
        });
    </script>
@endpush
@endsection