<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Basket extends Model
{
    protected $primaryKey = "id";
    protected $keyType = "int";
    protected $table = "baskets";
    public $incrementing = true;
    public $timestamp = true;

    public function user(): BelongsTo
    {
        return $this->belongsTo(Basket::class, "user_id", "id");
    }
    public function basketItems(): HasMany
    {
        return $this->hasMany(BasketItems::class, "basket_id", "id");
    }
    public function transaction(): HasOne
    {
        return $this->hasOne(Transaction::class, "basket_id", "id");
    }
}
