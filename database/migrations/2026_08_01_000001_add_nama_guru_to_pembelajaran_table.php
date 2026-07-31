<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pembelajaran', function (Blueprint $table) {
            $table->string('nama_guru')->nullable()->after('mata_pelajaran');
        });
    }

    public function down(): void
    {
        Schema::table('pembelajaran', function (Blueprint $table) {
            $table->dropColumn('nama_guru');
        });
    }
};
