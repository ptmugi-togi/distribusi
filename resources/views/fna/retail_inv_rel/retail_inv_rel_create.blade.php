@extends('layout.main')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/global.css') }}">
@endpush

@section('container')
<main id="main" class="main">
  <div class="d-flex justify-content-between align-items-center">
    <div class="pagetitle">
      <h1>Tambah Data Retail Invoice Release</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
          <li class="breadcrumb-item"><a href="{{ route('retail_inv_rel.index') }}">List Retail Invoice Release</a></li>
          <li class="breadcrumb-item active">Retail Invoice Release Create</li>
        </ol>
      </nav>
    </div>
    <div class="card">
      <h5 class="p-2"><b>Branch : {{ auth()->user()->cabang }}</b></h5>
    </div>
  </div>

  <section class="section">
    <form id="form-retail_inv_rel" action="{{ route('retail_inv_rel.store') }}" method="POST">
      @csrf

      {{-- Global header (muncul dari awal) --}}
      <input type="hidden" name="braco" id="braco" value="{{ auth()->user()->cabang }}">
      <input type="hidden" class="form-control" id="formc" name="formc" id="formc-store" value="SC">

      <div class="row">
        <div class="col-md-6 mt-3">
          <label for="invno" class="form-label">Invoice No.</label><span class="text-danger"> *</span>
          <input type="text" id="invno" class="form-control" readonly style="background-color: #E9ECEF;">
          <input type="hidden" name="invno" id="invno_raw" value="{{ old('invno') }}">
        </div>

        <input type="hidden" name="priod" id="priod" value="{{ old('priod') }}">

        <div class="col-md-6 mt-3">
          <label for="invdt" class="form-label">Invoice Date</label><span class="text-danger"> *</span>
          <input type="date" class="form-control" name="invdt" id="invdt" value="{{ old('invdt', date('Y-m-d')) }}" required min="{{ $minDate }}">
        </div>

        <div class="col-md-6 mt-3">
          <label for="invdd" class="form-label">Invoice Due Date</label><span class="text-danger"> *</span>
          <input type="date" class="form-control" name="invdd" id="invdd" value="{{ old('invdd') }}" required min="{{ $minDate }}">
        </div>

        <input type="hidden" name="topay" id="topay" value="{{ old('topay') }}">

        <div class="col-md-6 mt-3">
          <label for="ocno" class="form-label">OC No.</label><span class="text-danger"> *</span>
          <select name="ocno" id="ocno" class="form-control select2" required>
            <option value="" disabled selected>Loading data OC...</option>
          </select>
          <input type="hidden" id="sorfc" name="sorfc" value="{{ old('sorfc') }}">
          <input type="hidden" id="sorno" name="sorno" value="{{ old('sorno') }}">
        </div>

        <div class="col-md-6 mt-3">
          <label for="ocdt" class="form-label">OC Date</label>
          <input type="text" class="form-control" name="ocdt" id="ocdt" value="{{ old('ocdt') }}" readonly style="background-color: #E9ECEF">
        </div>

        <div class="col-md-6 mt-3">
          <label for="refcno" class="form-label">Reference</label>
          <input type="text" class="form-control" name="refcno" id="refcno" value="{{ old('refcno') }}" readonly style="background-color: #E9ECEF">
          <input type="hidden" id="rfc01" name="rfc01" value="{{ old('rfc01') }}">
          <input type="hidden" id="ref01" name="ref01" value="{{ old('ref01') }}">
        </div>

        <div class="col-md-6 mt-3">
          <label for="curco" class="form-label">Currency</label>
          <input type="text" class="form-control" name="curco" id="curco" value="{{ old('curco') }}" readonly style="background-color: #E9ECEF">
        </div>

        <div class="col-md-6 mt-3">
          <label for="crate" class="form-label">Kurs</label>
          <input type="text" class="form-control" name="crate_display" id="crate_display" value="{{ old('crate_display') }}">
          <input type="hidden" class="form-control" name="crate" id="crate_raw" value="{{ old('crate') }}">
        </div>
                
        <div class="col-md-6 mt-3">
          <label for="gross" class="form-label">Full Amount</label>
          <input type="text" class="form-control price-input" id="gross" value="{{ old('gross') }}" readonly style="background-color: #E9ECEF">
          <input type="hidden" name="gross" id="gross_raw">
        </div>
                
        <div class="col-md-6 mt-3">
          <label for="odisa" class="form-label">Discount</label>
          <input type="text" class="form-control price-input" id="odisa" value="{{ old('odisa') }}" readonly style="background-color: #E9ECEF">
          <input type="hidden" name="odisa" id="odisa_raw">
        </div>

        <div class="col-md-6 mt-3">
          <label for="dpper" class="form-label">Down Payment (%)</label>
          <input type="text" class="form-control price-input" id="dpper" value="{{ old('dpper') }}" readonly style="background-color: #E9ECEF">
          <input type="hidden" id="dpper_val" name="dpper" id="dpper_raw">
        </div>

        <div class="col-md-6 mt-3">
          <label for="dpamt" class="form-label">Down Payment</label>
          <input type="text" class="form-control price-input" id="dpamt" value="{{ old('dpamt') }}" readonly style="background-color: #E9ECEF">
          <input type="hidden" name="dpamt" id="dpamt_raw">
        </div>

        <div class="col-md-6 mt-3">
          <label for="ntamt" class="form-label">Net Amount</label>
          <input type="text" class="form-control price-input" id="ntamt" value="{{ old('ntamt') }}" readonly style="background-color: #E9ECEF">
          <input type="hidden" name="ntamt" id="ntamt_raw">
        </div>
        
        <div class="col-md-6 mt-3">
          <label for="txamt" class="form-label">Tax <span id="tax"></span></label>
          <input type="text" class="form-control" name="txamt" id="txamt" value="{{ old('txamt') }}" readonly style="background-color: #E9ECEF">
          <input type="hidden" name="txamt" id="txamt_raw">
          <input type="hidden" name="vatax" id="vatax">
        </div>

        <div class="col-md-6 mt-3">
          <label for="blamt" class="form-label">Bill Amount</label>
          <input type="text" class="form-control price-input" id="blamt" value="{{ old('blamt') }}" readonly style="background-color: #E9ECEF">
          <input type="hidden" name="blamt" id="blamt_raw">
        </div>

        <div class="col-md-12 mt-3">
          <label for="itext" class="form-label">Invoice Notes</label>
          <textarea class="form-control" name="itext" id="itext" rows="4">{{ old('itext') }}</textarea>
        </div>
      </div>

      <div class="Address">
        <hr>
        <h5>Address</h5>
          <div id="section-address">
            <div class="col-md-6 mt-3">
              <label class="form-label">Address Type</label>
              <div class="form-check">
                <input class="form-check-input" type="radio" name="address_type" id="addr_main" value="main" checked>
                <label class="form-check-label" for="addr_main">
                  Main Address
                </label>
              </div>

              <div class="form-check">
                <input class="form-check-input" type="radio" name="address_type" id="addr_delivery" value="delivery">
                <label class="form-check-label" for="addr_delivery">
                  Delivery Address
                </label>
              </div>
          </div>
          <div class="row">
            <input type="hidden" name="cusno" id="cusno" value="{{ old('cusno') }}">
            <input type="hidden" name="sreno" id="sreno" value="{{ old('sreno') }}">
            <input type="hidden" name="cuspo" id="cuspo" value="{{ old('cuspo') }}">
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

      <div class="detail">
        @include('fna.retail_inv_rel.retail_inv_rel_create_detail')
      </div>

      <div class="mt-3 d-flex justify-content-between">
        <a href="{{ route('retail_inv_rel.index') }}" class="btn btn-secondary">Kembali</a>
        <button type="submit" class="btn btn-primary">Simpan Data</button>
      </div>
    </form>
  </section>
