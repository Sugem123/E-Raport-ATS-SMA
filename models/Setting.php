<?php

class Setting
{
    private mysqli $conn;

    public function __construct(mysqli $conn)
    {
        $this->conn = $conn;
    }

    public function get(): array
    {
        $sql = "SELECT * FROM tb_pengaturan_rapor WHERE id = 1";
        $query = mysqli_query($this->conn, $sql);
        $data = mysqli_fetch_assoc($query);

        $defaults = [
            'nama_sekolah'        => 'SMA NEGERI 1 PRAMBON NGANJUK',
            'alamat_sekolah'      => 'JL. A. YANI 1 SUGIHWARAS PRAMBON',
            'logo_sekolah'        => null,
            'npsn'                => '20539744',
            'akreditasi'          => 'A (Unggul)',
            'slogan'              => 'Unggul dalam Prestasi, Berkarakter, dan Berbudaya Lingkungan',
            'telepon'             => '(0358) 771234',
            'email'               => 'info@sman1prambon.sch.id',
            'website'             => 'sman1prambon.sch.id',
            'deskripsi_sekolah'   => 'SMA Negeri 1 Prambon Nganjuk berkomitmen mewujudkan generasi cerdas, berakhlak mulia, kompetitif, serta siap menghadapi tantangan era digital dengan Kurikulum Merdeka.',
            'tahun_ajaran'        => '2025/2026',
            'semester'            => '2',
            'nama_kepala_sekolah' => 'Iin Yuristin Nadhiroh, S. Pd., M. MPd.',
            'nip_kepala_sekolah'  => '197405141999032010',
            'tempat_rapor'        => 'Prambon',
            'tanggal_rapor'       => '19 Juni 2026',
        ];

        if (!$data) {
            return $defaults;
        }

        foreach ($defaults as $k => $v) {
            if (!isset($data[$k]) || ($data[$k] === null && $k !== 'logo_sekolah')) {
                $data[$k] = $v;
            }
        }

        return $data;
    }

    public function update(array $data, ?array $file = null): array
    {
        $current = $this->get();

        $namaSekolah   = trim($data['nama_sekolah'] ?? $current['nama_sekolah']);
        $alamatSekolah = trim($data['alamat_sekolah'] ?? $current['alamat_sekolah']);
        $npsn          = trim($data['npsn'] ?? $current['npsn']);
        $akreditasi    = trim($data['akreditasi'] ?? $current['akreditasi']);
        $slogan        = trim($data['slogan'] ?? $current['slogan']);
        $telepon       = trim($data['telepon'] ?? $current['telepon']);
        $email         = trim($data['email'] ?? $current['email']);
        $website       = trim($data['website'] ?? $current['website']);
        $deskripsi     = trim($data['deskripsi_sekolah'] ?? $current['deskripsi_sekolah']);
        $tahunAjaran   = trim($data['tahun_ajaran'] ?? $current['tahun_ajaran']);
        $semester      = trim($data['semester'] ?? $current['semester']);
        $namaKepsek    = trim($data['nama_kepala_sekolah'] ?? '');
        $nipKepsek     = trim($data['nip_kepala_sekolah'] ?? '');
        $tempatRapor   = trim($data['tempat_rapor'] ?? $current['tempat_rapor']);
        $tanggalRapor  = trim($data['tanggal_rapor'] ?? $current['tanggal_rapor']);

        $logoPath = $current['logo_sekolah'];

        // Cek hapus logo
        if (!empty($data['hapus_logo'])) {
            if ($logoPath && file_exists(__DIR__ . '/../' . $logoPath)) {
                @unlink(__DIR__ . '/../' . $logoPath);
            }
            $logoPath = null;
        }

        // Cek upload logo baru
        if ($file && isset($file['error']) && $file['error'] === UPLOAD_ERR_OK) {
            $allowedExt = ['png', 'jpg', 'jpeg', 'svg', 'webp'];
            $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));

            if (!in_array($ext, $allowedExt, true)) {
                return ['success' => false, 'message' => 'Format logo harus PNG, JPG, JPEG, WEBP, atau SVG.'];
            }

