@extends('layout.main')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/global.css') }}">
@endpush

@section('container')
    <main id="main" class="main">
        <div class="d-flex justify-content-between align-items-center">
            <div class="pagetitle">
                <h1>Detail Garantie Card Release</h1>
                <nav>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('gr.index') }}">List Garantie Card Release</a></li>
                        <li class="breadcrumb-item active">Detail ({{ $header->id }})</li>
                    </ol>
                </nav>
            </div>
            <div class="card">
                <h5 class="p-2 mb-0"><b>Branch : {{ $header->braco }}</b></h5>
            </div>
        </div>

        <section class="section">
            <div class="card">
                <div class="card-body pt-3">
                    <div class="row">
                        <div class="col-md-6 mt-3">
                            <label class="form-label fw-bold">No. Garantie Card Release</label>
                            <input type="text" class="form-control" value="{{ $header->id }}" readonly style="background-color:#e9ecef">
                        </div>

                        <div class="col-md-6 mt-3">
                            <label class="form-label fw-bold">Release Date (DD/MM/YYYY)</label>
                            <input type="text" class="form-control" value="{{ $header->created_at ? \Carbon\Carbon::parse($header->created_at)->format('d-m-Y') : '-' }}" readonly style="background-color:#e9ecef">
                        </div>

                        <div class="col-md-6 mt-3">
                            <label class="form-label fw-bold">DO</label>
                            <input type="text" class="form-control" value="{{ $header->dofcd }} - {{ $header->donom }}" readonly style="background-color:#e9ecef">
                        </div>

                        <div class="col-md-6 mt-3">
                            <label class="form-label fw-bold">DO Date</label>
                            <input type="text" class="form-control" value="{{ $header->dodat ? \Carbon\Carbon::parse($header->dodat)->format('d-m-Y') : '-' }}" readonly style="background-color:#e9ecef">
                        </div>

                        <div class="col-md-6 mt-3">
                            <label class="form-label fw-bold">Customer</label>
                            <input type="text" class="form-control" value="{{ trim($header->cusno) }} - {{ trim($header->cusna) }}" readonly style="background-color:#e9ecef">
                        </div>

                        <div class="col-md-6 mt-3">
                            <label class="form-label fw-bold">Delivery Address</label>
                            <textarea class="form-control" rows="2" readonly style="background-color:#e9ecef">{{ $header->address ?? '-' }}</textarea>
                        </div>

                        <div class="col-md-6 mt-3">
                            <label class="form-label fw-bold">Garantie Period</label>
                            <div class="d-flex align-items-center gap-2">
                                <input type="text" class="form-control" value="{{ $header->gmcfr ? \Carbon\Carbon::parse($header->gmcfr)->format('d-m-Y') : '-' }}" readonly style="background-color:#e9ecef">
                                <span class="fw-bold fs-5">+</span>
                                <input type="text" class="form-control text-center" value="{{ $header->grdays ?? 0 }} days" readonly style="max-width: 100px; background-color:#e9ecef">
                                <span class="text-nowrap">=</span>
                                <input type="text" class="form-control" value="{{ $header->gmcto ? \Carbon\Carbon::parse($header->gmcto)->format('d-m-Y') : '-' }}" readonly style="background-color:#e9ecef">
                            </div>
                        </div>

                        <div class="col-md-6 mt-3">
                            <label class="form-label fw-bold">Tera Period</label>
                            <div class="d-flex align-items-center gap-2">
                                <input type="text" class="form-control" value="{{ $header->teraf ? \Carbon\Carbon::parse($header->teraf)->format('d-m-Y') : '-' }}" readonly style="background-color:#e9ecef">
                                <span class="fw-bold fs-5">+</span>
                                <input type="text" class="form-control text-center" value="{{ $header->trdays ?? 0 }} days" readonly style="max-width: 100px; background-color:#e9ecef">
                                <span class="text-nowrap">=</span>
                                <input type="text" class="form-control" value="{{ $header->terat ? \Carbon\Carbon::parse($header->terat)->format('d-m-Y') : '-' }}" readonly style="background-color:#e9ecef">
                            </div>
                        </div>

                        <div class="col-md-6 mt-3">
                            <label class="form-label fw-bold">Print Tera Period as</label>
                            <input type="text" class="form-control" value="{{ $header->terap ?? '-' }}" readonly style="background-color:#e9ecef">
                        </div>

                        <div class="col-md-6 mt-3">
                            <label class="form-label fw-bold">Tera Certificate</label>
                            <input type="text" class="form-control" value="{{ $header->terac ?? '-' }}" readonly style="background-color:#e9ecef">
                        </div>

                        <div class="col-md-12 mt-3">
                            <label class="form-label fw-bold">Notes</label>
                            <textarea class="form-control" rows="2" readonly style="background-color:#e9ecef">{{ $header->noteh ?? '-' }}</textarea>
                        </div>
                    </div>

                    <!-- Tabel Daftar Produk -->
                    <div class="col-md-12 mt-4">
                        <label class="form-label fw-bold">Daftar Produk Garansi</label>
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped">
                                <thead class="table-light">
                                    <tr>
                                        <th class="text-center" style="width: 60px;">No</th>
                                        <th>Product No</th>
                                        <th>Product Name</th>
                                        <th>Serial No</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($items as $index => $item)
                                        <tr>
                                            <td class="text-center">{{ $index + 1 }}</td>
                                            <td>{{ $item->opron }}</td>
                                            <td>{{ $item->prona ?? '-' }}</td>
                                            <td>{{ $item->lotno ?? '-' }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="mt-4 d-flex justify-content-between">
                        <a href="{{ route('gr.index') }}" class="btn btn-secondary">Kembali</a>
                    </div>
                </div>
            </div>
        </section>
    </main>
@endsection