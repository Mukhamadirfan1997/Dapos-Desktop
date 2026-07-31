<?php

namespace App\Http\Controllers\Dapos;

use App\Http\Controllers\Controller;
use App\Models\DapodikConfig;
use App\Models\Periodik;
use App\Models\Siswa;
use App\Services\DapodikService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class DapodikSettingController extends Controller
{
    protected DapodikService $dapodik;

    public function __construct(DapodikService $dapodik)
    {
        $this->dapodik = $dapodik;
    }

    protected function importStats()
    {
        return Cache::remember('dapodik_import_stats', now()->addMinutes(5), function () {
            return $this->dapodik->getImportStats();
        });
    }

    protected function flushImportStats(): void
    {
        Cache::forget('dapodik_import_stats');
    }

    protected function flushSiswaMap(): void
    {
        Cache::forget('dapodik_siswa_map');
    }

    public function index()
    {
        $config = DapodikConfig::first();
        return view('dapos.dapodik.setting', compact('config'));
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'base_url' => 'required|url',
            'token' => 'required|string',
            'npsn' => 'required|string|max:20',
            'tahun_ajaran' => 'nullable|string|max:20',
        ]);

        $this->dapodik->saveConfig($validated);

        if ($request->boolean('test')) {
            $result = $this->dapodik->testConnection();
            return redirect()->route('dapos.dapodik.setting')
                ->with($result['success'] ? 'success' : 'error', $result['message']);
        }

        return redirect()->route('dapos.dapodik.setting')
            ->with('success', 'Konfigurasi disimpan');
    }

    public function importPage()
    {
        $stats = $this->importStats();
        $config = DapodikConfig::first();
        return view('dapos.dapodik.import', compact('stats', 'config'));
    }

    public function importSiswa()
    {
        if (!$this->dapodik->isConfigured()) {
            return redirect()->route('dapos.dapodik.import')
                ->with('error', 'Konfigurasi Dapodik belum lengkap');
        }

        $result = $this->dapodik->importSiswaFromDapodik();
        $this->flushImportStats();

        return redirect()->route('dapos.dapodik.import')
            ->with($result['success'] ? 'success' : 'error', $result['message']);
    }

    public function importRegistrasi()
    {
        if (!$this->dapodik->isConfigured()) {
            return redirect()->route('dapos.dapodik.import')
                ->with('error', 'Konfigurasi Dapodik belum lengkap');
        }

        $result = $this->dapodik->importRegistrasiFromDapodik();
        $this->flushImportStats();

        return redirect()->route('dapos.dapodik.import')
            ->with($result['success'] ? 'success' : 'error', $result['message']);
    }

    public function importRombel()
    {
        if (!$this->dapodik->isConfigured()) {
            return redirect()->route('dapos.dapodik.import')
                ->with('error', 'Konfigurasi Dapodik belum lengkap');
        }

        $result = $this->dapodik->importRombelFromDapodik();
        $this->flushImportStats();

        return redirect()->route('dapos.dapodik.import')
            ->with($result['success'] ? 'success' : 'error', $result['message']);
    }

    public function importAnggotaRombel()
    {
        if (!$this->dapodik->isConfigured()) {
            return redirect()->route('dapos.dapodik.import')
                ->with('error', 'Konfigurasi Dapodik belum lengkap');
        }

        $result = $this->dapodik->importAnggotaRombelFromDapodik();
        $this->flushImportStats();

        return redirect()->route('dapos.dapodik.import')
            ->with($result['success'] ? 'success' : 'error', $result['message']);
    }

    public function importPembelajaran()
    {
        if (!$this->dapodik->isConfigured()) {
            return redirect()->route('dapos.dapodik.import')
                ->with('error', 'Konfigurasi Dapodik belum lengkap');
        }

        $result = $this->dapodik->importPembelajaranFromDapodik();
        $this->flushImportStats();

        return redirect()->route('dapos.dapodik.import')
            ->with($result['success'] ? 'success' : 'error', $result['message']);
    }

    public function importAll()
    {
        if (!$this->dapodik->isConfigured()) {
            return redirect()->route('dapos.dapodik.import')
                ->with('error', 'Konfigurasi Dapodik belum lengkap');
        }

        $result = $this->dapodik->importAll();
        $this->flushImportStats();

        return redirect()->route('dapos.dapodik.import')
            ->with($result['success'] ? 'success' : 'error', $result['message']);
    }

    public function syncAll()
    {
        if (!$this->dapodik->isConfigured()) {
            return redirect()->route('dapos.dapodik.import')
                ->with('error', 'Konfigurasi Dapodik belum lengkap');
        }

        $result = $this->dapodik->syncAll();

        $message = "Sinkron selesai: {$result['synced']} berhasil, {$result['failed']} gagal";
        if (!empty($result['errors'])) {
            $message .= ' | ' . implode('; ', array_slice($result['errors'], 0, 3));
        }

        return redirect()->route('dapos.dapodik.import')
            ->with($result['failed'] > 0 ? 'warning' : 'success', $message);
    }

    public function syncOne(Periodik $periodik)
    {
        $result = $this->dapodik->syncPeriodik($periodik);

        return redirect()->route('dapos.periodik.index')
            ->with($result['success'] ? 'success' : 'error', $result['message']);
    }

    protected function getDapodikSiswaMap(): array
    {
        return Cache::remember('dapodik_siswa_map', now()->addMinutes(10), function () {
            $map = [];
            $list = $this->dapodik->getSiswaList();
            if (is_array($list)) {
                foreach ($list as $s) {
                    if (!empty($s['nisn'])) {
                        $map[$s['nisn']] = $s;
                    }
                }
            }
            return $map;
        });
    }

    public function syncPage()
    {
        $config = DapodikConfig::first();
        $periodikTotal = Periodik::count();
        $periodikPending = Periodik::whereHas('siswa', fn ($q) => $q->whereNotNull('nisn'))
            ->where(fn ($q) => $q->where('sync_status', '!=', 'synced')->orWhereNull('sync_status'))
            ->count();
        $periodikSynced = Periodik::where('sync_status', 'synced')->count();
        $siswaTotal = Siswa::count();
        $siswaWithNisn = Siswa::whereNotNull('nisn')->count();

        return view('dapos.dapodik.sync', compact(
            'config', 'periodikTotal', 'periodikPending', 'periodikSynced', 'siswaTotal', 'siswaWithNisn'
        ));
    }

    public function syncBatch(Request $request)
    {
        $type = $request->input('type');
        $offset = max(0, (int) $request->input('offset', 0));
        $limit = min(max((int) $request->input('limit', 10), 1), 50);

        if (!in_array($type, ['periodik', 'siswa'])) {
            return response()->json(['error' => 'Tipe sinkron tidak valid'], 422);
        }

        if (!$this->dapodik->isConfigured()) {
            return response()->json(['error' => 'Konfigurasi Dapodik belum lengkap'], 422);
        }

        $synced = 0;
        $failed = 0;
        $errors = [];
        $processed = 0;
        $total = 0;

        if ($type === 'periodik') {
            $items = Periodik::with('siswa')
                ->whereHas('siswa', fn ($q) => $q->whereNotNull('nisn'))
                ->where(fn ($q) => $q->where('sync_status', '!=', 'synced')->orWhereNull('sync_status'))
                ->orderBy('id')
                ->get();
            $total = $items->count();
            $siswaMap = $this->getDapodikSiswaMap();
            $slice = $items->slice($offset, $limit);

            foreach ($slice as $periodik) {
                $nisn = $periodik->siswa->nisn;
                $dapodikSiswa = $siswaMap[$nisn] ?? null;
                if (!$dapodikSiswa) {
                    $failed++;
                    $errors[] = "NISN {$nisn}: tidak ditemukan di Dapodik";
                    $periodik->update(['sync_status' => 'failed']);
                    $processed++;
                    continue;
                }
                $result = $this->dapodik->syncPeriodik($periodik, $dapodikSiswa);
                if ($result['success']) {
                    $synced++;
                } else {
                    $failed++;
                    $errors[] = $result['message'];
                }
                $processed++;
            }
        } elseif ($type === 'siswa') {
            $items = Siswa::whereNotNull('nisn')->orderBy('id')->get();
            $total = $items->count();
            $siswaMap = $this->getDapodikSiswaMap();
            $slice = $items->slice($offset, $limit);

            foreach ($slice as $siswa) {
                $result = $this->dapodik->syncSiswa($siswa);
                if ($result['success']) {
                    $synced++;
                } else {
                    $failed++;
                    $errors[] = $result['message'];
                }
                $processed++;
            }
        }

        $done = ($offset + $processed) >= $total;

        return response()->json([
            'type' => $type,
            'offset' => $offset,
            'next_offset' => $offset + $processed,
            'processed' => $processed,
            'synced' => $synced,
            'failed' => $failed,
            'total' => $total,
            'done' => $done,
            'errors' => array_slice($errors, 0, 5),
        ]);
    }

    public function importStep(string $step)
    {
        $methods = [
            'siswa' => 'importSiswa',
            'registrasi' => 'importRegistrasi',
            'rombel' => 'importRombel',
            'anggota_rombel' => 'importAnggotaRombel',
            'pembelajaran' => 'importPembelajaran',
        ];

        if (!isset($methods[$step])) {
            return response()->json(['success' => false, 'message' => 'Langkah import tidak valid'], 422);
        }

        $result = $this->dapodik->{$methods[$step]}();
        $this->flushImportStats();
        $this->flushSiswaMap();

        return response()->json($result);
    }
}
