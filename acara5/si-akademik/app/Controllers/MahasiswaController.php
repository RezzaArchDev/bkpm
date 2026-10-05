<?php

namespace App\Controllers;

use App\Models\Mahasiswa;

class MahasiswaController
{
    private function semua(): array
    {
        return [
            new Mahasiswa("2401001", "Axel Wangsa", "Teknik Informatika"),
            new Mahasiswa("2402045", "Yogian Eriya", "Sistem Informasi"),
            new Mahasiswa("2501078", "Rezza Hebat", "Teknik Informatika"),
        ];
    }

    public function index(): void
    {
        $daftarMahasiswa = $this->semua();
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

    // ===== TUGAS MANDIRI =====
    public function show(int $id): void
    {
        $daftar = $this->semua();

        if (!isset($daftar[$id - 1])) {
            http_response_code(404);
            echo "404 - Mahasiswa dengan ID {$id} tidak ditemukan";
            return;
        }

        $mhs = $daftar[$id - 1];
        echo "<h1>Detail Mahasiswa (ID: {$id})</h1>";
        echo "<p>" . htmlspecialchars($mhs->getLabel()) . "</p>";
        echo "<p>Angkatan: " . htmlspecialchars($mhs->getAngkatan()) . "</p>";
        echo "<a href='/bkpm/acara5/si-akademik/public/mahasiswa'>Kembali</a>";
    }
    // ===== AKHIR TUGAS MANDIRI =====
}