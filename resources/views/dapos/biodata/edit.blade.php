@extends('dapos.layouts.app')

@section('title', 'Edit Biodata Siswa')

@section('content')
<div class="card">
    <div class="card-header">
        <h5 class="mb-0"><i class="bi bi-pencil-square"></i> Edit Biodata: {{ $biodatum->nama }}</h5>
    </div>
    <div class="card-body">
        <form action="{{ route('dapos.biodata.update', $biodatum) }}" method="POST">
            @csrf @method('PUT')
            @include('dapos.biodata._form', ['biodatum' => $biodatum])

            <div class="mt-4 d-flex gap-2">
                <button type="submit" class="btn btn-primary"><i class="bi bi-save"></i> Simpan</button>
                <a href="{{ route('dapos.biodata.show', $biodatum) }}" class="btn btn-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection
