            <div class="col-lg-3">
                <nav class="navbar navbar-expand-lg bg-body-tertiary rounded border mt-2">
                <div class="container-fluid">
                    <button class="navbar-toggler" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasNavbar" aria-controls="offcanvasNavbar" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                    </button>
                    <div class="offcanvas offcanvas-start" tabindex="-1" id="offcanvasNavbar" aria-labelledby="offcanvasNavbarLabel" style="width: 300px">
                    <div class="offcanvas-header">
                        <h5 class="offcanvas-title" id="offcanvasNavbarLabel">Navigation</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
                    </div>
                    <div class="offcanvas-body">
                        <ul class="navbar-nav nav-pills flex-column justify-content-end flex-grow-1">
                        <li class="nav-item">
                            <a class="nav-link ps-2 <?php echo ((isset($_GET['x']) && $_GET['x']=='home') || !isset($_GET['x'])) ? 'active link-light' : 'link-dark'; ?>" aria-current="page" href="home"><i class="fa-solid fa-school"></i></i> Dashboard</a>
                        </li>
                        <?php if ($_SESSION["role"] == "admin") { ?>
                        <li class="nav-item">
                            <a class="nav-link ps-2 <?php echo (isset($_GET['x']) && $_GET['x']=='admins') ? 'active link-light' : 'link-dark'; ?> " href="admins"><i class="fa-solid fa-user-circle"></i> Admin</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link ps-2 <?php echo (isset($_GET['x']) && $_GET['x']=='teachers') ? 'active link-light' : 'link-dark'; ?> " href="teachers"><i class="fa-solid fa-chalkboard-teacher"></i> Guru</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link ps-2 <?php echo (isset($_GET['x']) && $_GET['x']=='students') ? 'active link-light' : 'link-dark'; ?> " href="students"><i class="fa-solid fa-users"></i> Siswa</a>
                        </li>
                        <?php }
                        if ($_SESSION["role"] == "guru") { ?>
                        <li class="nav-item">
                            <a class="nav-link ps-2 <?php echo (isset($_GET['x']) && $_GET['x']=='grade-recap') ? 'active link-light' : 'link-dark'; ?> " href="grade-recap"><i class="fa-solid fa-chart-bar"></i> Rekap Nilai</a>
                        </li>
                        <?php } 
                        if ($_SESSION["role"] == "siswa") { ?>
                        <li class="nav-item">
                            <a class="nav-link ps-2 <?php echo (isset($_GET['x']) && $_GET['x']=='grade-summary') ? 'active link-light' : 'link-dark'; ?> " href="grade-summary"><i class="fa-solid fa-chart-line"></i> Rangkuman Nilai</a>
                        </li>
                        <?php } ?>
                        <!-- <li class="nav-item">
                            <a class="nav-link ps-2 <?php echo (isset($_GET['x']) && $_GET['x']=='user') ? 'active link-light' : 'link-dark'; ?> " href="user"><i class="bi bi-person-fill"></i> User</a>
                        </li> -->
                        </ul>
                    </div>
                    </div>
                </div>
                </nav>
            </div>