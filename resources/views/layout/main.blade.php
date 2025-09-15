<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">
  <meta name="csrf-token" content="{{ csrf_token() }}" />

  <title>Distribusi</title>
  <meta content="" name="description">
  <meta content="" name="keywords">

  <link href="{{ URL::asset('img/IconMugi.ico'); }}" rel="icon">
  <link href="{{ URL::asset('img/apple-touch-icon.png'); }}" rel="apple-touch-icon">

  <link href="https://fonts.gstatic.com" rel="preconnect">
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Nunito:300,300i,400,400i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">

  {{-- <link href="{{ URL::asset('css/bootstrap.min.css'); }}" rel="stylesheet"> --}}
  <link href="{{ URL::asset('css/bootstrap-icons.css'); }}" rel="stylesheet">
  <link href="{{ URL::asset('css/boxicons.min.css'); }}" rel="stylesheet">
  <link href="{{ URL::asset('css/quill.snow.css'); }}" rel="stylesheet">
  <link href="{{ URL::asset('css/quill.bubble.css'); }}" rel="stylesheet">
  <link href="{{ URL::asset('css/remixicon.css'); }}" rel="stylesheet">
  <link href="{{ URL::asset('css/datatables/style.css'); }}" rel="stylesheet">
  {{-- <link rel="stylesheet" href="https://cdn.datatables.net/2.0.2/css/dataTables.dataTables.css" /> --}}
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/css/bootstrap.min.css" />
  <link rel="stylesheet" href="https://cdn.datatables.net/2.0.3/css/dataTables.bootstrap5.css" />
  <link rel="stylesheet" href="https://cdn.datatables.net/responsive/3.0.0/css/responsive.bootstrap5.css" />
  <link href="{{ URL::asset('css/style.css'); }}" rel="stylesheet">


</head>

