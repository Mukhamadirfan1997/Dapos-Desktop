@extends('dapos.layouts.app')

@section('title', 'Tambah Rombel')

@section('content')
<div class="card">
    <div class="card-header"><h5 class="mb-0">Tambah Rombel</h5></div>
    <div class="card-body">
        <form action="{{ route('dapos.rombel.store') }}" method="POST">
            @csrf
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Nama Rombel <span class="text-danger">*</span></label>
                    <input type="text" name="nama" class="form-control" required>
                </div>
                <div class="col-md-3 mb-3">
                    <label class="form-label">Tingkat <span class="text-danger">*</span></label>
                    <select name="tingkat" class="form-select" required>
                        @for ($i = 1; $i <= 12; $i++)
                            <option value="{{ $i }}">{{ $i }}</option>
                        @endfor
                    </select>
                </div>
                <div class="col-md-3 mb-3">
                    <label class="form-label">Tahun Ajaran <span class="text-danger">*</span></label>
                    <input type="text" name="tahun_ajaran" class="form-control" value="{{ date('Y') . '/' . (date('Y')+1) }}" required>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Nama Wali Kelas</label>
                    <input type="text" name="nama_wali_kelas" class="form-control">
                </div>
                <div class="col-md-3 mb-3">
                    <label class="form-label">NIP Wali Kelas</label>
                    <input type="text" name="nip_wali_kelas" class="form-control">
                </div>
                <div class="col-md-3 mb-3">
                    <label class="form-label">Kapasitas</label>
                    <input type="number" name="kapasitas" class="form-control" value="32">
                </div>
            </div>
            <button type="submit" class="btn btn-primary">Simpan</button>
            <a href="{{ route('dapos.rombel.index') }}" class="btn btn-secondary">Batal</a>
        </form>
    </div>
</div>
@endsection
