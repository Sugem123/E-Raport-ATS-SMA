<?php

class Kelas
{
    private mysqli $conn;

    public function __construct(mysqli $conn)
    {
        $this->conn = $conn;
    }

    public function getAll(): array
    {
        $sql = "SELECT k.*, g.nama_guru AS nama_walikelas,
                       (SELECT COUNT(*) FROM tb_siswa s WHERE s.id_kelas = k.id_kelas) AS total_siswa,
                       (SELECT COUNT(*) FROM tb_pengampu p WHERE p.id_kelas = k.id_kelas) AS total_mapel
                FROM tb_kelas k
                LEFT JOIN tb_guru g ON k.id_guru_walikelas = g.id_guru
                ORDER BY k.tingkat ASC, k.nama_kelas ASC";
        $query = mysqli_query($this->conn, $sql);
        $data = [];
        while ($row = mysqli_fetch_assoc($query)) {
            $data[] = $row;
        }
        return $data;
    }

    public function getById(int $idKelas): ?array
    {
        $stmt = mysqli_prepare(
            $this->conn,
            "SELECT k.*, g.nama_guru AS nama_walikelas
             FROM tb_kelas k
             LEFT JOIN tb_guru g ON k.id_guru_walikelas = g.id_guru
             WHERE k.id_kelas = ?"
        );
        mysqli_stmt_bind_param($stmt, "i", $idKelas);
        mysqli_stmt_execute($stmt);
        $res = mysqli_stmt_get_result($stmt);
        return mysqli_fetch_assoc($res);
    }

    public function getByWaliKelas(string $idGuru): ?array
    {
        $stmt = mysqli_prepare(
            $this->conn,
            "SELECT k.*, g.nama_guru AS nama_walikelas
             FROM tb_kelas k
             INNER JOIN tb_guru g ON k.id_guru_walikelas = g.id_guru
             WHERE k.id_guru_walikelas = ?
             LIMIT 1"
        );
        mysqli_stmt_bind_param($stmt, "s", $idGuru);
        mysqli_stmt_execute($stmt);
        $res = mysqli_stmt_get_result($stmt);
        return mysqli_fetch_assoc($res);
    }

    public function create(string $namaKelas, string $tingkat, ?string $idGuru = null): array
    {
        $namaKelas = trim($namaKelas);
        $tingkat = trim($tingkat);
        $idGuru = !empty($idGuru) ? trim($idGuru) : null;

        $check = mysqli_prepare($this->conn, "SELECT id_kelas FROM tb_kelas WHERE nama_kelas = ?");
        mysqli_stmt_bind_param($check, "s", $namaKelas);
        mysqli_stmt_execute($check);
        mysqli_stmt_store_result($check);

        if (mysqli_stmt_num_rows($check) > 0) {
            return ['success' => false, 'message' => "Kelas '$namaKelas' sudah ada."];
        }

        $stmt = mysqli_prepare($this->conn, "INSERT INTO tb_kelas (nama_kelas, tingkat, id_guru_walikelas) VALUES (?, ?, ?)");
        mysqli_stmt_bind_param($stmt, "sss", $namaKelas, $tingkat, $idGuru);

        if (mysqli_stmt_execute($stmt)) {
            return ['success' => true, 'message' => 'Kelas berhasil ditambahkan.'];
        }
        return ['success' => false, 'message' => 'Gagal menambahkan kelas.'];
    }

    public function update(int $idKelas, string $namaKelas, string $tingkat, ?string $idGuru = null): array
    {
        $namaKelas = trim($namaKelas);
        $tingkat = trim($tingkat);
        $idGuru = !empty($idGuru) ? trim($idGuru) : null;

        $check = mysqli_prepare($this->conn, "SELECT id_kelas FROM tb_kelas WHERE nama_kelas = ? AND id_kelas != ?");
        mysqli_stmt_bind_param($check, "si", $namaKelas, $idKelas);
        mysqli_stmt_execute($check);
        mysqli_stmt_store_result($check);

        if (mysqli_stmt_num_rows($check) > 0) {
            return ['success' => false, 'message' => "Nama kelas '$namaKelas' sudah digunakan."];
        }

        $stmt = mysqli_prepare($this->conn, "UPDATE tb_kelas SET nama_kelas = ?, tingkat = ?, id_guru_walikelas = ? WHERE id_kelas = ?");
        mysqli_stmt_bind_param($stmt, "sssi", $namaKelas, $tingkat, $idGuru, $idKelas);

        if (mysqli_stmt_execute($stmt)) {
            return ['success' => true, 'message' => 'Kelas berhasil diupdate.'];
        }
        return ['success' => false, 'message' => 'Gagal mengupdate kelas.'];
    }

