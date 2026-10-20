<?php
class Router
{
    private array $map = [
        'mahasiswa' => 'MahasiswaController',
        'buku' => 'BukuController',
    ];

    public function dispatch(): void
    {
        $uri = trim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/');
        $segments = explode('/', $uri);
        $resource = end($segments) ?: 'mahasiswa';

        // Mendukung URL seperti /sistem-informasi-akademik/public/mahasiswa
        $controllerName = $this->map[$resource] ?? null;

        if (!$controllerName) {
            $this->jsonError(404, 'Endpoint tidak ditemukan');
            return;
        }

        $controller = new $controllerName();

        switch ($_SERVER['REQUEST_METHOD']) {
            case 'GET':
                $controller->index();
                break;
            case 'POST':
                $controller->store();
                break;
            case 'PUT':
                $controller->update($_GET['id'] ?? null);
                break;
            case 'DELETE':
                $controller->destroy($_GET['id'] ?? null);
                break;
            default:
                header('Allow: GET, POST, PUT, DELETE');
                $this->jsonError(405, 'Method tidak diizinkan');
        }
    }

    private function jsonError(int $status, string $message): void
    {
        http_response_code($status);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode(['success' => false, 'message' => $message]);
    }
}
