<?php
// routes/web.php
$routes = [
    'GET' => [
        '/'                 => ['HomeController', 'index'],
        '/mahasiswa'        => ['MahasiswaController', 'index'],
        '/mahasiswa/create' => ['MahasiswaController', 'create'],
        '/data-mahasiswa'   => ['MahasiswaController', 'tampilTabel'], // ===== ACARA 4 digabung ke sini =====
        '/login'            => ['AuthController', 'loginForm'],
    ],
    'POST' => [
        '/mahasiswa' => ['MahasiswaController', 'store'],
        '/login'     => ['AuthController', 'login'],
    ],
];