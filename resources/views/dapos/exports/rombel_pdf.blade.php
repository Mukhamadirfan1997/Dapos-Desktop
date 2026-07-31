<!DOCTYPE html>
<html>
<head><meta charset="utf-8"><title>Data Rombel</title>
<style>table { width:100%; border-collapse:collapse; } th, td { border:1px solid #000; padding:6px; text-align:left; font-size:12px; } th { background:#f0f0f0; }</style>
</head>
<body>
<h3 style="text-align:center;">Data Rombel</h3>
<table>
<thead><tr><th>No</th><th>Nama Rombel</th><th>Tingkat</th><th>Tahun Ajaran</th><th>Wali Kelas</th><th>Anggota</th></tr></thead>
<tbody>
@foreach ($rombel as $i => $r)
<tr><td>{{ $i + 1 }}</td><td>{{ $r->nama }}</td><td>{{ $r->tingkat }}</td><td>{{ $r->tahun_ajaran }}</td><td>{{ $r->nama_wali_kelas ?? '-' }}</td><td>{{ $r->anggota_count }}</td></tr>
@endforeach
</tbody>
</table>
</body>
</html>
