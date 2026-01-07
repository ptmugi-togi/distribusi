@extends('layout.main')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/global.css') }}">
@endpush

@section('container')
<main id="main" class="main">
    <div class="d-flex justify-content-between align-items-center">
        <div class="pagetitle">
            <h1>Detail BPB ({{ $bpb->bpbid }})</h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('bpb.index') }}">List BPB</a></li>
                    <li class="breadcrumb-item active">Detail BPB</li>
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
                    <label for="formc" class="form-label">BPB (Bon Permintaan Barang)</label>
                    <input type="text" class="form-control" id="formc" value="RA (Stock Requisition)" disabled>
                </div>

                <div class="col-md-6 mt-3">
                    <label for="reqno" class="form-label">Requisition No.</label>
                    <input type="text" class="form-control" id="reqno" value="{{ $bpb->reqno }}" disabled>
                </div>

                <div class="col-md-6 mt-3">
                    <label for="reqdt" class="form-label">Requisition Date</label>
                    <input type="text" class="form-control" id="reqdt" value="{{ \Carbon\Carbon::parse($bpb->reqdt)->format('d/m/Y') }}" disabled>
                </div>

                <div class="col-md-6 mt-3">
                    <label for="sorfcno" class="form-label">Order Confirmation</label>
                    <div class="d-flex">
                        <input type="text" class="form-control" id="sorfcno" value="{{ $bpb->sorfc }}{{ $bpb->sorno }}" disabled>
                    </div>
                </div>

                <div class="col-md-6 mt-3">
                    <label for="rqfor" class="form-label">Request For</label>
                    <input type="text" class="form-control" id="rqfor" value="{{ $bpb->rqfor }}" disabled>
                </div>

                <div class="col-md-6 mt-3">
                    <label for="reqto" class="form-label">Request To Branch</label>
                    <input type="text" class="form-control" id="reqto" value="{{ $bpb->reqto }}" disabled>
                </div>

                <div class="col-md-6 mt-3">
                    <label for="reqtn" class="form-label">Attn.</label>
                    <input type="text" class="form-control" id="reqtn" value="{{ $bpb->reqtn }}" disabled>
                </div>

                <div class="col-md-6 mt-3">
                    <label for="delto" class="form-label">Delivery To W/H</label>
                    <input type="text" class="form-control" id="delto" value="{{ $bpb->delto }}" disabled>
                </div>

                <div class="col-md-6 mt-3">
                    <label for="delco" class="form-label">Delivery Code</label>
                    <input type="text" class="form-control" id="delco" value="{{ $bpb->delco }}" disabled>
                </div>

                <div class="col-md-6 mt-3">
                    <label for="warna" class="form-label">Ship to Name</label>
                    <input type="text" class="form-control" id="warna" value="{{ $bpb->mwarco->warna }}" disabled>
                </div>

                <div class="col-md-6 mt-3">
                    <label for="address" class="form-label">Address</label>
                    <input type="text" class="form-control" id="address" value="{{ $bpb->mwarco->address }}" disabled>
                </div>

                <div class="col-md-6 mt-3">
                    <label for="contp" class="form-label">Contact Person</label>
                    <input type="text" class="form-control" id="contp" value="{{ $bpb->contp }}" disabled>
                </div>

                <div class="col-md-12 mt-3">
                    <label class="form-label">Notes</label>
                    <textarea type="text" class="form-control" id="noteh" maxlength="200" disabled>{{ $bpb->noteh }}</textarea>
                </div>
            </div>
        </div>

        {{-- Detail --}}
        <div class="row mt-4">
            <h3>BPB Detail</h3>
            <div class="accordion" id="accordionBpb">
                @foreach ($bpb->bpbdtls as $i => $detail)
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
                                    <label class="form-label">Request Quantity</label>
                                    <div class="input-group">
                                        <input type="text" class="form-control" value="{{ $detail->rqqty }}" disabled>
                                        <span class="input-group-text">{{ $detail->mpromas->stdqu }}</span>
                                    </div>
                                </div>

                                <div class="col-md-6 mt-3">
                                    <label class="form-label">Expected Arrival</label>
                                    <input type="text" class="form-control" value="{{ $detail->eariv }}" disabled>
                                </div>

                                <div class="col-md-6 mt-3">
                                    <label class="form-label">Alokasi</label>
                                    <input type="text" class="form-control" value="{{ $detail->aloka }}" disabled>
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
            <a href="{{ route('bpb.index') }}" class="btn btn-secondary">Kembali</a>
        </div>
    </section>
</main>
@endsection
