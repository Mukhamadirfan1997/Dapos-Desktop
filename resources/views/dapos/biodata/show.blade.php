@extends('dapos.layouts.app')

@section('title', 'Detail Siswa - ' . $biodatum->nama)

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Detail Siswa</h5>
        <div class="d-flex gap-2">
            <a href="{{ route('dapos.biodata.edit', $biodatum) }}" class="btn btn-warning btn-sm"><i class="bi bi-pencil"></i> Edit</a>
            <a href="{{ route('dapos.biodata.index') }}" class="btn btn-secondary btn-sm">Kembali</a>
        </div>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-6">
                <h6 class="border-bottom pb-2">Data Pribadi</h6>
                <table class="table table-sm">
                    <tr><th style="width:180px">NISN</th><td>{{ $biodatum->nisn ?? '-' }}</td></tr>
                    <tr><th>NIK</th><td>{{ $biodatum->nik ?? '-' }}</td></tr>
                    <tr><th>No. KK</th><td>{{ $biodatum->no_kk ?? '-' }}</td></tr>
                    <tr><th>Nama Lengkap</th><td>{{ $biodatum->nama }}</td></tr>
                    <tr><th>Jenis Kelamin</th><td>{{ $biodatum->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan' }}</td></tr>
                    <tr><th>Tempat, Tgl Lahir</th><td>{{ $biodatum->tempat_lahir ?? '-' }}, {{ $biodatum->tanggal_lahir ? $biodatum->tanggal_lahir->format('d/m/Y') : '-' }}</td></tr>
                    <tr><th>Agama</th><td>{{ $biodatum->agama->nama ?? '-' }}</td></tr>
                    <tr><th>Kewarganegaraan</th><td>{{ $biodatum->kewarganegaraan ?? '-' }}</td></tr>
                    <tr><th>Reg. Akta Lahir</th><td>{{ $biodatum->reg_akta_lahir ?? '-' }}</td></tr>
                    <tr><th>Anak Ke-</th><td>{{ $biodatum->anak_keberapa ?? '-' }}</td></tr>
                    <tr><th>Kebutuhan Khusus</th><td>{{ $biodatum->kebutuhan_khusus ?? '-' }}</td></tr>
                    <tr><th>Sekolah Asal</th><td>{{ $biodatum->sekolah_asal ?? '-' }}</td></tr>
                </table>
            </div>
            <div class="col-md-6">
                <h6 class="border-bottom pb-2">Alamat & Kontak</h6>
                <table class="table table-sm">
                    <tr><th style="width:180px">Alamat</th><td>{{ $biodatum->alamat_jalan ?? '-' }}</td></tr>
                    <tr><th>RT/RW</th><td>{{ $biodatum->rt ?? '-' }}/{{ $biodatum->rw ?? '-' }}</td></tr>
                    <tr><th>Dusun</th><td>{{ $biodatum->nama_dusun ?? '-' }}</td></tr>
                    <tr><th>Desa/Kecamatan</th><td>{{ $biodatum->kodeWilayah->nama ?? '-' }}</td></tr>
                    <tr><th>Kode Pos</th><td>{{ $biodatum->kode_pos ?? '-' }}</td></tr>
                    <tr><th>Jenis Tinggal</th><td>{{ $biodatum->jenisTinggal->nama ?? '-' }}</td></tr>
                    <tr><th>Alat Transportasi</th><td>{{ $biodatum->alatTransportasi->nama ?? '-' }}</td></tr>
                    <tr><th>No. Telepon</th><td>{{ $biodatum->nomor_telepon_rumah ?? '-' }}</td></tr>
                    <tr><th>No. HP</th><td>{{ $biodatum->nomor_telepon_seluler ?? '-' }}</td></tr>
                    <tr><th>Email</th><td>{{ $biodatum->email ?? '-' }}</td></tr>
                    <tr><th>Lintang/Bujur</th><td>{{ $biodatum->lintang ?? '-' }} / {{ $biodatum->bujur ?? '-' }}</td></tr>
                </table>

                <h6 class="border-bottom pb-2 mt-3">Rombel Saat Ini</h6>
                <table class="table table-sm">
                    <tr><th style="width:180px">Rombel</th><td>{{ $biodatum->rombelAktif->nama ?? '-' }}</td></tr>
                    <tr><th>Tingkat</th><td>{{ $biodatum->rombelAktif->tingkat ?? '-' }}</td></tr>
                    <tr><th>Tahun Ajaran</th><td>{{ $biodatum->rombelAktif->tahun_ajaran ?? '-' }}</td></tr>
                </table>
            </div>
        </div>

        <div class="row mt-3">
            <div class="col-md-4">
                <h6 class="border-bottom pb-2">Data Ayah</h6>
                <table class="table table-sm">
                    <tr><th style="width:150px">Nama</th><td>{{ $biodatum->nama_ayah ?? '-' }}</td></tr>
                    <tr><th>NIK</th><td>{{ $biodatum->nik_ayah ?? '-' }}</td></tr>
                    <tr><th>Tahun Lahir</th><td>{{ $biodatum->tahun_lahir_ayah ?? '-' }}</td></tr>
                    <tr><th>Pendidikan</th><td>{{ $biodatum->jenjangPendidikanAyah->nama ?? '-' }}</td></tr>
                    <tr><th>Pekerjaan</th><td>{{ $biodatum->pekerjaanAyah->nama ?? '-' }}</td></tr>
                    <tr><th>Penghasilan</th><td>{{ $biodatum->penghasilanAyah->rentang ?? '-' }}</td></tr>
                </table>
            </div>
            <div class="col-md-4">
                <h6 class="border-bottom pb-2">Data Ibu</h6>
                <table class="table table-sm">
                    <tr><th style="width:150px">Nama</th><td>{{ $biodatum->nama_ibu_kandung ?? '-' }}</td></tr>
                    <tr><th>NIK</th><td>{{ $biodatum->nik_ibu ?? '-' }}</td></tr>
                    <tr><th>Tahun Lahir</th><td>{{ $biodatum->tahun_lahir_ibu ?? '-' }}</td></tr>
                    <tr><th>Pendidikan</th><td>{{ $biodatum->jenjangPendidikanIbu->nama ?? '-' }}</td></tr>
                    <tr><th>Pekerjaan</th><td>{{ $biodatum->pekerjaanIbu->nama ?? '-' }}</td></tr>
                    <tr><th>Penghasilan</th><td>{{ $biodatum->penghasilanIbu->rentang ?? '-' }}</td></tr>
                </table>
            </div>
            <div class="col-md-4">
                <h6 class="border-bottom pb-2">Data Wali</h6>
                <table class="table table-sm">
                    <tr><th style="width:150px">Nama</th><td>{{ $biodatum->nama_wali ?? '-' }}</td></tr>
                    <tr><th>NIK</th><td>{{ $biodatum->nik_wali ?? '-' }}</td></tr>
                    <tr><th>Tahun Lahir</th><td>{{ $biodatum->tahun_lahir_wali ?? '-' }}</td></tr>
                    <tr><th>Pendidikan</th><td>{{ $biodatum->jenjangPendidikanWali->nama ?? '-' }}</td></tr>
                    <tr><th>Pekerjaan</th><td>{{ $biodatum->pekerjaanWali->nama ?? '-' }}</td></tr>
                    <tr><th>Penghasilan</th><td>{{ $biodatum->penghasilanWali->rentang ?? '-' }}</td></tr>
                </table>
            </div>
        </div>

        @if ($biodatum->catatan)
        <div class="row mt-3">
            <div class="col-12">
                <h6 class="border-bottom pb-2">Catatan</h6>
                <p class="mb-0">{{ $biodatum->catatan }}</p>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection
