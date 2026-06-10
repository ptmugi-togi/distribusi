<hr>
<h4 class="my-3">Detail Product</h4>

<div class="accordion" id="accordionProductDetail">
    @forelse ($mc->mcdtls as $i => $detail)
        <div class="accordion-item mb-3">
            <h2 class="accordion-header">
                <button class="accordion-button {{ $i > 0 ? 'collapsed' : '' }}" type="button"
                    data-bs-toggle="collapse"
                    data-bs-target="#productCollapse{{ $i }}">
                    Product {{ $i + 1 }} - {{ $detail->mpromas->prona ?? $detail->opron }}
                </button>
            </h2>

            <div id="productCollapse{{ $i }}" class="accordion-collapse collapse {{ $i == 0 ? 'show' : '' }}">
                <div class="accordion-body">
                    <div class="row">
                        <div class="col-md-6 mt-3">
                            <label class="form-label">Product</label>
                            <input class="form-control" value="{{ $detail->opron }} - {{ $detail->mpromas->prona ?? '' }}" disabled>
                        </div>

                        <div class="col-md-6 mt-3">
                            <label class="form-label">Serial No</label>
                            <input class="form-control" value="{{ $detail->lotno }}" disabled>
                        </div>

                        <div class="col-md-6 mt-3">
                            <label class="form-label">MC Status</label>

                            <input
                                class="form-control"
                                value="@if($detail->mcsts == 'R')R - Renewal
                                        @elseif($detail->mcsts == 'G')G - Garantie
                                        @elseif($detail->mcsts == 'C')C - Competitor Product
                                        @elseif($detail->mcsts == 'O')O - Others
                                        @else-
                                        @endif"
                                disabled>
                        </div>

                        <div class="col-md-6 mt-3">
                            <label class="form-label">Gross Amount</label>
                            <input class="form-control" value="{{ $fmt($detail->price) }}" disabled>
                        </div>

                        <div class="col-md-6 mt-3">
                            <label class="form-label">Plan to Visit / Year</label>
                            <input class="form-control" value="{{ $detail->pvisi }}" disabled>
                        </div>

                        <div class="col-md-6 mt-3">
                            <label class="form-label">First Visit</label>
                            <input class="form-control" value="{{ $detail->fvisi }}" disabled>
                        </div>

                        <div class="col-md-6 mt-3">
                            <label class="form-label">Site Address</label>
                            <textarea class="form-control" rows="2" disabled>{{ trim(($detail->add01 ?? '') . ", " . ($detail->add02 ?? '') . "\n" . ($detail->add03 ?? '') . "\n" . ($detail->add04 ?? '') . "\n" . ($detail->city ?? '')) }}</textarea>
                        </div>

                        <div class="col-md-6 mt-3">
                            <label class="form-label">Phone</label>
                            <input class="form-control" value="{{ $detail->phone }}" disabled>
                        </div>

                        <div class="col-md-12 mt-3">
                            <label class="form-label">Noted</label>
                            <textarea class="form-control" rows="2" disabled>{{ $detail->noted }}</textarea>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @empty
        <div class="alert alert-info">Tidak ada detail product.</div>
    @endforelse
</div>