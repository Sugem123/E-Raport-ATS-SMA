<?php
session_start();
if (empty($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
    die('Akses tidak valid.');
}

require_once __DIR__ . '/../config/Database.php';
require_once __DIR__ . '/../models/Setting.php';

$db = new Database();
$conn = $db->connect();
$settingModel = new Setting($conn);

$logoFile = $_FILES['logo_file'] ?? null;
$kopFile  = $_FILES['kop_file'] ?? null;
$result   = $settingModel->update($_POST, $logoFile, $kopFile);

$redirectTo = $_POST['redirect_to'] ?? 'settings';
if (!in_array($redirectTo, ['settings', 'school-profile'], true)) {
    $redirectTo = 'settings';
}

echo "<script>
alert('" . addslashes($result['message']) . "');
window.location='../{$redirectTo}';
</script>";
