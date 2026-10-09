<?php
session_start();

if (empty($_SESSION['username']) || !in_array($_SESSION['role'] ?? '', ['admin', 'walikelas'])) {
    die('Akses ditolak. Silakan login sebagai Admin atau Wali Kelas terlebih dahulu.');
}

include_once "config/Database.php";
require_once "models/Ledger.php";
require_once "models/Kelas.php";

$db = new Database();
$conn = $db->connect();

$ledgerModel = new Ledger($conn);
$kelasModel  = new Kelas($conn);

$idKelas = (int)($_GET['kelas'] ?? 0);

// Keamanan khusus Wali Kelas: kunci akses pada kelas perwaliannya
if (($_SESSION['role'] ?? '') === 'walikelas') {
    $idGuru = $_SESSION['id'] ?? '';
    $myClass = $kelasModel->getByWaliKelas($idGuru);
    $myIdKelas = $myClass ? (int)$myClass['id_kelas'] : 0;

    if ($myIdKelas <= 0) {
        die('Anda belum terdaftar sebagai wali kelas aktif.');
    }
    if ($idKelas > 0 && $idKelas !== $myIdKelas) {
        die('Anda hanya dapat mencetak leger untuk kelas perwalian Anda sendiri.');
    }
    $idKelas = $myIdKelas;
}

if ($idKelas <= 0) {
    die('Parameter ID Kelas tidak valid.');
}

$ledgerData = $ledgerModel->getLedgerData($idKelas);
if (!$ledgerData || empty($ledgerData['kelas'])) {
    die('Data kelas tidak ditemukan.');
}

$stats = $ledgerModel->calculateStats($ledgerData);

$kelas        = $ledgerData['kelas'];
$setting      = $ledgerData['setting'];
$mapelList    = $ledgerData['mapel_list'];
$mapelUmum    = $ledgerData['mapel_umum'];
$mapelPilihan = $ledgerData['mapel_pilihan'];
$siswaList    = $ledgerData['siswa_list'];
$grades       = $ledgerData['grades'];

$studentStats   = $stats['student_stats'] ?? [];
$subjectSummary = $stats['subject_summary'] ?? [];

$kelasFormatted = str_replace('-', ' ', $kelas['nama_kelas']);
$fase = ($kelas['tingkat'] === '10' || str_starts_with($kelas['nama_kelas'], 'X-')) ? 'E' : 'F';
$semRaw = trim((string)($setting['semester'] ?? '1'));
$semGanjilGenap = ($semRaw === '2' || stripos($semRaw, 'genap') !== false) ? 'GENAP' : 'GANJIL';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Leger Nilai STS - Kelas <?= htmlspecialchars($kelas['nama_kelas']) ?> (<?= count($siswaList) ?> Siswa)</title>
    <link href="vendor/bootstrap-5.3.8/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        * {
            box-sizing: border-box;
        }
        body {
            background-color: #525659;
            color: #000;
            font-family: Arial, Helvetica, sans-serif;
            margin: 0;
            padding: 0;
        }

        /* Toolbar Kontrol Cetak */
        .print-toolbar {
            position: sticky;
            top: 0;
            z-index: 1000;
            background: #1e293b;
            color: #ffffff;
            padding: 10px 24px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3);
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
        }

        /* Lembar Ledger Cetak Landscape */
        .ledger-page {
            width: 297mm;
            min-height: 210mm;
            padding: 12mm 14mm 10mm 14mm;
            margin: 20px auto;
            background: #ffffff;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.35);
            position: relative;
        }

        .kop-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 8px;
        }
        .kop-table td {
            vertical-align: middle;
        }

        .ledger-title {
            text-align: center;
            font-weight: 800;
            font-size: 14px;
            letter-spacing: 0.5px;
            margin: 6px 0 10px 0;
            text-transform: uppercase;
        }

        .meta-grid {
            width: 100%;
            font-size: 11.5px;
            margin-bottom: 10px;
            border-collapse: collapse;
        }
        .meta-grid td {
            padding: 1px 0;
            vertical-align: top;
        }

        /* Tabel Leger Nilai Matrix */
        .table-ledger {
            width: 100%;
            border-collapse: collapse;
            font-size: 10.5px;
            margin-bottom: 12px;
        }
        .table-ledger th,
        .table-ledger td {
            border: 1px solid #000;
            padding: 3px 4px;
        }
        .table-ledger thead th {
            text-align: center;
            font-weight: bold;
            vertical-align: middle;
            background-color: #f1f5f9;
        }
        .table-ledger thead th.group-umum {
            background-color: #e2e8f0;
        }
        .table-ledger thead th.group-pilihan {
            background-color: #e0f2fe;
        }
        .table-ledger td.text-center {
            text-align: center;
        }
        .table-ledger td.text-start {
            text-align: left;
            padding-left: 6px;
        }
        .table-ledger tfoot td {
            font-weight: bold;
            background-color: #f8fafc;
        }

        /* Tabel Tanda Tangan */
        .signature-table {
            width: 100%;
            font-size: 11px;
            margin-top: 14px;
            border-collapse: collapse;
            page-break-inside: avoid;
        }
        .signature-table td {
            text-align: center;
            vertical-align: top;
            width: 50%;
            padding: 0 8px;
        }
        .signature-space {
            height: 55px;
        }

        @media print {
            body {
                background: #ffffff;
                margin: 0;
                padding: 0;
            }
            .no-print {
                display: none !important;
            }
            .ledger-page {
                box-shadow: none;
                margin: 0;
                padding: 8mm 10mm 6mm 10mm;
                width: 100%;
                min-height: auto;
            }
            @page {
                size: landscape;
                margin: 8mm 10mm 8mm 10mm;
            }
        }
    </style>
