@extends('dapos.layouts.app')

@section('title', 'Daftar Siswa per Rombel')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
    <h5 class="mb-0">Daftar Siswa per Rombel</h5>
    <a href="{{ route('dapos.export.siswa-per-rombel-excel') }}" class="btn btn-success btn-sm">
        <i class="bi bi-download me-1"></i> Export Excel
    </a>
</div>

@forelse ($rombelList as $tingkat => $rombelGroup)
<div class="mb-4">
    <h6 class="text-secondary border-bottom pb-2">
        <i class="bi bi-bookmark me-1"></i> Tingkat {{ $tingkat }}
    </h6>
    @foreach ($rombelGroup as $rombel)
    <div class="card mb-3">
        <div class="card-header d-flex justify-content-between align-items-center py-2">
            <div>
                <strong>{{ $rombel->nama }}</strong>
                <span class="text-muted ms-2 small">{{ $rombel->tahun_ajaran }}</span>
            </div>
            <div class="small">
                @php
                    $l = $rombel->anggota->filter(fn($a) => $a->siswa->jenis_kelamin === 'L')->count();
                    $p = $rombel->anggota->filter(fn($a) => $a->siswa->jenis_kelamin === 'P')->count();
                @endphp
                <span class="text-primary me-2"><i class="bi bi-gender-male"></i> {{ $l }}</span>
                <span class="text-danger me-2"><i class="bi bi-gender-female"></i> {{ $p }}</span>
                <span class="fw-bold">Total: {{ $rombel->anggota_count }}</span>
            </div>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover table-sm mb-0 datatable">
                    <thead class="table-light">
                        <tr>
                            <th>No</th>
                            <th>NISN</th>
                            <th>NIK</th>
                            <th>Nama</th>
                            <th>JK</th>
                            <th>Tempat Lahir</th>
                            <th>Tgl Lahir</th>
                            <th>Agama</th>
                            <th>Anak Ke-</th>
                            <th>No. HP</th>
                            <th>Nama Ayah</th>
                            <th>Sekolah Asal</th>
                            <th>Tinggi</th>
                            <th>Berat</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($rombel->anggota as $a)
                        @php $p = $a->siswa->periodik->sortByDesc('tahun_periodik')->first(); @endphp
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $a->siswa->nisn ?? '-' }}</td>
                            <td>{{ $a->siswa->nik ?? '-' }}</td>
                            <td>
                                <a href="{{ route('dapos.biodata.show', $a->siswa) }}">{{ $a->siswa->nama }}</a>
                            </td>
                            <td>{{ $a->siswa->jenis_kelamin }}</td>
                            <td>{{ $a->siswa->tempat_lahir ?? '-' }}</td>
                            <td>{{ $a->siswa->tanggal_lahir ? $a->siswa->tanggal_lahir->format('d/m/Y') : '-' }}</td>
                            <td>{{ $a->siswa->agama->nama ?? '-' }}</td>
                            <td>{{ $a->siswa->anak_keberapa ?: '-' }}</td>
                            <td>{{ $a->siswa->nomor_telepon_seluler ?: '-' }}</td>
                            <td>{{ $a->siswa->nama_ayah ?: '-' }}</td>
                            <td>{{ $a->siswa->sekolah_asal ?: '-' }}</td>
                            <td>{{ $p?->tinggi_badan ? $p->tinggi_badan . ' cm' : '-' }}</td>
                            <td>{{ $p?->berat_badan ? $p->berat_badan . ' kg' : '-' }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @endforeach
</div>
@empty
<div class="alert alert-info">
    <i class="bi bi-info-circle me-1"></i> Belum ada data anggota rombel. Silakan import dari Dapodik terlebih dahulu.
</div>
@endforelse
@endsection
