<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Rekap Jam Mengajar Guru</title>
    <style>
        body { font-family: sans-serif; font-size: 9pt; }
        table { width: 100%; border-collapse: collapse; margin-top: 8px; }
        th, td { border: 1px solid #333; padding: 3px 5px; text-align: left; }
        th { background: #e0e0e0; }
        h2 { text-align: center; margin: 0 0 2px; }
        .text-center { text-align: center; }
        .small { font-size: 8pt; }
        .status-sesuai { color: #0a0; font-weight: bold; }
        .status-kurang, .status-lebih, .status-masalah { color: #a00; font-weight: bold; }
    </style>
</head>
<body>
    <h2>Rekap Jam Mengajar Guru</h2>
    <p class="text-center small">Tanggal: {{ date('d/m/Y H:i') }}</p>
    <p class="small">
        Ringkasan: Total Guru {{ $totalGuru }} | Sesuai (24-40 JP) {{ $guruSesuai }} |
        Kurang (&lt;24 JP) {{ $guruKurang }} | Lebih (&gt;40 JP) {{ $guruLebih }}.
        Aturan: Permendikbud 15/2018 &mdash; beban mengajar 24-40 JP/minggu; per rombel SD: Guru Kelas 24 JP, PJOK 4 JP, Agama 4 JP.
    </p>

    <h3 class="small">Per Guru</h3>
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Guru</th>
                <th>Rincian</th>
                <th>Total JJM</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($daftarGuru as $i => $g)
            <tr>
                <td class="text-center">{{ $i + 1 }}</td>
                <td>{{ $g['nama'] }}</td>
                <td>
                    @foreach ($g['rincian'] as $rc)
                        {{ $rc['rombel'] }} - {{ $rc['mapel'] }}: {{ $rc['jam'] }} JP; 
                    @endforeach
                </td>
                <td class="text-center">{{ $g['total_jam'] }}</td>
                <td class="{{ $g['status'] === 'sesuai' ? 'status-sesuai' : 'status-kurang status-lebih' }}">
                    @if ($g['status'] === 'sesuai')
                        Sesuai
                    @elseif ($g['status'] === 'kurang')
                        Kurang
                    @else
                        Lebih
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <h3 class="small">Per Rombel</h3>
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Rombel</th>
                <th>Guru Kelas (JP)</th>
                <th>PJOK (JP)</th>
                <th>Agama (JP)</th>
                <th>Total JP</th>
                <th>Catatan</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($daftarRombel as $i => $r)
            <tr>
                <td class="text-center">{{ $i + 1 }}</td>
                <td>{{ $r['nama'] }}</td>
                <td>{{ $r['guru_kelas'] ? $r['guru_kelas']['nama'] . ' (' . $r['guru_kelas']['jam'] . ')' : '-' }}</td>
                <td>{{ $r['pjok'] ? $r['pjok']['nama'] . ' (' . $r['pjok']['jam'] . ')' : '-' }}</td>
                <td>{{ $r['agama'] ? $r['agama']['nama'] . ' (' . $r['agama']['jam'] . ')' : '-' }}</td>
                <td class="text-center">{{ $r['total_jam'] }}</td>
                <td class="{{ empty($r['masalah']) ? 'status-sesuai' : 'status-masalah' }}">
                    {{ empty($r['masalah']) ? 'OK' : implode('; ', $r['masalah']) }}
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
