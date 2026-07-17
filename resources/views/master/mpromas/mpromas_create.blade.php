@extends('layout.main')

@section('container')
<main id="main" class="main">

    <div class="pagetitle">
        <h1>Tambah Produk</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('mpromas.index') }}">List Produk</a></li>
                <li class="breadcrumb-item active">Tambah Produk</li>
            </ol>
        </nav>
    </div>

    <section class="section">
        <div class="card">
            <div class="card-body pt-4">

                <form method="POST" id="form-mpromas" action="{{ route('mpromas.store') }}" class="row g-3">
                    @csrf

                    <div class="col-md-2">
                        <label class="form-label">Product No.</label><span class="text-danger"> *</span>
                        <input type="text" class="form-control text-uppercase" id="masterOpron" name="opron" required>
                    </div>

                    <div class="col-md-5">
                        <label class="form-label">Product Name</label><span class="text-danger"> *</span>
                        <input type="text" class="form-control text-uppercase" id="prona" name="prona" required>
                    </div>

                    <div class="col-md-5">
                        <label class="form-label">Prod Sup. Name</label>
                        <input type="text" class="form-control text-uppercase" id="iname" name="iname">
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Standard Unit</label>
                        <input type="text" class="form-control text-uppercase" id="stdqu" name="stdqu">
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Inventory Type</label><span class="text-danger"> *</span>
                        <select class="form-control select2 text-uppercase" name="itype_id" id="itype_id" required>
                            <option value="-" disabled selected>Silahkan Pilih Inventory Type</option>
                            @foreach ($itypes as $i)
                                <option value="{{ $i->itype_id }}">{{ $i->itype_id }} - {{ $i->descr_itype }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Brand</label><span class="text-danger"> *</span>
                        <select class="form-control select2 text-uppercase" name="brand" id="brand" required>
                            <option value="-" disabled selected>Silahkan Pilih Brand</option>
                            @foreach ($brands as $b)
                                <option value="{{ $b->brand_name }}">{{ $b->brand_name }} - {{ $b->descr_brand }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Product Group</label><span class="text-danger"> *</span>
                        <select class="form-control select2 text-uppercase" name="pgrup" id="pgrup" required>
                            <option value="-" disabled selected>Silahkan Pilih Product Group</option>
                            @foreach ($pgrups as $p)
                                <option value="{{ $p->pgrup }}">{{ $p->pgrup }} - {{ $p->descr }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Product Sub-Group</label><span class="text-danger"> *</span>
                        <select class="form-control select2 text-uppercase" name="sgrup_id" id="sgrup_id" required>
                            <option value="-" disabled selected>Silahkan Pilih Product Sub-Group</option>
                            @foreach ($sgrups as $s)
                                <option value="{{ $s->sgrup_id }}">{{ $s->sgrup_id }} - {{ $s->descr_sgrup }}</option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div class="col-md-4">
                        <label class="form-label">Sub - SubGroup (MKT)</label><span class="text-danger"> *</span>
                        <select class="form-control select2 text-uppercase" name="ssgrup_id" id="ssgrup_id" required>
                            <option value="-" disabled selected>Silahkan Pilih Product Sub-Group</option>
                            @foreach ($ssgrups as $ss)
                                <option value="{{ $ss->ssgrup_id }}">{{ $ss->ssgrup_id }} - {{ $ss->descr_ssgrup }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">(LOG)</label><span class="text-danger"> *</span>
                        <select class="form-control select2 text-uppercase" name="lssgrup" id="lssgrup" required>
                            <option value="-" disabled selected>Silahkan Pilih LOG Sub - Subgroup</option>
                            @foreach ($ssgrups as $ss)
                                <option value="{{ $ss->ssgrup_id }}">{{ $ss->ssgrup_id }} - {{ $ss->descr_ssgrup }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Status</label>
                        <select class="form-control select2 text-uppercase" name="status" id="status">
                            <option value="A" selected>Active</option>
                            <option value="N">Non Active</option>
                            <option value="D">Discontinued</option>
                        </select>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Garantine (days)</label><span class="text-danger"> *</span>
                        <input type="number" class="form-control" id="garan" name="garan" required>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Product Capacity</label>
                        <input type="text" class="form-control text-uppercase" id="capac" name="capac">
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Product Platform</label>
                        <input type="text" class="form-control text-uppercase" id="platf" name="platf">
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Weight / UOM (KG)</label>
                        <input type="number" class="form-control text-uppercase" id="weigh" name="weigh">
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Measurement (T x L x P) (mm)</label>
                        <div class="d-flex align-items-center">
                            <input type="number" class="form-control" id="meast" name="meast">
                            <span class="mx-2 fw-bold">X</span>
                            <input type="number" class="form-control" id="measl" name="measl">
                            <span class="mx-2 fw-bold">X</span>
                            <input type="number" class="form-control" id="measp" name="measp">
                        </div>
                    </div>
                    
                    <div class="col-md-4">
                        <label class="form-label">Ijin Type</label>
                        <input type="text" class="form-control text-uppercase" id="ijtype" name="ijtype">
                    </div>
                    
                    <div class="col-md-4">
                        <label class="form-label">Minimum Order</label>
                        <input type="text" class="form-control text-uppercase" id="mstok" name="mstok">
                    </div>
                    
                    <div class="col-md-4">
                        <label class="form-label">Part No.</label>
                        <input type="text" class="form-control text-uppercase" id="spnum" name="spnum">
                    </div>
                    
                    <div class="col-md-4">
                        <label class="form-label">A/C Inventory</label>
                        <input type="text" class="form-control text-uppercase" id="acinv" name="acinv">
                    </div>
                    
                    <div class="col-md-4">
                        <label class="form-label">A/C COGS</label>
                        <input type="text" class="form-control text-uppercase" id="achpp" name="achpp">
                    </div>
                    
                    <div class="col-md-4">
                        <label class="form-label">A/C Gross Sales</label>
                        <input type="text" class="form-control text-uppercase" id="acals" name="acals">
                    </div>
                    
                    <div class="col-md-4">
                        <label class="form-label">A/C Sales Discount</label>
                        <input type="text" class="form-control text-uppercase" id="acdis" name="acdis">
                    </div>
                    
                    <div class="col-md-4">
                        <label class="form-label">This Product Have a Bill of Material</label>
                        <select class="form-control select2 text-uppercase" name="pbilp" id="pbilp">
                            <option value="Y" selected>Yes</option>
                            <option value="N">No</option>
                        </select>
                    </div>

                    <div class="d-flex justify-content-between mt-3">
                        <a href="{{ route('mpromas.index') }}" class="btn btn-secondary">Kembali</a>
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
            const form = document.getElementById('form-mpromas');
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