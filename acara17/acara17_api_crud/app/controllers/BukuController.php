<?php
class BukuController
{
    private Buku $model;

    public function __construct()
    {
        $this->model = new Buku();
    }

    public function index(): void
    {
        header('Content-Type: application/json; charset=utf-8');
        $id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

        if ($id !== null && $id !== false) {
            $data = $this->model->find((int) $id);
            if (!$data) {
                $this->respond(404, ['success' => false, 'message' => 'Buku tidak ditemukan']);
                return;
            }
            $this->respond(200, ['success' => true, 'data' => $data]);
            return;
        }

        $this->respond(200, ['success' => true, 'data' => $this->model->getAll()]);
    }

    public function store(): void
    {
        header('Content-Type: application/json; charset=utf-8');
        $data = $this->readJson();

        if (!$this->validData($data)) {
            $this->respond(400, [
                'success' => false,
                'message' => 'Field judul dan penulis wajib diisi'
            ]);
            return;
        }

        try {
            $id = $this->model->create($this->cleanData($data));
            $this->respond(201, [
                'success' => true,
                'message' => 'Buku berhasil ditambahkan',
                'data' => ['id' => $id]
            ]);
        } catch (PDOException $e) {
            $this->respond(500, ['success' => false, 'message' => 'Gagal menyimpan buku']);
        }
    }

    public function update($id): void
    {
        header('Content-Type: application/json; charset=utf-8');
        $id = filter_var($id, FILTER_VALIDATE_INT);
        if (!$id || !$this->model->find((int) $id)) {
            $this->respond(404, ['success' => false, 'message' => 'Buku tidak ditemukan']);
            return;
        }

        $data = $this->readJson();
        if (!$this->validData($data)) {
            $this->respond(400, [
                'success' => false,
                'message' => 'Field judul dan penulis wajib diisi'
            ]);
            return;
        }

        try {
            $this->model->update((int) $id, $this->cleanData($data));
            $this->respond(200, ['success' => true, 'message' => 'Buku berhasil diperbarui']);
        } catch (PDOException $e) {
            $this->respond(500, ['success' => false, 'message' => 'Gagal memperbarui buku']);
        }
    }

    public function destroy($id): void
    {
        header('Content-Type: application/json; charset=utf-8');
        $id = filter_var($id, FILTER_VALIDATE_INT);
        if (!$id || !$this->model->find((int) $id)) {
            $this->respond(404, ['success' => false, 'message' => 'Buku tidak ditemukan']);
            return;
        }

        try {
            $this->model->delete((int) $id);
            $this->respond(200, ['success' => true, 'message' => 'Buku berhasil dihapus']);
        } catch (PDOException $e) {
            $this->respond(500, ['success' => false, 'message' => 'Gagal menghapus buku']);
        }
    }

    private function readJson(): array
    {
        $data = json_decode(file_get_contents('php://input'), true);
        return is_array($data) ? $data : [];
    }

    private function validData(array $data): bool
    {
        return isset($data['judul'], $data['penulis'])
            && trim((string) $data['judul']) !== ''
            && trim((string) $data['penulis']) !== '';
    }

    private function cleanData(array $data): array
    {
        return [
            'judul' => trim((string) $data['judul']),
            'penulis' => trim((string) $data['penulis']),
            'tahun_terbit' => isset($data['tahun_terbit']) ? trim((string) $data['tahun_terbit']) : '',
        ];
    }

    private function respond(int $status, array $payload): void
    {
        http_response_code($status);
        echo json_encode($payload, JSON_UNESCAPED_UNICODE);
    }
}
