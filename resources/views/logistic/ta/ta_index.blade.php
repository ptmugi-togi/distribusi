@extends('layout.main')

@section('container')
<main id="main" class="main">
    <div class="pagetitle">
        <h1>List TA</h1>
        <nav>
          <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
            <li class="breadcrumb-item active">List TA</li>
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
                    <a id="tambahTa" href="{{ route('ta.create') }}" type="button" class="btn btn-success"> Tambah</a>
                </div>

                <table id="myTable" class="table table-striped nowrap" style="width:100%">
                  <thead>
                    <tr>
                        <th class="text-center">TA No.</th>
                        <th class="text-center">TA Date</th>
                        <th class="text-center">Req by Branch</th>
                        <th class="text-center">RA No.</th>
                        <th class="text-center">Action</th>
                        <th class="text-center">Created At</th>
                    </tr>
                  </thead>
                  <tbody>
                    @foreach ($tahdr as $t)
                    @if ($t->formc === 'TA')
                    <tr>
                        <td class="text-center">TA {{ $t->trano ?? '-' }}</td>
                        <td class="text-center" data-order="{{ \Carbon\Carbon::parse($t->tradt)->format('Y-m-d') }}">
                            {{ \Carbon\Carbon::parse($t->tradt)->format('d/m/Y') }}
                        </td>
                        <td class="">{{ $t->rqbrc ?? '-' }}</td>
                        <td class="text-center">{{ $t->ref01 ?? '-' }}</td>
                        <td class="text-center">
                            {{-- preview --}}
                            {{-- <a href="{{ route('ta.previewTa', $t->bbkid) }}" class="badge bg-success" data-tooltip="true" data-bs-placement="top" title="Preview"><i class="bi bi-file-earmark-image-fill"></i></a> --}}

                            {{-- print --}}
                            {{-- <a href="{{ route('ta.printTa', $t->bbkid) }}" class="badge bg-success" data-tooltip="true" data-bs-placement="top" title="Print"><i class="bi bi-file-earmark-arrow-down"></i></a> --}}
                            
                            <a href="/ta/{{ $t->bbkid }}/detail" class="badge bg-primary" data-tooltip="true" data-bs-placement="top" title="Detail"><i class="bi bi-info-circle"></i></a>
                              <a href="/ta/{{ $t->bbkid }}/edit" class="badge bg-warning" data-tooltip="true" data-bs-placement="top" title="Edit"><i class="bi bi-pencil"></i></a>
                              <form id="delete-ta-{{ $t->bbkid }}" action="{{ url('/ta/'.$t->bbkid.'/delete') }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <a class="badge bg-danger btn-delete-ta" data-bbkid="{{ $t->bbkid }}" data-tooltip="true" data-bs-placement="top" title="Delete" style="cursor: pointer;">
                                      <i class="bi bi-trash"></i>
                                </a>
                              </form>
                        </td>
                        <td class="text-center" data-order="{{ \Carbon\Carbon::parse($t->created_at)->format('Y-m-d H:i:s') }}">
                            {{ \Carbon\Carbon::parse($t->created_at)->format('d/m/Y H:i:s') }}
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
        $('#myTable').DataTable({
          destroy: true,
          order: [[5, 'desc']], // sorting berdasarkan created at
          stateSave: false,
          columnDefs: [
            { targets: [5], visible: false } //ilangin tabel created at, karna hanya untuk sorting saja
          ]
        });
      });
    </script>

    {{-- modal delete data invoice --}}
    <script>
      document.addEventListener('DOMContentLoaded', function () {
          // Event delegation untuk semua tombol hapus
          $(document).on('click', '.btn-delete-ta', function (e) {
              e.preventDefault();

              const bbkid = $(this).data('bbkid');
              const form = document.getElementById(`delete-ta-${bbkid}`);

              Swal.fire({
                  title: 'Hapus TA?',
                  text: `Yakin ingin menghapus data TA "${bbkid}" ini?`,
                  icon: 'warning',
                  showCancelButton: true,
                  confirmButtonText: 'Ya, Hapus!',
                  cancelButtonText: 'Batal',
                  confirmButtonColor: '#d33',
                  cancelButtonColor: '#6c757d'
              }).then((result) => {
                  if (result.isConfirmed) {
                      Swal.fire({
                          title: 'Menghapus...',
                          text: 'Mohon tunggu sebentar.',
                          icon: 'info',
                          allowOutsideClick: false,
                          allowEscapeKey: false,
                          showConfirmButton: false,
                          didOpen: () => {
                              Swal.showLoading();
                              form.submit(); // kirim form DELETE
                          }
                      });
                  }
              });
          });
      });
    </script>
@endpush

@endsection