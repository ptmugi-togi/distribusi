@extends('layout.main')

@section('container')
<main id="main" class="main">

    <div class="pagetitle">
        <h1>Detail Form Code</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('formc.index') }}">List Form Code</a></li>
                <li class="breadcrumb-item active">Detail Form Code</li>
            </ol>
        </nav>
    </div>

    <section class="section">
        <div class="card">
            <div class="card-body pt-4">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Branch</label>
                        <input type="text" class="form-control text-uppercase" id="braco" name="braco" value="{{ $formc->braco }}" disabled>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Form Code</label>
                        <input type="text" class="form-control text-uppercase" id="formc" name="formc" value="{{ $formc->formc }}" disabled>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Description</label>
                        <input type="text" class="form-control text-uppercase" id="descr" name="descr" value="{{ $formc->descr }}" disabled>
                    </div>

                    <div class="row mt-3">
                        <h5>Approval Purposes 1</h5>
                        <div class="col-md-6">
                            <label class="form-label">Person Position</label>
                            <input type="text" class="form-control text-uppercase" id="pos1" name="pos1" value="{{ $formc->pos1 }}" disabled>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Person Name</label>
                            <input type="text" class="form-control text-uppercase" id="name1" name="name1" value="{{ $formc->name1 }}" disabled>
                        </div>
                    </div>

                    <div class="row mt-3">
                        <h5>Approval Purposes 2</h5>
                        <div class="col-md-6">
                            <label class="form-label">Person Position</label>
                            <input type="text" class="form-control text-uppercase" id="pos2" name="pos2" value="{{ $formc->pos2 }}" disabled>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Person Name</label>
                            <input type="text" class="form-control text-uppercase" id="name2" name="name2" value="{{ $formc->name2 }}" disabled>
                        </div>
                    </div>

                    <div class="row mt-3">
                        <h5>Approval Purposes 3</h5>
                        <div class="col-md-6">
                            <label class="form-label">Person Position</label>
                            <input type="text" class="form-control text-uppercase" id="pos3" name="pos3" value="{{ $formc->pos3 }}" disabled>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Person Name</label>
                            <input type="text" class="form-control text-uppercase" id="name3" name="name3" value="{{ $formc->name3 }}" disabled>
                        </div>
                    </div>

                    <div class="row mt-3">
                        <h5>Approval Purposes 4</h5>
                        <div class="col-md-6">
                            <label class="form-label">Person Position</label>
                            <input type="text" class="form-control text-uppercase" id="pos4" name="pos4" value="{{ $formc->pos4 }}" disabled>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Person Name</label>
                            <input type="text" class="form-control text-uppercase" id="name4" name="name4" value="{{ $formc->name4 }}" disabled>
                        </div>
                    </div>

                    <div class="row mt-3">
                        <h5>Distribution of Document</h5>
                        <div class="col-md-12">
                            <input type="text" class="form-control text-uppercase" id="docd1" name="docd1" value="{{ $formc->docd1 }}" disabled>
                        </div>
                        <div class="col-md-12">
                            <input type="text" class="form-control text-uppercase" id="docd2" name="docd2" value="{{ $formc->docd2 }}" disabled>
                        </div>
                    </div>
                    <div class="d-flex justify-content-between mt-3">
                        <a href="{{ route('formc.index') }}" class="btn btn-secondary">Kembali</a>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>
@endsection