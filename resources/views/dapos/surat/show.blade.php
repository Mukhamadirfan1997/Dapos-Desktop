@extends('dapos.layouts.app')

@section('title', 'Detail Surat')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between">
        <h5 class="mb-0">Detail Surat</h5>
        <a href="{{ route('dapos.surat.index') }}" class="btn btn-secondary btn-sm">Kembali</a>
    </div>
    <div class="card-body">
        <table class="table table-sm">
            <tr><th style="width:150px">Jenis Surat</th><td>{{ $surat->jenis_surat }}</td></tr>
            <tr><th>Nomor Surat</th><td>{{ $surat->nomor_surat ?? '-' }}</td></tr>
            <tr><th>Tanggal</th><td>{{ $surat->tgl_surat ? $surat->tgl_surat->format('d/m/Y') : '-' }}</td></tr>
            <tr><th>Siswa</th><td>{{ $surat->siswa->nama ?? '-' }}</td></tr>
            <tr><th>Kepada</th><td>{{ $surat->kepada ?? '-' }}</td></tr>
            <tr><th>Isi Surat</th><td>{!! nl2br(e($surat->isi_surat ?? '-')) !!}</td></tr>
        </table>
    </div>
</div>
@endsection
