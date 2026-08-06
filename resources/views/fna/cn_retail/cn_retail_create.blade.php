@extends('layout.main')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/global.css') }}">
@endpush

@section('container')
<main id="main" class="main">
  <div class="d-flex justify-content-between align-items-center">
    <div class="pagetitle">
      <h1>Tambah Data CN Retail</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
          <li class="breadcrumb-item"><a href="{{ route('cn_retail.index') }}">List CN Retail</a></li>
          <li class="breadcrumb-item active">CN Retail Create</li>
        </ol>
      </nav>
    </div>
    <div class="card">
      <h5 class="p-2"><b>Branch : {{ auth()->user()->cabang }}</b></h5>
    </div>
  </div>

  <section class="section">
    <form id="form-cn-retail" action="{{ route('cn_retail.store') }}" method="POST">
      @csrf

      <input type="text" name="braco" id="braco" value="{{ auth()->user()->cabang }}" hidden>
      <input type="text" class="form-control" id="formc" name="formc" id="formc-store" value="CN" hidden>

      <div class="row">
        <input type="text" name="priod" id="priod" value="{{ old('priod') }}" hidden>

        <div class="col-md-6 mt-3">
          <label for="crndt" class="form-label">Credit Note Date</label><span class="text-danger"> *</span>
          <input type="date" class="form-control" name="crndt" id="crndt" value="{{ old('crndt') }}" required min="{{ $minDate }}">
        </div>

        <div class="col-md-6 mt-3">
          <label for="listic" class="form-label">Stock Receipt No.</label><span class="text-danger"> *</span>
          <select name="listic" id="listic" class="form-control select2" required>
            <option value="" disabled selected>Loading data Stock Receipt...</option>
          </select>
          <input type="hidden" id="icfc" name="icfc" value="IC">
          <input type="hidden" id="icno" name="icno" value="{{ old('icno') }}">
          <input type="hidden" id="dorfc" name="dorfc" value="{{ old('dorfc') }}">
          <input type="hidden" id="donom" name="donom" value="{{ old('donom') }}">
        </div>
        
        <div class="col-md-6 mt-3">
          <label for="cusno" class="form-label">Customer</label>
          <input type="text" class="form-control" name="cust" id="cust" value="{{ old('cust') }}" readonly style="background-color: #E9ECEF">
          <input type="hidden" class="form-control" name="cusno" id="cusno" value="{{ old('cusno') }}" readonly style="background-color: #E9ECEF">
        </div>

        <div class="col-md-6 mt-3">
          <label for="invno" class="form-label">Invoice No.</label><span class="text-danger"> *</span>
          <input type="text" class="form-control" name="invno" id="invno" value="{{ old('invno') }}" readonly style="background-color: #E9ECEF">
          <input type="hidden" id="sorfc" name="sorfc" value="{{ old('sorfc') }}">
          <input type="hidden" id="sorno" name="sorno" value="{{ old('sorno') }}">
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
          <input type="hidden" name="gross_hdr" id="gross_raw">
        </div>
                        
        <div class="col-md-6 mt-3">
          <label for="odisa" class="form-label">Off Discount</label>
          <input type="text" class="form-control price-input" id="odisa" value="{{ old('odisa') }}" readonly style="background-color: #E9ECEF">
          <input type="hidden" name="odisa_hdr" id="odisa_raw">
        </div>
        
        <div class="col-md-6 mt-3">
          <label for="dpper" class="form-label">Down Payment (%)</label>
          <input type="text" class="form-control price-input" id="dpper" value="{{ old('dpper') }}" readonly style="background-color: #E9ECEF">
          <input type="hidden" name="dpper_hdr" id="dpper_raw">
        </div>

        <div class="col-md-6 mt-3">
          <label for="dpamt" class="form-label">Down Payment</label>
          <input type="text" class="form-control price-input" id="dpamt" value="{{ old('dpamt') }}" readonly style="background-color: #E9ECEF">
          <input type="hidden" name="dpamt_hdr" id="dpamt_raw">
        </div>

        <div class="col-md-6 mt-3">
          <label for="ntamt" class="form-label">Net Amount</label>
          <input type="text" class="form-control price-input" id="ntamt" value="{{ old('ntamt') }}" readonly style="background-color: #E9ECEF">
          <input type="hidden" name="ntamt_hdr" id="ntamt_raw">
        </div>
        
        <div class="col-md-6 mt-3">
          <label for="txamt" class="form-label">Tax <span id="tax"></span></label>
          <input type="text" class="form-control" name="txamt" id="txamt" value="{{ old('txamt') }}" readonly style="background-color: #E9ECEF">
          <input type="hidden" name="txamt_hdr" id="txamt_raw">
          <input type="hidden" name="vatax" id="vatax">
        </div>

        <div class="col-md-6 mt-3">
          <label for="cramt" class="form-label">CN Amount</label>
          <input type="text" class="form-control price-input" id="cramt" value="{{ old('cramt') }}" readonly style="background-color: #E9ECEF">
          <input type="hidden" name="cramt_hdr" id="cramt_raw">
        </div>
        
        <div class="col-md-6 mt-3">
          <label for="notar" class="form-label">Nota Retur</label>
          <input type="text" class="form-control" name="notar" id="notar" value="{{ old('notar') }}">
        </div>
        
        <div class="col-md-12 mt-3">
          <label for="reaso" class="form-label">Notes</label>
          <textarea type="text" class="form-control" name="reaso" id="reaso" rows="3">{{ old('reaso') }}</textarea>
        </div>
      </div>

      <div class="detail">
        @include('fna.cn_retail.cn_retail_create_detail')
      </div>

      <div class="mt-3 d-flex justify-content-between">
        <a href="{{ route('cn_retail.index') }}" class="btn btn-secondary">Kembali</a>
        <button id="btn-submit" type="submit" class="btn btn-primary">Simpan Data</button>
      </div>
    </form>
  </section>
