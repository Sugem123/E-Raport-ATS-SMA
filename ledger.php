<?php
include_once "config/Database.php";
require_once "models/Ledger.php";
require_once "models/Kelas.php";

$db = new Database();
$conn = $db->connect();

$ledgerModel = new Ledger($conn);
$kelasModel  = new Kelas($conn);

$role   = $_SESSION['role'] ?? '';
$idGuru = $_SESSION['id'] ?? '';

if (!in_array($role, ['admin', 'walikelas'])) {
    echo "<div class='alert alert-danger'>Akses ditolak.</div>";
    return;
}

$allClasses = $kelasModel->getAll();

// Wali Kelas: otomatis kunci pada kelas perwaliannya
$myClass = null;
if ($role === 'walikelas') {
    if (!empty($_SESSION['id_kelas'])) {
        $myClass = $kelasModel->getById((int)$_SESSION['id_kelas']);
    }
    if (!$myClass && !empty($idGuru)) {
        $myClass = $kelasModel->getByWaliKelas($idGuru);
    }
}

$selectedKelasId = null;
if ($role === 'walikelas' && $myClass) {
    $selectedKelasId = (int)$myClass['id_kelas'];
} elseif ($role === 'admin') {
    $selectedKelasId = isset($_GET['kelas']) && $_GET['kelas'] !== '' ? (int)$_GET['kelas'] : null;
    if (!$selectedKelasId && !empty($allClasses)) {
        $selectedKelasId = (int)$allClasses[0]['id_kelas'];
    }
}

$ledgerData = null;
$stats = null;
if ($selectedKelasId) {
    $ledgerData = $ledgerModel->getLedgerData($selectedKelasId);
    if ($ledgerData) {
        $stats = $ledgerModel->calculateStats($ledgerData);
    }
}
?>

