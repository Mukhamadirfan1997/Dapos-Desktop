@extends('dapos.layouts.app')

@section('title', 'Edit Registrasi')

@section('content')
<div class="card">
    <div class="card-header"><h5 class="mb-0">Edit Registrasi</h5></div>
    <div class="card-body">
        <form action="{{ route('dapos.registrasi.update', $registrasi) }}" method="POST">
            @csrf @method('PUT')
            <div class="mb-3">
                <label class="form-label">Siswa <span class="text-danger">*</span></label>
                <select name="siswa_id" class="form-select select2" required>
                    @foreach ($siswa as $s)
                        <option value="{{ $s->id }}" {{ $registrasi->siswa_id == $s->id ? 'selected' : '' }}>{{ $s->nama }}</option>
                    @endforeach
                </select>
            </div>
            <div class="row">
                <div class="col-md-4 mb-3">
                    <label class="form-label">Jenis Daftar</label>
                    <select name="jenis_daftar_id" class="form-select">
                        @foreach ($jenisDaftar as $j)
                            <option value="{{ $j->id }}" {{ $registrasi->jenis_daftar_id == $j->id ? 'selected' : '' }}>{{ $j->nama }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label">NIS</label>
                    <input type="text" name="nis" class="form-control" value="{{ old('nis', $registrasi->nis) }}">
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label">Tanggal Masuk</label>
                    <input type="date" name="tanggal_masuk" class="form-control" value="{{ old('tanggal_masuk', $registrasi->tanggal_masuk?->format('Y-m-d')) }}">
                </div>
            </div>
            <button type="submit" class="btn btn-primary">Simpan</button>
            <a href="{{ route('dapos.registrasi.index') }}" class="btn btn-secondary">Batal</a>
        </form>
    </div>
</div>
@endsection
