@extends('layout.main')

@section('container')
<main id="main" class="main">
    <div class="pagetitle">
        <h1>List DP Invoice Release</h1>
        <nav>
          <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
            <li class="breadcrumb-item active">List DP Invoice Release</li>
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
                    <a id="tambahDpInvRel" href="{{ route('dp_inv_rel.create') }}" type="button" class="btn btn-success"> Tambah</a>
                </div>

                <div class="table-responsive">
                  <table id="myTable" class="table table-striped nowrap" style="width:100%">
                    <thead>
                      <tr>
                          <th class="text-center">OC NO.</th>
                          <th class="text-center">OC Date</th>
                          <th class="text-center">CUSTOMER</th>
                          <th class="text-center">DP %</th>
                          <th class="text-center">DP AMOUNT</th>
                          <th class="text-center">Action</th>
                          <th class="text-center">Created At</th>
                      </tr>
                    </thead>
                    <tbody>
                      @foreach ($dp_inv_rel as $d)
                        <tr>
                            <td class="text-center">SA {{ $d->sorno ?? '-' }}</td>
                            <td class="text-center" data-order="{{ \Carbon\Carbon::parse($d->invdt)->format('Y-m-d') }}">
                                {{ \Carbon\Carbon::parse($d->invdt)->format('d/m/Y') }}
                            </td>
                            <td class="text-left">{{ $d->mcusmas->cusna ?? '-' }}</td>
                            <td class="text-center">{{ $d->dpper ?? '-' }}</td>
                            <td class="text-center">{{ number_format($d->dpamt, 0) }}</td>
                        <td class="text-center">
                          {{-- preview --}}
                          {{-- <a href="{{ route('dp_inv_rel.preview', $d->invid) }}" class="badge bg-success" data-tooltip="true" data-bs-placement="top" title="Preview"><i class="bi bi-file-earmark-image-fill"></i></a> --}}
                      
                          @if (!$periodClosed && $d->braco == auth()->user()->cabang)
                            @if ($d->dpist != 'Y')
                                <form id="release-dpinvrel-{{ $d->invid }}" action="{{ route('dp_inv_rel.release', $d->invid) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('PUT')

                                    <a class="badge bg-success btn-release-dpinvrel" data-invid="{{ $d->invid }}" style="cursor: pointer;" data-tooltip="true" data-bs-placement="top" title="Release">
                                        Release
                                    </a>
                                </form>
                            @endif
                            {{-- print --}}
                            @if ($d->resta != 'C')
                            <a href="{{ route('dp_inv_rel.print', $d->invid) }}" class="badge bg-success" data-tooltip="true" data-bs-placement="top" title="Print"><i class="bi bi-file-earmark-arrow-down"></i></a>
                            @endif
                          @endif
                        </td>
                        <td class="text-center" data-order="{{ \Carbon\Carbon::parse($d->created_at)->format('Y-m-d H:i:s') }}">
                            {{ \Carbon\Carbon::parse($d->created_at)->format('d/m/Y H:i:s') }}
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
        $('#myTable').DataTable({
          destroy: true,
          order: [[0, 'desc']], // sorting berdasarkan created at
          stateSave: false,
          columnDefs: [
            { targets: [6], visible: false } //ilangin tabel created at, karna hanya untuk sorting saja
          ]
        });
      });
    </script>

    <script>
      $(document).on('click', '.btn-release-dpinvrel', function () {

        let invid = $(this).data('invid');

        Swal.fire({
            title: 'Release Invoice?',
            text: `Yakin ingin release invoice "${invid}"?`,
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Ya, Release!',
            cancelButtonText: 'Batal',
            confirmButtonColor: '#198754',
            cancelButtonColor: '#6c757d'
        }).then((result) => {

            if (result.isConfirmed) {

                let form = document.getElementById('release-dpinvrel-' + invid);

                Swal.fire({
                    title: 'Processing...',
                    text: 'Sedang melakukan release',
                    icon: 'info',
                    allowOutsideClick: false,
                    showConfirmButton: false,
                    didOpen: () => {
                        Swal.showLoading();
                        form.submit();
                    }
                });
            }
        });
    });
    </script>
@endpush

@endsection