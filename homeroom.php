<?php
include_once "config/Database.php";
require_once "models/Kelas.php";

$db = new Database();
$conn = $db->connect();

$kelasModel = new Kelas($conn);

$idGuru = $_SESSION['id'] ?? '';
$myClass = null;

if (!empty($_SESSION['id_kelas'])) {
    $myClass = $kelasModel->getById((int)$_SESSION['id_kelas']);
}
if (!$myClass && !empty($idGuru)) {
    $myClass = $kelasModel->getByWaliKelas($idGuru);
}

$summary = null;
if ($myClass) {
    $summary = $kelasModel->getHomeroomSummary((int)$myClass['id_kelas']);
}
?>

<div class="col-lg-9 mt-2">
    <div class="card shadow-sm border-0">
        <div class="card-header bg-white py-3 d-flex flex-wrap justify-content-between align-items-center gap-2">
            <div>
                <h5 class="mb-0 fw-bold text-primary">
                    <i class="fa-solid fa-users-line me-2"></i>Perwalian Kelas <?= $myClass ? htmlspecialchars($myClass['nama_kelas']) : '' ?>
                </h5>
                <?php if ($myClass) { ?>
                    <small class="text-muted">
                        Wali Kelas: <strong><?= htmlspecialchars($myClass['nama_walikelas'] ?? $_SESSION['nama']) ?></strong>
                    </small>
                <?php } ?>
            </div>
            <?php if ($myClass) { ?>
                <div class="d-flex gap-2">
                    <a href="grade-monitor" class="btn btn-outline-info btn-sm">
                        <i class="fa-solid fa-chart-pie me-1"></i> Monitor Mapel
                    </a>
                    <a href="bulk-print-view.php?kelas=<?= $myClass['id_kelas'] ?>" target="_blank" class="btn btn-danger btn-sm shadow-sm">
                        <i class="fa-solid fa-print me-1"></i> Cetak Massal Rapor
                    </a>
                </div>
            <?php } ?>
        </div>
        <div class="card-body">
            <?php if (!$myClass) { ?>
                <div class="alert alert-warning">
                    <i class="fa-solid fa-triangle-exclamation me-1"></i> Anda belum ditugaskan sebagai Wali Kelas pada kelas manapun. Hubungi Administrator untuk penugasan.
                </div>
            <?php } else { ?>

                <div class="alert alert-light border small text-muted mb-4 py-2">
                    <i class="fa-solid fa-circle-info text-primary me-1"></i>
                    Sebagai <strong>Wali Kelas</strong>, Anda bertugas memantau progres nilai dari seluruh guru mata pelajaran, mengelola catatan ketidakhadiran (S/I/A), dan mencetak lembar <strong>e-Rapor STS</strong> siswa.
                </div>

                <div class="table-responsive">
                    <table class="table table-hover align-middle border">
                        <thead class="table-light">
                            <tr class="text-center">
                                <th style="width: 5%">No</th>
                                <th>NIS</th>
                                <th class="text-start">Nama Siswa</th>
                                <th>Rata-rata Nilai</th>
                                <th>Status Nilai</th>
                                <th style="width: 7%">S</th>
                                <th style="width: 7%">I</th>
                                <th style="width: 7%">A</th>
                                <th style="width: 18%">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($summary['siswa_list'])) { ?>
                                <tr><td colspan="9" class="text-center py-4 text-muted">Belum ada siswa di kelas ini.</td></tr>
                            <?php } else {
                                $no = 1;
                                foreach ($summary['siswa_list'] as $s) {
                                    $isFull = ($s['total_mapel'] > 0 && $s['jumlah_terisi'] === $s['total_mapel']);
                            ?>
                                <tr class="text-center">
                                    <td><?= $no++ ?></td>
                                    <td class="font-monospace fw-semibold"><?= htmlspecialchars($s['nis']) ?></td>
                                    <td class="text-start fw-semibold"><?= htmlspecialchars($s['nama']) ?></td>
                                    <td class="fw-bold fs-6 text-primary">
                                        <?= $s['rata_rata_semua'] > 0 ? $s['rata_rata_semua'] : '-' ?>
                                    </td>
                                    <td>
                                        <span class="badge <?= $isFull ? 'bg-success' : 'bg-warning text-dark' ?>">
                                            <?= $s['jumlah_terisi'] ?> / <?= $s['total_mapel'] ?> Mapel
                                        </span>
                                    </td>
                                    <td><span class="badge bg-secondary"><?= (int)$s['sakit'] ?></span></td>
                                    <td><span class="badge bg-secondary"><?= (int)$s['izin'] ?></span></td>
                                    <td><span class="badge <?= (int)$s['alpa'] > 0 ? 'bg-danger' : 'bg-secondary' ?>"><?= (int)$s['alpa'] ?></span></td>
                                    <td>
                                        <button class="btn btn-warning btn-sm me-1" data-bs-toggle="modal" data-bs-target="#ModalPresensi<?= $s['nis'] ?>" title="Input/Edit Presensi S/I/A">
                                            <i class="fa-solid fa-clipboard-user me-1"></i> Presensi
                                        </button>
                                        <a href="student-report.php?nis=<?= urlencode($s['nis']) ?>" target="_blank" class="btn btn-outline-primary btn-sm" title="Cetak e-Rapor STS">
                                            <i class="fa fa-print me-1"></i> Rapor
                                        </a>
                                    </td>
                                </tr>

                                <!-- Modal Presensi Siswa -->
                                <div class="modal fade" id="ModalPresensi<?= $s['nis'] ?>" tabindex="-1">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <form action="controllers/grade.php" method="POST">
                                                <input type="hidden" name="action" value="save_presensi">
                                                <input type="hidden" name="nis" value="<?= $s['nis'] ?>">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Input Ketidakhadiran: <?= htmlspecialchars($s['nama']) ?></h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="mb-3">
                                                        <label class="form-label fw-semibold">Sakit (S) - Hari</label>
                                                        <input type="number" min="0" class="form-control" name="sakit" value="<?= (int)$s['sakit'] ?>" required>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="form-label fw-semibold">Izin (I) - Hari</label>
                                                        <input type="number" min="0" class="form-control" name="izin" value="<?= (int)$s['izin'] ?>" required>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="form-label fw-semibold">Tanpa Keterangan / Alpa (A) - Hari</label>
                                                        <input type="number" min="0" class="form-control" name="alpa" value="<?= (int)$s['alpa'] ?>" required>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                                    <button type="submit" class="btn btn-primary">Simpan Presensi</button>
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
