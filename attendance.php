<?php
include_once "config/Database.php";
require_once "models/Kelas.php";
require_once "models/Teacher.php";
require_once "models/Setting.php";

$db = new Database();
$conn = $db->connect();

$role   = $_SESSION['role'] ?? '';
$idGuru = (string)($_SESSION['id'] ?? '');

$kelasModel   = new Kelas($conn);
$teacherModel = new Teacher($conn);
$settingModel = new Setting($conn);
$setting      = $settingModel->get();

$allClasses = $kelasModel->getAll();

// Deteksi status Guru BK
$isGuruBk = ($role === 'guru' || $role === 'walikelas') && $teacherModel->isBk($idGuru);

// Hak akses: Hanya Admin, Wali Kelas, dan Guru BK yang berhak mengelola ketidakhadiran
if ($role === 'admin') {
    $canAccess = true;
} elseif ($role === 'walikelas') {
    $canAccess = true;
} elseif ($role === 'guru' && $isGuruBk) {
    $canAccess = true;
} else {
    $canAccess = false;
}

if (!$canAccess) {
    echo "<div class='container my-4'><div class='alert alert-danger shadow-sm border-0'><i class='fa-solid fa-triangle-exclamation me-2'></i><strong>Akses Ditolak:</strong> Halaman input ketidakhadiran hanya dapat diakses oleh <strong>Guru BK</strong>, <strong>Wali Kelas</strong>, dan <strong>Administrator</strong>.</div></div>";
    return;
}

$bkClasses = $isGuruBk ? $teacherModel->getBkClasses($idGuru) : [];

// Deteksi kelas wali
$myHomeroomClass = null;
if ($role === 'walikelas') {
    if (!empty($_SESSION['id_kelas'])) {
        $myHomeroomClass = $kelasModel->getById((int)$_SESSION['id_kelas']);
    }
    if (!$myHomeroomClass && !empty($idGuru)) {
        $myHomeroomClass = $kelasModel->getByWaliKelas($idGuru);
    }
}

// Tentukan kelas yang dapat dipilih pengguna
$availableClasses = [];
if ($role === 'admin') {
    $availableClasses = $allClasses;
} elseif ($role === 'walikelas') {
    // Wali kelas selalu memiliki kelas perwaliannya, plus jika merangkap Guru BK, kelas BK-nya juga
    if ($myHomeroomClass) {
        $availableClasses[] = $myHomeroomClass;
    }
    foreach ($bkClasses as $bkC) {
        if (!$myHomeroomClass || $bkC['id_kelas'] != $myHomeroomClass['id_kelas']) {
            $availableClasses[] = $bkC;
        }
    }
    if (empty($availableClasses)) {
        $availableClasses = $allClasses;
    }
} elseif ($role === 'guru' && $isGuruBk) {
    $availableClasses = !empty($bkClasses) ? $bkClasses : $allClasses;
}

// Pemilihan kelas aktif
$selectedKelasId = (int)($_GET['kelas'] ?? 0);
if ($selectedKelasId <= 0) {
    if ($myHomeroomClass) {
        $selectedKelasId = (int)$myHomeroomClass['id_kelas'];
    } elseif (!empty($availableClasses)) {
        $selectedKelasId = (int)$availableClasses[0]['id_kelas'];
    } elseif (!empty($allClasses)) {
        $selectedKelasId = (int)$allClasses[0]['id_kelas'];
    }
}

$currentClass = $kelasModel->getById($selectedKelasId);

// Ambil daftar siswa beserta ketidakhadiran di kelas ini
$siswaList = [];
$totalSakit = 0;
$totalIzin  = 0;
$totalAlpa  = 0;
$siswaNihil = 0;

if ($currentClass) {
    $sqlSiswa = "SELECT s.nis, s.nisn, s.nama, s.id_kelas,
                        COALESCE(pr.sakit, 0) AS sakit,
                        COALESCE(pr.izin, 0) AS izin,
                        COALESCE(pr.alpa, 0) AS alpa
                 FROM tb_siswa s
                 LEFT JOIN tb_presensi_sts pr ON s.nis = pr.nis
                 WHERE s.id_kelas = ?
                 ORDER BY s.nama ASC";
    $stmt = mysqli_prepare($conn, $sqlSiswa);
    mysqli_stmt_bind_param($stmt, "i", $selectedKelasId);
    mysqli_stmt_execute($stmt);
    $res = mysqli_stmt_get_result($stmt);

    while ($r = mysqli_fetch_assoc($res)) {
        $sakit = (int)$r['sakit'];
        $izin  = (int)$r['izin'];
        $alpa  = (int)$r['alpa'];

        $totalSakit += $sakit;
        $totalIzin  += $izin;
        $totalAlpa  += $alpa;

        if ($sakit === 0 && $izin === 0 && $alpa === 0) {
            $siswaNihil++;
        }

        $r['total_absen'] = $sakit + $izin + $alpa;
        $siswaList[] = $r;
    }
}

