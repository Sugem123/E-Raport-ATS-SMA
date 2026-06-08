<?php

// Memanggil konfigurasi database
require_once "../config/Database.php";

// Memanggil model Teacher
require_once "../models/Teacher.php";

// Membuat objek database
$db = new Database();

// Membuka koneksi ke database
$conn = $db->connect();

// Membuat instance model Teacher
$teacher = new Teacher($conn);

/* =========================================================
   VALIDASI AKSES
   Mencegah akses langsung tanpa submit form
   ========================================================= */
if (empty($_POST['input_teacher_validate'])) {
    die("<script>alert('Akses tidak valid.');</script>");
}

// Mengambil action dari form
$action = $_POST['action'] ?? '';

// Variabel untuk menyimpan hasil proses
$result = null;

// Redirect default setelah proses
$redirect = "../teachers";

/* =========================================================
   ROUTING ACTION (CRUD TEACHER)
   ========================================================= */
switch ($action) {

    // =========================
    // CREATE (INPUT GURU)
    // =========================
    case 'input':

        $result = $teacher->create(
            $_POST['guru_id'],
            $_POST['nama_guru'],
            $_POST['mata_pelajaran'],
            $_POST['username'],
            $_POST['pass'],
            $_POST['role']
        );

        break;

    // =========================
    // UPDATE DATA GURU
    // =========================
    case 'update':

        $result = $teacher->update(
            (int)$_POST['id'],
            $_POST['username'],
            $_POST['nama_guru'],
            $_POST['mata_pelajaran']
        );

        break;

    // =========================
    // DELETE DATA GURU
    // =========================
    case 'delete':

        $result = $teacher->delete(
            (int)$_POST['id']
        );

        break;

    // =========================
    // JIKA ACTION TIDAK VALID
    // =========================
    default:

        $result = [
            'success' => false,
            'message' => 'Action tidak ditemukan'
        ];
}

/* =========================================================
   RESPONSE KE USER (ALERT + REDIRECT)
   ========================================================= */
echo "
<script>
alert('{$result['message']}');
window.location='$redirect';
</script>
";

?>