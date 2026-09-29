<?php

class Student
{
    private mysqli $conn;

    public function __construct(mysqli $conn)
    {
        $this->conn = $conn;
    }

    public function getAll(int $limit = 0, int $offset = 0): array
    {
        $sql = "SELECT u.id_user, u.username, s.nis, s.nisn, s.nama, s.id_kelas, k.nama_kelas, k.tingkat
                FROM tb_user u
                INNER JOIN tb_siswa s ON u.id_user = s.id_user
                INNER JOIN tb_kelas k ON s.id_kelas = k.id_kelas
                ORDER BY k.tingkat ASC, k.nama_kelas ASC, s.nama ASC";
        if ($limit > 0) {
            $sql .= sprintf(" LIMIT %d OFFSET %d", $limit, max(0, $offset));
        }
        $query = mysqli_query($this->conn, $sql);
        $data = [];
        while ($row = mysqli_fetch_assoc($query)) {
            $data[] = $row;
        }
        return $data;
    }

    public function countAll(): int
    {
        $res = mysqli_query($this->conn, "SELECT COUNT(*) AS total FROM tb_siswa");
        $row = mysqli_fetch_assoc($res);
        return (int)($row['total'] ?? 0);
    }

    public function getById(string $nis): ?array
    {
        $stmt = mysqli_prepare(
            $this->conn,
            "SELECT u.id_user, u.username, s.nis, s.nisn, s.nama, s.id_kelas, k.nama_kelas, k.tingkat
             FROM tb_siswa s
             INNER JOIN tb_user u ON s.id_user = u.id_user
             INNER JOIN tb_kelas k ON s.id_kelas = k.id_kelas
             WHERE s.nis = ?"
        );
        mysqli_stmt_bind_param($stmt, "s", $nis);
        mysqli_stmt_execute($stmt);
        $res = mysqli_stmt_get_result($stmt);
        return mysqli_fetch_assoc($res);
    }

    public function create(
        string $nis,
        string $nama,
        int $idKelas,
        string $username,
        string $password = '12345',
        string $role = 'siswa',
        ?string $nisn = null
    ): array {
        $nis = trim($nis);
        $nama = trim($nama);
        $username = trim($username);
        $nisn = $nisn !== null ? trim($nisn) : null;
        if ($nisn === '') {
            $nisn = null;
        }

        $check = mysqli_prepare(
            $this->conn,
            "SELECT id_user FROM tb_user WHERE username = ?
             UNION
             SELECT id_user FROM tb_siswa WHERE nis = ?"
        );
        mysqli_stmt_bind_param($check, "ss", $username, $nis);
        mysqli_stmt_execute($check);
        mysqli_stmt_store_result($check);

        if (mysqli_stmt_num_rows($check) > 0) {
            return [
                'success' => false,
                'message' => "Username '$username' atau NIS '$nis' sudah terdaftar."
            ];
        }

        if ($nisn !== null) {
            $checkNisn = mysqli_prepare($this->conn, "SELECT nis FROM tb_siswa WHERE nisn = ?");
            mysqli_stmt_bind_param($checkNisn, "s", $nisn);
            mysqli_stmt_execute($checkNisn);
            mysqli_stmt_store_result($checkNisn);
            if (mysqli_stmt_num_rows($checkNisn) > 0) {
                return [
                    'success' => false,
                    'message' => "NISN '$nisn' sudah terdaftar pada siswa lain."
                ];
            }
        }

        mysqli_begin_transaction($this->conn);
        try {
            $hashed = password_hash($password, PASSWORD_BCRYPT);
            $userStmt = mysqli_prepare($this->conn, "INSERT INTO tb_user (username, password, role) VALUES (?, ?, ?)");
            mysqli_stmt_bind_param($userStmt, "sss", $username, $hashed, $role);
            mysqli_stmt_execute($userStmt);
            $userId = mysqli_insert_id($this->conn);

            $siswaStmt = mysqli_prepare($this->conn, "INSERT INTO tb_siswa (nis, id_user, nama, id_kelas, nisn) VALUES (?, ?, ?, ?, ?)");
            mysqli_stmt_bind_param($siswaStmt, "sisis", $nis, $userId, $nama, $idKelas, $nisn);
            mysqli_stmt_execute($siswaStmt);

            // Inisialisasi presensi 0
            $presensiStmt = mysqli_prepare($this->conn, "INSERT INTO tb_presensi_sts (nis, sakit, izin, alpa) VALUES (?, 0, 0, 0)");
            mysqli_stmt_bind_param($presensiStmt, "s", $nis);
            mysqli_stmt_execute($presensiStmt);

            mysqli_commit($this->conn);
            return ['success' => true, 'message' => 'Siswa berhasil ditambahkan.'];
        } catch (Throwable $e) {
            mysqli_rollback($this->conn);
            return ['success' => false, 'message' => 'Gagal menambah siswa: ' . $e->getMessage()];
        }
    }

