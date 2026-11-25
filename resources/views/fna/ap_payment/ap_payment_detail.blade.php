@extends('layout.main')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/global.css') }}">
@endpush

@section('container')
    <main id="main" class="main">
        <div class="d-flex justify-content-between align-items-center">
            <div class="pagetitle">
                <h1>Detail AP Payment</h1>
                <nav>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('ap_payment.index') }}">List AP Payment</a></li>
                        <li class="breadcrumb-item active">Detail ({{ $header->apid }})</li>
                    </ol>
                </nav>
            </div>
        </div>

        <section class="section">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4 mt-3">
                            <label class="form-label text-muted">Voucher No</label>
                            <input type="text" class="form-control" value="{{ $header->vcrno }}" disabled>
                        </div>

                        <div class="col-md-4 mt-3">
                            <label class="form-label text-muted">Payment Date</label>
                            <input type="text" class="form-control" value="{{ \Carbon\Carbon::parse($header->sordt)->format('d-m-Y') }}" disabled>
                        </div>

                        <div class="col-md-4 mt-3">
                            <label class="form-label text-muted">Period</label>
                            <input type="text" class="form-control" value="{{ $header->priod ? \Carbon\Carbon::createFromFormat('Ym', trim($header->priod))->format('m-Y') : '-' }}" disabled>
                        </div>

                        <div class="col-md-6 mt-3">
                            <label class="form-label text-muted">Supplier</label>
                            <input type="text" class="form-control"
                                value="{{ trim($header->supno) }}{{ $header->supplier ? ' - ' . trim($header->supplier->supna) : '' }}" disabled>
                        </div>

                        <div class="col-md-6 mt-3">
                            <label class="form-label text-muted">Bank</label>
                            <input type="text" class="form-control"
                                value="{{ trim($header->glacc) }} - {{ trim($header->bankname) }}" disabled>
                        </div>

                        <div class="col-md-3 mt-3">
                            <label class="form-label text-muted">Currency Code Payment</label>
                            <input type="text" class="form-control" value="{{ $header->curco }}" disabled>
                        </div>

                        <div class="col-md-3 mt-3">
                            <label class="form-label text-muted">Currency Rate</label>
                            <input type="text" class="form-control" value="Rp {{ number_format($header->payrate, 2, ',', '.') }}" disabled>
                        </div>

                        <div class="col-md-3 mt-3">
                            <label class="form-label text-muted">Total Payment</label>
                            <input type="text" class="form-control" value="Rp {{ number_format($header->tpayment, 2, ',', '.') }}" disabled>
                        </div>

                        <div class="col-md-3 mt-3">
                            <label class="form-label text-muted">Total Payment Detail</label>
                            <input type="text" class="form-control" value="Rp {{ number_format($header->tpayment_detail, 2, ',', '.') }}" disabled>
                        </div>

                        <div class="col-md-12 mt-3">
                            <label class="form-label text-muted">Notes</label>
                            <textarea class="form-control" rows="2" disabled>{{ $header->noteh ?? '-' }}</textarea>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card mt-3">
                <div class="card-body pt-4">
                    <h5 class="card-title mb-3">Rincian Invoice Dibayar</h5>

                    <div class="table-responsive">
                        <table class="table table-bordered table-striped align-middle">
                            <thead class="table-light text-center">
                                <tr>
                                    <th style="width: 50px;">No</th>
                                    <th>No. Invoice</th>
                                    <th class="text-end">Inv Rate</th>
                                    <th class="text-end">Pay Rate</th>
                                    <th class="text-end">Pay Value</th>
                                    <th class="text-center">Adjust</th>
                                    <th class="text-end">Total IDR</th>
                                    <th>Notes</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($header->details as $index => $dtl)
                                    <tr>
                                        <td class="text-center">{{ $index + 1 }}</td>
                                        <td class="fw-bold">{{ $dtl->invno }}</td>
                                        <td class="text-end">
                                            {{ $dtl->invrate ? 'Rp ' . number_format($dtl->invrate, 2, ',', '.') : '-' }}
                                        </td>
                                        <td class="text-end">
                                            {{ $dtl->payrate ? 'Rp ' . number_format($dtl->payrate, 2, ',', '.') : '-' }}
                                        </td>
                                        <td class="text-end fw-semibold">
                                            {{ number_format($dtl->payval, 2, ',', '.') }}
                                        </td>
                                        <td class="text-center">{{ $dtl->adjust ?? '-' }}</td>
                                        <td class="text-end fw-bold">
                                            Rp {{ number_format($dtl->totalidr, 2, ',', '.') }}
                                        </td>
                                        <td>{{ $dtl->noted ?? '-' }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center text-muted py-3">Tidak ada data rincian invoice.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                            <tfoot class="table-light">
                                <tr>
                                    <th colspan="6" class="text-end">Grand Total Payment (IDR) :</th>
                                    <th class="text-end fs-6">
                                        Rp {{ number_format($header->details->sum('totalidr'), 2, ',', '.') }}
                                    </th>
                                    <th></th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>

                    <div class="mt-4 d-flex justify-content-between">
                        <a href="{{ route('ap_payment.index') }}" class="btn btn-secondary">
                            Kembali
                        </a>
                    </div>
                </div>
            </div>
        </section>
    </main>
@endsection