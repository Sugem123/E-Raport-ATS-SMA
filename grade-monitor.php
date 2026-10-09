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

// Penentuan kelas yang dipantau
$selectedKelasId = null;
$myClass = null;

if ($role === 'walikelas') {
    // Wali kelas hanya boleh memantau kelasnya sendiri
    if (!empty($_SESSION['id_kelas'])) {
        $myClass = $kelasModel->getById((int)$_SESSION['id_kelas']);
    }
    if (!$myClass && !empty($idUser)) {
        $myClass = $kelasModel->getByWaliKelas($idUser);
    }
    if ($myClass) {
        $selectedKelasId = (int)$myClass['id_kelas'];
    }
} else {
    // Admin: bisa memilih kelas mana saja via parameter ?kelas=...
    if (isset($_GET['kelas']) && $_GET['kelas'] !== '') {
        $selectedKelasId = (int)$_GET['kelas'];
    }
}

// Ambil data monitoring
$overview = null;
$classDetail = null;
$homeroomSummary = null;

if ($selectedKelasId === null && $role === 'admin') {
    // Tampilan overview seluruh kelas untuk Admin
    $overview = $kelasModel->getMonitoringOverview();
} else if ($selectedKelasId !== null) {
    // Tampilan detail kelas terpilih
    $classDetail = $kelasModel->getMonitoringPerMapel($selectedKelasId);
    $homeroomSummary = $kelasModel->getHomeroomSummary($selectedKelasId);
}
?>

<div class="col-lg-9 mt-2">

<?php if ($role === 'walikelas' && !$myClass) { ?>
    <!-- Notice untuk wali kelas yang belum ditugaskan -->
    <div class="card shadow-sm border-0 p-4">
        <div class="alert alert-warning mb-0">
            <h5 class="fw-bold mb-2"><i class="fa-solid fa-triangle-exclamation me-2"></i>Belum Ada Kelas Perwalian</h5>
            <p class="mb-0">Akun Anda belum terhubung sebagai Wali Kelas pada data kelas manapun. Silakan hubungi Administrator untuk mengatur penugasan wali kelas pada menu <strong>Data Master &gt; Data Kelas</strong>.</p>
        </div>
    </div>
<?php return; } ?>

