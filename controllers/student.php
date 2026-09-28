<?php
session_start();
if (empty($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
    die('Akses tidak valid.');
}

require_once __DIR__ . '/../config/Database.php';
require_once __DIR__ . '/../models/Student.php';

$db = new Database();
$conn = $db->connect();
$student = new Student($conn);

$action = $_POST['action'] ?? '';
$result = ['success' => false, 'message' => 'Action tidak ditemukan'];

switch ($action) {
    case 'input':
        $result = $student->create(
            $_POST['nis'] ?? '',
            $_POST['nama'] ?? '',
            (int)($_POST['id_kelas'] ?? 0),
            $_POST['username'] ?? '',
            $_POST['pass'] ?? '12345',
            'siswa',
            $_POST['nisn'] ?? null
        );
        break;

    case 'update':
        $result = $student->update(
            (int)($_POST['id_user'] ?? 0),
            $_POST['username'] ?? '',
            $_POST['nama'] ?? '',
            (int)($_POST['id_kelas'] ?? 0),
            $_POST['nisn'] ?? null
        );
        break;

    case 'delete':
        $result = $student->delete((int)($_POST['id_user'] ?? 0));
        break;

    case 'upload_excel':
        if (isset($_FILES['excel_file']) && $_FILES['excel_file']['error'] === UPLOAD_ERR_OK) {
            $result = $student->importExcel($_FILES['excel_file']['tmp_name'], $_FILES['excel_file']['name']);
        } else {
            $result = ['success' => false, 'message' => 'Gagal mengupload file Excel.'];
        }
        break;
}

$page = (int)($_POST['page'] ?? 1);
$perPage = (int)($_POST['per_page'] ?? 25);
if (!in_array($perPage, [25, 50, 100], true)) {
    $perPage = 25;
}
if ($page < 1) {
    $page = 1;
}

$redirect = "../students?page={$page}&per_page={$perPage}";

echo "<script>
alert('" . addslashes($result['message']) . "');
window.location='{$redirect}';
</script>";
