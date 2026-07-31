<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Siswa extends Model
{
    use SoftDeletes;

    protected $table = 'siswa';

    protected $fillable = [
        'nisn', 'dapodik_id', 'nik', 'no_kk', 'nama', 'jenis_kelamin', 'tempat_lahir', 'tanggal_lahir',
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

    protected $casts = [
        'tanggal_lahir' => 'date',
        'tahun_lahir_ayah' => 'integer',
        'tahun_lahir_ibu' => 'integer',
        'tahun_lahir_wali' => 'integer',
    ];

    public function agama()
    {
        return $this->belongsTo(Agama::class);
    }

    public function pekerjaanAyah()
    {
        return $this->belongsTo(Pekerjaan::class, 'pekerjaan_id_ayah');
    }

    public function pekerjaanIbu()
    {
        return $this->belongsTo(Pekerjaan::class, 'pekerjaan_id_ibu');
    }

    public function pekerjaanWali()
    {
        return $this->belongsTo(Pekerjaan::class, 'pekerjaan_id_wali');
    }

    public function penghasilanAyah()
    {
        return $this->belongsTo(Penghasilan::class, 'penghasilan_id_ayah');
    }

    public function penghasilanIbu()
    {
        return $this->belongsTo(Penghasilan::class, 'penghasilan_id_ibu');
    }

    public function penghasilanWali()
    {
        return $this->belongsTo(Penghasilan::class, 'penghasilan_id_wali');
    }

    public function jenisTinggal()
    {
        return $this->belongsTo(JenisTinggal::class);
    }

    public function alatTransportasi()
    {
        return $this->belongsTo(AlatTransportasi::class);
    }

    public function jenjangPendidikanAyah()
    {
        return $this->belongsTo(JenjangPendidikan::class, 'jenjang_pendidikan_ayah');
    }

    public function jenjangPendidikanIbu()
    {
        return $this->belongsTo(JenjangPendidikan::class, 'jenjang_pendidikan_ibu');
    }

    public function jenjangPendidikanWali()
    {
        return $this->belongsTo(JenjangPendidikan::class, 'jenjang_pendidikan_wali');
    }

    public function kodeWilayah()
    {
        return $this->belongsTo(KodeWilayah::class, 'kode_wilayah', 'kode');
    }

    public function registrasi()
    {
        return $this->hasOne(Registrasi::class);
    }

    public function periodik()
    {
        return $this->hasMany(Periodik::class);
    }

    public function anggotaRombel()
    {
        return $this->hasMany(AnggotaRombel::class);
    }

    public function surat()
    {
        return $this->hasMany(Surat::class);
    }

    public function rombelAktif()
    {
        return $this->hasOneThrough(
            Rombel::class,
            AnggotaRombel::class,
            'siswa_id',
            'id',
            'id',
            'rombel_id'
        )->where('status_di_rombel', 'Aktif');
    }
}
