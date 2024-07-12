<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\BillboardApiController;
use App\Http\Controllers\API\ProductApiController;
use App\Http\Controllers\API\NavigationApiController;
use App\Http\Controllers\API\CartApiController;
use App\Http\Controllers\API\FavouriteApiController;
use App\Http\Controllers\API\OrderApiController;
use App\Http\Controllers\API\DashboardApiController;
use App\Http\Controllers\API\AuthApiController;
use App\Http\Controllers\API\CustomerApiController;
use App\Http\Controllers\API\RestokeSubscriptionApiController;
use App\Http\Controllers\API\RecentlyViewedApiController;

Route::get('navigation', [NavigationApiController::class, 'getCategoryWithFiltersAndTags']);

Route::get('billboards', [BillboardApiController::class, 'index']);

Route::get('products', [ProductApiController::class, 'index']);
Route::get('products/{id}', [ProductApiController::class, 'show']);

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

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

    Route::post('/checkout', [OrderApiController::class, 'checkout']);

    Route::get('/my-orders', [DashboardApiController::class, 'myOrders'])->name('dashboard.myorders');

    Route::get('restoke-subscriptions', [ProductApiController::class, 'restokeSubscriptions']);
    Route::post('restoke-subscriptions', [RestokeSubscriptionApiController::class, 'store']);
    Route::delete('restoke-subscriptions', [RestokeSubscriptionApiController::class, 'delete']);

    Route::post('/recently-viewed-items', [RecentlyViewedApiController::class, 'store']);
    Route::get('/recently-viewed-items', [RecentlyViewedApiController::class, 'index']);
});

Route::get('/checkout/success', [OrderApiController::class, 'success'])->name('checkout.success');
Route::get('/checkout/cancel', [OrderApiController::class, 'cancel'])->name('checkout.cancel');



