<?php

class Router
{
    // Peta resource pada URL => nama class Controller
    private array $map = [
        'mahasiswa' => 'MahasiswaController',
        'buku'      => 'BukuController',
        'register'  => 'AuthController',
        'login'     => 'AuthController',
    ];

    public function dispatch(): void
    {
        $uri = trim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/');
        $segments = explode('/', $uri);
        $resource = end($segments);

        $controllerName = $this->map[$resource] ?? null;

        if (!$controllerName) {
            Response::json(404, false, 'Endpoint tidak ditemukan');
        }

        $controller = new $controllerName();
        $method = $_SERVER['REQUEST_METHOD'];

        // PHP tidak mengisi $_POST dan $_FILES untuk request PUT multipart,
        // jadi PUT berisi berkas dikirim sebagai POST dengan parameter ?_method=PUT
        if ($method === 'POST' && strtoupper($_GET['_method'] ?? '') === 'PUT') {
            $method = 'PUT';
        }

        // register dan login bukan resource CRUD, hanya menerima POST
        if ($resource === 'login' || $resource === 'register') {
            if ($method !== 'POST') {
                header('Allow: POST');
                Response::json(405, false, 'Method tidak diizinkan');
            }

            if ($resource === 'login') {
                $controller->login();
            } else {
                $controller->register();
            }
            return;
        }

        $id = $_GET['id'] ?? null;

        switch ($method) {
            case 'GET':
                $controller->index();
                break;
            case 'POST':
                $controller->store();
                break;
            case 'PUT':
                $controller->update($id);
                break;
            case 'DELETE':
                $controller->destroy($id);
                break;
            default:
                header('Allow: GET, POST, PUT, DELETE');
                Response::json(405, false, 'Method tidak diizinkan');
        }
    }
}