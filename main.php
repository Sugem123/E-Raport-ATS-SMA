<?php
    if(empty($_SESSION['username'])) {
        header('location:login');
    }

    include_once "config/Database.php";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>e-Raport SMAN 67 Tangerang</title>
    <link rel="stylesheet" href="../vendor/bootstrap-5.3.8/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css">
    <link rel="stylesheet" href="https://unpkg.com">
    <link rel="stylesheet" href="assets/css/style.css">
    <script src="https://unpkg.com"></script>
    <script src="/assets/js/main.js"></script>
</head>
<body>
    <!-- HEADER -->
    <?php include "header.php" ?>
    <!-- END HEADER -->
    <!-- SIDEBAR / CONTENT -->
     <div class="container-lg">
        <div class="row">
            <!-- SIDEBAR -->
            <?php include "sidebar.php"?>
            <!-- END SIDEBAR -->
            <!-- CONTENT -->
            <?php 
            include $page;
            ?>
            <!-- END CONTENT -->
        </div>
        <!-- END SIDEBAR/CONTENT -->
         <div class="bg-body-tertiary text-center text-lg-start mt-4">
            <div class="text-center p-3" style="background-color: rgba(0, 0, 0, 0.05);">
    © 2026 Copyright:
                <a class="text-body" href="#">e-Raport SMAN 67 Tangerang</a>
            </div>
         </div>
     </div>
    <script src="../vendor/bootstrap-5.3.8/js/bootstrap.bundle.min.js"></script>
</body>
</html>