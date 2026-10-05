<?php

namespace App\Controllers;

class AuthController
{
    // Hardcode username/password dulu (nanti diganti database)
    private const USERNAME = 'admin';
    private const PASSWORD = 'admin123';

    public function loginForm(): void
    {
        global $base;
        if (session_status() === PHP_SESSION_NONE) session_start();
        $error = null;
        require __DIR__ . '/../Views/auth/login.php';
    }

    public function login(): void
    {
        global $base;
        if (session_status() === PHP_SESSION_NONE) session_start();

        $username = $_POST['username'] ?? '';
        $password = $_POST['password'] ?? '';

        if ($username === self::USERNAME && $password === self::PASSWORD) {
            $_SESSION['logged_in'] = true;
            $_SESSION['user_name'] = 'Admin';
            $_SESSION['flash_message'] = 'Selamat datang, Admin';

            header('Location: ' . $base . '/dashboard');
            exit;
        }

        $error = 'Username atau password salah';
        require __DIR__ . '/../Views/auth/login.php';
    }

    public function logout(): void
    {
        global $base;
        if (session_status() === PHP_SESSION_NONE) session_start();
        session_destroy();
        session_start(); 

        $_SESSION['flash_message'] = 'Anda telah logout';
        
        header('Location: ' . $base . '/login');
        exit;
    }
}