<?php

$routes = [
    'GET' => [
        '/'                 => ['HomeController', 'index'],
        '/mahasiswa'        => ['MahasiswaController', 'index'],
        '/mahasiswa/create' => ['MahasiswaController', 'create'],
        '/login'            => ['AuthController', 'loginForm'],
        '/logout'           => ['AuthController', 'logout'],
        '/dashboard'        => ['DashboardController', 'index'],
    ],
    'POST' => [
        '/mahasiswa' => ['MahasiswaController', 'store'],
        '/login'     => ['AuthController', 'login'],
    ],
];

$middlewareRoutes = [
    'GET' => ['/dashboard', '/mahasiswa'],
];