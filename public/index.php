<?php

declare(strict_types=1);

require_once __DIR__ . '/../vendor/autoload.php';

use lbs\order\actions\GetItemByCommande;
use lbs\order\actions\GetOrderByIdAction;
use lbs\order\actions\GetOrdersAction;
use lbs\order\actions\UpdateOrderAction;
use lbs\actions\AuthAction;
use lbs\actions\RefreshTokenAction;
use lbs\middleware\JwtMiddleware;
use lbs\order\errors\renderer\JsonErrorRenderer;
use Illuminate\Database\Capsule\Manager as DB;

$db = new DB();
$db->addConnection([
    'driver' => 'mysql',
    'host' => 'order.db',
    'database' => 'order_lbs',
    'username' => 'order_lbs',
    'password' => 'order_lbs',
    'charset' => 'utf8',
    'collation' => 'utf8_unicode_ci',
    'prefix' => ''
]);
$db->setAsGlobal();
$db->bootEloquent();


$app = AppFactory::create();
$app->addRoutingMiddleware();
$app->addBodyParsingMiddleware();
$errorMiddleware = $app->addErrorMiddleware(true, false, false);
$errorMiddleware->getDefaultErrorHandler()->forceContentType('application/json');
$errorMiddleware->getDefaultErrorHandler()->registerErrorRenderer('application/json', JsonErrorRenderer::class);

$app->add(new JwtMiddleware("3fa8f92857a74abb950df8ce83a7d2ee"));

/**
 * configuring API Routes
 */

$app->post('/auth', AuthAction::class);
$app->post('/refresh', RefreshTokenAction::class);

$app->get('/orders', GetOrdersAction::class)->setName('orders');

$app->get('/orders/{id}', GetOrderByIdAction::class)->setName('orderById');

$app->get('/orders/{id}/items', GetItemByCommande::class)->setName('itemsByCommand');

$app->put('/orders/{id}', UpdateOrderAction::class)->setName('orderUpdate');

$app->run();
