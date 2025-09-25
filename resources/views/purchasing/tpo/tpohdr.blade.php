@extends('layout.main')

@section('container')
  <main id="main" class="main">
    <div class="pagetitle">
        <h1>List PO</h1>
        <nav>
          <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
            <li class="breadcrumb-item active">List PO</li>
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
                    <a id="tambahTpo" href="{{ route('tpo.create') }}" type="button" class="btn btn-success"> Tambah</a>
                </div>

                <table id="myTable" class="table table-striped nowrap" style="width:100%">
                  <thead>
                    <tr>
                      <th class="text-center">PO NO</th>
                      <th class="text-center">Tipe PO</th>
                      <th class="text-center">Nama Supplier</th>
                      <th class="text-center">Tanggal PO</th>
                      <th class="text-center">PO PDF</th>
                      <th class="text-center">Action</th>
                    </tr>
                  </thead>
                  <tbody>
                    @foreach ($tpohdr as $tpo)
                    <tr>
                      <td class="text-center">{{ $tpo->pono }}</td>
                      <td class="text-center">{{ $tpo->potype ?? '-' }}</td>
                      <td>{{ $tpo->vendor->supna ?? '-' }}</td>
                      <td class="text-center" data-order="{{ \Carbon\Carbon::parse($tpo->podat)->format('Y-m-d') }}">
                        {{ \Carbon\Carbon::parse($tpo->podat)->format('d/m/Y') }}
                      </td>
                      <td class="text-center">
                        <a href="{{ route('pdf.preview', $tpo->pono) }}" class="btn btn-primary btn-sm m-1" target="_blank"><i class="bi bi-file-earmark-pdf"></i> Preview</a>
                        <a href="{{ route('pdf.print', $tpo->pono) }}" class="btn btn-success btn-sm m-1"><i class="bi bi-file-earmark-arrow-down"></i> Print</a>
                      <td class="text-center">
                        <a href="/tpo/{{ $tpo->pono }}/detail" class="badge bg-primary p-auto"><i class="bi bi-info-circle"></i></a>
                        <a href="/tpo/{{ $tpo->pono }}/edit" class="badge bg-warning p-auto"><i class="bi bi-pencil"></i></a>
                        @if($tpo->tpodtl->every(fn($d) => $d->rcqty == 0 && $d->inqty == 0))
                            <a href="#" class="badge bg-danger" 
                              data-bs-toggle="modal" 
                              data-bs-target="#modalDeleteTpo-{{ $tpo->pono }}">
                              <i class="bi bi-trash"></i>
                            </a>
                        @endif

                        <div class="modal fade" id="modalDeleteTpo-{{ $tpo->pono }}" tabindex="-1">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Hapus TPO?</h5>
                                    </div>
                                    <div class="modal-body">
                                        <p>Yakin ingin menghapus data PO "{{ $tpo->pono }}" ini?</p>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                        <form action="{{ url('/tpo/'.$tpo->pono.'/delete') }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger">Hapus</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
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
              order: [[3, 'desc']],
              stateSave: false,
            });
          });
        </script>
    @endpush

@endsection

