@extends('dapos.layouts.app')

@section('title', 'Tambah Biodata Siswa')

@section('content')
<div class="card">
    <div class="card-header">
        <h5 class="mb-0"><i class="bi bi-person-plus"></i> Tambah Biodata Siswa</h5>
    </div>
    <div class="card-body">
        <form action="{{ route('dapos.biodata.store') }}" method="POST">
            @csrf
            @include('dapos.biodata._form', ['biodatum' => null])

            <div class="mt-4 d-flex gap-2">
                <button type="submit" class="btn btn-primary"><i class="bi bi-save"></i> Simpan</button>
                <a href="{{ route('dapos.biodata.index') }}" class="btn btn-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection
