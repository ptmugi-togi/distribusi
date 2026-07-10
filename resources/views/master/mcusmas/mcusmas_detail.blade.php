@extends('layout.main')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/global.css') }}">
@endpush

@section('container')
<main id="main" class="main">
    <div class="d-flex justify-content-between align-items-center">
        <div class="pagetitle">
            <h1>Detail Customer</h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('cusmas.index') }}">List Customer</a></li>
                    <li class="breadcrumb-item active">Detail Customer</li>
                </ol>
            </nav>
        </div>
    </div>

    <section class="section">
        <div class="card p-3 shacustw-sm">
            {{-- Header --}}
            <div class="row">
                <div class="col-md-6 mt-3">
                    <label class="form-label">Branch</label>
                    <input type="text" class="form-control" value="{{ $cust->braco }}" disabled>
                </div>

                <div class="col-md-6 mt-3">
                    <label class="form-label">Join Date</label>
                    <input type="text" class="form-control" value="{{ \Carbon\Carbon::parse($cust->dopen)->format('d/m/Y') }}" disabled>
                </div>

                <div class="col-md-4 mt-3">
                    <label class="form-label">Customer No.</label>
                    <input type="text" class="form-control" value="{{ $cust->cusno }}" disabled>
                </div>

                <div class="col-md-4 mt-3">
                    <label class="form-label">Customer Name</label>
                    <input type="text" class="form-control" value="{{ $cust->cusna }}" disabled>
                </div>

                <div class="col-md-4 mt-3">
                    <label class="form-label">Bill Name</label>
                    <input type="text" class="form-control" value="{{ $cust->cusna }}" disabled>
                </div>

                <div class="col-md-4 mt-3">
                    <label class="form-label">Email</label>
                    <input type="text" class="form-control" value="{{ $cust->email }}" disabled>
                </div>

                <div class="col-md-4 mt-3">
                    <label class="form-label">NPWP / NIK</label>
                    <input type="text" class="form-control" value="{{ $cust->taxrn }}" disabled>
                </div>

                <div class="col-md-4 mt-3">
                    <label class="form-label">NITKU</label>
                    <input type="text" class="form-control" value="{{ $cust->nitku }}" disabled>
                </div>

                <div class="col-md-6 mt-3">
                    <label class="form-label">Title</label>
                    <input type="text" class="form-control" value="{{ $cust->title }}" disabled>
                </div>

                <div class="col-md-6 mt-3">
                    <label class="form-label">PKP</label>
                    <input type="text" class="form-control" value="{{ $cust->pkp }}" disabled>
                </div>

                <div class="col-md-6 mt-3">
                    <label class="form-label">Provinsi</label>
                    <input type="text" class="form-control" value="{{ $cust->prov->provinsi }}" disabled>
                </div>

                <div class="col-md-6 mt-3">
                    <label class="form-label">Kabupaten Kota</label>
                    <input type="text" class="form-control" value="{{ $cust->kabkota->kabupaten }}" disabled>
                </div>

                <div class="col-md-6 mt-3">
                    <label class="form-label">Kode Pos</label>
                    <input type="text" class="form-control" value="{{ $cust->opost }}" disabled>
                </div>

                <div class="col-md-6 mt-3">
                    <label class="form-label">Alamat</label>
                    <textarea class="form-control" rows="2" disabled>{{ $cust->address }}</textarea>
                </div>

                <div class="col-md-4 mt-3">
                    <label class="form-label">Phone</label>
                    <input type="text" class="form-control" value="{{ $cust->offph }}" disabled>
                </div>

                <div class="col-md-4 mt-3">
                    <label class="form-label">Fax</label>
                    <input type="text" class="form-control" value="{{ $cust->offax }}" disabled>
                </div>

                <div class="col-md-4 mt-3">
                    <label class="form-label">Contact</label>
                    <input type="text" class="form-control" value="{{ $cust->ofcon }}" disabled>
                </div>

                <div class="col-md-4 mt-3">
                    <label class="form-label">TOP</label>
                    <input type="text" class="form-control" value="{{ $cust->topay }}" disabled>
                </div>

                <div class="col-md-4 mt-3">
                    <label class="form-label">Industry</label>
                    <input type="text" class="form-control" value="{{ $cust->industry->descr_cindu }}" disabled>
                </div>

                <div class="col-md-4 mt-3">
                    <label class="form-label">LAUID</label>
                    <input type="text" class="form-control" value="{{ $cust->lauid }}" disabled>
                </div>
            </div>
            <div class="mt-4">
                <a href="{{ route('cusmas.index') }}" class="btn btn-secondary">Kembali</a>
            </div>
        </div>
    </section>
</main>
@endsection
