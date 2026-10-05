<?php

namespace App\Controllers;

use App\Models\MahasiswaModel;

require_once __DIR__ . '/../Core/Model.php';
require_once __DIR__ . '/../Models/MahasiswaModel.php';

class MahasiswaController
{
    // ===== STUDI KASUS (ACARA 7): data mahasiswa dari database asli =====
    public function index(): void
    {
        global $base;

        $model = new MahasiswaModel();
        $daftarMahasiswa = $model->all();

        require __DIR__ . '/../Views/mahasiswa/list.php';
    }
    // ===== AKHIR STUDI KASUS (ACARA 7) =====

    public function create(): void
    {
        echo "Form tambah mahasiswa (MahasiswaController::create)";
    }

    public function store(): void
    {
        echo "Menyimpan data mahasiswa baru (MahasiswaController::store)";
    }

    public function show(string $id): void
    {
        echo "Detail mahasiswa dengan ID: {$id} (MahasiswaController::show)";
    }
}