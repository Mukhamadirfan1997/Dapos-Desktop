<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Surat extends Model
{
    protected $table = 'surat';

    protected $fillable = [
        'jenis_surat', 'nomor_surat', 'tgl_surat', 'siswa_id', 'kepada',
        'kelas', 'npsn_sekolah_tujuan', 'nama_sekolah_tujuan', 'alamat_sekolah_tujuan',
        'alasan_keluar', 'hp_ortu', 'nama_surat', 'isi_surat', 'file_surat',
    ];

    protected $casts = [
        'tgl_surat' => 'date',
    ];

    public function siswa()
    {
        return $this->belongsTo(Siswa::class);
    }
}
