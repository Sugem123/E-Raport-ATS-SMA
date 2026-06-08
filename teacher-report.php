<?php
include_once "config/Database.php";

$db = new Database();
$conn = $db->connect();

// Pastikan parameter 'id' ada di URL untuk menghindari error
// Ini id user
if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    // echo "ID yang diterima adalah: " . $id;
    
    $header = mysqli_fetch_assoc(mysqli_query($conn,"
        SELECT * FROM tb_guru 
        INNER JOIN tb_user ON tb_guru.id_user = tb_user.id_user
        WHERE tb_guru.id_user = '$id'
    "));

    $detail = mysqli_query($conn,"
        SELECT * FROM tb_nilai n
        INNER JOIN tb_siswa s ON s.nis = n.nis
        WHERE n.id_guru_matpel = '{$header['id_guru']}'
    ");
} else {
    echo "ID tidak ditemukan!";
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak Laporan Nilai Siswa - <?=$header['nama_guru']?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <style>
        /* --- Gaya Tampilan di Layar Komputer --- */
        body {
            background-color: #f8f9fa;
            padding: 20px;
        }
        .print-container {
            background: #fff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0,0,0,0.05);
        }
        .info-text {
            font-size: 1.1rem;
            font-weight: bold;
            color: #333;
        }

        /* --- 🖨️ ATURAN KHUSUS UNTUK CETAK (PRINT) 🖨️ --- */
        @media print {
            body {
                background-color: #fff;
                padding: 0;
            }
            .print-container {
                box-shadow: none;
                padding: 0;
                border-radius: 0;
            }
            /* Sembunyikan tombol cetak dan kolom aksi tabel saat dicetak */
            .no-print {
                display: none !important;
            }
            /* Pengaturan halaman cetak agar pas dengan kertas */
            @page {
                size: A4;
                margin: 20mm 15mm 20mm 15mm;
            }
            /* Memastikan warna teks header tabel tercetak dengan baik */
            .table thead th {
                background-color: #f2f2f2 !important;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
        }
    </style>
</head>
<body>

<div class="container print-container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4 no-print">
         <button class="btn btn-secondary" onclick="window.close();">
            ✕ Tutup Halaman
        </button>
        <button onclick="window.print();" class="btn btn-primary">
            🖨️ Cetak Sekarang
        </button>
    </div>

    <div class="text-center mb-4 border-bottom pb-3">
        <h2>LAPORAN NILAI HASIL BELAJAR SISWA</h2>
        <p class="text-muted mb-0">Sistem Informasi Akademik SMAN 67 Tangerang</p>
    </div>

    <div class="row mb-4 bg-light p-3 rounded mx-1">
        <div class="col-4">
            <small class="text-muted d-block">ID GURU</small>
            <span class="info-text"><?=$header['id_guru']?></span>
        </div>
        <div class="col-4">
            <small class="text-muted d-block">NAMA GURU</small>
            <span class="info-text"><?=$header['nama_guru']?></span>
        </div>
        <div class="col-4">
            <small class="text-muted d-block">MATA PELAJARAN</small>
            <span class="info-text"><?=$header['mata_pelajaran']?></span>
        </div>
    </div>

    <h5 class="mb-3">Daftar Nilai Siswa</h5>
    <?php
    if (empty($detail)) {
        echo "<div class='alert alert-warning'>Data detail nilai tidak ditemukan.</div>";
    } else {
    ?>
        <table class="table table-bordered align-middle">
            <thead class="table-light">
                <tr>
                    <th scope="col" style="width: 5%">No</th>
                    <th scope="col">NIS</th>
                    <th scope="col" style="width: 25%">Nama</th>
                    <th scope="col" style="width: 10%">Kelas</th>
                    <th scope="col">Tugas</th>
                    <th scope="col">UTS</th>
                    <th scope="col">UAS</th>
                    <th scope="col">Nilai Akhir</th>
                    <th scope="col">Status</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $no = 1;
                foreach ($detail as $row) {
                    // Beri warna teks tipis pembeda lulus / tidak lulus di cetakan
                    $status_class = ($row['status_kelulusan'] == 'Lulus') ? 'text-success fw-bold' : 'text-danger fw-bold';
                ?>
                    <tr>
                        <td class="text-center"><?php echo $no++ ?></td>
                        <td><?php echo $row['nis'] ?></td>
                        <td><?php echo $row['nama'] ?></td>
                        <td class="text-center"><?php echo $row['kelas'] ?></td>
                        <td class="text-center"><?php echo $row['tugas'] ?></td>
                        <td class="text-center"><?php echo $row['uts'] ?></td>
                        <td class="text-center"><?php echo $row['uas'] ?></td>
                        <td class="text-center fw-bold"><?php echo $row['nilai_akhir'] ?></td>
                        <td class="<?php echo $status_class; ?>"><?php echo $row['status_kelulusan'] ?></td>
                    </tr>
                <?php
                }
                ?>
            </tbody>
        </table>
    <?php
    }
    ?>

    <div class="row mt-5 pt-4">
        <div class="col-8"></div>
        <div class="col-4 text-center">
            <p>Tangerang, <?= date('d F Y') ?></p>
            <p class="mb-5">Guru Mata Pelajaran,</p>
            <br><br>
            <p class="fw-bold text-decoration-underline"><?=$header['nama_guru']?></p>
            <p class="text-muted" style="margin-top: -15px;">NIP. <?=$header['id_guru']?></p>
        </div>
    </div>
</div>

<script>
    window.addEventListener('DOMContentLoaded', (event) => {
        // Beri jeda sedikit agar layout browser selesai memproses CSS render
        setTimeout(function() {
            window.print();
        }, 500);
    });
</script>

</body>
</html>