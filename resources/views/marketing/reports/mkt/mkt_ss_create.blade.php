@extends('layout.main')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/global.css') }}">
@endpush

@section('container')
<main id="main" class="main">
    <div class="d-flex justify-content-between align-items-center">
        <div class="pagetitle">
        <h1>Print Data Sales Report/Product Group</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                <li class="breadcrumb-item active">Print Data Sales Report/Product Group</li>
            </ol>
        </nav>
        </div>
    </div>

    <section class="section">
        <form id="form-mkt" action="" method="POST">
            @csrf

            <div class="row">
                <div class="col-md-4 mt-3">
                    <label for="braco" class="form-label">Branch</label><span class="text-danger"> *</span>
                    <input type="text" class="form-control" name="braco" id="braco" value="{{ (old('braco') ? old('braco') : auth()->user()->cabang) }}" disabled required>
                </div>
                
                <div class="col-md-4 mt-3">
                    <label for="depo" class="form-label">Depo</label>
                    <select name="depo" id="depo" class="form-control select2">
                        <option value="" disabled {{ old('depo') ? '' : 'selected' }}>Silahkan Pilih Depo</option>
                        @foreach ($depo as $d)
                            <option value="{{ $d->depo }}" {{ old('depo') == $d->depo ? 'selected' : '' }}>
                                {{ $d->depo }} - {{ $d->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                
                <div class="col-md-4 mt-3">
                    <label for="sreno" class="form-label">Sales Rep</label>
                    <select name="sreno" id="sreno" class="form-control select2">
                        <option value="" disabled {{ old('sreno') ? '' : 'selected' }}>Silahkan Pilih Sales Rep</option>
                        @foreach ($sales as $s)
                            <option value="{{ $s->sreno }}" {{ old('sreno') == $s->sreno ? 'selected' : '' }}>
                                {{ $s->sreno }} - {{ $s->srena }}
                            </option>
                        @endforeach
                    </select>
                </div>
                
                <div class="col-md-6 mt-3">
                    <label for="msgrup" class="form-label">Sub Group</label>
                    <select name="msgrup" id="msgrup" class="form-control select2">
                        <option value="" disabled {{ old('sgrup') ? '' : 'selected' }}>Silahkan Pilih Sub Group</option>
                        @foreach ($msgrup as $ms)
                            <option value="{{ $ms->sgrup }}" {{ old('sgrup') == $ms->sgrup ? 'selected' : '' }}>
                                {{ $ms->sgrup }} - {{ $ms->descr }}
                            </option>
                        @endforeach
                    </select>
                </div>
                
                <div class="col-md-6 mt-3">
                    <label for="mssgrup" class="form-label">Sub Sub Group</label>
                    <select name="mssgrup" id="mssgrup" class="form-control select2">
                        <option value="" disabled {{ old('ssgrup') ? '' : 'selected' }}>Silahkan Pilih Sub Sub Group</option>
                        @foreach ($mssgrup as $mss)
                            <option value="{{ $mss->ssgrup }}" {{ old('ssgrup') == $mss->ssgrup ? 'selected' : '' }}>
                                {{ $mss->ssgrup }} - {{ $mss->descr }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-6 mt-3">
                    <label for="ocdat_s" class="form-label">OC Date Start</label><span class="text-danger"> *</span>
                    <input type="date" class="form-control" name="ocdat_s" id="ocdat_s" value="{{ old('ocdat_s') }}" required>
                </div>

                <div class="col-md-6 mt-3">
                    <label for="ocdat_e" class="form-label">OC Date End</label><span class="text-danger"> *</span>
                    <input type="date" class="form-control" name="ocdat_e" id="ocdat_e" value="{{ old('ocdat_e') }}" required>
                </div>
            </div>

            <div class="mt-3 d-flex justify-content-end">
                <button type="button" id="printMkt" class="btn btn-primary">Print Data</button>
            </div>
        </form>
    </section>
</main>

    @push('scripts')
        <script>
            document.getElementById('printMkt').addEventListener('click', function () {
                // ambil elemen input
                const braco = document.getElementById('braco').value.trim();
                const depo = document.getElementById('depo').value.trim() || '';
                const sreno = document.getElementById('sreno').value.trim() || '';
                const msgrup = document.getElementById('msgrup').value.trim() || '';
                const mssgrup = document.getElementById('mssgrup').value.trim() || '';
                const ocdat_s = document.getElementById('ocdat_s').value.trim();
                const ocdat_e = document.getElementById('ocdat_e').value.trim();

                // cek required field
                if (!braco) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Oops...',
                        text: 'Pilih Branch Terlebih Dahulu!',
                    }).then(() => {
                        document.getElementById('braco').focus();
                    });
                    return;
                }
                if (!ocdat_s) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Oops...',
                        text: 'Pilih Tanggal Awal Terlebih Dahulu!',
                    }).then(() => {
                        document.getElementById('ocdat_s').focus();
                    });
                    return;
                }
                if (!ocdat_e) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Oops...',
                        text: 'Pilih Tanggal Akhir Terlebih Dahulu!',
                    }).then(() => {
                        document.getElementById('ocdat_e').focus();
                    });
                    return;
                }

                // jika lolos validasi, buat URL dan buka window
                let params = new URLSearchParams({
                    braco: braco,
                    depo: depo,
                    msgrup: msgrup,
                    mssgrup: mssgrup,
                    sreno: sreno,
                    ocdat_s: ocdat_s,
                    ocdat_e: ocdat_e,
                });

                window.open("{{ route('mkt.previewMktSs') }}?" + params.toString(), "_blank");
            });
        </script>
@endpush
@endsection