<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Transaction extends Model
{
    protected $primaryKey = "id";
    protected $keyType = "int";
    protected $table = "transactions";
    public $incrementing = true;
    public $timestamp = true;

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, "user_id", "id");
    }
    public function basket(): BelongsTo
    {
        return $this->belongsTo(Basket::class, "basket_id", "id");
    }
}
