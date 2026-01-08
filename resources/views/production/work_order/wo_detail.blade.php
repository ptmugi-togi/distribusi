@extends('layout.main')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/global.css') }}">
@endpush

@section('container')
<main id="main" class="main">
    <div class="d-flex justify-content-between align-items-center">
        <div class="pagetitle">
            <h1>Detail WO ({{ $wo->woid }})</h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('wo.index') }}">List WO</a></li>
                    <li class="breadcrumb-item active">Detail WO</li>
                </ol>
            </nav>
        </div>
    </div>

    <section class="section">
        {{-- Header --}}
        <div class="card p-3 shadow-sm">
            <input type="text" id="braco" value="{{ auth()->user()->cabang }}" hidden>

            <div class="row">
                <div class="col-md-6 mt-3">
                    <label for="wonum" class="form-label">Work Order No.</label>
                    <input type="text" class="form-control" id="wonum" value="{{ $wo->wonum }}" disabled>
                </div>

                <div class="col-md-6 mt-3">
                    <label for="wodat" class="form-label">Work Order Date</label>
                    <input type="text" class="form-control" id="wodat" value="{{ \Carbon\Carbon::parse($wo->wodat)->format('d/m/Y') }}" disabled>
                </div>

                <div class="col-md-6 mt-3">
                    <label for="reqbr" class="form-label">Request By Branch</label>
                    <input type="text" class="form-control" id="reqbr" value="{{ $wo->reqbr }}" disabled>
                </div>

                <div class="col-md-6 mt-3">
                    <label for="ppose" class="form-label">Purpose</label>
                    <input type="text" class="form-control" id="ppose" value="{{ $wo->ppose }}" disabled>
                </div>

                <div class="col-md-6 mt-3">
                    <label for="reqby" class="form-label">Request By</label>
                    <input type="text" class="form-control" id="reqby" value="{{ $wo->reqby }}" disabled>
                </div>

                <div class="col-md-6 mt-3">
                    <label for="reqdt" class="form-label">Request Date</label>
                    <input type="text" class="form-control" id="reqdt" value="{{ \Carbon\Carbon::parse($wo->reqdt)->format('d/m/Y') }}" disabled>
                </div>

                <div class="col-md-6 mt-3">
                    <label for="cusna" class="form-label">Customer</label>
                    <input type="text" class="form-control" id="cusna" value="{{ $wo->cusna }}" disabled>
                </div>

                <div class="col-md-6 mt-3">
                    <label for="sorno" class="form-label">Order Confirmation Number</label>
                    <input type="text" class="form-control" id="sorno" value="{{ $wo->sorno }}" disabled>
                </div>

                <div class="col-md-6 mt-3">
                    <label for="costc" class="form-label">Const Center</label>
                    <input type="text" class="form-control" id="costc" value="{{ $wo->costc }}" disabled>
                </div>

                <div class="col-md-6 mt-3">
                    <label for="fdate" class="form-label">Finish Schedule</label>
                    <input type="text" class="form-control" id="fdate" value="{{ \Carbon\Carbon::parse($wo->fdate)->format('d/m/Y') }}" disabled>
                </div>

                <div class="col-md-12 mt-3">
                    <label class="form-label">Notes</label>
                    <textarea type="text" class="form-control" id="noteh" maxlength="200" disabled>{{ $wo->noteh }}</textarea>
                </div>
            </div>
        </div>

        {{-- Detail --}}
        <div class="row mt-4">
            <h3>WO Detail</h3>
            <div class="accordion" id="accordionBpb">
                @foreach ($wo->wodtls as $i => $detail)
                <div class="accordion-item">
                    <h2 class="accordion-header" id="heading-{{ $i }}">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                            data-bs-target="#collapse-{{ $i }}" aria-expanded="false">
                                Product: {{ $detail->outpr }} - {{ $detail->mpromas->prona }}
                        </button>
                    </h2>
                    <div id="collapse-{{ $i }}" class="accordion-collapse collapse"
                        aria-labelledby="heading-{{ $i }}">
                        <div class="accordion-body">
                            <div class="row">
                                <div class="col-md-6 mt-3">
                                    <label class="form-label">Barang</label>
                                    <input type="text" class="form-control" value="{{ $detail->outpr }} - {{ $detail->mpromas->prona }}" disabled>
                                </div>

                                <div class="col-md-6 mt-3">
                                    <label class="form-label">Request Quantity</label>
                                    <div class="input-group">
                                        <input type="text" class="form-control" value="{{ $detail->outqt }}" disabled>
                                        <span class="input-group-text">{{ $detail->stdqu }}</span>
                                    </div>
                                </div>

                                <div class="col-md-12 mt-3">
                                    <label class="form-label">Notes</label>
                                    <textarea class="form-control" disabled>{{ $detail->noted }}</textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        <div class="mt-4">
            <a href="{{ route('wo.index') }}" class="btn btn-secondary">Kembali</a>
        </div>
    </section>
</main>
@endsection
