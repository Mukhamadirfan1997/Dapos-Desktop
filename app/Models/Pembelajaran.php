<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pembelajaran extends Model
{
    protected $table = 'pembelajaran';

    protected $fillable = [
        'rombel_id', 'ptk_id', 'mata_pelajaran', 'jam_mengajar', 'nama_guru',
    ];

    public function rombel()
    {
        return $this->belongsTo(Rombel::class);
    }
}
