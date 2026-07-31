@extends('dapos.layouts.app')

@section('title', 'Tambah Periodik')

@section('content')
<div class="card">
    <div class="card-header"><h5 class="mb-0">Tambah Data Periodik</h5></div>
    <div class="card-body">
        <form action="{{ route('dapos.periodik.store') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label class="form-label">Siswa <span class="text-danger">*</span></label>
                <select name="siswa_id" class="form-select select2" required>
                    <option value="">-- Pilih --</option>
                    @foreach ($siswa as $s)
                        <option value="{{ $s->id }}">{{ $s->nama }}</option>
                    @endforeach
                </select>
            </div>
            <div class="row">
                <div class="col-md-4 mb-3">
                    <label class="form-label">Tinggi Badan (cm)</label>
                    <input type="number" step="0.1" min="0" name="tinggi_badan" class="form-control">
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label">Berat Badan (kg)</label>
                    <input type="number" step="0.1" min="0" name="berat_badan" class="form-control">
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label">Lingkar Kepala (cm)</label>
                    <input type="number" step="0.1" min="0" name="lingkar_kepala" class="form-control">
                </div>
            </div>
            <div class="row">
                <div class="col-md-4 mb-3">
                    <label class="form-label">Jarak Rumah ke Sekolah (m)</label>
                    <input type="number" min="0" name="jarak_rumah_sekolah" class="form-control">
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label">Waktu Tempuh (menit)</label>
                    <input type="number" min="0" name="waktu_tempuh" class="form-control">
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label">Jumlah Saudara Kandung</label>
                    <input type="number" min="0" name="jumlah_saudara_kandung" class="form-control">
                </div>
            </div>
            <div class="row">
                <div class="col-md-4 mb-3">
                    <label class="form-label">Tahun Periodik <span class="text-danger">*</span></label>
                    <input type="number" name="tahun_periodik" class="form-control" value="{{ date('Y') }}" required>
                </div>
            </div>
            <button type="submit" class="btn btn-primary">Simpan</button>
            <a href="{{ route('dapos.periodik.index') }}" class="btn btn-secondary">Batal</a>
        </form>
    </div>
</div>
@endsection
