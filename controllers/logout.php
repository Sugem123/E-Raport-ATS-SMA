<?php

// Memulai session jika belum aktif
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Mengosongkan data session
$_SESSION = [];

// Menghapus cookie session jika ada
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(
        session_name(),
        '',
        time() - 42000,
        $params["path"],
        $params["domain"],
        $params["secure"],
        $params["httponly"]
    );
}

// Menghancurkan session
session_destroy();

// Mengarahkan pengguna kembali ke halaman login
header("Location: login");
exit;
