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
        ?float $s1 = null,
        ?float $s2 = null,
        ?float $s3 = null,
        ?float $s4 = null,
        ?float $sts = null
    ): array {
        foreach (['Sumatif 1' => $s1, 'Sumatif 2' => $s2, 'Sumatif 3' => $s3, 'Sumatif 4' => $s4, 'Nilai ATS' => $sts] as $label => $val) {
            if ($val !== null && ($val < 0 || $val > 100)) {
                return ['success' => false, 'message' => "$label harus berada di rentang 0-100."];
            }
        }

        // Pengolahan nilai dinonaktifkan: nilai disimpan dan ditampilkan murni apa adanya
        $stmt = mysqli_prepare(
            $this->conn,
            "INSERT INTO tb_nilai_sts (nis, id_pengampu, sumatif_1, sumatif_2, sumatif_3, sumatif_4, nilai_sts)
             VALUES (?, ?, ?, ?, ?, ?, ?)
             ON DUPLICATE KEY UPDATE
                sumatif_1 = VALUES(sumatif_1),
                sumatif_2 = VALUES(sumatif_2),
                sumatif_3 = VALUES(sumatif_3),
                sumatif_4 = VALUES(sumatif_4),
                nilai_sts = VALUES(nilai_sts)"
        );
        mysqli_stmt_bind_param(
            $stmt,
            "siddddd",
            $nis,
            $idPengampu,
            $s1,
            $s2,
            $s3,
            $s4,
            $sts
        );

        if (mysqli_stmt_execute($stmt)) {
            return [
                'success' => true,
                'message' => 'Nilai berhasil disimpan.'
            ];
        }

        return ['success' => false, 'message' => 'Gagal menyimpan nilai: ' . mysqli_error($this->conn)];
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

            $s1 = (isset($row['sumatif_1']) && $row['sumatif_1'] !== '') ? (float)$row['sumatif_1'] : (isset($row['01']) && $row['01'] !== '' ? (float)$row['01'] : null);
            $s2 = (isset($row['sumatif_2']) && $row['sumatif_2'] !== '') ? (float)$row['sumatif_2'] : (isset($row['02']) && $row['02'] !== '' ? (float)$row['02'] : null);
            $s3 = (isset($row['sumatif_3']) && $row['sumatif_3'] !== '') ? (float)$row['sumatif_3'] : (isset($row['03']) && $row['03'] !== '' ? (float)$row['03'] : null);
            $s4 = (isset($row['sumatif_4']) && $row['sumatif_4'] !== '') ? (float)$row['sumatif_4'] : (isset($row['04']) && $row['04'] !== '' ? (float)$row['04'] : null);

            $sts = null;
            if (isset($row['nilai_ats']) && $row['nilai_ats'] !== '') {
                $sts = (float)$row['nilai_ats'];
            } elseif (isset($row['nilai_sts']) && $row['nilai_sts'] !== '') {
                $sts = (float)$row['nilai_sts'];
            } elseif (isset($row['ats']) && $row['ats'] !== '') {
                $sts = (float)$row['ats'];
            } elseif (isset($row['sts']) && $row['sts'] !== '') {
                $sts = (float)$row['sts'];
            }

            $res = $this->saveGrade($nis, $idPengampu, $s1, $s2, $s3, $s4, $sts);
            if ($res['success']) {
                $updated++;
            } else {
                $failed++;
            }
        }

        return [
            'success' => true,
            'message' => "Upload nilai berhasil diproses. $updated data nilai siswa berhasil disimpan" . ($failed > 0 ? ", $failed gagal." : ".")
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
