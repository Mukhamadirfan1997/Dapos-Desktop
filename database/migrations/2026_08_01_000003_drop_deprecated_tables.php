<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::dropIfExists('keaktifan');
        Schema::dropIfExists('pd_keluar');
        Schema::dropIfExists('ptk');
        Schema::dropIfExists('siswa_pip');
        Schema::dropIfExists('layak_pip');
    }

    public function down(): void
    {
        Schema::create('layak_pip', function (Blueprint $table) {
            $table->smallInteger('id')->primary();
            $table->string('keterangan', 255);
        });

        Schema::create('ptk', function (Blueprint $table) {
            $table->id();
            $table->string('nama', 150);
            $table->string('nip', 30)->nullable();
            $table->string('nuptk', 30)->nullable();
            $table->string('nik', 30)->nullable();
            $table->enum('jenis_kelamin', ['L', 'P'])->nullable();
            $table->string('tempat_lahir', 100)->nullable();
            $table->date('tanggal_lahir')->nullable();
            $table->tinyInteger('agama_id')->nullable();
            $table->string('alamat', 255)->nullable();
            $table->string('status_kepegawaian', 50)->nullable();
            $table->string('jenis_ptk', 50)->nullable();
            $table->date('tmt')->nullable();
            $table->string('pendidikan', 100)->nullable();
            $table->string('jurusan', 100)->nullable();
            $table->integer('jam_mengajar')->nullable();
            $table->string('email', 100)->nullable();
            $table->string('nomor_hp', 20)->nullable();
            $table->timestamps();
        });

        Schema::create('pd_keluar', function (Blueprint $table) {
            $table->id();
            $table->foreignId('siswa_id')->constrained('siswa')->cascadeOnDelete();
            $table->date('tanggal_keluar')->nullable();
            $table->string('alasan_keluar', 50)->nullable();
            $table->text('keterangan')->nullable();
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
};
