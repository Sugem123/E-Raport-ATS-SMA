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

    case 'save_pembelajaran':
        $idKelas = (int)($_POST['id_kelas'] ?? 0);
        $guruList = $_POST['guru'] ?? []; // [ id_mapel => id_guru, ... ]

        if ($idKelas <= 0) {
            $result = ['success' => false, 'message' => 'Kelas tidak valid.'];
            break;
        }

        $saved = 0;
        $removed = 0;

        foreach ($guruList as $idMapel => $idGuru) {
            $idMapel = trim((string)$idMapel);
            $idGuru  = trim((string)$idGuru);

            if ($idGuru !== '') {
                // Cek apakah sudah ada pengampu untuk mapel di kelas ini
                $cek = mysqli_prepare($conn, "SELECT id_pengampu, id_guru FROM tb_pengampu WHERE id_kelas = ? AND id_mapel = ? LIMIT 1");
                mysqli_stmt_bind_param($cek, "is", $idKelas, $idMapel);
                mysqli_stmt_execute($cek);
                $rCek = mysqli_fetch_assoc(mysqli_stmt_get_result($cek));

                if ($rCek) {
                    // Update guru pengampu (menjaga FK id_pengampu dan nilai yang sudah ada)
                    if ($rCek['id_guru'] !== $idGuru) {
                        $upd = mysqli_prepare($conn, "UPDATE tb_pengampu SET id_guru = ? WHERE id_pengampu = ?");
                        mysqli_stmt_bind_param($upd, "si", $idGuru, $rCek['id_pengampu']);
                        mysqli_stmt_execute($upd);
                    }
                } else {
                    // Insert baru
                    $ins = mysqli_prepare($conn, "INSERT INTO tb_pengampu (id_guru, id_mapel, id_kelas) VALUES (?, ?, ?)");
                    mysqli_stmt_bind_param($ins, "ssi", $idGuru, $idMapel, $idKelas);
                    mysqli_stmt_execute($ins);
                }
                $saved++;
            } else {
                // Jika dikosongkan guru pengampunya, hapus baris pengampu jika ada
                $del = mysqli_prepare($conn, "DELETE FROM tb_pengampu WHERE id_kelas = ? AND id_mapel = ?");
                mysqli_stmt_bind_param($del, "is", $idKelas, $idMapel);
                mysqli_stmt_execute($del);
                if (mysqli_affected_rows($conn) > 0) {
                    $removed++;
                }
            }
        }

        $result = [
            'success' => true,
            'message' => "Pembelajaran kelas berhasil disimpan. $saved mata pelajaran aktif ditugaskan guru" . ($removed > 0 ? ", $removed penugasan dikosongkan." : ".")
        ];
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