</main>

@push('scripts')
    <script>
        $(document).ready(function(){
                $('.select2').select2({ width: '100%', theme: 'bootstrap-5' });

                let invdt = $('#invdt').val();
                let formc = $('#formc').val();

                if(invdt && formc){
                    generateInv(invdt, formc);
                    generatePriod(invdt);
                }

                let ocno = $('#ocno');
                ocno.prop('disabled', true);
                ocno.empty().append('<option disabled selected>Memuat data OC...</option>');

                $.get("{{ route('get-do') }}", function(data){
                    ocno.empty();

                    if(!data.length){
                        ocno.append('<option disabled selected>Tidak ada data OC</option>');
                        return;
                    }

                    ocno.append('<option value="" disabled selected>Silahkan Pilih OC</option>');

                    data.forEach(function(item){
                        ocno.append(`<option 
                        value="${item.value}"
                        data-text="${item.text}"
                        data-formc="${item.formc}"
                        data-trano="${item.value}"
                        data-ocdt="${item.ocdt}"
                        data-cusno="${item.cusno}"
                        data-cuspo="${item.cuspo}"
                        data-sreno="${item.sreno}"
                        data-shpto="${item.shpto}"
                        data-shpnm="${item.shpnm}"
                        data-contp="${item.contp}"
                        data-province="${item.province}"
                        data-kabupaten="${item.kabupaten}"
                        data-phone="${item.phone}"
                        data-address="${item.address}"
                        data-taxes="${item.taxes}"
                        data-rfc01="${item.rfc01}"
                        data-ref01="${item.ref01}"
                        data-topay="${item.topay}"
                        data-curco="${item.curco}"
                        data-crate="${item.crate}"
                        data-dpper="${item.dpper}"
                        >
                        ${item.formc} - ${item.value} (${item.cust})
                        </option>`);
                    });
                  ocno.prop('disabled', false);
                });
        });

        function generateInv(invdt, formc){
            $.get("{{ route('generate-invno-sc-retail') }}", {formc, invdt}, function(res){

                let display = formc + '-' + res;

                $('#invno').val(display);
                $('#invno_raw').val(res);
            });
        }

        function generatePriod(invdt){
            if(!invdt) return;

            let year = invdt.substring(0, 4);
            let month = invdt.substring(5, 7);

            let priod = year + month;

            $('#priod').val(priod);
        }

        $('#invdt').on('change', function(){
            let invdt = $(this).val();
            let formc = $('#formc').val();

            if(invdt && formc){
                generateInv(invdt, formc);
                generatePriod(invdt);
            }
        });

        $('#ocno').on('change', function(){
            let selected = $(this).find(':selected');
            let selectedText = $("#ocno option:selected").text();
            let parts = selectedText.split(' - ');

            $('#rfc01').val(selected.data('rfc01'));
            $('#ref01').val(selected.data('ref01'));
            $('#sorfc').val(selected.data('formc'));
            $('#sorno').val(selected.data('trano'));
            $('#refcno').val(selected.data('text'));

            $('#ocdt').val(selected.data('ocdt'));
            $('#cusno').val(selected.data('cusno'));
            $('#sreno').val(selected.data('sreno'));
            $('#cuspo').val(selected.data('cuspo'));
            $('#shpto').val(selected.data('shpto'));

            let topay = Number(selected.data('topay')) || 0;
            $('#topay').val(topay);

            let invdt = $('#invdt').val();

            if(invdt){
                let dueDate = new Date(invdt);
                dueDate.setDate(dueDate.getDate() + topay);

                let yyyy = dueDate.getFullYear();
                let mm = String(dueDate.getMonth() + 1).padStart(2, '0');
                let dd = String(dueDate.getDate()).padStart(2, '0');

                $('#invdd').val(`${yyyy}-${mm}-${dd}`);
            }

            let curco = selected.data('curco');
            let crate = selected.data('crate');
            let tax = selected.data('taxes');

            // set currency & rate (raw)
            $('#curco').val(curco);
            $('#crate_raw').val(crate);
            $('#crate_display').val(formatRupiah(crate));
            $('#tax').text(`(${tax || 0}%)`);
            $('#vatax').val(tax);
            $('#dpper').val(selected.data('dpper'));
            $('#dpper_val').val(selected.data('dpper'));

            toggleCrateField(curco, crate);

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
                $('#addr_main').prop('checked', true).trigger('change');
            });

            let refno = selected.data('ref01');
            let trano = selected.data('trano');

            // get detail by sorno
            $.get("{{ route('get-opron-by-do-sa') }}", { refno: refno, trano: trano }, function(res){

              let container = $('#table_detail');
              container.empty(); // reset dulu
              
              if(!res.length){
                  $('.section-detail').hide();
                  return;
              }

              $('.section-detail').show();

              if(!res.length){
                  container.html('<p class="text-muted">Tidak ada detail</p>');
                  return;
              }

              res.forEach((item, i) => {

                let net = (item.gross_dtl || 0) - (item.odisa_dtl || 0);

                let html = `
                <tr>
                    <td>
                        ${item.opron} - ${item.prona} (${item.lotno})
                        <input type="hidden" name="opron[]" value="${item.opron}">
                        <input type="hidden" name="prona[]" value="${item.prona}">
                        <input type="hidden" name="lotno[]" value="${item.lotno}">
                    </td>

                    <td>
                        ${item.trqty} ${item.stdqu}
                        <input type="hidden" name="rqqty[]" value="${item.trqty}">
                        <input type="hidden" name="stdqu[]" value="${item.stdqu}">
                    </td>

                    <td style="text-align: right";>
                        ${formatCurrency(item.gross_dtl, curco)}
                        <input type="hidden" name="price[]" value="${item.price}">
                        <input type="hidden" name="gross_dtl[]" value="${item.gross_dtl}">
                    </td>

                    <td style="text-align: right";>
                        ${formatCurrency(item.odisa_dtl, curco)}
                        <input type="hidden" name="odisa_dtl[]" value="${item.odisa_dtl}">
                    </td>

                    <td style="text-align: right";>
                        ${formatCurrency(net, curco)}
                    </td>
                </tr>
                              
                `;

                container.append(html);
            });
            calculateHeader();
          });
        });

        // crate change
        function toggleCrateField(curco, crate){
            const crateDisplay = document.getElementById('crate_display');

            if(curco === 'IDR'){
                crateDisplay.setAttribute('readonly', true);
                crateDisplay.style.backgroundColor = '#e9ecef';

                $('#crate_raw').val(1);
                $('#crate_display').val(formatRupiah(1));
            }else{
                crateDisplay.removeAttribute('readonly');
                crateDisplay.style.backgroundColor = '';

                if(crate){
                    $('#crate_raw').val(crate);
                    $('#crate_display').val(formatRupiah(crate));
                }
            }
        }

        // format currency from db
        function formatCurrency(value, currency) {
            if (!value) return '';

            const fraction = currency === 'IDR' ? 0 : 2;

            return new Intl.NumberFormat(
                currency === 'IDR' ? 'id-ID' : 'en-US',
                {
                    style: 'currency',
                    currency: currency,
                    minimumFractionDigits: fraction,
                    maximumFractionDigits: fraction
                }
            ).format(value);
        }

        // format rupiah only
        function formatRupiah(value) {
            if (!value) return '';

            return new Intl.NumberFormat('id-ID', {
                style: 'currency',
                currency: 'IDR',
                minimumFractionDigits: 0
            }).format(value);
        }

        const crateDisplay = document.getElementById('crate_display');
        const crateRaw = document.getElementById('crate_raw');

        crateDisplay.addEventListener('input', function(e){

            let value = e.target.value.replace(/[^\d]/g, '');

            crateRaw.value = value || '';

            e.target.value = value;
        });

        crateDisplay.addEventListener('blur', function(e){

            let value = crateRaw.value;

            if(value){
                e.target.value = formatRupiah(value);
            }
        });

        crateDisplay.addEventListener('focus', function(e){

            let value = crateRaw.value;

            if(value){
                e.target.value = value;
            }
        });

        $('input[name="address_type"]').on('change', function(){
          let type = $(this).val();

          if(type === 'delivery'){
              $('#shpto').prop('disabled', false);

              $('#shpto').trigger('change');
          }

          if(type === 'main'){
              $('#shpto').prop('disabled', true);
              $('#shpto').val('0').trigger('change.select2').prop('disabled', true);

              let cusno = $('#cusno').val();
              if(!cusno) return;

              $.get("{{ route('get-main-address') }}", { cusno }, function(res){

                  $('#shpto_name').val(res.name || '');
                  $('#shpto_attn').val(res.attn || '');
                  $('#shpto_phone').val(res.phone || '');
                  $('#shpto_address').val(res.address || '');
                  $('#shpto_prov').val('');
                  $('#shpto_kab').val('');
              });
          }
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


        function calculateHeader(){
            let gross = 0;
            let odisa = 0;

            $('input[name="price[]"]').each(function(){
                gross += Number($(this).val()) || 0;
            });

            $('input[name="odisa_dtl[]"]').each(function(){
                odisa += Number($(this).val()) || 0;
            });

            let dpper = Number($('#dpper').val()) || 0;
            let tax = Number($('#vatax').val()) || 0;

            let net = gross - odisa;
            let dpamt = net * (dpper / 100);
            let ntamt = net - dpamt;
            let txamt = ntamt * (tax / 100);
            let blamt = ntamt + txamt;

            // set ke header (display)
            $('#gross').val(formatCurrency(gross, $('#curco').val()));
            $('#odisa').val(formatCurrency(odisa, $('#curco').val()));
            $('#dpamt').val(formatCurrency(dpamt, $('#curco').val()));
            $('#ntamt').val(formatCurrency(ntamt, $('#curco').val()));
            $('#txamt').val(formatCurrency(txamt, $('#curco').val()));
            $('#blamt').val(formatCurrency(blamt, $('#curco').val()));

            // set raw (buat save)
            $('#gross_raw').val(gross);
            $('#odisa_raw').val(odisa);
            $('#dpamt_raw').val(dpamt);
            $('#ntamt_raw').val(ntamt);
            $('#txamt_raw').val(txamt);
            $('#blamt_raw').val(blamt);
        }

        // SweetAlert confirm submit
        document.addEventListener("DOMContentLoaded", function() {
            const form = document.getElementById('form-retail_inv_rel');
            form.addEventListener('submit', function (e) {
            e.preventDefault();
            if (!form.checkValidity()) { form.classList.add('was-validated'); return; }
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
