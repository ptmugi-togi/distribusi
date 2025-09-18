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
                <li class="breadcrumb-item"><a href="{{ route('tpohdr.index') }}">List PO</a></li>
                <li class="breadcrumb-item active">Detail PO</li>
            </ol>
        </nav>
    </div>

    <section class="section">
        <div class="row">
            <div class="col-md-6 mt-3">
                <label class="form-label">Form Code</label>
                <input type="text" class="form-control" value="{{ $tpohdr->formc }}" readonly>
            </div>

            <div class="col-md-6 mt-3">
                <label class="form-label">Nomor PO</label>
                <input type="text" class="form-control" value="{{ $tpohdr->pono }}" readonly>
            </div>

            <div class="col-md-6 mt-3">
                <label class="form-label">Tanggal PO</label>
                <input type="text" class="form-control" value="{{ $tpohdr->podat }}" readonly>
            </div>

            <div class="col-md-6 mt-3">
                <label class="form-label">Supplier</label>
                <input type="text" class="form-control" value="{{ $tpohdr->supno }} - {{ $tpohdr->vendor->supna ?? '' }}" readonly>
            </div>

            <div class="col-md-6 mt-3">
                <label class="form-label">Tipe PO</label>
                <input type="text" class="form-control" value="{{ $tpohdr->potype }}" readonly>
            </div>

            <div class="col-md-6 mt-3">
                <label class="form-label">Term Of Payment</label>
                <input type="text" class="form-control" value="{{ $tpohdr->topay }} Hari ({{ $tpohdr->tdesc }})" readonly>
            </div>

            <div class="col-md-6 mt-3">
                <label class="form-label">Currency Code</label>
                <input type="text" class="form-control" value="{{ $tpohdr->curco }}" readonly>
            </div>

            <div class="col-md-6 mt-3">
                <label class="form-label">Pengiriman</label>
                <input type="text" class="form-control" value="{{ $tpohdr->shvia }}" readonly>
            </div>

            <div class="col-md-6 mt-3">
                <label class="form-label">Kode Pengirim</label>
                <input type="text" class="form-control" value="{{ $tpohdr->delco }}" readonly>
            </div>

            <div class="col-md-6 mt-3">
                <label class="form-label">Nama Pengirim</label>
                <input type="text" class="form-control" value="{{ $tpohdr->delnm }}" readonly>
            </div>

            <div class="col-md-6 mt-3">
                <label class="form-label">Kontak Pengirim</label>
                <input type="text" class="form-control" value="{{ $tpohdr->dconp }}" readonly>
            </div>

            <div class="col-md-6 mt-3">
                <label class="form-label">Diskon (%)</label>
                <input type="text" class="form-control" value="{{ $tpohdr->diper }}" readonly>
            </div>

            <div class="col-md-6 mt-3">
                <label class="form-label">Tax Rate (%)</label>
                <input type="text" class="form-control" value="{{ $tpohdr->vatax }}" readonly>
            </div>

            <div class="col-md-6 mt-3">
                <label class="form-label">PPH (%)</label>
                <input type="text" class="form-control" value="{{ $tpohdr->pph }}" readonly>
            </div>

            <div class="col-md-6 mt-3">
                <label class="form-label">Meterai</label>
                <input type="text" class="form-control" value="{{ $tpohdr->stamp }}" readonly>
            </div>

            <div class="col-md-12 mt-3">
                <label class="form-label">Catatan</label>
                <textarea class="form-control" readonly>{{ $tpohdr->noteh }}</textarea>
            </div>
        </div>

        <hr class="my-4">

        <h3 class="my-2">Detail Barang PO</h3>
        <div class="accordion" id="accordionPoBarang">
            @foreach($tpohdr->tpodtl as $i => $d)
                <div class="accordion-item">
                    <h2 class="accordion-header" id="heading-{{ $i }}">
                        <button class="accordion-button {{ $i > 0 ? 'collapsed' : '' }}" type="button"
                                data-bs-toggle="collapse" data-bs-target="#barang-{{ $i }}"
                                aria-expanded="{{ $i == 0 ? 'true' : 'false' }}" aria-controls="barang-{{ $i }}">
                            Barang PO {{ $i+1 }}
                        </button>
                    </h2>
                    <div id="barang-{{ $i }}" class="accordion-collapse collapse {{ $i == 0 ? 'show' : '' }}">
                        <div class="accordion-body">
                            <div class="row">
                                <div class="col-md-6 mt-3">
                                    <label class="form-label">Barang</label>
                                    <input type="text" class="form-control" value="{{ $d->opron }} - {{ $d->prona }}" readonly>
                                </div>
                                <div class="col-md-6 mt-3">
                                    <label class="form-label">Qty</label>
                                    <input type="text" class="form-control" value="{{ $d->poqty }}" readonly>
                                </div>
                                <div class="col-md-6 mt-3">
                                    <label class="form-label">Harga</label>
                                    <input type="text" class="form-control" value="{{ number_format($d->price,0,',','.') }}" readonly>
                                </div>
                                <div class="col-md-6 mt-3">
                                    <label class="form-label">Berat (Kg)</label>
                                    <input type="text" class="form-control" value="{{ $d->weigh }}" readonly>
                                </div>
                                <div class="col-md-6 mt-3">
                                    <label class="form-label">Diskon (%)</label>
                                    <input type="text" class="form-control" value="{{ $d->odisp }}" readonly>
                                </div>
                                <div class="col-md-6 mt-3">
                                    <label class="form-label">Ekspetasi Pengiriman</label>
                                    <input type="text" class="form-control" value="{{ $d->edeld }}" readonly>
                                </div>
                                <div class="col-md-6 mt-3">
                                    <label class="form-label">Ekspetasi Kedatangan</label>
                                    <input type="text" class="form-control" value="{{ $d->earrd }}" readonly>
                                </div>
                                <div class="col-md-6 mt-3">
                                    <label class="form-label">HS Code</label>
                                    <input type="text" class="form-control" value="{{ $d->hsn }}" readonly>
                                </div>
                                <div class="col-md-6 mt-3">
                                    <label class="form-label">BM (%)</label>
                                    <input type="text" class="form-control" value="{{ $d->bm }}" readonly>
                                </div>
                                <div class="col-md-6 mt-3">
                                    <label class="form-label">BMT (%)</label>
                                    <input type="text" class="form-control" value="{{ $d->bmt }}" readonly>
                                </div>
                                <div class="col-md-6 mt-3">
                                    <label class="form-label">PPH (%)</label>
                                    <input type="text" class="form-control" value="{{ $d->pphd }}" readonly>
                                </div>
                                <div class="col-md-12 mt-3">
                                    <label class="form-label">Catatan</label>
                                    <textarea class="form-control" readonly>{{ $d->noted }}</textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="mt-3 d-flex justify-content-between">
            <a href="{{ route('tpohdr.index') }}" class="btn btn-secondary">Kembali</a>
        </div>
    </section>
</main>
@endsection
