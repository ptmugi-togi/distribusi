@extends('layout.main')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/global.css') }}">
@endpush

@section('container')
<main id="main" class="main">

    <div class="d-flex justify-content-between align-items-center">
        <div class="pagetitle">
            <h1>Detail Write Off A/R</h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a href="{{ route('dashboard') }}">Home</a>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="{{ route('writeoff_ar.index') }}">List Write Off A/R</a>
                    </li>
                    <li class="breadcrumb-item active">
                        View Detail
                    </li>
                </ol>
            </nav>
        </div>

        <div class="card">
            <h5 class="p-2">
                <b>Branch : {{ $woffar->braco }}</b>
            </h5>
        </div>
    </div>

    <section class="section">

        {{-- HEADER --}}
        <div class="card">
            <div class="card-body pt-4">
                <div class="row">
                    <div class="col-md-6 mt-2">
                        <label class="form-label">AR Write Off No.</label>
                        <input type="text" class="form-control" value="{{ $woffar->formc . '-' . $woffar->vcrno }}" disabled>
                    </div>

                    <div class="col-md-6 mt-2">
                        <label class="form-label">Write Off Date</label>
                        <input type="text" class="form-control" value="{{ \Carbon\Carbon::parse($woffar->pdate)->format('d-m-Y') }}" disabled>
                    </div>

                    <div class="col-md-6 mt-2">
                        <label class="form-label">Refference No.</label>
                        <input type="text" class="form-control" value="{{ $woffar->refno }}" disabled>
                    </div>

                    <div class="col-md-12 mt-2">
                        <label class="form-label">Voucher Notes</label>
                        <textarea class="form-control" rows="3" disabled>{{ $woffar->noteh }}</textarea>
                    </div>
                </div>
            </div>
        </div>

        {{-- DETAIL --}}
        <div class="mt-4">
            <h4>Write Off A/R Detail</h4>
            <div class="accordion" id="accordionDetail">
                @foreach($details as $i => $row)
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
                                    <input type="text" class="form-control" value="{{ $row->invfc . ' - ' . $row->invrn . ' (' . $row->cusna . ')' }}" disabled>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Customer</label>
                                    <input type="text" class="form-control" value="{{ $row->cusno }} {{ $row->cusna }}" disabled>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Currency</label>
                                    <input type="text" class="form-control" value="{{ $row->curco }}" disabled>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Kurs</label>
                                    <input type="text" class="form-control" value="{{ $row->curco == 'IDR' ? 'Rp ' . number_format($row->irate,0,',','.') : '$ ' . number_format($row->irate,2,'.',',') }}" disabled>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label">DPP</label>
                                    <input type="text" class="form-control" value="{{ $row->curco == 'IDR' ? 'Rp ' . number_format($row->ntamt,0,',','.') : '$ ' . number_format($row->ntamt,2,'.',',') }}" disabled>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label">PPN</label>
                                    <input type="text" class="form-control" value="{{ $row->curco == 'IDR' ? 'Rp ' . number_format($row->txamt,0,',','.') : '$ ' . number_format($row->txamt,2,'.',',') }}" disabled>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Bill Amount</label>
                                    <input type="text" class="form-control" value="{{ $row->curco == 'IDR' ? 'Rp ' . number_format($row->blamt,0,',','.') : '$ ' . number_format($row->blamt,2,'.',',') }}" disabled>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label">A/R Value Amount</label>
                                    <input type="text" class="form-control" value="{{ $row->curco == 'IDR' ? 'Rp ' . number_format($row->arval,0,',','.') : '$ ' . number_format($row->arval,2,'.',',') }}" disabled>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Write Off Value</label>
                                    <input type="text" class="form-control" value="{{ $row->curco == 'IDR' ? 'Rp ' . number_format($row->trval,0,',','.') : '$ ' . number_format($row->trval,2,'.',',') }}" disabled>
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
            <a href="{{ route('writeoff_ar.index') }}" class="btn btn-secondary">
                Kembali
            </a>
        </div>

    </section>
</main>
@endsection