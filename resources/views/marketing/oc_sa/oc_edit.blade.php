@extends('layout.main')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/global.css') }}">
@endpush

@section('container')
<main id="main" class="main">
    <div class="d-flex justify-content-between align-items-center">
        <div class="pagetitle">
            <h1>Edit OC ({{ $oc->ocid }})</h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('oc.index') }}">List OC</a></li>
                    <li class="breadcrumb-item active">Edit OC</li>
                </ol>
            </nav>
        </div>
    </div>

    <section class="section">
        <form id="form-oc" action="{{ route('oc.update', $oc->ocid) }}" method="POST">
            @csrf
            @method('PUT')
            {{-- Header --}}
            <div class="card p-3 shadow-sm">
                <input type="text" id="braco" name="braco" value="{{ auth()->user()->cabang }}" hidden>
                <input type="text" id="formc" name="formc" value="SA" hidden>

                <div class="row">
                    <div class="col-md-6 mt-3">
                        <label for="sorno" class="form-label">OC No.</label>
                        <input type="text" class="form-control" id="sorno" name="sorno" value="{{ $oc->sorno }}" readonly style="background-color:#e9ecef">
                    </div>

                    <div class="col-md-6 mt-3">
                        <label for="sordt" class="form-label">OC Date</label>
                        <input type="text" class="form-control" id="sordt" value="{{ \Carbon\Carbon::parse($oc->sordt)->format('d/m/Y') }}" disabled>
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
                        <input type="text" class="form-control price-input" id="crate" value="{{ $oc->crate }}" disabled>
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
                        <input type="text" class="form-control price-input" id="edisa" value="{{ $oc->edisa ?? '-' }}" disabled>
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
                        <textarea type="text" class="form-control" name="noteh" id="noteh" cols="30" rows="3">{{ $oc->noteh }}</textarea>
                    </div>
                </div>
            </div>

            {{-- Detail --}}
            <div class="row mt-4">
                <h3>OC Detail</h3>
                <div class="accordion" id="accordionOC">
                    @foreach ($oc->ocdtls as $i => $detail)
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="heading-{{ $i }}">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                data-bs-target="#collapse-{{ $i }}" aria-expanded="false">
                                <span class="accordion-title">
                                    Product: {{ $detail->opron }} - {{ $detail->mpromas->prona }}
                                </span>
                            </button>
                        </h2>
                        <div id="collapse-{{ $i }}" class="accordion-collapse collapse"
                            aria-labelledby="heading-{{ $i }}">
                            <div class="accordion-body">
                                <div class="row">
                                    <div class="col-md-6 mt-3">
                                        <label class="form-label">Product</label><span class="text-danger"> *</span>
                                        <select class="form-select opron-oc" name="opron[]" id="opron-oc-{{ $i }}" required>
                                            <option value="{{ $detail->opron }}" selected>
                                                {{ $detail->opron }} - {{ $detail->prona ?? '' }}
                                            </option>
                                        </select>
                                        <input type="text" class="prona-oc" name="prona[]" id="prona-oc-{{ $i }}" value="{{ $detail->prona }}">
                                    </div>

                                    <div class="col-md-6 mt-3">
                                        <label class="form-label">Order Quantity</label><span class="text-danger"> *</span>
                                        <div class="input-group">
                                            <input type="text" name="qtyor[]" class="form-control" id="qtyor-oc-{{ $i }}" value="{{ $detail->qtyor }}" required oninput="this.value = this.value.replace(/[^0-9]/g, '');">
                                            <span class="input-group-text unit-label-oc" id="unit-label-oc-{{ $i }}">{{ $detail->stdqu }}</span>
                                            <input type="text" class="stdqu-oc" name="stdqu[]" id="stdqu-oc-{{ $i }}" value="{{ $detail->stdqu }}" hidden>
                                        </div>
                                    </div>

                                    <div class="col-md-6 mt-3">
                                        <label for="rqeta" class="form-label">Request ETA</label><span class="text-danger"> *</span>
                                        <input type="date" name="rqeta[]" class="form-control" id="rqeta-oc-{{ $i }}" value="{{ $detail->rqeta }}" required>
                                    </div>

                                    <div class="col-md-6 mt-3">
                                        <label for="whetd" class="form-label">ETD by W/H</label><span class="text-danger"> *</span>
                                        <input type="date" name="whetd[]" class="form-control" id="whetd-oc-{{ $i }}" value="{{ $detail->whetd }}" required>
                                    </div>

                                    <div class="col-md-6 mt-3">
                                        <label for="price" class="form-label">Selling Price</label><span class="text-danger"> *</span>
                                        <input type="text" class="form-control price-input" name="price[]" id="price-oc-{{ $i }}" value="{{ $detail->price }}" required>
                                    </div>

                                    <div class="col-md-6 mt-3">
                                        <label for="plist" class="form-label">Price List/Unit</label><span class="text-danger"> *</span>
                                        <input type="text" class="form-control price-input" name="plist[]" id="plist-oc-{{ $i }}" value="{{ $detail->plist }}" required>
                                    </div>

                                    <div class="col-md-6 mt-3">
                                        <label for="odisp" class="form-label">Official Discount</label>
                                        <input type="text" class="form-control price-input" name="odisp[]" id="odisp-oc-{{ $i }}" value="{{ $detail->odisp ?? '-' }}">
                                    </div>

                                    <div class="col-md-6 mt-3">
                                        <label for="teknik" class="form-label">Jasa Teknik (Unit)</label>
                                        <input type="text" class="form-control price-input" name="teknik[]" id="teknik-oc-{{ $i }}" value="{{ $detail->teknik }}">
                                    </div>

                                    <div class="col-md-6 mt-3">
                                        <label for="srcog" class="form-label">Source of Goods</label><span class="text-danger"> *</span>
                                        <select class="form-select select2" name="srcog[]" id="srcog-oc-{{ $i }}" required>
                                            <option value="1" {{ $detail->srcog == 1 ? 'selected' : '' }}>1. Branch's Stock</option>
                                            <option value="2" {{ $detail->srcog == 2 ? 'selected' : '' }}>2. Request to Head Office</option>
                                        </select>
                                    </div>

                                    <div class="col-md-12 mt-3">
                                        <label class="form-label">Notes</label>
                                        <textarea class="form-control" name="noted[]" id="noted-oc-{{ $i }}">{{ $detail->noted }}</textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
                <div class="text-end">
                    <button type="button" class="btn mt-3" style="background-color:#4456f1;color:#fff" onclick="addOC()">Tambah Detail </button>
                </div>
            </div>

            <div class="mt-3 d-flex justify-content-between">
                <a href="{{ route('oc.index') }}" class="btn btn-secondary">Kembali</a>
                <button type="submit" class="btn btn-primary">Simpan Data</button>
            </div>
        </form>
    </section>
