<?php
session_start();
if (empty($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
    die('Akses tidak diizinkan.');
}

require_once __DIR__ . '/../config/Database.php';
require_once __DIR__ . '/../models/Mapel.php';

$db = new Database();
$conn = $db->connect();
$model = new Mapel($conn);

$action = $_POST['action'] ?? '';
$result = ['success' => false, 'message' => 'Action tidak valid.'];

switch ($action) {
    case 'input':
        $result = $model->create(
            $_POST['id_mapel'] ?? '',
            $_POST['nama_mapel'] ?? ''
        );
        break;

    case 'update':
        $idMapelLama = $_POST['id_mapel_lama'] ?? ($_POST['id_mapel'] ?? '');
        $idMapelBaru = $_POST['id_mapel'] ?? '';
        $result = $model->update(
            $idMapelLama,
            $idMapelBaru,
            $_POST['nama_mapel'] ?? ''
        );
        break;

    case 'delete':
        $result = $model->delete($_POST['id_mapel'] ?? '');
        break;

    case 'upload_excel':
        if (isset($_FILES['excel_file']) && $_FILES['excel_file']['error'] === UPLOAD_ERR_OK) {
            $result = $model->importExcel($_FILES['excel_file']['tmp_name'], $_FILES['excel_file']['name']);
        } else {
            $result = ['success' => false, 'message' => 'Gagal mengupload file Excel.'];
        }
        break;
}

echo "<script>
alert('" . addslashes($result['message']) . "');
window.location='../subjects';
</script>";
