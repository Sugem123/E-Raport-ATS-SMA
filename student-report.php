<?php
session_start();
if (empty($_SESSION['username'])) {
    die('Akses ditolak. Silakan login terlebih dahulu.');
}

include_once "config/Database.php";
require_once "models/Student.php";
require_once "models/Bobot.php";
require_once "models/Setting.php";

$db = new Database();
$conn = $db->connect();

$studentModel = new Student($conn);
$settingModel = new Setting($conn);

// Parameter NIS dari URL atau session siswa
$nis = $_GET['nis'] ?? '';
if (empty($nis) && ($_SESSION['role'] ?? '') === 'siswa') {
    $nis = $_SESSION['id'] ?? '';
}

// Support ID User juga jika dipanggil via ?id=...
if (empty($nis) && isset($_GET['id'])) {
    $idUser = (int)$_GET['id'];
    $qUser = mysqli_query($conn, "SELECT nis FROM tb_siswa WHERE id_user = $idUser");
    $rUser = mysqli_fetch_assoc($qUser);
    if ($rUser) {
        $nis = $rUser['nis'];
    }
}

if (empty($nis)) {
    die('NIS Siswa tidak ditemukan.');
}

$reportData = $studentModel->getReportSts($nis);
$student    = $reportData['student'];
$grades     = $reportData['grades'];
$setting    = $settingModel->get();

if (!$student) {
    die('Data siswa tidak ditemukan.');
}

