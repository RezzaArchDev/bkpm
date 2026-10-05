<?php
session_start();   // harus dipanggil sebelum ada output

require_once __DIR__ . '/../app/Core/Database.php';
require_once __DIR__ . '/../app/Core/Model.php';
require_once __DIR__ . '/../app/Core/Middleware/AuthMiddleware.php';
require_once __DIR__ . '/../app/Models/MahasiswaModel.php';
require_once __DIR__ . '/../app/Models/ProdiModel.php';
require_once __DIR__ . '/../app/Models/MatakuliahModel.php';
require_once __DIR__ . '/../app/Controllers/HomeController.php';
require_once __DIR__ . '/../app/Controllers/AuthController.php';
require_once __DIR__ . '/../app/Controllers/DashboardController.php';
require_once __DIR__ . '/../app/Controllers/MahasiswaController.php';
require_once __DIR__ . '/../app/Controllers/ProdiController.php';
require_once __DIR__ . '/../app/Controllers/MatakuliahController.php';
require_once __DIR__ . '/../routes/web.php';

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

$base = '/acara8/si-akademik/public';
if (str_starts_with($uri, $base)) {
    $uri = substr($uri, strlen($base)) ?: '/';
}

$method = $_SERVER['REQUEST_METHOD'];

// 1. Cari route yang cocok persis
$route  = $routes[$method][$uri] ?? null;
$params = [];

// 2. Kalau tidak ada, cari route berparameter, misal /mahasiswa/5/edit
if ($route === null) {
    foreach ($routes[$method] ?? [] as $pattern => $r) {
        if (!str_contains($pattern, '{')) {
            continue;
        }
        $regex = '#^' . preg_replace('#\{\w+\}#', '(\d+)', $pattern) . '$#';
        if (preg_match($regex, $uri, $cocok)) {
            array_shift($cocok);
            $route  = $r;
            $params = array_map('intval', $cocok);
            break;
        }
    }
}

// 3. Tidak ketemu -> 404
if ($route === null) {
    http_response_code(404);
    echo "404 - Halaman tidak ditemukan";
    exit;
}

// 4. Jalankan middleware (jika ada)
foreach ($route['middleware'] ?? [] as $mw) {
    $mwInstance = new $mw();
    $mwInstance->handle();
}

// 5. Panggil controller
$controllerClass = "App\\Controllers\\{$route[0]}";
$controller = new $controllerClass();
$controller->{$route[1]}(...$params);