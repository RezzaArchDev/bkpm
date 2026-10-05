<?php

namespace App\Models;

class Mahasiswa
{
    private string $nim;
    private string $nama;
    private string $prodi;

    public function __construct(string $nim, string $nama, string $prodi)
    {
        $this->nim   = $nim;
        $this->nama  = $nama;
        $this->prodi = $prodi;
    }

    public function getNim(): string
    {
        return $this->nim;
    }

    public function getNama(): string
    {
        return $this->nama;
    }

    public function getProdi(): string
    {
        return $this->prodi;
    }

    public function setNama(string $nama): void
    {
        if (strlen($nama) < 3) {
            throw new \InvalidArgumentException("Nama terlalu pendek");
        }
        $this->nama = $nama;
    }

    public function getLabel(): string
    {
        return "{$this->nim} - {$this->nama} ({$this->prodi})";
    }

    // ===== TUGAS MANDIRI =====
    // Mengembalikan angkatan mahasiswa berdasarkan 2 digit awal NIM.
    // Asumsi: 2 digit awal NIM = tahun masuk, contoh "24" -> 2024.
    public function getAngkatan(): string
    {
        $duaDigitAwal = substr($this->nim, 0, 2);

        if (strlen($duaDigitAwal) < 2 || !ctype_digit($duaDigitAwal)) {
            return "Tidak diketahui";
        }

        return "20" . $duaDigitAwal;
    }
    // ===== AKHIR TUGAS MANDIRI =====
}