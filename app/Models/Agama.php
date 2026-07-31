<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Agama extends Model
{
    protected $table = 'agama';
    public $timestamps = false;
    public $incrementing = false;
    protected $keyType = 'integer';

    protected $fillable = ['id', 'nama'];
}
