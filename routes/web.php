<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Dapos\DashboardController;
use App\Http\Controllers\Dapos\BiodataController;
use App\Http\Controllers\Dapos\RegistrasiController;
use App\Http\Controllers\Dapos\PeriodikController;
use App\Http\Controllers\Dapos\RombelController;
use App\Http\Controllers\Dapos\SuratController;
use App\Http\Controllers\Dapos\ReferensiController;
use App\Http\Controllers\Dapos\DapodikSettingController;
use App\Http\Controllers\Dapos\ExportController;
use App\Http\Controllers\Dapos\PembelajaranController;
use App\Http\Controllers\Dapos\AuthController;
use App\Http\Controllers\Dapos\RekapJamMengajarController;

Route::get('dapos/login', [AuthController::class, 'showLoginForm'])->middleware('guest')->name('dapos.login');
Route::post('dapos/login', [AuthController::class, 'login'])->middleware('guest')->name('dapos.login.attempt');
Route::post('dapos/logout', [AuthController::class, 'logout'])->middleware('auth')->name('dapos.logout');
Route::get('login', fn () => redirect()->route('dapos.login'))->middleware('guest')->name('login');

Route::prefix('dapos')->name('dapos.')->middleware('auth')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    Route::resource('biodata', BiodataController::class);
    Route::get('biodata-trash', [BiodataController::class, 'trash'])->name('biodata.trash');
    Route::get('biodata/{id}/restore', [BiodataController::class, 'restore'])->name('biodata.restore');
    Route::get('biodata/{id}/force-delete', [BiodataController::class, 'forceDelete'])->name('biodata.force-delete');
    Route::resource('registrasi', RegistrasiController::class);
    Route::resource('periodik', PeriodikController::class);
    Route::redirect('rekap-pd', '/dapos')->name('rekap-pd');

    Route::get('rombel/daftar-siswa', [RombelController::class, 'daftarSiswa'])->name('rombel.daftar-siswa');
    Route::resource('rombel', RombelController::class);
    Route::post('rombel/{rombel}/add-siswa', [RombelController::class, 'addSiswa'])->name('rombel.add-siswa');
    Route::delete('rombel/{rombel}/remove-siswa/{anggota}', [RombelController::class, 'removeSiswa'])->name('rombel.remove-siswa');

    Route::resource('surat', SuratController::class);
    Route::get('pembelajaran', [PembelajaranController::class, 'index'])->name('pembelajaran');
    Route::get('rekap-jam-mengajar', [RekapJamMengajarController::class, 'index'])->name('rekap-jam-mengajar');
    Route::get('rekap-jam-mengajar/excel', [RekapJamMengajarController::class, 'rekapExcel'])->name('rekap-jam-mengajar.excel');
    Route::get('rekap-jam-mengajar/pdf', [RekapJamMengajarController::class, 'rekapPdf'])->name('rekap-jam-mengajar.pdf');
    Route::get('referensi', [ReferensiController::class, 'index'])->name('referensi');
    Route::get('akun', [AuthController::class, 'showAccount'])->name('akun');
    Route::put('akun', [AuthController::class, 'updateAccount'])->name('akun.update');
    Route::post('disclaimer-ack', [AuthController::class, 'ackDisclaimer'])->name('disclaimer.ack');

    Route::prefix('dapodik')->name('dapodik.')->group(function () {
        Route::get('setting', [DapodikSettingController::class, 'index'])->name('setting');
        Route::post('setting', [DapodikSettingController::class, 'update'])->name('setting.update');

        Route::get('import', [DapodikSettingController::class, 'importPage'])->name('import');
        Route::get('import-siswa', [DapodikSettingController::class, 'importSiswa'])->name('import-siswa');
        Route::get('import-registrasi', [DapodikSettingController::class, 'importRegistrasi'])->name('import-registrasi');
        Route::get('import-rombel', [DapodikSettingController::class, 'importRombel'])->name('import-rombel');
        Route::get('import-anggota-rombel', [DapodikSettingController::class, 'importAnggotaRombel'])->name('import-anggota-rombel');
        Route::get('import-pembelajaran', [DapodikSettingController::class, 'importPembelajaran'])->name('import-pembelajaran');
        Route::get('import-all', [DapodikSettingController::class, 'importAll'])->name('import-all');
        Route::get('sync-all', [DapodikSettingController::class, 'syncAll'])->name('sync-all');
        Route::get('sync-one/{periodik}', [DapodikSettingController::class, 'syncOne'])->name('sync-one');

        Route::get('sync', [DapodikSettingController::class, 'syncPage'])->name('sync');
        Route::post('sync-batch', [DapodikSettingController::class, 'syncBatch'])->name('sync-batch');
        Route::post('import-step/{step}', [DapodikSettingController::class, 'importStep'])->name('import-step');
    });

    Route::prefix('export')->name('export.')->group(function () {
        Route::get('siswa-excel', [ExportController::class, 'siswaExcel'])->name('siswa-excel');
        Route::get('siswa-pdf', [ExportController::class, 'siswaPdf'])->name('siswa-pdf');
        Route::get('periodik-excel', [ExportController::class, 'periodikExcel'])->name('periodik-excel');
        Route::post('periodik-import', [ExportController::class, 'periodikImport'])->name('periodik-import');
        Route::get('periodik-pdf', [ExportController::class, 'periodikPdf'])->name('periodik-pdf');
        Route::get('rombel-excel', [ExportController::class, 'rombelExcel'])->name('rombel-excel');
        Route::get('rombel-pdf', [ExportController::class, 'rombelPdf'])->name('rombel-pdf');
        Route::get('registrasi-excel', [ExportController::class, 'registrasiExcel'])->name('registrasi-excel');
        Route::get('registrasi-pdf', [ExportController::class, 'registrasiPdf'])->name('registrasi-pdf');
        Route::get('surat-excel', [ExportController::class, 'suratExcel'])->name('surat-excel');
        Route::get('surat-pdf', [ExportController::class, 'suratPdf'])->name('surat-pdf');
        Route::get('siswa-per-rombel-excel', [ExportController::class, 'siswaPerRombelExcel'])->name('siswa-per-rombel-excel');
    });

});

Route::redirect('/', '/dapos');
