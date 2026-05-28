<div class="row mt-4">
    <h4>Detail Service</h4>

    <div class="accordion" id="accordionServiceDetail">
        @foreach($services as $service)
            <div class="accordion-item mb-3">
                <h2 class="accordion-header">
                    <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#service{{ $service->dnlin }}">
                        Product : {{ $service->opron }} - {{ $service->prona }}
                    </button>
                </h2>

                <div id="service{{ $service->dnlin }}" class="accordion-collapse collapse show">
                    <div class="accordion-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Product</label>
                                <input class="form-control" value="{{ $service->opron }} - {{ $service->prona }}" disabled>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Quantity</label>
                                <input class="form-control" value="{{ $service->trqty }} {{ $service->stdqu ?? '' }}" disabled>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Serial No</label>
                                <input class="form-control" value="{{ $service->lotno }}" disabled>
                            </div>

                            <div class="col-md-12 mb-3">
                                <label class="form-label">Service Type</label>

                                @foreach(($serviceFees[$service->dnlin] ?? []) as $fee)
                                    <div class="row g-2 mb-2">
                                        <div class="col-md-8">
                                            <input class="form-control" value="{{ $fee->tofee }} - {{ $fee->descr }}" disabled>
                                        </div>
                                        <div class="col-md-4">
                                            <input class="form-control" value="{{ $currencySymbol }} {{ number_format($fee->gramt ?? 0, 0, ',', '.') }}" disabled>
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Gross Amount</label>
                                <input class="form-control" value="{{ $currencySymbol }} {{ number_format($service->gramt ?? 0, 0, ',', '.') }}" disabled>
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Discount</label>
                                <input class="form-control" value="{{ $currencySymbol }} {{ number_format($service->odisa ?? 0, 0, ',', '.') }}" disabled>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>