<?php
session_start();
if (empty($_SESSION['username']) || !in_array($_SESSION['role'] ?? '', ['admin', 'walikelas'])) {
    die('Akses ditolak. Silakan login sebagai Admin atau Wali Kelas.');
}

include_once "config/Database.php";
require_once "models/Kelas.php";
require_once "models/Student.php";
require_once "models/Setting.php";

$db = new Database();
$conn = $db->connect();

$kelasModel   = new Kelas($conn);
$studentModel = new Student($conn);
$settingModel = new Setting($conn);
$setting      = $settingModel->get();

$idKelas = (int)($_GET['kelas'] ?? 0);
if ($idKelas <= 0) {
    die('Parameter ID Kelas tidak valid.');
}

$kelas = $kelasModel->getById($idKelas);
if (!$kelas) {
    die('Data kelas tidak ditemukan.');
}

// Keamanan: Jika wali kelas, pastikan kelas yang dicetak adalah kelas perwaliannya
if (($_SESSION['role'] ?? '') === 'walikelas') {
    $idGuru = $_SESSION['id'] ?? '';
    $myClass = $kelasModel->getByWaliKelas($idGuru);
    if (!$myClass || (int)$myClass['id_kelas'] !== $idKelas) {
        die('Anda hanya dapat mencetak rapor untuk kelas perwalian Anda sendiri.');
    }
}

// Ambil seluruh siswa di kelas ini
$sqlSiswa = "SELECT nis, nama, nisn, id_kelas FROM tb_siswa WHERE id_kelas = ? ORDER BY nama ASC";
$stmtS = mysqli_prepare($conn, $sqlSiswa);
mysqli_stmt_bind_param($stmtS, "i", $idKelas);
mysqli_stmt_execute($stmtS);
$resS = mysqli_stmt_get_result($stmtS);

$siswaList = [];
while ($row = mysqli_fetch_assoc($resS)) {
    $siswaList[] = $row;
}

$totalSiswa = count($siswaList);
if ($totalSiswa === 0) {
    die('Tidak ada data siswa pada kelas ini.');
}

