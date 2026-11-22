
<?php

class Database
{
    private static ?PDO $connection = null;

    public static function getConnection(): PDO
    {
        if (self::$connection === null) {
            // Konfigurasi Database Hosting Rumahweb
            $host = 'localhost';
            $db = 'arcr5441_rezzaarchdev';
            $username = 'arcr5441_rezzaarchdev';
            $password = 'rezzaarchdev160407';
            $charset = 'utf8mb4';

            $dsn = "mysql:host={$host};dbname={$db};charset={$charset}";

            $options = [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            ];

            try {
                self::$connection = new PDO(
                    $dsn,
                    $username,
                    $password,
                    $options
                );
            } catch (PDOException $e) {
                error_log('Database connection error: ' . $e->getMessage());

                Response::json(
                    500,
                    false,
                    'Koneksi database gagal. Periksa konfigurasi database hosting.'
                );
            }
        }

        return self::$connection;
    }
}
