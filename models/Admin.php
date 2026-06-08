<?php

// Class Admin digunakan untuk mengelola data admin
// seperti menambah, mengubah, dan menghapus akun admin.
class Admin
{
    // Menyimpan koneksi database
    private mysqli $conn;

    // Constructor untuk menerima koneksi database
    public function __construct($db)
    {
        $this->conn = $db;
    }

    // Method untuk menambahkan admin baru
    public function create($adminId, $username, $role, $password)
    {
        // Mengecek apakah username sudah digunakan
        // atau id_admin sudah terdaftar
        $check = mysqli_prepare(
            $this->conn,
            "SELECT * FROM tb_user
             WHERE username=?
             OR id_user=(
                SELECT id_user
                FROM tb_admin
                WHERE id_admin=?
             )"
        );

        mysqli_stmt_bind_param(
            $check,
            "ss",
            $username,
            $adminId
        );

        mysqli_stmt_execute($check);
        mysqli_stmt_store_result($check);

        // Jika username atau id admin sudah ada
        if (mysqli_stmt_num_rows($check) > 0) {
            return [
                "status" => false,
                "message" => "Username sudah terdaftar."
            ];
        }

        // Mengenkripsi password menggunakan bcrypt
        $hashed = password_hash(
            $password,
            PASSWORD_BCRYPT
        );

        // Menambahkan data user ke tabel tb_user
        $query = mysqli_prepare(
            $this->conn,
            "INSERT INTO tb_user(username,password,role)
             VALUES(?,?,?)"
        );

        mysqli_stmt_bind_param(
            $query,
            "sss",
            $username,
            $hashed,
            $role
        );

        // Jika gagal menambahkan user
        if (!mysqli_stmt_execute($query)) {
            return [
                "status" => false,
                "message" => "Gagal menambah user."
            ];
        }

        // Mengambil id_user yang baru saja dibuat
        $userId = mysqli_insert_id($this->conn);

        // Menambahkan data admin ke tabel tb_admin
        $adminQuery = mysqli_prepare(
            $this->conn,
            "INSERT INTO tb_admin(id_admin,id_user)
             VALUES(?,?)"
        );

        mysqli_stmt_bind_param(
            $adminQuery,
            "si",
            $adminId,
            $userId
        );

        mysqli_stmt_execute($adminQuery);

        return [
            "status" => true,
            "message" => "Berhasil menambahkan admin."
        ];
    }

    // Method untuk mengubah username admin
    public function update($id, $username)
    {
        // Memastikan username baru belum digunakan user lain
        $check = mysqli_prepare(
            $this->conn,
            "SELECT *
             FROM tb_user
             WHERE username=?
             AND id_user != ?"
        );

        mysqli_stmt_bind_param(
            $check,
            "si",
            $username,
            $id
        );

        mysqli_stmt_execute($check);
        mysqli_stmt_store_result($check);

        // Jika username sudah digunakan
        if (mysqli_stmt_num_rows($check) > 0) {
            return [
                "status" => false,
                "message" => "Username sudah terdaftar."
            ];
        }

        // Mengupdate username pada tabel user
        $query = mysqli_prepare(
            $this->conn,
            "UPDATE tb_user
             SET username=?
             WHERE id_user=?"
        );

        mysqli_stmt_bind_param(
            $query,
            "si",
            $username,
            $id
        );

        mysqli_stmt_execute($query);

        return [
            "status" => true,
            "message" => "Berhasil mengubah admin."
        ];
    }

    // Method untuk menghapus admin
    public function delete($id)
    {
        // Menghapus data user berdasarkan id_user
        // Jika menggunakan foreign key CASCADE,
        // data pada tb_admin akan ikut terhapus.
        $query = mysqli_prepare(
            $this->conn,
            "DELETE FROM tb_user
             WHERE id_user=?"
        );

        mysqli_stmt_bind_param(
            $query,
            "i",
            $id
        );

        mysqli_stmt_execute($query);

        return [
            "status" => true,
            "message" => "Berhasil menghapus admin."
        ];
    }

    public function getAll(): array
    {
        $query = mysqli_query(
            $this->conn,
            "SELECT *
             FROM tb_user
             INNER JOIN tb_admin
             ON tb_user.id_user = tb_admin.id_user
             ORDER BY tb_user.id_user ASC"
        );

        $data = [];

        while ($row = mysqli_fetch_assoc($query)) {
            $data[] = $row;
        }

        return $data;
    }
}