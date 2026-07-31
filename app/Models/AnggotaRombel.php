<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AnggotaRombel extends Model
{
    protected $table = 'anggota_rombel';

    protected $fillable = ['rombel_id', 'siswa_id', 'status_di_rombel'];

    public function rombel()
    {
        return $this->belongsTo(Rombel::class);
    }

    public function siswa()
    {
        return $this->belongsTo(Siswa::class);
    }
}
