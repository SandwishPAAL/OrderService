<?php

declare(strict_types=1);

require_once __DIR__ . '/../vendor/autoload.php';

use Slim\Factory\AppFactory;
use lbs\order\actions\GetOrdersAction;
use lbs\order\actions\UpdateOrderAction;
use lbs\order\actions\GetOrderByIdAction;
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



/**
 * configuring API Routes
 */

$app->get('/orders', GetOrdersAction::class);

$app->get('/orders/{id}', GetOrderByIdAction::class);

$app->put('/orders/{id}', UpdateOrderAction::class);

$app->run();