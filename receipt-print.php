<?php
session_start();

if (empty($_SESSION['username']) || !in_array($_SESSION['role'] ?? '', ['admin', 'walikelas'])) {
    die('Akses ditolak. Silakan login sebagai Admin atau Wali Kelas terlebih dahulu.');
}

include_once "config/Database.php";
require_once "models/Kelas.php";
require_once "models/Setting.php";

$db = new Database();
$conn = $db->connect();

$kelasModel   = new Kelas($conn);
$settingModel = new Setting($conn);
$setting      = $settingModel->get();

$idKelas = (int)($_GET['kelas'] ?? 0);

if ($idKelas <= 0 && ($_SESSION['role'] ?? '') === 'walikelas') {
    $myClass = $kelasModel->getByWaliKelas($_SESSION['id'] ?? '');
    if ($myClass) {
        $idKelas = (int)$myClass['id_kelas'];
    }
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
        die('Anda hanya dapat mencetak tanda terima untuk kelas perwalian Anda sendiri.');
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

// Info wali kelas & semester
$namaWali = $kelas['nama_walikelas'] ?? '-';
$nipWali  = $kelas['id_guru_walikelas'] ?? '-';
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
    <title>Daftar Penerimaan Rapor STS - Kelas <?= htmlspecialchars($kelas['nama_kelas']) ?></title>
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

        /* Halaman Kertas Cetak A4 Portrait */
        .receipt-page {
            width: 210mm;
            min-height: 297mm;
            padding: 14mm 18mm 14mm 18mm;
            margin: 24px auto;
            background: #ffffff;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.35);
            position: relative;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }
        .content-area {
            flex: 1;
        }

        /* Banner Kop Surat Resmi / Placeholder */
        .kop-banner {
            width: 100%;
            margin-bottom: 8px;
            text-align: center;
        }
        .kop-banner img {
            width: 100%;
            max-height: 125px;
            object-fit: contain;
        }
        .kop-placeholder-table {
            width: 100%;
            border-collapse: collapse;
        }
        .kop-placeholder-table td {
            vertical-align: middle;
            padding: 0;
        }
        .kop-divider {
            border: none;
            border-top: 2px solid #000;
            border-bottom: 1px solid #000;
            height: 4px;
            margin: 6px 0 10px 0;
            opacity: 1;
        }

        /* Judul Dokumen */
        .doc-title {
            text-align: center;
            font-weight: 800;
            font-size: 14px;
            letter-spacing: 0.5px;
            text-transform: uppercase;
            margin-bottom: 4px;
            color: #0b2246;
        }
        .doc-subtitle {
            text-align: center;
            font-size: 12px;
            font-weight: 600;
            margin-bottom: 12px;
            text-transform: uppercase;
        }

        /* Metadata Rombel */
        .meta-grid {
            width: 100%;
            font-size: 11.5px;
            margin-bottom: 10px;
            border-collapse: collapse;
        }
        .meta-grid td {
            padding: 2px 0;
            vertical-align: top;
        }

        /* Tabel Daftar Penerima */
        .table-receipt {
            width: 100%;
            border-collapse: collapse;
            font-size: 11px;
            margin-bottom: 14px;
        }
        .table-receipt th,
        .table-receipt td {
            border: 1px solid #000;
            padding: 4px 6px;
        }
        .table-receipt thead th {
            text-align: center;
            font-weight: bold;
            background-color: #f1f5f9;
            vertical-align: middle;
            height: 28px;
        }
        .table-receipt td.ttd-cell {
            padding: 2px 8px;
            height: 24px;
            font-size: 10px;
            color: #64748b;
        }

        /* Tanda Tangan */
        .signature-table {
            width: 100%;
            font-size: 11px;
            margin-top: 8px;
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
            .receipt-page {
                box-shadow: none;
                margin: 0;
                padding: 10mm 15mm 8mm 15mm;
                width: 100%;
                min-height: auto;
            }
            @page {
                size: A4 portrait;
                margin: 8mm 10mm;
            }
        }
    </style>
</head>
<body>

<!-- Floating Control Toolbar (Hidden on Print) -->
<div class="print-toolbar no-print">
    <div>
        <div class="fw-bold fs-6">
            <i class="fa-solid fa-file-signature text-warning me-2"></i>
            Daftar Penerimaan e-Raport STS &bull; Kelas <?= htmlspecialchars($kelas['nama_kelas']) ?>
        </div>
        <div class="small opacity-75">
            Total <?= $totalSiswa ?> Siswa &bull; Fase <?= htmlspecialchars($fase) ?> &bull; Wali Kelas: <?= htmlspecialchars($namaWali) ?>
        </div>
    </div>
    <div class="d-flex align-items-center gap-2">
        <button onclick="window.print();" class="btn btn-warning fw-bold px-3 shadow-sm">
            <i class="fa-solid fa-print me-1"></i> Cetak Dokumen
        </button>
        <button onclick="window.close();" class="btn btn-outline-light btn-sm">
            Tutup Tab
        </button>
    </div>
</div>

<div class="receipt-page">
    <div class="content-area">
        <!-- KOP SURAT (Dinamis: Banner Gambar jika ada, atau Placeholder Standar) -->
        <div class="kop-banner">
            <?php if (!empty($setting['kop_surat']) && file_exists(__DIR__ . '/' . $setting['kop_surat'])) { ?>
                <img src="<?= htmlspecialchars($setting['kop_surat']) ?>" alt="Kop Surat Resmi">
            <?php } else { ?>
                <table class="kop-placeholder-table">
                    <tr>
                        <td style="width: 75px; text-align: center;">
                            <?php if (!empty($setting['logo_sekolah']) && file_exists(__DIR__ . '/' . $setting['logo_sekolah'])) { ?>
                                <img src="<?= htmlspecialchars($setting['logo_sekolah']) ?>" alt="Logo" style="width: 60px; height: 60px; object-fit: contain;">
                            <?php } else { ?>
                                <i class="fa-solid fa-graduation-cap fa-3x text-primary"></i>
                            <?php } ?>
                        </td>
                        <td style="text-align: center; padding-right: 40px;">
                            <div style="font-size: 11px; font-weight: bold; letter-spacing: 0.5px; text-transform: uppercase;">
                                PEMERINTAH PROVINSI JAWA TIMUR &bull; DINAS PENDIDIKAN
                            </div>
                            <div style="font-size: 16px; font-weight: 800; letter-spacing: -0.01em; color: #0b2246;">
                                <?= htmlspecialchars($setting['nama_sekolah']) ?>
                            </div>
                            <div style="font-size: 10px; color: #334155;">
                                <?= htmlspecialchars($setting['alamat_sekolah']) ?> &bull; NPSN: <?= htmlspecialchars($setting['npsn']) ?> &bull; Akreditasi: <?= htmlspecialchars($setting['akreditasi']) ?>
                            </div>
                        </td>
                    </tr>
                </table>
                <div class="kop-divider"></div>
            <?php } ?>
        </div>

        <!-- Judul Dokumen -->
        <div class="doc-title">
            DAFTAR PENERIMAAN LAPORAN HASIL ASESMEN TENGAH SEMESTER (STS)
        </div>
        <div class="doc-subtitle">
            SEMESTER <?= $semGanjilGenap ?> &bull; TAHUN AJARAN <?= htmlspecialchars($setting['tahun_ajaran']) ?>
        </div>

        <!-- Metadata Rombel -->
        <table class="meta-grid">
            <tr>
                <td style="width: 14%;">Kelas / Fase</td>
                <td style="width: 2%;">:</td>
                <td style="width: 44%; font-weight: bold;">Kelas <?= htmlspecialchars($kelasFormatted) ?> (Fase <?= htmlspecialchars($fase) ?>)</td>
                <td style="width: 15%;">Wali Kelas</td>
                <td style="width: 2%;">:</td>
                <td style="width: 23%; font-weight: bold;"><?= htmlspecialchars($namaWali) ?></td>
            </tr>
            <tr>
                <td>Tanggal Penerimaan</td>
                <td style="width: 2%;">:</td>
                <td><?= htmlspecialchars($setting['tempat_rapor'] ?? 'Nganjuk') ?>, <?= htmlspecialchars($setting['tanggal_rapor'] ?? date('d F Y')) ?></td>
                <td>NIP Wali Kelas</td>
                <td style="width: 2%;">:</td>
                <td><?= htmlspecialchars($nipWali) ?></td>
            </tr>
        </table>

        <!-- Tabel Daftar Penerima & Tanda Tangan -->
        <table class="table-receipt">
            <thead>
                <tr>
                    <th style="width: 5%;">No</th>
                    <th style="width: 13%;">NIS</th>
                    <th style="width: 15%;">NISN</th>
                    <th style="width: 37%;" class="text-start ps-2">Nama Peserta Didik</th>
                    <th colspan="2" style="width: 30%;">Tanda Tangan Penerima (Wali Murid)</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $no = 1;
                foreach ($siswaList as $idx => $s) {
                    $isGanjil = ($no % 2 !== 0);
                ?>
                    <tr>
                        <td class="text-center"><?= $no ?></td>
                        <td class="text-center font-monospace"><?= htmlspecialchars($s['nis']) ?></td>
                        <td class="text-center font-monospace"><?= htmlspecialchars($s['nisn'] ?? '-') ?></td>
                        <td class="fw-semibold ps-2 text-uppercase"><?= htmlspecialchars(mb_strtoupper((string)$s['nama'], 'UTF-8')) ?></td>
                        
                        <!-- Kolom Tanda Tangan Pola Selang-seling Kiri/Kanan -->
                        <?php if ($isGanjil) { ?>
                            <td class="ttd-cell" style="width: 15%;">
                                <span><?= $no ?>.</span>
                            </td>
                            <td class="ttd-cell" style="width: 15%; border-left: none;">
                                &nbsp;
                            </td>
                        <?php } else { ?>
                            <td class="ttd-cell" style="width: 15%;">
                                &nbsp;
                            </td>
                            <td class="ttd-cell" style="width: 15%; border-left: none;">
                                <span><?= $no ?>.</span>
                            </td>
                        <?php } ?>
                    </tr>
                <?php
                    $no++;
                }
                ?>
            </tbody>
        </table>

        <!-- Lembar Tanda Tangan Pengesahan -->
        <table class="signature-table">
            <tr>
                <td>
                    Mengetahui,<br>
                    Kepala Sekolah
                </td>
                <td>
                    <?= htmlspecialchars($setting['tempat_rapor'] ?? 'Nganjuk') ?>, <?= htmlspecialchars($setting['tanggal_rapor'] ?? date('d F Y')) ?><br>
                    Wali Kelas <?= htmlspecialchars($kelasFormatted) ?>
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
                    <span style="white-space: nowrap; display: inline-block;"><u><strong><?= htmlspecialchars($namaWali) ?></strong></u></span><br>
                    NIP. <?= htmlspecialchars($nipWali) ?>
                </td>
            </tr>
        </table>
    </div>
</div>

</body>
</html>
