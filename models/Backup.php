<?php

class Backup
{
    private mysqli $conn;

    // Tabel inti yang menjadi prioritas urutan relasi foreign key
    private const CORE_TABLES = [
        'tb_user',
        'tb_admin',
        'tb_guru',
        'tb_kelas',
        'tb_mapel_referensi',
        'tb_mapel_mapping',
        'tb_siswa',
        'tb_pengampu',
        'tb_nilai_sts',
        'tb_presensi_sts',
        'tb_pengaturan_bobot',
        'tb_pengaturan_rapor'
    ];

    public function __construct(mysqli $conn)
    {
        $this->conn = $conn;
    }

    /**
     * Mengambil ringkasan statistik database saat ini (ukuran, jumlah tabel, baris data)
     */
    public function getDatabaseStats(): array
    {
        $tables = [];
        $totalRows = 0;
        $totalBytes = 0;

        // Ambil daftar tabel dan ukuran dari information_schema
        $sql = "SELECT table_name AS nama_tabel,
                       table_rows AS perkiraan_baris,
                       data_length + index_length AS total_size
                FROM information_schema.tables
                WHERE table_schema = DATABASE() AND table_type = 'BASE TABLE'
                ORDER BY table_name ASC";
        $q = mysqli_query($this->conn, $sql);

        if ($q) {
            while ($r = mysqli_fetch_assoc($q)) {
                $tableName = $r['nama_tabel'];
                // Hitung baris aktual dengan COUNT(*) untuk data akurat
                $qCount = mysqli_query($this->conn, "SELECT COUNT(*) as c FROM `" . mysqli_real_escape_string($this->conn, $tableName) . "`");
                $actualRows = ($qCount && $rowC = mysqli_fetch_assoc($qCount)) ? (int)$rowC['c'] : (int)$r['perkiraan_baris'];

                $size = (int)$r['total_size'];
                $totalRows += $actualRows;
                $totalBytes += $size;

                $tables[] = [
                    'nama' => $tableName,
                    'baris' => $actualRows,
                    'ukuran_bytes' => $size,
                    'ukuran_kb' => round($size / 1024, 2)
                ];
            }
        }

        return [
            'total_tabel' => count($tables),
            'total_baris' => $totalRows,
            'total_bytes' => $totalBytes,
            'total_kb' => round($totalBytes / 1024, 2),
            'total_mb' => round($totalBytes / (1024 * 1024), 2),
            'tabel_list' => $tables
        ];
    }

