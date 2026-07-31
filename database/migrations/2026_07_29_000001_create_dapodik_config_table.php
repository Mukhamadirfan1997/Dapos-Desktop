<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('dapodik_config', function (Blueprint $table) {
            $table->id();
            $table->string('base_url')->default('http://localhost:5774/webservice');
            $table->string('token');
            $table->string('npsn', 20)->nullable();
            $table->string('tahun_ajaran', 20)->nullable();
            $table->timestamps();
        });

        Schema::table('periodik', function (Blueprint $table) {
            $table->string('dapodik_id', 50)->nullable()->after('tahun_periodik');
            $table->string('sync_status', 20)->default('unsynced')->after('dapodik_id');
            $table->timestamp('last_sync_at')->nullable()->after('sync_status');
        });
    }

    public function down(): void
    {
        Schema::table('periodik', function (Blueprint $table) {
            $table->dropColumn(['dapodik_id', 'sync_status', 'last_sync_at']);
        });
        Schema::dropIfExists('dapodik_config');
    }
};
