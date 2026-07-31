@extends('dapos.layouts.app')

@section('title', 'Edit Periodik')

@section('content')
<div class="card">
    <div class="card-header"><h5 class="mb-0">Edit Data Periodik</h5></div>
    <div class="card-body">
        <form action="{{ route('dapos.periodik.update', $periodik) }}" method="POST">
            @csrf @method('PUT')
            <div class="mb-3">
                <label class="form-label">Siswa <span class="text-danger">*</span></label>
                <select name="siswa_id" class="form-select select2" required>
                    @foreach ($siswa as $s)
                        <option value="{{ $s->id }}" {{ $periodik->siswa_id == $s->id ? 'selected' : '' }}>{{ $s->nama }}</option>
                    @endforeach
                </select>
            </div>
            <div class="row">
                <div class="col-md-4 mb-3">
                    <label class="form-label">Tinggi Badan (cm)</label>
                    <input type="number" step="0.1" min="0" name="tinggi_badan" class="form-control" value="{{ $periodik->tinggi_badan }}">
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label">Berat Badan (kg)</label>
                    <input type="number" step="0.1" min="0" name="berat_badan" class="form-control" value="{{ $periodik->berat_badan }}">
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label">Lingkar Kepala (cm)</label>
                    <input type="number" step="0.1" min="0" name="lingkar_kepala" class="form-control" value="{{ $periodik->lingkar_kepala }}">
                </div>
            </div>
            <div class="row">
                <div class="col-md-4 mb-3">
                    <label class="form-label">Jarak Rumah ke Sekolah (m)</label>
                    <input type="number" min="0" name="jarak_rumah_sekolah" class="form-control" value="{{ $periodik->jarak_rumah_sekolah }}">
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label">Waktu Tempuh (menit)</label>
                    <input type="number" min="0" name="waktu_tempuh" class="form-control" value="{{ $periodik->waktu_tempuh }}">
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label">Jumlah Saudara Kandung</label>
                    <input type="number" min="0" name="jumlah_saudara_kandung" class="form-control" value="{{ $periodik->jumlah_saudara_kandung }}">
                </div>
            </div>
            <div class="row">
                <div class="col-md-4 mb-3">
                    <label class="form-label">Tahun Periodik</label>
                    <input type="number" name="tahun_periodik" class="form-control" value="{{ $periodik->tahun_periodik }}" required>
                </div>
            </div>
            <button type="submit" class="btn btn-primary">Simpan</button>
            <a href="{{ route('dapos.periodik.index') }}" class="btn btn-secondary">Batal</a>
        </form>
    </div>
</div>
@endsection
