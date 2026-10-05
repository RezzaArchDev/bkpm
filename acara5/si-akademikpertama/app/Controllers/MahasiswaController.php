<?php

namespace App\Controllers;

use App\Models\Mahasiswa;

class MahasiswaController
{
    // ===== STUDI KASUS (ACARA 5) =====
    public function index(): void
    {
        echo "Daftar semua mahasiswa (MahasiswaController::index)";
    }

    public function create(): void
    {
        echo "Form tambah mahasiswa (MahasiswaController::create)";
    }

    public function store(): void
    {
        echo "Menyimpan data mahasiswa baru (MahasiswaController::store)";
    }
    // ===== AKHIR STUDI KASUS (ACARA 5) =====

    // ===== TUGAS MANDIRI (ACARA 5) =====
    public function show(string $id): void
    {
        echo "Detail mahasiswa dengan ID: {$id} (MahasiswaController::show)";
    }
    // ===== AKHIR TUGAS MANDIRI (ACARA 5) =====

    // ===== ACARA 4 digabung ke sini (Studi Kasus + Tugas Mandiri Acara 4) =====
    public function tampilTabel(): void
    {
        require_once __DIR__ . '/../Models/Mahasiswa.php';

        $daftarMahasiswa = [
            new Mahasiswa("6101001", "Axel Wangsa", "Teknik Informatika"),
            new Mahasiswa("6201045", "Yogian Eriya", "Sistem Informasi"),
            new Mahasiswa("6301078", "Rezza Hebat", "Teknik Informatika"),
        ];

        $content = __DIR__ . '/../Views/mahasiswa/index.php';
        require __DIR__ . '/../Views/layouts/main.php';
    }
    // ===== AKHIR ACARA 4 =====
}