@extends('dapos.layouts.app')

@section('title', 'Rekap Jam Mengajar Guru')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between flex-wrap gap-2">
        <h5 class="mb-0">Rekap Jam Mengajar Guru</h5>
        <div class="d-flex gap-2">
            <a href="{{ route('dapos.rekap-jam-mengajar.excel') }}" class="btn btn-success btn-sm">
                <i class="bi bi-file-earmark-excel me-1"></i> Excel
            </a>
            <a href="{{ route('dapos.rekap-jam-mengajar.pdf') }}" class="btn btn-danger btn-sm">
                <i class="bi bi-file-earmark-pdf me-1"></i> PDF
            </a>
        </div>
    </div>
    <div class="card-body">
        <div class="row g-3 mb-4">
            <div class="col-6 col-md-3">
                <div class="border rounded p-3 text-center bg-light">
                    <div class="fs-3 fw-bold">{{ $totalGuru }}</div>
                    <div class="text-muted small">Total Guru</div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="border rounded p-3 text-center bg-success-subtle">
                    <div class="fs-3 fw-bold text-success">{{ $guruSesuai }}</div>
                    <div class="text-muted small">Sesuai (24-40 JP)</div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="border rounded p-3 text-center bg-warning-subtle">
                    <div class="fs-3 fw-bold text-warning">{{ $guruKurang }}</div>
                    <div class="text-muted small">Kurang (&lt; 24 JP)</div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="border rounded p-3 text-center bg-danger-subtle">
                    <div class="fs-3 fw-bold text-danger">{{ $guruLebih }}</div>
                    <div class="text-muted small">Lebih (&gt; 40 JP)</div>
                </div>
            </div>
        </div>

        <div class="alert alert-info py-2 small">
            <i class="bi bi-info-circle me-1"></i>
            <strong>Aturan Dapodik (Permendikbud 15/2018):</strong>
            beban mengajar guru <strong>24&ndash;40 JP/minggu</strong>;
            per rombel SD: Guru Kelas <strong>24 JP</strong>, PJOK <strong>4 JP</strong>, Agama <strong>4 JP</strong>;
            satu guru kelas hanya boleh mengampu satu rombel.
        </div>

        <h6 class="mt-4"><i class="bi bi-people me-1"></i> Per Guru</h6>
        <div class="table-responsive">
            <table class="table table-hover datatable">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Guru</th>
                        <th>Jumlah Mapel</th>
                        <th>Rincian (Rombel - Mapel - JP)</th>
                        <th>Total JJM</th>
                        <th>Status</th>
                        <th>Guru Kelas Rangkap</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($daftarGuru as $i => $g)
                    <tr>
                        <td>{{ $i + 1 }}</td>
                        <td>{{ $g['nama'] }}</td>
                        <td>{{ $g['jml_mapel'] }}</td>
                        <td>
                            @foreach ($g['rincian'] as $rc)
                                <span class="badge bg-light text-dark border me-1">{{ $rc['rombel'] }} - {{ $rc['mapel'] }}: {{ $rc['jam'] }} JP</span>
                            @endforeach
                        </td>
                        <td class="fw-bold">{{ $g['total_jam'] }}</td>
                        <td>
                            @if ($g['status'] === 'sesuai')
                                <span class="badge bg-success">Sesuai</span>
                            @elseif ($g['status'] === 'kurang')
                                <span class="badge bg-warning text-dark">Kurang</span>
                            @else
                                <span class="badge bg-danger">Lebih</span>
                            @endif
                        </td>
                        <td>
                            @if (empty($g['rangkap']))
                                <span class="text-muted">-</span>
                            @else
                                <span class="badge bg-danger">{{ implode(', ', $g['rangkap']) }}</span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <h6 class="mt-4"><i class="bi bi-layers me-1"></i> Per Rombel</h6>
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Rombel</th>
                        <th>Guru Kelas (JP)</th>
                        <th>PJOK (JP)</th>
                        <th>Agama (JP)</th>
                        <th>Total JP</th>
                        <th>Catatan</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($daftarRombel as $i => $r)
                    <tr>
                        <td>{{ $i + 1 }}</td>
                        <td class="fw-bold">{{ $r['nama'] }}</td>
                        <td>
                            @if ($r['guru_kelas'])
                                {{ $r['guru_kelas']['nama'] }} ({{ $r['guru_kelas']['jam'] }})
                            @else
                                <span class="badge bg-danger">tidak ada</span>
                            @endif
                        </td>
                        <td>
                            @if ($r['pjok'])
                                {{ $r['pjok']['nama'] }} ({{ $r['pjok']['jam'] }})
                            @else
                                <span class="badge bg-danger">tidak ada</span>
                            @endif
                        </td>
                        <td>
                            @if ($r['agama'])
                                {{ $r['agama']['nama'] }} ({{ $r['agama']['jam'] }})
                            @else
                                <span class="badge bg-danger">tidak ada</span>
                            @endif
                        </td>
                        <td class="fw-bold">{{ $r['total_jam'] }}</td>
                        <td>
                            @if (empty($r['masalah']))
                                <span class="badge bg-success">OK</span>
                            @else
                                @foreach ($r['masalah'] as $m)
                                    <span class="badge bg-warning text-dark me-1">{{ $m }}</span>
                                @endforeach
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
