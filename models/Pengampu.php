<?php

class Pengampu
{
    private mysqli $conn;

    /**
     * Urutan tampilan penugasan. Daftar tetap dikelompokkan per kelas supaya
     * guru yang mengajar satu kelas terlihat berurutan.
     * Dipakai bersama oleh getAll() dan findPage() agar tidak melenceng.
     */
    private const URUT = 'k.tingkat ASC, k.nama_kelas ASC, m.nama_mapel ASC, g.nama_guru ASC';

    public function __construct(mysqli $conn)
    {
        $this->conn = $conn;
    }

    /** Kolom yang diurutkan, dalam urutan yang sama dengan self::URUT. */
    private const KOLOM_URUT = ['k.tingkat', 'k.nama_kelas', 'm.nama_mapel', 'g.nama_guru'];

    /**
     * $limit = 0 berarti tanpa paginasi (ambil semua).
     * $limit/$offset sudah bertipe int, jadi aman disisipkan langsung ke SQL.
     */
    public function getAll(int $limit = 0, int $offset = 0): array
    {
        $sql = "SELECT p.*, g.nama_guru, m.nama_mapel, k.nama_kelas, k.tingkat
                FROM tb_pengampu p
                INNER JOIN tb_guru g ON p.id_guru = g.id_guru
                INNER JOIN tb_mapel_referensi m ON p.id_mapel = m.id_mapel
                INNER JOIN tb_kelas k ON p.id_kelas = k.id_kelas
                ORDER BY " . self::URUT;
        if ($limit > 0) {
            $sql .= " LIMIT $limit OFFSET $offset";
        }
        $query = mysqli_query($this->conn, $sql);
        $data = [];
        while ($row = mysqli_fetch_assoc($query)) {
            $data[] = $row;
        }
        return $data;
    }

    /** Total penugasan, untuk menghitung jumlah halaman. */
    public function countAll(): int
    {
        $res = mysqli_query($this->conn, "SELECT COUNT(*) AS c FROM tb_pengampu");
        return (int)mysqli_fetch_assoc($res)['c'];
    }

    /**
     * Halaman ke-1 tempat penugasan (idGuru, idMapel, idKelas) muncul,
     * mengikuti urutan yang sama dengan getAll().
     *
     * Dipakai supaya setelah tambah/edit admin langsung diarahkan ke baris itu,
     * bukan ke halaman 1 — daftar tetap terlihat berkelompok per kelas.
     * Mengembalikan 1 bila barisnya tidak ditemukan.
     */
    public function findPage(string $idGuru, string $idMapel, int $idKelas, int $perPage): int
    {
        if ($perPage < 1) {
            $perPage = 25;
        }

        $sql = "SELECT k.tingkat, k.nama_kelas, m.nama_mapel, g.nama_guru
                FROM tb_pengampu p
                INNER JOIN tb_guru g ON p.id_guru = g.id_guru
                INNER JOIN tb_mapel_referensi m ON p.id_mapel = m.id_mapel
                INNER JOIN tb_kelas k ON p.id_kelas = k.id_kelas
                WHERE p.id_guru = ? AND p.id_mapel = ? AND p.id_kelas = ?";
        $stmt = mysqli_prepare($this->conn, $sql);
        mysqli_stmt_bind_param($stmt, "ssi", $idGuru, $idMapel, $idKelas);
        mysqli_stmt_execute($stmt);
        $res = mysqli_stmt_get_result($stmt);
        $row = mysqli_fetch_assoc($res);
        mysqli_stmt_free_result($stmt);

        if (!$row) {
            return 1;
        }

        // Berapa baris yang urutannya mendahului baris ini.
        $tuple = '(' . implode(', ', self::KOLOM_URUT) . ') < (?, ?, ?, ?)';
        $sql2 = "SELECT COUNT(*) AS c
                 FROM tb_pengampu p
                 INNER JOIN tb_guru g ON p.id_guru = g.id_guru
                 INNER JOIN tb_mapel_referensi m ON p.id_mapel = m.id_mapel
                 INNER JOIN tb_kelas k ON p.id_kelas = k.id_kelas
                 WHERE $tuple";
        $stmt2 = mysqli_prepare($this->conn, $sql2);
        mysqli_stmt_bind_param($stmt2, "ssss", $row['tingkat'], $row['nama_kelas'],
            $row['nama_mapel'], $row['nama_guru']);
        mysqli_stmt_execute($stmt2);
        $res2 = mysqli_stmt_get_result($stmt2);
        $sebelum = (int)mysqli_fetch_assoc($res2)['c'];
        mysqli_stmt_free_result($stmt2);

        return intdiv($sebelum, $perPage) + 1;
    }

