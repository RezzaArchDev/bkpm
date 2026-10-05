<?php

$routes = [
    'GET' => [
        '/'                 => ['HomeController', 'index'],
        '/mahasiswa'        => ['MahasiswaController', 'index'],
        '/mahasiswa/create' => ['MahasiswaController', 'create'],

        '/data-mahasiswa'          => ['MahasiswaController', 'tampilStudiKasus'],
        '/data-mahasiswa/angkatan' => ['MahasiswaController', 'tampilTugasMandiri'],

        '/login'     => ['AuthController', 'loginForm'],
        '/logout'    => ['AuthController', 'logout'],
        '/dashboard' => ['DashboardController', 'index'],
    ],
    'POST' => [
        '/mahasiswa' => ['MahasiswaController', 'store'],
        '/login'     => ['AuthController', 'login'],
    ],
];

// Daftar route yang wajib login
$middlewareRoutes = [
    'GET' => ['/dashboard', '/mahasiswa'],
];