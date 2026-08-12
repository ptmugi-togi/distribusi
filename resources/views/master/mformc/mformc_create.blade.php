@extends('layout.main')

@section('container')
<main id="main" class="main">

    <div class="pagetitle">
        <h1>Tambah Form Code</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('formc.index') }}">List Form Code</a></li>
                <li class="breadcrumb-item active">Tambah Form Code</li>
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

                <form method="POST" id="form-formc" action="{{ route('formc.store') }}" class="row g-3">
                    @csrf

                    <div class="col-md-6">
                        <label class="form-label">Branch</label>
                        <select name="braco" id="braco" class="form-control select2">
                            <option value="" disabled selected>Silahkan Pilih Branch</option>
                            @foreach ($branch as $b)
                                <option value="{{ $b->braco }}" {{ old('braco') == $b->braco ? 'selected' : '' }}>{{ $b->braco }} - {{ $b->brana }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Form Code</label><span class="text-danger"> *</span>
                        <input type="text" class="form-control text-uppercase" id="formc" name="formc" value="{{ old('formc') }}" required>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Description</label><span class="text-danger"> *</span>
                        <input type="text" class="form-control text-uppercase" id="descr" name="descr" value="{{ old('descr') }}" required>
                    </div>

                    <div class="row mt-3">
                        <h5>Approval Purposes 1</h5>
                        <div class="col-md-6">
                            <label class="form-label">Person Position</label>
                            <input type="text" class="form-control text-uppercase" id="pos1" name="pos1" value="{{ old('pos1') }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Person Name</label>
                            <input type="text" class="form-control text-uppercase" id="name1" name="name1" value="{{ old('name1') }}">
                        </div>
                    </div>

                    <div class="row mt-3">
                        <h5>Approval Purposes 2</h5>
                        <div class="col-md-6">
                            <label class="form-label">Person Position</label>
                            <input type="text" class="form-control text-uppercase" id="pos2" name="pos2" value="{{ old('pos2') }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Person Name</label>
                            <input type="text" class="form-control text-uppercase" id="name2" name="name2" value="{{ old('name2') }}">
                        </div>
                    </div>

                    <div class="row mt-3">
                        <h5>Approval Purposes 3</h5>
                        <div class="col-md-6">
                            <label class="form-label">Person Position</label>
                            <input type="text" class="form-control text-uppercase" id="pos3" name="pos3" value="{{ old('pos3') }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Person Name</label>
                            <input type="text" class="form-control text-uppercase" id="name3" name="name3" value="{{ old('name3') }}">
                        </div>
                    </div>

                    <div class="row mt-3">
                        <h5>Approval Purposes 4</h5>
                        <div class="col-md-6">
                            <label class="form-label">Person Position</label>
                            <input type="text" class="form-control text-uppercase" id="pos4" name="pos4" value="{{ old('pos4') }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Person Name</label>
                            <input type="text" class="form-control text-uppercase" id="name4" name="name4" value="{{ old('name4') }}">
                        </div>
                    </div>

                    <div class="row mt-3">
                        <h5>Distribution of Document</h5>
                        <div class="col-md-12">
                            <input type="text" class="form-control text-uppercase" id="docd1" name="docd1" value="{{ old('docd1') }}">
                        </div>
                        <div class="col-md-12">
                            <input type="text" class="form-control text-uppercase" id="docd2" name="docd2" value="{{ old('docd2') }}">
                        </div>
                    </div>

                    <div class="d-flex justify-content-between mt-3">
                        <a href="{{ route('formc.index') }}" class="btn btn-secondary">Kembali</a>
                        <button type="submit" class="btn btn-primary">Simpan</button>
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
            if (!form.checkValidity()) { form.classList.add('was-validated'); return; }
            Swal.fire({
                title: 'Konfirmasi Simpan',
                text: 'Apakah Anda yakin ingin menyimpan data ini?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, Simpan!',
                cancelButtonText: 'Batal'
            }).then((res)=>{
                if(res.isConfirmed){
                Swal.fire({ title:'Menyimpan...', text:'Mohon tunggu sebentar', icon:'info', showConfirmButton:false, allowOutsideClick:false, allowEscapeKey:false, didOpen:()=>Swal.showLoading() });
                form.submit();
                }
            });
            });
        });
    </script>
@endpush
</main>
@endsection