    public function delete(int $idKelas): array
    {
        $stmt = mysqli_prepare($this->conn, "DELETE FROM tb_kelas WHERE id_kelas = ?");
        mysqli_stmt_bind_param($stmt, "i", $idKelas);

        if (mysqli_stmt_execute($stmt)) {
            return ['success' => true, 'message' => 'Kelas berhasil dihapus.'];
        }
        return ['success' => false, 'message' => 'Gagal menghapus kelas.'];
    }

    public function importExcel(string $filePath, string $originalFileName): array
    {
        require_once __DIR__ . '/../config/ExcelHelper.php';
        $rows = ExcelHelper::parse($filePath, $originalFileName);

        if (empty($rows)) {
            return ['success' => false, 'message' => 'File Excel kosong atau format tidak sesuai.'];
        }

        $inserted = 0;
        $skipped = 0;

        foreach ($rows as $row) {
            $nama = $row['nama_kelas'] ?? '';
            $tingkat = $row['tingkat'] ?? '';
            $idGuru = !empty($row['id_guru_walikelas']) ? trim($row['id_guru_walikelas']) : null;

            if (empty($nama) || empty($tingkat)) {
                $skipped++;
                continue;
            }

            $res = $this->create($nama, $tingkat, $idGuru);
            if ($res['success']) {
                $inserted++;
            } else {
                $skipped++;
            }
        }

        return [
            'success' => true,
            'message' => "Upload selesai. $inserted kelas berhasil ditambahkan, $skipped dilewati/sudah ada."
        ];
    }

    /**
     * Mengambil daftar siswa dan rekap perolehan nilai untuk wali kelas
     */
    public function getHomeroomSummary(int $idKelas): array
    {
        // 1. Ambil daftar siswa
        $sqlSiswa = "SELECT s.nis, s.nama, s.id_user,
                            COALESCE(pr.sakit, 0) AS sakit,
                            COALESCE(pr.izin, 0) AS izin,
                            COALESCE(pr.alpa, 0) AS alpa
                     FROM tb_siswa s
                     LEFT JOIN tb_presensi_sts pr ON s.nis = pr.nis
                     WHERE s.id_kelas = ?
                     ORDER BY s.nama ASC";
        $stmtS = mysqli_prepare($this->conn, $sqlSiswa);
        mysqli_stmt_bind_param($stmtS, "i", $idKelas);
        mysqli_stmt_execute($stmtS);
        $resS = mysqli_stmt_get_result($stmtS);

        // 2. Ambil seluruh mata pelajaran di kelas ini, diurutkan sesuai
        //    mapping jenjang kelas tersebut (untuk cetak rapor).
        //    Hanya mapel yang terdaftar di mapping kurikulum jenjang tersebut yang dimunculkan.
        $sqlMapel = "SELECT p.id_pengampu, m.id_mapel, m.nama_mapel, g.nama_guru,
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
                         m.nama_mapel ASC";
        $stmtM = mysqli_prepare($this->conn, $sqlMapel);
        mysqli_stmt_bind_param($stmtM, "i", $idKelas);
        mysqli_stmt_execute($stmtM);
        $resM = mysqli_stmt_get_result($stmtM);

        $mapelList = [];
        while ($m = mysqli_fetch_assoc($resM)) {
            $mapelList[] = $m;
        }

        $siswaList = [];
        while ($s = mysqli_fetch_assoc($resS)) {
            $nis = $s['nis'];

            // Ambil semua nilai siswa ini
            $sqlNilai = "SELECT n.id_pengampu, n.sumatif_1, n.sumatif_2, n.sumatif_3, n.rata_sumatif,
                                n.nilai_sts, n.nilai_akhir, n.status_kelulusan
                         FROM tb_nilai_sts n
                         WHERE n.nis = ?";
            $stmtN = mysqli_prepare($this->conn, $sqlNilai);
            mysqli_stmt_bind_param($stmtN, "s", $nis);
            mysqli_stmt_execute($stmtN);
            $resN = mysqli_stmt_get_result($stmtN);

            $nilaiMapel = [];
            $totalAkhir = 0;
            $countLengkap = 0;
            while ($n = mysqli_fetch_assoc($resN)) {
                $nilaiMapel[$n['id_pengampu']] = $n;
                // Nilai terhitung jika nilai_sts tidak null atau nilai_akhir tidak null
                $valRef = ($n['nilai_sts'] !== null && $n['nilai_sts'] !== '')
                    ? (float)$n['nilai_sts']
                    : (($n['nilai_akhir'] !== null && $n['nilai_akhir'] !== '') ? (float)$n['nilai_akhir'] : null);
                if ($valRef !== null) {
                    $totalAkhir += $valRef;
                    $countLengkap++;
                }
            }

            $s['nilai'] = $nilaiMapel;
            $s['rata_rata_semua'] = ($countLengkap > 0) ? round($totalAkhir / $countLengkap, 2) : 0;
            $s['jumlah_terisi'] = $countLengkap;
            $s['total_mapel'] = count($mapelList);
            $siswaList[] = $s;
        }

        return [
            'mapel_list' => $mapelList,
            'siswa_list' => $siswaList
        ];
    }

