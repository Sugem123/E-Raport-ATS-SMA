<?php

require_once __DIR__ . '/../config/ExcelHelper.php';

/**
 * Data Referensi Mata Pelajaran (master).
 *
 * Menyimpan daftar induk seluruh mata pelajaran yang dikenal sekolah.
 * TIDUK menyimpan jenjang / kategori / urutan cetakan rapor — itu
 * ditangani oleh MapelMapping (tabel tb_mapel_mapping).
 *
 * Contoh: "Fisika" disimpan satu kali di sini, lalu dipetakan ke
 * jenjang 10 (Umum), 11 (Pilihan), dan 12 (Pilihan) di mapping.
 */
class Mapel
{
    private mysqli $conn;

    /** Jenjang yang wajib punya mapping (dipakai untuk ringkasan referensi) */
    public const JENJANG = ['10', '11', '12'];

    public function __construct(mysqli $conn)
    {
        $this->conn = $conn;
    }

    /**
     * Ambil seluruh data referensi, lengkap dengan ringkasan mapping
     * per jenjang (agar admin tahu mapel ini terpakai di mana saja).
     */
    public function getAll(?string $search = null): array
    {
        $where = "";
        $params = [];
        $types = "";

        if ($search !== null && $search !== '') {
            $where = " WHERE r.id_mapel LIKE ? OR r.nama_mapel LIKE ?";
            $params[] = "%$search%";
            $params[] = "%$search%";
            $types = "ss";
        }

        $sql = "SELECT r.id_mapel, r.nama_mapel, r.sistem,
                       (SELECT GROUP_CONCAT(m.jenjang ORDER BY m.jenjang SEPARATOR ',')
                          FROM tb_mapel_mapping m WHERE m.id_mapel = r.id_mapel) AS jenjang_terpakai,
                       (SELECT COUNT(*) FROM tb_mapel_mapping m WHERE m.id_mapel = r.id_mapel) AS jml_mapping
                FROM tb_mapel_referensi r"
              . $where
              . " ORDER BY r.nama_mapel ASC";

        if (!empty($params)) {
            $stmt = mysqli_prepare($this->conn, $sql);
            mysqli_stmt_bind_param($stmt, $types, ...$params);
            mysqli_stmt_execute($stmt);
            $query = mysqli_stmt_get_result($stmt);
        } else {
            $query = mysqli_query($this->conn, $sql);
        }

        $data = [];
        while ($row = mysqli_fetch_assoc($query)) {
            $data[] = $row;
        }
        return $data;
    }

    /**
     * Daftar ringkas untuk dropdown (dipakai di form mapping & penugasan).
     */
    public function getOptions(): array
    {
        $query = mysqli_query(
            $this->conn,
            "SELECT id_mapel, nama_mapel FROM tb_mapel_referensi ORDER BY nama_mapel ASC"
        );
        $data = [];
        while ($row = mysqli_fetch_assoc($query)) {
            $data[] = $row;
        }
        return $data;
    }

    public function getById(string $idMapel): ?array
    {
        $stmt = mysqli_prepare(
            $this->conn,
            "SELECT id_mapel, nama_mapel, sistem FROM tb_mapel_referensi WHERE id_mapel = ?"
        );
        mysqli_stmt_bind_param($stmt, "s", $idMapel);
        mysqli_stmt_execute($stmt);
        $res = mysqli_stmt_get_result($stmt);
        return mysqli_fetch_assoc($res);
    }

    public function create(string $idMapel, string $namaMapel): array
    {
        $idMapel   = strtoupper(trim($idMapel));
        $namaMapel = trim($namaMapel);

        if ($idMapel === '' || $namaMapel === '') {
            return ['success' => false, 'message' => 'Kode dan nama mapel wajib diisi.'];
        }

        $check = mysqli_prepare($this->conn, "SELECT id_mapel FROM tb_mapel_referensi WHERE id_mapel = ?");
        mysqli_stmt_bind_param($check, "s", $idMapel);
        mysqli_stmt_execute($check);
        mysqli_stmt_store_result($check);

        if (mysqli_stmt_num_rows($check) > 0) {
            return ['success' => false, 'message' => "Kode Mapel '$idMapel' sudah terdaftar."];
        }

        $stmt = mysqli_prepare(
            $this->conn,
            "INSERT INTO tb_mapel_referensi (id_mapel, nama_mapel, sistem) VALUES (?, ?, 0)"
        );
        mysqli_stmt_bind_param($stmt, "ss", $idMapel, $namaMapel);

        if (mysqli_stmt_execute($stmt)) {
            return [
                'success' => true,
                'message' => "Mapel '$namaMapel' ditambahkan. Silakan atur penempatannya di Menu Mapping Mapel."
            ];
        }
        return ['success' => false, 'message' => 'Gagal menambahkan mapel.'];
    }

