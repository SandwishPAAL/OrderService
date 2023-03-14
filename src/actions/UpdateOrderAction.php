<?php

namespace lbs\order\actions;

use lbs\order\services\OrderCommandeService;
use Slim\Exception\HttpBadRequestException;
use Slim\Psr7\Request;
use Slim\Psr7\Response;

final class UpdateOrderAction
{

    public function __invoke(Request $rq, Response $rs, mixed $args): Response
    {


        $data = $rq->getParsedBody();

        // if ($data) {

        // }
        $rs = $rs->withHeader('Content-type', 'application/json');
        $rs->getBody()->write(json_encode($data["client_nom"]));
        return $rs;
    }
}
