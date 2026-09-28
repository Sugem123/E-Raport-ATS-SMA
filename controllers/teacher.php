<?php
session_start();
if (empty($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
    die('Akses tidak valid.');
}

require_once __DIR__ . '/../config/Database.php';
require_once __DIR__ . '/../models/Teacher.php';

$db = new Database();
$conn = $db->connect();
$teacher = new Teacher($conn);

$action = $_POST['action'] ?? '';
$result = ['success' => false, 'message' => 'Action tidak ditemukan'];

switch ($action) {
    case 'input':
        $result = $teacher->create(
            $_POST['id_guru'] ?? '',
            $_POST['nama_guru'] ?? '',
            $_POST['username'] ?? '',
            $_POST['pass'] ?? '12345',
            'guru'
        );
        break;

    case 'update':
        $result = $teacher->update(
            (int)($_POST['id_user'] ?? 0),
            $_POST['username'] ?? '',
            $_POST['nama_guru'] ?? ''
        );
        break;

    case 'delete':
        $result = $teacher->delete((int)($_POST['id_user'] ?? 0));
        break;

    case 'upload_excel':
        if (isset($_FILES['excel_file']) && $_FILES['excel_file']['error'] === UPLOAD_ERR_OK) {
            $result = $teacher->importExcel($_FILES['excel_file']['tmp_name'], $_FILES['excel_file']['name']);
        } else {
            $result = ['success' => false, 'message' => 'Gagal mengupload file Excel.'];
        }
        break;
}

echo "<script>
alert('" . addslashes($result['message']) . "');
window.location='../teachers';
</script>";
