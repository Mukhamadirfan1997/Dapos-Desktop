<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - DAPOS Desktop</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-light d-flex align-items-center" style="min-height: 100vh;">
    <div class="container" style="max-width: 420px;">
        <div class="card shadow-sm border-0">
            <div class="card-body p-4">
                <div class="text-center mb-4">
                    <i class="bi bi-mortarboard-fill fs-1 text-primary"></i>
                    <h4 class="mt-2 mb-0">DAPOS Desktop</h4>
                    <p class="text-muted small mb-0">Data Pokok Sekolah</p>
                </div>

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
            </div>
        </div>
        <p class="text-center text-muted small mt-3 mb-0">
            DAPOS Desktop &mdash; {{ date('Y') }}
        </p>
    </div>
</body>
</html>
