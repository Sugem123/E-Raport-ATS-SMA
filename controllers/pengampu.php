<?php
session_start();
if (empty($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
    die('Akses tidak diizinkan.');
}

require_once __DIR__ . '/../config/Database.php';
require_once __DIR__ . '/../models/Pengampu.php';

$db = new Database();
$conn = $db->connect();
$model = new Pengampu($conn);

$action = $_POST['action'] ?? '';
$result = ['success' => false, 'message' => 'Action tidak valid.'];

// Kembalikan admin ke halaman tempat baris yang baru disimpan muncul,
// supaya daftar tetap terlihat berkelompok per kelas.
// Semua nilai dipaksa jadi int supaya tidak bisa dipakai sebagai open redirect.
$backPerPage = (int)($_POST['per_page'] ?? 25);
if (!in_array($backPerPage, [25, 50, 100], true)) {
    $backPerPage = 25;
}
$backPage = max(1, (int)($_POST['page'] ?? 1));

switch ($action) {
    case 'input':
        $idGuru = $_POST['id_guru'] ?? '';
        $idMapel = $_POST['id_mapel'] ?? '';
        $idKelas = (int)($_POST['id_kelas'] ?? 0);
        $result = $model->create($idGuru, $idMapel, $idKelas);
        if ($result['success']) {
            $backPage = $model->findPage($idGuru, $idMapel, $idKelas, $backPerPage);
        }
        break;

    case 'edit':
        $idPengampu = (int)($_POST['id_pengampu'] ?? 0);
        $idGuru     = $_POST['id_guru'] ?? '';
        $idMapel    = $_POST['id_mapel'] ?? '';
        $idKelas    = (int)($_POST['id_kelas'] ?? 0);

        if ($idGuru === '' || $idMapel === '' || $idKelas <= 0) {
            $result = ['success' => false, 'message' => 'Guru, mata pelajaran, dan kelas wajib dipilih.'];
            break;
        }

        $jmlNilai = $model->countNilai($idPengampu);
        $result = $model->update($idPengampu, $idGuru, $idMapel, $idKelas);

        // Nilai yang sudah terlanjur terinput tidak ikut berubah isinya,
        // tapi kini terasosiasi ke mapel/kelas yang baru. Beri tahu admin.
        if ($result['success'] && $jmlNilai > 0) {
            $result['message'] .= " Perhatian: $jmlNilai nilai sudah terlanjur terinput pada penugasan ini "
                . "dan sekarang ikut terasosiasi ke mapel/kelas yang baru.";
        }

        // Baris bisa pindah ke kelompok kelas lain, jadi hitung ulang posisinya.
        if ($result['success']) {
            $backPage = $model->findPage($idGuru, $idMapel, $idKelas, $backPerPage);
        }
        break;

    case 'delete':
        $idPengampu = (int)($_POST['id_pengampu'] ?? 0);
        $result = $model->delete($idPengampu);
        if ($result['success']) {
            // Baris terakhir di halaman ini hilang, mundur satu halaman bila perlu.
            $total = $model->countAll();
            $totalPage = max(1, (int)ceil($total / $backPerPage));
            if ($backPage > $totalPage) {
                $backPage = $totalPage;
            }
        }
        break;

    case 'reset':
        $result = $model->resetAll();
        $backPage = 1;
        break;

    case 'upload_excel':
        if (isset($_FILES['excel_file']) && $_FILES['excel_file']['error'] === UPLOAD_ERR_OK) {
            $result = $model->importExcel($_FILES['excel_file']['tmp_name'], $_FILES['excel_file']['name']);
        } else {
            $result = ['success' => false, 'message' => 'Gagal mengupload file Excel penugasan.'];
        }
        $backPage = 1;
        break;
}

$back = '../assignments?page=' . $backPage . '&per_page=' . $backPerPage;

echo "<script>
    alert('" . addslashes($result['message']) . "');
    window.location='" . $back . "';
</script>";
