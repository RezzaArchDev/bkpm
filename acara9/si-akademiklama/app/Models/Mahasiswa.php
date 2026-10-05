<?php

namespace App\Models;

use InvalidArgumentException;

class Mahasiswa
{
    private ?int $id = null;
    private string $nim = '';
    private string $nama = '';
    private string $email = '';
    private int $prodiId = 0;
    private int $angkatan = 0;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): void
    {
        $this->id = $id;
    }

    public function getNim(): string
    {
        return $this->nim;
    }

    // ===== STUDI KASUS Langkah 5: validasi NIM harus angka =====
    public function setNim(string $nim): void
    {
        if (!ctype_digit($nim)) {
            throw new InvalidArgumentException("NIM harus berupa angka");
        }
        $this->nim = $nim;
    }

    public function getNama(): string
    {
        return $this->nama;
    }

    // ===== STUDI KASUS Langkah 5: validasi nama tidak boleh kosong =====
    public function setNama(string $nama): void
    {
        if (trim($nama) === '') {
            throw new InvalidArgumentException("Nama tidak boleh kosong");
        }
        $this->nama = $nama;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    public function getProdiId(): int
    {
        return $this->prodiId;
    }

    public function setProdiId(int $prodiId): void
    {
        $this->prodiId = $prodiId;
    }

    public function getAngkatan(): int
    {
        return $this->angkatan;
    }

    public function setAngkatan(int $angkatan): void
    {
        $this->angkatan = $angkatan;
    }

    // Ubah object jadi array biasa, supaya gampang dikirim ke Repository
    public function toArray(): array
    {
        return [
            'nim'      => $this->nim,
            'nama'     => $this->nama,
            'email'    => $this->email,
            'prodi_id' => $this->prodiId,
            'angkatan' => $this->angkatan,
        ];
    }
}