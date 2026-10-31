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
        $id = $_GET['id'] ?? null;

        if ($id !== null) {
            $id = $this->validId($id);
            $data = $this->model->find($id);

            if (!$data) {
                Response::json(404, false, 'Buku tidak ditemukan');
            }
            Response::json(200, true, 'Data buku berhasil diambil', $data);
        }

        Response::json(200, true, 'Data buku berhasil diambil', $this->model->getAll());
    }

    public function store(): void
    {
        $data = $this->validateData($this->readBody());

        try {
            $id = $this->model->create($data);
        } catch (PDOException $e) {
            Response::json(500, false, 'Terjadi kesalahan pada server');
        }

        Response::json(201, true, 'Buku berhasil ditambahkan', ['id' => $id]);
    }

    public function update($id): void
    {
        $id = $this->validId($id);

        if (!$this->model->find($id)) {
            Response::json(404, false, 'Buku tidak ditemukan');
        }

        $data = $this->validateData($this->readBody());

        try {
            $this->model->update($id, $data);
        } catch (PDOException $e) {
            Response::json(500, false, 'Terjadi kesalahan pada server');
        }

        Response::json(200, true, 'Buku berhasil diperbarui');
    }

    public function destroy($id): void
    {
        $id = $this->validId($id);

        if (!$this->model->find($id)) {
            Response::json(404, false, 'Buku tidak ditemukan');
        }

        try {
            $this->model->delete($id);
        } catch (PDOException $e) {
            Response::json(500, false, 'Terjadi kesalahan pada server');
        }

        Response::json(200, true, 'Buku berhasil dihapus');
    }

    /* ---------- helper private ---------- */

    private function validId($id): int
    {
        if ($id === null || !ctype_digit((string) $id) || (int) $id < 1) {
            Response::json(
                400,
                false,
                'ID buku wajib diisi dan harus valid',
                null,
                ['id' => 'ID harus berupa angka positif']
            );
        }
        return (int) $id;
    }

    private function readBody(): array
    {
        $data = json_decode(file_get_contents('php://input'), true);

        if (!is_array($data)) {
            Response::json(
                400,
                false,
                'Request body harus berupa JSON yang valid',
                null,
                ['body' => 'Periksa format JSON']
            );
        }
        return $data;
    }

    private function validateData(array $data): array
    {
        $errors = [];
        foreach (['judul', 'penulis'] as $field) {
            if (!isset($data[$field]) || trim((string) $data[$field]) === '') {
                $errors[$field] = "$field wajib diisi";
            }
        }
        if (!empty($errors)) {
            Response::json(400, false, 'Validasi data gagal', null, $errors);
        }

        $tahun = isset($data['tahun_terbit']) ? trim((string) $data['tahun_terbit']) : '';

        if ($tahun !== '' && !preg_match('/^\d{4}$/', $tahun)) {
            Response::json(
                422,
                false,
                'Format tahun terbit tidak valid',
                null,
                ['tahun_terbit' => 'Tahun harus 4 digit angka']
            );
        }

        return [
            'judul'        => trim((string) $data['judul']),
            'penulis'      => trim((string) $data['penulis']),
            'tahun_terbit' => $tahun,
        ];
    }
}