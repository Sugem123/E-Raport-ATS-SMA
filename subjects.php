<?php
include_once "config/Database.php";
require_once "models/Mapel.php";
require_once "models/MapelMapping.php";

$db = new Database();
$conn = $db->connect();

$mapelModel   = new Mapel($conn);
$mappingModel = new MapelMapping($conn);

$search   = trim($_GET['cari'] ?? '');
$subjects = $mapelModel->getAll($search !== '' ? $search : null);
$stat     = $mappingModel->getStatistik();

$jenjangBadge = [
    '10' => ['label' => 'Kelas X',  'color' => 'primary'],
    '11' => ['label' => 'Kelas XI', 'color' => 'success'],
    '12' => ['label' => 'Kelas XII','color' => 'warning'],
];
?>

<div class="col-lg-9 mt-2">
    <div class="card shadow-sm border-0">
        <div class="card-header bg-white py-3 d-flex flex-wrap justify-content-between align-items-center gap-2">
            <div>
                <h5 class="mb-0 fw-bold text-primary">
                    <i class="fa-solid fa-book me-2"></i>Data Referensi Mata Pelajaran
                </h5>
                <small class="text-muted">Daftar induk seluruh mata pelajaran yang dikenal sekolah.</small>
            </div>
            <div class="d-flex gap-2">
                <a href="subject-mapping" class="btn btn-info btn-sm">
                    <i class="fa-solid fa-list-ol me-1"></i> Mapping Mapel
                </a>
                <a href="controllers/template.php?type=mapel" class="btn btn-outline-success btn-sm">
                    <i class="fa-solid fa-file-excel me-1"></i> Download Template
                </a>
                <button type="button" class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#ModalUploadMapel">
                    <i class="fa-solid fa-upload me-1"></i> Upload Excel
                </button>
                <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#ModalInputMapel">
                    <i class="fa-solid fa-plus me-1"></i> Tambah Mapel
                </button>
            </div>
        </div>
        <div class="card-body">
            <div class="alert alert-info py-2 small">
                <i class="fa-solid fa-circle-info me-1"></i>
                Halaman ini hanya menyimpan <strong>nama mata pelajaran</strong>.
                Penempatan <strong>jenjang</strong>, <strong>kategori</strong>, dan <strong>urutan cetak rapor</strong>
                diatur terpisah di menu <a href="subject-mapping" class="fw-bold">Mapping Mapel</a>.
            </div>

            <!-- Ringkasanmapping per jenjang -->
            <div class="row g-2 mb-3">
                <div class="col-md-4">
                    <div class="card border-0 bg-light h-100">
                        <div class="card-body py-2 px-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="small fw-semibold"><?= $jenjangBadge['10']['label'] ?></span>
                                <span class="badge bg-primary"><?= $stat['10']['terpakai'] ?>/<?= $stat['10']['total'] ?></span>
                            </div>
                            <div class="progress mt-1" style="height: 4px;">
                                <div class="progress-bar bg-primary" style="width: <?= $stat['10']['total'] > 0 ? round($stat['10']['terpakai'] / $stat['10']['total'] * 100) : 0 ?>%"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card border-0 bg-light h-100">
                        <div class="card-body py-2 px-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="small fw-semibold"><?= $jenjangBadge['11']['label'] ?></span>
                                <span class="badge bg-success"><?= $stat['11']['terpakai'] ?>/<?= $stat['11']['total'] ?></span>
                            </div>
                            <div class="progress mt-1" style="height: 4px;">
                                <div class="progress-bar bg-success" style="width: <?= $stat['11']['total'] > 0 ? round($stat['11']['terpakai'] / $stat['11']['total'] * 100) : 0 ?>%"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card border-0 bg-light h-100">
                        <div class="card-body py-2 px-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="small fw-semibold"><?= $jenjangBadge['12']['label'] ?></span>
                                <span class="badge bg-warning text-dark"><?= $stat['12']['terpakai'] ?>/<?= $stat['12']['total'] ?></span>
                            </div>
                            <div class="progress mt-1" style="height: 4px;">
                                <div class="progress-bar bg-warning" style="width: <?= $stat['12']['total'] > 0 ? round($stat['12']['terpakai'] / $stat['12']['total'] * 100) : 0 ?>%"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Pencarian -->
            <form method="GET" action="subjects" class="d-flex justify-content-end mb-3">
                <div class="input-group input-group-sm" style="max-width: 320px;">
                    <input type="text" name="cari" class="form-control" placeholder="Cari kode atau nama mapel..."
                           value="<?= htmlspecialchars($search) ?>">
                    <button class="btn btn-outline-secondary" type="submit">
                        <i class="fa-solid fa-magnifying-glass"></i>
                    </button>
                    <?php if ($search !== '') { ?>
                        <a href="subjects" class="btn btn-outline-danger">
                            <i class="fa-solid fa-xmark"></i>
                        </a>
                    <?php } ?>
                </div>
            </form>

            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th style="width: 16%">Kode Mapel</th>
                            <th>Nama Mata Pelajaran</th>
                            <th style="width: 30%">Dipakai di Jenjang</th>
                            <th style="width: 10%" class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($subjects)) { ?>
                            <tr>
                                <td colspan="4" class="text-center py-4 text-muted">
                                    Belum ada data referensi mata pelajaran.
                                </td>
                            </tr>
                        <?php } else {
                            foreach ($subjects as $s) {
                                $jmlMapping = (int)$s['jml_mapping'];
                                $listJenjang = trim((string)$s['jenjang_terpakai']);
                        ?>
                                <tr>
                                    <td class="fw-bold font-monospace text-dark"><?= htmlspecialchars($s['id_mapel']) ?></td>
                                    <td class="fw-semibold text-dark"><?= htmlspecialchars($s['nama_mapel']) ?></td>
                                    <td>
                                        <?php if ($jmlMapping === 0) { ?>
                                            <span class="badge bg-secondary">Belum dipetakan</span>
                                        <?php } else {
                                            foreach (explode(',', $listJenjang) as $j) {
                                                $j = trim($j);
                                                $b = $jenjangBadge[$j] ?? ['label' => $j, 'color' => 'secondary'];
                                                $txt = ($b['color'] === 'warning') ? ' text-dark' : '';
                                                echo '<span class="badge bg-' . $b['color'] . $txt . ' me-1">' . htmlspecialchars($b['label']) . '</span> ';
                                            }
                                        } ?>
                                    </td>
                                    <td class="text-center">
                                        <button type="button" class="btn btn-sm btn-outline-primary"
                                                data-bs-toggle="modal" data-bs-target="#ModalEditMapel"
                                                data-id="<?= htmlspecialchars($s['id_mapel']) ?>"
                                                data-nama="<?= htmlspecialchars($s['nama_mapel']) ?>">
                                            <i class="fa-solid fa-pen"></i>
                                        </button>
                                        <form action="controllers/mapel.php" method="POST" class="d-inline"
                                              onsubmit="return confirm('Hapus mapel <?= htmlspecialchars($s['nama_mapel']) ?> beserta seluruh mapping-nya?')">
                                            <input type="hidden" name="action" value="delete">
                                            <input type="hidden" name="id_mapel" value="<?= htmlspecialchars($s['id_mapel']) ?>">
                                            <button type="submit" class="btn btn-sm btn-outline-danger">
                                                <i class="fa-solid fa-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                        <?php } } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Modal Tambah Mapel Referensi -->
<div class="modal fade" id="ModalInputMapel" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="controllers/mapel.php" method="POST">
                <input type="hidden" name="action" value="input">
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Mata Pelajaran (Referensi)</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Kode Mapel (Singkatan)</label>
                        <input type="text" class="form-control text-uppercase" name="id_mapel"
                               placeholder="Contoh: FIS, KIM, EKO, SOS" maxlength="20" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Nama Mata Pelajaran</label>
                        <input type="text" class="form-control" name="nama_mapel"
                               placeholder="Contoh: Fisika" required>
                    </div>
                    <div class="alert alert-light border small text-muted py-2 mb-0">
                        <i class="fa-solid fa-arrow-right me-1"></i>
                        Setelah disimpan, atur jenjang &amp; urutan cetaknya di menu
                        <strong>Mapping Mapel</strong>.
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

<!-- Modal Edit Mapel Referensi -->
<div class="modal fade" id="ModalEditMapel" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="controllers/mapel.php" method="POST">
                <input type="hidden" name="action" value="update">
                <input type="hidden" name="id_mapel_lama" id="edit_id_lama">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Mata Pelajaran (Referensi)</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Kode Mapel (Singkatan)</label>
                        <input type="text" class="form-control text-uppercase" name="id_mapel"
                               id="edit_id_mapel" maxlength="20" required>
                        <div class="form-text">
                            Mengubah kode akan otomatis memperbarui penugasan mengajar &amp; mapping.
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Nama Mata Pelajaran</label>
                        <input type="text" class="form-control" name="nama_mapel" id="edit_nama_mapel" required>
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

<!-- Modal Upload Excel Referensi -->
<div class="modal fade" id="ModalUploadMapel" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="controllers/mapel.php" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="action" value="upload_excel">
                <div class="modal-header">
                    <h5 class="modal-title">Upload Referensi Mapel (Excel / CSV)</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3 p-3 bg-light rounded border d-flex justify-content-between align-items-center">
                        <div>
                            <span class="fw-semibold d-block text-dark small">
                                <i class="fa-solid fa-file-excel text-success me-1"></i> Template Referensi Mapel
                            </span>
                            <small class="text-muted">Format: <code>id_mapel</code>, <code>nama_mapel</code></small>
                        </div>
                        <a href="controllers/template.php?type=mapel" class="btn btn-outline-success btn-sm">
                            <i class="fa-solid fa-download me-1"></i> Download
                        </a>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Pilih File (.xlsx atau .csv)</label>
                        <input type="file" class="form-control" name="excel_file" accept=".xlsx, .csv" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-success">Upload &amp; Import</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.getElementById('ModalEditMapel').addEventListener('show.bs.modal', function (event) {
    const btn = event.relatedTarget;
    document.getElementById('edit_id_lama').value    = btn.dataset.id;
    document.getElementById('edit_id_mapel').value   = btn.dataset.id;
    document.getElementById('edit_nama_mapel').value = btn.dataset.nama;
});
</script>
