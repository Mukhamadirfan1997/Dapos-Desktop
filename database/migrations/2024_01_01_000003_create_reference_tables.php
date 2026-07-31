<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('agama', function (Blueprint $table) {
            $table->tinyInteger('id')->primary();
            $table->string('nama', 50);
        });

        Schema::create('pekerjaan', function (Blueprint $table) {
            $table->tinyInteger('id')->primary();
            $table->string('nama', 100);
        });

        Schema::create('penghasilan', function (Blueprint $table) {
            $table->tinyInteger('id')->primary();
            $table->string('rentang', 100);
        });

        Schema::create('jenis_tinggal', function (Blueprint $table) {
            $table->tinyInteger('id')->primary();
            $table->string('nama', 100);
        });

        Schema::create('alat_transportasi', function (Blueprint $table) {
            $table->tinyInteger('id')->primary();
            $table->string('nama', 100);
        });

        Schema::create('jenjang_pendidikan', function (Blueprint $table) {
            $table->tinyInteger('id')->primary();
            $table->string('nama', 100);
        });

        Schema::create('jenis_daftar', function (Blueprint $table) {
            $table->tinyInteger('id')->primary();
            $table->string('nama', 100);
        });

        Schema::create('kode_wilayah', function (Blueprint $table) {
            $table->string('kode', 20)->primary();
            $table->string('nama', 255);
        });

        Schema::create('layak_pip', function (Blueprint $table) {
            $table->smallInteger('id')->primary();
            $table->string('keterangan', 255);
        });

        Schema::create('citacita', function (Blueprint $table) {
            $table->smallInteger('id')->primary();
            $table->string('nama', 100);
        });

        Schema::create('hobby', function (Blueprint $table) {
            $table->smallInteger('id')->primary();
            $table->string('nama', 100);
        });

        $this->seedReferenceData();
    }

    private function seedReferenceData(): void
    {
        DB::table('agama')->insert([
            ['id' => 1, 'nama' => 'Islam'], ['id' => 2, 'nama' => 'Kristen'],
            ['id' => 3, 'nama' => 'Katholik'], ['id' => 4, 'nama' => 'Hindu'],
            ['id' => 5, 'nama' => 'Budha'], ['id' => 6, 'nama' => 'Khonghucu'],
            ['id' => 7, 'nama' => 'Kepercayaan'], ['id' => 99, 'nama' => 'Lainnya'],
        ]);

        DB::table('pekerjaan')->insert([
            ['id' => 1, 'nama' => 'Tidak bekerja'], ['id' => 2, 'nama' => 'Nelayan'],
            ['id' => 3, 'nama' => 'Petani'], ['id' => 4, 'nama' => 'Peternak'],
            ['id' => 5, 'nama' => 'PNS/TNI/Polri'], ['id' => 6, 'nama' => 'Karyawan Swasta'],
            ['id' => 7, 'nama' => 'Pedagang Kecil'], ['id' => 8, 'nama' => 'Pedagang Besar'],
            ['id' => 9, 'nama' => 'Wiraswasta'], ['id' => 10, 'nama' => 'Wirausaha'],
            ['id' => 11, 'nama' => 'Buruh'], ['id' => 12, 'nama' => 'Pensiunan'],
            ['id' => 13, 'nama' => 'Karyawan BUMN'], ['id' => 14, 'nama' => 'TKI'],
            ['id' => 90, 'nama' => 'Tidak dapat diterapkan'], ['id' => 98, 'nama' => 'Sudah Meninggal'],
            ['id' => 99, 'nama' => 'Lainnya'],
        ]);

        DB::table('penghasilan')->insert([
            ['id' => 11, 'rentang' => '< Rp 500.000'],
            ['id' => 12, 'rentang' => 'Rp 500.000 - Rp 999.999'],
            ['id' => 13, 'rentang' => 'Rp 1.000.000 - Rp 1.999.999'],
            ['id' => 14, 'rentang' => 'Rp 2.000.000 - Rp 4.999.999'],
            ['id' => 15, 'rentang' => 'Rp 5.000.000 - Rp 20.000.000'],
            ['id' => 16, 'rentang' => '> Rp 20.000.000'],
            ['id' => 99, 'rentang' => 'Tidak Berpenghasilan'],
        ]);

        DB::table('jenis_daftar')->insert([
            ['id' => 1, 'nama' => 'Siswa baru'], ['id' => 2, 'nama' => 'Pindahan'],
            ['id' => 3, 'nama' => 'Naik kelas'], ['id' => 5, 'nama' => 'Mengulang'],
            ['id' => 6, 'nama' => 'Lanjutan semester'], ['id' => 7, 'nama' => 'Kembali bersekolah'],
        ]);

        DB::table('layak_pip')->insert([
            ['id' => -3, 'keterangan' => 'Dilarang Pemda karena menerima bantuan serupa'],
            ['id' => -2, 'keterangan' => 'Sudah Mampu'],
            ['id' => -1, 'keterangan' => 'Menolak'],
            ['id' => 1, 'keterangan' => 'Pemegang PKH/KPS/KKS'],
            ['id' => 3, 'keterangan' => 'Yatim Piatu/Panti Asuhan/Panti Sosial'],
            ['id' => 4, 'keterangan' => 'Dampak Bencana Alam'],
            ['id' => 5, 'keterangan' => 'Pernah Drop Out'],
            ['id' => 6, 'keterangan' => 'Siswa Miskin/Rentan Miskin'],
            ['id' => 7, 'keterangan' => 'Daerah Konflik'],
            ['id' => 8, 'keterangan' => 'Keluarga Terpidana/Berada di LAPAS'],
            ['id' => 9, 'keterangan' => 'Kelainan Fisik'],
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('hobby');
        Schema::dropIfExists('citacita');
        Schema::dropIfExists('layak_pip');
        Schema::dropIfExists('kode_wilayah');
        Schema::dropIfExists('jenis_daftar');
        Schema::dropIfExists('jenjang_pendidikan');
        Schema::dropIfExists('alat_transportasi');
        Schema::dropIfExists('jenis_tinggal');
        Schema::dropIfExists('penghasilan');
        Schema::dropIfExists('pekerjaan');
        Schema::dropIfExists('agama');
    }
};
