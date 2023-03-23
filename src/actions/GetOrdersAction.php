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
        $page = $rq->getQueryParams()["page"] ?? 1;
        $sort = $rq->getQueryParams()["sort"] ?? null;
        $size = $rq->getQueryParams()["size"] ?? 10;

        try {
            $orders = OrderCommandeService::getAll($client, $page, $size, $sort);
        } catch (HttpNotFoundException $e) {
            throw $e;
        }

        $routeParser = RouteContext::fromRequest($rq)->getRouteParser();

        $data = [
            "type" => "collection",
            "count" => $orders["totalCount"],
            "size" => count($orders["items"]),
            "links" => [
                "next" => [
                    "href" => $routeParser->urlFor("orders", [], ["page" => $page + 1 > ($orders["totalCount"] / count($orders["items"])) ? $orders["pageNumberMax"] : $page + 1])
                ],
                "prev" => [
                    "href" => $routeParser->urlFor("orders", [], ["page" => $page - 1  < 1 ? 1 : $page - 1])
                ],
                "last" => [
                    "href" => $routeParser->urlFor("orders", [], ["page" => $orders["pageNumberMax"]])
                ],
                "first" => [
                    "href" => $routeParser->urlFor("orders", [], ["page" => 1])
                ]
            ],
            "orders" => []
        ];

        foreach ($orders["items"] as $key => $order) {
            $data["orders"][$key]["order"] = $order;
            $data["orders"][$key]["order"]["links"]["self"]["href"] =  $routeParser->urlFor("orderById", ["id" => $order["id"]]);
        }



        $rs = $rs->withHeader('Content-type', 'application/json');
        $rs->getBody()->write(json_encode($data, JSON_OBJECT_AS_ARRAY | JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));

        return $rs;
    }
}
