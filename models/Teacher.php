<?php

class Teacher
{
    private mysqli $conn;

    public function __construct(mysqli $conn)
    {
        $this->conn = $conn;
    }

    public function getAll(int $limit = 0, int $offset = 0, string $search = ''): array
    {
        $search = trim($search);
        $whereClause = "";
        if (!empty($search)) {
            $s = mysqli_real_escape_string($this->conn, $search);
            $whereClause = " WHERE (g.nama_guru LIKE '%$s%' OR g.id_guru LIKE '%$s%' OR u.username LIKE '%$s%')";
        }

        $sql = "SELECT u.id_user, u.username, g.id_guru, g.nama_guru
                FROM tb_user u
                INNER JOIN tb_guru g ON u.id_user = g.id_user
                $whereClause
                ORDER BY g.nama_guru ASC";

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

    public function countAll(string $search = ''): int
    {
        $search = trim($search);
        $whereClause = "";
        if (!empty($search)) {
            $s = mysqli_real_escape_string($this->conn, $search);
            $whereClause = " WHERE (g.nama_guru LIKE '%$s%' OR g.id_guru LIKE '%$s%' OR u.username LIKE '%$s%')";
        }

        $sql = "SELECT COUNT(*) AS c
                FROM tb_user u
                INNER JOIN tb_guru g ON u.id_user = g.id_user
                $whereClause";

        $res = mysqli_query($this->conn, $sql);
        return (int)(mysqli_fetch_assoc($res)['c'] ?? 0);
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
                  AND m.id_mapel != 'BDKB'
                  AND m.nama_mapel NOT LIKE '%Konseling%'
                  AND m.nama_mapel NOT LIKE '%Bimbingan%'
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
                       n.id_nilai, n.sumatif_1, n.sumatif_2, n.sumatif_3, n.sumatif_4,
                       n.nilai_sts,
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

    public function isBk(string $idGuru): bool
    {
        $idGuru = trim($idGuru);
        if (empty($idGuru)) {
            return false;
        }

        // Daftar NIP resmi Guru BK SMAN 1 Prambon
        $knownBkTeachers = [
            '199112092022211020', // WAHAYU PUJA UTAMA, S.Pd. (BK Kelas X)
            '198503222022212022', // FERY BEKTIYANI, S.Pd. (BK Kelas XI)
            '198605122019032006', // RAHMAWATI VIDA MEIKANTINA, S.Pd. (BK Kelas XII)
            '197209231998022003', // ENDANG RAHAYU NINGSIH, S.Pd. (BK Kelas XII)
        ];
        if (in_array($idGuru, $knownBkTeachers, true)) {
            return true;
        }

        // Cek apakah guru mengampu mapel BDKB atau BK/Konseling di tb_pengampu
        $safeId = mysqli_real_escape_string($this->conn, $idGuru);
        $q = mysqli_query($this->conn, "
            SELECT 1 FROM tb_pengampu p
            JOIN tb_mapel_referensi m ON p.id_mapel = m.id_mapel
            WHERE p.id_guru = '$safeId'
              AND (m.id_mapel = 'BDKB' OR m.nama_mapel LIKE '%Konseling%' OR m.nama_mapel LIKE '%BK%')
            LIMIT 1
        ");
        if ($q && mysqli_num_rows($q) > 0) {
            return true;
        }

        // Cek nama guru jika memuat gelar/indikator BK
        $qName = mysqli_query($this->conn, "
            SELECT 1 FROM tb_guru
            WHERE id_guru = '$safeId'
              AND (nama_guru LIKE '%BK%' OR nama_guru LIKE '%Bimbingan%' OR nama_guru LIKE '%Konseling%')
            LIMIT 1
        ");
        return ($qName && mysqli_num_rows($qName) > 0);
    }

    public function getBkClasses(string $idGuru): array
    {
        $safeId = mysqli_real_escape_string($this->conn, $idGuru);
        $q = mysqli_query($this->conn, "
            SELECT DISTINCT k.id_kelas, k.nama_kelas, k.tingkat
            FROM tb_pengampu p
            JOIN tb_kelas k ON p.id_kelas = k.id_kelas
            JOIN tb_mapel_referensi m ON p.id_mapel = m.id_mapel
            WHERE p.id_guru = '$safeId'
              AND (m.id_mapel = 'BDKB' OR m.nama_mapel LIKE '%Konseling%' OR m.nama_mapel LIKE '%BK%')
            ORDER BY k.tingkat ASC, k.nama_kelas ASC
        ");
        $classes = [];
        if ($q) {
            while ($r = mysqli_fetch_assoc($q)) {
                $classes[] = $r;
            }
        }

        // Fallback jika belum diatur secara spesifik di tb_pengampu
        if (empty($classes)) {
            $defaultTingkat = match ($idGuru) {
                '199112092022211020' => '10',
                '198503222022212022' => '11',
                '198605122019032006', '197209231998022003' => '12',
                default => null
            };

            if ($defaultTingkat !== null) {
                $qDef = mysqli_query($this->conn, "
                    SELECT id_kelas, nama_kelas, tingkat
                    FROM tb_kelas
                    WHERE tingkat = '$defaultTingkat'
                    ORDER BY nama_kelas ASC
                ");
                if ($qDef) {
                    while ($r = mysqli_fetch_assoc($qDef)) {
                        $classes[] = $r;
                    }
                }
            } else {
                // Guru BK umum: sediakan seluruh kelas
                $qAll = mysqli_query($this->conn, "
                    SELECT id_kelas, nama_kelas, tingkat
                    FROM tb_kelas
                    ORDER BY tingkat ASC, nama_kelas ASC
                ");
                if ($qAll) {
                    while ($r = mysqli_fetch_assoc($qAll)) {
                        $classes[] = $r;
                    }
                }
            }
        }

        return $classes;
    }
}
