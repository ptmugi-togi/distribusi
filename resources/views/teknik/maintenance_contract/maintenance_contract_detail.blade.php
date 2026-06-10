@extends('layout.main')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/global.css') }}">
@endpush

@section('container')
<main id="main" class="main">
    @php
        $currencySymbol = $mc->curco == 'USD' ? '$' : 'Rp.';
        $fmt = fn($value) => $currencySymbol . ' ' . number_format($value ?? 0, 0, ',', '.');
    @endphp

    <div class="pagetitle">
        <h1>Detail Maintenance Contract</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('maintenance_contract.index') }}">List Maintenance Contract</a></li>
                <li class="breadcrumb-item active">Maintenance Contract Detail</li>
            </ol>
        </nav>
    </div>

    <section class="section">
        <div class="row">
            <div class="col-md-6 mt-3">
                <label class="form-label">MC No.</label>
                <input class="form-control" value="{{ $mc->refno }}" disabled>
            </div>

            <div class="col-md-6 mt-3">
                <label class="form-label">MC Date</label>
                <input class="form-control" value="{{ \Carbon\Carbon::parse($mc->mcdat)->format('d/m/Y') }}" disabled>
            </div>

            <div class="col-md-6 mt-3">
                <label class="form-label">Depo</label>
                <input class="form-control" value="{{ $mc->depo }}" disabled>
            </div>

            <div class="col-md-6 mt-3">
                <label class="form-label">Customer</label>
                <input class="form-control" value="{{ $mc->cusno }} - {{ $mc->mcusmas->cusna ?? '' }}" disabled>
            </div>

            <div class="col-md-6 mt-3">
                <label class="form-label">Customer PO</label>
                <input class="form-control" value="{{ $mc->mcnom }}" disabled>
            </div>

            <div class="col-md-6 mt-3">
                <label class="form-label">Currency</label>
                <input class="form-control" value="{{ $mc->curco }}" disabled>
            </div>

            <div class="col-md-6 mt-3">
                <label class="form-label">MC Period Start</label>
                <input class="form-control" value="{{ \Carbon\Carbon::parse($mc->gmcfr)->format('d/m/Y') }}" disabled>
            </div>

            <div class="col-md-6 mt-3">
                <label class="form-label">MC Period End</label>
                <input class="form-control" value="{{ \Carbon\Carbon::parse($mc->gmcto)->format('d/m/Y') }}" disabled>
            </div>

            <div class="col-md-6 mt-3">
                <label class="form-label">Gross Amount</label>
                <input class="form-control" value="{{ $fmt($mc->gramt) }}" disabled>
            </div>

            <div class="col-md-6 mt-3">
                <label class="form-label">Official Discount</label>
                <input class="form-control" value="{{ $fmt($mc->odisa) }}" disabled>
            </div>

            <div class="col-md-6 mt-3">
                <label class="form-label">Net Amount</label>
                <input class="form-control" value="{{ $fmt($mc->ntamt) }}" disabled>
            </div>

            <div class="col-md-6 mt-3">
                <label class="form-label">VAT ({{ $mc->vatax }}%)</label>
                <input class="form-control" value="{{ $fmt($mc->txamt) }}" disabled>
            </div>

            <div class="col-md-6 mt-3">
                <label class="form-label">MC Amount</label>
                <input class="form-control" value="{{ $fmt($mc->blamt) }}" disabled>
            </div>

            <div class="col-md-12 mt-3">
                <label class="form-label">Notes</label>
                <textarea class="form-control" rows="2" disabled>{{ $mc->noteh }}</textarea>
            </div>
        </div>

        @include('teknik.maintenance_contract.partial_detail.detail_product')
        @include('teknik.maintenance_contract.partial_detail.termin_phase')

        <div class="mt-3">
            <a href="{{ route('maintenance_contract.index') }}" class="btn btn-secondary">Kembali</a>
        </div>
    </section>
</main>
@endsection