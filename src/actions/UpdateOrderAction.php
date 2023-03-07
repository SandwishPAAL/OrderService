<?php

namespace lbs\order\actions;

use lbs\order\errors\exceptions\HttpNoContentException;
use Slim\Psr7\Request;
use Slim\Psr7\Response;
use lbs\order\models\Commande;

use Slim\Exception\HttpInternalServerErrorException;
use Slim\Exception\HttpMethodNotAllowedException;
use Slim\Exception\HttpNotFoundException;

final class UpdateOrderAction
{

    public function __invoke(Request $rq, Response $rs, mixed $args): void
    {
        $rq->getMethod() != "PUT" ?? throw new HttpMethodNotAllowedException($rq, "Methode non autorisée");
        $data = $rq->getParsedBody() ?? throw new HttpNotFoundException($rq, "Ressource non disponible : " . $rq->getUri()->getPath() . "");


        if (isset($data["client_nom"]) && isset($data["client_mail"]) && isset($data["order_date"]) && isset($data["delivery_date"])) {

            $order = Commande::find($args["id"]) ?? throw new HttpNotFoundException($rq, "ressource non disponible 2 : " . $rq->getUri()->getPath() . "");

            $order->nom = filter_var($data["client_nom"], FILTER_SANITIZE_SPECIAL_CHARS);
            $order->mail = filter_var($data["client_mail"], FILTER_SANITIZE_EMAIL);
            $order->created_at = filter_var($data["order_date"], FILTER_SANITIZE_SPECIAL_CHARS);
            $order->livraison = filter_var($data["delivery_date"], FILTER_SANITIZE_SPECIAL_CHARS);
            if ($order->save()) {
                throw new HttpNoContentException($rq);
            } else {
                throw new HttpInternalServerErrorException($rq, "La ressource n'a pû être enregistée");
            }
        } else {
            throw new HttpInternalServerErrorException($rq, "La ressource n'a pû être enregistée");
        }
    }
}
