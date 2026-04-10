@extends('layout.main')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/global.css') }}">
@endpush

@section('container')
<main id="main" class="main">
  <div class="d-flex justify-content-between align-items-center">
    <div class="pagetitle">
      <h1>Tambah Data Project Invoice Release</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
          <li class="breadcrumb-item"><a href="{{ route('project_inv_rel.index') }}">List Project Invoice Release</a></li>
          <li class="breadcrumb-item active">Project Invoice Release Create</li>
        </ol>
      </nav>
    </div>
    <div class="card">
      <h5 class="p-2"><b>Branch : {{ auth()->user()->cabang }}</b></h5>
    </div>
  </div>

  <section class="section">
    <form id="form-project_inv_rel" action="{{ route('project_inv_rel.store') }}" method="POST">
      @csrf

      {{-- Global header (muncul dari awal) --}}
      <input type="text" name="braco" id="braco" value="{{ auth()->user()->cabang }}" hidden>
      <input type="text" class="form-control" id="formc" name="formc" id="formc-store" value="SC" hidden>

      <div class="row">
        <div class="col-md-6 mt-3">
          <label for="invno" class="form-label">Invoice No.</label><span class="text-danger"> *</span>
          <input type="text" id="invno" class="form-control" readonly style="background-color: #E9ECEF;">
          <input type="hidden" name="invno" id="invno_raw" value="{{ old('invno') }}">
        </div>

        <input type="text" name="priod" id="priod" value="{{ old('priod') }}" hidden>

        <div class="col-md-6 mt-3">
          <label for="invdt" class="form-label">Invoice Date</label><span class="text-danger"> *</span>
          <input type="date" class="form-control" name="invdt" id="invdt" value="{{ old('invdt', date('Y-m-d')) }}" required min="{{ $minDate }}">
        </div>

        <div class="col-md-6 mt-3">
          <label for="invdd" class="form-label">Invoice Due Date</label><span class="text-danger"> *</span>
          <input type="date" class="form-control" name="invdd" id="invdd" value="{{ old('invdd', date('Y-m-d')) }}" required min="{{ $minDate }}">
        </div>

        <input type="text" name="topay" id="topay" value="{{ old('topay') }}" hidden>

        <div class="col-md-6 mt-3">
          <label for="ocno" class="form-label">OC No.</label><span class="text-danger"> *</span>
          <select name="ocno" id="ocno" class="form-control select2" required>
            <option value="" disabled selected>Loading data OC...</option>
          </select>
          <input type="text" id="sorfc" name="sorfc" value="{{ old('sorfc') }}" hidden>
          <input type="text" id="sorno" name="sorno" value="{{ old('sorno') }}" hidden>
        </div>

        <div class="col-md-6 mt-3">
          <label for="ocdt" class="form-label">OC Date</label>
          <input type="text" class="form-control" name="ocdt" id="ocdt" value="{{ old('ocdt') }}" readonly style="background-color: #E9ECEF">
        </div>
        
        <div class="col-md-6 mt-3">
          <label for="phase" class="form-label">Phase</label><span class="text-danger"> *</span>
          <select name="phase" id="phase" class="form-control select2" required>
            <option value="" disabled selected>Silahkan Pilih OC No Terlebih Dahulu...</option>
          </select>
          <input type="hidden" name="toppc" id="toppc">
        </div>

        <div class="col-md-6 mt-3">
          <label for="descr" class="form-label">Deskripsi Termin</label>
          <input type="text" class="form-control" name="descr" id="descr" value="{{ old('descr') }}" readonly style="background-color: #E9ECEF">
        </div>

        <div class="col-md-6 mt-3">
          <label for="curco" class="form-label">Currency</label>
          <input type="text" class="form-control" name="curco" id="curco" value="{{ old('curco') }}" readonly style="background-color: #E9ECEF">
        </div>

        <div class="col-md-6 mt-3">
          <label for="crate" class="form-label">Kurs</label>
          <input type="text" class="form-control" name="crate_display" id="crate_display" value="{{ old('crate_display') }}">
          <input type="text" class="form-control" name="crate" id="crate_raw" value="{{ old('crate') }}" hidden>
        </div>
                
        <div class="col-md-6 mt-3">
          <label for="gross" class="form-label">Gross Amount</label>
          <input type="text" class="form-control price-input" id="gross" value="{{ old('gross') }}" readonly style="background-color: #E9ECEF">
          <input type="hidden" name="gross" id="gross_raw">
        </div>
                
        <div class="col-md-6 mt-3">
          <label for="odisa" class="form-label">Discount</label>
          <input type="text" class="form-control price-input" id="odisa" value="{{ old('odisa') }}" readonly style="background-color: #E9ECEF">
          <input type="hidden" name="odisa" id="odisa_raw">
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
          <label for="noted" class="form-label">Invoice Notes</label>
          <textarea class="form-control" name="noted" id="noted" rows="4">{{ old('noted') }}</textarea>
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
            <input type="text" name="cusno" id="cusno" value="{{ old('cusno') }}" hidden>
            <input type="text" name="sreno" id="sreno" value="{{ old('sreno') }}" hidden>
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
      
      <div class="detail">
        @include('fna.project_inv_rel.project_inv_rel_create_detail')
      </div>

      <div class="mt-3 d-flex justify-content-between">
        <a href="{{ route('project_inv_rel.index') }}" class="btn btn-secondary">Kembali</a>
        <button type="submit" class="btn btn-primary">Simpan Data</button>
      </div>
    </form>
  </section>
