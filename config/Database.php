<?php
// Membuat class Database untuk mengelola koneksi ke database
class Database
{
    private $host;
    private $user;
    private $pass;
    private $db;
    private $port;

    public $conn;

    public function __construct()
    {
        $this->host = getenv('DB_HOST') ?: "localhost";
        $this->user = getenv('DB_USER') ?: "root";
        $this->pass = getenv('DB_PASS') !== false ? getenv('DB_PASS') : "";
        $this->db   = getenv('DB_NAME') ?: "db_raport";
        $this->port = getenv('DB_PORT') ? (int)getenv('DB_PORT') : 3306;
    }

    // Method untuk membuat koneksi ke database
    public function connect()
    {
        // Membuat koneksi menggunakan fungsi mysqli_connect()
        $this->conn = mysqli_connect(
            $this->host,
            $this->user,
            $this->pass,
            $this->db,
            $this->port
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