<?php

// Class Student digunakan untuk mengelola data siswa
// meliputi tambah, ubah, dan hapus data siswa.
class Student
{
    // Menyimpan koneksi database
    private mysqli $conn;

    // Constructor menerima objek koneksi database
    public function __construct(mysqli $conn)
    {
        $this->conn = $conn;
    }

    // Method untuk menambahkan data siswa baru
    public function create(
        string $nis,
        string $nama,
        string $kelas,
        string $username,
        string $password,
        string $role = 'student'
    ): array {

        // Mengecek apakah username sudah digunakan
        // atau NIS sudah terdaftar
        $check = mysqli_prepare(
            $this->conn,
            "SELECT *
             FROM tb_user
             WHERE username=?
             OR id_user=(
                 SELECT id_user
                 FROM tb_siswa
                 WHERE nis=?
             )"
        );

        mysqli_stmt_bind_param(
            $check,
            "ss",
            $username,
            $nis
        );

        mysqli_stmt_execute($check);
        mysqli_stmt_store_result($check);

        // Jika username atau NIS sudah ada
        if (mysqli_stmt_num_rows($check) > 0) {
            return [
                'success' => false,
                'message' => 'Username sudah terdaftar'
            ];
        }

        // Memulai transaction
        mysqli_begin_transaction($this->conn);

        try {

            // Mengenkripsi password menggunakan bcrypt
            $hashedPassword = password_hash(
                $password,
                PASSWORD_BCRYPT
            );

            // Menambahkan akun ke tabel tb_user
            $userStmt = mysqli_prepare(
                $this->conn,
                "INSERT INTO tb_user
                (
                    username,
                    password,
                    role
                )
                VALUES(?,?,?)"
            );

            mysqli_stmt_bind_param(
                $userStmt,
                "sss",
                $username,
                $hashedPassword,
                $role
            );

            mysqli_stmt_execute($userStmt);

            // Mengambil id_user yang baru dibuat
            $userId = mysqli_insert_id($this->conn);

            // Menambahkan data siswa ke tabel tb_siswa
            $studentStmt = mysqli_prepare(
                $this->conn,
                "INSERT INTO tb_siswa
                (
                    nis,
                    id_user,
                    nama,
                    kelas
                )
                VALUES(?,?,?,?)"
            );

            mysqli_stmt_bind_param(
                $studentStmt,
                "siss",
                $nis,
                $userId,
                $nama,
                $kelas
            );

            mysqli_stmt_execute($studentStmt);

            // Menyimpan seluruh perubahan
            mysqli_commit($this->conn);

            return [
                'success' => true,
                'message' => 'Berhasil menambah siswa'
            ];

        } catch (Exception $e) {

            // Membatalkan seluruh perubahan jika terjadi error
            mysqli_rollback($this->conn);

            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
    }

    // Method untuk mengubah data siswa
    public function update(
        int $idUser,
        string $username,
        string $nama,
        string $kelas
    ): array {

        // Mengecek apakah username baru sudah digunakan user lain
        $check = mysqli_prepare(
            $this->conn,
            "SELECT *
             FROM tb_user
             WHERE username=?
             AND id_user!=?"
        );

        mysqli_stmt_bind_param(
            $check,
            "si",
            $username,
            $idUser
        );

        mysqli_stmt_execute($check);
        mysqli_stmt_store_result($check);

        // Jika username sudah digunakan
        if (mysqli_stmt_num_rows($check) > 0) {
            return [
                'success' => false,
                'message' => 'Username sudah terdaftar'
            ];
        }

        // Memulai transaction
        mysqli_begin_transaction($this->conn);

        try {

            // Mengubah username pada tabel user
            $userStmt = mysqli_prepare(
                $this->conn,
                "UPDATE tb_user
                 SET username=?
                 WHERE id_user=?"
            );

            mysqli_stmt_bind_param(
                $userStmt,
                "si",
                $username,
                $idUser
            );

            mysqli_stmt_execute($userStmt);

            // Mengubah data siswa pada tabel siswa
            $studentStmt = mysqli_prepare(
                $this->conn,
                "UPDATE tb_siswa
                 SET nama=?,
                     kelas=?
                 WHERE id_user=?"
            );

            mysqli_stmt_bind_param(
                $studentStmt,
                "ssi",
                $nama,
                $kelas,
                $idUser
            );

            mysqli_stmt_execute($studentStmt);

            // Menyimpan perubahan
            mysqli_commit($this->conn);

            return [
                'success' => true,
                'message' => 'Berhasil mengubah siswa'
            ];

        } catch (Exception $e) {

            // Membatalkan perubahan jika terjadi error
            mysqli_rollback($this->conn);

            return [
                'success' => false,
                'message' => 'Gagal mengubah siswa'
            ];
        }
    }

    // Method untuk menghapus data siswa
    public function delete(
        int $idUser
    ): array {

        // Menghapus data user berdasarkan id_user
        // Jika relasi foreign key menggunakan CASCADE,
        // data siswa akan ikut terhapus otomatis.
        $stmt = mysqli_prepare(
            $this->conn,
            "DELETE FROM tb_user
             WHERE id_user=?"
        );

        mysqli_stmt_bind_param(
            $stmt,
            "i",
            $idUser
        );

        mysqli_stmt_execute($stmt);

        return [
            'success' => true,
            'message' => 'Berhasil menghapus siswa'
        ];
    }

    public function getAll(): array
    {
        $query = mysqli_query(
            $this->conn,
            "SELECT * 
            FROM tb_user 
            INNER JOIN tb_siswa ON tb_user.id_user = tb_siswa.id_user
            ORDER BY tb_user.id_user ASC"
        );

        $data = [];

        while ($row = mysqli_fetch_assoc($query)) {
            $data[] = $row;
        }

        return $data;
    }

    public function getById(
        string $idSiswa
    ): ?array {
        // Mendapatkan data siswa berdasarkan id siswa
        // untuk halaman rangkuman nilai siswa
        $stmt = mysqli_prepare(
            $this->conn,
            "SELECT *
            FROM tb_siswa s
            INNER JOIN tb_user u ON u.id_user = s.id_user
            WHERE s.nis = ?"
        );

        mysqli_stmt_bind_param(
            $stmt,
            "s",
            $idSiswa
        );

        mysqli_stmt_execute($stmt);

        return mysqli_fetch_assoc(
            mysqli_stmt_get_result($stmt)
        );
    }

    public function getGrades(
        string $idGuru
    ): array {
        // Mendapatkan daftar nilai siswa berdasarkan id siswa
        $stmt = mysqli_prepare(
            $this->conn,
            "SELECT *
            FROM tb_nilai n
            INNER JOIN tb_siswa s ON s.nis = n.nis
            INNER JOIN tb_guru g ON g.id_guru = n.id_guru_matpel
            WHERE n.nis = ?"
        );

        mysqli_stmt_bind_param(
            $stmt,
            "s",
            $idGuru
        );

        mysqli_stmt_execute($stmt);

        $result = mysqli_stmt_get_result($stmt);

        $data = [];

        while ($row = mysqli_fetch_assoc($result)) {
            $data[] = $row;
        }

        return $data;
    }
}
?>