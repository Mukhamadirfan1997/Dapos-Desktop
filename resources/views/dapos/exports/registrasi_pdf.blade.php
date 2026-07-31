<!DOCTYPE html>
<html>
<head><meta charset="utf-8"><title>Data Registrasi</title>
<style>table { width:100%; border-collapse:collapse; } th, td { border:1px solid #000; padding:6px; text-align:left; font-size:12px; } th { background:#f0f0f0; }</style>
</head>
<body>
<h3 style="text-align:center;">Data Registrasi</h3>
<table>
<thead><tr><th>No</th><th>Nama Siswa</th><th>NIS</th><th>Jenis Daftar</th><th>Tgl Masuk</th><th>Tingkat</th></tr></thead>
<tbody>
@foreach ($registrasi as $i => $r)
<tr><td>{{ $i + 1 }}</td><td>{{ $r->siswa->nama ?? '-' }}</td><td>{{ $r->nis ?? '-' }}</td><td>{{ $r->jenisDaftar->nama ?? '-' }}</td><td>{{ $r->tanggal_masuk ? $r->tanggal_masuk->format('d/m/Y') : '-' }}</td><td>{{ $r->tingkat_awal ?? '-' }}</td></tr>
@endforeach
</tbody>
</table>
</body>
</html>
