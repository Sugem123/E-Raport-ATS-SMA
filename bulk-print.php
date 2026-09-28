<?php
include_once "config/Database.php";
require_once "models/Kelas.php";
require_once "models/Setting.php";

$db = new Database();
$conn = $db->connect();

$kelasModel = new Kelas($conn);
$settingModel = new Setting($conn);
$setting = $settingModel->get();

$role = $_SESSION['role'] ?? '';
$idUser = $_SESSION['id'] ?? '';

if (!in_array($role, ['admin', 'walikelas'])) {
    echo "<div class='alert alert-danger'>Akses ditolak.</div>";
    return;
}

$allClasses = $kelasModel->getAll();

// Wali Kelas: langsung arahkan ke kelasnya
$myClass = null;
if ($role === 'walikelas') {
    if (!empty($_SESSION['id_kelas'])) {
        $myClass = $kelasModel->getById((int)$_SESSION['id_kelas']);
    }
    if (!$myClass && !empty($idUser)) {
        $myClass = $kelasModel->getByWaliKelas($idUser);
    }
}

$selectedKelasId = null;
if ($role === 'walikelas' && $myClass) {
    $selectedKelasId = (int)$myClass['id_kelas'];
} else if ($role === 'admin' && isset($_GET['kelas']) && $_GET['kelas'] !== '') {
    $selectedKelasId = (int)$_GET['kelas'];
}

$classData = null;
$homeroomData = null;
if ($selectedKelasId) {
    $classData = $kelasModel->getById($selectedKelasId);
    $homeroomData = $kelasModel->getHomeroomSummary($selectedKelasId);
}
?>

