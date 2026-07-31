<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('siswa', function (Blueprint $table) {
            $table->id();
            $table->string('nisn', 20)->unique()->nullable();
            $table->string('nik', 30)->nullable();
            $table->string('no_kk', 30)->nullable();
            $table->string('nama', 150);
            $table->enum('jenis_kelamin', ['L', 'P']);
            $table->string('tempat_lahir', 100)->nullable();
            $table->date('tanggal_lahir')->nullable();
            $table->string('reg_akta_lahir', 100)->nullable();
            $table->tinyInteger('agama_id')->nullable();
            $table->string('kewarganegaraan', 5)->default('ID');
            $table->string('alamat_jalan', 255)->nullable();
            $table->string('rt', 5)->nullable();
            $table->string('rw', 5)->nullable();
            $table->string('nama_dusun', 100)->nullable();
            $table->string('kode_wilayah', 20)->nullable();
            $table->string('kode_pos', 10)->nullable();
            $table->string('lintang', 20)->nullable();
            $table->string('bujur', 20)->nullable();
            $table->tinyInteger('jenis_tinggal_id')->nullable();
            $table->tinyInteger('alat_transportasi_id')->nullable();
            $table->tinyInteger('anak_keberapa')->nullable();
            $table->string('nomor_telepon_rumah', 20)->nullable();
            $table->string('nomor_telepon_seluler', 20)->nullable();
            $table->string('email', 100)->nullable();
            $table->string('nama_ayah', 150)->nullable();
            $table->string('nik_ayah', 30)->nullable();
            $table->year('tahun_lahir_ayah')->nullable();
            $table->tinyInteger('jenjang_pendidikan_ayah')->nullable();
            $table->tinyInteger('pekerjaan_id_ayah')->nullable();
            $table->tinyInteger('penghasilan_id_ayah')->nullable();
            $table->string('nama_ibu_kandung', 150)->nullable();
            $table->string('nik_ibu', 30)->nullable();
            $table->year('tahun_lahir_ibu')->nullable();
            $table->tinyInteger('jenjang_pendidikan_ibu')->nullable();
            $table->tinyInteger('pekerjaan_id_ibu')->nullable();
            $table->tinyInteger('penghasilan_id_ibu')->nullable();
            $table->string('nama_wali', 150)->nullable();
            $table->string('nik_wali', 30)->nullable();
            $table->year('tahun_lahir_wali')->nullable();
            $table->tinyInteger('jenjang_pendidikan_wali')->nullable();
            $table->tinyInteger('pekerjaan_id_wali')->nullable();
            $table->tinyInteger('penghasilan_id_wali')->nullable();
            $table->string('kebutuhan_khusus', 100)->nullable();
            $table->string('sekolah_asal', 150)->nullable();
            $table->text('catatan')->nullable();
            $table->string('foto', 255)->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('agama_id')->references('id')->on('agama');
            $table->foreign('kode_wilayah')->references('kode')->on('kode_wilayah');
            $table->foreign('jenis_tinggal_id')->references('id')->on('jenis_tinggal');
            $table->foreign('alat_transportasi_id')->references('id')->on('alat_transportasi');
            $table->foreign('jenjang_pendidikan_ayah')->references('id')->on('jenjang_pendidikan');
            $table->foreign('pekerjaan_id_ayah')->references('id')->on('pekerjaan');
            $table->foreign('penghasilan_id_ayah')->references('id')->on('penghasilan');
            $table->foreign('jenjang_pendidikan_ibu')->references('id')->on('jenjang_pendidikan');
            $table->foreign('pekerjaan_id_ibu')->references('id')->on('pekerjaan');
            $table->foreign('penghasilan_id_ibu')->references('id')->on('penghasilan');
        });

        Schema::create('registrasi', function (Blueprint $table) {
            $table->id();
            $table->foreignId('siswa_id')->constrained('siswa')->cascadeOnDelete();
            $table->tinyInteger('jenis_daftar_id')->nullable();
            $table->string('nis', 30)->nullable();
            $table->string('no_peserta_ujian', 30)->nullable();
            $table->string('no_seri_ijazah', 50)->nullable();
            $table->string('no_seri_skhun', 50)->nullable();
            $table->string('sekolah_asal', 150)->nullable();
            $table->date('tanggal_masuk')->nullable();
            $table->string('rombel_awal', 50)->nullable();
            $table->tinyInteger('tingkat_awal')->nullable();
            $table->timestamps();

            $table->foreign('jenis_daftar_id')->references('id')->on('jenis_daftar');
        });

        Schema::create('periodik', function (Blueprint $table) {
            $table->id();
            $table->foreignId('siswa_id')->constrained('siswa')->cascadeOnDelete();
            $table->decimal('tinggi_badan', 5, 1)->nullable();
            $table->decimal('berat_badan', 5, 1)->nullable();
            $table->string('lingkar_kepala', 10)->nullable();
            $table->integer('jarak_rumah_sekolah')->nullable();
            $table->tinyInteger('waktu_tempuh')->nullable();
            $table->smallInteger('jumlah_saudara_kandung')->nullable();
            $table->year('tahun_periodik');
            $table->timestamps();
        });

        Schema::create('rombel', function (Blueprint $table) {
            $table->id();
            $table->string('nama', 100);
            $table->string('tingkat', 10);
            $table->string('jurusan', 100)->nullable();
            $table->string('kurikulum', 100)->nullable();
            $table->string('tahun_ajaran', 20);
            $table->string('nama_wali_kelas', 150)->nullable();
            $table->string('nip_wali_kelas', 30)->nullable();
            $table->string('ruangan', 100)->nullable();
            $table->integer('kapasitas')->nullable();
            $table->integer('jumlah_anggota')->default(0);
            $table->timestamps();
        });

        Schema::create('anggota_rombel', function (Blueprint $table) {
            $table->id();
            $table->foreignId('rombel_id')->constrained('rombel')->cascadeOnDelete();
            $table->foreignId('siswa_id')->constrained('siswa')->cascadeOnDelete();
            $table->string('status_di_rombel', 50)->default('Aktif');
            $table->timestamps();
            $table->unique(['rombel_id', 'siswa_id']);
        });

        Schema::create('surat', function (Blueprint $table) {
            $table->id();
            $table->string('jenis_surat', 50);
            $table->string('nomor_surat', 100)->nullable();
            $table->date('tgl_surat')->nullable();
            $table->foreignId('siswa_id')->nullable()->constrained('siswa')->nullOnDelete();
            $table->string('kepada', 255)->nullable();
            $table->string('kelas', 50)->nullable();
            $table->string('npsn_sekolah_tujuan', 30)->nullable();
            $table->string('nama_sekolah_tujuan', 255)->nullable();
            $table->text('alamat_sekolah_tujuan')->nullable();
            $table->text('alasan_keluar')->nullable();
            $table->string('hp_ortu', 20)->nullable();
            $table->string('nama_surat', 255)->nullable();
            $table->text('isi_surat')->nullable();
            $table->string('file_surat', 255)->nullable();
            $table->timestamps();
        });

        Schema::create('keaktifan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('siswa_id')->constrained('siswa')->cascadeOnDelete();
            $table->string('status', 50);
            $table->date('tanggal_status')->nullable();
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });

        Schema::create('siswa_pip', function (Blueprint $table) {
            $table->id();
            $table->foreignId('siswa_id')->constrained('siswa')->cascadeOnDelete();
            $table->smallInteger('layak_pip_id')->nullable();
            $table->string('no_kks', 50)->nullable();
            $table->string('no_pkh', 50)->nullable();
            $table->string('no_kip', 50)->nullable();
            $table->string('nama_penerima', 150)->nullable();
            $table->text('keterangan')->nullable();
            $table->timestamps();

            $table->foreign('layak_pip_id')->references('id')->on('layak_pip');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('siswa_pip');
        Schema::dropIfExists('keaktifan');
        Schema::dropIfExists('surat');
        Schema::dropIfExists('anggota_rombel');
        Schema::dropIfExists('rombel');
        Schema::dropIfExists('periodik');
        Schema::dropIfExists('registrasi');
        Schema::dropIfExists('siswa');
    }
};