// Info wali kelas
$namaWali = $kelas['nama_walikelas'] ?? '-';
$nipWali  = $kelas['id_guru_walikelas'] ?? '-';
$kelasFormatted = str_replace('-', ' ', $kelas['nama_kelas']);
$fase = ($kelas['tingkat'] === '10' || str_starts_with($kelas['nama_kelas'], 'X-')) ? 'E' : 'F';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak Massal Rapor STS - Kelas <?= htmlspecialchars($kelas['nama_kelas']) ?> (<?= $totalSiswa ?> Siswa)</title>
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

        /* Floating Top Toolbar */
        .print-toolbar {
            position: sticky;
            top: 0;
            z-index: 1000;
            background: #1e293b;
            color: #ffffff;
            padding: 12px 24px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3);
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
        }

        /* Sheet Page for each student */
        .report-page {
            width: 210mm;
            min-height: 297mm;
            padding: 16mm 20mm 14mm 20mm;
            margin: 24px auto;
            background: #ffffff;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.35);
            position: relative;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            page-break-after: always;
            break-after: page;
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
            margin-bottom: 14px;
        }
        .table-raport {
            width: 100%;
            border-collapse: collapse;
            font-size: 12.5px;
            margin-bottom: 14px;
        }
        .table-raport th,
        .table-raport td {
            border: 1px solid #000;
            padding: 5px 6px;
        }
        .table-raport th {
            text-align: center;
            font-weight: bold;
            vertical-align: middle;
            background-color: #ffffff;
        }
        .table-raport td.text-center {
            text-align: center;
        }
        .table-raport td.mapel-name {
            text-align: left;
            padding-left: 8px;
        }
        .table-ketidakhadiran {
            width: 42%;
            border-collapse: collapse;
            font-size: 12px;
            margin-top: 10px;
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
            background-color: #fafafa;
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
                margin: 0;
                padding: 0;
            }
            .no-print {
                display: none !important;
            }
            .report-page {
                box-shadow: none;
                margin: 0;
                padding: 12mm 16mm 10mm 16mm;
                width: 100%;
                min-height: auto;
                page-break-after: always;
                break-after: page;
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

<!-- Floating Control Toolbar (Hidden on Print) -->
<div class="print-toolbar no-print">
    <div>
        <div class="fw-bold fs-6">
            <i class="fa-solid fa-file-pdf text-danger me-2"></i>
            Pratinjau Cetak Massal e-Raport STS &bull; Kelas <?= htmlspecialchars($kelas['nama_kelas']) ?>
        </div>
        <div class="small opacity-75">
            Total <?= $totalSiswa ?> Siswa &bull; Fase <?= htmlspecialchars($fase) ?> &bull; Wali Kelas: <?= htmlspecialchars($namaWali) ?>
        </div>
    </div>
    <div class="d-flex align-items-center gap-2">
        <button onclick="window.print();" class="btn btn-primary fw-bold px-4 shadow">
            <i class="fa-solid fa-print me-1"></i> Cetak / Simpan PDF (<?= $totalSiswa ?> Siswa)
        </button>
        <button onclick="window.close();" class="btn btn-outline-light btn-sm">
            Tutup Tab
        </button>
    </div>
</div>

<!-- Loop Setiap Siswa: 1 Siswa = 1 Halaman A4 -->
<?php
foreach ($siswaList as $s) {
    $reportData = $studentModel->getReportSts($s['nis']);
    $student    = $reportData['student'];
    $grades     = $reportData['grades'];
?>
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
        <div class="report-title">LAPORAN HASIL BELAJAR</div>

        <!-- Tabel Nilai -->
        <table class="table-raport">
            <thead>
                <tr>
                    <th style="width: 5%;">No</th>
                    <th style="width: 37%;">Mata Pelajaran</th>
                    <th style="width: 11%;">Nilai<br>Sumatif 1</th>
                    <th style="width: 11%;">Nilai<br>Sumatif 2</th>
                    <th style="width: 11%;">Nilai<br>Sumatif 3</th>
                    <th style="width: 11%;">Nilai<br>ATS</th>
                    <th style="width: 14%;">Nilai akhir</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if (empty($grades)) {
                    echo '<tr><td colspan="7" class="text-center py-4 text-muted">Belum ada data mata pelajaran untuk kelas ini.</td></tr>';
                } else {
                    $no = 1;
                    foreach ($grades as $g) {
                        $s1  = ($g['sumatif_1'] !== null && $g['sumatif_1'] !== '') ? (float)$g['sumatif_1'] : '';
                        $s2  = ($g['sumatif_2'] !== null && $g['sumatif_2'] !== '') ? (float)$g['sumatif_2'] : '';
                        $s3  = ($g['sumatif_3'] !== null && $g['sumatif_3'] !== '') ? (float)$g['sumatif_3'] : '';
                        $ats = ($g['nilai_sts'] !== null && $g['nilai_sts'] !== '') ? (float)$g['nilai_sts'] : '';
                        $na  = ($g['nilai_akhir'] !== null && $g['nilai_akhir'] !== '') ? (float)$g['nilai_akhir'] : '';
                ?>
                    <tr>
                        <td class="text-center"><?= $no++ ?></td>
                        <td class="mapel-name"><?= htmlspecialchars($g['nama_mapel']) ?></td>
                        <td class="text-center"><?= $s1 ?></td>
                        <td class="text-center"><?= $s2 ?></td>
                        <td class="text-center"><?= $s3 ?></td>
                        <td class="text-center"><?= $ats ?></td>
                        <td class="text-center"><?= $na ?></td>
                    </tr>
                <?php
                    }
                }
                ?>
            </tbody>
        </table>

        <!-- Keterangan Ketidakhadiran -->
        <?php
        $presensi = $reportData['presensi'] ?? ['sakit' => 0, 'izin' => 0, 'alpa' => 0];
        $sakitTxt = ((int)($presensi['sakit'] ?? 0) > 0) ? (int)$presensi['sakit'] . ' Hari' : '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Hari';
        $izinTxt  = ((int)($presensi['izin'] ?? 0) > 0) ? (int)$presensi['izin'] . ' Hari' : '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Hari';
        $alpaTxt  = ((int)($presensi['alpa'] ?? 0) > 0) ? (int)$presensi['alpa'] . ' Hari' : '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Hari';
        ?>
        <table class="table-ketidakhadiran">
            <thead>
                <tr>
                    <th colspan="3">Ketidakhadiran</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td style="width: 52%;">Sakit</td>
                    <td style="width: 6%; text-align: center;">:</td>
                    <td style="width: 42%;"><?= $sakitTxt ?></td>
                </tr>
                <tr>
                    <td>Izin</td>
                    <td style="text-align: center;">:</td>
                    <td><?= $izinTxt ?></td>
                </tr>
                <tr>
                    <td>Tanpa Keterangan</td>
                    <td style="text-align: center;">:</td>
                    <td><?= $alpaTxt ?></td>
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
<?php } ?>

</body>
</html>
