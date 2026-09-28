<?php
include_once "config/Database.php";
require_once "models/Student.php";
require_once "models/Kelas.php";

$db = new Database();
$conn = $db->connect();

$studentModel = new Student($conn);
$kelasModel   = new Kelas($conn);

// --- Paginasi ---
$total     = $studentModel->countAll();
$perPage   = (int)($_GET['per_page'] ?? 25);
if (!in_array($perPage, [25, 50, 100], true)) { $perPage = 25; }

$totalPage = max(1, (int)ceil($total / $perPage));
$page      = (int)($_GET['page'] ?? 1);
if ($page < 1)          { $page = 1; }
if ($page > $totalPage) { $page = $totalPage; }

$offset    = ($page - 1) * $perPage;
$students  = $studentModel->getAll($perPage, $offset);
$firstNo   = $total === 0 ? 0 : $offset + 1;
$lastNo    = min($offset + $perPage, $total);

$classes   = $kelasModel->getAll();

/** Bangun URL halaman lain sambil mempertahankan per_page. */
$pageUrl = fn(int $p): string => '?page=' . $p . '&per_page=' . $perPage;
?>

<div class="col-lg-9 mt-2">
    <div class="card shadow-sm border-0">
        <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
            <h5 class="mb-0 fw-bold text-primary"><i class="fa-solid fa-user-graduate me-2"></i>Data Siswa</h5>
            <div class="d-flex gap-2">
                <a href="controllers/template.php?type=siswa" class="btn btn-outline-success btn-sm">
                    <i class="fa-solid fa-file-excel me-1"></i> Download Template
                </a>
                <button type="button" class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#ModalUploadSiswa">
                    <i class="fa-solid fa-upload me-1"></i> Upload Excel
                </button>
                <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#ModalInputStudent">
                    <i class="fa-solid fa-plus me-1"></i> Tambah Siswa
                </button>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th style="width: 5%">No</th>
                            <th>NIS</th>
                            <th>NISN</th>
                            <th>Nama Siswa</th>
                            <th>Kelas</th>
                            <th>Username</th>
                            <th style="width: 15%">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($students)) { ?>
                            <tr><td colspan="7" class="text-center py-4 text-muted">Belum ada data siswa.</td></tr>
                        <?php } else {
                            $no = $firstNo;
                            foreach ($students as $row) {
                        ?>
                            <tr>
                                <td><?= $no++ ?></td>
                                <td class="font-monospace fw-bold text-dark"><?= htmlspecialchars($row['nis']) ?></td>
                                <td class="font-monospace text-muted"><?= htmlspecialchars($row['nisn'] ?? '-') ?></td>
                                <td class="fw-semibold"><?= htmlspecialchars($row['nama']) ?></td>
                                <td><span class="badge bg-secondary"><?= htmlspecialchars($row['nama_kelas']) ?></span></td>
                                <td><code><?= htmlspecialchars($row['username']) ?></code></td>
                                <td>
                                    <a href="student-report.php?nis=<?= urlencode($row['nis']) ?>" target="_blank" class="btn btn-info btn-sm me-1 text-white" title="Lihat/Cetak Rapor STS">
                                        <i class="fa fa-print"></i>
                                    </a>
                                    <button class="btn btn-warning btn-sm me-1" data-bs-toggle="modal" data-bs-target="#ModalUpdateStudent<?= $row['id_user'] ?>" title="Edit">
                                        <i class="fa fa-edit"></i>
                                    </button>
                                    <button class="btn btn-danger btn-sm me-1" data-bs-toggle="modal" data-bs-target="#ModalDeleteStudent<?= $row['id_user'] ?>" title="Hapus">
                                        <i class="fa fa-trash"></i>
                                    </button>
                                    <button class="btn btn-secondary btn-sm" data-bs-toggle="modal" data-bs-target="#ModalResetPass<?= $row['id_user'] ?>" title="Reset Password">
                                        <i class="fa fa-key"></i>
                                    </button>
                                </td>
                            </tr>

                            <!-- Modal Edit Siswa -->
                            <div class="modal fade" id="ModalUpdateStudent<?= $row['id_user'] ?>" tabindex="-1">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <form action="controllers/student.php" method="POST">
                                            <input type="hidden" name="action" value="update">
                                            <input type="hidden" name="id_user" value="<?= $row['id_user'] ?>">
                                            <input type="hidden" name="page" value="<?= $page ?>">
                                            <input type="hidden" name="per_page" value="<?= $perPage ?>">
                                            <div class="modal-header">
                                                <h5 class="modal-title">Edit Data Siswa</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="mb-3">
                                                    <label class="form-label">NIS</label>
                                                    <input type="text" class="form-control" value="<?= htmlspecialchars($row['nis']) ?>" readonly>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label">NISN (Nomor Induk Siswa Nasional)</label>
                                                    <input type="text" class="form-control font-monospace" name="nisn" value="<?= htmlspecialchars($row['nisn'] ?? '') ?>" placeholder="Contoh: 0115119643 (opsional)">
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label">Nama Siswa</label>
                                                    <input type="text" class="form-control" name="nama" value="<?= htmlspecialchars($row['nama']) ?>" required>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label">Kelas</label>
                                                    <select class="form-select" name="id_kelas" required>
                                                        <?php foreach ($classes as $c) { ?>
                                                            <option value="<?= $c['id_kelas'] ?>" <?= $row['id_kelas'] == $c['id_kelas'] ? 'selected' : '' ?>>
                                                                <?= htmlspecialchars($c['nama_kelas']) ?>
                                                            </option>
                                                        <?php } ?>
                                                    </select>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label">Username Login</label>
                                                    <input type="text" class="form-control" name="username" value="<?= htmlspecialchars($row['username']) ?>" required>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                                <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>

                            <!-- Modal Delete Siswa -->
                            <div class="modal fade" id="ModalDeleteStudent<?= $row['id_user'] ?>" tabindex="-1">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <form action="controllers/student.php" method="POST">
                                            <input type="hidden" name="action" value="delete">
                                            <input type="hidden" name="id_user" value="<?= $row['id_user'] ?>">
                                            <input type="hidden" name="page" value="<?= $page ?>">
                                            <input type="hidden" name="per_page" value="<?= $perPage ?>">
                                            <div class="modal-header">
                                                <h5 class="modal-title">Hapus Siswa</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body">
                                                Apakah Anda yakin ingin menghapus siswa <strong><?= htmlspecialchars($row['nama']) ?> (<?= $row['nis'] ?>)</strong>?
                                                <br><small class="text-danger">*Seluruh riwayat nilai dan presensi siswa ini akan ikut terhapus.</small>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                                <button type="submit" class="btn btn-danger">Hapus</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>

                            <!-- Modal Reset Password -->
                            <div class="modal fade" id="ModalResetPass<?= $row['id_user'] ?>" tabindex="-1">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <form action="controllers/user.php" method="POST">
                                            <input type="hidden" name="action" value="reset_password">
                                            <input type="hidden" name="id" value="<?= $row['id_user'] ?>">
                                            <div class="modal-header">
                                                <h5 class="modal-title">Reset Password Siswa</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body">
                                                Reset password siswa <strong><?= htmlspecialchars($row['nama']) ?></strong> kembali menjadi <code>12345</code>?
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                                <button type="submit" class="btn btn-warning" name="input_user_validate" value="1">Reset Password</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>

                        <?php } } ?>
                    </tbody>
                </table>
            </div>

            <?php if (!empty($students)) { ?>
            <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mt-3">
                <div class="text-muted small">
                    Menampilkan <strong><?= $firstNo ?></strong>&ndash;<strong><?= $lastNo ?></strong>
                    dari <strong><?= number_format($total, 0, ',', '.') ?></strong> siswa
                </div>

                <div class="d-flex align-items-center gap-2">
                    <label class="text-muted small mb-0" for="perPageSelect">Baris per halaman</label>
                    <select class="form-select form-select-sm w-auto" id="perPageSelect"
                            onchange="location.href='?page=1&per_page=' + this.value;">
                        <?php foreach ([25, 50, 100] as $opt) { ?>
                            <option value="<?= $opt ?>" <?= $opt === $perPage ? 'selected' : '' ?>><?= $opt ?></option>
                        <?php } ?>
                    </select>
                </div>
            </div>

            <?php if ($totalPage > 1) { ?>
            <nav class="mt-3" aria-label="Navigasi halaman siswa">
                <ul class="pagination pagination-sm justify-content-center mb-0">
                    <li class="page-item <?= $page <= 1 ? 'disabled' : '' ?>">
                        <a class="page-link" href="<?= $pageUrl(max(1, $page - 1)) ?>" aria-label="Sebelumnya">
                            <i class="fa fa-chevron-left"></i>
                        </a>
                    </li>
                    <?php
                    $start = max(1, min($page - 2, $totalPage - 4));
                    $end   = min($totalPage, max($page + 2, 5));
                    for ($p = $start; $p <= $end; $p++) {
                    ?>
                        <li class="page-item <?= $p === $page ? 'active' : '' ?>">
                            <a class="page-link" href="<?= $pageUrl($p) ?>"><?= $p ?></a>
                        </li>
                    <?php } ?>
                    <li class="page-item <?= $page >= $totalPage ? 'disabled' : '' ?>">
                        <a class="page-link" href="<?= $pageUrl(min($totalPage, $page + 1)) ?>" aria-label="Berikutnya">
                            <i class="fa fa-chevron-right"></i>
                        </a>
                    </li>
                </ul>
            </nav>
            <?php } ?>
            <?php } ?>
        </div>
    </div>
