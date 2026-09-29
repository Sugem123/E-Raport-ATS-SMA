<?php
include_once "config/Database.php";
require_once "models/Mapel.php";
require_once "models/MapelMapping.php";

$db = new Database();
$conn = $db->connect();

$mapelModel   = new Mapel($conn);
$mappingModel = new MapelMapping($conn);

$jenjang = $_GET['jenjang'] ?? '10';
if (!in_array((string)$jenjang, MapelMapping::JENJANG, true)) {
    $jenjang = '10';
}

$options   = $mapelModel->getOptions();
$mapping   = $mappingModel->getByJenjang($jenjang, true);
$stat      = $mappingModel->getStatistik();

$jenjangInfo = [
    '10' => ['label' => 'Kelas X',   'fase' => 'Fase E', 'color' => 'primary'],
    '11' => ['label' => 'Kelas XI',  'fase' => 'Fase F', 'color' => 'success'],
    '12' => ['label' => 'Kelas XII', 'fase' => 'Fase F', 'color' => 'warning'],
];
$info = $jenjangInfo[$jenjang];

// Mapel yang belum punya mapping di jenjang ini (untuk form tambah)
$sudahDiJenjang = [];
foreach ($mapping as $m) {
    $sudahDiJenjang[$m['id_mapel']] = true;
}
$belumTerpakai = array_values(array_filter(
    $options,
    function ($o) use ($sudahDiJenjang) {
        return !isset($sudahDiJenjang[$o['id_mapel']]);
    }
));

$nextUrutan = 0;
foreach ($mapping as $m) {
    if ((int)$m['urutan'] > $nextUrutan) {
        $nextUrutan = (int)$m['urutan'];
    }
}
$nextUrutan++;
?>