    /**
     * Menghasilkan dump SQL murni secara aman (hanya membaca skema & data, tanpa mengubah apa pun)
     */
    public function exportSql(callable $outputChunk = null): ?string
    {
        // Ambil semua tabel dasar yang ada
        $allTables = [];
        $res = mysqli_query($this->conn, "SHOW FULL TABLES WHERE Table_type = 'BASE TABLE'");
        while ($row = mysqli_fetch_row($res)) {
            $allTables[] = $row[0];
        }

        // Urutkan tabel: tabel inti terlebih dahulu, diikuti tabel lainnya
        $sortedTables = [];
        foreach (self::CORE_TABLES as $ct) {
            if (in_array($ct, $allTables, true)) {
                $sortedTables[] = $ct;
            }
        }
        foreach ($allTables as $t) {
            if (!in_array($t, $sortedTables, true)) {
                $sortedTables[] = $t;
            }
        }

        $header = "-- ========================================================\n"
                . "-- Backup Database e-Raport STS (SMAN 1 Prambon Nganjuk)\n"
                . "-- Waktu Export : " . date('Y-m-d H:i:s') . "\n"
                . "-- Host         : " . mysqli_get_host_info($this->conn) . "\n"
                . "-- Server Ver   : " . mysqli_get_server_info($this->conn) . "\n"
                . "-- ========================================================\n\n"
                . "SET FOREIGN_KEY_CHECKS = 0;\n"
                . "SET SQL_MODE = 'NO_AUTO_VALUE_ON_ZERO';\n"
                . "SET NAMES utf8mb4;\n\n";

        if ($outputChunk) {
            $outputChunk($header);
        } else {
            $sqlContent = $header;
        }

        foreach ($sortedTables as $table) {
            $escapedTable = "`" . str_replace("`", "``", $table) . "`";

            // 1. DROP & CREATE TABLE
            $tableHeader = "-- --------------------------------------------------------\n"
                         . "-- Struktur Tabel: $escapedTable\n"
                         . "-- --------------------------------------------------------\n"
                         . "DROP TABLE IF EXISTS $escapedTable;\n";

            $qCreate = mysqli_query($this->conn, "SHOW CREATE TABLE $escapedTable");
            if ($qCreate && $rCreate = mysqli_fetch_row($qCreate)) {
                $tableHeader .= $rCreate[1] . ";\n\n";
            }

            if ($outputChunk) {
                $outputChunk($tableHeader);
            } else {
                $sqlContent .= $tableHeader;
            }

            // 2. DATA (INSERT INTO)
            $qData = mysqli_query($this->conn, "SELECT * FROM $escapedTable", MYSQLI_USE_RESULT);
            if ($qData) {
                $fields = mysqli_fetch_fields($qData);
                $colNames = array_map(fn($f) => "`" . str_replace("`", "``", $f->name) . "`", $fields);
                $colListSql = implode(", ", $colNames);

                $batch = [];
                $batchSize = 100;

                while ($row = mysqli_fetch_row($qData)) {
                    $escapedVals = [];
                    foreach ($row as $val) {
                        if ($val === null) {
                            $escapedVals[] = "NULL";
                        } else {
                            $escapedVals[] = "'" . mysqli_real_escape_string($this->conn, $val) . "'";
                        }
                    }
                    $batch[] = "(" . implode(", ", $escapedVals) . ")";

                    if (count($batch) >= $batchSize) {
                        $insertSql = "INSERT INTO $escapedTable ($colListSql) VALUES\n"
                                   . implode(",\n", $batch) . ";\n";
                        if ($outputChunk) {
                            $outputChunk($insertSql);
                        } else {
                            $sqlContent .= $insertSql;
                        }
                        $batch = [];
                    }
                }

                if (!empty($batch)) {
                    $insertSql = "INSERT INTO $escapedTable ($colListSql) VALUES\n"
                               . implode(",\n", $batch) . ";\n\n";
                    if ($outputChunk) {
                        $outputChunk($insertSql);
                    } else {
                        $sqlContent .= $insertSql;
                    }
                } else {
                    if ($outputChunk) {
                        $outputChunk("\n");
                    } else {
                        $sqlContent .= "\n";
                    }
                }
            }
        }

        $footer = "SET FOREIGN_KEY_CHECKS = 1;\n"
                . "-- Selesai export backup database.\n";

        if ($outputChunk) {
            $outputChunk($footer);
            return null;
        }

        $sqlContent .= $footer;
        return $sqlContent;
    }

    /**
     * Memulihkan / mengimpor database dari berkas .sql
     */
    public function importSql(string $filePath): array
    {
        if (!file_exists($filePath) || !is_readable($filePath)) {
            return ['success' => false, 'message' => 'Berkas backup tidak ditemukan atau tidak dapat dibaca.'];
        }

        $handle = fopen($filePath, 'r');
        if (!$handle) {
            return ['success' => false, 'message' => 'Gagal membuka berkas backup.'];
        }

        mysqli_query($this->conn, "SET FOREIGN_KEY_CHECKS = 0");

        $query = '';
        $executed = 0;
        $error = null;

        while (($line = fgets($handle)) !== false) {
            // Abaikan baris komentar atau baris kosong
            $trimmed = trim($line);
            if ($trimmed === '' || str_starts_with($trimmed, '--') || str_starts_with($trimmed, '/*')) {
                continue;
            }

            $query .= $line;

            // Jika akhir pernyataan (tanda ;)
            if (str_ends_with($trimmed, ';')) {
                if (!mysqli_query($this->conn, $query)) {
                    $error = mysqli_error($this->conn);
                    break;
                }
                $executed++;
                $query = '';
            }
        }

        fclose($handle);
        mysqli_query($this->conn, "SET FOREIGN_KEY_CHECKS = 1");

        if ($error) {
            return [
                'success' => false,
                'message' => "Gagal restore database pada query ke-$executed: $error"
            ];
        }

        return [
            'success' => true,
            'message' => "Database berhasil direstore. Sebanyak $executed perintah query berhasil dijalankan."
        ];
    }
}
