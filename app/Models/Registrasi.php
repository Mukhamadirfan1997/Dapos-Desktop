<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Registrasi extends Model
{
    protected $table = 'registrasi';

    protected $fillable = [
        'siswa_id', 'jenis_daftar_id', 'nis', 'no_peserta_ujian',
        'no_seri_ijazah', 'no_seri_skhun', 'sekolah_asal',
        'tanggal_masuk', 'rombel_awal', 'tingkat_awal',
    ];

    protected $casts = [
        'tanggal_masuk' => 'date',
    ];

    public function siswa()
    {
        return $this->belongsTo(Siswa::class);
    }

    public function jenisDaftar()
    {
        return $this->belongsTo(JenisDaftar::class, 'jenis_daftar_id');
    }
}
