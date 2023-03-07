<?php

namespace lbs\order\services;



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
}
