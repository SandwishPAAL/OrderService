<?php

namespace lbs\actions;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Firebase\JWT\JWT;
use Firebase\JWT\ExpiredException;

class RefreshTokenAction
{
    private $jwt_secret = "3fa8f92857a74abb950df8ce83a7d2ee";

    public function __invoke(Request $request, Response $response)
    {
        $data = $request->getParsedBody();
        $refreshToken = $data['refreshToken'] ?? null;

        if (!$refreshToken) {
            return $response->withStatus(401)->withHeader('Content-Type', 'application/json');
        }

        try {
            $decoded = JWT::decode($refreshToken, $this->jwt_secret, ['HS256']);
        } catch (ExpiredException $e) {
            return $response->withStatus(401)->withHeader('Content-Type', 'application/json');
        }


        $issuedAt = time();
        $expirationTime = $issuedAt + 60;
        $payload = array(
            "iat" => $issuedAt,
            "exp" => $expirationTime,
            "data" => [
                "userId" => $decoded->data->userId
            ]
        );

        $newAccessToken = JWT::encode($payload, $this->jwt_secret, "HS256");

        $response->getBody()->write(json_encode(["token" => $newAccessToken]));

        return $response
            ->withHeader("Content-Type", "application/json")
            ->withStatus(200);
    }
}