<div class="col-lg-9 mt-2">

    <!-- Header Banner -->
    <div class="card shadow-sm border-0 mb-4 bg-primary text-white" style="background: linear-gradient(135deg, #1e3a8a, #0f172a) !important; border-radius: 18px;">
        <div class="card-body p-4">
            <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
                <div>
                    <span class="badge bg-danger text-white px-3 py-1 mb-2 fw-semibold">
                        <i class="fa-solid fa-print me-1"></i> CETAK MASSAL RAPOR STS
                    </span>
                    <h4 class="fw-bold mb-1">Cetak Lembar Rapor Hasil Belajar Siswa</h4>
                    <p class="mb-0 opacity-90 small">
                        Cetak rapor tengah semester Kurikulum Merdeka sekaligus untuk seluruh siswa dalam satu kelas (Format A4 &bull; Siap Print / Save as PDF).
                    </p>
                </div>
                <div class="text-end">
                    <a href="grade-monitor<?= $selectedKelasId ? '?kelas=' . $selectedKelasId : '' ?>" class="btn btn-outline-light btn-sm">
                        <i class="fa-solid fa-chart-pie me-1"></i> Monitor Kelengkapan
                    </a>
                </div>
            </div>
        </div>
    </div>

    <?php if ($role === 'walikelas' && !$myClass) { ?>
        <div class="card shadow-sm border-0 p-4">
            <div class="alert alert-warning mb-0">
                <i class="fa-solid fa-triangle-exclamation me-1"></i> Akun Anda belum terdaftar sebagai wali kelas aktif. Hubungi Administrator.
            </div>
        </div>
    <?php return; } ?>

    <!-- Selector Kelas untuk Admin -->
    <?php if ($role === 'admin') { ?>
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-body p-3">
                <form action="index.php" method="GET" class="row g-2 align-items-center">
                    <input type="hidden" name="x" value="bulk-print">
                    <div class="col-auto">
                        <label class="fw-bold small text-muted"><i class="fa-solid fa-chalkboard me-1"></i> Pilih Rombel Kelas:</label>
                    </div>
                    <div class="col-md-5">
                        <select name="kelas" class="form-select form-select-sm" onchange="this.form.submit()">
                            <option value="">-- Pilih Kelas untuk Dicetak --</option>
                            <?php foreach ($allClasses as $c) { ?>
                                <option value="<?= $c['id_kelas'] ?>" <?= $selectedKelasId == $c['id_kelas'] ? 'selected' : '' ?>>
                                    Kelas <?= htmlspecialchars($c['nama_kelas']) ?> (Fase <?= $c['tingkat'] === '10' ? 'E' : 'F' ?>) - Wali: <?= htmlspecialchars($c['nama_walikelas'] ?? 'Belum ditentukan') ?>
                                </option>
                            <?php } ?>
                        </select>
                    </div>
                    <div class="col-auto">
                        <button type="submit" class="btn btn-primary btn-sm">Tampilkan</button>
                    </div>
                </form>
            </div>
        </div>
    <?php } ?>

    <?php if ($selectedKelasId && $classData && $homeroomData) {
        $siswaList = $homeroomData['siswa_list'] ?? [];
        $mapelList = $homeroomData['mapel_list'] ?? [];
        $totalSiswa = count($siswaList);
        $totalMapel = count($mapelList);

        $siapCetak = 0;
        foreach ($siswaList as $s) {
            if ($s['total_mapel'] > 0 && $s['jumlah_terisi'] === $s['total_mapel']) {
                $siapCetak++;
            }
        }
    ?>
        <!-- Detail Kelas & Tombol Cetak Massal -->
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-header bg-white py-3 d-flex flex-wrap justify-content-between align-items-center gap-2">
                <div>
                    <h5 class="mb-0 fw-bold text-primary">
                        <i class="fa-solid fa-users-rectangle me-2"></i>Kelas <?= htmlspecialchars($classData['nama_kelas']) ?>
                    </h5>
                    <small class="text-muted">
                        Wali Kelas: <strong><?= htmlspecialchars($classData['nama_walikelas'] ?? 'Belum diatur') ?></strong> &bull;
                        Tingkat <?= htmlspecialchars($classData['tingkat']) ?> (Fase <?= $classData['tingkat'] === '10' ? 'E' : 'F' ?>)
                    </small>
                </div>
                <div class="d-flex gap-2">
                    <a href="bulk-print-view.php?kelas=<?= $selectedKelasId ?>" target="_blank" class="btn btn-danger shadow-sm">
                        <i class="fa-solid fa-print me-1"></i> Buka & Cetak Massal Semua Siswa (<?= $totalSiswa ?>)
                    </a>
                </div>
            </div>
            <div class="card-body">
                <!-- Status Bar Kelengkapan -->
                <div class="row g-3 mb-3">
                    <div class="col-md-4">
                        <div class="p-3 bg-light rounded border text-center">
                            <span class="text-muted small d-block">Total Peserta Didik</span>
                            <span class="fs-4 fw-bold text-primary"><?= $totalSiswa ?> Siswa</span>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="p-3 bg-light rounded border text-center">
                            <span class="text-muted small d-block">Jumlah Mata Pelajaran</span>
                            <span class="fs-4 fw-bold text-info"><?= $totalMapel ?> Mapel</span>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="p-3 bg-light rounded border text-center">
                            <span class="text-muted small d-block">Siswa Nilai Lengkap</span>
                            <span class="fs-4 fw-bold <?= $siapCetak === $totalSiswa && $totalSiswa > 0 ? 'text-success' : 'text-warning' ?>">
                                <?= $siapCetak ?> / <?= $totalSiswa ?> Siswa
                            </span>
                        </div>
                    </div>
                </div>

                <div class="alert alert-info py-2 small mb-3">
                    <i class="fa-solid fa-circle-info me-1"></i>
                    Klik tombol <strong>"Buka & Cetak Massal Semua Siswa"</strong> di atas untuk membuka dokumen rapor gabungan seluruh siswa kelas ini. Anda dapat mencetaknya langsung ke printer fisik atau memilih <strong>"Save as PDF"</strong> pada dialog print browser.
                </div>

                <!-- Daftar Siswa dengan Opsi Cetak Individual -->
                <div class="table-responsive">
                    <table class="table table-hover align-middle border">
                        <thead class="table-light">
                            <tr class="text-center">
                                <th style="width: 5%">No</th>
                                <th>NIS</th>
                                <th class="text-start">Nama Lengkap Siswa</th>
                                <th>Mapel Terisi</th>
                                <th>Rata-rata Nilai</th>
                                <th>Status Kelengkapan</th>
                                <th style="width: 15%">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($siswaList)) { ?>
                                <tr><td colspan="7" class="text-center py-4 text-muted">Belum ada siswa di kelas ini.</td></tr>
                            <?php } else {
                                $no = 1;
                                foreach ($siswaList as $s) {
                                    $isFull = ($s['total_mapel'] > 0 && $s['jumlah_terisi'] === $s['total_mapel']);
                            ?>
                            <tr class="text-center">
                                <td><?= $no++ ?></td>
                                <td class="font-monospace fw-semibold"><?= htmlspecialchars($s['nis']) ?></td>
                                <td class="text-start fw-semibold text-dark"><?= htmlspecialchars($s['nama']) ?></td>
                                <td>
                                    <span class="badge <?= $isFull ? 'bg-success' : 'bg-warning text-dark' ?>">
                                        <?= $s['jumlah_terisi'] ?> / <?= $s['total_mapel'] ?> Mapel
                                    </span>
                                </td>
                                <td class="fw-bold text-primary fs-6">
                                    <?= $s['rata_rata_semua'] > 0 ? $s['rata_rata_semua'] : '-' ?>
                                </td>
                                <td>
                                    <?php if ($isFull) { ?>
                                        <span class="badge bg-success-subtle text-success border border-success">
                                            <i class="fa-solid fa-circle-check me-1"></i> Nilai Lengkap
                                        </span>
                                    <?php } else { ?>
                                        <span class="badge bg-warning-subtle text-warning-emphasis border border-warning">
                                            <i class="fa-solid fa-hourglass-half me-1"></i> Belum Lengkap
                                        </span>
                                    <?php } ?>
                                </td>
                                <td>
                                    <a href="student-report.php?nis=<?= urlencode($s['nis']) ?>" target="_blank" class="btn btn-outline-primary btn-sm" title="Cetak Rapor Individual Siswa Ini">
                                        <i class="fa-solid fa-print me-1"></i> Rapor
                                    </a>
                                </td>
                            </tr>
                            <?php } } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    <?php } else if ($role === 'admin' && !$selectedKelasId) { ?>
        <!-- Panduan Pemilihan Kelas untuk Admin -->
        <div class="card shadow-sm border-0 p-4">
            <h5 class="fw-bold text-primary mb-3"><i class="fa-solid fa-list-check me-2"></i>Pilih Kelas yang Ingin Dicetak</h5>
            <p class="text-muted">Silakan pilih salah satu kelas di bawah ini untuk melihat pratinjau dan melakukan pencetakan massal rapor STS:</p>
            <div class="row g-2">
                <?php foreach ($allClasses as $c) { ?>
                    <div class="col-sm-6 col-md-4 col-lg-3">
                        <a href="index.php?x=bulk-print&kelas=<?= $c['id_kelas'] ?>" class="btn btn-outline-secondary w-100 py-2 text-start d-flex justify-content-between align-items-center">
                            <div>
                                <span class="fw-bold text-dark d-block">Kelas <?= htmlspecialchars($c['nama_kelas']) ?></span>
                                <small class="text-muted" style="font-size: 11px;">Fase <?= $c['tingkat'] === '10' ? 'E' : 'F' ?></small>
                            </div>
                            <i class="fa-solid fa-chevron-right text-muted small"></i>
                        </a>
                    </div>
                <?php } ?>
            </div>
        </div>
    <?php } ?>

</div>
