<?php

namespace App\Controllers;

use App\Models\MahasiswaModel;

class MahasiswaController
{
    public function index(): void
    {
        $model = new MahasiswaModel();
        $daftarMahasiswa = $model->all();

        $content = __DIR__ . '/../Views/mahasiswa/index.php';
        require __DIR__ . '/../Views/layouts/main.php';
    }

    public function create(): void
    {
        echo "<h1>Form Tambah Mahasiswa</h1>";
    }

    public function store(): void
    {
        echo "Data diterima (POST ke /mahasiswa)";
    }

    public function show(int $id): void
    {
        $model = new MahasiswaModel();
        $mhs = $model->find($id);

        if ($mhs === null) {
            http_response_code(404);
            echo "404 - Mahasiswa dengan ID {$id} tidak ditemukan";
            return;
        }

        echo "<h1>Detail Mahasiswa (ID: {$id})</h1>";
        echo "<p>" . htmlspecialchars("{$mhs['nim']} - {$mhs['nama']} ({$mhs['prodi']})") . "</p>";
        echo "<p>Email: " . htmlspecialchars($mhs['email']) . "</p>";
        echo "<p>Angkatan: " . htmlspecialchars($mhs['angkatan']) . "</p>";
        echo "<a href='/bkpm/acara7/si-akademik/public/mahasiswa'>Kembali</a>";
    }
}