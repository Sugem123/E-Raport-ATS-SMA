<?php
include_once "config/Database.php";
require_once "models/Kelas.php";
require_once "models/Teacher.php";

$db = new Database();
$conn = $db->connect();

$kelasModel = new Kelas($conn);
$teacherModel = new Teacher($conn);

$classes = $kelasModel->getAll();
$teachers = $teacherModel->getAll();

// Ambil seluruh siswa dikelompokkan per kelas (hanya 1 query efisien)
$allStudentsQuery = mysqli_query($conn, "
    SELECT s.nis, s.nisn, s.nama, s.id_kelas, s.id_user, u.username, k.nama_kelas
    FROM tb_siswa s
    INNER JOIN tb_user u ON s.id_user = u.id_user
    INNER JOIN tb_kelas k ON s.id_kelas = k.id_kelas
    ORDER BY s.nama ASC
");

$studentsByClass = [];
$allStudentsFlat = [];
while ($row = mysqli_fetch_assoc($allStudentsQuery)) {
    $studentsByClass[$row['id_kelas']][] = [
        'nis' => $row['nis'],
        'nisn' => $row['nisn'] ?? '-',
        'nama' => $row['nama'],
        'id_user' => (int)$row['id_user'],
        'id_kelas' => (int)$row['id_kelas'],
        'nama_kelas' => $row['nama_kelas']
    ];
    $allStudentsFlat[] = [
        'nis' => $row['nis'],
        'nama' => $row['nama'],
        'id_kelas' => (int)$row['id_kelas'],
        'nama_kelas' => $row['nama_kelas']
    ];
}
?>

<div class="col-lg-9 mt-2">
    <div class="card shadow-sm border-0">
        <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
            <h5 class="mb-0 fw-bold text-primary"><i class="fa-solid fa-chalkboard me-2"></i>Manajemen Data Kelas</h5>
            <div class="d-flex gap-2">
                <a href="controllers/template.php?type=kelas" class="btn btn-outline-success btn-sm">
                    <i class="fa-solid fa-file-excel me-1"></i> Download Template
                </a>
                <button type="button" class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#ModalUploadKelas">
                    <i class="fa-solid fa-upload me-1"></i> Upload Excel
                </button>
                <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#ModalInputKelas">
                    <i class="fa-solid fa-plus me-1"></i> Tambah Kelas
                </button>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th style="width: 5%">No</th>
                            <th>Nama Kelas</th>
                            <th>Tingkat</th>
                            <th>Wali Kelas</th>
                            <th style="width: 24%">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($classes)) { ?>
                            <tr><td colspan="5" class="text-center py-4 text-muted">Belum ada data kelas.</td></tr>
                        <?php } else {
                            $no = 1;
                            foreach ($classes as $c) {
                                $totalSiswaKelas = (int)($c['total_siswa'] ?? count($studentsByClass[$c['id_kelas']] ?? []));
                        ?>
                            <tr>
                                <td><?= $no++ ?></td>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <span class="fw-bold text-dark fs-6"><?= htmlspecialchars($c['nama_kelas']) ?></span>
                                        <button type="button" class="btn btn-outline-primary btn-sm rounded-pill px-2 py-0 btn-trigger-anggota"
                                                style="font-size: 11.5px; font-weight: 600;"
                                                data-id-kelas="<?= $c['id_kelas'] ?>"
                                                title="Klik untuk membuka daftar anggota kelas">
                                            <i class="fa-solid fa-users me-1"></i> <?= $totalSiswaKelas ?> Siswa
                                        </button>
                                    </div>
                                </td>
                                <td><span class="badge bg-secondary">Tingkat <?= htmlspecialchars($c['tingkat']) ?> (Fase <?= $c['tingkat'] === '10' ? 'E' : 'F' ?>)</span></td>
                                <td>
                                    <?php if (!empty($c['nama_walikelas'])) { ?>
                                        <i class="fa-solid fa-user-tie text-success me-1"></i> <?= htmlspecialchars($c['nama_walikelas']) ?>
                                    <?php } else { ?>
                                        <span class="text-muted fst-italic">Belum ditentukan</span>
                                    <?php } ?>
                                </td>
                                <td>
                                    <button class="btn btn-info btn-sm text-white me-1 btn-trigger-anggota"
                                            data-id-kelas="<?= $c['id_kelas'] ?>"
                                            title="Kelola Anggota Siswa (<?= $totalSiswaKelas ?> Siswa)">
                                        <i class="fa-solid fa-users me-1"></i> Anggota
                                    </button>
                                    <button class="btn btn-warning btn-sm me-1" data-bs-toggle="modal" data-bs-target="#ModalEditKelas<?= $c['id_kelas'] ?>" title="Edit Kelas">
                                        <i class="fa fa-edit"></i>
                                    </button>
                                    <button class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#ModalDeleteKelas<?= $c['id_kelas'] ?>" title="Hapus Kelas">
                                        <i class="fa fa-trash"></i>
                                    </button>
                                </td>
                            </tr>

                            <!-- Modal Edit Kelas -->
                            <div class="modal fade" id="ModalEditKelas<?= $c['id_kelas'] ?>" tabindex="-1">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <form action="controllers/kelas.php" method="POST">
                                            <input type="hidden" name="action" value="update">
                                            <input type="hidden" name="id_kelas" value="<?= $c['id_kelas'] ?>">
                                            <div class="modal-header">
                                                <h5 class="modal-title">Edit Data Kelas</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="mb-3">
                                                    <label class="form-label">Nama Kelas</label>
                                                    <input type="text" class="form-control" name="nama_kelas" value="<?= htmlspecialchars($c['nama_kelas']) ?>" required>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label">Tingkat</label>
                                                    <select class="form-select" name="tingkat" required>
                                                        <option value="10" <?= $c['tingkat'] == '10' ? 'selected' : '' ?>>10 (Fase E)</option>
                                                        <option value="11" <?= $c['tingkat'] == '11' ? 'selected' : '' ?>>11 (Fase F)</option>
                                                        <option value="12" <?= $c['tingkat'] == '12' ? 'selected' : '' ?>>12 (Fase F)</option>
                                                    </select>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label">Wali Kelas</label>
                                                    <select class="form-select" name="id_guru_walikelas">
                                                        <option value="">-- Tanpa Wali Kelas --</option>
                                                        <?php foreach ($teachers as $t) { ?>
                                                            <option value="<?= $t['id_guru'] ?>" <?= $c['id_guru_walikelas'] == $t['id_guru'] ? 'selected' : '' ?>>
                                                                <?= htmlspecialchars($t['nama_guru']) ?> (<?= $t['id_guru'] ?>)
                                                            </option>
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

                            <!-- Modal Delete Kelas -->
                            <div class="modal fade" id="ModalDeleteKelas<?= $c['id_kelas'] ?>" tabindex="-1">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <form action="controllers/kelas.php" method="POST">
                                            <input type="hidden" name="action" value="delete">
                                            <input type="hidden" name="id_kelas" value="<?= $c['id_kelas'] ?>">
                                            <div class="modal-header">
                                                <h5 class="modal-title">Hapus Kelas</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body">
                                                Yakin ingin menghapus kelas <strong><?= htmlspecialchars($c['nama_kelas']) ?></strong>?
                                                <br><small class="text-danger">*Siswa dan nilai pada kelas ini akan ikut terhapus.</small>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                                <button type="submit" class="btn btn-danger">Hapus</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>

                        <?php } } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- ======================================================================= -->
<!-- FLOATING MODAL UTAMA: DAFTAR & KELOLA ANGGOTA KELAS                     -->
<!-- ======================================================================= -->
<div class="modal fade" id="ModalAnggotaKelas" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content shadow-lg border-0" style="border-radius: 18px;">
            <div class="modal-header bg-light py-3 border-bottom">
                <div>
                    <h5 class="modal-title fw-bold text-primary mb-1">
                        <i class="fa-solid fa-users-rectangle me-2"></i>Anggota Siswa: <span id="labelNamaKelasModal">-</span>
                    </h5>
                    <div class="small text-muted">
                        <span class="badge bg-primary-subtle text-primary border border-primary px-2 py-1 me-2 fw-bold" id="badgeJumlahSiswaModal">
                            <i class="fa-solid fa-user-graduate me-1"></i> 0 Siswa
                        </span>
                        <span id="labelWaliKelasModal">Wali: -</span>
                    </div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body p-4">
                <!-- Informasi Rujukan Template & Rapor -->
                <div class="alert alert-light border small text-muted py-2 mb-3">
                    <i class="fa-solid fa-circle-info text-primary me-1"></i>
                    Daftar anggota siswa di bawah ini secara otomatis menjadi rujukan untuk <strong>Template Excel Nilai Guru</strong> dan <strong>Cetak Rapor STS</strong>.
                </div>

                <!-- Tombol Aksi Tambah & Pindah -->
                <div class="d-flex flex-wrap gap-2 mb-3">
                    <button class="btn btn-primary btn-sm" type="button" data-bs-toggle="collapse" data-bs-target="#formCollapseTambahBaru">
                        <i class="fa-solid fa-user-plus me-1"></i> + Tambah Siswa Baru
                    </button>
                    <button class="btn btn-outline-secondary btn-sm" type="button" data-bs-toggle="collapse" data-bs-target="#formCollapsePindahMasuk">
                        <i class="fa-solid fa-arrow-right-arrow-left me-1"></i> Masukkan Siswa dari Kelas Lain
                    </button>
                </div>

                <!-- COLLAPSIBLE FORM 1: Tambah Siswa Baru -->
                <div class="collapse mb-3" id="formCollapseTambahBaru">
                    <div class="card card-body bg-light border p-3">
                        <h6 class="fw-bold text-primary mb-2"><i class="fa-solid fa-user-plus me-1"></i> Daftarkan Siswa Baru ke Kelas Ini</h6>
                        <form action="controllers/kelas.php" method="POST">
                            <input type="hidden" name="action" value="add_student_new">
                            <input type="hidden" name="id_kelas" id="inputHiddenIdKelasTambah" value="">
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

                <!-- COLLAPSIBLE FORM 2: Masukkan Siswa dari Kelas Lain -->
                <div class="collapse mb-3" id="formCollapsePindahMasuk">
                    <div class="card card-body bg-light border p-3">
                        <h6 class="fw-bold text-success mb-2"><i class="fa-solid fa-arrow-right-arrow-left me-1"></i> Pindahkan Siswa Masuk ke Kelas Ini</h6>
                        <form action="controllers/kelas.php" method="POST">
                            <input type="hidden" name="action" value="move_student">
                            <input type="hidden" name="id_kelas_tujuan" id="inputHiddenIdKelasTujuan" value="">
                            <div class="row g-2 mb-2">
                                <div class="col-md-9">
                                    <label class="form-label small fw-semibold">Pilih Siswa</label>
                                    <select name="nis" id="selectSiswaLainModal" class="form-select form-select-sm" required>
                                        <option value="">-- Pilih Siswa --</option>
                                    </select>
                                </div>
                                <div class="col-md-3 d-flex align-items-end">
                                    <button type="submit" class="btn btn-success btn-sm w-100">
                                        <i class="fa-solid fa-arrow-right-to-bracket me-1"></i> Pindahkan
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Live Search Input -->
                <div class="input-group input-group-sm mb-3">
                    <span class="input-group-text bg-white"><i class="fa-solid fa-magnifying-glass text-muted"></i></span>
                    <input type="text" class="form-control" id="inputLiveSearchAnggota"
                           placeholder="Ketik nama atau NIS untuk mencari siswa di kelas ini...">
                </div>

                <!-- Tabel Anggota Siswa -->
                <div class="table-responsive">
                    <table class="table table-hover align-middle border mb-0" id="tableAnggotaModalBody">
                        <thead class="table-light">
                            <tr class="text-center">
                                <th style="width: 6%">No</th>
                                <th style="width: 14%">NIS</th>
                                <th style="width: 16%">NISN</th>
                                <th class="text-start">Nama Siswa</th>
                                <th style="width: 22%">Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="tbodyAnggotaModal">
                            <!-- Diisi secara dinamis oleh JavaScript -->
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="modal-footer bg-light py-2">
                <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

<!-- ======================================================================= -->
<!-- MODAL KECIL: PINDAHKAN SISWA KE KELAS LAIN                              -->
<!-- ======================================================================= -->
<div class="modal fade" id="ModalPindahSiswaGlobal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form action="controllers/kelas.php" method="POST">
                <input type="hidden" name="action" value="move_student">
                <input type="hidden" name="nis" id="moveModalNis" value="">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="fa-solid fa-arrow-right-arrow-left me-2 text-primary"></i>Pindahkan Siswa</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p class="mb-3">
                        Pindahkan siswa <strong id="moveModalNamaSiswa">-</strong> (NIS: <span id="moveModalNisDisplay" class="font-monospace fw-bold">-</span>) ke kelas lain:
                    </p>
                    <label class="form-label fw-semibold">Pilih Kelas Tujuan:</label>
                    <select name="id_kelas_tujuan" id="selectKelasTujuanPindah" class="form-select" required>
                        <!-- Diisi via JavaScript -->
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

<!-- ======================================================================= -->
<!-- MODAL KECIL: HAPUS SISWA DARI SISTEM                                    -->
<!-- ======================================================================= -->
<div class="modal fade" id="ModalHapusSiswaGlobal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form action="controllers/kelas.php" method="POST">
                <input type="hidden" name="action" value="delete_student">
                <input type="hidden" name="nis" id="deleteModalNis" value="">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title"><i class="fa-solid fa-triangle-exclamation me-2"></i>Hapus Siswa</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    Yakin ingin menghapus siswa <strong id="deleteModalNamaSiswa">-</strong> (NIS: <span id="deleteModalNisDisplay" class="font-monospace fw-bold">-</span>) dari sistem?
                    <br><small class="text-danger">*Seluruh data nilai dan akun login siswa ini akan ikut terhapus secara permanen.</small>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-danger">Hapus Permanen</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Tambah Kelas Baru -->
<div class="modal fade" id="ModalInputKelas" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="controllers/kelas.php" method="POST">
                <input type="hidden" name="action" value="input">
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Kelas Baru</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Nama Kelas</label>
                        <input type="text" class="form-control" name="nama_kelas" placeholder="Contoh: X-1, XI-MIPA-1" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Tingkat</label>
                        <select class="form-select" name="tingkat" required>
                            <option value="10">10 (Fase E)</option>
                            <option value="11">11 (Fase F)</option>
                            <option value="12">12 (Fase F)</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Wali Kelas</label>
                        <select class="form-select" name="id_guru_walikelas">
                            <option value="">-- Tanpa Wali Kelas --</option>
                            <?php foreach ($teachers as $t) { ?>
                                <option value="<?= $t['id_guru'] ?>"><?= htmlspecialchars($t['nama_guru']) ?> (<?= $t['id_guru'] ?>)</option>
                            <?php } ?>
                        </select>
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

<!-- Modal Upload Excel Kelas -->
<div class="modal fade" id="ModalUploadKelas" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="controllers/kelas.php" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="action" value="upload_excel">
                <div class="modal-header">
                    <h5 class="modal-title">Upload Data Kelas (Excel / CSV)</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3 p-3 bg-light rounded border d-flex justify-content-between align-items-center">
                        <div>
                            <span class="fw-semibold d-block text-dark small"><i class="fa-solid fa-file-excel text-success me-1"></i> Template Excel Kelas</span>
                            <small class="text-muted">Unduh format baku sebelum mengunggah data</small>
                        </div>
                        <a href="controllers/template.php?type=kelas" class="btn btn-outline-success btn-sm">
                            <i class="fa-solid fa-download me-1"></i> Download Template
                        </a>
                    </div>
                    <div class="alert alert-info py-2 small">
                        Gunakan file template Excel resmi agar format kolom sesuai (<code>nama_kelas</code>, <code>tingkat</code>, <code>id_guru_walikelas</code>).
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

<!-- Data Store untuk Dynamic Modal -->
<script>
const STORE_KELAS = <?= json_encode($classes, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP) ?>;
const STORE_SISWA_PER_KELAS = <?= json_encode($studentsByClass, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP) ?>;
const STORE_SEMUA_SISWA = <?= json_encode($allStudentsFlat, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP) ?>;

let currentActiveKelasId = null;

// Event handler saat tombol Anggota diklik
document.querySelectorAll('.btn-trigger-anggota').forEach(btn => {
    btn.addEventListener('click', function() {
        const idKelas = parseInt(this.getAttribute('data-id-kelas'));
        openModalAnggotaKelas(idKelas);
    });
});

function openModalAnggotaKelas(idKelas) {
    currentActiveKelasId = idKelas;
    const kelas = STORE_KELAS.find(k => parseInt(k.id_kelas) === idKelas);
    if (!kelas) return;

    const siswaList = STORE_SISWA_PER_KELAS[idKelas] || [];

    // Set Header
    document.getElementById('labelNamaKelasModal').textContent = 'Kelas ' + kelas.nama_kelas;
    document.getElementById('badgeJumlahSiswaModal').innerHTML = '<i class="fa-solid fa-user-graduate me-1"></i> ' + siswaList.length + ' Siswa Terdaftar';
    document.getElementById('labelWaliKelasModal').textContent = 'Tingkat ' + kelas.tingkat + ' (Fase ' + (kelas.tingkat === '10' ? 'E' : 'F') + ') • Wali: ' + (kelas.nama_walikelas || 'Belum ditentukan');

    // Set Hidden Inputs
    document.getElementById('inputHiddenIdKelasTambah').value = idKelas;
    document.getElementById('inputHiddenIdKelasTujuan').value = idKelas;

    // Reset Collapses
    const collapseTambah = bootstrap.Collapse.getInstance(document.getElementById('formCollapseTambahBaru'));
    if (collapseTambah) collapseTambah.hide();
    const collapsePindah = bootstrap.Collapse.getInstance(document.getElementById('formCollapsePindahMasuk'));
    if (collapsePindah) collapsePindah.hide();

    // Populate Select Siswa Lain
    const selectLain = document.getElementById('selectSiswaLainModal');
    selectLain.innerHTML = '<option value="">-- Pilih Siswa yang Akan Dipindahkan --</option>';
    STORE_SEMUA_SISWA.forEach(s => {
        if (s.id_kelas !== idKelas) {
            const opt = document.createElement('option');
            opt.value = s.nis;
            opt.textContent = s.nama + ' (NIS: ' + s.nis + ' • Kelas: ' + s.nama_kelas + ')';
            selectLain.appendChild(opt);
        }
    });

    // Reset Filter Input
    const searchInput = document.getElementById('inputLiveSearchAnggota');
    searchInput.value = '';

    // Render Table Rows
    renderTabelAnggota(siswaList);

    // Buka Modal
    const modalEl = document.getElementById('ModalAnggotaKelas');
    const modal = new bootstrap.Modal(modalEl);
    modal.show();
}

function renderTabelAnggota(siswaList) {
    const tbody = document.getElementById('tbodyAnggotaModal');
    tbody.innerHTML = '';

    if (siswaList.length === 0) {
        tbody.innerHTML = '<tr><td colspan="5" class="text-center py-4 text-muted">Belum ada siswa di kelas ini.</td></tr>';
        return;
    }

    siswaList.forEach((s, idx) => {
        const tr = document.createElement('tr');
        tr.className = 'text-center row-item-siswa';

        const safeNama = escapeHtml(s.nama);
        const safeNis = escapeHtml(s.nis);
        const safeNisn = escapeHtml(s.nisn || '-');

        tr.innerHTML = `
            <td>${idx + 1}</td>
            <td class="font-monospace fw-semibold cell-nis">${safeNis}</td>
            <td class="font-monospace text-muted small cell-nisn">${safeNisn}</td>
            <td class="text-start fw-semibold text-dark cell-nama">${safeNama}</td>
            <td>
                <div class="btn-group btn-group-sm">
                    <button type="button" class="btn btn-outline-secondary btn-action-pindah"
                            title="Pindahkan ke Kelas Lain"
                            data-nis="${safeNis}" data-nama="${safeNama}">
                        <i class="fa-solid fa-arrow-right-arrow-left"></i>
                    </button>
                    <button type="button" class="btn btn-outline-danger btn-action-hapus"
                            title="Hapus Siswa dari Sistem"
                            data-nis="${safeNis}" data-nama="${safeNama}">
                        <i class="fa-solid fa-trash"></i>
                    </button>
                    <a href="student-report.php?nis=${encodeURIComponent(s.nis)}" target="_blank" class="btn btn-outline-primary" title="Lihat Rapor">
                        <i class="fa-solid fa-print"></i>
                    </a>
                </div>
            </td>
        `;

        // Event listener Pindah
        tr.querySelector('.btn-action-pindah').addEventListener('click', () => {
            bukaModalPindahSiswa(s.nis, s.nama, s.id_kelas);
        });

        // Event listener Hapus
        tr.querySelector('.btn-action-hapus').addEventListener('click', () => {
            bukaModalHapusSiswa(s.nis, s.nama);
        });

        tbody.appendChild(tr);
    });
}

// Live Search Filter
document.getElementById('inputLiveSearchAnggota').addEventListener('input', function() {
    const q = this.value.toLowerCase().trim();
    const rows = document.querySelectorAll('#tbodyAnggotaModal tr.row-item-siswa');

    rows.forEach(r => {
        const nis = r.querySelector('.cell-nis')?.textContent.toLowerCase() || '';
        const nisn = r.querySelector('.cell-nisn')?.textContent.toLowerCase() || '';
        const nama = r.querySelector('.cell-nama')?.textContent.toLowerCase() || '';

        if (nis.includes(q) || nisn.includes(q) || nama.includes(q)) {
            r.style.display = '';
        } else {
            r.style.display = 'none';
        }
    });
});

// Helper Pindah Siswa
function bukaModalPindahSiswa(nis, nama, idKelasAsal) {
    document.getElementById('moveModalNis').value = nis;
    document.getElementById('moveModalNamaSiswa').textContent = nama;
    document.getElementById('moveModalNisDisplay').textContent = nis;

    const select = document.getElementById('selectKelasTujuanPindah');
    select.innerHTML = '';
    STORE_KELAS.forEach(k => {
        if (parseInt(k.id_kelas) !== parseInt(idKelasAsal)) {
            const opt = document.createElement('option');
            opt.value = k.id_kelas;
            opt.textContent = 'Kelas ' + k.nama_kelas + ' (Fase ' + (k.tingkat === '10' ? 'E' : 'F') + ')';
            select.appendChild(opt);
        }
    });

    const modal = new bootstrap.Modal(document.getElementById('ModalPindahSiswaGlobal'));
    modal.show();
}

// Helper Hapus Siswa
function bukaModalHapusSiswa(nis, nama) {
    document.getElementById('deleteModalNis').value = nis;
    document.getElementById('deleteModalNamaSiswa').textContent = nama;
    document.getElementById('deleteModalNisDisplay').textContent = nis;

    const modal = new bootstrap.Modal(document.getElementById('ModalHapusSiswaGlobal'));
    modal.show();
}

function escapeHtml(str) {
    return String(str)
        .replace(/&/g, '&amp;')
        .replace(/</g, '&lt;')
        .replace(/>/g, '&gt;')
        .replace(/"/g, '&quot;')
        .replace(/'/g, '&#039;');
}
</script>
