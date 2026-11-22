# Dokumentasi API Sistem Informasi Akademik (MVC)

**Base URL:** `http://localhost/bkpm/acara25/acara25_api_admin/public`

Seluruh response berformat JSON dengan struktur seragam:

```json
{
  "success": true,
  "message": "Pesan hasil proses",
  "data": null,
  "errors": [],
  "timestamp": "2026-10-26T10:30:00+07:00"
}
```

Autentikasi memakai JWT. Endpoint terproteksi wajib menyertakan header:
`Authorization: Bearer <token>` (diperoleh dari `POST /login`, berlaku 1 jam).

## 1. Peta Router

| Resource (URL) | Controller | Keterangan |
|---|---|---|
| `/mahasiswa` | `MahasiswaController` | CRUD data mahasiswa (index/store/update/destroy) |
| `/login` | `AuthController::login` | Autentikasi pengguna, menghasilkan token |
| `/register` | `AuthController::register` | Pendaftaran pengguna baru |

## 2. Ringkasan Endpoint

| No | Method | URL | Controller | Autentikasi | Status Sukses |
|---|---|---|---|---|---|
| 1 | POST | `/register` | `AuthController::register()` | Publik | 201 |
| 2 | POST | `/login` | `AuthController::login()` | Publik | 200 |
| 3 | GET | `/mahasiswa` | `MahasiswaController::index()` | Publik | 200 |
| 4 | GET | `/mahasiswa?id={id}` | `MahasiswaController::index()` | Publik | 200 |
| 5 | POST | `/mahasiswa` | `MahasiswaController::store()` | Bearer Token | 201 |
| 6 | PUT | `/mahasiswa?id={id}` | `MahasiswaController::update()` | Bearer Token | 200 |
| 7 | DELETE | `/mahasiswa?id={id}` | `MahasiswaController::destroy()` | Bearer Token | 200 |

## 3. Endpoint Autentikasi

### POST /register

Controller: `AuthController::register()` | Akses: publik

**Headers:** `Content-Type: application/json`

**Request Body:**
```json
{ "username": "budi", "email": "budi@mail.com", "password": "rahasia123" }
```

**Response 201 Created:**
```json
{ "success": true, "message": "Registrasi berhasil", "data": { "id": "1" }, "errors": [] }
```

**Response gagal:**

| Status | Penyebab | Contoh message |
|---|---|---|
| 400 | Body bukan JSON atau field kosong | Validasi data gagal |
| 409 | Username/email sudah terdaftar | Data sudah terdaftar |
| 422 | Format email tidak valid | Format email tidak valid |

### POST /login

Controller: `AuthController::login()` | Akses: publik

**Headers:** `Content-Type: application/json`

**Request Body:**
```json
{ "username": "budi", "password": "rahasia123" }
```

**Response 200 OK:**
```json
{
  "success": true,
  "message": "Login berhasil",
  "data": { "token": "eyJhbGciOiJIUzI1NiIs...", "role": "user" },
  "errors": []
}
```

**Response gagal:**

| Status | Penyebab | Contoh message |
|---|---|---|
| 400 | Body bukan JSON atau field kosong | Validasi data gagal |
| 401 | Username atau password salah | Username atau password salah |

## 4. Endpoint Mahasiswa

### GET /mahasiswa

Controller: `MahasiswaController::index()` | Akses: publik

**Response 200 OK:**
```json
{
  "success": true,
  "message": "Data mahasiswa berhasil diambil",
  "data": [
    { "id": 4, "nim": "230100123", "nama": "Budi Santoso", "prodi": "Teknik Informatika", "created_at": "2026-10-21 09:45:29" }
  ],
  "errors": []
}
```

### GET /mahasiswa?id={id}

Controller: `MahasiswaController::index()` | Akses: publik

**Query Parameter:** `id` (integer, angka positif)

**Response 200 OK:** `data` berisi satu objek mahasiswa.

**Response gagal:**

| Status | Penyebab |
|---|---|
| 400 | `id` bukan angka positif |
| 404 | Data mahasiswa tidak ditemukan |

### POST /mahasiswa

Controller: `MahasiswaController::store()` | Akses: **Bearer Token**

**Headers:** `Content-Type: application/json`, `Authorization: Bearer <token>`

**Request Body:**

| Field | Tipe | Wajib | Keterangan |
|---|---|---|---|
| nim | string | Ya | Tepat 9 karakter, unik |
| nama | string | Ya | |
| prodi | string | Ya | |

```json
{ "nim": "230100123", "nama": "Budi Santoso", "prodi": "Teknik Informatika" }
```

**Response 201 Created:**
```json
{ "success": true, "message": "Data mahasiswa berhasil ditambahkan", "data": { "id": "5" }, "errors": [] }
```

**Response gagal:**

| Status | Penyebab |
|---|---|
| 400 | Body bukan JSON atau field wajib kosong |
| 401 | Token tidak ditemukan, tidak valid, atau kedaluwarsa |
| 409 | NIM sudah digunakan |
| 422 | NIM tidak 9 karakter |

### PUT /mahasiswa?id={id}

Controller: `MahasiswaController::update()` | Akses: **Bearer Token**

**Headers:** `Content-Type: application/json`, `Authorization: Bearer <token>`

**Request Body:** sama dengan `POST /mahasiswa`.

**Response 200 OK:**
```json
{ "success": true, "message": "Data mahasiswa berhasil diperbarui", "data": null, "errors": [] }
```

**Response gagal:** 400 (id/body/field tidak valid), 401 (token), 404 (data tidak ditemukan), 409 (NIM dipakai mahasiswa lain), 422 (NIM tidak 9 karakter).

### DELETE /mahasiswa?id={id}

Controller: `MahasiswaController::destroy()` | Akses: **Bearer Token**

**Headers:** `Authorization: Bearer <token>`

**Response 200 OK:**
```json
{ "success": true, "message": "Data mahasiswa berhasil dihapus", "data": null, "errors": [] }
```

**Response gagal:** 400 (id tidak valid), 401 (token), 404 (data tidak ditemukan).

## 5. Kode Status Umum

| Status | Arti | Contoh |
|---|---|---|
| 200 | OK | Data berhasil diambil, diperbarui, dihapus |
| 201 | Created | Data berhasil dibuat |
| 400 | Bad Request | Body/ID/field tidak valid |
| 401 | Unauthorized | Token tidak ada, salah, atau kedaluwarsa |
| 404 | Not Found | Endpoint atau data tidak ditemukan |
| 405 | Method Not Allowed | Method tidak diizinkan pada endpoint |
| 409 | Conflict | Data sudah ada (NIM/username/email) |
| 422 | Unprocessable Entity | Format data salah (NIM, email) |
| 500 | Internal Server Error | Kesalahan server/database |