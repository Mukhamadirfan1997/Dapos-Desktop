<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pekerjaan extends Model
{
    protected $table = 'pekerjaan';
    public $timestamps = false;
    public $incrementing = false;
    protected $keyType = 'integer';

    protected $fillable = ['id', 'nama'];
}
