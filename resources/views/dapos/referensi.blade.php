@extends('dapos.layouts.app')

@section('title', 'Referensi')

@section('content')
<div class="row">
    <div class="col-md-6 mb-4">
        <div class="card">
            <div class="card-header"><h5 class="mb-0">Agama</h5></div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-sm table-hover">
                        <thead><tr><th>ID</th><th>Nama</th></tr></thead>
                        <tbody>
                            @foreach ($agama as $a)
                            <tr><td>{{ $a->id }}</td><td>{{ $a->nama }}</td></tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6 mb-4">
        <div class="card">
            <div class="card-header"><h5 class="mb-0">Pekerjaan</h5></div>
            <div class="card-body">
                <div class="table-responsive" style="max-height:300px">
                    <table class="table table-sm table-hover">
                        <thead><tr><th>ID</th><th>Nama</th></tr></thead>
                        <tbody>
                            @foreach ($pekerjaan as $p)
                            <tr><td>{{ $p->id }}</td><td>{{ $p->nama }}</td></tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6 mb-4">
        <div class="card">
            <div class="card-header"><h5 class="mb-0">Penghasilan</h5></div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-sm table-hover">
                        <thead><tr><th>ID</th><th>Rentang</th></tr></thead>
                        <tbody>
                            @foreach ($penghasilan as $p)
                            <tr><td>{{ $p->id }}</td><td>{{ $p->rentang }}</td></tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6 mb-4">
        <div class="card">
            <div class="card-header"><h5 class="mb-0">Jenis Daftar</h5></div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-sm table-hover">
                        <thead><tr><th>ID</th><th>Nama</th></tr></thead>
                        <tbody>
                            @foreach ($jenisDaftar as $j)
                            <tr><td>{{ $j->id }}</td><td>{{ $j->nama }}</td></tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
