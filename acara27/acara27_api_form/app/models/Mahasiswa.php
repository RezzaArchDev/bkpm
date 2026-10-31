<?php

class Mahasiswa
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::getConnection();
    }

    public function getAll(): array
    {
        try {
            return $this->db
                ->query('SELECT * FROM mahasiswa ORDER BY id DESC')
                ->fetchAll();
        } catch (PDOException $e) {
            error_log($e->getMessage());
            Response::json(500, false, 'Terjadi kesalahan pada server');
        }
    }

    public function find(int $id)
    {
        try {
            $stmt = $this->db->prepare('SELECT * FROM mahasiswa WHERE id = :id');
            $stmt->execute([':id' => $id]);

            return $stmt->fetch();
        } catch (PDOException $e) {
            error_log($e->getMessage());
            Response::json(500, false, 'Terjadi kesalahan pada server');
        }
    }

    public function existsByNim(string $nim, ?int $exceptId = null): bool
    {
        try {
            $sql = 'SELECT COUNT(*) FROM mahasiswa WHERE nim = :nim';
            $params = [':nim' => $nim];

            if ($exceptId !== null) {
                $sql .= ' AND id != :id';
                $params[':id'] = $exceptId;
            }

            $stmt = $this->db->prepare($sql);
            $stmt->execute($params);

            return (int) $stmt->fetchColumn() > 0;
        } catch (PDOException $e) {
            error_log($e->getMessage());
            Response::json(500, false, 'Terjadi kesalahan pada server');
        }
    }

    public function create(array $data): string
    {
        try {
            $stmt = $this->db->prepare(
                'INSERT INTO mahasiswa (nim, nama, prodi, foto) VALUES (:nim, :nama, :prodi, :foto)'
            );
            $stmt->execute([
                ':nim'   => $data['nim'],
                ':nama'  => $data['nama'],
                ':prodi' => $data['prodi'],
                ':foto'  => $data['foto'] ?? null,
            ]);

            return $this->db->lastInsertId();
        } catch (PDOException $e) {
            error_log($e->getMessage());
            Response::json(500, false, 'Terjadi kesalahan pada server');
        }
    }

    public function update(int $id, array $data): void
    {
        try {
            $sql = 'UPDATE mahasiswa SET nim = :nim, nama = :nama, prodi = :prodi';
            $params = [
                ':nim'   => $data['nim'],
                ':nama'  => $data['nama'],
                ':prodi' => $data['prodi'],
                ':id'    => $id,
            ];

            // Kolom foto hanya diubah kalau ada foto baru
            if (!empty($data['foto'])) {
                $sql .= ', foto = :foto';
                $params[':foto'] = $data['foto'];
            }

            $stmt = $this->db->prepare($sql . ' WHERE id = :id');
            $stmt->execute($params);
        } catch (PDOException $e) {
            error_log($e->getMessage());
            Response::json(500, false, 'Terjadi kesalahan pada server');
        }
    }

    public function delete(int $id): void
    {
        try {
            $stmt = $this->db->prepare('DELETE FROM mahasiswa WHERE id = :id');
            $stmt->execute([':id' => $id]);
        } catch (PDOException $e) {
            error_log($e->getMessage());
            Response::json(500, false, 'Terjadi kesalahan pada server');
        }
    }
}