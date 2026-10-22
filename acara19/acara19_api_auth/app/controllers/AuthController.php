<?php

class AuthController
{
    private User $model;

    public function __construct()
    {
        $this->model = new User();
    }

    // POST /register
    public function register(): void
    {
        $data = json_decode(file_get_contents('php://input'), true);

        if (!is_array($data)) {
            Response::json(
                400,
                false,
                'Request body harus berupa JSON yang valid',
                null,
                ['body' => 'Periksa format JSON']
            );
        }

        // 1) Field wajib -> 400
        $errors = [];
        foreach (['username', 'email', 'password'] as $field) {
            if (!isset($data[$field]) || trim((string) $data[$field]) === '') {
                $errors[$field] = "$field wajib diisi";
            }
        }
        if (!empty($errors)) {
            Response::json(400, false, 'Validasi data gagal', null, $errors);
        }

        $username = trim((string) $data['username']);
        $email    = trim((string) $data['email']);
        $password = (string) $data['password'];

        // 2) Username / email sudah dipakai -> 409
        if ($this->model->findByUsername($username)) {
            $errors['username'] = 'Username sudah digunakan';
        }
        if ($this->model->findByEmail($email)) {
            $errors['email'] = 'Email sudah digunakan';
        }
        if (!empty($errors)) {
            Response::json(409, false, 'Data sudah terdaftar', null, $errors);
        }

        // 3) Simpan. Password di-hash, role selalu default 'user'
        try {
            $id = $this->model->create([
                'username' => $username,
                'email'    => $email,
                'password' => password_hash($password, PASSWORD_DEFAULT),
            ]);
        } catch (InvalidArgumentException $e) {
            Response::json(
                422,
                false,
                $e->getMessage(),
                null,
                ['email' => 'Gunakan format email yang valid, contoh: nama@domain.com']
            );
        } catch (PDOException $e) {
            Response::json(500, false, 'Terjadi kesalahan pada server');
        }

        Response::json(201, true, 'Registrasi berhasil', ['id' => $id]);
    }
}