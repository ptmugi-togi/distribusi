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
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                @endif
                <div class="col-lg-12" style="padding:0px 10px 10px 0px;">
                    <button id="tambahUser" type="button" class="btn btn-success"data-bs-toggle="modal" data-bs-target="#mTUser"> Tambah</button>
                </div>

                <div style="overflow-y: scroll;">
                    <table id="myTable27" class="table table-striped nowrap" style="width:100%;">
                        <thead>
                          <tr>
                            <th>Nama Pengguna</th>
                            <th>Nama</th>
                            <th>Level</th>
                            <th>Cabang</th>
                            <th>Status</th>
                            {{-- <th>Online/Offline</th> --}}
                            <th>Aktifitas Terakhir</th>

                            <th></th>
                          </tr>
                        </thead>
                        <tbody>
                          @foreach ($users as $user)
                          <tr>
                            <td>{{ $user->username }}</td>
                            <td>{{ strtoupper($user->name) }}</td>
                            <td>{{ strtoupper($user->level) }}</td>
                            <td>{{ strtoupper($user->cabang) }}</td>
                            <td>{{ strtoupper($user->status) }}</td>
                            <td></td>

                            <td>
                                <a data-bs-toggle="modal" data-bs-target="#mLUser-{{ $user->id }}" class="badge bg-info">Ubah</a>
                                <form method="post" action="/register/{{ $user->id }}" class="d-inline">
                                  @csrf
                                  @method("delete")
                                  <button type="" class="border-0 badge bg-danger" onclick="return confirm('Apakah anda yakin?')">Hapus</button>
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


<div class="modal fade" id="mTUser" tabindex="-1">
    <div class="modal-dialog modal-dialog-scrollable">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Tambah Pengguna</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
        <form method="post" action="/register" class="row g-3">
            @csrf
            <div class="col-12">
                <label for="" class="col-sm-12 col-form-label">Nama Pengguna</label>
                <input type="text" class="form-control" id="username" name="username" required tabindex="1">
            </div>
            <div class="col-12">
                <label for="" class="col-sm-12 col-form-label">Nama</label>
                <input type="text" class="form-control" id="nama" name="name" required tabindex="2">
            </div>
            <div class="col-12">
                <label for="" class="col-sm-12 col-form-label">Kata Sandi</label>
                <input type="text" class="form-control" id="password" name="password" required min="5" tabindex="3">
            </div>
            <div class="col-12">
                <label for="" class="col-sm-12 col-form-label">Level</label>
                <select id="level" name="level" required class="form-control text-uppercase" tabindex="4">
                    <option value="STAF">STAF</option>
                    <option value="SUPERVISOR">SUPERVISOR</option>
                    <option value="MANAGER">MANAGER</option>
                </select>
            </div>
            <div class="col-12">
                <label for="" class="col-sm-12 col-form-label">Cabang</label>
                <select id="cabang" name="cabang" required class="form-control text-uppercase" tabindex="5">

                </select>
            </div>
            <div class="col-12">
                <label for="" class="col-sm-12 col-form-label">Status</label>
                <select id="status" name="status" required class="form-control text-uppercase" tabindex="6">
                    <option value="AKTIF">AKTIF</option>
                    <option value="TIDAK AKTIF">TIDAK AKTIF</option>
                    <option value="BLOKIR">BLOKIR</option>
                    <option value="MAINTENANCE">MAINTENANCE</option>
                </select>
            </div>
        </div>
        <div class="modal-footer">
            <button type="submit" class="btn btn-primary">Simpan</button>
        </form>
        </div>
      </div>
    </div>
</div>

@foreach($users as $user)
<div class="modal fade editUser" id="mLUser-{{ $user->id }}" tabindex="-1">
    <div class="modal-dialog modal-dialog-scrollable">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Ubah Pengguna</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
        <form method="post" action="/register/{{ $user->id }}" class="row g-3">
            @csrf
            <div class="col-12">
                <label for="" class="col-sm-12 col-form-label">Nama Pengguna</label>
                <input type="text" class="form-control" id="username_edit" name="username" readonly tabindex="1" value="{{ $user->username }}">
            </div>
            <div class="col-12">
                <label for="" class="col-sm-12 col-form-label">Nama</label>
                <input type="text" class="form-control" id="nama_edit" name="name" required tabindex="2" value="{{ $user->name }}">
            </div>
            <div class="col-12">
                <label for="" class="col-sm-12 col-form-label">Kata Sandi</label>
                <input type="text" class="form-control" id="password_edit" name="password" tabindex="3" placeholder="Silahkan isi jika ingin ubah">
            </div>
            <div class="col-12">
                <label for="" class="col-sm-12 col-form-label">Level</label>
                <select id="level_edit" name="level" required class="form-control text-uppercase" tabindex="4">
                    <option value="{{ $user->level }}" selected>{{ $user->level }}</option>
                    <option value="STAF">STAF</option>
                    <option value="SUPERVISOR">SUPERVISOR</option>
                    <option value="MANAGER">MANAGER</option>
                </select>
            </div>
            <div class="col-12">
                <label for="" class="col-sm-12 col-form-label">Cabang</label>
                <select id="cabang_edit" name="cabang" required class="form-control text-uppercase" tabindex="5">
                    @foreach($mbranch as $kk)
                        @if(old('cabang', $user->cabang) == $kk->braco)
                            <option value="{{ $kk->braco }}" selected>{{ $kk->brana }}</option>
                        @else
                            <option value="{{ $kk->braco }}">{{ $kk->brana }}</option>
                        @endif
                    @endforeach
                </select>
            </div>
            <div class="col-12">
                <label for="" class="col-sm-12 col-form-label">Status</label>
                <select id="status_edit" name="status" required class="form-control text-uppercase" tabindex="6">
                    <option value="{{ $user->status }}" selected>{{ $user->status }}</option>
                    <option value="AKTIF">AKTIF</option>
                    <option value="TIDAK AKTIF">TIDAK AKTIF</option>
                    <option value="BLOKIR">BLOKIR</option>
                    <option value="MAINTENANCE">MAINTENANCE</option>
                </select>
            </div>
        </div>
        <div class="modal-footer">
            <button type="submit" class="btn btn-primary">Simpan</button>
        </form>
        </div>
      </div>
    </div>
</div>
@endforeach

@endsection
