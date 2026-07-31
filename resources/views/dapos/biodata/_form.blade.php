@php $siswa = $biodatum ?? null; @endphp

<div class="row">
    <div class="col-md-6 mb-3">
        <label class="form-label">NISN</label>
        <input type="text" name="nisn" class="form-control @error('nisn') is-invalid @enderror"
            value="{{ old('nisn', $siswa?->nisn) }}" maxlength="20">
        @error('nisn') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
    <div class="col-md-6 mb-3">
        <label class="form-label">NIK</label>
        <input type="text" name="nik" class="form-control" value="{{ old('nik', $siswa?->nik) }}" maxlength="30">
    </div>
</div>

<div class="row">
    <div class="col-md-6 mb-3">
        <label class="form-label">No. KK</label>
        <input type="text" name="no_kk" class="form-control" value="{{ old('no_kk', $siswa?->no_kk) }}" maxlength="30">
    </div>
    <div class="col-md-6 mb-3">
        <label class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
        <input type="text" name="nama" class="form-control @error('nama') is-invalid @enderror"
            value="{{ old('nama', $siswa?->nama) }}" required>
        @error('nama') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
</div>

<div class="row">
    <div class="col-md-4 mb-3">
        <label class="form-label">Jenis Kelamin <span class="text-danger">*</span></label>
        <select name="jenis_kelamin" class="form-select" required>
            <option value="">-- Pilih --</option>
            <option value="L" {{ old('jenis_kelamin', $siswa?->jenis_kelamin) == 'L' ? 'selected' : '' }}>Laki-laki</option>
            <option value="P" {{ old('jenis_kelamin', $siswa?->jenis_kelamin) == 'P' ? 'selected' : '' }}>Perempuan</option>
        </select>
    </div>
    <div class="col-md-4 mb-3">
        <label class="form-label">Tempat Lahir</label>
        <input type="text" name="tempat_lahir" class="form-control" value="{{ old('tempat_lahir', $siswa?->tempat_lahir) }}" maxlength="100">
    </div>
    <div class="col-md-4 mb-3">
        <label class="form-label">Tanggal Lahir</label>
        <input type="date" name="tanggal_lahir" class="form-control" value="{{ old('tanggal_lahir', $siswa?->tanggal_lahir?->format('Y-m-d')) }}">
    </div>
</div>

