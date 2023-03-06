<?php

namespace lbs\order\models;

class Commande extends \Illuminate\Database\Eloquent\Model
{
    protected $table = 'commande';
    protected $primaryKey = 'id';
    public $keyType = 'string';
    public $timestamps = true;
}
