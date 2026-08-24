@extends('layout.main')

@section('container')
<main id="main" class="main">
    <div class="pagetitle">
        <h1>List Service Invoice Release</h1>
        <nav>
          <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
            <li class="breadcrumb-item active">List Service Invoice Release</li>
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
                <div class="col-lg-12" style="padding:0px 10px 10px 0px;">
                    <a id="tambahSir" href="{{ route('service_invoice_release.create') }}" type="button" class="btn btn-success"> Tambah</a>
                </div>

                <table id="myTable" class="table table-striped nowrap" style="width:100%">
                  <thead>
                    <tr>
                        <th class="text-center">Branch</th>
                        <th class="text-center">Invoice No.</th>
                        <th class="text-center">Customer</th>
                        <th class="text-center">Invoice Date</th>
                        <th class="text-center">Due Date</th>
                        <th class="text-center">Reference</th>
                        <th class="text-center">Action</th>
                        <th class="text-center">Created At</th>
                    </tr>
                  </thead>
                  <tbody>
                    @foreach ($sir as $s)
                    @if ($s->formc === 'SD')
                      <tr>
                          <td class="text-center">{{ $s->braco ?? '-' }}</td>
                          <td class="text-center">SD {{ $s->invno ?? '-' }}</td>
                          <td>{{ $s->cusna ?? '-' }}</td>
                          <td class="text-center" data-order="{{ \Carbon\Carbon::parse($s->invdt)->format('Y-m-d') }}">
                              {{ \Carbon\Carbon::parse($s->invdt)->format('d/m/Y') }}
                          </td>
                          <td class="text-center" data-order="{{ \Carbon\Carbon::parse($s->duedt)->format('Y-m-d') }}">
                              {{ \Carbon\Carbon::parse($s->duedt)->format('d/m/Y') }}
                          </td>
                          <td class="text-center">{{ $s->dorfc ?? '-' }} {{ $s->donom ?? '-' }}</td>
                          <td class="text-center">
                              {{-- preview --}}
                              {{-- <a href="{{ route('service_invoice_release.preview', $s->invid) }}" class="badge bg-success" data-tooltip="true" data-bs-placement="top" title="Preview"><i class="bi bi-file-earmark-image-fill"></i></a> --}}

                              {{-- print --}}
                              @if (!$periodClosed && $s->braco == auth()->user()->cabang)
                                <a href="{{ route('service_invoice_release.print', $s->invid) }}" class="badge bg-success" data-tooltip="true" data-bs-placement="top" title="Print"><i class="bi bi-file-earmark-arrow-down"></i></a>
                              @endif
                          </td>
                          <td class="text-center" data-order="{{ \Carbon\Carbon::parse($s->created_at)->format('Y-m-d H:i:s') }}">
                              {{ \Carbon\Carbon::parse($s->created_at)->format('d/m/Y H:i:s') }}
                          </td>
                      </tr>
                    @endif
                    @endforeach
                  </tbody>
                </table>
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
          order: [[6, 'desc']],
          stateSave: false,
          responsive: true,
          columnDefs: [
            { targets: [6], visible: false }
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