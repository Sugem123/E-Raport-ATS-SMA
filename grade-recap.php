<?php
include_once "config/Database.php";
require_once "models/Teacher.php";
require_once "models/Bobot.php";

$db = new Database();
$conn = $db->connect();

$teacherModel = new Teacher($conn);
$bobotModel   = new Bobot($conn);

$idGuru = $_SESSION['id'] ?? '';
$myAssignments = $teacherModel->getPengampu($idGuru);

// Pengampu yang sedang dipilih
$selectedPengampuId = isset($_GET['id_pengampu']) ? (int)$_GET['id_pengampu'] : 0;
if ($selectedPengampuId === 0 && !empty($myAssignments)) {
    $selectedPengampuId = (int)$myAssignments[0]['id_pengampu'];
}

$currentAssignment = null;
foreach ($myAssignments as $a) {
    if ((int)$a['id_pengampu'] === $selectedPengampuId) {
        $currentAssignment = $a;
        break;
    }
}

$students = [];
if ($currentAssignment) {
    $students = $teacherModel->getStudentsForGrade($selectedPengampuId);
}

$bobot = $bobotModel->get();
?>

<div class="col-lg-9 mt-2">
    <div class="card shadow-sm border-0">
        <div class="card-header bg-white py-3">
            <h5 class="mb-0 fw-bold text-primary"><i class="fa-solid fa-pen-to-square me-2"></i>Penilaian Sumatif Tengah Semester (STS)</h5>
        </div>
        <div class="card-body">
            <!-- Pilihan Kelas & Mapel yang Diampu -->
            <div class="row align-items-center mb-4 bg-light p-3 rounded mx-1">
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
            </div>

            <?php if (!$currentAssignment) { ?>
                <div class="alert alert-warning">
                    <i class="fa-solid fa-triangle-exclamation me-1"></i> Anda belum memiliki penugasan mengajar mata pelajaran di kelas manapun. Silakan hubungi Administrator.
                </div>
            <?php } else { ?>

                <!-- Info Bobot Aktif -->
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h6 class="fw-bold mb-0 text-dark">Daftar Nilai Siswa (<?= count($students) ?> Siswa)</h6>
                    <small class="text-muted">
                        Bobot: <strong>Sumatif <?= (float)$bobot['bobot_sumatif'] ?>%</strong> + <strong>STS <?= (float)$bobot['bobot_sts'] ?>%</strong> | KKM: <strong><?= (float)$bobot['kkm'] ?></strong>
                    </small>
                </div>

                <!-- Tabel Nilai Siswa -->
                <div class="table-responsive">
                    <table class="table table-hover align-middle border">
                        <thead class="table-light">
                            <tr class="text-center">
                                <th style="width: 5%">No</th>
                                <th>NIS</th>
                                <th class="text-start">Nama Siswa</th>
                                <th>Sumatif 1</th>
                                <th>Sumatif 2</th>
                                <th>Sumatif 3</th>
                                <th>Rata-rata</th>
                                <th>Nilai STS</th>
                                <th>Nilai Akhir</th>
                                <th>Status</th>
                                <th style="width: 10%">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($students)) { ?>
                                <tr><td colspan="11" class="text-center py-4 text-muted">Belum ada siswa di kelas ini.</td></tr>
                            <?php } else {
                                $no = 1;
                                foreach ($students as $s) {
                                    $hasGrade = ($s['nilai_akhir'] !== null);
                                    $isPass = ($s['status_kelulusan'] === 'Tercapai');
                            ?>
                                <tr class="text-center">
                                    <td><?= $no++ ?></td>
                                    <td class="font-monospace fw-semibold"><?= htmlspecialchars($s['nis']) ?></td>
                                    <td class="text-start fw-semibold"><?= htmlspecialchars($s['nama']) ?></td>
                                    <td><?= $s['sumatif_1'] !== null ? (float)$s['sumatif_1'] : '-' ?></td>
                                    <td><?= $s['sumatif_2'] !== null ? (float)$s['sumatif_2'] : '-' ?></td>
                                    <td><?= $s['sumatif_3'] !== null ? (float)$s['sumatif_3'] : '-' ?></td>
                                    <td class="fw-semibold text-secondary"><?= $s['rata_sumatif'] !== null ? (float)$s['rata_sumatif'] : '-' ?></td>
                                    <td class="fw-semibold"><?= $s['nilai_sts'] !== null ? (float)$s['nilai_sts'] : '-' ?></td>
                                    <td class="fw-bold fs-6 text-primary"><?= $s['nilai_akhir'] !== null ? (float)$s['nilai_akhir'] : '-' ?></td>
                                    <td>
                                        <?php if ($hasGrade) { ?>
                                            <span class="badge <?= $isPass ? 'bg-success' : 'bg-danger' ?>">
                                                <?= htmlspecialchars($s['status_kelulusan']) ?>
                                            </span>
                                        <?php } else { ?>
                                            <span class="badge bg-light text-muted border">Belum dinilai</span>
                                        <?php } ?>
                                    </td>
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
                                                    <h5 class="modal-title">Input Nilai STS: <?= htmlspecialchars($s['nama']) ?></h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="row mb-3">
                                                        <div class="col-6">
                                                            <label class="form-label small fw-semibold">Nilai Sumatif 1</label>
                                                            <input type="number" step="0.01" min="0" max="100" class="form-control s1-input" id="s1_<?= $s['nis'] ?>" name="sumatif_1" value="<?= $s['sumatif_1'] !== null ? (float)$s['sumatif_1'] : '' ?>" oninput="calcLive('<?= $s['nis'] ?>')" required>
                                                        </div>
                                                        <div class="col-6">
                                                            <label class="form-label small fw-semibold">Nilai Sumatif 2</label>
                                                            <input type="number" step="0.01" min="0" max="100" class="form-control s2-input" id="s2_<?= $s['nis'] ?>" name="sumatif_2" value="<?= $s['sumatif_2'] !== null ? (float)$s['sumatif_2'] : '' ?>" oninput="calcLive('<?= $s['nis'] ?>')" required>
                                                        </div>
                                                    </div>
                                                    <div class="row mb-3">
                                                        <div class="col-6">
                                                            <label class="form-label small fw-semibold">Nilai Sumatif 3</label>
                                                            <input type="number" step="0.01" min="0" max="100" class="form-control s3-input" id="s3_<?= $s['nis'] ?>" name="sumatif_3" value="<?= $s['sumatif_3'] !== null ? (float)$s['sumatif_3'] : '' ?>" oninput="calcLive('<?= $s['nis'] ?>')" required>
                                                        </div>
                                                        <div class="col-6">
                                                            <label class="form-label small fw-semibold">Nilai Asesmen STS</label>
                                                            <input type="number" step="0.01" min="0" max="100" class="form-control sts-input" id="sts_<?= $s['nis'] ?>" name="nilai_sts" value="<?= $s['nilai_sts'] !== null ? (float)$s['nilai_sts'] : '' ?>" oninput="calcLive('<?= $s['nis'] ?>')" required>
                                                        </div>
                                                    </div>
                                                    <div class="p-3 bg-light rounded border">
                                                        <div class="d-flex justify-content-between mb-1">
                                                            <span class="small text-muted">Rata-rata Sumatif:</span>
                                                            <span class="fw-semibold" id="rata_<?= $s['nis'] ?>"><?= $s['rata_sumatif'] !== null ? (float)$s['rata_sumatif'] : '-' ?></span>
                                                        </div>
                                                        <div class="d-flex justify-content-between mb-1">
                                                            <span class="small text-muted">Nilai Akhir STS (Kalkulasi):</span>
                                                            <span class="fw-bold text-primary fs-6" id="na_<?= $s['nis'] ?>"><?= $s['nilai_akhir'] !== null ? (float)$s['nilai_akhir'] : '-' ?></span>
                                                        </div>
                                                        <div class="d-flex justify-content-between">
                                                            <span class="small text-muted">Status Capaian:</span>
                                                            <span class="badge <?= $isPass ? 'bg-success' : 'bg-danger' ?>" id="status_<?= $s['nis'] ?>">
                                                                <?= $s['status_kelulusan'] ?? 'Belum Lengkap' ?>
                                                            </span>
                                                        </div>
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
