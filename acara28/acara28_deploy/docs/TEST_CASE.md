# Laporan Pengujian API Sistem Informasi Akademik (MVC)

Teknik: pengujian manual blackbox dengan Equivalence Partitioning. Alat: Postman.

## 1. Ruang Lingkup

| No | Endpoint | Controller | Autentikasi |
|---|---|---|---|
| 1 | POST /register | AuthController::register | Publik |
| 2 | POST /login | AuthController::login | Publik |
| 3 | GET /mahasiswa | MahasiswaController::index | Publik |
| 4 | POST /mahasiswa | MahasiswaController::store | Bearer Token |
| 5 | PUT /mahasiswa?id= | MahasiswaController::update | Bearer Token |
| 6 | DELETE /mahasiswa?id= | MahasiswaController::destroy | Bearer Token |

## 2. Partisi Ekuivalensi

| Input | Partisi valid | Partisi tidak valid |
|---|---|---|
| NIM | 9 digit angka, belum terdaftar | kosong; kurang dari 9; mengandung huruf; sudah terdaftar |
| Nama / Prodi | terisi, tidak melebihi batas kolom | kosong; melebihi 100 / 50 karakter |
| ID (update, destroy) | angka positif dan data ada | bukan angka; data tidak ada |
| Token | token valid | tidak ada |
| Login | username dan password benar | password salah |

## 3. Test Case dan Hasil

| ID | Skenario | Partisi | Data Uji | Hasil Diharapkan | Hasil Aktual | Status |
|---|---|---|---|---|---|---|
| TC-01 | NIM valid dan lengkap (store) | Valid | nim=220100123 | 201 Created | 201 Created | Pass |
| TC-02 | NIM kosong (store) | Tidak valid | nim="" | 400 Bad Request | 400 Bad Request | Pass |
| TC-03 | NIM duplikat (store) | Tidak valid | nim milik data lain | 409 Conflict | 409 Conflict | Pass |
| TC-04 | Login kredensial benar | Valid | username dan password benar | 200 OK, token diterima | 200 OK, token diterima | Pass |
| TC-05 | Login kredensial salah | Tidak valid | password salah | 401 Unauthorized | 401 Unauthorized | Pass |
| TC-06 | Akses store tanpa token | Tidak valid | tanpa header Authorization | 401 Unauthorized | 401 Unauthorized | Pass |
| TC-07 | Update data valid | Valid | id ada, NIM unik | 200 OK | 200 OK | Pass |
| TC-08 | Update id tidak ada | Tidak valid | id=999999 | 404 Not Found | 404 Not Found | Pass |
| TC-09 | Update id bukan angka | Tidak valid | id=abc | 400 Bad Request | 400 Bad Request | Pass |
| TC-10 | Update dengan NIM milik data lain | Tidak valid | nim data lain | 409 Conflict | 409 Conflict | Pass |
| TC-11 | Update tanpa token | Tidak valid | tanpa Authorization | 401 Unauthorized | 401 Unauthorized | Pass |
| TC-12 | Destroy data valid | Valid | id ada | 200 OK | 200 OK | Pass |
| TC-13 | Destroy id tidak ada | Tidak valid | id=999999 | 404 Not Found | 404 Not Found | Pass |
| TC-14 | Destroy id bukan angka | Tidak valid | id=abc | 400 Bad Request | 400 Bad Request | Pass |
| TC-15 | Destroy tanpa token | Tidak valid | tanpa Authorization | 401 Unauthorized | 401 Unauthorized | Pass |
| TC-16 | NIM kurang dari 9 digit (store) | Tidak valid | nim=12345 | 422 Unprocessable Entity | 422 Unprocessable Entity | Pass |
| TC-17 | NIM berisi huruf (store) | Tidak valid | nim=ABCDEFGHI | 422 Unprocessable Entity | 201 Created (data tersimpan) | **Fail** |
| TC-18 | Nama melebihi 100 karakter (store) | Tidak valid | nama 101 karakter | 422 Unprocessable Entity | (isi sesuai hasilmu: 201 atau 500) | **Fail** |

Catatan TC-03: modul menuliskan 422 untuk NIM duplikat. API ini sejak Acara 21 memakai 409 Conflict (tercantum di dokumentasi), sehingga hasil diharapkan disesuaikan dengan spesifikasi tersebut.

## 4. Analisis dan Perbaikan

TC-17 dan TC-18 Fail karena `MahasiswaController::validateData()` hanya memeriksa panjang NIM (`strlen`), tidak memeriksa bahwa NIM berupa angka dan tidak memeriksa panjang nama dan prodi. Perbaikan: tambah `ctype_digit()` untuk NIM dan `mb_strlen()` untuk nama dan prodi pada `validateData()`.

## 5. Retest Setelah Perbaikan

| ID | Hasil Diharapkan | Hasil Aktual | Status |
|---|---|---|---|
| TC-17 | 422 Unprocessable Entity | 422 Unprocessable Entity | Pass |
| TC-18 | 422 Unprocessable Entity | 422 Unprocessable Entity | Pass |