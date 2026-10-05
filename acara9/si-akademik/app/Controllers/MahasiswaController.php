<?php

namespace App\Controllers;

use App\Models\Mahasiswa;
use App\Models\ProdiModel;
use App\Repositories\MahasiswaRepository;
use InvalidArgumentException;
use PDOException;

class MahasiswaController
{
    private MahasiswaRepository $repo;

    // Constructor injection: Repository diberikan dari luar.
    // Controller tidak lagi membuat koneksi database sendiri.
    public function __construct(MahasiswaRepository $repo)
    {
        $this->repo = $repo;
    }

    public function index(): void
    {
        $keyword = trim($_GET['q'] ?? '');
        $daftarMahasiswa = $keyword !== '' ? $this->repo->search($keyword) : $this->repo->all();

        $content = __DIR__ . '/../Views/mahasiswa/index.php';
        require __DIR__ . '/../Views/layouts/main.php';
    }

    public function create(): void
    {
        $mhs = null;   // null = form tambah
        $prodiList = (new ProdiModel())->all();

        $content = __DIR__ . '/../Views/mahasiswa/form.php';
        require __DIR__ . '/../Views/layouts/main.php';
    }

    public function store(): void
    {
        try {
            $mhs = $this->buatMahasiswa();   // setter memvalidasi
            $this->repo->create($mhs);
        } catch (InvalidArgumentException $e) {
            $_SESSION['flash'] = ['type' => 'danger', 'message' => $e->getMessage()];
            header('Location: /bkpm/acara9/si-akademik/public/mahasiswa/create');
            exit;
        } catch (PDOException $e) {
            $_SESSION['flash'] = ['type' => 'danger', 'message' => 'Gagal menyimpan: NIM sudah terdaftar'];
            header('Location: /bkpm/acara9/si-akademik/public/mahasiswa/create');
            exit;
        }

        $_SESSION['flash'] = ['type' => 'success', 'message' => 'Mahasiswa berhasil ditambahkan'];
        header('Location: /bkpm/acara9/si-akademik/public/mahasiswa');
        exit;
    }

    public function show(int $id): void
    {
        $mhs = $this->repo->find($id);

        if ($mhs === null) {
            http_response_code(404);
            echo "404 - Mahasiswa dengan ID {$id} tidak ditemukan";
            return;
        }

        echo "<h1>Detail Mahasiswa (ID: {$id})</h1>";
        echo "<p>" . htmlspecialchars("{$mhs['nim']} - {$mhs['nama']} ({$mhs['prodi']})") . "</p>";
        echo "<p>Email: " . htmlspecialchars($mhs['email']) . "</p>";
        echo "<p>Angkatan: " . htmlspecialchars($mhs['angkatan']) . "</p>";
        echo "<a href='/bkpm/acara9/si-akademik/public/mahasiswa'>Kembali</a>";
    }

    public function edit(int $id): void
    {
        $mhs = $this->repo->find($id);   // berisi data lama = form edit

        if ($mhs === null) {
            http_response_code(404);
            echo "404 - Mahasiswa dengan ID {$id} tidak ditemukan";
            return;
        }

        $prodiList = (new ProdiModel())->all();

        $content = __DIR__ . '/../Views/mahasiswa/form.php';
        require __DIR__ . '/../Views/layouts/main.php';
    }

    public function update(int $id): void
    {
        try {
            $mhs = $this->buatMahasiswa($id);   // setter memvalidasi
            $this->repo->update($mhs);
        } catch (InvalidArgumentException $e) {
            $_SESSION['flash'] = ['type' => 'danger', 'message' => $e->getMessage()];
            header("Location: /bkpm/acara9/si-akademik/public/mahasiswa/{$id}/edit");
            exit;
        } catch (PDOException $e) {
            $_SESSION['flash'] = ['type' => 'danger', 'message' => 'Gagal mengubah: NIM sudah dipakai'];
            header("Location: /bkpm/acara9/si-akademik/public/mahasiswa/{$id}/edit");
            exit;
        }

        $_SESSION['flash'] = ['type' => 'success', 'message' => 'Data mahasiswa berhasil diubah'];
        header('Location: /bkpm/acara9/si-akademik/public/mahasiswa');
        exit;
    }

    public function destroy(int $id): void
    {
        $this->repo->delete($id);

        $_SESSION['flash'] = ['type' => 'success', 'message' => 'Data mahasiswa berhasil dihapus'];
        header('Location: /bkpm/acara9/si-akademik/public/mahasiswa');
        exit;
    }

    // Membuat objek Mahasiswa dari input form.
    // Setiap setter bisa melempar InvalidArgumentException jika data tidak valid.
    private function buatMahasiswa(?int $id = null): Mahasiswa
    {
        $mhs = new Mahasiswa();

        if ($id !== null) {
            $mhs->setId($id);
        }

        $mhs->setNim($_POST['nim'] ?? '');
        $mhs->setNama($_POST['nama'] ?? '');
        $mhs->setEmail($_POST['email'] ?? '');
        $mhs->setProdiId((int) ($_POST['prodi_id'] ?? 0));
        $mhs->setAngkatan((int) ($_POST['angkatan'] ?? date('Y')));
        $mhs->setStatus($_POST['status'] ?? 'aktif');

        return $mhs;
    }
}