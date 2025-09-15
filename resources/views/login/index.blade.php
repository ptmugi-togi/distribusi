<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">
  <meta name="csrf-token" content="{{ csrf_token() }}" />

  <title>{{ $title }} Distribusi</title>
  <meta content="" name="description">
  <meta content="" name="keywords">

  <link href="{{ URL::asset('img/IconMugi.ico'); }}" rel="icon">
  <link href="{{ URL::asset('img/apple-touch-icon.png'); }}" rel="apple-touch-icon">

  <link href="https://fonts.gstatic.com" rel="preconnect">
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Nunito:300,300i,400,400i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">

  <link href="{{ URL::asset('css/bootstrap.min.css'); }}" rel="stylesheet">
  <link href="{{ URL::asset('css/bootstrap-icons.css'); }}" rel="stylesheet">
  <link href="{{ URL::asset('css/boxicons.min.css'); }}" rel="stylesheet">
  <link href="{{ URL::asset('css/quill.snow.css'); }}" rel="stylesheet">
  <link href="{{ URL::asset('css/quill.bubble.css'); }}" rel="stylesheet">
  <link href="{{ URL::asset('css/remixicon.css'); }}" rel="stylesheet">
  <link href="{{ URL::asset('css/datatables/style.css'); }}" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/css/bootstrap.min.css" />
  <link rel="stylesheet" href="https://cdn.datatables.net/2.0.3/css/dataTables.bootstrap5.css" />
  <link rel="stylesheet" href="https://cdn.datatables.net/responsive/3.0.0/css/responsive.bootstrap5.css" />
  <link href="{{ URL::asset('css/style.css'); }}" rel="stylesheet">


</head>

<body>

    <main>
        <div class="container">

        <section class="section register min-vh-100 d-flex flex-column align-items-center justify-content-center py-4">
            <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-4 col-md-6 d-flex flex-column align-items-center justify-content-center">
                    <div class="d-flex justify-content-center py-4">
                        <a href="index.html" class="logo d-flex align-items-center w-auto">
                        <img src="{{ URL::asset('img/logomugi.png'); }}" alt="">
                        {{-- <span class="d-none d-lg-block"><img src="{{ URL::asset('img/logomugi.png'); }}" alt="logo" class="logo"></span> --}}
                        </a>
                    </div><!-- End Logo -->

                    @if(session()->has('loginError'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            {{ session('loginError') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif
                    <div class="card mb-3">
                        <div class="card-body">
                            <div class="pt-4 pb-2">
                                <h5 class="card-title text-center pb-0 fs-4">Login to Your Account</h5>
                                {{-- <p class="text-center small">Enter your username & password to login</p> --}}
                            </div>

                            <form class="row g-3 needs-validation" novalidate id="inputform" action="/login" method="post">
                            @csrf
                                <div class="col-12">
                                    <label for="username" class="form-label">Username</label>
                                    <div class="input-group has-validation">
                                        {{-- <span class="input-group-text" id="inputGroupPrepend">@</span> --}}
                                        <input type="text" name="username" class="form-control" id="username" required autofocus data-index="1" @error('username') is-invalid @enderror value="{{ old('username') }}">
                                        @error('username')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-12">
                                    <label for="paswword" class="form-label">Password</label>
                                    <input type="password" name="password" class="form-control" id="password" required data-index="2" @error('password') is-invalid @enderror>
                                    @error('password')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- <div class="col-12">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="remember" value="true" id="rememberMe">
                                    <label class="form-check-label" for="rememberMe">Remember me</label>
                                </div>
                                </div> --}}
                                <div class="col-12">
                                    <button class="btn btn-primary w-100" type="submit" data-index="3">Login</button>
                                </div>
                                <div class="col-12">
                                    <p class="small mb-0">Don't have account? <a href="pages-register.html">Create an account</a></p>
                                </div>
                            </form>

                        </div>
                    </div>

                </div>
            </div>
            </div>

        </section>

        </div>
    </main><!-- End #main -->

  <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

  <script src="{{ URL::asset('js/apexcharts.min.js'); }}"></script>
  <script src="{{ URL::asset('js/bootstrap.bundle.min.js'); }}"></script>
  <script src="{{ URL::asset('js/chart.umd.js'); }}"></script>
  <script src="{{ URL::asset('js/echarts.min.js'); }}"></script>
  <script src="{{ URL::asset('js/quill.min.js'); }}"></script>
  <script src="{{ URL::asset('js/simple-datatables.js'); }}"></script>
  <script src="{{ URL::asset('js/tinymce.min.js'); }}"></script>
  <script src="{{ URL::asset('js/validate.js'); }}"></script>
  <script src="{{ URL::asset('js/main.js'); }}"></script>
  <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
  {{-- <script src="https://cdn.datatables.net/2.0.2/js/dataTables.js"></script> --}}
  <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
  <script src="https://cdn.datatables.net/2.0.3/js/dataTables.js"></script>
  <script src="https://cdn.datatables.net/2.0.3/js/dataTables.bootstrap5.js"></script>
  <script src="https://cdn.datatables.net/responsive/3.0.0/js/dataTables.responsive.js"></script>
  <script src="https://cdn.datatables.net/responsive/3.0.0/js/responsive.bootstrap5.js"></script>
  <script src="{{ URL::asset('js/js.js'); }}"></script>

  <script>
    $('#inputform').on('keydown', 'input', function (event) {
        if (event.which == 13) {
            event.preventDefault();
            var $this = $(event.target);
            var index = parseFloat($this.attr('data-index'));
            $('[data-index="' + (index + 1).toString() + '"]').focus();
        }
    });
  </script>
</body>

</html>
