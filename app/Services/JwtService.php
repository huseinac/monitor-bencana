<?php

namespace App\Services;

class JwtService {

    public function generateJwt($payload, $secret = 'your-secret-key', $remember = false) {
        $header = json_encode(['typ' => 'JWT', 'alg' => 'HS256']);
        $defaultPayload = [
            'iat' => time(),
            'exp' => time() + ($remember === true ? (365 * 24 * 60 * 60) : (3 * 24 * 60 * 60)),
        ];
        $payload = array_merge($defaultPayload, $payload);

        $base64UrlHeader = $this->base64UrlEncode($header);
        $base64UrlPayload = $this->base64UrlEncode(json_encode($payload));
        $signature = hash_hmac('sha256', $base64UrlHeader . "." . $base64UrlPayload, $secret, true);
        $base64UrlSignature = $this->base64UrlEncode($signature);
        return $base64UrlHeader . "." . $base64UrlPayload . "." . $base64UrlSignature;
    }

    public function base64UrlEncode($data) {
        return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
    }

    function verifyJwt($jwt, $secret = 'your-secret-key') {
        $parts = explode('.', $jwt);
        if (count($parts) !== 3) return false;

        list($header, $payload, $signature) = $parts;
        $verifiedSignature = $this->base64UrlEncode(hash_hmac('sha256', $header . "." . $payload, $secret, true));

        if ($signature !== $verifiedSignature) return false;
        $decodedPayload = json_decode($this->base64UrlDecode($payload), true);

        if (isset($decodedPayload['exp']) && $decodedPayload['exp'] < time()) return false;
        return $decodedPayload;
    }

    public function base64UrlDecode($data) {
        return base64_decode(str_pad(strtr($data, '-_', '+/'), strlen($data) % 4, '=', STR_PAD_RIGHT));
    }

}
