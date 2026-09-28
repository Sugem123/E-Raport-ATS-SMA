<?php
// router.php untuk PHP Built-in Server (php -S localhost:8000 router.php)

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$filePath = __DIR__ . $uri;

// 1. Jika mengakses file fisik yang ada (CSS, JS, gambar, font, dsb)
if ($uri !== '/' && file_exists($filePath)) {
    // Jika file PHP langsung diakses (misal controllers/*.php atau report)
    if (pathinfo($filePath, PATHINFO_EXTENSION) === 'php') {
        chdir(dirname($filePath));
        require $filePath;
        return true;
    }
    // File statis biasa: serahkan ke built-in server
    return false;
}

// 2. Routing clean URL (misal: /home, /login, /admins, /teachers, dsb)
$route = trim($uri, '/');
if (!empty($route)) {
    $_GET['x'] = $route;
}

require __DIR__ . '/index.php';
