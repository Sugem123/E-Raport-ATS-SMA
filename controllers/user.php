<?php

// Memulai session untuk mengakses data pengguna yang login
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

// Memastikan request berasal dari form yang valid
if (empty($_POST['input_user_validate'])) {
    die("<script>alert('Akses tidak valid');</script>");
}

// Mengambil nilai action dari form
$action = $_POST['action'] ?? '';

// Menentukan proses berdasarkan action
switch ($action) {

    // Reset password pengguna
    case 'reset_password':

        // Mengambil id user yang akan direset passwordnya
        $id = (int)$_POST['id'];

        // Memanggil method resetPassword()
        $result = $userModel->resetPassword($id);

        break;

    // Mengubah password pengguna yang sedang login
    case 'change_password':

        $result = $userModel->changePassword(

            // Username dari session login
            $_SESSION['username'],

            // Password lama
            $_POST['oldpass'],

            // Password baru
            $_POST['newpass'],

            // Konfirmasi password baru
            $_POST['confirmnewpass']
        );

        break;

    // Jika action tidak dikenali
    default:

        $result = [
            'success' => false,
            'message' => 'Action tidak ditemukan'
        ];
}

// Menampilkan pesan hasil proses
// kemudian mengarahkan kembali ke halaman home
echo "<script>
alert('{$result['message']}');
window.location='../home';
</script>";

?>