<?php

namespace App\Controllers;

use App\Models\MahasiswaModel;
use App\Models\ProdiModel;

require_once __DIR__ . '/../Core/Database.php';
require_once __DIR__ . '/../Core/Model.php';
require_once __DIR__ . '/../Models/MahasiswaModel.php';
require_once __DIR__ . '/../Models/ProdiModel.php';

class MahasiswaController
{
    // ===== STUDI KASUS (ACARA 8): index sekarang pakai JOIN =====
    public function index(): void
    {
        global $base;

        $model = new MahasiswaModel();

        // ===== TUGAS MANDIRI (ACARA 8): pencarian =====
        $keyword = trim($_GET['q'] ?? '');
        $daftarMahasiswa = $keyword !== ''
            ? $model->search($keyword)
            : $model->all();
        // ===== AKHIR TUGAS MANDIRI (ACARA 8) =====

        require __DIR__ . '/../Views/mahasiswa/list.php';
    }

    public function create(): void
    {
        global $base;
        $prodiModel = new ProdiModel();
        $daftarProdi = $prodiModel->all();
        $error = null;

        require __DIR__ . '/../Views/mahasiswa/create.php';
    }

    public function store(): void
    {
        global $base;

        $data = [
            'nim'      => trim($_POST['nim'] ?? ''),
            'nama'     => trim($_POST['nama'] ?? ''),
            'email'    => trim($_POST['email'] ?? ''),
            'prodi_id' => (int)($_POST['prodi_id'] ?? 0),
            'angkatan' => (int)($_POST['angkatan'] ?? date('Y')),
        ];

        if ($data['nim'] === '' || $data['nama'] === '') {
            header('Location: ' . $base . '/mahasiswa/create');
            exit;
        }

        $model = new MahasiswaModel();
        $model->create($data);

        header('Location: ' . $base . '/mahasiswa');
        exit;
    }

    public function edit(string $id): void
    {
        global $base;

        $model = new MahasiswaModel();
        $mahasiswa = $model->find((int)$id);

        if (!$mahasiswa) {
            http_response_code(404);
            echo "Mahasiswa tidak ditemukan";
            return;
        }

        $prodiModel = new ProdiModel();
        $daftarProdi = $prodiModel->all();

        require __DIR__ . '/../Views/mahasiswa/edit.php';
    }

    public function update(string $id): void
    {
        global $base;

        $data = [
            'nim'      => trim($_POST['nim'] ?? ''),
            'nama'     => trim($_POST['nama'] ?? ''),
            'email'    => trim($_POST['email'] ?? ''),
            'prodi_id' => (int)($_POST['prodi_id'] ?? 0),
            'angkatan' => (int)($_POST['angkatan'] ?? date('Y')),
        ];

        $model = new MahasiswaModel();
        $model->update((int)$id, $data);

        header('Location: ' . $base . '/mahasiswa');
        exit;
    }

    public function destroy(string $id): void
    {
        global $base;

        $model = new MahasiswaModel();
        $model->delete((int)$id);

        header('Location: ' . $base . '/mahasiswa');
        exit;
    }
    // ===== AKHIR STUDI KASUS (ACARA 8) =====

    public function show(string $id): void
    {
        $model = new MahasiswaModel();
        $mahasiswa = $model->find((int)$id);
        echo $mahasiswa ? "Detail: " . htmlspecialchars($mahasiswa['nama']) : "Tidak ditemukan";
    }
}
