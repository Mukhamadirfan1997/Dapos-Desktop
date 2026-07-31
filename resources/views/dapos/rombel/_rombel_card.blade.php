@php
    $l = $rombel->anggota->filter(fn($a) => $a->siswa->jenis_kelamin === 'L')->count();
    $p = $rombel->anggota->filter(fn($a) => $a->siswa->jenis_kelamin === 'P')->count();
@endphp
<div class="card shadow-sm mb-3 border-0">
    <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-2 py-2 bg-white">
        <div class="d-flex align-items-center gap-2">
            <span class="rounded-2 text-white d-flex align-items-center justify-content-center"
                style="width: 38px; height: 38px; background: {{ $colors[$cardIndex % count($colors)] ?? '#0d6efd' }};">
                <i class="bi bi-people-fill"></i>
            </span>
            <div>
                <div class="fw-semibold lh-1">{{ $rombel->nama }}</div>
                <div class="small text-muted">
                    {{ $rombel->tahun_ajaran }}
                    @if ($rombel->nama_wali_kelas)
                        &middot; Wali: <span class="text-dark">{{ $rombel->nama_wali_kelas }}</span>
                    @endif
                </div>
            </div>
        </div>
        <div class="d-flex align-items-center gap-2 small">
            <span class="badge text-bg-light border"><i class="bi bi-gender-male text-primary me-1"></i>{{ $l }}</span>
            <span class="badge text-bg-light border"><i class="bi bi-gender-female text-danger me-1"></i>{{ $p }}</span>
            <span class="badge text-bg-primary"><i class="bi bi-people me-1"></i>{{ $rombel->anggota_count }}</span>
        </div>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover table-sm mb-0 daftar-siswa-table align-middle">
                <thead class="table-light small text-uppercase text-muted">
                    <tr>
                        <th class="ps-3">No</th>
                        <th>NISN</th>
                        <th>Nama</th>
                        <th>JK</th>
                        <th>Tempat Lahir</th>
                        <th>Tgl Lahir</th>
                        <th>Agama</th>
                        <th>No. HP</th>
                        <th>Nama Ayah</th>
                        <th>Sekolah Asal</th>
                        <th>Tinggi</th>
                        <th>Berat</th>
                        <th class="pe-3"></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($rombel->anggota as $a)
                    @php $p = $a->siswa->periodik->sortByDesc('tahun_periodik')->first(); @endphp
                    <tr>
                        <td class="ps-3 text-muted">{{ $loop->iteration }}</td>
                        <td>{{ $a->siswa->nisn ?? '-' }}</td>
                        <td class="fw-semibold">{{ $a->siswa->nama }}</td>
                        <td>
                            <span class="badge {{ $a->siswa->jenis_kelamin === 'L' ? 'text-bg-primary' : 'text-bg-danger' }}">
                                {{ $a->siswa->jenis_kelamin }}
                            </span>
                        </td>
                        <td>{{ $a->siswa->tempat_lahir ?? '-' }}</td>
                        <td>{{ $a->siswa->tanggal_lahir ? $a->siswa->tanggal_lahir->format('d/m/Y') : '-' }}</td>
                        <td>{{ $a->siswa->agama->nama ?? '-' }}</td>
                        <td>{{ $a->siswa->nomor_telepon_seluler ?: '-' }}</td>
                        <td>{{ $a->siswa->nama_ayah ?: '-' }}</td>
                        <td>{{ $a->siswa->sekolah_asal ?: '-' }}</td>
                        <td>{{ $p?->tinggi_badan ? $p->tinggi_badan . ' cm' : '-' }}</td>
                        <td>{{ $p?->berat_badan ? $p->berat_badan . ' kg' : '-' }}</td>
                        <td class="pe-3 text-end">
                            <a href="{{ route('dapos.biodata.show', $a->siswa) }}" class="btn btn-outline-primary btn-sm"
                                title="Lihat biodata"><i class="bi bi-eye"></i></a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
