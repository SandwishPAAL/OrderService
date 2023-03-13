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
        $itemsOption = isset($rq->getQueryParams()["embed"]) && $rq->getQueryParams()["embed"] === "items" ? true : false;

        $order = $itemsOption ? OrderCommandeService::getById($args["id"]) : OrderCommandeService::getItems($args["id"]);

        $routeParser = RouteContext::fromRequest($rq)->getRouteParser();

        $data = [
            "type" => "resource",
            "order" => $order,
            "links" => [
                "items" => ["href" => $routeParser->urlFor("itemsByCommand", ["id" => $args["id"]])],
                "self" => ["href" => $routeParser->urlFor("orderById", ["id" => $args["id"]])]
            ]
        ];
        $rs = $rs->withHeader('Content-type', 'application/json');
        $rs->getBody()->write(json_encode($data));

        return $rs;
    }
}
