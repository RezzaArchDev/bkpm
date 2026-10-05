<?php
require_once __DIR__ . '/../app/Models/Mahasiswa.php';
require_once __DIR__ . '/../app/Controllers/HomeController.php';
require_once __DIR__ . '/../app/Controllers/MahasiswaController.php';
require_once __DIR__ . '/../routes/web.php';

use App\Controllers\MahasiswaController;

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

$base = '/acara5/si-akademik/public';
if (str_starts_with($uri, $base)) {
    $uri = substr($uri, strlen($base)) ?: '/';
}

$method = $_SERVER['REQUEST_METHOD'];

if (isset($routes[$method][$uri])) {
    [$controllerName, $action] = $routes[$method][$uri];
    $controllerClass = "App\\Controllers\\{$controllerName}";
    $controller = new $controllerClass();
    $controller->$action();

// ===== TUGAS MANDIRI: parameter URL /mahasiswa/5 -> show($id) =====
} elseif ($method === 'GET' && preg_match('#^/mahasiswa/(\d+)$#', $uri, $cocok)) {
    $controller = new MahasiswaController();
    $controller->show((int) $cocok[1]);
// ===== AKHIR TUGAS MANDIRI =====

} else {
    http_response_code(404);
    echo "404 - Halaman tidak ditemukan";
}