</main>

    @push('scripts')
        {{-- ambil priod dari yyyymm crndt --}}
        <script>
          document.getElementById('crndt').addEventListener('change', function () {
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

                let listic = $('#listic');
                listic.prop('disabled', true);
                listic.empty().append('<option disabled selected>Memuat data IC...</option>');

                $.get("{{ route('get-listic-cn-retail') }}", function(data){
                    listic.empty();

                    if(!data.length){
                        listic.append('<option disabled selected>Tidak ada data IC</option>');
                        return;
                    }

                    listic.append('<option value="" disabled selected>Silahkan Pilih IC</option>');

                    data.forEach(function(item){
                        listic.append(`<option 
                        value="${item.value}"
                        data-trano="${item.trano}"
                        data-reffc="${item.reffc}"
                        data-refno="${item.refno}"
                        data-customer="${item.customer}"
                        data-cusno="${item.cusno}"
                        >
                        ${item.formc} - ${item.trano}
                        </option>`);
                    });
                listic.prop('disabled', false);
                });
            });

            $('#listic').on('change', function() {
                const selected = $(this).find(':selected');

                const customer = selected.data('customer');
                const cusno = selected.data('cusno');
                const icno = selected.data('trano');
                const dorfc = selected.data('reffc');
                const donom = selected.data('refno');

                $('#cust').val(customer);
                $('#cusno').val(cusno);
                $('#icno').val(icno);
                $('#dorfc').val(dorfc);
                $('#donom').val(donom);

                $.get("{{ route('get-listsc-cn-retail') }}", { dorfc, donom }, function(res){
                    if(!res.length){
                        $('#invno').val('');
                        return;
                    }

                    const item = res[0];

                    $('#invno').val(`${item.formc} - ${item.invno}`);

                    $('#sorfc').val(item.formc);
                    $('#sorno').val(item.invno);

                    let curco = item.curco;
                    let crate = item.crate;
                    let gross = item.gramt;
                    let odisa = item.odisa;
                    let dpamt = item.dpamt;
                    let ntamt = item.ntamt;
                    let tax   = item.vatax;
                    let txamt = item.txamt;
                    let dpper = item.dpper;
                    let blamt = item.blamt;

                    $('#curco').val(curco);
                    $('#crate_raw').val(crate);
                    $('#crate_display').val(formatRupiah(crate));
                    $('#tax').text(`(${tax || 0}%)`);
                    $('#vatax').val(tax);

                    toggleCrateField(curco, crate);

                    $('#gross').val(formatCurrency(gross, curco));
                    $('#gross_raw').val(gross);

                    $('#odisa').val(formatCurrency(odisa, curco));
                    $('#odisa_raw').val(odisa);

                    $('#dpper').val(dpper + ' %');
                    $('#dpper_raw').val(dpper);

                    $('#dpamt').val(formatCurrency(dpamt, curco));
                    $('#dpamt_raw').val(dpamt);

                    $('#ntamt').val(formatCurrency(ntamt, curco));
                    $('#ntamt_raw').val(ntamt);

                    $('#txamt').val(formatCurrency(txamt, curco));
                    $('#txamt_raw').val(txamt);

                    $('#cramt').val(formatCurrency(blamt, curco));
                    $('#cramt_raw').val(blamt);

                    $.get("{{ route('check-invoice-cn-retail') }}", {
                        sorno: $('#sorno').val()
                    }, function(res){

                        if (res.is_paid) {
                            $('#btn-submit').prop('disabled', true).text('Invoice Sudah Dibayar');

                            $('#table_detail').empty();
                            $('.section-detail').hide();

                            Swal.fire({
                                icon: 'warning',
                                title: 'Invoice Sudah Dibayar',
                                text: 'Invoice ini telah dibayar sehingga tidak dapat dibuat Credit Note.',
                                confirmButtonText: 'OK'
                            });

                            return;
                        }
                        $('#btn-submit').prop('disabled', false).text('Simpan Data');

                        // get detail by sc
                        $.get("{{ route('get-detail-by-sc-cn-retail') }}", { sorno: item.invno }, function(res){
                            let container = $('#table_detail');
                            container.empty();
                            
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
                                let html = `
                                <tr>
                                    <td>
                                        ${item.opron} - ${item.prona}
                                        <input type="hidden" name="opron[]" value="${item.opron}">
                                        <input type="hidden" name="prona[]" value="${item.prona}">
                                    </td>
    
                                    <td>
                                        ${item.qtyin} ${item.stdqu}
                                        <input type="hidden" name="qtycn[]" value="${item.qtyin}">
                                        <input type="hidden" name="stdqu[]" value="${item.stdqu}">
                                    </td>
    
                                    <td style="text-align: right";>
                                        ${formatCurrency(item.price, curco)}
                                        <input type="hidden" name="price_dtl[]" value="${item.price}">
                                        <input type="hidden" name="gramt_dtl[]" value="${item.gramt}">
                                    </td>
    
                                    <td style="text-align: right";>
                                        ${formatCurrency(item.odisa, curco)}
                                        <input type="hidden" name="odisa_dtl[]" value="${item.odisa}">
                                    </td>
    
                                    <td style="text-align: right";>
                                        ${formatCurrency(item.dpamt, curco)}
                                        <input type="hidden" name="dpamt_dtl[]" value="${item.dpamt}">
                                    </td>
                                        
                                    <td style="text-align: right";>
                                        ${formatCurrency(item.netbe, curco)}
                                        <input type="hidden" name="ntamt_dtl[]" value="${item.netbe}">
                                    </td>
    
                                    <td>
                                        <input type="text" class="form-control" name="noted[]">
                                    </td>
                                </tr>
                                `;
    
                                container.append(html);
                            });
                        });
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

            // SweetAlert confirm submit
            document.addEventListener("DOMContentLoaded", function() {
                const form = document.getElementById('form-cn-retail');
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
