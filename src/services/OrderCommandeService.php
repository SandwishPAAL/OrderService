<?php

namespace lbs\order\services;

use Exception;
use Illuminate\Database\Capsule\Manager as DB;
use Illuminate\Database\Eloquent\ModelNotFoundException;

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
use Slim\Exception\HttpNotFoundException;
use Throwable;

class OrderCommandeService
{
    public static function getAll()
    {
        return Commande::select('id', 'mail as client_mail', 'nom as client_nom', 'created_at as order_date', 'livraison as delivery_date', 'montant as total_amount')->get()->toJson(JSON_PRETTY_PRINT);
    }

    public static function getById(string $id, string $embed)
    {
        $query = Commande::select('id', 'mail as client_mail', 'nom as client_nom', 'created_at as order_date', 'livraison as delivery_date', 'montant as total_amount')->where('id', '=', $id);
        if ($embed === 'items') {
            $query = $query->with('items');
        }

        try {
            return $query->firstOrFail()->toArray();
        } catch (ModelNotFoundException $e) {
            throw $e;
        }
    }

    public static function getItems(string $id)
    {
        return Commande::select('id', 'mail as client_mail', 'nom as client_nom', 'created_at as order_date', 'livraison as delivery_date', 'montant as total_amount')->where('id', '=', $id)->first()->items()->get()->toArray();
    }
}
