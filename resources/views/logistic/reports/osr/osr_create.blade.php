@extends('layout.main')

<link rel="stylesheet" href="{{ asset('css/global.css') }}">

@section('container')
    <main id="main" class="main">
        <div class="pagetitle">
            <h1>Create Data OSR</h1>
            <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                <li class="breadcrumb-item active">OSR Create</li>
            </ol>
            </nav>
        </div>

        <section class="section">
            <form id="form-osr" action="" method="POST">
                @csrf
                <div class="row">
                    <div class="col-md-6 mt-3">
                        <label for="braco" class="form-label">Branch</label><span class="text-danger"> *</span>
                        <input type="text" class="form-control" name="braco" id="braco" value="{{ old('braco', auth()->user()->cabang) }}" readonly style="background-color: #e9ecef">
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

                </div>

                <div class="mt-3 d-flex justify-content-between">
                    <button type="button" id="excelOsr" class="btn btn-success" disabled>Download Excel</button>
                    <button type="button" id="printOsr" class="btn btn-primary">Print Data</button>
                </div>
            </form>
        </section>
    </main>

    @push('scripts')
        <script>
            document.getElementById('printOsr').addEventListener('click', function () {
                // ambil elemen input
                const invtype = document.getElementById('invtype').value.trim();

                // jika lolos validasi, buat URL dan buka window
                let params = new URLSearchParams({
                    invtype: invtype,
                });

                window.open("{{ route('osr.print') }}?" + params.toString(), "_blank");
            });
        </script>
    @endpush

@endsection