<?php
// Membuat class Database untuk mengelola koneksi ke database
class Database
{
    // Menyimpan konfigurasi database
    private $host = "localhost";   // Nama host database
    private $user = "root";        // Username database
    private $pass = "";            // Password database
    private $db   = "db_raport";   // Nama database yang digunakan

    // Variabel untuk menyimpan objek koneksi
    public $conn;

    // Method untuk membuat koneksi ke database
    public function connect()
    {
        // Membuat koneksi menggunakan fungsi mysqli_connect()
        $this->conn = mysqli_connect(
            $this->host,
            $this->user,
            $this->pass,
            $this->db
        );

        // Mengecek apakah koneksi berhasil atau gagal
        if (!$this->conn) {
            // Menghentikan program dan menampilkan pesan jika koneksi gagal
            die("Gagal koneksi database: " . mysqli_connect_error());
        }

        // Mengembalikan objek koneksi agar dapat digunakan oleh class lain
        return $this->conn;
    }
}
?>