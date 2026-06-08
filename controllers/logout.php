<?php

// Memulai session agar session yang aktif dapat diakses
session_start();

// Menghapus seluruh data session pengguna
session_destroy();

// Mengarahkan pengguna kembali ke halaman login
header("Location: login");

// Menghentikan eksekusi script setelah redirect
exit;

?>