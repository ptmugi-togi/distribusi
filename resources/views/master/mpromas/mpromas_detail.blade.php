@extends('layout.main')

@section('container')
<main id="main" class="main">

    <div class="pagetitle">
        <h1>Detail Produk ({{ $mpromas->opron }} - {{ $mpromas->prona }})</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('mpromas.index') }}">List Produk</a></li>
                <li class="breadcrumb-item active">Detail Produk</li>
            </ol>
        </nav>
    </div>

    <section class="section">
        <div class="card">
            <div class="card-body pt-4">
                <div class="row g-3">
                    <div class="col-md-2">
                        <label class="form-label">Product No.</label>
                        <input type="text" class="form-control text-uppercase" id="masterOpron" name="opron" value="{{ $mpromas->opron }}" disabled>
                    </div>

                    <div class="col-md-5">
                        <label class="form-label">Product Name</label>
                        <input type="text" class="form-control text-uppercase" id="prona" name="prona" value="{{ $mpromas->prona }}" disabled>
                    </div>

                    <div class="col-md-5">
                        <label class="form-label">Prod Sup. Name</label>
                        <input type="text" class="form-control text-uppercase" id="iname" name="iname" value="{{ $mpromas->iname }}" disabled>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Standard Unit</label>
                        <input type="text" class="form-control text-uppercase" id="stdqu" name="stdqu" value="{{ $mpromas->stdqu }}" disabled>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Inventory Type</label>
                        <input type="text" class="form-control text-uppercase" id="itype" name="itype" value="{{ $mpromas->itype_id }} - {{ $mpromas->mitype->descr_itype }}" disabled>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Brand</label>
                        <input type="text" class="form-control text-uppercase" id="brand" name="brand" value="{{ $mpromas->brand }}" disabled>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Product Group</label>
                        <input type="text" class="form-control text-uppercase" id="grup" name="grup" value="{{ $mpromas->pgrup }} - {{ $mpromas->mpgrup->descr }}" disabled>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Product Sub-Group</label>
                        <input type="text" class="form-control text-uppercase" id="sgrup" name="sgrup" value="{{ $mpromas->sgrup_id }} - {{ $mpromas->msgrup->descr_sgrup }}" disabled>
                    </div>
                    
                    <div class="col-md-4">
                        <label class="form-label">Sub - SubGroup (MKT)</label>
                        <input type="text" class="form-control text-uppercase" id="ssgrup" name="ssgrup" value="{{ $mpromas->ssgrup_id }} - {{ $mpromas->mssgrup->descr_ssgrup }}" disabled>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">(LOG)</label>
                        <input type="text" class="form-control text-uppercase" id="log" name="log" value="{{ $mpromas->lssgrup }} - {{ $mpromas->mlssgrup->descr_ssgrup }}" disabled>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Status</label>
                        <input type="text" class="form-control text-uppercase" id="status" name="status" value="{{ $mpromas->status == 'A' ? 'Active' : ($mpromas->status == 'N' ? 'Non Active' : 'Discontinued') }}" disabled>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Garantine (days)</label>
                        <input type="number" class="form-control" id="garan" name="garan" value="{{ $mpromas->garan }}" disabled>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Product Capacity</label>
                        <input type="text" class="form-control text-uppercase" id="capac" name="capac" value="{{ $mpromas->capac }}" disabled>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Product Platform</label>
                        <input type="text" class="form-control text-uppercase" id="platf" name="platf" value="{{ $mpromas->platf }}" disabled>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Weight / UOM (KG)</label>
                        <input type="number" class="form-control text-uppercase" id="weigh" name="weigh" value="{{ $mpromas->weigh }}" disabled>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Measurement (T x L x P) (mm)</label>
                        <div class="d-flex align-items-center">
                            <input type="number" class="form-control" id="meast" name="meast" value="{{ $mpromas->meast }}" disabled>
                            <span class="mx-2 fw-bold">X</span>
                            <input type="number" class="form-control" id="measl" name="measl" value="{{ $mpromas->measl }}" disabled>
                            <span class="mx-2 fw-bold">X</span>
                            <input type="number" class="form-control" id="measp" name="measp" value="{{ $mpromas->measp }}" disabled>
                        </div>
                    </div>
                    
                    <div class="col-md-4">
                        <label class="form-label">Ijin Type</label>
                        <input type="text" class="form-control text-uppercase" id="ijtype" name="ijtype" value="{{ $mpromas->ijtype }}" disabled>
                    </div>
                    
                    <div class="col-md-4">
                        <label class="form-label">Minimum Order</label>
                        <input type="text" class="form-control text-uppercase" id="mstok" name="mstok" value="{{ $mpromas->mstok }}" disabled>
                    </div>
                    
                    <div class="col-md-4">
                        <label class="form-label">Part No.</label>
                        <input type="text" class="form-control text-uppercase" id="spnum" name="spnum" value="{{ $mpromas->spnum }}" disabled>
                    </div>
                    
                    <div class="col-md-4">
                        <label class="form-label">A/C Inventory</label>
                        <input type="text" class="form-control text-uppercase" id="acinv" name="acinv" value="{{ $mpromas->acinv }}" disabled>
                    </div>
                    
                    <div class="col-md-4">
                        <label class="form-label">A/C COGS</label>
                        <input type="text" class="form-control text-uppercase" id="achpp" name="achpp" value="{{ $mpromas->achpp }}" disabled>
                    </div>
                    
                    <div class="col-md-4">
                        <label class="form-label">A/C Gross Sales</label>
                        <input type="text" class="form-control text-uppercase" id="acals" name="acals" value="{{ $mpromas->acals }}" disabled>
                    </div>
                    
                    <div class="col-md-4">
                        <label class="form-label">A/C Sales Discount</label>
                        <input type="text" class="form-control text-uppercase" id="acdis" name="acdis" value="{{ $mpromas->acdis }}" disabled>
                    </div>
                    
                    <div class="col-md-4">
                        <label class="form-label">This Product Have a Bill of Material</label>
                        <input type="text" class="form-control text-uppercase" id="pbilp" name="pbilp" value="{{ $mpromas->pbilp == 'Y' ? 'Yes' : 'No' }}" disabled>
                    </div>
                </div>
            </div>
        </div>
        <div class="d-flex justify-content-between mt-3">
            <a href="{{ route('mpromas.index') }}" class="btn btn-secondary">Kembali</a>
        </div>
    </section>
</main>
@endsection