@extends('layout.main')

@section('container')
<main id="main" class="main">
    <div class="pagetitle">
        <h1>Detail Data BL AWB</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('blawb.index') }}">List BL AWB</a></li>
                <li class="breadcrumb-item active">Detail</li>
            </ol>
        </nav>
    </div>

    <section class="section">
        <div class="card p-3">
            <div class="row">
                <div class="col-md-6 mt-3">
                    <label for="rinum" class="form-label">Receipt Number</label>
                    <input type="text" class="form-control" value="RI{{ $tbolh->rinum }}" disabled>
                </div>

                <div class="col-md-6 mt-3">
                    <label for="nocal" class="form-label">Calculation Number</label>
                    <input type="text" class="form-control" value="{{ $tbolh->nocal }}" disabled>
                </div>

                <div class="col-md-6 mt-3">
                    <label for="blnum" class="form-label">BL/AWB No.</label>
                    <input type="text" class="form-control" value="{{ $tbolh->blnum }}" disabled>
                </div>

                <div class="col-md-6 mt-3">
                    <label for="bldat" class="form-label">BL/AWB Date</label>
                    <input type="text" class="form-control"
                           value="{{ \Carbon\Carbon::parse($tbolh->bldat)->format('d/m/Y') }}" disabled>
                </div>

                <div class="col-md-6 mt-3">
                    <label for="shpln" class="form-label">Shipping Line</label>
                    <input type="text" class="form-control" value="{{ $tbolh->shpln }}" disabled>
                </div>

                <div class="col-md-6 mt-3">
                    <label for="vesel" class="form-label">Vessel/Flight</label>
                    <input type="text" class="form-control" value="{{ $tbolh->vesel }}" disabled>
                </div>

                <div class="col-md-6 mt-3">
                    <label for="pload" class="form-label">Port of Loading</label>
                    <input type="text" class="form-control" value="{{ $tbolh->pload }}" disabled>
                </div>

                <div class="col-md-6 mt-3">
                    <label for="pdest" class="form-label">Port of Destination</label>
                    <input type="text" class="form-control" value="{{ $tbolh->pdest }}" disabled>
                </div>

                <div class="col-md-4 mt-3">
                    <label for="etds" class="form-label">ETD</label>
                    <input type="text" class="form-control"
                           value="{{ \Carbon\Carbon::parse($tbolh->etds)->format('d/m/Y') }}" disabled>
                </div>

                <div class="col-md-4 mt-3">
                    <label for="etah" class="form-label">ETA Harbour</label>
                    <input type="text" class="form-control"
                           value="{{ \Carbon\Carbon::parse($tbolh->etah)->format('d/m/Y') }}" disabled>
                </div>

                <div class="col-md-4 mt-3">
                    <label for="etaw" class="form-label">ETA Warehouse</label>
                    <input type="text" class="form-control"
                           value="{{ \Carbon\Carbon::parse($tbolh->etaw)->format('d/m/Y') }}" disabled>
                </div>

                <div class="col-md-6 mt-3">
                    <label for="supno" class="form-label">Supplier</label>
                    <input type="text" class="form-control"
                           value="{{ $tbolh->vendor->supna ?? '-' }}" disabled>
                </div>
            </div>

            <hr class="my-4">

            <div class="row">
                <h3 class="my-2">Detail Biaya</h3>

                <div class="col-md-6 mt-3">
                    <label for="I01" class="form-label">Biaya Transport</label>
                    <input type="text" class="form-control currency"
                        value="{{ formatRupiahNull($biaya['I01'] ?? 0, 0, ',', '.') }}" disabled>
                </div>

                <div class="col-md-6 mt-3">
                    <label for="I02" class="form-label">Biaya Asuransi</label>
                    <input type="text" class="form-control currency"
                        value="{{ formatRupiahNull($biaya['I02'] ?? 0, 0, ',', '.') }}" disabled>
                </div>

                <div class="col-md-6 mt-3">
                    <label for="I03" class="form-label">Biaya Sewa Gudang</label>
                    <input type="text" class="form-control currency"
                        value="{{ formatRupiahNull($biaya['I03'] ?? 0, 0, ',', '.') }}" disabled>
                </div>

                <div class="col-md-6 mt-3">
                    <label for="I04" class="form-label">Biaya Handling Charges</label>
                    <input type="text" class="form-control currency"
                        value="{{ formatRupiahNull($biaya['I04'] ?? 0, 0, ',', '.') }}" disabled>
                </div>

                <div class="col-md-6 mt-3">
                    <label for="I05" class="form-label">Freight Collect</label>
                    <input type="text" class="form-control currency"
                        value="{{ formatRupiahNull($biaya['I05'] ?? 0, 0, ',', '.') }}" disabled>
                </div>

                <div class="col-md-6 mt-3">
                    <label for="I06" class="form-label">BEA Masuk</label>
                    <input type="text" class="form-control currency"
                        value="{{ formatRupiahNull($biaya['I06'] ?? 0, 0, ',', '.') }}" disabled>
                </div>

                <div class="col-md-6 mt-3">
                    <label for="I07" class="form-label">Biaya Lain - lain</label>
                    <input type="text" class="form-control currency"
                        value="{{ formatRupiahNull($biaya['I07'] ?? 0, 0, ',', '.') }}" disabled>
                </div>
            </div>

            <div class="mt-4">
                <a href="{{ route('blawb.index') }}" class="btn btn-secondary">Kembali</a>
            </div>
        </div>
    </section>
</main>
@endsection