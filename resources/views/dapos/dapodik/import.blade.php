@extends('dapos.layouts.app')

@section('title', 'Import Data Dapodik')

@section('content')
@if (!$config || !$config->token)
<div class="alert alert-warning">
    <i class="bi bi-exclamation-triangle me-1"></i>
    Konfigurasi Dapodik belum diatur.
    <a href="{{ route('dapos.dapodik.setting') }}" class="alert-link">Atur sekarang</a>
</div>
@else
<div class="row g-4 mb-4">
    <div class="col-md-3">
        <div class="card h-100 border-primary">
            <div class="card-body text-center">
                <i class="bi bi-people fs-1 text-primary"></i>
                <h5 class="mt-2">Siswa</h5>
                <div class="d-flex justify-content-center gap-3 my-2">
                    <div><small class="text-muted">Lokal</small><br><strong class="fs-4">{{ $stats['siswa']['local'] }}</strong></div>
                    <div><small class="text-muted">Dapodik</small><br><strong class="fs-4">{{ $stats['siswa']['dapodik'] }}</strong></div>
                </div>
                <div class="mt-2">
                    <a href="{{ route('dapos.dapodik.import-siswa') }}" class="btn btn-primary btn-sm w-100"
                        onclick="return confirm('Import data siswa dari Dapodik?')">
                        <i class="bi bi-cloud-download me-1"></i> Import Siswa
                    </a>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card h-100 border-dark">
            <div class="card-body text-center">
                <i class="bi bi-person-plus fs-1 text-dark"></i>
                <h5 class="mt-2">Registrasi</h5>
                <div class="d-flex justify-content-center gap-3 my-2">
                    <div><small class="text-muted">Lokal</small><br><strong class="fs-4">{{ $stats['registrasi']['local'] }}</strong></div>
                    <div><small class="text-muted">Dapodik</small><br><strong class="fs-4">{{ $stats['registrasi']['dapodik'] }}</strong></div>
                </div>
                <div class="mt-2">
                    <a href="{{ route('dapos.dapodik.import-registrasi') }}" class="btn btn-dark btn-sm w-100"
                        onclick="return confirm('Import data Registrasi dari Dapodik?')">
                        <i class="bi bi-cloud-download me-1"></i> Import Registrasi
                    </a>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card h-100 border-info">
            <div class="card-body text-center">
                <i class="bi bi-people-fill fs-1 text-info"></i>
                <h5 class="mt-2">Rombel</h5>
                <div class="d-flex justify-content-center gap-3 my-2">
                    <div><small class="text-muted">Lokal</small><br><strong class="fs-4">{{ $stats['rombel']['local'] }}</strong></div>
                    <div><small class="text-muted">Dapodik</small><br><strong class="fs-4">{{ $stats['rombel']['dapodik'] }}</strong></div>
                </div>
                <div class="mt-2">
                    <a href="{{ route('dapos.dapodik.import-rombel') }}" class="btn btn-info btn-sm w-100"
                        onclick="return confirm('Import data Rombel dari Dapodik?')">
                        <i class="bi bi-cloud-download me-1"></i> Import Rombel
                    </a>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card h-100 border-secondary">
            <div class="card-body text-center">
                <i class="bi bi-journal-bookmark-fill fs-1 text-secondary"></i>
                <h5 class="mt-2">Pembelajaran</h5>
                <div class="d-flex justify-content-center gap-3 my-2">
                    <div><small class="text-muted">Lokal</small><br><strong class="fs-4">{{ $stats['pembelajaran']['local'] }}</strong></div>
                    <div><small class="text-muted">Dapodik</small><br><strong class="fs-4">{{ $stats['pembelajaran']['dapodik'] }}</strong></div>
                </div>
                <div class="mt-2">
                    <a href="{{ route('dapos.dapodik.import-pembelajaran') }}" class="btn btn-secondary btn-sm w-100"
                        onclick="return confirm('Import data Pembelajaran dari Dapodik?')">
                        <i class="bi bi-cloud-download me-1"></i> Import Pembelajaran
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="text-center mb-4">
    <a href="{{ route('dapos.dapodik.import-all') }}" class="btn btn-lg btn-primary"
        onclick="return confirm('Import SEMUA data dari Dapodik?\n\nSiswa + Registrasi + Rombel + Anggota Rombel + Pembelajaran akan diimport/diperbarui.')">
        <i class="bi bi-cloud-download me-2"></i> Import All
    </a>
</div>

<div class="card">
    <div class="card-header"><h5 class="mb-0">Informasi</h5></div>
    <div class="card-body">
        <ul class="mb-0">
            <li><strong>Import Siswa</strong> — Mengambil data siswa + periodik dari Dapodik. Data yang sudah ada akan diperbarui.</li>
            <li><strong>Import Registrasi</strong> — Mengambil data registrasi (NIS, tanggal masuk, sekolah asal, jenis daftar) dari Dapodik. No. Peserta Ujian & No. Seri Ijazah/SKHUN tidak tersedia di Dapodik → diisi manual.</li>
            <li><strong>Import Rombel</strong> — Mengambil data rombongan belajar dari Dapodik, termasuk anggota rombel.</li>
            <li><strong>Import Pembelajaran</strong> — Mengambil data mata pelajaran dari Dapodik (nama guru diambil dari wali kelas; guru mapel lain tidak tersedia di Dapodik).</li>
            <li><strong>Import All</strong> — Menjalankan semua import secara berurutan (Siswa, Registrasi, Rombel, Anggota Rombel, Pembelajaran).</li>
        </ul>
    </div>
</div>
@endif
@endsection
