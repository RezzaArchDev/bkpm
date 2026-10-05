<?php
session_start();   // harus dipanggil sebelum ada output

require_once __DIR__ . '/../app/Models/Mahasiswa.php';
require_once __DIR__ . '/../app/Core/Middleware/AuthMiddleware.php';
require_once __DIR__ . '/../app/Controllers/HomeController.php';
require_once __DIR__ . '/../app/Controllers/AuthController.php';
require_once __DIR__ . '/../app/Controllers/DashboardController.php';
require_once __DIR__ . '/../app/Controllers/MahasiswaController.php';
require_once __DIR__ . '/../routes/web.php';

use App\Core\Middleware\AuthMiddleware;

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

$base = '/bkpm/acara6/si-akademik/public';
if (str_starts_with($uri, $base)) {
    $uri = substr($uri, strlen($base)) ?: '/';
}

$method = $_SERVER['REQUEST_METHOD'];

// 1. Cari route
$route  = $routes[$method][$uri] ?? null;
$params = [];

// Dari Acara 5 (tugas mandiri): parameter URL /mahasiswa/5 -> show($id)
if ($route === null && $method === 'GET' && preg_match('#^/mahasiswa/(\d+)$#', $uri, $cocok)) {
    $route  = ['MahasiswaController', 'show', 'middleware' => [AuthMiddleware::class]];
    $params = [(int) $cocok[1]];
}

// 2. Tidak ketemu -> 404
if ($route === null) {
    http_response_code(404);
    echo "404 - Halaman tidak ditemukan";
    exit;
}

// 3. Jalankan middleware (jika ada) sebelum controller
foreach ($route['middleware'] ?? [] as $mw) {
    $mwInstance = new $mw();
    $mwInstance->handle();
}

// 4. Panggil controller
$controllerClass = "App\\Controllers\\{$route[0]}";
$controller = new $controllerClass();
$controller->{$route[1]}(...$params);