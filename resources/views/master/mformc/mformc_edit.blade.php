@extends('layout.main')

@section('container')
<main id="main" class="main">

    <div class="pagetitle">
        <h1>Edit Form Code</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('formc.index') }}">List Form Code</a></li>
                <li class="breadcrumb-item active">Edit Form Code</li>
            </ol>
        </nav>
    </div>

    <section class="section">
        <div class="card">
            <div class="card-body pt-4">

                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" id="form-formc" action="{{ route('formc.update', $formc->bracoformc) }}" class="row g-3">
                    @csrf
                    @method('PUT')

                    <div class="col-md-6">
                        <label class="form-label">Branch</label>
                        <input type="text" class="form-control text-uppercase" id="braco" name="braco" value="{{ $formc->braco }}" readonly style="background-color:#e9ecef">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Form Code</label><span class="text-danger"> *</span>
                        <input type="text" class="form-control text-uppercase" id="formc" name="formc" value="{{ $formc->formc }}" required>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Description</label><span class="text-danger"> *</span>
                        <input type="text" class="form-control text-uppercase" id="descr" name="descr" value="{{ $formc->descr }}" required>
                    </div>

                    <div class="row mt-3">
                        <h5>Approval Purposes 1</h5>
                        <div class="col-md-6">
                            <label class="form-label">Person Position</label>
                            <input type="text" class="form-control text-uppercase" id="pos1" name="pos1" value="{{ $formc->pos1 }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Person Name</label>
                            <input type="text" class="form-control text-uppercase" id="name1" name="name1" value="{{ $formc->name1 }}">
                        </div>
                    </div>

                    <div class="row mt-3">
                        <h5>Approval Purposes 2</h5>
                        <div class="col-md-6">
                            <label class="form-label">Person Position</label>
                            <input type="text" class="form-control text-uppercase" id="pos2" name="pos2" value="{{ $formc->pos2 }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Person Name</label>
                            <input type="text" class="form-control text-uppercase" id="name2" name="name2" value="{{ $formc->name2 }}">
                        </div>
                    </div>

                    <div class="row mt-3">
                        <h5>Approval Purposes 3</h5>
                        <div class="col-md-6">
                            <label class="form-label">Person Position</label>
                            <input type="text" class="form-control text-uppercase" id="pos3" name="pos3" value="{{ $formc->pos3 }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Person Name</label>
                            <input type="text" class="form-control text-uppercase" id="name3" name="name3" value="{{ $formc->name3 }}">
                        </div>
                    </div>

                    <div class="row mt-3">
                        <h5>Approval Purposes 4</h5>
                        <div class="col-md-6">
                            <label class="form-label">Person Position</label>
                            <input type="text" class="form-control text-uppercase" id="pos4" name="pos4" value="{{ $formc->pos4 }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Person Name</label>
                            <input type="text" class="form-control text-uppercase" id="name4" name="name4" value="{{ $formc->name4 }}">
                        </div>
                    </div>

                    <div class="row mt-3">
                        <h5>Distribution of Document</h5>
                        <div class="col-md-12">
                            <input type="text" class="form-control text-uppercase" id="docd1" name="docd1" value="{{ $formc->docd1 }}">
                        </div>
                        <div class="col-md-12">
                            <input type="text" class="form-control text-uppercase" id="docd2" name="docd2" value="{{ $formc->docd2 }}">
                        </div>
                    </div>

                    <div class="d-flex justify-content-between mt-3">
                        <a href="{{ route('formc.index') }}" class="btn btn-secondary">Kembali</a>
                        <button type="submit" class="btn btn-primary">Update</button>
                    </div>

                </form>

            </div>
        </div>
    </section>
@push('scripts')
    <script>
        // SweetAlert confirm submit
        document.addEventListener("DOMContentLoaded", function() {
            const form = document.getElementById('form-formc');

            form.addEventListener('submit', function (e) {
                e.preventDefault();

                if (!form.checkValidity()) {
                    form.classList.add('was-validated');
                    return;
                }

                Swal.fire({
                    title: 'Konfirmasi Update',
                    text: 'Apakah Anda yakin ingin mengubah data Form Code ini?',
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Ya, Ubah Data!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        Swal.fire({
                            title: 'Mengubah Data...',
                            text: 'Mohon tunggu sebentar',
                            icon: 'info',
                            showConfirmButton: false,
                            allowOutsideClick: false,
                            allowEscapeKey: false,
                            didOpen: () => {
                                Swal.showLoading();

                                form.submit();
                            }
                        });
                    }
                });
            });
        });
    </script>
@endpush
</main>
@endsection