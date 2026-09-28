<?php
include_once "config/Database.php";
require_once "models/Setting.php";

$db = new Database();
$conn = $db->connect();
$settingModelHome = new Setting($conn);
$schoolHome = $settingModelHome->get();

$role = $_SESSION['role'] ?? '';
$nama = $_SESSION['nama'] ?? 'Pengguna';
?>

<div class="col-lg-9 mt-2">
    <!-- Welcome Banner -->
    <div class="card shadow-sm border-0 mb-4 bg-primary text-white" style="background: linear-gradient(135deg, #1e3a8a, #2563eb) !important; border-radius: 20px;">
        <div class="card-body p-4">
            <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
                <div>
                    <h4 class="fw-bold mb-1">Selamat Datang, <?= htmlspecialchars($nama) ?>!</h4>
                    <p class="mb-0 opacity-90 small">
                        Sistem Informasi e-Raport STS &bull; <?= htmlspecialchars($schoolHome['nama_sekolah']) ?> (TA <?= htmlspecialchars($schoolHome['tahun_ajaran']) ?> Semester <?= htmlspecialchars($schoolHome['semester']) ?>)
                    </p>
                </div>
                <span class="badge bg-light text-primary fs-6 px-3 py-2 text-uppercase fw-bold rounded-pill">
                    Role: <?= htmlspecialchars($role) ?>
                </span>
            </div>
        </div>
    </div>

    <?php if ($role === 'admin') {
        $cntGuru  = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as c FROM tb_guru"))['c'];
        $cntKelas = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as c FROM tb_kelas"))['c'];
        $cntMapel = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as c FROM tb_mapel_referensi"))['c'];
        $cntSiswa = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as c FROM tb_siswa"))['c'];
    ?>
        <!-- Admin Dashboard Cards -->
        <div class="row g-3 mb-4">
            <div class="col-sm-6 col-xl-3">
                <div class="card shadow-sm border-0 p-3">
                    <div class="d-flex align-items-center">
                        <div class="bg-primary-subtle text-primary p-3 rounded me-3 fs-4">
                            <i class="fa-solid fa-chalkboard-user"></i>
                        </div>
                        <div>
                            <span class="text-muted small">Total Guru</span>
                            <h4 class="fw-bold mb-0"><?= $cntGuru ?></h4>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-xl-3">
                <div class="card shadow-sm border-0 p-3">
                    <div class="d-flex align-items-center">
                        <div class="bg-success-subtle text-success p-3 rounded me-3 fs-4">
                            <i class="fa-solid fa-chalkboard"></i>
                        </div>
                        <div>
                            <span class="text-muted small">Total Kelas</span>
                            <h4 class="fw-bold mb-0"><?= $cntKelas ?></h4>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-xl-3">
                <div class="card shadow-sm border-0 p-3">
                    <div class="d-flex align-items-center">
                        <div class="bg-warning-subtle text-warning p-3 rounded me-3 fs-4">
                            <i class="fa-solid fa-book"></i>
                        </div>
                        <div>
                            <span class="text-muted small">Mata Pelajaran</span>
                            <h4 class="fw-bold mb-0"><?= $cntMapel ?></h4>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-xl-3">
                <div class="card shadow-sm border-0 p-3">
                    <div class="d-flex align-items-center">
                        <div class="bg-info-subtle text-info p-3 rounded me-3 fs-4">
                            <i class="fa-solid fa-user-graduate"></i>
                        </div>
                        <div>
                            <span class="text-muted small">Total Siswa</span>
                            <h4 class="fw-bold mb-0"><?= $cntSiswa ?></h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Monitoring & Cetak Action Banner for Admin -->
        <div class="card shadow-sm border-0 mb-4 p-4" style="background: linear-gradient(135deg, #f8fafc, #edf2f7); border-left: 5px solid #2563eb !important;">
            <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
                <div>
                    <h5 class="fw-bold text-dark mb-1">
                        <i class="fa-solid fa-chart-pie text-primary me-2"></i>Monitoring Penilaian & Cetak Rapor STS
                    </h5>
                    <p class="text-muted small mb-0">
                        Pantau status pengisian nilai per rombel/mapel secara real-time dan cetak rapor hasil belajar secara massal.
                    </p>
                </div>
                <div class="d-flex gap-2">
                    <a href="grade-monitor" class="btn btn-primary px-3 py-2 shadow-sm">
                        <i class="fa-solid fa-gauge-high me-1"></i> Buka Monitor Kelas
                    </a>
                    <a href="bulk-print" class="btn btn-outline-danger px-3 py-2">
                        <i class="fa-solid fa-print me-1"></i> Cetak Rapor
                    </a>
                </div>
            </div>
        </div>

        <div class="card shadow-sm border-0 p-4">
            <h6 class="fw-bold mb-3">Panduan Cepat Administrator</h6>
            <div class="row g-3">
                <div class="col-md-4">
                    <div class="border rounded p-3 text-center h-100">
                        <i class="fa-solid fa-file-excel text-success fs-1 mb-2"></i>
                        <h6 class="fw-bold">1. Upload Master Data</h6>
                        <p class="small text-muted mb-0">Upload Guru, Kelas, Mapel, dan Siswa langsung via format file Excel (.xlsx).</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="border rounded p-3 text-center h-100">
                        <i class="fa-solid fa-link text-primary fs-1 mb-2"></i>
                        <h6 class="fw-bold">2. Penugasan Guru</h6>
                        <p class="small text-muted mb-0">Atur pembagian kelas & mata pelajaran yang diajar masing-masing guru.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="border rounded p-3 text-center h-100">
                        <i class="fa-solid fa-sliders text-warning fs-1 mb-2"></i>
                        <h6 class="fw-bold">3. Pengaturan Bobot</h6>
                        <p class="small text-muted mb-0">Tentukan persentase bobot Sumatif vs STS dan standar KKM kelulusan.</p>
                    </div>
                </div>
            </div>
        </div>

    <?php } elseif ($role === 'guru') {
        $idGuru = $_SESSION['id'] ?? '';
        $qP = mysqli_query($conn, "SELECT COUNT(*) as c FROM tb_pengampu WHERE id_guru = '$idGuru'");
        $cntPengampu = mysqli_fetch_assoc($qP)['c'];
    ?>
        <!-- Guru Dashboard -->
        <div class="card shadow-sm border-0 p-4">
            <h5 class="fw-bold mb-3 text-primary"><i class="fa-solid fa-list-check me-2"></i>Menu Penilaian Guru</h5>
            <p class="text-muted">
                Anda mengampu sebanyak <strong><?= $cntPengampu ?></strong> rombongan belajar/mata pelajaran.
            </p>
            <a href="grade-recap" class="btn btn-primary px-4 py-2">
                <i class="fa-solid fa-pen-to-square me-1"></i> Mulai Input / Upload Nilai STS
            </a>
        </div>

    <?php } elseif ($role === 'walikelas') {
        $idGuru = $_SESSION['id'] ?? '';
        $qW = mysqli_query($conn, "SELECT nama_kelas FROM tb_kelas WHERE id_guru_walikelas = '$idGuru'");
        $rW = mysqli_fetch_assoc($qW);
        $kelasNama = $rW['nama_kelas'] ?? '-';
    ?>
        <!-- Wali Kelas Dashboard -->
        <div class="card shadow-sm border-0 p-4">
            <h5 class="fw-bold mb-3 text-primary"><i class="fa-solid fa-users-line me-2"></i>Menu Wali Kelas</h5>
            <p class="text-muted">
                Anda adalah Wali Kelas untuk <strong>Kelas <?= htmlspecialchars($kelasNama) ?></strong>.
            </p>
            <div class="d-flex flex-wrap gap-2">
                <a href="homeroom" class="btn btn-primary px-4 py-2">
                    <i class="fa-solid fa-clipboard-check me-1"></i> Perwalian & Presensi
                </a>
                <a href="grade-monitor" class="btn btn-outline-info px-4 py-2">
                    <i class="fa-solid fa-chart-pie me-1"></i> Monitor Kelengkapan Nilai Per Mapel
                </a>
                <a href="bulk-print" class="btn btn-outline-danger px-4 py-2">
                    <i class="fa-solid fa-print me-1"></i> Cetak Rapor Kelas
                </a>
            </div>
        </div>

    <?php } elseif ($role === 'siswa') { ?>
        <!-- Siswa Dashboard -->
        <div class="card shadow-sm border-0 p-4">
            <h5 class="fw-bold mb-3 text-primary"><i class="fa-solid fa-graduation-cap me-2"></i>Hasil Belajar Tengah Semester</h5>
            <p class="text-muted">
                Periksa nilai pencapaian Sumatif Lingkup Materi 1, 2, 3 dan Asesmen Tengah Semester (STS) Anda.
            </p>
            <a href="grade-summary" class="btn btn-primary px-4 py-2">
                <i class="fa-solid fa-file-invoice me-1"></i> Buka Rapor STS Saya
            </a>
        </div>
    <?php } ?>
</div>
