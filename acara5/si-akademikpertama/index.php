<?php
require_once __DIR__ . '/app/Models/Mahasiswa.php';

use App\Models\Mahasiswa;

$daftarMahasiswa = [
    new Mahasiswa("2401001", "Axel Wangsa", "Teknik Informatika"),
    new Mahasiswa("2402001", "Yogian Eriya", "Sistem Informasi"),
    new Mahasiswa("2501078", "Rezza Hebat", "Teknik Informatika"),
];

$content = __DIR__ . '/app/Views/mahasiswa/index.php';
require __DIR__ . '/app/Views/layouts/main.php';