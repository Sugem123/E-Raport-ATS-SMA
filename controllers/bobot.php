<?php
session_start();
if (empty($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
    die('Akses tidak diizinkan.');
}

require_once __DIR__ . '/../config/Database.php';
require_once __DIR__ . '/../models/Bobot.php';

$db = new Database();
$conn = $db->connect();
$model = new Bobot($conn);

$bSumatif = (float)($_POST['bobot_sumatif'] ?? 60);
$bSts = (float)($_POST['bobot_sts'] ?? 40);
$kkm = (float)($_POST['kkm'] ?? 75);

$result = $model->update($bSumatif, $bSts, $kkm);

echo "<script>
alert('" . addslashes($result['message']) . "');
window.location='../weights';
</script>";