    public function update(string $idMapelLama, string $idMapelBaru, string $namaMapel): array
    {
        $idMapelLama = trim($idMapelLama);
        $idMapelBaru  = strtoupper(trim($idMapelBaru));
        $namaMapel    = trim($namaMapel);

        if ($idMapelBaru === '' || $namaMapel === '') {
            return ['success' => false, 'message' => 'Kode dan nama mapel wajib diisi.'];
        }

        // Tolak perubahan kode pada mapel sistem
        $existing = $this->getById($idMapelLama);
        if (!$existing) {
            return ['success' => false, 'message' => "Mapel '$idMapelLama' tidak ditemukan."];
        }
        if ((int)$existing['sistem'] === 1 && $idMapelBaru !== $idMapelLama) {
            return ['success' => false, 'message' => 'Kode mapel sistem tidak boleh diubah.'];
        }

        if ($idMapelBaru !== $idMapelLama) {
            $check = mysqli_prepare(
                $this->conn,
                "SELECT id_mapel FROM tb_mapel_referensi WHERE id_mapel = ? AND id_mapel != ?"
            );
            mysqli_stmt_bind_param($check, "ss", $idMapelBaru, $idMapelLama);
            mysqli_stmt_execute($check);
            mysqli_stmt_store_result($check);

            if (mysqli_stmt_num_rows($check) > 0) {
                return ['success' => false, 'message' => "Kode Mapel '$idMapelBaru' sudah digunakan mapel lain."];
            }
        }

        // FK tb_mapel_mapping & tb_pengampu memakai ON UPDATE CASCADE,
        // jadi perubahan kode otomatis ter-propagasi ke tabel anak.
        $stmt = mysqli_prepare(
            $this->conn,
            "UPDATE tb_mapel_referensi SET id_mapel = ?, nama_mapel = ? WHERE id_mapel = ?"
        );
        mysqli_stmt_bind_param($stmt, "sss", $idMapelBaru, $namaMapel, $idMapelLama);

        if (mysqli_stmt_execute($stmt)) {
            return ['success' => true, 'message' => 'Data referensi mapel berhasil diupdate.'];
        }
        return ['success' => false, 'message' => 'Gagal mengupdate mapel.'];
    }

    public function delete(string $idMapel): array
    {
        $existing = $this->getById($idMapel);
        if (!$existing) {
            return ['success' => false, 'message' => "Mapel '$idMapel' tidak ditemukan."];
        }
        if ((int)$existing['sistem'] === 1) {
            return ['success' => false, 'message' => 'Mapel sistem tidak dapat dihapus.'];
        }

        // Cek apakah masih dipakai di penugasan mengajar
        $cek = mysqli_prepare($this->conn, "SELECT COUNT(*) AS jml FROM tb_pengampu WHERE id_mapel = ?");
        mysqli_stmt_bind_param($cek, "s", $idMapel);
        mysqli_stmt_execute($cek);
        $jml = mysqli_fetch_assoc(mysqli_stmt_get_result($cek))['jml'];

        if ((int)$jml > 0) {
            return [
                'success' => false,
                'message' => "Mapel '$idMapel' masih ditugaskan mengajar di $jml kelas. Hapus penugasannya dulu."
            ];
        }

        // Mapping ikut terhapus otomatis (ON DELETE CASCADE)
        $stmt = mysqli_prepare($this->conn, "DELETE FROM tb_mapel_referensi WHERE id_mapel = ?");
        mysqli_stmt_bind_param($stmt, "s", $idMapel);

        if (mysqli_stmt_execute($stmt)) {
            return ['success' => true, 'message' => 'Mapel beserta seluruh mapping-nya berhasil dihapus.'];
        }
        return ['success' => false, 'message' => 'Gagal menghapus mapel.'];
    }

    /**
     * Import daftar referensi dari Excel/CSV.
     * Header: id_mapel, nama_mapel
     * Tidak menyentuh tabel mapping.
     */
    public function importExcel(string $filePath, string $originalFileName): array
    {
        $rows = ExcelHelper::parse($filePath, $originalFileName);

        if (empty($rows)) {
            return ['success' => false, 'message' => 'File Excel kosong atau format tidak sesuai.'];
        }

        $inserted = 0;
        $skipped  = 0;

        foreach ($rows as $row) {
            $id   = strtoupper(trim($row['id_mapel'] ?? ''));
            $nama = trim($row['nama_mapel'] ?? '');

            if ($id === '' || $nama === '') {
                $skipped++;
                continue;
            }

            $res = $this->create($id, $nama);
            if ($res['success']) {
                $inserted++;
            } else {
                $skipped++;
            }
        }

        return [
            'success' => true,
            'message' => "Import referensi selesai. $inserted mapel ditambahkan, $skipped dilewati. "
                       . "Langkah berikutnya: atur penempatan per jenjang di Menu Mapping Mapel."
        ];
    }
}