    /**
     * Mengambil ringkasan monitoring pengisian nilai untuk seluruh kelas (Admin View)
     */
    public function getMonitoringOverview(): array
    {
        $classes = $this->getAll();
        $list = [];

        $totalKelas = count($classes);
        $totalLengkap = 0;
        $totalBelum = 0;
        $totalSiswaSekolah = 0;
        $totalNilaiSekolah = 0;
        $totalTargetSekolah = 0;

        foreach ($classes as $c) {
            $idKelas = (int)$c['id_kelas'];

            // Total siswa di kelas ini
            $qS = mysqli_query($this->conn, "SELECT COUNT(*) as c FROM tb_siswa WHERE id_kelas = $idKelas");
            $totalSiswa = (int)(mysqli_fetch_assoc($qS)['c'] ?? 0);

            // Total mapel di kelas ini
            $qM = mysqli_query($this->conn, "SELECT COUNT(*) as c FROM tb_pengampu WHERE id_kelas = $idKelas");
            $totalMapel = (int)(mysqli_fetch_assoc($qM)['c'] ?? 0);

            // Total nilai masuk (di mana nilai_sts IS NOT NULL atau nilai_akhir IS NOT NULL)
            $sqlNilai = "SELECT COUNT(*) as c
                         FROM tb_nilai_sts n
                         INNER JOIN tb_pengampu p ON n.id_pengampu = p.id_pengampu
                         INNER JOIN tb_siswa s ON n.nis = s.nis
                         WHERE p.id_kelas = $idKelas AND s.id_kelas = $idKelas 
                           AND (n.nilai_sts IS NOT NULL OR n.nilai_akhir IS NOT NULL)";
            $qN = mysqli_query($this->conn, $sqlNilai);
            $totalNilaiMasuk = (int)(mysqli_fetch_assoc($qN)['c'] ?? 0);

            $targetNilai = $totalSiswa * $totalMapel;

            // Hitung berapa mapel yang sudah lengkap (siswa dinilai >= total siswa)
            $sqlMapelLengkap = "SELECT COUNT(*) as c FROM (
                                    SELECT p.id_pengampu, COUNT(n.id_nilai) as cnt_nilai
                                    FROM tb_pengampu p
                                    LEFT JOIN tb_nilai_sts n ON (p.id_pengampu = n.id_pengampu AND (n.nilai_sts IS NOT NULL OR n.nilai_akhir IS NOT NULL))
                                    LEFT JOIN tb_siswa s ON (n.nis = s.nis AND s.id_kelas = p.id_kelas)
                                    WHERE p.id_kelas = $idKelas
                                    GROUP BY p.id_pengampu
                                    HAVING cnt_nilai >= $totalSiswa AND $totalSiswa > 0
                                ) as tbl";
            $qML = mysqli_query($this->conn, $sqlMapelLengkap);
            $mapelLengkap = (int)(mysqli_fetch_assoc($qML)['c'] ?? 0);

            $persen = ($targetNilai > 0) ? round(($totalNilaiMasuk / $targetNilai) * 100, 1) : 0;
            $isLengkap = ($totalMapel > 0 && $totalSiswa > 0 && $mapelLengkap === $totalMapel && $totalNilaiMasuk >= $targetNilai);

            if ($isLengkap) {
                $totalLengkap++;
            } else {
                $totalBelum++;
            }

            $totalSiswaSekolah += $totalSiswa;
            $totalNilaiSekolah += $totalNilaiMasuk;
            $totalTargetSekolah += $targetNilai;

            $list[] = [
                'id_kelas' => $idKelas,
                'nama_kelas' => $c['nama_kelas'],
                'tingkat' => $c['tingkat'],
                'id_guru_walikelas' => $c['id_guru_walikelas'],
                'nama_walikelas' => $c['nama_walikelas'],
                'total_siswa' => $totalSiswa,
                'total_mapel' => $totalMapel,
                'total_nilai_masuk' => $totalNilaiMasuk,
                'target_nilai' => $targetNilai,
                'mapel_lengkap' => $mapelLengkap,
                'persen' => $persen,
                'is_lengkap' => $isLengkap
            ];
        }