</div>

<!-- Modal Tambah Siswa -->
<div class="modal fade" id="ModalInputStudent" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="controllers/student.php" method="POST">
                <input type="hidden" name="action" value="input">
                <input type="hidden" name="page" value="<?= $page ?>">
                <input type="hidden" name="per_page" value="<?= $perPage ?>">
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Siswa Baru</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-info py-2 small">Password default akun baru adalah <code>12345</code>.</div>
                    <div class="mb-3">
                        <label class="form-label">NIS (Nomor Induk Siswa)</label>
                        <input type="text" class="form-control" name="nis" placeholder="Contoh: SIS005" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">NISN (Nomor Induk Siswa Nasional)</label>
                        <input type="text" class="form-control font-monospace" name="nisn" placeholder="Contoh: 0115119643 (opsional)">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Nama Siswa Lengkap</label>
                        <input type="text" class="form-control" name="nama" placeholder="Contoh: Muhammad Farhan" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Kelas</label>
                        <select class="form-select" name="id_kelas" required>
                            <option value="">-- Pilih Kelas --</option>
                            <?php foreach ($classes as $c) { ?>
                                <option value="<?= $c['id_kelas'] ?>"><?= htmlspecialchars($c['nama_kelas']) ?></option>
                            <?php } ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Username Login</label>
                        <input type="text" class="form-control" name="username" placeholder="Contoh: farhan2026" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Upload Excel Siswa -->