$totalSiswa = count($siswaList);
?>

<div class="col-lg-9 mt-2">
    <!-- Header Banner -->
    <div class="card shadow-sm border-0 mb-4 bg-primary text-white" style="background: linear-gradient(135deg, #0f172a, #1e3a8a) !important; border-radius: 18px;">
        <div class="card-body p-4">
            <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
                <div>
                    <span class="badge bg-warning text-dark px-3 py-1 mb-2 fw-semibold">
                        <i class="fa-solid fa-clipboard-user me-1"></i> PENGISIAN KETIDAKHADIRAN SISWA
                    </span>
                    <h4 class="fw-bold mb-1">Catatan Ketidakhadiran (S / I / A)</h4>
                    <p class="mb-0 opacity-90 small">
                        Dikelola oleh <strong>Guru BK</strong> atau <strong>Wali Kelas</strong> &bull; Rujukan langsung tabel Ketidakhadiran pada lembar e-Rapor STS.
                    </p>
                </div>
                <div class="text-end d-flex flex-wrap gap-2">
                    <?php if ($currentClass) { ?>
                        <a href="controllers/template.php?type=presensi_kelas&id_kelas=<?= $selectedKelasId ?>" class="btn btn-outline-success btn-sm bg-white text-success fw-semibold shadow-sm">
                            <i class="fa-solid fa-file-excel me-1"></i> Download Template
                        </a>
                        <button type="button" class="btn btn-success btn-sm fw-semibold shadow-sm" data-bs-toggle="modal" data-bs-target="#ModalUploadPresensi">
                            <i class="fa-solid fa-file-arrow-up me-1"></i> Upload Excel
                        </button>
                        <a href="bulk-print-view.php?kelas=<?= $selectedKelasId ?>" target="_blank" class="btn btn-outline-light btn-sm shadow-sm">
                            <i class="fa-solid fa-print me-1"></i> Pratinjau Rapor
                        </a>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Selector Kelas -->
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-body p-3">
            <form action="index.php" method="GET" class="row g-2 align-items-center">
                <input type="hidden" name="x" value="attendance">
                <div class="col-auto">
                    <label class="fw-bold small text-muted"><i class="fa-solid fa-chalkboard me-1"></i> Pilih Kelas / Rombel:</label>
                </div>
                <div class="col-md-5">
                    <select name="kelas" class="form-select form-select-sm" onchange="this.form.submit()">
                        <?php foreach ($availableClasses as $c) { ?>
                            <option value="<?= $c['id_kelas'] ?>" <?= $selectedKelasId == $c['id_kelas'] ? 'selected' : '' ?>>
                                Kelas <?= htmlspecialchars($c['nama_kelas']) ?> (Fase <?= $c['tingkat'] === '10' ? 'E' : 'F' ?>)
                                <?php if (!empty($c['nama_walikelas'])) { ?>
                                    - Wali: <?= htmlspecialchars($c['nama_walikelas']) ?>
                                <?php } ?>
                            </option>
                        <?php } ?>
                    </select>
                </div>
                <div class="col-auto">
                    <button type="submit" class="btn btn-primary btn-sm">Tampilkan</button>
                </div>
                <?php if ($isGuruBk) { ?>
                    <div class="col-auto ms-auto">
                        <span class="badge bg-success-subtle text-success border border-success px-2 py-1 small">
                            <i class="fa-solid fa-user-check me-1"></i> Akses Guru BK Terbuka
                        </span>
                    </div>
                <?php } ?>
            </form>
        </div>
    </div>

    <?php if (!$currentClass) { ?>
        <div class="alert alert-warning">
            <i class="fa-solid fa-triangle-exclamation me-1"></i> Data kelas tidak ditemukan.
        </div>
    <?php return; } ?>

    <!-- Summary Metrik Ketidakhadiran Kelas -->
    <div class="row g-3 mb-4">
        <div class="col-sm-6 col-xl-3">
            <div class="card shadow-sm border-0 p-3 h-100">
                <div class="d-flex align-items-center">
                    <div class="bg-primary-subtle text-primary p-3 rounded me-3 fs-4">
                        <i class="fa-solid fa-users"></i>
                    </div>
                    <div>
                        <span class="text-muted small">Total Siswa</span>
                        <h4 class="fw-bold mb-0"><?= $totalSiswa ?></h4>
                        <small class="text-muted">Kelas <?= htmlspecialchars($currentClass['nama_kelas']) ?></small>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-sm-6 col-xl-3">
            <div class="card shadow-sm border-0 p-3 h-100">
                <div class="d-flex align-items-center">
                    <div class="bg-success-subtle text-success p-3 rounded me-3 fs-4">
                        <i class="fa-solid fa-user-check"></i>
                    </div>
                    <div>
                        <span class="text-muted small">Hadir Penuh (Nihil)</span>
                        <h4 class="fw-bold mb-0 text-success"><?= $siswaNihil ?> Siswa</h4>
                        <small class="text-success">0 Sakit / Izin / Alpa</small>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-sm-6 col-xl-3">
            <div class="card shadow-sm border-0 p-3 h-100">
                <div class="d-flex align-items-center">
                    <div class="bg-warning-subtle text-warning p-3 rounded me-3 fs-4">
                        <i class="fa-solid fa-head-side-cough"></i>
                    </div>
                    <div>
                        <span class="text-muted small">Total Sakit / Izin</span>
                        <h4 class="fw-bold mb-0 text-warning"><?= $totalSakit + $totalIzin ?> Hari</h4>
                        <small class="text-muted">S: <?= $totalSakit ?> &bull; I: <?= $totalIzin ?></small>
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
                        <span class="text-muted small">Tanpa Keterangan (A)</span>
                        <h4 class="fw-bold mb-0 text-danger"><?= $totalAlpa ?> Hari</h4>
                        <small class="text-danger">Perlu bimbingan BK</small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Batch Form Table -->
    <div class="card shadow-sm border-0">
        <form action="controllers/grade.php" method="POST" id="formBatchPresensi">
            <input type="hidden" name="action" value="save_presensi_batch">
            <input type="hidden" name="redirect_to" value="attendance?kelas=<?= $selectedKelasId ?>">

            <div class="card-header bg-white py-3 d-flex flex-wrap justify-content-between align-items-center gap-2">
                <div>
                    <h5 class="mb-0 fw-bold text-primary">
                        <i class="fa-solid fa-list-check me-2"></i>Data Ketidakhadiran: Kelas <?= htmlspecialchars($currentClass['nama_kelas']) ?>
                    </h5>
                    <small class="text-muted">
                        Wali Kelas: <strong><?= htmlspecialchars($currentClass['nama_walikelas'] ?? 'Belum ditentukan') ?></strong> &bull;
                        Semester <?= htmlspecialchars($setting['semester']) ?> TA <?= htmlspecialchars($setting['tahun_ajaran']) ?>
                    </small>
                </div>
                <div class="d-flex flex-wrap gap-2">
                    <a href="controllers/template.php?type=presensi_kelas&id_kelas=<?= $selectedKelasId ?>" class="btn btn-outline-success btn-sm">
                        <i class="fa-solid fa-file-excel me-1"></i> Download Template
                    </a>
                    <button type="button" class="btn btn-outline-primary btn-sm" data-bs-toggle="modal" data-bs-target="#ModalUploadPresensi">
                        <i class="fa-solid fa-file-arrow-up me-1"></i> Upload Excel
                    </button>
                    <button type="button" class="btn btn-outline-secondary btn-sm" id="btnSetSemuaNihil">
                        <i class="fa-solid fa-rotate-left me-1"></i> Isi Semua 0 (Nihil)
                    </button>
                    <button type="submit" class="btn btn-success btn-sm shadow-sm px-3 fw-bold">
                        <i class="fa-solid fa-floppy-disk me-1"></i> Simpan Semua Presensi Kelas
                    </button>
                </div>
            </div>

            <div class="card-body p-0">
                <div class="alert alert-light border-bottom rounded-0 mb-0 py-2 px-3 small text-muted">
                    <i class="fa-solid fa-circle-info text-primary me-1"></i>
                    Ubah angka <strong>Sakit (S)</strong>, <strong>Izin (I)</strong>, dan <strong>Tanpa Keterangan (A)</strong> sesuai rekap presensi. Setelah selesai, klik tombol <strong>"Simpan Semua Presensi Kelas"</strong>. Nilai ini otomatis tercetak pada lembar rapor siswa.
                </div>

                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0" id="tablePresensiSiswa">
                        <thead class="table-light">
                            <tr class="text-center">
                                <th style="width: 5%">No</th>
                                <th style="width: 14%">NIS</th>
                                <th class="text-start">Nama Siswa</th>
                                <th style="width: 13%">Sakit (Hari)</th>
                                <th style="width: 13%">Izin (Hari)</th>
                                <th style="width: 13%">Alpa (Hari)</th>
                                <th style="width: 12%">Total Tidak Hadir</th>
                                <th style="width: 10%">Rapor</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($siswaList)) { ?>
                                <tr><td colspan="8" class="text-center py-4 text-muted">Belum ada data siswa di kelas ini.</td></tr>
                            <?php } else {
                                $no = 1;
                                foreach ($siswaList as $s) {
                                    $hasAbsen = ($s['total_absen'] > 0);
                            ?>
                            <tr class="text-center <?= $hasAbsen ? 'table-warning-subtle' : '' ?>">
                                <td><?= $no++ ?></td>
                                <td class="font-monospace fw-semibold"><?= htmlspecialchars($s['nis']) ?></td>
                                <td class="text-start fw-semibold text-dark">
                                    <?= htmlspecialchars($s['nama']) ?>
                                    <?php if ($s['alpa'] > 0) { ?>
                                        <span class="badge bg-danger-subtle text-danger border border-danger ms-1 small">Alpa <?= $s['alpa'] ?></span>
                                    <?php } ?>
                                </td>
                                <td>
                                    <input type="number" min="0" max="180" class="form-control form-control-sm text-center fw-bold input-sakit"
                                           name="presensi[<?= $s['nis'] ?>][sakit]" value="<?= (int)$s['sakit'] ?>" required>
                                </td>
                                <td>
                                    <input type="number" min="0" max="180" class="form-control form-control-sm text-center fw-bold input-izin"
                                           name="presensi[<?= $s['nis'] ?>][izin]" value="<?= (int)$s['izin'] ?>" required>
                                </td>
                                <td>
                                    <input type="number" min="0" max="180" class="form-control form-control-sm text-center fw-bold input-alpa <?= $s['alpa'] > 0 ? 'text-danger border-danger' : '' ?>"
                                           name="presensi[<?= $s['nis'] ?>][alpa]" value="<?= (int)$s['alpa'] ?>" required>
                                </td>
                                <td>
                                    <span class="badge <?= $hasAbsen ? 'bg-warning text-dark' : 'bg-light text-secondary border' ?> px-2 py-1">
                                        <?= $s['total_absen'] ?> Hari
                                    </span>
                                </td>
                                <td>
                                    <a href="student-report.php?nis=<?= urlencode($s['nis']) ?>" target="_blank" class="btn btn-outline-primary btn-sm" title="Lihat Lembar Rapor">
                                        <i class="fa-solid fa-print"></i>
                                    </a>
                                </td>
                            </tr>
                            <?php } } ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="card-footer bg-white py-3 d-flex justify-content-between align-items-center">
                <span class="text-muted small">
                    Total Siswa: <strong><?= $totalSiswa ?></strong> &bull; Perubahan langsung tersimpan ke database e-Raport STS.
                </span>
                <button type="submit" class="btn btn-success px-4 fw-bold shadow-sm">
                    <i class="fa-solid fa-floppy-disk me-1"></i> Simpan Semua Presensi Kelas
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Upload Excel Presensi -->
<div class="modal fade" id="ModalUploadPresensi" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="controllers/grade.php" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="action" value="upload_excel_presensi">
                <input type="hidden" name="id_kelas" value="<?= $selectedKelasId ?>">
                <input type="hidden" name="redirect_to" value="attendance?kelas=<?= $selectedKelasId ?>">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="fa-solid fa-file-arrow-up text-primary me-2"></i>Upload Ketidakhadiran (Excel / CSV)</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3 p-3 bg-light rounded border d-flex justify-content-between align-items-center">
                        <div>
                            <span class="fw-semibold d-block text-dark small"><i class="fa-solid fa-file-excel text-success me-1"></i> Template Excel Presensi</span>
                            <small class="text-muted">Kelas <?= htmlspecialchars($currentClass['nama_kelas']) ?></small>
                        </div>
                        <a href="controllers/template.php?type=presensi_kelas&id_kelas=<?= $selectedKelasId ?>" class="btn btn-outline-success btn-sm">
                            <i class="fa-solid fa-download me-1"></i> Download Template
                        </a>
                    </div>
                    <div class="alert alert-info py-2 small">
                        Pastikan menggunakan format template resmi (kolom: <code>nis</code>, <code>nama_siswa</code>, <code>sakit</code>, <code>izin</code>, <code>alpa</code>). Kolom NIS dan Nama Siswa jangan diubah.
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Pilih File (.xlsx atau .csv)</label>
                        <input type="file" class="form-control" name="excel_file" accept=".xlsx, .csv" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-success">Upload & Simpan Presensi</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
// Helper tombol "Isi Semua 0 (Nihil)"
document.getElementById('btnSetSemuaNihil')?.addEventListener('click', function() {
    if (confirm('Atur seluruh kolom Sakit, Izin, dan Alpa kelas ini menjadi 0 (Nihil)?')) {
        document.querySelectorAll('#tablePresensiSiswa input[type="number"]').forEach(input => {
            input.value = 0;
        });
    }
});
</script>
