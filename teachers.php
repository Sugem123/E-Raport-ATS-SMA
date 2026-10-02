<?php
include_once "config/Database.php";
require_once "models/Teacher.php";

$db = new Database();
$conn = $db->connect();

$teacher = new Teacher($conn);

// --- Filter Pencarian & Paginasi ---
$search  = trim($_GET['search'] ?? '');
$total   = $teacher->countAll($search);

$perPage = (int)($_GET['per_page'] ?? 25);
if (!in_array($perPage, [25, 50, 100], true)) {
    $perPage = 25;
}

$totalPage = max(1, (int)ceil($total / $perPage));
$page      = (int)($_GET['page'] ?? 1);
if ($page < 1) { $page = 1; }
if ($page > $totalPage) { $page = $totalPage; }

$offset   = ($page - 1) * $perPage;
$teachers = $teacher->getAll($perPage, $offset, $search);

$firstNo  = $total === 0 ? 0 : $offset + 1;
$lastNo   = min($offset + $perPage, $total);

/** Bangun URL halaman lain sambil mempertahankan pencarian & per_page. */
$pageUrl = function(int $p) use ($search, $perPage): string {
    $params = ['x' => 'teachers', 'page' => $p, 'per_page' => $perPage];
    if (!empty($search)) {
        $params['search'] = $search;
    }
    return '?' . http_build_query($params);
};
?>