    public function update(int $idUser, string $username, string $nama, int $idKelas, ?string $nisn = null): array
    {
        $username = trim($username);
        $nama = trim($nama);
        $nisn = $nisn !== null ? trim($nisn) : null;
        if ($nisn === '') {
            $nisn = null;
        }

        $check = mysqli_prepare($this->conn, "SELECT id_user FROM tb_user WHERE username = ? AND id_user != ?");
        mysqli_stmt_bind_param($check, "si", $username, $idUser);
        mysqli_stmt_execute($check);
        mysqli_stmt_store_result($check);

        if (mysqli_stmt_num_rows($check) > 0) {
            return ['success' => false, 'message' => 'Username sudah digunakan user lain.'];
        }

        if ($nisn !== null) {
            $checkNisn = mysqli_prepare($this->conn, "SELECT nis FROM tb_siswa WHERE nisn = ? AND id_user != ?");
            mysqli_stmt_bind_param($checkNisn, "si", $nisn, $idUser);
            mysqli_stmt_execute($checkNisn);
            mysqli_stmt_store_result($checkNisn);
            if (mysqli_stmt_num_rows($checkNisn) > 0) {
                return ['success' => false, 'message' => "NISN '$nisn' sudah digunakan siswa lain."];
            }
        }

        mysqli_begin_transaction($this->conn);
        try {
            $userStmt = mysqli_prepare($this->conn, "UPDATE tb_user SET username = ? WHERE id_user = ?");
            mysqli_stmt_bind_param($userStmt, "si", $username, $idUser);
            mysqli_stmt_execute($userStmt);

            $siswaStmt = mysqli_prepare($this->conn, "UPDATE tb_siswa SET nama = ?, id_kelas = ?, nisn = ? WHERE id_user = ?");
            mysqli_stmt_bind_param($siswaStmt, "sisi", $nama, $idKelas, $nisn, $idUser);
            mysqli_stmt_execute($siswaStmt);

            mysqli_commit($this->conn);
            return ['success' => true, 'message' => 'Data siswa berhasil diupdate.'];
        } catch (Throwable $e) {
            mysqli_rollback($this->conn);
            return ['success' => false, 'message' => 'Gagal update siswa: ' . $e->getMessage()];
        }
    }

    public function delete(int $idUser): array
    {
        $stmt = mysqli_prepare($this->conn, "DELETE FROM tb_user WHERE id_user = ?");
        mysqli_stmt_bind_param($stmt, "i", $idUser);

        if (mysqli_stmt_execute($stmt)) {
            return ['success' => true, 'message' => 'Data siswa berhasil dihapus.'];
        }
        return ['success' => false, 'message' => 'Gagal menghapus siswa.'];
    }

