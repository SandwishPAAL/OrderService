<?php

namespace lbs\order\actions;

use Slim\Psr7\Request;
use Slim\Psr7\Response;
use lbs\order\services\OrderCommandeService;

final class GetOrderByIdAction
{
    public function __invoke(Request $rq, Response $rs, mixed $args)
    {
        $order = OrderCommandeService::getById($args["id"]);
        $data = [
            "type" => "collection",
            "count" => count($order),
            "order" => $order,
        ];
        $rs = $rs->withHeader('Content-type', 'application/json');
        $rs->getBody()->write(json_encode($data));

        return $rs;
    }
}
