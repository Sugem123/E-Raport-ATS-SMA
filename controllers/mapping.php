<?php
session_start();
if (empty($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
    die('Akses tidak diizinkan.');
}

require_once __DIR__ . '/../config/Database.php';
require_once __DIR__ . '/../models/MapelMapping.php';

$db = new Database();
$conn = $db->connect();
$model = new MapelMapping($conn);

$action = $_POST['action'] ?? '';
$result = ['success' => false, 'message' => 'Action tidak valid.'];

// Kembalikan user ke tab jenjang yang sedang dikerjakan (whitelist agar aman)
$jenjangPost = $_POST['jenjang'] ?? '10';
$jenjangPost = in_array((string)$jenjangPost, MapelMapping::JENJANG, true) ? $jenjangPost : '10';
$redirect = '../subject-mapping?jenjang=' . urlencode($jenjangPost);

switch ($action) {
    case 'input':
    case 'update':
        $result = $model->save(
            $_POST['id_mapel'] ?? '',
            $_POST['jenjang'] ?? '10',
            $_POST['kategori'] ?? 'Umum',
            (int)($_POST['urutan'] ?? 0)
        );
        break;

    case 'delete':
        $result = $model->delete(
            $_POST['id_mapel'] ?? '',
            $_POST['jenjang'] ?? '10'
        );
        break;

    case 'copy':
        $result = $model->copyJenjang(
            $_POST['dari'] ?? '11',
            $_POST['ke'] ?? '12',
            !empty($_POST['timpa'])
        );
        $redirect = '../subject-mapping';
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
window.location='{$redirect}';
</script>";
