<?php
include_once "config/Database.php";
require_once "models/Setting.php";

$db = new Database();
$conn = $db->connect();

$settingModel = new Setting($conn);
$settings = $settingModel->get();

$logoUrl = (!empty($settings['logo_sekolah']) && file_exists(__DIR__ . '/' . $settings['logo_sekolah']))
    ? $settings['logo_sekolah'] . '?t=' . time()
    : null;
?>

<div class="col-lg-9 mt-2">
    <!-- Header Page -->
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-header bg-white py-3 d-flex flex-wrap justify-content-between align-items-center gap-2 border-bottom">
            <div>
                <h5 class="mb-1 fw-bold text-primary">
                    <i class="fa-solid fa-school-flag me-2"></i>Pengaturan Data Sekolah, NPSN &amp; Logo
                </h5>
                <small class="text-muted">Kelola identitas resmi yang tampil pada Landing Page, Halaman Login, dan Kop Dokumen Rapor.</small>
            </div>
            <div class="d-flex gap-2">
                <a href="landing" target="_blank" class="btn btn-outline-primary btn-sm">
                    <i class="fa-solid fa-arrow-up-right-from-square me-1"></i> Buka Landing Page
                </a>
                <a href="login" target="_blank" class="btn btn-outline-secondary btn-sm">
                    <i class="fa-solid fa-right-to-bracket me-1"></i> Buka Halaman Login
                </a>
            </div>
        </div>

        <div class="card-body p-4">
            <form action="controllers/setting.php" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="redirect_to" value="school-profile">

                <!-- 1. PENGATURAN LOGO SEKOLAH -->
                <div class="card border mb-4 rounded-4 shadow-none">
                    <div class="card-header bg-light py-2 px-3 fw-bold text-dark d-flex align-items-center justify-content-between">
                        <span><i class="fa-solid fa-image text-primary me-2"></i>Logo Satuan Pendidikan</span>
                        <span class="badge bg-primary bg-opacity-10 text-primary border border-primary border-opacity-25 small">
                            Tampil di Depan &amp; Kop Rapor
                        </span>
                    </div>
                    <div class="card-body p-4">
                        <div class="row align-items-center g-4">
                            <!-- Preview Box -->
                            <div class="col-md-4 text-center">
                                <label class="form-label fw-semibold small text-muted mb-2 d-block">Pratinjau Logo Saat Ini</label>
                                <div class="p-3 border rounded-4 bg-light d-flex align-items-center justify-content-center mx-auto shadow-sm"
                                     style="width: 160px; height: 160px; overflow: hidden; background-color: #ffffff !important;">
                                    <?php if ($logoUrl) { ?>
                                        <img id="logoPreview" src="<?= htmlspecialchars($logoUrl) ?>"
                                             alt="Logo Sekolah" style="max-width: 100%; max-height: 100%; object-fit: contain;">
                                    <?php } else { ?>
                                        <div id="logoPlaceholder" class="text-center text-muted">
                                            <i class="fa-solid fa-shield-halved fa-4x text-primary opacity-50 mb-2"></i>
                                            <div class="small fw-semibold text-secondary">Belum Ada Logo</div>
                                            <div style="font-size: 11px;" class="text-muted">(Pakai Lambang Default)</div>
                                        </div>
                                        <img id="logoPreview" src="" alt="Preview" style="display: none; max-width: 100%; max-height: 100%; object-fit: contain;">
                                    <?php } ?>
                                </div>
                            </div>

                            <!-- Upload Control -->
                            <div class="col-md-8">
                                <label class="form-label fw-bold">Pilih File Logo Baru</label>
                                <input type="file" class="form-control mb-2" name="logo_file" id="inputLogoFile" accept=".png,.jpg,.jpeg,.svg,.webp">
                                <div class="small text-muted mb-3">
                                    <i class="fa-solid fa-circle-check text-success me-1"></i> Format didukung: <strong>PNG, JPG, JPEG, WEBP, SVG</strong> (Maks. 2MB).
                                    <br>
                                    <i class="fa-solid fa-circle-info text-primary me-1"></i> Disarankan menggunakan format PNG transparan dengan resolusi proporsional (persegi / rasio 1:1).
                                </div>

                                <?php if (!empty($settings['logo_sekolah'])) { ?>
                                    <div class="form-check p-2 bg-danger-subtle rounded-3 border border-danger-subtle">
                                        <input class="form-check-input ms-1" type="checkbox" name="hapus_logo" value="1" id="checkHapusLogo">
                                        <label class="form-check-label text-danger small fw-semibold ms-2" for="checkHapusLogo">
                                            Hapus logo kustom saat ini dan kembalikan ke lambang bawaan
                                        </label>
                                    </div>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- 2. DATA NPSN & IDENTITAS SEKOLAH -->
                <div class="card border mb-4 rounded-4 shadow-none">
                    <div class="card-header bg-light py-2 px-3 fw-bold text-dark">
                        <i class="fa-solid fa-id-card text-primary me-2"></i>NPSN &amp; Identitas Satuan Pendidikan
                    </div>
                    <div class="card-body p-4">
                        <div class="row g-3">
                            <div class="col-md-8">
                                <label class="form-label fw-semibold">Nama Resmi Satuan Pendidikan</label>
                                <input type="text" class="form-control" name="nama_sekolah"
                                       value="<?= htmlspecialchars($settings['nama_sekolah']) ?>"
                                       placeholder="Contoh: SMA NEGERI 1 PRAMBON NGANJUK" required>
                                <small class="text-muted">Nama resmi sekolah yang tercantum pada dokumen laporan &amp; portal.</small>
                            </div>

                            <div class="col-md-4">
                                <label class="form-label fw-semibold">
                                    NPSN (Nomor Pokok Sekolah Nasional)
                                    <span class="text-danger">*</span>
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light text-primary font-monospace"><i class="fa-solid fa-fingerprint"></i></span>
                                    <input type="text" class="form-control font-monospace fw-bold" name="npsn"
                                           value="<?= htmlspecialchars($settings['npsn']) ?>"
                                           placeholder="Contoh: 20539744" required>
                                </div>
                                <small class="text-muted">Ditampilkan pada badge depan &amp; informasi sekolah.</small>
                            </div>

                            <div class="col-md-4">
                                <label class="form-label fw-semibold">Status Akreditasi</label>
                                <input type="text" class="form-control" name="akreditasi"
                                       value="<?= htmlspecialchars($settings['akreditasi']) ?>"
                                       placeholder="Contoh: A (Unggul)">
                            </div>

                            <div class="col-md-8">
                                <label class="form-label fw-semibold">Motto / Slogan Sekolah</label>
                                <input type="text" class="form-control" name="slogan"
                                       value="<?= htmlspecialchars($settings['slogan']) ?>"
                                       placeholder="Contoh: Unggul dalam Prestasi, Berkarakter, dan Berbudaya Lingkungan">
                            </div>

                            <div class="col-12">
                                <label class="form-label fw-semibold">Alamat Lengkap Satuan Pendidikan</label>
                                <input type="text" class="form-control" name="alamat_sekolah"
                                       value="<?= htmlspecialchars($settings['alamat_sekolah']) ?>"
                                       placeholder="Contoh: JL. A. YANI 1 SUGIHWARAS PRAMBON" required>
                            </div>

                            <div class="col-12">
                                <label class="form-label fw-semibold">Deskripsi Profil Singkat Sekolah</label>
                                <textarea class="form-control" name="deskripsi_sekolah" rows="3"
                                          placeholder="Tuliskan gambaran umum visi, misi, atau komitmen sekolah untuk pengunjung halaman depan..."><?= htmlspecialchars($settings['deskripsi_sekolah'] ?? '') ?></textarea>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- 3. KONTAK & SALURAN RESMI -->
                <div class="card border mb-4 rounded-4 shadow-none">
                    <div class="card-header bg-light py-2 px-3 fw-bold text-dark">
                        <i class="fa-solid fa-address-book text-primary me-2"></i>Kontak &amp; Informasi Publik
                    </div>
                    <div class="card-body p-4">
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label class="form-label fw-semibold">Nomor Telepon / Fax</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light text-muted"><i class="fa-solid fa-phone"></i></span>
                                    <input type="text" class="form-control" name="telepon"
                                           value="<?= htmlspecialchars($settings['telepon']) ?>"
                                           placeholder="Contoh: (0358) 771234">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-semibold">Alamat Email Resmi</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light text-muted"><i class="fa-solid fa-envelope"></i></span>
                                    <input type="email" class="form-control" name="email"
                                           value="<?= htmlspecialchars($settings['email']) ?>"
                                           placeholder="Contoh: info@sman1prambon.sch.id">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-semibold">Alamat Website</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light text-muted"><i class="fa-solid fa-globe"></i></span>
                                    <input type="text" class="form-control" name="website"
                                           value="<?= htmlspecialchars($settings['website']) ?>"
                                           placeholder="Contoh: sman1prambon.sch.id">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- TOMBOL SIMPAN -->
                <div class="d-flex align-items-center gap-3">
                    <button type="submit" class="btn btn-primary px-4 py-2 fw-semibold">
                        <i class="fa-solid fa-floppy-disk me-2"></i>Simpan Perubahan Data Sekolah &amp; Logo
                    </button>
                    <a href="settings" class="btn btn-outline-secondary px-3 py-2">
                        <i class="fa-solid fa-file-signature me-1"></i> Ke Pengaturan Format Rapor
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
</script>
