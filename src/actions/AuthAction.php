<?php

namespace lbs\actions;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Firebase\JWT\JWT;

class AuthAction
{
    private $jwt_secret = "3fa8f92857a74abb950df8ce83a7d2ee";

    public function __invoke(Request $request, Response $response)
    {
        $data = $request->getParsedBody();
        $issuedAt = time();
        $expirationTime = $issuedAt + 60;
        $payload = array(
            "iat" => $issuedAt,
            "exp" => $expirationTime,
            "data" => [
                "userId" => 1 //Ceci est un example
            ]
        );

        $token_jwt = JWT::encode($payload, $this->jwt_secret, "HS256");

        $response->getBody()->write(json_encode(["token" => $token_jwt]));

        return $response
            ->withHeader("Content-Type", "application/json")
            ->withStatus(200);
    }
}
