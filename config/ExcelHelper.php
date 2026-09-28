<?php

require_once __DIR__ . '/SimpleXlsx.php';
require_once __DIR__ . '/CsvHelper.php';

class ExcelHelper
{
    /**
     * Membaca file yang diupload (baik .xlsx maupun .csv)
     * Mengembalikan array asosiatif [ ['header1' => val1, ...], ... ]
     */
    public static function parse(string $filePath, string $originalFileName): array
    {
        $ext = strtolower(pathinfo($originalFileName, PATHINFO_EXTENSION));

        if ($ext === 'xlsx') {
            $rawRows = SimpleXlsx::parse($filePath);
            if (empty($rawRows)) {
                return [];
            }

            // Cari baris header yang berisi kolom kunci: 'nis' atau 'id_guru' atau 'nama_kelas' atau 'id_mapel'
            $headerRowIndex = 0;
            foreach ($rawRows as $idx => $r) {
                foreach ($r as $cell) {
                    $cleaned = strtolower(trim((string)$cell));
                    if (in_array($cleaned, ['nis', 'id_guru', 'nama_kelas', 'id_mapel', 'nama_siswa'])) {
                        $headerRowIndex = $idx;
                        break 2;
                    }
                }
            }

            // Buang baris judul / metadata kop sebelum header tabel
            if ($headerRowIndex > 0) {
                array_splice($rawRows, 0, $headerRowIndex);
            }

            // Ambil header
            $headers = array_shift($rawRows);
            $normalizedHeaders = array_map(function ($h) {
                // Bersihkan spasi dan ubah ke snake_case lowercase
                $h = strtolower(trim((string)$h));
                $h = str_replace([' ', '-'], '_', $h);
                return $h;
            }, $headers);

            $result = [];
            foreach ($rawRows as $row) {
                if (empty($row) || (count($row) === 1 && trim((string)$row[0]) === '')) {
                    continue;
                }
                $assoc = [];
                foreach ($normalizedHeaders as $colIdx => $key) {
                    $assoc[$key] = isset($row[$colIdx]) ? trim((string)$row[$colIdx]) : '';
                }
                $result[] = $assoc;
            }
            return $result;
        }

        // Default CSV
        return CsvHelper::parse($filePath);
    }

    /**
     * Download template Excel resmi (.xlsx)
     */
    public static function downloadTemplate(string $type, array $extraData = []): void
    {
        switch ($type) {
            case 'guru':
                $headers = ['id_guru', 'nama_guru', 'username'];
                $sample = [
                    ['GURU003', 'Dra. Hj. Siti Aminah, M.Pd.', 'sitiaminah'],
                    ['GURU004', 'Ahmad Fauzi, S.Pd.', 'ahmadfauzi'],
                ];
                SimpleXlsx::download('template_guru.xlsx', $headers, $sample);
                break;

            case 'kelas':
                $headers = ['nama_kelas', 'tingkat', 'id_guru_walikelas'];
                $sample = [
                    ['X-1', '10', 'GURU003'],
                    ['X-2', '10', 'GURU001'],
                    ['XI-MIPA-1', '11', 'GURU002'],
                ];
                SimpleXlsx::download('template_kelas.xlsx', $headers, $sample);
                break;

            case 'mapel':
                // Template DATA REFERENSI mapel (daftar induk semua mapel)
                $headers = ['id_mapel', 'nama_mapel'];
                $sample = [
                    ['PKN', 'Pendidikan Pancasila dan Kewarganegaraan'],
                    ['BIND', 'Bahasa Indonesia'],
                    ['MTK', 'Matematika'],
                    ['FIS', 'Fisika'],
                    ['EKO', 'Ekonomi'],
                ];
                SimpleXlsx::download('template_referensi_mapel.xlsx', $headers, $sample);
                break;

            case 'mapping':
                // Template MAPPING mapel per jenjang (urutan cetak rapor)
                $headers = ['id_mapel', 'jenjang', 'kategori', 'urutan'];
                $sample = [
                    ['PKN', '10', 'Umum', 1],
                    ['BIND', '10', 'Umum', 2],
                    ['FIS', '10', 'Umum', 5],
                    ['FIS', '11', 'Pilihan', 5],
                    ['EKO', '11', 'Pilihan', 6],
                ];
                SimpleXlsx::download('template_mapping_mapel.xlsx', $headers, $sample);
                break;

            case 'siswa':
                $headers = ['nis', 'nisn', 'nama', 'nama_kelas', 'username'];
                $sample = [
                    ['SIS003', '0115119643', 'Aulia Rahmawati', 'X-1', 'auliarahma'],
                    ['SIS004', '0114219348', 'Rizky Ramadhan', 'X-1', 'rizkyramadhan'],
                ];
                SimpleXlsx::download('template_siswa.xlsx', $headers, $sample);
                break;

            case 'nilai_kelas':
                // Template nilai yang SUDAH TERCANTUM NAMA SISWA SESUAI KELASNYA
                $namaKelas = $extraData['nama_kelas'] ?? 'Kelas';
                $namaMapel = $extraData['nama_mapel'] ?? 'Mapel';
                $namaGuru  = $extraData['nama_guru'] ?? '';
                $students  = $extraData['students'] ?? [];

                $headers = ['nis', 'nama_siswa', 'sumatif_1', 'sumatif_2', 'sumatif_3', 'nilai_sts'];
                $data = [];
                foreach ($students as $s) {
                    $data[] = [
                        $s['nis'],
                        $s['nama'],
                        isset($s['sumatif_1']) && $s['sumatif_1'] !== null ? $s['sumatif_1'] : '',
                        isset($s['sumatif_2']) && $s['sumatif_2'] !== null ? $s['sumatif_2'] : '',
                        isset($s['sumatif_3']) && $s['sumatif_3'] !== null ? $s['sumatif_3'] : '',
                        isset($s['nilai_sts']) && $s['nilai_sts'] !== null ? $s['nilai_sts'] : '',
                    ];
                }

                $baseName  = preg_replace('/[^a-zA-Z0-9_\-]/', '_', "template_nilai_{$namaKelas}_{$namaMapel}");
                $cleanName = $baseName . '.xlsx';
                SimpleXlsx::downloadTemplateNilai($cleanName, $namaKelas, $namaMapel, $namaGuru, $headers, $data);
                break;

            case 'presensi_kelas':
                $namaKelas     = $extraData['nama_kelas'] ?? 'Kelas';
                $namaWalikelas = $extraData['nama_walikelas'] ?? '';
                $students      = $extraData['students'] ?? [];

                $headers = ['nis', 'nama_siswa', 'sakit', 'izin', 'alpa'];
                $data = [];
                foreach ($students as $s) {
                    $data[] = [
                        $s['nis'],
                        $s['nama'],
                        (int)($s['sakit'] ?? 0),
                        (int)($s['izin'] ?? 0),
                        (int)($s['alpa'] ?? 0),
                    ];
                }

                $baseName  = preg_replace('/[^a-zA-Z0-9_\-]/', '_', "template_presensi_{$namaKelas}");
                $cleanName = $baseName . '.xlsx';
                SimpleXlsx::downloadTemplatePresensi($cleanName, $namaKelas, $namaWalikelas, $headers, $data);
                break;

            default:
                die('Jenis template tidak dikenali.');
        }
    }
}
