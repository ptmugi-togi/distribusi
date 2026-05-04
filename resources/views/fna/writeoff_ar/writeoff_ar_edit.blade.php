@extends('layout.main')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/global.css') }}">
@endpush

@section('container')
<main id="main" class="main">

<div class="d-flex justify-content-between align-items-center">
    <div class="pagetitle">
        <h1>Edit Write Off A/R</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item">
                    <a href="{{ route('dashboard') }}">Home</a>
                </li>
                <li class="breadcrumb-item">
                    <a href="{{ route('writeoff_ar.index') }}">
                        List Write Off A/R
                    </a>
                </li>
                <li class="breadcrumb-item active">
                    Edit Write Off A/R
                </li>
            </ol>
        </nav>
    </div>

    <div class="card">
        <h5 class="p-2">
            <b>Branch : {{ auth()->user()->cabang }}</b>
        </h5>
    </div>
</div>

<section class="section">

<form id="form-woff"
      action="{{ route('writeoff_ar.update',$woffar->woffid) }}"
      method="POST">

    @csrf
    @method('PUT')

    <input type="hidden" name="woffid" value="{{ $woffar->woffid }}">
    <input type="hidden" name="braco" value="{{ $woffar->braco }}">
    <input type="hidden" name="formc" value="{{ $woffar->formc }}">
    <input type="hidden" name="vcrno" value="{{ $woffar->vcrno }}">

    <div class="row">

        <div class="col-md-6 mt-3">
            <label class="form-label">Voucher No</label>
            <input type="text" class="form-control" readonly value="{{ $woffar->formc }} {{ $woffar->vcrno }}" style="background:#E9ECEF;">
        </div>

        <div class="col-md-6 mt-3">
            <label class="form-label">Voucher Date</label>
            <input type="date" name="pdate" class="form-control" value="{{ $woffar->tradt }}" disabled>
        </div>

        <div class="col-md-6 mt-3">
            <label class="form-label">Reference No</label>
            <input type="text" name="refno" class="form-control" value="{{ $woffar->refno }}" disabled>
        </div>

        <div class="col-md-12 mt-3">
            <label class="form-label">WO Notes</label>
            <textarea name="noteh" class="form-control" rows="4">{{ $woffar->noteh }}</textarea>
        </div>

    </div>

    <hr class="my-4">

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4>Detail Write Off A/R</h4>
    </div>

    <div class="accordion" id="accordionInvoicePayment">
        @foreach($details as $i => $row)
        <div class="accordion-item" id="row_{{ $i }}">
            <h2 class="accordion-header d-flex align-items-center">
                <button class="accordion-button {{ $i > 0 ? 'collapsed' : '' }}" type="button" data-bs-toggle="collapse" data-bs-target="#collapse{{ $i }}">
                    Invoice {{ $i + 1 }}
                </button>
            </h2>

            <div id="collapse{{ $i }}"
                 class="accordion-collapse collapse {{ $i == 0 ? 'show' : '' }}">

                <div class="accordion-body">

                    <input type="hidden" name="detail_id[]" value="{{ $row->id ?? '' }}">

                    <div class="row">

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Invoice No</label>
                            <input type="text" name="invno[]" id="invno_{{ $i }}" class="form-control" value="{{ $row->invfc }} - {{ $row->invrn }} ({{ $row->cusna }})" readonly style="background:#E9ECEF;">

                            <input type="hidden" name="formc_inv[]" value="{{ $row->invfc ?? '' }}">
                            <input type="hidden" name="invrn[]" value="{{ $row->invrn ?? '' }}">
                            <input type="hidden" name="cusno[]" id="cusno_{{ $i }}" value="{{ $row->cusno ?? '' }}">
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Customer</label>
                            <input type="text" id="cusna_{{ $i }}" class="form-control" readonly value="{{ $row->cusno }} {{ $row->cusna }}" style="background:#E9ECEF;">
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Currency</label>
                            <input type="text" id="curco_{{ $i }}" class="form-control" name="curco[]" readonly value="{{ $row->curco }}" style="background:#E9ECEF;">
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Kurs</label>
                            <input type="text" id="irate_{{ $i }}" class="form-control price-input" data-index="{{ $i }}" readonly value="{{ $row->irate }}" style="background:#E9ECEF;">
                            <input type="hidden" id="irate_raw_{{ $i }}" name="irate[]" value="{{ $row->irate }}">
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">DPP</label>
                            <input type="text" id="ntamt_{{ $i }}" class="form-control price-input" data-index="{{ $i }}" readonly value="{{ $row->curco == 'IDR' ? 'Rp ' . number_format($row->ntamt,0,',','.') : '$ ' . number_format($row->ntamt,2,'.',',') }}" style="background:#E9ECEF;">
                            <input type="hidden" id="ntamt_raw_{{ $i }}" value="{{ $row->ntamt }}">
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">PPN</label>
                            <input type="text" id="txamt_{{ $i }}" class="form-control price-input" data-index="{{ $i }}" readonly value="{{ $row->curco == 'IDR' ? 'Rp ' . number_format($row->txamt,0,',','.') : '$ ' . number_format($row->txamt,2,'.',',') }}" style="background:#E9ECEF;">
                            <input type="hidden" id="txamt_raw_{{ $i }}" value="{{ $row->txamt }}">
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Bill Amount</label>
                            <input type="text" id="blamt_{{ $i }}" class="form-control price-input" data-index="{{ $i }}" readonly value="{{ $row->curco == 'IDR' ? 'Rp ' . number_format($row->blamt,0,',','.') : '$ ' . number_format($row->blamt,2,'.',',') }}" style="background:#E9ECEF;">
                            <input type="hidden" id="blamt_raw_{{ $i }}" value="{{ $row->blamt }}">
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">A/R Value</label>
                            <input type="text" id="arval_{{ $i }}" class="form-control price-input" data-index="{{ $i }}" readonly value="{{ $row->curco == 'IDR' ? 'Rp ' . number_format($row->arval,0,',','.') : '$ ' . number_format($row->arval,2,'.',',') }}" style="background:#E9ECEF;">
                            <input type="hidden" id="arval_raw_{{ $i }}" value="{{ $row->arval }}">
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Write Off Value</label>
                            <input type="text" id="trval_{{ $i }}" class="form-control price-input" data-index="{{ $i }}" value="{{ $row->curco == 'IDR' ? 'Rp ' . number_format($row->trval,0,',','.') : '$ ' . number_format($row->trval,2,'.',',') }}">
                            <input type="hidden" name="trval[]" id="trval_raw_{{ $i }}" value="{{ $row->trval }}">
                        </div>

                        <div class="col-md-12 mb-3">
                            <label class="form-label">Detail Notes</label>
                            <textarea name="noted[]" class="form-control" rows="3">{{ $row->noted }}</textarea>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>
    <div class="text-end">
        <button type="button" class="btn" id="btn-add-row" style="background:#4456f1;color:#fff;">
            Tambah Detail
        </button>
    </div>

    <div class="mt-4 d-flex justify-content-between">
        <a href="{{ route('writeoff_ar.index') }}" class="btn btn-secondary">
            Kembali
        </a>

        <button type="submit" class="btn btn-primary">
            Update Data
        </button>
    </div>
