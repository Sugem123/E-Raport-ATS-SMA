<?php
include_once "config/Database.php";
require_once "models/Teacher.php";
require_once "models/Bobot.php";

$db = new Database();
$conn = $db->connect();

$teacherModel = new Teacher($conn);
$bobotModel   = new Bobot($conn);

$role   = $_SESSION['role'] ?? '';
$idGuru = (string)($_SESSION['id'] ?? '');

$selectedPengampuId = isset($_GET['id_pengampu']) ? (int)$_GET['id_pengampu'] : 0;
$selectedKelasId    = isset($_GET['kelas']) ? (int)$_GET['kelas'] : 0;

$allClasses = [];
if ($role === 'admin') {
    require_once "models/Kelas.php";
    $kelasModel = new Kelas($conn);
    $allClasses = $kelasModel->getAll();

    // Jika id_pengampu disertakan, cari kelas miliknya
    if ($selectedPengampuId > 0) {
        $qCekP = mysqli_query($conn, "SELECT id_kelas FROM tb_pengampu WHERE id_pengampu = $selectedPengampuId");
        if ($rCekP = mysqli_fetch_assoc($qCekP)) {
            $selectedKelasId = (int)$rCekP['id_kelas'];
        }
    }

    if ($selectedKelasId === 0 && !empty($allClasses)) {
        $selectedKelasId = (int)$allClasses[0]['id_kelas'];
    }

    // Ambil penugasan di kelas ini (hanya mapel yang masuk dalam mapping jenjang & bukan BK)
    $qMapelKelas = mysqli_query($conn, "
        SELECT p.id_pengampu, p.id_guru, g.nama_guru, m.id_mapel, m.nama_mapel, k.id_kelas, k.nama_kelas, k.tingkat
        FROM tb_pengampu p
        INNER JOIN tb_mapel_referensi m ON p.id_mapel = m.id_mapel
        INNER JOIN tb_kelas k ON p.id_kelas = k.id_kelas
        INNER JOIN tb_guru g ON p.id_guru = g.id_guru
        INNER JOIN tb_mapel_mapping mp ON (mp.id_mapel = m.id_mapel AND mp.jenjang = k.tingkat)
        WHERE p.id_kelas = $selectedKelasId
          AND m.id_mapel != 'BDKB'
          AND m.nama_mapel NOT LIKE '%Konseling%'
          AND m.nama_mapel NOT LIKE '%Bimbingan%'
        ORDER BY
            CASE WHEN mp.kategori = 'Umum' THEN 1 ELSE 2 END ASC,
            mp.urutan ASC,
            m.nama_mapel ASC
    ");
    $myAssignments = [];
    while ($rowM = mysqli_fetch_assoc($qMapelKelas)) {
        $myAssignments[] = $rowM;
    }

    // Pastikan selectedPengampuId ada dalam daftar myAssignments kelas ini
    $foundP = false;
    foreach ($myAssignments as $a) {
        if ((int)$a['id_pengampu'] === $selectedPengampuId) {
            $foundP = true;
            break;
        }
    }
    if (!$foundP && !empty($myAssignments)) {
        $selectedPengampuId = (int)$myAssignments[0]['id_pengampu'];
    }
} else {
    $myAssignments = $teacherModel->getPengampu($idGuru);
    if ($selectedPengampuId === 0 && !empty($myAssignments)) {
        $selectedPengampuId = (int)$myAssignments[0]['id_pengampu'];
    }
}

$currentAssignment = null;
foreach ($myAssignments as $a) {
    if ((int)$a['id_pengampu'] === $selectedPengampuId) {
        $currentAssignment = $a;
        break;
    }
}

$isOnlyBk = ($role === 'guru') && $teacherModel->isBk($idGuru) && empty($myAssignments);

$students = [];
if ($currentAssignment) {
    $students = $teacherModel->getStudentsForGrade($selectedPengampuId);
}

$bobot = $bobotModel->get();
?>

<div class="col-lg-9 mt-2">
    <div class="card shadow-sm border-0">
        <div class="card-header bg-white py-3 d-flex flex-wrap justify-content-between align-items-center gap-2">
            <h5 class="mb-0 fw-bold text-primary">
                <i class="fa-solid fa-pen-to-square me-2"></i>Penilaian Sumatif Tengah Semester (STS)
                <?php if ($role === 'admin') { ?>
                    <span class="badge bg-danger-subtle text-danger border border-danger ms-2 fs-6">Mode Administrator</span>
                <?php } ?>
            </h5>
            <?php if ($role === 'admin') { ?>
                <a href="grade-monitor<?= $selectedKelasId > 0 ? '?kelas=' . $selectedKelasId : '' ?>" class="btn btn-outline-secondary btn-sm">
                    <i class="fa-solid fa-chart-pie me-1"></i> Buka Monitor Kelas
                </a>
            <?php } ?>
        </div>
        <div class="card-body">
            <?php if ($isOnlyBk) { ?>
                <div class="alert alert-info border-0 shadow-sm p-4 text-center my-3">
                    <i class="fa-solid fa-user-shield fa-3x text-primary mb-3"></i>
                    <h5 class="fw-bold">Akun Guru Bimbingan &amp; Konseling (BK)</h5>
                    <p class="text-muted mb-3">
                        Sebagai <strong>Guru BK</strong>, Anda tidak menginputkan penilaian angka/kognitif (Sumatif &amp; STS).
                        Tugas Anda adalah mengelola <strong>Catatan Ketidakhadiran Siswa (Sakit, Izin, Alpa)</strong> untuk rombel binaan.
                    </p>
                    <a href="attendance" class="btn btn-warning fw-bold px-4 py-2">
                        <i class="fa-solid fa-clipboard-user me-2"></i> Buka Menu Ketidakhadiran Siswa (BK)
                    </a>
                </div>
            <?php } else { ?>
            <!-- Pilihan Kelas & Mapel yang Diampu -->
            <div class="row align-items-center mb-4 bg-light p-3 rounded mx-1 g-2">
                <?php if ($role === 'admin') { ?>
                    <div class="col-12 col-md-3">
                        <label class="form-label small fw-bold text-muted text-uppercase mb-1">
                            <i class="fa-solid fa-chalkboard me-1 text-primary"></i>Pilih Rombel / Kelas
                        </label>
                        <select class="form-select form-select-sm fw-bold border-primary" onchange="location.href='grade-recap?kelas=' + this.value;">
                            <?php foreach ($allClasses as $c) { ?>
                                <option value="<?= $c['id_kelas'] ?>" <?= (int)$c['id_kelas'] === $selectedKelasId ? 'selected' : '' ?>>
                                    Kelas <?= htmlspecialchars($c['nama_kelas']) ?> (Tingkat <?= $c['tingkat'] ?>)
                                </option>
                            <?php } ?>
                        </select>
                    </div>
                    <div class="col-12 col-md-5">
                        <label class="form-label small fw-bold text-muted text-uppercase mb-1">
                            <i class="fa-solid fa-book-open me-1 text-primary"></i>Pilih Mata Pelajaran
                        </label>
                        <select class="form-select form-select-sm fw-semibold" onchange="location.href='grade-recap?kelas=<?= $selectedKelasId ?>&id_pengampu=' + this.value;">
                            <?php if (empty($myAssignments)) { ?>
                                <option value="">(Belum ada mapel di kelas ini)</option>
                            <?php } else {
                                foreach ($myAssignments as $a) { ?>
                                <option value="<?= $a['id_pengampu'] ?>" <?= (int)$a['id_pengampu'] === $selectedPengampuId ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($a['nama_mapel']) ?> &bull; Guru: <?= htmlspecialchars($a['nama_guru']) ?>
                                </option>
                            <?php } } ?>
                        </select>
                    </div>
                    <div class="col-12 col-md-4 d-flex justify-content-md-end gap-2 mt-2 mt-md-0">
                        <?php if ($currentAssignment) { ?>
                            <a href="controllers/template.php?type=nilai_kelas&id_pengampu=<?= $selectedPengampuId ?>" class="btn btn-outline-success btn-sm">
                                <i class="fa-solid fa-file-excel me-1"></i> Template
                            </a>
                            <button type="button" class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#ModalUploadNilai">
                                <i class="fa-solid fa-upload me-1"></i> Upload Nilai
                            </button>
                        <?php } ?>
                    </div>
                <?php } else { ?>
                    <!-- Tampilan Guru -->
                    <div class="col-md-7">
                        <label class="form-label small fw-bold text-muted text-uppercase">Pilih Kelas & Mata Pelajaran Diampu</label>
                        <div class="dropdown">
                            <button class="btn btn-outline-primary dropdown-toggle w-100 text-start d-flex justify-content-between align-items-center" type="button" data-bs-toggle="dropdown">
                                <span>
                                    <?php if ($currentAssignment) { ?>
                                        <strong><?= htmlspecialchars($currentAssignment['nama_mapel']) ?></strong> &mdash; Kelas <?= htmlspecialchars($currentAssignment['nama_kelas']) ?> (Tingkat <?= $currentAssignment['tingkat'] ?>)
                                    <?php } else { ?>
                                        Belum ada penugasan mengajar
                                    <?php } ?>
                                </span>
                            </button>
                            <ul class="dropdown-menu w-100 shadow">
                                <?php foreach ($myAssignments as $a) { ?>
                                    <li>
                                        <a class="dropdown-item py-2 <?= (int)$a['id_pengampu'] === $selectedPengampuId ? 'active' : '' ?>" href="grade-recap?id_pengampu=<?= $a['id_pengampu'] ?>">
                                            <i class="fa-solid fa-chalkboard me-2"></i>
                                            <strong><?= htmlspecialchars($a['nama_mapel']) ?></strong> &mdash; Kelas <?= htmlspecialchars($a['nama_kelas']) ?>
                                        </a>
                                    </li>
                                <?php } ?>
                            </ul>
                        </div>
                    </div>
                    <div class="col-md-5 mt-3 mt-md-0 d-flex justify-content-md-end gap-2">
                        <?php if ($currentAssignment) { ?>
                            <a href="controllers/template.php?type=nilai_kelas&id_pengampu=<?= $selectedPengampuId ?>" class="btn btn-outline-success btn-sm">
                                <i class="fa-solid fa-file-excel me-1"></i> Download Template Excel
                            </a>
                            <button type="button" class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#ModalUploadNilai">
                                <i class="fa-solid fa-upload me-1"></i> Upload Excel Nilai
                            </button>
                        <?php } ?>
                    </div>
                <?php } ?>
            </div>

            <?php if (!$currentAssignment) { ?>
                <div class="alert alert-warning">
                    <i class="fa-solid fa-triangle-exclamation me-1"></i> <?= $role === 'admin' ? 'Belum ada mata pelajaran yang ditugaskan pada kelas ini.' : 'Anda belum memiliki penugasan mengajar mata pelajaran di kelas manapun. Silakan hubungi Administrator.' ?>
                </div>
            <?php } else { ?>

                <!-- Info Header Nilai -->
                <div class="d-flex flex-wrap justify-content-between align-items-center mb-3 gap-2">
                    <h6 class="fw-bold mb-0 text-dark"><i class="fa-solid fa-list-ol me-1 text-primary"></i> Daftar Nilai Siswa (<?= count($students) ?> Siswa)</h6>
                    <small class="text-muted">
                        Mata Pelajaran: <strong><?= htmlspecialchars($currentAssignment['nama_mapel']) ?></strong> &bull; Kelas: <strong><?= htmlspecialchars($currentAssignment['nama_kelas']) ?></strong>
                        <?php if ($role === 'admin' && !empty($currentAssignment['nama_guru'])) { ?>
                            &bull; Guru: <strong class="text-primary"><?= htmlspecialchars($currentAssignment['nama_guru']) ?></strong> (<?= htmlspecialchars($currentAssignment['id_guru']) ?>)
                        <?php } ?>
                    </small>
                </div>

                <!-- Tabel Nilai Siswa -->
                <div class="table-responsive">
                    <table class="table table-hover align-middle border">
                        <thead class="table-light">
                            <tr class="text-center">
                                <th style="width: 5%">No</th>
                                <th style="width: 12%">NIS</th>
                                <th class="text-start">Nama Siswa</th>
                                <th style="width: 10%">Sumatif 1</th>
                                <th style="width: 10%">Sumatif 2</th>
                                <th style="width: 10%">Sumatif 3</th>
                                <th style="width: 10%">Sumatif 4</th>
                                <th style="width: 12%">Nilai ATS</th>
                                <th style="width: 8%">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($students)) { ?>
                                <tr><td colspan="9" class="text-center py-4 text-muted">Belum ada siswa di kelas ini.</td></tr>
                            <?php } else {
                                $no = 1;
                                foreach ($students as $s) {
                            ?>
                                <tr class="text-center">
                                    <td><?= $no++ ?></td>
                                    <td class="font-monospace fw-semibold"><?= htmlspecialchars($s['nis']) ?></td>
                                    <td class="text-start fw-semibold"><?= htmlspecialchars($s['nama']) ?></td>
                                    <td><?= $s['sumatif_1'] !== null ? (float)$s['sumatif_1'] : '-' ?></td>
                                    <td><?= $s['sumatif_2'] !== null ? (float)$s['sumatif_2'] : '-' ?></td>
                                    <td><?= $s['sumatif_3'] !== null ? (float)$s['sumatif_3'] : '-' ?></td>
                                    <td><?= (isset($s['sumatif_4']) && $s['sumatif_4'] !== null) ? (float)$s['sumatif_4'] : '-' ?></td>
                                    <td class="fw-bold text-primary fs-6"><?= $s['nilai_sts'] !== null ? (float)$s['nilai_sts'] : '-' ?></td>
                                    <td>
                                        <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#ModalInputNilai<?= $s['nis'] ?>" title="Input / Edit Nilai">
                                            <i class="fa fa-pen"></i>
                                        </button>
                                    </td>
                                </tr>

                                <!-- Modal Input/Edit Nilai Siswa -->
                                <div class="modal fade" id="ModalInputNilai<?= $s['nis'] ?>" tabindex="-1">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <form action="controllers/grade.php" method="POST">
                                                <input type="hidden" name="action" value="save_grade">
                                                <input type="hidden" name="id_pengampu" value="<?= $selectedPengampuId ?>">
                                                <input type="hidden" name="nis" value="<?= $s['nis'] ?>">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Input Nilai: <?= htmlspecialchars($s['nama']) ?></h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="row mb-3">
                                                        <div class="col-6">
                                                            <label class="form-label small fw-semibold">Nilai Sumatif 1 (01)</label>
                                                            <input type="number" step="0.01" min="0" max="100" class="form-control" name="sumatif_1" value="<?= $s['sumatif_1'] !== null ? (float)$s['sumatif_1'] : '' ?>" placeholder="0 - 100">
                                                        </div>
                                                        <div class="col-6">
                                                            <label class="form-label small fw-semibold">Nilai Sumatif 2 (02)</label>
                                                            <input type="number" step="0.01" min="0" max="100" class="form-control" name="sumatif_2" value="<?= $s['sumatif_2'] !== null ? (float)$s['sumatif_2'] : '' ?>" placeholder="0 - 100">
                                                        </div>
                                                    </div>
                                                    <div class="row mb-3">
                                                        <div class="col-6">
                                                            <label class="form-label small fw-semibold">Nilai Sumatif 3 (03)</label>
                                                            <input type="number" step="0.01" min="0" max="100" class="form-control" name="sumatif_3" value="<?= $s['sumatif_3'] !== null ? (float)$s['sumatif_3'] : '' ?>" placeholder="0 - 100">
                                                        </div>
                                                        <div class="col-6">
                                                            <label class="form-label small fw-semibold">Nilai Sumatif 4 (04)</label>
                                                            <input type="number" step="0.01" min="0" max="100" class="form-control" name="sumatif_4" value="<?= (isset($s['sumatif_4']) && $s['sumatif_4'] !== null) ? (float)$s['sumatif_4'] : '' ?>" placeholder="0 - 100">
                                                        </div>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="form-label small fw-semibold text-primary">Nilai Asesmen STS (Nilai ATS)</label>
                                                        <input type="number" step="0.01" min="0" max="100" class="form-control form-control-lg fw-bold" name="nilai_sts" value="<?= $s['nilai_sts'] !== null ? (float)$s['nilai_sts'] : '' ?>" placeholder="0 - 100">
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                                    <button type="submit" class="btn btn-primary">Simpan Nilai</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>

                            <?php } } ?>
                        </tbody>
                    </table>
                </div>

            <?php } ?>
        </div>
    </div>
</div>

<!-- Modal Upload Excel Nilai -->
<?php if ($currentAssignment) { ?>
<div class="modal fade" id="ModalUploadNilai" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="controllers/grade.php" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="action" value="upload_excel">
                <input type="hidden" name="id_pengampu" value="<?= $selectedPengampuId ?>">
                <div class="modal-header">
                    <h5 class="modal-title">Upload Excel Nilai STS</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3 p-3 bg-light rounded border d-flex justify-content-between align-items-center">
                        <div>
                            <span class="fw-semibold d-block text-dark small"><i class="fa-solid fa-file-excel text-success me-1"></i> Template Excel Siswa Kelas Ini</span>
                            <small class="text-muted">Nama siswa kelas ini sudah tercantum otomatis</small>
                        </div>
                        <a href="controllers/template.php?type=nilai_kelas&id_pengampu=<?= $selectedPengampuId ?>" class="btn btn-outline-success btn-sm">
                            <i class="fa-solid fa-download me-1"></i> Download Template
                        </a>
                    </div>
                    <div class="alert alert-info py-2 small">
                        Silakan download template di atas, isi nilai pada kolom Excel, lalu upload kembali file yang sudah diisi.
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Pilih File Nilai (.xlsx atau .csv)</label>
                        <input type="file" class="form-control" name="excel_file" accept=".xlsx, .csv" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-success">Upload & Proses</button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php } ?>
<?php } ?>

<script>
const bSumatif = <?= (float)$bobot['bobot_sumatif'] ?>;
const bSts = <?= (float)$bobot['bobot_sts'] ?>;
const kkm = <?= (float)$bobot['kkm'] ?>;

function calcLive(nis) {
    const s1 = parseFloat(document.getElementById('s1_' + nis).value) || 0;
    const s2 = parseFloat(document.getElementById('s2_' + nis).value) || 0;
    const s3 = parseFloat(document.getElementById('s3_' + nis).value) || 0;
    const sts = parseFloat(document.getElementById('sts_' + nis).value) || 0;

    const rata = ((s1 + s2 + s3) / 3).toFixed(2);
    const totalBobot = bSumatif + bSts;
    const na = (((rata * bSumatif) + (sts * bSts)) / totalBobot).toFixed(2);
    const pass = (na >= kkm);

    document.getElementById('rata_' + nis).innerText = rata;
    document.getElementById('na_' + nis).innerText = na;

    const statusBadge = document.getElementById('status_' + nis);
    statusBadge.innerText = pass ? 'Tercapai' : 'Belum Tercapai';
    statusBadge.className = 'badge ' + (pass ? 'bg-success' : 'bg-danger');
}
</script>
