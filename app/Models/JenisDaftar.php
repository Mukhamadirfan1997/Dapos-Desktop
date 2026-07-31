<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JenisDaftar extends Model
{
    protected $table = 'jenis_daftar';
    public $timestamps = false;
    public $incrementing = false;
    protected $keyType = 'integer';

    protected $fillable = ['id', 'nama'];
}
