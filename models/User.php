<?php

// Class User digunakan untuk mengelola autentikasi pengguna
// seperti login, reset password, dan perubahan password.
class User
{
    // Menyimpan koneksi database
    private mysqli $conn;

    // Constructor untuk menerima koneksi database
    public function __construct($conn)
    {
        $this->conn = $conn;
    }

    // Method login untuk memverifikasi username dan password
    public function login($username, $password): array
    {
        // Menyiapkan query untuk mencari user berdasarkan username
        $stmt = mysqli_prepare(
            $this->conn,
            "SELECT * FROM tb_user WHERE username=?"
        );

        // Mengikat parameter username ke query
        mysqli_stmt_bind_param($stmt, "s", $username);
        mysqli_stmt_execute($stmt);

        // Mengambil data user
        $result = mysqli_fetch_assoc(
            mysqli_stmt_get_result($stmt)
        );

        // Jika user tidak ditemukan atau password salah
        if (!$result || !password_verify($password, $result['password'])) {
            return [
                'success' => false
            ];
        }

        // Menyimpan data dasar user
        $userId = $result['id_user'];
        $role = $result['role'];

        // Default nama menggunakan username
        $nama = $result['username'];

        // Variabel untuk menyimpan ID sesuai role
        $idRole = null;

        // Jika role admin
        if ($role === 'admin') {

            $q = mysqli_query(
                $this->conn,
                "SELECT id_admin FROM tb_admin WHERE id_user='$userId'"
            );

            $r = mysqli_fetch_assoc($q);

            // Simpan id_admin
            $idRole = $r['id_admin'];

        // Jika role guru
        } elseif ($role === 'guru') {

            $q = mysqli_query(
                $this->conn,
                "SELECT id_guru, nama_guru FROM tb_guru WHERE id_user='$userId'"
            );

            $r = mysqli_fetch_assoc($q);

            // Simpan id guru dan nama guru
            $idRole = $r['id_guru'];
            $nama = $r['nama_guru'];

        // Jika role siswa
        } elseif ($role === 'siswa') {

            $q = mysqli_query(
                $this->conn,
                "SELECT nis, nama FROM tb_siswa WHERE id_user='$userId'"
            );

            $r = mysqli_fetch_assoc($q);

            // Simpan NIS dan nama siswa
            $idRole = $r['nis'];
            $nama = $r['nama'];
        }

        // Mengembalikan data login yang berhasil
        return [
            'success' => true,
            'data' => [
                'id_user' => $userId,
                'id_role' => $idRole,
                'username' => $result['username'],
                'role' => $role,
                'nama' => $nama
            ]
        ];
    }

    // Method untuk mereset password user menjadi "12345"
    public function resetPassword($id): array
    {
        // Hash password default
        $default = password_hash("12345", PASSWORD_BCRYPT);

        // Update password berdasarkan id_user
        $stmt = mysqli_prepare(
            $this->conn,
            "UPDATE tb_user SET password=? WHERE id_user=?"
        );

        mysqli_stmt_bind_param($stmt, "si", $default, $id);
        mysqli_stmt_execute($stmt);

        return [
            'success' => true,
            'message' => 'Password berhasil direset'
        ];
    }

    // Method untuk mengganti password user
    public function changePassword($username, $old, $new, $confirm): array
    {
        // Ambil data user berdasarkan username
        $stmt = mysqli_prepare(
            $this->conn,
            "SELECT * FROM tb_user WHERE username=?"
        );

        mysqli_stmt_bind_param($stmt, "s", $username);
        mysqli_stmt_execute($stmt);

        $user = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));

        // Validasi password lama
        if (!$user || !password_verify($old, $user['password'])) {
            return [
                'success' => false,
                'message' => 'Password lama salah'
            ];
        }

        // Validasi konfirmasi password baru
        if ($new !== $confirm) {
            return [
                'success' => false,
                'message' => 'Konfirmasi password tidak cocok'
            ];
        }

        // Hash password baru
        $hash = password_hash($new, PASSWORD_BCRYPT);

        // Update password baru ke database
        $update = mysqli_prepare(
            $this->conn,
            "UPDATE tb_user SET password=? WHERE username=?"
        );

        mysqli_stmt_bind_param($update, "ss", $hash, $username);
        mysqli_stmt_execute($update);

        return [
            'success' => true,
            'message' => 'Password berhasil diubah'
        ];
    }
}

?>