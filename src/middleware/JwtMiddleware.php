<?php

namespace lbs\middleware;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Handler\RequestHandlerInterface as RequestHandler;
use Psr\Http\Message\ResponseInterface as Response;
use Firebase\JWT\JWT;
use Firebase\JWT\ExpiredException;

class JwtMiddleware
{
    private $jwt_secret = "3fa8f92857a74abb950df8ce83a7d2ee";

    public function __invoke(Request $request, RequestHandler $handler): Response
    {
        $token = $request->getHeaderLine("Authorization");

        if (!$token) {
            $response = new \Slim\Psr7\Response();
            return $response->withStatus(401);
        }

        try {
            JWT::decode($token, $this->jwt_secret, ["HS256"]);
        } catch (ExpiredException $e) {
            $response = new \Slim\Psr7\Response();
            return $response->withStatus(401);
        } catch (\Exception $e) {
            $response = new \Slim\Psr7\Response();
            return $response->withStatus(401);
        }

        $response = $handler->handle($request);
        return $response;
    }
}
