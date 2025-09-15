@extends('layout.main')

@section('container')
  <main id="main" class="main">
    <div class="pagetitle">
        <h1>TPO</h1>
        <nav>
          <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/dashboard">Home</a></li>
            <li class="breadcrumb-item active">TPO</li>
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
                    <button id="tambahMssgrup" type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#mTMssgrup"> Tambah</button>
                </div>

                <table id="myTable" class="table table-striped nowrap" style="width:100%">
                  <thead>
                    <tr>
                      <th>pono</th>
                      <th>formc</th>
                      <th>podat</th>
                      <th>potype</th>
                      <th>topay</th>
                    </tr>
                  </thead>
                  <tbody>
                    @foreach ($tpohdr as $tpo)
                    <tr>
                      <td>{{ $tpo->pono }}</td>
                      <td>{{ $tpo->formc }}</td>
                      <td>{{ $tpo->podat }}</td>
                      <td>{{ $tpo->potype }}</td>
                      <td>{{ $tpo->topay }}</td>
                    </tr>
                    @endforeach
                  </tbody>
                </table>

              </div>
            </div>
          </div>
        </div>
    </section>
  </main>


@endsection