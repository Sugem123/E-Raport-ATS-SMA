<?php

class Bobot
{
    private mysqli $conn;

    public function __construct(mysqli $conn)
    {
        $this->conn = $conn;
    }

    public function get(): array
    {
        $query = mysqli_query($this->conn, "SELECT * FROM tb_pengaturan_bobot WHERE id_pengaturan = 1");
        $data = mysqli_fetch_assoc($query);
        if (!$data) {
            return [
                'id_pengaturan' => 1,
                'bobot_sumatif' => 60.00,
                'bobot_sts' => 40.00,
                'kkm' => 75.00
            ];
        }
        return $data;
    }

    public function update(float $bobotSumatif, float $bobotSts, float $kkm): array
    {
        if ($bobotSumatif < 0 || $bobotSts < 0 || ($bobotSumatif + $bobotSts) <= 0) {
            return [
                'success' => false,
                'message' => 'Bobot penilaian harus berupa angka positif.'
            ];
        }

        $stmt = mysqli_prepare(
            $this->conn,
            "INSERT INTO tb_pengaturan_bobot (id_pengaturan, bobot_sumatif, bobot_sts, kkm)
             VALUES (1, ?, ?, ?)
             ON DUPLICATE KEY UPDATE bobot_sumatif = VALUES(bobot_sumatif), bobot_sts = VALUES(bobot_sts), kkm = VALUES(kkm)"
        );
        mysqli_stmt_bind_param($stmt, "ddd", $bobotSumatif, $bobotSts, $kkm);

        if (mysqli_stmt_execute($stmt)) {
            return [
                'success' => true,
                'message' => 'Pengaturan pembagian bobot STS berhasil disimpan.'
            ];
        }

        return [
            'success' => false,
            'message' => 'Gagal menyimpan pengaturan bobot.'
        ];
    }
}
