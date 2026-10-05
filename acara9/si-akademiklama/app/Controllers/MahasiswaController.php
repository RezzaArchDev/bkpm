<?php

namespace App\Controllers;

use App\Core\Database;
use App\Models\Mahasiswa;
use App\Models\MahasiswaRepository;
use App\Models\ProdiModel;
use InvalidArgumentException;

require_once __DIR__ . '/../Core/Database.php';
require_once __DIR__ . '/../Models/Mahasiswa.php';
require_once __DIR__ . '/../Core/Model.php';
require_once __DIR__ . '/../Models/MahasiswaRepository.php';
require_once __DIR__ . '/../Models/ProdiModel.php';

class MahasiswaController
{
    private MahasiswaRepository $repo;

    // ===== STUDI KASUS Langkah 3: Controller menerima Repository via constructor =====
    public function __construct()
    {
        $this->repo = new MahasiswaRepository(Database::getInstance());
    }
    // ===== AKHIR =====

    public function index(): void
    {
        global $base;

        $keyword = trim($_GET['q'] ?? '');
        $daftarMahasiswa = $keyword !== ''
            ? $this->repo->search($keyword)
            : $this->repo->all();

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

    // ===== STUDI KASUS Langkah 4 & 5: pakai object Mahasiswa + validasi setter =====
    public function store(): void
    {
        global $base;

        try {
            $mhs = new Mahasiswa();
            $mhs->setNim(trim($_POST['nim'] ?? ''));
            $mhs->setNama(trim($_POST['nama'] ?? ''));
            $mhs->setEmail(trim($_POST['email'] ?? ''));
            $mhs->setProdiId((int)($_POST['prodi_id'] ?? 0));
            $mhs->setAngkatan((int)($_POST['angkatan'] ?? date('Y')));
        } catch (InvalidArgumentException $e) {
            $prodiModel = new ProdiModel();
            $daftarProdi = $prodiModel->all();
            $error = $e->getMessage();
            require __DIR__ . '/../Views/mahasiswa/create.php';
            return;
        }

        $this->repo->create($mhs->toArray());
        header('Location: ' . $base . '/mahasiswa');
        exit;
    }
    // ===== AKHIR =====

    public function edit(string $id): void
    {
        global $base;

        $mahasiswa = $this->repo->find((int)$id);
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

        try {
            $mhs = new Mahasiswa();
            $mhs->setNim(trim($_POST['nim'] ?? ''));
            $mhs->setNama(trim($_POST['nama'] ?? ''));
            $mhs->setEmail(trim($_POST['email'] ?? ''));
            $mhs->setProdiId((int)($_POST['prodi_id'] ?? 0));
            $mhs->setAngkatan((int)($_POST['angkatan'] ?? date('Y')));
        } catch (InvalidArgumentException $e) {
            $mahasiswa = $this->repo->find((int)$id);
            $prodiModel = new ProdiModel();
            $daftarProdi = $prodiModel->all();
            $error = $e->getMessage();
            require __DIR__ . '/../Views/mahasiswa/edit.php';
            return;
        }

        $this->repo->update((int)$id, $mhs->toArray());
        header('Location: ' . $base . '/mahasiswa');
        exit;
    }

    public function destroy(string $id): void
    {
        global $base;
        $this->repo->delete((int)$id);
        header('Location: ' . $base . '/mahasiswa');
        exit;
    }

    public function show(string $id): void
    {
        $mahasiswa = $this->repo->find((int)$id);
        echo $mahasiswa ? "Detail: " . htmlspecialchars($mahasiswa['nama']) : "Tidak ditemukan";
    }
}