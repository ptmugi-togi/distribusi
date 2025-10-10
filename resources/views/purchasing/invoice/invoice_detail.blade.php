@extends('layout.main')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/purchasing/invoice.css') }}">
@endpush

@section('container')
<main id="main" class="main">
    <div class="pagetitle">
        <h1>Detail Invoice</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('invoice.index') }}">List Invoice</a></li>
                <li class="breadcrumb-item active">Detail</li>
            </ol>
        </nav>
    </div>

    <section class="section">
        <div class="card p-4 shadow-sm">
            <div class="row">
                <div class="col-md-6 mt-2">
                    <label class="form-label">PO Type</label>
                    <input type="text" class="form-control" value="{{ $invoice->details->first()->potyp }}" disabled>
                </div>

                <div class="col-md-6 mt-2">
                    <label class="form-label">Supplier</label>
                    <input type="text" class="form-control"
                        value="{{ $invoice->vendor->supno ?? '' }} - {{ $invoice->vendor->supna ?? '' }}"
                        disabled>
                </div>

                <div class="col-md-6 mt-2">
                    <label class="form-label">Invoice No.</label>
                    <input type="text" class="form-control" value="{{ $invoice->invno }}" disabled>
                </div>

                <div class="col-md-6 mt-2">
                    <label class="form-label">Invoice Date</label>
                    <input type="text" class="form-control"
                        value="{{ \Carbon\Carbon::parse($invoice->invdt)->format('d M Y') }}" disabled>
                </div>

                <div class="col-md-6 mt-2">
                    <label class="form-label">Due Date</label>
                    <input type="text" class="form-control"
                        value="{{ \Carbon\Carbon::parse($invoice->duedt)->format('d M Y') }}" disabled>
                </div>

                <div class="col-md-6 mt-2">
                    <label class="form-label">Currency</label>
                    <input type="text" class="form-control" value="{{ $invoice->curco }}" disabled>
                </div>

                @if($invoice->details->first()->potyp === 'PI')
                {{-- Import Fields --}}
                <div class="col-md-6 mt-2">
                    <label class="form-label">Receipt Number</label>
                    <input type="text" class="form-control" value="RI {{ $invoice->rinum }}" disabled>
                </div>
                <div class="col-md-6 mt-2">
                    <label class="form-label">BL / AWB No.</label>
                    <input type="text" class="form-control" value="{{ $invoice->blnum }}" disabled>
                </div>
                <div class="col-md-6 mt-2">
                    <label class="form-label">Freight</label>
                    <input type="text" class="form-control" value="{{ formatCurrencyDetail($invoice->tfreight, $invoice->curco) }}" disabled>
                </div>
                @endif
            </div>

            <hr class="my-4">

            <h4 class="mb-3">Invoice Detail</h4>
            @foreach($invoice->details as $i => $detail)
            <div class="accordion mb-2" id="accordionDetail-{{ $i }}">
                <div class="accordion-item border">
                    <h2 class="accordion-header">
                        <button class="accordion-button {{ $i > 0 ? 'collapsed' : '' }}" type="button"
                            data-bs-toggle="collapse" data-bs-target="#detail-{{ $i }}">
                            {{ $detail->opron }} - {{ $detail->barang_name ?? '' }}
                        </button>
                    </h2>
                    <div id="detail-{{ $i }}" class="accordion-collapse collapse {{ $i == 0 ? 'show' : '' }}">
                        <div class="accordion-body">
                            <div class="row">
                                <div class="col-md-6 mt-2">
                                    <label class="form-label">PO No.</label>
                                    <input type="text" class="form-control" value="{{ $detail->pono }}" disabled>
                                </div>
                                <div class="col-md-6 mt-2">
                                    <label class="form-label">Barang</label>
                                    <input type="text" class="form-control"
                                        value="{{ $detail->opron }} - {{ $detail->barang_name ?? '' }}" disabled>
                                </div>

                                <div class="col-md-6 mt-2">
                                    <label class="form-label">PO Quantity</label>
                                    <input type="text" class="form-control"
                                        value="{{ $detail->poqty }} {{ $detail->stdqt }}" disabled>
                                </div>

                                <div class="col-md-6 mt-2">
                                    <label class="form-label">Invoice Quantity</label>
                                    <input type="text" class="form-control" value="{{ $detail->inqty }}" disabled>
                                </div>

                                <div class="col-md-6 mt-2">
                                    <label class="form-label">Invoice Price</label>
                                    <input type="text" class="form-control"
                                        value="{{ formatCurrencyDetail($detail->netpr, $invoice->curco) }}" disabled>
                                </div>

                                <div class="col-md-6 mt-2">
                                    <label class="form-label">Invoice Amount</label>
                                    <input type="text" class="form-control"
                                        value="{{ formatCurrencyDetail($detail->inprc, $invoice->curco) }}" disabled>
                                </div>

                                @if($invoice->details->first()->potyp === 'PI')
                                    <div class="col-md-6 mt-2">
                                        <label class="form-label">Ex-work Price</label>
                                        <input type="text" class="form-control"
                                            value="{{ formatCurrencyDetail($detail->ewprc, $invoice->curco) }}" disabled>
                                    </div>

                                    <div class="col-md-6 mt-2">
                                        <label class="form-label">FOB Charges</label>
                                        <input type="text" class="form-control"
                                            value="{{ formatCurrencyDetail($detail->fobch, $invoice->curco) }}" disabled>
                                    </div>

                                    <div class="col-md-6 mt-2">
                                        <label class="form-label">Insurance</label>
                                        <input type="text" class="form-control"
                                            value="{{ formatCurrencyDetail($detail->incst, $invoice->curco) }}" disabled>
                                    </div>

                                    <div class="col-md-6 mt-2">
                                        <label class="form-label">HS No.</label>
                                        <input type="text" class="form-control" value="{{ $detail->hsn }}" disabled>
                                    </div>

                                    <div class="col-md-6 mt-2">
                                        <label class="form-label">BM (%)</label>
                                        <input type="text" class="form-control" value="{{ $detail->bm }}" disabled>
                                    </div>
                                @endif

                                <div class="col-md-2 mt-2">
                                    <label class="form-label">PPn (%)</label>
                                    <input type="text" class="form-control" value="{{ $detail->ppn }}" disabled>
                                </div>

                                <div class="col-md-2 mt-2">
                                    <label class="form-label">PPnBM (%)</label>
                                    <input type="text" class="form-control" value="{{ $detail->ppnbm }}" disabled>
                                </div>

                                <div class="col-md-2 mt-2">
                                    <label class="form-label">PPh (%)</label>
                                    <input type="text" class="form-control" value="{{ $detail->pph }}" disabled>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach

            <div class="mt-3">
                <a href="{{ route('invoice.index') }}" class="btn btn-secondary">Kembali</a>
            </div>
        </div>
    </section>
</main>
@endsection
