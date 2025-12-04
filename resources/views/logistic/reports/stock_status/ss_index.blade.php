@extends('layout.main')

@section('container')
<style>
    .clickable-row {
        cursor: pointer;
    }

    .clickable-row:hover {
        background-color: #e6f7ff !important;
    }
</style>
<main id="main" class="main">
    <div class="pagetitle">
        <h1>Stock Status</h1>
        <nav>
          <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
            <li class="breadcrumb-item active">Stock Status</li>
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

                <table id="myTable" class="table table-striped nowrap" style="width:100%">
                  <thead>
                    <tr>
                        <th class="text-center">Cabang</th>
                        <th class="text-center">WH</th>
                        <th class="text-center">Location</th>
                        <th class="text-center">Kode Stok</th>
                        <th class="text-center">Nama Barang</th>
                        <th class="text-center">Jumlah Stok</th>
                    </tr>
                  </thead>
                  <tbody>
                    @foreach ($ss as $s)
                      <tr class="clickable-row" data-opron="{{ $s->opron }}">
                          <td class="text-center">{{ $s->braco ?? '-' }}</td>
                          <td class="">{{ $s->warco ?? '-' }}</td>
                          <td class="">{{ $s->locco ?? '-' }}</td>
                          <td class="text-center">{{ $s->opron ?? '-' }}</td>
                          <td class="">{{ $s->prona ?? '-' }}</td>
                          <td class="text-center">{{ $s->total_stock ?? '-' }}</td>
                      </tr>
                    @endforeach
                  </tbody>
                </table>
                
                <!-- Modal Detail Lot -->
                <div class="modal fade" id="lotModal" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Detail Lot</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">

                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Lot No</th>
                                    <th>Total Qty</th>
                                </tr>
                            </thead>
                            <tbody id="lotTableBody">
                            </tbody>
                        </table>

                    </div>
                    </div>
                </div>
                </div>

              </div>
            </div>
          </div>
        </div>
    </section>
</main>

@push('scripts')
    <script>
        $(document).ready(function() {

            $(document).on('click', '.clickable-row', function() {
                let opron = $(this).data('opron');

                $("#lotTableBody").html(`<tr><td colspan="2" class="text-center">Loading...</td></tr>`);

                $.ajax({
                    url: `/stock-status/lot/${opron}`,
                    method: "GET",
                    success: function(data) {
                        let rows = "";

                        if (data.length === 0) {
                            rows = `<tr><td colspan="2" class="text-center">Tidak ada data lot</td></tr>`;
                        } else {
                            data.forEach(item => {
                                if (item.toqoh !== 0) {
                                    rows += `
                                        <tr>
                                            <td>${item.lotno}</td>
                                            <td>${item.toqoh}</td>
                                        </tr>
                                    `;
                                }
                            });
                        }

                        $("#lotTableBody").html(rows);

                        var modal = new bootstrap.Modal(document.getElementById('lotModal'));
                        modal.show();
                    }
                });
            });

        });
    </script>
@endpush

@endsection