@extends('dapos.layouts.app')

@section('title', 'Edit Rombel')

@section('content')
<div class="card">
    <div class="card-header"><h5 class="mb-0">Edit Rombel</h5></div>
    <div class="card-body">
        <form action="{{ route('dapos.rombel.update', $rombel) }}" method="POST">
            @csrf @method('PUT')
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Nama Rombel <span class="text-danger">*</span></label>
                    <input type="text" name="nama" class="form-control" value="{{ old('nama', $rombel->nama) }}" required>
                </div>
                <div class="col-md-3 mb-3">
                    <label class="form-label">Tingkat <span class="text-danger">*</span></label>
                    <select name="tingkat" class="form-select" required>
                        @for ($i = 1; $i <= 12; $i++)
                            <option value="{{ $i }}" {{ $rombel->tingkat == $i ? 'selected' : '' }}>{{ $i }}</option>
                        @endfor
                    </select>
                </div>
                <div class="col-md-3 mb-3">
                    <label class="form-label">Tahun Ajaran <span class="text-danger">*</span></label>
                    <input type="text" name="tahun_ajaran" class="form-control" value="{{ old('tahun_ajaran', $rombel->tahun_ajaran) }}" required>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Nama Wali Kelas</label>
                    <input type="text" name="nama_wali_kelas" class="form-control" value="{{ old('nama_wali_kelas', $rombel->nama_wali_kelas) }}">
                </div>
                <div class="col-md-3 mb-3">
                    <label class="form-label">NIP Wali Kelas</label>
                    <input type="text" name="nip_wali_kelas" class="form-control" value="{{ old('nip_wali_kelas', $rombel->nip_wali_kelas) }}">
                </div>
                <div class="col-md-3 mb-3">
                    <label class="form-label">Kapasitas</label>
                    <input type="number" name="kapasitas" class="form-control" value="{{ old('kapasitas', $rombel->kapasitas ?? 32) }}">
                </div>
            </div>
            <button type="submit" class="btn btn-primary">Simpan</button>
            <a href="{{ route('dapos.rombel.index') }}" class="btn btn-secondary">Batal</a>
        </form>
    </div>
</div>
@endsection
