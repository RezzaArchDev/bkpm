<?php

namespace App\Models;

use App\Core\Model;

class MahasiswaModel extends Model
{
    // Mengambil semua mahasiswa beserta nama prodinya
    public function all(): array
    {
        $sql = "SELECT m.*, p.nama AS prodi
                FROM mahasiswa m
                JOIN prodi p ON p.id = m.prodi_id
                ORDER BY m.id";

        return $this->db->query($sql)->fetchAll();
    }

    // Tambahan agar route /mahasiswa/{id} dari Acara 5 tetap jalan
    public function find(int $id): ?array
    {
        $stmt = $this->db->prepare(
            "SELECT m.*, p.nama AS prodi
             FROM mahasiswa m
             JOIN prodi p ON p.id = m.prodi_id
             WHERE m.id = :id"
        );
        $stmt->execute(['id' => $id]);

        $row = $stmt->fetch();
        return $row ?: null;
    }
}