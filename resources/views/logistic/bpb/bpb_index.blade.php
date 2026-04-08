@extends('layout.main')

@section('container')
<main id="main" class="main">
    <div class="pagetitle">
        <h1>List BPB</h1>
        <nav>
          <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
            <li class="breadcrumb-item active">List BPB</li>
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
                    <a id="tambahBpb" href="{{ route('bpb.create') }}" type="button" class="btn btn-success"> Tambah</a>
                </div>

                <div class="table-responsive">
                  <table id="myTable" class="table table-striped nowrap" style="width:100%">
                    <thead>
                      <tr>
                          <th class="text-center">Branch</th>
                          <th class="text-center">Request No</th>
                          <th class="text-center">Request Date</th>
                          <th class="text-center">sorfc+sorno</th> {{-- nanti ganti yaa --}}
                          <th class="text-center">Request For</th>
                          <th class="text-center">Delivery / WH</th>
                          <th class="text-center">Action</th>
                          <th class="text-center">Created At</th>
                      </tr>
                    </thead>
                    <tbody>
                      @foreach ($bpbhdr as $b)
                      <tr>
                          <td class="text-center">{{ $b->braco ?? '-' }}</td>
                          <td class="">{{ $b->reqno ?? '-' }}</td>
                          <td class="text-center" data-order="{{ \Carbon\Carbon::parse($b->tradt)->format('Y-m-d') }}">
                              {{ \Carbon\Carbon::parse($b->tradt)->format('d/m/Y') }}
                          </td>
                          <td class="">{{ $b->sorfc }} {{ $b->sorno}}</td>
                          <td class="text-center">{{ $b->rqfor ?? '-' }}</td>
                          <td class="text-center">{{ $b->delco ?? '-' }}</td>
                          <td class="text-center">
                              {{-- preview --}}
                              {{-- <a href="{{ route('bpb.previewBpb', $b->bpbid) }}" class="badge bg-success" data-tooltip="true" data-bs-placement="top" title="Preview"><i class="bi bi-file-earmark-image-fill"></i></a> --}}
  
                              {{-- print --}}
                              @if (!$periodClosed && $b->braco == auth()->user()->cabang)
                                <a href="{{ route('bpb.printBpb', $b->bpbid) }}" class="badge bg-success" data-tooltip="true" data-bs-placement="top" title="Print"><i class="bi bi-file-earmark-arrow-down"></i></a>
                                
                                <a href="/bpb/{{ $b->bpbid }}/detail" class="badge bg-primary" data-tooltip="true" data-bs-placement="top" title="Detail"><i class="bi bi-info-circle"></i></a>
                                <a href="/bpb/{{ $b->bpbid }}/edit" class="badge bg-warning" data-tooltip="true" data-bs-placement="top" title="Edit"><i class="bi bi-pencil"></i></a>
                                <form id="delete-inv-{{ $b->bpbid }}" action="{{ url('/bpb/'.$b->bpbid.'/delete') }}" method="POST" style="display:inline;">
                                  @csrf
                                  @method('DELETE')
                                  <a class="badge bg-danger btn-delete-inv" data-bpbid="{{ $b->bpbid }}" data-tooltip="true" data-bs-placement="top" title="Delete" style="cursor: pointer;">
                                        <i class="bi bi-trash"></i>
                                  </a>
                                </form>
                              @endif
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
        </div>
    </section>
</main>

@push('scripts')
    <script>
      $(function () {
        $('#myTable').DataTable({
          destroy: true,
          order: [[7, 'desc']], // sorting berdasarkan created at
          stateSave: false,
          responsive: true,
          columnDefs: [
            { targets: [7], visible: false } //ilangin tabel created at, karna hanya untuk sorting saja
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

              const bpbid = $(this).data('bpbid');
              const form = document.getElementById(`delete-inv-${bpbid}`);

              Swal.fire({
                  title: 'Hapus BPB?',
                  text: `Yakin ingin menghapus data BPB "${bpbid}" ini?`,
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