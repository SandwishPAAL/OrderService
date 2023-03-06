<?php

namespace lbs\order\actions;

use Slim\Psr7\Request;
use Slim\Psr7\Response;
use lbs\order\services\OrderCommandeService;

final class GetOrdersAction
{
    public function __invoke(Request $rq, Response $rs, mixed $args)
    {
        $orders = OrderCommandeService::getAll();
        $rs = $rs->withHeader('Content-type', 'application/json');
        $rs->getBody()->write($orders);

        return $rs;
    }
}
