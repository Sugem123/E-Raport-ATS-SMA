<?php
session_start();

require_once __DIR__ . "/../config/Database.php";
require_once __DIR__ . "/../models/User.php";

$db = new Database();
$conn = $db->connect();
$userModel = new User($conn);

if (empty($_POST['submit_validate'])) {
    die("<script>alert('Akses tidak valid'); window.location='../login';</script>");
}

$username = trim($_POST['username'] ?? '');
$password = $_POST['pass'] ?? '';
$role     = trim($_POST['role'] ?? '');

$result = $userModel->login($username, $password, $role);

if (!$result['success']) {
    echo "<script>
        alert('Username, password, atau role salah.');
        window.location='../login';
    </script>";
    exit;
}

$user = $result['data'];

$_SESSION["username"]   = $user['username'];
$_SESSION["role"]       = $user['role'];
$_SESSION["id"]         = $user['id_role'];
$_SESSION["nama"]       = $user['nama'];
$_SESSION["id_kelas"]   = $user['id_kelas'];
$_SESSION["nama_kelas"] = $user['nama_kelas'];

header("Location: ../home");
exit;