</head>
<body>

<!-- Floating Control Toolbar (Hidden on Print) -->
<div class="print-toolbar no-print">
    <div>
        <div class="fw-bold fs-6">
            <i class="fa-solid fa-table me-2 text-warning"></i>
            Leger Nilai STS (Ringkas) &bull; Kelas <?= htmlspecialchars($kelas['nama_kelas']) ?>
        </div>
        <div class="small opacity-75">
            Total <?= count($siswaList) ?> Siswa &bull; <?= count($mapelList) ?> Mata Pelajaran &bull; Wali: <?= htmlspecialchars($kelas['nama_walikelas'] ?? '-') ?>
        </div>
    </div>
    <div class="d-flex align-items-center gap-2">
        <button onclick="window.print();" class="btn btn-primary fw-bold px-3 shadow-sm">
            <i class="fa-solid fa-print me-1"></i> Cetak / Simpan PDF
        </button>
        <a href="controllers/ledger.php?action=excel&type=ringkas&kelas=<?= $idKelas ?>" class="btn btn-outline-success btn-sm text-white border-white">
            <i class="fa-solid fa-file-excel me-1"></i> Excel Ringkas
        </a>
        <a href="controllers/ledger.php?action=excel&type=lengkap&kelas=<?= $idKelas ?>" class="btn btn-outline-info btn-sm text-white border-white">
            <i class="fa-solid fa-file-lines me-1"></i> Excel Lengkap
        </a>
        <button onclick="window.close();" class="btn btn-outline-light btn-sm">
            Tutup Tab
        </button>
    </div>
</div>

