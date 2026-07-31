@extends('dapos.layouts.app')

@section('title', 'Detail Rombel - ' . $rombel->nama)

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between">
        <h5 class="mb-0">Rombel: {{ $rombel->nama }} ({{ $rombel->tingkat }})</h5>
        <a href="{{ route('dapos.rombel.index') }}" class="btn btn-secondary btn-sm">Kembali</a>
    </div>
    <div class="card-body">
        <div class="row mb-4">
            <div class="col-md-6">
                <table class="table table-sm">
                    <tr><th>Tahun Ajaran</th><td>{{ $rombel->tahun_ajaran }}</td></tr>
                    <tr><th>Wali Kelas</th><td>{{ $rombel->nama_wali_kelas ?? '-' }}</td></tr>
                    <tr><th>Jumlah Anggota</th><td>{{ $rombel->anggota_count ?? $rombel->anggota->count() }}</td></tr>
                </table>
            </div>
        </div>

        <h6 class="border-bottom pb-2">Tambah Anggota</h6>
        <form action="{{ route('dapos.rombel.add-siswa', $rombel) }}" method="POST" class="row g-2 mb-4">
            @csrf
            <div class="col-md-8">
                <select name="siswa_id" class="form-select select2" required>
                    <option value="">-- Pilih Siswa --</option>
                    @foreach ($siswaTersedia as $s)
                        <option value="{{ $s->id }}">
                            {{ $s->nama }} ({{ $s->nisn ?? '-' }}){{ $s->rombelAktif ? ' — ' . $s->rombelAktif->nama : '' }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-primary"><i class="bi bi-plus"></i> Tambah</button>
            </div>
        </form>

        <h6 class="border-bottom pb-2">Daftar Anggota</h6>
        <div class="table-responsive">
            <table class="table table-hover datatable">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama</th>
                        <th>NISN</th>
                        <th>NIK</th>
                        <th>JK</th>
                        <th>Tempat, Tgl Lahir</th>
                        <th>Agama</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($rombel->anggota as $a)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>
                            <a href="{{ route('dapos.biodata.show', $a->siswa) }}">{{ $a->siswa->nama }}</a>
                        </td>
                        <td>{{ $a->siswa->nisn ?? '-' }}</td>
                        <td>{{ $a->siswa->nik ?? '-' }}</td>
                        <td>{{ $a->siswa->jenis_kelamin }}</td>
                        <td>
                            {{ $a->siswa->tempat_lahir ?? '-' }},
                            {{ $a->siswa->tanggal_lahir ? $a->siswa->tanggal_lahir->format('d/m/Y') : '-' }}
                        </td>
                        <td>{{ $a->siswa->agama->nama ?? '-' }}</td>
                        <td>{{ $a->status_di_rombel }}</td>
                        <td>
                            <form action="{{ route('dapos.rombel.remove-siswa', [$rombel, $a]) }}" method="POST"
                                onsubmit="return confirm('Keluarkan {{ $a->siswa->nama }} dari rombel?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-danger btn-sm" title="Keluarkan"><i class="bi bi-x"></i></button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="9" class="text-center">Belum ada anggota.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
