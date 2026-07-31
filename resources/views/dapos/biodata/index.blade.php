@extends('dapos.layouts.app')

@section('title', 'Biodata Siswa')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-2">
        <h5 class="mb-0">Data Biodata Siswa</h5>
        <div class="d-flex gap-2">
            <div class="dropdown">
                <button class="btn btn-outline-secondary btn-sm dropdown-toggle" data-bs-toggle="dropdown">
                    <i class="bi bi-download me-1"></i> Export
                </button>
                <ul class="dropdown-menu">
                    <li><a class="dropdown-item" href="{{ route('dapos.export.siswa-excel') }}"><i class="bi bi-file-earmark-excel me-1"></i> Excel</a></li>
                    <li><a class="dropdown-item" href="{{ route('dapos.export.siswa-pdf') }}"><i class="bi bi-file-earmark-pdf me-1"></i> PDF</a></li>
                </ul>
            </div>
            <a href="{{ route('dapos.biodata.trash') }}" class="btn btn-outline-danger btn-sm">
                <i class="bi bi-trash"></i> Sampah
            </a>
            <a href="{{ route('dapos.biodata.create') }}" class="btn btn-primary btn-sm">
                <i class="bi bi-plus"></i> Tambah Siswa
            </a>
        </div>
    </div>
    <div class="card-body">
        <form method="GET" class="row g-2 mb-3">
            <div class="col-md-3">
                <input type="text" name="q" class="form-control form-control-sm" placeholder="Cari nama, NISN, NIK..." value="{{ request('q') }}">
            </div>
            <div class="col-md-2">
                <select name="jenis_kelamin" class="form-select form-select-sm">
                    <option value="">Semua JK</option>
                    <option value="L" {{ request('jenis_kelamin') == 'L' ? 'selected' : '' }}>Laki-laki</option>
                    <option value="P" {{ request('jenis_kelamin') == 'P' ? 'selected' : '' }}>Perempuan</option>
                </select>
            </div>
            <div class="col-md-2">
                <select name="agama_id" class="form-select form-select-sm">
                    <option value="">Semua Agama</option>
                    @foreach ($agamaList as $a)
                        <option value="{{ $a->id }}" {{ request('agama_id') == $a->id ? 'selected' : '' }}>{{ $a->nama }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <select name="rombel_id" class="form-select form-select-sm">
                    <option value="">Semua Rombel</option>
                    @foreach ($rombelList as $r)
                        <option value="{{ $r->id }}" {{ request('rombel_id') == $r->id ? 'selected' : '' }}>{{ $r->tingkat }} - {{ $r->nama }} ({{ $r->tahun_ajaran }})</option>
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
                    <tr>
                        <th>No</th>
                        <th>NISN</th>
                        <th>NIK</th>
                        <th>Nama</th>
                        <th>JK</th>
                        <th>Rombel</th>
                        <th>Tempat Lahir</th>
                        <th>Tgl Lahir</th>
                        <th>Agama</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($siswa as $s)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $s->nisn ?? '-' }}</td>
                            <td>{{ $s->nik ?? '-' }}</td>
                            <td>{{ $s->nama }}</td>
                            <td>{{ $s->jenis_kelamin }}</td>
                            <td>{{ $s->rombelAktif->nama ?? '-' }}</td>
                            <td>{{ $s->tempat_lahir ?? '-' }}</td>
                            <td>{{ $s->tanggal_lahir ? $s->tanggal_lahir->format('d/m/Y') : '-' }}</td>
                            <td>{{ $s->agama->nama ?? '-' }}</td>
                            <td>
                                <a href="{{ route('dapos.biodata.show', $s) }}" class="btn btn-info btn-sm" title="Detail">
                                    <i class="bi bi-eye"></i>
                                </a>
                                <a href="{{ route('dapos.biodata.edit', $s) }}" class="btn btn-warning btn-sm" title="Edit">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <form action="{{ route('dapos.biodata.destroy', $s) }}" method="POST" class="d-inline"
                                    onsubmit="return confirm('Hapus data siswa {{ $s->nama }}?')">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-danger btn-sm" title="Hapus">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="mt-3">
            {{ $siswa->appends(request()->query())->links() }}
        </div>
    </div>
</div>
@endsection
