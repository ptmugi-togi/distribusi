<hr>
<h4 class="my-3">Termin Pembayaran</h4>

<div class="accordion" id="accordionPhaseDetail">
    @forelse ($mc->mcphase as $i => $phase)
        <div class="accordion-item mb-3">
            <h2 class="accordion-header">
                <button class="accordion-button {{ $i > 0 ? 'collapsed' : '' }}" type="button"
                    data-bs-toggle="collapse"
                    data-bs-target="#phaseCollapse{{ $i }}">
                    Phase {{ $phase->phase ?? $i + 1 }} - {{ $phase->toppc }}%
                </button>
            </h2>

            <div id="phaseCollapse{{ $i }}" class="accordion-collapse collapse {{ $i == 0 ? 'show' : '' }}">
                <div class="accordion-body">
                    <div class="row">
                        <div class="col-md-6 mt-3">
                            <label class="form-label">Description</label>
                            <input class="form-control" value="{{ $phase->descr }}" disabled>
                        </div>

                        <div class="col-md-6 mt-3">
                            <label class="form-label">Term of Payment (%)</label>
                            <input class="form-control" value="{{ $phase->toppc }}%" disabled>
                        </div>

                        <div class="col-md-6 mt-3">
                            <label class="form-label">Gross Amount</label>
                            <input class="form-control" value="{{ $fmt($phase->gramt) }}" disabled>
                        </div>

                        <div class="col-md-6 mt-3">
                            <label class="form-label">Official Discount</label>
                            <input class="form-control" value="{{ $fmt($phase->odisa) }}" disabled>
                        </div>

                        <div class="col-md-6 mt-3">
                            <label class="form-label">Net Amount</label>
                            <input class="form-control" value="{{ $fmt($phase->ntamt) }}" disabled>
                        </div>

                        <div class="col-md-6 mt-3">
                            <label class="form-label">VAT</label>
                            <input class="form-control" value="{{ $fmt($phase->txamt) }}" disabled>
                        </div>

                        <div class="col-md-6 mt-3">
                            <label class="form-label">Billing Amount</label>
                            <input class="form-control" value="{{ $fmt($phase->blamt) }}" disabled>
                        </div>

                        <div class="col-md-6 mt-3">
                            <label class="form-label">Billing Date</label>
                            <input class="form-control" value="{{ $phase->billd }}" disabled>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @empty
        <div class="alert alert-info">Tidak ada termin pembayaran.</div>
    @endforelse
</div>