    public function importExcel(string $filePath, string $originalFileName): array
    {
        require_once __DIR__ . '/../config/ExcelHelper.php';
        $rows = ExcelHelper::parse($filePath, $originalFileName);

        if (empty($rows)) {
            return ['success' => false, 'message' => 'File Excel kosong atau format tidak sesuai.'];
        }

        // Cache kelas: nama_kelas => id_kelas
        $qKelas = mysqli_query($this->conn, "SELECT id_kelas, nama_kelas FROM tb_kelas");
        $mapKelas = [];
        while ($k = mysqli_fetch_assoc($qKelas)) {
            $mapKelas[strtolower(trim($k['nama_kelas']))] = (int)$k['id_kelas'];
        }

        $inserted = 0;
        $skipped = 0;

        foreach ($rows as $row) {
            $nis = $row['nis'] ?? '';
            $nisn = $row['nisn'] ?? null;
            $nama = $row['nama'] ?? '';
            $namaKelas = strtolower(trim($row['nama_kelas'] ?? ''));
            $username = $row['username'] ?? '';

            if (empty($nis) || empty($nama) || empty($username) || !isset($mapKelas[$namaKelas])) {
                $skipped++;
                continue;
            }

            $idKelas = $mapKelas[$namaKelas];
            $res = $this->create($nis, $nama, $idKelas, $username, '12345', 'siswa', $nisn);
            if ($res['success']) {
                $inserted++;
            } else {
                $skipped++;
            }
        }

        return [
            'success' => true,
            'message' => "Upload selesai. $inserted siswa berhasil ditambahkan, $skipped dilewati/sudah ada/kelas tidak cocok."
        ];
    }

    public function getReportSts(string $nis): array
    {
        // 1. Data Siswa
        $student = $this->getById($nis);
        if (!$student) {
            return ['student' => null, 'grades' => [], 'presensi' => ['sakit' => 0, 'izin' => 0, 'alpa' => 0]];
        }

        $idKelas = (int)$student['id_kelas'];
        $tingkat = (string)$student['tingkat'];

        // 2. Daftar nilai setiap mapel di kelas tersebut.
        //    Urutan & kategori diambil dari tb_mapel_mapping berdasarkan jenjang
        //    kelas siswa, bukan dari tabel referensi.
        $sql = "SELECT m.id_mapel, m.nama_mapel,
                       COALESCE(mp.kategori, 'Umum') AS kategori,
                       COALESCE(mp.urutan, 999) AS urutan,
                       g.nama_guru,
                       n.sumatif_1, n.sumatif_2, n.sumatif_3, n.sumatif_4,
                       n.nilai_sts
                FROM tb_pengampu p
                INNER JOIN tb_mapel_referensi m ON p.id_mapel = m.id_mapel
                INNER JOIN tb_guru g ON p.id_guru = g.id_guru
                LEFT JOIN tb_mapel_mapping mp ON (mp.id_mapel = m.id_mapel AND mp.jenjang = ?)
                LEFT JOIN tb_nilai_sts n ON (p.id_pengampu = n.id_pengampu AND n.nis = ?)
                WHERE p.id_kelas = ?
                ORDER BY
                    CASE WHEN COALESCE(mp.kategori, 'Umum') = 'Umum' THEN 1 ELSE 2 END ASC,
                    COALESCE(mp.urutan, 999) ASC,
                    m.nama_mapel ASC";
        $stmt = mysqli_prepare($this->conn, $sql);
        mysqli_stmt_bind_param($stmt, "ssi", $tingkat, $nis, $idKelas);
        mysqli_stmt_execute($stmt);
        $res = mysqli_stmt_get_result($stmt);

        $grades = [];
        while ($row = mysqli_fetch_assoc($res)) {
            $grades[] = $row;
        }

        // 3. Presensi
        $presStmt = mysqli_prepare($this->conn, "SELECT sakit, izin, alpa FROM tb_presensi_sts WHERE nis = ?");
        mysqli_stmt_bind_param($presStmt, "s", $nis);
        mysqli_stmt_execute($presStmt);
        $presensi = mysqli_fetch_assoc(mysqli_stmt_get_result($presStmt));
        if (!$presensi) {
            $presensi = ['sakit' => 0, 'izin' => 0, 'alpa' => 0];
        }

        return [
            'student' => $student,
            'grades' => $grades,
            'presensi' => $presensi
        ];
    }
}
