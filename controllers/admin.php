<?php

// Memanggil file konfigurasi database
require_once "../config/Database.php";

// Memanggil model Admin
require_once "../models/Admin.php";

// Membuat objek database
$db = new Database();

// Membuka koneksi ke database
$conn = $db->connect();

// Membuat objek model Admin dengan koneksi database
$admin = new Admin($conn);

// Mengambil action dari form POST
$action = $_POST['action'] ?? '';

// Menentukan proses berdasarkan action yang dikirim
switch ($action) {

    // Menambahkan data admin baru
    case 'input':

        $result = $admin->create(
            $_POST['admin_id'],
            $_POST['username'],
            $_POST['role'],
            $_POST['pass']
        );

        break;

    // Mengubah data admin
    case 'update':

        $result = $admin->update(
            $_POST['id'],
            $_POST['username']
        );

        break;

    // Menghapus data admin
    case 'delete':

        $result = $admin->delete(
            $_POST['id']
        );

        break;

    // Jika action tidak sesuai
    default:

        $result = [
            "status" => false,
            "message" => "Action tidak dikenali"
        ];
}

// Menampilkan pesan hasil proses dan redirect ke halaman admin
echo "
<script>
alert('{$result['message']}');
window.location='../admins';
</script>
";
?>