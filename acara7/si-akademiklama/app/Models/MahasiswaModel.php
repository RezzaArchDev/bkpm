<?php

namespace App\Models;

use App\Core\Model;
use PDO;

class MahasiswaModel extends Model
{
    public function all(): array
    {
        $stmt = $this->db->query("SELECT * FROM mahasiswa ORDER BY id ASC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}