<body>

  <!-- ======= Header ======= -->
  @include('partials.header')
  <!-- End Header -->

  <!-- ======= Sidebar ======= -->
  @include('partials.navbar')
  <!-- End Sidebar-->

  <div class="container-fluid">
    @yield('container')
  </div>


  <!-- ======= Footer ======= -->
  @include('partials.footer')
  <!-- End Footer -->

  <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

  <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
  <script src="{{ URL::asset('js/apexcharts.min.js'); }}"></script>
  {{-- <script src="{{ URL::asset('js/bootstrap.bundle.min.js'); }}"></script> --}}
  <script src="{{ URL::asset('js/chart.umd.js'); }}"></script>
  <script src="{{ URL::asset('js/echarts.min.js'); }}"></script>
  <script src="{{ URL::asset('js/quill.min.js'); }}"></script>
  <script src="{{ URL::asset('js/simple-datatables.js'); }}"></script>
  <script src="{{ URL::asset('js/tinymce.min.js'); }}"></script>
  <script src="{{ URL::asset('js/validate.js'); }}"></script>
  <script src="{{ URL::asset('js/main.js'); }}"></script>
  {{-- <script src="https://cdn.datatables.net/2.0.2/js/dataTables.js"></script> --}}
  <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
  <script src="https://cdn.datatables.net/2.0.3/js/dataTables.js"></script>
  <script src="https://cdn.datatables.net/2.0.3/js/dataTables.bootstrap5.js"></script>
  <script src="https://cdn.datatables.net/responsive/3.0.0/js/dataTables.responsive.js"></script>
  <script src="https://cdn.datatables.net/responsive/3.0.0/js/responsive.bootstrap5.js"></script>
  <script src="{{ URL::asset('js/js.js'); }}"></script>

  <script>
    $(document).ready(function () {
        $('#myTable26').DataTable({
            processing: true,
            serverSide: true,
            responsive: true,
            columnDefs: [{ "className": "dt-left", "targets": "_all" }, ],
            ajax: '{{ url()->current() }}',
            columns: [
                {
                    data: 'groupp',
                    name: 'groupp',
                    orderable: true,
                    searchable: true,
                    width: '25%'
                },
                {
                    data: 'title',
                    name: 'title',
                    orderable: true,
                    searchable: true,
                    width: '5%'
                },
                {
                    data: 'nama_perusahaan',
                    name: 'nama_perusahaan',
                    orderable: true,
                    searchable: true,
                    width: '25%'
                },
                {
                    data: 'lokasi',
                    name: 'lokasi',
                    orderable: true,
                    searchable: true,
                    width: '25%'
                },
                {
                    data: 'alamat',
                    name: 'alamat',
                    orderable: true,
                    searchable: true,
                    width: '20%'
                },
                {
                    data: 'pos',
                    name: 'pos',
                    orderable: true,
                    searchable: true,
                },
                {
                    data: 'telp',
                    name: 'telp',
                    orderable: true,
                    searchable: true,
                },
                {
                    data: 'fax',
                    name: 'fax',
                    orderable: true,
                    searchable: true,
                },
                {
                    data: 'email',
                    name: 'email',
                    orderable: true,
                    searchable: true,
                },
                {
                    data: 'kontak',
                    name: 'kontak',
                    orderable: true,
                    searchable: true,
                },
                {
                    data: 'npwp',
                    name: 'npwp',
                    orderable: true,
                    searchable: true,
                },
                {
                    data: 'nik',
                    name: 'nik',
                    orderable: true,
                    searchable: true,
                },
                {
                    data: 'provinsi',
                    name: 'provinsi',
                    orderable: true,
                    searchable: true,
                },
                {
                    data: 'cindu',
                    name: 'cindu',
                    orderable: true,
                    searchable: true,
                },
                {
                    data: 'braco',
                    name: 'braco',
                    orderable: true,
                    searchable: true,
                },
                {
                    data: 'depo',
                    name: 'depo',
                    orderable: true,
                    searchable: true,
                },
                {
                    data: 'telp_kontak',
                    name: 'telp_kontak',
                    orderable: true,
                    searchable: true,
                },
                {
                    data: 'kabupaten',
                    name: 'kabupaten',
                    orderable: true,
                    searchable: true,
                },
                {
                    data: 'action',
                    name: 'action',
                    orderable: false,
                    searchable: false,
                }
            ]
        });
        $('#example26').DataTable({
            processing: true,
            serverSide: true,
            responsive: true,
            columnDefs: [{ "className": "dt-left", "targets": "_all" }, ],
            ajax: '{{ url()->current() }}',
            columns: [
                {
                    data: 'opron',
                    name: 'opron',
                    orderable: true,
                    searchable: true,
                    width: '25%'
                },
                {
                    data: 'prona',
                    name: 'prona',
                    orderable: true,
                    searchable: true,
                    width: '25%'
                },
                {
                    data: 'nama_supplier',
                    name: 'nama_supplier',
                    orderable: true,
                    searchable: true,
                    width: '25%'
                },
                {
                    data: 'stdqu',
                    name: 'stdqu',
                    orderable: true,
                    searchable: true,
                    width: '25%'
                },
                {
                    data: 'itype_id',
                    name: 'itype_id',
                    orderable: true,
                    searchable: true,
                    width: '25%'
                },
                {
                    data: 'brand_name',
                    name: 'brand_name',
                    orderable: true,
                    searchable: true,
                    width: '25%'
                },
                {
                    data: 'pgrup',
                    name: 'pgrup',
                    orderable: true,
                    searchable: true,
                    width: '25%'
                },
                {
                    data: 'sgrup_id',
                    name: 'sgrup_id',
                    orderable: true,
                    searchable: true,
                    width: '25%'
                },
                {
                    data: 'ssgrup_id',
                    name: 'ssgrup_id',
                    orderable: true,
                    searchable: true,
                    width: '25%'
                },
                {
                    data: 'lssgrup',
                    name: 'lssgrup',
                    orderable: true,
                    searchable: true,
                    width: '25%'
                },
                {
                    data: 'weigh',
                    name: 'weigh',
                    orderable: true,
                    searchable: true,
                    width: '25%'
                },
                {
                    data: 'meast',
                    name: 'meast',
                    orderable: true,
                    searchable: true,
                    width: '25%'
                },
                {
                    data: 'measl',
                    name: 'measl',
                    orderable: true,
                    searchable: true,
                    width: '25%'
                },
                {
                    data: 'measp',
                    name: 'measp',
                    orderable: true,
                    searchable: true,
                    width: '25%'
                },
                {
                    data: 'volum',
                    name: 'volum',
                    orderable: true,
                    searchable: true,
                    width: '25%'
                },
                {
                    data: 'abccl',
                    name: 'abccl',
                    orderable: true,
                    searchable: true,
                    width: '25%'
                },
                {
                    data: 'capac',
                    name: 'capac',
                    orderable: true,
                    searchable: true,
                    width: '25%'
                },
                {
                    data: 'platf',
                    name: 'platf',
                    orderable: true,
                    searchable: true,
                    width: '25%'
                },
                {
                    data: 'mstok',
                    name: 'mstok',
                    orderable: true,
                    searchable: true,
                    width: '25%'
                },
                {
                    data: 'spnum',
                    name: 'spnum',
                    orderable: true,
                    searchable: true,
                    width: '25%'
                },
                {
                    data: 'garansi',
                    name: 'garansi',
                    orderable: true,
                    searchable: true,
                    width: '25%'
                },
                {
                    data: 'pbilp',
                    name: 'pbilp',
                    orderable: true,
                    searchable: true,
                    width: '25%'
                },
                {
                    data: 'ijtype',
                    name: 'ijtype',
                    orderable: true,
                    searchable: true,
                    width: '25%'
                },
                {
                    data: 'id_cls',
                    name: 'id_cls',
                    orderable: true,
                    searchable: true,
                    width: '25%'
                },
                {
                    data: 'action',
                    name: 'action',
                    orderable: false,
                    searchable: false,
                }
            ]
        });
        $('#example27').DataTable({
            processing: true,
            serverSide: true,
            responsive: true,
            columnDefs: [{ "className": "dt-left", "targets": "_all" }, ],
            ajax: '{{ url()->current() }}',
            columns: [
                {
                    data: 'braco',
                    name: 'braco',
                    orderable: true,
                    searchable: true,
                    width: '25%'
                },
                {
                    data: 'sreno',
                    name: 'sreno',
                    orderable: true,
                    searchable: true,
                    width: '25%'
                },
                {
                    data: 'srena',
                    name: 'srena',
                    orderable: true,
                    searchable: true,
                    width: '25%'
                },
                {
                    data: 'steam',
                    name: 'steam',
                    orderable: true,
                    searchable: true,
                    width: '25%'
                },
                {
                    data: 'address',
                    name: 'address',
                    orderable: true,
                    searchable: true,
                    width: '25%'
                },
                {
                    data: 'phone',
                    name: 'phone',
                    orderable: true,
                    searchable: true,
                    width: '25%'
                },
                {
                    data: 'grade',
                    name: 'grade',
                    orderable: true,
                    searchable: true,
                    width: '25%'
                },
                {
                    data: 'aktif',
                    name: 'aktif',
                    orderable: true,
                    searchable: true,
                    width: '25%'
                },
                {
                    data: 'action',
                    name: 'action',
                    orderable: false,
                    searchable: false,
                }
            ]
        });
        $('#example28').DataTable({
            processing: true,
            serverSide: true,
            responsive: true,
            columnDefs: [{ "className": "dt-left", "targets": "_all" }, ],
            ajax: '{{ url()->current() }}',
            columns: [
                {
                    data: 'braco',
                    name: 'braco',
                    orderable: true,
                    searchable: true,
                    width: '25%'
                },
                {
                    data: 'cusno',
                    name: 'cusno',
                    orderable: true,
                    searchable: true,
                    width: '25%'
                },
                {
                    data: 'shpto',
                    name: 'shpto',
                    orderable: true,
                    searchable: true,
                    width: '25%'
                },
                {
                    data: 'shpnm',
                    name: 'shpnm',
                    orderable: true,
                    searchable: true,
                    width: '25%'
                },
                {
                    data: 'deliveryaddress',
                    name: 'deliveryaddress',
                    orderable: true,
                    searchable: true,
                    width: '25%'
                },
                {
                    data: 'phone',
                    name: 'phone',
                    orderable: true,
                    searchable: true,
                    width: '25%'
                },
                {
                    data: 'fax',
                    name: 'fax',
                    orderable: true,
                    searchable: true,
                    width: '25%'
                },
                {
                    data: 'contp',
                    name: 'contp',
                    orderable: true,
                    searchable: true,
                    width: '25%'
                },
                {
                    data: 'province',
                    name: 'province',
                    orderable: true,
                    searchable: true,
                    width: '25%'
                },
                {
                    data: 'kabupaten',
                    name: 'kabupaten',
                    orderable: true,
                    searchable: true,
                    width: '25%'
                },
                {
                    data: 'action',
                    name: 'action',
                    orderable: false,
                    searchable: false,
                }
            ]
        });
        $('#example29').DataTable({
            processing: true,
            serverSide: true,
            responsive: true,
            columnDefs: [{ "className": "dt-left", "targets": "_all" }, ],
            ajax: '{{ url()->current() }}',
            columns: [
                {
                    data: 'cusno',
                    name: 'cusno',
                    orderable: true,
                    searchable: true,
                    width: '25%'
                },
                {
                    data: 'braco',
                    name: 'braco',
                    orderable: true,
                    searchable: true,
                    width: '25%'
                },
                {
                    data: 'cusna',
                    name: 'cusna',
                    orderable: true,
                    searchable: true,
                    width: '25%'
                },
                {
                    data: 'billn',
                    name: 'billn',
                    orderable: true,
                    searchable: true,
                    width: '25%'
                },
                {
                    data: 'title',
                    name: 'title',
                    orderable: true,
                    searchable: true,
                    width: '25%'
                },
                {
                    data: 'prpos',
                    name: 'prpos',
                    orderable: true,
                    searchable: true,
                    width: '25%'
                },
                {
                    data: 'pkp',
                    name: 'pkp',
                    orderable: true,
                    searchable: true,
                    width: '25%'
                },
                {
                    data: 'npwp',
                    name: 'npwp',
                    orderable: true,
                    searchable: true,
                    width: '25%'
                },
                {
                    data: 'address',
                    name: 'address',
                    orderable: true,
                    searchable: true,
                    width: '25%'
                },
                {
                    data: 'city',
                    name: 'city',
                    orderable: true,
                    searchable: true,
                    width: '25%'
                },
                {
                    data: 'kodepost',
                    name: 'kodepost',
                    orderable: false,
                    searchable: false,
                },
                {
                    data: 'phone',
                    name: 'phone',
                    orderable: false,
                    searchable: false,
                },
                {
                    data: 'fax',
                    name: 'fax',
                    orderable: false,
                    searchable: false,
                },
                {
                    data: 'contact',
                    name: 'contact',
                    orderable: false,
                    searchable: false,
                },
                {
                    data: 'email',
                    name: 'email',
                    orderable: false,
                    searchable: false,
                },
                {
                    data: 'topay',
                    name: 'topay',
                    orderable: false,
                    searchable: false,
                },
                {
                    data: 'cindu',
                    name: 'cindu',
                    orderable: false,
                    searchable: false,
                },
                {
                    data: 'czone',
                    name: 'czone',
                    orderable: false,
                    searchable: false,
                },
                {
                    data: 'carea',
                    name: 'carea',
                    orderable: false,
                    searchable: false,
                },
                {
                    data: 'dopen',
                    name: 'dopen',
                    orderable: false,
                    searchable: false,
                },
                {
                    data: 'crlim',
                    name: 'crlim',
                    orderable: false,
                    searchable: false,
                },
                {
                    data: 'lauid',
                    name: 'lauid',
                    orderable: false,
                    searchable: false,
                },
                {
                    data: 'ladup',
                    name: 'ladup',
                    orderable: false,
                    searchable: false,
                },
                {
                    data: 'status',
                    name: 'status',
                    orderable: false,
                    searchable: false,
                },
                {
                    data: 'barval',
                    name: 'barval',
                    orderable: false,
                    searchable: false,
                },
                {
                    data: 'openo',
                    name: 'openo',
                    orderable: false,
                    searchable: false,
                },
                {
                    data: 'oarval',
                    name: 'oarval',
                    orderable: false,
                    searchable: false,
                },
                {
                    data: 'csect',
                    name: 'csect',
                    orderable: false,
                    searchable: false,
                },
                {
                    data: 'action',
                    name: 'action',
                    orderable: false,
                    searchable: false,
                }
            ]
        });
    });
  </script>

</body>

</html>
