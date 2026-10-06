<?php

class Ledger
{
    private mysqli $conn;

    public function __construct(mysqli $conn)
    {
        $this->conn = $conn;
    }

    /**
     * Mengambil data lengkap matriks Ledger Nilai untuk suatu kelas (Read-Only)
     */
    public function getLedgerData(int $idKelas): ?array
    {
        if ($idKelas <= 0) {
            return null;
        }

        // 1. Data Kelas & Wali Kelas
        $stmtK = mysqli_prepare($this->conn, "
            SELECT k.id_kelas, k.nama_kelas, k.tingkat, k.id_guru_walikelas, g.nama_guru AS nama_walikelas
            FROM tb_kelas k
            LEFT JOIN tb_guru g ON k.id_guru_walikelas = g.id_guru
            WHERE k.id_kelas = ?
        ");
        mysqli_stmt_bind_param($stmtK, "i", $idKelas);
        mysqli_stmt_execute($stmtK);
        $kelas = mysqli_fetch_assoc(mysqli_stmt_get_result($stmtK));

        if (!$kelas) {
            return null;
        }

        // 2. Data Pengaturan Rapor & Identitas Sekolah
        $qSet = mysqli_query($this->conn, "SELECT * FROM tb_pengaturan_rapor WHERE id = 1");
        $setting = mysqli_fetch_assoc($qSet) ?: [];

        // 3. Daftar Mata Pelajaran di Kelas ini terurut sesuai mapping kurikulum
        //    (Kelompok Umum No. 1 s/d selesai di sebelah kiri, lalu Kelompok Pilihan No. 1 s/d selesai)
        //    Hanya mapel yang telah di-mapping untuk jenjang kelas ini yang dimunculkan di leger.
        $sqlMapel = "
            SELECT p.id_pengampu, m.id_mapel, m.nama_mapel, g.nama_guru,
                   mp.kategori,
                   mp.urutan
            FROM tb_pengampu p
            INNER JOIN tb_kelas k ON p.id_kelas = k.id_kelas
            INNER JOIN tb_mapel_referensi m ON p.id_mapel = m.id_mapel
            INNER JOIN tb_guru g ON p.id_guru = g.id_guru
            INNER JOIN tb_mapel_mapping mp ON (mp.id_mapel = m.id_mapel AND mp.jenjang = k.tingkat)
            WHERE p.id_kelas = ?
            ORDER BY
                CASE WHEN mp.kategori = 'Umum' THEN 1 ELSE 2 END ASC,
                mp.urutan ASC,
                m.nama_mapel ASC
        ";
        $stmtM = mysqli_prepare($this->conn, $sqlMapel);
        mysqli_stmt_bind_param($stmtM, "i", $idKelas);
        mysqli_stmt_execute($stmtM);
        $resM = mysqli_stmt_get_result($stmtM);

        $mapelList = [];
        $mapelUmum = [];
        $mapelPilihan = [];

        while ($m = mysqli_fetch_assoc($resM)) {
            $mapelList[] = $m;
            if (strtolower(trim((string)$m['kategori'])) === 'pilihan') {
                $mapelPilihan[] = $m;
            } else {
                $mapelUmum[] = $m;
            }
        }

        // 4. Daftar Siswa & Presensi di Kelas ini
        $sqlSiswa = "
            SELECT s.nis, s.nisn, s.nama, s.id_kelas,
                   COALESCE(pr.sakit, 0) AS sakit,
                   COALESCE(pr.izin, 0) AS izin,
                   COALESCE(pr.alpa, 0) AS alpa
            FROM tb_siswa s
            LEFT JOIN tb_presensi_sts pr ON s.nis = pr.nis
            WHERE s.id_kelas = ?
            ORDER BY s.nama ASC
        ";
        $stmtS = mysqli_prepare($this->conn, $sqlSiswa);
        mysqli_stmt_bind_param($stmtS, "i", $idKelas);
        mysqli_stmt_execute($stmtS);
        $resS = mysqli_stmt_get_result($stmtS);

        $siswaList = [];
        while ($s = mysqli_fetch_assoc($resS)) {
            $siswaList[] = $s;
        }

        // 5. Seluruh Nilai Siswa pada Kelas ini
        $sqlNilai = "
            SELECT n.nis, n.id_pengampu,
                   n.sumatif_1, n.sumatif_2, n.sumatif_3, n.sumatif_4,
                   n.nilai_sts
            FROM tb_nilai_sts n
            INNER JOIN tb_siswa s ON n.nis = s.nis
            WHERE s.id_kelas = ?
        ";
        $stmtN = mysqli_prepare($this->conn, $sqlNilai);
        mysqli_stmt_bind_param($stmtN, "i", $idKelas);
        mysqli_stmt_execute($stmtN);
        $resN = mysqli_stmt_get_result($stmtN);

        $grades = [];
        while ($n = mysqli_fetch_assoc($resN)) {
            $grades[$n['nis']][$n['id_pengampu']] = [
                's1'  => ($n['sumatif_1'] !== null && $n['sumatif_1'] !== '') ? (float)$n['sumatif_1'] : null,
                's2'  => ($n['sumatif_2'] !== null && $n['sumatif_2'] !== '') ? (float)$n['sumatif_2'] : null,
                's3'  => ($n['sumatif_3'] !== null && $n['sumatif_3'] !== '') ? (float)$n['sumatif_3'] : null,
                's4'  => ($n['sumatif_4'] !== null && $n['sumatif_4'] !== '') ? (float)$n['sumatif_4'] : null,
                'ats' => ($n['nilai_sts'] !== null && $n['nilai_sts'] !== '') ? (float)$n['nilai_sts'] : null,
            ];
        }

        return [
            'kelas'         => $kelas,
            'setting'       => $setting,
            'mapel_list'    => $mapelList,
            'mapel_umum'    => $mapelUmum,
            'mapel_pilihan' => $mapelPilihan,
            'siswa_list'    => $siswaList,
            'grades'        => $grades
        ];
    }

    /**
     * Menghitung kalkulasi statistik leger per siswa (total, rata-rata, ranking)
     * dan per mata pelajaran (rata-rata, tertinggi, terendah)
     */
    public function calculateStats(array $data): array
    {
        $siswaList = $data['siswa_list'] ?? [];
        $mapelList = $data['mapel_list'] ?? [];
        $grades    = $data['grades'] ?? [];

        $studentStats = [];
        $subjectStats = [];

        // Inisialisasi statistik mapel
        foreach ($mapelList as $m) {
            $idP = (int)$m['id_pengampu'];
            $subjectStats[$idP] = [
                'ats_scores' => [],
                's1_scores'  => [],
                's2_scores'  => [],
                's3_scores'  => [],
                's4_scores'  => [],
            ];
        }

        // Kalkulasi per siswa
        foreach ($siswaList as $s) {
            $nis = $s['nis'];
            $totalAts = 0;
            $countAts = 0;

            foreach ($mapelList as $m) {
                $idP = (int)$m['id_pengampu'];
                $g = $grades[$nis][$idP] ?? null;

                if ($g) {
                    if ($g['ats'] !== null) {
                        $totalAts += $g['ats'];
                        $countAts++;
                        $subjectStats[$idP]['ats_scores'][] = $g['ats'];
                    }
                    if ($g['s1'] !== null) $subjectStats[$idP]['s1_scores'][] = $g['s1'];
                    if ($g['s2'] !== null) $subjectStats[$idP]['s2_scores'][] = $g['s2'];
                    if ($g['s3'] !== null) $subjectStats[$idP]['s3_scores'][] = $g['s3'];
                    if ($g['s4'] !== null) $subjectStats[$idP]['s4_scores'][] = $g['s4'];
                }
            }

            $avgAts = ($countAts > 0) ? round($totalAts / $countAts, 2) : 0;
            $studentStats[$nis] = [
                'total_ats' => $totalAts,
                'count_ats' => $countAts,
                'avg_ats'   => $avgAts,
            ];
        }

        // Hitung rata-rata, max, min per mapel
        $subjectSummary = [];
        foreach ($mapelList as $m) {
            $idP = (int)$m['id_pengampu'];
            $atsArr = $subjectStats[$idP]['ats_scores'] ?? [];
            $s1Arr  = $subjectStats[$idP]['s1_scores'] ?? [];
            $s2Arr  = $subjectStats[$idP]['s2_scores'] ?? [];
            $s3Arr  = $subjectStats[$idP]['s3_scores'] ?? [];
            $s4Arr  = $subjectStats[$idP]['s4_scores'] ?? [];

            $cnt = count($atsArr);
            $subjectSummary[$idP] = [
                'count'   => $cnt,
                'avg_ats' => $cnt > 0 ? round(array_sum($atsArr) / $cnt, 2) : '-',
                'max_ats' => $cnt > 0 ? max($atsArr) : '-',
                'min_ats' => $cnt > 0 ? min($atsArr) : '-',
                'avg_s1'  => count($s1Arr) > 0 ? round(array_sum($s1Arr) / count($s1Arr), 2) : '-',
                'avg_s2'  => count($s2Arr) > 0 ? round(array_sum($s2Arr) / count($s2Arr), 2) : '-',
                'avg_s3'  => count($s3Arr) > 0 ? round(array_sum($s3Arr) / count($s3Arr), 2) : '-',
                'avg_s4'  => count($s4Arr) > 0 ? round(array_sum($s4Arr) / count($s4Arr), 2) : '-',
            ];
        }

        return [
            'student_stats'   => $studentStats,
            'subject_summary' => $subjectSummary
        ];
    }
}
