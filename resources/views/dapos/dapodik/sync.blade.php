@extends('dapos.layouts.app')

@section('title', 'Sinkron ke Dapodik')

@section('content')
@if (!$config || !$config->token)
<div class="alert alert-warning">
    <i class="bi bi-exclamation-triangle me-1"></i>
    Konfigurasi Dapodik belum diatur.
    <a href="{{ route('dapos.dapodik.setting') }}" class="alert-link">Atur sekarang</a>
</div>
@else
<div class="card mb-4">
    <div class="card-header">
        <h5 class="mb-0">
            <i class="bi bi-cloud-arrow-up me-2"></i>Sinkron ke Dapodik
        </h5>
    </div>
    <div class="card-body">
        <p class="text-muted">
            Sinkronkan data yang diubah di aplikasi ini kembali ke Dapodik.
            Data dikirim bertahap (10 data per permintaan) agar tidak membebani server Dapodik.
        </p>
        <div class="row g-4">
            <div class="col-md-6">
                <div class="card h-100 border-primary">
                    <div class="card-body">
                        <div class="d-flex align-items-center mb-2">
                            <i class="bi bi-person-badge fs-3 text-primary me-2"></i>
                            <div>
                                <h6 class="mb-0">Periodik (Tinggi & Berat Badan)</h6>
                                <small class="text-muted">Tinggi, berat, lingkar kepala, jarak & waktu tempuh, jumlah saudara</small>
                            </div>
                        </div>
                        <div class="d-flex justify-content-between align-items-center mt-3">
                            <div>
                                <span class="badge bg-primary me-1">{{ $periodikPending }} pending</span>
                                <span class="badge bg-success">{{ $periodikSynced }} tersinkron</span>
                                <span class="badge bg-light text-dark">{{ $periodikTotal }} total</span>
                            </div>
                            <button type="button" class="btn btn-sm btn-primary"
                                onclick="window.DaposRunner.runSync('{{ route('dapos.dapodik.sync-batch') }}', [
                                    { type: 'periodik', label: 'Periodik', total: {{ $periodikPending }} }
                                ])">
                                <i class="bi bi-cloud-arrow-up me-1"></i> Sinkron Periodik
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card h-100 border-success">
                    <div class="card-body">
                        <div class="d-flex align-items-center mb-2">
                            <i class="bi bi-people fs-3 text-success me-2"></i>
                            <div>
                                <h6 class="mb-0">Biodata Siswa</h6>
                                <small class="text-muted">Data identitas siswa (NISN, nama, tempat/tanggal lahir, dll.)</small>
                            </div>
                        </div>
                        <div class="d-flex justify-content-between align-items-center mt-3">
                            <div>
                                <span class="badge bg-success me-1">{{ $siswaWithNisn }} punya NISN</span>
                                <span class="badge bg-light text-dark">{{ $siswaTotal }} total</span>
                            </div>
                            <button type="button" class="btn btn-sm btn-success"
                                onclick="window.DaposRunner.runSync('{{ route('dapos.dapodik.sync-batch') }}', [
                                    { type: 'siswa', label: 'Biodata Siswa', total: {{ $siswaWithNisn }} }
                                ])">
                                <i class="bi bi-cloud-arrow-up me-1"></i> Sinkron Biodata
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header"><h5 class="mb-0">Informasi</h5></div>
    <div class="card-body">
        <ul class="mb-0">
            <li><strong>Periodik</strong> — Mengirim tinggi badan, berat badan, lingkar kepala, jarak rumah-sekolah, waktu tempuh, dan jumlah saudara kandung ke Dapodik. Hanya data yang belum tersinkron yang dikirim.</li>
            <li><strong>Biodata Siswa</strong> — Mengirim perubahan identitas siswa (nama, NISN, tempat/tanggal lahir, jenis kelamin, alamat, dll.) ke Dapodik.</li>
            <li><strong>Perlu diperhatikan:</strong> endpoint tulis Dapodik tidak dijamin selalu tersedia. Jika suatu data gagal, nama siswa akan muncul di log modal sehingga bisa diperiksa ulang.</li>
        </ul>
    </div>
</div>
@endif
@endsection
