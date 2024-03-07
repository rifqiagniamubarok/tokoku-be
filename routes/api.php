<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
 */

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::post('/users', [\App\Http\Controllers\UserController::class, 'register']);
Route::post('/users/login', [\App\Http\Controllers\UserController::class, 'login']);

Route::middleware(\App\Http\Middleware\ApiAuthAdminMiddleware::class)->group(function () {
    Route::post('/admin/products', [\App\Http\Controllers\ProductController::class, 'create']);
    Route::get('/admin/products', [\App\Http\Controllers\ProductController::class, 'adminGet']);
    Route::get('/admin/products/{id}', [\App\Http\Controllers\ProductController::class, 'adminGetDetail']);

    Route::get('/admin/transactions', [\App\Http\Controllers\TransactionController::class, 'adminGet']);
    Route::post('/admin/transactions/confirm/{id}', [\App\Http\Controllers\TransactionController::class, 'adminConfirm']);
});

Route::middleware(\App\Http\Middleware\ApiAuthMiddleware::class)->group(function () {
    Route::get('/users/current', [\App\Http\Controllers\UserController::class, 'get']);
    Route::patch('/users/current', [\App\Http\Controllers\UserController::class, 'update']);
    Route::delete('/users/logout', [\App\Http\Controllers\UserController::class, 'logout']);

    Route::get('/products', [\App\Http\Controllers\ProductController::class, 'get']);

    Route::post('/baskets/input/{id}', [\App\Http\Controllers\BasketController::class, 'inputBasket']);
    Route::get('/baskets', [\App\Http\Controllers\BasketController::class, 'get']);

    Route::post('/transactions/checkout', [\App\Http\Controllers\TransactionController::class, 'checkout']);
    Route::get('/transactions', [\App\Http\Controllers\TransactionController::class, 'get']);
    Route::get('/transactions/{id}', [\App\Http\Controllers\TransactionController::class, 'getDetail']);
    Route::post('/transactions/pay/{id}', [\App\Http\Controllers\TransactionController::class, 'pay']);
});
