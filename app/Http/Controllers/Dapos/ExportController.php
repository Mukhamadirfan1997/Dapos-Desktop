<?php

namespace App\Http\Controllers\Dapos;

use App\Http\Controllers\Controller;
use App\Models\Siswa;
use App\Models\Periodik;
use App\Models\Rombel;
use App\Models\Registrasi;
use App\Models\Surat;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class ExportController extends Controller
{
    public function siswaExcel()
    {
        $siswa = Siswa::with('agama')->latest()->get();

        $spreadsheet = new Spreadsheet;
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Data Siswa');

        $headers = ['No', 'NISN', 'NIK', 'Nama', 'JK', 'Tempat Lahir', 'Tanggal Lahir', 'Agama', 'Alamat', 'RT', 'RW'];
        foreach (array_keys($headers) as $i) {
            $sheet->setCellValue([$i + 1, 1], $headers[$i]);
        }
        $sheet->getStyle('A1:K1')->getFont()->setBold(true);

        $row = 2;
        foreach ($siswa as $i => $s) {
            $sheet->setCellValue([1, $row], $i + 1);
            $sheet->setCellValue([2, $row], $s->nisn ?? '-');
            $sheet->setCellValue([3, $row], $s->nik ?? '-');
            $sheet->setCellValue([4, $row], $s->nama);
            $sheet->setCellValue([5, $row], $s->jenis_kelamin);
            $sheet->setCellValue([6, $row], $s->tempat_lahir ?? '-');
            $sheet->setCellValue([7, $row], $s->tanggal_lahir?->format('d/m/Y') ?? '-');
            $sheet->setCellValue([8, $row], $s->agama?->nama ?? '-');
            $sheet->setCellValue([9, $row], $s->alamat_jalan ?? '-');
            $sheet->setCellValue([10, $row], $s->rt ?? '-');
            $sheet->setCellValue([11, $row], $s->rw ?? '-');
            $row++;
        }

        foreach (range(1, 11) as $col) {
            $sheet->getColumnDimension(Coordinate::stringFromColumnIndex($col))->setAutoSize(true);
        }

        $writer = new Xlsx($spreadsheet);
        $filename = 'data_siswa_' . date('Ymd_His') . '.xlsx';

        return response()->streamDownload(function () use ($writer) {
            $writer->save('php://output');
        }, $filename, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ]);
    }

    public function siswaPdf()
    {
        $siswa = Siswa::with('agama')->latest()->get();
        $pdf = Pdf::loadView('dapos.exports.siswa_pdf', compact('siswa'));
        return $pdf->download('data_siswa_' . date('Ymd_His') . '.pdf');
    }

    public function periodikExcel()
    {
        $periodik = Periodik::with('siswa.rombelAktif')->latest()->get();

        $spreadsheet = new Spreadsheet;
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Data Periodik');

        $headers = ['No', 'NISN', 'Nama Siswa', 'Kelas', 'Tinggi (cm)', 'Berat (kg)', 'Lingkar Kepala (cm)', 'Jarak Rumah (m)', 'Waktu Tempuh (menit)', 'Jumlah Saudara', 'Tahun', 'Sync Status'];
        foreach (array_keys($headers) as $i) {
            $sheet->setCellValue([$i + 1, 1], $headers[$i]);
        }
        $sheet->getStyle('A1:L1')->getFont()->setBold(true);

        $row = 2;
        foreach ($periodik as $i => $p) {
            $sheet->setCellValue([1, $row], $i + 1);
            $sheet->setCellValue([2, $row], $p->siswa?->nisn ?? '-');
            $sheet->setCellValue([3, $row], $p->siswa?->nama ?? '-');
            $sheet->setCellValue([4, $row], $p->siswa?->rombelAktif?->nama ?? '-');
            $sheet->setCellValue([5, $row], $p->tinggi_badan ?? '-');
            $sheet->setCellValue([6, $row], $p->berat_badan ?? '-');
            $sheet->setCellValue([7, $row], $p->lingkar_kepala ?? '-');
            $sheet->setCellValue([8, $row], $p->jarak_rumah_sekolah ?? '-');
            $sheet->setCellValue([9, $row], $p->waktu_tempuh ?? '-');
            $sheet->setCellValue([10, $row], $p->jumlah_saudara_kandung ?? '-');
            $sheet->setCellValue([11, $row], $p->tahun_periodik);
            $sheet->setCellValue([12, $row], $p->sync_status ?? 'Unsynced');
            $row++;
        }

        foreach (range(1, 12) as $col) {
            $sheet->getColumnDimension(Coordinate::stringFromColumnIndex($col))->setAutoSize(true);
        }

        $writer = new Xlsx($spreadsheet);
        $filename = 'data_periodik_' . date('Ymd_His') . '.xlsx';

        return response()->streamDownload(function () use ($writer) {
            $writer->save('php://output');
        }, $filename, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ]);
    }

    public function periodikImport(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:xlsx,xls',
        ]);

        try {
            $spreadsheet = IOFactory::load($request->file('file')->getRealPath());
        } catch (\Exception $e) {
            return redirect()->route('dapos.periodik.index')
                ->with('error', 'File Excel tidak dapat dibaca: ' . $e->getMessage());
        }

        $sheet = $spreadsheet->getActiveSheet();
        $rows = $sheet->toArray();

        $siswaByNisn = Siswa::whereNotNull('nisn')->pluck('id', 'nisn');
        $updated = 0;
        $notFound = 0;
        $skipped = 0;
        $first = true;

        $fields = [
            4 => 'tinggi_badan',
            5 => 'berat_badan',
            6 => 'lingkar_kepala',
            7 => 'jarak_rumah_sekolah',
            8 => 'waktu_tempuh',
            9 => 'jumlah_saudara_kandung',
        ];

        foreach ($rows as $cells) {
            $cells = array_values($cells);
            if ($first) {
                $first = false;
                continue;
            }

            $nisn = trim((string) ($cells[1] ?? ''));
            $tahun = trim((string) ($cells[10] ?? ''));
            if ($nisn === '' || $nisn === '-' || $tahun === '' || $tahun === '-') {
                $skipped++;
                continue;
            }

            $siswaId = $siswaByNisn[$nisn] ?? null;
            $periodik = $siswaId
                ? Periodik::where('siswa_id', $siswaId)->where('tahun_periodik', (int) $tahun)->first()
                : null;
            if (!$periodik) {
                $notFound++;
                continue;
            }

            $data = [];
            foreach ($fields as $col => $field) {
                $val = trim((string) ($cells[$col] ?? ''));
                if ($val === '' || $val === '-') continue;
                $data[$field] = $field === 'jumlah_saudara_kandung' ? (int) $val : (float) $val;
            }

            foreach ($data as $field => $val) {
                if ($periodik->{$field} !== null && (string) $periodik->{$field} === (string) $val) {
                    unset($data[$field]);
                }
            }

            if (empty($data)) {
                $skipped++;
                continue;
            }

            $periodik->update($data);
            $updated++;
        }

        $message = "Import periodik selesai: {$updated} diperbarui, {$notFound} tidak ditemukan, {$skipped} dilewati";

        return redirect()->route('dapos.periodik.index')
            ->with('success', $message);
    }

    public function periodikPdf()
    {
        $periodik = Periodik::with('siswa')->latest()->get();
        $pdf = Pdf::loadView('dapos.exports.periodik_pdf', compact('periodik'));
        return $pdf->download('data_periodik_' . date('Ymd_His') . '.pdf');
    }

    public function rombelExcel()
    {
        $rombel = Rombel::withCount('anggota')->latest()->get();

        $spreadsheet = new Spreadsheet;
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Data Rombel');

        $headers = ['No', 'Nama Rombel', 'Tingkat', 'Tahun Ajaran', 'Wali Kelas', 'Jumlah Anggota'];
        foreach (array_keys($headers) as $i) {
            $sheet->setCellValue([$i + 1, 1], $headers[$i]);
        }
        $sheet->getStyle('A1:F1')->getFont()->setBold(true);

        $row = 2;
        foreach ($rombel as $i => $r) {
            $sheet->setCellValue([1, $row], $i + 1);
            $sheet->setCellValue([2, $row], $r->nama);
            $sheet->setCellValue([3, $row], $r->tingkat);
            $sheet->setCellValue([4, $row], $r->tahun_ajaran);
            $sheet->setCellValue([5, $row], $r->nama_wali_kelas ?? '-');
            $sheet->setCellValue([6, $row], $r->anggota_count);
            $row++;
        }

        foreach (range(1, 6) as $col) {
            $sheet->getColumnDimension(Coordinate::stringFromColumnIndex($col))->setAutoSize(true);
        }

        $writer = new Xlsx($spreadsheet);
        $filename = 'data_rombel_' . date('Ymd_His') . '.xlsx';

        return response()->streamDownload(function () use ($writer) {
            $writer->save('php://output');
        }, $filename, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ]);
    }

    public function rombelPdf()
    {
        $rombel = Rombel::withCount('anggota')->latest()->get();
        $pdf = Pdf::loadView('dapos.exports.rombel_pdf', compact('rombel'));
        return $pdf->download('data_rombel_' . date('Ymd_His') . '.pdf');
    }

    public function registrasiExcel()
    {
        $registrasi = Registrasi::with('siswa', 'jenisDaftar')->latest()->get();

        $spreadsheet = new Spreadsheet;
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Data Registrasi');

        $headers = ['No', 'Nama Siswa', 'NIS', 'Jenis Daftar', 'Tanggal Masuk', 'Tingkat Awal'];
        foreach (array_keys($headers) as $i) {
            $sheet->setCellValue([$i + 1, 1], $headers[$i]);
        }
        $sheet->getStyle('A1:F1')->getFont()->setBold(true);

        $row = 2;
        foreach ($registrasi as $i => $r) {
            $sheet->setCellValue([1, $row], $i + 1);
            $sheet->setCellValue([2, $row], $r->siswa?->nama ?? '-');
            $sheet->setCellValue([3, $row], $r->nis ?? '-');
            $sheet->setCellValue([4, $row], $r->jenisDaftar?->nama ?? '-');
            $sheet->setCellValue([5, $row], $r->tanggal_masuk?->format('d/m/Y') ?? '-');
            $sheet->setCellValue([6, $row], $r->tingkat_awal ?? '-');
            $row++;
        }

        foreach (range(1, 6) as $col) {
            $sheet->getColumnDimension(Coordinate::stringFromColumnIndex($col))->setAutoSize(true);
        }

        $writer = new Xlsx($spreadsheet);
        $filename = 'data_registrasi_' . date('Ymd_His') . '.xlsx';

        return response()->streamDownload(function () use ($writer) {
            $writer->save('php://output');
        }, $filename, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ]);
    }

    public function registrasiPdf()
    {
        $registrasi = Registrasi::with('siswa', 'jenisDaftar')->latest()->get();
        $pdf = Pdf::loadView('dapos.exports.registrasi_pdf', compact('registrasi'));
        return $pdf->download('data_registrasi_' . date('Ymd_His') . '.pdf');
    }

    public function suratExcel()
    {
        $surat = Surat::with('siswa')->latest()->get();

        $spreadsheet = new Spreadsheet;
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Data Surat');

        $headers = ['No', 'Jenis', 'Nomor', 'Tanggal', 'Siswa', 'Kepada'];
        foreach (array_keys($headers) as $i) {
            $sheet->setCellValue([$i + 1, 1], $headers[$i]);
        }
        $sheet->getStyle('A1:F1')->getFont()->setBold(true);

        $row = 2;
        foreach ($surat as $i => $s) {
            $sheet->setCellValue([1, $row], $i + 1);
            $sheet->setCellValue([2, $row], $s->jenis_surat);
            $sheet->setCellValue([3, $row], $s->nomor_surat ?? '-');
            $sheet->setCellValue([4, $row], $s->tgl_surat?->format('d/m/Y') ?? '-');
            $sheet->setCellValue([5, $row], $s->siswa?->nama ?? '-');
            $sheet->setCellValue([6, $row], $s->kepada ?? '-');
            $row++;
        }

        foreach (range(1, 6) as $col) {
            $sheet->getColumnDimension(Coordinate::stringFromColumnIndex($col))->setAutoSize(true);
        }

        $writer = new Xlsx($spreadsheet);
        $filename = 'data_surat_' . date('Ymd_His') . '.xlsx';

        return response()->streamDownload(function () use ($writer) {
            $writer->save('php://output');
        }, $filename, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ]);
    }

    public function suratPdf()
    {
        $surat = Surat::with('siswa')->latest()->get();
        $pdf = Pdf::loadView('dapos.exports.surat_pdf', compact('surat'));
        return $pdf->download('data_surat_' . date('Ymd_His') . '.pdf');
    }

    public function siswaPerRombelExcel()
    {
        $headers = [
            'No', 'Rombel', 'Tingkat', 'Tahun Ajaran', 'Wali Kelas', 'Jurusan', 'Kurikulum',
            'NISN', 'NIK', 'No KK', 'Nama', 'JK', 'Tempat Lahir', 'Tanggal Lahir', 'Agama',
            'Kewarganegaraan', 'Anak Ke-', 'Alamat', 'RT', 'RW', 'No. HP', 'Email',
            'Nama Ayah', 'Nama Ibu', 'Nama Wali', 'Sekolah Asal',
            'Tinggi (cm)', 'Berat (kg)', 'Lingkar Kepala (cm)', 'Jarak Rumah (km)', 'Waktu Tempuh (mnt)', 'Jumlah Saudara',
        ];
        $colCount = count($headers);

        $rombelList = Rombel::with(['anggota.siswa.agama', 'anggota.siswa.periodik'])
            ->orderBy('tingkat')->orderBy('nama')
            ->get()
            ->filter(fn($r) => $r->anggota->isNotEmpty());

        if ($rombelList->isEmpty()) {
            $spreadsheet = new Spreadsheet;
            $spreadsheet->getActiveSheet()->setCellValue('A1', 'Tidak ada data anggota rombel.');
            $writer = new Xlsx($spreadsheet);
            $filename = 'daftar_siswa_per_rombel_' . date('Ymd_His') . '.xlsx';
            return response()->streamDownload(function () use ($writer) {
                $writer->save('php://output');
            }, $filename, [
                'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            ]);
        }

        $spreadsheet = new Spreadsheet;
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Daftar Siswa per Rombel');

        foreach ($headers as $i => $h) {
            $col = Coordinate::stringFromColumnIndex($i + 1);
            $sheet->setCellValue($col . '1', $h);
        }
        $sheet->getStyle('A1:' . Coordinate::stringFromColumnIndex($colCount) . '1')->getFont()->setBold(true);

        $row = 2;
        foreach ($rombelList as $rombel) {
            foreach ($rombel->anggota as $a) {
                $s = $a->siswa;
                $p = $s->periodik->sortByDesc('tahun_periodik')->first();
                $c = 1;
                $set = function ($value) use ($sheet, &$c, $row) {
                    $sheet->setCellValue(Coordinate::stringFromColumnIndex($c++) . $row, ($value === null || $value === '') ? '-' : $value);
                };
                $set($row - 1);
                $set($rombel->nama);
                $set($rombel->tingkat);
                $set($rombel->tahun_ajaran);
                $set($rombel->nama_wali_kelas);
                $set($rombel->jurusan);
                $set($rombel->kurikulum);
                $set($s->nisn);
                $set($s->nik);
                $set($s->no_kk);
                $set($s->nama);
                $set($s->jenis_kelamin);
                $set($s->tempat_lahir);
                $set($s->tanggal_lahir?->format('d/m/Y'));
                $set($s->agama?->nama);
                $set($s->kewarganegaraan);
                $set($s->anak_keberapa);
                $set($s->alamat_jalan);
                $set($s->rt);
                $set($s->rw);
                $set($s->nomor_telepon_seluler);
                $set($s->email);
                $set($s->nama_ayah);
                $set($s->nama_ibu_kandung);
                $set($s->nama_wali);
                $set($s->sekolah_asal);
                $set($p?->tinggi_badan);
                $set($p?->berat_badan);
                $set($p?->lingkar_kepala);
                $set($p?->jarak_rumah_sekolah);
                $set($p?->waktu_tempuh);
                $set($p?->jumlah_saudara_kandung);
                $row++;
            }
        }

        foreach (range(1, $colCount) as $i) {
            $sheet->getColumnDimension(Coordinate::stringFromColumnIndex($i))->setAutoSize(true);
        }

        $writer = new Xlsx($spreadsheet);
        $filename = 'daftar_siswa_per_rombel_' . date('Ymd_His') . '.xlsx';

        return response()->streamDownload(function () use ($writer) {
            $writer->save('php://output');
        }, $filename, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ]);
    }
}
