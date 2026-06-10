@extends('layout.main')

@section('container')
<main id="main" class="main">
    <div class="pagetitle">
        <h1>List Maintenance Contract</h1>
        <nav>
          <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
            <li class="breadcrumb-item active">List Maintenance Contract</li>
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
                    <a id="tambahMc" href="{{ route('maintenance_contract.create') }}" type="button" class="btn btn-success"> Tambah</a>
                </div>

                <table id="myTable" class="table table-striped nowrap" style="width:100%">
                  <thead>
                    <tr>
                        <th class="text-center">Branch</th>
                        <th class="text-center">MC No.</th>
                        <th class="text-center">MC Date</th>
                        <th class="text-center">MC Awal</th>
                        <th class="text-center">MC Akhir</th>
                        <th class="text-center">Customer</th>
                        <th class="text-center">Customer PO</th>
                        <th class="text-center">Action</th>
                        <th class="text-center">Created At</th>
                    </tr>
                  </thead>
                  <tbody>
                    @foreach ($mc as $m)
                    @if ($m->formc === 'MC')
                      <tr>
                          <td class="text-center">{{ $m->braco ?? '-' }}</td>
                          <td class="">{{ $m->refno ?? '-' }}</td>
                          <td class="text-center" data-order="{{ \Carbon\Carbon::parse($m->tradt)->format('Y-m-d') }}">
                              {{ \Carbon\Carbon::parse($m->mcdat)->format('d/m/Y') }}
                          </td>
                          <td class="text-center" data-order="{{ \Carbon\Carbon::parse($m->tradt)->format('Y-m-d') }}">
                              {{ \Carbon\Carbon::parse($m->gmcfr)->format('d/m/Y') }}
                          </td>
                          <td class="text-center" data-order="{{ \Carbon\Carbon::parse($m->tradt)->format('Y-m-d') }}">
                              {{ \Carbon\Carbon::parse($m->gmcto)->format('d/m/Y') }}
                          </td>
                          <td class="">{{ $m->mcusmas->cusna ?? '-' }}</td>
                          <td class="">{{ $m->mcnom ?? '-' }}</td>
                          <td class="text-center">
                              {{-- preview --}}
                              {{-- <a href="{{ route('maintenance_contract.preview', $m->mcid) }}" class="badge bg-success" data-tooltip="true" data-bs-placement="top" title="Preview"><i class="bi bi-file-earmark-image-fill"></i></a> --}}

                              {{-- print --}}
                              @if (!$periodClosed && $m->braco == auth()->user()->cabang && $m->resta != 'C')
                                {{-- <a href="{{ route('maintenance_contract.print', $m->mcid) }}" class="badge bg-success" data-tooltip="true" data-bs-placement="top" title="Print"><i class="bi bi-file-earmark-arrow-down"></i></a> --}}
                                
                                <a href="/maintenance-contract/detail/{{ $m->mcid }}" class="badge bg-primary" data-tooltip="true" data-bs-placement="top" title="Detail"><i class="bi bi-info-circle"></i></a>
                                <a href="/maintenance-contract/edit/{{ $m->mcid }}" class="badge bg-warning" data-tooltip="true" data-bs-placement="top" title="Edit"><i class="bi bi-pencil"></i></a>
                                <form id="cancel-mc-{{ $m->mcid }}" action="{{ route('maintenance_contract.cancel', $m->mcid) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('PATCH')

                                    <a class="badge bg-danger btn-cancel-mc"
                                        data-mcid="{{ $m->mcid }}"
                                        data-tooltip="true"
                                        data-bs-placement="top"
                                        title="Cancel"
                                        style="cursor: pointer;">
                                        <i class="bi bi-x-circle"></i>
                                    </a>
                                </form>
                              @endif
                              @if ($m->resta == 'C')
                                <a href="/maintenance-contract/detail/{{ $m->mcid }}" class="badge bg-primary" data-tooltip="true" data-bs-placement="top" title="Detail"><i class="bi bi-info-circle"></i></a>
                                <div class="badge bg-danger">CANCELED</div>
                              @endif
                          </td>
                          <td class="text-center" data-order="{{ \Carbon\Carbon::parse($m->created_at)->format('Y-m-d H:i:s') }}">
                              {{ \Carbon\Carbon::parse($m->created_at)->format('d/m/Y H:i:s') }}
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
          order: [[8, 'desc']],
          stateSave: false,
          responsive: true,
          columnDefs: [
            { targets: [8], visible: false }
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

      $(document).on('click', '.btn-cancel-mc', function(e){
        e.preventDefault();

        let mcid = $(this).data('mcid');

        Swal.fire({
            title: 'Cancel Maintenance Contract?',
            text: 'Data akan dicancel.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Ya, Cancel',
            cancelButtonText: 'Batal',
            confirmButtonColor: '#d33',
            cancelButtonColor: '#6c757d'
        }).then((result) => {
            if(result.isConfirmed){
                $('#cancel-mc-' + mcid).submit();
            }
        });
    });
    </script>
@endpush

@endsection