<?php
include_once "config/Database.php";
require_once "models/Pengampu.php";
require_once "models/Teacher.php";
require_once "models/Mapel.php";
require_once "models/Kelas.php";

$db = new Database();
$conn = $db->connect();

$pengampuModel = new Pengampu($conn);
$teacherModel  = new Teacher($conn);
$mapelModel    = new Mapel($conn);
$kelasModel    = new Kelas($conn);

// --- Filter & Pencarian ---
$search  = trim($_GET['search'] ?? '');
$jenjang = trim($_GET['jenjang'] ?? '');
if (!in_array($jenjang, ['10', '11', '12'], true)) {
    $jenjang = '';
}

// --- Paginasi ---
$total     = $pengampuModel->countAll($search, $jenjang);
$perPage   = (int)($_GET['per_page'] ?? 25);
if (!in_array($perPage, [25, 50, 100], true)) { $perPage = 25; }

$totalPage = max(1, (int)ceil($total / $perPage));
$page      = (int)($_GET['page'] ?? 1);
if ($page < 1)    { $page = 1; }
if ($page > $totalPage) { $page = $totalPage; }

$offset   = ($page - 1) * $perPage;
$assignments = $pengampuModel->getAll($perPage, $offset, $search, $jenjang);
$firstNo   = $total === 0 ? 0 : $offset + 1;
$lastNo    = min($offset + $perPage, $total);

$teachers = $teacherModel->getAll();
$subjects = $mapelModel->getAll();
$classes  = $kelasModel->getAll();

/** Bangun URL halaman lain sambil mempertahankan filter, search, & per_page. */
$pageUrl = function(int $p) use ($search, $jenjang, $perPage): string {
    $params = ['x' => 'assignments', 'page' => $p, 'per_page' => $perPage];
    if (!empty($search))  { $params['search'] = $search; }
    if (!empty($jenjang)) { $params['jenjang'] = $jenjang; }
    return '?' . http_build_query($params);
};
?>

