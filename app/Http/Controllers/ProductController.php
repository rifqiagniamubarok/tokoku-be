<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProductCreateRequest;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProductController extends Controller
{
    public function create(ProductCreateRequest $request): ProductResource
    {
        $data = $request->validated();
        $user = Auth::user();

        $product = new Product($data);
        $product->save();

        return new ProductResource($product);
    }
    public function adminGet(Request $request): JsonResponse
    {
        $products = Product::all();

        return response()->json([
            "data" => $products,
        ])->setStatusCode(200);
    }
    public function adminGetDetail(Request $request, $id): JsonResponse
    {

        $product = Product::find($id);

        if (!$product) {
            return response()->json([
                "error" => "Produk tidak ditemukan",
            ])->setStatusCode(404);
        }

        return response()->json([
            "data" => [
                "id" => $product->id,
                "name" => $product->name,
                "cost_price" => $product->cost_price,
                "selling_price" => $product->selling_price,
                "stock" => $product->stock,
                "is_disable" => $product->is_disable,
            ],
        ])->setStatusCode(200);
    }

    public function get(Request $request): JsonResponse
    {
        $products = Product::where('is_disable', '==', false)
            ->where('stock', '>', 0)->get();

        $filteredProducts = $products->map(function ($product) {
            return [
                'id' => $product->id,
                'name' => $product->name,
                'price' => $product->selling_price,
                'stock' => $product->stock,
            ];
        });

        return response()->json([
            "data" => $filteredProducts,
        ])->setStatusCode(200);
    }
}
