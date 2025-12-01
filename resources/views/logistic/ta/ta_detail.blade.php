@extends('layout.main')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/global.css') }}">
@endpush

@section('container')
<main id="main" class="main">
    <div class="d-flex justify-content-between align-items-center">
        <div class="pagetitle">
            <h1>Detail TA</h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('ta.index') }}">List TA</a></li>
                    <li class="breadcrumb-item active">Detail TA</li>
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
                    <input type="text" class="form-control" value="{{ $ta->formc }} ({{ $ta->mformcode->desc_c }})" disabled>
                </div>

                <div class="col-md-6 mt-3">
                    <label class="form-label">Warehouse</label>
                    <input type="text" class="form-control" value="{{ $ta->warco }}" disabled>
                </div>

                <div class="col-md-6 mt-3">
                    <label class="form-label">Transfer Note No.</label>
                    <input type="text" class="form-control" value="{{ $ta->trano }}" disabled>
                </div>

                <div class="col-md-6 mt-3">
                    <label class="form-label">Transfer Note Date</label>
                    <input type="date" class="form-control" value="{{ $ta->tradt }}" disabled>
                </div>

                <div class="col-md-6 mt-3">
                    <label class="form-label">Request by Branch</label>
                    <input type="text" class="form-control" value="{{ $ta->rqbrc }}" disabled>
                </div>

                <div class="col-md-6 mt-3">
                    <label class="form-label">Stock Requisitoin No.</label>
                    <input type="text" class="form-control" value="{{ $ta->rfc01 }} - {{ $ta->ref01 }}" disabled>
                </div>

                <div class="col-md-6 mt-3">
                    <label class="form-label">Expediter</label>
                    <input type="text" class="form-control" value="{{ $ta->exped }}" disabled>
                </div>

                <div class="col-md-6 mt-3">
                    <label class="form-label">Transfer to Name</label>
                    <input type="text" class="form-control" value="{{ $ta->mbranch->brana }}" disabled>
                </div>

                <div class="col-md-6 mt-3">
                    <label class="form-label">Transfer to Address</label>
                    <textarea class="form-control" rows="2" disabled>{{ $ta->mbranch->address }}</textarea>
                </div>

                <div class="col-md-6 mt-3">
                    <label class="form-label">Notes</label>
                    <textarea class="form-control" rows="2" disabled>{{ $ta->noteh }}</textarea>
                </div>
            </div>

            {{-- Detail --}}
            <div class="row mt-4">
                <h3>TA Detail</h3>
                <div class="accordion" id="accordionTa">
                    @foreach ($ta->tadtls as $i => $detail)
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
                                    <div class="col-md-6 mt-3">
                                        <label class="form-label">Barang</label>
                                        <input type="text" class="form-control" value="{{ $detail->opron }} - {{ $detail->mpromas->prona }}" disabled>
                                    </div>

                                    <div class="col-md-6 mt-3">
                                        <label class="form-label">Serial / Batch No.</label>
                                        <input type="text" class="form-control" value="{{ $detail->lotno }}" disabled>
                                    </div>

                                    <div class="col-md-6 mt-3">
                                        <label class="form-label">Issue Quantity</label>
                                        <div class="input-group">
                                            <input type="text" class="form-control" value="{{ $detail->trqty }}" disabled>
                                            <span class="input-group-text">{{ $detail->qunit }}</span>
                                        </div>
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
                <a href="{{ route('ta.index') }}" class="btn btn-secondary">Kembali</a>
            </div>
        </div>
    </section>
</main>
@endsection