<?php if ($selectedKelasId === null && $role === 'admin') {
    $stats = $overview['stats'];
    $classes = $overview['classes'];
?>
    <!-- ======================================================================= -->
    <!-- VIEW 1: OVERVIEW SEMUA KELAS (ADMIN VIEW)                               -->
    <!-- ======================================================================= -->

    <!-- Header Section -->
    <div class="card shadow-sm border-0 mb-4 bg-primary text-white" style="background: linear-gradient(135deg, #0f172a, #1e3a8a) !important; border-radius: 18px;">
        <div class="card-body p-4">
            <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
                <div>
                    <span class="badge bg-primary-subtle text-primary border border-primary px-3 py-1 mb-2 fw-semibold">
                        <i class="fa-solid fa-chart-pie me-1"></i> MONITORING PENILAIAN STS
                    </span>
                    <h4 class="fw-bold mb-1">Status Pengisian Nilai Seluruh Kelas</h4>
                    <p class="mb-0 opacity-90 small">
                        Pantau progres kelengkapan nilai sumatif & STS dari seluruh mata pelajaran di <?= $stats['total_kelas'] ?> rombel belajar.
                    </p>
                </div>
                <div class="text-end">
                    <a href="bulk-print" class="btn btn-warning fw-bold px-3 py-2 shadow-sm">
                        <i class="fa-solid fa-print me-1"></i> Cetak Rapor STS
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Stat Cards Overview -->
    <div class="row g-3 mb-4">
        <div class="col-sm-6 col-xl-3">
            <div class="card shadow-sm border-0 p-3 h-100">
                <div class="d-flex align-items-center">
                    <div class="bg-primary-subtle text-primary p-3 rounded me-3 fs-4">
                        <i class="fa-solid fa-chalkboard"></i>
                    </div>
                    <div>
                        <span class="text-muted small">Total Kelas</span>
                        <h4 class="fw-bold mb-0"><?= $stats['total_kelas'] ?> Rombel</h4>
                        <small class="text-muted"><?= $stats['total_siswa'] ?> Siswa</small>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-sm-6 col-xl-3">
            <div class="card shadow-sm border-0 p-3 h-100">
                <div class="d-flex align-items-center">
                    <div class="bg-success-subtle text-success p-3 rounded me-3 fs-4">
                        <i class="fa-solid fa-circle-check"></i>
                    </div>
                    <div>
                        <span class="text-muted small">Penuh Terisi</span>
                        <h4 class="fw-bold mb-0 text-success"><?= $stats['kelas_lengkap'] ?> Kelas</h4>
                        <small class="text-success fw-semibold">Semua mapel terisi</small>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-sm-6 col-xl-3">
            <div class="card shadow-sm border-0 p-3 h-100">
                <div class="d-flex align-items-center">
                    <div class="bg-warning-subtle text-warning p-3 rounded me-3 fs-4">
                        <i class="fa-solid fa-clock-rotate-left"></i>
                    </div>
                    <div>
                        <span class="text-muted small">Belum Lengkap</span>
                        <h4 class="fw-bold mb-0 text-warning"><?= $stats['kelas_belum'] ?> Kelas</h4>
                        <small class="text-muted">Masih dalam pengisian</small>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-sm-6 col-xl-3">
            <div class="card shadow-sm border-0 p-3 h-100">
                <div class="d-flex align-items-center">
                    <div class="bg-info-subtle text-info p-3 rounded me-3 fs-4">
                        <i class="fa-solid fa-percent"></i>
                    </div>
                    <div>
                        <span class="text-muted small">Progress Global</span>
                        <h4 class="fw-bold mb-0 text-info"><?= $stats['persen_global'] ?>%</h4>
                        <small class="text-muted"><?= number_format($stats['total_nilai_masuk']) ?> / <?= number_format($stats['total_target_nilai']) ?></small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter & Table Card -->
    <div class="card shadow-sm border-0">
        <div class="card-header bg-white py-3">
            <div class="d-flex flex-wrap align-items-center justify-content-between gap-2">
                <div class="d-flex align-items-center gap-2">
                    <h5 class="mb-0 fw-bold text-primary">
                        <i class="fa-solid fa-table-list me-2"></i>Daftar Kelengkapan Penilaian Kelas
                    </h5>
                </div>
                <!-- Filter Pills -->
                <div class="btn-group btn-group-sm" role="group">
                    <button type="button" class="btn btn-outline-secondary active btn-filter-status" data-filter="all">Semua (<?= $stats['total_kelas'] ?>)</button>
                    <button type="button" class="btn btn-outline-success btn-filter-status" data-filter="lengkap">Lengkap (<?= $stats['kelas_lengkap'] ?>)</button>
                    <button type="button" class="btn btn-outline-warning btn-filter-status" data-filter="belum">Belum (<?= $stats['kelas_belum'] ?>)</button>
                    <button type="button" class="btn btn-outline-primary btn-filter-status" data-filter="t10">Tingkat 10</button>
                    <button type="button" class="btn btn-outline-primary btn-filter-status" data-filter="t11">Tingkat 11</button>
                    <button type="button" class="btn btn-outline-primary btn-filter-status" data-filter="t12">Tingkat 12</button>
                </div>
            </div>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0" id="tableMonitorOverview">
                    <thead class="table-light">
                        <tr class="text-center">
                            <th style="width: 5%">No</th>
                            <th class="text-start">Kelas & Tingkat</th>
                            <th class="text-start">Wali Kelas</th>
                            <th>Siswa</th>
                            <th>Mapel</th>
                            <th>Mapel Lengkap</th>
                            <th style="width: 22%">Progres Pengisian</th>
                            <th>Status</th>
                            <th style="width: 14%">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $no = 1;
                        foreach ($classes as $c) {
                            $isFull = $c['is_lengkap'];
                            $tingkatClass = 't' . $c['tingkat'];
                            $statusClass = $isFull ? 'lengkap' : 'belum';
                        ?>
                        <tr class="text-center row-kelas" data-status="<?= $statusClass ?>" data-tingkat="<?= $tingkatClass ?>">
                            <td><?= $no++ ?></td>
                            <td class="text-start">
                                <a href="grade-monitor?kelas=<?= $c['id_kelas'] ?>" class="fw-bold text-decoration-none text-primary fs-6">
                                    Kelas <?= htmlspecialchars($c['nama_kelas']) ?>
                                </a>
                                <span class="badge bg-secondary-subtle text-secondary small ms-1">Fase <?= ($c['tingkat'] === '10' ? 'E' : 'F') ?></span>
                            </td>
                            <td class="text-start">
                                <?php if (!empty($c['nama_walikelas'])) { ?>
                                    <i class="fa-solid fa-user-tie text-success me-1"></i>
                                    <span class="small fw-semibold"><?= htmlspecialchars($c['nama_walikelas']) ?></span>
                                <?php } else { ?>
                                    <span class="text-muted small fst-italic">Belum diatur</span>
                                <?php } ?>
                            </td>
                            <td><span class="badge bg-light text-dark border"><?= $c['total_siswa'] ?></span></td>
                            <td><span class="badge bg-light text-dark border"><?= $c['total_mapel'] ?></span></td>
                            <td>
                                <span class="badge <?= $c['mapel_lengkap'] === $c['total_mapel'] && $c['total_mapel'] > 0 ? 'bg-success' : 'bg-warning text-dark' ?>">
                                    <?= $c['mapel_lengkap'] ?> / <?= $c['total_mapel'] ?>
                                </span>
                            </td>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <div class="progress flex-grow-1" style="height: 8px;">
                                        <div class="progress-bar <?= $isFull ? 'bg-success' : 'bg-primary' ?>"
                                             role="progressbar" style="width: <?= $c['persen'] ?>%"></div>
                                    </div>
                                    <span class="small fw-bold" style="min-width: 45px; text-align: right;"><?= $c['persen'] ?>%</span>
                                </div>
                                <div class="text-muted text-start" style="font-size: 10.5px;">
                                    <?= $c['total_nilai_masuk'] ?> dari <?= $c['target_nilai'] ?> nilai
                                </div>
                            </td>
                            <td>
                                <?php if ($isFull) { ?>
                                    <span class="badge bg-success-subtle text-success border border-success px-2 py-1">
                                        <i class="fa-solid fa-check me-1"></i> Penuh Terisi
                                    </span>
                                <?php } else { ?>
                                    <span class="badge bg-warning-subtle text-warning-emphasis border border-warning px-2 py-1">
                                        <i class="fa-solid fa-hourglass-half me-1"></i> Belum Lengkap
                                    </span>
                                <?php } ?>
                            </td>
                            <td>
                                <div class="btn-group btn-group-sm">
                                    <a href="grade-monitor?kelas=<?= $c['id_kelas'] ?>" class="btn btn-outline-primary" title="Pantau Detail Per Mapel">
                                        <i class="fa-solid fa-eye me-1"></i> Detail
                                    </a>
                                    <a href="bulk-print?kelas=<?= $c['id_kelas'] ?>" class="btn btn-outline-danger" title="Cetak Rapor Kelas">
                                        <i class="fa-solid fa-print"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

<?php } else if ($selectedKelasId !== null && $classDetail) {
    $cK = $classDetail['kelas'];
    $cStats = $classDetail['stats'];
    $mapelList = $classDetail['mapel_list'];
    $siswaList = $homeroomSummary['siswa_list'] ?? [];
?>
    <!-- ======================================================================= -->
    <!-- VIEW 2: DETAIL MONITORING PER KELAS (ADMIN & WALI KELAS)                -->
    <!-- ======================================================================= -->

    <!-- Top Navigation Breadcrumb / Actions -->
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
        <div>
            <?php if ($role === 'admin') { ?>
                <a href="grade-monitor" class="btn btn-outline-secondary btn-sm">
                    <i class="fa-solid fa-arrow-left me-1"></i> Kembali ke Semua Kelas
                </a>
            <?php } ?>
        </div>
        <div class="d-flex gap-2">
            <?php if ($role === 'admin') { ?>
                <!-- Dropdown Pilih Kelas Lain -->
                <div class="dropdown">
                    <button class="btn btn-outline-primary btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown">
                        <i class="fa-solid fa-arrow-right-arrow-left me-1"></i> Ganti Kelas: <strong><?= htmlspecialchars($cK['nama_kelas']) ?></strong>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end shadow" style="max-height: 320px; overflow-y: auto;">
                        <?php foreach ($allClasses as $opt) { ?>
                            <li>
                                <a class="dropdown-item <?= $opt['id_kelas'] == $selectedKelasId ? 'active' : '' ?>"
                                   href="grade-monitor?kelas=<?= $opt['id_kelas'] ?>">
                                    Kelas <?= htmlspecialchars($opt['nama_kelas']) ?> (Fase <?= $opt['tingkat'] === '10' ? 'E' : 'F' ?>)
                                </a>
                            </li>
                        <?php } ?>
                    </ul>
                </div>
            <?php } ?>

            <a href="bulk-print?kelas=<?= $selectedKelasId ?>" class="btn btn-danger btn-sm shadow-sm">
                <i class="fa-solid fa-print me-1"></i> Cetak Rapor Kelas Ini
            </a>
        </div>
    </div>

    <!-- Class Header Banner -->
    <div class="card shadow-sm border-0 mb-4 bg-primary text-white" style="background: linear-gradient(135deg, #1e3a8a, #3b82f6) !important; border-radius: 18px;">
        <div class="card-body p-4">
            <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
                <div>
                    <span class="badge bg-light text-primary fs-6 px-3 py-1 mb-2 fw-bold rounded-pill">
                        Kelas <?= htmlspecialchars($cK['nama_kelas']) ?> &bull; Tingkat <?= htmlspecialchars($cK['tingkat']) ?> (Fase <?= $cK['tingkat'] === '10' ? 'E' : 'F' ?>)
                    </span>
                    <h4 class="fw-bold mb-1">Monitoring Penilaian: Kelas <?= htmlspecialchars($cK['nama_kelas']) ?></h4>
                    <p class="mb-0 opacity-90 small">
                        Wali Kelas: <strong><?= htmlspecialchars($cK['nama_walikelas'] ?? 'Belum ditentukan') ?></strong> &bull;
                        Semester <?= htmlspecialchars($setting['semester']) ?> TA <?= htmlspecialchars($setting['tahun_ajaran']) ?>
                    </p>
                </div>
                <div class="text-end">
                    <?php if ($cStats['is_lengkap']) { ?>
                        <span class="badge bg-success fs-6 px-3 py-2 rounded-pill">
                            <i class="fa-solid fa-circle-check me-1"></i> PENUH TERISI (100%)
                        </span>
                    <?php } else { ?>
                        <span class="badge bg-warning text-dark fs-6 px-3 py-2 rounded-pill">
                            <i class="fa-solid fa-hourglass-half me-1"></i> PROGRES: <?= $cStats['persen_kelas'] ?>%
                        </span>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Stat Cards Per Kelas -->
    <div class="row g-3 mb-4">
        <div class="col-sm-6 col-xl-3">
            <div class="card shadow-sm border-0 p-3 h-100">
                <div class="d-flex align-items-center">
                    <div class="bg-primary-subtle text-primary p-3 rounded me-3 fs-4">
                        <i class="fa-solid fa-users"></i>
                    </div>
                    <div>
                        <span class="text-muted small">Jumlah Siswa</span>
                        <h4 class="fw-bold mb-0"><?= $cStats['total_siswa'] ?></h4>
                        <small class="text-muted">Peserta Didik</small>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-sm-6 col-xl-3">
            <div class="card shadow-sm border-0 p-3 h-100">
                <div class="d-flex align-items-center">
                    <div class="bg-info-subtle text-info p-3 rounded me-3 fs-4">
                        <i class="fa-solid fa-book"></i>
                    </div>
                    <div>
                        <span class="text-muted small">Total Mapel</span>
                        <h4 class="fw-bold mb-0"><?= $cStats['total_mapel'] ?></h4>
                        <small class="text-muted">Mata Pelajaran</small>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-sm-6 col-xl-3">
            <div class="card shadow-sm border-0 p-3 h-100">
                <div class="d-flex align-items-center">
                    <div class="bg-success-subtle text-success p-3 rounded me-3 fs-4">
                        <i class="fa-solid fa-circle-check"></i>
                    </div>
                    <div>
                        <span class="text-muted small">Mapel Lengkap</span>
                        <h4 class="fw-bold mb-0 text-success"><?= $cStats['mapel_lengkap'] ?></h4>
                        <small class="text-success">Sudah dinilai semua</small>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-sm-6 col-xl-3">
            <div class="card shadow-sm border-0 p-3 h-100">
                <div class="d-flex align-items-center">
                    <div class="bg-danger-subtle text-danger p-3 rounded me-3 fs-4">
                        <i class="fa-solid fa-triangle-exclamation"></i>
                    </div>
                    <div>
                        <span class="text-muted small">Mapel Belum Lengkap</span>
                        <h4 class="fw-bold mb-0 text-danger"><?= $cStats['mapel_belum'] ?></h4>
                        <small class="text-muted">Menunggu guru pengampu</small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Navigation Tabs (Per Mapel & Per Siswa) -->
    <div class="card shadow-sm border-0">
        <div class="card-header bg-white py-2">
            <ul class="nav nav-tabs card-header-tabs" id="monitorTab" role="tablist">
                <li class="nav-item">
                    <button class="nav-link active fw-bold" id="mapel-tab" data-bs-toggle="tab" data-bs-target="#tabMapel" type="button" role="tab">
                        <i class="fa-solid fa-book-open me-2 text-primary"></i>1. Monitor Per Mata Pelajaran (Status Guru)
                        <span class="badge bg-primary-subtle text-primary ms-1"><?= count($mapelList) ?></span>
                    </button>
                </li>
                <li class="nav-item">
                    <button class="nav-link fw-bold" id="siswa-tab" data-bs-toggle="tab" data-bs-target="#tabSiswa" type="button" role="tab">
                        <i class="fa-solid fa-user-graduate me-2 text-success"></i>2. Monitor Per Siswa & Cetak Rapor
                        <span class="badge bg-success-subtle text-success ms-1"><?= count($siswaList) ?></span>
                    </button>
                </li>
            </ul>
        </div>
        <div class="card-body p-0">
            <div class="tab-content" id="monitorTabContent">

                <!-- TAB 1: PER MATA PELAJARAN -->
                <div class="tab-pane fade show active p-3" id="tabMapel" role="tabpanel">
                    <div class="alert alert-light border small text-muted mb-3 py-2">
                        <i class="fa-solid fa-circle-info text-primary me-1"></i>
                        Tabel ini menampilkan status pengisian nilai per mata pelajaran beserta <strong>nama guru pengampu</strong>.
                        Mata pelajaran dinyatakan <strong>LENGKAP</strong> apabila seluruh <?= $cStats['total_siswa'] ?> siswa di kelas ini telah mendapatkan nilai akhir STS.
                    </div>

                    <div class="table-responsive">
                        <table class="table table-hover align-middle border">
                            <thead class="table-light">
                                <tr class="text-center">
                                    <th style="width: 5%">No</th>
                                    <th class="text-start">Mata Pelajaran</th>
                                    <th class="text-start">Guru Pengampu</th>
                                    <th>Target</th>
                                    <th>Dinilai</th>
                                    <th style="width: 20%">Progres Pengisian</th>
                                    <th>Rata-rata</th>
                                    <th>Status Kelengkapan</th>
                                    <th style="width: 8%">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($mapelList)) { ?>
                                    <tr><td colspan="9" class="text-center py-4 text-muted">Belum ada penugasan mapel di kelas ini.</td></tr>
                                <?php } else {
                                    $no = 1;
                                    foreach ($mapelList as $m) {
                                        $isMFull = $m['is_lengkap'];
                                ?>
                                <tr class="text-center">
                                    <td><?= $no++ ?></td>
                                    <td class="text-start">
                                        <div class="fw-bold text-dark"><?= htmlspecialchars($m['nama_mapel']) ?></div>
                                        <span class="badge bg-secondary-subtle text-secondary small"><?= htmlspecialchars($m['kategori']) ?></span>
                                    </td>
                                    <td class="text-start">
                                        <div class="fw-semibold text-primary"><?= htmlspecialchars($m['nama_guru']) ?></div>
                                        <small class="text-muted font-monospace"><?= htmlspecialchars($m['id_guru']) ?></small>
                                    </td>
                                    <td><span class="badge bg-light text-dark border"><?= $m['total_siswa'] ?></span></td>
                                    <td>
                                        <span class="badge <?= $isMFull ? 'bg-success' : 'bg-primary' ?>">
                                            <?= $m['siswa_dinilai'] ?>
                                        </span>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center gap-2">
                                            <div class="progress flex-grow-1" style="height: 8px;">
                                                <div class="progress-bar <?= $isMFull ? 'bg-success' : 'bg-warning' ?>"
                                                     role="progressbar" style="width: <?= $m['persen'] ?>%"></div>
                                            </div>
                                            <span class="small fw-bold" style="min-width: 45px; text-align: right;"><?= $m['persen'] ?>%</span>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="fw-bold fs-6 <?= $m['rata_nilai'] >= 75 ? 'text-success' : 'text-primary' ?>">
                                            <?= $m['rata_nilai'] > 0 ? $m['rata_nilai'] : '-' ?>
                                        </span>
                                    </td>
                                    <td>
                                        <?php if ($isMFull) { ?>
                                            <span class="badge bg-success-subtle text-success border border-success px-2 py-1">
                                                <i class="fa-solid fa-check me-1"></i> LENGKAP
                                            </span>
                                        <?php } else { ?>
                                            <span class="badge bg-danger-subtle text-danger border border-danger px-2 py-1"
                                                  title="Kurang <?= $m['siswa_belum'] ?> siswa belum dinilai">
                                                <i class="fa-solid fa-triangle-exclamation me-1"></i> Kurang <?= $m['siswa_belum'] ?> Siswa
                                            </span>
                                        <?php } ?>
                                    </td>
                                    <td>
                                        <a href="grade-recap?kelas=<?= $selectedKelasId ?>&id_pengampu=<?= (int)$m['id_pengampu'] ?>"
                                           class="btn btn-outline-primary btn-sm py-1 px-2"
                                           title="Input / Kelola Nilai Mapel Ini">
                                            <i class="fa-solid fa-pen-to-square me-1"></i> Nilai
                                        </a>
                                    </td>
                                </tr>
                                <?php } } ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- TAB 2: PER SISWA & CETAK RAPOR -->
                <div class="tab-pane fade p-3" id="tabSiswa" role="tabpanel">
                    <div class="d-flex flex-wrap justify-content-between align-items-center mb-3">
                        <div class="small text-muted">
                            <i class="fa-solid fa-circle-info text-primary me-1"></i>
                            Daftar seluruh siswa di kelas <strong><?= htmlspecialchars($cK['nama_kelas']) ?></strong> beserta jumlah mapel yang sudah dinilai.
                        </div>
                        <a href="bulk-print?kelas=<?= $selectedKelasId ?>" class="btn btn-success btn-sm">
                            <i class="fa-solid fa-print me-1"></i> Cetak Massal Rapor Semua Siswa
                        </a>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-hover align-middle border">
                            <thead class="table-light">
                                <tr class="text-center">
                                    <th style="width: 5%">No</th>
                                    <th>NIS</th>
                                    <th class="text-start">Nama Siswa</th>
                                    <th>Kelengkapan Nilai</th>
                                    <th>Rata-rata Nilai</th>
                                    <th style="width: 6%">S</th>
                                    <th style="width: 6%">I</th>
                                    <th style="width: 6%">A</th>
                                    <th>Status Rapor</th>
                                    <th style="width: 12%">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($siswaList)) { ?>
                                    <tr><td colspan="10" class="text-center py-4 text-muted">Belum ada siswa di kelas ini.</td></tr>
                                <?php } else {
                                    $no = 1;
                                    foreach ($siswaList as $s) {
                                        $isFullSiswa = ($s['total_mapel'] > 0 && $s['jumlah_terisi'] === $s['total_mapel']);
                                ?>
                                <tr class="text-center">
                                    <td><?= $no++ ?></td>
                                    <td class="font-monospace fw-semibold"><?= htmlspecialchars($s['nis']) ?></td>
                                    <td class="text-start fw-semibold text-dark"><?= htmlspecialchars($s['nama']) ?></td>
                                    <td>
                                        <span class="badge <?= $isFullSiswa ? 'bg-success' : 'bg-warning text-dark' ?>">
                                            <?= $s['jumlah_terisi'] ?> / <?= $s['total_mapel'] ?> Mapel
                                        </span>
                                    </td>
                                    <td class="fw-bold fs-6 text-primary">
                                        <?= $s['rata_rata_semua'] > 0 ? $s['rata_rata_semua'] : '-' ?>
                                    </td>
                                    <td><span class="badge bg-secondary"><?= (int)$s['sakit'] ?></span></td>
                                    <td><span class="badge bg-secondary"><?= (int)$s['izin'] ?></span></td>
                                    <td><span class="badge <?= (int)$s['alpa'] > 0 ? 'bg-danger' : 'bg-secondary' ?>"><?= (int)$s['alpa'] ?></span></td>
                                    <td>
                                        <?php if ($isFullSiswa) { ?>
                                            <span class="badge bg-success-subtle text-success border border-success">Siap Cetak</span>
                                        <?php } else { ?>
                                            <span class="badge bg-secondary-subtle text-secondary border">Belum Lengkap</span>
                                        <?php } ?>
                                    </td>
                                    <td>
                                        <a href="student-report.php?nis=<?= urlencode($s['nis']) ?>" target="_blank" class="btn btn-outline-primary btn-sm" title="Lihat & Cetak Rapor">
                                            <i class="fa fa-print me-1"></i> Rapor
                                        </a>
                                    </td>
                                </tr>
                                <?php } } ?>
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
        </div>
    </div>

<?php } ?>

</div>

<script>
// Filter status overview table
document.querySelectorAll('.btn-filter-status').forEach(btn => {
    btn.addEventListener('click', function() {
        document.querySelectorAll('.btn-filter-status').forEach(b => b.classList.remove('active'));
        this.classList.add('active');

        const filter = this.getAttribute('data-filter');
        const rows = document.querySelectorAll('#tableMonitorOverview tbody tr.row-kelas');

        rows.forEach(row => {
            const status = row.getAttribute('data-status');
            const tingkat = row.getAttribute('data-tingkat');

            if (filter === 'all') {
                row.style.display = '';
            } else if (filter === 'lengkap' || filter === 'belum') {
                row.style.display = (status === filter) ? '' : 'none';
            } else if (filter === 't10' || filter === 't11' || filter === 't12') {
                row.style.display = (tingkat === filter) ? '' : 'none';
            }
        });
    });
});
</script>
