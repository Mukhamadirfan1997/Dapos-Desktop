@extends('dapos.layouts.app')

@section('title', 'Tambah Registrasi')

@section('content')
<div class="card">
    <div class="card-header"><h5 class="mb-0">Tambah Registrasi</h5></div>
    <div class="card-body">
        <form action="{{ route('dapos.registrasi.store') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label class="form-label">Siswa <span class="text-danger">*</span></label>
                <select name="siswa_id" class="form-select select2" required>
                    <option value="">-- Pilih --</option>
                    @foreach ($siswa as $s)
                        <option value="{{ $s->id }}" {{ old('siswa_id') == $s->id ? 'selected' : '' }}>{{ $s->nama }} ({{ $s->nisn ?? '-' }})</option>
                    @endforeach
                </select>
            </div>
            <div class="row">
                <div class="col-md-4 mb-3">
                    <label class="form-label">Jenis Daftar</label>
                    <select name="jenis_daftar_id" class="form-select">
                        <option value="">-- Pilih --</option>
                        @foreach ($jenisDaftar as $j)
                            <option value="{{ $j->id }}" {{ old('jenis_daftar_id') == $j->id ? 'selected' : '' }}>{{ $j->nama }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label">NIS</label>
                    <input type="text" name="nis" class="form-control" value="{{ old('nis') }}">
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label">Tanggal Masuk</label>
                    <input type="date" name="tanggal_masuk" class="form-control" value="{{ old('tanggal_masuk') }}">
                </div>
            </div>
            <button type="submit" class="btn btn-primary">Simpan</button>
            <a href="{{ route('dapos.registrasi.index') }}" class="btn btn-secondary">Batal</a>
        </form>
    </div>
</div>
@endsection
