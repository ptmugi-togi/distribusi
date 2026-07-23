@extends('layout.main')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/global.css') }}">
@endpush

@section('container')
<main id="main" class="main">
    <div class="d-flex justify-content-between align-items-center">
        <div class="pagetitle">
            <h1>Edit Customer</h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('cusmas.index') }}">List Customer</a></li>
                    <li class="breadcrumb-item active">Edit Customer</li>
                </ol>
            </nav>
        </div>
    </div>

    <section class="section">
        <div class="card p-3 shacustw-sm">
            <form method="POST" id="form-cusmas" action="{{ route('cusmas.update', $cust->cusno) }}" class="row g-3">
                @csrf
                @method('PUT')
                <div class="row">
                    <div class="col-md-4 mt-3">
                        <label class="form-label">Branch</label>
                        <input type="text" class="form-control" id="bracoCusmas" value="{{ $cust->braco }}" disabled>
                        <input type="hidden" class="form-control" name="braco" value="{{ $cust->braco }}">
                    </div>

                    <div class="col-md-4 mt-3">
                        <label class="form-label">Join Date (mm/dd/yyyy)</label>
                        <input type="text" class="form-control" id="dopenCusmas" value="{{ \Carbon\Carbon::parse($cust->dopen)->format('m/d/Y') }}" disabled>
                        <input type="hidden" class="form-control" id="dopenCusmas" name="dopen" value="{{ $cust->dopen }}">
                    </div>

                    <div class="col-md-4 mt-3">
                        <label class="form-label">Customer No.</label>
                        <input type="text" class="form-control" value="{{ $cust->cusno }}" disabled>
                        <input type="hidden" class="form-control" name="cusno" value="{{ $cust->cusno }}">
                    </div>

                    <div class="col-md-2 mt-3">
                        <label class="form-label">Title</label>
                        <select class="form-control select2 text-uppercase" name="title" id="titleCusmas" required>
                            <option value="" disabled>Silahkan Pilih Title</option>
                            <option value="PT." {{ $cust->title == 'PT.' ? 'selected' : '' }}>PT.</option>
                            <option value="CV." {{ $cust->title == 'CV.' ? 'selected' : '' }}>CV.</option>
                            <option value="BPK" {{ $cust->title == 'BPK' ? 'selected' : '' }}>BAPAK</option>
                            <option value="IBU" {{ $cust->title == 'IBU' ? 'selected' : '' }}>IBU</option>
                            <option value="TOKO" {{ $cust->title == 'TOKO' ? 'selected' : '' }}>TOKO</option>
                            <option value="UD." {{ $cust->title == 'UD.' ? 'selected' : '' }}>UD.</option>
                            <option value="TM." {{ $cust->title == 'TM.' ? 'selected' : '' }}>TM.</option>
                            <option value="HOTEL" {{ $cust->title == 'HOTEL' ? 'selected' : '' }}>HOTEL</option>
                            <option value="KOP" {{ $cust->title == 'KOP' ? 'selected' : '' }}>UNIT KOPERASI</option>
                        </select>
                    </div>

                    <div class="col-md-5 mt-3">
                        <label class="form-label">Customer Name</label>
                        <input type="text" class="form-control" id="cusnaCusmas" name="cusna" value="{{ $cust->cusna }}">
                    </div>

                    <div class="col-md-5 mt-3">
                        <label class="form-label">Bill Name</label>
                        <input type="text" class="form-control" id="billnCusmas" name="billn" value="{{ $cust->billn }}">
                    </div>
                    
                    <div class="col-md-4 mt-3">
                        <label class="form-label">PKP</label>
                        <select name="pkp" id="pkpCusmas" class="form-control select2">
                            <option value="" disabled>Silahkan Pilih PKP</option>
                            <option value="Y" {{ $cust->pkp == 'Y' ? 'selected' : '' }}>YA</option>
                            <option value="N" {{ $cust->pkp == 'N' ? 'selected' : '' }}>TIDAK</option>
                        </select>
                    </div>

                    <div class="col-md-4 mt-3">
                        <label class="form-label">NPWP / NIK</label>
                        <input type="text" class="form-control" id="taxrnCusmas" name="taxrn" value="{{ $cust->taxrn }}">
                    </div>

                    <div class="col-md-4 mt-3">
                        <label class="form-label">NITKU</label>
                        <input type="text" class="form-control" id="nitkuCusmas" name="nitku" value="{{ $cust->nitku }}">
                    </div>

                    <div class="col-md-6 mt-3">
                        <label class="form-label">Provinsi</label>
                        <select class="form-control select2" id="provinsiCusmas" name="province">
                            <option value="" disabled selected>Pilih Provinsi</option>
                            @foreach($prov as $p)
                                <option value="{{ $p->id_prov }}"
                                    {{ $cust->province == $p->id_prov ? 'selected' : '' }}>
                                    {{ $p->provinsi }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-6 mt-3">
                        <label class="form-label">Kabupaten / Kota</label>
                        <select class="form-control select2" id="kabKotaCusmas" name="kabupaten">
                            <option value="">Pilih Kabupaten/Kota</option>
                        </select>
                    </div>

                    <div class="col-md-6 mt-3">
                        <label class="form-label">Alamat</label>
                        <textarea class="form-control" id="alamatCusmas" name="address" rows="2">{{ $cust->address }}</textarea>
                    </div>

                    <div class="col-md-6 mt-3">
                        <label class="form-label">Kode Pos</label>
                        <input type="text" class="form-control" id="postCusmas" name="opost" value="{{ $cust->opost }}">
                    </div>

                    <div class="col-md-4 mt-3">
                        <label class="form-label">Phone</label>
                        <input type="text" class="form-control" id="phoneCusmas" name="offph" value="{{ $cust->offph }}">
                    </div>

                    <div class="col-md-4 mt-3">
                        <label class="form-label">Fax</label>
                        <input type="text" class="form-control" id="faxCusmas" name="offax" value="{{ $cust->offax }}">
                    </div>

                    <div class="col-md-4 mt-3">
                        <label class="form-label">Contact</label>
                        <input type="text" class="form-control" id="contactCusmas" name="ofcon" value="{{ $cust->ofcon }}">
                    </div>
                    
                    <div class="col-md-4 mt-3">
                        <label class="form-label">Email</label>
                        <input type="text" class="form-control" id="emailCusmas" name="email" value="{{ $cust->email }}">
                    </div>

                    <div class="col-md-4 mt-3">
                        <label class="form-label">TOP</label>
                        <input type="text" class="form-control" id="topayCusmas" name="topay" value="{{ $cust->topay }}">
                    </div>

                    <div class="col-md-4 mt-3">
                        <label class="form-label">Industry</label>
                        <select class="form-control select2" name="cindu">
                            @foreach($cindu as $c)
                                <option value="{{ $c->cindu }}"
                                    {{ $cust->cindu == $c->cindu ? 'selected' : '' }}>
                                    {{ $c->cindu }} - {{ $c->descr_cindu }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-4 mt-3">
                        <label class="form-label">LAUID</label>
                        <input type="text" class="form-control" value="{{ $cust->lauid }}" disabled>
                        <input type="hidden" class="form-control" name="lauid" value="{{ $cust->lauid }}">
                    </div>
                </div>
                <div class="mt-4 d-flex justify-content-between">
                    <a href="{{ route('cusmas.index') }}" class="btn btn-secondary">Kembali</a>
                    <button type="submit" class="btn btn-primary">Update</button>
                </div>
            </form>
        </div>
    </section>
</main>

@push('scripts')
    <script>
        $(document).ready(function () {
            function loadKabupaten(prov, selected = null) {
                $('#kabKotaCusmas').html('<option value="">Loading...</option>');

                $.ajax({
                    url: "{{ route('cusmas.getKabKota') }}",
                    type: "GET",
                    data: {
                        prov: prov
                    },
                    success: function (response) {
                        console.log(response);
                        $('#kabKotaCusmas').empty();
                        $('#kabKotaCusmas').append(
                            '<option value="" disabled>Silahkan Pilih Kabupaten/Kota</option>'
                        );
                        $.each(response, function (i, item) {
                            let isSelected = selected == item.id ? 'selected' : '';
                            $('#kabKotaCusmas').append(`
                                <option value="${item.id}" ${isSelected}>
                                    ${item.kabupaten}
                                </option>
                            `);
                        });
                        $('#kabKotaCusmas').val(selected).trigger('change.select2');
                    }
                });
            }

            // pertama kali halaman dibuka
            loadKabupaten(
                $('#provinsiCusmas').val(),
                "{{ $cust->kabupaten }}"
            );

            // ketika provinsi diganti
            $('#provinsiCusmas').on('change', function () {
                loadKabupaten($(this).val());
            });
        });

        // sweetalert konfirmasi & sukses
        document.addEventListener("DOMContentLoaded", function() {
            const form = document.getElementById('form-cusmas');

            form.addEventListener('submit', function (e) {
                e.preventDefault();

                if (!form.checkValidity()) {
                    form.classList.add('was-validated');
                    return;
                }

                Swal.fire({
                    title: 'Konfirmasi Update',
                    text: 'Apakah Anda yakin ingin mengubah data Customer ini?',
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
@endsection
