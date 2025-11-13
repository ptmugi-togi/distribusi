@extends('layout.main')

@section('container')
<main id="main" class="main">
    <div class="pagetitle">
        <h1>List BBK</h1>
        <nav>
          <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
            <li class="breadcrumb-item active">List BBK</li>
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
                    <a id="tambahBbk" href="{{ route('bbk.create') }}" type="button" class="btn btn-success"> Tambah</a>
                </div>

                <table id="myTable" class="table table-striped nowrap" style="width:100%">
                  <thead>
                    <tr>
                        <th class="text-center">WH</th>
                        <th class="text-center">BBK</th>
                        <th class="text-center">No BBK</th>
                        <th class="text-center">Receipt Date</th>
                        <th class="text-center">Reference</th>
                        <th class="text-center">Action</th>
                        <th class="text-center">Braco</th>
                        <th class="text-center">Created At</th>
                    </tr>
                  </thead>
                  <tbody>
                    @foreach ($bbkhdr as $b)
                    <tr>
                        <td class="text-center">{{ $b->warco ?? '-' }}</td>
                        <td class="">({{ $b->mformcode->desc_c ?? '-' }})</td>
                        <td class="text-center">{{ $b->formc }} {{ substr($b->bbkid, -6) }}</td>
                        <td class="text-center" data-order="{{ \Carbon\Carbon::parse($b->tradt)->format('Y-m-d') }}">
                            {{ \Carbon\Carbon::parse($b->tradt)->format('d/m/Y') }}
                        </td>
                        <td class="">{{ $b->rfc01 }} {{ $b->ref01 ?? '-' }}</td>
                        <td class="text-center">
                            {{-- preview --}}
                            {{-- <a href="{{ route('bbk.previewbbk', $b->bbkid) }}" class="badge bg-success" data-tooltip="true" data-bs-placement="top" title="Preview"><i class="bi bi-file-earmark-image-fill"></i></a> --}}

                            {{-- print --}}
                            @if (!$periodClosed && $b->braco == auth()->user()->cabang)
                            {{-- <a href="{{ route('bbk.printbbk', $b->bbkid) }}" class="badge bg-success" data-tooltip="true" data-bs-placement="top" title="Print"><i class="bi bi-file-earmark-arrow-down"></i></a> --}}
                            
                            <a href="/bbk/{{ $b->bbkid }}/detail" class="badge bg-primary" data-tooltip="true" data-bs-placement="top" title="Detail"><i class="bi bi-info-circle"></i></a>
                              <a href="/bbk/{{ $b->bbkid }}/edit" class="badge bg-warning" data-tooltip="true" data-bs-placement="top" title="Edit"><i class="bi bi-pencil"></i></a>
                              <form id="delete-inv-{{ $b->bbkid }}" action="{{ url('/bbk/'.$b->bbkid.'/delete') }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <a class="badge bg-danger btn-delete-inv" data-bbkid="{{ $b->bbkid }}" data-tooltip="true" data-bs-placement="top" title="Delete" style="cursor: pointer;">
                                      <i class="bi bi-trash"></i>
                                </a>
                              </form>
                            @endif
                        </td>
                        <td class="text-center">{{ $b->braco ?? '-' }}</td>
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
            { targets: [6, 7], visible: false } //ilangin tabel created at, karna hanya untuk sorting saja
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

              const bbkid = $(this).data('bbkid');
              const form = document.getElementById(`delete-inv-${bbkid}`);

              Swal.fire({
                  title: 'Hapus BBK?',
                  text: `Yakin ingin menghapus data BBK "${bbkid}" ini?`,
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