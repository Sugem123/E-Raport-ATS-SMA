<?php

// Memanggil konfigurasi database
require_once "../config/Database.php";

// Memanggil model Grade
require_once "../models/Grade.php";

// Membuat objek database
$db = new Database();

// Membuka koneksi ke database
$conn = $db->connect();

// Membuat instance model Grade
$grade = new Grade($conn);

/* =========================================================
   VALIDASI AKSES
   Mencegah akses langsung tanpa submit form
   ========================================================= */
if (!isset($_POST['input_grade_validate'])) {
    die("Akses tidak valid");
}

// Mengambil action dari form
$action = $_POST['action'] ?? '';

/* =========================================================
   ROUTING CRUD NILAI
   ========================================================= */
switch ($action) {

    // =========================
    // CREATE NILAI
    // =========================
    case 'input':

        $result = $grade->create(
            $_POST['nis'],
            $_POST['id'],
            (float)$_POST['nilai-tugas'],
            (float)$_POST['nilai-uts'],
            (float)$_POST['nilai-uas']
        );

        // Redirect ke halaman rekap nilai
        $redirect = "../grade-recap";
        break;

    // =========================
    // UPDATE NILAI
    // =========================
    case 'update':

        $result = $grade->update(
            (int)$_POST['id'],
            (float)$_POST['nilai-tugas'],
            (float)$_POST['nilai-uts'],
            (float)$_POST['nilai-uas']
        );

        // Redirect ke halaman detail guru
        $redirect = "../teacher-detail?id=" . (int)$_POST['id_user'];

        break;

    // =========================
    // DELETE NILAI
    // =========================
    case 'delete':

        $result = $grade->delete(
            (int)$_POST['id']
        );

        // Redirect ke rekap nilai
        $redirect = "../grade-recap";
        break;

    // =========================
    // ACTION TIDAK VALID
    // =========================
    default:

        $result = [
            'success' => false,
            'message' => 'Action tidak ditemukan'
        ];

        $redirect = "../grade-recap";
}

/* =========================================================
   RESPONSE KE USER
   ========================================================= */
echo "
<script>
alert('{$result['message']}');
window.location='$redirect';
</script>
";
?>