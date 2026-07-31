<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Siswa;
use App\Models\Rombel;
use App\Models\Periodik;
use App\Models\Surat;
use App\Models\DapodikConfig;
use App\Models\AnggotaRombel;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SmokeTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::create([
            'name' => 'Admin',
            'email' => 'dapos.desktop@gmail.com',
            'password' => bcrypt('dapos2026'),
        ]);
    }

    public function test_login_flow(): void
    {
        $this->withoutMiddleware(\Illuminate\Foundation\Http\Middleware\ValidateCsrfToken::class);

        $this->get('/dapos/login')->assertOk();
        $this->get('/dapos')->assertRedirect('/login');

        $this->from('/dapos/login')->post('/dapos/login', [
            'email' => 'dapos.desktop@gmail.com',
            'password' => 'password-salah',
        ])->assertRedirect('/dapos/login');

        $this->from('/dapos/login')->post('/dapos/login', [
            'email' => 'dapos.desktop@gmail.com',
            'password' => 'dapos2026',
        ])->assertRedirect('/dapos');

        $this->assertAuthenticated();

        $this->post('/dapos/logout')->assertRedirect('/dapos/login');
        $this->assertGuest();
    }

    public function test_all_pages_render(): void
    {
        $siswa = Siswa::create([
            'nisn' => '1111111111',
            'nama' => 'Siswa Test',
            'jenis_kelamin' => 'L',
        ]);
        $rombel = Rombel::create([
            'nama' => 'Kelas 1A',
            'tingkat' => 'Kelas 1',
            'tahun_ajaran' => '2026/2027',
        ]);
        Rombel::create([
            'nama' => 'Kelas 2A',
            'tingkat' => 'Kelas 2',
            'tahun_ajaran' => '2026/2027',
        ]);
        Periodik::create(['siswa_id' => $siswa->id, 'tahun_periodik' => 2026]);
        AnggotaRombel::create(['rombel_id' => $rombel->id, 'siswa_id' => $siswa->id, 'status_di_rombel' => 'Aktif']);
        Surat::create(['jenis_surat' => 'SK', 'nomor_surat' => '001', 'tgl_surat' => now()]);
        DapodikConfig::create([
            'base_url' => 'http://localhost/dapodik',
            'token' => 'test-token',
            'npsn' => '12345678',
            'tahun_ajaran' => '2026/2027',
        ]);

        $this->actingAs($this->user);

        $urls = [
            '/dapos',
            '/dapos/biodata',
            '/dapos/biodata/create',
            '/dapos/biodata/' . $siswa->id,
            '/dapos/biodata/' . $siswa->id . '/edit',
            '/dapos/registrasi',
            '/dapos/registrasi/create',
            '/dapos/periodik',
            '/dapos/periodik/create',
            '/dapos/rombel',
            '/dapos/rombel/daftar-siswa',
            '/dapos/rombel/create',
            '/dapos/surat',
            '/dapos/surat/create',
            '/dapos/pembelajaran',
            '/dapos/rekap-jam-mengajar',
            '/dapos/rekap-jam-mengajar/excel',
            '/dapos/rekap-jam-mengajar/pdf',
            '/dapos/referensi',
            '/dapos/akun',
            '/dapos/dapodik/setting',
            '/dapos/dapodik/import',
            '/dapos/dapodik/sync',
        ];

        foreach ($urls as $url) {
            $this->get($url)->assertOk();
        }

        $daftar = $this->get('/dapos/rombel/daftar-siswa');
        $daftar->assertOk();
        $this->assertSame(1, substr_count($daftar->getContent(), 'id="filterSiswa"'));
    }

    public function test_all_exports_render(): void
    {
        $siswa = Siswa::create([
            'nisn' => '1111111111',
            'nama' => 'Siswa Test',
            'jenis_kelamin' => 'L',
        ]);
        Rombel::create([
            'nama' => 'Kelas 1A',
            'tingkat' => '1',
            'tahun_ajaran' => '2026/2027',
        ]);
        Periodik::create(['siswa_id' => $siswa->id, 'tahun_periodik' => 2026]);

        $this->actingAs($this->user);

        $exports = [
            '/dapos/export/siswa-excel',
            '/dapos/export/siswa-pdf',
            '/dapos/export/periodik-excel',
            '/dapos/export/periodik-pdf',
            '/dapos/export/rombel-excel',
            '/dapos/export/rombel-pdf',
            '/dapos/export/registrasi-excel',
            '/dapos/export/registrasi-pdf',
            '/dapos/export/surat-excel',
            '/dapos/export/surat-pdf',
            '/dapos/export/siswa-per-rombel-excel',
        ];

        foreach ($exports as $url) {
            $this->get($url)->assertOk();
        }
    }
}
