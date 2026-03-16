@extends('layout.main')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/global.css') }}">
@endpush

@section('container')
<main id="main" class="main">
    <div class="d-flex justify-content-between align-items-center">
        <div class="pagetitle">
            <h1>Detail OC Retail (SA) ({{ $oc->ocid }})</h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('oc.index') }}">List OC Retail (SA)</a></li>
                    <li class="breadcrumb-item active">Detail OC Retail (SA)</li>
                </ol>
            </nav>
        </div>
    </div>

    <section class="section">
        {{-- Header --}}
        <div class="card p-3 shadow-sm">
            <input type="text" id="braco" value="{{ auth()->user()->cabang }}" hidden>

            <div class="row">
                <div class="col-md-4 mt-3">
                    <label for="sorno" class="form-label">OC No.</label>
                    <input type="text" class="form-control" id="sorno" value="{{ $oc->sorno }}" disabled>
                </div>

                <div class="col-md-4 mt-3">
                    <label for="depo" class="form-label">OC Date</label>
                    <input type="text" class="form-control" id="sordt" value="{{ \Carbon\Carbon::parse($oc->sordt)->format('d/m/Y') }}" disabled>
                </div>

                <div class="col-md-4 mt-3">
                    <label for="depo" class="form-label">Depo</label>
                    <input type="text" class="form-control" id="depo" value="{{ $oc->depo }} - {{ $oc->mdepo->name }}" disabled>
                </div>

                <div class="col-md-6 mt-3">
                    <label for="cusno" class="form-label">Customer</label>
                    <input type="text" class="form-control" id="cusno" value="{{ $oc->cusno }} - {{ $oc->mcusmas->cusna }}" disabled>
                </div>

                <div class="col-md-6 mt-3">
                    <label for="sreno" class="form-label">Sales Rep</label>
                    <input type="text" class="form-control" id="sreno" value="{{ $oc->msreno->srena }}" disabled>
                </div>

                <div class="col-md-4 mt-3">
                    <label for="topay" class="form-label">Payment Term (days)</label>
                    <input type="text" class="form-control" id="topay" value="{{ $oc->topay }}" disabled>
                </div>

                <div class="col-md-8 mt-3">
                    <label for="cuspo" class="form-label">Customer PO</label>
                    <input type="text" class="form-control" id="cuspo" value="{{ $oc->cuspo }}" disabled>
                </div>

                <div class="col-md-6 mt-3">
                    <label for="curco" class="form-label">Currency Code</label>
                    <input type="text" class="form-control" id="curco" value="{{ $oc->curco }}" disabled>
                </div>

                <div class="col-md-6 mt-3">
                    <label for="crate" class="form-label">Currency Rate</label>
                    <input type="text" class="form-control price-display" id="crate" value="{{ $oc->crate }}" disabled>
                </div>

                <div class="col-md-4 mt-3">
                    <label for="ebtyp" class="form-label">EB Type</label>
                    <input type="text" class="form-control" id="ebtyp" value="{{ $oc->ebtyp }}" disabled>
                </div>

                <div class="col-md-4 mt-3">
                    <label for="edisp" class="form-label">EB (%)</label>
                    <input type="text" class="form-control" id="edisp" value="{{ $oc->edisp ?? '-' }}" disabled>
                </div>

                <div class="col-md-4 mt-3">
                    <label for="edisa" class="form-label">EB (Amount)</label>
                    <input type="text" class="form-control price-display" id="edisa" value="{{ $oc->edisa ?? '-' }}" disabled>
                </div>

                <div class="col-md-6 mt-3">
                    <label for="nodeb" class="form-label">Disposisi EB#</label>
                    <input type="text" class="form-control" id="nodeb" value="{{ $oc->nodeb }}" disabled>
                </div>

                <div class="col-md-6 mt-3">
                    <label for="dpper" class="form-label">Down Payment (%)</label>
                    <input type="text" class="form-control" id="dpper" value="{{ $oc->dpper ?? '-' }}" disabled>
                </div>

                @if ($oc->sqper != null)
                    <hr class="my-4">

                    <div class="col-md-4 mt-3">
                        <label for="sqper" class="form-label">Split Quota (%)</label>
                        <input type="text" class="form-control" id="sqper" value="{{ $oc->sqper ?? '-' }}" disabled>
                    </div>

                    <div class="col-md-4 mt-3">
                        <label for="sqtbr" class="form-label">To Branch</label>
                        <input type="text" class="form-control" id="sqtbr" value="{{ $oc->sqtbr ?? '-' }}" disabled>
                    </div>

                    <div class="col-md-4 mt-3">
                        <label for="sqtsr" class="form-label">Sales Rep.</label>
                        <input type="text" class="form-control" id="sqtsr" value="{{ $oc->sqtsr ?? '-' }}" disabled>
                    </div>
                @endif

                <hr class="my-4">

                <h3>Address</h3>

                <div class="col-md-4 mt-3">
                    <label for="delto" class="form-label">Delivery To</label>
                    <input type="text" class="form-control" name="delto" id="delto" value="{{ $oc->delto }}" disabled>
                </div>
                
                <div class="col-md-4 mt-3">
                    <label for="delto_name" class="form-label">Name</label>
                    <input type="text" class="form-control" name="delto_name" id="delto_name" value="{{ $delto->shpnm }}" disabled>
                </div>
                
                <div class="col-md-4 mt-3">
                    <label for="delto_attn" class="form-label">Attn.</label>
                    <input type="text" class="form-control" name="delto_attn" id="delto_attn" value="{{ $delto->contp }}" disabled>
                </div>
                
                <div class="col-md-6 mt-3">
                    <label for="delto_prov" class="form-label">Provinsi</label>
                    <input type="text" class="form-control" name="delto_prov" id="delto_prov" value="{{ $delto->province }}" disabled>
                </div>

                <div class="col-md-6 mt-3">
                    <label for="delto_kab" class="form-label">Kabupaten</label>
                    <input type="text" class="form-control" name="delto_kab" id="delto_kab" value="{{ $delto->kabupaten }}" disabled>
                </div>

                <div class="col-md-6 mt-3">
                    <label for="delto_addrress" class="form-label">Address</label>
                    <textarea type="text" class="form-control" name="delto_addrress" id="delto_addrress" cols="30" rows="5" disabled>{{ $delto->deliveryaddress }}</textarea>
                </div>

                <div class="col-md-6 mt-3">
                    <label for="delto_phone" class="form-label">Phone</label>
                    <input type="text" class="form-control" name="delto_phone" id="delto_phone" value="{{ $delto->phone }}" disabled>
                </div>

                <div class="col-md-12 mt-3"> 
                    <label for="noteh" class="form-label">Notes</label>
                    <textarea type="text" class="form-control" name="noteh" id="noteh" cols="30" rows="3" disabled>{{ $oc->noteh }}</textarea>
                </div>
            </div>
        </div>

        {{-- Detail --}}
        <div class="row mt-4">
            <h3>OC Detail</h3>
            <div class="accordion" id="accordionOc">
                @foreach ($oc->ocdtls as $i => $detail)
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

                                <div class="col-md-6 mt-3">
                                    <label class="form-label">Order Quantity</label>
                                    <div class="input-group">
                                        <input type="text" class="form-control" value="{{ $detail->qtyor }}" disabled>
                                        <span class="input-group-text">{{ $detail->stdqu }}</span>
                                    </div>
                                </div>

                                <div class="col-md-6 mt-3">
                                    <label for="rqeta" class="form-label">Request ETA</label>
                                    <input type="text" class="form-control" id="rqeta" value="{{ \Carbon\Carbon::parse($detail->rqeta)->format('d/m/Y') }}" disabled>
                                </div>

                                <div class="col-md-6 mt-3">
                                    <label for="whetd" class="form-label">ETD by W/H</label>
                                    <input type="text" class="form-control" id="whetd" value="{{ \Carbon\Carbon::parse($detail->whetd)->format('d/m/Y') }}" disabled>
                                </div>

                                <div class="col-md-6 mt-3">
                                    <label for="price" class="form-label">Selling Price</label>
                                    <input type="text" class="form-control price-display" name="price" id="price" value="{{ $detail->price }}" disabled>
                                </div>

                                <div class="col-md-6 mt-3">
                                    <label for="plist" class="form-label">Price List/Unit</label>
                                    <input type="text" class="form-control price-display" name="plist" id="plist" value="{{ $detail->plist }}" disabled>
                                </div>

                                <div class="col-md-6 mt-3">
                                    <label for="odisa" class="form-label">Official Discount</label>
                                    <input type="text" class="form-control price-display" name="odisa" id="odisa" value="{{ $detail->odisa ?? '-' }}" disabled>
                                </div>

                                <div class="col-md-6 mt-3">
                                    <label for="teknik" class="form-label">Jasa Teknik (Unit)</label>
                                    <input type="text" class="form-control price-display" name="teknik" id="teknik" value="{{ $detail->teknik }}" disabled>
                                </div>

                                <div class="col-md-6 mt-3">
                                    <label for="srcog" class="form-label">Source of Goods</label>
                                    @if ($detail->srcog == 1)
                                        <input type="text" class="form-control" name="srcog" id="srcog" value="Branch's Stock" disabled>
                                    @elseif ($detail->srcog == 2)
                                        <input type="text" class="form-control" name="srcog" id="srcog" value="Request to Head Office" disabled>
                                    @endif
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

        <div class="mt-4">
            <a href="{{ route('oc.index') }}" class="btn btn-secondary">Kembali</a>
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
            const currency = "{{ $oc->curco }}";

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
