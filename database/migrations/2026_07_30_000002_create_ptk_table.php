<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ptk', function (Blueprint $table) {
            $table->id();
            $table->string('nama', 150);
            $table->string('nip', 30)->nullable();
            $table->string('nuptk', 30)->nullable();
            $table->string('nik', 30)->nullable();
            $table->enum('jenis_kelamin', ['L', 'P']);
            $table->string('tempat_lahir', 100)->nullable();
            $table->date('tanggal_lahir')->nullable();
            $table->tinyInteger('agama_id')->nullable();
            $table->text('alamat')->nullable();
            $table->string('status_kepegawaian', 50)->nullable();
            $table->string('jenis_ptk', 50)->nullable();
            $table->date('tmt')->nullable();
            $table->string('pendidikan', 50)->nullable();
            $table->string('jurusan', 100)->nullable();
            $table->smallInteger('jam_mengajar')->nullable();
            $table->string('email', 100)->nullable();
            $table->string('nomor_hp', 20)->nullable();
            $table->timestamps();

            $table->foreign('agama_id')->references('id')->on('agama');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ptk');
    }
};
