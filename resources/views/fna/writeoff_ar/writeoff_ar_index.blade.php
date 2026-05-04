@extends('layout.main')

@section('container')
<main id="main" class="main">
    <div class="pagetitle">
        <h1>List Write Off A/R</h1>
        <nav>
          <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
            <li class="breadcrumb-item active">List Write Off A/R</li>
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
                    <a id="tambahInvoicePayment" href="{{ route('writeoff_ar.create') }}" type="button" class="btn btn-success"> Write Off A/R Create</a>
                </div>

                <div class="table-responsive">
                  <table id="myTable" class="table table-striped nowrap" style="width:100%">
                    <thead>
                      <tr>
                          <th class="text-center">BRANCH</th>
                          <th class="text-center">WO#</th>
                          <th class="text-center">WO DATE</th>
                          <th class="text-center">Action</th>
                      </tr>
                    </thead>
                    <tbody>
                      @foreach ($writeoff_ar as $i)
                        <tr>
                            <td class="text-center">{{ $i->braco }}</td>
                            <td class="text-center">{{ $i->formc }} {{ $i->vcrno ?? '-' }}</td>
                            <td class="text-center" data-order="{{ \Carbon\Carbon::parse($i->pdate)->format('Y-m-d') }}">
                                {{ \Carbon\Carbon::parse($i->pdate)->format('d/m/Y') }}
                            </td>
                        <td class="text-center">
                          {{-- preview --}}
                          {{-- <a href="{{ route('retail_inv_rel.preview', $i->invid) }}" class="badge bg-success" data-tooltip="true" data-bs-placement="top" title="Preview"><i class="bi bi-file-earmark-image-fill"></i></a> --}}
                      
                          @if (!$periodClosed && $i->braco == auth()->user()->cabang)
                            <a href="/writeoff-ar/detail/{{ $i->woffid }}" class="badge bg-primary" data-tooltip="true" data-bs-placement="top" title="Detail"><i class="bi bi-info-circle"></i></a>
                            <a href="/writeoff-ar/edit/{{ $i->woffid }}" class="badge bg-warning" data-tooltip="true" data-bs-placement="top" title="Edit"><i class="bi bi-pencil"></i></a>
                            {{-- print --}}
                            @if ($i->resta != 'C')
                            {{-- <a href="{{ route('retail_inv_rel.print', $i->invid) }}" class="badge bg-success" data-tooltip="true" data-bs-placement="top" title="Print"><i class="bi bi-file-earmark-arrow-down"></i></a> --}}
                            @endif
                          @endif
                        </td>
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