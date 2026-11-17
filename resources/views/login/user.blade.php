@extends('layout.main')
@section('container')

<main id="main" class="main">
    <div class="pagetitle">
        <h1>Pengguna</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item">MASTER</li>
                <li class="breadcrumb-item active">USER</li>
            </ol>
        </nav>
    </div>

    <section class="section">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        @if(session()->has('success'))
                        <div class="alert alert-success alert-dismissible fade show mt-3" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                        @endif

                        @if($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show mt-3" role="alert">
                            <ul class="mb-0">
                                @foreach($errors->all() as $err)
                                    <li>{{ $err }}</li>
                                @endforeach
                            </ul>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                        @endif

                        <div class="col-lg-12 pt-3 pb-2">
                            <button id="tambahUser" type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#mTUser">
                               Tambah
                            </button>
                        </div>

                        <div class="table-responsive">
                            <table id="myTable27" class="table table-striped nowrap" style="width:100%;">
                                <thead>
                                    <tr>
                                        <th>Nama Pengguna</th>
                                        <th>Nama</th>
                                        <th>Level</th>
                                        <th>Role</th>
                                        <th>Cabang</th>
                                        <th>Status</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($users as $user)
                                    <tr>
                                        <td>{{ $user->username }}</td>
                                        <td>{{ strtoupper($user->name) }}</td>
                                        <td>{{ strtoupper($user->level) }}</td>
                                        <td>{{ strtoupper($user->role ?? '-') }}</td>
                                        <td>{{ strtoupper($user->cabang) }}</td>
                                        <td>{{ strtoupper($user->status) }}</td>
                                        <td>
                                            <button type="button" data-bs-toggle="modal" data-bs-target="#mLUser-{{ $user->id }}" class="border-0 badge bg-info">Ubah</button>
                                            <form method="post" action="/register/{{ $user->id }}" class="d-inline">
                                                @csrf
                                                @method("delete")
                                                <button type="submit" class="border-0 badge bg-danger" onclick="return confirm('Apakah anda yakin?')">Hapus</button>
                                            </form>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>

<!-- Modal Tambah Pengguna -->
<div class="modal fade modal-user-select2" id="mTUser" tabindex="-1" data-bs-focus="false" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable">
        <form method="post" action="/register" class="modal-content">
            @csrf
            <div class="modal-header">
                <h5 class="modal-title">Tambah Pengguna</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row g-3">
                    <div class="col-12">
                        <label for="username" class="form-label">Nama Pengguna (untuk login)</label>
                        <input type="text" class="form-control" id="username" name="username" required tabindex="1">
                    </div>
                    <div class="col-12">
                        <label for="nama" class="form-label">Nama</label>
                        <input type="text" class="form-control" id="nama" name="name" required tabindex="2">
                    </div>
                    <div class="col-12">
                        <label for="password" class="form-label">Kata Sandi</label>
                        <div class="input-group">
                            <input type="password" class="form-control" id="password" name="password" required minlength="5" tabindex="3">
                            <button class="btn btn-outline-secondary" type="button" id="togglePassword" tabindex="-1">
                                <i class="bi bi-eye" id="togglePasswordIcon"></i>
                            </button>
                        </div>
                    </div>
                    <div class="col-12">
                        <label for="level" class="form-label">Level</label>
                        <select id="level" name="level" required class="form-select text-uppercase select2-modal" tabindex="4">
                            <option value="" disabled selected>Silahkan pilih level</option>
                            <option value="IT">IT</option>
                            <option value="STAF">STAF</option>
                            <option value="SUPERVISOR">SUPERVISOR</option>
                            <option value="MANAGER">MANAGER</option>
                        </select>
                    </div>
                    <div class="col-12">
                        <label for="role" class="form-label">Role</label>
                        <select id="role" name="role" required class="form-select text-uppercase select2-modal" tabindex="5">
                            <option value="" disabled selected>Silahkan pilih role</option>
                            <option value="IT">IT</option>
                            <option value="LOGISTIK">LOGISTIK</option>
                            <option value="F&A">F&A</option>
                            <option value="MARKETING">MARKETING</option>
                        </select>
                    </div>
                    <div class="col-12">
                        <label for="cabang" class="form-label">Cabang</label>
                        <select id="cabang" name="cabang" required class="form-select text-uppercase select2-modal" tabindex="6">
                            <option value="" disabled selected>Silahkan pilih cabang</option>
                            @foreach($mbranch as $kk)
                                <option value="{{ $kk->braco }}">{{ $kk->brana }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-12">
                        <label for="status" class="form-label">Status</label>
                        <select id="status" name="status" required class="form-select text-uppercase select2-modal" tabindex="7">
                            <option value="AKTIF" selected>AKTIF</option>
                            <option value="TIDAK AKTIF">TIDAK AKTIF</option>
                            <option value="BLOKIR">BLOKIR</option>
                            <option value="MAINTENANCE">MAINTENANCE</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="submit" class="btn btn-primary">Simpan</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Ubah Pengguna -->
@php
    $listLevel  = ['IT', 'STAF', 'SUPERVISOR', 'MANAGER'];
    $listRole   = ['IT', 'LOGISTIK', 'F&A', 'MARKETING'];
    $listStatus = ['AKTIF', 'TIDAK AKTIF', 'BLOKIR', 'MAINTENANCE'];
@endphp

@foreach($users as $user)
<div class="modal fade editUser modal-user-select2" id="mLUser-{{ $user->id }}" tabindex="-1" data-bs-focus="false" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable">
        <form method="post" action="/register/{{ $user->id }}" class="modal-content">
            @csrf
            <div class="modal-header">
                <h5 class="modal-title">Ubah Pengguna</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row g-3">
                    <div class="col-12">
                        <label for="username_edit_{{ $user->id }}" class="form-label">Nama Pengguna</label>
                        <input type="text" class="form-control" id="username_edit_{{ $user->id }}" name="username" readonly value="{{ $user->username }}">
                    </div>
                    <div class="col-12">
                        <label for="nama_edit_{{ $user->id }}" class="form-label">Nama</label>
                        <input type="text" class="form-control" id="nama_edit_{{ $user->id }}" name="name" required value="{{ $user->name }}">
                    </div>
                    <div class="col-12">
                        <label for="password_edit_{{ $user->id }}" class="form-label">Kata Sandi</label>
                        <input type="text" class="form-control" id="password_edit_{{ $user->id }}" name="password" placeholder="Silahkan isi jika ingin ubah">
                    </div>
                    <div class="col-12">
                        <label for="level_edit_{{ $user->id }}" class="form-label">Level</label>
                        <select id="level_edit_{{ $user->id }}" name="level" required class="form-select text-uppercase select2-modal">
                            @foreach($listLevel as $lvl)
                                <option value="{{ $lvl }}" {{ strtoupper($user->level) == $lvl ? 'selected' : '' }}>{{ $lvl }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-12">
                        <label for="role_edit_{{ $user->id }}" class="form-label">Role</label>
                        <select id="role_edit_{{ $user->id }}" name="role" required class="form-select text-uppercase select2-modal">
                            <option value="" disabled {{ empty($user->role) ? 'selected' : '' }}>Silahkan pilih role</option>
                            @foreach($listRole as $rl)
                                <option value="{{ $rl }}" {{ strtoupper((string) $user->role) == $rl ? 'selected' : '' }}>{{ $rl }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-12">
                        <label for="cabang_edit_{{ $user->id }}" class="form-label">Cabang</label>
                        <select id="cabang_edit_{{ $user->id }}" name="cabang" required class="form-select text-uppercase select2-modal">
                            @foreach($mbranch as $kk)
                                <option value="{{ $kk->braco }}" {{ $user->cabang == $kk->braco ? 'selected' : '' }}>{{ $kk->brana }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-12">
                        <label for="status_edit_{{ $user->id }}" class="form-label">Status</label>
                        <select id="status_edit_{{ $user->id }}" name="status" required class="form-select text-uppercase select2-modal">
                            @foreach($listStatus as $st)
                                <option value="{{ $st }}" {{ strtoupper($user->status) == $st ? 'selected' : '' }}>{{ $st }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="submit" class="btn btn-primary">Simpan</button>
            </div>
        </form>
    </div>
</div>
@endforeach

<script>
    // Toggle Show/Hide Password
    document.getElementById('togglePassword').addEventListener('click', function () {
        const passwordInput = document.getElementById('password');
        const icon = document.getElementById('togglePasswordIcon');

        if (passwordInput.type === 'password') {
            passwordInput.type = 'text';
            icon.classList.remove('bi-eye');
            icon.classList.add('bi-eye-slash');
        } else {
            passwordInput.type = 'password';
            icon.classList.remove('bi-eye-slash');
            icon.classList.add('bi-eye');
        }
    });

    // Inisialisasi Select2 di dalam Modal Tambah & Edit User
    window.addEventListener('load', function () {
        function initUserModalSelect2() {
            $('.modal-user-select2').on('shown.bs.modal', function () {
                const $modal = $(this);
                $modal.find('.select2-modal').each(function () {
                    if (!$(this).hasClass('select2-hidden-accessible')) {
                        $(this).select2({
                            theme: 'bootstrap-5',
                            dropdownParent: $modal,
                            width: '100%',
                            minimumResultsForSearch: 0
                        });
                    }
                });
            });

            $(document).on('select2:open', function () {
                const searchField = document.querySelector('.select2-container--open .select2-search__field');
                if (searchField) {
                    searchField.focus();
                }
            });
        }

        if (typeof $.fn.select2 === 'undefined') {
            $.getScript('https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js', function () {
                initUserModalSelect2();
            });
        } else {
            initUserModalSelect2();
        }
    });
</script>

@endsection