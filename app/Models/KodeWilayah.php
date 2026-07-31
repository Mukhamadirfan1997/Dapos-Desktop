<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KodeWilayah extends Model
{
    protected $table = 'kode_wilayah';
    public $timestamps = false;
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = ['kode', 'nama'];
}
