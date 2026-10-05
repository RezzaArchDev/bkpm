<?php

namespace App\Models;

use App\Core\Model;

class MahasiswaModel extends Model
{
    // ===== STUDI KASUS (ACARA 8): JOIN ke prodi =====
    public function all(): array
    {
        $sql = "SELECT m.*, p.nama AS prodi_nama
                FROM mahasiswa m
                JOIN prodi p ON m.prodi_id = p.id
                ORDER BY m.nim";
        return $this->db->query($sql)->fetchAll();
    }

    public function find(int $id): ?array
    {
        $stmt = $this->db->prepare("SELECT * FROM mahasiswa WHERE id = :id");
        $stmt->execute(['id' => $id]);
        $result = $stmt->fetch();
        return $result ?: null;
    }

    public function create(array $data): bool
    {
        $sql = "INSERT INTO mahasiswa (nim, nama, email, prodi_id, angkatan)
                VALUES (:nim, :nama, :email, :prodi_id, :angkatan)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute($data);
    }

    public function update(int $id, array $data): bool
    {
        $sql = "UPDATE mahasiswa
                SET nim = :nim, nama = :nama, email = :email,
                    prodi_id = :prodi_id, angkatan = :angkatan
                WHERE id = :id";
        $data['id'] = $id;
        $stmt = $this->db->prepare($sql);
        return $stmt->execute($data);
    }

    public function delete(int $id): bool
    {
        $stmt = $this->db->prepare("DELETE FROM mahasiswa WHERE id = :id");
        return $stmt->execute(['id' => $id]);
    }
    // ===== AKHIR STUDI KASUS (ACARA 8) =====

    // ===== TUGAS MANDIRI (ACARA 8): pencarian pakai LIKE =====
    public function search(string $keyword): array
    {
        $sql = "SELECT m.*, p.nama AS prodi_nama
                FROM mahasiswa m
                JOIN prodi p ON m.prodi_id = p.id
                WHERE m.nama LIKE :keyword1 OR m.nim LIKE :keyword2
                ORDER BY m.nim";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            'keyword1' => "%{$keyword}%",
            'keyword2' => "%{$keyword}%",
        ]);
        return $stmt->fetchAll();
    }
    // ===== AKHIR TUGAS MANDIRI (ACARA 8) =====
}