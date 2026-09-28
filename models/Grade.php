<?php

require_once __DIR__ . '/../config/ExcelHelper.php';

class Grade
{
    private mysqli $conn;

    public function __construct(mysqli $conn)
    {
        $this->conn = $conn;
    }

    public function saveGrade(
        string $nis,
        int $idPengampu,
        float $s1,
        float $s2,
        float $s3,
        float $sts
    ): array {
        foreach (['Sumatif 1' => $s1, 'Sumatif 2' => $s2, 'Sumatif 3' => $s3, 'Nilai STS' => $sts] as $label => $val) {
            if ($val < 0 || $val > 100) {
                return ['success' => false, 'message' => "$label harus berada di rentang 0-100."];
            }
        }

        $rataSumatif = round(($s1 + $s2 + $s3) / 3, 2);

        $qBobot = mysqli_query($this->conn, "SELECT bobot_sumatif, bobot_sts, kkm FROM tb_pengaturan_bobot WHERE id_pengaturan = 1");
        $bobot = mysqli_fetch_assoc($qBobot);
        $bSumatif = (float)($bobot['bobot_sumatif'] ?? 60);
        $bSts = (float)($bobot['bobot_sts'] ?? 40);
        $kkm = (float)($bobot['kkm'] ?? 75);

        $totalBobot = $bSumatif + $bSts;
        if ($totalBobot <= 0) {
            $totalBobot = 100;
        }

        $nilaiAkhir = round((($rataSumatif * $bSumatif) + ($sts * $bSts)) / $totalBobot, 2);
        $status = ($nilaiAkhir >= $kkm) ? 'Tercapai' : 'Belum Tercapai';

        $stmt = mysqli_prepare(
            $this->conn,
            "INSERT INTO tb_nilai_sts (nis, id_pengampu, sumatif_1, sumatif_2, sumatif_3, rata_sumatif, nilai_sts, nilai_akhir, status_kelulusan)
             VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)
             ON DUPLICATE KEY UPDATE
                sumatif_1 = VALUES(sumatif_1),
                sumatif_2 = VALUES(sumatif_2),
                sumatif_3 = VALUES(sumatif_3),
                rata_sumatif = VALUES(rata_sumatif),
                nilai_sts = VALUES(nilai_sts),
                nilai_akhir = VALUES(nilai_akhir),
                status_kelulusan = VALUES(status_kelulusan)"
        );
        mysqli_stmt_bind_param(
            $stmt,
            "sidddddds",
            $nis,
            $idPengampu,
            $s1,
            $s2,
            $s3,
            $rataSumatif,
            $sts,
            $nilaiAkhir,
            $status
        );

        if (mysqli_stmt_execute($stmt)) {
            return [
                'success' => true,
                'message' => 'Nilai STS berhasil disimpan.',
                'rata_sumatif' => $rataSumatif,
                'nilai_akhir' => $nilaiAkhir,
                'status' => $status
            ];
        }

        return ['success' => false, 'message' => 'Gagal menyimpan nilai STS.'];
    }

    public function importExcel(string $filePath, string $originalFileName, int $idPengampu): array
    {
        $rows = ExcelHelper::parse($filePath, $originalFileName);
        if (empty($rows)) {
            return ['success' => false, 'message' => 'File Excel kosong atau format tidak sesuai.'];
        }

        $updated = 0;
        $failed = 0;

        foreach ($rows as $row) {
            $nis = $row['nis'] ?? '';
            if (empty($nis)) {
                continue;
            }

            $s1  = isset($row['sumatif_1']) && $row['sumatif_1'] !== '' ? (float)$row['sumatif_1'] : 0;
            $s2  = isset($row['sumatif_2']) && $row['sumatif_2'] !== '' ? (float)$row['sumatif_2'] : 0;
            $s3  = isset($row['sumatif_3']) && $row['sumatif_3'] !== '' ? (float)$row['sumatif_3'] : 0;
            $sts = isset($row['nilai_sts']) && $row['nilai_sts'] !== '' ? (float)$row['nilai_sts'] : 0;

            $res = $this->saveGrade($nis, $idPengampu, $s1, $s2, $s3, $sts);
            if ($res['success']) {
                $updated++;
            } else {
                $failed++;
            }
        }

        return [
            'success' => true,
            'message' => "Upload nilai berhasil diproses. $updated data nilai siswa berhasil disimpan/diperbarui" . ($failed > 0 ? ", $failed gagal." : ".")
        ];
    }

    public function importExcelPresensi(string $filePath, string $originalFileName): array
    {
        $rows = ExcelHelper::parse($filePath, $originalFileName);
        if (empty($rows)) {
            return ['success' => false, 'message' => 'File Excel kosong atau format tidak sesuai.'];
        }

        $updated = 0;
        $failed = 0;

        foreach ($rows as $row) {
            $nis = trim((string)($row['nis'] ?? ''));
            if (empty($nis)) {
                continue;
            }

            $sakit = isset($row['sakit']) && $row['sakit'] !== '' ? max(0, (int)$row['sakit']) : 0;
            $izin  = isset($row['izin']) && $row['izin'] !== '' ? max(0, (int)$row['izin']) : 0;
            $alpa  = isset($row['alpa']) && $row['alpa'] !== '' ? max(0, (int)$row['alpa']) : 0;

            $res = $this->savePresensi($nis, $sakit, $izin, $alpa);
            if ($res['success']) {
                $updated++;
            } else {
                $failed++;
            }
        }

        return [
            'success' => true,
            'message' => "Upload presensi berhasil diproses. Data ketidakhadiran $updated siswa berhasil disimpan" . ($failed > 0 ? ", $failed gagal." : ".")
        ];
    }

    public function savePresensi(string $nis, int $sakit, int $izin, int $alpa): array
    {
        $sakit = max(0, $sakit);
        $izin  = max(0, $izin);
        $alpa  = max(0, $alpa);

        $stmt = mysqli_prepare(
            $this->conn,
            "INSERT INTO tb_presensi_sts (nis, sakit, izin, alpa)
             VALUES (?, ?, ?, ?)
             ON DUPLICATE KEY UPDATE
                sakit = VALUES(sakit),
                izin = VALUES(izin),
                alpa = VALUES(alpa)"
        );
        mysqli_stmt_bind_param($stmt, "siii", $nis, $sakit, $izin, $alpa);

        if (mysqli_stmt_execute($stmt)) {
            return ['success' => true, 'message' => 'Data ketidakhadiran berhasil disimpan.'];
        }
        return ['success' => false, 'message' => 'Gagal menyimpan ketidakhadiran.'];
    }

    public function deleteGrade(int $idNilai): array
    {
        $stmt = mysqli_prepare($this->conn, "DELETE FROM tb_nilai_sts WHERE id_nilai = ?");
        mysqli_stmt_bind_param($stmt, "i", $idNilai);

        if (mysqli_stmt_execute($stmt)) {
            return ['success' => true, 'message' => 'Nilai berhasil dihapus.'];
        }
        return ['success' => false, 'message' => 'Gagal menghapus nilai.'];
    }
}
