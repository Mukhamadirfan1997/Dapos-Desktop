<?php

namespace App\Http\Controllers\Dapos;

use App\Http\Controllers\Controller;
use App\Models\Pembelajaran;
use App\Models\Rombel;
use Barryvdh\DomPDF\Facade\Pdf;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;

class RekapJamMengajarController extends Controller
{
    protected function data()
    {
        $pembelajaran = Pembelajaran::with('rombel')->orderBy('rombel_id')->get();

        $rombels = Rombel::orderBy('tingkat')->orderBy('nama')->get()->map(function ($r) {
            $r->jam_rows = Pembelajaran::where('rombel_id', $r->id)->get();
            return $r;
        });

        $guru = [];
        foreach ($pembelajaran as $p) {
            $nama = trim((string) $p->nama_guru);
            if ($nama === '') {
                $nama = 'Tidak tersedia';
            }
            $guru[$nama]['total_jam'] = ($guru[$nama]['total_jam'] ?? 0) + (int) $p->jam_mengajar;
            $guru[$nama]['rincian'][] = [
                'rombel' => $p->rombel?->nama ?? '-',
                'mapel' => $p->mata_pelajaran,
                'jam' => (int) $p->jam_mengajar,
            ];
        }

        $guruRangkap = [];
        foreach ($pembelajaran->filter(fn($p) => str_starts_with($p->mata_pelajaran, 'Guru Kelas')) as $p) {
            $nama = trim((string) $p->nama_guru);
            if ($nama === '') {
                continue;
            }
            $guruRangkap[$nama][] = $p->rombel?->nama ?? '-';
        }

        $daftarGuru = [];
        foreach ($guru as $nama => $g) {
            $total = $g['total_jam'];
            $daftarGuru[] = [
                'nama' => $nama,
                'total_jam' => $total,
                'jml_mapel' => count($g['rincian']),
                'rincian' => $g['rincian'],
                'status' => $total < 24 ? 'kurang' : ($total > 40 ? 'lebih' : 'sesuai'),
                'rangkap' => (($guruRangkap[$nama] ?? null) && count($guruRangkap[$nama]) > 1)
                    ? array_values(array_unique($guruRangkap[$nama])) : [],
            ];
        }
        usort($daftarGuru, fn($a, $b) => $b['total_jam'] <=> $a['total_jam']);

        $daftarRombel = [];
        foreach ($rombels as $r) {
            $guruKelas = $r->jam_rows->first(fn($p) => str_starts_with($p->mata_pelajaran, 'Guru Kelas'));
            $pjok = $r->jam_rows->first(fn($p) => str_starts_with($p->mata_pelajaran, 'Pendidikan Jasmani'));
            $agama = $r->jam_rows->first(fn($p) => str_starts_with($p->mata_pelajaran, 'Pendidikan Agama'));

            $total = $r->jam_rows->sum(fn($p) => (int) $p->jam_mengajar);

            $masalah = [];
            if (!$guruKelas) {
                $masalah[] = 'Tidak ada Guru Kelas';
            } elseif ((int) $guruKelas->jam_mengajar !== 24) {
                $masalah[] = 'Jam Guru Kelas ' . $guruKelas->jam_mengajar . ' (harus 24)';
            }
            if (!$pjok) {
                $masalah[] = 'Tidak ada PJOK';
            } elseif ((int) $pjok->jam_mengajar !== 4) {
                $masalah[] = 'Jam PJOK ' . $pjok->jam_mengajar . ' (ideal 4, kemungkinan diampu guru kelas)';
            }
            if (!$agama) {
                $masalah[] = 'Tidak ada Agama';
            } elseif ((int) $agama->jam_mengajar !== 4) {
                $masalah[] = 'Jam Agama ' . $agama->jam_mengajar . ' (harus 4)';
            }

            $daftarRombel[] = [
                'nama' => $r->nama,
                'tingkat' => $r->tingkat,
                'guru_kelas' => $guruKelas ? ['jam' => (int) $guruKelas->jam_mengajar, 'nama' => trim((string) $guruKelas->nama_guru) ?: '-'] : null,
                'pjok' => $pjok ? ['jam' => (int) $pjok->jam_mengajar, 'nama' => trim((string) $pjok->nama_guru) ?: '-'] : null,
                'agama' => $agama ? ['jam' => (int) $agama->jam_mengajar, 'nama' => trim((string) $agama->nama_guru) ?: '-'] : null,
                'total_jam' => $total,
                'masalah' => $masalah,
                'status' => empty($masalah) ? 'oke' : 'masalah',
            ];
        }

        $totalGuru = count($daftarGuru);
        $guruSesuai = count(array_filter($daftarGuru, fn($g) => $g['status'] === 'sesuai'));
        $guruKurang = count(array_filter($daftarGuru, fn($g) => $g['status'] === 'kurang'));
        $guruLebih = count(array_filter($daftarGuru, fn($g) => $g['status'] === 'lebih'));

        return compact('daftarGuru', 'daftarRombel', 'totalGuru', 'guruSesuai', 'guruKurang', 'guruLebih');
    }

