ACARA 18 - API CRUD MAHASISWA

CARA MEMASANG DI XAMPP
1. Ekstrak folder acara18_api_response ke:
   C:\xampp\htdocs\bkpm\acara18\
   Hasil akhirnya harus:
   C:\xampp\htdocs\bkpm\acara18\acara18_api_response\public\index.php

2. Jalankan Apache dan MySQL dari XAMPP.
3. Buka phpMyAdmin: http://localhost/phpmyadmin
4. Import file database/db_workshop_mahasiswa.sql.
   Catatan: kalau database db_workshop dan tabel mahasiswa sudah ada dengan struktur berbeda,
   backup dahulu dan sesuaikan struktur sebelum import. Jangan hapus database tugas lain.
5. Tes GET:
   http://localhost/bkpm/acara18/acara18_api_response/public/mahasiswa

POST / PUT body (Postman > Body > raw > JSON):
{
  "nim": "240123457",
  "nama": "Siti Aminah",
  "email": "siti@example.com"
}

ENDPOINT
GET    /mahasiswa
POST   /mahasiswa
PUT    /mahasiswa/1
DELETE /mahasiswa/1

Catatan: ini paket proyek mandiri yang konsisten untuk Acara 18. Sebelum mengganti proyek lama,
backup folder yang sekarang. Kode ini menggunakan tabel mahasiswa dengan kolom id, nim, nama, email.

ENDPOINT BUKU (tetap disertakan)
GET     /buku
POST    /buku
PUT     /buku/{id}
DELETE  /buku/{id}

Body JSON untuk POST/PUT buku:
{
  "judul": "Belajar PHP",
  "penulis": "Budi Santoso"
}

Database SQL juga membuat tabel buku jika belum ada.
