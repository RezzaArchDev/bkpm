# Acara 17 — API CRUD (PHP Native MVC)

Proyek latihan berdasarkan Acara 17 BKPM. Berisi CRUD API untuk `mahasiswa` dan resource tambahan `buku`, validasi NIM duplikat, serta contoh dokumentasi endpoint.

## 1. Instalasi
1. Ekstrak folder `acara17_api_crud` ke `C:\xampp\htdocs\`.
2. Jalankan Apache dan MySQL dari XAMPP.
3. Buka `http://localhost/phpmyadmin`.
4. Pilih tab **Import**, lalu impor file `db_workshop.sql`.
5. Pastikan konfigurasi database di `app/config/Database.php` sesuai. Default XAMPP: user `root`, password kosong.
6. Jika rewrite URL tidak bekerja, pastikan `mod_rewrite` aktif dan Apache mengizinkan `.htaccess`.

## 2. Base URL
`http://localhost/acara17_api_crud/public`

Contoh endpoint:
- `GET /mahasiswa`
- `GET /mahasiswa?id=1`
- `POST /mahasiswa`
- `PUT /mahasiswa?id=1`
- `DELETE /mahasiswa?id=1`
- `GET /buku`
- `GET /buku?id=1`
- `POST /buku`
- `PUT /buku?id=1`
- `DELETE /buku?id=1`

## 3. Contoh request POST mahasiswa
Pilih Body > raw > JSON pada Postman:
```json
{
  "nim": "2301001",
  "nama": "Nama Mahasiswa",
  "prodi": "Teknik Informatika"
}
```

## 4. Contoh request POST buku
```json
{
  "judul": "Belajar PHP",
  "penulis": "Nama Penulis",
  "tahun_terbit": 2024
}
```

## 5. Catatan
- Response dikirim dalam format JSON.
- NIM duplikat ditolak dengan status HTTP `409 Conflict`.
- Pada Acara 17, response JSON masih dibuat langsung oleh Controller/Router; class `Response.php` baru diperkenalkan pada Acara 18.
- Pastikan URL endpoint di Postman menggunakan base URL yang sesuai dengan nama folder tempat proyek diekstrak.
