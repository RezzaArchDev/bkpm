<?php

namespace App\Controllers;

use App\Models\MatakuliahModel;
use App\Models\ProdiModel;
use PDOException;

class MatakuliahController
{
    public function index(): void
    {
        $daftarMatakuliah = (new MatakuliahModel())->all();

        $content = __DIR__ . '/../Views/matakuliah/index.php';
        require __DIR__ . '/../Views/layouts/main.php';
    }

    public function create(): void
    {
        $mk = null;   // null = form tambah
        $prodiList = (new ProdiModel())->all();

        $content = __DIR__ . '/../Views/matakuliah/form.php';
        require __DIR__ . '/../Views/layouts/main.php';
    }

    public function store(): void
    {
        $data = $this->input();

        if ($data['kode'] === '' || $data['nama'] === '' || $data['sks'] < 1 || $data['prodi_id'] < 1) {
            $_SESSION['flash'] = ['type' => 'danger', 'message' => 'Semua kolom wajib diisi dengan benar'];
            header('Location: /bkpm/acara8/si-akademik/public/matakuliah/create');
            exit;
        }

        try {
            (new MatakuliahModel())->create($data);
        } catch (PDOException $e) {
            $_SESSION['flash'] = ['type' => 'danger', 'message' => 'Gagal menyimpan: kode mata kuliah sudah dipakai'];
            header('Location: /bkpm/acara8/si-akademik/public/matakuliah/create');
            exit;
        }

        $_SESSION['flash'] = ['type' => 'success', 'message' => 'Mata kuliah berhasil ditambahkan'];
        header('Location: /bkpm/acara8/si-akademik/public/matakuliah');
        exit;
    }

    public function edit(int $id): void
    {
        $mk = (new MatakuliahModel())->find($id);

        if ($mk === null) {
            http_response_code(404);
            echo "404 - Mata kuliah dengan ID {$id} tidak ditemukan";
            return;
        }

        $prodiList = (new ProdiModel())->all();

        $content = __DIR__ . '/../Views/matakuliah/form.php';
        require __DIR__ . '/../Views/layouts/main.php';
    }

    public function update(int $id): void
    {
        $data = $this->input();

        if ($data['kode'] === '' || $data['nama'] === '' || $data['sks'] < 1 || $data['prodi_id'] < 1) {
            $_SESSION['flash'] = ['type' => 'danger', 'message' => 'Semua kolom wajib diisi dengan benar'];
            header("Location: /bkpm/acara8/si-akademik/public/matakuliah/{$id}/edit");
            exit;
        }

        try {
            (new MatakuliahModel())->update($id, $data);
        } catch (PDOException $e) {
            $_SESSION['flash'] = ['type' => 'danger', 'message' => 'Gagal mengubah: kode mata kuliah sudah dipakai'];
            header("Location: /bkpm/acara8/si-akademik/public/matakuliah/{$id}/edit");
            exit;
        }

        $_SESSION['flash'] = ['type' => 'success', 'message' => 'Mata kuliah berhasil diubah'];
        header('Location: /bkpm/acara8/si-akademik/public/matakuliah');
        exit;
    }

    public function destroy(int $id): void
    {
        (new MatakuliahModel())->delete($id);

        $_SESSION['flash'] = ['type' => 'success', 'message' => 'Mata kuliah berhasil dihapus'];
        header('Location: /bkpm/acara8/si-akademik/public/matakuliah');
        exit;
    }

    private function input(): array
    {
        return [
            'kode'     => trim($_POST['kode'] ?? ''),
            'nama'     => trim($_POST['nama'] ?? ''),
            'sks'      => (int) ($_POST['sks'] ?? 0),
            'prodi_id' => (int) ($_POST['prodi_id'] ?? 0),
        ];
    }
}