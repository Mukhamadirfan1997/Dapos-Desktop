<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Rombel extends Model
{
    protected $table = 'rombel';

    protected $fillable = [
        'nama', 'tingkat', 'jurusan', 'kurikulum', 'tahun_ajaran',
        'nama_wali_kelas', 'nip_wali_kelas', 'ruangan', 'kapasitas', 'jumlah_anggota',
    ];

    public function anggota()
    {
        return $this->hasMany(AnggotaRombel::class);
    }
}
