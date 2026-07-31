<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="/favicon.ico">
    <title>Login - DAPOS Desktop</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="login-body">
    <div class="container d-flex flex-column align-items-center justify-content-center" style="min-height: 100vh; max-width: 440px;">
        <div class="login-brand text-center mb-4">
            <div class="login-logo-wrap mx-auto mb-3">
                <img src="/images/dapos-logo.png" width="72" height="72" alt="DAPOS">
            </div>
            <h4 class="text-white mb-1 fw-semibold">DAPOS Desktop</h4>
            <p class="text-white-50 small mb-0">Data Pokok Sekolah &middot; v{{ config('app.version') }}</p>
        </div>

        <div class="card login-card w-100">
            <div class="card-body p-4">
                @if ($errors->any())
                    <div class="alert alert-danger py-2 small" role="alert">
                        {{ $errors->first() }}
                    </div>
                @endif

                <form method="POST" action="{{ route('dapos.login.attempt') }}">
                    @csrf
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" id="email" name="email" class="form-control"
                            value="{{ old('email') }}" required autofocus autocomplete="username">
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" id="password" name="password" class="form-control"
                            required autocomplete="current-password">
                    </div>
                    <div class="mb-3 form-check">
                        <input type="checkbox" id="remember" name="remember" class="form-check-input">
                        <label for="remember" class="form-check-label small">Ingat saya</label>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="bi bi-box-arrow-in-right me-1"></i> Masuk
                    </button>
                </form>

                <div class="login-notice mt-4 p-3">
                    <div class="d-flex align-items-start">
                        <i class="bi bi-shield-exclamation me-2 fs-5 text-warning"></i>
                        <div class="small">
                            <strong>Perhatian:</strong> Aplikasi ini resmi untuk digunakan sekolah. Dilarang
                            memperjualbelikan, memodifikasi, atau menyalahgunakan aplikasi dan data yang tersimpan
                            di dalamnya.
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <p class="text-white-50 small text-center mt-4 mb-0">
            &copy; {{ date('Y') }} DAPOS Desktop v{{ config('app.version') }} &mdash; Dikembangkan oleh
            <span class="text-white-75">IrfanDev97</span> (<a href="mailto:irfandev30@gmail.com" class="text-info text-decoration-none">irfandev30@gmail.com</a>)
        </p>
    </div>
</body>
</html>
