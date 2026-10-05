<?php

use App\Core\Middleware\AuthMiddleware;

$routes = [
    'GET' => [
        '/'                 => ['HomeController', 'index'],
        '/login'            => ['AuthController', 'loginForm'],
        '/logout'           => ['AuthController', 'logout'],
        '/dashboard'        => ['DashboardController', 'index',  'middleware' => [AuthMiddleware::class]],
        '/mahasiswa'        => ['MahasiswaController', 'index',  'middleware' => [AuthMiddleware::class]],
        '/mahasiswa/create' => ['MahasiswaController', 'create', 'middleware' => [AuthMiddleware::class]],
    ],
    'POST' => [
        '/login'            => ['AuthController', 'login'],
        '/mahasiswa'        => ['MahasiswaController', 'store',  'middleware' => [AuthMiddleware::class]],
    ],
];