<div class="col-lg-9 mt-2">
    <!-- Header Banner -->
    <div class="card shadow-sm border-0 mb-4 text-white" style="background: linear-gradient(135deg, #1e3a8a, #0f172a) !important; border-radius: 18px;">
        <div class="card-body p-4">
            <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
                <div>
                    <span class="badge bg-warning text-dark px-3 py-1 mb-2 fw-semibold">
                        <i class="fa-solid fa-table me-1"></i> BUKU LEGER NILAI STS
                    </span>
                    <h4 class="fw-bold mb-1">Ledger Nilai Hasil Belajar Tengah Semester</h4>
                    <p class="mb-0 opacity-90 small">
                        Rekapitulasi nilai seluruh peserta didik per rombel kelas &bull; Tersedia format Ringkas (Nilai ATS) &amp; Lengkap (Sumatif 1&ndash;4 &amp; ATS).
                    </p>
                </div>
                <?php if ($ledgerData) { ?>
                    <div class="d-flex flex-wrap gap-2">
                        <a href="ledger-print.php?kelas=<?= $selectedKelasId ?>" target="_blank" class="btn btn-warning fw-bold btn-sm shadow-sm px-3">
                            <i class="fa-solid fa-print me-1"></i> Cetak PDF (Ringkas)
                        </a>
                        <a href="controllers/ledger.php?action=excel&type=ringkas&kelas=<?= $selectedKelasId ?>" class="btn btn-outline-success btn-sm bg-white text-success fw-bold">
                            <i class="fa-solid fa-file-excel me-1"></i> Excel Ringkas
                        </a>
                        <a href="controllers/ledger.php?action=excel&type=lengkap&kelas=<?= $selectedKelasId ?>" class="btn btn-outline-info btn-sm bg-white text-primary fw-bold">
                            <i class="fa-solid fa-file-lines me-1"></i> Excel Lengkap
                        </a>
                    </div>
                <?php } ?>
            </div>
        </div>
    </div>

    <!-- Selector Kelas (Khusus Admin) -->
    <?php if ($role === 'admin') { ?>
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-body p-3">
                <form action="index.php" method="GET" class="row g-2 align-items-center">
                    <input type="hidden" name="x" value="ledger">
                    <div class="col-auto">
                        <label class="fw-bold small text-muted"><i class="fa-solid fa-chalkboard me-1"></i> Pilih Kelas / Rombel:</label>
                    </div>
                    <div class="col-md-5">
                        <select name="kelas" class="form-select form-select-sm" onchange="this.form.submit()">
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

    <?php if (!$ledgerData) { ?>
        <div class="alert alert-warning">
            <i class="fa-solid fa-triangle-exclamation me-1"></i> Data kelas tidak ditemukan.
        </div>
    <?php return; } ?>

    <?php
    $kelas        = $ledgerData['kelas'];
    $setting      = $ledgerData['setting'];
    $mapelList    = $ledgerData['mapel_list'];
    $mapelUmum    = $ledgerData['mapel_umum'];
    $mapelPilihan = $ledgerData['mapel_pilihan'];
    $siswaList    = $ledgerData['siswa_list'];
    $grades       = $ledgerData['grades'];

    $studentStats   = $stats['student_stats'] ?? [];
    $subjectSummary = $stats['subject_summary'] ?? [];
    ?>

    <!-- Status Kelas Info Card -->
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-body p-3 d-flex flex-wrap justify-content-between align-items-center gap-3">
            <div>
                <h5 class="fw-bold text-primary mb-1">
                    <i class="fa-solid fa-chalkboard-user me-2"></i>Kelas <?= htmlspecialchars($kelas['nama_kelas']) ?>
                </h5>
                <span class="small text-muted">
                    Wali Kelas: <strong><?= htmlspecialchars($kelas['nama_walikelas'] ?? '-') ?></strong> &bull;
                    Tingkat <?= htmlspecialchars($kelas['tingkat']) ?> (Fase <?= $kelas['tingkat'] === '10' ? 'E' : 'F' ?>) &bull;
                    Total: <strong><?= count($siswaList) ?> Siswa</strong> &bull;
                    <strong><?= count($mapelList) ?> Mapel</strong> (<?= count($mapelUmum) ?> Umum, <?= count($mapelPilihan) ?> Pilihan)
                </span>
            </div>
            <!-- Toggle Tabs Ringkas & Lengkap -->
            <ul class="nav nav-pills" id="pillsLedger" role="tablist">
                <li class="nav-item">
                    <button class="nav-link active btn-sm fw-bold" id="tab-ringkas-btn" data-bs-toggle="pill" data-bs-target="#tab-ringkas" type="button" role="tab">
                        <i class="fa-solid fa-table me-1"></i> Ringkas
                    </button>
                </li>
                <li class="nav-item">
                    <button class="nav-link btn-sm fw-bold ms-2" id="tab-lengkap-btn" data-bs-toggle="pill" data-bs-target="#tab-lengkap" type="button" role="tab">
                        <i class="fa-solid fa-table-cells me-1"></i> Lengkap
                    </button>
                </li>
            </ul>
        </div>
    </div>

    <!-- Style Sticky Columns & Grid -->
    <style>
        .ledger-container {
            max-height: 65vh;
            overflow: auto;
            position: relative;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
        }
        .table-ledger-screen {
            font-size: 11.5px;
            white-space: nowrap;
            margin-bottom: 0;
        }
        .table-ledger-screen thead th {
            position: sticky;
            top: 0;
            z-index: 10;
            background-color: #f1f5f9;
            box-shadow: inset 0 -1px 0 #cbd5e1;
        }
        .table-ledger-screen thead tr:nth-child(2) th {
            top: 34px;
        }
        .table-ledger-screen th, .table-ledger-screen td {
            padding: 5px 8px;
        }
        .sticky-col-1 {
            position: sticky;
            left: 0;
            z-index: 8;
            background-color: #ffffff;
            box-shadow: inset -1px 0 0 #cbd5e1;
        }
        .sticky-col-2 {
            position: sticky;
            left: 36px;
            z-index: 8;
            background-color: #ffffff;
            box-shadow: inset -1px 0 0 #cbd5e1;
        }
        .sticky-col-3 {
            position: sticky;
            left: 110px;
            z-index: 8;
            background-color: #ffffff;
            box-shadow: inset -1px 0 0 #cbd5e1;
        }
        .sticky-col-4 {
            position: sticky;
            left: 195px;
            z-index: 8;
            background-color: #ffffff;
            box-shadow: inset -2px 0 0 #94a3b8;
        }
        thead th.sticky-col-1, thead th.sticky-col-2, thead th.sticky-col-3, thead th.sticky-col-4 {
            z-index: 12 !important;
            background-color: #f1f5f9 !important;
        }
    </style>

    <div class="tab-content" id="pillsLedgerContent">
        <!-- =================================================================== -->
        <!-- TAB 1: OPSI A - LEDGER RINGKAS (NILAI ATS SAJA)                     -->
        <!-- =================================================================== -->
        <div class="tab-pane fade show active" id="tab-ringkas" role="tabpanel">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white py-2 d-flex justify-content-between align-items-center">
                    <span class="small fw-semibold text-secondary">
                        <i class="fa-solid fa-circle-info text-primary me-1"></i>
                        Menampilkan 1 nilai utama (Nilai ATS) per mapel &bull; Urutan mapel No. 1 dari kiri ke kanan.
                    </span>
                    <a href="ledger-print.php?kelas=<?= $selectedKelasId ?>" target="_blank" class="btn btn-warning btn-sm fw-bold">
                        <i class="fa-solid fa-print me-1"></i> Buka Lembar Cetak PDF
                    </a>
                </div>
                <div class="card-body p-0">
                    <div class="ledger-container">
                        <table class="table table-hover align-middle table-ledger-screen mb-0">
                            <thead>
                                <tr class="text-center">
                                    <th rowspan="2" class="sticky-col-1" style="width: 36px;">No</th>
                                    <th rowspan="2" class="sticky-col-2" style="width: 74px;">NIS</th>
                                    <th rowspan="2" class="sticky-col-3" style="width: 85px;">NISN</th>
                                    <th rowspan="2" class="sticky-col-4 text-start" style="width: 220px;">Nama Peserta Didik</th>

                                    <?php if (!empty($mapelUmum)) { ?>
                                        <th colspan="<?= count($mapelUmum) ?>" class="bg-primary-subtle text-primary border-start border-end border-primary">
                                            Kelompok Umum (<?= count($mapelUmum) ?> Mapel)
                                        </th>
                                    <?php } ?>

                                    <?php if (!empty($mapelPilihan)) { ?>
                                        <th colspan="<?= count($mapelPilihan) ?>" class="bg-success-subtle text-success border-start border-end border-success">
                                            Kelompok Pilihan (<?= count($mapelPilihan) ?> Mapel)
                                        </th>
                                    <?php } ?>

                                    <th rowspan="2" class="bg-warning-subtle text-dark" style="width: 60px;">Total</th>
                                    <th rowspan="2" class="bg-warning-subtle text-dark" style="width: 60px;">Rata-rata</th>
                                    <th colspan="3" class="bg-light" style="width: 80px;">Ketidakhadiran</th>
                                </tr>
                                <tr class="text-center">
                                    <!-- Kelompok Umum Header Mapel -->
                                    <?php
                                    $noU = 1;
                                    foreach ($mapelUmum as $m) {
                                    ?>
                                        <th class="bg-light" title="<?= htmlspecialchars($m['nama_mapel']) ?>">
                                            <div style="font-size: 9px; opacity: 0.75;"><?= $noU++ ?></div>
                                            <span class="font-monospace fw-bold"><?= htmlspecialchars($m['id_mapel']) ?></span>
                                        </th>
                                    <?php } ?>

                                    <!-- Kelompok Pilihan Header Mapel -->
                                    <?php
                                    $noP = 1;
                                    foreach ($mapelPilihan as $m) {
                                    ?>
                                        <th class="bg-light" title="<?= htmlspecialchars($m['nama_mapel']) ?>">
                                            <div style="font-size: 9px; opacity: 0.75;"><?= $noP++ ?></div>
                                            <span class="font-monospace fw-bold text-success"><?= htmlspecialchars($m['id_mapel']) ?></span>
                                        </th>
                                    <?php } ?>

                                    <th style="width: 26px;">S</th>
                                    <th style="width: 26px;">I</th>
                                    <th style="width: 26px;">A</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $no = 1;
                                foreach ($siswaList as $s) {
                                    $nis = $s['nis'];
                                    $st  = $studentStats[$nis] ?? ['total_ats' => 0, 'avg_ats' => 0];
                                ?>
                                    <tr class="text-center">
                                        <td class="sticky-col-1"><?= $no++ ?></td>
                                        <td class="sticky-col-2 font-monospace"><?= htmlspecialchars($s['nis']) ?></td>
                                        <td class="sticky-col-3 font-monospace small text-muted"><?= htmlspecialchars($s['nisn'] ?? '-') ?></td>
                                        <td class="sticky-col-4 text-start fw-semibold text-dark"><?= htmlspecialchars($s['nama']) ?></td>

                                        <!-- Nilai Kelompok Umum -->
                                        <?php foreach ($mapelUmum as $m) {
                                            $idP = (int)$m['id_pengampu'];
                                            $val = $grades[$nis][$idP]['ats'] ?? null;
                                        ?>
                                            <td><?= ($val !== null && $val !== '') ? (float)$val : '-' ?></td>
                                        <?php } ?>

                                        <!-- Nilai Kelompok Pilihan -->
                                        <?php foreach ($mapelPilihan as $m) {
                                            $idP = (int)$m['id_pengampu'];
                                            $val = $grades[$nis][$idP]['ats'] ?? null;
                                        ?>
                                            <td><?= ($val !== null && $val !== '') ? (float)$val : '-' ?></td>
                                        <?php } ?>

                                        <!-- Total & Rata-rata -->
                                        <td class="fw-bold"><?= $st['total_ats'] > 0 ? (float)$st['total_ats'] : '-' ?></td>
                                        <td class="fw-bold text-primary"><?= $st['avg_ats'] > 0 ? (float)$st['avg_ats'] : '-' ?></td>

                                        <!-- Absensi -->
                                        <td><?= (int)$s['sakit'] ?></td>
                                        <td><?= (int)$s['izin'] ?></td>
                                        <td class="<?= (int)$s['alpa'] > 0 ? 'fw-bold text-danger' : '' ?>"><?= (int)$s['alpa'] ?></td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- =================================================================== -->
        <!-- TAB 2: OPSI B - LEDGER LENGKAP (SUMATIF 1-4 & ATS)                   -->
        <!-- =================================================================== -->
        <div class="tab-pane fade" id="tab-lengkap" role="tabpanel">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white py-2 d-flex justify-content-between align-items-center">
                    <span class="small fw-semibold text-secondary">
                        <i class="fa-solid fa-circle-info text-primary me-1"></i>
                        Menampilkan seluruh rincian Sumatif 01, 02, 03, 04, dan Nilai ATS untuk setiap mapel.
                    </span>
                    <a href="controllers/ledger.php?action=excel&type=lengkap&kelas=<?= $selectedKelasId ?>" class="btn btn-outline-info btn-sm text-primary fw-bold">
                        <i class="fa-solid fa-file-excel me-1"></i> Download Excel Lengkap
                    </a>
                </div>
                <div class="card-body p-0">
                    <div class="ledger-container">
                        <table class="table table-hover align-middle table-ledger-screen mb-0">
                            <thead>
                                <tr class="text-center">
                                    <th rowspan="2" class="sticky-col-1" style="width: 36px;">No</th>
                                    <th rowspan="2" class="sticky-col-2" style="width: 74px;">NIS</th>
                                    <th rowspan="2" class="sticky-col-3" style="width: 85px;">NISN</th>
                                    <th rowspan="2" class="sticky-col-4 text-start" style="width: 220px;">Nama Peserta Didik</th>

                                    <?php foreach ($mapelList as $m) {
                                        $isUmum = (strtolower(trim((string)$m['kategori'])) === 'umum');
                                    ?>
                                        <th colspan="5" class="<?= $isUmum ? 'bg-primary-subtle text-primary border-start border-end border-primary' : 'bg-success-subtle text-success border-start border-end border-success' ?>">
                                            <div style="font-size: 11px;" class="fw-bold"><?= htmlspecialchars($m['nama_mapel']) ?></div>
                                            <small class="font-monospace text-muted"><?= htmlspecialchars($m['id_mapel']) ?> &bull; <?= htmlspecialchars($m['kategori']) ?></small>
                                        </th>
                                    <?php } ?>

                                    <th colspan="3" class="bg-light" style="width: 80px;">Ketidakhadiran</th>
                                    <th rowspan="2" class="bg-warning-subtle text-dark" style="width: 60px;">Total ATS</th>
                                    <th rowspan="2" class="bg-warning-subtle text-dark" style="width: 60px;">Rata ATS</th>
                                </tr>
                                <tr class="text-center">
                                    <?php foreach ($mapelList as $m) { ?>
                                        <th style="min-width: 32px; font-size: 10px;">01</th>
                                        <th style="min-width: 32px; font-size: 10px;">02</th>
                                        <th style="min-width: 32px; font-size: 10px;">03</th>
                                        <th style="min-width: 32px; font-size: 10px;">04</th>
                                        <th style="min-width: 36px; font-size: 10px;" class="fw-bold text-primary">ATS</th>
                                    <?php } ?>
                                    <th style="width: 26px;">S</th>
                                    <th style="width: 26px;">I</th>
                                    <th style="width: 26px;">A</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $no = 1;
                                foreach ($siswaList as $s) {
                                    $nis = $s['nis'];
                                    $st  = $studentStats[$nis] ?? ['total_ats' => 0, 'avg_ats' => 0];
                                ?>
                                    <tr class="text-center">
                                        <td class="sticky-col-1"><?= $no++ ?></td>
                                        <td class="sticky-col-2 font-monospace"><?= htmlspecialchars($s['nis']) ?></td>
                                        <td class="sticky-col-3 font-monospace small text-muted"><?= htmlspecialchars($s['nisn'] ?? '-') ?></td>
                                        <td class="sticky-col-4 text-start fw-semibold text-dark"><?= htmlspecialchars($s['nama']) ?></td>

                                        <?php foreach ($mapelList as $m) {
                                            $idP = (int)$m['id_pengampu'];
                                            $g = $grades[$nis][$idP] ?? null;
                                        ?>
                                            <td><?= ($g && $g['s1'] !== null) ? (float)$g['s1'] : '-' ?></td>
                                            <td><?= ($g && $g['s2'] !== null) ? (float)$g['s2'] : '-' ?></td>
                                            <td><?= ($g && $g['s3'] !== null) ? (float)$g['s3'] : '-' ?></td>
                                            <td><?= ($g && $g['s4'] !== null) ? (float)$g['s4'] : '-' ?></td>
                                            <td class="fw-bold text-primary"><?= ($g && $g['ats'] !== null) ? (float)$g['ats'] : '-' ?></td>
                                        <?php } ?>

                                        <td><?= (int)$s['sakit'] ?></td>
                                        <td><?= (int)$s['izin'] ?></td>
                                        <td class="<?= (int)$s['alpa'] > 0 ? 'fw-bold text-danger' : '' ?>"><?= (int)$s['alpa'] ?></td>

                                        <td class="fw-bold"><?= $st['total_ats'] > 0 ? (float)$st['total_ats'] : '-' ?></td>
                                        <td class="fw-bold text-primary"><?= $st['avg_ats'] > 0 ? (float)$st['avg_ats'] : '-' ?></td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