</main>

@push('scripts')
    @include('marketing.oc_sa.partial_edit.add_detail_oc')
    {{-- product --}}
    <script>
        function loadMasterProductAll() {
            $('select.opron-oc').each(function () {

                $(this).select2({
                    placeholder: 'Pilih Barang',
                    theme: 'bootstrap-5',
                    width: '100%',
                    allowClear: true,
                    minimumInputLength: 0,
                    ajax: {
                        url: '{{ route("api.products") }}',
                        dataType: 'json',
                        delay: 250,
                        cache: true,
                        data: function (params) {
                            return {
                                q: params.term || '',
                                page: params.page || 1
                            };
                        },
                        processResults: function (data) {
                            return {
                                results: (data.results || []).map(item => ({
                                    id: item.id,
                                    text: item.text,
                                    prona: item.data_prona,
                                    stdqt: item.data_stdqu,
                                    locco: item.data_locco
                                })),
                                pagination: { more: data.pagination?.more || false }
                            };
                        }
                    },
                    templateResult: function (data) {
                        return data.text || '';
                    },
                    templateSelection: function (data) {
                        return data.text || '';
                    }
                });

                const selectedId = $(this).data("selected-id");
                const selectedText = $(this).data("selected-text");

                if (selectedId && $(this).val() == null) {
                    const option = new Option(selectedText, selectedId, true, true);
                    $(this).append(option).trigger("change");
                }
            });
        }

        $(document).ready(function(){
            setTimeout(function(){
                loadMasterProductAll();
            }, 500);
        });

        function setAccordionTitle($item){
            const text = $item.find('select.opron-oc option:selected').text() || '';
            $item.find('.accordion-title').text(text ? `Product : ${text}` : '-');
        }

        $(document).on('select2:select', 'select.opron-oc', function (e) {
            const $select = $(this);
            const data = e.params.data || {};
            const $accordionItem = $select.closest('.accordion-item');

            if (data.stdqt) {
                $accordionItem.find('.stdqu-oc').val(data.stdqt);

                $accordionItem.find('.unit-label-oc').text(data.stdqt);
            }

            if (data.prona) {
                $accordionItem.find('.prona-oc').val(data.prona);
            }

            setAccordionTitle($accordionItem);
        });

        $(document).on('change', 'select.opron-oc', function(){
            const $accordionItem = $(this).closest('.accordion-item');
            setAccordionTitle($accordionItem);
        });
    </script>

    {{-- format currency --}}
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
            if (!currency) return value;

            const locale = getLocale(currency);
            const fraction = (currency === "IDR") ? 0 : 2;

            return new Intl.NumberFormat(locale, {
                style: 'currency',
                currency: currency,
                minimumFractionDigits: fraction,
                maximumFractionDigits: fraction
            }).format(value);
        }

        function applyCurrencyFormatter(container = document) {
            const currency = "{{ $oc->curco }}";

            container.querySelectorAll(".price-input").forEach((input) => {

                if (input.dataset.currencyBind === "1") return;
                input.dataset.currencyBind = "1";

                let raw = (input.value || "").toString().replace(/[^\d.,-]/g, "");
                if (raw !== "") {
                    if (currency === "IDR") {
                        raw = raw.replace(/,/g, "");
                        const num = Math.floor(parseFloat(raw));
                        input.value = formatCurrency(num, currency);
                    } else {
                        raw = raw.replace(/,/g, "");
                        input.value = formatCurrency(parseFloat(raw), currency);
                    }
                }

                input.addEventListener("input", () => {
                    if (currency === "IDR") {
                        // hanya angka
                        input.value = input.value.replace(/[^\d]/g, "");
                    } else {
                        // USD: angka + decimal (max 2 digit)
                        let v = input.value.replace(/[^\d.,]/g, "");

                        // koma jadi titik
                        v = v.replace(/,/g, ".");

                        // hanya boleh 1 titik
                        v = v.replace(/(\..*)\./g, "$1");

                        // max 2 decimal
                        if (v.includes(".")) {
                            const parts = v.split(".");
                            v = parts[0] + "." + (parts[1] || "").slice(0, 2);
                        }
                        input.value = v;
                    }
                });

                input.addEventListener("focus", () => {
                    input.value = (input.value || "").toString().replace(/[^\d.,-]/g, "");
                });

                input.addEventListener("blur", () => {
                    let v = (input.value || "").toString().replace(/[^\d.,-]/g, "");
                    if (v === "") return;

                    if (currency === "IDR") {
                        v = v.replace(/[.,]/g, "");
                        input.value = formatCurrency(Math.floor(parseFloat(v)), currency);
                    } else {
                        v = v.replace(/,/g, "");
                        input.value = formatCurrency(parseFloat(v), currency);
                    }
                });
            });
        }

        document.addEventListener("DOMContentLoaded", function() {
            applyCurrencyFormatter(document);

            const form = document.getElementById("form-oc");
            if (!form) return;

            form.addEventListener("submit", function () {
                const currency = "{{ $oc->curco }}";

                document.querySelectorAll(".price-input").forEach((input) => {
                    let v = (input.value || "").toString();

                    if (currency === "IDR") {
                        v = v.replace(/[^\d]/g, "");
                        input.value = v ? parseFloat(v) : "";
                    } else {
                        v = v.replace(/[^\d.,]/g, "");
                        v = v.replace(/,/g, ".");
                        v = v.replace(/(\..*)\./g, "$1");

                        if (v.includes(".")) {
                            const parts = v.split(".");
                            v = parts[0] + "." + (parts[1] || "").slice(0, 2);
                        }

                        input.value = v ? parseFloat(v) : "";
                    }
                });
            });
        });
    </script>

    <script>
        // SweetAlert confirm submit
        document.addEventListener("DOMContentLoaded", function() {
            const form = document.getElementById('form-oc');
            form.addEventListener('submit', function (e) {
            e.preventDefault();
            Swal.fire({
                title: 'Konfirmasi Ubah',
                text: 'Apakah Anda yakin ingin mengubah data ini?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, Ubah!',
                cancelButtonText: 'Batal'
            }).then((res)=>{
                if(res.isConfirmed){
                Swal.fire({ title:'Mengubah...', text:'Mohon tunggu sebentar', icon:'info', showConfirmButton:false, allowOutsideClick:false, allowEscapeKey:false, didOpen:()=>Swal.showLoading() });
                form.submit();
                }
            });
            });
        });
    </script>
@endpush
@endsection
