<?php

// Memanggil konfigurasi database
require_once "../config/Database.php";

// Memanggil model Student
require_once "../models/Student.php";

// Membuat objek Database
$db = new Database();

// Membuka koneksi ke database
$conn = $db->connect();

// Membuat instance model Student
$student = new Student($conn);

/* =========================================================
   VALIDASI AKSES
   Mencegah akses langsung tanpa submit form
   ========================================================= */
if (!isset($_POST['input_student_validate'])) {
    die("Akses tidak valid");
}

// Mengambil action dari form
$action = $_POST['action'] ?? '';

/* =========================================================
   ROUTING CRUD STUDENT
   ========================================================= */
switch ($action) {

    // =========================
    // CREATE (INPUT SISWA)
    // =========================
    case 'input':

        $result = $student->create(
            $_POST['nis'],
            $_POST['nama'],
            $_POST['kelas'],
            $_POST['username'],
            $_POST['pass'],
            $_POST['role']
        );

        break;

    // =========================
    // UPDATE SISWA
    // =========================
    case 'update':

        $result = $student->update(
            (int)$_POST['id'],
            $_POST['username'],
            $_POST['nama'],
            $_POST['kelas']
        );

        break;

    // =========================
    // DELETE SISWA
    // =========================
    case 'delete':

        $result = $student->delete(
            (int)$_POST['id']
        );

        break;

    // =========================
    // ACTION TIDAK DIKENALI
    // =========================
    default:

        $result = [
            'success' => false,
            'message' => 'Action tidak ditemukan'
        ];
}

/* =========================================================
   RESPONSE KE USER
   ========================================================= */
echo "
<script>
alert('{$result['message']}');
window.location='../students';
</script>
";
?>