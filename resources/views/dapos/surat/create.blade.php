@extends('dapos.layouts.app')

@section('title', 'Tambah Surat')

@section('content')
<div class="card">
    <div class="card-header"><h5 class="mb-0">Tambah Surat</h5></div>
    <div class="card-body">
        <form action="{{ route('dapos.surat.store') }}" method="POST">
            @csrf
            <div class="row">
                <div class="col-md-4 mb-3">
                    <label class="form-label">Jenis Surat <span class="text-danger">*</span></label>
                    <select name="jenis_surat" class="form-select" required>
                        <option value="">-- Pilih --</option>
                        <option value="Pindah">Surat Pindah</option>
                        <option value="Keluar">Surat Keluar</option>
                        <option value="Izin">Surat Izin</option>
                        <option value="Keterangan">Surat Keterangan</option>
                        <option value="Lainnya">Lainnya</option>
                    </select>
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label">Nomor Surat</label>
                    <input type="text" name="nomor_surat" class="form-control">
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label">Tanggal Surat</label>
                    <input type="date" name="tgl_surat" class="form-control" value="{{ date('Y-m-d') }}">
                </div>
            </div>
            <div class="mb-3">
                <label class="form-label">Siswa</label>
                <select name="siswa_id" class="form-select select2">
                    <option value="">-- Pilih --</option>
                    @foreach ($siswa as $s)
                        <option value="{{ $s->id }}">{{ $s->nama }}</option>
                    @endforeach
                </select>
            </div>
            <div class="mb-3">
                <label class="form-label">Kepada</label>
                <input type="text" name="kepada" class="form-control">
            </div>
            <div class="mb-3">
                <label class="form-label">Isi Surat</label>
                <textarea name="isi_surat" class="form-control" rows="5"></textarea>
            </div>
            <button type="submit" class="btn btn-primary">Simpan</button>
            <a href="{{ route('dapos.surat.index') }}" class="btn btn-secondary">Batal</a>
        </form>
    </div>
</div>
@endsection
