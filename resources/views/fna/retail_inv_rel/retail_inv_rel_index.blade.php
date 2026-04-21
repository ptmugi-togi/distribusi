@extends('layout.main')

@section('container')
<main id="main" class="main">
    <div class="pagetitle">
        <h1>List Retail Invoice Release</h1>
        <nav>
          <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
            <li class="breadcrumb-item active">List Retail Invoice Release</li>
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
                    <a id="tambahRetailInvRel" href="{{ route('retail_inv_rel.create') }}" type="button" class="btn btn-success"> Release Retail Invoice</a>
                </div>

                <div class="table-responsive">
                  <table id="myTable" class="table table-striped nowrap" style="width:100%">
                    <thead>
                      <tr>
                          <th class="text-center">INVOICE#</th>
                          <th class="text-center">INVOICE DATE</th>
                          <th class="text-center">OC NO.</th>
                          <th class="text-center">OC Date</th>
                          <th class="text-center">CUSTOMER</th>
                          <th class="text-center">DP %</th>
                          <th class="text-center">DP AMOUNT</th>
                          <th class="text-center">Action</th>
                      </tr>
                    </thead>
                    <tbody>
                      @foreach ($retail_inv_rel as $p)
                        <tr>
                            <td class="text-center">{{ $p->formc }} {{ $p->invno ?? '-' }}</td>
                            <td class="text-center" data-order="{{ \Carbon\Carbon::parse($p->invdt)->format('Y-m-d') }}">
                                {{ \Carbon\Carbon::parse($p->invdt)->format('d/m/Y') }}
                            </td>
                            <td class="text-center">{{ $p->sorfc }} {{ $p->sorno ?? '-' }}</td>
                            <td class="text-center" data-order="{{ \Carbon\Carbon::parse($p->duedt)->format('Y-m-d') }}">
                                {{ \Carbon\Carbon::parse($p->duedt)->format('d/m/Y') }}
                            </td>
                            <td class="text-left">{{ $p->mcusmas->cusna ?? '-' }}</td>
                            <td class="text-center">{{ $p->dpper ?? '-' }}</td>
                            <td class="text-right">{{ number_format($p->dpamt, 0) }}</td>
                        <td class="text-center">
                          {{-- preview --}}
                          {{-- <a href="{{ route('retail_inv_rel.preview', $p->invid) }}" class="badge bg-success" data-tooltip="true" data-bs-placement="top" title="Preview"><i class="bi bi-file-earmark-image-fill"></i></a> --}}
                      
                          @if (!$periodClosed && $p->braco == auth()->user()->cabang)
                            {{-- print --}}
                            @if ($p->resta != 'C')
                            <a href="{{ route('retail_inv_rel.print', $p->invid) }}" class="badge bg-success" data-tooltip="true" data-bs-placement="top" title="Print"><i class="bi bi-file-earmark-arrow-down"></i></a>
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