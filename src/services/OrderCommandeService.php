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
}
