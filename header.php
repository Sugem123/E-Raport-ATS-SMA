    <nav class="navbar navbar-expand bg-orange sticky-top" data-bs-theme="dark">
        <div class="container-lg">
            <a class="navbar-brand" href="."><img src="assets/images/rapor.png" alt="e-Report" width="30" height="30" class="d-inline-block align-text-top"> e-Report SMAN 67 Tangerang</a>
            <div class="collapse navbar-collapse justify-content-end" id="navbarNavDropdown">
                <ul class="navbar-nav">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <?php echo $_SESSION['nama']; ?>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end mt-2">
                            <li><a class="dropdown-item" data-bs-toggle="modal" data-bs-target="#ModalChangePass" href="#"><i class="fa-solid fa-key"></i> Change Password</a></li>
                            <li><a class="dropdown-item" href="logout"><i class="fa-solid fa-sign-out-alt"></i> Logout</a></li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Modal Change Password -->
    <div class="modal fade" id="ModalChangePass" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-fullscreen-md-down">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Change Password</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form class="needs-validation" novalidate action="controllers/user.php" method="POST">
                        <input type="hidden" name="action" value="change_password">
                        <input type="hidden" name="user" value="<?= $_SESSION['username'] ?>">
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-floating mb-3">
                                    <input type="password" class="form-control" id="floatingPassword" placeholder="Your Password" name="oldpass" required>
                                    <label for="floatingInput">Current Password</label>
                                    <div class="invalid-feedback">
                                        Masukkan password lama.
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-floating mb-3">
                                    <input type="password" class="form-control" id="floatingPassword" placeholder="Your New Password" name="newpass" required>
                                    <label for="floatingInput">New Password</label>
                                    <div class="invalid-feedback">
                                        Masukkan password baru.
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-floating mb-3">
                                    <input type="password" class="form-control" id="floatingPassword" placeholder="Confirm New Password" name="confirmnewpass" required>
                                    <label for="floatingInput">Confirm New Password</label>
                                    <div class="invalid-feedback">
                                        Konfirmasi password baru.
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary" name="input_user_validate" value="12345">Update</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- Akhir Modal Edit User -->
     
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