<?php
require_once __DIR__ . '/config/Config.php';
require_once __DIR__ . '/config/Database.php';
require_once __DIR__ . '/models/Setting.php';

$db = new Database();
$conn = $db->connect();
$settingModel = new Setting($conn);

$setting = $settingModel->get();
$stats   = $settingModel->getStats();

$logoUrl = (!empty($setting['logo_sekolah']) && file_exists(__DIR__ . '/' . $setting['logo_sekolah']))
    ? $base_url . $setting['logo_sekolah']
    : null;
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($setting['nama_sekolah']) ?> &mdash; e-Raport STS</title>
    
    <!-- Google Fonts: Plus Jakarta Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=Space+Grotesk:wght@600;700&display=swap" rel="stylesheet">
    
    <!-- Bootstrap 5.3 & Font Awesome 6.5 -->
    <link href="<?= $base_url ?>vendor/bootstrap-5.3.8/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    
    <style>
        :root {
            --primary: #2563eb;
            --primary-dark: #1d4ed8;
            --primary-glow: rgba(37, 99, 235, 0.25);
            --accent: #0ea5e9;
            --accent-green: #10b981;
            --dark-base: #090d16;
            --dark-card: #0f172a;
            --dark-border: rgba(255, 255, 255, 0.08);
            --text-main: #f8fafc;
            --text-muted: #94a3b8;
            --ease-out: cubic-bezier(0.16, 1, 0.3, 1);
        }

        * {
            box-sizing: border-box;
            scroll-behavior: smooth;
        }

        body {
            font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, sans-serif;
            background-color: var(--dark-base);
            color: var(--text-main);
            margin: 0;
            padding: 0;
            overflow-x: hidden;
            line-height: 1.6;
        }

        /* Ambient Glow Background */
        .ambient-mesh {
            position: fixed;
            top: 0;
            left: 0;
            width: 100vw;
            height: 100vh;
            pointer-events: none;
            z-index: 0;
            overflow: hidden;
        }
        .orb-1 {
            position: absolute;
            top: -15%;
            left: 15%;
            width: 600px;
            height: 600px;
            background: radial-gradient(circle, rgba(37, 99, 235, 0.18) 0%, rgba(37, 99, 235, 0) 70%);
            filter: blur(80px);
        }
        .orb-2 {
            position: absolute;
            top: 35%;
            right: -10%;
            width: 550px;
            height: 550px;
            background: radial-gradient(circle, rgba(14, 165, 233, 0.14) 0%, rgba(14, 165, 233, 0) 70%);
            filter: blur(90px);
        }
        .orb-3 {
            position: absolute;
            bottom: -10%;
            left: 20%;
            width: 650px;
            height: 650px;
            background: radial-gradient(circle, rgba(16, 185, 129, 0.10) 0%, rgba(16, 185, 129, 0) 70%);
            filter: blur(100px);
        }

        /* Floating Island Navbar */
        .nav-island {
            position: fixed;
            top: 24px;
            left: 50%;
            transform: translateX(-50%);
            width: min(1140px, 92%);
            z-index: 1000;
            background: rgba(15, 23, 42, 0.75);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid var(--dark-border);
            border-radius: 9999px;
            padding: 8px 14px 8px 24px;
            box-shadow: 0 20px 40px -15px rgba(0, 0, 0, 0.5);
            transition: all 0.4s var(--ease-out);
        }
        .nav-island:hover {
            border-color: rgba(255, 255, 255, 0.15);
            box-shadow: 0 25px 50px -12px rgba(37, 99, 235, 0.2);
        }
        .nav-brand {
            display: flex;
            align-items: center;
            gap: 12px;
            text-decoration: none;
            color: #fff;
        }
        .brand-logo-frame {
            width: 42px;
            height: 42px;
            border-radius: 12px;
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.1);
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            flex-shrink: 0;
        }
        .brand-logo-frame img {
            width: 100%;
            height: 100%;
            object-fit: contain;
        }
        .brand-title {
            font-size: 14px;
            font-weight: 700;
            letter-spacing: -0.01em;
            line-height: 1.2;
            color: #fff;
        }
        .brand-subtitle {
            font-size: 11px;
            color: var(--text-muted);
            letter-spacing: 0.02em;
        }

        .nav-links {
            display: flex;
            align-items: center;
            gap: 28px;
            list-style: none;
            margin: 0;
            padding: 0;
        }
        .nav-links a {
            color: #cbd5e1;
            text-decoration: none;
            font-size: 13.5px;
            font-weight: 500;
            transition: color 0.2s ease;
        }
        .nav-links a:hover {
            color: #fff;
        }

        /* Button-in-Button CTA */
        .btn-island-cta {
            background: linear-gradient(135deg, #2563eb, #1d4ed8);
            color: #fff !important;
            padding: 7px 8px 7px 18px;
            border-radius: 9999px;
            font-size: 13px;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 12px;
            text-decoration: none;
            border: 1px solid rgba(255, 255, 255, 0.2);
            box-shadow: 0 4px 15px rgba(37, 99, 235, 0.4);
            transition: all 0.3s var(--ease-out);
        }
        .btn-island-cta:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(37, 99, 235, 0.6);
        }
        .btn-cta-circle {
            width: 28px;
            height: 28px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.2);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 11px;
            transition: transform 0.3s var(--ease-out);
        }
        .btn-island-cta:hover .btn-cta-circle {
            transform: translateX(2px) rotate(-15deg);
        }

        /* Hero Section */
        .hero-section {
            position: relative;
            z-index: 1;
            padding-top: 170px;
            padding-bottom: 90px;
            text-align: center;
        }
        .badge-pill-glow {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 6px 16px;
            border-radius: 9999px;
            background: rgba(37, 99, 235, 0.12);
            border: 1px solid rgba(37, 99, 235, 0.3);
            font-size: 12px;
            font-weight: 600;
            color: #60a5fa;
            letter-spacing: 0.05em;
            text-transform: uppercase;
            margin-bottom: 24px;
        }
        .badge-pill-glow .pulse-dot {
            width: 7px;
            height: 7px;
            border-radius: 50%;
            background: #60a5fa;
            box-shadow: 0 0 10px #60a5fa;
            animation: pulse 2s infinite;
        }
        @keyframes pulse {
            0% { transform: scale(0.95); opacity: 0.8; }
            50% { transform: scale(1.3); opacity: 1; }
            100% { transform: scale(0.95); opacity: 0.8; }
        }

        .hero-title {
            font-size: clamp(34px, 5.5vw, 64px);
            font-weight: 800;
            line-height: 1.15;
            letter-spacing: -0.03em;
            max-width: 980px;
            margin: 0 auto 20px auto;
            color: #ffffff;
        }
        .hero-title-highlight {
            background: linear-gradient(135deg, #60a5fa 20%, #38bdf8 60%, #34d399 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        .hero-desc {
            font-size: clamp(16px, 1.8vw, 19px);
            color: var(--text-muted);
            max-width: 680px;
            margin: 0 auto 36px auto;
            font-weight: 400;
        }

        .hero-actions {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 16px;
            flex-wrap: wrap;
            margin-bottom: 50px;
        }
        .btn-hero-primary {
            background: #ffffff;
            color: #090d16 !important;
            padding: 13px 28px;
            border-radius: 9999px;
            font-weight: 700;
            font-size: 14.5px;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 10px;
            box-shadow: 0 10px 30px rgba(255, 255, 255, 0.15);
            transition: all 0.3s var(--ease-out);
        }
        .btn-hero-primary:hover {
            transform: translateY(-3px);
            box-shadow: 0 15px 40px rgba(255, 255, 255, 0.3);
            background: #f8fafc;
        }
        .btn-hero-secondary {
            background: rgba(255, 255, 255, 0.05);
            color: #e2e8f0 !important;
            border: 1px solid rgba(255, 255, 255, 0.12);
            padding: 13px 26px;
            border-radius: 9999px;
            font-weight: 600;
            font-size: 14.5px;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            backdrop-filter: blur(10px);
            transition: all 0.3s var(--ease-out);
        }
        .btn-hero-secondary:hover {
            background: rgba(255, 255, 255, 0.1);
            color: #fff !important;
            border-color: rgba(255, 255, 255, 0.25);
            transform: translateY(-2px);
        }

        /* Double-Bezel Concentric Card Architecture */
        .double-bezel {
            background: rgba(255, 255, 255, 0.03);
            border: 1px solid rgba(255, 255, 255, 0.07);
            border-radius: 28px;
            padding: 8px;
            transition: all 0.4s var(--ease-out);
        }
        .double-bezel:hover {
            border-color: rgba(37, 99, 235, 0.35);
            box-shadow: 0 20px 45px -15px rgba(37, 99, 235, 0.25);
            transform: translateY(-3px);
        }
        .bezel-core {
            background: #0f172a;
            border: 1px solid rgba(255, 255, 255, 0.04);
            border-radius: 20px;
            padding: 28px;
            height: 100%;
            position: relative;
            overflow: hidden;
        }

        /* Stats Row */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 16px;
            max-width: 1140px;
            margin: 0 auto;
        }
        .stat-card {
            text-align: left;
        }
        .stat-number {
            font-family: 'Space Grotesk', sans-serif;
            font-size: clamp(32px, 3.5vw, 44px);
            font-weight: 700;
            color: #fff;
            line-height: 1;
            margin-bottom: 6px;
            background: linear-gradient(135deg, #fff 40%, #94a3b8 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        .stat-label {
            font-size: 13px;
            color: var(--text-muted);
            font-weight: 500;
        }

        /* Section Layouts */
        .section-wrap {
            position: relative;
            z-index: 1;
            padding: 80px 0;
        }
        .section-header {
            text-align: center;
            max-width: 650px;
            margin: 0 auto 50px auto;
        }
        .section-badge {
            font-size: 11px;
            font-weight: 700;
            color: #38bdf8;
            letter-spacing: 0.15em;
            text-transform: uppercase;
            margin-bottom: 12px;
            display: block;
        }
        .section-title {
            font-size: clamp(26px, 3.2vw, 40px);
            font-weight: 800;
            letter-spacing: -0.02em;
            color: #fff;
            margin-bottom: 14px;
        }
        .section-desc {
            font-size: 15.5px;
            color: var(--text-muted);
            margin: 0;
        }

        /* Feature Bento Grid */
        .bento-grid {
            display: grid;
            grid-template-columns: repeat(12, 1fr);
            gap: 20px;
            max-width: 1140px;
            margin: 0 auto;
        }
        .bento-col-8 { grid-column: span 8; }
        .bento-col-4 { grid-column: span 4; }
        .bento-col-6 { grid-column: span 6; }
        .bento-col-12 { grid-column: span 12; }

        .feature-icon-box {
            width: 48px;
            height: 48px;
            border-radius: 14px;
            background: rgba(37, 99, 235, 0.15);
            border: 1px solid rgba(37, 99, 235, 0.3);
            display: flex;
            align-items: center;
            justify-content: center;
            color: #60a5fa;
            font-size: 20px;
            margin-bottom: 20px;
        }

        /* Role Badges */
        .role-pill {
            padding: 4px 10px;
            border-radius: 9999px;
            font-size: 11px;
            font-weight: 600;
            text-transform: uppercase;
            display: inline-block;
            margin-bottom: 10px;
        }
        .role-admin { background: rgba(239, 68, 68, 0.15); color: #f87171; border: 1px solid rgba(239, 68, 68, 0.3); }
        .role-guru { background: rgba(59, 130, 246, 0.15); color: #60a5fa; border: 1px solid rgba(59, 130, 246, 0.3); }
        .role-wali { background: rgba(16, 185, 129, 0.15); color: #34d399; border: 1px solid rgba(16, 185, 129, 0.3); }
        .role-siswa { background: rgba(245, 158, 11, 0.15); color: #fbbf24; border: 1px solid rgba(245, 158, 11, 0.3); }

        /* School Identity Showcase Card */
        .identity-banner {
            max-width: 1140px;
            margin: 0 auto;
            background: linear-gradient(135deg, rgba(37, 99, 235, 0.1), rgba(15, 23, 42, 0.8));
            border: 1px solid rgba(37, 99, 235, 0.25);
            border-radius: 28px;
            padding: 40px;
            position: relative;
            overflow: hidden;
        }

        /* Footer */
        footer {
            position: relative;
            z-index: 1;
            border-top: 1px solid var(--dark-border);
            padding: 60px 0 35px 0;
            background: rgba(11, 15, 25, 0.85);
            font-size: 13.5px;
            color: var(--text-muted);
        }
        footer a {
            color: var(--text-muted);
            text-decoration: none;
            transition: color 0.2s ease;
        }
        footer a:hover {
            color: #fff;
        }

        @media (max-width: 991px) {
            .nav-links { display: none; }
            .bento-col-8, .bento-col-4, .bento-col-6 { grid-column: span 12; }
            .hero-section { padding-top: 140px; }
            .identity-banner { padding: 28px 20px; }
        }
    </style>
</head>
<body>

<!-- Ambient Light Mesh -->
<div class="ambient-mesh">
    <div class="orb-1"></div>
    <div class="orb-2"></div>
    <div class="orb-3"></div>
</div>

<!-- Floating Island Navbar -->
<nav class="nav-island d-flex justify-content-between align-items-center">
    <a href="<?= $base_url ?>" class="nav-brand">
        <div class="brand-logo-frame">
            <?php if ($logoUrl) { ?>
                <img src="<?= htmlspecialchars($logoUrl) ?>" alt="Logo">
            <?php } else { ?>
                <i class="fa-solid fa-graduation-cap text-primary"></i>
            <?php } ?>
        </div>
        <div>
            <div class="brand-title"><?= htmlspecialchars($setting['nama_sekolah']) ?></div>
            <div class="brand-subtitle">Portal e-Raport Sumatif Tengah Semester (STS)</div>
        </div>
    </a>

    <ul class="nav-links">
        <li><a href="#fitur">Keunggulan</a></li>
        <li><a href="#peran">Hak Akses</a></li>
        <li><a href="#identitas">Profil Sekolah</a></li>
        <li><a href="#kontak">Kontak</a></li>
    </ul>

    <div>
        <a href="login" class="btn-island-cta">
            <span>Masuk Akun</span>
            <div class="btn-cta-circle">
                <i class="fa-solid fa-arrow-right"></i>
            </div>
        </a>
    </div>
</nav>

<!-- Hero Section -->
<section class="hero-section">
    <div class="container">
        <div class="badge-pill-glow">
            <span class="pulse-dot"></span>
            Kurikulum Merdeka &bull; Tahun Ajaran <?= htmlspecialchars($setting['tahun_ajaran']) ?>
        </div>

        <h1 class="hero-title">
            Transformasi Penilaian Digital<br>
            <span class="hero-title-highlight"><?= htmlspecialchars($setting['nama_sekolah']) ?></span>
        </h1>

        <p class="hero-desc">
            Sistem pengolahan asesmen sumatif materi & nilai STS yang akurat, terstruktur,
            terintegrasi langsung dengan database resmi Dapodik, dan siap cetak 1-lembar standar.
        </p>

        <div class="hero-actions">
            <a href="login" class="btn-hero-primary">
                <i class="fa-solid fa-right-to-bracket"></i>
                Buka Portal Login
            </a>
            <a href="#fitur" class="btn-hero-secondary">
                <i class="fa-solid fa-circle-nodes"></i>
                Eksplorasi Fitur
            </a>
        </div>

        <!-- Live Stats Grid (Double Bezel) -->
        <div class="stats-grid">
            <div class="double-bezel">
                <div class="bezel-core stat-card">
                    <div class="stat-number"><?= number_format($stats['total_siswa'], 0, ',', '.') ?></div>
                    <div class="stat-label"><i class="fa-solid fa-user-graduate text-primary me-1"></i> Peserta Didik Aktif</div>
                </div>
            </div>
            <div class="double-bezel">
                <div class="bezel-core stat-card">
                    <div class="stat-number"><?= $stats['total_kelas'] ?></div>
                    <div class="stat-label"><i class="fa-solid fa-chalkboard-user text-info me-1"></i> Rombongan Belajar (X, XI, XII)</div>
                </div>
            </div>
            <div class="double-bezel">
                <div class="bezel-core stat-card">
                    <div class="stat-number"><?= $stats['total_guru'] ?></div>
                    <div class="stat-label"><i class="fa-solid fa-chalkboard-teacher text-success me-1"></i> Dewan Guru & Wali Kelas</div>
                </div>
            </div>
            <div class="double-bezel">
                <div class="bezel-core stat-card">
                    <div class="stat-number"><?= $stats['total_mapel'] ?></div>
                    <div class="stat-label"><i class="fa-solid fa-book-bookmark text-warning me-1"></i> Mata Pelajaran Referensi</div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Keunggulan Sistem (Asymmetrical Bento Grid) -->
<section class="section-wrap" id="fitur">
    <div class="container">
        <div class="section-header">
            <span class="section-badge">Arsitektur & Fitur</span>
            <h2 class="section-title">Dirancang untuk Presisi Akademik</h2>
            <p class="section-desc">Mendukung penuh instrumen Kurikulum Merdeka (Fase E & Fase F) dengan alur kerja yang mudah dan transparan.</p>
        </div>

        <div class="bento-grid">
            <!-- Bento 1: Formula STS -->
            <div class="bento-col-8 double-bezel">
                <div class="bezel-core">
                    <div class="feature-icon-box">
                        <i class="fa-solid fa-calculator"></i>
                    </div>
                    <h4 class="fw-bold text-white mb-2">Formula Pembobotan Otomatis</h4>
                    <p class="text-muted mb-4">
                        Nilai akhir dihitung secara real-time dari akumulasi Nilai Sumatif 1, 2, dan 3 dipadukan dengan skor Asesmen Tengah Semester (STS) sesuai ketetapan bobot KKM/KKTP satuan pendidikan.
                    </p>
                    <div class="p-3 rounded-3" style="background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.06);">
                        <code class="text-info font-monospace small">
                            Nilai Akhir = ((Rata-Rata Sumatif &times; %Bobot Sumatif) + (Nilai STS &times; %Bobot STS)) / Total Bobot
                        </code>
                    </div>
                </div>
            </div>

            <!-- Bento 2: Layout Rapor Resmi -->
            <div class="bento-col-4 double-bezel">
                <div class="bezel-core">
                    <div class="feature-icon-box" style="background: rgba(16, 185, 129, 0.15); border-color: rgba(16, 185, 129, 0.3); color: #34d399;">
                        <i class="fa-solid fa-file-invoice"></i>
                    </div>
                    <h4 class="fw-bold text-white mb-2">Cetak 1 Lembar Standar</h4>
                    <p class="text-muted small mb-0">
                        Layout cetak A4 portrait rapi tanpa terpotong, memuat identitas, tabel kisi nilai, kotak tanggapan wali murid, dan 3 kolom tanda tangan resmi.
                    </p>
                </div>
            </div>

            <!-- Bento 3: Data Dapodik Presisi -->
            <div class="bento-col-4 double-bezel">
                <div class="bezel-core">
                    <div class="feature-icon-box" style="background: rgba(245, 158, 11, 0.15); border-color: rgba(245, 158, 11, 0.3); color: #fbbf24;">
                        <i class="fa-solid fa-database"></i>
                    </div>
                    <h4 class="fw-bold text-white mb-2">Akurasi Basis Dapodik</h4>
                    <p class="text-muted small mb-0">
                        100% data siswa tersinkronisasi dengan NIPD dan NISN resmi. Penempatan rombel otomatis di kelas X, XI, hingga XII.
                    </p>
                </div>
            </div>

            <!-- Bento 4: Penugasan Mengajar 2-Layer -->
            <div class="bento-col-8 double-bezel">
                <div class="bezel-core">
                    <div class="feature-icon-box" style="background: rgba(14, 165, 233, 0.15); border-color: rgba(14, 165, 233, 0.3); color: #38bdf8;">
                        <i class="fa-solid fa-layer-group"></i>
                    </div>
                    <h4 class="fw-bold text-white mb-2">Mapping Mata Pelajaran Fleksibel</h4>
                    <p class="text-muted mb-0">
                        Memisahkan katalog induk mata pelajaran referensi dengan mapping kurikulum per jenjang (Umum & Pilihan), memudahkan kustomisasi urutan cetak rapor untuk Fase E maupun Fase F.
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Peran & Hak Akses Pengguna -->
<section class="section-wrap" id="peran" style="background: rgba(15, 23, 42, 0.3);">
    <div class="container">
        <div class="section-header">
            <span class="section-badge">Multi-Role Portal</span>
            <h2 class="section-title">Ruang Kerja Sesuai Tanggung Jawab</h2>
            <p class="section-desc">Hak akses terisolasi untuk memastikan integritas dan keamanan data evaluasi belajar siswa.</p>
        </div>

        <div class="row g-4">
            <div class="col-md-6 col-lg-3">
                <div class="double-bezel h-100">
                    <div class="bezel-core">
                        <span class="role-pill role-admin">Administrator</span>
                        <h5 class="fw-bold text-white mb-2">Pengelola Sistem</h5>
                        <p class="text-muted small mb-0">
                            Kelola master guru, kelas, referensi & mapping mapel, penugasan mengajar, bobot STS, dan konfigurasi identitas sekolah.
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-lg-3">
                <div class="double-bezel h-100">
                    <div class="bezel-core">
                        <span class="role-pill role-guru">Guru Mapel</span>
                        <h5 class="fw-bold text-white mb-2">Pendidik Mata Pelajaran</h5>
                        <p class="text-muted small mb-0">
                            Input nilai Sumatif 1, 2, 3 dan skor STS untuk setiap rombel yang diampu, baik secara manual maupun impor file Excel.
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-lg-3">
                <div class="double-bezel h-100">
                    <div class="bezel-core">
                        <span class="role-pill role-wali">Wali Kelas</span>
                        <h5 class="fw-bold text-white mb-2">Pembina Rombel</h5>
                        <p class="text-muted small mb-0">
                            Pantau kemajuan nilai kelas binaan, catat rekap presensi (Sakit, Izin, Alpa), serta cetak laporan evaluasi STS kolektif.
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-lg-3">
                <div class="double-bezel h-100">
                    <div class="bezel-core">
                        <span class="role-pill role-siswa">Peserta Didik</span>
                        <h5 class="fw-bold text-white mb-2">Siswa / Orang Tua</h5>
                        <p class="text-muted small mb-0">
                            Akses mandiri menggunakan nomor induk (NIS) untuk melihat progres belajar, rekapitulasi nilai STS, dan unduh rapor.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Profil Sekolah & Identitas Terpadu -->
<section class="section-wrap" id="identitas">
    <div class="container">
        <div class="identity-banner">
            <div class="row align-items-center g-4">
                <div class="col-lg-3 text-center">
                    <div class="p-3 bg-white rounded-4 shadow d-inline-flex align-items-center justify-content-center"
                         style="width: 140px; height: 140px;">
                        <?php if ($logoUrl) { ?>
                            <img src="<?= htmlspecialchars($logoUrl) ?>" alt="Logo" style="max-width: 100%; max-height: 100%; object-fit: contain;">
                        <?php } else { ?>
                            <i class="fa-solid fa-school-flag fa-4x text-primary"></i>
                        <?php } ?>
                    </div>
                </div>
                <div class="col-lg-9">
                    <div class="d-flex flex-wrap gap-2 mb-2">
                        <span class="badge bg-primary bg-opacity-25 text-info border border-primary border-opacity-50 px-3 py-1 rounded-pill">
                            NPSN: <?= htmlspecialchars($setting['npsn']) ?>
                        </span>
                        <span class="badge bg-success bg-opacity-25 text-success border border-success border-opacity-50 px-3 py-1 rounded-pill">
                            Akreditasi: <?= htmlspecialchars($setting['akreditasi']) ?>
                        </span>
                    </div>
                    <h3 class="fw-bold text-white mb-1"><?= htmlspecialchars($setting['nama_sekolah']) ?></h3>
                    <p class="text-primary-subtle fw-medium mb-3">
                        <i class="fa-solid fa-quote-left me-1 opacity-50"></i>
                        <?= htmlspecialchars($setting['slogan']) ?>
                    </p>
                    <p class="text-muted small mb-4">
                        <?= htmlspecialchars($setting['deskripsi_sekolah'] ?? '') ?>
                    </p>
                    <div class="row g-3 text-muted small">
                        <div class="col-sm-6">
                            <i class="fa-solid fa-location-dot text-danger me-2"></i>
                            <?= htmlspecialchars($setting['alamat_sekolah']) ?>
                        </div>
                        <div class="col-sm-6">
                            <i class="fa-solid fa-phone text-success me-2"></i>
                            <?= htmlspecialchars($setting['telepon']) ?>
                        </div>
                        <div class="col-sm-6">
                            <i class="fa-solid fa-envelope text-info me-2"></i>
                            <?= htmlspecialchars($setting['email']) ?>
                        </div>
                        <div class="col-sm-6">
                            <i class="fa-solid fa-globe text-warning me-2"></i>
                            <?= htmlspecialchars($setting['website']) ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Footer -->
<footer id="kontak">
    <div class="container">
        <div class="row g-4 mb-4">
            <div class="col-lg-6">
                <div class="d-flex align-items-center gap-2 mb-2">
                    <div class="brand-logo-frame" style="width: 32px; height: 32px;">
                        <?php if ($logoUrl) { ?>
                            <img src="<?= htmlspecialchars($logoUrl) ?>" alt="Logo">
                        <?php } else { ?>
                            <i class="fa-solid fa-graduation-cap text-primary"></i>
                        <?php } ?>
                    </div>
                    <span class="fw-bold text-white"><?= htmlspecialchars($setting['nama_sekolah']) ?></span>
                </div>
                <p class="text-muted small mb-0" style="max-width: 440px;">
                    Sistem Evaluasi Capaian Pembelajaran & Penilaian Tengah Semester (STS) Kurikulum Merdeka.
                </p>
            </div>
            <div class="col-lg-3 col-6">
                <div class="fw-bold text-white mb-2">Tautan Navigasi</div>
                <ul class="list-unstyled small mb-0">
                    <li class="mb-1"><a href="#fitur">Keunggulan Fitur</a></li>
                    <li class="mb-1"><a href="#peran">Hak Akses Pengguna</a></li>
                    <li class="mb-1"><a href="#identitas">Identitas Satuan</a></li>
                    <li><a href="login">Halaman Login</a></li>
                </ul>
            </div>
            <div class="col-lg-3 col-6">
                <div class="fw-bold text-white mb-2">Pimpinan Satuan</div>
                <p class="small text-white mb-1 fw-semibold">
                    <?= htmlspecialchars($setting['nama_kepala_sekolah']) ?>
                </p>
                <div class="text-muted small">Kepala Sekolah</div>
                <div class="text-muted font-monospace small">NIP. <?= htmlspecialchars($setting['nip_kepala_sekolah']) ?></div>
            </div>
        </div>

        <div class="border-top border-secondary border-opacity-25 pt-4 d-flex flex-wrap justify-content-between align-items-center gap-2">
            <div class="small">
                &copy; <?= date('Y') ?> <strong><?= htmlspecialchars($setting['nama_sekolah']) ?></strong>. All rights reserved.
            </div>
            <div class="small">
                <a href="login" class="text-primary fw-semibold">
                    <i class="fa-solid fa-lock me-1"></i> Masuk e-Raport
                </a>
            </div>
        </div>
    </div>
</footer>

<script src="<?= $base_url ?>vendor/bootstrap-5.3.8/js/bootstrap.bundle.min.js"></script>
</body>
</html>
