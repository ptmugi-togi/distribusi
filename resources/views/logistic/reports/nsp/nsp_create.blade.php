@extends('layout.main')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/global.css') }}">
@endpush

@section('container')
<main id="main" class="main">
    <div class="d-flex justify-content-between align-items-center">
        <div class="pagetitle">
            <h1>Print National Stock Position</h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item active">Print Data National Stock Position</li>
                </ol>
            </nav>
        </div>
    </div>

    <section class="section">
        <form id="form-nsp">
            @csrf
            <div class="row">
                <div class="col-md-6 mt-3">
                    <label for="itype" class="form-label">Inventory Type</label>
                    <select name="itype" id="itype" class="form-control select2">
                        <option value="N" {{ old('itype') == 'N' ? 'selected' : '' }}>N (New Goods)</option>
                        <option value="S" {{ old('itype') == 'S' ? 'selected' : '' }}>S (Spareparts)</option>
                    </select>
                </div>

                <div class="col-md-6 mt-3">
                    <label for="sort_by" class="form-label">Sort By</label>
                    <select name="sort_by" id="sort_by" class="form-control select2">
                        <option value="opron" {{ old('sort_by') == 'opron' ? 'selected' : '' }}>Product No.</option>
                        <option value="prona" {{ old('sort_by') == 'prona' ? 'selected' : '' }}>Product Name</option>
                    </select>
                </div>
            </div>

            <div class="mt-3 d-flex justify-content-end">
                <button type="button" id="printNSPList" class="btn btn-primary">Print Data</button>
            </div>
        </form>
    </section>
</main>

@push('scripts')
<script>
    document.getElementById('printNSPList').addEventListener('click', function () {
        const itype = document.getElementById('itype').value;
        const sort_by = document.getElementById('sort_by').value;

        let params = new URLSearchParams({
            itype: itype,
            sort_by: sort_by
        });

        window.open("{{ route('nsp.preview') }}?" + params.toString(), "_blank");
    });
</script>
@endpush
@endsection