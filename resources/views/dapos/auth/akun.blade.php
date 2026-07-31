@extends('dapos.layouts.app')

@section('title', 'Ubah Akun')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Ubah Akun</h5>
            </div>
            <div class="card-body">
                @if ($errors->any())
                    <div class="alert alert-danger py-2 small">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $e)
                                <li>{{ $e }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('dapos.akun.update') }}">
                    @csrf
                    @method('PUT')
                    <div class="mb-3">
                        <label for="name" class="form-label">Nama</label>
                        <input type="text" id="name" name="name" class="form-control"
                            value="{{ old('name', $user->name) }}" required>
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" id="email" name="email" class="form-control"
                            value="{{ old('email', $user->email) }}" required>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Password Baru <span class="text-muted small">(kosongkan jika tidak diganti)</span></label>
                        <input type="password" id="password" name="password" class="form-control" minlength="8" autocomplete="new-password">
                    </div>
                    <div class="mb-3">
                        <label for="password_confirmation" class="form-label">Ulangi Password Baru</label>
                        <input type="password" id="password_confirmation" name="password_confirmation" class="form-control" autocomplete="new-password">
                    </div>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-check-lg me-1"></i> Simpan
                    </button>
                </form>
            </div>
            <div class="card-footer">
                <div class="d-flex align-items-center justify-content-between">
                    <div class="small text-muted">
                        <i class="bi bi-box me-1"></i> DAPOS Desktop v{{ config('app.version') }}
                        <br><span class="small">Dikembangkan oleh IrfanDev97 (irfandev30@gmail.com)</span>
                    </div>
                    <button type="button" class="btn btn-outline-info btn-sm check-update-btn">
                        <i class="bi bi-cloud-arrow-down me-1"></i> Cek Pembaruan
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
