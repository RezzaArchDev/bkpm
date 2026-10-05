<?php

namespace App\Controllers;

class DashboardController
{
    public function index(): void
    {
        $content = __DIR__ . '/../Views/dashboard/index.php';
        require __DIR__ . '/../Views/layouts/main.php';
    }
}