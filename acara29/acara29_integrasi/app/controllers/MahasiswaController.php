<?php

class MahasiswaController
{
    private Mahasiswa $model;

    // Folder penyimpanan foto (di dalam public/ agar bisa diakses browser)
    private const FOLDER_UPLOAD = __DIR__ . '/../../public_html/uploads/';

    public function __construct()
    {
        $this->model = new Mahasiswa();
    }

    // GET /mahasiswa  atau  GET /mahasiswa?id=1  (publik, tanpa token)
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

    // POST /mahasiswa  (khusus admin, boleh JSON atau multipart dengan foto)
    public function store(): void
    {
        Auth::requireAdmin();

        $data = $this->validateData($this->readBody());

        $foto = $this->prosesFoto();
        if ($foto !== null) {
            $data['foto'] = $foto;
        }

        $id = $this->model->create($data);

        Response::json(201, true, 'Data mahasiswa berhasil ditambahkan', ['id' => $id]);
    }

    // PUT /mahasiswa?id=1  (khusus admin). Dengan foto: POST /mahasiswa?id=1&_method=PUT
    public function update($id): void
    {
        Auth::requireAdmin();

        $id = $this->validId($id);

        $lama = $this->model->find($id);
        if (!$lama) {
            Response::json(404, false, 'Data mahasiswa tidak ditemukan');
        }

        $data = $this->validateData($this->readBody(), $id);

        $foto = $this->prosesFoto();
        if ($foto !== null) {
            $data['foto'] = $foto;
        }

        $this->model->update($id, $data);

        // Tugas 1: hapus berkas foto lama jika diganti foto baru
        if ($foto !== null && !empty($lama['foto'])) {
            $this->hapusBerkas($lama['foto']);
        }

        Response::json(200, true, 'Data mahasiswa berhasil diperbarui');
    }

    // DELETE /mahasiswa?id=1  (khusus admin)
    public function destroy($id): void
    {
        Auth::requireAdmin();

        $id = $this->validId($id);

        $data = $this->model->find($id);
        if (!$data) {
            Response::json(404, false, 'Data mahasiswa tidak ditemukan');
        }

        $this->model->delete($id);

        if (!empty($data['foto'])) {
            $this->hapusBerkas($data['foto']);
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

    // Membaca body: multipart/form-data (dari FormData) atau JSON
    private function readBody(): array
    {
        $tipe = $_SERVER['CONTENT_TYPE'] ?? '';

        if (stripos($tipe, 'multipart/form-data') !== false || !empty($_POST)) {
            return $_POST;
        }

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

    // Validasi dan penyimpanan foto. Mengembalikan nama berkas baru, atau null jika tidak ada foto
    private function prosesFoto(): ?string
    {
        if (empty($_FILES['foto']) || $_FILES['foto']['error'] === UPLOAD_ERR_NO_FILE) {
            return null;
        }

        $file = $_FILES['foto'];

        if ($file['error'] !== UPLOAD_ERR_OK) {
            Response::json(
                422,
                false,
                'Upload berkas gagal',
                null,
                ['foto' => 'Kode error upload: ' . $file['error']]
            );
        }

        // Cek ekstensi DAN isi berkas (signature gambar yang sebenarnya)
        $ekstensi = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        $jenis = $this->jenisGambar($file['tmp_name']);

        if (!in_array($ekstensi, ['jpg', 'jpeg', 'png'], true)
            || !in_array($jenis, [IMAGETYPE_JPEG, IMAGETYPE_PNG], true)) {
            Response::json(
                422,
                false,
                'Berkas harus berupa gambar JPG/PNG yang valid',
                null,
                ['foto' => 'Hanya JPG atau PNG yang diizinkan']
            );
        }

        if ($file['size'] > 2 * 1024 * 1024) {
            Response::json(
                422,
                false,
                'Ukuran berkas maksimal 2MB',
                null,
                ['foto' => 'Ukuran berkas melebihi 2MB']
            );
        }

        // Nama baru unik agar tidak saling menimpa dan tidak memakai nama dari pengguna
        $namaBaru = uniqid('foto_', true) . '.' . $ekstensi;

        if (!move_uploaded_file($file['tmp_name'], self::FOLDER_UPLOAD . $namaBaru)) {
            Response::json(500, false, 'Gagal menyimpan berkas');
        }

        return $namaBaru;
    }

    // exif_imagetype() jika tersedia, selain itu getimagesize()
    private function jenisGambar(string $path)
    {
        if (function_exists('exif_imagetype')) {
            return @exif_imagetype($path);
        }
        $info = @getimagesize($path);
        return $info ? $info[2] : false;
    }

    private function hapusBerkas(string $namaBerkas): void
    {
        $path = self::FOLDER_UPLOAD . basename($namaBerkas);
        if (is_file($path)) {
            unlink($path);
        }
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

        // 2) Format NIM -> 422 (harus 9 digit angka)
        if (!ctype_digit($clean['nim']) || strlen($clean['nim']) !== 9) {
            Response::json(
                422,
                false,
                'Format NIM tidak valid, harus 9 digit angka',
                null,
                ['nim' => 'NIM harus terdiri dari 9 digit angka']
            );
        }

        // 3) Panjang nama dan prodi -> 422 (sesuai batas kolom database)
        $errors = [];
        if (mb_strlen($clean['nama']) > 100) {
            $errors['nama'] = 'Nama maksimal 100 karakter';
        }
        if (mb_strlen($clean['prodi']) > 50) {
            $errors['prodi'] = 'Program studi maksimal 50 karakter';
        }
        if (!empty($errors)) {
            Response::json(422, false, 'Panjang data melebihi batas', null, $errors);
        }

        // 4) NIM duplikat -> 409
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