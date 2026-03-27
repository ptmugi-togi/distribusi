@extends('layout.main')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/global.css') }}">
@endpush

@section('container')
<main id="main" class="main">
    <div class="d-flex justify-content-between align-items-center">
        <div class="pagetitle">
            <h1>Detail DO</h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('do.index') }}">List DO</a></li>
                    <li class="breadcrumb-item active">Detail DO</li>
                </ol>
            </nav>
        </div>
    </div>

    <section class="section">
        <div class="card p-3 shadow-sm">
            {{-- Header --}}
            <div class="row">
                <div class="col-md-6 mt-3">
                    <label class="form-label">Form Code</label>
                    <input type="text" class="form-control" value="{{ $do->formc }} ({{ $do->mformcode->descr }})" disabled>
                </div>

                <div class="col-md-6 mt-3">
                    <label class="form-label">DO No.</label>
                    <input type="text" class="form-control" value="{{ $do->trano }}" disabled>
                </div>

                <div class="col-md-6 mt-3">
                    <label class="form-label">DO Date</label>
                    <input type="date" class="form-control" value="{{ $do->tradt }}" disabled>
                </div>

                <div class="col-md-6 mt-3">
                    <label class="form-label">OC No.</label>
                    <input type="text" class="form-control" value="{{ $do->rfc01 }} - {{ $do->ref01 }}" disabled>
                </div>

                <div class="col-md-6 mt-3">
                    <label class="form-label">Expediter</label>
                    <input type="text" class="form-control" value="{{ $do->exped }}" disabled>
                </div>

                <div class="col-md-12 mt-3">
                    <label class="form-label">Notes</label>
                    <textarea class="form-control" rows="2" disabled>{{ $do->noteh }}</textarea>
                </div>
            </div>

            <div class="Address">
                <hr>
                <h5>Address</h5>
                <div id="section-address">
                <div class="row">
                    <div class="col-md-4 mt-3">
                        <label for="shpto" class="form-label">Deliver To</label>
                        <input name="shpto" id="shpto" class="form-control" value="{{ $do->shpto }}" disabled>
                    </div>

                    <div class="col-md-4 mt-3">
                        <label for="shpto_name" class="form-label">Deliver To Name</label>
                        <input type="text" class="form-control" name="shpto_name" id="shpto_name" value="{{ $do->shipto->shpnm }}" disabled>
                    </div>

                    <div class="col-md-4 mt-3">
                        <label for="shpto_attn" class="form-label">Attn</label>
                        <input type="text" class="form-control" name="shpto_attn" id="shpto_attn" value="{{ $do->shipto->contp }}" disabled>
                    </div>

                    <div class="col-md-4 mt-3">
                        <label for="shpto_prov" class="form-label">Provinsi</label>
                        <input type="text" class="form-control" name="shpto_prov" id="shpto_prov" value="{{ $do->shipto->province }}" disabled>
                    </div>

                    <div class="col-md-4 mt-3">
                        <label for="shpto_kab" class="form-label">Kabupaten</label>
                        <input type="text" class="form-control" name="shpto_kab" id="shpto_kab" value="{{ $do->shipto->kabupaten }}" disabled>
                    </div>

                    <div class="col-md-4 mt-3">
                        <label for="shpto_phone" class="form-label">Phone</label>
                        <input type="text" class="form-control" name="shpto_phone" id="shpto_phone" value="{{ $do->shipto->phone }}" disabled>
                    </div>

                    <div class="col-md-12 mt-3">
                        <label for="shpto_address" class="form-label">Deliver To Address</label>
                        <textarea class="form-control" name="shpto_address" id="shpto_address" rows="2" disabled>{{ $do->shipto->deliveryaddress }}</textarea>
                    </div>
                </div>
                </div>
            </div>

            {{-- Detail --}}
            <div class="row mt-4">
                <h3>DO Detail</h3>
                <div class="accordion" id="accordionDo">
                    @foreach ($do->dodtls as $i => $detail)
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
                <a href="{{ route('do.index') }}" class="btn btn-secondary">Kembali</a>
            </div>
        </div>
    </section>
</main>
@endsection
