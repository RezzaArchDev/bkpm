<?php

namespace App\Models;

use App\Core\Model;

class ProdiModel extends Model
{
    public function all(): array
    {
        return $this->db->query("SELECT * FROM prodi ORDER BY nama")->fetchAll();
    }
}
