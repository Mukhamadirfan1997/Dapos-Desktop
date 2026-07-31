@extends('dapos.layouts.app')

@section('title', 'Registrasi Siswa')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-2">
        <h5 class="mb-0">Data Registrasi Siswa</h5>
        <div class="d-flex gap-2">
            <div class="dropdown">
                <button class="btn btn-outline-secondary btn-sm dropdown-toggle" data-bs-toggle="dropdown">
                    <i class="bi bi-download me-1"></i> Export
                </button>
                <ul class="dropdown-menu">
                    <li><a class="dropdown-item" href="{{ route('dapos.export.registrasi-excel') }}"><i class="bi bi-file-earmark-excel me-1"></i> Excel</a></li>
                    <li><a class="dropdown-item" href="{{ route('dapos.export.registrasi-pdf') }}"><i class="bi bi-file-earmark-pdf me-1"></i> PDF</a></li>
                </ul>
            </div>
            <a href="{{ route('dapos.registrasi.create') }}" class="btn btn-primary btn-sm"><i class="bi bi-plus"></i> Tambah</a>
        </div>
    </div>
    <div class="card-body">
        <form method="GET" class="row g-2 mb-3">
            <div class="col-md-5">
                <input type="text" name="q" class="form-control form-control-sm" placeholder="Cari nama siswa..." value="{{ request('q') }}">
            </div>
            <div class="col-md-3">
                <select name="jenis_daftar_id" class="form-select form-select-sm">
                    <option value="">Semua Jenis Daftar</option>
                    @foreach ($jenisDaftar as $j)
                        <option value="{{ $j->id }}" {{ request('jenis_daftar_id') == $j->id ? 'selected' : '' }}>{{ $j->nama }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <select name="tingkat_awal" class="form-select form-select-sm">
                    <option value="">Semua Tingkat</option>
                    @for ($i = 1; $i <= 13; $i++)
                        <option value="{{ $i }}" {{ request('tingkat_awal') == $i ? 'selected' : '' }}>{{ $i }}</option>
                    @endfor
                </select>
            </div>
            <div class="col-md-2">
                <button class="btn btn-primary btn-sm w-100"><i class="bi bi-search"></i> Cari</button>
            </div>
        </form>
        <div class="table-responsive">
            <table class="table table-hover datatable">
                <thead>
                    <tr><th>No</th><th>Nama Siswa</th><th>NIS</th><th>Jenis Daftar</th><th>Tgl Masuk</th><th>Tingkat</th><th>Aksi</th></tr>
                </thead>
                <tbody>
                    @foreach ($registrasi as $r)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $r->siswa->nama ?? '-' }}</td>
                        <td>{{ $r->nis ?? '-' }}</td>
                        <td>{{ $r->jenisDaftar->nama ?? '-' }}</td>
                        <td>{{ $r->tanggal_masuk ? $r->tanggal_masuk->format('d/m/Y') : '-' }}</td>
                        <td>{{ $r->tingkat_awal ?? '-' }}</td>
                        <td>
                            <a href="{{ route('dapos.registrasi.show', $r) }}" class="btn btn-info btn-sm" title="Detail"><i class="bi bi-eye"></i></a>
                            <a href="{{ route('dapos.registrasi.edit', $r) }}" class="btn btn-warning btn-sm" title="Edit"><i class="bi bi-pencil"></i></a>
                            <form action="{{ route('dapos.registrasi.destroy', $r) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-danger btn-sm" title="Hapus"><i class="bi bi-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        {{ $registrasi->appends(request()->query())->links() }}
    </div>
</div>
@endsection
