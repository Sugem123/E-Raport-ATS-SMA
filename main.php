<?php
require_once 'config/Config.php';

if (empty($_SESSION['username'])) {
    header('location:login');
    exit;
}

include_once "config/Database.php";
require_once "models/Setting.php";

$db = new Database();
$conn = $db->connect();
$settingModelMain = new Setting($conn);
$school = $settingModelMain->get();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($school['nama_sekolah']) ?> &mdash; e-Raport STS</title>

    <!-- Google Fonts: Plus Jakarta Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Space+Grotesk:wght@600;700&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="<?= $base_url ?>vendor/bootstrap-5.3.8/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="<?= $base_url ?>assets/css/style.css">
    <script src="<?= $base_url ?>assets/js/main.js"></script>
</head>
<body>
    <!-- HEADER -->
    <?php include "header.php"; ?>
    <!-- END HEADER -->

    <!-- SIDEBAR / CONTENT -->
    <div class="container-lg my-3">
        <div class="row">
            <!-- SIDEBAR -->
            <?php include "sidebar.php"; ?>
            <!-- END SIDEBAR -->

            <!-- CONTENT -->
            <?php include $page; ?>
            <!-- END CONTENT -->
        </div>

        <!-- FOOTER -->
        <footer class="app-footer text-center">
            <div class="container">
                &copy; <?= date('Y') ?> <strong><?= htmlspecialchars($school['nama_sekolah']) ?></strong> &bull; Sistem Informasi e-Raport STS Kurikulum Merdeka.
            </div>
        </footer>
    </div>

    <script src="<?= $base_url ?>vendor/bootstrap-5.3.8/js/bootstrap.bundle.min.js"></script>
</body>
</html>
