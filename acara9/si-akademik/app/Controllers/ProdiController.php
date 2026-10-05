<?php

namespace App\Controllers;

use App\Models\ProdiModel;
use PDOException;

class ProdiController
{
    public function index(): void
    {
        $daftarProdi = (new ProdiModel())->all();

        $content = __DIR__ . '/../Views/prodi/index.php';
        require __DIR__ . '/../Views/layouts/main.php';
    }

    public function create(): void
    {
        $prodi = null;   // null = form tambah

        $content = __DIR__ . '/../Views/prodi/form.php';
        require __DIR__ . '/../Views/layouts/main.php';
    }

    public function store(): void
    {
        $data = $this->input();

        if ($data['kode'] === '' || $data['nama'] === '') {
            $_SESSION['flash'] = ['type' => 'danger', 'message' => 'Kode dan nama prodi wajib diisi'];
            header('Location: /bkpm/acara9/si-akademik/public/prodi/create');
            exit;
        }

        try {
            (new ProdiModel())->create($data);
        } catch (PDOException $e) {
            $_SESSION['flash'] = ['type' => 'danger', 'message' => 'Gagal menyimpan: kode prodi sudah dipakai'];
            header('Location: /bkpm/acara9/si-akademik/public/prodi/create');
            exit;
        }

        $_SESSION['flash'] = ['type' => 'success', 'message' => 'Prodi berhasil ditambahkan'];
        header('Location: /bkpm/acara9/si-akademik/public/prodi');
        exit;
    }

    public function edit(int $id): void
    {
        $prodi = (new ProdiModel())->find($id);

        if ($prodi === null) {
            http_response_code(404);
            echo "404 - Prodi dengan ID {$id} tidak ditemukan";
            return;
        }

        $content = __DIR__ . '/../Views/prodi/form.php';
        require __DIR__ . '/../Views/layouts/main.php';
    }

    public function update(int $id): void
    {
        $data = $this->input();

        if ($data['kode'] === '' || $data['nama'] === '') {
            $_SESSION['flash'] = ['type' => 'danger', 'message' => 'Kode dan nama prodi wajib diisi'];
            header("Location: /bkpm/acara9/si-akademik/public/prodi/{$id}/edit");
            exit;
        }

        try {
            (new ProdiModel())->update($id, $data);
        } catch (PDOException $e) {
            $_SESSION['flash'] = ['type' => 'danger', 'message' => 'Gagal mengubah: kode prodi sudah dipakai'];
            header("Location: /bkpm/acara9/si-akademik/public/prodi/{$id}/edit");
            exit;
        }

        $_SESSION['flash'] = ['type' => 'success', 'message' => 'Prodi berhasil diubah'];
        header('Location: /bkpm/acara9/si-akademik/public/prodi');
        exit;
    }

    public function destroy(int $id): void
    {
        try {
            (new ProdiModel())->delete($id);
            $_SESSION['flash'] = ['type' => 'success', 'message' => 'Prodi berhasil dihapus'];
        } catch (PDOException $e) {
            // Foreign key RESTRICT: prodi masih dipakai mahasiswa / mata kuliah
            $_SESSION['flash'] = ['type' => 'danger', 'message' => 'Prodi tidak dapat dihapus karena masih dipakai'];
        }

        header('Location: /bkpm/acara9/si-akademik/public/prodi');
        exit;
    }

    private function input(): array
    {
        return [
            'kode' => trim($_POST['kode'] ?? ''),
            'nama' => trim($_POST['nama'] ?? ''),
        ];
    }
}