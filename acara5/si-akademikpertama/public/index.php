<?php
// public/index.php
require_once __DIR__ . '/../routes/web.php';

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

$base = '/acara5/si-akademik/public';
if (str_starts_with($uri, $base)) {
    $uri = substr($uri, strlen($base)) ?: '/';
}

$method = $_SERVER['REQUEST_METHOD'];

// ===== STUDI KASUS: cocokkan URL persis (exact match) ke daftar $routes =====
if (isset($routes[$method][$uri])) {
    [$controllerName, $action] = $routes[$method][$uri];
    $controllerClass = "App\\Controllers\\{$controllerName}";
    require_once __DIR__ . "/../app/Controllers/{$controllerName}.php";
    $controller = new $controllerClass();
    $controller->$action();
    exit;
}
// ===== AKHIR STUDI KASUS =====

if ($method === 'GET' && preg_match('#^/mahasiswa/(\d+)$#', $uri, $matches)) {
    $id = $matches[1];

    require_once __DIR__ . '/../app/Controllers/MahasiswaController.php';
    $controller = new \App\Controllers\MahasiswaController();
    $controller->show($id);
    exit;
}

http_response_code(404);
echo "404 - Halaman tidak ditemukan";