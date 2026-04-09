@extends('layout.main')

@section('container')
<main id="main" class="main">
    <div class="pagetitle">
        <h1>List OC Project (SB)</h1>
        <nav>
          <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
            <li class="breadcrumb-item active">List OC Project (SB)</li>
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
                    <a id="tambahOc" href="{{ route('oc_sb.create') }}" type="button" class="btn btn-success"> Tambah</a>
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
                      @foreach ($ocsbhdr as $o)
                      <tr>
                          <td class="text-center">{{ $o->braco ?? '-' }}</td>
                          <td>SB {{ $o->sorno ?? '-' }}</td>
                          <td class="text-center" data-order="{{ \Carbon\Carbon::parse($o->sordt)->format('Y-m-d') }}">
                              {{ \Carbon\Carbon::parse($o->sordt)->format('d/m/Y') }}
                          </td>
                          <td>{{ $o->mcusmas->cusna ?? '-' }}</td>
                          <td>{{ $o->msreno->srena ?? '-' }}</td>
                          <td class="text-center">
                              @if ($o->resta == 'C')
                                <span class="badge bg-danger">CANCELED</span>
                              @endif
                              {{-- preview --}}
                              {{-- <a href="{{ route('oc.previewOcSb', $o->ocsbid) }}" class="badge bg-success" data-tooltip="true" data-bs-placement="top" title="Preview"><i class="bi bi-file-earmark-image-fill"></i></a> --}}

                              {{-- print --}}
                              @if (!$periodClosed && $o->braco == auth()->user()->cabang)
                                @if ($o->resta != 'C')
                                  <a href="{{ route('oc.printOcSb', $o->ocsbid) }}" class="badge bg-success" data-tooltip="true" data-bs-placement="top" title="Print"><i class="bi bi-file-earmark-arrow-down"></i></a>
                                @endif
                                <a href="/oc-sb/{{ $o->ocsbid }}/detail" class="badge bg-primary" data-tooltip="true" data-bs-placement="top" title="Detail"><i class="bi bi-info-circle"></i></a>
                                
                                @if ($o->resta != 'C')
                                  <a href="/oc-sb/{{ $o->ocsbid }}/edit" class="badge bg-warning" data-tooltip="true" data-bs-placement="top" title="Edit"><i class="bi bi-pencil"></i></a>
                                  <form id="cancel-oc-{{ $o->ocsbid }}" action="{{ route('oc_sb.cancel', $o->ocsbid) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('PUT')
                                    <a class="badge bg-danger btn-cancel-oc" data-ocsbid="{{ $o->ocsbid }}" data-tooltip="true" data-bs-placement="top" title="Cancel" style="cursor: pointer;">
                                          <i class="bi bi-x-circle"></i>
                                    </a>
                                  </form>
                                @endif
                                {{-- <form id="delete-oc-{{ $o->ocsbid }}" action="{{ url('/oc-sb/'.$o->ocsbid.'/delete') }}" method="POST" style="display:inline;">
                                  @csrf
                                  @method('DELETE')
                                  <a class="badge bg-danger btn-delete-oc" data-ocsbid="{{ $o->ocsbid }}" data-tooltip="true" data-bs-placement="top" title="Delete" style="cursor: pointer;">
                                        <i class="bi bi-trash"></i>
                                  </a>
                                </form> --}}
                              @endif
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

{{-- Modal Cancel OC --}}
<div class="modal fade" id="cancelOcModal" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">

      <div class="modal-header">
        <h5 class="modal-title">Cancel OC</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>

      <div class="modal-body">
        <input type="hidden" id="cancel_ocsbid">

        <div class="mb-3">
          <label class="form-label">Tanggal Cancel</label><span class="text-danger">*</span>
          <input type="date" id="cancd" class="form-control">
        </div>

        <div class="mb-3">
          <label class="form-label">Period</label>
          <input type="text" id="cancp" class="form-control" readonly style="background-color:#e9ecef">
        </div>

        <div class="mb-3">
          <label class="form-label">Reason</label><span class="text-danger">*</span>
          <textarea type="text" class="form-control" name="reason" id="reason" maxlength="200">{{ old('reason') }}</textarea>
          <div class="form-text text-danger text-end" style="font-size:0.7rem;">Maksimal 200 karakter</div>
        </div>
      </div>

      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
          Batal
        </button>
        <button type="button" id="btnSubmitCancel" class="btn btn-danger">
          Cancel OC
        </button>
      </div>

    </div>
  </div>
</div>

@push('scripts')
    <script>
      $(function () {
        var table = $('#myTable').DataTable({
          destroy: true,
          order: [[1, 'desc']],
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
    </script>

    <script>
      $(document).on('click', '.btn-cancel-oc', function () {

          let ocsbid = $(this).data('ocsbid');

          $('#cancel_ocsbid').val(ocsbid);
          $('#cancd').val('');
          $('#cancp').val('');
          $('#reason').val('');

          $('#cancelOcModal').modal('show');
      });

      // Auto generate period dari tanggal
      $('#cancd').on('change', function () {
          let date = new Date(this.value);

          if (!isNaN(date)) {
            let year  = date.getFullYear();
            let month = String(date.getMonth() + 1).padStart(2, '0');
            $('#cancp').val(year + month);
          }
      });

      // Submit cancel
      $('#btnSubmitCancel').on('click', function () {

          let ocsbid  = $('#cancel_ocsbid').val();
          let cancd = $('#cancd').val();
          let reason = $('#reason').val();

          if (!cancd || !reason) {
              Swal.fire('Warning', 'Tanggal & Reason wajib diisi!', 'warning');
              return;
          }

          let form = document.getElementById('cancel-oc-' + ocsbid);

          $('<input>').attr({
              type: 'hidden',
              name: 'cancd',
              value: cancd
          }).appendTo(form);

          $('<input>').attr({
              type: 'hidden',
              name: 'reason',
              value: reason
          }).appendTo(form);

          form.submit();
      });
    </script>


    {{-- modal delete data invoice --}}
    <script>
      document.addEventListener('DOMContentLoaded', function () {
          // Event delegation untuk semua tombol hapus
          $(document).on('click', '.btn-delete-oc', function (e) {
              e.preventDefault();

              const ocsbid = $(this).data('ocsbid');
              const form = document.getElementById(`delete-oc-${ocsbid}`);

              Swal.fire({
                  title: 'Hapus OC?',
                  text: `Yakin ingin menghapus data OC "${ocsbid}" ini?`,
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