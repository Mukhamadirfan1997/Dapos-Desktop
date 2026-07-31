@extends('dapos.layouts.app')

@section('title', 'Pembelajaran')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-2">
        <h5 class="mb-0"><i class="bi bi-journal-bookmark"></i> Data Pembelajaran</h5>
        <a href="{{ route('dapos.dapodik.import-pembelajaran') }}" class="btn btn-secondary btn-sm"
            onclick="return confirm('Import data Pembelajaran dari Dapodik?')">
            <i class="bi bi-cloud-download me-1"></i> Import Pembelajaran
        </a>
    </div>
    <div class="card-body">
        <form method="GET" class="row g-2 mb-3">
            <div class="col-md-4">
                <input type="text" name="q" class="form-control form-control-sm" placeholder="Cari mata pelajaran / rombel..." value="{{ request('q') }}">
            </div>
            <div class="col-md-3">
                <select name="rombel_id" class="form-select form-select-sm">
                    <option value="">Semua Rombel</option>
                    @foreach ($rombelList as $r)
                        <option value="{{ $r->id }}" {{ request('rombel_id') == $r->id ? 'selected' : '' }}>{{ $r->nama }}</option>
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
                        <th>Rombel</th>
                        <th>Tingkat</th>
                        <th>Tahun Ajaran</th>
                        <th>Mata Pelajaran</th>
                        <th>Guru</th>
                        <th>Jam Mengajar</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($pembelajaran as $p)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $p->rombel?->nama ?? '-' }}</td>
                        <td>{{ $p->rombel?->tingkat ?? '-' }}</td>
                        <td>{{ $p->rombel?->tahun_ajaran ?? '-' }}</td>
                        <td>{{ $p->mata_pelajaran }}</td>
                        <td>
                            @if ($p->nama_guru)
                                {{ $p->nama_guru }}
                            @else
                                <span class="text-muted">Tidak tersedia</span>
                            @endif
                        </td>
                        <td>{{ $p->jam_mengajar ?? '-' }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center text-muted py-3">Belum ada data pembelajaran. Klik "Import Pembelajaran" untuk mengambil dari Dapodik.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        {{ $pembelajaran->appends(request()->query())->links() }}
    </div>
</div>
@endsection
