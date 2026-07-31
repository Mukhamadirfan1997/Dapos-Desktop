<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" href="/favicon.ico">
    <title>@yield('title', 'DAPOS Desktop') - DAPOS Desktop</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
    <div class="d-flex" style="min-height: 100vh;">
        <div class="sidebar d-flex flex-column flex-shrink-0 p-3 text-bg-dark" style="width: 250px;">
            <a href="{{ route('dapos.dashboard') }}" class="d-flex align-items-center mb-3 text-white text-decoration-none">
                <img src="/images/dapos-logo.png" width="36" height="36" class="me-2 rounded-3" alt="DAPOS">
                <span class="fs-5 fw-semibold">DAPOS Desktop</span>
            </a>
            <hr>
            <ul class="nav nav-pills flex-column mb-auto">
                <li class="nav-item">
                    <a href="{{ route('dapos.dashboard') }}" class="nav-link text-white {{ request()->routeIs('dapos.dashboard') ? 'active' : '' }}">
                        <i class="bi bi-speedometer2 me-2"></i> Dashboard
                    </a>
                </li>
                <li>
                    <a href="{{ route('dapos.biodata.index') }}" class="nav-link text-white {{ request()->routeIs('dapos.biodata*') ? 'active' : '' }}">
                        <i class="bi bi-people me-2"></i> Biodata
                    </a>
                </li>
                <li>
                    <a href="{{ route('dapos.registrasi.index') }}" class="nav-link text-white {{ request()->routeIs('dapos.registrasi*') ? 'active' : '' }}">
                        <i class="bi bi-person-plus me-2"></i> Registrasi
                    </a>
                </li>
                <li>
                    <a href="{{ route('dapos.periodik.index') }}" class="nav-link text-white {{ request()->routeIs('dapos.periodik*') ? 'active' : '' }}">
                        <i class="bi bi-clipboard-data me-2"></i> Periodik
                    </a>
                </li>
                <li>
                    <a href="{{ route('dapos.rombel.index') }}" class="nav-link text-white {{ request()->routeIs('dapos.rombel*') && !request()->routeIs('dapos.rombel.daftar-siswa') ? 'active' : '' }}">
                        <i class="bi bi-layers me-2"></i> Rombel
                    </a>
                </li>
                <li>
                    <a href="{{ route('dapos.surat.index') }}" class="nav-link text-white {{ request()->routeIs('dapos.surat*') ? 'active' : '' }}">
                        <i class="bi bi-envelope me-2"></i> Surat
                    </a>
                </li>
                <li>
                    <a href="{{ route('dapos.pembelajaran') }}" class="nav-link text-white {{ request()->routeIs('dapos.pembelajaran') ? 'active' : '' }}">
                        <i class="bi bi-journal-bookmark me-2"></i> Pembelajaran
                    </a>
                </li>
                <li>
                    <a href="{{ route('dapos.rekap-jam-mengajar') }}" class="nav-link text-white {{ request()->routeIs('dapos.rekap-jam-mengajar*') ? 'active' : '' }}">
                        <i class="bi bi-stopwatch me-2"></i> Rekap Jam Mengajar
                    </a>
                </li>
                <li>
                    <a href="{{ route('dapos.rombel.daftar-siswa') }}" class="nav-link text-white {{ request()->routeIs('dapos.rombel.daftar-siswa') ? 'active' : '' }}">
                        <i class="bi bi-list-columns-reverse me-2"></i> Daftar Siswa/Rombel
                    </a>
                </li>
                <li>
                    <a href="{{ route('dapos.referensi') }}" class="nav-link text-white {{ request()->routeIs('dapos.referensi') ? 'active' : '' }}">
                        <i class="bi bi-book me-2"></i> Referensi
                    </a>
                </li>
                <hr class="my-2">
                <li>
                    <a href="{{ route('dapos.dapodik.import') }}" class="nav-link text-white {{ request()->routeIs('dapos.dapodik.import') ? 'active' : '' }}">
                        <i class="bi bi-cloud-download me-2"></i> Import Dapodik
                    </a>
                </li>
                <li>
                    <a href="{{ route('dapos.dapodik.sync') }}" class="nav-link text-white {{ request()->routeIs('dapos.dapodik.sync') ? 'active' : '' }}">
                        <i class="bi bi-cloud-arrow-up me-2"></i> Sinkron ke Dapodik
                    </a>
                </li>
                <li>
                    <a href="{{ route('dapos.dapodik.setting') }}" class="nav-link text-white {{ request()->routeIs('dapos.dapodik.setting') ? 'active' : '' }}">
                        <i class="bi bi-gear me-2"></i> Pengaturan
                    </a>
                </li>
                <li>
                    <a href="{{ route('dapos.akun') }}" class="nav-link text-white {{ request()->routeIs('dapos.akun') ? 'active' : '' }}">
                        <i class="bi bi-person-gear me-2"></i> Ubah Akun
                    </a>
                </li>
            </ul>
            <hr>
            <div class="text-white-50 small text-center mb-2">
                DAPOS Desktop v{{ config('app.version') }}
            </div>
            <button type="button" class="btn btn-outline-info btn-sm w-100 check-update-btn" title="Cek pembaruan aplikasi">
                <i class="bi bi-cloud-arrow-down me-1"></i> Cek Pembaruan
            </button>
        </div>

        <div class="flex-grow-1 d-flex flex-column">
            <nav class="navbar navbar-expand navbar-light bg-white shadow-sm px-4">
                <div class="container-fluid">
                    <span class="navbar-brand mb-0 h6">@yield('title', 'Dashboard')</span>
                    <div class="ms-auto d-flex align-items-center">
                        <span class="badge bg-primary me-2">
                            <i class="bi bi-database me-1"></i> SQLite
                        </span>
                        <span class="text-muted small" id="clock"></span>
                        <span class="ms-3 text-muted">
                            <i class="bi bi-person-circle me-1"></i>{{ auth()->user()->name ?? 'User' }}
                        </span>
                        <form method="POST" action="{{ route('dapos.logout') }}" class="ms-2">
                            @csrf
                            <button type="submit" class="btn btn-outline-danger btn-sm" title="Logout">
                                <i class="bi bi-box-arrow-right"></i>
                            </button>
                        </form>
                    </div>
                </div>
            </nav>

            <main class="flex-grow-1 p-4 bg-light">
                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="bi bi-check-circle me-1"></i> {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @if (session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="bi bi-exclamation-triangle me-1"></i> {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @if (session('warning'))
                    <div class="alert alert-warning alert-dismissible fade show" role="alert">
                        <i class="bi bi-exclamation-circle me-1"></i> {{ session('warning') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @if (session('info'))
                    <div class="alert alert-info alert-dismissible fade show" role="alert">
                        <i class="bi bi-info-circle me-1"></i> {{ session('info') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @yield('content')
            </main>

            <footer class="text-center py-2 text-muted small border-top bg-white">
                DAPOS Desktop v{{ config('app.version') }} &mdash; &copy; {{ date('Y') }} Dikembangkan oleh IrfanDev97 (irfandev30@gmail.com)
            </footer>
        </div>
    </div>

    <script>
        function updateClock() {
            const now = new Date();
            document.getElementById('clock').textContent = now.toLocaleString('id-ID');
        }
        updateClock();
        setInterval(updateClock, 1000);

        window.addEventListener('load', function() {
            if (window.$) {
                $('.select2').select2({ theme: 'bootstrap-5' });
                $('.datatable').DataTable();
            }
        });
    </script>
    @php $disclaimerSeen = \App\Models\Setting::where('key', 'dapos_disclaimer_seen')->exists(); @endphp
    @include('dapos.dapodik._disclaimer_modal', ['disclaimerSeen' => $disclaimerSeen])
    @include('dapos.dapodik._update_modal')
    @include('dapos.dapodik._progress_modal')
    @stack('scripts')
</body>
</html>
