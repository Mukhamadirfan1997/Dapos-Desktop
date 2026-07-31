@extends('dapos.layouts.app')

@section('title', 'Tempat Sampah - Biodata')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Tempat Sampah</h5>
        <a href="{{ route('dapos.biodata.index') }}" class="btn btn-secondary btn-sm">Kembali</a>
    </div>
    <div class="card-body">
        <form method="GET" class="row g-2 mb-3">
            <div class="col-md-10">
                <input type="text" name="q" class="form-control form-control-sm" placeholder="Cari nama atau NISN..." value="{{ request('q') }}">
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
                        <th>Nama</th>
                        <th>JK</th>
                        <th>Dihapus</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($siswa as $s)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $s->nisn ?? '-' }}</td>
                            <td>{{ $s->nama }}</td>
                            <td>{{ $s->jenis_kelamin }}</td>
                            <td>{{ $s->deleted_at ? $s->deleted_at->format('d/m/Y H:i') : '-' }}</td>
                            <td>
                                <a href="{{ route('dapos.biodata.restore', $s->id) }}" class="btn btn-success btn-sm"
                                    onclick="return confirm('Restore data {{ $s->nama }}?')">
                                    <i class="bi bi-arrow-counterclockwise"></i> Restore
                                </a>
                                <a href="{{ route('dapos.biodata.force-delete', $s->id) }}" class="btn btn-danger btn-sm"
                                    onclick="return confirm('Hapus PERMANEN data {{ $s->nama }}? Data tidak bisa dikembalikan!')">
                                    <i class="bi bi-trash"></i> Hapus Permanen
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        {{ $siswa->appends(request()->query())->links() }}
    </div>
</div>
@endsection
