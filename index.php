            <?php
            session_start(); 
            if (isset($_GET['x']) && $_GET['x']=='home') {
                $page = "home.php";
                include "main.php";
            } else if (isset($_GET['x']) && $_GET['x']=='admins') {
                if ($_SESSION["role"] == "admin") {
                    $page = "admins.php";
                    include "main.php";
                } else {
                    $page = "home.php";
                    include "main.php";
                }
            } else if (isset($_GET['x']) && $_GET['x']=='teachers') {
                if ($_SESSION["role"] == "admin") {
                    $page = "teachers.php";
                    include "main.php";
                } else {
                    $page = "home.php";
                    include "main.php";
                }
            } else if (isset($_GET['x']) && $_GET['x']=='students') {
                if ($_SESSION["role"] == "admin") {
                    $page = "students.php";
                    include "main.php";
                } else {
                    $page = "home.php";
                    include "main.php";
                }
            } else if (isset($_GET['x']) && $_GET['x']=='student-detail') {
                if ($_SESSION["role"] == "admin") {
                    $page = "student-detail.php";
                    include "main.php";
                } else {
                    $page = "home.php";
                    include "main.php";
                }
            } else if (isset($_GET['x']) && $_GET['x']=='teacher-detail') {
                if ($_SESSION["role"] == "admin") {
                    $page = "teacher-detail.php";
                    include "main.php";
                } else {
                    $page = "home.php";
                    include "main.php";
                }
            } else if (isset($_GET['x']) && $_GET['x']=='grade-recap') {
                if ($_SESSION["role"] == "guru") {
                    $page = "grade-recap.php";
                    include "main.php";
                } else {
                    $page = "home.php";
                    include "main.php";
                }
            } else if (isset($_GET['x']) && $_GET['x']=='grade-summary') {
                if ($_SESSION["role"] == "siswa") {
                    $page = "grade-summary.php";
                    include "main.php";
                } else {
                    $page = "home.php";
                    include "main.php";
                }
            } else if (isset($_GET['x']) && $_GET['x']=='login') {
                include "login.php";
            } else if (isset($_GET['x']) && $_GET['x']=='logout') {
                include "controllers/logout.php";
            } else {
                $page = "home.php";
                include "main.php";
            }
            ?>