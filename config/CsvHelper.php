<?php

class CsvHelper
{
    /**
     * Parsing file CSV dengan auto-detect delimiter (koma atau titik koma)
     * dan menangani encoding BOM UTF-8
     */
    public static function parse(string $filePath): array
    {
        if (!file_exists($filePath) || !is_readable($filePath)) {
            return [];
        }

        $handle = fopen($filePath, 'r');
        if (!$handle) {
            return [];
        }

        // Baca baris pertama untuk deteksi delimiter dan buang BOM
        $firstLine = fgets($handle);
        if ($firstLine === false) {
            fclose($handle);
            return [];
        }

        // Hapus UTF-8 BOM jika ada
        $bom = pack('H*', 'EFBBBF');
        $firstLine = preg_replace("/^$bom/", '', $firstLine);

        // Deteksi delimiter
        $countSemicolon = substr_count($firstLine, ';');
        $countComma = substr_count($firstLine, ',');
        $delimiter = ($countSemicolon > $countComma) ? ';' : ',';

        // Parse header
        $headers = str_getcsv(trim($firstLine), $delimiter);
        $headers = array_map(function ($h) {
            return strtolower(trim($h));
        }, $headers);

        $rows = [];
        while (($data = fgetcsv($handle, 1000, $delimiter)) !== false) {
            if (empty($data) || (count($data) === 1 && trim($data[0]) === '')) {
                continue;
            }
            $row = [];
            foreach ($headers as $index => $headerName) {
                $row[$headerName] = isset($data[$index]) ? trim($data[$index]) : '';
            }
            $rows[] = $row;
        }

        fclose($handle);
        return $rows;
    }

    /**
     * Download template file CSV resmi untuk upload
     */
    public static function downloadTemplate(string $type): void
    {
        header('Content-Type: text/csv; charset=utf-8');
        header('Pragma: no-cache');
        header('Expires: 0');

        switch ($type) {
            case 'guru':
                header('Content-Disposition: attachment; filename="template_guru.csv"');
                $out = fopen('php://output', 'w');
                fputcsv($out, ['id_guru', 'nama_guru', 'username'], ',');
                fputcsv($out, ['GURU003', 'Dra. Sri Wahyuni', 'sriwahyuni'], ',');
                fputcsv($out, ['GURU004', 'Ahmad Fauzi, S.Pd.', 'ahmadfauzi'], ',');
                fclose($out);
                exit;

            case 'kelas':
                header('Content-Disposition: attachment; filename="template_kelas.csv"');
                $out = fopen('php://output', 'w');
                fputcsv($out, ['nama_kelas', 'tingkat'], ',');
                fputcsv($out, ['X-3', '10'], ',');
                fputcsv($out, ['XI-MIPA-2', '11'], ',');
                fputcsv($out, ['XII-IPS-1', '12'], ',');
                fclose($out);
                exit;

            case 'mapel':
                header('Content-Disposition: attachment; filename="template_mapel.csv"');
                $out = fopen('php://output', 'w');
                fputcsv($out, ['id_mapel', 'nama_mapel'], ',');
                fputcsv($out, ['KIM', 'Kimia'], ',');
                fputcsv($out, ['BIO', 'Biologi'], ',');
                fputcsv($out, ['SEJ', 'Sejarah'], ',');
                fclose($out);
                exit;

            case 'siswa':
                header('Content-Disposition: attachment; filename="template_siswa.csv"');
                $out = fopen('php://output', 'w');
                fputcsv($out, ['nis', 'nama', 'nama_kelas', 'username'], ',');
                fputcsv($out, ['SIS003', 'Aulia Rahma', 'X-1', 'aulia_rahma'], ',');
                fputcsv($out, ['SIS004', 'Rizky Ramadhan', 'X-1', 'rizky_r'], ',');
                fclose($out);
                exit;

            default:
                die('Jenis template tidak ditemukan');
        }
    }
}
