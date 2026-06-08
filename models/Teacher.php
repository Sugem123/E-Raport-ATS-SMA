<?php

// Class Teacher digunakan untuk mengelola data guru
// meliputi tambah, ubah, dan hapus data guru.
class Teacher
{
    // Menyimpan koneksi database
    private mysqli $conn;

    // Constructor menerima objek koneksi database
    public function __construct(mysqli $conn)
    {
        $this->conn = $conn;
    }

    // Method untuk menambahkan data guru baru
    public function create(
        string $guruId,
        string $namaGuru,
        string $mataPelajaran,
        string $username,
        string $password,
        string $role
    ): array {

        // Mengecek apakah username sudah digunakan
        // atau id_guru sudah terdaftar
        $check = mysqli_prepare(
            $this->conn,
            "SELECT *
             FROM tb_user
             WHERE username=?
             OR id_user=(
                 SELECT id_user
                 FROM tb_guru
                 WHERE id_guru=?
             )"
        );

        mysqli_stmt_bind_param(
            $check,
            "ss",
            $username,
            $guruId
        );

        mysqli_stmt_execute($check);
        mysqli_stmt_store_result($check);

        // Jika username atau id guru sudah ada
        if (mysqli_stmt_num_rows($check) > 0) {
            return [
                'success' => false,
                'message' => 'Username sudah terdaftar'
            ];
        }

        // Memulai transaction agar proses insert
        // pada dua tabel berjalan secara atomik
        mysqli_begin_transaction($this->conn);

        try {

            // Mengenkripsi password menggunakan bcrypt
            $hashed = password_hash(
                $password,
                PASSWORD_BCRYPT
            );

            // Menambahkan data ke tabel user
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
                $hashed,
                $role
            );

            mysqli_stmt_execute($userStmt);

            // Mengambil id_user yang baru dibuat
            $userId = mysqli_insert_id($this->conn);

            // Menambahkan data guru ke tabel tb_guru
            $teacherStmt = mysqli_prepare(
                $this->conn,
                "INSERT INTO tb_guru
                (
                    id_guru,
                    id_user,
                    nama_guru,
                    mata_pelajaran
                )
                VALUES(?,?,?,?)"
            );

            mysqli_stmt_bind_param(
                $teacherStmt,
                "siss",
                $guruId,
                $userId,
                $namaGuru,
                $mataPelajaran
            );

            mysqli_stmt_execute($teacherStmt);

            // Menyimpan seluruh perubahan
            mysqli_commit($this->conn);

            return [
                'success' => true,
                'message' => 'Guru berhasil ditambahkan'
            ];

        } catch (Exception $e) {

            // Membatalkan seluruh perubahan jika terjadi error
            mysqli_rollback($this->conn);

            return [
                'success' => false,
                'message' => 'Gagal menambah guru'
            ];
        }
    }

    // Method untuk mengubah data guru
    public function update(
        int $idUser,
        string $username,
        string $namaGuru,
        string $mataPelajaran
    ): array {

        // Memastikan username baru belum digunakan user lain
        $check = mysqli_prepare(
            $this->conn,
            "SELECT id_user
             FROM tb_user
             WHERE username=?
             AND id_user != ?"
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
                'message' => 'Username sudah digunakan'
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

            // Mengubah data guru pada tabel tb_guru
            $guruStmt = mysqli_prepare(
                $this->conn,
                "UPDATE tb_guru
                 SET nama_guru=?,
                     mata_pelajaran=?
                 WHERE id_user=?"
            );

            mysqli_stmt_bind_param(
                $guruStmt,
                "ssi",
                $namaGuru,
                $mataPelajaran,
                $idUser
            );

            mysqli_stmt_execute($guruStmt);

            // Menyimpan perubahan
            mysqli_commit($this->conn);

            return [
                'success' => true,
                'message' => 'Guru berhasil diupdate'
            ];

        } catch (Throwable $e) {

            // Membatalkan perubahan jika terjadi error
            mysqli_rollback($this->conn);

            return [
                'success' => false,
                'message' => 'Gagal update guru'
            ];
        }
    }

    // Method untuk menghapus data guru
    public function delete(
        int $idUser
    ): array {

        // Menghapus data user berdasarkan id_user
        // Dengan asumsi relasi foreign key menggunakan CASCADE
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
            'message' => 'Guru berhasil dihapus'
        ];
    }

    public function getAll(): array
    {
        $query = mysqli_query(
            $this->conn,
            "SELECT * 
            FROM tb_user 
            INNER JOIN tb_guru ON tb_user.id_user = tb_guru.id_user
            ORDER BY tb_user.id_user ASC"
        );

        $data = [];

        while ($row = mysqli_fetch_assoc($query)) {
            $data[] = $row;
        }

        return $data;
    }

    public function getById(
        string $idGuru
    ): ?array {
        // Mendapatkan data guru berdasarkan id guru
        // untuk halaman rekap yang diakses guru
        $stmt = mysqli_prepare(
            $this->conn,
            "SELECT *
            FROM tb_guru 
            INNER JOIN tb_user 
            ON tb_guru.id_user = tb_user.id_user
            WHERE tb_guru.id_guru = ?"
        );

        mysqli_stmt_bind_param(
            $stmt,
            "s",
            $idGuru
        );

        mysqli_stmt_execute($stmt);

        return mysqli_fetch_assoc(
            mysqli_stmt_get_result($stmt)
        );
    }

    public function getGrades(
        string $idGuru
    ): array {
        // Mendapatkan daftar nilai siswa berdasarkan id guru
        $stmt = mysqli_prepare(
            $this->conn,
            "SELECT *
            FROM tb_nilai n
            INNER JOIN tb_siswa s 
            ON s.nis = n.nis
            WHERE n.id_guru_matpel = ?"
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