<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::table('jenjang_pendidikan')->insertOrIgnore([
            ['id' => 1, 'nama' => 'Tidak Sekolah'],
            ['id' => 2, 'nama' => 'SD Sederajat'],
            ['id' => 3, 'nama' => 'SMP Sederajat'],
            ['id' => 4, 'nama' => 'SMA Sederajat'],
            ['id' => 5, 'nama' => 'D1'],
            ['id' => 6, 'nama' => 'D2'],
            ['id' => 7, 'nama' => 'D3'],
            ['id' => 8, 'nama' => 'S1/D4'],
            ['id' => 9, 'nama' => 'S2'],
            ['id' => 10, 'nama' => 'S3'],
        ]);

        DB::table('jenis_tinggal')->insertOrIgnore([
            ['id' => 1, 'nama' => 'Bersama orang tua'],
            ['id' => 2, 'nama' => 'Bersama wali'],
            ['id' => 3, 'nama' => 'Kos'],
            ['id' => 4, 'nama' => 'Asrama'],
            ['id' => 5, 'nama' => 'Panti asuhan'],
            ['id' => 99, 'nama' => 'Lainnya'],
        ]);

        DB::table('alat_transportasi')->insertOrIgnore([
            ['id' => 1, 'nama' => 'Jalan kaki'],
            ['id' => 2, 'nama' => 'Kendaraan pribadi'],
            ['id' => 3, 'nama' => 'Kendaraan umum/angkot'],
            ['id' => 4, 'nama' => 'Jemputan sekolah'],
            ['id' => 5, 'nama' => 'Kereta api'],
            ['id' => 6, 'nama' => 'Ojek'],
            ['id' => 7, 'nama' => 'Andong/bendi/sado/dokar/delman/beca'],
            ['id' => 8, 'nama' => 'Perahu penyeberangan/rakit/getek'],
            ['id' => 99, 'nama' => 'Lainnya'],
        ]);
    }

    public function down(): void
    {
        DB::table('jenjang_pendidikan')->delete();
        DB::table('jenis_tinggal')->delete();
        DB::table('alat_transportasi')->delete();
    }
};
