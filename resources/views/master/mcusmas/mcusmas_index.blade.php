@extends('layout.main')

@section('container')
<main id="main" class="main">
    <div class="pagetitle">
        <h1>List Customer</h1>
        <nav>
          <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
            <li class="breadcrumb-item active">List Customer</li>
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
                <div class="col-lg-12" style="padding:0px 10px 10px 0px;">
                    <a id="tambahCusmas" href="{{ route('cusmas.create') }}" type="button" class="btn btn-success"> Tambah</a>
                </div>

                <table id="myTable" class="table table-striped nowrap" style="width:100%">
                    <thead>
                      <tr>
                          <th class="text-center">Kode Cust</th>
                          <th class="text-center">Title</th>
                          <th class="text-center">Customer Name</th>
                          <th class="text-center">NPWP</th>
                          <th class="text-center">Telp</th>
                          <th class="text-center">Action</th>
                          
                      </tr>
                    </thead>
                    <tbody>
                      @foreach ($cusmas as $c)
                      <tr>
                          <td>{{ $c->cusno ?? '-' }}</td>
                          <td>{{ $c->title ?? '-' }}</td>
                          <td>{{ $c->cusna ?? '-' }}</td>
                          <td class="text-center">{{ $c->taxrn ?? '-' }}</td>
                          <td>{{ $c->offph ?? '-' }}</td>
                          <td class="text-center">
                            <a href="/cust/{{ $c->cusno }}/detail" class="badge bg-primary" data-tooltip="true" data-bs-placement="top" title="Detail"><i class="bi bi-info-circle"></i></a>
                            <a href="/cust/{{ $c->cusno }}/edit" class="badge bg-warning" data-tooltip="true" data-bs-placement="top" title="Edit"><i class="bi bi-pencil"></i></a>
                            <form id="delete-cust-{{ $c->cusno }}" action="{{ url('/cust/'.$c->cusno.'/delete') }}" method="POST" style="display:inline;">
                              @csrf
                              @method('DELETE')
                              <a class="badge bg-danger btn-delete-cust" data-cusno="{{ $c->cusno }}" data-tooltip="true" data-bs-placement="top" title="Delete" style="cursor: pointer;">
                                    <i class="bi bi-trash"></i>
                              </a>
                            </form>
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
        var table = $('#myTable').DataTable({
          destroy: true,
          order: [[0, 'desc']],
          stateSave: false,
          responsive: true,
          columnDefs: [
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

    {{-- modal delete data invoice --}}
    <script>
      document.addEventListener('DOMContentLoaded', function () {
          // Event delegation untuk semua tombol hapus
          $(document).on('click', '.btn-delete-cust', function (e) {
              e.preventDefault();

              const cusno = $(this).data('cusno');
              const form = document.getElementById(`delete-cust-${cusno}`);

              Swal.fire({
                  title: 'Hapus Customer?',
                  text: `Yakin ingin menghapus data Customer "${cusno}" ini?`,
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