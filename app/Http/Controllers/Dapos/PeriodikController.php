<?php

namespace App\Http\Controllers\Dapos;

use App\Http\Controllers\Controller;
use App\Models\Siswa;
use App\Models\Periodik;
use Illuminate\Http\Request;

class PeriodikController extends Controller
{
    public function index(Request $request)
    {
        $query = Periodik::with('siswa.rombelAktif');

        if ($request->filled('q')) {
            $q = $request->q;
            $query->whereHas('siswa', function ($w) use ($q) {
                $w->where('nama', 'like', "%{$q}%")
                  ->orWhere('nisn', 'like', "%{$q}%");
            });
        }

        if ($request->filled('tahun_periodik')) {
            $query->where('tahun_periodik', $request->tahun_periodik);
        }

        if ($request->filled('sync_status')) {
            $query->where('sync_status', $request->sync_status);
        }

        $periodik = $query->latest()->paginate(20);
        $tahunList = Periodik::distinct()->orderBy('tahun_periodik', 'desc')->pluck('tahun_periodik');
        return view('dapos.periodik.index', compact('periodik', 'tahunList'));
    }

    public function create()
    {
        $siswa = Siswa::all();
        return view('dapos.periodik.create', compact('siswa'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'siswa_id' => 'required|exists:siswa,id',
            'tinggi_badan' => 'nullable|numeric|min:0',
            'berat_badan' => 'nullable|numeric|min:0',
            'lingkar_kepala' => 'nullable|numeric|min:0',
            'jarak_rumah_sekolah' => 'nullable|numeric|min:0',
            'waktu_tempuh' => 'nullable|numeric|min:0',
            'jumlah_saudara_kandung' => 'nullable|integer|min:0',
            'tahun_periodik' => 'required|integer|min:2000|max:2099',
        ]);

        Periodik::create($validated);

        return redirect()->route('dapos.periodik.index')
            ->with('success', 'Data periodik berhasil ditambahkan.');
    }

    public function show(Periodik $periodik)
    {
        $periodik->load('siswa');
        return view('dapos.periodik.show', compact('periodik'));
    }

    public function edit(Periodik $periodik)
    {
        $siswa = Siswa::all();
        return view('dapos.periodik.edit', compact('periodik', 'siswa'));
    }

    public function update(Request $request, Periodik $periodik)
    {
        $validated = $request->validate([
            'siswa_id' => 'required|exists:siswa,id',
            'tinggi_badan' => 'nullable|numeric|min:0',
            'berat_badan' => 'nullable|numeric|min:0',
            'lingkar_kepala' => 'nullable|numeric|min:0',
            'jarak_rumah_sekolah' => 'nullable|numeric|min:0',
            'waktu_tempuh' => 'nullable|numeric|min:0',
            'jumlah_saudara_kandung' => 'nullable|integer|min:0',
            'tahun_periodik' => 'required|integer|min:2000|max:2099',
        ]);

        $periodik->update($validated);

        return redirect()->route('dapos.periodik.index')
            ->with('success', 'Data periodik berhasil diperbarui.');
    }

    public function destroy(Periodik $periodik)
    {
        $periodik->delete();
        return redirect()->route('dapos.periodik.index')
            ->with('success', 'Data periodik berhasil dihapus.');
    }
}