    public function index()
    {
        return view('dapos.rekap_jam_mengajar.index', $this->data());
    }

    public function rekapExcel()
    {
        $data = $this->data();

        $spreadsheet = new Spreadsheet;

        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Per Guru');
        $sheet->setCellValue([1, 1], 'No');
        $sheet->setCellValue([2, 1], 'Nama Guru');
        $sheet->setCellValue([3, 1], 'Jumlah Mapel');
        $sheet->setCellValue([4, 1], 'Total JJM');
        $sheet->setCellValue([5, 1], 'Status');
        $sheet->setCellValue([6, 1], 'Kelas Rangkap (Guru Kelas)');
        $sheet->getStyle('A1:F1')->getFont()->setBold(true);

        $row = 2;
        foreach ($data['daftarGuru'] as $i => $g) {
            $sheet->setCellValue([1, $row], $i + 1);
            $sheet->setCellValue([2, $row], $g['nama']);
            $sheet->setCellValue([3, $row], $g['jml_mapel']);
            $sheet->setCellValue([4, $row], $g['total_jam']);
            $sheet->setCellValue([5, $row], match ($g['status']) {
                'kurang' => 'Kurang (<24)',
                'lebih' => 'Lebih (>40)',
                default => 'Sesuai (24-40)',
            });
            $sheet->setCellValue([6, $row], empty($g['rangkap']) ? '-' : implode(', ', $g['rangkap']));
            $row++;
        }

        $sheet2 = $spreadsheet->createSheet();
        $sheet2->setTitle('Per Rombel');
        $sheet2->setCellValue([1, 1], 'No');
        $sheet2->setCellValue([2, 1], 'Rombel');
        $sheet2->setCellValue([3, 1], 'Guru Kelas (jam)');
        $sheet2->setCellValue([4, 1], 'PJOK (jam)');
        $sheet2->setCellValue([5, 1], 'Agama (jam)');
        $sheet2->setCellValue([6, 1], 'Total JP');
        $sheet2->setCellValue([7, 1], 'Catatan');
        $sheet2->getStyle('A1:G1')->getFont()->setBold(true);

        $row = 2;
        foreach ($data['daftarRombel'] as $i => $r) {
            $sheet2->setCellValue([1, $row], $i + 1);
            $sheet2->setCellValue([2, $row], $r['nama']);
            $sheet2->setCellValue([3, $row], $r['guru_kelas'] ? $r['guru_kelas']['nama'] . ' (' . $r['guru_kelas']['jam'] . ')' : '-');
            $sheet2->setCellValue([4, $row], $r['pjok'] ? $r['pjok']['nama'] . ' (' . $r['pjok']['jam'] . ')' : '-');
            $sheet2->setCellValue([5, $row], $r['agama'] ? $r['agama']['nama'] . ' (' . $r['agama']['jam'] . ')' : '-');
            $sheet2->setCellValue([6, $row], $r['total_jam']);
            $sheet2->setCellValue([7, $row], empty($r['masalah']) ? 'OK' : implode('; ', $r['masalah']));
            $row++;
        }

        foreach ([$sheet, $sheet2] as $s) {
            foreach (range(1, $s === $sheet ? 6 : 7) as $col) {
                $s->getColumnDimension(Coordinate::stringFromColumnIndex($col))->setAutoSize(true);
            }
        }

        $writer = new Xlsx($spreadsheet);
        $filename = 'rekap_jam_mengajar_' . date('Ymd_His') . '.xlsx';

        return response()->streamDownload(function () use ($writer) {
            $writer->save('php://output');
        }, $filename, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ]);
    }

    public function rekapPdf()
    {
        $data = $this->data();
        $pdf = Pdf::loadView('dapos.exports.rekap_jam_mengajar_pdf', $data);
        return $pdf->download('rekap_jam_mengajar_' . date('Ymd_His') . '.pdf');
    }
}
