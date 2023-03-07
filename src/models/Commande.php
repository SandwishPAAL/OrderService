<?php

namespace lbs\order\models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Commande extends Model
{
    protected $table = 'commande';
    protected $primaryKey = 'id';
    public $keyType = 'string';
    public $timestamps = true;

    function items() {
        return $this->hasMany(Item::class, 'command_id', 'id');
    }
}
