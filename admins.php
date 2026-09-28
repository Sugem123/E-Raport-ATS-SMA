<?php
include_once "config/Database.php";
require_once "models/Admin.php";

$db = new Database();
$conn = $db->connect();

$admin = new Admin($conn);

// Untuk mendapatkan data admin
$admins = $admin->getAll();
?>
<div class="col-lg-9 mt-2">
    <div class="card">
        <div class="card-header">
            Halaman Admin
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col d-flex justify-content-end">
                    <button type="button" class="btn btn-orange" data-bs-toggle="modal" data-bs-target="#ModalInputAdmin">Tambah Admin</button>
                </div>
            </div>
            <!-- Modal Tambah Admin Baru -->
            <div class="modal fade" id="ModalInputAdmin" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-xl modal-fullscreen-md-down">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h1 class="modal-title fs-5" id="exampleModalLabel">Tambah Admin Baru</h1>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form class="needs-validation" novalidate action="controllers/admin.php" method="POST">
                                <input type="hidden" name="action" value="input">
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="alert alert-info" role="alert">
                                            Password default untuk admin baru adalah <strong>12345</strong>. Mohon untuk segera mereset password setelah admin baru berhasil dibuat.
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="form-floating mb-3">
                                            <input type="text" class="form-control" id="floatingInput" placeholder="Admin ID" name="admin_id" required>
                                            <label for="floatingInput">Admin ID</label>
                                            <div class="invalid-feedback">
                                                Masukkan ID admin.
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
                                <input type="hidden" name="role" value="admin">
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
                                    <button type="submit" class="btn btn-primary" name="input_admin_validate" value="12345">Save changes</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Akhir Modal Tambah Admin Baru -->
            <?php
            foreach ($admins as $row) {
            ?>
            <!-- Modal View -->
            <div class="modal fade" id="ModalView<?php echo $row['id_user']?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-xl modal-fullscreen-md-down">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h1 class="modal-title fs-5" id="exampleModalLabel">Data Admin</h1>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form class="needs-validation" novalidate>
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="form-floating mb-3">
                                            <input type="text" class="form-control" id="floatingInput" value="<?php echo $row['id_admin']; ?>" name="admin_id" readonly>
                                            <label for="floatingInput">Admin ID</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="form-floating mb-3">
                                            <input type="text" class="form-control" id="floatingInput" value="<?php echo $row['username']; ?>" name="username" readonly>
                                            <label for="floatingInput">Username</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Akhir Modal View -->
            <!-- Modal Edit Admin -->
            <div class="modal fade" id="ModalUpdateAdmin<?php echo $row['id_user']?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-xl modal-fullscreen-md-down">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h1 class="modal-title fs-5" id="exampleModalLabel">Edit Admin</h1>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form class="needs-validation" novalidate action="controllers/admin.php" method="POST">
                                <input type="hidden" name="action" value="update">
                                <input type="hidden" name="id" value="<?= $row['id_user']; ?>">
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="form-floating mb-3">
                                            <input type="text" class="form-control" id="floatingInput" placeholder="Your Name" name="username" value="<?php echo $row['username']?>" required>
                                            <label for="floatingInput">Username</label>
                                            <div class="invalid-feedback">
                                                Masukkan username.
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                    <button type="submit" class="btn btn-warning" name="input_admin_validate" value="12345">Update</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Akhir Modal Edit Admin -->
            <!-- Modal Delete Admin -->
            <div class="modal fade" id="ModalDeleteAdmin<?php echo $row['id_user']?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-xl modal-fullscreen-md-down">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h1 class="modal-title fs-5" id="exampleModalLabel">Delete Admin</h1>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form class="needs-validation" novalidate action="controllers/admin.php" method="POST">
                                <input type="hidden" name="action" value="delete">
                                <input type="hidden" name="id" value="<?= $row['id_user']; ?>">
                                <div class="col-lg-12">
                                    <?php if ($row['username'] == $_SESSION['username']) { ?>
                                        <div class="alert alert-danger" role="alert">
                                            Anda tidak dapat menghapus admin <strong><?php echo $row['username']?></strong> karena sedang digunakan untuk login.
                                        </div>
                                    <?php } else { ?>
                                        Apa Anda yakin ingin menghapus admin <strong><?php echo $row['username']?></strong>?
                                    <?php } ?>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                    <button type="submit" class="btn btn-danger" name="input_admin_validate" value="12345" <?php if ($row['username'] == $_SESSION['username']) { echo "disabled"; } ?>>Delete</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Akhir Modal Delete Admin -->
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
                                            Anda tidak dapat mereset password admin <strong><?php echo $row['username']?></strong> karena sedang digunakan untuk login.
                                        </div>
                                    <?php } else { ?>
                                        Apakah Anda yakin ingin mereset password admin <strong><?php echo $row['username']?></strong> menjadi <strong>12345</strong>?
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
            if (empty($admins)) {
                echo "Data user tidak ada.";
            } else {
            ?>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th scope="col">No</th>
                                <th scope="col">ID Admin</th>
                                <th scope="col">Username</th>
                                <th scope="col">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $no = 1;
                            foreach ($admins as $row) {
                            ?>
                                <tr>
                                    <th scope="row"><?php echo $no++ ?></th>
                                    <td><?php echo $row['id_admin'] ?></td>
                                    <td><?php echo $row['username'] ?></td>
                                    <td>
                                        <div class="d-flex">
                                            <button class="btn btn-info btn-sm me-1" data-bs-toggle="modal" data-bs-target="#ModalView<?php echo $row['id_user']?>"><i class="fa fa-eye"></i></button>
                                            <button class="btn btn-warning btn-sm me-1" data-bs-toggle="modal" data-bs-target="#ModalUpdateAdmin<?php echo $row['id_user']?>"><i class="fa fa-edit"></i></button>
                                            <button class="btn btn-danger btn-sm me-1" data-bs-toggle="modal" data-bs-target="#ModalDeleteAdmin<?php echo $row['id_user']?>"><i class="fa fa-trash"></i></button>
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