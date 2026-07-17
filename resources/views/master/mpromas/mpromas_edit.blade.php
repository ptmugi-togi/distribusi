@extends('layout.main')

@section('container')
<main id="main" class="main">

    <div class="pagetitle">
        <h1>Edit Produk ({{ $mpromas->opron }} - {{ $mpromas->prona }})</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('mpromas.index') }}">List Produk</a></li>
                <li class="breadcrumb-item active">Edit Produk</li>
            </ol>
        </nav>
    </div>

    <section class="section">
        <div class="card">
            <div class="card-body pt-4">
                <form method="POST" id="form-mpromas" action="{{ route('mpromas.update', $mpromas->opron) }}" class="row g-3">
                    @csrf
                    @method('PUT')

                    <div class="col-md-2">
                        <label class="form-label">Product No.</label>
                        <input type="text" class="form-control text-uppercase" id="masterOpron" name="opron" value="{{ $mpromas->opron }}">
                    </div>

                    <div class="col-md-5">
                        <label class="form-label">Product Name</label>
                        <input type="text" class="form-control text-uppercase" id="prona" name="prona" value="{{ $mpromas->prona }}">
                    </div>

                    <div class="col-md-5">
                        <label class="form-label">Prod Sup. Name</label>
                        <input type="text" class="form-control text-uppercase" id="iname" name="iname" value="{{ $mpromas->iname }}">
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Standard Unit</label>
                        <input type="text" class="form-control text-uppercase" id="stdqu" name="stdqu" value="{{ $mpromas->stdqu }}">
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Inventory Type</label><span class="text-danger"> *</span>
                        <select class="form-control select2 text-uppercase" name="itype_id" id="itype_id" required>
                            @foreach ($itypes as $i)
                                <option value="{{ $i->itype_id }}"
                                    {{ $mpromas->itype_id == $i->itype_id ? 'selected' : '' }}>
                                    {{ $i->itype_id }} - {{ $i->descr_itype }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Brand</label><span class="text-danger"> *</span>
                        <select class="form-control select2 text-uppercase" name="brand" id="brand" required>
                            @foreach ($brands as $b)
                                <option value="{{ $b->brand_name }}"
                                    {{ $mpromas->brand == $b->brand_name ? 'selected' : '' }}>
                                    {{ $b->brand_name }} - {{ $b->descr_brand }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Product Group</label><span class="text-danger"> *</span>
                        <select class="form-control select2 text-uppercase" name="pgrup" id="pgrup" required>
                            @foreach ($pgrups as $p)
                                <option value="{{ $p->pgrup }}"
                                    {{ $mpromas->pgrup == $p->pgrup ? 'selected' : '' }}>
                                    {{ $p->pgrup }} - {{ $p->descr }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Product Sub-Group</label><span class="text-danger"> *</span>
                        <select class="form-control select2 text-uppercase" name="sgrup_id" id="sgrup_id" required>
                            @foreach ($sgrups as $s)
                                <option value="{{ $s->sgrup_id }}"
                                    {{ $mpromas->sgrup_id == $s->sgrup_id ? 'selected' : '' }}>
                                    {{ $s->sgrup_id }} - {{ $s->descr_sgrup }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div class="col-md-4">
                        <label class="form-label">Sub - SubGroup (MKT)</label><span class="text-danger"> *</span>
                        <select class="form-control select2 text-uppercase" name="ssgrup_id" id="ssgrup_id" required>
                            @foreach ($ssgrups as $ss)
                                <option value="{{ $ss->ssgrup_id }}"
                                    {{ $mpromas->ssgrup_id == $ss->ssgrup_id ? 'selected' : '' }}>
                                    {{ $ss->ssgrup_id }} - {{ $ss->descr_ssgrup }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">(LOG)</label><span class="text-danger"> *</span>
                        <select class="form-control select2 text-uppercase" name="lssgrup" id="lssgrup" required>
                            @foreach ($ssgrups as $ss)
                                <option value="{{ $ss->ssgrup_id }}"
                                    {{ $mpromas->lssgrup == $ss->ssgrup_id ? 'selected' : '' }}>
                                    {{ $ss->ssgrup_id }} - {{ $ss->descr_ssgrup }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Status</label>
                        <select class="form-control select2 text-uppercase" name="status" id="status">
                            <option value="A" {{ $mpromas->status == 'A' ? 'selected' : '' }}>Active</option>
                            <option value="N" {{ $mpromas->status == 'N' ? 'selected' : '' }}>Non Active</option>
                            <option value="D" {{ $mpromas->status == 'D' ? 'selected' : '' }}>Discontinued</option>
                        </select>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Garantine (days)</label>
                        <input type="number" class="form-control" id="garan" name="garan" value="{{ $mpromas->garan }}">
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Product Capacity</label>
                        <input type="text" class="form-control text-uppercase" id="capac" name="capac" value="{{ $mpromas->capac }}">
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Product Platform</label>
                        <input type="text" class="form-control text-uppercase" id="platf" name="platf" value="{{ $mpromas->platf }}">
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Weight / UOM (KG)</label>
                        <input type="number" class="form-control text-uppercase" id="weigh" name="weigh" value="{{ $mpromas->weigh }}">
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Measurement (T x L x P) (mm)</label>
                        <div class="d-flex align-items-center">
                            <input type="number" class="form-control" id="meast" name="meast" value="{{ $mpromas->meast }}">
                            <span class="mx-2 fw-bold">X</span>
                            <input type="number" class="form-control" id="measl" name="measl" value="{{ $mpromas->measl }}">
                            <span class="mx-2 fw-bold">X</span>
                            <input type="number" class="form-control" id="measp" name="measp" value="{{ $mpromas->measp }}">
                        </div>
                    </div>
                    
                    <div class="col-md-4">
                        <label class="form-label">Ijin Type</label>
                        <input type="text" class="form-control text-uppercase" id="ijtype" name="ijtype" value="{{ $mpromas->ijtype }}">
                    </div>
                    
                    <div class="col-md-4">
                        <label class="form-label">Minimum Order</label>
                        <input type="text" class="form-control text-uppercase" id="mstok" name="mstok" value="{{ $mpromas->mstok }}">
                    </div>
                    
                    <div class="col-md-4">
                        <label class="form-label">Part No.</label>
                        <input type="text" class="form-control text-uppercase" id="spnum" name="spnum" value="{{ $mpromas->spnum }}">
                    </div>
                    
                    <div class="col-md-4">
                        <label class="form-label">A/C Inventory</label>
                        <input type="text" class="form-control text-uppercase" id="acinv" name="acinv" value="{{ $mpromas->acinv }}">
                    </div>
                    
                    <div class="col-md-4">
                        <label class="form-label">A/C COGS</label>
                        <input type="text" class="form-control text-uppercase" id="achpp" name="achpp" value="{{ $mpromas->achpp }}">
                    </div>
                    
                    <div class="col-md-4">
                        <label class="form-label">A/C Gross Sales</label>
                        <input type="text" class="form-control text-uppercase" id="acals" name="acals" value="{{ $mpromas->acals }}">
                    </div>
                    
                    <div class="col-md-4">
                        <label class="form-label">A/C Sales Discount</label>
                        <input type="text" class="form-control text-uppercase" id="acdis" name="acdis" value="{{ $mpromas->acdis }}">
                    </div>
                    
                    <div class="col-md-4">
                        <label class="form-label">This Product Have a Bill of Material</label>
                        <select class="form-control select2 text-uppercase" name="pbilp" id="pbilp">
                            <option value="Y" {{ $mpromas->pbilp == 'Y' ? 'selected' : '' }}>Yes</option>
                            <option value="N" {{ $mpromas->pbilp == 'N' ? 'selected' : '' }}>No</option>
                        </select>
                    </div>

                    <div class="d-flex justify-content-between mt-3">
                        <a href="{{ route('mpromas.index') }}" class="btn btn-secondary">Kembali</a>
                        <button type="submit" class="btn btn-primary">Update</button>
                    </div>
                </form>
            </div>
        </div>
    </section>
</main>
@push('scripts')
<script>
document.addEventListener("DOMContentLoaded", function () {

    const form = document.getElementById('form-mpromas');

    form.addEventListener('submit', function (e) {
        e.preventDefault();

        if (!form.checkValidity()) {
            form.classList.add('was-validated');
            return;
        }

        Swal.fire({
            title: 'Konfirmasi Update',
            text: 'Apakah Anda yakin ingin mengubah data produk ini?',
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya, Ubah Data!',
            cancelButtonText: 'Batal'
        }).then((result) => {

            if (result.isConfirmed) {

                Swal.fire({
                    title: 'Mengupdate...',
                    text: 'Mohon tunggu sebentar',
                    icon: 'info',
                    showConfirmButton: false,
                    allowOutsideClick: false,
                    allowEscapeKey: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });

                form.submit();
            }

        });

    });

});
</script>
@endpush
@endsection