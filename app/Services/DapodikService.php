<?php

namespace App\Services;

use App\Models\DapodikConfig;
use App\Models\Periodik;
use App\Models\Siswa;
use App\Models\Rombel;
use App\Models\AnggotaRombel;
use App\Models\Pembelajaran;
use App\Models\Registrasi;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class DapodikService
{
    protected ?DapodikConfig $config;

    protected string $webServiceBase = '/WebService';

    protected string $restBase = '/rest';

    public function __construct()
    {
        $this->config = DapodikConfig::first();
    }

    public function isConfigured(): bool
    {
        return $this->config !== null
            && !empty($this->config->token)
            && !empty($this->config->npsn);
    }

    public function getBaseUrl(): string
    {
        return rtrim($this->config->base_url ?? 'http://localhost:5774', '/');
    }

    public function webServiceUrl(string $endpoint): string
    {
        return $this->getBaseUrl() . $this->webServiceBase . '/' . ltrim($endpoint, '/');
    }

    public function restUrl(string $path): string
    {
        return $this->getBaseUrl() . $this->restBase . '/' . ltrim($path, '/');
    }

    protected function wsHeaders(): array
    {
        return [
            'Authorization' => 'Bearer ' . ($this->config->token ?? ''),
            'Accept' => 'application/json',
        ];
    }

    protected function restHeaders(): array
    {
        return [
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
        ];
    }

    protected function wsGet(string $endpoint, array $params = [], int $timeout = 30): ?array
    {
        if (!$this->isConfigured()) return null;

        try {
            $url = $this->webServiceUrl($endpoint);
            $response = Http::withHeaders($this->wsHeaders())
                ->timeout($timeout)
                ->get($url, array_merge(['npsn' => $this->config->npsn], $params));

            if ($response->successful()) {
                $data = $response->json();
                return $data['rows'] ?? [];
            }

            if ($response->status() === 404) {
                Log::warning("Dapodik endpoint tidak ditemukan: {$endpoint}");
                return null;
            }
        } catch (\Exception $e) {
            Log::error("Dapodik {$endpoint}: " . $e->getMessage());
        }

        return [];
    }

    public function testConnection(): array
    {
        if (!$this->isConfigured()) {
            return ['success' => false, 'message' => 'Lengkapi Base URL, Token, dan NPSN terlebih dahulu'];
        }

        try {
            $url = $this->webServiceUrl('/getSekolah');
            $response = Http::withHeaders($this->wsHeaders())
                ->timeout(10)
                ->get($url, ['npsn' => $this->config->npsn]);

            if ($response->successful()) {
                $data = $response->json();
                $sekolah = $data['rows'] ?? [];
                $namaSekolah = $sekolah['nama'] ?? 'N/A';

                return [
                    'success' => true,
                    'message' => "Koneksi berhasil! Sekolah: {$namaSekolah}",
                    'data' => ['sekolah' => $sekolah],
                ];
            }

            $status = $response->status();
            if ($status === 403) return ['success' => false, 'message' => 'Akses ditolak (403) - pastikan NPSN dan Token benar'];
            if ($status === 401) return ['success' => false, 'message' => 'Token tidak valid (401) - generate ulang token di Dapodik'];

            return ['success' => false, 'message' => "Gagal: HTTP {$status}"];

        } catch (\Exception $e) {
            return ['success' => false, 'message' => 'Error: ' . $e->getMessage()];
        }
    }

    public function saveConfig(array $data): array
    {
        if ($this->config) {
            $this->config->update($data);
        } else {
            $this->config = DapodikConfig::create($data);
        }
        return ['success' => true, 'message' => 'Konfigurasi disimpan'];
    }

    protected function getSemesterId(): string
    {
        $year = date('Y');
        $month = date('n');
        return $month >= 7 ? $year . '1' : ($year - 1) . '2';
    }

    // -----------------------------------------------------------------------
    // READ: DAPODIK -> LOCAL
    // -----------------------------------------------------------------------

    // -- SISWA --

    public function getSiswaList(): ?array
    {
        return $this->wsGet('/getPesertaDidik', ['semester_id' => $this->getSemesterId()]);
    }

    public function importSiswaFromDapodik(): array
    {
        if (!$this->isConfigured()) {
            return ['success' => false, 'message' => 'Lengkapi Base URL, Token, dan NPSN terlebih dahulu'];
        }

        $siswaList = $this->getSiswaList();
        if ($siswaList === null) {
            return ['success' => false, 'message' => 'Endpoint siswa (getPesertaDidik) tidak tersedia di server Dapodik'];
        }
        if (empty($siswaList)) {
            return ['success' => false, 'message' => 'Tidak ada data siswa dari Dapodik'];
        }

        $imported = 0;
        $updated = 0;
        $periodikImported = 0;
        $errors = [];

        $fieldMap = [
            'nisn', 'nik', 'no_kk', 'nama', 'jenis_kelamin', 'tempat_lahir', 'tanggal_lahir',
            'reg_akta_lahir', 'agama_id', 'kewarganegaraan', 'alamat_jalan', 'rt', 'rw',
            'nama_dusun', 'kode_wilayah', 'kode_pos', 'lintang', 'bujur', 'jenis_tinggal_id',
            'alat_transportasi_id', 'anak_keberapa', 'nomor_telepon_rumah', 'nomor_telepon_seluler',
            'email', 'nama_ayah', 'nik_ayah', 'tahun_lahir_ayah', 'jenjang_pendidikan_ayah',
            'pekerjaan_id_ayah', 'penghasilan_id_ayah', 'nama_ibu_kandung', 'nik_ibu',
            'tahun_lahir_ibu', 'jenjang_pendidikan_ibu', 'pekerjaan_id_ibu', 'penghasilan_id_ibu',
            'nama_wali', 'nik_wali', 'tahun_lahir_wali', 'jenjang_pendidikan_wali',
            'pekerjaan_id_wali', 'penghasilan_id_wali', 'kebutuhan_khusus', 'sekolah_asal',
            'catatan', 'foto',
        ];

        foreach ($siswaList as $dapodikSiswa) {
            $nisn = $dapodikSiswa['nisn'] ?? null;
            if (!$nisn) continue;

            $data = [];
            foreach ($fieldMap as $field) {
                if (array_key_exists($field, $dapodikSiswa)) {
                    $data[$field] = $dapodikSiswa[$field];
                }
            }

            try {
                $data['dapodik_id'] = $dapodikSiswa['peserta_didik_id'] ?? null;

                $siswa = Siswa::withTrashed()->where('nisn', $nisn)->first();
                if ($siswa) {
                    if ($siswa->trashed()) $siswa->restore();
                    $siswa->update($data);
                    $updated++;
                } else {
                    $siswa = Siswa::create($data);
                    $imported++;
                }

                $periodikFields = ['tinggi_badan', 'berat_badan', 'lingkar_kepala', 'jarak_rumah_sekolah', 'waktu_tempuh', 'jumlah_saudara_kandung'];
                $hasPeriodik = false;
                $periodikData = ['siswa_id' => $siswa->id, 'tahun_periodik' => date('Y')];
                foreach ($periodikFields as $pf) {
                    if (!empty($dapodikSiswa[$pf])) {
                        $periodikData[$pf] = $dapodikSiswa[$pf];
                        $hasPeriodik = true;
                    }
                }

                if ($hasPeriodik) {
                    $existing = Periodik::where('siswa_id', $siswa->id)
                        ->where('tahun_periodik', date('Y'))->first();
                    if ($existing) {
                        $existing->update($periodikData);
                    } else {
                        $periodikData['sync_status'] = 'synced';
                        $periodikData['dapodik_id'] = $dapodikSiswa['peserta_didik_id'] ?? null;
                        Periodik::create($periodikData);
                        $periodikImported++;
                    }
                }
            } catch (\Exception $e) {
                $errors[] = "NISN {$nisn}: " . $e->getMessage();
                Log::error("Import siswa error NISN {$nisn}: " . $e->getMessage());
            }
        }

        $msg = "Import selesai: {$imported} siswa baru, {$updated} diperbarui";
        if ($periodikImported > 0) $msg .= ", {$periodikImported} data periodik";
        if (!empty($errors)) $msg .= ', ' . count($errors) . ' error';

        return ['success' => true, 'message' => $msg];
    }

    // -- REGISTRASI --

    public function importRegistrasiFromDapodik(): array
    {
        if (!$this->isConfigured()) {
            return ['success' => false, 'message' => 'Lengkapi Base URL, Token, dan NPSN terlebih dahulu'];
        }

        $siswaList = $this->getSiswaList();
        if ($siswaList === null) {
            return ['success' => false, 'message' => 'Endpoint siswa (getPesertaDidik) tidak tersedia di server Dapodik'];
        }
        if (empty($siswaList)) {
            return ['success' => false, 'message' => 'Tidak ada data siswa dari Dapodik'];
        }

        $imported = 0;
        $updated = 0;
        $errors = [];

        foreach ($siswaList as $dapodikSiswa) {
            $nisn = $dapodikSiswa['nisn'] ?? null;
            if (!$nisn) continue;

            $siswa = Siswa::where('nisn', $nisn)->first();
            if (!$siswa) continue;

            try {
                $jenisDaftarId = $dapodikSiswa['jenis_pendaftaran_id'] ?? null;
                if ($jenisDaftarId && !\Illuminate\Support\Facades\DB::table('jenis_daftar')
                    ->where('id', $jenisDaftarId)->exists()) {
                    $jenisDaftarId = null;
                }

                $data = [
                    'siswa_id' => $siswa->id,
                    'jenis_daftar_id' => $jenisDaftarId,
                    'nis' => $dapodikSiswa['nipd'] ?? null,
                    'sekolah_asal' => $dapodikSiswa['sekolah_asal'] ?? null,
                    'tanggal_masuk' => $dapodikSiswa['tanggal_masuk_sekolah'] ?? null,
                ];

                $existing = Registrasi::where('siswa_id', $siswa->id)->first();
                if ($existing) {
                    $existing->update($data);
                    $updated++;
                } else {
                    Registrasi::create($data);
                    $imported++;
                }
            } catch (\Exception $e) {
                $errors[] = "NISN {$nisn}: " . $e->getMessage();
                Log::error("Import registrasi error NISN {$nisn}: " . $e->getMessage());
            }
        }

        $msg = "Import selesai: {$imported} registrasi baru, {$updated} diperbarui";
        if (!empty($errors)) $msg .= ', ' . count($errors) . ' error';

        return ['success' => true, 'message' => $msg];
    }

    // -- ROMBEL --

    public function getRombelList(): ?array
    {
        return $this->wsGet('/getRombonganBelajar', ['semester_id' => $this->getSemesterId()]);
    }

    public function importRombelFromDapodik(): array
    {
        if (!$this->isConfigured()) {
            return ['success' => false, 'message' => 'Lengkapi konfigurasi Dapodik terlebih dahulu'];
        }

        $list = $this->getRombelList();
        if ($list === null) {
            return ['success' => false, 'message' => 'Endpoint rombel (getRombonganBelajar) tidak tersedia di server Dapodik ini'];
        }
        if (empty($list)) {
            return ['success' => false, 'message' => 'Tidak ada data rombel dari Dapodik'];
        }

        $imported = 0;
        $updated = 0;
        $errors = [];

        foreach ($list as $item) {
            $namaRombel = $item['nama'] ?? $item['rombongan_belajar'] ?? null;
            if (!$namaRombel) continue;

            try {
                $semesterId = $item['semester_id'] ?? null;
                $tahunAjaran = null;
                if ($semesterId) {
                    $tahun = (int) substr($semesterId, 0, 4);
                    $tahunAjaran = $tahun > 0 ? $tahun . '/' . ($tahun + 1) : null;
                }

                $data = [
                    'nama' => $namaRombel,
                    'tingkat' => $item['tingkat_pendidikan_id_str'] ?? $item['tingkat_pendidikan'] ?? $item['tingkat'] ?? $item['tingkat_pendidikan_id'] ?? '-',
                    'jurusan' => $item['jurusan'] ?? $item['jurusan_nama'] ?? null,
                    'kurikulum' => $item['kurikulum_id_str'] ?? $item['kurikulum'] ?? null,
                    'tahun_ajaran' => $tahunAjaran ?? $item['tahun_ajaran'] ?? $item['semester_id'] ?? date('Y') . '/' . (date('Y') + 1),
                    'nama_wali_kelas' => $item['ptk_id_str'] ?? $item['wali_kelas'] ?? $item['nama_wali'] ?? null,
                    'nip_wali_kelas' => $item['nip_wali'] ?? null,
                    'ruangan' => $item['id_ruang_str'] ?? $item['ruangan'] ?? null,
                    'kapasitas' => $item['kapasitas'] ?? null,
                ];

                $rombel = Rombel::where('nama', $namaRombel)
                    ->where('tahun_ajaran', $data['tahun_ajaran'])
                    ->first();

                if ($rombel) {
                    $rombel->update($data);
                    $updated++;
                } else {
                    $rombel = Rombel::create($data);
                    $imported++;
                }

                // Import anggota rombel if available
                $anggotaList = $item['anggota_rombel'] ?? $item['peserta_didik'] ?? [];
                if (!empty($anggotaList) && is_array($anggotaList)) {
                    $this->syncAnggotaRombel($rombel, $anggotaList);
                }
            } catch (\Exception $e) {
                $errors[] = "Rombel {$namaRombel}: " . $e->getMessage();
                Log::error("Import rombel error: " . $e->getMessage());
            }
        }

        $msg = "Import selesai: {$imported} rombel baru, {$updated} diperbarui";
        if (!empty($errors)) $msg .= ', ' . count($errors) . ' error';
        return ['success' => true, 'message' => $msg];
    }

    protected function syncAnggotaRombel(Rombel $rombel, array $anggotaList): void
    {
        $existingIds = $rombel->anggota()->pluck('siswa_id')->toArray();
        $newCount = 0;

        foreach ($anggotaList as $anggota) {
            $dapodikId = $anggota['peserta_didik_id'] ?? null;
            if (!$dapodikId) continue;

            $siswa = Siswa::where('dapodik_id', $dapodikId)->first();
            if (!$siswa) {
                $nisn = $anggota['nisn'] ?? null;
                if ($nisn) {
                    $siswa = Siswa::where('nisn', $nisn)->first();
                }
            }
            if (!$siswa) continue;

            if (!in_array($siswa->id, $existingIds)) {
                AnggotaRombel::create([
                    'rombel_id' => $rombel->id,
                    'siswa_id' => $siswa->id,
                ]);
                $newCount++;
            }
        }

        if ($newCount > 0) {
            $rombel->increment('jumlah_anggota', $newCount);
        }
    }

    public function getAnggotaRombelList(): ?array
    {
        return $this->wsGet('/getAnggotaRombel', ['semester_id' => $this->getSemesterId()]);
    }

    public function importAnggotaRombelFromDapodik(): array
    {
        if (!$this->isConfigured()) {
            return ['success' => false, 'message' => 'Lengkapi konfigurasi Dapodik terlebih dahulu'];
        }

        $rombelList = $this->getRombelList();
        if ($rombelList === null) {
            return ['success' => false, 'message' => 'Endpoint rombel tidak tersedia — gagal import anggota rombel'];
        }
        if (empty($rombelList)) {
            return ['success' => false, 'message' => 'Tidak ada data rombel — gagal import anggota rombel'];
        }

        $imported = 0;
        $errors = [];

        foreach ($rombelList as $item) {
            $anggotaList = $item['anggota_rombel'] ?? [];
            $rombelNama = $item['nama'] ?? null;
            if (!$rombelNama || empty($anggotaList)) continue;

            try {
                $rombel = Rombel::where('nama', $rombelNama)->first();
                if (!$rombel) continue;

                $existingIds = $rombel->anggota()->pluck('siswa_id')->toArray();

                foreach ($anggotaList as $anggota) {
                    $dapodikId = $anggota['peserta_didik_id'] ?? null;
                    if (!$dapodikId) continue;

                    $siswa = Siswa::where('dapodik_id', $dapodikId)->first();
                    if (!$siswa) {
                        $nisn = $anggota['nisn'] ?? null;
                        if ($nisn) {
                            $siswa = Siswa::where('nisn', $nisn)->first();
                        }
                    }
                    if (!$siswa) continue;

                    if (!in_array($siswa->id, $existingIds)) {
                        AnggotaRombel::create([
                            'rombel_id' => $rombel->id,
                            'siswa_id' => $siswa->id,
                        ]);
                        $rombel->increment('jumlah_anggota');
                        $imported++;
                    }
                }
            } catch (\Exception $e) {
                $errors[] = "Rombel {$rombelNama}: " . $e->getMessage();
            }
        }

        $msg = "Import selesai: {$imported} anggota rombel baru";
        if (!empty($errors)) $msg .= ', ' . count($errors) . ' error';
        return ['success' => true, 'message' => $msg];
    }

    // -- PEMBELAJARAN --

    public function getPembelajaranList(): ?array
    {
        return $this->wsGet('/getPembelajaran', ['semester_id' => $this->getSemesterId()]);
    }

    public function importPembelajaranFromDapodik(): array
    {
        if (!$this->isConfigured()) {
            return ['success' => false, 'message' => 'Lengkapi konfigurasi Dapodik terlebih dahulu'];
        }

        $rombelList = $this->getRombelList();
        if ($rombelList === null) {
            return ['success' => false, 'message' => 'Endpoint rombel tidak tersedia — gagal import pembelajaran'];
        }
        if (empty($rombelList)) {
            return ['success' => false, 'message' => 'Tidak ada data rombel — gagal import pembelajaran'];
        }

        $imported = 0;
        $updated = 0;
        $errors = [];

        $guruByUuid = [];
        foreach ($rombelList as $rombelItem) {
            if (!empty($rombelItem['ptk_id']) && !empty($rombelItem['ptk_id_str'])) {
                $guruByUuid[$rombelItem['ptk_id']] = $rombelItem['ptk_id_str'];
            }
        }

        foreach ($rombelList as $rombelItem) {
            $pembelajaranList = $rombelItem['pembelajaran'] ?? [];
            if (empty($pembelajaranList)) continue;

            $rombelNama = $rombelItem['nama'] ?? null;
            $rombel = $rombelNama ? Rombel::where('nama', $rombelNama)->first() : null;
            if (!$rombel) continue;

            foreach ($pembelajaranList as $item) {
                $mataPelajaran = $item['mata_pelajaran'] ?? $item['nama_mata_pelajaran'] ?? null;
                if (!$mataPelajaran) continue;

                try {
                    $data = [
                        'rombel_id' => $rombel->id,
                        'mata_pelajaran' => $mataPelajaran,
                        'jam_mengajar' => $item['jam_mengajar_per_minggu'] ?? $item['jam_mengajar'] ?? null,
                        'nama_guru' => $guruByUuid[$item['ptk_id'] ?? null] ?? null,
                    ];

                    $existing = Pembelajaran::where('mata_pelajaran', $mataPelajaran)
                        ->where('rombel_id', $rombel->id)->first();

                    if ($existing) {
                        $existing->update($data);
                        $updated++;
                    } else {
                        Pembelajaran::create($data);
                        $imported++;
                    }
                } catch (\Exception $e) {
                    $errors[] = "Mapel {$mataPelajaran}: " . $e->getMessage();
                    Log::error("Import pembelajaran error: " . $e->getMessage());
                }
            }
        }

        $msg = "Import selesai: {$imported} pembelajaran baru, {$updated} diperbarui";
        if (!empty($errors)) $msg .= ', ' . count($errors) . ' error';
        return ['success' => true, 'message' => $msg];
    }

    // -----------------------------------------------------------------------
    // WRITE: LOCAL -> DAPODIK (REST)
    // -----------------------------------------------------------------------

    protected function restPut(string $path, array $payload): array
    {
        try {
            $url = $this->restUrl($path);
            $response = Http::withOptions(['query' => ['token' => $this->config->token]])
                ->withHeaders($this->restHeaders())
                ->timeout(15)
                ->put($url, $payload);

            if ($response->successful()) {
                return ['success' => true, 'message' => 'Berhasil', 'data' => $response->json()];
            }

            return ['success' => false, 'message' => "HTTP {$response->status()}: " . $response->body()];
        } catch (\Exception $e) {
            return ['success' => false, 'message' => 'Error: ' . $e->getMessage()];
        }
    }

    protected function restPost(string $path, array $payload): array
    {
        try {
            $url = $this->restUrl($path);
            $response = Http::withOptions(['query' => ['token' => $this->config->token]])
                ->withHeaders($this->restHeaders())
                ->timeout(15)
                ->post($url, $payload);

            if ($response->successful()) {
                return ['success' => true, 'message' => 'Berhasil', 'data' => $response->json()];
            }

            return ['success' => false, 'message' => "HTTP {$response->status()}: " . $response->body()];
        } catch (\Exception $e) {
            return ['success' => false, 'message' => 'Error: ' . $e->getMessage()];
        }
    }

    // -- PERIODIK --

    public function syncPeriodik(Periodik $periodik, array $dapodikSiswa = null): array
    {
        if (!$this->isConfigured()) {
            return ['success' => false, 'message' => 'Konfigurasi Dapodik belum diatur'];
        }

        $siswa = $periodik->siswa;
        if (!$siswa || !$siswa->nisn) {
            return ['success' => false, 'message' => 'Siswa tidak memiliki NISN'];
        }

        if (!$dapodikSiswa) {
            $dapodikSiswa = $this->cariSiswaDiDapodik($siswa->nisn);
            if (!$dapodikSiswa) {
                return ['success' => false, 'message' => "NISN {$siswa->nisn} tidak ditemukan di Dapodik"];
            }
        }

        $payload = [
            'nisn' => $siswa->nisn,
            'tinggi_badan' => (string) $periodik->tinggi_badan,
            'berat_badan' => (string) $periodik->berat_badan,
            'lingkar_kepala' => (string) $periodik->lingkar_kepala,
            'jarak_rumah_sekolah' => (string) $periodik->jarak_rumah_sekolah,
            'waktu_tempuh' => (string) $periodik->waktu_tempuh,
            'jumlah_saudara_kandung' => (string) $periodik->jumlah_saudara_kandung,
        ];

        $result = $this->restPut('/periodik', $payload);

        if ($result['success']) {
            $periodik->update([
                'dapodik_id' => $dapodikSiswa['peserta_didik_id'] ?? $dapodikSiswa['registrasi_id'] ?? null,
                'sync_status' => 'synced',
                'last_sync_at' => now(),
            ]);
            return ['success' => true, 'message' => "NISN {$siswa->nisn}: sinkron berhasil"];
        }

        $periodik->update(['sync_status' => 'failed']);
        return $result;
    }

    public function syncAll(): array
    {
        $results = ['success' => true, 'synced' => 0, 'failed' => 0, 'errors' => []];

        $siswaDapodik = $this->getSiswaList();
        if (!is_array($siswaDapodik) || empty($siswaDapodik)) {
            return ['success' => false, 'message' => 'Gagal mengambil data siswa dari Dapodik'];
        }

        $siswaMap = [];
        foreach ($siswaDapodik as $s) {
            if (!empty($s['nisn'])) $siswaMap[$s['nisn']] = $s;
        }

        $periodikList = Periodik::with('siswa')
            ->whereHas('siswa', fn($q) => $q->whereNotNull('nisn'))
            ->where(function ($q) {
                $q->where('sync_status', '!=', 'synced')
                  ->orWhereNull('sync_status');
            })
            ->get();

        foreach ($periodikList as $periodik) {
            $siswa = $periodik->siswa;
            if (!$siswa || !$siswa->nisn) continue;

            $dapodikSiswa = $siswaMap[$siswa->nisn] ?? null;
            if (!$dapodikSiswa) {
                $results['errors'][] = "NISN {$siswa->nisn} tidak ditemukan di Dapodik";
                $results['failed']++;
                $periodik->update(['sync_status' => 'failed']);
                continue;
            }

            $syncResult = $this->syncPeriodik($periodik, $dapodikSiswa);
            if ($syncResult['success']) {
                $results['synced']++;
            } else {
                $results['errors'][] = $syncResult['message'];
                $results['failed']++;
            }
        }

        return $results;
    }

    // -- SISWA (biodata) --

    public function syncSiswa(Siswa $siswa): array
    {
        if (!$this->isConfigured()) {
            return ['success' => false, 'message' => 'Konfigurasi Dapodik belum diatur'];
        }

        $payload = [];
        foreach ($siswa->fillable as $field) {
            $payload[$field] = $siswa->$field;
        }
        $payload['nisn'] = $siswa->nisn;

        $pesertaDidikId = $this->cariSiswaDiDapodik($siswa->nisn)['peserta_didik_id'] ?? null;
        if ($pesertaDidikId) {
            return $this->restPut("/peserta_didik/{$pesertaDidikId}", $payload);
        }

        return $this->restPost('/peserta_didik', $payload);
    }

    // -- ROMBEL --

    public function syncRombel(Rombel $rombel): array
    {
        if (!$this->isConfigured()) {
            return ['success' => false, 'message' => 'Konfigurasi Dapodik belum diatur'];
        }

        $payload = [
            'nama' => $rombel->nama,
            'tingkat' => $rombel->tingkat,
            'tahun_ajaran' => $rombel->tahun_ajaran,
            'nama_wali_kelas' => $rombel->nama_wali_kelas,
        ];

        return $this->restPost('/rombongan_belajar', $payload);
    }

    // -- IMPORT ALL + STATS --

    public function getImportStats(): array
    {
        $stats = [
            'siswa' => ['local' => Siswa::count(), 'dapodik' => 0],
            'registrasi' => ['local' => Registrasi::count(), 'dapodik' => 0],
            'rombel' => ['local' => Rombel::count(), 'dapodik' => 0],
            'pembelajaran' => ['local' => Pembelajaran::count(), 'dapodik' => 0],
        ];

        if ($this->isConfigured()) {
            $siswa = $this->getSiswaList();
            $stats['siswa']['dapodik'] = is_array($siswa) ? count($siswa) : 0;
            $stats['registrasi']['dapodik'] = is_array($siswa) ? count($siswa) : 0;

            $rombel = $this->getRombelList();
            $stats['rombel']['dapodik'] = is_array($rombel) ? count($rombel) : 0;

            // Pembelajaran dihitung dari nested rombel, bukan endpoint terpisah
            $pembCount = 0;
            if (is_array($rombel)) {
                foreach ($rombel as $r) {
                    $pembCount += count($r['pembelajaran'] ?? []);
                }
            }
            $stats['pembelajaran']['dapodik'] = $pembCount;
        }

        return $stats;
    }

    public function importAll(): array
    {
        $order = ['siswa', 'registrasi', 'rombel', 'anggota_rombel', 'pembelajaran'];
        $methods = [
            'siswa' => 'importSiswaFromDapodik',
            'registrasi' => 'importRegistrasiFromDapodik',
            'rombel' => 'importRombelFromDapodik',
            'anggota_rombel' => 'importAnggotaRombelFromDapodik',
            'pembelajaran' => 'importPembelajaranFromDapodik',
        ];

        $results = [];
        $allSuccess = true;
        $messages = [];

        foreach ($order as $key) {
            $result = $this->{$methods[$key]}();
            if ($result['success'] || strpos($result['message'], 'tidak tersedia') === 0) {
                $results[$key] = $result;
                $messages[] = $result['message'];
            } else {
                $results[$key] = $result;
                $messages[] = $result['message'];
                if (!$result['success']) $allSuccess = false;
            }
        }

        return [
            'success' => $allSuccess,
            'message' => "Import All selesai!\n" . implode("\n", $messages),
            'results' => $results,
        ];
    }

    protected function cariSiswaDiDapodik(string $nisn): ?array
    {
        $list = $this->getSiswaList();
        if (!is_array($list)) return null;
        foreach ($list as $s) {
            if (($s['nisn'] ?? '') === $nisn) return $s;
        }
        return null;
    }
}
