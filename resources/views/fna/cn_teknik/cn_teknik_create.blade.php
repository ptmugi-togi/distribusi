@extends('layout.main')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/global.css') }}">
@endpush

@section('container')
<main id="main" class="main">
  <div class="d-flex justify-content-between align-items-center">
    <div class="pagetitle">
      <h1>Tambah Data CN Teknik</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
          <li class="breadcrumb-item"><a href="{{ route('cn_teknik.index') }}">List CN Teknik</a></li>
          <li class="breadcrumb-item active">CN Teknik Create</li>
        </ol>
      </nav>
    </div>
    <div class="card">
      <h5 class="p-2"><b>Branch : {{ auth()->user()->cabang }}</b></h5>
    </div>
  </div>

  <section class="section">
    <form id="form-cn-teknik" action="{{ route('cn_teknik.store') }}" method="POST">
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
          <label for="listsd" class="form-label">EX Invoice No.</label><span class="text-danger"> *</span>
          <select name="listsd" id="listsd" class="form-control select2" required>
            <option value="" disabled selected>Loading data EX Invoice...</option>
          </select>
          <input type="hidden" name="invfc" id="invfc">
          <input type="hidden" name="invno" id="invno">
        </div>
        
        <div class="col-md-6 mt-3">
          <label for="cusno" class="form-label">Customer</label>
          <input type="text" class="form-control" name="cust" id="cust" value="{{ old('cust') }}" readonly style="background-color: #E9ECEF">
          <input type="hidden" class="form-control" name="cusno" id="cusno" value="{{ old('cusno') }}" readonly style="background-color: #E9ECEF">
        </div>

        <div class="col-md-6 mt-3">
          <label for="curco" class="form-label">Currency</label>
          <input type="text" class="form-control" name="curco" id="curco" value="{{ old('curco') }}" readonly style="background-color: #E9ECEF">
        </div>

        <div class="col-md-6 mt-3">
          <label for="crate" class="form-label">Kurs</label>
          <input type="text" class="form-control" name="crate_display" id="crate_display" value="{{ old('crate_display') }}" readonly style="background-color: #E9ECEF">
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
        @include('fna.cn_teknik.cn_teknik_create_detail')
      </div>

      <div class="mt-3 d-flex justify-content-between">
        <a href="{{ route('cn_teknik.index') }}" class="btn btn-secondary">Kembali</a>
        <button type="submit" class="btn btn-primary">Simpan Data</button>
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

                let listsd = $('#listsd');
                listsd.prop('disabled', true);
                listsd.empty().append('<option disabled selected>Memuat data Invoice...</option>');

                $.get("{{ route('get-listsd-cn-teknik') }}", function(data){
                    listsd.empty();

                    if(!data.length){
                        listsd.append('<option disabled selected>Tidak ada data Invoice</option>');
                        return;
                    }

                    listsd.append('<option value="" disabled selected>Silahkan Pilih Invoice</option>');

                    data.forEach(function(item){
                        listsd.append(`
                          <option
                              value="${item.invno}"
                              data-invfc="${item.formc}"
                              data-cusno="${item.cusno}"
                              data-customer="${item.customer}"
                              data-curco="${item.curco}"
                              data-crate="${item.crate}"
                              data-gross="${item.gramt}"
                              data-odisa="${item.odisa}"
                              data-dpper="${item.dpper}"
                              data-dpamt="${item.dpamt}"
                              data-ntamt="${item.ntamt}"
                              data-txamt="${item.txamt}"
                              data-cramt="${item.blamt}"
                              data-vatax="${item.vatax}"
                              data-ortyp="${item.ortyp}"
                          >
                          SD - ${item.invno}
                          </option>
                        `);
                    });
                listsd.prop('disabled', false);
                });
            });
            
            $('#listsd').on('change', function(){
              let selected = $(this).find(':selected');

              $('#cust').val(selected.data('cusno') + ' - ' + selected.data('customer'));
              $('#cusno').val(selected.data('cusno'));
              $('#invfc').val(selected.data('invfc'));
              $('#invno').val(selected.val());

              $('#curco').val(selected.data('curco'));

              toggleCrateField(
                  selected.data('curco'),
                  selected.data('crate')
              );

              let curco = selected.data('curco');

              $('#gross').val(formatCurrency(selected.data('gross'), selected.data('curco')));
              $('#gross_raw').val(selected.data('gross'));

              $('#odisa').val(formatCurrency(selected.data('odisa'), selected.data('curco')));
              $('#odisa_raw').val(selected.data('odisa'));

              $('#dpper').val(selected.data('dpper'));
              $('#dpper_raw').val(selected.data('dpper'));

              $('#dpamt').val(formatCurrency(selected.data('dpamt'), selected.data('curco')));
              $('#dpamt_raw').val(selected.data('dpamt'));

              $('#ntamt').val(formatCurrency(selected.data('ntamt'), selected.data('curco')));
              $('#ntamt_raw').val(selected.data('ntamt'));

              $('#txamt').val(formatCurrency(selected.data('txamt'), selected.data('curco')));
              $('#txamt_raw').val(selected.data('txamt'));

              $('#cramt').val(formatCurrency(selected.data('cramt'), selected.data('curco')));
              $('#cramt_raw').val(selected.data('cramt'));

              $('#vatax').val(selected.data('vatax'));
              $('#ortyp').val(selected.data('ortyp'));

              $('#tax').text('(' + selected.data('vatax') + '%)');

              // ambil detail
              $.get("{{ route('get-detail-by-sd-cn-teknik') }}", {
                  sorno: $('#invno').val()
              }, function(res){
                let tableBarang = $('#table_barang');
                let tableService = $('#table_service');
                let tableSparepart = $('#table_sparepart');

                tableBarang.empty();
                tableService.empty();
                tableSparepart.empty();

                $('#section_barang').hide();
                $('#section_service').hide();
                $('#section_sparepart').hide();
                $('#no_detail').hide();

                const hasBarang = res.barang.length > 0;
                const hasService = res.service.length > 0;
                const hasSparepart = res.sparepart.length > 0;

                $('.section-detail').show();

                if (!hasBarang && !hasService && !hasSparepart) {
                    $('#no_detail').show();
                    return;
                }

                if (hasBarang) {
                  $('#section_barang').show();
                  res.barang.forEach(function(item){
                      tableBarang.append(`
                          <tr>
                              <td>
                                  ${item.opron} - ${item.prona}
                                  <input type="hidden" name="tdna_dnlin[]" value="${item.invln}">
                                  <input type="hidden" name="tdna_opron[]" value="${item.opron}">
                                  <input type="hidden" name="tdna_tofee[]" value="${item.tofee}">
                                  <input type="hidden" name="tdna_descr[]" value="${item.descr}">
                              </td>

                              <td>
                                  ${item.trqty} ${item.stdqu}
                                  <input type="hidden" name="tdna_trqty[]" value="${item.trqty}">
                              </td>

                              <td>
                                  ${item.lotno ?? '-'}
                                  <input type="hidden" name="tdna_lotno[]" value="${item.lotno ?? ''}">
                              </td>

                              <input type="hidden" name="tdna_gramt[]" value="${item.gramt}">
                              <input type="hidden" name="tdna_odisa[]" value="${item.odisa}">
                              <input type="hidden" name="tdna_odisp[]" value="${item.odisp}">
                              <input type="hidden" name="tdna_ntamt[]" value="${item.netbe}">
                          </tr>
                      `);
                  });
                }

                if (hasService) {
                  $('#section_service').show();
                  res.service.forEach(function(item){
                      tableService.append(`
                          <tr>
                              <td>
                                  ${item.tofee}
                                  <input type="hidden" name="tdnb_dnlin[]" value="${item.invln}">
                                  <input type="hidden" name="tdnb_serty[]" value="${item.serty}">
                                  <input type="hidden" name="tdnb_tofee[]" value="${item.tofee}">
                              </td>

                              <td>
                                  ${formatCurrency(item.gramt, curco)}
                                  <input type="hidden" name="tdnb_gramt[]" value="${item.gramt}">
                              </td>

                              <td class="text-end">
                                  ${formatCurrency(item.odisa, curco)}
                                  <input type="hidden" name="tdnb_odisa[]" value="${item.odisa}">
                                  <input type="hidden" name="tdnb_odisp[]" value="${item.odisp}">
                              </td>

                              <td class="text-end">
                                  ${formatCurrency(item.netbe, curco)}
                                  <input type="hidden" name="tdnb_ntamt[]" value="${item.netbe}">
                              </td>
                          </tr>
                      `);
                  });
                }

                if (hasSparepart) {
                  $('#section_sparepart').show();
                  res.sparepart.forEach(function(item){
                      tableSparepart.append(`
                          <tr>
                              <td>
                                  ${item.opron} - ${item.prona}
                                  <input type="hidden" name="tdnc_opron[]" value="${item.opron}">
                              </td>
  
                              <td>
                                  ${item.trqty} ${item.stdqu}
                                  <input type="hidden" name="tdnc_trqty[]" value="${item.trqty}">
                              </td>
  
                              <td>
                                  ${item.lotno ?? '-'}
                                  <input type="hidden" name="tdnc_lotno[]" value="${item.lotno ?? ''}">
                              </td>
  
                              <td class="text-end">
                                  ${formatCurrency(item.price, curco)}
                                  <input type="hidden" name="tdnc_price[]" value="${item.price}">
                              </td>
  
                              <td class="text-end">
                                  ${formatCurrency(item.odisa, curco)}
                                  <input type="hidden" name="tdnc_odisa[]" value="${item.odisa}">
                                  <input type="hidden" name="tdnc_odisp[]" value="${item.odisp}">
                              </td>
  
                              <td class="text-end">
                                  ${formatCurrency(item.netbe, curco)}
                                  <input type="hidden" name="tdnc_gramt[]" value="${item.gramt}">
                                  <input type="hidden" name="tdnc_ntamt[]" value="${item.netbe}">
                              </td>
                          </tr>
                      `);
                  });
                }
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
                const form = document.getElementById('form-cn-teknik');
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
