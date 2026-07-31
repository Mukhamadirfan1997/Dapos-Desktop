<?php

namespace App\Http\Controllers\Dapos;

use App\Http\Controllers\Controller;
use App\Models\Agama;
use App\Models\Pekerjaan;
use App\Models\Penghasilan;
use App\Models\JenisDaftar;

class ReferensiController extends Controller
{
    public function index()
    {
        $agama = Agama::all();
        $pekerjaan = Pekerjaan::all();
        $penghasilan = Penghasilan::all();
        $jenisDaftar = JenisDaftar::all();

        return view('dapos.referensi', compact('agama', 'pekerjaan', 'penghasilan', 'jenisDaftar'));
    }
}
