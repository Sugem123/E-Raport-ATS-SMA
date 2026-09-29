<?php
session_start();

if (empty($_SESSION['username']) || ($_SESSION['role'] ?? '') !== 'admin') {
    die('Akses tidak diizinkan. Hanya Administrator yang dapat mengakses modul Backup & Restore.');
}

require_once __DIR__ . '/../config/Database.php';
require_once __DIR__ . '/../models/Backup.php';

$db = new Database();
$conn = $db->connect();
$backupModel = new Backup($conn);

$action = $_GET['action'] ?? ($_POST['action'] ?? '');

switch ($action) {
    case 'download':
        // Bersihkan output buffer sebelum streaming file
        while (ob_get_level()) {
            ob_end_clean();
        }

        $filename = 'backup_db_raport_' . date('Ymd_His') . '.sql';

        header('Content-Type: application/sql; charset=utf-8');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        header('Pragma: no-cache');
        header('Expires: 0');
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');

        $backupModel->exportSql(function ($chunk) {
            echo $chunk;
            flush();
        });
        exit;

    case 'restore':
        $result = ['success' => false, 'message' => 'Berkas backup belum dipilih.'];

        if (isset($_FILES['backup_file']) && $_FILES['backup_file']['error'] === UPLOAD_ERR_OK) {
            $ext = strtolower(pathinfo($_FILES['backup_file']['name'], PATHINFO_EXTENSION));

            if ($ext !== 'sql') {
                $result = ['success' => false, 'message' => 'Format berkas tidak valid. Harap unggah berkas berekstensi .sql'];
            } else {
                $result = $backupModel->importSql($_FILES['backup_file']['tmp_name']);
            }
        } elseif (isset($_FILES['backup_file']) && $_FILES['backup_file']['error'] !== UPLOAD_ERR_NO_FILE) {
            $result = ['success' => false, 'message' => 'Gagal mengunggah berkas backup (Error Code: ' . $_FILES['backup_file']['error'] . ').'];
        }

        $msg = addslashes($result['message']);
        echo "<script>
            alert('$msg');
            window.location='../backup-restore';
        </script>";
        exit;

    default:
        header('Location: ../backup-restore');
        exit;
}
