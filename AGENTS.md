# AGENTS.md — DAPOS v8.7 (dapos-desktop)

Aplikasi desktop Data Pokok Sekolah: **Laravel 12 + Electron 43**. Modul shortcut Dapodik di `../daposhortcut` (CodeIgniter). Satu repo di `dapos-desktop`.

## Environment
- OS: Windows, Shell: PowerShell 5.1. PHP: `C:\xampp\php`. Workdir: `dapos-desktop`.
- DB: SQLite `database/database.sqlite`. Login admin: `dapos.desktop@gmail.com` / `dapos2026` (ubah via `php artisan dapos:create-admin`).
- `.env` `APP_NAME="DAPOS Desktop"` — **wajib dikutip** (spasi); tanpa kutip error parse dotenv.
- PHP inline (`php -r`) rentan konflik `$` PowerShell → pakai file skrip di `C:\Users\yudhi\AppData\Local\Temp\opencode`.

## ⚠️ PENTING — JANGAN config:cache saat akan test
- **Jangan jalankan `php artisan config:cache`** sebelum `php artisan test`. Cache mengunci `database.connections.sqlite.database` ke file produksi sehingga `DB_DATABASE=:memory:` di `phpunit.xml` diabaikan → `RefreshDatabase` (migrate:fresh) **MENIMPA database produksi**. Pernah terjadi: 187 siswa + semua data hilang (31 Juli 2026).
- Pengaman: `tests/bootstrap.php` (bootstrap phpunit) menghentikan test dengan pesan jelas bila `bootstrap/cache/config.php` ada. Fix: `php artisan config:clear`.
- State normal proyek ini: config **tidak** di-cache. Jangan ubah tanpa alasan.

## Perintah utama
```bash
php artisan serve            # dev server (Laravel)
npm run dev                  # Vite dev (hot reload)
npm run build                # build asset produksi (Vite)
npm run electron             # jalankan aplikasi desktop
php artisan test             # smoke test (HARUS config:clear dulu)
php artisan migrate --force  # migrasi DB produksi
php artisan view:cache       # compile blade
php artisan dapos:create-admin
```
- Lint PHP: `php -l <file>`.
- Test: 5 test (Unit Example, Feature Example, SmokeTest login/pages/exports) di `tests/Feature/SmokeTest.php`.
- SmokeTest pakai DB `:memory:` (phpunit.xml) + `RefreshDatabase`. Login test memakai `withoutMiddleware(\Illuminate\Foundation\Http\Middleware\ValidateCsrfToken::class)` — kelas CSRF Laravel 12 (bukan `App\Http\Middleware\VerifyCsrfToken` yang tidak ada).

## Struktur & modul aktif
- Routes semua di `routes/web.php`, grup prefix `dapos` + middleware `auth`.
- Modul aktif: Biodata (siswa), Registrasi, Periodik, Rombel (+ Daftar Siswa), Surat, Pembelajaran, Rekap Jam Mengajar, Dashboard, Referensi, Ubah Akun, Dapodik Sync (Setting + Import).
- **Modul dihapus (7/31/2026):** Keaktifan, PIP/SiswaPip, PD Keluar, PTK — routes, controller, model, view, relation, service method, export sudah dibersihkan. Migration `2026_08_01_000003_drop_deprecated_tables.php` drop `keaktifan`, `pd_keluar`, `ptk`, `siswa_pip`, `layak_pip`. Jangan menambahkan ulang tanpa konfirmasi.
- Statistik dashboard berbasis `siswa`, `rombel`, `anggota_rombel` (status_di_rombel='Aktif'), `periodik`, `surat` — TANPA keaktifan/ptk.

## Dapodik Sync & data
- Endpoint yang **200**: `getPesertaDidik`, `getRombonganBelajar`. Yang 404/kosong: `getGuru`, `getPtkList`, `getPdKeluar`, dst → PTK/PD Keluar memang tidak tersedia.
- Import flow: Setting (`base_url`, `token`, `npsn`, `tahun_ajaran`) → Test Koneksi → halaman Import → **Import All** (siswa, registrasi, rombel, anggota rombel, pembelajaran).
- Registrasi: `nipd`→NIS, `tanggal_masuk_sekolah`, `sekolah_asal`, `jenis_pendaftaran_id`. No. Peserta Ujian & No. Seri Ijazah/SKHUN diisi manual.
- Pembelajaran: `jam_mengajar_per_minggu`→jam, nama guru dari UUID wali kelas (`ptk_id_str`); guru agama bukan wali kelas → tanpa nama.
- Siswa punya `dapodik_id` (`peserta_didik_id`) untuk cocokkan anggota rombel.
- Periodik: tinggi/berat/tahun dari Dapodik; **lingkar kepala, jarak rumah, waktu tempuh, jumlah saudara** harus diisi manual.
- **Isi cepat periodik:** Export Excel → isi → Import. Template **12 kolom**: No(0), NISN(1), Nama(2), Kelas(3), Tinggi(4), Berat(5), Lingkar(6), Jarak(7), Waktu(8), Saudara(9), Tahun(10), Sync(11). Kunci: NISN + kolom Tahun(index 10); sel kosong/`-` dilewati. Route `POST dapos/export/periodik-import`.

## Konvensi
- Laravel 12: `bootstrap/app.php` (bukan `app/Http/Kernel.php`); tidak ada `app/Http/Middleware/` default.
- Export Excel: PhpSpreadsheet 5.x → pakai `setCellValue([$col, $row], $val)` & `getColumnDimension(Coordinate::stringFromColumnIndex($col))` (metode lama dihapus di 5.x).
- UI: Bootstrap 5.3 + DataTables + Select2 + Chart.js, semua lokal via Vite (`@vite`), bukan CDN. Jangan buat `public/hot` basi (membuat `@vite` menunjuk dev server mati).
- Script inline pakai `window.addEventListener('load', ...)` + guard (`if (window.Chart)`) karena `app.js` module deferred.
- Pesan validasi: Bahasa Indonesia (`APP_LOCALE=id`, `lang/id/validation.php`).
- Komunikasi user dalam Bahasa Indonesia.

## Data & backup
- `database/*.sqlite*` di-gitignore (data pribadi siswa) — jangan commit DB.
- Setelah modul dihapus, DB produksi saat ini **kosong** (menunggu import ulang Dapodik). Data pribadi JANGAN pernah masuk commit git.
