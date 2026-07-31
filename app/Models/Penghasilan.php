<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Penghasilan extends Model
{
    protected $table = 'penghasilan';
    public $timestamps = false;
    public $incrementing = false;
    protected $keyType = 'integer';

    protected $fillable = ['id', 'rentang'];
}
