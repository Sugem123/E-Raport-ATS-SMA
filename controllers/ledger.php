<?php
session_start();

if (empty($_SESSION['username']) || !in_array($_SESSION['role'] ?? '', ['admin', 'walikelas'])) {
    die('Akses tidak diizinkan. Silakan login terlebih dahulu.');
}

require_once __DIR__ . '/../config/Database.php';
require_once __DIR__ . '/../models/Ledger.php';
require_once __DIR__ . '/../models/Kelas.php';
require_once __DIR__ . '/../config/SimpleXlsx.php';

$db = new Database();
$conn = $db->connect();

$ledgerModel = new Ledger($conn);
$kelasModel  = new Kelas($conn);

$action = $_GET['action'] ?? ($_POST['action'] ?? '');
$type   = $_GET['type'] ?? 'ringkas';
$idKelas = (int)($_GET['kelas'] ?? 0);

// Keamanan khusus Wali Kelas: paksa atau validasi kelas yang diakses adalah perwaliannya
if (($_SESSION['role'] ?? '') === 'walikelas') {
    $idGuru = $_SESSION['id'] ?? '';
    $myClass = $kelasModel->getByWaliKelas($idGuru);
    $myIdKelas = $myClass ? (int)$myClass['id_kelas'] : 0;

    if ($myIdKelas <= 0) {
        die('Anda belum terdaftar sebagai wali kelas aktif.');
    }

    if ($idKelas > 0 && $idKelas !== $myIdKelas) {
        die('Anda hanya dapat mengakses ledger nilai untuk kelas perwalian Anda sendiri.');
    }
    $idKelas = $myIdKelas;
}

if ($idKelas <= 0) {
    die('Parameter ID Kelas tidak valid.');
}

$data = $ledgerModel->getLedgerData($idKelas);
if (!$data || empty($data['kelas'])) {
    die('Data kelas tidak ditemukan.');
}

$stats = $ledgerModel->calculateStats($data);

if ($action === 'excel') {
    // Bersihkan buffer output
    while (ob_get_level()) {
        ob_end_clean();
    }

    $namaKelasClean = preg_replace('/[^a-zA-Z0-9_\-]/', '_', $data['kelas']['nama_kelas']);

    $info = array_merge($data['setting'], $data['kelas']);

    if ($type === 'lengkap') {
        $filename = "ledger_sts_lengkap_kelas_{$namaKelasClean}.xlsx";
        SimpleXlsx::downloadLedgerLengkap(
            $filename,
            $info,
            $data['mapel_list'],
            $data['siswa_list'],
            $data['grades'],
            $stats
        );
    } else {
        $filename = "ledger_sts_ringkas_kelas_{$namaKelasClean}.xlsx";
        SimpleXlsx::downloadLedgerRingkas(
            $filename,
            $info,
            $data['mapel_list'],
            $data['siswa_list'],
            $data['grades'],
            $stats
        );
    }
    exit;
}

header('Location: ../ledger?kelas=' . $idKelas);
exit;