<div class="col-lg-9 mt-2">
    <div class="card shadow-sm border-0">
        <div class="card-header bg-white py-3 d-flex flex-wrap justify-content-between align-items-center gap-2">
            <h5 class="mb-0 fw-bold text-primary"><i class="fa-solid fa-link me-2"></i>Penugasan Mengajar Guru</h5>
            <div class="d-flex flex-wrap gap-2">
                <a href="controllers/template.php?type=penugasan" class="btn btn-outline-success btn-sm">
                    <i class="fa-solid fa-file-excel me-1"></i> Download Template
                </a>
                <button type="button" class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#ModalUploadPengampu">
                    <i class="fa-solid fa-upload me-1"></i> Upload Excel
                </button>
                <button type="button" class="btn btn-outline-danger btn-sm" data-bs-toggle="modal" data-bs-target="#ModalResetPengampu">
                    <i class="fa-solid fa-rotate-left me-1"></i> Reset Semua Penugasan
                </button>
                <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#ModalInputPengampu">
                    <i class="fa-solid fa-plus me-1"></i> Tambah Penugasan
                </button>
            </div>
        </div>
        <div class="card-body">
            <!-- Filter Bar: Search + Filter Jenjang -->
            <div class="card bg-light border-0 mb-3">
                <div class="card-body p-3">
                    <form method="GET" action="" class="row g-2 align-items-center">
                        <input type="hidden" name="x" value="assignments">
                        <input type="hidden" name="per_page" value="<?= $perPage ?>">

                        <!-- Input Pencarian -->
                        <div class="col-12 col-md-5">
                            <div class="input-group input-group-sm">
                                <span class="input-group-text bg-white"><i class="fa-solid fa-magnifying-glass text-muted"></i></span>
                                <input type="text" class="form-control" name="search"
                                       value="<?= htmlspecialchars($search) ?>"
                                       placeholder="Cari nama guru, NIP, mapel, atau kelas...">
                                <?php if (!empty($search)) { ?>
                                    <a href="?x=assignments<?= !empty($jenjang) ? '&jenjang=' . $jenjang : '' ?>" class="btn btn-outline-secondary" title="Hapus pencarian">
                                        <i class="fa-solid fa-xmark"></i>
                                    </a>
                                <?php } ?>
                            </div>
                        </div>

                        <!-- Filter Tab Jenjang -->
                        <div class="col-12 col-md-5">
                            <div class="btn-group btn-group-sm w-100" role="group">
                                <a href="?x=assignments<?= !empty($search) ? '&search=' . urlencode($search) : '' ?>"
                                   class="btn <?= empty($jenjang) ? 'btn-primary fw-bold' : 'btn-outline-primary bg-white text-primary' ?>">
                                    Semua
                                </a>
                                <a href="?x=assignments&jenjang=10<?= !empty($search) ? '&search=' . urlencode($search) : '' ?>"
                                   class="btn <?= $jenjang === '10' ? 'btn-primary fw-bold' : 'btn-outline-primary bg-white text-primary' ?>">
                                    Kelas X
                                </a>
                                <a href="?x=assignments&jenjang=11<?= !empty($search) ? '&search=' . urlencode($search) : '' ?>"
                                   class="btn <?= $jenjang === '11' ? 'btn-primary fw-bold' : 'btn-outline-primary bg-white text-primary' ?>">
                                    Kelas XI
                                </a>
                                <a href="?x=assignments&jenjang=12<?= !empty($search) ? '&search=' . urlencode($search) : '' ?>"
                                   class="btn <?= $jenjang === '12' ? 'btn-primary fw-bold' : 'btn-outline-primary bg-white text-primary' ?>">
                                    Kelas XII
                                </a>
                            </div>
                        </div>

                        <!-- Tombol Cari / Terapkan -->
                        <div class="col-12 col-md-2 d-grid">
                            <button type="submit" class="btn btn-primary btn-sm fw-semibold">
                                <i class="fa-solid fa-filter me-1"></i> Filter
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="alert alert-light border small text-muted mb-3 py-2">
                <i class="fa-solid fa-circle-info text-primary me-1"></i>
                Penugasan ini menghubungkan <strong>Guru</strong> dengan <strong>Mata Pelajaran</strong> dan <strong>Kelas</strong> yang diajar. Guru hanya dapat menginput nilai pada kelas yang ditugaskan di sini.
            </div>
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th style="width: 5%">No</th>
                            <th>Guru Pengampu</th>
                            <th>Mata Pelajaran</th>
                            <th>Kelas</th>
                            <th style="width: 10%">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($assignments)) { ?>
                            <tr><td colspan="5" class="text-center py-4 text-muted">Belum ada penugasan mengajar.</td></tr>
                        <?php } else {
                            $no = $firstNo;
                            foreach ($assignments as $a) {
                        ?>
                            <tr>
                                <td><?= $no++ ?></td>
                                <td class="fw-semibold text-dark">
                                    <i class="fa-solid fa-chalkboard-user text-primary me-1"></i>
                                    <?= htmlspecialchars($a['nama_guru']) ?>
                                    <small class="text-muted d-block font-monospace"><?= htmlspecialchars($a['id_guru']) ?></small>
                                </td>
                                <td>
                                    <span class="badge bg-info text-dark px-2 py-1"><?= htmlspecialchars($a['id_mapel']) ?></span>
                                    <?= htmlspecialchars($a['nama_mapel']) ?>
                                </td>
                                <td>
                                    <span class="badge bg-secondary"><?= htmlspecialchars($a['nama_kelas']) ?></span>
                                </td>
                                <td>
                                    <div class="d-flex gap-1">
                                        <button type="button"
                                                class="btn btn-outline-primary btn-sm js-edit-pengampu"
                                                title="Edit Penugasan"
                                                data-bs-toggle="modal"
                                                data-bs-target="#ModalEditPengampu"
                                                data-id="<?= (int)$a['id_pengampu'] ?>"
                                                data-guru="<?= htmlspecialchars($a['id_guru']) ?>"
                                                data-mapel="<?= htmlspecialchars($a['id_mapel']) ?>"
                                                data-kelas="<?= (int)$a['id_kelas'] ?>">
                                            <i class="fa fa-pen"></i>
                                        </button>
                                        <form action="controllers/pengampu.php" method="POST" onsubmit="return confirm('Hapus penugasan ini?');">
                                            <input type="hidden" name="action" value="delete">
                                            <input type="hidden" name="id_pengampu" value="<?= (int)$a['id_pengampu'] ?>">
                                            <input type="hidden" name="page" value="<?= $page ?>">
                                            <input type="hidden" name="per_page" value="<?= $perPage ?>">
                                            <button type="submit" class="btn btn-outline-danger btn-sm" title="Hapus Penugasan">
                                                <i class="fa fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        <?php } } ?>
                    </tbody>
                </table>
            </div>

            <?php if ($total > 0) { ?>
            <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mt-3">
                <div class="text-muted small">
                    Menampilkan <strong><?= $firstNo ?></strong>&ndash;<strong><?= $lastNo ?></strong>
                    dari <strong><?= number_format($total, 0, ',', '.') ?></strong> penugasan
                </div>

                <div class="d-flex align-items-center gap-2">
                    <label class="text-muted small mb-0" for="perPageSelect">Baris per halaman</label>
                    <select class="form-select form-select-sm w-auto" id="perPageSelect"
                            onchange="location.href='?x=assignments&page=1&per_page=' + this.value + '<?= !empty($search) ? '&search=' . urlencode($search) : '' ?><?= !empty($jenjang) ? '&jenjang=' . $jenjang : '' ?>';">
                        <?php foreach ([25, 50, 100] as $opt) { ?>
                            <option value="<?= $opt ?>" <?= $opt === $perPage ? 'selected' : '' ?>><?= $opt ?></option>
                        <?php } ?>
                    </select>
                </div>
            </div>

            <?php if ($totalPage > 1) { ?>
            <nav class="mt-3" aria-label="Navigasi halaman penugasan">
                <ul class="pagination pagination-sm justify-content-center mb-0">
                    <li class="page-item <?= $page <= 1 ? 'disabled' : '' ?>">
                        <a class="page-link" href="<?= $pageUrl(max(1, $page - 1)) ?>" aria-label="Sebelumnya">
                            <i class="fa fa-chevron-left"></i>
                        </a>
                    </li>
                    <?php
                    // Tampilkan maksimal 5 nomor halaman di sekitar halaman aktif.
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

<!-- Modal Tambah Penugasan -->
<div class="modal fade" id="ModalInputPengampu" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="controllers/pengampu.php" method="POST">
                <input type="hidden" name="action" value="input">
                <input type="hidden" name="page" value="<?= $page ?>">
                <input type="hidden" name="per_page" value="<?= $perPage ?>">
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Penugasan Mengajar</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Pilih Guru</label>
                        <select class="form-select" name="id_guru" required>
                            <option value="">-- Pilih Guru --</option>
                            <?php foreach ($teachers as $t) { ?>
                                <option value="<?= $t['id_guru'] ?>"><?= htmlspecialchars($t['nama_guru']) ?> (<?= $t['id_guru'] ?>)</option>
                            <?php } ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Pilih Mata Pelajaran</label>
                        <select class="form-select" name="id_mapel" required>
                            <option value="">-- Pilih Mapel --</option>
                            <?php foreach ($subjects as $s) {
                                // Tampilkan jenjang dari mapping (mis. "X, XI" atau "Belum dipetakan")
                                $jMap = trim((string)($s['jenjang_terpakai'] ?? ''));
                                $jLabel = $jMap === '' ? 'Belum dipetakan' : strtr($jMap, ['10' => 'X', '11' => 'XI', '12' => 'XII']);
                            ?>
                                <option value="<?= $s['id_mapel'] ?>">
                                    <?= htmlspecialchars($s['nama_mapel']) ?> (<?= $s['id_mapel'] ?> &bull; <?= $jLabel ?>)
                                </option>
                            <?php } ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Pilih Kelas</label>
                        <select class="form-select" name="id_kelas" required>
                            <option value="">-- Pilih Kelas --</option>
                            <?php foreach ($classes as $c) { ?>
                                <option value="<?= $c['id_kelas'] ?>"><?= htmlspecialchars($c['nama_kelas']) ?> (Tingkat <?= $c['tingkat'] ?>)</option>
                            <?php } ?>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan Penugasan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Edit Penugasan (satu modal dipakai ulang untuk semua baris) -->
<div class="modal fade" id="ModalEditPengampu" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="controllers/pengampu.php" method="POST" id="formEditPengampu">
                <input type="hidden" name="action" value="edit">
                <input type="hidden" name="id_pengampu" id="editIdPengampu" value="">
                <input type="hidden" name="page" value="<?= $page ?>">
                <input type="hidden" name="per_page" value="<?= $perPage ?>">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Penugasan Mengajar</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-light border small text-muted py-2">
                        <i class="fa-solid fa-circle-info text-primary me-1"></i>
                        Daftar tetap diurutkan dan dikelompokkan per kelas. Setelah disimpan,
                        halaman akan diarahkan ke posisi baris ini pada kelompoknya.
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="editIdGuru">Pilih Guru</label>
                        <select class="form-select" name="id_guru" id="editIdGuru" required>
                            <option value="">-- Pilih Guru --</option>
                            <?php foreach ($teachers as $t) { ?>
                                <option value="<?= htmlspecialchars($t['id_guru']) ?>"><?= htmlspecialchars($t['nama_guru']) ?> (<?= htmlspecialchars($t['id_guru']) ?>)</option>
                            <?php } ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="editIdMapel">Pilih Mata Pelajaran</label>
                        <select class="form-select" name="id_mapel" id="editIdMapel" required>
                            <option value="">-- Pilih Mapel --</option>
                            <?php foreach ($subjects as $s) {
                                $jMap = trim((string)($s['jenjang_terpakai'] ?? ''));
                                $jLabel = $jMap === '' ? 'Belum dipetakan' : strtr($jMap, ['10' => 'X', '11' => 'XI', '12' => 'XII']);
                            ?>
                                <option value="<?= htmlspecialchars($s['id_mapel']) ?>">
                                    <?= htmlspecialchars($s['nama_mapel']) ?> (<?= htmlspecialchars($s['id_mapel']) ?> &bull; <?= $jLabel ?>)
                                </option>
                            <?php } ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="editIdKelas">Pilih Kelas</label>
                        <select class="form-select" name="id_kelas" id="editIdKelas" required>
                            <option value="">-- Pilih Kelas --</option>
                            <?php foreach ($classes as $c) { ?>
                                <option value="<?= (int)$c['id_kelas'] ?>"><?= htmlspecialchars($c['nama_kelas']) ?> (Tingkat <?= (int)$c['tingkat'] ?>)</option>
                            <?php } ?>
                        </select>
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

<!-- Modal Upload Excel Penugasan -->
<div class="modal fade" id="ModalUploadPengampu" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="controllers/pengampu.php" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="action" value="upload_excel">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="fa-solid fa-file-arrow-up text-primary me-2"></i>Upload Penugasan Guru (Excel / CSV)</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3 p-3 bg-light rounded border d-flex justify-content-between align-items-center">
                        <div>
                            <span class="fw-semibold d-block text-dark small"><i class="fa-solid fa-file-excel text-success me-1"></i> Template Penugasan Mengajar</span>
                            <small class="text-muted">Unduh format baku sebelum mengunggah</small>
                        </div>
                        <a href="controllers/template.php?type=penugasan" class="btn btn-outline-success btn-sm">
                            <i class="fa-solid fa-download me-1"></i> Download Template
                        </a>
                    </div>
                    <div class="alert alert-info py-2 small">
                        Format kolom file Excel: <code>id_guru</code>, <code>nama_guru</code> (opsional), <code>id_mapel</code>, <code>nama_mapel</code> (opsional), <code>nama_kelas</code> (contoh: X-1, XI-5).
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Pilih File (.xlsx atau .csv)</label>
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

<!-- Modal Reset Semua Penugasan -->
<div class="modal fade" id="ModalResetPengampu" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form action="controllers/pengampu.php" method="POST">
                <input type="hidden" name="action" value="reset">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title"><i class="fa-solid fa-triangle-exclamation me-2"></i>Reset Seluruh Penugasan Guru</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p class="mb-2 fw-semibold text-danger">Peringatan: Tindakan ini akan mengosongkan seluruh data penugasan mengajar guru!</p>
                    <p class="small text-muted mb-0">
                        Total <strong><?= $total ?> penugasan</strong> yang saat ini ada di sistem akan dihapus. Anda dapat mengisi ulang kembali penugasan mengajar dengan mengunggah template Excel penugasan.
                    </p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-danger fw-bold">Ya, Reset Semua Penugasan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
(function () {
    const modalEl = document.getElementById('ModalEditPengampu');
    if (!modalEl) { return; }

    document.querySelectorAll('.js-edit-pengampu').forEach(function (btn) {
        btn.addEventListener('click', function () {
            document.getElementById('editIdPengampu').value = btn.dataset.id;
            document.getElementById('editIdGuru').value     = btn.dataset.guru;
            document.getElementById('editIdMapel').value    = btn.dataset.mapel;
            document.getElementById('editIdKelas').value    = btn.dataset.kelas;
        });
    });
})();
</script>
