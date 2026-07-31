<?php

namespace App\Http\Controllers\Dapos;

use App\Http\Controllers\Controller;
use App\Models\Siswa;
use App\Models\Registrasi;
use App\Models\JenisDaftar;
use Illuminate\Http\Request;

class RegistrasiController extends Controller
{
    public function index(Request $request)
    {
        $query = Registrasi::with('siswa', 'jenisDaftar');

        if ($request->filled('q')) {
            $q = $request->q;
            $query->whereHas('siswa', function ($w) use ($q) {
                $w->where('nama', 'like', "%{$q}%")
                  ->orWhere('nisn', 'like', "%{$q}%");
            });
        }

        if ($request->filled('jenis_daftar_id')) {
            $query->where('jenis_daftar_id', $request->jenis_daftar_id);
        }

        if ($request->filled('tingkat_awal')) {
            $query->where('tingkat_awal', $request->tingkat_awal);
        }

        $registrasi = $query->latest()->paginate(20);
        $jenisDaftar = JenisDaftar::all();
        return view('dapos.registrasi.index', compact('registrasi', 'jenisDaftar'));
    }

    public function create()
    {
        $siswa = Siswa::all();
        $jenisDaftar = JenisDaftar::all();
        return view('dapos.registrasi.create', compact('siswa', 'jenisDaftar'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'siswa_id' => 'required|exists:siswa,id',
            'jenis_daftar_id' => 'nullable|exists:jenis_daftar,id',
            'nis' => 'nullable|string|max:30',
            'tanggal_masuk' => 'nullable|date',
            'tingkat_awal' => 'nullable|integer',
        ]);

        Registrasi::create($validated);

        return redirect()->route('dapos.registrasi.index')
            ->with('success', 'Data registrasi berhasil ditambahkan.');
    }

    public function show(Registrasi $registrasi)
    {
        $registrasi->load('siswa', 'jenisDaftar');
        return view('dapos.registrasi.show', compact('registrasi'));
    }

    public function edit(Registrasi $registrasi)
    {
        $siswa = Siswa::all();
        $jenisDaftar = JenisDaftar::all();
        return view('dapos.registrasi.edit', compact('registrasi', 'siswa', 'jenisDaftar'));
    }

    public function update(Request $request, Registrasi $registrasi)
    {
        $validated = $request->validate([
            'siswa_id' => 'required|exists:siswa,id',
            'jenis_daftar_id' => 'nullable|exists:jenis_daftar,id',
            'nis' => 'nullable|string|max:30',
            'tanggal_masuk' => 'nullable|date',
            'tingkat_awal' => 'nullable|integer',
        ]);

        $registrasi->update($validated);

        return redirect()->route('dapos.registrasi.index')
            ->with('success', 'Data registrasi berhasil diperbarui.');
    }

    public function destroy(Registrasi $registrasi)
    {
        $registrasi->delete();
        return redirect()->route('dapos.registrasi.index')
            ->with('success', 'Data registrasi berhasil dihapus.');
    }
}
