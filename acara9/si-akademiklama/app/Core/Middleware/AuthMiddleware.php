<?php

namespace App\Core\Middleware;

class AuthMiddleware
{
    public function handle(string $base = ''): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (empty($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
            header('Location: ' . $base . '/login');
            exit;
        }
    }
}