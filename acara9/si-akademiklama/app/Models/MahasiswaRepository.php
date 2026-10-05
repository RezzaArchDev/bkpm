<?php

namespace App\Models;

use PDO;

class MahasiswaRepository
{
    private PDO $pdo;

    // ===== STUDI KASUS Langkah 2: constructor injection =====
    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }
    // ===== AKHIR =====

    public function all(): array
    {
        $sql = "SELECT m.*, p.nama AS prodi_nama
                FROM mahasiswa m
                JOIN prodi p ON m.prodi_id = p.id
                ORDER BY m.nim";
        return $this->pdo->query($sql)->fetchAll();
    }

    public function search(string $keyword): array
    {
        $sql = "SELECT m.*, p.nama AS prodi_nama
                FROM mahasiswa m
                JOIN prodi p ON m.prodi_id = p.id
                WHERE m.nama LIKE :keyword1 OR m.nim LIKE :keyword2
                ORDER BY m.nim";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            'keyword1' => "%{$keyword}%",
            'keyword2' => "%{$keyword}%",
        ]);
        return $stmt->fetchAll();
    }

    public function find(int $id): ?array
    {
        $stmt = $this->pdo->prepare("SELECT * FROM mahasiswa WHERE id = :id");
        $stmt->execute(['id' => $id]);
        $result = $stmt->fetch();
        return $result ?: null;
    }

    public function create(array $data): bool
    {
        $sql = "INSERT INTO mahasiswa (nim, nama, email, prodi_id, angkatan)
                VALUES (:nim, :nama, :email, :prodi_id, :angkatan)";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute($data);
    }

    public function update(int $id, array $data): bool
    {
        $sql = "UPDATE mahasiswa
                SET nim = :nim, nama = :nama, email = :email,
                    prodi_id = :prodi_id, angkatan = :angkatan
                WHERE id = :id";
        $data['id'] = $id;
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute($data);
    }

    public function delete(int $id): bool
    {
        $stmt = $this->pdo->prepare("DELETE FROM mahasiswa WHERE id = :id");
        return $stmt->execute(['id' => $id]);
    }
}