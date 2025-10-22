@extends('layout.main')

@section('container')
<main id="main" class="main">
    <div class="pagetitle">
        <h1>List BL AWB</h1>
        <nav>
          <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
            <li class="breadcrumb-item active">List BL AWB</li>
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
                    <a id="tambahBlawb" href="{{ route('blawb.create') }}" type="button" class="btn btn-success"> Tambah</a>
                </div>

                <table id="myTable" class="table table-striped nowrap" style="width:100%">
                  <thead>
                    <tr>
                      <th class="text-center">Receipt<br> Number</th>
                      <th class="text-center">Calculation <br> Number</th>
                      <th class="text-center">BL/AWB <br> Number</th>
                      <th class="text-center">BL/AWB <br> Date</th>
                      <th class="text-center">Supplier</th>
                      <th class="text-center">PIB</th>
                      <th class="text-center">PIB <br> Date</th>
                      <th class="text-center">Action</th>
                      <th class="text-center">Created At</th>
                    </tr>
                  </thead>
                  <tbody>
                    @foreach ($tbolh as $tbolh)
                    <tr>
                      <td class="text-center">RI {{ $tbolh->rinum }}</td>
                      <td class="text-center">{{ $tbolh->nocal}}</td>
                      <td class="text-center">{{ $tbolh->blnum}}</td>
                      <td class="text-center" data-order="{{ \Carbon\Carbon::parse($tbolh->bldat)->format('Y-m-d') }}">
                        {{ \Carbon\Carbon::parse($tbolh->bldat)->format('d/m/Y') }}
                      </td>
                      <td>{{ $tbolh->vendor->supna ?? '-' }}</td>
                      <td class="text-center">{{ $tbolh->npiud}}</td>
                      <td class="text-center" data-order="{{ \Carbon\Carbon::parse($tbolh->tpiud)->format('Y-m-d') }}">
                        {{ \Carbon\Carbon::parse($tbolh->tpiud)->format('d/m/Y') }}
                      </td>
                      <td class="text-center">
                        <a href="/blawb/{{ $tbolh->rinum }}/detail" class="badge bg-primary" data-tooltip="true" data-bs-placement="top" title="Detail"><i class="bi bi-info-circle"></i></a>
                        <a href="/blawb/{{ $tbolh->rinum }}/edit" class="badge bg-warning" data-tooltip="true" data-bs-placement="top" title="Edit"><i class="bi bi-pencil"></i></a>
                        <form id="delete-blawb-{{ $tbolh->rinum }}" action="/blawb/{{ $tbolh->rinum }}/delete" method="POST" style="display:inline;">
                          @csrf
                          @method('DELETE')
                          <a
                            class="badge bg-danger border-0 btn-delete-blawb" data-rinum="{{ $tbolh->rinum }}" data-tooltip="true" data-bs-placement="top" title="Delete" style="cursor: pointer;">
                            <i class="bi bi-trash"></i>
                          </a>
                        </form>
                      </td>
                      <td class="text-center" data-order="{{ \Carbon\Carbon::parse($tbolh->created_at)->format('Y-m-d H:i:s') }}">
                        {{ \Carbon\Carbon::parse($tbolh->created_at)->format('d/m/Y H:i:s') }}
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
          order: [[8, 'desc']], // sorting berdasarkan created at
          stateSave: false,
          columnDefs: [
            { targets: [8], visible: false } //ilangin tabel created at, karna hanya untuk sorting saja
          ]
        });
      });
    </script>

    {{-- modal delete data blawb --}}
    <script>
      document.addEventListener('DOMContentLoaded', function () {
          $(document).on('click', '.btn-delete-blawb', function (e) {
              e.preventDefault();

              const rinum = $(this).data('rinum');
              const form = document.getElementById(`delete-blawb-${rinum}`);

              Swal.fire({
                  title: 'Hapus Data BL / AWB?',
                  text: `Yakin ingin menghapus data BL / AWB "RI ${rinum}" ini?`,
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
                              form.submit(); 
                          }
                      });
                  }
              });
          });
      });
    </script>
@endpush

@endsection