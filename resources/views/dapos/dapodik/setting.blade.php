@extends('dapos.layouts.app')

@section('title', 'Pengaturan Dapodik API')

@section('content')
<div class="row">
    <div class="col-md-8">
        <div class="card mb-4">
            <div class="card-header"><h5 class="mb-0">Konfigurasi Web Service Dapodik</h5></div>
            <div class="card-body">
                <form action="{{ route('dapos.dapodik.setting.update') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Base URL <span class="text-danger">*</span></label>
                        <input type="url" name="base_url" class="form-control"
                            value="{{ old('base_url', $config->base_url ?? 'http://localhost:5774') }}" required>
                        <div class="form-text">Contoh: http://localhost:5774 (base URL Dapodik lokal)</div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Token <span class="text-danger">*</span></label>
                        <input type="text" name="token" class="form-control"
                            value="{{ old('token', $config->token ?? '') }}" required>
                        <div class="form-text">Token web service dari Dapodik (Settings > Manajemen Web Service)</div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">NPSN <span class="text-danger">*</span></label>
                            <input type="text" name="npsn" class="form-control"
                                value="{{ old('npsn', $config->npsn ?? '') }}" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Tahun Ajaran</label>
                            <input type="text" name="tahun_ajaran" class="form-control"
                                value="{{ old('tahun_ajaran', $config->tahun_ajaran ?? date('Y').'/'.(date('Y')+1)) }}">
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary"><i class="bi bi-save me-1"></i> Simpan</button>
                    <button type="submit" name="test" value="1" class="btn btn-outline-info">
                        <i class="bi bi-plug me-1"></i> Simpan & Test Koneksi
                    </button>
                </form>
            </div>
        </div>

        <div class="card">
            <div class="card-header"><h5 class="mb-0">Petunjuk</h5></div>
            <div class="card-body">
                <ol class="mb-0">
                    <li>Buka Dapodik di browser (<strong>localhost:5774</strong>)</li>
                    <li>Login sebagai admin/operator</li>
                    <li>Masuk ke <strong>Pengaturan > Manajemen Web Service</strong></li>
                    <li>Klik <strong>Tambah</strong>, isi nama aplikasi dan IP <strong>localhost</strong></li>
                    <li>Salin <strong>Token</strong> yang digenerate</li>
                    <li>Isi <strong>Base URL</strong> (http://localhost:5774), <strong>Token</strong>, dan <strong>NPSN sekolah</strong></li>
                    <li>Klik <strong>Test Koneksi</strong> untuk verifikasi</li>
                    <li>Data periodik siap disinkron</li>
                </ol>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card">
            <div class="card-header"><h5 class="mb-0">Status</h5></div>
            <div class="card-body">
                @if ($config && $config->token)
                    <div class="d-flex align-items-center mb-3">
                        <i class="bi bi-check-circle-fill text-success fs-4 me-2"></i>
                        <span>Terkonfigurasi</span>
                    </div>
                    <hr>
                    <div class="small">
                        <div class="mb-1"><strong>Base URL:</strong> {{ $config->base_url }}</div>
                        <div class="mb-1"><strong>NPSN:</strong> {{ $config->npsn ?? '-' }}</div>
                        <div><strong>Token:</strong> {{ substr($config->token, 0, 10) }}...</div>
                    </div>
                @else
                    <div class="text-center py-3">
                        <i class="bi bi-x-circle-fill text-danger fs-1 mb-2 d-block"></i>
                        <p class="mb-0 text-muted">Belum dikonfigurasi</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
