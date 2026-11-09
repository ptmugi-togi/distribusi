@extends('layout.main')
@section('container')

<main id="main" class="main">
    <div class="pagetitle">
        <h1>Role Permission</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item">MASTER</li>
                <li class="breadcrumb-item active">Role Permission</li>
            </ol>
        </nav>
    </div>

    <section class="section">
        <div class="row">
            <div class="col-lg-12">
                
                @if(session()->has('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="bi bi-check-circle me-1"></i> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                @endif
                @if(session()->has('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="bi bi-exclamation-octagon me-1"></i> {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
                @endif

                <div class="card">
                    <div class="card-body pt-3">
                        
                        <ul class="nav nav-tabs nav-tabs-bordered" id="branchTab" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active" id="cabang-pst-tab" data-bs-toggle="tab" data-bs-target="#cabang-pst" type="button" role="tab">
                                    <i class="bi bi-building me-1"></i> PST (Head Office)
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="cabang-jk1-tab" data-bs-toggle="tab" data-bs-target="#cabang-jk1" type="button" role="tab">
                                    <i class="bi bi-building me-1"></i> JK1 (Branch)
                                </button>
                            </li>
                        </ul>

                        <div class="tab-content pt-3" id="branchTabContent">
                            <div class="tab-pane fade show active" id="cabang-pst" role="tabpanel">
                                <div class="accordion accordion-flush" id="accordionCabangA">
                                    <div class="accordion-item">
                                        <h2 class="accordion-header" id="headingA1">
                                            <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseA1">
                                                <i class="bi bi-cash-stack me-2 text-primary"></i> <strong>Finance & Accounting (F&A) - PST</strong>
                                            </button>
                                        </h2>
                                        <div id="collapseA1" class="accordion-collapse collapse show" data-bs-parent="#accordionCabangA">
                                            <div class="accordion-body pt-3">
                                                <form action="{{ route('roles.update', 'fa-cabang-pst') }}" method="POST">
                                                    @csrf
                                                    @method('PUT')
                                                    
                                                    @include('master.role_permission.table_matrix', [
                                                        'branch' => 'PST',
                                                        'role' => 'F&A',
                                                        'activePermissions' => $rolePermissions['fa_cabang_pst']
                                                    ])

                                                    <div class="d-flex justify-content-end mt-3">
                                                        <button type="submit" class="btn btn-primary btn-sm">
                                                            <i class="bi bi-save me-1"></i> Simpan Permission F&A (PST)
                                                        </button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="accordion-item">
                                        <h2 class="accordion-header" id="headingA2">
                                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseA2">
                                                <i class="bi bi-box-seam me-2 text-primary"></i> <strong>Logistik & Gudang - PST</strong>
                                            </button>
                                        </h2>
                                        <div id="collapseA2" class="accordion-collapse collapse" data-bs-parent="#accordionCabangA">
                                            <div class="accordion-body pt-3">
                                                <form action="{{ route('roles.update', 'logistik-cabang-pst') }}" method="POST">
                                                    @csrf
                                                    @method('PUT')
                                                    
                                                    @include('master.role_permission.table_matrix', [
                                                        'branch' => 'PST',
                                                        'role' => 'Logistik',
                                                        'activePermissions' => $rolePermissions['logistik_cabang_pst']
                                                    ])

                                                    <div class="d-flex justify-content-end mt-3">
                                                        <button type="submit" class="btn btn-primary btn-sm">
                                                            <i class="bi bi-save me-1"></i> Simpan Permission Logistik (PST)
                                                        </button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="tab-pane fade" id="cabang-jk1" role="tabpanel">
                                <div class="accordion accordion-flush" id="accordionCabangB">
                                    <div class="accordion-item">
                                        <h2 class="accordion-header" id="headingB1">
                                            <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseB1">
                                                <i class="bi bi-cash-stack me-2 text-success"></i> <strong>Finance & Accounting (F&A) - JK1</strong>
                                            </button>
                                        </h2>
                                        <div id="collapseB1" class="accordion-collapse collapse show" data-bs-parent="#accordionCabangB">
                                            <div class="accordion-body pt-3">
                                                <form action="{{ route('roles.update', 'fa-cabang-jk1') }}" method="POST">
                                                    @csrf
                                                    @method('PUT')
                                                    
                                                    @include('master.role_permission.table_matrix', [
                                                        'branch' => 'JK1',
                                                        'role' => 'F&A',
                                                        'activePermissions' => $rolePermissions['fa_cabang_jk1']
                                                    ])

                                                    <div class="d-flex justify-content-end mt-3">
                                                        <button type="submit" class="btn btn-success btn-sm">
                                                            <i class="bi bi-save me-1"></i> Simpan Permission F&A (JK1)
                                                        </button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="accordion-item">
                                        <h2 class="accordion-header" id="headingB2">
                                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseB2">
                                                <i class="bi bi-box-seam me-2 text-success"></i> <strong>Logistik & Gudang - JK1</strong>
                                            </button>
                                        </h2>
                                        <div id="collapseB2" class="accordion-collapse collapse" data-bs-parent="#accordionCabangB">
                                            <div class="accordion-body pt-3">
                                                <form action="{{ route('roles.update', 'logistik-cabang-jk1') }}" method="POST">
                                                    @csrf
                                                    @method('PUT')
                                                    
                                                    @include('master.role_permission.table_matrix', [
                                                        'branch' => 'JK1',
                                                        'role' => 'Logistik',
                                                        'activePermissions' => $rolePermissions['logistik_cabang_jk1']
                                                    ])

                                                    <div class="d-flex justify-content-end mt-3">
                                                        <button type="submit" class="btn btn-success btn-sm">
                                                            <i class="bi bi-save me-1"></i> Simpan Permission Logistik (JK1)
                                                        </button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
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
    $(function () {
        $('.check-all-row').on('change', function() {
            var targetClass = $(this).data('target');
            $('.' + targetClass).prop('checked', $(this).prop('checked'));
        });
    });
</script>
@endpush

@endsection