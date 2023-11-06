<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\BillboardApiController;
use App\Http\Controllers\API\ProductApiController;
use App\Http\Controllers\API\NavigationApiController;
use App\Http\Controllers\API\CartApiController;
use App\Http\Controllers\API\ClientApiController;

Route::get('navigation', [NavigationApiController::class, 'getCategoryWithFiltersAndTags']);

Route::get('billboards/{id}', [BillboardApiController::class, 'show']);

Route::get('products', [ProductApiController::class, 'index']);
Route::get('products/{id}', [ProductApiController::class, 'show']);
Route::get('carts/{userId}', [ProductApiController::class, 'cart']);

Route::post('clients', [ClientApiController::class, 'processRequest']);

Route::post('carts', [CartApiController::class, 'store']);
Route::delete('carts', [CartApiController::class, 'delete']);

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
