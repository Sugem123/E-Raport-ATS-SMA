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
$myIdKelas = $myClass ? (int)$myClass['id_kelas'] : 0;
$allClasses = $kelasModel->getAll();

$otherStudents = [];
if ($myIdKelas > 0) {
    $summary = $kelasModel->getHomeroomSummary($myIdKelas);

    // Ambil daftar siswa dari kelas lain untuk opsi tarik masuk
    $qOther = mysqli_query($conn, "
        SELECT s.nis, s.nama, s.id_kelas, k.nama_kelas
        FROM tb_siswa s
        INNER JOIN tb_kelas k ON s.id_kelas = k.id_kelas
        WHERE s.id_kelas != $myIdKelas
        ORDER BY s.nama ASC
    ");
    while ($rO = mysqli_fetch_assoc($qOther)) {
        $otherStudents[] = $rO;
    }
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
                        Wali Kelas: <strong><?= htmlspecialchars($myClass['nama_walikelas'] ?? $_SESSION['nama']) ?></strong> &bull;
                        Tingkat <?= htmlspecialchars($myClass['tingkat']) ?> (Fase <?= $myClass['tingkat'] === '10' ? 'E' : 'F' ?>) &bull;
                        Total: <strong><?= count($summary['siswa_list'] ?? []) ?> Siswa</strong>
                    </small>
                <?php } ?>
            </div>
            <?php if ($myClass) { ?>
                <div class="d-flex flex-wrap gap-2">
                    <button class="btn btn-primary btn-sm" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTambahSiswaWali">
                        <i class="fa-solid fa-user-plus me-1"></i> + Tambah Siswa
                    </button>
                    <button class="btn btn-outline-secondary btn-sm" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTarikSiswaWali">
                        <i class="fa-solid fa-arrow-right-arrow-left me-1"></i> Tarik Siswa
                    </button>
                    <a href="ledger?kelas=<?= $myIdKelas ?>" class="btn btn-warning btn-sm fw-bold shadow-sm">
                        <i class="fa-solid fa-table me-1"></i> Leger Nilai STS
                    </a>
                    <a href="grade-monitor" class="btn btn-outline-info btn-sm">
                        <i class="fa-solid fa-chart-pie me-1"></i> Monitor Mapel
                    </a>
                    <a href="receipt-print.php?kelas=<?= $myIdKelas ?>" target="_blank" class="btn btn-outline-warning text-dark fw-semibold btn-sm shadow-sm">
                        <i class="fa-solid fa-file-signature me-1 text-warning"></i> Tanda Terima Rapor
                    </a>
                    <a href="bulk-print-view.php?kelas=<?= $myIdKelas ?>" target="_blank" class="btn btn-danger btn-sm shadow-sm">
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

                <!-- COLLAPSIBLE FORM 1: Tambah Siswa Baru Langsung ke Kelas Perwalian -->
                <div class="collapse mb-3" id="collapseTambahSiswaWali">
                    <div class="card card-body bg-light border p-3">
                        <h6 class="fw-bold text-primary mb-2">
                            <i class="fa-solid fa-user-plus me-1"></i> Daftarkan Siswa Baru ke Kelas <?= htmlspecialchars($myClass['nama_kelas']) ?>
                        </h6>
                        <form action="controllers/kelas.php" method="POST">
                            <input type="hidden" name="action" value="add_student_new">
                            <input type="hidden" name="id_kelas" value="<?= $myIdKelas ?>">
                            <input type="hidden" name="redirect_to" value="homeroom">
                            <div class="row g-2 mb-2">
                                <div class="col-md-4">
                                    <label class="form-label small fw-semibold">NIS (Wajib)</label>
                                    <input type="text" class="form-control form-control-sm font-monospace" name="nis" placeholder="Contoh: 7501" required>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label small fw-semibold">NISN (10 Digit)</label>
                                    <input type="text" class="form-control form-control-sm font-monospace" name="nisn" placeholder="Contoh: 0081234567">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label small fw-semibold">Nama Lengkap Siswa</label>
                                    <input type="text" class="form-control form-control-sm" name="nama" placeholder="Nama Lengkap" required>
                                </div>
                            </div>
                            <div class="text-end">
                                <button type="submit" class="btn btn-primary btn-sm">
                                    <i class="fa-solid fa-check me-1"></i> Simpan Siswa Baru
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- COLLAPSIBLE FORM 2: Masukkan / Tarik Siswa dari Kelas Lain -->
                <div class="collapse mb-3" id="collapseTarikSiswaWali">
                    <div class="card card-body bg-light border p-3">
                        <h6 class="fw-bold text-success mb-2">
                            <i class="fa-solid fa-arrow-right-arrow-left me-1"></i> Tarik Siswa Masuk ke Kelas <?= htmlspecialchars($myClass['nama_kelas']) ?>
                        </h6>
                        <form action="controllers/kelas.php" method="POST">
                            <input type="hidden" name="action" value="move_student">
                            <input type="hidden" name="id_kelas_tujuan" value="<?= $myIdKelas ?>">
                            <input type="hidden" name="redirect_to" value="homeroom">
                            <div class="row g-2 mb-2">
                                <div class="col-md-9">
                                    <label class="form-label small fw-semibold">Pilih Siswa</label>
                                    <select name="nis" class="form-select form-select-sm" required>
                                        <option value="">-- Pilih Siswa yang Akan Ditarik Masuk --</option>
                                        <?php foreach ($otherStudents as $os) { ?>
                                            <option value="<?= htmlspecialchars($os['nis']) ?>">
                                                <?= htmlspecialchars($os['nama']) ?> (NIS: <?= htmlspecialchars($os['nis']) ?> &bull; Dari Kelas <?= htmlspecialchars($os['nama_kelas']) ?>)
                                            </option>
                                        <?php } ?>
                                    </select>
                                </div>
                                <div class="col-md-3 d-flex align-items-end">
                                    <button type="submit" class="btn btn-success btn-sm w-100">
                                        <i class="fa-solid fa-arrow-right-to-bracket me-1"></i> Masukkan ke Kelas
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="alert alert-light border small text-muted mb-4 py-2">
                    <i class="fa-solid fa-circle-info text-primary me-1"></i>
                    Sebagai <strong>Wali Kelas</strong>, Anda dapat menambah/mengeluarkan anggota kelas, memantau progres nilai seluruh mata pelajaran, mengelola catatan ketidakhadiran (S/I/A), dan mencetak <strong>e-Rapor STS</strong> maupun <strong>Leger Nilai</strong>.
                </div>

                <div class="table-responsive">
                    <table class="table table-hover align-middle border">
                        <thead class="table-light">
                            <tr class="text-center">
                                <th style="width: 5%">No</th>
                                <th style="width: 12%">NIS</th>
                                <th class="text-start">Nama Siswa</th>
                                <th style="width: 14%">Status Nilai</th>
                                <th style="width: 6%">S</th>
                                <th style="width: 6%">I</th>
                                <th style="width: 6%">A</th>
                                <th style="width: 20%">Aksi Anggota &amp; Rapor</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($summary['siswa_list'])) { ?>
                                <tr><td colspan="8" class="text-center py-4 text-muted">Belum ada siswa di kelas ini. Klik "+ Tambah Siswa" untuk menambahkan.</td></tr>
                            <?php } else {
                                $no = 1;
                                foreach ($summary['siswa_list'] as $s) {
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
                                    <td><span class="badge bg-secondary"><?= (int)$s['sakit'] ?></span></td>
                                    <td><span class="badge bg-secondary"><?= (int)$s['izin'] ?></span></td>
                                    <td><span class="badge <?= (int)$s['alpa'] > 0 ? 'bg-danger' : 'bg-secondary' ?>"><?= (int)$s['alpa'] ?></span></td>
                                    <td>
                                        <div class="btn-group btn-group-sm">
                                            <!-- Presensi -->
                                            <button class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#ModalPresensi<?= $s['nis'] ?>" title="Input/Edit Presensi">
                                                <i class="fa-solid fa-clipboard-user"></i>
                                            </button>
                                            <!-- Cetak Rapor -->
                                            <a href="student-report.php?nis=<?= urlencode($s['nis']) ?>" target="_blank" class="btn btn-outline-primary btn-sm" title="Cetak e-Rapor STS">
                                                <i class="fa fa-print"></i>
                                            </a>
                                            <!-- Pindahkan Siswa Keluar -->
                                            <button type="button" class="btn btn-outline-secondary btn-sm" data-bs-toggle="modal" data-bs-target="#ModalPindahSiswaWali<?= $s['nis'] ?>" title="Pindahkan Siswa Keluar ke Kelas Lain">
                                                <i class="fa-solid fa-arrow-right-arrow-left"></i>
                                            </button>
                                            <!-- Keluarkan Siswa -->
                                            <button type="button" class="btn btn-outline-danger btn-sm" data-bs-toggle="modal" data-bs-target="#ModalKeluarkanSiswaWali<?= $s['nis'] ?>" title="Keluarkan Siswa dari Kelas Ini">
                                                <i class="fa-solid fa-user-xmark"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>

                                <!-- MODAL 1: Presensi Siswa -->
                                <div class="modal fade" id="ModalPresensi<?= $s['nis'] ?>" tabindex="-1">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content">
                                            <form action="controllers/grade.php" method="POST">
                                                <input type="hidden" name="action" value="save_presensi">
                                                <input type="hidden" name="nis" value="<?= $s['nis'] ?>">
                                                <input type="hidden" name="redirect_to" value="homeroom">
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

                                <!-- MODAL 2: Pindahkan Siswa Keluar ke Kelas Lain -->
                                <div class="modal fade" id="ModalPindahSiswaWali<?= $s['nis'] ?>" tabindex="-1">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content">
                                            <form action="controllers/kelas.php" method="POST">
                                                <input type="hidden" name="action" value="move_student">
                                                <input type="hidden" name="nis" value="<?= $s['nis'] ?>">
                                                <input type="hidden" name="redirect_to" value="homeroom">
                                                <div class="modal-header">
                                                    <h5 class="modal-title"><i class="fa-solid fa-arrow-right-arrow-left text-primary me-2"></i>Pindahkan Siswa</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <p class="mb-3">
                                                        Pindahkan siswa <strong><?= htmlspecialchars($s['nama']) ?></strong> (NIS: <?= htmlspecialchars($s['nis']) ?>) dari Kelas <?= htmlspecialchars($myClass['nama_kelas']) ?> ke kelas lain:
                                                    </p>
                                                    <label class="form-label fw-semibold">Pilih Kelas Tujuan:</label>
                                                    <select name="id_kelas_tujuan" class="form-select" required>
                                                        <option value="">-- Pilih Kelas Tujuan --</option>
                                                        <?php foreach ($allClasses as $optK) {
                                                            if ((int)$optK['id_kelas'] === $myIdKelas) continue;
                                                        ?>
                                                            <option value="<?= (int)$optK['id_kelas'] ?>">
                                                                Kelas <?= htmlspecialchars($optK['nama_kelas']) ?> (Fase <?= $optK['tingkat'] === '10' ? 'E' : 'F' ?>) - Wali: <?= htmlspecialchars($optK['nama_walikelas'] ?? 'Belum ditentukan') ?>
                                                            </option>
                                                        <?php } ?>
                                                    </select>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                                    <button type="submit" class="btn btn-primary">Pindahkan Siswa</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>

                                <!-- MODAL 3: Keluarkan Siswa dari Sistem -->
                                <div class="modal fade" id="ModalKeluarkanSiswaWali<?= $s['nis'] ?>" tabindex="-1">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content">
                                            <form action="controllers/kelas.php" method="POST">
                                                <input type="hidden" name="action" value="delete_student">
                                                <input type="hidden" name="nis" value="<?= $s['nis'] ?>">
                                                <input type="hidden" name="redirect_to" value="homeroom">
                                                <div class="modal-header bg-danger text-white">
                                                    <h5 class="modal-title"><i class="fa-solid fa-triangle-exclamation me-2"></i>Keluarkan Siswa</h5>
                                                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <p class="mb-2">
                                                        Yakin ingin mengeluarkan siswa <strong><?= htmlspecialchars($s['nama']) ?></strong> (NIS: <?= htmlspecialchars($s['nis']) ?>) dari kelas perwalian ini?
                                                    </p>
                                                    <small class="text-danger">*Tindakan ini akan menghapus data siswa dan akun loginnya dari sistem.</small>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                                    <button type="submit" class="btn btn-danger">Hapus / Keluarkan</button>
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
