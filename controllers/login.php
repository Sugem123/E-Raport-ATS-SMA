<?php

// Memulai session untuk menyimpan data login pengguna
session_start();

// Memanggil file koneksi database dan model User
require_once "../config/Database.php";
require_once "../models/User.php";

// Membuat objek database
$db = new Database();

// Membuka koneksi ke database
$conn = $db->connect();

// Membuat objek model User
$userModel = new User($conn);

// Memastikan request berasal dari form login yang valid
if (empty($_POST['submit_validate'])) {
    die("<script>alert('Akses tidak valid');</script>");
}

// Mengambil data username dan password dari form
$username = trim($_POST['username'] ?? '');
$password = $_POST['pass'] ?? '';
$role = $_POST['role'] ?? '';

// Memproses login menggunakan method login() pada model User
$result = $userModel->login($username, $password, $role);

// Jika login gagal
if (!$result['success']) {

    echo "<script>
        alert('Username, password, atau role salah.');
        window.location='../login';
    </script>";

    exit;
}

// Mengambil data user hasil login
$user = $result['data'];

/* =========================
   Menyimpan session umum
   ========================= */

// Username pengguna
$_SESSION["username"] = $user['username'];

// Role pengguna (admin, guru, siswa)
$_SESSION["role"] = $user['role'];

/* =========================
   Menyimpan session berdasarkan role
   ========================= */

switch ($user['role']) {

    // Jika login sebagai admin
    case 'admin':

        // Menyimpan ID admin
        $_SESSION["id"] = $user['id_role'];

        // Menyimpan nama admin
        $_SESSION["nama"] = $user['nama'];

        break;

    // Jika login sebagai guru
    case 'guru':

        // Menyimpan ID guru
        $_SESSION["id"] = $user['id_role'];

        // Menyimpan nama guru
        $_SESSION["nama"] = $user['nama'];

        break;

    // Jika login sebagai siswa
    case 'siswa':

        // Menyimpan NIS siswa
        $_SESSION["id"] = $user['id_role'];

        // Menyimpan nama siswa
        $_SESSION["nama"] = $user['nama'];

        break;
}

/* =========================
   Redirect ke halaman home
   ========================= */

header("Location: ../home");
exit;
?>