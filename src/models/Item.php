<?php

namespace lbs\order\models;

class Item extends \Illuminate\Database\Eloquent\Model
{
    protected $table = 'item';
    protected $primaryKey = 'id';
    public $timestamps = true;

    function commande() {
        return $this->hasOne(Commande::class, 'id', 'command_id');
    }
}
