<?php
$currentX = $_GET['x'] ?? 'home';
function navActive(string $target, string $current): string {
    return ($target === $current) ? 'active' : '';
}
?>
<div class="col-lg-3 mt-2">
    <div class="sidebar-card">
        <div class="d-lg-none mb-3">
            <button class="btn btn-outline-primary w-100" type="button" data-bs-toggle="collapse" data-bs-target="#sidebarCollapse" aria-expanded="false">
                <i class="fa-solid fa-bars me-2"></i> Menu Navigasi
            </button>
        </div>
        <div class="collapse d-lg-block" id="sidebarCollapse">
            <ul class="nav nav-pills flex-column">
                <li class="nav-item">
                    <a class="nav-link <?= navActive('home', $currentX) ?>" href="home">
                        <i class="fa-solid fa-gauge text-primary"></i>
                        <span>Dashboard</span>
                    </a>
                </li>

                <?php if (($_SESSION["role"] ?? '') === "admin") { ?>
                <li class="sidebar-section-title">Data Master</li>
                <li class="nav-item">
                    <a class="nav-link <?= navActive('classes', $currentX) ?>" href="classes">
                        <i class="fa-solid fa-chalkboard text-info"></i>
                        <span>Data Kelas</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= navActive('subjects', $currentX) ?>" href="subjects">
                        <i class="fa-solid fa-book text-warning"></i>
                        <span>Referensi Mapel</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= navActive('subject-mapping', $currentX) ?>" href="subject-mapping">
                        <i class="fa-solid fa-list-ol text-success"></i>
                        <span>Mapping Mapel</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= navActive('teachers', $currentX) ?>" href="teachers">
                        <i class="fa-solid fa-chalkboard-user text-primary"></i>
                        <span>Data Guru</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= navActive('assignments', $currentX) ?>" href="assignments">
                        <i class="fa-solid fa-link text-danger"></i>
                        <span>Penugasan Mengajar</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= navActive('students', $currentX) ?>" href="students">
                        <i class="fa-solid fa-user-graduate text-secondary"></i>
                        <span>Data Siswa</span>
                    </a>
                </li>

                <li class="sidebar-section-title">Monitoring & Cetak</li>
                <li class="nav-item">
                    <a class="nav-link <?= navActive('grade-monitor', $currentX) ?>" href="grade-monitor">
                        <i class="fa-solid fa-chart-pie text-info"></i>
                        <span>Monitor Penilaian</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= navActive('ledger', $currentX) ?>" href="ledger">
                        <i class="fa-solid fa-table text-warning"></i>
                        <span>Ledger Nilai STS</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= navActive('attendance', $currentX) ?>" href="attendance">
                        <i class="fa-solid fa-clipboard-user text-warning"></i>
                        <span>Ketidakhadiran Siswa</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= navActive('bulk-print', $currentX) ?>" href="bulk-print">
                        <i class="fa-solid fa-print text-danger"></i>
                        <span>Cetak Rapor STS</span>
                    </a>
                </li>

                <li class="sidebar-section-title">Konfigurasi</li>
                <li class="nav-item">
                    <a class="nav-link <?= navActive('school-profile', $currentX) ?>" href="school-profile">
                        <i class="fa-solid fa-school-flag text-primary"></i>
                        <span>Data Sekolah & Logo</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= navActive('settings', $currentX) ?>" href="settings">
                        <i class="fa-solid fa-file-signature text-success"></i>
                        <span>Format Cetak Rapor</span>
                    </a>
                </li>
                <?php /* Pengolahan nilai dinonaktifkan sementara
                <li class="nav-item">
                    <a class="nav-link <?= navActive('weights', $currentX) ?>" href="weights">
                        <i class="fa-solid fa-sliders text-info"></i>
                        <span>Pengaturan Bobot STS</span>
                    </a>
                </li>
                */ ?>
                <li class="nav-item">
                    <a class="nav-link <?= navActive('admins', $currentX) ?>" href="admins">
                        <i class="fa-solid fa-user-shield text-danger"></i>
                        <span>Kelola Admin</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= navActive('backup-restore', $currentX) ?>" href="backup-restore">
                        <i class="fa-solid fa-database text-warning"></i>
                        <span>Backup &amp; Restore</span>
                    </a>
                </li>
                <?php } ?>

                <?php if (($_SESSION["role"] ?? '') === "guru") { ?>
                <li class="sidebar-section-title">Akademik Guru</li>
                <li class="nav-item">
                    <a class="nav-link <?= navActive('grade-recap', $currentX) ?>" href="grade-recap">
                        <i class="fa-solid fa-pen-to-square text-primary"></i>
                        <span>Input Nilai STS</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= navActive('attendance', $currentX) ?>" href="attendance">
                        <i class="fa-solid fa-clipboard-user text-warning"></i>
                        <span>Ketidakhadiran Siswa (BK)</span>
                    </a>
                </li>
                <?php } ?>

                <?php if (($_SESSION["role"] ?? '') === "walikelas") { ?>
                <li class="sidebar-section-title">Wali Kelas</li>
                <li class="nav-item">
                    <a class="nav-link <?= navActive('homeroom', $currentX) ?>" href="homeroom">
                        <i class="fa-solid fa-users-line text-success"></i>
                        <span>Perwalian & Rapor</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= navActive('ledger', $currentX) ?>" href="ledger">
                        <i class="fa-solid fa-table text-warning"></i>
                        <span>Ledger Nilai Kelas</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= navActive('attendance', $currentX) ?>" href="attendance">
                        <i class="fa-solid fa-clipboard-user text-warning"></i>
                        <span>Input Ketidakhadiran</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= navActive('grade-monitor', $currentX) ?>" href="grade-monitor">
                        <i class="fa-solid fa-chart-pie text-info"></i>
                        <span>Monitor Nilai Kelas</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= navActive('bulk-print', $currentX) ?>" href="bulk-print">
                        <i class="fa-solid fa-print text-danger"></i>
                        <span>Cetak Rapor Kelas</span>
                    </a>
                </li>
                <?php } ?>

                <?php if (($_SESSION["role"] ?? '') === "siswa") { ?>
                <li class="sidebar-section-title">Hasil Belajar</li>
                <li class="nav-item">
                    <a class="nav-link <?= navActive('grade-summary', $currentX) ?>" href="grade-summary">
                        <i class="fa-solid fa-file-invoice text-primary"></i>
                        <span>Rapor STS Saya</span>
                    </a>
                </li>
                <?php } ?>
            </ul>
        </div>
    </div>
</div>
