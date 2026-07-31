<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Periodik extends Model
{
    protected $table = 'periodik';

    protected $fillable = [
        'siswa_id', 'tinggi_badan', 'berat_badan', 'lingkar_kepala',
        'jarak_rumah_sekolah', 'waktu_tempuh', 'jumlah_saudara_kandung', 'tahun_periodik',
        'dapodik_id', 'sync_status', 'last_sync_at',
    ];

    protected $casts = [
        'tahun_periodik' => 'integer',
    ];

    public function siswa()
    {
        return $this->belongsTo(Siswa::class);
    }
}
