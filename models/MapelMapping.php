<?php

require_once __DIR__ . '/../config/ExcelHelper.php';

/**
 * Mapping Mata Pelajaran per Jenjang.
 *
 * Tabel: tb_mapel_mapping
 *   id_mapel  -> FK ke tb_mapel_referensi (mapel mana)
 *   jenjang   -> 10 / 11 / 12 (untuk jenjang mana)
 *   kategori  -> Umum / Pilihan (kelompok mana di rapor)
 *   urutan    -> urutan tampil di cetak rapor
 *
 * Contoh: "Fisika" (FIS) ada di jenjang 10 sebagai Umum urut 5,
 *         dan di jenjang 11 sebagai Pilihan urut 5 — cukup 2 baris,
 *         tanpa perlu membuat kode mapel terpisah.
 */
class MapelMapping
{
    private mysqli $conn;

    public const JENJANG = ['10', '11', '12'];

    public function __construct(mysqli $conn)
    {
        $this->conn = $conn;
    }

    private function normalizeJenjang(string $j): string
    {
        $j = strtoupper(trim($j));
        $map = [
            '10' => '10', 'X' => '10', 'KELAS X' => '10', 'FASE E' => '10',
            '11' => '11', 'XI' => '11', 'KELAS XI' => '11', 'FASE F' => '11',
            '12' => '12', 'XII' => '12', 'KELAS XII' => '12',
        ];
        return $map[$j] ?? '';
    }

    private function normalizeKategori(string $k): string
    {
        return (stripos($k, 'pilih') !== false || stripos($k, 'peminatan') !== false)
            ? 'Pilihan'
            : 'Umum';
    }

    /**
     * Daftar mapping untuk satu jenjang, diurutkan sesuai urutan rapor.
     * $onlyMapped = true  -> hanya mapel yang sudah/setup di jenjang ini
     * $onlyMapped = false -> semua referensi, plus info sudah ter-mapping atau belum
     */
    public function getByJenjang(string $jenjang, bool $onlyMapped = true): array
    {
        $jenjang = $this->normalizeJenjang($jenjang);
        if ($jenjang === '') {
            return [];
        }

        if ($onlyMapped) {
            $sql = "SELECT m.id_mapel, r.nama_mapel, m.kategori, m.urutan
                    FROM tb_mapel_mapping m
                    INNER JOIN tb_mapel_referensi r ON r.id_mapel = m.id_mapel
                    WHERE m.jenjang = ?
                    ORDER BY m.urutan ASC, r.nama_mapel ASC";
            $stmt = mysqli_prepare($this->conn, $sql);
            mysqli_stmt_bind_param($stmt, "s", $jenjang);
            mysqli_stmt_execute($stmt);
            $res = mysqli_stmt_get_result($stmt);
        } else {
            $sql = "SELECT r.id_mapel, r.nama_mapel,
                           COALESCE(m.kategori, '') AS kategori,
                           COALESCE(m.urutan, 0) AS urutan
                    FROM tb_mapel_referensi r
                    LEFT JOIN tb_mapel_mapping m
                           ON m.id_mapel = r.id_mapel AND m.jenjang = ?
                    ORDER BY (m.urutan IS NULL) DESC, m.urutan ASC, r.nama_mapel ASC";
            $stmt = mysqli_prepare($this->conn, $sql);
            mysqli_stmt_bind_param($stmt, "s", $jenjang);
            mysqli_stmt_execute($stmt);
            $res = mysqli_stmt_get_result($stmt);
        }

        $data = [];
        while ($row = mysqli_fetch_assoc($res)) {
            $data[] = $row;
        }
        return $data;
    }

    /**
     * Statistik mapping untuk semua jenjang (dipakai di halaman referensi).
     * Mengembalikan: ['10' => ['terpakai' => n, 'total' => n], ...]
     */
    public function getStatistik(): array
    {
        $query = mysqli_query(
            $this->conn,
            "SELECT jenjang, COUNT(*) AS jml FROM tb_mapel_mapping GROUP BY jenjang"
        );
        $perJenjang = [];
        foreach (self::JENJANG as $j) {
            $perJenjang[$j] = 0;
        }
        while ($row = mysqli_fetch_assoc($query)) {
            $perJenjang[$row['jenjang']] = (int)$row['jml'];
        }

        $total = mysqli_fetch_assoc(
            mysqli_query($this->conn, "SELECT COUNT(*) AS jml FROM tb_mapel_referensi")
        )['jml'];

        $stat = ['total_referensi' => (int)$total];
        foreach (self::JENJANG as $j) {
            $stat[$j] = [
                'terpakai' => $perJenjang[$j],
                'total'    => (int)$total,
            ];
        }
        return $stat;
    }

