<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $primaryKey = "id";
    protected $keyType = "int";
    protected $table = "products";
    public $incrementing = true;
    public $timestamp = true;

    protected $fillable = [
        'name', 'cost_price', 'selling_price', 'stock', 'is_disable',
    ];
}
