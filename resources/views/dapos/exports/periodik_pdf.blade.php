<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Data Periodik</title>
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
    <h2>Data Periodik Siswa</h2>
    <p class="text-center">Tanggal: {{ date('d/m/Y H:i') }}</p>
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Siswa</th>
                <th>NISN</th>
                <th>Tinggi (cm)</th>
                <th>Berat (kg)</th>
                <th>Tahun</th>
                <th>Status Sync</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($periodik as $i => $p)
            <tr>
                <td class="text-center">{{ $i + 1 }}</td>
                <td>{{ $p->siswa?->nama ?? '-' }}</td>
                <td>{{ $p->siswa?->nisn ?? '-' }}</td>
                <td class="text-center">{{ $p->tinggi_badan ?? '-' }}</td>
                <td class="text-center">{{ $p->berat_badan ?? '-' }}</td>
                <td class="text-center">{{ $p->tahun_periodik }}</td>
                <td>{{ $p->sync_status ?? 'Unsynced' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
