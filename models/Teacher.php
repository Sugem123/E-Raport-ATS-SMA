<?php

class Teacher
{
    private mysqli $conn;

    public function __construct(mysqli $conn)
    {
        $this->conn = $conn;
    }

    public function getAll(): array
    {
        $sql = "SELECT u.id_user, u.username, g.id_guru, g.nama_guru
                FROM tb_user u
                INNER JOIN tb_guru g ON u.id_user = g.id_user
                ORDER BY g.nama_guru ASC";
        $query = mysqli_query($this->conn, $sql);
        $data = [];
        while ($row = mysqli_fetch_assoc($query)) {
            $data[] = $row;
        }
        return $data;
    }

    public function getById(string $idGuru): ?array
    {
        $stmt = mysqli_prepare(
            $this->conn,
            "SELECT u.id_user, u.username, g.id_guru, g.nama_guru
             FROM tb_guru g
             INNER JOIN tb_user u ON g.id_user = u.id_user
             WHERE g.id_guru = ?"
        );
        mysqli_stmt_bind_param($stmt, "s", $idGuru);
        mysqli_stmt_execute($stmt);
        $res = mysqli_stmt_get_result($stmt);
        return mysqli_fetch_assoc($res);
    }

    public function create(
        string $idGuru,
        string $namaGuru,
        string $username,
        string $password = '12345',
        string $role = 'guru'
    ): array {
        $idGuru = trim($idGuru);
        $namaGuru = trim($namaGuru);
        $username = trim($username);

        $check = mysqli_prepare(
            $this->conn,
            "SELECT id_user FROM tb_user WHERE username = ?
             UNION
             SELECT id_user FROM tb_guru WHERE id_guru = ?"
        );
        mysqli_stmt_bind_param($check, "ss", $username, $idGuru);
        mysqli_stmt_execute($check);
        mysqli_stmt_store_result($check);

        if (mysqli_stmt_num_rows($check) > 0) {
            return [
                'success' => false,
                'message' => "Username '$username' atau ID Guru '$idGuru' sudah terdaftar."
            ];
        }

        mysqli_begin_transaction($this->conn);
        try {
            $hashed = password_hash($password, PASSWORD_BCRYPT);
            $userStmt = mysqli_prepare($this->conn, "INSERT INTO tb_user (username, password, role) VALUES (?, ?, ?)");
            mysqli_stmt_bind_param($userStmt, "sss", $username, $hashed, $role);
            mysqli_stmt_execute($userStmt);
            $userId = mysqli_insert_id($this->conn);

            $guruStmt = mysqli_prepare($this->conn, "INSERT INTO tb_guru (id_guru, id_user, nama_guru) VALUES (?, ?, ?)");
            mysqli_stmt_bind_param($guruStmt, "sis", $idGuru, $userId, $namaGuru);
            mysqli_stmt_execute($guruStmt);

            mysqli_commit($this->conn);
            return ['success' => true, 'message' => 'Guru berhasil ditambahkan.'];
        } catch (Throwable $e) {
            mysqli_rollback($this->conn);
            return ['success' => false, 'message' => 'Gagal menambah guru: ' . $e->getMessage()];
        }
    }

    public function update(int $idUser, string $username, string $namaGuru): array
    {
        $username = trim($username);
        $namaGuru = trim($namaGuru);

        $check = mysqli_prepare($this->conn, "SELECT id_user FROM tb_user WHERE username = ? AND id_user != ?");
        mysqli_stmt_bind_param($check, "si", $username, $idUser);
        mysqli_stmt_execute($check);
        mysqli_stmt_store_result($check);

        if (mysqli_stmt_num_rows($check) > 0) {
            return ['success' => false, 'message' => 'Username sudah digunakan oleh user lain.'];
        }

        mysqli_begin_transaction($this->conn);
        try {
            $userStmt = mysqli_prepare($this->conn, "UPDATE tb_user SET username = ? WHERE id_user = ?");
            mysqli_stmt_bind_param($userStmt, "si", $username, $idUser);
            mysqli_stmt_execute($userStmt);

            $guruStmt = mysqli_prepare($this->conn, "UPDATE tb_guru SET nama_guru = ? WHERE id_user = ?");
            mysqli_stmt_bind_param($guruStmt, "si", $namaGuru, $idUser);
            mysqli_stmt_execute($guruStmt);

            mysqli_commit($this->conn);
            return ['success' => true, 'message' => 'Data guru berhasil diupdate.'];
        } catch (Throwable $e) {
            mysqli_rollback($this->conn);
            return ['success' => false, 'message' => 'Gagal update guru: ' . $e->getMessage()];
        }
    }