            if ($file['size'] > 2 * 1024 * 1024) {
                return ['success' => false, 'message' => 'Ukuran logo maksimal 2MB.'];
            }

            $uploadDir = __DIR__ . '/../uploads/';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }

            // Hapus logo lama jika ada
            if ($logoPath && file_exists(__DIR__ . '/../' . $logoPath)) {
                @unlink(__DIR__ . '/../' . $logoPath);
            }

            $filename = 'logo_sekolah_' . time() . '.' . $ext;
            $dest = $uploadDir . $filename;

            if (move_uploaded_file($file['tmp_name'], $dest)) {
                $logoPath = 'uploads/' . $filename;
            } else {
                return ['success' => false, 'message' => 'Gagal mengupload logo sekolah ke server.'];
            }
        }

        if (empty($namaKepsek)) {
            return ['success' => false, 'message' => 'Nama Kepala Sekolah tidak boleh kosong.'];
        }
        if (empty($tahunAjaran)) {
            return ['success' => false, 'message' => 'Tahun Ajaran tidak boleh kosong.'];
        }

        $stmt = mysqli_prepare(
            $this->conn,
            "INSERT INTO tb_pengaturan_rapor
             (id, nama_sekolah, alamat_sekolah, logo_sekolah, npsn, akreditasi, slogan, telepon, email, website, deskripsi_sekolah, tahun_ajaran, semester, nama_kepala_sekolah, nip_kepala_sekolah, tempat_rapor, tanggal_rapor)
             VALUES (1, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
             ON DUPLICATE KEY UPDATE
             nama_sekolah = VALUES(nama_sekolah),
             alamat_sekolah = VALUES(alamat_sekolah),
             logo_sekolah = VALUES(logo_sekolah),
             npsn = VALUES(npsn),
             akreditasi = VALUES(akreditasi),
             slogan = VALUES(slogan),
             telepon = VALUES(telepon),
             email = VALUES(email),
             website = VALUES(website),
             deskripsi_sekolah = VALUES(deskripsi_sekolah),
             tahun_ajaran = VALUES(tahun_ajaran),
             semester = VALUES(semester),
             nama_kepala_sekolah = VALUES(nama_kepala_sekolah),
             nip_kepala_sekolah = VALUES(nip_kepala_sekolah),
             tempat_rapor = VALUES(tempat_rapor),
             tanggal_rapor = VALUES(tanggal_rapor)"
        );

        mysqli_stmt_bind_param(
            $stmt,
            "ssssssssssssssss",
            $namaSekolah,
            $alamatSekolah,
            $logoPath,
            $npsn,
            $akreditasi,
            $slogan,
            $telepon,
            $email,
            $website,
            $deskripsi,
            $tahunAjaran,
            $semester,
            $namaKepsek,
            $nipKepsek,
            $tempatRapor,
            $tanggalRapor
        );

        if (mysqli_stmt_execute($stmt)) {
            return ['success' => true, 'message' => 'Identitas sekolah & pengaturan rapor berhasil disimpan.'];
        }

        return ['success' => false, 'message' => 'Gagal menyimpan pengaturan: ' . mysqli_error($this->conn)];
    }

    public function getStats(): array
    {
        $qSiswa = mysqli_query($this->conn, "SELECT COUNT(*) as c FROM tb_siswa");
        $qGuru  = mysqli_query($this->conn, "SELECT COUNT(*) as c FROM tb_guru");
        $qKelas = mysqli_query($this->conn, "SELECT COUNT(*) as c FROM tb_kelas");
        $qMapel = mysqli_query($this->conn, "SELECT COUNT(*) as c FROM tb_mapel_referensi");

        return [
            'total_siswa' => (int)(mysqli_fetch_assoc($qSiswa)['c'] ?? 0),
            'total_guru'  => (int)(mysqli_fetch_assoc($qGuru)['c'] ?? 0),
            'total_kelas' => (int)(mysqli_fetch_assoc($qKelas)['c'] ?? 0),
            'total_mapel' => (int)(mysqli_fetch_assoc($qMapel)['c'] ?? 0),
        ];
    }
}
