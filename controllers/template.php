<?php
session_start();

if (empty($_SESSION['username'])) {
    die('Akses tidak diizinkan. Silakan login terlebih dahulu.');
}

require_once __DIR__ . '/../config/Database.php';
require_once __DIR__ . '/../config/ExcelHelper.php';
require_once __DIR__ . '/../models/Teacher.php';

$type = $_GET['type'] ?? '';

// Khusus template nilai guru
if ($type === 'nilai_kelas') {
    $idPengampu = (int)($_GET['id_pengampu'] ?? 0);
    if ($idPengampu <= 0) {
        die('Parameter id_pengampu tidak valid.');
    }

    $db = new Database();
    $conn = $db->connect();

    // Verifikasi pengampu
    $stmt = mysqli_prepare(
        $conn,
        "SELECT p.id_pengampu, p.id_guru, p.id_mapel, g.nama_guru, m.nama_mapel, k.nama_kelas
         FROM tb_pengampu p
         INNER JOIN tb_mapel_referensi m ON p.id_mapel = m.id_mapel
         INNER JOIN tb_kelas k ON p.id_kelas = k.id_kelas
         LEFT JOIN tb_guru g ON p.id_guru = g.id_guru
         WHERE p.id_pengampu = ?"
    );
    mysqli_stmt_bind_param($stmt, "i", $idPengampu);
    mysqli_stmt_execute($stmt);
    $pengampu = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));

    if (!$pengampu) {
        die('Penugasan mengajar tidak ditemukan.');
    }

    // Layanan Bimbingan Konseling (BK) tidak memiliki penilaian kognitif/angka
    if ($pengampu['id_mapel'] === 'BDKB' || stripos($pengampu['nama_mapel'], 'Konseling') !== false || stripos($pengampu['nama_mapel'], 'Bimbingan') !== false) {
        die('Layanan Bimbingan dan Konseling (BK) tidak menggunakan penilaian angka/kognitif. Guru BK hanya menginputkan ketidakhadiran siswa.');
    }

    // Jika user adalah guru, pastikan pengampu ini miliknya
    if ($_SESSION['role'] === 'guru' && $_SESSION['id'] !== $pengampu['id_guru']) {
        die('Anda tidak memiliki akses ke penugasan ini.');
    }

    $teacherModel = new Teacher($conn);
    $students = $teacherModel->getStudentsForGrade($idPengampu);

    ExcelHelper::downloadTemplate('nilai_kelas', [
        'nama_kelas' => $pengampu['nama_kelas'],
        'nama_mapel' => $pengampu['nama_mapel'],
        'nama_guru'  => $pengampu['nama_guru'] ?? $pengampu['id_guru'],
        'students'   => $students
    ]);
    exit;
}

// Khusus template presensi / ketidakhadiran kelas
if ($type === 'presensi_kelas') {
    $idKelas = (int)($_GET['id_kelas'] ?? 0);
    if ($idKelas <= 0) {
        die('Parameter id_kelas tidak valid.');
    }

    $db = new Database();
    $conn = $db->connect();
    require_once __DIR__ . '/../models/Kelas.php';
    $kelasModel = new Kelas($conn);
    $kelas = $kelasModel->getById($idKelas);

    if (!$kelas) {
        die('Data kelas tidak ditemukan.');
    }

    // Ambil siswa beserta presensi yang sudah ada di kelas ini
    $sql = "SELECT s.nis, s.nama,
                   COALESCE(pr.sakit, 0) AS sakit,
                   COALESCE(pr.izin, 0) AS izin,
                   COALESCE(pr.alpa, 0) AS alpa
            FROM tb_siswa s
            LEFT JOIN tb_presensi_sts pr ON s.nis = pr.nis
            WHERE s.id_kelas = ?
            ORDER BY s.nama ASC";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "i", $idKelas);
    mysqli_stmt_execute($stmt);
    $res = mysqli_stmt_get_result($stmt);

    $students = [];
    while ($r = mysqli_fetch_assoc($res)) {
        $students[] = $r;
    }

    ExcelHelper::downloadTemplate('presensi_kelas', [
        'nama_kelas'     => $kelas['nama_kelas'],
        'nama_walikelas' => $kelas['nama_walikelas'] ?? 'Belum ditentukan',
        'students'       => $students
    ]);
    exit;
}

// Untuk template admin (guru, kelas, referensi mapel, mapping mapel, siswa)
if (($_SESSION['role'] ?? '') !== 'admin') {
    die('Hanya admin yang dapat mendownload template master data.');
}

ExcelHelper::downloadTemplate($type);
