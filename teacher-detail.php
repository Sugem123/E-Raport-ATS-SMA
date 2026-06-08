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

<div class="col-lg-9 mt-2">
    <div class="card">
        <div class="card-header">
            Halaman List Nilai Siswa yang Diampu Guru
        </div>
        <div class="card-body">
            <!-- Tombol Cetak Laporan -->
            <div class="row">
                <div class="col d-flex justify-content-end">
                    <a href="teacher-report.php?id=<?=$id?>"
                    target="_blank"
                    class="btn btn-secondary">
                        <i class="fa fa-print"></i> Cetak Laporan
                    </a>
                </div>
            </div>
            <!-- Akhir Tombol Cetak Laporan -->
            <?php
            foreach ($detail as $row) {
                $id_nilai = $row['id_nilai']; // Simpan ke variabel agar kode lebih rapi
            ?>
            <!-- Modal Update Input Nilai -->
            <div class="modal fade" id="ModalUpdateGrade<?=$id_nilai?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-xl modal-fullscreen-md-down">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h1 class="modal-title fs-5" id="exampleModalLabel">Update Input Nilai</h1>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form class="needs-validation" novalidate action="controllers/grade.php" method="POST">
                                <input type="hidden" name="action" value="update">
                                <input type="hidden" name="id" value="<?=$id_nilai?>">
                                <input type="hidden" name="id_user" value="<?=$header['id_user']?>">
                                
                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="form-floating mb-3">
                                            <input type="text" class="form-control" id="nis<?=$id_nilai?>" placeholder="NIS" name="nis" value="<?php echo $row['nis']?>" readonly>
                                            <label for="nis<?=$id_nilai?>">NIS</label>
                                            <div class="invalid-feedback">Masukkan nis.</div>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-floating mb-3">
                                            <input type="text" class="form-control" id="nama<?=$id_nilai?>" placeholder="Nama" name="nama" value="<?php echo $row['nama']?>" readonly>
                                            <label for="nama<?=$id_nilai?>">Nama</label>
                                            <div class="invalid-feedback">Masukkan nama.</div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="form-floating mb-3">
                                            <input type="text" class="form-control" id="kelas<?=$id_nilai?>" placeholder="Kelas" name="kelas" value="<?php echo $row['kelas']?>" readonly>
                                            <label for="kelas<?=$id_nilai?>">Kelas</label>
                                            <div class="invalid-feedback">Masukkan kelas.</div>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-floating mb-3">
                                            <input type="number" min="0" max="100" class="form-control" id="tugas<?=$id_nilai?>" placeholder="Nilai Tugas" name="nilai-tugas" value="<?=$row['tugas']?>" step="0.01">
                                            <label for="tugas<?=$id_nilai?>">Nilai Tugas</label>
                                            <div class="invalid-feedback">Masukkan nilai tugas (0-100).</div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="form-floating mb-3">
                                            <input type="number" min="0" max="100" class="form-control" id="uts<?=$id_nilai?>" placeholder="Nilai UTS" name="nilai-uts" value="<?=$row['uts']?>" step="0.01">
                                            <label for="uts<?=$id_nilai?>">Nilai UTS</label>
                                            <div class="invalid-feedback">Masukkan nilai uts (0-100).</div>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-floating mb-3">
                                            <input type="number" min="0" max="100" class="form-control" id="uas<?=$id_nilai?>" placeholder="Nilai UAS" name="nilai-uas" value="<?=$row['uas']?>" step="0.01">
                                            <label for="uas<?=$id_nilai?>">Nilai UAS</label>
                                            <div class="invalid-feedback">Masukkan nilai uas (0-100).</div>
                                        </div>
                                    </div>
                                </div>

                                <button type="button" class="btn btn-orange mb-3" onclick="hitungNilaiAkhir(<?=$id_nilai?>)">
                                    Hitung Nilai Akhir
                                </button>

                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="form-floating mb-3">
                                            <input type="number" min="0" max="100" class="form-control" id="nilai_akhir<?=$id_nilai?>" name="nilai-akhir" value="<?php echo $row['nilai_akhir']?>" readonly step="0.01">
                                            <label for="nilai_akhir<?=$id_nilai?>">Nilai Akhir</label>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-floating mb-3">
                                            <input type="text" class="form-control" id="status<?=$id_nilai?>" placeholder="Status Kelulusan" name="status-kelulusan" value="<?php echo $row['status_kelulusan']?>" readonly>
                                            <label for="status<?=$id_nilai?>">Status Kelulusan</label>
                                        </div>
                                    </div>
                                </div>

                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                    <button type="submit" class="btn btn-primary" name="input_grade_validate" value="12345">Submit</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Akhir Modal Update Input Nilai -->
            <?php } ?>
            <!-- Informasi Guru -->
            <div class="row mt-3">
                <div class="col-md-4">
                    <div class="form-floating mb-3">
                        <input class="form-control"
                            value="<?=$header['id_guru']?>"
                            readonly>
                        <label>ID Guru</label>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form-floating mb-3">
                        <input class="form-control"
                            value="<?=$header['nama_guru']?>"
                            readonly>
                        <label>Nama Guru</label>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form-floating mb-3">
                        <input class="form-control"
                            value="<?=$header['mata_pelajaran']?>"
                            readonly>
                        <label>Mata Pelajaran</label>
                    </div>
                </div>
            </div>
            <!-- Akhir Informasi Guru -->
            <!-- List Nilai Siswa dari Semua Kelas -->
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
                                <th scope="col">NIS</th>
                                <th scope="col">Nama</th>
                                <th scope="col">Kelas</th>
                                <th scope="col">Nilai Tugas</th>
                                <th scope="col">Nilai UTS</th>
                                <th scope="col">Nilai UAS</th>
                                <th scope="col">Nilai Akhir</th>
                                <th scope="col">Status Kelulusan</th>
                                <th scope="col">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $no = 1;
                            foreach ($detail as $row) {
                            ?>
                                <tr>
                                    <th scope="row"><?php echo $no++ ?></th>
                                    <td><?php echo $row['nis'] ?></td>
                                    <td><?php echo $row['nama'] ?></td>
                                    <td><?php echo $row['kelas'] ?></td>
                                    <td><?php echo $row['tugas'] ?></td>
                                    <td><?php echo $row['uts'] ?></td>
                                    <td><?php echo $row['uas'] ?></td>
                                    <td><?php echo $row['nilai_akhir'] ?></td>
                                    <td><?php echo $row['status_kelulusan'] ?></td>
                                    <td>
                                        <button class="btn btn-warning btn-sm me-1" data-bs-toggle="modal" data-bs-target="#ModalUpdateGrade<?php echo $row['id_nilai']?>"><i class="fa fa-edit"></i></button>
                                    </td>
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
            <!-- Akhir List Nilai Siswa dari Kelas Terpilih -->
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
<script>
function hitungNilaiAkhir(id)
{
    // Menggunakan nilai default 0 jika input kosong
    let tugas = parseFloat(document.getElementById('tugas'+id).value) || 0;
    let uts   = parseFloat(document.getElementById('uts'+id).value) || 0;
    let uas   = parseFloat(document.getElementById('uas'+id).value) || 0;

    // Hitung rumus nilai akhir
    let nilaiAkhir = (tugas * 0.3) + (uts * 0.3) + (uas * 0.4);

    let status = (nilaiAkhir >= 70) ? 'Lulus' : 'Tidak Lulus';

    // Cetak hasil ke komponen input terkait
    document.getElementById('nilai_akhir'+id).value = nilaiAkhir.toFixed(2);
    document.getElementById('status'+id).value = status;
}
</script>