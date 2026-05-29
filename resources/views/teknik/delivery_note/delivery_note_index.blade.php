@extends('layout.main')

@section('container')
<main id="main" class="main">
    <div class="pagetitle">
        <h1>List Delivery Note</h1>
        <nav>
          <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
            <li class="breadcrumb-item active">List Delivery Note</li>
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
                    <a id="tambahDn" href="{{ route('delivery_note.create') }}" type="button" class="btn btn-success"> Tambah</a>
                </div>

                <table id="myTable" class="table table-striped nowrap" style="width:100%">
                  <thead>
                    <tr>
                        <th class="text-center">Branch</th>
                        <th class="text-center">DN No.</th>
                        <th class="text-center">DN Date</th>
                        <th class="text-center">Customer</th>
                        <th class="text-center">Customer PO</th>
                        <th class="text-center">Action</th>
                        <th class="text-center">Created At</th>
                    </tr>
                  </thead>
                  <tbody>
                    @foreach ($dn as $d)
                    @if ($d->formc === 'DN')
                      <tr>
                          <td class="text-center">{{ $d->braco ?? '-' }}</td>
                          <td class="text-center">DN {{ $d->dnnum ?? '-' }}</td>
                          <td class="text-center" data-order="{{ \Carbon\Carbon::parse($d->tradt)->format('Y-m-d') }}">
                              {{ \Carbon\Carbon::parse($d->tradt)->format('d/m/Y') }}
                          </td>
                          <td class="text-center">{{ $d->mcusmas->cusna ?? '-' }}</td>
                          <td class="text-center">{{ $d->cuspo ?? '-' }}</td>
                          <td class="text-center">
                              {{-- preview --}}
                              {{-- <a href="{{ route('delivery_note.preview', $d->dnid) }}" class="badge bg-success" data-tooltip="true" data-bs-placement="top" title="Preview"><i class="bi bi-file-earmark-image-fill"></i></a> --}}

                              {{-- print --}}
                              @if (!$periodClosed && $d->braco == auth()->user()->cabang && $d->resta != 'C')
                                <a href="{{ route('delivery_note.print', $d->dnid) }}" class="badge bg-success" data-tooltip="true" data-bs-placement="top" title="Print"><i class="bi bi-file-earmark-arrow-down"></i></a>
                                
                                <a href="/delivery-note/detail/{{ $d->dnid }}" class="badge bg-primary" data-tooltip="true" data-bs-placement="top" title="Detail"><i class="bi bi-info-circle"></i></a>
                                <a href="/delivery-note/edit/{{ $d->dnid }}" class="badge bg-warning" data-tooltip="true" data-bs-placement="top" title="Edit"><i class="bi bi-pencil"></i></a>
                                <form id="cancel-dn-{{ $d->dnid }}" action="{{ route('delivery_note.cancel', $d->dnid) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('PATCH')

                                    <a class="badge bg-danger btn-cancel-dn"
                                        data-dnid="{{ $d->dnid }}"
                                        data-tooltip="true"
                                        data-bs-placement="top"
                                        title="Cancel"
                                        style="cursor: pointer;">
                                        <i class="bi bi-x-circle"></i>
                                    </a>
                                </form>
                              @endif
                              @if ($d->resta == 'C')
                                <div class="badge bg-danger">CANCELED</div>
                              @endif
                          </td>
                          <td class="text-center" data-order="{{ \Carbon\Carbon::parse($d->created_at)->format('Y-m-d H:i:s') }}">
                              {{ \Carbon\Carbon::parse($d->created_at)->format('d/m/Y H:i:s') }}
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

      $(document).on('click', '.btn-cancel-dn', function(e){
        e.preventDefault();

        let dnid = $(this).data('dnid');

        Swal.fire({
            title: 'Cancel Delivery Note?',
            text: 'Data akan dicancel dan stock sparepart akan dikembalikan.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Ya, Cancel',
            cancelButtonText: 'Batal',
            confirmButtonColor: '#d33',
            cancelButtonColor: '#6c757d'
        }).then((result) => {
            if(result.isConfirmed){
                $('#cancel-dn-' + dnid).submit();
            }
        });
    });
    </script>
@endpush

@endsection