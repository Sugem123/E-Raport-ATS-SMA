<?php

// Auto-detect base URL agar fleksibel baik di Virtual Host (Laragon), Subfolder XAMPP, maupun PHP Built-in Server
$scriptDir = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'] ?? ''));
$base_url = rtrim($scriptDir, '/') . '/';
if ($base_url === '//' || $base_url === '') {
    $base_url = '/';
}

?>