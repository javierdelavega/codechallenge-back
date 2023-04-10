<?php

use Illuminate\Support\Facades\Route;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use App\Http\Controllers\CartController;
use App\Http\Controllers\UserController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/



Route::get('/token', [UserController::class, 'token']);

Route::middleware('auth:sanctum')->group(function () {
    /** @group Product management */
    Route::get('/products', function () {
        return ProductResource::collection(Product::all());
    });
    /** @group Product management */
    Route::get('/product/{id}', function (string $id) {
        return new ProductResource(Product::findOrFail($id));
    });
    
    Route::get('/user', [UserController::class, 'get']);
    Route::post('/user/login', [UserController::class, 'login']);
    Route::post('/user/register', [UserController::class, 'register']);
    Route::get('/user/orders', [UserController::class, 'orders']);

    Route::get('/cart/products', [CartController::class, 'list']);
    Route::get('/cart/products/count', [CartController::class, 'count']);
    Route::get('/cart/products/total', [CartController::class, 'total']);
    Route::delete('/cart/products', [CartController::class, 'empty']);
    
    Route::post('/cart/product', [CartController::class, 'add']);
    Route::put('/cart/product/{id}', [CartController::class, 'update']);
    Route::delete('/cart/product/{id}', [CartController::class, 'remove']);

    Route::post('/cart/confirm', [CartController::class, 'confirm']);
});

