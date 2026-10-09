<?php
include_once "config/Database.php";
require_once "models/Setting.php";
require_once "models/Teacher.php";

$db = new Database();
$conn = $db->connect();

$settingModel = new Setting($conn);
$teacherModel = new Teacher($conn);

$settings = $settingModel->get();
$teachers = $teacherModel->getAll();
?>

<div class="col-lg-9 mt-2">
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center border-bottom">
            <h5 class="mb-0 fw-bold text-primary">
                <i class="fa-solid fa-school me-2"></i>Pengaturan Identitas Sekolah & Rapor
            </h5>
            <a href="landing" target="_blank" class="btn btn-outline-primary btn-sm">
                <i class="fa-solid fa-arrow-up-right-from-square me-1"></i> Lihat Halaman Depan
            </a>
        </div>
        <div class="card-body p-4">
            <div class="alert alert-light border small text-muted mb-4 py-3 rounded-3">
                <i class="fa-solid fa-circle-info text-primary me-1"></i>
                Identitas sekolah di bawah ini ditampilkan secara dinamis di <strong>Landing Page publik</strong>,
                <strong>Halaman Login</strong>, dan kop dokumen <strong>Cetak Rapor STS</strong>.
            </div>

            <form action="controllers/setting.php" method="POST" enctype="multipart/form-data" class="col-lg-10">

                <!-- BAGIAN 1: LOGO SEKOLAH -->
                <div class="card border mb-4 rounded-3 shadow-none">
                    <div class="card-header bg-light py-2 px-3 fw-semibold text-dark">
                        <i class="fa-solid fa-image me-1 text-primary"></i> Logo Satuan Pendidikan (Ikon & Watermark Rapor)
                    </div>
                    <div class="card-body p-3">
                        <div class="d-flex flex-wrap align-items-center gap-4">
                            <div class="text-center">
                                <div class="p-2 border rounded-4 bg-white shadow-sm d-flex align-items-center justify-content-center"
                                     style="width: 120px; height: 120px; overflow: hidden;">
                                    <?php if (!empty($settings['logo_sekolah']) && file_exists(__DIR__ . '/' . $settings['logo_sekolah'])) { ?>
                                        <img id="logoPreview" src="<?= htmlspecialchars($settings['logo_sekolah']) ?>?t=<?= time() ?>"
                                             alt="Logo Sekolah" style="max-width: 100%; max-height: 100%; object-fit: contain;">
                                    <?php } else { ?>
                                        <div id="logoPlaceholder" class="text-center text-muted">
                                            <i class="fa-solid fa-shield-halved fa-3x text-primary opacity-50 mb-1"></i>
                                            <div style="font-size: 10px;">Logo Default</div>
                                        </div>
                                        <img id="logoPreview" src="" alt="Preview" style="display: none; max-width: 100%; max-height: 100%; object-fit: contain;">
                                    <?php } ?>
                                </div>
                            </div>
                            <div class="flex-grow-1">
                                <label class="form-label fw-semibold mb-1">Unggah Logo Baru</label>
                                <input type="file" class="form-control mb-2" name="logo_file" accept=".png,.jpg,.jpeg,.svg,.webp" id="inputLogoFile">
                                <small class="text-muted d-block mb-2">Format: PNG, JPG, JPEG, WEBP, atau SVG (Maksimal 2 MB). Disarankan latar transparan.</small>
                                <?php if (!empty($settings['logo_sekolah'])) { ?>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="hapus_logo" value="1" id="checkHapusLogo">
                                        <label class="form-check-label text-danger small" for="checkHapusLogo">
                                            Hapus logo saat ini dan gunakan lambang default
                                        </label>
                                    </div>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- BAGIAN 1B: KOP SURAT RESMI (BANNER GAMBAR KOP CETAK) -->
                <div class="card border mb-4 rounded-3 shadow-none">
                    <div class="card-header bg-light py-2 px-3 fw-semibold text-dark d-flex justify-content-between align-items-center">
                        <div>
                            <i class="fa-solid fa-heading me-1 text-success"></i> Gambar Kop Surat Resmi (Untuk Lembar Cetak)
                        </div>
                        <span class="badge bg-success-subtle text-success border border-success small">Format Cetak A4</span>
                    </div>
                    <div class="card-body p-3">
                        <div class="alert alert-light border small text-muted mb-3 py-2">
                            <i class="fa-solid fa-circle-info text-primary me-1"></i>
                            Kop surat ini akan otomatis digunakan pada bagian paling atas <strong>Daftar Penerimaan Rapor</strong>, <strong>Lembar Rapor Siswa</strong>, dan <strong>Leger Nilai</strong>. Jika belum diunggah, sistem akan menggunakan <em>Placeholder Kop Surat Standar</em> berbasis identitas sekolah.
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold mb-1">Pratinjau Kop Surat Saat Ini</label>
                            <div class="p-3 border rounded-3 bg-white text-center shadow-sm" style="min-height: 110px; display: flex; align-items: center; justify-content: center; overflow: hidden;">
                                <?php if (!empty($settings['kop_surat']) && file_exists(__DIR__ . '/' . $settings['kop_surat'])) { ?>
                                    <img id="kopPreview" src="<?= htmlspecialchars($settings['kop_surat']) ?>?t=<?= time() ?>"
                                         alt="Kop Surat Resmi" style="max-width: 100%; max-height: 140px; object-fit: contain;">
                                <?php } else { ?>
                                    <div id="kopPlaceholderBox" class="w-100 p-2" style="border: 2px dashed #cbd5e1; border-radius: 8px; background: #f8fafc;">
                                        <div class="d-flex align-items-center justify-content-center gap-3">
                                            <?php if (!empty($settings['logo_sekolah']) && file_exists(__DIR__ . '/' . $settings['logo_sekolah'])) { ?>
                                                <img src="<?= htmlspecialchars($settings['logo_sekolah']) ?>" alt="Logo" style="width: 55px; height: 55px; object-fit: contain;">
                                            <?php } else { ?>
                                                <i class="fa-solid fa-graduation-cap fa-3x text-primary opacity-75"></i>
                                            <?php } ?>
                                            <div class="text-start">
                                                <div style="font-size: 11px; font-weight: 700; letter-spacing: 0.5px; text-transform: uppercase; color: #475569;">
                                                    PEMERINTAH PROVINSI JAWA TIMUR &bull; DINAS PENDIDIKAN
                                                </div>
                                                <div style="font-size: 15px; font-weight: 800; color: #0f172a;">
                                                    <?= htmlspecialchars($settings['nama_sekolah']) ?>
                                                </div>
                                                <div style="font-size: 10.5px; color: #64748b;">
                                                    <?= htmlspecialchars($settings['alamat_sekolah']) ?> &bull; NPSN: <?= htmlspecialchars($settings['npsn']) ?> &bull; Telp: <?= htmlspecialchars($settings['telepon']) ?>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="badge bg-secondary-subtle text-secondary border mt-2" style="font-size: 10px;">
                                            [ Placeholder Kop Standar Aktif &mdash; Unggah gambar jika ingin menggunakan kop desain resmi ]
                                        </div>
                                    </div>
                                    <img id="kopPreview" src="" alt="Preview" style="display: none; max-width: 100%; max-height: 140px; object-fit: contain;">
                                <?php } ?>
                            </div>
                        </div>

                        <div class="row align-items-center g-2">
                            <div class="col-md-8">
                                <label class="form-label fw-semibold mb-1">Unggah Berkas Gambar Kop Surat Baru</label>
                                <input type="file" class="form-control" name="kop_file" accept=".png,.jpg,.jpeg,.svg,.webp" id="inputKopFile">
                                <small class="text-muted d-block mt-1">Disarankan gambar horizontal memanjang (Rasio sekitar 5:1 s/d 7:1, lebar minimal 1200px, PNG/JPG transparan/putih, Maks 4MB).</small>
                            </div>
                            <?php if (!empty($settings['kop_surat'])) { ?>
                            <div class="col-md-4 pt-md-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="hapus_kop" value="1" id="checkHapusKop">
                                    <label class="form-check-label text-danger small fw-semibold" for="checkHapusKop">
                                        Hapus gambar kop dan kembali ke kop placeholder standar
                                    </label>
                                </div>
                            </div>
                            <?php } ?>
                        </div>
                    </div>
                </div>

                <!-- BAGIAN 2: IDENTITAS SEKOLAH -->
                <div class="card border mb-4 rounded-3 shadow-none">
                    <div class="card-header bg-light py-2 px-3 fw-semibold text-dark">
                        <i class="fa-solid fa-landmark me-1 text-primary"></i> Identitas & Profil Sekolah
                    </div>
                    <div class="card-body p-3">
                        <div class="row g-3">
                            <div class="col-md-8">
                                <label class="form-label fw-semibold">Nama Resmi Sekolah</label>
                                <input type="text" class="form-control" name="nama_sekolah"
                                       value="<?= htmlspecialchars($settings['nama_sekolah']) ?>"
                                       placeholder="Contoh: SMA NEGERI 1 PRAMBON NGANJUK" required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-semibold">NPSN</label>
                                <input type="text" class="form-control font-monospace" name="npsn"
                                       value="<?= htmlspecialchars($settings['npsn']) ?>"
                                       placeholder="Contoh: 20539744">
                            </div>

                            <div class="col-md-8">
                                <label class="form-label fw-semibold">Alamat Lengkap</label>
                                <input type="text" class="form-control" name="alamat_sekolah"
                                       value="<?= htmlspecialchars($settings['alamat_sekolah']) ?>"
                                       placeholder="Contoh: JL. A. YANI 1 SUGIHWARAS PRAMBON" required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-semibold">Akreditasi</label>
                                <input type="text" class="form-control" name="akreditasi"
                                       value="<?= htmlspecialchars($settings['akreditasi']) ?>"
                                       placeholder="Contoh: A (Unggul)">
                            </div>

                            <div class="col-12">
                                <label class="form-label fw-semibold">Motto / Slogan Sekolah</label>
                                <input type="text" class="form-control" name="slogan"
                                       value="<?= htmlspecialchars($settings['slogan']) ?>"
                                       placeholder="Contoh: Unggul dalam Prestasi, Berkarakter, dan Berbudaya Lingkungan">
                            </div>

                            <div class="col-md-4">
                                <label class="form-label fw-semibold">Telepon / Fax</label>
                                <input type="text" class="form-control" name="telepon"
                                       value="<?= htmlspecialchars($settings['telepon']) ?>"
                                       placeholder="Contoh: (0358) 771234">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-semibold">Email Resmi</label>
                                <input type="email" class="form-control" name="email"
                                       value="<?= htmlspecialchars($settings['email']) ?>"
                                       placeholder="Contoh: info@sman1prambon.sch.id">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-semibold">Website</label>
                                <input type="text" class="form-control" name="website"
                                       value="<?= htmlspecialchars($settings['website']) ?>"
                                       placeholder="Contoh: sman1prambon.sch.id">
                            </div>

                            <div class="col-12">
                                <label class="form-label fw-semibold">Deskripsi Singkat (Tampil di Landing Page)</label>
                                <textarea class="form-control" name="deskripsi_sekolah" rows="3"
                                          placeholder="Tuliskan gambaran singkat sekolah untuk pengunjung landing page..."><?= htmlspecialchars($settings['deskripsi_sekolah'] ?? '') ?></textarea>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- BAGIAN 3: DATA KEPALA SEKOLAH -->
                <div class="card border mb-4 rounded-3 shadow-none">
                    <div class="card-header bg-light py-2 px-3 fw-semibold text-dark">
                        <i class="fa-solid fa-user-tie me-1 text-primary"></i> Data Kepala Sekolah
                    </div>
                    <div class="card-body p-3">
                        <div class="row g-3">
                            <div class="col-md-7">
                                <label class="form-label fw-semibold">Nama Kepala Sekolah (dengan gelar)</label>
                                <input type="text" class="form-control" name="nama_kepala_sekolah"
                                       list="guruList"
                                       value="<?= htmlspecialchars($settings['nama_kepala_sekolah']) ?>"
                                       placeholder="Contoh: Iin Yuristin Nadhiroh, S. Pd., M. MPd." required>
                                <datalist id="guruList">
                                    <?php foreach ($teachers as $t) { ?>
                                        <option value="<?= htmlspecialchars($t['nama_guru']) ?>" label="NIP: <?= htmlspecialchars($t['id_guru']) ?>">
                                    <?php } ?>
                                </datalist>
                                <small class="text-muted">Bisa memilih dari daftar guru atau ketik langsung.</small>
                            </div>
                            <div class="col-md-5">
                                <label class="form-label fw-semibold">NIP Kepala Sekolah</label>
                                <input type="text" class="form-control font-monospace" name="nip_kepala_sekolah"
                                       value="<?= htmlspecialchars($settings['nip_kepala_sekolah']) ?>"
                                       placeholder="Contoh: 197405141999032010">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- BAGIAN 4: TAHUN AJARAN, SEMESTER & TITIMANGSA -->
                <div class="card border mb-4 rounded-3 shadow-none">
                    <div class="card-header bg-light py-2 px-3 fw-semibold text-dark">
                        <i class="fa-solid fa-calendar-check me-1 text-primary"></i> Periode Akademik & Titimangsa Rapor
                    </div>
                    <div class="card-body p-3">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Tahun Ajaran</label>
                                <input type="text" class="form-control" name="tahun_ajaran"
                                       value="<?= htmlspecialchars($settings['tahun_ajaran']) ?>"
                                       placeholder="Contoh: 2025/2026" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Semester</label>
                                <select class="form-select" name="semester" required>
                                    <option value="1" <?= $settings['semester'] === '1' ? 'selected' : '' ?>>1 (Ganjil)</option>
                                    <option value="2" <?= $settings['semester'] === '2' ? 'selected' : '' ?>>2 (Genap)</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Tempat Terbit Dokumen</label>
                                <input type="text" class="form-control" name="tempat_rapor"
                                       value="<?= htmlspecialchars($settings['tempat_rapor']) ?>"
                                       placeholder="Contoh: Prambon" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Tanggal Cetak Rapor</label>
                                <input type="text" class="form-control" name="tanggal_rapor"
                                       value="<?= htmlspecialchars($settings['tanggal_rapor']) ?>"
                                       placeholder="Contoh: 19 Juni 2026" required>
                                <small class="text-muted">Format: <em>[Tempat], [Tanggal]</em> (misal: <?= htmlspecialchars($settings['tempat_rapor']) ?>, <?= htmlspecialchars($settings['tanggal_rapor']) ?>)</small>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary px-4 py-2 fw-semibold">
                        <i class="fa-solid fa-floppy-disk me-1"></i> Simpan Perubahan Identitas
                    </button>
                    <a href="landing" target="_blank" class="btn btn-light border px-3 py-2">
                        Pratinjau di Depan
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.getElementById('inputLogoFile')?.addEventListener('change', function(e) {
    const file = e.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(evt) {
            const preview = document.getElementById('logoPreview');
            const placeholder = document.getElementById('logoPlaceholder');
            if (preview) {
                preview.src = evt.target.result;
                preview.style.display = 'block';
            }
            if (placeholder) {
                placeholder.style.display = 'none';
            }
        };
        reader.readAsDataURL(file);
    }
});

document.getElementById('inputKopFile')?.addEventListener('change', function(e) {
    const file = e.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(evt) {
            const preview = document.getElementById('kopPreview');
            const placeholder = document.getElementById('kopPlaceholderBox');
            if (preview) {
                preview.src = evt.target.result;
                preview.style.display = 'inline-block';
            }
            if (placeholder) {
                placeholder.style.display = 'none';
            }
        };
        reader.readAsDataURL(file);
    }
});
</script>
