<?php

namespace App\Http\Controllers\Dapos;

use App\Http\Controllers\Controller;
use App\Models\Siswa;
use App\Models\Rombel;
use App\Models\Surat;
use App\Models\Periodik;
use App\Models\AnggotaRombel;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $totalSiswa = Siswa::count();
        $totalSiswaAktif = AnggotaRombel::where('status_di_rombel', 'Aktif')
            ->distinct()->count('siswa_id');
        $totalRombel = Rombel::count();
        $totalSurat = Surat::count();
        $totalPeriodik = Periodik::count();
        $totalAnggotaRombel = AnggotaRombel::count();
        $siswaTerbaru = Siswa::latest()->take(5)->get();

        $siswaPerJk = Siswa::select('jenis_kelamin', DB::raw('count(*) as total'))
            ->groupBy('jenis_kelamin')->pluck('total', 'jenis_kelamin');

        $siswaPerRombel = Rombel::withCount('anggota')->whereHas('anggota')
            ->orderBy('tingkat')->orderBy('nama')
            ->get()
            ->map(function ($r) {
                $r->laki = AnggotaRombel::where('rombel_id', $r->id)
                    ->whereHas('siswa', fn($q) => $q->where('jenis_kelamin', 'L'))->count();
                $r->perempuan = AnggotaRombel::where('rombel_id', $r->id)
                    ->whereHas('siswa', fn($q) => $q->where('jenis_kelamin', 'P'))->count();
                return $r;
            });

        $perTingkat = AnggotaRombel::select(
            DB::raw("(SELECT tingkat FROM rombel WHERE rombel.id = anggota_rombel.rombel_id) as tingkat"),
            DB::raw('count(*) as total')
        )->groupBy('tingkat')->orderBy('tingkat')->get();

        $perTahunAjaran = DB::table('anggota_rombel')
            ->join('rombel', 'rombel.id', '=', 'anggota_rombel.rombel_id')
            ->select('rombel.tahun_ajaran as tahun_ajaran', DB::raw('count(*) as total'))
            ->groupBy('rombel.tahun_ajaran')
            ->orderBy('rombel.tahun_ajaran', 'desc')
            ->get();

        $periodikPerTahun = Periodik::select('tahun_periodik', DB::raw('count(*) as total'))
            ->groupBy('tahun_periodik')->orderBy('tahun_periodik')->pluck('total', 'tahun_periodik');

        return view('dapos.dashboard', compact(
            'totalSiswa', 'totalSiswaAktif', 'totalRombel', 'totalSurat', 'totalPeriodik',
            'totalAnggotaRombel', 'siswaTerbaru', 'siswaPerJk', 'siswaPerRombel',
            'perTingkat', 'perTahunAjaran', 'periodikPerTahun'
        ));
    }
}
