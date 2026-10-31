<?php

class Auth
{
    const SECRET = 'kunci_rahasia_workshop_2026';

    private static function base64urlEncode(string $data): string
    {
        return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
    }

    private static function base64urlDecode(string $data): string
    {
        return base64_decode(strtr($data, '-_', '+/'));
    }

    public static function generateToken(array $payload): string
    {
        $header = self::base64urlEncode(json_encode(['alg' => 'HS256', 'typ' => 'JWT']));

        $payload['exp'] = time() + 3600; // masa berlaku 1 jam

        $payloadEnc = self::base64urlEncode(json_encode($payload));
        $signature = self::base64urlEncode(
            hash_hmac('sha256', "$header.$payloadEnc", self::SECRET, true)
        );

        return "$header.$payloadEnc.$signature";
    }

    public static function validateToken(string $token)
    {
        $parts = explode('.', $token);
        if (count($parts) !== 3) {
            return false;
        }

        [$header, $payload, $signature] = $parts;

        $valid = self::base64urlEncode(
            hash_hmac('sha256', "$header.$payload", self::SECRET, true)
        );
        if (!hash_equals($valid, $signature)) {
            return false;
        }

        $data = json_decode(self::base64urlDecode($payload), true);
        if (!is_array($data) || !isset($data['exp']) || $data['exp'] < time()) {
            return false;
        }

        return $data;
    }

    private static function getBearerToken(): ?string
    {
        $header = '';

        if (function_exists('getallheaders')) {
            foreach (getallheaders() as $name => $value) {
                if (strtolower($name) === 'authorization') {
                    $header = $value;
                    break;
                }
            }
        }

        if ($header === '') {
            $header = $_SERVER['HTTP_AUTHORIZATION']
                ?? $_SERVER['REDIRECT_HTTP_AUTHORIZATION']
                ?? '';
        }

        if (preg_match('/^Bearer\s+(\S+)$/i', trim($header), $m)) {
            return $m[1];
        }

        return null;
    }

    public static function requireAuth(): array
    {
        $token = self::getBearerToken();

        if ($token === null) {
            Response::json(401, false, 'Token tidak ditemukan');
        }

        $payload = self::validateToken($token);

        if (!$payload) {
            Response::json(401, false, 'Token tidak valid atau kedaluwarsa');
        }

        return $payload;
    }
}