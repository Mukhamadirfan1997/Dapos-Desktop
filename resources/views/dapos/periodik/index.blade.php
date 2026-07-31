@extends('dapos.layouts.app')

@section('title', 'Data Periodik')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between flex-wrap gap-2">
        <h5 class="mb-0">Data Periodik Siswa</h5>
        <div class="d-flex gap-2 flex-wrap">
            <div class="dropdown">
                <button class="btn btn-outline-secondary btn-sm dropdown-toggle" data-bs-toggle="dropdown">
                    <i class="bi bi-download me-1"></i> Export
                </button>
                <ul class="dropdown-menu">
                    <li><a class="dropdown-item" href="{{ route('dapos.export.periodik-excel') }}"><i class="bi bi-file-earmark-excel me-1"></i> Excel</a></li>
                    <li><a class="dropdown-item" href="{{ route('dapos.export.periodik-pdf') }}"><i class="bi bi-file-earmark-pdf me-1"></i> PDF</a></li>
                </ul>
            </div>
            <a href="{{ route('dapos.dapodik.import') }}" class="btn btn-success btn-sm">
                <i class="bi bi-cloud-download me-1"></i> Import Dapodik
            </a>
            <a href="{{ route('dapos.dapodik.sync-all') }}" class="btn btn-info btn-sm"
                onclick="return confirm('Sinkron semua data periodik ke Dapodik?')">
                <i class="bi bi-cloud-arrow-up me-1"></i> Sync All
            </a>
            <a href="{{ route('dapos.periodik.create') }}" class="btn btn-primary btn-sm">
                <i class="bi bi-plus"></i> Tambah
            </a>
        </div>
    </div>
    <div class="card-body">
        <div class="border rounded p-3 mb-3 bg-light">
            <div class="d-flex flex-wrap gap-2 align-items-center justify-content-between">
                <div>
                    <strong><i class="bi bi-file-earmark-excel me-1"></i> Isi Cepat via Excel</strong>
                    <div class="text-muted small">Download file periodik, isi kolom Lingkar Kepala / Jarak Rumah / Waktu Tempuh / Jumlah Saudara di Excel, lalu upload kembali.</div>
                </div>
                <a href="{{ route('dapos.export.periodik-excel') }}" class="btn btn-outline-success btn-sm">
                    <i class="bi bi-download me-1"></i> Download Template
                </a>
            </div>
            <form action="{{ route('dapos.export.periodik-import') }}" method="POST" enctype="multipart/form-data" class="row g-2 mt-2 align-items-center">
                @csrf
                <div class="col-md-6">
                    <input type="file" name="file" class="form-control form-control-sm" accept=".xlsx,.xls" required>
                </div>
                <div class="col-md-3">
                    <button type="submit" class="btn btn-success btn-sm w-100">
                        <i class="bi bi-upload me-1"></i> Import Excel
                    </button>
                </div>
            </form>
            @if (session('success') || session('error') || session('warning'))
                <div class="alert alert-{{ session('error') ? 'danger' : (session('warning') ? 'warning' : 'success') }} alert-dismissible fade show mt-2 mb-0 py-2">
                    {{ session('error') ?? session('warning') ?? session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
        </div>
        <form method="GET" class="row g-2 mb-3">
            <div class="col-md-4">
                <input type="text" name="q" class="form-control form-control-sm" placeholder="Cari nama siswa..." value="{{ request('q') }}">
            </div>
            <div class="col-md-3">
                <select name="tahun_periodik" class="form-select form-select-sm">
                    <option value="">Semua Tahun</option>
                    @foreach ($tahunList as $t)
                        <option value="{{ $t }}" {{ request('tahun_periodik') == $t ? 'selected' : '' }}>{{ $t }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <select name="sync_status" class="form-select form-select-sm">
                    <option value="">Semua Status</option>
                    <option value="synced" {{ request('sync_status') == 'synced' ? 'selected' : '' }}>Synced</option>
                    <option value="failed" {{ request('sync_status') == 'failed' ? 'selected' : '' }}>Failed</option>
                    <option value="unsynced" {{ request('sync_status') == 'unsynced' ? 'selected' : '' }}>Unsynced</option>
                </select>
            </div>
            <div class="col-md-2">
                <button class="btn btn-primary btn-sm w-100"><i class="bi bi-search"></i> Cari</button>
            </div>
        </form>
        <div class="table-responsive">
            <table class="table table-hover datatable">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Siswa</th>
                        <th>Kelas</th>
                        <th>Tinggi</th>
                        <th>Berat</th>
                        <th>Lingkar Kepala</th>
                        <th>Jarak Rumah</th>
                        <th>Waktu Tempuh</th>
                        <th>Jumlah Saudara</th>
                        <th>Tahun</th>
                        <th>Sync</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($periodik as $p)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $p->siswa->nama ?? '-' }}</td>
                        <td>{{ $p->siswa->rombelAktif->nama ?? '-' }}</td>
                        <td>{{ $p->tinggi_badan ?? '-' }} cm</td>
                        <td>{{ $p->berat_badan ?? '-' }} kg</td>
                        <td>{{ $p->lingkar_kepala ?? '-' }} cm</td>
                        <td>{{ $p->jarak_rumah_sekolah ?? '-' }} m</td>
                        <td>{{ $p->waktu_tempuh ?? '-' }} mnt</td>
                        <td>{{ $p->jumlah_saudara_kandung ?? '-' }}</td>
                        <td>{{ $p->tahun_periodik }}</td>
                        <td>
                            @if ($p->sync_status === 'synced')
                                <span class="badge bg-success" title="Sync {{ $p->last_sync_at ? $p->last_sync_at->diffForHumans() : '' }}">
                                    <i class="bi bi-check-circle"></i> Synced
                                </span>
                            @elseif ($p->sync_status === 'failed')
                                <span class="badge bg-danger">
                                    <i class="bi bi-exclamation-circle"></i> Failed
                                </span>
                            @else
                                <span class="badge bg-secondary">
                                    <i class="bi bi-cloud-slash"></i> Unsynced
                                </span>
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('dapos.periodik.show', $p) }}" class="btn btn-info btn-sm" title="Detail">
                                <i class="bi bi-eye"></i>
                            </a>
                            <a href="{{ route('dapos.periodik.edit', $p) }}" class="btn btn-warning btn-sm" title="Edit">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <a href="{{ route('dapos.dapodik.sync-one', $p) }}" class="btn btn-info btn-sm" title="Sync ke Dapodik">
                                <i class="bi bi-cloud-arrow-up"></i>
                            </a>
                            <form action="{{ route('dapos.periodik.destroy', $p) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-danger btn-sm" title="Hapus"><i class="bi bi-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        {{ $periodik->appends(request()->query())->links() }}
    </div>
</div>
@endsection
