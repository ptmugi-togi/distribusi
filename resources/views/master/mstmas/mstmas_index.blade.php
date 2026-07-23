@extends('layout.main')
@section('container')

<main id="main" class="main">
    <div class="pagetitle">
        <h1>SHIPTO MASTER</h1>
        <nav>
            <ol class="breadcrumb">
            <li class="breadcrumb-item">MASTER</li>
            <li class="breadcrumb-item active">SHIPTO</li>
            </ol>
        </nav>
    </div>

    <section class="section">
        <div class="row">
          <div class="col-lg-12">
            <div class="card">
              <div class="card-body">
                @if(session()->has('success'))
                  <div class="alert alert-success alert-dismissible fade show" role="alert">
                      {{ session('success') }}
                      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                  </div>
                @endif
                @if(session()->has('error'))
                  <div class="alert alert-danger alert-dismissible fade show" role="alert">
                      {{ session('error') }}
                      <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                  </div>
                @endif

                <table id="myTable" class="table table-striped nowrap" style="width:100%">
                    <thead>
                          <tr>
                            <th>Branch</th>
                            <th>Customer</th>
                            <th class="text-left">NPWP</th>
                            <th class="text-center">Action</th>
                          </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
              </div>
            </div>
          </div>
        </div>
    </section>
</main>
@push('scripts')
    <script>
      $(function () {
        var table = $('#myTable').DataTable({
          destroy: true,
          order: [[0, 'asc']],
          stateSave: false,
          responsive: true,
          processing: true,
            serverSide: true,
            ajax: "{{ route('mstmas.data') }}",
            columns: [
                { data: 'braco', name: 'braco', searchable: false },
                { data: 'customer', name: 'customer' },
                { data: 'npwp', name: 'npwp', searchable: false, className: 'text-start' },
                { data: 'action', name: 'action', orderable: false, searchable: false, className: 'text-center' },
            ]
        });

        function initTooltip() {
          document.querySelectorAll('[data-tooltip="true"]').forEach(function (el) {
            new bootstrap.Tooltip(el, {
              container: 'body',
              boundary: 'window',
              placement: 'top'
            });
          });
        }

        initTooltip();

        table.on('draw responsive-display', function () {
          initTooltip();
        });
      });
    </script>
@endpush
@endsection