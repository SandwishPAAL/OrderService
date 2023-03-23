<?php

namespace lbs\order\services;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use lbs\order\models\Commande;

class OrderCommandeService
{
    public static function getAll(string $client, int $page, int $items_per_page, string $sort) : array
    {
        $query = Commande::select('id', 'mail as client_mail', 'nom as client_nom', 'created_at as order_date', 'livraison as delivery_date', 'montant as total_amount');

        if ($client) {
            $query = $query->where('nom', '=', $client);
        }
        if ($sort === "date") {
            $query = $query->orderBy('order_date', 'DESC');
        }
        if ($sort === "amount") {
            $query = $query->orderBy('total_amount', 'DESC');
        }


        $page = is_null($page) ?? 1;

        $page = $page > ceil($query->count() / $items_per_page) ? ceil($query->count() / $items_per_page) : $page;

        if ($page > ceil($query->count() / $items_per_page)) {
            $page = ceil($query->count() / $items_per_page);
        }


        try {
            $data = [
                "pageNumberMax" => ceil($query->count() / $items_per_page),
                "totalCount" => $query->count(),
                "items" => $query->forPage($page, $items_per_page)->get()->toArray()
            ];
            return $data;
        } catch (ModelNotFoundException $e) {
            throw $e;
        }
    }

    public static function getById(string $id, string $embed) : array
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

    public static function update(string $id, array $data) : void
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

    public static function getItems(string $id) : array
    {
        return Commande::select('id', 'mail as client_mail', 'nom as client_nom', 'created_at as order_date', 'livraison as delivery_date', 'montant as total_amount')->where('id', '=', $id)->first()->items()->get()->toArray();
    }
}
