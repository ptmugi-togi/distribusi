@extends('layout.main')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/global.css') }}">
@endpush

@section('container')
<main id="main" class="main">

    <div class="d-flex justify-content-between align-items-center">
        <div class="pagetitle">
            <h1>Detail Invoice Payment</h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a href="{{ route('dashboard') }}">Home</a>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="{{ route('invoice_payment.index') }}">List Invoice Payment</a>
                    </li>
                    <li class="breadcrumb-item active">
                        View Detail
                    </li>
                </ol>
            </nav>
        </div>

        <div class="card">
            <h5 class="p-2">
                <b>Branch : {{ $invp->braco }}</b>
            </h5>
        </div>
    </div>

    <section class="section">

        {{-- HEADER --}}
        <div class="card">
            <div class="card-body pt-4">
                <div class="row">
                    <div class="col-md-6 mt-2">
                        <label class="form-label">Voucher No.</label>
                        <input type="text" class="form-control" value="{{ $invp->formc . '-' . $invp->vcrno }}" disabled>
                    </div>

                    <div class="col-md-6 mt-2">
                        <label class="form-label">Voucher Date</label>
                        <input type="text" class="form-control" value="{{ \Carbon\Carbon::parse($invp->pdate)->format('d-m-Y') }}" disabled>
                    </div>

                    <div class="col-md-6 mt-2">
                        <label class="form-label">IOR No.</label>
                        <input type="text" class="form-control" value="{{ $invp->iorno }}" disabled>
                    </div>

                    <div class="col-md-6 mt-2">
                        <label class="form-label">Currency</label>
                        <input type="text" class="form-control" value="{{ $invp->curco }}" disabled>
                    </div>

                    <div class="col-md-6 mt-2">
                        <label class="form-label">Kurs</label>
                        <input type="text" class="form-control" value="{{ number_format($invp->prate, 0, ',', '.') }}" disabled>
                    </div>

                    <div class="col-md-6 mt-2">
                        <label class="form-label">Total Amount</label>
                        <input type="text" class="form-control" value="{{ $invp->curco == 'IDR' ? 'Rp ' . number_format($invp->tpaye,0,',','.') : '$ ' . number_format($invp->tpaye,2,'.',',') }}" disabled>
                    </div>

                    <div class="col-md-12 mt-2">
                        <label class="form-label">Voucher Notes</label>
                        <textarea class="form-control" rows="3" disabled>{{ $invp->noteh }}</textarea>
                    </div>
                </div>
            </div>
        </div>

        {{-- DETAIL --}}
        <div class="mt-4">
            <h4>Invoice Payment Detail</h4>
            <div class="accordion" id="accordionDetail">
                @foreach($invp->invoicepaymentdtls as $i => $row)
                <div class="accordion-item">
                    <h2 class="accordion-header">
                        <button class="accordion-button {{ $i > 0 ? 'collapsed' : '' }}" type="button" data-bs-toggle="collapse" data-bs-target="#detail{{ $i }}">
                            Invoice {{ $i + 1 }}
                        </button>
                    </h2>

                    <div id="detail{{ $i }}"
                        class="accordion-collapse collapse {{ $i == 0 ? 'show' : '' }}">
                        <div class="accordion-body">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Invoice No</label>
                                    <input type="text" class="form-control" value="{{ $row->invfc . ' - ' . $row->invrn }}" disabled>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Due Date</label>
                                    <input type="text" class="form-control" value="{{ $row->duedt ? \Carbon\Carbon::parse($row->duedt)->format('d/m/Y') : '-' }}" disabled>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Bill Amount</label>
                                    <input type="text" class="form-control" value="{{ $invp->curco == 'IDR' ? 'Rp ' . number_format($row->blamt,0,',','.') : '$ ' . number_format($row->blamt,2,'.',',') }}" disabled>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label">A/R Value</label>
                                    <input type="text" class="form-control" value="{{ $invp->curco == 'IDR' ? 'Rp ' . number_format($row->arval,0,',','.') : '$ ' . number_format($row->pcval,2,'.',',') }}" disabled>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Payment Value</label>
                                    <input type="text" class="form-control" value="{{ $invp->curco == 'IDR' ? 'Rp ' . number_format($row->payva,0,',','.') : '$ ' . number_format($row->pcwo,2,'.',',') }}" disabled>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Payment Write Off</label>
                                    <input type="text" class="form-control" value="{{ $invp->curco == 'IDR' ? 'Rp ' . number_format($row->pcwo,0,',','.') : '$ ' . number_format($row->payva,2,'.',',') }}" disabled>
                                </div>

                                <div class="col-md-12">
                                    <label class="form-label">Detail Notes</label>
                                    <textarea class="form-control" rows="3" disabled>{{ $row->noted }}</textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        <div class="mt-4">
            <a href="{{ route('invoice_payment.index') }}" class="btn btn-secondary">
                Kembali
            </a>
        </div>

    </section>
</main>
@endsection