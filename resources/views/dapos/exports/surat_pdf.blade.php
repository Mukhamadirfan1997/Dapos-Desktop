<!DOCTYPE html>
<html>
<head><meta charset="utf-8"><title>Data Surat</title>
<style>table { width:100%; border-collapse:collapse; } th, td { border:1px solid #000; padding:6px; text-align:left; font-size:12px; } th { background:#f0f0f0; }</style>
</head>
<body>
<h3 style="text-align:center;">Data Surat</h3>
<table>
<thead><tr><th>No</th><th>Jenis</th><th>Nomor</th><th>Tanggal</th><th>Siswa</th><th>Kepada</th></tr></thead>
<tbody>
@foreach ($surat as $i => $s)
<tr><td>{{ $i + 1 }}</td><td>{{ $s->jenis_surat }}</td><td>{{ $s->nomor_surat ?? '-' }}</td><td>{{ $s->tgl_surat ? $s->tgl_surat->format('d/m/Y') : '-' }}</td><td>{{ $s->siswa->nama ?? '-' }}</td><td>{{ $s->kepada ?? '-' }}</td></tr>
@endforeach
</tbody>
</table>
</body>
</html>
