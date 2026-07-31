@extends('dapos.layouts.app')

@section('title', 'Detail Registrasi - ' . $registrasi->siswa->nama ?? '-')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Detail Registrasi</h5>
        <a href="{{ route('dapos.registrasi.index') }}" class="btn btn-secondary btn-sm">Kembali</a>
    </div>
    <div class="card-body">
        <table class="table table-sm">
            <tr><th style="width:180px">Nama Siswa</th><td>{{ $registrasi->siswa->nama ?? '-' }}</td></tr>
            <tr><th>NIS</th><td>{{ $registrasi->nis ?? '-' }}</td></tr>
            <tr><th>Jenis Daftar</th><td>{{ $registrasi->jenisDaftar->nama ?? '-' }}</td></tr>
            <tr><th>Tanggal Masuk</th><td>{{ $registrasi->tanggal_masuk ? $registrasi->tanggal_masuk->format('d/m/Y') : '-' }}</td></tr>
            <tr><th>Tingkat Awal</th><td>{{ $registrasi->tingkat_awal ?? '-' }}</td></tr>
            <tr><th>No Peserta Ujian</th><td>{{ $registrasi->no_peserta_ujian ?? '-' }}</td></tr>
            <tr><th>No Seri Ijazah</th><td>{{ $registrasi->no_seri_ijazah ?? '-' }}</td></tr>
            <tr><th>No Seri SKHUN</th><td>{{ $registrasi->no_seri_skhun ?? '-' }}</td></tr>
            <tr><th>Sekolah Asal</th><td>{{ $registrasi->sekolah_asal ?? '-' }}</td></tr>
            <tr><th>Rombel Awal</th><td>{{ $registrasi->rombel_awal ?? '-' }}</td></tr>
        </table>
    </div>
</div>
@endsection
