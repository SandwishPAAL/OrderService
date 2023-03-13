<?php

namespace lbs\order\actions;

use Slim\Psr7\Request;
use Slim\Psr7\Response;
use lbs\order\services\OrderCommandeService;

final class GetOrderByIdAction
{
    public function __invoke(Request $rq, Response $rs, mixed $args)
    {
        $itemsOption = isset($rq->getQueryParams()["embed"]) ? true : false;

        $order = $itemsOption ? OrderCommandeService::getById($args["id"]) : OrderCommandeService::getItems($args["id"]);

        $rs = $rs->withHeader('Content-type', 'application/json');
        $rs->getBody()->write($order);

        return $rs;
    }
}
