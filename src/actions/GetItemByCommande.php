<?php

namespace lbs\order\actions;

use Slim\Psr7\Request;
use Slim\Psr7\Response;
use lbs\order\services\OrderCommandeService;

final class GetItemByCommande
{
    public function __invoke(Request $rq, Response $rs, mixed $args)
    {
        $order = OrderCommandeService::getItems($args["id"]);
        $data = [
            "type" => "collection",
            "count" => count($order),
            "items" => $order
        ];
        $rs = $rs->withHeader('Content-type', 'application/json');
        $rs->getBody()->write(json_encode($data));

        return $rs;
    }
}
