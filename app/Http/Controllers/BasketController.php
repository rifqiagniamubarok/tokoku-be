<?php

namespace App\Http\Controllers;

use App\Models\Basket;
use App\Models\BasketItems;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BasketController extends Controller
{
    public function inputBasket(Request $request, $id): JsonResponse
    {
        $qty = $request->qty;
        $product = Product::where('is_disable', false)->where('stock', '>', 0)
            ->find($id);
        if (!$product) {
            return response()->json([
                'error' => 'Product not found',
            ]);
        }

        $user = Auth::user();
        $basket = Basket::where('status', 'pending')->where('user_id', $user->id)->first();

        if (!$basket) {
            $basket = new Basket();
            $basket->total_price = 0;
            $basket->items = 0;
            $basket->user_id = $user->id;
            $basket->status = 'pending';
            $basket->save();
        }

        $basket_item = BasketItems::where("product_id", $id)->where('basket_id', $basket->id)->first();

        if (!$basket_item) {
            $basket_item = new BasketItems();
            $basket_item->basket_id = $basket->id;
            $basket_item->product_id = $id;
            $basket_item->price_per_item = $product->selling_price;
            $basket_item->total_price = $product->selling_price * $qty;
            $basket_item->qty = $qty;
            $basket_item->save();
        } else {
            $basket_item->qty = $basket_item->qty + $qty;
            $basket_item->total_price = $basket_item->total_price + ($product->selling_price * $qty);
            $basket_item->save();
        }

        $basket->items = $basket->items + $qty;
        $basket->total_price = $basket->total_price + ($product->selling_price * $qty);
        $basket->save();

        $getBaskets = Basket::with('basketItems')->where('id', $basket->id)->get();

        return response()->json([
            'data' => $getBaskets,
        ]);

    }
    public function get(Request $request): JsonResponse
    {
        $user = Auth::user();
        $basket = Basket::with('basketItems')->where('status', 'pending')->where('user_id', $user->id)->first();
        if (!$basket) {
            $basket = new Basket();
            $basket->total_price = 0;
            $basket->items = 0;
            $basket->user_id = $user->id;
            $basket->status = 'pending';
            $basket->save();
        }

        return response()->json([
            'data' => $basket,
        ]);
    }
}
