<?php

namespace App\Controllers;

class DashboardController
{
    public function index(): void
    {
        global $base;
        if (session_status() === PHP_SESSION_NONE) session_start();
        require __DIR__ . '/../Views/dashboard/index.php';
    }
}