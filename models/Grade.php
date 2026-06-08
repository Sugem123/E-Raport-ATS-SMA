<?php

// Class Grade digunakan untuk mengelola data nilai siswa
// meliputi tambah, ubah, dan hapus nilai.
class Grade
{
    // Menyimpan koneksi database
    private mysqli $conn;

    // Constructor menerima objek koneksi database
    public function __construct(mysqli $conn)
    {
        $this->conn = $conn;
    }

    // Method private untuk memvalidasi nilai
    // agar berada pada rentang 0 - 100
    private function validateScore(
        float $tugas,
        float $uts,
        float $uas
    ): bool {
        return (
            $tugas >= 0 && $tugas <= 100 &&
            $uts >= 0 && $uts <= 100 &&
            $uas >= 0 && $uas <= 100
        );
    }

    // Method private untuk menghitung nilai akhir
    // dan menentukan status kelulusan
    private function calculateFinal(
        float $tugas,
        float $uts,
        float $uas
    ): array {

        // Bobot:
        // Tugas = 30%
        // UTS = 30%
        // UAS = 40%
        $nilaiAkhir =
            ($tugas * 0.3) +
            ($uts * 0.3) +
            ($uas * 0.4);

        return [
            'nilai_akhir' => $nilaiAkhir,

            // Lulus jika nilai akhir >= 70
            'status' => $nilaiAkhir >= 70
                ? 'Lulus'
                : 'Tidak Lulus'
        ];
    }

    // Method untuk menambahkan nilai siswa
    public function create(
        string $nis,
        string $guruId,
        float $tugas,
        float $uts,
        float $uas
    ): array {

        // Validasi nilai
        if (
            !$this->validateScore(
                $tugas,
                $uts,
                $uas
            )
        ) {
            return [
                'success' => false,
                'message' => 'Nilai harus 0-100'
            ];
        }

        // Memastikan NIS siswa ada
        $siswa = mysqli_prepare(
            $this->conn,
            "SELECT nis
             FROM tb_siswa
             WHERE nis=?"
        );

        mysqli_stmt_bind_param(
            $siswa,
            "s",
            $nis
        );

        mysqli_stmt_execute($siswa);
        mysqli_stmt_store_result($siswa);

        // Jika siswa tidak ditemukan
        if (
            mysqli_stmt_num_rows($siswa) == 0
        ) {
            return [
                'success' => false,
                'message' => 'NIS tidak ditemukan'
            ];
        }

        // Mengecek apakah siswa sudah memiliki nilai
        // dari guru/mata pelajaran yang sama
        $check = mysqli_prepare(
            $this->conn,
            "SELECT id_nilai
             FROM tb_nilai
             WHERE nis=?
             AND id_guru_matpel=?"
        );

        mysqli_stmt_bind_param(
            $check,
            "ss",
            $nis,
            $guruId
        );

        mysqli_stmt_execute($check);
        mysqli_stmt_store_result($check);

        // Jika nilai sudah ada
        if (
            mysqli_stmt_num_rows($check) > 0
        ) {
            return [
                'success' => false,
                'message' => 'Siswa sudah dinilai'
            ];
        }

        // Menghitung nilai akhir dan status kelulusan
        $result = $this->calculateFinal(
            $tugas,
            $uts,
            $uas
        );

        // Menyimpan data nilai ke database
        $stmt = mysqli_prepare(
            $this->conn,
            "INSERT INTO tb_nilai
            (
                nis,
                id_guru_matpel,
                tugas,
                uts,
                uas,
                nilai_akhir,
                status_kelulusan
            )
            VALUES(?,?,?,?,?,?,?)"
        );

        mysqli_stmt_bind_param(
            $stmt,
            "ssdddds",
            $nis,
            $guruId,
            $tugas,
            $uts,
            $uas,
            $result['nilai_akhir'],
            $result['status']
        );

        mysqli_stmt_execute($stmt);

        return [
            'success' => true,
            'message' => 'Nilai berhasil ditambah'
        ];
    }

    // Method untuk memperbarui nilai siswa
    public function update(
        int $idNilai,
        float $tugas,
        float $uts,
        float $uas
    ): array {

        // Validasi nilai
        if (
            !$this->validateScore(
                $tugas,
                $uts,
                $uas
            )
        ) {
            return [
                'success' => false,
                'message' => 'Nilai harus 0-100'
            ];
        }

        // Menghitung ulang nilai akhir
        $result = $this->calculateFinal(
            $tugas,
            $uts,
            $uas
        );

        // Mengupdate data nilai
        $stmt = mysqli_prepare(
            $this->conn,
            "UPDATE tb_nilai
             SET tugas=?,
                 uts=?,
                 uas=?,
                 nilai_akhir=?,
                 status_kelulusan=?
             WHERE id_nilai=?"
        );

        mysqli_stmt_bind_param(
            $stmt,
            "ddddsi",
            $tugas,
            $uts,
            $uas,
            $result['nilai_akhir'],
            $result['status'],
            $idNilai
        );

        mysqli_stmt_execute($stmt);

        return [
            'success' => true,
            'message' => 'Nilai berhasil diperbarui'
        ];
    }

    // Method untuk menghapus data nilai
    public function delete(
        int $idNilai
    ): array {

        $stmt = mysqli_prepare(
            $this->conn,
            "DELETE FROM tb_nilai
             WHERE id_nilai=?"
        );

        mysqli_stmt_bind_param(
            $stmt,
            "i",
            $idNilai
        );

        mysqli_stmt_execute($stmt);

        return [
            'success' => true,
            'message' => 'Nilai berhasil dihapus'
        ];
    }
}
?>