<div class="row">
    <div class="col-md-4 mb-3">
        <label class="form-label">Agama</label>
        <select name="agama_id" class="form-select select2">
            <option value="">-- Pilih --</option>
            @foreach ($agama as $a)
                <option value="{{ $a->id }}" {{ old('agama_id', $siswa?->agama_id) == $a->id ? 'selected' : '' }}>{{ $a->nama }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-4 mb-3">
        <label class="form-label">Kewarganegaraan</label>
        <input type="text" name="kewarganegaraan" class="form-control" value="{{ old('kewarganegaraan', $siswa?->kewarganegaraan ?? 'ID') }}" maxlength="20">
    </div>
    <div class="col-md-4 mb-3">
        <label class="form-label">Reg. Akta Lahir</label>
        <input type="text" name="reg_akta_lahir" class="form-control" value="{{ old('reg_akta_lahir', $siswa?->reg_akta_lahir) }}" maxlength="30">
    </div>
</div>

<div class="row">
    <div class="col-md-4 mb-3">
        <label class="form-label">Anak Ke-</label>
        <input type="number" name="anak_keberapa" class="form-control" value="{{ old('anak_keberapa', $siswa?->anak_keberapa) }}" min="1" max="20">
    </div>
    <div class="col-md-4 mb-3">
        <label class="form-label">Kebutuhan Khusus</label>
        <input type="text" name="kebutuhan_khusus" class="form-control" value="{{ old('kebutuhan_khusus', $siswa?->kebutuhan_khusus) }}" maxlength="50">
    </div>
    <div class="col-md-4 mb-3">
        <label class="form-label">Sekolah Asal</label>
        <input type="text" name="sekolah_asal" class="form-control" value="{{ old('sekolah_asal', $siswa?->sekolah_asal) }}" maxlength="150">
    </div>
</div>

<h6 class="border-bottom pb-2 mt-4">Alamat & Kontak</h6>
<div class="mb-3">
    <label class="form-label">Alamat</label>
    <textarea name="alamat_jalan" class="form-control" rows="2">{{ old('alamat_jalan', $siswa?->alamat_jalan) }}</textarea>
</div>

<div class="row">
    <div class="col-md-3 mb-3">
        <label class="form-label">RT</label>
        <input type="text" name="rt" class="form-control" value="{{ old('rt', $siswa?->rt) }}" maxlength="5">
    </div>
    <div class="col-md-3 mb-3">
        <label class="form-label">RW</label>
        <input type="text" name="rw" class="form-control" value="{{ old('rw', $siswa?->rw) }}" maxlength="5">
    </div>
    <div class="col-md-3 mb-3">
        <label class="form-label">Dusun</label>
        <input type="text" name="nama_dusun" class="form-control" value="{{ old('nama_dusun', $siswa?->nama_dusun) }}" maxlength="100">
    </div>
    <div class="col-md-3 mb-3">
        <label class="form-label">Kode Pos</label>
        <input type="text" name="kode_pos" class="form-control" value="{{ old('kode_pos', $siswa?->kode_pos) }}" maxlength="10">
    </div>
</div>

<div class="row">
    <div class="col-md-4 mb-3">
        <label class="form-label">Kode Wilayah</label>
        <input type="text" name="kode_wilayah" class="form-control" value="{{ old('kode_wilayah', $siswa?->kode_wilayah) }}" maxlength="20">
    </div>
    <div class="col-md-4 mb-3">
        <label class="form-label">Jenis Tinggal</label>
        <select name="jenis_tinggal_id" class="form-select select2">
            <option value="">-- Pilih --</option>
            @foreach ($jenisTinggal as $j)
                <option value="{{ $j->id }}" {{ old('jenis_tinggal_id', $siswa?->jenis_tinggal_id) == $j->id ? 'selected' : '' }}>{{ $j->nama }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-4 mb-3">
        <label class="form-label">Alat Transportasi</label>
        <select name="alat_transportasi_id" class="form-select select2">
            <option value="">-- Pilih --</option>
            @foreach ($alatTransportasi as $t)
                <option value="{{ $t->id }}" {{ old('alat_transportasi_id', $siswa?->alat_transportasi_id) == $t->id ? 'selected' : '' }}>{{ $t->nama }}</option>
            @endforeach
        </select>
    </div>
</div>

<div class="row">
    <div class="col-md-6 mb-3">
        <label class="form-label">No. Telepon</label>
        <input type="text" name="nomor_telepon_rumah" class="form-control" value="{{ old('nomor_telepon_rumah', $siswa?->nomor_telepon_rumah) }}" maxlength="30">
    </div>
    <div class="col-md-6 mb-3">
        <label class="form-label">No. HP</label>
        <input type="text" name="nomor_telepon_seluler" class="form-control" value="{{ old('nomor_telepon_seluler', $siswa?->nomor_telepon_seluler) }}" maxlength="30">
    </div>
</div>

<div class="mb-3">
    <label class="form-label">Email</label>
    <input type="email" name="email" class="form-control" value="{{ old('email', $siswa?->email) }}" maxlength="100">
</div>

<h6 class="border-bottom pb-2 mt-4">Data Ayah</h6>
<div class="row">
    <div class="col-md-4 mb-3">
        <label class="form-label">Nama Ayah</label>
        <input type="text" name="nama_ayah" class="form-control" value="{{ old('nama_ayah', $siswa?->nama_ayah) }}" maxlength="150">
    </div>
    <div class="col-md-4 mb-3">
        <label class="form-label">NIK Ayah</label>
        <input type="text" name="nik_ayah" class="form-control" value="{{ old('nik_ayah', $siswa?->nik_ayah) }}" maxlength="30">
    </div>
    <div class="col-md-4 mb-3">
        <label class="form-label">Tahun Lahir Ayah</label>
        <input type="number" name="tahun_lahir_ayah" class="form-control" value="{{ old('tahun_lahir_ayah', $siswa?->tahun_lahir_ayah) }}" min="1950" max="2030">
    </div>
</div>
<div class="row">
    <div class="col-md-4 mb-3">
        <label class="form-label">Pendidikan Ayah</label>
        <select name="jenjang_pendidikan_ayah" class="form-select select2">
            <option value="">-- Pilih --</option>
            @foreach ($jenjangPendidikan as $jp)
                <option value="{{ $jp->id }}" {{ old('jenjang_pendidikan_ayah', $siswa?->jenjang_pendidikan_ayah) == $jp->id ? 'selected' : '' }}>{{ $jp->nama }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-4 mb-3">
        <label class="form-label">Pekerjaan Ayah</label>
        <select name="pekerjaan_id_ayah" class="form-select select2">
            <option value="">-- Pilih --</option>
            @foreach ($pekerjaan as $p)
                <option value="{{ $p->id }}" {{ old('pekerjaan_id_ayah', $siswa?->pekerjaan_id_ayah) == $p->id ? 'selected' : '' }}>{{ $p->nama }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-4 mb-3">
        <label class="form-label">Penghasilan Ayah</label>
        <select name="penghasilan_id_ayah" class="form-select select2">
            <option value="">-- Pilih --</option>
            @foreach ($penghasilan as $p)
                <option value="{{ $p->id }}" {{ old('penghasilan_id_ayah', $siswa?->penghasilan_id_ayah) == $p->id ? 'selected' : '' }}>{{ $p->rentang }}</option>
            @endforeach
        </select>
    </div>
</div>

<h6 class="border-bottom pb-2 mt-4">Data Ibu</h6>
<div class="row">
    <div class="col-md-4 mb-3">
        <label class="form-label">Nama Ibu Kandung</label>
        <input type="text" name="nama_ibu_kandung" class="form-control" value="{{ old('nama_ibu_kandung', $siswa?->nama_ibu_kandung) }}" maxlength="150">
    </div>
    <div class="col-md-4 mb-3">
        <label class="form-label">NIK Ibu</label>
        <input type="text" name="nik_ibu" class="form-control" value="{{ old('nik_ibu', $siswa?->nik_ibu) }}" maxlength="30">
    </div>
    <div class="col-md-4 mb-3">
        <label class="form-label">Tahun Lahir Ibu</label>
        <input type="number" name="tahun_lahir_ibu" class="form-control" value="{{ old('tahun_lahir_ibu', $siswa?->tahun_lahir_ibu) }}" min="1950" max="2030">
    </div>
</div>
<div class="row">
    <div class="col-md-4 mb-3">
        <label class="form-label">Pendidikan Ibu</label>
        <select name="jenjang_pendidikan_ibu" class="form-select select2">
            <option value="">-- Pilih --</option>
            @foreach ($jenjangPendidikan as $jp)
                <option value="{{ $jp->id }}" {{ old('jenjang_pendidikan_ibu', $siswa?->jenjang_pendidikan_ibu) == $jp->id ? 'selected' : '' }}>{{ $jp->nama }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-4 mb-3">
        <label class="form-label">Pekerjaan Ibu</label>
        <select name="pekerjaan_id_ibu" class="form-select select2">
            <option value="">-- Pilih --</option>
            @foreach ($pekerjaan as $p)
                <option value="{{ $p->id }}" {{ old('pekerjaan_id_ibu', $siswa?->pekerjaan_id_ibu) == $p->id ? 'selected' : '' }}>{{ $p->nama }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-4 mb-3">
        <label class="form-label">Penghasilan Ibu</label>
        <select name="penghasilan_id_ibu" class="form-select select2">
            <option value="">-- Pilih --</option>
            @foreach ($penghasilan as $p)
                <option value="{{ $p->id }}" {{ old('penghasilan_id_ibu', $siswa?->penghasilan_id_ibu) == $p->id ? 'selected' : '' }}>{{ $p->rentang }}</option>
            @endforeach
        </select>
    </div>
</div>

<h6 class="border-bottom pb-2 mt-4">Data Wali</h6>
<div class="row">
    <div class="col-md-4 mb-3">
        <label class="form-label">Nama Wali</label>
        <input type="text" name="nama_wali" class="form-control" value="{{ old('nama_wali', $siswa?->nama_wali) }}" maxlength="150">
    </div>
    <div class="col-md-4 mb-3">
        <label class="form-label">NIK Wali</label>
        <input type="text" name="nik_wali" class="form-control" value="{{ old('nik_wali', $siswa?->nik_wali) }}" maxlength="30">
    </div>
    <div class="col-md-4 mb-3">
        <label class="form-label">Tahun Lahir Wali</label>
        <input type="number" name="tahun_lahir_wali" class="form-control" value="{{ old('tahun_lahir_wali', $siswa?->tahun_lahir_wali) }}" min="1950" max="2030">
    </div>
</div>
<div class="row">
    <div class="col-md-4 mb-3">
        <label class="form-label">Pendidikan Wali</label>
        <select name="jenjang_pendidikan_wali" class="form-select select2">
            <option value="">-- Pilih --</option>
            @foreach ($jenjangPendidikan as $jp)
                <option value="{{ $jp->id }}" {{ old('jenjang_pendidikan_wali', $siswa?->jenjang_pendidikan_wali) == $jp->id ? 'selected' : '' }}>{{ $jp->nama }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-4 mb-3">
        <label class="form-label">Pekerjaan Wali</label>
        <select name="pekerjaan_id_wali" class="form-select select2">
            <option value="">-- Pilih --</option>
            @foreach ($pekerjaan as $p)
                <option value="{{ $p->id }}" {{ old('pekerjaan_id_wali', $siswa?->pekerjaan_id_wali) == $p->id ? 'selected' : '' }}>{{ $p->nama }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-4 mb-3">
        <label class="form-label">Penghasilan Wali</label>
        <select name="penghasilan_id_wali" class="form-select select2">
            <option value="">-- Pilih --</option>
            @foreach ($penghasilan as $p)
                <option value="{{ $p->id }}" {{ old('penghasilan_id_wali', $siswa?->penghasilan_id_wali) == $p->id ? 'selected' : '' }}>{{ $p->rentang }}</option>
            @endforeach
        </select>
    </div>
</div>

<div class="mb-3 mt-4">
    <label class="form-label">Catatan</label>
    <textarea name="catatan" class="form-control" rows="3">{{ old('catatan', $siswa?->catatan) }}</textarea>
</div>
