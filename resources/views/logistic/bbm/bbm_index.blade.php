@extends('layout.main')

@section('container')
<main id="main" class="main">
    <div class="pagetitle">
        <h1>List BBM</h1>
        <nav>
          <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
            <li class="breadcrumb-item active">List BBM</li>
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
                    <a id="tambahBbm" href="{{ route('bbm.create') }}" type="button" class="btn btn-success"> Tambah</a>
                </div>

                <table id="myTable" class="table table-striped nowrap" style="width:100%">
                  <thead>
                    <tr>
                        <th class="text-center">Warco</th>
                        <th class="text-center">Form Code</th>
                        <th class="text-center">No BBM</th>
                        <th class="text-center">Receipt Date</th>
                        <th class="text-center">Reference</th>
                        <th class="text-center">Action</th>
                        <th class="text-center">Created At</th>
                    </tr>
                  </thead>
                  <tbody>
                    @foreach ($bbmhdr as $b)
                    <tr>
                        <td class="text-center">{{ $b->warco ?? '-' }}</td>
                        <td class="text-center">{{ $b->formc}}</td>
                        <td class="text-center">{{ substr($b->bbmid, -6) }}</td>
                        <td class="text-center" data-order="{{ \Carbon\Carbon::parse($b->tradt)->format('Y-m-d') }}">
                            {{ \Carbon\Carbon::parse($b->tradt)->format('d/m/Y') }}
                        </td>
                        <td class="text-center">{{ $b->reffc }} {{ $b->refno }}</td>
                        <td class="text-center">
                            <a href="/bbm/{{ $b->bbmid }}/detail" class="badge bg-primary" data-tooltip="true" data-bs-placement="top" title="Detail"><i class="bi bi-info-circle"></i></a>
                            <a href="/bbm/{{ $b->bbmid }}/edit" class="badge bg-warning" data-tooltip="true" data-bs-placement="top" title="Edit"><i class="bi bi-pencil"></i></a>
                            <form id="delete-inv-{{ $b->bbmid }}" action="{{ url('/bbm/'.$b->bbmid.'/delete') }}" method="POST" style="display:inline;">
                              @csrf
                              @method('DELETE')
                              <a class="badge bg-danger btn-delete-inv" data-bbmid="{{ $b->bbmid }}" data-tooltip="true" data-bs-placement="top" title="Delete" style="cursor: pointer;">
                                    <i class="bi bi-trash"></i>
                              </a>
                            </form>
                        </td>
                        <td class="text-center" data-order="{{ \Carbon\Carbon::parse($b->created_at)->format('Y-m-d H:i:s') }}">
                            {{ \Carbon\Carbon::parse($b->created_at)->format('d/m/Y H:i:s') }}
                        </td>
                    </tr>
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
          order: [[3, 'desc']], // sorting berdasarkan created at
          stateSave: false,
          columnDefs: [
            { targets: [6], visible: false } //ilangin tabel created at, karna hanya untuk sorting saja
          ]
        });
      });
    </script>

    {{-- modal delete data invoice --}}
    <script>
      document.addEventListener('DOMContentLoaded', function () {
          // Event delegation untuk semua tombol hapus
          $(document).on('click', '.btn-delete-inv', function (e) {
              e.preventDefault();

              const bbmid = $(this).data('bbmid');
              const form = document.getElementById(`delete-inv-${bbmid}`);

              Swal.fire({
                  title: 'Hapus BBM?',
                  text: `Yakin ingin menghapus data BBM "${bbmid}" ini?`,
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