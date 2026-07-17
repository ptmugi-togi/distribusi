@extends('layout.main')
@section('container')

<main id="main" class="main">
    <div class="pagetitle">
        <h1>PRODUK MASTER</h1>
        <nav>
            <ol class="breadcrumb">
            <li class="breadcrumb-item">MASTER</li>
            <li class="breadcrumb-item active">MPROMAS</li>
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
                    <a id="tambahPromas" href="{{ route('mpromas.create') }}" type="button" class="btn btn-success"> Tambah</a>
                </div>

                <table id="myTable" class="table table-striped nowrap" style="width:100%">
                    <thead>
                          <tr>
                            <th>Kode Produk</th>
                            <th>Nama</th>
                            <th>Type Inventory</th>
                            <th>SGRUP</th>
                            <th>SSGRUP</th>
                            <th>Action</th>
                          </tr>
                        </thead>
                        <tbody>
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
          order: [[0, 'asc']],
          stateSave: false,
          responsive: true,
          processing: true,
            serverSide: true,
            ajax: "{{ route('mpromas.data') }}",
            columns: [
              { data: 'opron', name: 'opron' },
              { data: 'prona', name: 'prona' },
              { data: 'itype', name: 'mitype_tbl.descr_itype' },
              { data: 'sgrup', name: 'msgrup.descr_sgrup' },
              { data: 'ssgrup', name: 'mssgrup.descr_ssgrup' },
              { data: 'action', name: 'action', orderable: false, searchable: false}
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

    {{-- modal delete data promas --}}
    <script>
      document.addEventListener('DOMContentLoaded', function () {
          $(document).on('click', '.btn-delete-promas', function (e) {
              e.preventDefault();

              const opron = $(this).data('opron');
              const prona = $(this).data('prona');

              const form = document.getElementById(`delete-promas-${opron}`);

              Swal.fire({
                  title: 'Hapus Produk?',
                  text: `Yakin ingin menghapus Produk "${opron} - ${prona}"?`,
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
                          }
                      });
                      form.submit();
                  }
              });
          });
      });
    </script>
@endpush
@endsection
