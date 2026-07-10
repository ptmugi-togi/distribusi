@extends('layout.main')

@section('container')
<main id="main" class="main">

    <div class="pagetitle">
        <h1>Tambah Customer</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('cusmas.index') }}">List Customer</a></li>
                <li class="breadcrumb-item active">Tambah Customer</li>
            </ol>
        </nav>
    </div>

    <section class="section">
        <div class="card">
            <div class="card-body pt-4">

                <form method="POST" id="form-cusmas" action="{{ route('cusmas.store') }}" class="row g-3">
                    @csrf

                    <div class="col-md-6">
                        <label class="form-label">Branch</label><span class="text-danger"> *</span>
                        <input type="text" class="form-control text-uppercase" id="bracoCusmas" name="braco" value="{{ auth()->user()->cabang }}" required readonly style="background-color:#e9ecef">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Join Date (mm/dd/yyyy)</label><span class="text-danger"> *</span>
                        <input type="date" class="form-control" id="dopenCusmas" name="dopen" value="{{ date('Y-m-d') }}" required readonly style="background-color:#e9ecef">
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Customer No.</label><span class="text-danger"> *</span>
                        <input type="text" class="form-control text-uppercase" id="cusnoCusmas" name="cusno" required>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Customer Name</label><span class="text-danger"> *</span>
                        <input type="text" class="form-control text-uppercase" id="cusnaCusmas" name="cusna" maxlength="200" required>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Bill Name</label><span class="text-danger"> *</span>
                        <input type="text" class="form-control text-uppercase" id="billnCusmas" name="billn" maxlength="100" required>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Email</label><span class="text-danger"> *</span>
                        <input type="text" class="form-control" id="emailCusmas" name="email" maxlength="100" required>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">NPWP / NIK</label><span class="text-danger"> *</span>
                        <input type="text" class="form-control text-uppercase" id="taxrnCusmas" name="taxrn" maxlength="100" required>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">NITKU</label><span class="text-danger"> *</span>
                        <input type="text" class="form-control text-uppercase" id="nitkuCusmas" name="nitku" maxlength="100" required>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Title</label><span class="text-danger"> *</span>
                        <select class="form-control select2 text-uppercase" name="title" id="titleCusmas" maxlength="5" required>
                            <option value="" disabled selected>Silahkan Pilih Title</option>
                            <option value="PT.">PT.</option>
                            <option value="CV.">CV.</option>
                            <option value="BPK">BAPAK</option>
                            <option value="IBU">IBU</option>
                            <option value="TOKO">TOKO</option>
                            <option value="UD.">UD.</option>
                            <option value="TM.">TM.</option>
                            <option value="HOTEL">HOTEL</option>
                            <option value="KOP">UNIT KOPERASI</option>
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">PKP</label>
                        <select name="pkp" id="pkp" class="form-control select2">
                            <option value="" disabled selected>Silahkan Pilih PKP</option>
                            <option value="Y">YA</option>
                            <option value="N">TIDAK</option>
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Provinsi</label><span class="text-danger"> *</span>
                        <select name="province" id="provinsiCusmas" required class="form-control select2">
                            <option value="" disabled selected>Silahkan Pilih Provinsi</option>
                            @foreach ($provinsi as $prov)
                                <option value="{{ $prov->id_prov }}">{{ $prov->provinsi }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Kabupaten</label><span class="text-danger"> *</span>
                        <select name="kabupaten" id="kabKotaCusmas" required class="form-control select2">
                            <option value="" disabled selected>Silahkan Pilih Kabupaten / Kota</option>
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Kode Pos</label><span class="text-danger"> *</span>
                        <input type="text" class="form-control text-uppercase" name="opost" id="opostCusmas" maxlength="5" required>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Alamat</label><span class="text-danger"> *</span>
                        <textarea class="form-control" name="address" id="addressCusmas" style="height: 100px;" required></textarea>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Phone</label><span class="text-danger"> *</span>
                        <input type="text" class="form-control text-uppercase" name="offph" id="offphCusmas" maxlength="40" required>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Fax</label><span class="text-danger"> *</span>
                        <input type="text" class="form-control text-uppercase" name="offax" id="offaxCusmas" maxlength="40" required>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Contact</label><span class="text-danger"> *</span>
                        <input type="text" class="form-control text-uppercase" name="ofcon" id="ofconCusmas" maxlength="40" required>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">TOP</label><span class="text-danger"> *</span>
                        <input type="number" class="form-control text-uppercase" name="topay" id="topayCusmas" maxlength="3" required>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Industry</label><span class="text-danger"> *</span>
                        <select class="form-control select2 text-uppercase" name="cindu" id="cinduCusmas" maxlength="3" required>
                            <option value="-" disabled selected>Silahkan Pilih Industry</option>
                            @foreach ($cindus as $cindu)
                                <option value="{{ $cindu->cindu }}">{{ $cindu->cindu }} - {{ $cindu->descr_cindu }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">LAUID</label>
                        <input type="text" class="form-control text-uppercase" value="{{ auth()->user()->username }}" name="lauid" id="lauidCusmas" maxlength="50" readonly style="background-color: #e9ecef">
                    </div>

                    <input type="hidden" value="{{ date("Y-m-d H:i:s") }}" class="form-control text-uppercase" name="ladup" id="ladupCusmas" required>

                    <div class="d-flex justify-content-between mt-3">
                        <a href="{{ route('cusmas.index') }}" class="btn btn-secondary">Kembali</a>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>

                </form>

            </div>
        </div>
    </section>
@push('scripts')
    <script>
        $(document).on('change', '#provinsiCusmas', function () {
            const prov = $('#provinsiCusmas').val();
            const $kabKota = $('#kabKotaCusmas');

            // reset dulu
            $kabKota
                .prop('disabled', true)
                .html('<option disabled selected>Pilih Provinsi terlebih dahulu</option>');

            $kabKota.html('<option disabled selected>Loading...</option>');

            $.ajax({
                url: "{{ route('cusmas.getKabKota') }}",
                type: "GET",
                data: { prov },
                success(response) {
                    $kabKota.empty();

                    if (!Array.isArray(response) || response.length === 0) {
                        $kabKota.append('<option disabled selected>Tidak ada data Kabupaten / Kota</option>');
                        return;
                    }

                    $kabKota.append('<option disabled selected>Pilih Kabupaten / Kota</option>');

                    response.forEach(item => {
                        $kabKota.append(`
                            <option value="${item.id}"
                                data-kabupaten="${item.kabupaten}"
                                data-id_prov="${item.id_prov}">
                                ${item.id} - ${item.kabupaten}
                            </option>
                        `);
                    });

                    $kabKota.prop('disabled', false);
                },
                error() {
                    $kabKota
                        .html('<option disabled selected>Gagal mengambil data</option>')
                        .prop('disabled', false);
                }
            });
        });

        // SweetAlert confirm submit
        document.addEventListener("DOMContentLoaded", function() {
            const form = document.getElementById('form-cusmas');
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