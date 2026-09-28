<?php
require_once __DIR__ . '/config/Config.php';
require_once __DIR__ . '/config/Database.php';
require_once __DIR__ . '/models/Setting.php';

if (!isset($conn) || !$conn) {
    $dbHeader = new Database();
    $conn = $dbHeader->connect();
}

$settingModelHeader = new Setting($conn);
$schoolHeader = $settingModelHeader->get();

$headerLogoUrl = (!empty($schoolHeader['logo_sekolah']) && file_exists(__DIR__ . '/' . $schoolHeader['logo_sekolah']))
    ? ($base_url ?? '') . $schoolHeader['logo_sekolah']
    : null;
?>
    <nav class="navbar navbar-expand app-navbar sticky-top">
        <div class="container-lg">
            <a class="navbar-brand" href="home">
                <div class="brand-icon-box">
                    <?php if ($headerLogoUrl) { ?>
                        <img src="<?= htmlspecialchars($headerLogoUrl) ?>" alt="Logo">
                    <?php } else { ?>
                        <i class="fa-solid fa-graduation-cap text-info"></i>
                    <?php } ?>
                </div>
                <span>e-Raport STS &bull; <?= htmlspecialchars($schoolHeader['nama_sekolah']) ?></span>
            </a>
            <div class="collapse navbar-collapse justify-content-end" id="navbarNavDropdown">
                <ul class="navbar-nav align-items-center gap-2">
                    <li class="nav-item d-none d-md-block">
                        <span class="badge bg-light bg-opacity-10 text-white border border-light border-opacity-25 px-2 py-1 small" style="font-size: 11px;">
                            TA <?= htmlspecialchars($schoolHeader['tahun_ajaran']) ?> &bull; Sem <?= htmlspecialchars($schoolHeader['semester']) ?>
                        </span>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle user-badge-nav" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fa-solid fa-circle-user me-1 text-info"></i>
                            <strong><?php echo htmlspecialchars($_SESSION['nama'] ?? 'User'); ?></strong>
                            <span class="badge bg-primary text-white ms-1 text-uppercase" style="font-size: 10px;">
                                <?php echo htmlspecialchars($_SESSION['role'] ?? ''); ?>
                            </span>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0 mt-2 rounded-3" style="min-width: 200px;">
                            <li class="px-3 py-2 border-bottom text-muted small">
                                Signed in as<br><strong class="text-dark"><?= htmlspecialchars($_SESSION['username'] ?? '') ?></strong>
                            </li>
                            <li><a class="dropdown-item py-2" data-bs-toggle="modal" data-bs-target="#ModalChangePass" href="#"><i class="fa-solid fa-key me-2 text-warning"></i> Ubah Password</a></li>
                            <li><hr class="dropdown-divider my-1"></li>
                            <li><a class="dropdown-item py-2 text-danger" href="logout"><i class="fa-solid fa-arrow-right-from-bracket me-2"></i> Keluar (Logout)</a></li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Modal Change Password -->
    <div class="modal fade" id="ModalChangePass" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content rounded-4 border-0 shadow">
                <div class="modal-header border-bottom">
                    <h5 class="modal-title fw-bold" id="exampleModalLabel"><i class="fa-solid fa-key text-warning me-2"></i>Ubah Password Akun</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <form class="needs-validation" novalidate action="controllers/user.php" method="POST">
                        <input type="hidden" name="action" value="change_password">
                        <input type="hidden" name="user" value="<?= htmlspecialchars($_SESSION['username'] ?? '') ?>">
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Password Saat Ini</label>
                            <input type="password" class="form-control" name="oldpass" placeholder="Ketik password lama" required>
                            <div class="invalid-feedback">Masukkan password lama Anda.</div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Password Baru</label>
                            <input type="password" class="form-control" name="newpass" placeholder="Ketik password baru" required>
                            <div class="invalid-feedback">Masukkan password baru.</div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Konfirmasi Password Baru</label>
                            <input type="password" class="form-control" name="confirmnewpass" placeholder="Ketik ulang password baru" required>
                            <div class="invalid-feedback">Konfirmasi password baru harus sama.</div>
                        </div>
                        <div class="modal-footer px-0 pb-0 pt-3 border-top">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-primary px-4" name="input_user_validate" value="1">Simpan Password</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
