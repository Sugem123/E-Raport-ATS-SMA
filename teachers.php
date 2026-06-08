<?php
include_once "config/Database.php";
require_once "models/Teacher.php";

$db = new Database();
$conn = $db->connect();

$teacher = new Teacher($conn);

$teachers = $teacher->getAll();
?>

<div class="col-lg-9 mt-2">
    <div class="card">
        <div class="card-header">
            Halaman Guru
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col d-flex justify-content-end">
                    <button type="button" class="btn btn-orange" data-bs-toggle="modal" data-bs-target="#ModalInputTeacher">Tambah Guru</button>
                </div>
            </div>
            <!-- Modal Tambah Guru Baru -->
            <div class="modal fade" id="ModalInputTeacher" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-xl modal-fullscreen-md-down">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h1 class="modal-title fs-5" id="exampleModalLabel">Tambah Guru Baru</h1>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form class="needs-validation" novalidate action="controllers\teacher.php" method="POST">
                                <input type="hidden" name="action" value="input">
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="alert alert-info" role="alert">
                                            Password default untuk guru baru adalah <strong>12345</strong>. Mohon untuk segera mereset password setelah guru baru berhasil dibuat.
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="form-floating mb-3">
                                            <input type="text" class="form-control" id="floatingInput" placeholder="Guru ID" name="guru_id" required>
                                            <label for="floatingInput">Guru ID</label>
                                            <div class="invalid-feedback">
                                                Masukkan ID guru.
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="form-floating mb-3">
                                            <input type="text" class="form-control" id="floatingInput" placeholder="Username" name="username" required>
                                            <label for="floatingInput">Username</label>
                                            <div class="invalid-feedback">
                                                Masukkan username.
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="form-floating mb-3">
                                            <input type="text" class="form-control" id="floatingInput" placeholder="Nama Guru" name="nama_guru" required>
                                            <label for="floatingInput">Nama Guru</label>
                                            <div class="invalid-feedback">
                                                Masukkan nama guru.
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="form-floating mb-3">
                                            <input type="text" class="form-control" id="floatingInput" placeholder="Mata Pelajaran" name="mata_pelajaran" required>
                                            <label for="floatingInput">Mata Pelajaran</label>
                                            <div class="invalid-feedback">
                                                Masukkan mata pelajaran.
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <input type="hidden" name="role" value="guru">
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="form-floating mb-3">
                                            <input type="password" class="form-control" id="floatingPassword" placeholder="Password" readonly value="12345" name="pass">
                                            <label for="floatingPassword">Password</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                    <button type="submit" class="btn btn-primary" name="input_teacher_validate" value="12345">Save changes</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Akhir Modal Tambah Guru Baru -->
            <?php
            foreach ($teachers as $row) {
            ?>
            <!-- Modal Edit Guru -->
            <div class="modal fade" id="ModalUpdateTeacher<?php echo $row['id_user']?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-xl modal-fullscreen-md-down">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h1 class="modal-title fs-5" id="exampleModalLabel">Edit Guru</h1>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form class="needs-validation" novalidate action="controllers\teacher.php" method="POST">
                                <input type="hidden" name="action" value="update">
                                <input type="hidden" name="id" value="<?= $row['id_user']; ?>">
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="form-floating mb-3">
                                            <input type="text" class="form-control" id="floatingInput" placeholder="Username" name="username" value="<?php echo $row['username']?>" required>
                                            <label for="floatingInput">Username</label>
                                            <div class="invalid-feedback">
                                                Masukkan username.
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="form-floating mb-3">
                                            <input type="text" class="form-control" id="floatingInput" placeholder="Your Name" name="nama_guru" value="<?php echo $row['nama_guru']?>" required>
                                            <label for="floatingInput">Nama Guru</label>
                                            <div class="invalid-feedback">
                                                Masukkan nama guru.
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="form-floating mb-3">
                                            <input type="text" class="form-control" id="floatingInput" placeholder="Mata Pelajaran" name="mata_pelajaran" value="<?php echo $row['mata_pelajaran']?>" required>
                                            <label for="floatingInput">Mata Pelajaran</label>
                                            <div class="invalid-feedback">
                                                Masukkan mata pelajaran.
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                    <button type="submit" class="btn btn-warning" name="input_teacher_validate" value="12345">Update</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Akhir Modal Edit Guru -->
            <!-- Modal Delete Guru -->
            <div class="modal fade" id="ModalDeleteTeacher<?php echo $row['id_user']?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-xl modal-fullscreen-md-down">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h1 class="modal-title fs-5" id="exampleModalLabel">Hapus Guru</h1>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form class="needs-validation" novalidate action="controllers\teacher.php" method="POST">
                                <input type="hidden" name="action" value="delete">
                                <input type="hidden" name="id" value="<?= $row['id_user']; ?>">
                                <div class="col-lg-12">
                                    <?php if ($row['username'] == $_SESSION['username']) { ?>
                                        <div class="alert alert-danger" role="alert">
                                            Anda tidak dapat menghapus guru <strong><?php echo $row['username']?></strong> karena sedang digunakan untuk login.
                                        </div>
                                    <?php } else { ?>
                                        Apa Anda yakin ingin menghapus guru <strong><?php echo $row['username']?></strong>?
                                    <?php } ?>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                    <button type="submit" class="btn btn-danger" name="input_teacher_validate" value="12345" <?php if ($row['username'] == $_SESSION['username']) { echo "disabled"; } ?>>Delete</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Akhir Modal Delete Guru -->
            <!-- Modal Reset Password -->
            <div class="modal fade" id="ModalResetPass<?php echo $row['id_user']?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-md modal-fullscreen-md-down">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h1 class="modal-title fs-5" id="exampleModalLabel">Reset Password</h1>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form class="needs-validation" novalidate action="controllers/user.php" method="POST">
                                <input type="hidden" name="action" value="reset_password">
                                <input type="hidden" name="id" value="<?= $row['id_user']; ?>">
                                <div class="col-lg-12">
                                    <?php if ($row['username'] == $_SESSION['username']) { ?>
                                        <div class="alert alert-danger" role="alert">
                                            Anda tidak dapat mereset password guru <strong><?php echo $row['username']?></strong> karena sedang digunakan untuk login.
                                        </div>
                                    <?php } else { ?>
                                        Apakah Anda yakin ingin mereset password guru <strong><?php echo $row['username']?></strong> menjadi <strong>12345</strong>?
                                    <?php } ?>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                    <button type="submit" class="btn btn-success" name="input_user_validate" value="12345" <?php if ($row['username'] == $_SESSION['username']) { echo "disabled"; } ?>>Reset</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Akhir Modal Reset Password -->
            <?php
            }
            if (empty($teachers)) {
                echo "Data user tidak ada.";
            } else {
            ?>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th scope="col">No</th>
                                <th scope="col">ID Guru</th>
                                <th scope="col">Username</th>
                                <th scope="col">Nama Guru</th>
                                <th scope="col">Mata Pelajaran</th>
                                <th scope="col">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php
                        $no = 1;
                        foreach ($teachers as $row) {
                        ?>
                            <tr>
                                <td scope="row"><?php echo $no++ ?></th>
                                <td><?php echo $row['id_guru'] ?></td>
                                <td><?php echo $row['username'] ?></td>
                                <td><?php echo $row['nama_guru'] ?></td>
                                <td><?php echo $row['mata_pelajaran'] ?></td>
                                <td>
                                    <div class="d-flex">
                                        <button class="btn btn-info btn-sm me-1" onclick="location.href='teacher-detail?id=<?php echo $row['id_user'] ?>'"><i class="fa fa-eye"></i></button>
                                        <button class="btn btn-warning btn-sm me-1" data-bs-toggle="modal" data-bs-target="#ModalUpdateTeacher<?php echo $row['id_user']?>"><i class="fa fa-edit"></i></button>
                                        <button class="btn btn-danger btn-sm me-1" data-bs-toggle="modal" data-bs-target="#ModalDeleteTeacher<?php echo $row['id_user']?>"><i class="fa fa-trash"></i></button>
                                        <button class="btn btn-secondary btn-sm me-1" data-bs-toggle="modal" data-bs-target="#ModalResetPass<?php echo $row['id_user']?>"><i class="fa fa-key"></i></button>
                                    </div>
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