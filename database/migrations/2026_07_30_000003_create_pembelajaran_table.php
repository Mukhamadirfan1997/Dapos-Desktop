<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pembelajaran', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('rombel_id')->nullable();
            $table->unsignedBigInteger('ptk_id')->nullable();
            $table->string('mata_pelajaran');
            $table->integer('jam_mengajar')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pembelajaran');
    }
};