</main>

    @push('scripts')
        {{-- ambil priod dari yyyymm invdt --}}
        <script>
          document.getElementById('invdt').addEventListener('change', function () {
              let tanggal = this.value;

              if (tanggal) {
                  let year = tanggal.substring(0, 4);
                  let month = tanggal.substring(5, 7);

                  let priod = year + month;
                  document.getElementById('priod').value = priod;
              }
          });
        </script>
        
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

                    $.get("{{ route('get-oc-sb') }}", function(data){
                        ocno.empty();

                        if(!data.length){
                            ocno.append('<option disabled selected>Tidak ada data OC</option>');
                            return;
                        }

                        ocno.append('<option value="" disabled selected>Silahkan Pilih OC</option>');

                        data.forEach(function(item){
                            ocno.append(`<option 
                            value="${item.value}"
                            data-ocdt="${item.ocdt}"
                            data-cusno="${item.cusno}"
                            data-sreno="${item.sreno}"
                            data-cuspo="${item.cuspo}"
                            data-shpto="${item.shpto}"
                            data-shpnm="${item.shpnm}"
                            data-contp="${item.contp}"
                            data-province="${item.province}"
                            data-kabupaten="${item.kabupaten}"
                            data-phone="${item.phone}"
                            data-address="${item.address}"
                            data-curco="${item.curco}"
                            data-crate="${item.crate}"
                            >
                            ${item.text} (${item.cust})
                            </option>`);
                        });
                      ocno.prop('disabled', false);
                    });
            });

            // generate invno
            function generateInv(invdt, formc){
                $.get("{{ route('generate-invno-sc-project') }}", {formc, invdt}, function(res){

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

                let type = parts[0] || '';
                let sorno = parts[1] ? parts[1].split('(')[0].trim() : '';

                $('#sorfc').val(type);
                $('#sorno').val(sorno);

                $('#ocdt').val(selected.data('ocdt'));
                $('#topay').val(selected.data('topay'));
                $('#cusno').val(selected.data('cusno'));
                $('#sreno').val(selected.data('sreno'));
                $('#cuspo').val(selected.data('cuspo'));
                $('#shpto').val(selected.data('shpto'));

                let curco = selected.data('curco');
                let crate = selected.data('crate');

                // set currency & rate (raw)
                $('#curco').val(curco);
                $('#crate_raw').val(crate);
                $('#crate_display').val(formatRupiah(crate));

                toggleCrateField(curco, crate);

                let $phase = $('#phase');
                $phase.prop('disabled', true);
                $phase.empty().append('<option disabled selected>Loading phase...</option>').trigger('change');

                $.get("{{ route('get-phase-by-oc') }}", { sorno: selected.val() }, function(res){
                    $phase.empty();

                    if(!res.length){
                        $phase.append('<option disabled selected>Tidak ada data termin</option>');
                        return;
                    }

                    $phase.append('<option value="" disabled selected>Silahkan Pilih Phase</option>');

                    res.forEach(item => {
                        $phase.append(`
                            <option value="${item.phase}"
                                data-toppc="${item.toppc}"
                                data-descr="${item.descr}"
                                data-gross="${item.gross}"
                                data-odisa="${item.odisa}"
                                data-ntamt="${item.ntamt}"
                                data-taxes="${item.taxes}"
                                data-txamt="${item.txamt}"
                                data-blamt="${item.blamt}"
                                data-noted="${item.noted}"
                            >
                                Phase ${item.phase} (${item.toppc}%)
                            </option>
                        `);
                    });

                    $phase.prop('disabled', false);
                })

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
            });

            $('#phase').on('change', function(){
                let selected = $(this).find(':selected');

                let sorno = $('#sorno').val();
                let toppc = selected.data('toppc');
                let descr = selected.data('descr');
                let gross = selected.data('gross');
                let odisa = selected.data('odisa');
                let ntamt = selected.data('ntamt');
                let tax = selected.data('taxes');
                let txamt = selected.data('txamt');
                let blamt = selected.data('blamt');
                let noted = selected.data('noted');

                let curco = $('#curco').val();

                $('#toppc').val(toppc);
                $('#descr').val(descr);
                $('#tax').text(`(${tax || 0}%)`);
                $('#vatax').val(tax);

                $('#gross').val(formatCurrency(gross, curco));
                $('#gross_raw').val(gross);
                
                $('#odisa').val(formatCurrency(odisa, curco));
                $('#odisa_raw').val(odisa);

                $('#ntamt').val(formatCurrency(ntamt, curco));
                $('#ntamt_raw').val(ntamt);

                $('#txamt').val(formatCurrency(txamt, curco));
                $('#txamt_raw').val(txamt);

                $('#blamt').val(formatCurrency(blamt, curco));
                $('#blamt_raw').val(blamt);

                $('#noted').val(noted);
                
                // get detail by sorno
                $.get("{{ route('get-opron-by-oc-sb') }}", { sorno: sorno, toppc:toppc }, function(res){

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

                      let net = (item.price || 0) - (item.odisa || 0);

                      let html = `
                      <tr>
                          <td>
                              ${item.opron} - ${item.prona}
                              <input type="hidden" name="opron[]" value="${item.opron}">
                              <input type="hidden" name="prona[]" value="${item.prona}">
                          </td>

                          <td>
                              ${item.qtyor} ${item.stdqu}
                              <input type="hidden" name="rqqty[]" value="${item.qtyor}">
                              <input type="hidden" name="stdqu[]" value="${item.stdqu}">
                          </td>

                          <td style="text-align: right";>
                              ${formatCurrency(item.price, curco)}
                              <input type="hidden" name="price[]" value="${item.price}">
                          </td>

                          <td style="text-align: right";>
                              ${formatCurrency(item.odisa, curco)}
                              <input type="hidden" name="odisa_dtl[]" value="${item.odisa}">
                          </td>

                          <td style="text-align: right";>
                              ${formatCurrency(net, curco)}
                          </td>
                      </tr>
                      `;

                      container.append(html);
                  });
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

            // SweetAlert confirm submit
            document.addEventListener("DOMContentLoaded", function() {
                const form = document.getElementById('form-project_inv_rel');
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
