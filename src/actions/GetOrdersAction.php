<?php

namespace lbs\order\actions;

use Slim\Psr7\Request;
use Slim\Psr7\Response;
use Slim\Routing\RouteContext;
use Slim\Exception\HttpNotFoundException;
use lbs\order\services\OrderCommandeService;

final class GetOrdersAction
{
    public function __invoke(Request $rq, Response $rs, mixed $args)
    {
        $client = $rq->getQueryParams()["c"] ?? null;

        try {
            $orders = OrderCommandeService::getAll($client);
        } catch (HttpNotFoundException $e) {
            throw $e;
        }

        $routeParser = RouteContext::fromRequest($rq)->getRouteParser();

        $data = [
            "type" => "collection",
            "count" => count($orders),
            "orders" => []
        ];

        foreach ($orders as $key => $order) {
            $data["orders"][$key]["order"] = $order;
            $data["orders"][$key]["order"]["links"]["self"]["href"] =  $routeParser->urlFor("orderById", ["id" => $order["id"]]);
        }



        $rs = $rs->withHeader('Content-type', 'application/json');
        $rs->getBody()->write(json_encode($data, JSON_OBJECT_AS_ARRAY | JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));

        return $rs;
    }
}
