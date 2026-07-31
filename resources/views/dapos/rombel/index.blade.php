@extends('dapos.layouts.app')

@section('title', 'Rombel')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-2">
        <h5 class="mb-0">Data Rombel</h5>
        <div class="d-flex gap-2">
            <div class="dropdown">
                <button class="btn btn-outline-secondary btn-sm dropdown-toggle" data-bs-toggle="dropdown">
                    <i class="bi bi-download me-1"></i> Export
                </button>
                <ul class="dropdown-menu">
                    <li><a class="dropdown-item" href="{{ route('dapos.export.siswa-per-rombel-excel') }}"><i class="bi bi-file-earmark-excel me-1"></i> Siswa per Rombel (Excel)</a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li><a class="dropdown-item" href="{{ route('dapos.export.rombel-excel') }}"><i class="bi bi-file-earmark-excel me-1"></i> Rombel (Excel)</a></li>
                    <li><a class="dropdown-item" href="{{ route('dapos.export.rombel-pdf') }}"><i class="bi bi-file-earmark-pdf me-1"></i> Rombel (PDF)</a></li>
                </ul>
            </div>
            <a href="{{ route('dapos.rombel.create') }}" class="btn btn-primary btn-sm"><i class="bi bi-plus"></i> Tambah</a>
        </div>
    </div>
    <div class="card-body">
        <form method="GET" class="row g-2 mb-3">
            <div class="col-md-4">
                <input type="text" name="q" class="form-control form-control-sm" placeholder="Cari nama rombel..." value="{{ request('q') }}">
            </div>
            <div class="col-md-3">
                <select name="tingkat" class="form-select form-select-sm">
                    <option value="">Semua Tingkat</option>
                    @foreach ($tingkatList as $t)
                        <option value="{{ $t }}" {{ request('tingkat') == $t ? 'selected' : '' }}>{{ $t }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <select name="tahun_ajaran" class="form-select form-select-sm">
                    <option value="">Semua Tahun Ajaran</option>
                    @foreach ($tahunAjaranList as $ta)
                        <option value="{{ $ta }}" {{ request('tahun_ajaran') == $ta ? 'selected' : '' }}>{{ $ta }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <button class="btn btn-primary btn-sm w-100"><i class="bi bi-search"></i> Cari</button>
            </div>
        </form>
        <div class="table-responsive">
            <table class="table table-hover datatable">
                <thead>
                    <tr><th>No</th><th>Nama Rombel</th><th>Tingkat</th><th>Tahun Ajaran</th><th>Wali Kelas</th><th>Anggota</th><th>Aksi</th></tr>
                </thead>
                <tbody>
                    @foreach ($rombel as $r)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $r->nama }}</td>
                        <td>{{ $r->tingkat }}</td>
                        <td>{{ $r->tahun_ajaran }}</td>
                        <td>{{ $r->nama_wali_kelas ?? '-' }}</td>
                        <td>{{ $r->anggota_count }}</td>
                        <td>
                            <a href="{{ route('dapos.rombel.show', $r) }}" class="btn btn-info btn-sm" title="Detail"><i class="bi bi-eye"></i></a>
                            <a href="{{ route('dapos.rombel.edit', $r) }}" class="btn btn-warning btn-sm" title="Edit"><i class="bi bi-pencil"></i></a>
                            <form action="{{ route('dapos.rombel.destroy', $r) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus rombel?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-danger btn-sm" title="Hapus"><i class="bi bi-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        {{ $rombel->appends(request()->query())->links() }}
    </div>
</div>
@endsection
