@extends('layout.main')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/global.css') }}">
@endpush

@section('container')
<main id="main" class="main">
    <div class="d-flex justify-content-between align-items-center">
        <div class="pagetitle">
            <h1>Detail Shipto</h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('mstmas.index') }}">List Shipto</a></li>
                    <li class="breadcrumb-item active">Detail Shipto</li>
                </ol>
            </nav>
        </div>
    </div>

    <section class="section">
        <div class="card p-3 shacustw-sm">
            {{-- Header --}}
            @php
                $header = $shiptos->first();
            @endphp
            <div class="row">
                <div class="col-md-6 mt-3">
                    <label class="form-label">Branch</label>
                    <input type="text" class="form-control" value="{{ $header->braco }}" disabled>
                </div>

                <div class="col-md-6 mt-3">
                    <label class="form-label">Customer</label>
                    <input type="text" class="form-control" value="{{ $header->cusno }} - {{ $header->cusmas->cusna }}" disabled>
                </div>


            </div>
            
            <div class="accordion mt-3" id="accordionShipto">
                @foreach($shiptos as $i => $shipto)
                <div class="accordion-item">
                    <h2 class="accordion-header">
                        <button class="accordion-button {{ $i ? 'collapsed' : '' }}" data-bs-toggle="collapse" data-bs-target="#shipto{{ $i }}">
                          Shipto:  {{ $shipto->shpto }} - {{ $shipto->shpnm }} - {{ $shipto->deliveryaddress }}
                        </button>
                    </h2>
                    <div id="shipto{{ $i }}" class="accordion-collapse collapse {{ $i == 0 ? 'show' : '' }}" data-bs-parent="#accordionShipto">
                        <div class="accordion-body">
                            <div class="row">
                                <div class="col-md-6 mt-3">
                                    <label class="form-label">Ship Name</label>
                                    <input class="form-control" value="{{ $shipto->shpnm }}" disabled>
                                </div>

                                <div class="col-md-6 mt-3">
                                    <label class="form-label">Address</label>
                                    <input class="form-control" value="{{ $shipto->deliveryaddress }}" disabled>
                                </div>

                                <div class="col-md-6 mt-3">
                                    <label class="form-label">Phone</label>
                                    <input class="form-control" value="{{ $shipto->phone }}" disabled>
                                </div>

                                <div class="col-md-6 mt-3">
                                    <label class="form-label">Fax</label>
                                    <input class="form-control" value="{{ $shipto->fax }}" disabled>
                                </div>

                                <div class="col-md-6 mt-3">
                                    <label class="form-label">Contact Person</label>
                                    <input class="form-control" value="{{ $shipto->contp }}" disabled>
                                </div>

                                <div class="col-md-6 mt-3">
                                    <label class="form-label">Nitku</label>
                                    <input class="form-control" value="{{ $shipto->nitku }}" disabled>
                                </div>

                                <div class="col-md-6 mt-3">
                                    <label class="form-label">Provinsi</label>
                                    <input class="form-control" value="{{ optional($shipto->prov)->provinsi }}" disabled>
                                </div>

                                <div class="col-md-6 mt-3">
                                    <label class="form-label">Kabupaten</label>
                                    <input class="form-control" value="{{ optional($shipto->kabkota)->kabupaten }}" disabled>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            <div class="mt-4">
                <a href="{{ route('mstmas.index') }}" class="btn btn-secondary">Kembali</a>
            </div>
        </div>
    </section>
</main>
@endsection
