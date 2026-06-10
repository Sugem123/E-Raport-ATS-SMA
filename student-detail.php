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
        SELECT * FROM tb_siswa 
        INNER JOIN tb_user ON tb_siswa.id_user = tb_user.id_user
        WHERE tb_siswa.id_user = '$id'
    "));

    // Cek apakah data ditemukan
    if (!$header) {
        echo "<script>alert('Data dengan ID tersebut tidak ditemukan!');
        window.location='students'</script>";
    }

    $detail = mysqli_query($conn,"
        SELECT * FROM tb_nilai n
        INNER JOIN tb_siswa s ON s.nis = n.nis
        INNER JOIN tb_guru g ON g.id_guru = n.id_guru_matpel
        WHERE n.nis = '{$header['nis']}'
    ");
} else {
    echo "ID tidak ditemukan!";
}
?>

<div class="col-lg-9 mt-2">
    <div class="card">
        <div class="card-header">
            Halaman Rangkuman Nilai Siswa
        </div>
        <div class="card-body">
            <!-- Tombol Cetak Laporan -->
            <div class="row">
                <div class="col d-flex justify-content-end">
                    <a href="student-report.php?id=<?=$id?>"
                    target="_blank"
                    class="btn btn-secondary">
                        <i class="fa fa-print"></i> Cetak Laporan
                    </a>
                </div>
            </div>
            <!-- Akhir Tombol Cetak Laporan -->
            <!-- Informasi Siswa -->
            <div class="row mt-3">
                <div class="col-md-4">
                    <div class="form-floating mb-3">
                        <input class="form-control"
                            value="<?=$header['nis']?>"
                            readonly>
                        <label>NIS</label>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form-floating mb-3">
                        <input class="form-control"
                            value="<?=$header['nama']?>"
                            readonly>
                        <label>Nama</label>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form-floating mb-3">
                        <input class="form-control"
                            value="<?=$header['kelas']?>"
                            readonly>
                        <label>Kelas</label>
                    </div>
                </div>
            </div>
            <!-- Akhir Informasi Guru -->
            <!-- Rengkuman Nilai -->
            <h5 class="mt-4">List Nilai Siswa</h5>
            <?php
            if (empty($detail)) {
                echo "Data detail pesanan tidak ada.";
            } else {
            ?>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th scope="col">No</th>
                                <th scope="col">Mata Pelajaran</th>
                                <th scope="col">Nama Guru</th>
                                <th scope="col">Nilai Tugas</th>
                                <th scope="col">Nilai UTS</th>
                                <th scope="col">Nilai UAS</th>
                                <th scope="col">Nilai Akhir</th>
                                <th scope="col">Status Kelulusan</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $no = 1;
                            foreach ($detail as $row) {
                            ?>
                                <tr>
                                    <th scope="row"><?php echo $no++ ?></th>
                                    <td><?php echo $row['mata_pelajaran'] ?></td>
                                    <td><?php echo $row['nama_guru'] ?></td>
                                    <td><?php echo $row['tugas'] ?></td>
                                    <td><?php echo $row['uts'] ?></td>
                                    <td><?php echo $row['uas'] ?></td>
                                    <td><?php echo $row['nilai_akhir'] ?></td>
                                    <td><?php echo $row['status_kelulusan'] ?></td>
                                </tr>
                            <?php
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            <?php
            }
            ?>
            <!-- Akhir Rangkuman Nilai -->
        </div>
    </div>
</div>
<script>
    // Example starter JavaScript for disabling form submissions if there are invalid fields
    (() => {
        'use strict'

        // Fetch all the forms we want to apply custom Bootstrap validation styles to
        const forms = document.querySelectorAll('.needs-validation')

        // Loop over them and prevent submission
        Array.from(forms).forEach(form => {
            form.addEventListener('submit', event => {
                if (!form.checkValidity()) {
                    event.preventDefault()
                    event.stopPropagation()
                }

                form.classList.add('was-validated')
            }, false)
        })
    })()
</script>