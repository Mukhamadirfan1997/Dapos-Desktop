@extends('dapos.layouts.app')

@section('title', 'Detail Periodik - ' . ($periodik->siswa->nama ?? '-'))

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Detail Data Periodik</h5>
        <a href="{{ route('dapos.periodik.index') }}" class="btn btn-secondary btn-sm">Kembali</a>
    </div>
    <div class="card-body">
        <table class="table table-sm">
            <tr><th style="width:180px">Nama Siswa</th><td>{{ $periodik->siswa->nama ?? '-' }}</td></tr>
            <tr><th>NISN</th><td>{{ $periodik->siswa->nisn ?? '-' }}</td></tr>
            <tr><th>Tinggi Badan</th><td>{{ $periodik->tinggi_badan ?? '-' }} cm</td></tr>
            <tr><th>Berat Badan</th><td>{{ $periodik->berat_badan ?? '-' }} kg</td></tr>
            <tr><th>Lingkar Kepala</th><td>{{ $periodik->lingkar_kepala ?? '-' }}</td></tr>
            <tr><th>Jarak Rumah ke Sekolah</th><td>{{ $periodik->jarak_rumah_sekolah ?? '-' }} m</td></tr>
            <tr><th>Waktu Tempuh</th><td>{{ $periodik->waktu_tempuh ?? '-' }} menit</td></tr>
            <tr><th>Jumlah Saudara Kandung</th><td>{{ $periodik->jumlah_saudara_kandung ?? '-' }}</td></tr>
            <tr><th>Tahun Periodik</th><td>{{ $periodik->tahun_periodik }}</td></tr>
            <tr><th>Status Sync</th><td>
                @if ($periodik->sync_status === 'synced')
                    <span class="badge bg-success">Synced</span>
                @elseif ($periodik->sync_status === 'failed')
                    <span class="badge bg-danger">Failed</span>
                @else
                    <span class="badge bg-secondary">Unsynced</span>
                @endif
            </td></tr>
            @if ($periodik->last_sync_at)
                <tr><th>Terakhir Sync</th><td>{{ $periodik->last_sync_at->format('d/m/Y H:i') }}</td></tr>
            @endif
        </table>
    </div>
</div>
@endsection
