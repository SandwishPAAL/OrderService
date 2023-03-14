<?php

namespace lbs\order\actions;

use Slim\Psr7\Request;
use Slim\Psr7\Response;
use Slim\Routing\RouteContext;
use lbs\order\services\OrderCommandeService;

final class GetOrderByIdAction
{
    public function __invoke(Request $rq, Response $rs, mixed $args)
    {
        $routeParser = RouteContext::fromRequest($rq)->getRouteParser();

        $order = OrderCommandeService::getById($args["id"]);
        $data = [
            "type" => "resource",
            "order" => $order,
            "links" => [
                "items" => ["href" => $routeParser->urlFor("itemsByCommand", ["id" => $args["id"]])],
                "self" => ["href" => $routeParser->urlFor("orderById", ["id" => $args["id"]])]
            ]
        ];
        $rs = $rs->withHeader('Content-type', 'application/json');
        $rs->getBody()->write(json_encode($data, JSON_OBJECT_AS_ARRAY | JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));

        return $rs;
    }
}