<div class="col-lg-9 mt-2">
    <div class="card shadow-sm border-0">
        <div class="card-header bg-white py-3 d-flex flex-wrap justify-content-between align-items-center gap-2">
            <h5 class="mb-0 fw-bold text-primary"><i class="fa-solid fa-chalkboard-user me-2"></i>Data Guru</h5>
            <div class="d-flex flex-wrap gap-2">
                <a href="controllers/template.php?type=guru" class="btn btn-outline-success btn-sm">
                    <i class="fa-solid fa-file-excel me-1"></i> Download Template
                </a>
                <button type="button" class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#ModalUploadTeacher">
                    <i class="fa-solid fa-upload me-1"></i> Upload Excel
                </button>
                <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#ModalInputTeacher">
                    <i class="fa-solid fa-plus me-1"></i> Tambah Guru
                </button>
            </div>
        </div>
        <div class="card-body">
            <!-- Filter Pencarian Cepat -->
            <div class="card bg-light border-0 mb-3">
                <div class="card-body p-3">
                    <form method="GET" action="" class="row g-2 align-items-center">
                        <input type="hidden" name="x" value="teachers">
                        <input type="hidden" name="per_page" value="<?= $perPage ?>">

                        <div class="col-12 col-md-9">
                            <div class="input-group input-group-sm">
                                <span class="input-group-text bg-white"><i class="fa-solid fa-magnifying-glass text-muted"></i></span>
                                <input type="text" class="form-control" name="search"
                                       value="<?= htmlspecialchars($search) ?>"
                                       placeholder="Cari nama guru, NIP / ID Guru, atau username akun...">
                                <?php if (!empty($search)) { ?>
                                    <a href="?x=teachers" class="btn btn-outline-secondary" title="Hapus pencarian">
                                        <i class="fa-solid fa-xmark"></i>
                                    </a>
                                <?php } ?>
                            </div>
                        </div>
                        <div class="col-12 col-md-3 d-grid">
                            <button type="submit" class="btn btn-primary btn-sm fw-semibold">
                                <i class="fa-solid fa-filter me-1"></i> Cari Guru
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th style="width: 5%">No</th>
                            <th>ID Guru / NIP</th>
                            <th>Nama Lengkap</th>
                            <th>Username Akun</th>
                            <th style="width: 15%">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($teachers)) { ?>
                            <tr><td colspan="5" class="text-center py-4 text-muted">Belum ada data guru<?= !empty($search) ? ' yang sesuai dengan pencarian' : '' ?>.</td></tr>
                        <?php } else {
                            $no = $firstNo;
                            foreach ($teachers as $row) {
                        ?>
                            <tr>
                                <td><?= $no++ ?></td>
                                <td class="font-monospace fw-bold text-dark"><?= htmlspecialchars($row['id_guru']) ?></td>
                                <td class="fw-semibold"><?= htmlspecialchars($row['nama_guru']) ?></td>
                                <td><code><?= htmlspecialchars($row['username']) ?></code></td>
                                <td>
                                    <button class="btn btn-warning btn-sm me-1" data-bs-toggle="modal" data-bs-target="#ModalUpdateTeacher<?= $row['id_user'] ?>" title="Edit">
                                        <i class="fa fa-edit"></i>
                                    </button>
                                    <button class="btn btn-danger btn-sm me-1" data-bs-toggle="modal" data-bs-target="#ModalDeleteTeacher<?= $row['id_user'] ?>" title="Hapus">
                                        <i class="fa fa-trash"></i>
                                    </button>
                                    <button class="btn btn-secondary btn-sm" data-bs-toggle="modal" data-bs-target="#ModalResetPass<?= $row['id_user'] ?>" title="Reset Password">
                                        <i class="fa fa-key"></i>
                                    </button>
                                </td>
                            </tr>

                            <!-- Modal Edit Guru -->
                            <div class="modal fade" id="ModalUpdateTeacher<?= $row['id_user'] ?>" tabindex="-1">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <form action="controllers/teacher.php" method="POST">
                                            <input type="hidden" name="action" value="update">
                                            <input type="hidden" name="id_user" value="<?= $row['id_user'] ?>">
                                            <div class="modal-header">
                                                <h5 class="modal-title">Edit Data Guru</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="mb-3">
                                                    <label class="form-label">ID Guru / NIP</label>
                                                    <input type="text" class="form-control" value="<?= htmlspecialchars($row['id_guru']) ?>" readonly>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label">Nama Guru</label>
                                                    <input type="text" class="form-control" name="nama_guru" value="<?= htmlspecialchars($row['nama_guru']) ?>" required>
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

                            <!-- Modal Delete Guru -->
                            <div class="modal fade" id="ModalDeleteTeacher<?= $row['id_user'] ?>" tabindex="-1">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <form action="controllers/teacher.php" method="POST">
                                            <input type="hidden" name="action" value="delete">
                                            <input type="hidden" name="id_user" value="<?= $row['id_user'] ?>">
                                            <div class="modal-header">
                                                <h5 class="modal-title">Hapus Guru</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body">
                                                Apakah Anda yakin ingin menghapus guru <strong><?= htmlspecialchars($row['nama_guru']) ?></strong>?
                                                <br><small class="text-danger">*Penugasan mengajar guru ini akan ikut terhapus.</small>
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
                                                <h5 class="modal-title">Reset Password Guru</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body">
                                                Reset password guru <strong><?= htmlspecialchars($row['nama_guru']) ?></strong> kembali menjadi <code>12345</code>?
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

            <?php if ($total > 0) { ?>
            <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mt-3">
                <div class="text-muted small">
                    Menampilkan <strong><?= $firstNo ?></strong>&ndash;<strong><?= $lastNo ?></strong>
                    dari <strong><?= number_format($total, 0, ',', '.') ?></strong> data guru
                </div>

                <div class="d-flex align-items-center gap-2">
                    <label class="text-muted small mb-0" for="perPageSelect">Baris per halaman</label>
                    <select class="form-select form-select-sm w-auto" id="perPageSelect"
                            onchange="location.href='?x=teachers&page=1&per_page=' + this.value + '<?= !empty($search) ? '&search=' . urlencode($search) : '' ?>';">
                        <?php foreach ([25, 50, 100] as $opt) { ?>
                            <option value="<?= $opt ?>" <?= $opt === $perPage ? 'selected' : '' ?>><?= $opt ?></option>
                        <?php } ?>
                    </select>
                </div>
            </div>

            <?php if ($totalPage > 1) { ?>
            <nav class="mt-3" aria-label="Navigasi halaman guru">
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

<!-- Modal Tambah Guru -->
<div class="modal fade" id="ModalInputTeacher" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="controllers/teacher.php" method="POST">
                <input type="hidden" name="action" value="input">
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Guru Baru</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-info py-2 small">Password default akun baru adalah <code>12345</code>.</div>
                    <div class="mb-3">
                        <label class="form-label">ID Guru / NIP</label>
                        <input type="text" class="form-control" name="id_guru" placeholder="Contoh: GURU005 atau NIP" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Nama Lengkap & Gelar</label>
                        <input type="text" class="form-control" name="nama_guru" placeholder="Contoh: Dra. Sri Wahyuni, M.Pd." required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Username Login</label>
                        <input type="text" class="form-control" name="username" placeholder="Contoh: sriwahyuni" required>
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

<!-- Modal Upload Excel Guru -->
<div class="modal fade" id="ModalUploadTeacher" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="controllers/teacher.php" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="action" value="upload_excel">
                <div class="modal-header">
                    <h5 class="modal-title">Upload Data Guru (Excel / CSV)</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3 p-3 bg-light rounded border d-flex justify-content-between align-items-center">
                        <div>
                            <span class="fw-semibold d-block text-dark small"><i class="fa-solid fa-file-excel text-success me-1"></i> Template Excel Guru</span>
                            <small class="text-muted">Unduh format baku sebelum mengunggah data</small>
                        </div>
                        <a href="controllers/template.php?type=guru" class="btn btn-outline-success btn-sm">
                            <i class="fa-solid fa-download me-1"></i> Download Template
                        </a>
                    </div>
                    <div class="alert alert-info py-2 small">
                        Format kolom file Excel: <code>id_guru</code>, <code>nama_guru</code>, <code>username</code>.
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
