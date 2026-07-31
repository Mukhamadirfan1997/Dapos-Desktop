@extends('dapos.layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="mb-0"><i class="bi bi-speedometer2"></i> Dashboard</h4>
        <small class="text-muted">Rangkuman data peserta didik</small>
    </div>
    <a href="{{ route('dapos.export.siswa-per-rombel-excel') }}" class="btn btn-success btn-sm">
        <i class="bi bi-download me-1"></i> Export Excel
    </a>
</div>

<div class="row g-3 mb-4">
    <div class="col-6 col-md-4 col-xl-2">
        <div class="card border-start border-primary border-4 h-100">
            <div class="card-body d-flex align-items-center justify-content-between">
                <div>
                    <h6 class="card-title text-muted mb-1">Total Siswa</h6>
                    <h2 class="mb-0 text-primary">{{ $totalSiswa }}</h2>
                </div>
                <i class="bi bi-people fs-1 text-primary opacity-50"></i>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-4 col-xl-2">
        <div class="card border-start border-success border-4 h-100">
            <div class="card-body d-flex align-items-center justify-content-between">
                <div>
                    <h6 class="card-title text-muted mb-1">Siswa Aktif</h6>
                    <h2 class="mb-0 text-success">{{ $totalSiswaAktif }}</h2>
                </div>
                <i class="bi bi-person-check fs-1 text-success opacity-50"></i>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-4 col-xl-2">
        <div class="card border-start border-info border-4 h-100">
            <div class="card-body d-flex align-items-center justify-content-between">
                <div>
                    <h6 class="card-title text-muted mb-1">Total Rombel</h6>
                    <h2 class="mb-0 text-info">{{ $totalRombel }}</h2>
                </div>
                <i class="bi bi-layers fs-1 text-info opacity-50"></i>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-4 col-xl-2">
        <div class="card border-start border-warning border-4 h-100">
            <div class="card-body d-flex align-items-center justify-content-between">
                <div>
                    <h6 class="card-title text-muted mb-1">Total Surat</h6>
                    <h2 class="mb-0 text-warning">{{ $totalSurat }}</h2>
                </div>
                <i class="bi bi-envelope fs-1 text-warning opacity-50"></i>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-4 col-xl-2">
        <div class="card border-start border-secondary border-4 h-100">
            <div class="card-body d-flex align-items-center justify-content-between">
                <div>
                    <h6 class="card-title text-muted mb-1">Total Periodik</h6>
                    <h2 class="mb-0 text-secondary">{{ $totalPeriodik }}</h2>
                </div>
                <i class="bi bi-clipboard-data fs-1 text-secondary opacity-50"></i>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-4 col-xl-2">
        <div class="card border-start border-danger border-4 h-100">
            <div class="card-body d-flex align-items-center justify-content-between">
                <div>
                    <h6 class="card-title text-muted mb-1">Anggota Rombel</h6>
                    <h2 class="mb-0 text-danger">{{ $totalAnggotaRombel }}</h2>
                </div>
                <i class="bi bi-people-fill fs-1 text-danger opacity-50"></i>
            </div>
        </div>
    </div>
</div>

<div class="row g-3 mb-4">
    <div class="col-lg-6 col-xl-3">
        <div class="card h-100">
            <div class="card-header"><i class="bi bi-gender-ambiguous me-1"></i> Siswa per Jenis Kelamin</div>
            <div class="card-body">
                <canvas id="chartJk" height="200"></canvas>
            </div>
        </div>
    </div>
    <div class="col-lg-6 col-xl-3">
        <div class="card h-100">
            <div class="card-header"><i class="bi bi-diagram-3 me-1"></i> Siswa per Rombel</div>
            <div class="card-body">
                <canvas id="chartRombel" height="200"></canvas>
            </div>
        </div>
    </div>
    <div class="col-lg-6 col-xl-3">
        <div class="card h-100">
            <div class="card-header"><i class="bi bi-calendar-range me-1"></i> Siswa per Tahun Ajaran</div>
            <div class="card-body">
                <canvas id="chartTahun" height="200"></canvas>
            </div>
        </div>
    </div>
    <div class="col-lg-6 col-xl-3">
        <div class="card h-100">
            <div class="card-header"><i class="bi bi-clipboard-data me-1"></i> Data Periodik per Tahun</div>
            <div class="card-body">
                <canvas id="chartPeriodik" height="200"></canvas>
            </div>
        </div>
    </div>
</div>

<div class="row g-3 mb-4">
    <div class="col-md-6">
        <div class="card h-100">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span><i class="bi bi-diagram-3 me-1"></i> Siswa per Rombel</span>
                <a href="{{ route('dapos.export.siswa-per-rombel-excel') }}" class="btn btn-success btn-sm">
                    <i class="bi bi-download me-1"></i> Export
                </a>
            </div>
            <div class="card-body p-0">
                <table class="table table-hover align-middle mb-0">
                    <thead><tr><th>Rombel</th><th>Tingkat</th><th class="text-center">L</th><th class="text-center">P</th><th class="text-end">Total</th></tr></thead>
                    <tbody>
                        @forelse ($siswaPerRombel as $r)
                        <tr>
                            <td>{{ $r->nama }}</td>
                            <td>{{ $r->tingkat }}</td>
                            <td class="text-center">{{ $r->laki }}</td>
                            <td class="text-center">{{ $r->perempuan }}</td>
                            <td class="text-end"><strong>{{ $r->anggota_count }}</strong></td>
                        </tr>
                        @empty
                        <tr><td colspan="5" class="text-center text-muted py-3">Belum ada data</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card h-100">
            <div class="card-header"><i class="bi bi-bar-chart-steps me-1"></i> Siswa per Tingkat</div>
            <div class="card-body p-0">
                <table class="table table-hover align-middle mb-0">
                    <thead><tr><th>Tingkat</th><th class="text-end">Jumlah</th></tr></thead>
                    <tbody>
                        @forelse ($perTingkat as $t)
                        <tr><td>{{ $t->tingkat }}</td><td class="text-end"><strong>{{ $t->total }}</strong></td></tr>
                        @empty
                        <tr><td colspan="2" class="text-center text-muted py-3">Belum ada data</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span><i class="bi bi-clock-history me-1"></i> Siswa Terbaru</span>
        <a href="{{ route('dapos.biodata.index') }}" class="btn btn-sm btn-primary">Lihat Semua</a>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead>
                    <tr>
                        <th>NISN</th>
                        <th>Nama</th>
                        <th>JK</th>
                        <th>TTL</th>
                        <th>Dibuat</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($siswaTerbaru as $s)
                        <tr>
                            <td>{{ $s->nisn ?? '-' }}</td>
                            <td>{{ $s->nama }}</td>
                            <td>{{ $s->jenis_kelamin }}</td>
                            <td>{{ $s->tempat_lahir ? $s->tempat_lahir . ', ' . $s->tanggal_lahir->format('d/m/Y') : '-' }}</td>
                            <td>{{ $s->created_at->format('d/m/Y H:i') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted py-3">Belum ada data siswa.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
window.addEventListener('load', function() {
    if (!window.Chart) return;

    new Chart(document.getElementById('chartJk'), {
        type: 'doughnut',
        data: {
            labels: ['Laki-laki', 'Perempuan'],
            datasets: [{
                data: [{{ $siswaPerJk['L'] ?? 0 }}, {{ $siswaPerJk['P'] ?? 0 }}],
                backgroundColor: ['#0d6efd', '#d63384'],
            }]
        },
        options: { responsive: true, plugins: { legend: { position: 'bottom' } } }
    });

    new Chart(document.getElementById('chartRombel'), {
        type: 'bar',
        data: {
            labels: [@foreach($siswaPerRombel as $r) '{{ $r->nama }}', @endforeach],
            datasets: [{
                label: 'Jumlah Siswa',
                data: [@foreach($siswaPerRombel as $r) {{ $r->anggota_count }}, @endforeach],
                backgroundColor: '#0d6efd',
            }]
        },
        options: {
            responsive: true,
            scales: { y: { beginAtZero: true, ticks: { stepSize: 1 } } },
            plugins: { legend: { display: false } }
        }
    });

    new Chart(document.getElementById('chartTahun'), {
        type: 'bar',
        data: {
            labels: [@foreach($perTahunAjaran as $t) '{{ $t->tahun_ajaran }}', @endforeach],
            datasets: [{
                label: 'Jumlah Siswa',
                data: [@foreach($perTahunAjaran as $t) {{ $t->total }}, @endforeach],
                backgroundColor: '#198754',
            }]
        },
        options: {
            responsive: true,
            scales: { y: { beginAtZero: true, ticks: { stepSize: 1 } } },
            plugins: { legend: { display: false } }
        }
    });

    new Chart(document.getElementById('chartPeriodik'), {
        type: 'line',
        data: {
            labels: [@foreach($periodikPerTahun as $tahun => $count) '{{ $tahun }}', @endforeach],
            datasets: [{
                label: 'Jumlah Data Periodik',
                data: [@foreach($periodikPerTahun as $count) {{ $count }}, @endforeach],
                borderColor: '#198754',
                fill: true,
                tension: 0.3,
            }]
        },
        options: {
            responsive: true,
            scales: { y: { beginAtZero: true, ticks: { stepSize: 1 } } },
        }
    });
});
</script>
@endpush
