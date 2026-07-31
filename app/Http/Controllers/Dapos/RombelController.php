<?php

namespace App\Http\Controllers\Dapos;

use App\Http\Controllers\Controller;
use App\Models\Rombel;
use App\Models\Siswa;
use App\Models\AnggotaRombel;
use Illuminate\Http\Request;

class RombelController extends Controller
{
    public function daftarSiswa()
    {
        $rombelList = Rombel::with(['anggota.siswa.agama', 'anggota.siswa.periodik'])
            ->withCount('anggota')
            ->orderBy('tingkat')->orderBy('nama')
            ->get()
            ->filter(fn($r) => $r->anggota->isNotEmpty())
            ->groupBy('tingkat');

        return view('dapos.rombel.daftar_siswa', compact('rombelList'));
    }

    public function index(Request $request)
    {
        $query = Rombel::withCount('anggota');

        if ($request->filled('q')) {
            $q = $request->q;
            $query->where(function ($w) use ($q) {
                $w->where('nama', 'like', "%{$q}%")
                  ->orWhere('nama_wali_kelas', 'like', "%{$q}%");
            });
        }

        if ($request->filled('tingkat')) {
            $query->where('tingkat', $request->tingkat);
        }

        if ($request->filled('tahun_ajaran')) {
            $query->where('tahun_ajaran', $request->tahun_ajaran);
        }

        $rombel = $query->latest()->paginate(20);
        $tingkatList = Rombel::distinct()->orderBy('tingkat')->pluck('tingkat');
        $tahunAjaranList = Rombel::distinct()->orderBy('tahun_ajaran', 'desc')->pluck('tahun_ajaran');
        return view('dapos.rombel.index', compact('rombel', 'tingkatList', 'tahunAjaranList'));
    }

    public function create()
    {
        return view('dapos.rombel.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:100',
            'tingkat' => 'required|string|max:10',
            'tahun_ajaran' => 'required|string|max:20',
            'nama_wali_kelas' => 'nullable|string|max:150',
        ]);

        Rombel::create($validated);

        return redirect()->route('dapos.rombel.index')
            ->with('success', 'Rombel berhasil ditambahkan.');
    }

    public function show(Rombel $rombel)
    {
        $rombel->load(['anggota.siswa.agama', 'anggota.siswa.registrasi', 'anggota.siswa.rombelAktif']);
        $siswaTersedia = Siswa::with('rombelAktif')
            ->whereDoesntHave('anggotaRombel', function ($q) use ($rombel) {
                $q->where('rombel_id', $rombel->id);
            })->get();

        return view('dapos.rombel.show', compact('rombel', 'siswaTersedia'));
    }

    public function addSiswa(Request $request, Rombel $rombel)
    {
        $request->validate([
            'siswa_id' => 'required|exists:siswa,id',
        ]);

        AnggotaRombel::firstOrCreate([
            'rombel_id' => $rombel->id,
            'siswa_id' => $request->siswa_id,
        ]);

        $rombel->increment('jumlah_anggota');

        return back()->with('success', 'Siswa berhasil ditambahkan ke rombel.');
    }

    public function removeSiswa(Rombel $rombel, AnggotaRombel $anggota)
    {
        $anggota->delete();
        $rombel->decrement('jumlah_anggota');

        return back()->with('success', 'Siswa berhasil dikeluarkan dari rombel.');
    }

    public function edit(Rombel $rombel)
    {
        return view('dapos.rombel.edit', compact('rombel'));
    }

    public function update(Request $request, Rombel $rombel)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:100',
            'tingkat' => 'required|string|max:10',
            'tahun_ajaran' => 'required|string|max:20',
            'nama_wali_kelas' => 'nullable|string|max:150',
            'nip_wali_kelas' => 'nullable|string|max:30',
            'kapasitas' => 'nullable|integer',
        ]);

        $rombel->update($validated);

        return redirect()->route('dapos.rombel.index')
            ->with('success', 'Rombel berhasil diperbarui.');
    }

    public function destroy(Rombel $rombel)
    {
        $rombel->delete();
        return redirect()->route('dapos.rombel.index')
            ->with('success', 'Rombel berhasil dihapus.');
    }
}