    /**
     * Berapa nilai yang sudah terhubung ke penugasan ini.
     * Dipakai untuk memperingatkan admin sebelum memindahkan mapel/kelas.
     */
    public function countNilai(int $idPengampu): int
    {
        $stmt = mysqli_prepare($this->conn, "SELECT COUNT(*) AS c FROM tb_nilai_sts WHERE id_pengampu = ?");
        mysqli_stmt_bind_param($stmt, "i", $idPengampu);
        mysqli_stmt_execute($stmt);
        $res = mysqli_stmt_get_result($stmt);
        return (int)mysqli_fetch_assoc($res)['c'];
    }

    public function create(string $idGuru, string $idMapel, int $idKelas): array
    {
        $check = mysqli_prepare($this->conn, "SELECT id_pengampu FROM tb_pengampu WHERE id_guru = ? AND id_mapel = ? AND id_kelas = ?");
        mysqli_stmt_bind_param($check, "ssi", $idGuru, $idMapel, $idKelas);
        mysqli_stmt_execute($check);
        mysqli_stmt_store_result($check);

        if (mysqli_stmt_num_rows($check) > 0) {
            return ['success' => false, 'message' => 'Penugasan guru pada mapel dan kelas ini sudah ada.'];
        }

        $stmt = mysqli_prepare($this->conn, "INSERT INTO tb_pengampu (id_guru, id_mapel, id_kelas) VALUES (?, ?, ?)");
        mysqli_stmt_bind_param($stmt, "ssi", $idGuru, $idMapel, $idKelas);

        if (mysqli_stmt_execute($stmt)) {
            return ['success' => true, 'message' => 'Penugasan mengajar berhasil ditambahkan.'];
        }
        return ['success' => false, 'message' => 'Gagal menambahkan penugasan mengajar.'];
    }

    public function delete(int $idPengampu): array
    {
        $stmt = mysqli_prepare($this->conn, "DELETE FROM tb_pengampu WHERE id_pengampu = ?");
        mysqli_stmt_bind_param($stmt, "i", $idPengampu);

        if (mysqli_stmt_execute($stmt)) {
            return ['success' => true, 'message' => 'Penugasan mengajar berhasil dihapus.'];
        }
        return ['success' => false, 'message' => 'Gagal menghapus penugasan mengajar.'];
    }

    /**
     * Ubah guru / mapel / kelas pada satu penugasan.
     * id_pengampu tidak berubah, jadi nilai yang sudah terinput tetap menempel
     * pada baris yang sama (lihat catatan di countNilai()).
     */
    public function update(int $idPengampu, string $idGuru, string $idMapel, int $idKelas): array
    {
        if ($idPengampu <= 0) {
            return ['success' => false, 'message' => 'Penugasan tidak valid.'];
        }

        // pastikan baris-nya benar-benar ada
        $cekId = mysqli_prepare($this->conn, "SELECT id_pengampu FROM tb_pengampu WHERE id_pengampu = ?");
        mysqli_stmt_bind_param($cekId, "i", $idPengampu);
        mysqli_stmt_execute($cekId);
        mysqli_stmt_store_result($cekId);
        if (mysqli_stmt_num_rows($cekId) === 0) {
            return ['success' => false, 'message' => 'Penugasan tidak ditemukan.'];
        }

        // duplikat dicek terhadap baris lain, bukan terhadap dirinya sendiri
        $check = mysqli_prepare($this->conn, "SELECT id_pengampu FROM tb_pengampu
                WHERE id_guru = ? AND id_mapel = ? AND id_kelas = ? AND id_pengampu <> ?");
        mysqli_stmt_bind_param($check, "ssii", $idGuru, $idMapel, $idKelas, $idPengampu);
        mysqli_stmt_execute($check);
        mysqli_stmt_store_result($check);

        if (mysqli_stmt_num_rows($check) > 0) {
            return ['success' => false, 'message' => 'Penugasan guru pada mapel dan kelas ini sudah ada.'];
        }

        $stmt = mysqli_prepare($this->conn, "UPDATE tb_pengampu
                SET id_guru = ?, id_mapel = ?, id_kelas = ? WHERE id_pengampu = ?");
        mysqli_stmt_bind_param($stmt, "ssii", $idGuru, $idMapel, $idKelas, $idPengampu);

        if (mysqli_stmt_execute($stmt)) {
            return ['success' => true, 'message' => 'Penugasan mengajar berhasil diperbarui.'];
        }
        return ['success' => false, 'message' => 'Gagal memperbarui penugasan mengajar.'];
    }
}
