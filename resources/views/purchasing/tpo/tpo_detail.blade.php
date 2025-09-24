@extends('layout.main')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/purchasing/tpo.css') }}">
@endpush

@section('container')
<main id="main" class="main">
    <div class="pagetitle">
        <h1>Detail Data PO</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('tpo.index') }}">List PO</a></li>
                <li class="breadcrumb-item active">Detail PO</li>
            </ol>
        </nav>
    </div>

    <section class="section">
        <div class="row">
            <div class="col-md-6 mt-3">
                <label class="form-label">Tipe PO</label>
                <input type="text" class="form-control" value="{{ $tpohdr->potype }}" disabled>
            </div>

            <div class="col-md-6 mt-3">
                <label class="form-label">Nomor PO</label>
                <input type="text" class="form-control" value="{{ $tpohdr->pono }}" disabled>
            </div>

            <div class="col-md-6 mt-3">
                <label class="form-label">Tanggal PO</label>
                <input type="text" class="form-control" value="{{ date('d-m-Y', strtotime($tpohdr->podat)) }}" disabled>
            </div>

            <div class="col-md-6 mt-3">
                <label class="form-label">Supplier</label>
                <input type="text" class="form-control" value="{{ $tpohdr->supno }} - {{ $tpohdr->vendor->supna ?? '' }}" disabled>
            </div>

            <div class="col-md-6 mt-3">
                <label class="form-label">Term Of Payment</label>
                <input type="text" class="form-control" value="{{ $tpohdr->topay }} ({{ $tpohdr->tdesc }})" disabled>
            </div>

            <div class="col-md-6 mt-3">
                <label class="form-label">Currency Code</label>
                <input type="text" class="form-control" value="{{ $tpohdr->curco }}" disabled>
            </div>

            <div class="col-md-6 mt-3">
                <label class="form-label">Pengiriman</label>
                <input type="text" class="form-control" value="{{ $tpohdr->shvia }}" disabled>
            </div>

            <div class="col-md-6 mt-3">
                <label class="form-label">Kode Penerima</label>
                <input type="text" class="form-control" value="{{ $tpohdr->delco }}" disabled>
            </div>
        </div>

        <div class="row">
            <div class="col-md-4 mt-3">
                <label class="form-label">Diskon (%)</label>
                <input type="text" class="form-control" value="{{ $tpohdr->diper }}" disabled>
            </div>

            <div class="col-md-4 mt-3">
                <label class="form-label">Tax Rate (%)</label>
                <input type="text" class="form-control" value="{{ $tpohdr->vatax }}" disabled>
            </div>

            <div class="col-md-4 mt-3">
                <label class="form-label">Meterai</label>
                <input type="text" class="form-control" value="{{ $tpohdr->stamp }}" disabled>
            </div>

            <div class="col-md-12 mt-3">
                <label class="form-label">Catatan</label>
                <textarea class="form-control" disabled>{{ $tpohdr->noteh }}</textarea>
            </div>
        </div>

        <hr class="my-4">

        <div class="row">
            <h3 class="my-2">Detail Barang PO</h3>
            <div class="accordion" id="accordionPoBarang">
                @foreach($tpohdr->tpodtl as $i => $d)
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="heading-{{ $i }}">
                            <button class="accordion-button {{ $i > 0 ? 'collapsed' : '' }}" type="button"
                                    data-bs-toggle="collapse" data-bs-target="#barang-{{ $i }}"
                                    aria-expanded="{{ $i == 0 ? 'true' : 'false' }}" aria-controls="barang-{{ $i }}">
                                {{ $i+1 }}. {{ $d->opron }} - {{ $d->mpromas->prona ?? '-' }}
                            </button>
                        </h2>
                        <div id="barang-{{ $i }}" class="accordion-collapse collapse {{ $i == 0 ? 'show' : '' }}">
                            <div class="accordion-body">
                                <div class="row">
                                    <div class="col-md-6 mt-3">
                                        <label class="form-label">Barang</label>
                                        <input type="text" class="form-control" value="{{ $d->opron }} - {{ $d->mpromas->prona }}" disabled>
                                    </div>
                                    <div class="col-md-6 mt-3">
                                        <label class="form-label">Harga</label>
                                        <input type="text" class="form-control" value="{{ number_format($d->price,2,',','.') }}" disabled>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-4 mt-3">
                                        <label class="form-label">Qty</label>
                                        <input type="text" class="form-control" value="{{ $d->poqty }}" disabled>
                                    </div>
                                    <div class="col-md-4 mt-3">
                                        <label class="form-label">Berat (Kg)</label>
                                        <input type="text" class="form-control" value="{{ $d->berat }}" disabled>
                                    </div>
                                    <div class="col-md-4 mt-3">
                                        <label class="form-label">Diskon (%)</label>
                                        <input type="text" class="form-control" value="{{ $d->odisp }}" disabled>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6 mt-3">
                                        <label class="form-label">Ekspetasi Pengiriman</label>
                                        <input type="text" class="form-control" value="{{ date('d-m-Y', strtotime($d->edld)) }}" disabled>
                                    </div>
                                    <div class="col-md-6 mt-3">
                                        <label class="form-label">Ekspetasi Kedatangan</label>
                                        <input type="text" class="form-control" value="{{ date('d-m-Y', strtotime($d->earrd)) }}" disabled>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-3 mt-3">
                                        <label class="form-label">HS Code</label>
                                        <input type="text" class="form-control" value="{{ $d->hsn }}" disabled>
                                    </div>
                                    <div class="col-md-3 mt-3">
                                        <label class="form-label">BM (%)</label>
                                        <input type="text" class="form-control" value="{{ $d->bm }}" disabled>
                                    </div>
                                    <div class="col-md-3 mt-3">
                                        <label class="form-label">BMT (%)</label>
                                        <input type="text" class="form-control" value="{{ $d->bmt }}" disabled>
                                    </div>
                                    <div class="col-md-3 mt-3">
                                        <label class="form-label">PPH (%)</label>
                                        <input type="text" class="form-control" value="{{ $d->pphd }}" disabled>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12 mt-3">
                                        <label class="form-label">Catatan</label>
                                        <textarea class="form-control" disabled>{{ $d->noted }}</textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <div class="mt-3 d-flex justify-content-between">
            <a href="{{ route('tpo.index') }}" class="btn btn-secondary">Kembali</a>
            <a href="{{ route('pdf.preview', $tpohdr->pono) }}" class="btn" style="background-color:#4456f1; color:#fff"><i class="bi bi-file-earmark-pdf"></i> Preview PDF Data PO</a>
            <a href="{{ route('pdf.download', $tpohdr->pono) }}" class="btn btn-success btn-sm"><i class="bi bi-file-earmark-arrow-down"></i> Download PDF</a>
        </div>
    </section>
</main>
@endsection
