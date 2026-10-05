<?php

namespace App\Controllers;

use App\Models\MahasiswaModel;
use App\Models\ProdiModel;
use PDOException;

class MahasiswaController
{
    public function index(): void
    {
        $model = new MahasiswaModel();

        // ===== TUGAS MANDIRI =====
        $keyword = trim($_GET['q'] ?? '');
        $daftarMahasiswa = $keyword !== '' ? $model->search($keyword) : $model->all();
        // ===== AKHIR TUGAS MANDIRI =====

        $content = __DIR__ . '/../Views/mahasiswa/index.php';
        require __DIR__ . '/../Views/layouts/main.php';
    }

    public function create(): void
    {
        $mhs = null;   // null = form tambah (tidak ada data lama)
        $prodiList = (new ProdiModel())->all();

        $content = __DIR__ . '/../Views/mahasiswa/form.php';
        require __DIR__ . '/../Views/layouts/main.php';
    }

    public function store(): void
    {
        $data = $this->input();

        if ($data['nim'] === '' || $data['nama'] === '' || $data['prodi_id'] < 1) {
            $_SESSION['flash'] = ['type' => 'danger', 'message' => 'NIM, nama, dan prodi wajib diisi'];
            header('Location: /acara8/si-akademik/public/mahasiswa/create');
            exit;
        }

        try {
            (new MahasiswaModel())->create($data);
        } catch (PDOException $e) {
            $_SESSION['flash'] = ['type' => 'danger', 'message' => 'Gagal menyimpan: NIM sudah terdaftar'];
            header('Location: /acara8/si-akademik/public/mahasiswa/create');
            exit;
        }

        $_SESSION['flash'] = ['type' => 'success', 'message' => 'Mahasiswa berhasil ditambahkan'];
        header('Location: /acara8/si-akademik/public/mahasiswa');
        exit;
    }

    public function show(int $id): void
    {
        $mhs = (new MahasiswaModel())->find($id);

        if ($mhs === null) {
            http_response_code(404);
            echo "404 - Mahasiswa dengan ID {$id} tidak ditemukan";
            return;
        }

        echo "<h1>Detail Mahasiswa (ID: {$id})</h1>";
        echo "<p>" . htmlspecialchars("{$mhs['nim']} - {$mhs['nama']} ({$mhs['prodi']})") . "</p>";
        echo "<p>Email: " . htmlspecialchars($mhs['email']) . "</p>";
        echo "<p>Angkatan: " . htmlspecialchars($mhs['angkatan']) . "</p>";
        echo "<a href='/acara8/si-akademik/public/mahasiswa'>Kembali</a>";
    }

    public function edit(int $id): void
    {
        $mhs = (new MahasiswaModel())->find($id);   // berisi data lama = form edit

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
        $data = $this->input();

        if ($data['nim'] === '' || $data['nama'] === '' || $data['prodi_id'] < 1) {
            $_SESSION['flash'] = ['type' => 'danger', 'message' => 'NIM, nama, dan prodi wajib diisi'];
            header("Location: /acara8/si-akademik/public/mahasiswa/{$id}/edit");
            exit;
        }

        try {
            (new MahasiswaModel())->update($id, $data);
        } catch (PDOException $e) {
            $_SESSION['flash'] = ['type' => 'danger', 'message' => 'Gagal mengubah: NIM sudah dipakai'];
            header("Location: /acara8/si-akademik/public/mahasiswa/{$id}/edit");
            exit;
        }

        $_SESSION['flash'] = ['type' => 'success', 'message' => 'Data mahasiswa berhasil diubah'];
        header('Location: /acara8/si-akademik/public/mahasiswa');
        exit;
    }

    public function destroy(int $id): void
    {
        (new MahasiswaModel())->delete($id);

        $_SESSION['flash'] = ['type' => 'success', 'message' => 'Data mahasiswa berhasil dihapus'];
        header('Location: /acara8/si-akademik/public/mahasiswa');
        exit;
    }

    // Mengambil dan membersihkan input form
    private function input(): array
    {
        $status = $_POST['status'] ?? 'aktif';

        return [
            'nim'      => trim($_POST['nim'] ?? ''),
            'nama'     => trim($_POST['nama'] ?? ''),
            'email'    => trim($_POST['email'] ?? ''),
            'prodi_id' => (int) ($_POST['prodi_id'] ?? 0),
            'angkatan' => (int) ($_POST['angkatan'] ?? date('Y')),
            'status'   => in_array($status, ['aktif', 'cuti', 'lulus'], true) ? $status : 'aktif',
        ];
    }
}