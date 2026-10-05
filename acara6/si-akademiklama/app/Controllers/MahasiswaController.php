<?php

namespace App\Controllers;

use App\Models\Mahasiswa;

require_once __DIR__ . '/../Models/Mahasiswa.php';

class MahasiswaController
{
    // ===== STUDI KASUS (routing dasar, hanya echo) =====
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
    // ===== AKHIR STUDI KASUS =====

    // ===== TUGAS MANDIRI (parameter URL dinamis, dari Acara 5) =====
    public function show(string $id): void
    {
        echo "Detail mahasiswa dengan ID: {$id} (MahasiswaController::show)";
    }
    // ===== AKHIR TUGAS MANDIRI =====

    // ===== ACARA 4 (digabung lewat routing) - Studi Kasus =====
    public function tampilStudiKasus(): void
    {
        global $base;

        $daftarMahasiswa = [
            new Mahasiswa("6101001", "Axel Wangsa", "Teknik Informatika"),
            new Mahasiswa("6201045", "Yogian Eriya", "Sistem Informasi"),
            new Mahasiswa("6301078", "Rezza Hebat", "Teknik Informatika"),
        ];

        $content = __DIR__ . '/../Views/mahasiswa/index.php';
        require __DIR__ . '/../Views/layouts/main.php';
    }

    // ===== ACARA 4 (digabung lewat routing) - Tugas Mandiri =====
    public function tampilTugasMandiri(): void
    {
        global $base;

        $daftarMahasiswa = [
            new Mahasiswa("6101001", "Axel Wangsa", "Teknik Informatika"),
            new Mahasiswa("6201045", "Yogian Eriya", "Sistem Informasi"),
            new Mahasiswa("6301078", "Rezza Hebat", "Teknik Informatika"),
        ];

        $content = __DIR__ . '/../Views/mahasiswa/index-angkatan.php';
        require __DIR__ . '/../Views/layouts/main.php';
    }
}
