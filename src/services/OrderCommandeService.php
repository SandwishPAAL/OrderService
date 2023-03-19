<?php

namespace lbs\order\services;

// use Illuminate\Database\Capsule\Manager as DB;
use Illuminate\Database\Eloquent\ModelNotFoundException;

// $db = new DB();
// $db->addConnection([
//     'driver' => 'mysql',
//     'host' => 'order.db',
//     'database' => 'order_lbs',
//     'username' => 'order_lbs',
//     'password' => 'order_lbs',
//     'charset' => 'utf8',
//     'collation' => 'utf8_unicode_ci',
//     'prefix' => ''
// ]);
// $db->setAsGlobal();
// $db->bootEloquent();

use lbs\order\models\Commande;


class OrderCommandeService
{
    public static function getAll($client, int $page)
    {

        $items_per_page = 10;

        if (is_null($page)) {
            $page = 1;
        }

        $query = Commande::select('id', 'mail as client_mail', 'nom as client_nom', 'created_at as order_date', 'livraison as delivery_date', 'montant as total_amount');

        if ($client) {
            $query = $query->where('nom', '=', $client);
        }

        $orders_number = $query->count();

        $pages = ceil($orders_number / $items_per_page);

        $premier = ($page * $items_per_page) - $items_per_page;

        try {
            return $query->offset($premier)->limit($items_per_page)->get()->toArray();
        } catch (ModelNotFoundException $e) {
            throw $e;
        }
    }

    public static function getById(string $id, string $embed)
    {
        // return Commande::select('id', 'mail as client_mail', 'nom as client_nom', 'created_at as order_date', 'livraison as delivery_date', 'montant as total_amount')->where('id', '=', $id)->first()->toArray();
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

    public static function update(string $id, array $data)
    {
        try {
            $order = Commande::find($id);
            $order->nom = filter_var($data["client_nom"], FILTER_SANITIZE_SPECIAL_CHARS);
            $order->mail = filter_var($data["client_mail"], FILTER_SANITIZE_EMAIL);
            $order->created_at = filter_var($data["order_date"], FILTER_SANITIZE_SPECIAL_CHARS);
            $order->livraison = filter_var($data["delivery_date"], FILTER_SANITIZE_SPECIAL_CHARS);

            return $order->save();
        } catch (\Throwable $ex) {
            throw $ex;
        }
    }

    public static function getItems(string $id)
    {
        return Commande::select('id', 'mail as client_mail', 'nom as client_nom', 'created_at as order_date', 'livraison as delivery_date', 'montant as total_amount')->where('id', '=', $id)->first()->items()->get()->toArray();
    }
}