// Ambil nama & NIP wali kelas
$idKelas = (int)$student['id_kelas'];
$qWali = mysqli_query($conn, "
    SELECT g.nama_guru, g.id_guru
    FROM tb_kelas k
    LEFT JOIN tb_guru g ON k.id_guru_walikelas = g.id_guru
    WHERE k.id_kelas = $idKelas
");
$wali = mysqli_fetch_assoc($qWali);
$namaWali = $wali['nama_guru'] ?? '-';
$nipWali  = $wali['id_guru'] ?? '-';

// Format kelas sesuai template PDF (contoh "X 1" bukan "X-1")
$kelasFormatted = str_replace('-', ' ', $student['nama_kelas']);

// Fase Kurikulum Merdeka (Kelas 10 = E, Kelas 11 & 12 = F)
$fase = ($student['tingkat'] === '10' || str_starts_with($student['nama_kelas'], 'X-')) ? 'E' : 'F';

// Deteksi Semester Ganjil / Genap
$semRaw = trim((string)($setting['semester'] ?? '1'));
$semGanjilGenap = ($semRaw === '2' || stripos($semRaw, 'genap') !== false) ? 'GENAP' : 'GANJIL';
$semGanjilGenapCap = ucfirst(strtolower($semGanjilGenap));

// Pisahkan Kelompok Umum dan Kelompok Pilihan
$mapelUmum = [];
$mapelPilihan = [];
foreach ($grades as $g) {
    $kat = strtolower(trim((string)$g['kategori']));
    if ($kat === 'pilihan') {
        $mapelPilihan[] = $g;
    } else {
        $mapelUmum[] = $g;
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rapor STS - <?= htmlspecialchars($student['nama']) ?> (<?= htmlspecialchars($student['nis']) ?>)</title>
    <link href="vendor/bootstrap-5.3.8/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        * {
            box-sizing: border-box;
        }
        body {
            background-color: #f0f2f5;
            color: #000;
            font-family: Arial, Helvetica, sans-serif;
            margin: 0;
            padding: 0;
        }
        .report-page {
            width: 210mm;
            min-height: 297mm;
            padding: 16mm 20mm 14mm 20mm;
            margin: 20px auto;
            background: #ffffff;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            position: relative;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }
        .content-area {
            flex: 1;
        }
        .meta-table {
            width: 100%;
            font-size: 13px;
            line-height: 1.45;
            border-collapse: collapse;
        }
        .meta-table td {
            padding: 2px 0;
            vertical-align: top;
        }
        .meta-divider {
            border: none;
            border-top: 1.5px solid #000;
            margin: 10px 0 14px 0;
            opacity: 1;
        }
        .report-title {
            text-align: center;
            font-weight: bold;
            font-size: 15px;
            letter-spacing: 0.5px;
            margin-bottom: 12px;
            text-transform: uppercase;
        }
        .section-title {
            font-weight: bold;
            font-size: 13px;
            margin-bottom: 6px;
            margin-top: 6px;
        }
        .table-raport-container {
            position: relative;
            margin-bottom: 14px;
        }
        .raport-watermark {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 220px;
            height: 220px;
            object-fit: contain;
            opacity: 0.12;
            pointer-events: none;
            z-index: 0;
        }
        .table-raport {
            width: 100%;
            border-collapse: collapse;
            font-size: 12px;
            position: relative;
            z-index: 1;
            background: transparent !important;
        }
        .table-raport th,
        .table-raport td {
            border: 1px solid #000;
            padding: 4px 6px;
            background: transparent !important;
        }
        .table-raport th {
            text-align: center;
            font-weight: bold;
            vertical-align: middle;
        }
        .table-raport td.text-center {
            text-align: center;
        }
        .table-raport td.mapel-name {
            text-align: left;
            padding-left: 8px;
        }
        .table-group-header .group-header-cell {
            font-weight: bold;
            text-align: left;
            padding-left: 8px;
            background-color: transparent !important;
            border: 1px solid #000;
        }
        .table-ketidakhadiran {
            width: 48%;
            border-collapse: collapse;
            font-size: 12px;
            margin-bottom: 16px;
        }
        .table-ketidakhadiran th,
        .table-ketidakhadiran td {
            border: 1px solid #000;
            padding: 4px 8px;
        }
        .table-ketidakhadiran th {
            text-align: center;
            font-weight: bold;
            background-color: transparent !important;
        }
        .signature-table {
            width: 100%;
            font-size: 12px;
            margin-top: 8px;
            border-collapse: collapse;
        }
        .signature-table td {
            text-align: center;
            vertical-align: top;
            padding: 0 4px;
        }
        .signature-table td.col-ortu {
            width: 28%;
        }
        .signature-table td.col-kepsek {
            width: 40%;
            white-space: nowrap;
        }
        .signature-table td.col-wali {
            width: 32%;
            white-space: nowrap;
        }
        .signature-space {
            height: 65px;
        }
        .running-footer {
            font-family: 'Courier New', Courier, monospace;
            font-size: 11px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding-top: 10px;
            margin-top: 14px;
            border-top: 1px solid #eee;
        }
        @media print {
            body {
                background: #ffffff;
            }
            .report-page {
                box-shadow: none;
                margin: 0;
                padding: 12mm 16mm 10mm 16mm;
                width: 100%;
                min-height: auto;
                height: 100%;
            }
            .no-print {
                display: none !important;
            }
            .running-footer {
                border-top: none;
            }
            @page {
                size: A4 portrait;
                margin: 10mm 12mm;
            }
        }
    </style>
</head>
<body>

<div class="container text-center my-3 no-print">
    <button onclick="window.print();" class="btn btn-primary px-4 me-2">
        <i class="fa fa-print me-1"></i> Cetak Dokumen Rapor
    </button>
    <button onclick="window.close();" class="btn btn-secondary px-3">
        Tutup
    </button>
</div>

<div class="report-page">
    <div class="content-area">
        <!-- Metadata Siswa & Sekolah -->
        <table class="meta-table">
            <tr>
                <td style="width: 13%;">Nama Murid</td>
                <td style="width: 2%;">:</td>
                <td style="width: 47%; font-weight: bold;"><?= htmlspecialchars($student['nama']) ?></td>
                <td style="width: 15%;">Kelas</td>
                <td style="width: 2%;">:</td>
                <td style="width: 21%;"><?= htmlspecialchars($kelasFormatted) ?></td>
            </tr>
            <tr>
                <td>NIS/NISN</td>
                <td>:</td>
                <td><?= htmlspecialchars($student['nis']) ?> / <?= htmlspecialchars($student['nisn'] ?? '-') ?></td>
                <td>Fase</td>
                <td>:</td>
                <td><?= htmlspecialchars($fase) ?></td>
            </tr>
            <tr>
                <td>Sekolah</td>
                <td>:</td>
                <td><?= htmlspecialchars($setting['nama_sekolah']) ?></td>
                <td>Semester</td>
                <td>:</td>
                <td><?= htmlspecialchars($setting['semester']) ?></td>
            </tr>
            <tr>
                <td>Alamat</td>
                <td>:</td>
                <td><?= htmlspecialchars($setting['alamat_sekolah']) ?></td>
                <td>Tahun Ajaran</td>
                <td>:</td>
                <td><?= htmlspecialchars($setting['tahun_ajaran']) ?></td>
            </tr>
        </table>

        <div class="meta-divider"></div>

        <!-- Judul Laporan -->
        <div class="report-title">LAPORAN HASIL BELAJAR TENGAH SEMESTER <?= $semGanjilGenap ?></div>

        <!-- A. HASIL BELAJAR -->
        <div class="section-title">A. HASIL BELAJAR</div>

        <div class="table-raport-container">
            <?php if (!empty($setting['logo_sekolah']) && file_exists(__DIR__ . '/' . $setting['logo_sekolah'])) { ?>
                <img src="<?= htmlspecialchars($setting['logo_sekolah']) ?>" class="raport-watermark" alt="Watermark">
            <?php } ?>
            <table class="table-raport">
                <thead>
                    <tr>
                        <th rowspan="2" style="width: 5%;">No</th>
                        <th rowspan="2" style="width: 41%;">Mata Pelajaran</th>
                        <th colspan="4" style="width: 32%;">Sumatif</th>
                        <th rowspan="2" style="width: 22%;">Sumatif Tengah<br>Semester <?= $semGanjilGenapCap ?></th>
                    </tr>
                    <tr>
                        <th style="width: 8%;">01</th>
                        <th style="width: 8%;">02</th>
                        <th style="width: 8%;">03</th>
                        <th style="width: 8%;">04</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($mapelUmum)) { ?>
                        <tr class="table-group-header">
                            <td colspan="7" class="group-header-cell">Kelompok Umum</td>
                        </tr>
                        <?php
                        $noU = 1;
                        foreach ($mapelUmum as $g) {
                            $s1  = ($g['sumatif_1'] !== null && $g['sumatif_1'] !== '') ? (float)$g['sumatif_1'] : '';
                            $s2  = ($g['sumatif_2'] !== null && $g['sumatif_2'] !== '') ? (float)$g['sumatif_2'] : '';
                            $s3  = ($g['sumatif_3'] !== null && $g['sumatif_3'] !== '') ? (float)$g['sumatif_3'] : '';
                            $s4  = (isset($g['sumatif_4']) && $g['sumatif_4'] !== null && $g['sumatif_4'] !== '') ? (float)$g['sumatif_4'] : '';
                            $ats = ($g['nilai_sts'] !== null && $g['nilai_sts'] !== '') ? (float)$g['nilai_sts'] : '';
                        ?>
                        <tr>
                            <td class="text-center"><?= $noU++ ?></td>
                            <td class="mapel-name"><?= htmlspecialchars($g['nama_mapel']) ?></td>
                            <td class="text-center"><?= $s1 ?></td>
                            <td class="text-center"><?= $s2 ?></td>
                            <td class="text-center"><?= $s3 ?></td>
                            <td class="text-center"><?= $s4 ?></td>
                            <td class="text-center"><?= $ats ?></td>
                        </tr>
                        <?php } ?>
                    <?php } ?>

                    <?php if (!empty($mapelPilihan)) { ?>
                        <tr class="table-group-header">
                            <td colspan="7" class="group-header-cell">Kelompok Pilihan</td>
                        </tr>
                        <?php
                        $noP = 1;
                        foreach ($mapelPilihan as $g) {
                            $s1  = ($g['sumatif_1'] !== null && $g['sumatif_1'] !== '') ? (float)$g['sumatif_1'] : '';
                            $s2  = ($g['sumatif_2'] !== null && $g['sumatif_2'] !== '') ? (float)$g['sumatif_2'] : '';
                            $s3  = ($g['sumatif_3'] !== null && $g['sumatif_3'] !== '') ? (float)$g['sumatif_3'] : '';
                            $s4  = (isset($g['sumatif_4']) && $g['sumatif_4'] !== null && $g['sumatif_4'] !== '') ? (float)$g['sumatif_4'] : '';
                            $ats = ($g['nilai_sts'] !== null && $g['nilai_sts'] !== '') ? (float)$g['nilai_sts'] : '';
                        ?>
                        <tr>
                            <td class="text-center"><?= $noP++ ?></td>
                            <td class="mapel-name"><?= htmlspecialchars($g['nama_mapel']) ?></td>
                            <td class="text-center"><?= $s1 ?></td>
                            <td class="text-center"><?= $s2 ?></td>
                            <td class="text-center"><?= $s3 ?></td>
                            <td class="text-center"><?= $s4 ?></td>
                            <td class="text-center"><?= $ats ?></td>
                        </tr>
                        <?php } ?>
                    <?php } ?>
                </tbody>
            </table>
        </div>

        <!-- B. KETIDAK HADIRAN -->
        <div class="section-title">B. KETIDAK HADIRAN</div>
        <?php
        $presensi = $reportData['presensi'] ?? ['sakit' => 0, 'izin' => 0, 'alpa' => 0];
        $sakitVal = (int)($presensi['sakit'] ?? 0);
        $izinVal  = (int)($presensi['izin'] ?? 0);
        $alpaVal  = (int)($presensi['alpa'] ?? 0);
        ?>
        <table class="table-ketidakhadiran">
            <thead>
                <tr>
                    <th style="width: 10%;">No</th>
                    <th style="width: 50%;">Ketidak Hadiran</th>
                    <th style="width: 40%;">Jumlah</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="text-center">1</td>
                    <td class="ps-2">Sakit</td>
                    <td class="text-center"><?= $sakitVal ?> Hari</td>
                </tr>
                <tr>
                    <td class="text-center">2</td>
                    <td class="ps-2">Izin</td>
                    <td class="text-center"><?= $izinVal ?> Hari</td>
                </tr>
                <tr>
                    <td class="text-center">3</td>
                    <td class="ps-2">Tanpa Keterangan</td>
                    <td class="text-center"><?= $alpaVal ?> Hari</td>
                </tr>
            </tbody>
        </table>

        <!-- Tanda Tangan -->
        <table class="signature-table">
            <tr>
                <td class="col-ortu">Orang Tua Murid</td>
                <td class="col-kepsek">Kepala Sekolah</td>
                <td class="col-wali"><?= htmlspecialchars($setting['tempat_rapor']) ?>, <?= htmlspecialchars($setting['tanggal_rapor']) ?><br>Wali Kelas</td>
            </tr>
            <tr>
                <td class="signature-space"></td>
                <td class="signature-space"></td>
                <td class="signature-space"></td>
            </tr>
            <tr>
                <td class="col-ortu">................................................</td>
                <td class="col-kepsek">
                    <span style="white-space: nowrap; display: inline-block;"><u><strong><?= htmlspecialchars($setting['nama_kepala_sekolah']) ?></strong></u></span><br>
                    NIP <?= htmlspecialchars($setting['nip_kepala_sekolah']) ?>
                </td>
                <td class="col-wali">
                    <span style="white-space: nowrap; display: inline-block;"><u><strong><?= htmlspecialchars($namaWali) ?></strong></u></span><br>
                    NIP <?= htmlspecialchars($nipWali) ?>
                </td>
            </tr>
        </table>
    </div>

    <!-- Running Footer -->
    <div class="running-footer">
        <div><?= htmlspecialchars($kelasFormatted) ?> | <?= htmlspecialchars($student['nama']) ?> | <?= htmlspecialchars($student['nis']) ?></div>
        <div>Halaman : 1</div>
    </div>
</div>

</body>
</html>