<div class="col-lg-9 mt-2">
    <div class="card shadow-sm border-0">
        <div class="card-header bg-white py-3 d-flex flex-wrap justify-content-between align-items-center gap-2">
            <div>
                <h5 class="mb-0 fw-bold text-primary">
                    <i class="fa-solid fa-list-ol me-2"></i>Mapping Mapel per Jenjang
                </h5>
                <small class="text-muted">Menentukan urutan &amp; kategori mapel pada saat cetak rapor.</small>
            </div>
            <div class="d-flex gap-2">
                <a href="subjects" class="btn btn-outline-primary btn-sm">
                    <i class="fa-solid fa-book me-1"></i> Data Referensi
                </a>
                <a href="controllers/template.php?type=mapping" class="btn btn-outline-success btn-sm">
                    <i class="fa-solid fa-file-excel me-1"></i> Download Template
                </a>
                <button type="button" class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#ModalUploadMapping">
                    <i class="fa-solid fa-upload me-1"></i> Upload Excel
                </button>
                <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#ModalInputMapping"
                        <?= empty($belumTerpakai) ? 'disabled' : '' ?>>
                    <i class="fa-solid fa-plus me-1"></i> Tambah Mapel
                </button>
            </div>
        </div>
        <div class="card-body">
            <div class="alert alert-light border small text-muted mb-3 py-2">
                <i class="fa-solid fa-circle-info me-1"></i>
                Satu mata pelajaran cukup dibuat <strong>satu kali</strong> di
                <a href="subjects" class="fw-bold">Data Referensi</a>, lalu bisa dipetakan ke beberapa jenjang
                dengan kategori &amp; urutan yang berbeda.
                Contoh: <strong>Fisika</strong> → Kelas X sebagai <em>Umum</em>, Kelas XI sebagai <em>Pilihan</em>.
            </div>

            <!-- Tab Jenjang -->
            <ul class="nav nav-pills mb-3">
                <?php foreach (MapelMapping::JENJANG as $j) { ?>
                    <li class="nav-item">
                        <a class="nav-link btn-sm <?= $jenjang === $j ? 'active' : '' ?>"
                           href="subject-mapping?jenjang=<?= $j ?>">
                            <?= $jenjangInfo[$j]['label'] ?>
                            <span class="badge bg-light text-dark ms-1"><?= $stat[$j]['terpakai'] ?></span>
                        </a>
                    </li>
                <?php } ?>
            </ul>

            <div class="d-flex flex-wrap justify-content-between align-items-center mb-2">
                <div>
                    <h6 class="mb-0 text-secondary">
                        <?= $info['label'] ?> <small class="text-muted">(<?= $info['fase'] ?>)</small>
                    </h6>
                    <small class="text-muted"><i class="fa-solid fa-arrows-up-down text-primary me-1"></i> Geser baris ke atas/bawah atau klik panah untuk mengatur urutan cetak rapor (nomor otomatis menyesuaikan).</small>
                </div>
                <div class="d-flex align-items-center gap-2">
                    <span id="toastSaveStatus" class="badge bg-success-subtle text-success border border-success py-1 px-2 small d-none">
                        <i class="fa-solid fa-check me-1"></i> Urutan otomatis tersimpan
                    </span>
                    <button type="button" class="btn btn-sm btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#ModalCopyMapping">
                        <i class="fa-solid fa-copy me-1"></i> Salin dari Jenjang Lain
                    </button>
                </div>
            </div>

            <style>
                .row-mapel-item {
                    transition: background-color 0.15s ease;
                }
                .row-mapel-item.dragging {
                    opacity: 0.4;
                    background-color: #e0f2fe !important;
                }
                .row-mapel-item.drag-over-top {
                    border-top: 3px solid #2563eb !important;
                }
                .row-mapel-item.drag-over-bottom {
                    border-bottom: 3px solid #2563eb !important;
                }
                .drag-handle {
                    cursor: grab;
                    padding: 4px 6px;
                    border-radius: 4px;
                }
                .drag-handle:hover {
                    background-color: #f1f5f9;
                    color: #2563eb !important;
                }
                .drag-handle:active {
                    cursor: grabbing;
                }
            </style>

            <div class="table-responsive">
                <table class="table table-hover align-middle" id="tableReorderMapping">
                    <thead class="table-light">
                        <tr>
                            <th style="width: 16%" class="text-center"><i class="fa-solid fa-up-down me-1"></i> Urutan</th>
                            <th style="width: 16%">Kode Mapel</th>
                            <th>Nama Mata Pelajaran</th>
                            <th style="width: 20%">Kategori</th>
                            <th style="width: 12%" class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="tbodyReorderMapping">
                        <?php if (empty($mapping)) { ?>
                            <tr>
                                <td colspan="5" class="text-center py-4 text-muted">
                                    Belum ada mapel yang dipetakan ke <?= $info['label'] ?>.
                                    <br><small>Tambahkan mapel atau salin dari jenjang lain.</small>
                                </td>
                            </tr>
                        <?php } else {
                            foreach ($mapping as $m) {
                                $isPilihan = ($m['kategori'] === 'Pilihan');
                        ?>
                                <tr class="row-mapel-item" draggable="true" data-id-mapel="<?= htmlspecialchars($m['id_mapel']) ?>">
                                    <td class="text-center user-select-none">
                                        <div class="d-inline-flex align-items-center gap-1">
                                            <span class="drag-handle text-muted" title="Klik dan geser atas/bawah">
                                                <i class="fa-solid fa-grip-vertical fa-lg"></i>
                                            </span>
                                            <div class="btn-group btn-group-sm" role="group">
                                                <button type="button" class="btn btn-outline-secondary btn-sm px-1 py-0 btn-move-up" title="Geser Naik">
                                                    <i class="fa-solid fa-chevron-up" style="font-size: 10px;"></i>
                                                </button>
                                                <button type="button" class="btn btn-outline-secondary btn-sm px-1 py-0 btn-move-down" title="Geser Turun">
                                                    <i class="fa-solid fa-chevron-down" style="font-size: 10px;"></i>
                                                </button>
                                            </div>
                                            <span class="badge bg-dark rounded-pill px-3 py-1 fs-6 badge-urutan"><?= (int)$m['urutan'] ?></span>
                                        </div>
                                    </td>
                                    <td class="fw-bold font-monospace text-dark"><?= htmlspecialchars($m['id_mapel']) ?></td>
                                    <td class="fw-semibold text-dark"><?= htmlspecialchars($m['nama_mapel']) ?></td>
                                    <td>
                                        <?php if ($isPilihan) { ?>
                                            <span class="badge bg-info">Pilihan / Peminatan</span>
                                        <?php } else { ?>
                                            <span class="badge bg-primary">Umum (Wajib)</span>
                                        <?php } ?>
                                    </td>
                                    <td class="text-center">
                                        <button type="button" class="btn btn-sm btn-outline-primary"
                                                data-bs-toggle="modal" data-bs-target="#ModalEditMapping"
                                                data-id="<?= htmlspecialchars($m['id_mapel']) ?>"
                                                data-nama="<?= htmlspecialchars($m['nama_mapel']) ?>"
                                                data-kategori="<?= htmlspecialchars($m['kategori']) ?>"
                                                data-urutan="<?= (int)$m['urutan'] ?>">
                                            <i class="fa-solid fa-pen"></i>
                                        </button>
                                        <form action="controllers/mapping.php" method="POST" class="d-inline"
                                              onsubmit="return confirm('Hapus <?= htmlspecialchars($m['nama_mapel']) ?> dari <?= $info['label'] ?>?')">
                                            <input type="hidden" name="action" value="delete">
                                            <input type="hidden" name="id_mapel" value="<?= htmlspecialchars($m['id_mapel']) ?>">
                                            <input type="hidden" name="jenjang" value="<?= $jenjang ?>">
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

<!-- Modal Tambah Mapping -->
<div class="modal fade" id="ModalInputMapping" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="controllers/mapping.php" method="POST">
                <input type="hidden" name="action" value="input">
                <input type="hidden" name="jenjang" value="<?= $jenjang ?>">
                <div class="modal-header">
                    <h5 class="modal-title">Petakan Mapel ke <?= $info['label'] ?></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Mata Pelajaran</label>
                        <select class="form-select" name="id_mapel" required>
                            <option value="">-- Pilih dari Data Referensi --</option>
                            <?php foreach ($belumTerpakai as $o) { ?>
                                <option value="<?= htmlspecialchars($o['id_mapel']) ?>">
                                    <?= htmlspecialchars($o['nama_mapel']) ?> (<?= htmlspecialchars($o['id_mapel']) ?>)
                                </option>
                            <?php } ?>
                        </select>
                        <div class="form-text">
                            Hanya menampilkan mapel yang belum dipetakan di <?= $info['label'] ?>.
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Kategori di Rapor</label>
                        <select class="form-select" name="kategori" required>
                            <option value="Umum">Mata Pelajaran Umum (Wajib)</option>
                            <option value="Pilihan">Mata Pelajaran Pilihan / Peminatan</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Urutan Tampil di Rapor</label>
                        <input type="number" class="form-control" name="urutan"
                               value="<?= $nextUrutan ?>" min="1" required>
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

<!-- Modal Edit Mapping -->
<div class="modal fade" id="ModalEditMapping" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="controllers/mapping.php" method="POST">
                <input type="hidden" name="action" value="update">
                <input type="hidden" name="jenjang" value="<?= $jenjang ?>">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Penempatan Mapel</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Mata Pelajaran</label>
                        <input type="text" class="form-control" id="em_nama" disabled>
                        <input type="hidden" name="id_mapel" id="em_id">
                        <div class="form-text">Kode mapel tidak diubah di sini (ubah di Data Referensi).</div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Kategori di Rapor</label>
                        <select class="form-select" name="kategori" id="em_kategori" required>
                            <option value="Umum">Mata Pelajaran Umum (Wajib)</option>
                            <option value="Pilihan">Mata Pelajaran Pilihan / Peminatan</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Urutan Tampil di Rapor</label>
                        <input type="number" class="form-control" name="urutan" id="em_urutan" min="1" required>
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

<!-- Modal Salin dari Jenjang Lain -->
<div class="modal fade" id="ModalCopyMapping" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="controllers/mapping.php" method="POST">
                <input type="hidden" name="action" value="copy">
                <div class="modal-header">
                    <h5 class="modal-title">Salin Mapping antar Jenjang</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-warning py-2 small">
                        Berguna untuk mewarisi struktur mapel, misalnya menyalin mapel Kelas XI ke Kelas XII.
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Salin Dari Jenjang</label>
                        <select class="form-select" name="dari" required>
                            <?php foreach (MapelMapping::JENJANG as $j) {
                                if ($j === $jenjang) continue; ?>
                                <option value="<?= $j ?>"><?= $jenjangInfo[$j]['label'] ?> (<?= $stat[$j]['terpakai'] ?> mapel)</option>
                            <?php } ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Ke Jenjang</label>
                        <select class="form-select" name="ke" required>
                            <?php foreach (MapelMapping::JENJANG as $j) {
                                if ($j === $jenjang) continue; ?>
                                <option value="<?= $j ?>"><?= $jenjangInfo[$j]['label'] ?></option>
                            <?php } ?>
                        </select>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="timpa" value="1" id="chkTimpa">
                        <label class="form-check-label" for="chkTimpa">
                            Timpa urutan/kategori yang sudah ada di jenjang tujuan
                        </label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-warning">Salin Mapping</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Upload Excel Mapping -->
<div class="modal fade" id="ModalUploadMapping" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="controllers/mapping.php" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="action" value="upload_excel">
                <div class="modal-header">
                    <h5 class="modal-title">Upload Mapping Mapel (Excel / CSV)</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3 p-3 bg-light rounded border d-flex justify-content-between align-items-center">
                        <div>
                            <span class="fw-semibold d-block text-dark small">
                                <i class="fa-solid fa-file-excel text-success me-1"></i> Template Mapping Mapel
                            </span>
                            <small class="text-muted">
                                Format: <code>id_mapel</code>, <code>jenjang</code>, <code>kategori</code>, <code>urutan</code>
                            </small>
                        </div>
                        <a href="controllers/template.php?type=mapping" class="btn btn-outline-success btn-sm">
                            <i class="fa-solid fa-download me-1"></i> Download
                        </a>
                    </div>
                    <div class="alert alert-info py-2 small">
                        Pastikan <code>id_mapel</code> sudah terdaftar di
                        <a href="subjects" class="fw-bold">Data Referensi</a> sebelum di-import.
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
const CURRENT_JENJANG = '<?= $jenjang ?>';

document.getElementById('ModalEditMapping')?.addEventListener('show.bs.modal', function (event) {
    const btn = event.relatedTarget;
    document.getElementById('em_id').value       = btn.dataset.id;
    document.getElementById('em_nama').value     = btn.dataset.nama + ' (' + btn.dataset.id + ')';
    document.getElementById('em_kategori').value = btn.dataset.kategori;
    document.getElementById('em_urutan').value   = btn.dataset.urutan;
});

// Drag & Drop Reordering & Arrow Buttons Logic
(function() {
    const tbody = document.getElementById('tbodyReorderMapping');
    if (!tbody) return;

    let draggedRow = null;

    tbody.addEventListener('dragstart', function(e) {
        const tr = e.target.closest('.row-mapel-item');
        if (!tr) return;
        draggedRow = tr;
        tr.classList.add('dragging');
        e.dataTransfer.effectAllowed = 'move';
        e.dataTransfer.setData('text/plain', tr.getAttribute('data-id-mapel'));
    });

    tbody.addEventListener('dragend', function(e) {
        if (draggedRow) {
            draggedRow.classList.remove('dragging');
            draggedRow = null;
        }
        document.querySelectorAll('.row-mapel-item').forEach(r => {
            r.classList.remove('drag-over-top', 'drag-over-bottom');
        });
        renumberAndSave();
    });

    tbody.addEventListener('dragover', function(e) {
        e.preventDefault();
        const tr = e.target.closest('.row-mapel-item');
        if (!tr || tr === draggedRow) return;

        const rect = tr.getBoundingClientRect();
        const next = (e.clientY - rect.top) / (rect.bottom - rect.top) > 0.5;

        document.querySelectorAll('.row-mapel-item').forEach(r => {
            r.classList.remove('drag-over-top', 'drag-over-bottom');
        });

        if (next) {
            tr.classList.add('drag-over-bottom');
            tbody.insertBefore(draggedRow, tr.nextSibling);
        } else {
            tr.classList.add('drag-over-top');
            tbody.insertBefore(draggedRow, tr);
        }
    });

    // Move Up and Down Arrow Click
    tbody.addEventListener('click', function(e) {
        const btnUp = e.target.closest('.btn-move-up');
        if (btnUp) {
            const tr = btnUp.closest('.row-mapel-item');
            const prev = tr.previousElementSibling;
            if (prev && prev.classList.contains('row-mapel-item')) {
                tbody.insertBefore(tr, prev);
                renumberAndSave();
            }
            return;
        }

        const btnDown = e.target.closest('.btn-move-down');
        if (btnDown) {
            const tr = btnDown.closest('.row-mapel-item');
            const next = tr.nextElementSibling;
            if (next && next.classList.contains('row-mapel-item')) {
                tbody.insertBefore(next, tr);
                renumberAndSave();
            }
            return;
        }
    });

    let saveTimeout = null;

    function renumberAndSave() {
        const rows = document.querySelectorAll('#tbodyReorderMapping tr.row-mapel-item');
        const orderList = [];

        rows.forEach((row, idx) => {
            const newUrutan = idx + 1;
            const badge = row.querySelector('.badge-urutan');
            if (badge) {
                badge.textContent = newUrutan;
            }
            // Update edit button dataset jika ada
            const editBtn = row.querySelector('button[data-bs-target="#ModalEditMapping"]');
            if (editBtn) {
                editBtn.dataset.urutan = newUrutan;
            }

            const idMapel = row.getAttribute('data-id-mapel');
            if (idMapel) {
                orderList.push(idMapel);
            }
        });

        // Auto-save ke database via AJAX Fetch
        clearTimeout(saveTimeout);
        saveTimeout = setTimeout(() => {
            sendReorderToServer(orderList);
        }, 150);
    }

    function sendReorderToServer(orderList) {
        const formData = new FormData();
        formData.append('action', 'reorder');
        formData.append('jenjang', CURRENT_JENJANG);
        formData.append('ajax', '1');
        orderList.forEach(id => formData.append('order[]', id));

        fetch('controllers/mapping.php', {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(res => res.json())
        .then(data => {
            const toast = document.getElementById('toastSaveStatus');
            if (toast) {
                toast.classList.remove('d-none');
                setTimeout(() => {
                    toast.classList.add('d-none');
                }, 2200);
            }
        })
        .catch(err => {
            console.error('Gagal menyimpan urutan:', err);
        });
    }
})();
</script>