    public function getById(string $idMapel, string $jenjang): ?array
    {
        $jenjang = $this->normalizeJenjang($jenjang);
        $stmt = mysqli_prepare(
            $this->conn,
            "SELECT id_mapel, jenjang, kategori, urutan FROM tb_mapel_mapping WHERE id_mapel = ? AND jenjang = ?"
        );
        mysqli_stmt_bind_param($stmt, "ss", $idMapel, $jenjang);
        mysqli_stmt_execute($stmt);
        return mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));
    }

    /**
     * Simpan/upsert satu baris mapping.
     */
    public function save(string $idMapel, string $jenjang, string $kategori, int $urutan): array
    {
        $jenjang = $this->normalizeJenjang($jenjang);
        if ($jenjang === '') {
            return ['success' => false, 'message' => 'Jenjang tidak valid.'];
        }
        $kategori = $this->normalizeKategori($kategori);

        // pastikan mapel ada di referensi
        $cek = mysqli_prepare($this->conn, "SELECT id_mapel FROM tb_mapel_referensi WHERE id_mapel = ?");
        mysqli_stmt_bind_param($cek, "s", $idMapel);
        mysqli_stmt_execute($cek);
        mysqli_stmt_store_result($cek);
        if (mysqli_stmt_num_rows($cek) === 0) {
            return ['success' => false, 'message' => "Mapel '$idMapel' tidak ada di Data Referensi."];
        }

        $stmt = mysqli_prepare(
            $this->conn,
            "INSERT INTO tb_mapel_mapping (id_mapel, jenjang, kategori, urutan) VALUES (?, ?, ?, ?)
             ON DUPLICATE KEY UPDATE kategori = VALUES(kategori), urutan = VALUES(urutan)"
        );
        mysqli_stmt_bind_param($stmt, "sssi", $idMapel, $jenjang, $kategori, $urutan);

        if (mysqli_stmt_execute($stmt)) {
            return ['success' => true, 'message' => 'Mapping mapel berhasil disimpan.'];
        }
        return ['success' => false, 'message' => 'Gagal menyimpan mapping.'];
    }

    public function delete(string $idMapel, string $jenjang): array
    {
        $jenjang = $this->normalizeJenjang($jenjang);
        $stmt = mysqli_prepare(
            $this->conn,
            "DELETE FROM tb_mapel_mapping WHERE id_mapel = ? AND jenjang = ?"
        );
        mysqli_stmt_bind_param($stmt, "ss", $idMapel, $jenjang);

        if (mysqli_stmt_execute($stmt)) {
            return ['success' => true, 'message' => 'Mapel dihapus dari jenjang ini.'];
        }
        return ['success' => false, 'message' => 'Gagal menghapus mapping.'];
    }

    /**
     * Salin seluruh mapping dari satu jenjang ke jenjang lain.
     * Berguna saat mewarisi struktur mapel XI ke XII.
     */
    public function copyJenjang(string $dari, string $ke, bool $timpa = false): array
    {
        $dari = $this->normalizeJenjang($dari);
        $ke   = $this->normalizeJenjang($ke);

        if ($dari === '' || $ke === '') {
            return ['success' => false, 'message' => 'Jenjang asal/tujuan tidak valid.'];
        }
        if ($dari === $ke) {
            return ['success' => false, 'message' => 'Jenjang asal dan tujuan tidak boleh sama.'];
        }

        $stmtCek = mysqli_prepare($this->conn, "SELECT COUNT(*) AS jml FROM tb_mapel_mapping WHERE jenjang = ?");
        mysqli_stmt_bind_param($stmtCek, "s", $dari);
        mysqli_stmt_execute($stmtCek);
        $jml = (int)mysqli_fetch_assoc(mysqli_stmt_get_result($stmtCek))['jml'];
        $stmtCek->close();

        if ($jml === 0) {
            return ['success' => false, 'message' => "Jenjang $dari belum memiliki mapping untuk disalin."];
        }

        $sql = $timpa
            ? "INSERT INTO tb_mapel_mapping (id_mapel, jenjang, kategori, urutan)
               SELECT id_mapel, '$ke', kategori, urutan FROM tb_mapel_mapping WHERE jenjang = '$dari'
               ON DUPLICATE KEY UPDATE kategori = VALUES(kategori), urutan = VALUES(urutan)"
            : "INSERT IGNORE INTO tb_mapel_mapping (id_mapel, jenjang, kategori, urutan)
               SELECT id_mapel, '$ke', kategori, urutan FROM tb_mapel_mapping WHERE jenjang = '$dari'";

        if (mysqli_query($this->conn, $sql)) {
            return ['success' => true, 'message' => "Mapping jenjang $dari disalin ke jenjang $ke."];
        }
        return ['success' => false, 'message' => 'Gagal menyalin mapping.'];
    }

    /**
     * Import mapping dari Excel/CSV.
     * Header: id_mapel, jenjang, kategori, urutan
     */
    public function importExcel(string $filePath, string $originalFileName): array
    {
        $rows = ExcelHelper::parse($filePath, $originalFileName);

        if (empty($rows)) {
            return ['success' => false, 'message' => 'File Excel kosong atau format tidak sesuai.'];
        }

        $inserted = 0;
        $skipped  = 0;
        $detail   = [];

        foreach ($rows as $row) {
            $id      = strtoupper(trim($row['id_mapel'] ?? ''));
            $jenjang = $this->normalizeJenjang($row['jenjang'] ?? ($row['tingkat'] ?? ''));
            $kategori = $this->normalizeKategori($row['kategori'] ?? 'Umum');
            $urutan  = isset($row['urutan']) && is_numeric($row['urutan']) ? (int)$row['urutan'] : 0;

            if ($id === '' || $jenjang === '') {
                $skipped++;
                $detail[] = "Baris dilewati: id_mapel atau jenjang tidak valid.";
                continue;
            }

            $res = $this->save($id, $jenjang, $kategori, $urutan);
            if ($res['success']) {
                $inserted++;
            } else {
                $skipped++;
                $detail[] = "Baris dilewati: " . $res['message'];
            }
        }

        return [
            'success' => true,
            'message' => "Import mapping selesai. $inserted baris diproses, $skipped dilewati."
        ];
    }
}
