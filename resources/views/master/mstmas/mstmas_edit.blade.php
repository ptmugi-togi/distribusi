@extends('layout.main')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/global.css') }}">
@endpush

@section('container')
<main id="main" class="main">
    <div class="d-flex justify-content-between align-items-center">
        <div class="pagetitle">
            <h1>Edit Shipto</h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('mstmas.index') }}">List Shipto</a></li>
                    <li class="breadcrumb-item active">Edit Shipto</li>
                </ol>
            </nav>
        </div>
    </div>

    <section class="section">
        <div class="card p-3 shacustw-sm">
            {{-- Header --}}
            @php
                $header = $shiptos->first();
            @endphp
            <div class="row">
                <div class="col-md-6 mt-3">
                    <label class="form-label">Branch</label>
                    <input type="text" class="form-control" value="{{ $header->braco }}" disabled>
                </div>

                <div class="col-md-6 mt-3">
                    <label class="form-label">Customer</label>
                    <input type="text" class="form-control" value="{{ $header->cusno }} - {{ $header->cusmas->cusna }}" disabled>
                </div>


            </div>
            
            <form id="formUpdateMstmas" action="{{ route('mstmas.update', ['braco'=>$header->braco,'cusno'=>$header->cusno]) }}" method="POST">
                @csrf
                @method('PUT')

                <input type="text" name="braco" value="{{ $header->braco }}" hidden>
                <input type="text" name="cusno" value="{{ $header->cusno }}" hidden>
                <input type="text" id="cusna" name="cusna" value="{{ $header->cusmas->cusna }}" hidden>

                <div class="accordion mt-3" id="accordionShipto">
                    @foreach($shiptos as $i => $shipto)
                    <div class="accordion-item">
                        <h2 class="accordion-header d-flex justify-content-between align-items-center">
                            <button type="button" class="accordion-button {{ $i ? 'collapsed' : '' }}" data-bs-toggle="collapse" data-bs-target="#shipto{{ $i }}">
                                Shipto: {{ $shipto->shpto }} - {{ $shipto->shpnm }} - {{ $shipto->deliveryaddress }}
                            </button>
                            @if ($i != 0)
                                <button type="button" type="button" class="btn btn-danger btn-sm mx-2" onclick="removeShipto(this)">
                                    <i class="bi bi-trash"></i>
                                </button>
                            @endif
                        </h2>
                        <div id="shipto{{ $i }}" class="accordion-collapse collapse {{ $i == 0 ? 'show' : '' }}" data-bs-parent="#accordionShipto">
                            <div class="accordion-body">
                                <div class="row">
                                    <input type="hidden" name="shpto[]" value="{{ $shipto->shpto }}">
                                    <div class="col-md-6 mt-3">
                                        <label class="form-label">Ship Name</label>
                                        <input class="form-control text-uppercase" id="shpnm" name="shpnm[]" value="{{ $shipto->shpnm }}">
                                    </div>

                                    <div class="col-md-6 mt-3">
                                        <label class="form-label">Address</label>
                                        <textarea class="form-control text-uppercase" name="deliveryaddress[]">{{ $shipto->deliveryaddress }}</textarea>
                                    </div>

                                    <div class="col-md-6 mt-3">
                                        <label class="form-label">Phone</label>
                                        <input class="form-control" name="phone[]" value="{{ $shipto->phone }}">
                                    </div>

                                    <div class="col-md-6 mt-3">
                                        <label class="form-label">Fax</label>
                                        <input class="form-control" name="fax[]" value="{{ $shipto->fax }}">
                                    </div>

                                    <div class="col-md-6 mt-3">
                                        <label class="form-label">Contact Person</label>
                                        <input class="form-control text-uppercase" name="contp[]" value="{{ $shipto->contp }}">
                                    </div>

                                    <div class="col-md-6 mt-3">
                                        <label class="form-label">Nitku</label>
                                        <input class="form-control" name="nitku[]" value="{{ $shipto->nitku }}">
                                    </div>

                                    <div class="col-md-6 mt-3">
                                        <label class="form-label">Provinsi</label>
                                        <select class="form-control select2" id="provinsiMstmas{{ $i }}" name="province[]">
                                            <option value="" disabled>Pilih Provinsi</option>
                                            @foreach($prov as $p)
                                                <option value="{{ $p->id_prov }}"
                                                    {{ $shipto->province == $p->id_prov ? 'selected' : '' }}>
                                                    {{ $p->provinsi }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="col-md-6 mt-3">
                                        <label class="form-label">Kabupaten / Kota</label>
                                        <select class="form-control select2" id="kabKotaMstmas{{ $i }}" name="kabupaten[]">
                                            <option value="">Pilih Kabupaten/Kota</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
                <div class="mt-3 d-flex justify-content-end">
                    <button type="button" class="btn btn-primary" onclick="addShipto()">
                        Tambah Shipto
                    </button>
                </div>

                <div class="mt-4 d-flex justify-content-between">
                    <a href="{{ route('mstmas.index') }}" class="btn btn-secondary">Kembali</a>
                    <button type="button" id="btnUpdate" class="btn btn-primary">Update</button>
                </div>
            </form>
        </div>
    </section>
</main>

@push('scripts')
    <script>
        function loadKabupaten(provSelect, kabSelect, selected = null) {
            let prov = $(provSelect).val();

            $(kabSelect).html('<option>Loading...</option>');

            $.ajax({
                url: "{{ route('cusmas.getKabKota') }}",
                type: "GET",
                data: {
                    prov: prov
                },
                success: function(response) {

                    $(kabSelect).empty();

                    $(kabSelect).append(
                        '<option value="" disabled>Silahkan Pilih Kabupaten/Kota</option>'
                    );

                    $.each(response, function(i, item) {

                        $(kabSelect).append(`
                            <option value="${item.id}">
                                ${item.kabupaten}
                            </option>
                        `);

                    });

                    if(selected){
                        $(kabSelect).val(selected);
                    }

                    $(kabSelect).trigger('change');
                }
            });
        };

        $(document).ready(function () {
            $('.select2').select2({
                width:'100%',
                theme:'bootstrap-5'
            });

            setTimeout(function(){
                @foreach($shiptos as $i => $shipto)
                    loadKabupaten(
                        '#provinsiMstmas{{ $i }}',
                        '#kabKotaMstmas{{ $i }}',
                        '{{ $shipto->kabupaten }}'
                    );

                    $('#provinsiMstmas{{ $i }}').on('change', function () {
                        loadKabupaten(
                            '#provinsiMstmas{{ $i }}',
                            '#kabKotaMstmas{{ $i }}'
                        );
                    });
                @endforeach
            },100);
        });
    </script>
    <script>
        function getNextShipto() {
            let max = 0;

            $('input[name="shpto[]"]').each(function () {
                let val = parseInt($(this).val());

                if (val > max) {
                    max = val;
                }
            });

            return String(max + 1);
        };

        function addShipto(){
                let next = getNextShipto();
                let cusna = $('#cusna').val();
                let index=$('.accordion-item').length;

                let html=`

                <div class="accordion-item">
                    <h2 class="accordion-header d-flex justify-content-between align-items-center">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#ship${index}">
                            Shipto: ${next}
                        </button>
                        <button type="button" type="button" class="btn btn-danger btn-sm mx-2" onclick="removeShipto(this)">
                            <i class="bi bi-trash"></i>
                        </button>
                    </h2>

                    <div id="ship${index}" class="accordion-collapse collapse">
                        <div class="accordion-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <label class="form-label">Shipto</label>
                                    <input class="form-control" name="shpto[]" value="${next}" readonly style="background-color:#e9ecef">
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">Ship Name</label>
                                    <input class="form-control text-uppercase" name="shpnm[]" value="${cusna}">
                                </div>

                                <div class="col-md-6 mt-3">
                                    <label class="form-label">Address</label>
                                    <textarea class="form-control text-uppercase" name="deliveryaddress[]"></textarea>
                                </div>

                                <div class="col-md-6 mt-3">
                                    <label class="form-label">Phone</label>
                                    <input class="form-control" name="phone[]">
                                </div>

                                <div class="col-md-6 mt-3">
                                    <label class="form-label">Fax</label>
                                    <input class="form-control" name="fax[]">
                                </div>

                                <div class="col-md-6 mt-3">
                                    <label class="form-label">Contact</label>
                                    <input class="form-control text-uppercase" name="contp[]">
                                </div>

                                <div class="col-md-6 mt-3">
                                    <label class="form-label">Nitku</label>
                                    <input class="form-control" name="nitku[]">
                                </div>

                                <div class="col-md-6 mt-3">
                                    <label class="form-label">Provinsi</label>
                                    <select class="form-control select2" id="provinsiMstmas${index}" name="province[]">
                                        <option value="" disabled selected>Silahkan Pilih Provinsi</option>
                                        @foreach($prov as $p)
                                            <option value="{{ $p->id_prov }}">{{ $p->provinsi }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-md-6 mt-3">
                                    <label class="form-label">Kabupaten / Kota</label>
                                    <select class="form-control select2" id="kabKotaMstmas${index}" name="kabupaten[]">
                                        <option value="" disabled selected>Silahkan Pilih Kabupaten/Kota</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            `;
            $('#accordionShipto').append(html);

            $('#provinsiMstmas'+index).select2({
                width:'100%',
                theme:'bootstrap-5'
            });

            $('#kabKotaMstmas'+index).select2({
                width:'100%',
                theme:'bootstrap-5'
            });

            $('#provinsiMstmas'+index).change(function(){
                loadKabupaten(
                    '#provinsiMstmas'+index,
                    '#kabKotaMstmas'+index
                );
            });
        }

        function removeShipto(btn){
            $(btn)
                .closest('.accordion-item')
                .remove();
        }
    </script>
    <script>
        $('#btnUpdate').click(function () {
            Swal.fire({
                title: 'Update Shipto?',
                text: 'Perubahan data akan disimpan.',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Ya, Update',
                cancelButtonText: 'Batal',
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33'
            }).then((result)=>{
                if(result.isConfirmed){
                    Swal.fire({
                        title:'Mengupdate...',
                        text:'Mohon tunggu sebentar',
                        allowOutsideClick:false,
                        allowEscapeKey:false,
                        didOpen:()=>{
                            Swal.showLoading();
                        }
                    });

                    $('#formUpdateMstmas').submit();
                }
            });
        });
    </script>
@endpush
@endsection
