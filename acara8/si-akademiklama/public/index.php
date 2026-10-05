<?php
session_start();

require_once __DIR__ . '/../routes/web.php';

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

$base = '/acara8/si-akademik/public';
if (str_starts_with($uri, $base)) {
    $uri = substr($uri, strlen($base)) ?: '/';
}

$method = $_SERVER['REQUEST_METHOD'];

if (isset($routes[$method][$uri])) {

    if (in_array($uri, $middlewareRoutes[$method] ?? [], true)) {
        require_once __DIR__ . '/../app/Core/Middleware/AuthMiddleware.php';
        $mw = new \App\Core\Middleware\AuthMiddleware();
        $mw->handle($base);
    }

    [$controllerName, $action] = $routes[$method][$uri];
    $controllerClass = "App\\Controllers\\{$controllerName}";
    require_once __DIR__ . "/../app/Controllers/{$controllerName}.php";
    $controller = new $controllerClass();
    $controller->$action();
    exit;
}

// ===== STUDI KASUS (ACARA 8): route dinamis untuk edit/update/delete =====
if ($method === 'GET' && preg_match('#^/mahasiswa/(\d+)/edit$#', $uri, $m)) {
    require_once __DIR__ . '/../app/Controllers/MahasiswaController.php';
    (new \App\Controllers\MahasiswaController())->edit($m[1]);
    exit;
}

if ($method === 'POST' && preg_match('#^/mahasiswa/(\d+)/update$#', $uri, $m)) {
    require_once __DIR__ . '/../app/Controllers/MahasiswaController.php';
    (new \App\Controllers\MahasiswaController())->update($m[1]);
    exit;
}

if ($method === 'POST' && preg_match('#^/mahasiswa/(\d+)/delete$#', $uri, $m)) {
    require_once __DIR__ . '/../app/Controllers/MahasiswaController.php';
    (new \App\Controllers\MahasiswaController())->destroy($m[1]);
    exit;
}
// ===== AKHIR STUDI KASUS (ACARA 8) =====

if ($method === 'GET' && preg_match('#^/mahasiswa/(\d+)$#', $uri, $matches)) {
    $id = $matches[1];
    require_once __DIR__ . '/../app/Controllers/MahasiswaController.php';
    $controller = new \App\Controllers\MahasiswaController();
    $controller->show($id);
    exit;
}

http_response_code(404);
echo "404 - Halaman tidak ditemukan";
