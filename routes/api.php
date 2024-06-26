<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\BillboardApiController;
use App\Http\Controllers\API\ProductApiController;
use App\Http\Controllers\API\NavigationApiController;
use App\Http\Controllers\API\CartApiController;
use App\Http\Controllers\API\FavouriteApiController;
use App\Http\Controllers\API\ClientApiController;
use App\Http\Controllers\API\OrderApiController;
use App\Http\Controllers\API\DashboardApiController;
use App\Http\Controllers\API\AuthApiController;
use App\Http\Controllers\API\CustomerApiController;

Route::get('navigation', [NavigationApiController::class, 'getCategoryWithFiltersAndTags']);

Route::get('billboards', [BillboardApiController::class, 'index']);

Route::get('products', [ProductApiController::class, 'index']);
Route::get('products/{id}', [ProductApiController::class, 'show']);

Route::post('clients', [ClientApiController::class, 'processRequest']);

Route::post('/checkout', [OrderApiController::class, 'checkout']);
Route::get('/checkout/success', [OrderApiController::class, 'success'])->name('checkout.success');
Route::get('/checkout/cancel', [OrderApiController::class, 'cancel'])->name('checkout.cancel');

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('my-orders/{userId}', [DashboardApiController::class, 'myOrders'])->name('dashboard.myorders');

Route::post('/login', [AuthApiController::class, 'login']);
Route::post('/register', [AuthApiController::class, 'register']);

Route::middleware(['auth:api'])->group(function () {
    Route::post('/logout', [AuthApiController::class, 'logout']);
    Route::post('/auth-check', [AuthApiController::class, 'authCheck']);

    Route::get('/customers', [CustomerApiController::class, 'getCustomer']);
    Route::put('/update-personal-details', [CustomerApiController::class, 'updatePersonalDetails']);
    Route::put('/update-shipping-address', [CustomerApiController::class, 'updateShippingAddress']);
    Route::put('/update-billing-address', [CustomerApiController::class, 'updateBillingAddress']);

    Route::get('favourites', [ProductApiController::class, 'favourites']);
    Route::post('favourites', [FavouriteApiController::class, 'store']);
    Route::delete('favourites', [FavouriteApiController::class, 'delete']);

    Route::get('carts', [ProductApiController::class, 'cart']);
    Route::post('carts', [CartApiController::class, 'store']);
    Route::delete('carts', [CartApiController::class, 'delete']);
});



