@extends('layout.main')

@section('container')
<main id="main" class="main">
    <div class="pagetitle">
        <h1>List OC Retail (SA)</h1>
        <nav>
          <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
            <li class="breadcrumb-item active">List OC Retail (SA)</li>
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
                    <a id="tambahOc" href="{{ route('oc.create') }}" type="button" class="btn btn-success"> Tambah</a>
                </div>

                <div class="table-responsive">
                  <table id="myTable" class="table table-striped nowrap" style="width:100%">
                    <thead>
                      <tr>
                          <th class="text-center">Cabang</th>
                          <th class="text-center">No. OC</th>
                          <th class="text-center">OC Date</th>
                          <th class="text-center">Customer</th>
                          <th class="text-center">Sales</th>
                          <th class="text-center">Action</th>
                          <th class="text-center">Created At</th>
                      </tr>
                    </thead>
                    <tbody>
                      @foreach ($ochdr as $o)
                      <tr>
                          <td class="text-center">{{ $o->braco ?? '-' }}</td>
                          <td class="text-center">{{ $o->sorno ?? '-' }}</td>
                          <td class="text-center" data-order="{{ \Carbon\Carbon::parse($o->sordt)->format('Y-m-d') }}">
                              {{ \Carbon\Carbon::parse($o->sordt)->format('d/m/Y') }}
                          </td>
                          <td class="text-center">{{ $o->mcusmas->cusna ?? '-' }}</td>
                          <td class="text-center">{{ $o->msreno->srena ?? '-' }}</td>
                          <td class="text-center">
                              {{-- preview --}}
                              <a href="{{ route('oc.previewOc', $o->ocid) }}" class="badge bg-success" data-tooltip="true" data-bs-placement="top" title="Preview"><i class="bi bi-file-earmark-image-fill"></i></a>

                              {{-- print --}}
                              {{-- @if (!$periodClosed && $o->braco == auth()->user()->cabang) --}}
                                {{-- <a href="{{ route('oc.printOc', $o->ocid) }}" class="badge bg-success" data-tooltip="true" data-bs-placement="top" title="Print"><i class="bi bi-file-earmark-arrow-down"></i></a> --}}
                                
                                <a href="/oc/{{ $o->ocid }}/detail" class="badge bg-primary" data-tooltip="true" data-bs-placement="top" title="Detail"><i class="bi bi-info-circle"></i></a>
                                <a href="/oc/{{ $o->ocid }}/edit" class="badge bg-warning" data-tooltip="true" data-bs-placement="top" title="Edit"><i class="bi bi-pencil"></i></a>
                                <form id="delete-inv-{{ $o->ocid }}" action="{{ url('/oc/'.$o->ocid.'/delete') }}" method="POST" style="display:inline;">
                                  @csrf
                                  @method('DELETE')
                                  <a class="badge bg-danger btn-delete-inv" data-ocid="{{ $o->ocid }}" data-tooltip="true" data-bs-placement="top" title="Delete" style="cursor: pointer;">
                                        <i class="bi bi-trash"></i>
                                  </a>
                                </form>
                              {{-- @endif --}}
                          </td>
                          <td class="text-center" data-order="{{ \Carbon\Carbon::parse($o->created_at)->format('Y-m-d H:i:s') }}">
                              {{ \Carbon\Carbon::parse($o->created_at)->format('d/m/Y H:i:s') }}
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
          order: [[6, 'desc']], // sorting berdasarkan created at
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

              const ocid = $(this).data('ocid');
              const form = document.getElementById(`delete-inv-${ocid}`);

              Swal.fire({
                  title: 'Hapus BPB?',
                  text: `Yakin ingin menghapus data BPB "${ocid}" ini?`,
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