        $persenGlobal = ($totalTargetSekolah > 0) ? round(($totalNilaiSekolah / $totalTargetSekolah) * 100, 1) : 0;

        return [
            'stats' => [
                'total_kelas' => $totalKelas,
                'kelas_lengkap' => $totalLengkap,
                'kelas_belum' => $totalBelum,
                'total_siswa' => $totalSiswaSekolah,
                'total_nilai_masuk' => $totalNilaiSekolah,
                'total_target_nilai' => $totalTargetSekolah,
                'persen_global' => $persenGlobal
            ],
            'classes' => $list
        ];
    }

    /**
     * Mengambil detail monitoring pengisian nilai per mata pelajaran dalam 1 kelas
     */
    public function getMonitoringPerMapel(int $idKelas): array
    {
        $kelas = $this->getById($idKelas);
        if (!$kelas) {
            return [];
        }

        // Total siswa di kelas
        $qS = mysqli_query($this->conn, "SELECT COUNT(*) as c FROM tb_siswa WHERE id_kelas = $idKelas");
        $totalSiswa = (int)(mysqli_fetch_assoc($qS)['c'] ?? 0);

        // Ambil setiap pengampu mapel di kelas ini (hanya yang masuk dalam mapping jenjang)
        $sql = "SELECT p.id_pengampu, p.id_mapel, m.nama_mapel, p.id_guru, g.nama_guru,
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
                    m.nama_mapel ASC";
        $stmt = mysqli_prepare($this->conn, $sql);
        mysqli_stmt_bind_param($stmt, "i", $idKelas);
        mysqli_stmt_execute($stmt);
        $res = mysqli_stmt_get_result($stmt);

        $mapelList = [];
        $totalMapel = 0;
        $mapelLengkap = 0;
        $totalNilaiMasuk = 0;

        while ($row = mysqli_fetch_assoc($res)) {
            $idPengampu = (int)$row['id_pengampu'];
            $totalMapel++;

            // Hitung siswa yang sudah dinilai pada mapel ini
            $sqlNilai = "SELECT COUNT(*) as c, 
                                AVG(COALESCE(n.nilai_sts, n.nilai_akhir)) as avg_na
                         FROM tb_nilai_sts n
                         INNER JOIN tb_siswa s ON n.nis = s.nis
                         WHERE n.id_pengampu = $idPengampu AND s.id_kelas = $idKelas 
                           AND (n.nilai_sts IS NOT NULL OR n.nilai_akhir IS NOT NULL)";
            $resN = mysqli_query($this->conn, $sqlNilai);
            $rowN = mysqli_fetch_assoc($resN);

            $siswaDinilai = (int)($rowN['c'] ?? 0);
            $avgNa = !empty($rowN['avg_na']) ? round((float)$rowN['avg_na'], 2) : 0;
            $siswaBelum = max(0, $totalSiswa - $siswaDinilai);
            $persen = ($totalSiswa > 0) ? round(($siswaDinilai / $totalSiswa) * 100, 1) : 0;
            $isLengkap = ($totalSiswa > 0 && $siswaDinilai >= $totalSiswa);

            if ($isLengkap) {
                $mapelLengkap++;
            }
            $totalNilaiMasuk += $siswaDinilai;

            $row['total_siswa'] = $totalSiswa;
            $row['siswa_dinilai'] = $siswaDinilai;
            $row['siswa_belum'] = $siswaBelum;
            $row['persen'] = $persen;
            $row['is_lengkap'] = $isLengkap;
            $row['rata_nilai'] = $avgNa;

            $mapelList[] = $row;
        }

        $targetTotal = $totalSiswa * $totalMapel;
        $persenKelas = ($targetTotal > 0) ? round(($totalNilaiMasuk / $targetTotal) * 100, 1) : 0;
        $isKelasLengkap = ($totalMapel > 0 && $totalSiswa > 0 && $mapelLengkap === $totalMapel);

        return [
            'kelas' => $kelas,
            'stats' => [
                'total_siswa' => $totalSiswa,
                'total_mapel' => $totalMapel,
                'mapel_lengkap' => $mapelLengkap,
                'mapel_belum' => ($totalMapel - $mapelLengkap),
                'total_nilai_masuk' => $totalNilaiMasuk,
                'target_total' => $targetTotal,
                'persen_kelas' => $persenKelas,
                'is_lengkap' => $isKelasLengkap
            ],
            'mapel_list' => $mapelList
        ];
    }
}
