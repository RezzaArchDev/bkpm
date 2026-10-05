<?php
session_start();

require_once __DIR__ . '/../routes/web.php';

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

$base = '/acara6/si-akademik/public';   // sesuaikan dengan folder project kamu
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

http_response_code(404);
echo "404 - Halaman tidak ditemukan";