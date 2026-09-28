<?php
session_start();
if (empty($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
    die('Akses tidak diizinkan.');
}

require_once __DIR__ . '/../config/Database.php';
require_once __DIR__ . '/../models/Kelas.php';

$db = new Database();
$conn = $db->connect();
$model = new Kelas($conn);

$action = $_POST['action'] ?? '';
$result = ['success' => false, 'message' => 'Action tidak valid.'];

switch ($action) {
    case 'input':
        $result = $model->create($_POST['nama_kelas'] ?? '', $_POST['tingkat'] ?? '', $_POST['id_guru_walikelas'] ?? null);
        break;

    case 'update':
        $result = $model->update((int)($_POST['id_kelas'] ?? 0), $_POST['nama_kelas'] ?? '', $_POST['tingkat'] ?? '', $_POST['id_guru_walikelas'] ?? null);
        break;

    case 'delete':
        $result = $model->delete((int)($_POST['id_kelas'] ?? 0));
        break;

    case 'upload_excel':
        if (isset($_FILES['excel_file']) && $_FILES['excel_file']['error'] === UPLOAD_ERR_OK) {
            $result = $model->importExcel($_FILES['excel_file']['tmp_name'], $_FILES['excel_file']['name']);
        } else {
            $result = ['success' => false, 'message' => 'Gagal mengupload file Excel.'];
        }
        break;

    case 'add_student_new':
        require_once __DIR__ . '/../models/Student.php';
        $studentModel = new Student($conn);
        $idKelas = (int)($_POST['id_kelas'] ?? 0);
        $nis     = trim($_POST['nis'] ?? '');
        $nisn    = trim($_POST['nisn'] ?? '');
        $nama    = trim($_POST['nama'] ?? '');
        $username = $nis;
        $password = 'Abcde12345@';

        if (empty($nis) || empty($nama) || $idKelas <= 0) {
            $result = ['success' => false, 'message' => 'NIS, Nama Siswa, dan Kelas wajib diisi.'];
        } else {
            $result = $studentModel->create($nis, $nama, $idKelas, $username, $password, 'siswa', !empty($nisn) ? $nisn : null);
            if ($result['success']) {
                $result['message'] = 'Siswa baru berhasil didaftarkan ke kelas ini.';
            }
        }
        break;

    case 'move_student':
        $nis = trim($_POST['nis'] ?? '');
        $idKelasTujuan = (int)($_POST['id_kelas_tujuan'] ?? 0);

        if (empty($nis) || $idKelasTujuan <= 0) {
            $result = ['success' => false, 'message' => 'NIS dan Kelas Tujuan tidak valid.'];
        } else {
            $stmt = mysqli_prepare($conn, "UPDATE tb_siswa SET id_kelas = ? WHERE nis = ?");
            mysqli_stmt_bind_param($stmt, "is", $idKelasTujuan, $nis);
            if (mysqli_stmt_execute($stmt)) {
                $result = ['success' => true, 'message' => 'Siswa berhasil dipindahkan ke kelas tujuan.'];
            } else {
                $result = ['success' => false, 'message' => 'Gagal memindahkan siswa: ' . mysqli_error($conn)];
            }
        }
        break;

    case 'delete_student':
        require_once __DIR__ . '/../models/Student.php';
        $studentModel = new Student($conn);
        $nis = trim($_POST['nis'] ?? '');

        if (empty($nis)) {
            $result = ['success' => false, 'message' => 'NIS siswa tidak valid.'];
        } else {
            $q = mysqli_query($conn, "SELECT id_user FROM tb_siswa WHERE nis = '" . mysqli_real_escape_string($conn, $nis) . "'");
            $r = mysqli_fetch_assoc($q);
            if ($r) {
                $result = $studentModel->delete((int)$r['id_user']);
                if ($result['success']) {
                    $result['message'] = 'Siswa berhasil dihapus dari sistem.';
                }
            } else {
                $result = ['success' => false, 'message' => 'Data siswa tidak ditemukan.'];
            }
        }
        break;
}

echo "<script>
alert('" . addslashes($result['message']) . "');
window.location='../classes';
</script>";