    public function delete(int $idUser): array
    {
        $stmt = mysqli_prepare($this->conn, "DELETE FROM tb_user WHERE id_user = ?");
        mysqli_stmt_bind_param($stmt, "i", $idUser);

        if (mysqli_stmt_execute($stmt)) {
            return ['success' => true, 'message' => 'Data guru berhasil dihapus.'];
        }
        return ['success' => false, 'message' => 'Gagal menghapus guru.'];
    }

    public function importExcel(string $filePath, string $originalFileName): array
    {
        require_once __DIR__ . '/../config/ExcelHelper.php';
        $rows = ExcelHelper::parse($filePath, $originalFileName);

        if (empty($rows)) {
            return ['success' => false, 'message' => 'File Excel kosong atau format tidak sesuai.'];
        }

        $inserted = 0;
        $skipped = 0;

        foreach ($rows as $row) {
            $idGuru = $row['id_guru'] ?? '';
            $nama = $row['nama_guru'] ?? '';
            $username = $row['username'] ?? '';

            if (empty($idGuru) || empty($nama) || empty($username)) {
                $skipped++;
                continue;
            }

            $res = $this->create($idGuru, $nama, $username, '12345', 'guru');
            if ($res['success']) {
                $inserted++;
            } else {
                $skipped++;
            }
        }

        return [
            'success' => true,
            'message' => "Upload selesai. $inserted guru berhasil ditambahkan, $skipped dilewati/sudah ada."
        ];
    }

    public function getPengampu(string $idGuru): array
    {
        $sql = "SELECT p.id_pengampu, p.id_guru, m.id_mapel, m.nama_mapel, k.id_kelas, k.nama_kelas, k.tingkat
                FROM tb_pengampu p
                INNER JOIN tb_mapel_referensi m ON p.id_mapel = m.id_mapel
                INNER JOIN tb_kelas k ON p.id_kelas = k.id_kelas
                WHERE p.id_guru = ?
                ORDER BY k.tingkat ASC, k.nama_kelas ASC, m.nama_mapel ASC";
        $stmt = mysqli_prepare($this->conn, $sql);
        mysqli_stmt_bind_param($stmt, "s", $idGuru);
        mysqli_stmt_execute($stmt);
        $res = mysqli_stmt_get_result($stmt);

        $data = [];
        while ($row = mysqli_fetch_assoc($res)) {
            $data[] = $row;
        }
        return $data;
    }

    public function getStudentsForGrade(int $idPengampu): array
    {
        // Ambil info pengampu
        $stmtPengampu = mysqli_prepare($this->conn, "SELECT id_kelas FROM tb_pengampu WHERE id_pengampu = ?");
        mysqli_stmt_bind_param($stmtPengampu, "i", $idPengampu);
        mysqli_stmt_execute($stmtPengampu);
        $pengampu = mysqli_fetch_assoc(mysqli_stmt_get_result($stmtPengampu));

        if (!$pengampu) {
            return [];
        }

        $idKelas = (int)$pengampu['id_kelas'];

        // Ambil siswa di kelas tersebut beserta nilai dan presensinya
        $sql = "SELECT s.nis, s.nama, k.nama_kelas,
                       n.id_nilai, n.sumatif_1, n.sumatif_2, n.sumatif_3, n.rata_sumatif,
                       n.nilai_sts, n.nilai_akhir, n.status_kelulusan,
                       COALESCE(pr.sakit, 0) AS sakit,
                       COALESCE(pr.izin, 0) AS izin,
                       COALESCE(pr.alpa, 0) AS alpa
                FROM tb_siswa s
                INNER JOIN tb_kelas k ON s.id_kelas = k.id_kelas
                LEFT JOIN tb_nilai_sts n ON (s.nis = n.nis AND n.id_pengampu = ?)
                LEFT JOIN tb_presensi_sts pr ON s.nis = pr.nis
                WHERE s.id_kelas = ?
                ORDER BY s.nama ASC";
        $stmt = mysqli_prepare($this->conn, $sql);
        mysqli_stmt_bind_param($stmt, "ii", $idPengampu, $idKelas);
        mysqli_stmt_execute($stmt);
        $res = mysqli_stmt_get_result($stmt);

        $data = [];
        while ($row = mysqli_fetch_assoc($res)) {
            $data[] = $row;
        }
        return $data;
    }
}
