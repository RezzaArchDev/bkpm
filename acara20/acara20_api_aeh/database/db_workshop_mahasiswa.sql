CREATE DATABASE IF NOT EXISTS db_workshop CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE db_workshop;

CREATE TABLE IF NOT EXISTS mahasiswa (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    nim VARCHAR(9) NOT NULL UNIQUE,
    nama VARCHAR(100) NOT NULL,
    email VARCHAR(150) NOT NULL
);

-- Contoh data untuk tes GET (opsional)
INSERT INTO mahasiswa (nim, nama, email)
SELECT '240123456', 'Budi Santoso', 'budi@example.com'
WHERE NOT EXISTS (SELECT 1 FROM mahasiswa WHERE nim = '240123456');

-- Tabel buku untuk endpoint CRUD Acara 17/18
CREATE TABLE IF NOT EXISTS buku (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    judul VARCHAR(150) NOT NULL,
    penulis VARCHAR(100) NOT NULL
);

INSERT INTO buku (judul, penulis)
SELECT 'Contoh Buku', 'Penulis Contoh'
WHERE NOT EXISTS (SELECT 1 FROM buku WHERE judul = 'Contoh Buku' AND penulis = 'Penulis Contoh');
