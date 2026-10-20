CREATE DATABASE IF NOT EXISTS db_workshop
  CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE db_workshop;

CREATE TABLE IF NOT EXISTS mahasiswa (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nim VARCHAR(15) NOT NULL UNIQUE,
    nama VARCHAR(100) NOT NULL,
    prodi VARCHAR(50) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS buku (
    id INT AUTO_INCREMENT PRIMARY KEY,
    judul VARCHAR(150) NOT NULL,
    penulis VARCHAR(100) NOT NULL,
    tahun_terbit YEAR NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Data contoh (opsional). Hapus bagian INSERT jika ingin database kosong.
INSERT IGNORE INTO mahasiswa (nim, nama, prodi) VALUES
('2201001', 'Andi Saputra', 'Teknik Informatika'),
('2201002', 'Siti Rahma', 'Teknik Informatika');

INSERT INTO buku (judul, penulis, tahun_terbit)
SELECT 'Pemrograman PHP Dasar', 'Budi Santoso', 2023
WHERE NOT EXISTS (SELECT 1 FROM buku WHERE judul = 'Pemrograman PHP Dasar' AND penulis = 'Budi Santoso');