<div class="modal fade" id="ModalUploadSiswa" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="controllers/student.php" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="action" value="upload_excel">
                <input type="hidden" name="page" value="<?= $page ?>">
                <input type="hidden" name="per_page" value="<?= $perPage ?>">
                <div class="modal-header">
                    <h5 class="modal-title">Upload Data Siswa (Excel / CSV)</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3 p-3 bg-light rounded border d-flex justify-content-between align-items-center">
                        <div>
                            <span class="fw-semibold d-block text-dark small"><i class="fa-solid fa-file-excel text-success me-1"></i> Template Excel Siswa</span>
                            <small class="text-muted">Unduh format baku sebelum mengunggah data</small>
                        </div>
                        <a href="controllers/template.php?type=siswa" class="btn btn-outline-success btn-sm">
                            <i class="fa-solid fa-download me-1"></i> Download Template
                        </a>
                    </div>
                    <div class="alert alert-info py-2 small">
                        Format kolom file Excel: <code>nis</code>, <code>nisn</code> (opsional), <code>nama</code>, <code>nama_kelas</code>, <code>username</code>. Pastikan nama kelas sudah terdaftar di sistem.
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Pilih File (.xlsx atau .csv)</label>
                        <input type="file" class="form-control" name="excel_file" accept=".xlsx, .csv" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-success">Upload & Import</button>
                </div>
            </form>
        </div>
    </div>
</div>
