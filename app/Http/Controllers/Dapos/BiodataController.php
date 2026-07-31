<?php

namespace App\Http\Controllers\Dapos;

use App\Http\Controllers\Controller;
use App\Models\Siswa;
use App\Models\Agama;
use App\Models\Pekerjaan;
use App\Models\Penghasilan;
use App\Models\Rombel;
use App\Models\JenjangPendidikan;
use App\Models\JenisTinggal;
use App\Models\AlatTransportasi;
use Illuminate\Http\Request;

class BiodataController extends Controller
{
    public function index(Request $request)
    {
        $query = Siswa::with('agama', 'rombelAktif');

        if ($request->filled('q')) {
            $q = $request->q;
            $query->where(function ($w) use ($q) {
                $w->where('nama', 'like', "%{$q}%")
                  ->orWhere('nisn', 'like', "%{$q}%")
                  ->orWhere('nik', 'like', "%{$q}%");
            });
        }

        if ($request->filled('jenis_kelamin')) {
            $query->where('jenis_kelamin', $request->jenis_kelamin);
        }

        if ($request->filled('agama_id')) {
            $query->where('agama_id', $request->agama_id);
        }

        if ($request->filled('rombel_id')) {
            $query->whereHas('anggotaRombel', function ($q) use ($request) {
                $q->where('rombel_id', $request->rombel_id);
            });
        }

        $siswa = $query->latest()->paginate(20);
        $agamaList = Agama::all();
        $rombelList = Rombel::orderBy('tingkat')->orderBy('nama')->get();
        return view('dapos.biodata.index', compact('siswa', 'agamaList', 'rombelList'));
    }

    public function trash(Request $request)
    {
        $query = Siswa::onlyTrashed()->with('agama');

        if ($request->filled('q')) {
            $q = $request->q;
            $query->where(function ($w) use ($q) {
                $w->where('nama', 'like', "%{$q}%")
                  ->orWhere('nisn', 'like', "%{$q}%");
            });
        }

        $siswa = $query->latest('deleted_at')->paginate(20);
        return view('dapos.biodata.trash', compact('siswa'));
    }

    public function restore($id)
    {
        $siswa = Siswa::onlyTrashed()->findOrFail($id);
        $siswa->restore();

        return redirect()->route('dapos.biodata.trash')
            ->with('success', 'Data siswa ' . $siswa->nama . ' berhasil direstore.');
    }

    public function forceDelete($id)
    {
        $siswa = Siswa::onlyTrashed()->findOrFail($id);
        $nama = $siswa->nama;
        $siswa->forceDelete();

        return redirect()->route('dapos.biodata.trash')
            ->with('success', 'Data siswa ' . $nama . ' berhasil dihapus permanen.');
    }

    protected function formData()
    {
        return [
            'agama' => Agama::orderBy('nama')->get(),
            'pekerjaan' => Pekerjaan::orderBy('nama')->get(),
            'penghasilan' => Penghasilan::orderBy('id')->get(),
            'jenjangPendidikan' => JenjangPendidikan::orderBy('id')->get(),
            'jenisTinggal' => JenisTinggal::orderBy('id')->get(),
            'alatTransportasi' => AlatTransportasi::orderBy('id')->get(),
        ];
    }

