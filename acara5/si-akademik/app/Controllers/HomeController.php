<?php

namespace App\Controllers;

class HomeController
{
    public function index(): void
    {
        echo "<h1>Selamat datang di SI Akademik</h1>";
        echo "<p><a href='/bkpm/acara5/si-akademik/public/mahasiswa'>Lihat Data Mahasiswa</a></p>";
    }
}