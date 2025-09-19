@extends('layout.main')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/purchasing/tpo.css') }}">
@endpush

@section('container')
    <main id="main" class="main">
        <div class="pagetitle">
            <h1>Edit Data PO "{{ $tpohdr->pono }}"</h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('tpo.index') }}">List PO</a></li>
                    <li class="breadcrumb-item active">Edit PO</li>
                </ol>
            </nav>
        </div>

        <section class="section">
            <form action="{{ route('tpo.update', $tpohdr->pono) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="row">
                    <div class="col-md-6 mt-3">
                        <label class="form-label">Form Code</label>
                        <select class="select2 form-control" name="formc" required>
                            <option value="" disabled>Silahkan pilih Form Code</option>
                            <option value="PO" {{ old('formc',$tpohdr->formc)=='PO'?'selected':'' }}>PO (Lokal)</option>
                            <option value="PI" {{ old('formc',$tpohdr->formc)=='PI'?'selected':'' }}>PI (Import)</option>
                            <option value="PN" {{ old('formc',$tpohdr->formc)=='PN'?'selected':'' }}>PN (Inventaris)</option>
                        </select>
                    </div>

                    <div class="col-md-6 mt-3">
                        <label class="form-label">Nomor PO</label>
                        <input type="text" class="form-control" name="pono" value="{{ old('pono',$tpohdr->pono) }}" readonly>
                    </div>

                    <div class="col-md-6 mt-3">
                        <label class="form-label">Tanggal PO</label>
                        <input type="date" class="form-control" name="podat" value="{{ old('podat',$tpohdr->podat) }}" required>
                    </div>

                    <div class="col-md-6 mt-3">
                        <label class="form-label">Supplier</label>
                        <select class="select2 form-control" name="supno" required>
                            <option value="" disabled>Pilih Supplier</option>
                            @foreach($vendors as $v)
                                <option value="{{ $v->supno }}" {{ old('supno',$tpohdr->supno)==$v->supno?'selected':'' }}>
                                    {{ $v->supno }} - {{ $v->supna }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-6 mt-3">
                        <label class="form-label">Tipe PO</label>
                        <select class="select2 form-control" name="potype" required>
                            <option value="Lokal" {{ old('potype',$tpohdr->potype)=='Lokal'?'selected':'' }}>Lokal</option>
                            <option value="Import" {{ old('potype',$tpohdr->potype)=='Import'?'selected':'' }}>Import</option>
                            <option value="Inventaris" {{ old('potype',$tpohdr->potype)=='Inventaris'?'selected':'' }}>Inventaris</option>
                        </select>
                    </div>

                    <div class="col-md-6 mt-3">
                        <label class="form-label">Term Of Payment</label>
                        <input type="number" class="form-control" name="topay" value="{{ old('topay',$tpohdr->topay) }}" required>
                    </div>

                    <div class="col-md-6 mt-3">
                        <label class="form-label">Deskripsi TOP</label>
                        <input type="text" class="form-control" name="tdesc" value="{{ old('tdesc',$tpohdr->tdesc) }}">
                    </div>

                    <div class="col-md-6 mt-3">
                        <label class="form-label">Currency</label>
                        <select class="select2 form-control" name="curco" required>
                            <option value="IDR" {{ old('curco',$tpohdr->curco)=='IDR'?'selected':'' }}>IDR</option>
                            <option value="USD" {{ old('curco',$tpohdr->curco)=='USD'?'selected':'' }}>USD</option>
                            <option value="EUR" {{ old('curco',$tpohdr->curco)=='EUR'?'selected':'' }}>EUR</option>
                            <option value="GBP" {{ old('curco',$tpohdr->curco)=='GBP'?'selected':'' }}>GBP</option>
                        </select>
                    </div>

                    <div class="col-md-6 mt-3">
                        <label class="form-label">Pengiriman</label>
                        <select class="select2 form-control" name="shvia" required>
                            <option value="DARAT" {{ old('shvia',$tpohdr->shvia)=='DARAT'?'selected':'' }}>DARAT</option>
                            <option value="LAUT" {{ old('shvia',$tpohdr->shvia)=='LAUT'?'selected':'' }}>LAUT</option>
                            <option value="UDARA" {{ old('shvia',$tpohdr->shvia)=='UDARA'?'selected':'' }}>UDARA</option>
                        </select>
                    </div>

                    <div class="col-md-6 mt-3">
                        <label class="form-label">Diskon (%)</label>
                        <input type="number" class="form-control" name="diper" value="{{ old('diper',$tpohdr->diper) }}">
                    </div>

                    <div class="col-md-6 mt-3">
                        <label class="form-label">Tax Rate (%)</label>
                        <input type="number" class="form-control" name="vatax" value="{{ old('vatax',$tpohdr->vatax) }}">
                    </div>

                    <div class="col-md-6 mt-3">
                        <label class="form-label">PPH (%)</label>
                        <input type="number" class="form-control" name="pph" value="{{ old('pph',$tpohdr->pph) }}">
                    </div>

                    <div class="col-md-6 mt-3">
                        <label class="form-label">Meterai</label>
                        <input type="number" class="form-control" name="stamp" value="{{ old('stamp',$tpohdr->stamp) }}">
                    </div>

                    <div class="col-md-12 mt-3">
                        <label class="form-label">Catatan</label>
                        <textarea class="form-control" name="noteh" maxlength="200">{{ old('noteh',$tpohdr->noteh) }}</textarea>
                    </div>

                    <hr class="my-4">

                    <h3 class="my-2">Detail Barang PO</h3>

                    <div id="barang_po">
                        <div class="accordion" id="accordionPoBarang">
                            @foreach($tpohdr->tpodtl as $i => $d)
                            <div class="accordion-item" id="accordion-item-{{ $i }}">
                                <input type="hidden" name="idpo[]" value="{{ $d->idpo }}">
                                <h2 class="accordion-header d-flex justify-content-between align-items-center" id="heading-{{ $i }}">
                                    <button class="accordion-button {{ $i>0?'collapsed':'' }}" type="button"
                                            data-bs-toggle="collapse" data-bs-target="#barang-{{ $i }}"
                                            aria-expanded="{{ $i==0?'true':'false' }}" aria-controls="barang-{{ $i }}">
                                        Barang PO {{ $i+1 }}
                                    </button>
                                    @if($i > 0)
                                    <button type="button" class="btn btn-sm btn-danger mx-2" onclick="removeBarang({{ $i }})">
                                        <i class="bi bi-trash-fill"></i>
                                    </button>
                                    @endif
                                </h2>
                                <div id="barang-{{ $i }}" class="accordion-collapse collapse {{ $i==0?'show':'' }}">
                                    <div class="accordion-body">
                                        <div class="row">
                                            <div class="col-md-6 mt-3">
                                                <label class="form-label">Barang</label>
                                                <select class="select2 form-control" name="opron[]" id="opron-{{ $i }}">
                                                    @foreach($products as $p)
                                                    <option value="{{ $p->opron }}" {{ $d->opron==$p->opron?'selected':'' }}>
                                                        {{ $p->opron }} - {{ $p->prona }}
                                                    </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-md-6 mt-3">
                                                <label class="form-label">Qty</label>
                                                <input type="number" class="form-control" name="poqty[]" value="{{ $d->poqty }}">
                                            </div>
                                            <div class="col-md-6 mt-3">
                                                <label class="form-label">Harga</label>
                                                <input type="text" class="form-control" name="price[]" value="{{ $d->price }}">
                                            </div>
                                            <div class="col-md-6 mt-3">
                                                <label class="form-label">Berat (Kg)</label>
                                                <input type="number" class="form-control" name="weigh[]" value="{{ $d->berat }}">
                                            </div>
                                            <div class="col-md-6 mt-3">
                                                <label class="form-label">Diskon (%)</label>
                                                <input type="number" class="form-control" name="odisp[]" value="{{ $d->odisp }}">
                                            </div>
                                            <div class="col-md-6 mt-3">
                                                <label class="form-label">Tanggal Pengiriman</label>
                                                <input type="date" class="form-control" name="edeld[]" value="{{ $d->edeld }}">
                                            </div>
                                            <div class="col-md-6 mt-3">
                                                <label class="form-label">Tanggal Kedatangan</label>
                                                <input type="date" class="form-control" name="earrd[]" value="{{ $d->earrd }}">
                                            </div>
                                            <div class="col-md-6 mt-3">
                                                <label class="form-label">HSN</label>
                                                <input type="number" class="form-control" name="hsn[]" value="{{ $d->hsn }}">
                                            </div>
                                            <div class="col-md-6 mt-3">
                                                <label class="form-label">BM</label>
                                                <input type="number" class="form-control" name="bm[]" value="{{ $d->bm }}">
                                            </div>
                                            <div class="col-md-6 mt-3">
                                                <label class="form-label">BMT</label>
                                                <input type="number" class="form-control" name="bmt[]" value="{{ $d->bmt }}">
                                            </div>
                                            <div class="col-md-6 mt-3">
                                                <label class="form-label">PPH</label>
                                                <input type="number" class="form-control" name="pphd[]" value="{{ $d->pphd }}">
                                            </div>
                                            <div class="col-md-12 mt-3">
                                                <label class="form-label">Catatan</label>
                                                <textarea class="form-control" name="noted[]">{{ $d->noted }}</textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>

                    <div class="text-end">
                        <button type="button" class="btn mt-3" style="background-color:#4456f1;color:#fff" onclick="addBarang()">Tambah Barang</button>
                    </div>

                    <div class="mt-3 d-flex justify-content-between">
                        <a href="{{ route('tpo.index') }}" class="btn btn-secondary">Kembali</a>
                        <button type="submit" class="btn btn-primary">Perbaharui Data</button>
                    </div>
                </div>
            </form>
        </section>
    </main>

    @push('scripts')
        <script>
            let barangIndex = {{ count($tpohdr->tpodtl) }};

            function addBarang() {
                const accordion = document.getElementById('accordionPoBarang');
                const newItem = document.createElement('div');
                newItem.classList.add('accordion-item');
                newItem.id = `accordion-item-${barangIndex}`;

                newItem.innerHTML = `
                    <input type="hidden" name="idpo[]" value="">
                    <h2 class="accordion-header d-flex justify-content-between align-items-center" id="heading-${barangIndex}">
                        <button class="accordion-button collapsed" type="button"
                                data-bs-toggle="collapse" data-bs-target="#barang-${barangIndex}"
                                aria-expanded="false" aria-controls="barang-${barangIndex}">
                            Barang PO ${barangIndex+1}
                        </button>
                        <button type="button" class="btn btn-sm btn-danger mx-2" onclick="removeBarang(${barangIndex})">
                            <i class="bi bi-trash-fill"></i>
                        </button>
                    </h2>
                    <div id="barang-${barangIndex}" class="accordion-collapse collapse">
                        <div class="accordion-body">
                            <div class="row">
                                <div class="col-md-6 mt-3">
                                    <label class="form-label">Barang</label>
                                    <select class="select2 form-control" name="opron[]" id="opron-${barangIndex}">
                                        <option value="" disabled selected>Pilih Barang</option>
                                        @foreach($products as $p)
                                        <option value="{{ $p->opron }}">{{ $p->opron }} - {{ $p->prona }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-6 mt-3">
                                    <label class="form-label">Qty</label>
                                    <input type="number" class="form-control" name="poqty[]" placeholder="Cth : 10">
                                </div>
                                <div class="col-md-6 mt-3">
                                    <label class="form-label">Harga</label>
                                    <input type="text" class="form-control" name="price[]" placeholder="Cth : 1000000">
                                </div>
                                <div class="col-md-6 mt-3">
                                    <label class="form-label">Berat (Kg)</label>
                                    <input type="number" class="form-control" name="weigh[]" placeholder="Cth : 10">
                                </div>
                                <div class="col-md-6 mt-3">
                                    <label class="form-label">Diskon (%)</label>
                                    <input type="number" class="form-control" name="odisp[]" value="0">
                                </div>
                                <div class="col-md-6 mt-3">
                                    <label class="form-label">Tanggal Pengiriman</label>
                                    <input type="date" class="form-control" name="edeld[]">
                                </div>
                                <div class="col-md-6 mt-3">
                                    <label class="form-label">Tanggal Kedatangan</label>
                                    <input type="date" class="form-control" name="earrd[]">
                                </div>
                                <div class="col-md-6 mt-3">
                                    <label class="form-label">HSN</label>
                                    <input type="number" class="form-control" name="hsn[]">
                                </div>
                                <div class="col-md-6 mt-3">
                                    <label class="form-label">BM</label>
                                    <input type="number" class="form-control" name="bm[]" value="0">
                                </div>
                                <div class="col-md-6 mt-3">
                                    <label class="form-label">BMT</label>
                                    <input type="number" class="form-control" name="bmt[]" value="0">
                                </div>
                                <div class="col-md-6 mt-3">
                                    <label class="form-label">PPH</label>
                                    <input type="number" class="form-control" name="pphd[]" value="0">
                                </div>
                                <div class="col-md-12 mt-3">
                                    <label class="form-label">Catatan</label>
                                    <textarea class="form-control" name="noted[]" maxlength="200"></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                `;
                accordion.appendChild(newItem);
                $(`#opron-${barangIndex}`).select2({theme:'bootstrap-5',width:'100%'});
                barangIndex++;
            }

            function removeBarang(index) {
                const item = document.getElementById(`accordion-item-${index}`);
                if(item){ item.remove(); }
            }
        </script>
    @endpush
@endsection
