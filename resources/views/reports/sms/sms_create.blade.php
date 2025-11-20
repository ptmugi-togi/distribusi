@extends('layout.main')

<link rel="stylesheet" href="{{ asset('css/global.css') }}">

@section('container')
    <main id="main" class="main">
        <div class="pagetitle">
            <h1>Create Data SMS</h1>
            <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                <li class="breadcrumb-item active">SMS Create</li>
            </ol>
            </nav>
        </div>

        <section class="section">
            <form id="form-sms" action="" method="POST">
                @csrf
                <div class="row">
                    <div class="col-md-6 mt-3">
                        <label for="braco" class="form-label">Branch</label><span class="text-danger"> *</span>
                        <input type="text" class="form-control" name="braco" id="braco" value="{{ old('braco', auth()->user()->cabang) }}" readonly style="background-color: #e9ecef">
                    </div>

                    <div class="col-md-6 mt-3">
                        <label for="warco" class="form-label">Warehouse</label><span class="text-danger"> *</span>
                        <select class="form-control select2" name="warco" id="warco" required>
                            <option value="" disabled selected>Pilih Warehouse</option>
                            @foreach ($mwarco as $m)
                            <option value="{{ $m->warco }}" {{ old('warco') == $m->warco ? 'selected' : '' }}>
                                {{ $m->warco }}
                            </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-6 mt-3">
                        <label for="asof" class="form-label">As of</label><span class="text-danger"> *</span>
                        <input type="date" class="form-control" name="asof" id="asof" value="{{ old('asof') }}" required>
                    </div>

                    <div class="col-md-6 mt-3">
                        <label for="invtype" class="form-label">Inventory Type</label>
                        <select class="form-control select2" name="invtype" id="invtype">
                            <option value="" disabled selected>Pilih Inventory Type</option>
                            @foreach ($mitype as $mt )
                                <option value="{{ $mt->itype_id }}" {{ old('itype_id') == $mt->itype_id ? 'selected' : '' }}>
                                    {{ $mt->itype_id }} - {{ $mt->descr_itype }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-6 mt-3">
                        <label for="subgroup" class="form-label">Product Sub-Group</label>
                        <select class="form-control select2" name="subgroup" id="subgroup">
                            <option value="" disabled selected>Pilih Product Sub-Group</option>
                            @foreach ($msgrup as $ms )
                                <option value="{{ $ms->sgrup_id }}" {{ old('sgrup_id') == $ms->sgrup_id ? 'selected' : '' }}>
                                    {{ $ms->sgrup_id }} - {{ $ms->descr_sgrup }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-6 mt-3">
                        <label for="subsubgroup" class="form-label">Product Sub Sub Group</label>
                        <select class="form-control select2" name="subsubgroup" id="subsubgroup">
                            <option value="" disabled selected>Pilih Product Sub Sub Group</option>
                            @foreach ($mssgrup as $mss )
                                <option value="{{ $mss->ssgrup_id }}" {{ old('ssgrup_id') == $mss->ssgrup_id ? 'selected' : '' }}>
                                    {{ $mss->descr_ssgrup }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-6 mt-3"> 
                        <label for="sort" class="form-label">Sort By</label>
                        <select class="form-control select2" name="sort" id="sort">
                            <option value="" disabled selected>Pilih Sort By</option>
                            <option value="">Product No.</option>
                            <option value="">Product Name</option>
                            <option value="">Brand</option>
                        </select>
                    </div>
                </div>

                <div class="mt-3 d-flex justify-content-between">
                    <button type="button" id="excelSms" class="btn btn-success" disabled>Download Excel</button>
                    <button type="button" id="previewSms" class="btn btn-primary">Print Data</button>
                </div>
            </form>
        </section>
    </main>

    @push('scripts')
        <script>
            document.getElementById('previewSms').addEventListener('click', function () {
                // ambil elemen input
                const warco = document.getElementById('warco').value.trim();
                const asof = document.getElementById('asof').value.trim();
                const invtype = document.getElementById('invtype').value.trim();
                const subgroup = document.getElementById('subgroup').value.trim();
                const subsubgroup = document.getElementById('subsubgroup').value.trim();
                const sort = document.getElementById('sort').value.trim();

                // cek required field
                if (!warco) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Oops...',
                        text: 'Pilih Warehouse Terlebih Dahulu!',
                    }).then(() => {
                        document.getElementById('warco').focus();
                    });
                    return;
                }
                if (!asof) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Oops...',
                        text: 'Pilih Tanggal Akhir Terlebih Dahulu!',
                    }).then(() => {
                        document.getElementById('asof').focus();
                    });
                    return;
                }

                // jika lolos validasi, buat URL dan buka window
                let params = new URLSearchParams({
                    warco: warco,
                    asof: asof,
                    invtype: invtype,
                    subgroup: subgroup,
                    subsubgroup: subsubgroup,
                    sort: sort,
                });

                window.open("{{ route('sms.preview') }}?" + params.toString(), "_blank");
            });
        </script>
    @endpush

@endsection