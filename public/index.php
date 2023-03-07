<?php

declare(strict_types=1);

require_once __DIR__ . '/../vendor/autoload.php';

use lbs\order\actions\GetItemByCommande;
use lbs\order\actions\GetOrderByIdAction;
use lbs\order\actions\GetOrdersAction;
use lbs\order\actions\UpdateOrderAction;
use Slim\Factory\AppFactory;

$app = AppFactory::create();
$app->addRoutingMiddleware();
$app->addBodyParsingMiddleware();
$app->addErrorMiddleware(true, false, false);

/**
 * configuring API Routes
 */

$app->get('/orders', GetOrdersAction::class);

$app->get('/orders/{id}', GetOrderByIdAction::class);

$app->get('/orders/{id}/items', GetItemByCommande::class);

$app->put('/orders/{id}', UpdateOrderAction::class);

$app->run();
