<?php

namespace App\Http\Controllers\Dapos;

use App\Http\Controllers\Controller;
use App\Models\Surat;
use App\Models\Siswa;
use Illuminate\Http\Request;

class SuratController extends Controller
{
    public function index(Request $request)
    {
        $query = Surat::with('siswa');

        if ($request->filled('q')) {
            $q = $request->q;
            $query->where(function ($w) use ($q) {
                $w->where('nomor_surat', 'like', "%{$q}%")
                  ->orWhere('jenis_surat', 'like', "%{$q}%")
                  ->orWhereHas('siswa', function ($sw) use ($q) {
                      $sw->where('nama', 'like', "%{$q}%");
                  });
            });
        }

        if ($request->filled('jenis_surat')) {
            $query->where('jenis_surat', $request->jenis_surat);
        }

        $surat = $query->latest()->paginate(20);
        $jenisSuratList = Surat::distinct()->orderBy('jenis_surat')->pluck('jenis_surat');
        return view('dapos.surat.index', compact('surat', 'jenisSuratList'));
    }

    public function create()
    {
        $siswa = Siswa::all();
        return view('dapos.surat.create', compact('siswa'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'jenis_surat' => 'required|string|max:50',
            'nomor_surat' => 'nullable|string|max:100',
            'tgl_surat' => 'nullable|date',
            'siswa_id' => 'nullable|exists:siswa,id',
            'kepada' => 'nullable|string|max:255',
            'isi_surat' => 'nullable|string',
        ]);

        Surat::create($validated);

        return redirect()->route('dapos.surat.index')
            ->with('success', 'Surat berhasil ditambahkan.');
    }

    public function show(Surat $surat)
    {
        $surat->load('siswa');
        return view('dapos.surat.show', compact('surat'));
    }

    public function edit(Surat $surat)
    {
        $siswa = Siswa::all();
        return view('dapos.surat.edit', compact('surat', 'siswa'));
    }

    public function update(Request $request, Surat $surat)
    {
        $validated = $request->validate([
            'jenis_surat' => 'required|string|max:50',
            'nomor_surat' => 'nullable|string|max:100',
            'tgl_surat' => 'nullable|date',
            'siswa_id' => 'nullable|exists:siswa,id',
            'kepada' => 'nullable|string|max:255',
            'isi_surat' => 'nullable|string',
        ]);

        $surat->update($validated);

        return redirect()->route('dapos.surat.index')
            ->with('success', 'Surat berhasil diperbarui.');
    }

    public function destroy(Surat $surat)
    {
        $surat->delete();
        return redirect()->route('dapos.surat.index')
            ->with('success', 'Surat berhasil dihapus.');
    }
}
