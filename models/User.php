<?php

class User
{
    private mysqli $conn;

    public function __construct(mysqli $conn)
    {
        $this->conn = $conn;
    }

    public function login(string $username, string $password, string $role): array
    {
        $stmt = mysqli_prepare($this->conn, "SELECT * FROM tb_user WHERE username = ?");
        mysqli_stmt_bind_param($stmt, "s", $username);
        mysqli_stmt_execute($stmt);
        $result = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));

        if (!$result || !password_verify($password, $result['password'])) {
            return ['success' => false];
        }

        // Cek kecocokan role:
        // Catatan: Guru yang ditugaskan sebagai wali kelas di tb_kelas diperbolehkan login sebagai 'walikelas'
        $userRole = $result['role'];
        if ($role !== $userRole) {
            if (!($role === 'walikelas' && ($userRole === 'guru' || $userRole === 'walikelas'))) {
                return ['success' => false];
            }
        }

        $userId = (int)$result['id_user'];
        $nama = $result['username'];
        $idRole = null;
        $idKelas = null;
        $namaKelas = null;

        if ($role === 'admin') {
            $q = mysqli_query($this->conn, "SELECT id_admin FROM tb_admin WHERE id_user = $userId");
            $r = mysqli_fetch_assoc($q);
            $idRole = $r['id_admin'] ?? 'ADMIN';
            $nama = 'Administrator';
        } elseif ($role === 'guru') {
            $q = mysqli_query($this->conn, "SELECT id_guru, nama_guru FROM tb_guru WHERE id_user = $userId");
            $r = mysqli_fetch_assoc($q);
            $idRole = $r['id_guru'] ?? null;
            $nama = $r['nama_guru'] ?? $result['username'];
        } elseif ($role === 'walikelas') {
            $q = mysqli_query($this->conn, "
                SELECT g.id_guru, g.nama_guru, k.id_kelas, k.nama_kelas
                FROM tb_guru g
                LEFT JOIN tb_kelas k ON g.id_guru = k.id_guru_walikelas
                WHERE g.id_user = $userId
                LIMIT 1
            ");
            $r = mysqli_fetch_assoc($q);
            $idRole = $r['id_guru'] ?? null;
            $nama = $r['nama_guru'] ?? $result['username'];
            $idKelas = isset($r['id_kelas']) ? (int)$r['id_kelas'] : null;
            $namaKelas = $r['nama_kelas'] ?? null;
        } elseif ($role === 'siswa') {
            $q = mysqli_query($this->conn, "
                SELECT s.nis, s.nama, s.id_kelas, k.nama_kelas
                FROM tb_siswa s
                INNER JOIN tb_kelas k ON s.id_kelas = k.id_kelas
                WHERE s.id_user = $userId
            ");
            $r = mysqli_fetch_assoc($q);
            $idRole = $r['nis'] ?? null;
            $nama = $r['nama'] ?? $result['username'];
            $idKelas = isset($r['id_kelas']) ? (int)$r['id_kelas'] : null;
            $namaKelas = $r['nama_kelas'] ?? null;
        }

        return [
            'success' => true,
            'data' => [
                'id_user' => $userId,
                'id_role' => $idRole,
                'username' => $result['username'],
                'role' => $role,
                'nama' => $nama,
                'id_kelas' => $idKelas,
                'nama_kelas' => $namaKelas
            ]
        ];
    }

    public function resetPassword(int $id): array
    {
        $default = password_hash("12345", PASSWORD_BCRYPT);
        $stmt = mysqli_prepare($this->conn, "UPDATE tb_user SET password = ? WHERE id_user = ?");
        mysqli_stmt_bind_param($stmt, "si", $default, $id);
        mysqli_stmt_execute($stmt);

        return ['success' => true, 'message' => 'Password berhasil direset ke default (12345)'];
    }

    public function changePassword(string $username, string $old, string $new, string $confirm): array
    {
        $stmt = mysqli_prepare($this->conn, "SELECT * FROM tb_user WHERE username = ?");
        mysqli_stmt_bind_param($stmt, "s", $username);
        mysqli_stmt_execute($stmt);
        $user = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));

        if (!$user || !password_verify($old, $user['password'])) {
            return ['success' => false, 'message' => 'Password lama tidak cocok'];
        }

        if ($new !== $confirm) {
            return ['success' => false, 'message' => 'Konfirmasi password baru tidak cocok'];
        }

        $hash = password_hash($new, PASSWORD_BCRYPT);
        $update = mysqli_prepare($this->conn, "UPDATE tb_user SET password = ? WHERE username = ?");
        mysqli_stmt_bind_param($update, "ss", $hash, $username);
        mysqli_stmt_execute($update);

        return ['success' => true, 'message' => 'Password berhasil diubah'];
    }
}
