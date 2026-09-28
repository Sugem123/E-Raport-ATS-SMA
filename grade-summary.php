<?php
include_once "config/Database.php";
require_once "models/Student.php";
require_once "models/Bobot.php";

$db = new Database();
$conn = $db->connect();

$studentModel = new Student($conn);
$bobotModel   = new Bobot($conn);

$nis = $_SESSION['id'] ?? '';
$reportData = $studentModel->getReportSts($nis);

$student  = $reportData['student'];
$grades   = $reportData['grades'];
$presensi = $reportData['presensi'];
$bobot    = $bobotModel->get();

$mapelUmum = [];
$mapelPilihan = [];
foreach ($grades as $g) {
    if (($g['kategori'] ?? '') === 'Pilihan') {
        $mapelPilihan[] = $g;
    } else {
        $mapelUmum[] = $g;
    }
}
$hasPeminatan = !empty($mapelPilihan);
?>

<div class="col-lg-9 mt-2">
    <div class="card shadow-sm border-0">
        <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
            <h5 class="mb-0 fw-bold text-primary"><i class="fa-solid fa-file-invoice me-2"></i>Rapor Sumatif Tengah Semester (STS)</h5>
            <?php if ($student) { ?>
                <a href="student-report.php?nis=<?= urlencode($student['nis']) ?>" target="_blank" class="btn btn-outline-primary btn-sm">
                    <i class="fa-solid fa-print me-1"></i> Cetak Rapor STS
                </a>
            <?php } ?>
        </div>
        <div class="card-body">
            <?php if (!$student) { ?>
                <div class="alert alert-warning">Data siswa tidak ditemukan.</div>
            <?php } else { ?>

                <!-- Identitas Siswa -->
                <div class="row mb-4 bg-light p-3 rounded mx-1">
                    <div class="col-md-4">
                        <small class="text-muted d-block text-uppercase">Nomor Induk Siswa (NIS)</small>
                        <span class="fs-6 fw-bold text-dark font-monospace"><?= htmlspecialchars($student['nis']) ?></span>
                    </div>
                    <div class="col-md-4">
                        <small class="text-muted d-block text-uppercase">Nama Lengkap</small>
                        <span class="fs-6 fw-bold text-dark"><?= htmlspecialchars($student['nama']) ?></span>
                    </div>
                    <div class="col-md-4">
                        <small class="text-muted d-block text-uppercase">Kelas / Fase</small>
                        <span class="fs-6 fw-bold text-primary"><?= htmlspecialchars($student['nama_kelas']) ?> (Fase <?= $student['tingkat'] == '10' ? 'E' : 'F' ?>)</span>
                    </div>
                </div>

                <!-- Info Bobot -->
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <h6 class="fw-bold mb-0">Capaian Nilai Tengah Semester</h6>
                    <small class="text-muted">
                        Standar Ketuntasan (KKM): <strong><?= (float)$bobot['kkm'] ?></strong>
                    </small>
                </div>

                <!-- Tabel Nilai -->
                <div class="table-responsive mb-4">
                    <table class="table table-hover align-middle border">
                        <thead class="table-light text-center">
                            <tr>
                                <th style="width: 5%">No</th>
                                <th class="text-start">Mata Pelajaran</th>
                                <th class="text-start">Guru Pengampu</th>
                                <th>Sumatif 1</th>
                                <th>Sumatif 2</th>
                                <th>Sumatif 3</th>
                                <th>Rata-rata</th>
                                <th>Nilai STS</th>
                                <th>Nilai Akhir</th>
                                <th>Keterangan</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            if (empty($grades)) {
                                echo '<tr><td colspan="10" class="text-center py-4 text-muted">Belum ada mata pelajaran terdaftar untuk kelas Anda.</td></tr>';
                            } else {
                                $no = 1;
                                $renderSummaryRow = function($g, &$no) {
                                    $hasGrade = ($g['nilai_akhir'] !== null);
                                    $isPass = ($g['status_kelulusan'] === 'Tercapai');
                                    ?>
                                    <tr class="text-center">
                                        <td><?= $no++ ?></td>
                                        <td class="text-start fw-semibold text-dark"><?= htmlspecialchars($g['nama_mapel']) ?></td>
                                        <td class="text-start text-muted small"><?= htmlspecialchars($g['nama_guru']) ?></td>
                                        <td><?= $g['sumatif_1'] !== null ? (float)$g['sumatif_1'] : '-' ?></td>
                                        <td><?= $g['sumatif_2'] !== null ? (float)$g['sumatif_2'] : '-' ?></td>
                                        <td><?= $g['sumatif_3'] !== null ? (float)$g['sumatif_3'] : '-' ?></td>
                                        <td class="text-secondary fw-semibold"><?= $g['rata_sumatif'] !== null ? (float)$g['rata_sumatif'] : '-' ?></td>
                                        <td class="fw-semibold"><?= $g['nilai_sts'] !== null ? (float)$g['nilai_sts'] : '-' ?></td>
                                        <td class="fw-bold fs-6 text-primary"><?= $g['nilai_akhir'] !== null ? (float)$g['nilai_akhir'] : '-' ?></td>
                                        <td>
                                            <?php if ($hasGrade) { ?>
                                                <span class="badge <?= $isPass ? 'bg-success' : 'bg-danger' ?>">
                                                    <?= htmlspecialchars($g['status_kelulusan']) ?>
                                                </span>
                                            <?php } else { ?>
                                                <span class="badge bg-light text-muted border">Belum Terisi</span>
                                            <?php } ?>
                                        </td>
                                    </tr>
                                    <?php
                                };

                                if ($hasPeminatan) {
                                    echo '<tr class="table-light fw-bold text-start"><td colspan="10">A. KELOMPOK MATA PELAJARAN UMUM (WAJIB)</td></tr>';
                                    foreach ($mapelUmum as $g) {
                                        $renderSummaryRow($g, $no);
                                    }
                                    echo '<tr class="table-light fw-bold text-start"><td colspan="10">B. KELOMPOK MATA PELAJARAN PILIHAN / PEMINATAN</td></tr>';
                                    foreach ($mapelPilihan as $g) {
                                        $renderSummaryRow($g, $no);
                                    }
                                } else {
                                    foreach ($grades as $g) {
                                        $renderSummaryRow($g, $no);
                                    }
                                }
                            }
                            ?>
                        </tbody>
                    </table>
                </div>

                <!-- Rekap Ketidakhadiran -->
                <h6 class="fw-bold mb-3">Rekap Ketidakhadiran</h6>
                <div class="row col-md-8">
                    <div class="col-4">
                        <div class="card bg-light border-0 text-center p-3">
                            <span class="text-muted small">Sakit (S)</span>
                            <span class="fs-4 fw-bold text-dark"><?= (int)$presensi['sakit'] ?> <small class="fs-6 fw-normal text-muted">hari</small></span>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="card bg-light border-0 text-center p-3">
                            <span class="text-muted small">Izin (I)</span>
                            <span class="fs-4 fw-bold text-dark"><?= (int)$presensi['izin'] ?> <small class="fs-6 fw-normal text-muted">hari</small></span>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="card bg-light border-0 text-center p-3">
                            <span class="text-muted small">Tanpa Keterangan (A)</span>
                            <span class="fs-4 fw-bold text-danger"><?= (int)$presensi['alpa'] ?> <small class="fs-6 fw-normal text-muted">hari</small></span>
                        </div>
                    </div>
                </div>

            <?php } ?>
        </div>
    </div>
</div>
