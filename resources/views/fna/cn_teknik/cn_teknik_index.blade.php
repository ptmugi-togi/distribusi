@extends('layout.main')

@section('container')
<main id="main" class="main">
    <div class="pagetitle">
        <h1>List Credit Note Teknik</h1>
        <nav>
          <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
            <li class="breadcrumb-item active">List Credit Note Teknik</li>
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
                    <a id="tambahCnDpProject" href="{{ route('cn_teknik.create') }}" type="button" class="btn btn-success"> Tambah CN Teknik</a>
                </div>

                <div class="table-responsive">
                  <table id="myTable" class="table table-striped nowrap" style="width:100%">
                    <thead>
                      <tr>
                          <th class="text-center">BRANCH</th>
                          <th class="text-center">FORMCODE</th>
                          <th class="text-center">CREDIT NOTE NO.</th>
                          <th class="text-center">CREDIT NOTE DATE</th>
                          <th class="text-center">CUSTOMER</th>
                          <th class="text-center">INVOICE NO</th>
                          {{-- <th class="text-center">Action</th> --}}
                      </tr>
                    </thead>
                    <tbody>
                      @foreach ($cnhdr as $c)
                        <tr>
                          <td class="text-center">{{ $c->braco }}</td>
                          <td class="text-center">{{ $c->formc }}</td>
                          <td class="text-center">{{ $c->crnno }}</td>
                          <td class="text-center" data-order="{{ \Carbon\Carbon::parse($c->crndt)->format('Y-m-d') }}">
                              {{ \Carbon\Carbon::parse($c->crndt)->format('d/m/Y') }}
                          </td>
                          <td class="text-left">
                              {{ Str::limit($c->customer->cusna ?? '-', 30, '...') }}
                          </td>
                          <td class="text-center">{{ $c->invfc }} {{ $c->invno ?? '-' }}</td>
                          {{-- <td class="text-center">
                            preview
                            <a href="{{ route('dp_inv_rel.preview', $c->invid) }}" class="badge bg-success" data-tooltip="true" data-bs-placement="top" title="Preview"><i class="bi bi-file-earmark-image-fill"></i></a>
                        
                            print
                            <a href="{{ route('dp_inv_rel.print', $c->invid) }}" class="badge bg-success" data-tooltip="true" data-bs-placement="top" title="Print"><i class="bi bi-file-earmark-arrow-down"></i></a>
                            <a href="/cn-retail/detail/{{ $c->cnid }}" class="badge bg-primary" data-tooltip="true" data-bs-placement="top" title="Detail"><i class="bi bi-info-circle"></i></a>
                            <a href="/cn-retail/edit/{{ $c->cnid }}" class="badge bg-warning" data-tooltip="true" data-bs-placement="top" title="Edit"><i class="bi bi-pencil"></i></a>
                          </td> --}}
                        </tr>
                      @endforeach
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
          order: [[0, 'desc']],
          stateSave: false,
          responsive: true,
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