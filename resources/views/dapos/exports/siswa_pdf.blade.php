<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Data Siswa</title>
    <style>
        body { font-family: sans-serif; font-size: 11pt; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #333; padding: 4px 6px; text-align: left; }
        th { background: #e0e0e0; }
        h2 { text-align: center; margin-bottom: 5px; }
        .text-center { text-align: center; }
    </style>
</head>
<body>
    <h2>Data Siswa</h2>
    <p class="text-center">Tanggal: {{ date('d/m/Y H:i') }}</p>
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>NISN</th>
                <th>NIK</th>
                <th>Nama</th>
                <th>JK</th>
                <th>Tempat Lahir</th>
                <th>Tanggal Lahir</th>
                <th>Agama</th>
                <th>Alamat</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($siswa as $i => $s)
            <tr>
                <td class="text-center">{{ $i + 1 }}</td>
                <td>{{ $s->nisn ?? '-' }}</td>
                <td>{{ $s->nik ?? '-' }}</td>
                <td>{{ $s->nama }}</td>
                <td>{{ $s->jenis_kelamin }}</td>
                <td>{{ $s->tempat_lahir ?? '-' }}</td>
                <td>{{ $s->tanggal_lahir?->format('d/m/Y') ?? '-' }}</td>
                <td>{{ $s->agama?->nama ?? '-' }}</td>
                <td>{{ $s->alamat_jalan ?? '-' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
