<?php

namespace lbs\order\actions;

use Slim\Psr7\Request;
use Slim\Psr7\Response;
use Slim\Routing\RouteContext;
use lbs\order\services\OrderCommandeService;
use Slim\Exception\HttpBadRequestException;
use Slim\Exception\HttpNotFoundException;
use Throwable;

final class GetOrderByIdAction
{
    public function __invoke(Request $rq, Response $rs, mixed $args)
    {
        $embed = $rq->getQueryParams()["embed"] ?? null;

        try {
            $order = OrderCommandeService::getById($args["id"], $embed);
        } catch (HttpNotFoundException $e) {
            throw $e;
        }

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
        $rs->getBody()->write(json_encode($data, JSON_PRETTY_PRINT));

        return $rs;
    }
}
