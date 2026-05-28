@extends('layout.main')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/global.css') }}">
@endpush

@section('container')
<main id="main" class="main">
    @php
        $currencySymbol = $dn->curco == 'USD' ? '$' : 'Rp.';
    @endphp
    <div class="pagetitle">
        <h1>Detail Delivery Note</h1>
    </div>

    <section class="section">
        <div class="row">
            <div class="col-md-6 mt-3">
                <label class="form-label">D/N No.</label>
                <input class="form-control" value="{{ $dn->dnnum }}" disabled>
            </div>

            <div class="col-md-6 mt-3">
                <label class="form-label">D/N Date</label>
                <input class="form-control" value="{{ $dn->dndat }}" disabled>
            </div>

            <div class="col-md-6 mt-3">
                <label class="form-label">Customer</label>
                <input class="form-control" value="{{ $dn->cusno }}" disabled>
            </div>

            <div class="col-md-6 mt-3">
                <label class="form-label">Currency</label>
                <input class="form-control" value="{{ $dn->curco }}" disabled>
            </div>

            <div class="col-md-6 mt-3">
                <label class="form-label">Service Fee</label>
                <input class="form-control" value="{{ $currencySymbol }} {{ number_format($dn->total_service ?? 0, 0, ',', '.') }}" disabled>
            </div>

            <div class="col-md-6 mt-3">
                <label class="form-label">Sparepart</label>
                <input class="form-control" value="{{ $currencySymbol }} {{ number_format($dn->total_sparepart ?? 0, 0, ',', '.') }}" disabled>
            </div>

            <div class="col-md-6 mt-3">
                <label class="form-label">Off Discount</label>
                <input class="form-control" value="{{ $currencySymbol }} {{ number_format($dn->odisa ?? 0, 0, ',', '.') }}" disabled>
            </div>

            <div class="col-md-6 mt-3">
                <label class="form-label">Net Amount</label>
                <input class="form-control" value="{{ $currencySymbol }} {{ number_format($dn->ntamt ?? 0, 0, ',', '.') }}" disabled>
            </div>

            <div class="col-md-6 mt-3">
                <label class="form-label">VAT</label>
                <input class="form-control" value="{{ $currencySymbol }} {{ number_format($dn->txamt ?? 0, 0, ',', '.') }}" disabled>
            </div>

            <div class="col-md-6 mt-3">
                <label class="form-label">Billing Amount</label>
                <input class="form-control" value="{{ $currencySymbol }} {{ number_format($dn->blamt ?? 0, 0, ',', '.') }}" disabled>
            </div>

            <div class="col-md-12 mt-3">
                <label class="form-label">Notes</label>
                <textarea class="form-control" rows="2" disabled>{{ $dn->intxt }}</textarea>
            </div>
        </div>

        @include('teknik.delivery_note.partial_detail.product_service_detail')
        @include('teknik.delivery_note.partial_detail.sparepart_detail')

        <div class="mt-3">
            <a href="{{ route('delivery_note.index') }}" class="btn btn-secondary">Kembali</a>
        </div>
    </section>
</main>
@endsection