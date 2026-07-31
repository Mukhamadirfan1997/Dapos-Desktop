<?php

namespace App\Http\Controllers\Dapos;

use App\Http\Controllers\Controller;
use App\Models\DapodikConfig;
use App\Models\Periodik;
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
}
