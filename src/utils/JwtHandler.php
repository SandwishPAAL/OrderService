<?php

namespace VotreNamespace\utils;

use Firebase\JWT\JWT;
use Firebase\JWT\ExpiredException;

class JwtHandler
{
    private $jwt_secret;
    private $token;
    private $decoded;

    public function __construct($jwt_secret)
    {
        $this->jwt_secret = $jwt_secret;
    }

    public function encodeToken($data)
    {
        $issuedAt = time();
        $expire = $issuedAt + 3600;
        $payload = [
            'iat' => $issuedAt,
            'exp' => $expire,
            'data' => $data
        ];

        return JWT::encode($payload, $this->jwt_secret, 'HS256');
    }

    public function decodeToken($token)
    {
        try {
            $this->decoded = JWT::decode($token, $this->jwt_secret, ['HS256']);
        } catch (ExpiredException $e) {
            return 'Token has expired';
        } catch (\Exception $e) {
            return 'Invalid token';
        }

        return $this;
    }

    public function getDecoded()
    {
        return $this->decoded;
    }
}
