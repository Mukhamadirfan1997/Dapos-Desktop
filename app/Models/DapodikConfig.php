<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DapodikConfig extends Model
{
    protected $table = 'dapodik_config';

    protected $fillable = [
        'base_url', 'token', 'npsn', 'tahun_ajaran',
    ];

    protected $casts = [
        'base_url' => 'string',
        'token' => 'string',
    ];
}
