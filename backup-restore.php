<?php
include_once "config/Database.php";
require_once "models/Backup.php";
require_once "models/Setting.php";

$db = new Database();
$conn = $db->connect();

$backupModel  = new Backup($conn);
$settingModel = new Setting($conn);
$setting      = $settingModel->get();

$role = $_SESSION['role'] ?? '';
if ($role !== 'admin') {
    echo "<div class='alert alert-danger'>Akses ditolak. Hanya Administrator yang dapat mengakses halaman ini.</div>";
    return;
}

$stats = $backupModel->getDatabaseStats();
$dbName = mysqli_fetch_assoc(mysqli_query($conn, "SELECT DATABASE() as db"))['db'] ?? 'db_raport';
?>

<div class="col-lg-9 mt-2">
    <!-- Header Banner -->
    <div class="card shadow-sm border-0 mb-4 text-white" style="background: linear-gradient(135deg, #0f172a, #1e3a8a) !important; border-radius: 18px;">
        <div class="card-body p-4">
            <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
                <div>
                    <span class="badge bg-info text-white px-3 py-1 mb-2 fw-semibold">
                        <i class="fa-solid fa-database me-1"></i> SISTEM KESELAMATAN DATA
                    </span>
                    <h4 class="fw-bold mb-1">Backup &amp; Restore Database</h4>
                    <p class="mb-0 opacity-90 small">
                        Pencadangan dan pemulihan berkas basis data e-Raport STS secara utuh &bull; <?= htmlspecialchars($setting['nama_sekolah']) ?>
                    </p>
                </div>
                <div class="text-end">
                    <a href="controllers/backup.php?action=download" class="btn btn-warning fw-bold px-3 py-2 shadow-sm">
                        <i class="fa-solid fa-download me-1"></i> Download Backup (.sql)
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Metrik Statistik Database -->
    <div class="row g-3 mb-4">
        <div class="col-sm-6 col-xl-3">
            <div class="card shadow-sm border-0 p-3 h-100">
                <div class="d-flex align-items-center">
                    <div class="bg-primary-subtle text-primary p-3 rounded me-3 fs-4">
                        <i class="fa-solid fa-server"></i>
                    </div>
                    <div>
                        <span class="text-muted small">Database Aktif</span>
                        <h5 class="fw-bold mb-0 font-monospace text-primary"><?= htmlspecialchars($dbName) ?></h5>
                        <small class="text-muted">MySQL / MariaDB</small>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-sm-6 col-xl-3">
            <div class="card shadow-sm border-0 p-3 h-100">
                <div class="d-flex align-items-center">
                    <div class="bg-info-subtle text-info p-3 rounded me-3 fs-4">
                        <i class="fa-solid fa-table-list"></i>
                    </div>
                    <div>
                        <span class="text-muted small">Jumlah Tabel</span>
                        <h4 class="fw-bold mb-0 text-dark"><?= $stats['total_tabel'] ?> Tabel</h4>
                        <small class="text-muted">Tabel Sistem Terlindungi</small>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-sm-6 col-xl-3">
            <div class="card shadow-sm border-0 p-3 h-100">
                <div class="d-flex align-items-center">
                    <div class="bg-success-subtle text-success p-3 rounded me-3 fs-4">
                        <i class="fa-solid fa-list-check"></i>
                    </div>
                    <div>
                        <span class="text-muted small">Total Data / Baris</span>
                        <h4 class="fw-bold mb-0 text-success"><?= number_format($stats['total_baris']) ?> Baris</h4>
                        <small class="text-muted">Termasuk Siswa &amp; Guru</small>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-sm-6 col-xl-3">
            <div class="card shadow-sm border-0 p-3 h-100">
                <div class="d-flex align-items-center">
                    <div class="bg-warning-subtle text-warning p-3 rounded me-3 fs-4">
                        <i class="fa-solid fa-hard-drive"></i>
                    </div>
                    <div>
                        <span class="text-muted small">Ukuran Penyimpanan</span>
                        <h4 class="fw-bold mb-0 text-dark"><?= $stats['total_mb'] > 0 ? $stats['total_mb'] . ' MB' : $stats['total_kb'] . ' KB' ?></h4>
                        <small class="text-muted">Data &amp; Indeks Fisik</small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Dua Kolom: Backup & Restore -->
    <div class="row g-4 mb-4">
        <!-- Kolom Kiri: Backup -->
        <div class="col-lg-6">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0 fw-bold text-primary">
                        <i class="fa-solid fa-file-export me-2"></i>Pencadangan Data (Backup)
                    </h5>
                </div>
                <div class="card-body d-flex flex-column justify-content-between p-4">
                    <div>
                        <p class="text-muted small mb-3">
                            Fitur ini akan menghasilkan berkas dump SQL murni (<code>.sql</code>) yang memuat seluruh struktur skema dan data operasional sekolah:
                        </p>
                        <ul class="small text-muted mb-4 ps-3">
                            <li>Data Pengguna, Akun Login Admin, Guru, Wali Kelas, &amp; Siswa</li>
                            <li>Data Peserta Didik Dapodik (NIS, NISN, Rombel)</li>
                            <li>Data Kelas, Wali Kelas, &amp; Penugasan Mengajar Guru (484 Pengampu)</li>
                            <li>Daftar Referensi Mata Pelajaran &amp; Mapping Urutan Rapor</li>
                            <li>Nilai Sumatif 1&ndash;4, Nilai ATS, dan Catatan Ketidakhadiran (S/I/A)</li>
                            <li>Konfigurasi Satuan Pendidikan, NPSN, dan Profil Sekolah</li>
                        </ul>
                        <div class="alert alert-success-subtle border border-success py-2 px-3 small text-success-emphasis mb-4">
                            <i class="fa-solid fa-shield-check me-1"></i>
                            Proses pencadangan bersifat <em>read-only</em>, tidak mengubah atau menghapus data aktif di database.
                        </div>
                    </div>

                    <a href="controllers/backup.php?action=download" class="btn btn-primary w-100 py-2 fw-bold shadow-sm">
                        <i class="fa-solid fa-cloud-arrow-down me-1"></i> Download Berkas Backup (.sql)
                    </a>
                </div>
            </div>
        </div>

        <!-- Kolom Kanan: Restore -->
        <div class="col-lg-6">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0 fw-bold text-danger">
                        <i class="fa-solid fa-file-import me-2"></i>Pemulihan Data (Restore)
                    </h5>
                </div>
                <div class="card-body p-4">
                    <form action="controllers/backup.php" method="POST" enctype="multipart/form-data" id="formRestoreDatabase"
                          onsubmit="return validateRestoreForm();">
                        <input type="hidden" name="action" value="restore">

                        <div class="alert alert-warning py-2 px-3 small mb-3">
                            <i class="fa-solid fa-triangle-exclamation me-1"></i>
                            <strong>Perhatian:</strong> Memulihkan database akan memperbarui tabel sistem dengan data dari berkas <code>.sql</code> yang diunggah. Pastikan berkas berasal dari backup e-Raport resmi.
                        </div>

                        <div class="mb-3">
                            <label class="form-label small fw-semibold">Pilih Berkas Backup (.sql):</label>
                            <input type="file" class="form-control form-control-sm" name="backup_file" id="inputBackupFile" accept=".sql" required>
                        </div>

                        <div class="form-check mb-4">
                            <input class="form-check-input" type="checkbox" id="checkKonfirmasiRestore" required>
                            <label class="form-check-label small text-muted user-select-none" for="checkKonfirmasiRestore">
                                Saya mengerti dan yakin ingin memulihkan database dari berkas backup yang dipilih.
                            </label>
                        </div>

                        <button type="submit" class="btn btn-outline-danger w-100 py-2 fw-bold" id="btnSubmitRestore">
                            <i class="fa-solid fa-clock-rotate-left me-1"></i> Pulihkan Database Sekarang
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Ringkasan Rincian Tabel Sistem -->
    <div class="card shadow-sm border-0">
        <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
            <h6 class="mb-0 fw-bold text-dark">
                <i class="fa-solid fa-shield-halved text-success me-2"></i>Rincian Tabel Basis Data (Status Terkunci &amp; Terlindungi)
            </h6>
            <span class="badge bg-success-subtle text-success border border-success px-2 py-1 small">
                <i class="fa-solid fa-lock me-1"></i> Data Aman
            </span>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr class="text-center">
                            <th style="width: 6%">No</th>
                            <th class="text-start">Nama Tabel</th>
                            <th style="width: 25%">Kategori Data</th>
                            <th style="width: 18%">Jumlah Baris Data</th>
                            <th style="width: 18%">Ukuran Fisik</th>
                            <th style="width: 15%">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $no = 1;
                        foreach ($stats['tabel_list'] as $tbl) {
                            $namaTbl = $tbl['nama'];
                            $kategoriLabel = match ($namaTbl) {
                                'tb_user' => 'Autentikasi Akun',
                                'tb_admin' => 'Data Administrator',
                                'tb_guru' => 'Data Dewan Guru',
                                'tb_kelas' => 'Rombongan Belajar',
                                'tb_siswa' => 'Data Siswa Dapodik',
                                'tb_mapel_referensi' => 'Master Mata Pelajaran',
                                'tb_mapel_mapping' => 'Mapping Rapor Jenjang',
                                'tb_pengampu' => 'Penugasan Mengajar',
                                'tb_nilai_sts' => 'Nilai Sumatif & ATS',
                                'tb_presensi_sts' => 'Ketidakhadiran (S/I/A)',
                                'tb_pengaturan_rapor' => 'Profil & Identitas Sekolah',
                                'tb_pengaturan_bobot' => 'Parameter Bobot STS',
                                default => 'Tabel Sistem'
                            };
                        ?>
                        <tr class="text-center">
                            <td><?= $no++ ?></td>
                            <td class="text-start font-monospace fw-semibold text-primary">
                                <i class="fa-solid fa-table me-1 text-secondary opacity-75"></i>
                                <?= htmlspecialchars($namaTbl) ?>
                            </td>
                            <td><span class="badge bg-light text-dark border"><?= $kategoriLabel ?></span></td>
                            <td class="fw-bold"><?= number_format($tbl['baris']) ?> baris</td>
                            <td class="text-muted font-monospace small"><?= $tbl['ukuran_kb'] ?> KB</td>
                            <td>
                                <span class="badge bg-success-subtle text-success border border-success">
                                    <i class="fa-solid fa-check me-1"></i> Normal
                                </span>
                            </td>
                        </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
function validateRestoreForm() {
    const fileInput = document.getElementById('inputBackupFile');
    const check = document.getElementById('checkKonfirmasiRestore');

    if (!fileInput.files || fileInput.files.length === 0) {
        alert('Harap pilih berkas backup .sql terlebih dahulu.');
        return false;
    }

    const fileName = fileInput.files[0].name.toLowerCase();
    if (!fileName.endsWith('.sql')) {
        alert('Berkas yang dipilih harus berekstensi .sql');
        return false;
    }

    if (!check.checked) {
        alert('Harap beri tanda centang pada kotak konfirmasi.');
        return false;
    }

    return confirm('KONFIRMASI AKHIR:\n\nApakah Anda benar-benar yakin ingin memulihkan database dari berkas "' + fileInput.files[0].name + '"?\n\nTindakan ini akan menggantikan data tabel sistem dengan isi berkas backup.');
}
</script>
