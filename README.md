<p align="center">
  <img src="public/images/dapos-logo.png" alt="DAPOS Desktop" width="120">
</p>

<h1 align="center">DAPOS Desktop</h1>

<p align="center">
  Aplikasi desktop untuk membantu tugas <strong>operator sekolah</strong> dalam mengelola data pokok pendidikan (Dapodik).
</p>

<p align="center">
  <img alt="Versi" src="https://img.shields.io/badge/versi-1.0.2-blue">
  <img alt="Teknologi" src="https://img.shields.io/badge/Laravel-12-red">
  <img alt="Teknologi" src="https://img.shields.io/badge/Electron-43-9cf">
</p>

## Tentang Aplikasi

DAPOS Desktop adalah perangkat pendukung resmi untuk operator sekolah dalam mengelola data pokok sekolah. Aplikasi ini berjalan di desktop (Windows) dan menawarkan antarmuka yang lebih sederhana dibandingkan sistem Dapodik, dilengkapi sinkronisasi dua arah dengan server Dapodik.

## Fitur Utama

- **Manajemen Biodata Siswa** — kelola data peserta didik lengkap dengan dukungan impor/ekspor Excel.
- **Registrasi & Periodik** — pendaftaran siswa, tinggi badan, berat badan, dan data periodik lain.
- **Rombongan Belajar** — kelola rombel dan daftar anggota rombel.
- **Surat, Pembelajaran & Rekap Jam Mengajar** — administrasi surat dan data pembelajaran.
- **Sinkronisasi Dapodik** — import data siswa/rombel/pembelajaran dari Dapodik, serta sinkron periodik & biodata ke Dapodik.
- **Pemeriksaan Pembaruan** — aplikasi memeriksa versi terbaru secara otomatis saat dibuka.

## Teknologi

| Komponen | Teknologi |
|----------|-----------|
| Backend | Laravel 12 (PHP) |
| Frontend | Bootstrap 5.3, DataTables, Select2, Chart.js |
| Desktop | Electron 43 |
| Database | SQLite |

## Cara Menggunakan

1. Unduh installer terbaru dari [halaman rilis](https://github.com/Mukhamadirfan1997/Dapos-Desktop/releases).
2. Jalankan installer dan ikuti langkah instalasi.
3. Buka aplikasi lalu masuk dengan akun operator.

### Login Awal

| | |
|---|---|
| **Email** | `dapos.desktop@gmail.com` |
| **Password** | `dapos2026` |

> **Penting:** Setelah berhasil masuk, segera ganti password melalui menu **Ubah Akun** untuk keamanan.

4. Atur koneksi Dapodik (base URL, token, NPSN, tahun ajaran) pada menu **Dapodik Sync**.
5. Import data atau mulai kelola data secara manual.

## Lokasi Data & Backup

- **Lokasi database:** `%APPDATA%\dapos-desktop\database.sqlite` (folder data aplikasi milik user Windows).
- Data tersimpan **di luar folder instalasi** sehingga tetap aman saat aplikasi di-update atau di-uninstall (uninstall tidak menghapus data).
- Setiap user Windows memiliki database sendiri; data tidak tercampur antar user.
- **Backup:** cukup salin file `database.sqlite` di folder di atas ke lokasi lain. Untuk restore, tutup aplikasi lalu kembalikan file tersebut.
- Install pertama akan membuat database bersih otomatis (skema + akun admin, tanpa data contoh).

## Catatan Penting

- Data yang tersimpan bersifat **rahasia** dan hanya untuk kepentingan sekolah.
- Dilarang **memperjualbelikan, memodifikasi, atau menyalahgunakan** aplikasi ini.
- Gunakan sesuai ketentuan dan jangan mengakses data pihak lain tanpa izin.

## Dikembangkan Oleh

**IrfanDev97** — [irfandev30@gmail.com](mailto:irfandev30@gmail.com)

## Lisensi

Aplikasi ini dikembangkan untuk membantu operator sekolah. Tidak untuk diperjualbelikan tanpa izin.
