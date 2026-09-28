<?php
require_once __DIR__ . '/config/Config.php';
require_once __DIR__ . '/config/Database.php';
require_once __DIR__ . '/models/Setting.php';

if (!empty($_SESSION['username'])) {
    header('location:home');
    exit;
}

$db = new Database();
$conn = $db->connect();
$settingModel = new Setting($conn);
$setting = $settingModel->get();

$logoUrl = (!empty($setting['logo_sekolah']) && file_exists(__DIR__ . '/' . $setting['logo_sekolah']))
    ? $base_url . $setting['logo_sekolah']
    : null;
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>E-Raport Tengah Semester &mdash; SMAN 1 PRAMBON</title>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&family=Cinzel:wght@700;800;900&family=Great+Vibes&display=swap" rel="stylesheet">

    <!-- Bootstrap 5.3 & Font Awesome 6.5 -->
    <link href="<?= $base_url ?>vendor/bootstrap-5.3.8/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <style>
        :root {
            --brand-blue: #081d3d;
            --brand-deep: #030c1c;
            --brand-gold: #f59e0b;
            --brand-gold-bright: #fbbf24;
            --btn-blue: #5b9bd5;
            --btn-blue-hover: #488ac5;
            --input-bg: #eef5fc;
            --input-border: #d4e5f7;
            --ease: cubic-bezier(0.16, 1, 0.3, 1);
        }

        * {
            box-sizing: border-box;
        }

        body {
            font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, sans-serif;
            background-color: #051329;
            background-image: 
                radial-gradient(circle at 50% 18%, #143e7a 0%, rgba(13, 45, 94, 0.6) 40%, transparent 80%),
                radial-gradient(circle at 85% 60%, rgba(37, 99, 235, 0.25) 0%, transparent 50%),
                linear-gradient(180deg, #071935 0%, #030c1c 100%);
            min-height: 100vh;
            margin: 0;
            padding: 24px 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            overflow-x: hidden;
        }

        /* ==========================================================================
           3D CINEMATIC ATMOSPHERE & BACKGROUND ART
           ========================================================================== */
        .stage-backdrop {
            position: fixed;
            inset: 0;
            pointer-events: none;
            z-index: 0;
            overflow: hidden;
        }

        /* 3D Volumetric Stage Light Spotlight */
        .volumetric-light {
            position: absolute;
            top: -20%;
            left: 50%;
            transform: translateX(-50%) rotate(-15deg);
            width: 800px;
            height: 1200px;
            background: linear-gradient(180deg, rgba(56, 189, 248, 0.12) 0%, rgba(56, 189, 248, 0) 80%);
            filter: blur(70px);
        }

        /* Giant Golden Typography Watermark (Matching GEMPITA Style) */
        .watermark-container {
            position: absolute;
            top: 28px;
            left: 4%;
            user-select: none;
            opacity: 0.9;
        }
        .watermark-year {
            font-family: 'Cinzel', serif;
            font-size: clamp(24px, 3.2vw, 42px);
            font-weight: 900;
            letter-spacing: 0.16em;
            color: rgba(251, 191, 36, 0.32);
            text-shadow: 0 0 30px rgba(251, 191, 36, 0.4);
            margin-bottom: -10px;
        }
        .watermark-title {
            font-family: 'Cinzel', serif;
            font-size: clamp(64px, 8.5vw, 125px);
            font-weight: 900;
            letter-spacing: 0.04em;
            line-height: 0.9;
            background: linear-gradient(180deg, rgba(255, 255, 255, 0.28) 0%, rgba(251, 191, 36, 0.22) 50%, rgba(0, 0, 0, 0) 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        .watermark-sub {
            font-size: 11px;
            letter-spacing: 0.4em;
            text-transform: uppercase;
            font-weight: 800;
            color: rgba(255, 255, 255, 0.3);
            margin-top: 4px;
        }

        /* Left Floating Outline Badges in Background (like GEMPITA) */
        .bg-outline-features {
            position: absolute;
            top: 260px;
            left: 4.5%;
            display: flex;
            gap: 16px;
            user-select: none;
        }
        .bg-feature-pill {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 6px;
            width: 80px;
            text-align: center;
            opacity: 0.4;
        }
        .bg-feature-pill .icon-ring {
            width: 44px;
            height: 44px;
            border-radius: 50%;
            border: 1.5px solid rgba(251, 191, 36, 0.6);
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--brand-gold-bright);
            font-size: 16px;
        }
        .bg-feature-pill span {
            font-size: 9.5px;
            font-weight: 700;
            color: #ffffff;
            line-height: 1.2;
        }

        /* Elegant Golden Handwritten Script (Bottom Left) */
        .bg-script-flourish {
            position: absolute;
            bottom: 30px;
            left: 3.5%;
            font-family: 'Great Vibes', cursive;
            font-size: clamp(34px, 4.8vw, 68px);
            color: rgba(255, 255, 255, 0.22);
            transform: rotate(-3deg);
            line-height: 1.15;
            user-select: none;
            text-shadow: 0 4px 20px rgba(0, 0, 0, 0.5);
        }

        /* Right Background: 3D Eagle Mascot & Architectural Horizon */
        .bg-right-monument {
            position: absolute;
            right: 0;
            bottom: 0;
            width: clamp(380px, 42vw, 650px);
            height: 92vh;
            pointer-events: none;
            overflow: hidden;
        }
        .monument-graphic {
            position: absolute;
            right: 3%;
            bottom: 0;
            height: 82vh;
            opacity: 0.35;
            filter: drop-shadow(0 0 40px rgba(56, 189, 248, 0.3));
        }

        /* Golden Ribbon Sweep (3D Arc across bottom left) */
        .gold-ribbon-arc {
            position: absolute;
            bottom: -60px;
            left: -80px;
            width: 480px;
            height: 280px;
            border-radius: 50%;
            border-top: 14px solid rgba(251, 191, 36, 0.18);
            border-left: 8px solid rgba(251, 191, 36, 0.12);
            transform: rotate(-25deg);
            filter: blur(2px);
        }

        /* ==========================================================================
           THE 3D FLOATING DUAL-PANEL CARD (GEMPITA AESTHETIC)
           ========================================================================== */
        .portal-card-3d {
            position: relative;
            z-index: 10;
            width: 100%;
            max-width: 1040px;
            border-radius: 26px;
            background: transparent;
            box-shadow: 
                0 45px 110px -15px rgba(0, 0, 0, 0.9),
                0 15px 35px -5px rgba(0, 0, 0, 0.5),
                0 0 0 1px rgba(255, 255, 255, 0.18),
                inset 0 1px 2px rgba(255, 255, 255, 0.35);
            display: flex;
            overflow: hidden;
            backdrop-filter: blur(32px);
            -webkit-backdrop-filter: blur(32px);
            transition: transform 0.4s var(--ease);
        }

        /* --------------------------------------------------------------------------
           LEFT PANEL: Frosted Dark Glass (Glassmorphism)
           -------------------------------------------------------------------------- */
        .panel-glass-left {
            flex: 1.08;
            background: linear-gradient(145deg, rgba(13, 39, 78, 0.74) 0%, rgba(6, 21, 46, 0.90) 100%);
            padding: 46px 42px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            position: relative;
            border-right: 1px solid rgba(255, 255, 255, 0.12);
        }

        /* Glass Specular Top Highlight */
        .panel-glass-left::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 1.5px;
            background: linear-gradient(90deg, transparent 0%, rgba(255, 255, 255, 0.5) 40%, rgba(251, 191, 36, 0.4) 70%, transparent 100%);
        }

        .glass-head-badge {
            display: flex;
            align-items: center;
            gap: 16px;
            margin-bottom: 24px;
        }

        /* Square Rounded Glass Emblem Box (Matches Reference) */
        .emblem-glass-box {
            width: 62px;
            height: 62px;
            border-radius: 16px;
            background: rgba(255, 255, 255, 0.09);
            border: 1.5px solid rgba(255, 255, 255, 0.25);
            box-shadow: 
                0 10px 24px rgba(0, 0, 0, 0.4),
                inset 0 1px 2px rgba(255, 255, 255, 0.3);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 9px;
            flex-shrink: 0;
        }
        .emblem-glass-box img {
            max-width: 100%;
            max-height: 100%;
            object-fit: contain;
        }

        .eyebrow-golden-pill {
            font-size: 11px;
            font-weight: 800;
            letter-spacing: 0.18em;
            text-transform: uppercase;
            color: var(--brand-gold-bright);
            margin-bottom: 6px;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }

        .main-headline {
            font-size: clamp(24px, 2.6vw, 33px);
            font-weight: 800;
            line-height: 1.2;
            letter-spacing: -0.02em;
            color: #ffffff;
            margin-bottom: 12px;
        }
        .main-headline .gold-text {
            color: var(--brand-gold-bright);
            display: block;
            text-shadow: 0 0 25px rgba(251, 191, 36, 0.4);
        }

        .motto-line {
            font-size: 13.5px;
            font-weight: 600;
            color: #e2e8f0;
            margin-bottom: 8px;
        }
        .desc-text {
            font-size: 12.5px;
            color: #94a3b8;
            line-height: 1.6;
            margin-bottom: 26px;
            max-width: 420px;
        }

        /* 3 Pillar Cards (like in GEMPITA) */
        .glass-pillar-row {
            display: flex;
            gap: 12px;
            margin-bottom: 26px;
        }
        .pillar-item {
            flex: 1;
            background: rgba(255, 255, 255, 0.04);
            border: 1px solid rgba(255, 255, 255, 0.08);
            border-radius: 14px;
            padding: 12px 8px;
            text-align: center;
            transition: all 0.25s var(--ease);
        }
        .pillar-item:hover {
            background: rgba(255, 255, 255, 0.08);
            border-color: rgba(251, 191, 36, 0.35);
            transform: translateY(-2px);
        }
        .pillar-icon-circle {
            width: 30px;
            height: 30px;
            margin: 0 auto 6px auto;
            border-radius: 50%;
            background: rgba(251, 191, 36, 0.15);
            border: 1px solid rgba(251, 191, 36, 0.4);
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--brand-gold-bright);
            font-size: 12.5px;
        }
        .pillar-label {
            font-size: 10.5px;
            font-weight: 700;
            color: #ffffff;
            line-height: 1.25;
        }

        .glass-bottom-meta {
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            padding-top: 16px;
            font-size: 11px;
            color: #94a3b8;
            line-height: 1.55;
        }
        .glass-bottom-meta strong {
            color: #f1f5f9;
        }

        /* --------------------------------------------------------------------------
           RIGHT PANEL: Solid Crisp White 3D Elevated Card
           -------------------------------------------------------------------------- */
        .panel-white-right {
            flex: 0.92;
            background: #ffffff;
            padding: 42px 38px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            position: relative;
            box-shadow: inset 1px 0 0 rgba(0, 0, 0, 0.04);
        }

        .white-card-header {
            text-align: center;
            margin-bottom: 22px;
        }
        .badge-avatar-school {
            width: 54px;
            height: 54px;
            border-radius: 16px;
            background: #edf5fd;
            border: 1px solid #d4e5f7;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 8px;
            margin-bottom: 12px;
            box-shadow: 0 6px 16px rgba(9, 29, 61, 0.08);
        }
        .badge-avatar-school img {
            max-width: 100%;
            max-height: 100%;
            object-fit: contain;
        }

        .portal-title {
            font-size: 21px;
            font-weight: 800;
            color: #0b2246;
            letter-spacing: -0.02em;
            margin-bottom: 4px;
        }
        .portal-subtitle {
            font-size: 12px;
            color: #64748b;
            margin: 0;
        }

        /* Role Selector Pills */
        .role-pill-nav {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 6px;
            background: #f1f5f9;
            padding: 4px;
            border-radius: 12px;
            margin-bottom: 20px;
        }
        .role-pill-btn {
            background: transparent;
            border: none;
            color: #64748b;
            font-size: 11.5px;
            font-weight: 700;
            padding: 7px 4px;
            border-radius: 9px;
            cursor: pointer;
            transition: all 0.2s var(--ease);
            text-align: center;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 3px;
        }
        .role-pill-btn i {
            font-size: 13px;
        }
        .role-pill-btn.active {
            background: #2563eb;
            color: #ffffff;
            box-shadow: 0 4px 10px rgba(37, 99, 235, 0.35);
        }
        .role-pill-btn:hover:not(.active) {
            background: #e2e8f0;
            color: #0f172a;
        }

        /* Form Inputs (Light Blue Tint matching GEMPITA Reference) */
        .input-group-gem {
            position: relative;
            margin-bottom: 14px;
        }
        .input-group-gem .gem-icon {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: #64748b;
            font-size: 14px;
            pointer-events: none;
        }
        .input-field-gem {
            width: 100%;
            height: 46px;
            background: var(--input-bg);
            border: 1px solid var(--input-border);
            border-radius: 11px;
            color: #0f172a;
            padding: 0 42px 0 42px;
            font-size: 13.5px;
            font-weight: 500;
            transition: all 0.2s var(--ease);
        }
        .input-field-gem:focus {
            outline: none;
            background: #ffffff;
            border-color: #3b82f6;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.16);
        }
        .input-field-gem::placeholder {
            color: #94a3b8;
            font-weight: 400;
        }
        .gem-pwd-toggle {
            position: absolute;
            right: 14px;
            top: 50%;
            transform: translateY(-50%);
            background: transparent;
            border: none;
            color: #64748b;
            cursor: pointer;
            padding: 4px;
            font-size: 14px;
        }
        .gem-pwd-toggle:hover {
            color: #0f172a;
        }

        /* Dynamic Hint Badge */
        .dynamic-role-hint {
            background: #eff6ff;
            border: 1px solid #bfdbfe;
            border-radius: 10px;
            padding: 8px 12px;
            font-size: 11.5px;
            color: #1d4ed8;
            margin-bottom: 16px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        /* Mock reCAPTCHA Box (as seen in GEMPITA) */
        .recaptcha-widget-card {
            background: #fafafa;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            padding: 8px 14px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 18px;
        }
        .recaptcha-label {
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 12.5px;
            color: #374151;
            font-weight: 500;
            cursor: pointer;
            margin: 0;
        }
        .recaptcha-label input[type="checkbox"] {
            width: 20px;
            height: 20px;
            accent-color: #2563eb;
            cursor: pointer;
        }
        .recaptcha-brand {
            text-align: center;
            color: #9ca3af;
            font-size: 9px;
            line-height: 1.1;
        }
        .recaptcha-brand i {
            font-size: 16px;
            color: #2563eb;
            display: block;
            margin-bottom: 2px;
        }

        /* Submit Button (GEMPITA Sky Blue Pill) */
        .btn-submit-gempita {
            width: 100%;
            height: 46px;
            border-radius: 10px;
            background: linear-gradient(135deg, #5b9bd5 0%, #4688c2 100%);
            border: none;
            color: #ffffff;
            font-size: 14px;
            font-weight: 700;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            box-shadow: 0 8px 18px rgba(91, 155, 213, 0.4);
            cursor: pointer;
            transition: all 0.25s var(--ease);
        }
        .btn-submit-gempita:hover {
            background: linear-gradient(135deg, #4688c2 0%, #3778af 100%);
            transform: translateY(-2px);
            box-shadow: 0 12px 24px rgba(91, 155, 213, 0.55);
        }
        .btn-submit-gempita:active {
            transform: scale(0.98);
        }

        .back-nav-footer {
            text-align: center;
            margin-top: 18px;
            padding-top: 12px;
            border-top: 1px solid #f1f5f9;
        }
        .back-nav-footer a {
            color: #64748b;
            text-decoration: none;
            font-size: 12px;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            transition: color 0.2s ease;
        }
        .back-nav-footer a:hover {
            color: #0f172a;
        }

        @media (max-width: 991px) {
            .portal-card-3d {
                flex-direction: column;
                max-width: 500px;
            }
            .panel-glass-left {
                border-right: none;
                border-bottom: 1px solid rgba(255, 255, 255, 0.12);
                padding: 34px 26px;
            }
            .panel-white-right {
                padding: 34px 26px;
            }
            .watermark-container, .bg-outline-features, .bg-script-flourish, .bg-right-monument {
                display: none;
            }
        }
    </style>
</head>
<body>

<!-- 3D Cinematic Atmosphere & Background Art -->
<div class="stage-backdrop">
    <div class="volumetric-light"></div>

    <!-- Golden Watermark Top Left (Matching GEMPITA Poster) -->
    <div class="watermark-container">
        <div class="watermark-year">2026</div>
        <div class="watermark-title">E-RAPORT STS</div>
        <div class="watermark-sub">&mdash; SMAN 1 PRAMBON NGANJUK &mdash;</div>
    </div>

    <!-- 3 Circular Outline Badges in Background (like GEMPITA) -->
    <div class="bg-outline-features">
        <div class="bg-feature-pill">
            <div class="icon-ring"><i class="fa-solid fa-lightbulb"></i></div>
            <span>Inovasi Nyata</span>
        </div>
        <div class="bg-feature-pill">
            <div class="icon-ring"><i class="fa-solid fa-users"></i></div>
            <span>Akurasi Dapodik</span>
        </div>
        <div class="bg-feature-pill">
            <div class="icon-ring"><i class="fa-solid fa-award"></i></div>
            <span>Prestasi Belajar</span>
        </div>
    </div>

    <!-- Golden Ribbon Arc -->
    <div class="gold-ribbon-arc"></div>

    <!-- Elegant Cursive Flourish on Bottom Left -->
    <div class="bg-script-flourish">
        Unggul dalam Prestasi, Berkarakter &bull; SMAN 1 Prambon
    </div>

    <!-- Right Background: Monument & Bridge Architectural Silhouette -->
    <div class="bg-right-monument">
        <svg class="monument-graphic" viewBox="0 0 260 620" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M130 15L145 130H115L130 15Z" fill="url(#monGradTop)"/>
            <path d="M115 130L100 450H160L145 130H115Z" fill="url(#monGradMid)"/>
            <path d="M80 450L30 620H230L180 450H80Z" fill="url(#monGradBase)"/>
            <!-- 3D Cable Stayed Lines -->
            <line x1="130" y1="20" x2="10" y2="620" stroke="rgba(56, 189, 248, 0.15)" stroke-width="1.5"/>
            <line x1="130" y1="20" x2="60" y2="620" stroke="rgba(56, 189, 248, 0.12)" stroke-width="1.5"/>
            <line x1="130" y1="20" x2="200" y2="620" stroke="rgba(56, 189, 248, 0.12)" stroke-width="1.5"/>
            <line x1="130" y1="20" x2="250" y2="620" stroke="rgba(56, 189, 248, 0.15)" stroke-width="1.5"/>
            <defs>
                <linearGradient id="monGradTop" x1="130" y1="15" x2="130" y2="130" gradientUnits="userSpaceOnUse">
                    <stop stop-color="#38bdf8" stop-opacity="0.8"/>
                    <stop offset="1" stop-color="#0284c7" stop-opacity="0.3"/>
                </linearGradient>
                <linearGradient id="monGradMid" x1="130" y1="130" x2="130" y2="450" gradientUnits="userSpaceOnUse">
                    <stop stop-color="#0284c7" stop-opacity="0.35"/>
                    <stop offset="1" stop-color="#0f172a" stop-opacity="0.7"/>
                </linearGradient>
                <linearGradient id="monGradBase" x1="130" y1="450" x2="130" y2="620" gradientUnits="userSpaceOnUse">
                    <stop stop-color="#0f172a" stop-opacity="0.7"/>
                    <stop offset="1" stop-color="#030c1c" stop-opacity="0.95"/>
                </linearGradient>
            </defs>
        </svg>
    </div>
</div>

<!-- ==========================================================================
     THE 3D FLOATING DUAL-PANEL CARD
     ========================================================================== -->
<div class="portal-card-3d">

    <!-- LEFT PANEL: Dark Frosted Glassmorphism -->
    <div class="panel-glass-left">
        <div>
            <!-- Header Emblem: Official Jawa Timur / SMAN 1 Prambon -->
            <div class="glass-head-badge">
                <div class="emblem-glass-box">
                    <?php if ($logoUrl) { ?>
                        <img src="<?= htmlspecialchars($logoUrl) ?>" alt="Logo">
                    <?php } else { ?>
                        <!-- Shield Emblem Jawa Timur / Tut Wuri Handayani Vector -->
                        <svg viewBox="0 0 48 54" fill="none" xmlns="http://www.w3.org/2000/svg" style="width: 100%; height: 100%;">
                            <path d="M24 2L44 9V26C44 40 24 51 24 51C24 51 4 40 4 26V9L24 2Z" fill="url(#shldGrad2)" stroke="#fbbf24" stroke-width="2"/>
                            <path d="M24 11L28 19L36 20L30 26L32 34L24 29L16 34L18 26L12 20L20 19L24 11Z" fill="#fbbf24"/>
                            <defs>
                                <linearGradient id="shldGrad2" x1="24" y1="2" x2="24" y2="51" gradientUnits="userSpaceOnUse">
                                    <stop stop-color="#1e3a8a"/>
                                    <stop offset="1" stop-color="#0a192f"/>
                                </linearGradient>
                            </defs>
                        </svg>
                    <?php } ?>
                </div>
                <div>
                    <div class="text-white fw-bold fs-6" style="letter-spacing: -0.01em; line-height: 1.2;">
                        DINAS PENDIDIKAN
                    </div>
                    <div class="small" style="color: #93c5fd; font-size: 11px; letter-spacing: 0.08em; font-weight: 600;">
                        PROVINSI JAWA TIMUR
                    </div>
                </div>
            </div>

            <!-- Eyebrow Tag -->
            <div class="eyebrow-golden-pill">
                <span>E-RAPORT STS 2026</span>
            </div>

            <!-- Main Headline (E-Raport Tengah Semester SMAN 1 PRAMBON) -->
            <h1 class="main-headline">
                E-Raport Tengah Semester
                <span class="gold-text">SMAN 1 PRAMBON</span>
            </h1>

            <div class="motto-line">
                Unggul dalam Prestasi, Berkarakter, Berbudaya Lingkungan
            </div>

            <p class="desc-text">
                Portal evaluasi pembelajaran resmi Asesmen Sumatif Tengah Semester (STS) Kurikulum Merdeka bagi seluruh peserta didik dan dewan guru.
            </p>

            <!-- 3 Feature Pillars (Icons matching GEMPITA Reference) -->
            <div class="glass-pillar-row">
                <div class="pillar-item">
                    <div class="pillar-icon-circle"><i class="fa-solid fa-calculator"></i></div>
                    <div class="pillar-label">Asesmen Sumatif</div>
                </div>
                <div class="pillar-item">
                    <div class="pillar-icon-circle"><i class="fa-solid fa-database"></i></div>
                    <div class="pillar-label">Basis Dapodik</div>
                </div>
                <div class="pillar-item">
                    <div class="pillar-icon-circle"><i class="fa-solid fa-file-invoice"></i></div>
                    <div class="pillar-label">Rapor Standar</div>
                </div>
            </div>
        </div>

        <!-- Glass Footer Meta -->
        <div class="glass-bottom-meta">
            <div><strong>Portal Resmi Akademik &bull; SMAN 1 Prambon Nganjuk</strong></div>
            <div>NPSN: <?= htmlspecialchars($setting['npsn']) ?> &bull; Akreditasi <?= htmlspecialchars($setting['akreditasi']) ?> &bull; Semester <?= htmlspecialchars($setting['semester']) ?> TA <?= htmlspecialchars($setting['tahun_ajaran']) ?></div>
        </div>
    </div>

    <!-- RIGHT PANEL: Solid Crisp White Elevated Card -->
    <div class="panel-white-right">
        <div class="white-card-header">
            <div class="badge-avatar-school">
                <?php if ($logoUrl) { ?>
                    <img src="<?= htmlspecialchars($logoUrl) ?>" alt="Logo">
                <?php } else { ?>
                    <!-- Graduation Cap Icon in Blue -->
                    <i class="fa-solid fa-graduation-cap fa-2x" style="color: #2563eb;"></i>
                <?php } ?>
            </div>
            <h2 class="portal-title">Masuk ke Portal Peserta</h2>
            <p class="portal-sub">Gunakan NIP / NIS sebagai username dan password.</p>
        </div>

        <!-- Role Selector Tabs -->
        <div class="role-pill-nav" id="rolePillNav">
            <button type="button" class="role-pill-btn" data-role="guru">
                <i class="fa-solid fa-chalkboard-user"></i>
                <span>Guru</span>
            </button>
            <button type="button" class="role-pill-btn" data-role="walikelas">
                <i class="fa-solid fa-users-line"></i>
                <span>Wali</span>
            </button>
            <button type="button" class="role-pill-btn active" data-role="siswa">
                <i class="fa-solid fa-user-graduate"></i>
                <span>Siswa</span>
            </button>
            <button type="button" class="role-pill-btn" data-role="admin">
                <i class="fa-solid fa-user-shield"></i>
                <span>Admin</span>
            </button>
        </div>

        <!-- Dynamic Role Hint -->
        <div class="dynamic-role-hint" id="dynamicRoleHint">
            <i class="fa-solid fa-circle-info text-primary"></i>
            <span id="roleHintText">Siswa: Masukkan <strong>NIS</strong> (Nomor Induk Siswa).</span>
        </div>

        <!-- Form Login -->
        <form action="controllers/login.php" method="POST" id="formLogin">
            <input type="hidden" name="submit_validate" value="1">
            <select name="role" id="roleSelectField" class="d-none">
                <option value="guru">Guru</option>
                <option value="walikelas">Wali Kelas</option>
                <option value="siswa" selected>Siswa</option>
                <option value="admin">Admin</option>
            </select>

            <!-- Username Input -->
            <div class="input-group-gem">
                <i class="fa-solid fa-user gem-icon"></i>
                <input type="text" class="input-field-gem" id="usernameInput" name="username"
                       placeholder="Nomor Induk Siswa (Contoh: 7176 / 6818)" required autocomplete="username">
            </div>

            <!-- Password Input -->
            <div class="input-group-gem">
                <i class="fa-solid fa-lock gem-icon"></i>
                <input type="password" class="input-field-gem" id="passwordInput" name="pass"
                       placeholder="Password Akun" required autocomplete="current-password">
                <button type="button" class="gem-pwd-toggle" id="btnTogglePwd" aria-label="Toggle password">
                    <i class="fa-solid fa-eye" id="eyeIcon"></i>
                </button>
            </div>

            <!-- Captcha Checkbox Simulation (GEMPITA Style) -->
            <div class="recaptcha-widget-card">
                <label class="recaptcha-label">
                    <input type="checkbox" id="humanCheck" checked>
                    <span>Saya bukan robot</span>
                </label>
                <div class="recaptcha-brand">
                    <i class="fa-solid fa-rotate"></i>
                    <span>e-Raport Security</span>
                </div>
            </div>

            <!-- Submit Button (GEMPITA Style Sky Blue Pill) -->
            <button type="submit" class="btn-submit-gempita">
                <i class="fa-solid fa-right-to-bracket"></i>
                <span>Masuk</span>
            </button>
        </form>

        <div class="back-nav-footer">
            <a href="landing">
                <i class="fa-solid fa-arrow-left"></i>
                <span>Kembali ke Beranda</span>
            </a>
        </div>
    </div>
</div>

<script>
// Role Switcher & Dynamic Hints
const roleButtons = document.querySelectorAll('.role-pill-btn');
const roleSelect  = document.getElementById('roleSelectField');
const hintText    = document.getElementById('roleHintText');
const userInput   = document.getElementById('usernameInput');

const roleConfigs = {
    'siswa': {
        hint: 'Siswa: Masukkan <strong>NIS</strong> (Nomor Induk Siswa).',
        placeholder: 'Nomor Induk Siswa (Contoh: 7176 / 6818)'
    },
    'guru': {
        hint: 'Guru: Masukkan <strong>NIP 18-digit</strong> atau nama tanpa spasi.',
        placeholder: 'NIP / Username Guru'
    },
    'walikelas': {
        hint: 'Wali Kelas: Masukkan <strong>NIP</strong> atau username akun perwalian.',
        placeholder: 'NIP / Username Wali Kelas'
    },
    'admin': {
        hint: 'Admin: Masukkan kredensial pengelola sistem.',
        placeholder: 'Username Administrator'
    }
};

roleButtons.forEach(btn => {
    btn.addEventListener('click', () => {
        roleButtons.forEach(b => b.classList.remove('active'));
        btn.classList.add('active');
        const role = btn.getAttribute('data-role');
        roleSelect.value = role;

        if (roleConfigs[role]) {
            hintText.innerHTML = roleConfigs[role].hint;
            userInput.placeholder = roleConfigs[role].placeholder;
        }
        userInput.focus();
    });
});

// Toggle Password Visibility
const btnToggle = document.getElementById('btnTogglePwd');
const pwdField  = document.getElementById('passwordInput');
const eyeIcon   = document.getElementById('eyeIcon');

btnToggle?.addEventListener('click', () => {
    const isPassword = pwdField.type === 'password';
    pwdField.type = isPassword ? 'text' : 'password';
    eyeIcon.className = isPassword ? 'fa-solid fa-eye-slash' : 'fa-solid fa-eye';
});
</script>

</body>
</html>
