@extends('layout.main')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/global.css') }}">
@endpush

@section('container')
<main id="main" class="main">
    <div class="d-flex justify-content-between align-items-center">
        <div class="pagetitle">
            <h1>Detail OC Project (SB) ({{ $ocsb->ocsbid }})</h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('oc_sb.index') }}">List OC Project (SB)</a></li>
                    <li class="breadcrumb-item active">Detail OC Project (SB)</li>
                </ol>
            </nav>
        </div>
    </div>

    <section class="section">
        {{-- Header --}}
        <div class="card p-3 shadow-sm">

            <div class="row">
                <div class="col-md-4 mt-3">
                    <label for="sorno" class="form-label">OC No.</label>
                    <input type="text" class="form-control" id="sorno" value="{{ $ocsb->sorno }}" disabled>
                </div>

                <div class="col-md-4 mt-3">
                    <label for="depo" class="form-label">OC Date</label>
                    <input type="text" class="form-control" id="sordt" value="{{ \Carbon\Carbon::parse($ocsb->sordt)->format('d/m/Y') }}" disabled>
                </div>

                <div class="col-md-4 mt-3">
                    <label for="depo" class="form-label">Depo</label>
                    <input type="text" class="form-control" id="depo" value="{{ $ocsb->depo }} - {{ $ocsb->mdepo->name }}" disabled>
                </div>

                <div class="col-md-6 mt-3">
                    <label for="cusno" class="form-label">Customer</label>
                    <input type="text" class="form-control" id="cusno" value="{{ $ocsb->cusno }} - {{ $ocsb->mcusmas->cusna }}" disabled>
                </div>

                <div class="col-md-6 mt-3">
                    <label for="sreno" class="form-label">Sales Rep</label>
                    <input type="text" class="form-control" id="sreno" value="{{ $ocsb->msreno->srena }}" disabled>
                </div>

                <div class="col-md-4 mt-3">
                    <label for="pcuto" class="form-label">Payment Cut Off</label>
                    <input type="text" class="form-control" id="pcuto" value="{{ \Carbon\Carbon::parse($ocsb->pcuto)->format('d/m/Y') }}" disabled>
                </div>

                <div class="col-md-8 mt-3">
                    <label for="cuspo" class="form-label">Customer PO</label>
                    <input type="text" class="form-control" id="cuspo" value="{{ $ocsb->cuspo }}" disabled>
                </div>

                <div class="col-md-6 mt-3">
                    <label for="curco" class="form-label">Currency Code</label>
                    <input type="text" class="form-control" id="curco" value="{{ $ocsb->curco }}" disabled>
                </div>

                <div class="col-md-6 mt-3">
                    <label for="crate" class="form-label">Currency Rate</label>
                    <input type="text" class="form-control price-display" id="crate" value="{{ $ocsb->crate }}" disabled>
                </div>

                <div class="col-md-4 mt-3">
                    <label for="gross" class="form-label">Gross Value</label>
                    <input type="text" class="form-control price-display" id="gross" value="{{ $ocsb->gross }}" disabled>
                </div>

                <div class="col-md-4 mt-3">
                    <label for="odisa" class="form-label">Official Discount</label>
                    <input type="text" class="form-control price-display" id="odisa" value="{{ $ocsb->odisa ?? '-' }}" disabled>
                </div>

                <div class="col-md-4 mt-3">
                    <label for="insfe" class="form-label">Installation</label>
                    <input type="text" class="form-control price-display" id="insfe" value="{{ $ocsb->insfe ?? '-' }}" disabled>
                </div>

                <div class="col-md-6 mt-3">
                    <label for="vtamt" class="form-label">VAT {{ $ocsb->vatax }}%</label>
                    <input type="text" class="form-control price-display" id="vtamt" value="{{ $ocsb->vtamt ?? '-' }}" disabled>
                </div>

                <div class="col-md-6 mt-3">
                    <label for="billv" class="form-label">Billing Amount</label>
                    <input type="text" class="form-control price-display" id="billv" value="{{ $ocsb->billv ?? '-' }}" disabled>
                </div>

                <div class="col-md-6 mt-3">
                    <label for="nodeb" class="form-label">Disposisi EB#</label>
                    <input type="text" class="form-control" id="nodeb" value="{{ $ocsb->nodeb }}" disabled>
                </div>

                <div class="col-md-6 mt-3">
                    <label for="edisa" class="form-label">EB</label>
                    <input type="text" class="form-control price-display" id="edisa" value="{{ $ocsb->edisa ?? '-' }}" disabled>
                </div>

                <div class="col-md-6 mt-3">
                    <label for="cuspo" class="form-label">Customer PO</label>
                    <input type="text" class="form-control" id="cuspo" value="{{ $ocsb->cuspo ?? '-' }}" disabled>
                </div>

                @if ($ocsb->sqper != null)
                    <hr class="my-4">

                    <div class="col-md-4 mt-3">
                        <label for="sqper" class="form-label">Split Quota (%)</label>
                        <input type="text" class="form-control" id="sqper" value="{{ $ocsb->sqper ?? '-' }}" disabled>
                    </div>

                    <div class="col-md-4 mt-3">
                        <label for="sqtbr" class="form-label">To Branch</label>
                        <input type="text" class="form-control" id="sqtbr" value="{{ $ocsb->sqtbr ?? '-' }}" disabled>
                    </div>

                    <div class="col-md-4 mt-3">
                        <label for="sqtsr" class="form-label">Sales Rep.</label>
                        <input type="text" class="form-control" id="sqtsr" value="{{ $ocsb->sqtsr ?? '-' }}" disabled>
                    </div>
                @endif

                <div class="col-md-12 mt-3"> 
                    <label for="noteh" class="form-label">Notes</label>
                    <textarea type="text" class="form-control" name="noteh" id="noteh" cols="30" rows="3" disabled>{{ $ocsb->noteh }}</textarea>
                </div>
            </div>
        </div>

        {{-- Detail Installation --}}
        <div class="row mt-4">
            <h3>OC Detail Installation</h3>
            <div class="accordion" id="accordionOc">
                @foreach ($ocsb->ocsbdtls as $i => $detail)
                <div class="accordion-item">
                    <h2 class="accordion-header" id="heading-{{ $i }}">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                            data-bs-target="#collapse-{{ $i }}" aria-expanded="false">
                                Product: {{ $detail->opron }} - {{ $detail->mpromas->prona }}
                        </button>
                    </h2>
                    <div id="collapse-{{ $i }}" class="accordion-collapse collapse"
                        aria-labelledby="heading-{{ $i }}">
                        <div class="accordion-body">
                            <div class="row">
                                <div class="col-md-6 mt-3">
                                    <label class="form-label">Product</label>
                                    <input type="text" class="form-control" value="{{ $detail->opron }} - {{ $detail->mpromas->prona }}" disabled>
                                </div>

                                @php
                                    $bom = $bomList[$detail->opron] ?? collect();
                                @endphp

                                <div class="col-md-6 mt-2">
                                    @if($bom->isNotEmpty())
                                        <button type="button"
                                            class="btn btn-sm btn-primary"
                                            style="margin-top: 38px"
                                            data-bs-toggle="modal"
                                            data-bs-target="#bomModal{{ $i }}">
                                            Lihat Consist of Goods
                                        </button>
                                        @else
                                        <button type="button"
                                            class="btn btn-sm btn-secondary" style="margin-top: 38px">
                                            Tidak ada Consist of Goods
                                        </button>
                                    @endif
                                </div>

                                @if($bom->isNotEmpty())
                                    <div class="modal fade" id="bomModal{{ $i }}">
                                        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg">
                                            <div class="modal-content">
                                                <div class="modal-header bg-info text-white">
                                                    <h5 class="modal-title">Consist of Goods</h5>
                                                    <button class="btn-close" data-bs-dismiss="modal"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <table class="table table-bordered">
                                                        <thead>
                                                            <tr>
                                                                <th>Product#</th>
                                                                <th>Qty</th>
                                                                <th>Unit</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @foreach($bom as $b)
                                                                <tr>
                                                                    <td>{{ $b->opron }} - {{ $b->prona }}</td>
                                                                    <td>{{ $b->trqty }}</td>
                                                                    <td>{{ $b->stdqu }}</td>
                                                                </tr>
                                                            @endforeach
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endif

                                <div class="col-md-6 mt-3">
                                    <label class="form-label">Order Quantity</label>
                                    <div class="input-group">
                                        <input type="text" class="form-control" value="{{ $detail->qtyor }}" disabled>
                                        <span class="input-group-text">{{ $detail->stdqu }}</span>
                                    </div>
                                </div>

                                <div class="col-md-6 mt-3">
                                    <label for="price" class="form-label">Price / Unit</label>
                                    <input type="text" class="form-control price-display" name="price" id="price" value="{{ $detail->price }}" disabled>
                                </div>

                                <div class="col-md-6 mt-3">
                                    <label for="plist" class="form-label">Price List / Unit</label>
                                    <input type="text" class="form-control price-display" name="plist" id="plist" value="{{ $detail->plist }}" disabled>
                                </div>

                                <div class="col-md-6 mt-3">
                                    <label for="odisp" class="form-label">Total Official Discount</label>
                                    <input type="text" class="form-control price-display" name="odisp" id="odisp" value="{{ $detail->odisp ?? '-' }}" disabled>
                                </div>

                                <div class="col-md-6 mt-3">
                                    <label for="teknik" class="form-label">Jasa Teknik (Unit)</label>
                                    <input type="text" class="form-control price-display" name="teknik" id="teknik" value="{{ $detail->teknik }}" disabled>
                                </div>

                                <div class="col-md-6 mt-3">
                                    <label for="putama" class="form-label">Klasifikasi Produk</label>
                                    <input type="text" class="form-control price-display" name="putama" id="putama" value="{{ $detail->putama == 'U' ? 'Utama' : 'Non Utama' }}" disabled>
                                </div>

                                <div class="col-md-6 mt-3">
                                    <label class="form-label">Install by Branch</label>
                                    <input type="text" class="form-control" value="{{ $detail->insby }} - {{ $detail->mbranch->brana ?? '-' }}" disabled>
                                </div>

                                <hr class="my-4">

                                <h3>Address</h3>

                                <div class="col-md-4 mt-3">
                                    <label for="delto" class="form-label">Delivery To</label>
                                    <input type="text" class="form-control" name="delto" id="delto" value="{{ $detail->delto }}" disabled>
                                </div>
                                
                                <div class="col-md-4 mt-3">
                                    <label for="delto_name" class="form-label">Name</label>
                                    <input type="text" class="form-control" name="delto_name" id="delto_name" value="{{ $detail->site->shpnm }}" disabled>
                                </div>
                                
                                <div class="col-md-4 mt-3">
                                    <label for="delto_attn" class="form-label">Attn.</label>
                                    <input type="text" class="form-control" name="delto_attn" id="delto_attn" value="{{ $detail->site->contp }}" disabled>
                                </div>
                                
                                <div class="col-md-6 mt-3">
                                    <label for="delto_prov" class="form-label">Provinsi</label>
                                    <input type="text" class="form-control" name="delto_prov" id="delto_prov" value="{{ $detail->site->province }}" disabled>
                                </div>

                                <div class="col-md-6 mt-3">
                                    <label for="delto_kab" class="form-label">Kabupaten</label>
                                    <input type="text" class="form-control" name="delto_kab" id="delto_kab" value="{{ $detail->site->kabupaten }}" disabled>
                                </div>

                                <div class="col-md-6 mt-3">
                                    <label for="delto_addrress" class="form-label">Address</label>
                                    <textarea type="text" class="form-control" name="delto_addrress" id="delto_addrress" cols="30" rows="5" disabled>{{ $detail->site->deliveryaddress }}</textarea>
                                </div>

                                <div class="col-md-6 mt-3">
                                    <label for="delto_phone" class="form-label">Phone</label>
                                    <input type="text" class="form-control" name="delto_phone" id="delto_phone" value="{{ $detail->site->phone }}" disabled>
                                </div>

                                <div class="col-md-12 mt-3">
                                    <label class="form-label">Notes</label>
                                    <textarea class="form-control" disabled>{{ $detail->noted }}</textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        {{-- Detail Invoicing --}}
        <div class="row mt-4">
            <h3>OC Detail Invoicing</h3>

            <div class="accordion" id="accordionInvoicing">
                @forelse ($detailsInvoicing as $i => $dinv)
                <div class="accordion-item">
                    <h2 class="accordion-header" id="heading-inv-{{ $i }}">
                        <button class="accordion-button {{ $i > 0 ? 'collapsed' : '' }}"
                                type="button"
                                data-bs-toggle="collapse"
                                data-bs-target="#collapse-inv-{{ $i }}"
                                aria-expanded="{{ $i == 0 ? 'true' : 'false' }}">
                            Payment Phase: {{ $dinv->phase }}
                        </button>
                    </h2>

                    <div id="collapse-inv-{{ $i }}"
                        class="accordion-collapse collapse {{ $i == 0 ? 'show' : '' }}"
                        data-bs-parent="#accordionInvoicing">

                        <div class="accordion-body">
                            <div class="row">

                                <div class="col-md-4 mt-3">
                                    <label class="form-label">Description</label>
                                    <input type="text" class="form-control"
                                        value="{{ $dinv->descr ?? '-' }}" disabled>
                                </div>

                                <div class="col-md-4 mt-3">
                                    <label class="form-label">Term %</label>
                                    <input type="text" class="form-control"
                                        value="{{ $dinv->toppc }}%" disabled>
                                </div>

                                <div class="col-md-4 mt-3">
                                    <label class="form-label">Billing Date</label>
                                    <input type="text" class="form-control"
                                        value="{{ $dinv->billd ? \Carbon\Carbon::parse($dinv->billd)->format('d/m/Y') : '-' }}"
                                        disabled>
                                </div>

                                <div class="col-md-4 mt-3">
                                    <label class="form-label">Gross Amount</label>
                                    <input type="text" class="form-control price-display"
                                        value="{{ $dinv->gross }}" disabled>
                                </div>

                                <div class="col-md-4 mt-3">
                                    <label class="form-label">Official Discount</label>
                                    <input type="text" class="form-control price-display"
                                        value="{{ $dinv->odisa ?? 0 }}" disabled>
                                </div>

                                <div class="col-md-4 mt-3">
                                    <label class="form-label">Net Amount</label>
                                    <input type="text" class="form-control price-display"
                                        value="{{ $dinv->ntamt ?? 0 }}" disabled>
                                </div>

                                <div class="col-md-4 mt-3">
                                    <label class="form-label">Billing Amount</label>
                                    <input type="text" class="form-control price-display"
                                        value="{{ $dinv->blamt ?? 0 }}" disabled>
                                </div>
                                
                                <div class="col-md-4 mt-3">
                                    <label class="form-label">Extra Discount</label>
                                    <input type="text" class="form-control price-display"
                                        value="{{ $dinv->ebamt ?? 0 }}" disabled>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @empty
                    <div class="alert alert-warning mt-3">
                        Tidak ada data invoicing.
                    </div>
                @endforelse
            </div>
        </div>

        <div class="row mt-4">
            <hr class="my-4">
            <div class="card">
                <div class="col-md-12">
                    <h5>Split Quota</h5>
    
                    @for ($q = 1; $q <= 5; $q++)
                        @php
                            $percent = $dinv->{'smqp'.$q};
                            $branch  = $dinv->{'smqtb'.$q};
                            $sales   = $dinv->{'smqts'.$q};
                        @endphp
    
                        @if ($percent)
                        <div class="border rounded p-3 mb-2">
                            <div class="row">
                                <div class="col-md-4">
                                    <strong>Split {{ $q }} %:</strong> {{ $percent }}%
                                </div>
                                <div class="col-md-4">
                                    <strong>Branch:</strong> {{ $branch }}
                                </div>
                                <div class="col-md-4">
                                    <strong>Sales:</strong> {{ $sales }} - {{ $salesName[$sales] ?? '' }}
                                </div>
                            </div>
                        </div>
                        @endif
                    @endfor
                </div>
            </div>

        </div>

        <div class="mt-4">
            <a href="{{ route('oc_sb.index') }}" class="btn btn-secondary">Kembali</a>
        </div>
    </section>
</main>

@push('scripts')
    <script>
        function getLocale(currency) {
            switch(currency) {
                case "IDR": return "id-ID";
                case "USD": return "en-US";
                default: return "en-US";
            }
        }

        function formatCurrency(value, currency) {
            if (value === "" || value === null || isNaN(value)) return "";
            const locale = getLocale(currency);
            const fraction = (currency === "IDR") ? 0 : 2;

            return new Intl.NumberFormat(locale, {
                style: 'currency',
                currency: currency,
                minimumFractionDigits: fraction,
                maximumFractionDigits: fraction
            }).format(value);
        }

        document.addEventListener("DOMContentLoaded", function () {
            const currency = "{{ $ocsb->curco }}";

            document.querySelectorAll('.price-display').forEach(el => {
                const val = (el.value || '').toString().replace(/[^\d.-]/g, "");
                if(val !== ""){
                    el.value = formatCurrency(parseFloat(val), currency);
                }
            });
        });
    </script>
@endpush
@endsection
