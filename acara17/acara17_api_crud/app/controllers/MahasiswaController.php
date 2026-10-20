<?php
class MahasiswaController
{
    private Mahasiswa $model;

    public function __construct()
    {
        $this->model = new Mahasiswa();
    }

    public function index(): void
    {
        header('Content-Type: application/json; charset=utf-8');
        $id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

        if ($id !== null && $id !== false) {
            $data = $this->model->find((int) $id);
            if (!$data) {
                $this->respond(404, ['success' => false, 'message' => 'Data tidak ditemukan']);
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
                'message' => 'Field nim, nama, dan prodi wajib diisi'
            ]);
            return;
        }

        $data = $this->cleanData($data);
        if ($this->model->existsByNim($data['nim'])) {
            $this->respond(409, [
                'success' => false,
                'message' => 'NIM sudah digunakan'
            ]);
            return;
        }

        try {
            $id = $this->model->create($data);
            $this->respond(201, [
                'success' => true,
                'message' => 'Data berhasil ditambahkan',
                'data' => ['id' => $id]
            ]);
        } catch (PDOException $e) {
            $this->respond(500, ['success' => false, 'message' => 'Gagal menyimpan data']);
        }
    }

    public function update($id): void
    {
        header('Content-Type: application/json; charset=utf-8');
        $id = filter_var($id, FILTER_VALIDATE_INT);
        if (!$id || !$this->model->find((int) $id)) {
            $this->respond(404, ['success' => false, 'message' => 'Data tidak ditemukan']);
            return;
        }

        $data = $this->readJson();
        if (!$this->validData($data)) {
            $this->respond(400, [
                'success' => false,
                'message' => 'Field nim, nama, dan prodi wajib diisi'
            ]);
            return;
        }

        $data = $this->cleanData($data);
        if ($this->model->existsByNim($data['nim'], (int) $id)) {
            $this->respond(409, ['success' => false, 'message' => 'NIM sudah digunakan']);
            return;
        }

        try {
            $this->model->update((int) $id, $data);
            $this->respond(200, ['success' => true, 'message' => 'Data berhasil diperbarui']);
        } catch (PDOException $e) {
            $this->respond(500, ['success' => false, 'message' => 'Gagal memperbarui data']);
        }
    }

    public function destroy($id): void
    {
        header('Content-Type: application/json; charset=utf-8');
        $id = filter_var($id, FILTER_VALIDATE_INT);
        if (!$id || !$this->model->find((int) $id)) {
            $this->respond(404, ['success' => false, 'message' => 'Data tidak ditemukan']);
            return;
        }

        try {
            $this->model->delete((int) $id);
            $this->respond(200, ['success' => true, 'message' => 'Data berhasil dihapus']);
        } catch (PDOException $e) {
            $this->respond(500, ['success' => false, 'message' => 'Gagal menghapus data']);
        }
    }

    private function readJson(): array
    {
        $raw = file_get_contents('php://input');
        $data = json_decode($raw, true);
        return is_array($data) ? $data : [];
    }

    private function validData(array $data): bool
    {
        return isset($data['nim'], $data['nama'], $data['prodi'])
            && trim((string) $data['nim']) !== ''
            && trim((string) $data['nama']) !== ''
            && trim((string) $data['prodi']) !== '';
    }

    private function cleanData(array $data): array
    {
        return [
            'nim' => trim((string) $data['nim']),
            'nama' => trim((string) $data['nama']),
            'prodi' => trim((string) $data['prodi']),
        ];
    }

    private function respond(int $status, array $payload): void
    {
        http_response_code($status);
        echo json_encode($payload, JSON_UNESCAPED_UNICODE);
    }
}
