<div class="row mt-4">
    <h4>Sparepart</h4>

    <div class="accordion" id="accordionSparepartDetail">
        @foreach($spareparts as $sparepart)
            <div class="accordion-item mb-3">
                <h2 class="accordion-header">
                    <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#sparepart{{ $loop->index }}">
                        Sparepart : {{ $sparepart->opron }} - {{ $sparepart->prona }}
                    </button>
                </h2>

                <div id="sparepart{{ $loop->index }}" class="accordion-collapse collapse show">
                    <div class="accordion-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Sparepart</label>
                                <input class="form-control" value="{{ $sparepart->opron }} - {{ $sparepart->prona }}" disabled>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Serial No</label>
                                <input class="form-control" value="{{ $sparepart->lotno }}" disabled>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Warehouse</label>
                                <input class="form-control" value="{{ $sparepart->warco }}" disabled>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Location</label>
                                <input class="form-control" value="{{ $sparepart->locco }}" disabled>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Quantity Used</label>
                                <input class="form-control" value="{{ $sparepart->trqty }} {{ $sparepart->qunit }}" disabled>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Selling Price</label>
                                <input class="form-control" value="{{ $currencySymbol }} {{ number_format($sparepart->price ?? 0, 0, ',', '.') }}" disabled>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Discount</label>
                                <input class="form-control" value="{{ $currencySymbol }} {{ number_format($sparepart->odisa ?? 0, 0, ',', '.') }}" disabled>
                            </div>

                            <div class="col-md-12 mb-3">
                                <label class="form-label">Description</label>
                                <textarea class="form-control" rows="2" disabled>{{ $sparepart->descr }}</textarea>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>