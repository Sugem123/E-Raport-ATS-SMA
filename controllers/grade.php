<?php
session_start();
if (empty($_SESSION['username']) || !in_array($_SESSION['role'] ?? '', ['admin', 'guru', 'walikelas'])) {
    die('Akses tidak valid.');
}

require_once __DIR__ . '/../config/Database.php';
require_once __DIR__ . '/../models/Grade.php';

$db = new Database();
$conn = $db->connect();
$grade = new Grade($conn);

$action = $_POST['action'] ?? '';
$idPengampu = (int)($_POST['id_pengampu'] ?? 0);
$redirectTo = $_POST['redirect_to'] ?? '';

if (!empty($redirectTo)) {
    $redirect = '../' . ltrim($redirectTo, '/');
} else {
    $redirect = ($action === 'save_presensi' || ($_SESSION['role'] ?? '') === 'walikelas')
        ? '../homeroom'
        : ('../grade-recap' . ($idPengampu > 0 ? '?id_pengampu=' . $idPengampu : ''));
}

$result = ['success' => false, 'message' => 'Action tidak valid.'];

switch ($action) {
    case 'save_grade':
        $nis = $_POST['nis'] ?? '';
        $s1  = (float)($_POST['sumatif_1'] ?? 0);
        $s2  = (float)($_POST['sumatif_2'] ?? 0);
        $s3  = (float)($_POST['sumatif_3'] ?? 0);
        $sts = (float)($_POST['nilai_sts'] ?? 0);

        $result = $grade->saveGrade($nis, $idPengampu, $s1, $s2, $s3, $sts);
        break;

    case 'upload_excel':
        if (isset($_FILES['excel_file']) && $_FILES['excel_file']['error'] === UPLOAD_ERR_OK) {
            $result = $grade->importExcel($_FILES['excel_file']['tmp_name'], $_FILES['excel_file']['name'], $idPengampu);
        } else {
            $result = ['success' => false, 'message' => 'Gagal mengupload file Excel nilai.'];
        }
        break;

    case 'upload_excel_presensi':
        if (isset($_FILES['excel_file']) && $_FILES['excel_file']['error'] === UPLOAD_ERR_OK) {
            $result = $grade->importExcelPresensi($_FILES['excel_file']['tmp_name'], $_FILES['excel_file']['name']);
        } else {
            $result = ['success' => false, 'message' => 'Gagal mengupload file Excel presensi.'];
        }
        break;

    case 'save_presensi':
        $nis   = $_POST['nis'] ?? '';
        $sakit = (int)($_POST['sakit'] ?? 0);
        $izin  = (int)($_POST['izin'] ?? 0);
        $alpa  = (int)($_POST['alpa'] ?? 0);

        $result = $grade->savePresensi($nis, $sakit, $izin, $alpa);
        break;

    case 'save_presensi_batch':
        $presensiData = $_POST['presensi'] ?? [];
        $count = 0;
        foreach ($presensiData as $nis => $item) {
            $sakit = max(0, (int)($item['sakit'] ?? 0));
            $izin  = max(0, (int)($item['izin'] ?? 0));
            $alpa  = max(0, (int)($item['alpa'] ?? 0));
            $res = $grade->savePresensi((string)$nis, $sakit, $izin, $alpa);
            if ($res['success']) {
                $count++;
            }
        }
        $result = ['success' => true, 'message' => "Data ketidakhadiran $count siswa berhasil disimpan."];
        break;

    case 'delete':
        $idNilai = (int)($_POST['id_nilai'] ?? 0);
        $result = $grade->deleteGrade($idNilai);
        break;
}

echo "<script>
alert('" . addslashes($result['message']) . "');
window.location='$redirect';
</script>";
