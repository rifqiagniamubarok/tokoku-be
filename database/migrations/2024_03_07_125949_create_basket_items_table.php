<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('basket_items', function (Blueprint $table) {
            $table->id();
            $table->bigInteger("qty")->nullable(false);
            $table->bigInteger("price_per_item")->nullable(false);
            $table->bigInteger("total_price")->nullable(false);
            $table->unsignedBigInteger("product_id")->nullable(false);
            $table->unsignedBigInteger("basket_id")->nullable(false);
            $table->timestamps();

            $table->foreign("product_id")->on("products")->references("id");
            $table->foreign("basket_id")->on("baskets")->references("id");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('basket_items');
    }
};