    public function create()
    {
        return view('dapos.biodata.create', $this->formData());
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:150',
            'jenis_kelamin' => 'required|in:L,P',
            'nisn' => 'nullable|string|max:20|unique:siswa,nisn',
            'nik' => 'nullable|string|max:30',
            'no_kk' => 'nullable|string|max:30',
            'tempat_lahir' => 'nullable|string|max:100',
            'tanggal_lahir' => 'nullable|date',
            'reg_akta_lahir' => 'nullable|string|max:30',
            'agama_id' => 'nullable|exists:agama,id',
            'kewarganegaraan' => 'nullable|string|max:20',
            'alamat_jalan' => 'nullable|string',
            'rt' => 'nullable|string|max:5',
            'rw' => 'nullable|string|max:5',
            'nama_dusun' => 'nullable|string|max:100',
            'kode_wilayah' => 'nullable|string|max:20',
            'kode_pos' => 'nullable|string|max:10',
            'jenis_tinggal_id' => 'nullable|exists:jenis_tinggal,id',
            'alat_transportasi_id' => 'nullable|exists:alat_transportasi,id',
            'anak_keberapa' => 'nullable|integer|min:1|max:50',
            'nomor_telepon_rumah' => 'nullable|string|max:30',
            'nomor_telepon_seluler' => 'nullable|string|max:30',
            'email' => 'nullable|email|max:100',
            'nama_ayah' => 'nullable|string|max:150',
            'nik_ayah' => 'nullable|string|max:30',
            'tahun_lahir_ayah' => 'nullable|integer|min:1900|max:2030',
            'jenjang_pendidikan_ayah' => 'nullable|exists:jenjang_pendidikan,id',
            'pekerjaan_id_ayah' => 'nullable|exists:pekerjaan,id',
            'penghasilan_id_ayah' => 'nullable|exists:penghasilan,id',
            'nama_ibu_kandung' => 'nullable|string|max:150',
            'nik_ibu' => 'nullable|string|max:30',
            'tahun_lahir_ibu' => 'nullable|integer|min:1900|max:2030',
            'jenjang_pendidikan_ibu' => 'nullable|exists:jenjang_pendidikan,id',
            'pekerjaan_id_ibu' => 'nullable|exists:pekerjaan,id',
            'penghasilan_id_ibu' => 'nullable|exists:penghasilan,id',
            'nama_wali' => 'nullable|string|max:150',
            'nik_wali' => 'nullable|string|max:30',
            'tahun_lahir_wali' => 'nullable|integer|min:1900|max:2030',
            'jenjang_pendidikan_wali' => 'nullable|exists:jenjang_pendidikan,id',
            'pekerjaan_id_wali' => 'nullable|exists:pekerjaan,id',
            'penghasilan_id_wali' => 'nullable|exists:penghasilan,id',
            'kebutuhan_khusus' => 'nullable|string|max:50',
            'sekolah_asal' => 'nullable|string|max:150',
            'catatan' => 'nullable|string',
        ]);

        Siswa::create($validated);

        return redirect()->route('dapos.biodata.index')
            ->with('success', 'Data siswa berhasil ditambahkan.');
    }

    public function show(Siswa $biodatum)
    {
        $biodatum->load([
            'agama', 'pekerjaanAyah', 'pekerjaanIbu', 'pekerjaanWali',
            'penghasilanAyah', 'penghasilanIbu', 'penghasilanWali',
            'jenisTinggal', 'alatTransportasi',
            'jenjangPendidikanAyah', 'jenjangPendidikanIbu', 'jenjangPendidikanWali',
            'kodeWilayah',
            'registrasi', 'periodik', 'rombelAktif',
        ]);
        return view('dapos.biodata.show', compact('biodatum'));
    }

    public function edit(Siswa $biodatum)
    {
        return view('dapos.biodata.edit', array_merge(['biodatum' => $biodatum], $this->formData()));
    }

    public function update(Request $request, Siswa $biodatum)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:150',
            'jenis_kelamin' => 'required|in:L,P',
            'nisn' => 'nullable|string|max:20|unique:siswa,nisn,' . $biodatum->id,
            'nik' => 'nullable|string|max:30',
            'no_kk' => 'nullable|string|max:30',
            'tempat_lahir' => 'nullable|string|max:100',
            'tanggal_lahir' => 'nullable|date',
            'reg_akta_lahir' => 'nullable|string|max:30',
            'agama_id' => 'nullable|exists:agama,id',
            'kewarganegaraan' => 'nullable|string|max:20',
            'alamat_jalan' => 'nullable|string',
            'rt' => 'nullable|string|max:5',
            'rw' => 'nullable|string|max:5',
            'nama_dusun' => 'nullable|string|max:100',
            'kode_wilayah' => 'nullable|string|max:20',
            'kode_pos' => 'nullable|string|max:10',
            'jenis_tinggal_id' => 'nullable|exists:jenis_tinggal,id',
            'alat_transportasi_id' => 'nullable|exists:alat_transportasi,id',
            'anak_keberapa' => 'nullable|integer|min:1|max:50',
            'nomor_telepon_rumah' => 'nullable|string|max:30',
            'nomor_telepon_seluler' => 'nullable|string|max:30',
            'email' => 'nullable|email|max:100',
            'nama_ayah' => 'nullable|string|max:150',
            'nik_ayah' => 'nullable|string|max:30',
            'tahun_lahir_ayah' => 'nullable|integer|min:1900|max:2030',
            'jenjang_pendidikan_ayah' => 'nullable|exists:jenjang_pendidikan,id',
            'pekerjaan_id_ayah' => 'nullable|exists:pekerjaan,id',
            'penghasilan_id_ayah' => 'nullable|exists:penghasilan,id',
            'nama_ibu_kandung' => 'nullable|string|max:150',
            'nik_ibu' => 'nullable|string|max:30',
            'tahun_lahir_ibu' => 'nullable|integer|min:1900|max:2030',
            'jenjang_pendidikan_ibu' => 'nullable|exists:jenjang_pendidikan,id',
            'pekerjaan_id_ibu' => 'nullable|exists:pekerjaan,id',
            'penghasilan_id_ibu' => 'nullable|exists:penghasilan,id',
            'nama_wali' => 'nullable|string|max:150',
            'nik_wali' => 'nullable|string|max:30',
            'tahun_lahir_wali' => 'nullable|integer|min:1900|max:2030',
            'jenjang_pendidikan_wali' => 'nullable|exists:jenjang_pendidikan,id',
            'pekerjaan_id_wali' => 'nullable|exists:pekerjaan,id',
            'penghasilan_id_wali' => 'nullable|exists:penghasilan,id',
            'kebutuhan_khusus' => 'nullable|string|max:50',
            'sekolah_asal' => 'nullable|string|max:150',
            'catatan' => 'nullable|string',
        ]);

        $biodatum->update($validated);

        return redirect()->route('dapos.biodata.index')
            ->with('success', 'Data siswa berhasil diperbarui.');
    }

    public function destroy(Siswa $biodatum)
    {
        $biodatum->delete();
        return redirect()->route('dapos.biodata.index')
            ->with('success', 'Data siswa berhasil dihapus.');
    }
}