</form>

</section>
</main>
@endsection

@push('scripts')
    <script>
        $(document).ready(function () {
            $('.select2').select2({
                width: '100%',
                theme: 'bootstrap-5'
            });

            $('.invoice-select').each(function () {
                loadInvoice($(this), $(this).val());
            });

            $('.price-input').each(function () {
                let i = $(this).data('index');
                let field = $(this).attr('id').split('_')[0];

                let raw = $('#' + field + '_raw_' + i).val() || 
                        $(this).val().replace(/[^0-9]/g, '');

                $('#' + field + '_raw_' + i).val(raw);

                let currency = $('#curco_' + i).val() || 'IDR';

                $(this).val(formatCurrency(raw, currency));
            });

            updateTotal();
        });

        function formatCurrency(value,currency='IDR')
        {
            let digit = currency === 'IDR' ? 0 : 2;

            return new Intl.NumberFormat(
                currency === 'IDR' ? 'id-ID' : 'en-US',
                {
                    style:'currency',
                    currency:currency,
                    minimumFractionDigits:digit,
                    maximumFractionDigits:digit
                }
            ).format(value || 0);
        }

        function getNextDetailIndex()
        {
            let used = [];

            $('[id^="row_"]').each(function(){
                used.push(parseInt($(this).attr('id').split('_')[1]));
            });

            let i = 0;

            while(used.includes(i)){
                i++;
            }

            return i;
        }

        function loadInvoice(el, selectedValue='')
        {
            $.get("{{ route('get-invoice') }}", function(data){

                el.empty();
                el.append(`<option value="" disabled selected>Pilih Invoice</option>`);

                data.forEach(function(item){

                    let selected = selectedValue == item.invno ? 'selected' : '';

                    el.append(`
                        <option value="${item.invno}"
                            ${selected}
                            data-cusna="${item.cusna}"
                            data-blamt="${item.blamt}"
                            data-arval="${item.arval}"
                            data-cusno="${item.cusno}"
                            data-curco="${item.curco}"
                            data-crate="${item.crate}">
                            ${item.text} (${item.cusna})
                        </option>
                    `);

                });

                el.trigger('change');

            });
        }

        $('#btn-add-row').click(function(){

            let i = getNextDetailIndex();

            let html = `
            <div class="accordion-item mb-3" id="row_${i}">
                <h2 class="accordion-header d-flex align-items-center">

                    <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapse${i}">
                        Invoice ${i + 1}
                    </button>

                    <button type="button" class="btn btn-danger btn-sm ms-2" onclick="removeRow(${i})">
                        <i class="bi bi-trash"></i>
                    </button>

                </h2>

                <div id="collapse${i}" class="accordion-collapse collapse show">
                    <div class="accordion-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label>Invoice No</label>
                                <select name="invno[]" id="invno_${i}" class="form-control select2 invoice-select" data-index="${i}">
                                </select>
                                <input type="text" name="detail_id[]" value="">
                                <input type="text" name="cusno[]" id="cusno_${i}">
                            </div>

                            <div class="col-md-6 mb-3">
                                <label>Customer</label>
                                <input type="text" id="cusna_${i}" class="form-control" readonly style="background:#E9ECEF;">
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Currency</label>
                                <input type="text" id="curco_${i}" class="form-control" name="curco[]" readonly value="{{ $row->curco }}" style="background:#E9ECEF;">
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Kurs</label>
                                <input type="text" id="irate_${i}" class="form-control price-input" name="irate[]" data-index="{{ $i }}" readonly value="{{ $row->irate }}" style="background:#E9ECEF;">
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">DPP</label>
                                <input type="text" id="ntamt_${i}" class="form-control price-input" data-index="{{ $i }}" readonly value="{{ $row->curco == 'IDR' ? 'Rp ' . number_format($row->ntamt,0,',','.') : '$ ' . number_format($row->ntamt,2,'.',',') }}" style="background:#E9ECEF;">
                                <input type="text" id="ntamt_raw_${i}" value="{{ $row->ntamt }}">
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">PPN</label>
                                <input type="text" id="txamt_${i}" class="form-control price-input" data-index="{{ $i }}" readonly value="{{ $row->curco == 'IDR' ? 'Rp ' . number_format($row->txamt,0,',','.') : '$ ' . number_format($row->txamt,2,'.',',') }}" style="background:#E9ECEF;">
                                <input type="text" id="txamt_raw_${i}" value="{{ $row->txamt }}">
                            </div>

                            <div class="col-md-6 mb-3">
                                <label>Bill Amount</label>
                                <input type="text" id="blamt_${i}" class="form-control" readonly style="background:#E9ECEF;">
                            </div>

                            <div class="col-md-6 mb-3">
                                <label>A/R Value</label>
                                <input type="text" id="arval_${i}" class="form-control" readonly style="background:#E9ECEF;">
                                <input type="text" id="arval_raw_${i}">
                            </div>

                            <div class="col-md-6 mb-3">
                                <label>Write Off</label>
                                <input type="text" id="pcwo_${i}" class="form-control price-input" data-index="${i}">
                                <input type="text" name="pcwo[]" id="pcwo_raw_${i}">
                            </div>

                            <div class="col-md-12 mb-3">
                                <label>Detail Notes</label>
                                <textarea name="noted[]" class="form-control" rows="3"></textarea>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            `;

            $('#accordionInvoicePayment').append(html);

            $('#invno_' + i).select2({
                width:'100%',
                theme:'bootstrap-5'
            });

            loadInvoice($('#invno_' + i));
        });

        function removeRow(i)
        {
            $('#row_' + i).remove();
            updateTotal();
        }

        $(document).on('change','.invoice-select',function(){

            let i = $(this).data('index');
            let x = $(this).find(':selected');

            $('#cusna_' + i).val(x.data('cusna'));
            $('#blamt_' + i).val(formatCurrency(x.data('blamt')));
            $('#arval_' + i).val(formatCurrency(x.data('arval')));
            $('#arval_raw_' + i).val(x.data('arval'));
            $('#cusno_' + i).val(x.data('cusno'));
            $('#curco').val(x.data('curco'));

        });

        $(document).on('input','.price-input',function(){

            let i = $(this).data('index');
            let field = $(this).attr('id').split('_')[0];

            let val = $(this).val().replace(/[^0-9]/g,'');

            $(this).val(val);
            $('#' + field + '_raw_' + i).val(val);

            updateTotal();
        });

        $(document).on('blur','.price-input',function(){

            let i = $(this).data('index');
            let field = $(this).attr('id').split('_')[0];

            let raw = $('#' + field + '_raw_' + i).val();

            $(this).val(formatCurrency(raw));
        });

        function updateTotal()
        {
            let total = 0;

            $('input[id^="pcwo_raw_"]').each(function(){
                total += parseFloat($(this).val()) || 0;
            });

            $('#total_raw').val(total);
            $('#total').val(formatCurrency(total));
        }

        $('#form-woff').submit(function(e){

            e.preventDefault();

            let form = this;

            Swal.fire({
                title:'Konfirmasi Ubah',
                text: 'Apakah Anda yakin ingin mengubah data ini?',
                icon:'question',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, Ubah Data!',
                cancelButtonText: 'Batal'
            }).then((res)=>{

                if(res.isConfirmed){
                    form.submit();
                }

            });

        });
    </script>
@endpush