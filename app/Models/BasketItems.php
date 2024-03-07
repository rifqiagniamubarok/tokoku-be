<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BasketItems extends Model
{
    protected $primaryKey = "id";
    protected $keyType = "int";
    protected $table = "basket_items";
    public $incrementing = true;
    public $timestamp = true;

    public function basket(): BelongsTo
    {
        return $this->belongsTo(Basket::class, 'basket_id', 'id');
    }
}
