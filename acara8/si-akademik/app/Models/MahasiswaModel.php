<?php

namespace App\Models;

use App\Core\Model;

class MahasiswaModel extends Model
{
    public function all(): array
    {
        $sql = "SELECT m.*, p.nama AS prodi
                FROM mahasiswa m
                JOIN prodi p ON m.prodi_id = p.id
                ORDER BY m.nim";

        return $this->db->query($sql)->fetchAll();
    }

    public function find(int $id): ?array
    {
        $stmt = $this->db->prepare(
            "SELECT m.*, p.nama AS prodi
             FROM mahasiswa m
             JOIN prodi p ON m.prodi_id = p.id
             WHERE m.id = :id"
        );
        $stmt->execute(['id' => $id]);

        $row = $stmt->fetch();
        return $row ?: null;
    }

    public function create(array $d): void
    {
        $stmt = $this->db->prepare(
            "INSERT INTO mahasiswa (nim, nama, email, prodi_id, angkatan, status)
             VALUES (:nim, :nama, :email, :prodi_id, :angkatan, :status)"
        );
        $stmt->execute([
            'nim'      => $d['nim'],
            'nama'     => $d['nama'],
            'email'    => $d['email'],
            'prodi_id' => $d['prodi_id'],
            'angkatan' => $d['angkatan'],
            'status'   => $d['status'],
        ]);
    }

    public function update(int $id, array $d): void
    {
        $stmt = $this->db->prepare(
            "UPDATE mahasiswa
             SET nim = :nim, nama = :nama, email = :email,
                 prodi_id = :prodi_id, angkatan = :angkatan, status = :status
             WHERE id = :id"
        );
        $stmt->execute([
            'nim'      => $d['nim'],
            'nama'     => $d['nama'],
            'email'    => $d['email'],
            'prodi_id' => $d['prodi_id'],
            'angkatan' => $d['angkatan'],
            'status'   => $d['status'],
            'id'       => $id,
        ]);
    }

    public function delete(int $id): void
    {
        $stmt = $this->db->prepare("DELETE FROM mahasiswa WHERE id = :id");
        $stmt->execute(['id' => $id]);
    }
    
        // ===== TUGAS MANDIRI =====
    public function search(string $keyword): array
    {
        $sql = "SELECT m.*, p.nama AS prodi
                FROM mahasiswa m
                JOIN prodi p ON m.prodi_id = p.id
                WHERE m.nama LIKE :kw_nama OR m.nim LIKE :kw_nim
                ORDER BY m.nim";

        $stmt = $this->db->prepare($sql);
        // Nama parameter dibuat berbeda karena EMULATE_PREPARES = false
        $stmt->execute([
            'kw_nama' => "%{$keyword}%",
            'kw_nim'  => "%{$keyword}%",
        ]);

        return $stmt->fetchAll();
    }
    // ===== AKHIR TUGAS MANDIRI =====
}