<div class="ledger-page">
    <!-- Kop Resmi Sekolah -->
    <table class="kop-table">
        <tr>
            <td style="width: 70px; text-align: center;">
                <?php if (!empty($setting['logo_sekolah']) && file_exists(__DIR__ . '/' . $setting['logo_sekolah'])) { ?>
                    <img src="<?= htmlspecialchars($setting['logo_sekolah']) ?>" alt="Logo" style="width: 58px; height: 58px; object-fit: contain;">
                <?php } else { ?>
                    <i class="fa-solid fa-graduation-cap fa-3x text-primary"></i>
                <?php } ?>
            </td>
            <td style="padding-left: 12px;">
                <div style="font-size: 11px; font-weight: bold; letter-spacing: 0.5px; text-transform: uppercase;">
                    DINAS PENDIDIKAN PROVINSI JAWA TIMUR
                </div>
                <div style="font-size: 15px; font-weight: 800; letter-spacing: -0.01em; color: #0b2246;">
                    <?= htmlspecialchars($setting['nama_sekolah']) ?>
                </div>
                <div style="font-size: 10px; color: #475569;">
                    <?= htmlspecialchars($setting['alamat_sekolah']) ?> &bull; NPSN: <?= htmlspecialchars($setting['npsn']) ?> &bull; Akreditasi: <?= htmlspecialchars($setting['akreditasi']) ?>
                </div>
            </td>
        </tr>
    </table>

    <hr style="border: none; border-top: 1.5px solid #000; margin: 4px 0 8px 0; opacity: 1;">

    <!-- Judul Dokumen -->
    <div class="ledger-title">
        LEGER HASIL ASESMEN SUMATIF TENGAH SEMESTER <?= $semGanjilGenap ?>
    </div>

    <!-- Metadata Kelas & Semester -->
    <table class="meta-grid">
        <tr>
            <td style="width: 10%;">Kelas</td>
            <td style="width: 2%;">:</td>
            <td style="width: 38%; font-weight: bold;">Kelas <?= htmlspecialchars($kelasFormatted) ?> (Fase <?= htmlspecialchars($fase) ?>)</td>
            <td style="width: 12%;">Semester / TA</td>
            <td style="width: 2%;">:</td>
            <td style="width: 36%; font-weight: bold;">Semester <?= htmlspecialchars($setting['semester']) ?> / TA <?= htmlspecialchars($setting['tahun_ajaran']) ?></td>
        </tr>
        <tr>
            <td>Wali Kelas</td>
            <td>:</td>
            <td><?= htmlspecialchars($kelas['nama_walikelas'] ?? '-') ?></td>
            <td>NIP Wali Kelas</td>
            <td>:</td>
            <td><?= htmlspecialchars($kelas['id_guru_walikelas'] ?? '-') ?></td>
        </tr>
    </table>

    <!-- Tabel Matriks Leger Nilai -->
    <table class="table-ledger">
        <thead>
            <tr>
                <th rowspan="2" style="width: 28px;">No</th>
                <th rowspan="2" style="width: 65px;">NIS</th>
                <th rowspan="2" style="width: 75px;">NISN</th>
                <th rowspan="2">Nama Peserta Didik</th>

                <?php if (!empty($mapelUmum)) { ?>
                    <th colspan="<?= count($mapelUmum) ?>" class="group-umum">Kelompok Umum</th>
                <?php } ?>

                <?php if (!empty($mapelPilihan)) { ?>
                    <th colspan="<?= count($mapelPilihan) ?>" class="group-pilihan">Kelompok Pilihan</th>
                <?php } ?>

                <th rowspan="2" style="width: 48px;">Total<br>Nilai</th>
                <th rowspan="2" style="width: 48px;">Rata<br>Rata</th>
                <th colspan="3" style="width: 70px;">Ketidakhadiran</th>
            </tr>
            <tr>
                <!-- Sub-header Kelompok Umum -->
                <?php
                $noU = 1;
                foreach ($mapelUmum as $m) {
                ?>
                    <th style="min-width: 42px;" title="<?= htmlspecialchars($m['nama_mapel']) ?> (Guru: <?= htmlspecialchars($m['nama_guru']) ?>)">
                        <div style="font-size: 8.5px; opacity: 0.8;"><?= $noU++ ?></div>
                        <div class="font-monospace fw-bold" style="font-size: 9.5px;"><?= htmlspecialchars($m['id_mapel']) ?></div>
                    </th>
                <?php } ?>

                <!-- Sub-header Kelompok Pilihan -->
                <?php
                $noP = 1;
                foreach ($mapelPilihan as $m) {
                ?>
                    <th style="min-width: 42px;" title="<?= htmlspecialchars($m['nama_mapel']) ?> (Guru: <?= htmlspecialchars($m['nama_guru']) ?>)">
                        <div style="font-size: 8.5px; opacity: 0.8;"><?= $noP++ ?></div>
                        <div class="font-monospace fw-bold" style="font-size: 9.5px;"><?= htmlspecialchars($m['id_mapel']) ?></div>
                    </th>
                <?php } ?>

                <th style="width: 23px;">S</th>
                <th style="width: 23px;">I</th>
                <th style="width: 23px;">A</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if (empty($siswaList)) {
                $totalColSpan = 4 + count($mapelList) + 5;
                echo "<tr><td colspan='$totalColSpan' class='text-center py-4 text-muted'>Belum ada siswa di kelas ini.</td></tr>";
            } else {
                $no = 1;
                foreach ($siswaList as $s) {
                    $nis = $s['nis'];
                    $st  = $studentStats[$nis] ?? ['total_ats' => 0, 'avg_ats' => 0];
            ?>
                <tr>
                    <td class="text-center"><?= $no++ ?></td>
                    <td class="text-center font-monospace"><?= htmlspecialchars($s['nis']) ?></td>
                    <td class="text-center font-monospace" style="font-size: 9.5px;"><?= htmlspecialchars($s['nisn'] ?? '-') ?></td>
                    <td class="text-start fw-semibold text-uppercase"><?= htmlspecialchars(mb_strtoupper((string)$s['nama'], 'UTF-8')) ?></td>

                    <!-- Nilai Kelompok Umum -->
                    <?php foreach ($mapelUmum as $m) {
                        $idP = (int)$m['id_pengampu'];
                        $val = $grades[$nis][$idP]['ats'] ?? null;
                    ?>
                        <td class="text-center"><?= ($val !== null && $val !== '') ? (float)$val : '-' ?></td>
                    <?php } ?>

                    <!-- Nilai Kelompok Pilihan -->
                    <?php foreach ($mapelPilihan as $m) {
                        $idP = (int)$m['id_pengampu'];
                        $val = $grades[$nis][$idP]['ats'] ?? null;
                    ?>
                        <td class="text-center"><?= ($val !== null && $val !== '') ? (float)$val : '-' ?></td>
                    <?php } ?>

                    <!-- Total & Rata-rata ATS -->
                    <td class="text-center fw-bold text-dark"><?= $st['total_ats'] > 0 ? (float)$st['total_ats'] : '-' ?></td>
                    <td class="text-center fw-bold text-primary"><?= $st['avg_ats'] > 0 ? (float)$st['avg_ats'] : '-' ?></td>

                    <!-- Ketidakhadiran -->
                    <td class="text-center"><?= (int)$s['sakit'] ?></td>
                    <td class="text-center"><?= (int)$s['izin'] ?></td>
                    <td class="text-center"><?= (int)$s['alpa'] ?></td>
                </tr>
            <?php
                }
            }
            ?>
        </tbody>
        <tfoot>
            <!-- Rata-rata Kelas -->
            <tr>
                <td colspan="4" class="text-center">Rata-rata Kelas</td>
                <?php foreach ($mapelList as $m) {
                    $idP = (int)$m['id_pengampu'];
                    $avgVal = $subjectSummary[$idP]['avg_ats'] ?? '-';
                ?>
                    <td class="text-center"><?= $avgVal ?></td>
                <?php } ?>
                <td colspan="5"></td>
            </tr>
            <!-- Nilai Tertinggi -->
            <tr>
                <td colspan="4" class="text-center">Nilai Tertinggi</td>
                <?php foreach ($mapelList as $m) {
                    $idP = (int)$m['id_pengampu'];
                    $maxVal = $subjectSummary[$idP]['max_ats'] ?? '-';
                ?>
                    <td class="text-center"><?= $maxVal ?></td>
                <?php } ?>
                <td colspan="5"></td>
            </tr>
            <!-- Nilai Terendah -->
            <tr>
                <td colspan="4" class="text-center">Nilai Terendah</td>
                <?php foreach ($mapelList as $m) {
                    $idP = (int)$m['id_pengampu'];
                    $minVal = $subjectSummary[$idP]['min_ats'] ?? '-';
                ?>
                    <td class="text-center"><?= $minVal ?></td>
                <?php } ?>
                <td colspan="5"></td>
            </tr>
        </tfoot>
    </table>

    <!-- Tanda Tangan -->
    <table class="signature-table">
        <tr>
            <td>
                Mengetahui,<br>
                Kepala Sekolah
            </td>
            <td>
                <?= htmlspecialchars($setting['tempat_rapor'] ?? 'Nganjuk') ?>, <?= htmlspecialchars($setting['tanggal_rapor'] ?? date('d F Y')) ?><br>
                Wali Kelas
            </td>
        </tr>
        <tr>
            <td class="signature-space"></td>
            <td class="signature-space"></td>
        </tr>
        <tr>
            <td>
                <span style="white-space: nowrap; display: inline-block;"><u><strong><?= htmlspecialchars($setting['nama_kepala_sekolah'] ?? '-') ?></strong></u></span><br>
                NIP. <?= htmlspecialchars($setting['nip_kepala_sekolah'] ?? '-') ?>
            </td>
            <td>
                <span style="white-space: nowrap; display: inline-block;"><u><strong><?= htmlspecialchars($kelas['nama_walikelas'] ?? '-') ?></strong></u></span><br>
                NIP. <?= htmlspecialchars($kelas['id_guru_walikelas'] ?? '-') ?>
            </td>
        </tr>
    </table>
</div>

</body>
</html>
