<?php

class MahasiswaController
{
    private Mahasiswa $model;

    public function __construct()
    {
        $this->model = new Mahasiswa();
    }

    // GET /mahasiswa  atau  GET /mahasiswa?id=1
    public function index(): void
    {
        $id = $_GET['id'] ?? null;

        if ($id !== null) {
            $id = $this->validId($id);
            $data = $this->model->find($id);

            if (!$data) {
                Response::json(404, false, 'Data mahasiswa tidak ditemukan');
            }
            Response::json(200, true, 'Data mahasiswa berhasil diambil', $data);
        }

        Response::json(200, true, 'Data mahasiswa berhasil diambil', $this->model->getAll());
    }

    // POST /mahasiswa
    public function store(): void
    {
        $data = $this->validateData($this->readBody());

        try {
            $id = $this->model->create($data);
        } catch (PDOException $e) {
            Response::json(500, false, 'Terjadi kesalahan pada server');
        }

        Response::json(201, true, 'Data mahasiswa berhasil ditambahkan', ['id' => $id]);
    }

    // PUT /mahasiswa?id=1
    public function update($id): void
    {
        $id = $this->validId($id);

        if (!$this->model->find($id)) {
            Response::json(404, false, 'Data mahasiswa tidak ditemukan');
        }

        $data = $this->validateData($this->readBody(), $id);

        try {
            $this->model->update($id, $data);
        } catch (PDOException $e) {
            Response::json(500, false, 'Terjadi kesalahan pada server');
        }

        Response::json(200, true, 'Data mahasiswa berhasil diperbarui');
    }

    // DELETE /mahasiswa?id=1
    public function destroy($id): void
    {
        $id = $this->validId($id);

        if (!$this->model->find($id)) {
            Response::json(404, false, 'Data mahasiswa tidak ditemukan');
        }

        try {
            $this->model->delete($id);
        } catch (PDOException $e) {
            Response::json(500, false, 'Terjadi kesalahan pada server');
        }

        Response::json(200, true, 'Data mahasiswa berhasil dihapus');
    }

    /* ---------- helper private ---------- */

    private function validId($id): int
    {
        if ($id === null || !ctype_digit((string) $id) || (int) $id < 1) {
            Response::json(
                400,
                false,
                'ID mahasiswa wajib diisi dan harus valid',
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

    private function validateData(array $data, ?int $exceptId = null): array
    {
        // 1) Field wajib -> 400
        $errors = [];
        foreach (['nim', 'nama', 'prodi'] as $field) {
            if (!isset($data[$field]) || trim((string) $data[$field]) === '') {
                $errors[$field] = "$field wajib diisi";
            }
        }
        if (!empty($errors)) {
            Response::json(400, false, 'Validasi data gagal', null, $errors);
        }

        $clean = [
            'nim'   => trim((string) $data['nim']),
            'nama'  => trim((string) $data['nama']),
            'prodi' => trim((string) $data['prodi']),
        ];

        // 2) Format NIM -> 422
        if (strlen($clean['nim']) !== 9) {
            Response::json(
                422,
                false,
                'Format NIM tidak valid, harus 9 digit',
                null,
                ['nim' => 'NIM harus terdiri dari 9 karakter']
            );
        }

        // 3) NIM duplikat -> 409
        if ($this->model->existsByNim($clean['nim'], $exceptId)) {
            Response::json(
                409,
                false,
                'NIM sudah digunakan',
                null,
                ['nim' => 'NIM sudah terdaftar']
            );
        }

        return $clean;
    }
}