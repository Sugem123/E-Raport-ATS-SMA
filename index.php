<?php
session_start();

$x = $_GET['x'] ?? '';

// Default ke Landing Page untuk pengunjung publik (belum login)
// atau ke Home Dashboard jika sudah terautentikasi
if (empty($x)) {
    if (!empty($_SESSION['username'])) {
        $x = 'home';
    } else {
        include "landing.php";
        exit;
    }
}

switch ($x) {
    case 'landing':
        include "landing.php";
        break;

    case 'login':
        include "login.php";
        break;

    case 'logout':
        include "controllers/logout.php";
        break;

    case 'home':
        $page = "home.php";
        include "main.php";
        break;

    case 'admins':
        if (($_SESSION["role"] ?? '') === "admin") {
            $page = "admins.php";
        } else {
            $page = "home.php";
        }
        include "main.php";
        break;

    case 'classes':
        if (($_SESSION["role"] ?? '') === "admin") {
            $page = "classes.php";
        } else {
            $page = "home.php";
        }
        include "main.php";
        break;

    case 'subjects':
        if (($_SESSION["role"] ?? '') === "admin") {
            $page = "subjects.php";
        } else {
            $page = "home.php";
        }
        include "main.php";
        break;

    case 'subject-mapping':
        if (($_SESSION["role"] ?? '') === "admin") {
            $page = "subject-mapping.php";
        } else {
            $page = "home.php";
        }
        include "main.php";
        break;

    case 'teachers':
        if (($_SESSION["role"] ?? '') === "admin") {
            $page = "teachers.php";
        } else {
            $page = "home.php";
        }
        include "main.php";
        break;

    case 'assignments':
        if (($_SESSION["role"] ?? '') === "admin") {
            $page = "assignments.php";
        } else {
            $page = "home.php";
        }
        include "main.php";
        break;

    case 'students':
        if (($_SESSION["role"] ?? '') === "admin") {
            $page = "students.php";
        } else {
            $page = "home.php";
        }
        include "main.php";
        break;

    case 'school-profile':
        if (($_SESSION["role"] ?? '') === "admin") {
            $page = "school-profile.php";
        } else {
            $page = "home.php";
        }
        include "main.php";
        break;

    case 'settings':
        if (($_SESSION["role"] ?? '') === "admin") {
            $page = "settings.php";
        } else {
            $page = "home.php";
        }
        include "main.php";
        break;

    case 'weights':
        if (($_SESSION["role"] ?? '') === "admin") {
            $page = "weights.php";
        } else {
            $page = "home.php";
        }
        include "main.php";
        break;

    case 'student-detail':
        if (($_SESSION["role"] ?? '') === "admin") {
            $page = "student-detail.php";
        } else {
            $page = "home.php";
        }
        include "main.php";
        break;

    case 'grade-recap':
        if (($_SESSION["role"] ?? '') === "guru") {
            $page = "grade-recap.php";
        } else {
            $page = "home.php";
        }
        include "main.php";
        break;

    case 'homeroom':
        if (in_array($_SESSION["role"] ?? '', ['walikelas', 'admin'])) {
            $page = "homeroom.php";
        } else {
            $page = "home.php";
        }
        include "main.php";
        break;

    case 'grade-monitor':
        if (in_array($_SESSION["role"] ?? '', ['admin', 'walikelas'])) {
            $page = "grade-monitor.php";
        } else {
            $page = "home.php";
        }
        include "main.php";
        break;

    case 'bulk-print':
        if (in_array($_SESSION["role"] ?? '', ['admin', 'walikelas'])) {
            $page = "bulk-print.php";
        } else {
            $page = "home.php";
        }
        include "main.php";
        break;

    case 'attendance':
        if (in_array($_SESSION["role"] ?? '', ['admin', 'walikelas', 'guru'])) {
            $page = "attendance.php";
        } else {
            $page = "home.php";
        }
        include "main.php";
        break;

    case 'grade-summary':
        if (($_SESSION["role"] ?? '') === "siswa") {
            $page = "grade-summary.php";
        } else {
            $page = "home.php";
        }
        include "main.php";
        break;

    default:
        $page = "home.php";
        include "main.php";
        break;
}
