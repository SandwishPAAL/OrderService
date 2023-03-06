<?php

namespace lbs\order\services;

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

use lbs\order\models\Commande;

class OrderCommandeService
{
    public static function getAll()
    {
        return Commande::select('id', 'mail as client_mail', 'nom as client_nom', 'created_at as order_date', 'livraison as delivery_date', 'montant as total_amount')->get()->toJson(JSON_PRETTY_PRINT);
    }

    public static function getById(string $id)
    {
        return Commande::select('id', 'mail as client_mail', 'nom as client_nom', 'created_at as order_date', 'livraison as delivery_date', 'montant as total_amount')->where('id', '=', $id)->first()->toJson(JSON_PRETTY_PRINT);
    }
}
