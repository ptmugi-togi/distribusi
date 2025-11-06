@extends('layout.main')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/global.css') }}">
@endpush

@section('container')
<main id="main" class="main">
    <div class="d-flex justify-content-between align-items-center">
        <div class="pagetitle">
            <h1>Detail BBM</h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('bbm.index') }}">List BBM</a></li>
                    <li class="breadcrumb-item active">Detail BBM</li>
                </ol>
            </nav>
        </div>
    </div>

    <section class="section">
        <div class="card p-3 shadow-sm">
            {{-- Header --}}
            <div class="row">
                <div class="col-md-6 mt-3">
                    <label class="form-label">Formc</label>
                    <input type="text" class="form-control" value="{{ $bbm->formc }} ({{ $bbm->mformcode->desc_c }})" disabled>
                </div>

                <div class="col-md-6 mt-3">
                    <label class="form-label">Warehouse</label>
                    <input type="text" class="form-control" value="{{ $bbm->warco }}" disabled>
                </div>

                <div class="col-md-6 mt-3">
                    <label class="form-label">Stock Receipt No.</label>
                    <input type="text" class="form-control" value="{{ $bbm->trano }}" disabled>
                </div>

                <div class="col-md-6 mt-3">
                    <label class="form-label">Stock Receipt Date</label>
                    <input type="date" class="form-control" value="{{ $bbm->tradt }}" disabled>
                </div>

                @if($bbm->formc == 'IB')
                    <div class="col-md-6 mt-3">
                        <label class="form-label">Receiving Instruction</label>
                        <input type="text" class="form-control" value="{{ $bbm->reffc }} {{ $bbm->refno }}" disabled>
                    </div>
                @else
                    <div class="col-md-6 mt-3">
                        <label class="form-label">PO No</label>
                        <input type="text" class="form-control" value="{{ $bbm->refno }}" disabled>
                    </div>
                @endif

                <div class="col-md-6 mt-3">
                    <label class="form-label">Supplier</label>
                    <input type="text" class="form-control" value="{{ $bbm->supno }} - {{ $bbm->vendor->supna }}" disabled>
                </div>

                @if ($bbm->formc == 'IB')
                    <div class="col-md-6 mt-3">
                        <label class="form-label">BL No.</label>
                        <input type="text" class="form-control" value="{{ $bbm->blnum }}" disabled>
                    </div>

                    <div class="col-md-6 mt-3">
                        <label class="form-label">Vessel</label>
                        <input type="text" class="form-control" value="{{ $bbm->vesel }}" disabled>
                    </div>
                @endif

                <div class="col-md-12 mt-3">
                    <label class="form-label">Notes</label>
                    <textarea class="form-control" rows="3" disabled>{{ $bbm->noteh }}</textarea>
                </div>
            </div>

            {{-- Detail --}}
            <div class="row mt-4">
                <h3>BBM Detail</h3>
                <div class="accordion" id="accordionBbm">
                    @foreach ($bbm->bbmdtls as $i => $detail)
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="heading-{{ $i }}">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                data-bs-target="#collapse-{{ $i }}" aria-expanded="false">
                                    Product: {{ $detail->opron }} - {{ $detail->mpromas->prona }}
                            </button>
                        </h2>
                        <div id="collapse-{{ $i }}" class="accordion-collapse collapse"
                            aria-labelledby="heading-{{ $i }}">
                            <div class="accordion-body">
                                <div class="row">
                                    @if ($bbm->formc == 'IB')
                                        <div class="col-md-6 mt-3">
                                            <label class="form-label">Invoice No.</label>
                                            <input type="text" class="form-control" value="{{ $detail->invno }}" disabled>
                                        </div>
                                    @endif

                                    <div class="col-md-6 mt-3">
                                        <label class="form-label">Barang</label>
                                        <input type="text" class="form-control" value="{{ $detail->opron }} - {{ $detail->mpromas->prona }}" disabled>
                                    </div>

                                    @if ($bbm->formc == 'IB')
                                        <div class="col-md-6 mt-3">
                                            <label class="form-label">Invoice Quantity</label>
                                            <div class="input-group">
                                                <input type="text" class="form-control" value="{{ optional($detail->podtl)->poqty ?? '' }}" disabled>
                                                <span class="input-group-text">{{ $detail->qunit }}</span>
                                            </div>
                                        </div>
                                    @else
                                        <div class="col-md-6 mt-3">
                                            <label class="form-label">PO Quantity</label>
                                            <div class="input-group">
                                                <input type="text" class="form-control" value="{{ optional($detail->podtl)->poqty ?? '' }}" disabled>
                                                <span class="input-group-text">{{ $detail->qunit }}</span>
                                            </div>
                                        </div>
                                    @endif

                                    <div class="col-md-6 mt-3">
                                        <label class="form-label">Receipt Quantity</label>
                                        <div class="input-group">
                                            <input type="text" class="form-control" value="{{ $detail->trqty }}" disabled>
                                            <span class="input-group-text">{{ $detail->qunit }}</span>
                                        </div>
                                    </div>

                                    <div class="col-md-6 mt-3">
                                        <label class="form-label">Serial / Batch No.</label>
                                        <input type="text" class="form-control" value="{{ $detail->lotno }}" disabled>
                                    </div>

                                    <div class="col-md-6 mt-3">
                                        <label class="form-label">PO No.</label>
                                        <input type="text" class="form-control" value="{{ $detail->pono }}" disabled>
                                    </div>

                                    <div class="col-md-6 mt-3">
                                        <label class="form-label">Warehouse Location</label>
                                        <input type="text" class="form-control" value="{{ $detail->locco }}" disabled>
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
                <a href="{{ route('bbm.index') }}" class="btn btn-secondary">Kembali</a>
            </div>
        </div>
    </section>
</main>
@endsection
