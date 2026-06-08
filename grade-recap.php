<?php
include_once "config/Database.php";
include_once "models/Teacher.php";

$db = new Database();
$conn = $db->connect();

$teacher = new Teacher($conn);

$idGuru = $_SESSION['id'];

$header = $teacher->getById($idGuru);

$detail = $teacher->getGrades($idGuru);
?>

<div class="col-lg-9 mt-2">
    <div class="card">
        <div class="card-header">
            Halaman List Nilai Siswa yang Diampu Guru
        </div>
        <div class="card-body">
            <!-- Modal Input Nilai -->
            <div class="modal fade" id="ModalInputGrade" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-xl modal-fullscreen-md-down">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h1 class="modal-title fs-5" id="exampleModalLabel">Input Nilai</h1>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form class="needs-validation" novalidate action="controllers/grade.php" method="POST">
                                <input type="hidden" name="action" value="input">
                                <input type="hidden" name="id" value="<?= $idGuru ?>">
                                
                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="form-floating mb-3">
                                            <input type="text" class="form-control" id="nis" placeholder="NIS" name="nis" required>
                                            <label for="nis">NIS</label>
                                            <div class="invalid-feedback">Masukkan nis.</div>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-floating mb-3">
                                            <input type="number" min="0" max="100" class="form-control" id="tugas" placeholder="Nilai Tugas" name="nilai-tugas" step="0.01" required>
                                            <label for="tugas">Nilai Tugas</label>
                                            <div class="invalid-feedback">Masukkan nilai tugas (0-100).</div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="form-floating mb-3">
                                            <input type="number" min="0" max="100" class="form-control" id="uts" placeholder="Nilai UTS" name="nilai-uts" step="0.01" required>
                                            <label for="uts">Nilai UTS</label>
                                            <div class="invalid-feedback">Masukkan nilai uts (0-100).</div>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-floating mb-3">
                                            <input type="number" min="0" max="100" class="form-control" id="uas" placeholder="Nilai UAS" name="nilai-uas" step="0.01" required>
                                            <label for="uas">Nilai UAS</label>
                                            <div class="invalid-feedback">Masukkan nilai uas (0-100).</div>
                                        </div>
                                    </div>
                                </div>

                                <button type="button" class="btn btn-orange mb-3" onclick="hitungNilaiAkhir()">
                                    Hitung Nilai Akhir
                                </button>

                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="form-floating mb-3">
                                            <input type="number" min="0" max="100" class="form-control" id="nilai_akhir" name="nilai-akhir" readonly step="0.01" required>
                                            <label for="nilai_akhir">Nilai Akhir</label>
                                            <div class="invalid-feedback">Silakan hitung nilai akhir dahulu.</div>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-floating mb-3">
                                            <input type="text" class="form-control" id="status" placeholder="Status Kelulusan" name="status-kelulusan" readonly required>
                                            <label for="status">Status Kelulusan</label>
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
            <!-- Akhir Modal Input Nilai -->
            <?php
            foreach ($detail as $row) {
            ?>
            
            <!-- Modal Hapus Nilai -->
            <div class="modal fade" id="ModalDeleteGrade<?php echo $row['id_nilai']?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-xl modal-fullscreen-md-down">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h1 class="modal-title fs-5" id="exampleModalLabel">Hapus Nilai</h1>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form class="needs-validation" novalidate action="controllers/grade.php" method="POST">
                                <input type="hidden" name="action" value="delete">
                                <input type="hidden" name="id" value="<?= $row['id_nilai']; ?>">
                                <div class="col-lg-12">
                                    Apa Anda yakin ingin menghapus nilai dari Siswa <strong><?php echo $row['nama']?></strong>?
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                    <button type="submit" class="btn btn-danger" name="input_grade_validate" value="12345">Delete</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Akhir Modal Hapus Nilai -->
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
            <!-- Tombol Input Nilai -->
            <div class="row">
                <div class="col d-flex justify-content-end">
                    <button type="button" class="btn btn-orange" data-bs-toggle="modal" data-bs-target="#ModalInputGrade">Input Nilai Siswa</button>
                </div>
            </div>
            <!-- Akhir Tombol Input Nilai -->
            <!-- List Nilai Siswa dari Semua Kelas -->
            <h5 class="mt-4">List Nilai Siswa</h5>
            <?php
            if (empty($detail)) {
                echo "<div class='alert alert-warning mt-3'>Data detail nilai siswa belum ada.</div>";
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
                                        <button class="btn btn-danger btn-sm me-1" data-bs-toggle="modal" data-bs-target="#ModalDeleteGrade<?php echo $row['id_nilai']?>"><i class="fa fa-trash"></i></button>
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
            <!-- Akhir List Nilai Siswa dari Semua Kelas -->
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
function hitungNilaiAkhir() {
    let tugas = parseFloat(document.getElementById("tugas").value) || 0;
    let uts   = parseFloat(document.getElementById("uts").value) || 0;
    let uas   = parseFloat(document.getElementById("uas").value) || 0;

    let nilaiAkhir = (tugas * 0.3) + (uts * 0.3) + (uas * 0.4);
    document.getElementById("nilai_akhir").value = nilaiAkhir.toFixed(2);

    let status = nilaiAkhir >= 70 ? "Lulus" : "Tidak Lulus";
    document.getElementById("status").value = status;
}
</script>